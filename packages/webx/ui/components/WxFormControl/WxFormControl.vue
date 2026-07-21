<script setup lang="ts">
import { inject, useAttrs } from 'vue';
import type { Ref } from 'vue';
import type { ValidationError } from '@/types/api';
import type { WxFormControlProps } from './types';

const props = withDefaults(defineProps<WxFormControlProps>(), {
    title: null,
});

const attrs = useAttrs();

const errors = inject<Ref<ValidationError['errors']>>('wx-form-errors', null);

const getErrors = () => {
    return errors?.value[(attrs['data-name'] as any) || null] ?? [];
};
</script>

<template>
    <div class="wx-form-control mb-16">
        <div class="wx-form-control__title fs-14px mb-4" v-if="props.title">{{ props.title }}</div>
        <div class="wx-form-control__body">
            <slot></slot>

            <div v-if="$slots['footer']" class="mt-8">
                <slot name="footer" />
            </div>
        </div>

        <div v-if="getErrors().length > 0" class="wx-form-control__errors d-flex flex-column fs-12px text-danger mt-2 gap-2 ps-12">
            <div v-for="message in getErrors()" :key="message">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-form-control {
    &__title {
        .wx-form-control:has(.invalid, .wx-form-control__errors) & {
            color: var(--wx-danger);
        }
    }
}
</style>
