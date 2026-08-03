<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import WxIcon from '../../components/WxIcon/WxIcon.vue';
import type { WxEntityCardProps } from './types';

const props = withDefaults(defineProps<WxEntityCardProps>(), {});
const attrs = useAttrs();

const hasClick = computed(() => !!attrs.onClick);
</script>

<template>
    <div class="wx-entity-card d-flex align-items-center gap-8" :class="{ 'cursor-pointer': hasClick }">
        <div v-if="props.image" class="">
            <div v-if="props.image === 'dummy'" class="wx-dummy-photo d-flex align-items-center justify-content-center text-secondary shadow">
                <wx-icon name="image" width="24" height="24" />
            </div>
            <img v-else :src="props.image" class="wx-entity-card__image" alt="" />
        </div>
        <div class="d-flex flex-column">
            <div class="fw-semibold fs-14px">{{ props.title }}</div>
            <div v-if="params?.filter((i) => i !== null)?.length > 0" class="d-flex row-gap-4 column-gap-12 flex-wrap">
                <div v-for="(item, index) in params?.filter((i) => i !== null)" class="fs-12px text-secondary" :key="'s' + index">
                    <template v-if="item.option">{{ item.option }}:</template> <strong v-if="item.value">{{ item.value }}</strong>
                </div>
            </div>
        </div>
        <div v-if="$slots.actions" class="ms-12">
            <slot name="actions"></slot>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-entity-card {
    &__image {
        width: 42px;
        height: 42px;
        border-radius: var(--wx-border-radius);
        object-fit: scale-down;
    }
}
</style>
