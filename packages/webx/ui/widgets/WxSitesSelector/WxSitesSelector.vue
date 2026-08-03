<script setup lang="ts">
import { inject, ref, type Ref, watch } from 'vue';
import type { ValidationError } from '@/types/api';
import { useSitesStore } from '../../../stores/sites';
import type { Site } from '../../../templates/Sites/types';
import WxCheck from '../../components/WxCheck/WxCheck.vue';
import WxCheckGroup from '../../components/WxCheckGroup/WxCheckGroup.vue';
import type { WxSitesSelectorProps } from './types';

const props = withDefaults(defineProps<WxSitesSelectorProps>(), {});
const errors = inject<Ref<ValidationError['errors']>>('wx-form-errors');

const currentValue = ref<Site[]>(props.modelValue || props.value);
//const emit = defineEmits(['select']);

watch(
    () => props.modelValue,
    () => {
        currentValue.value = props.modelValue || props.value;
    },
);
watch(
    () => props.value,
    () => {
        currentValue.value = props.modelValue || props.value;
    },
);

const getErrors = () => {
    return errors?.value[props.name] ?? [];
};
</script>

<template>
    <div class="ws-sites-selector">
        <wx-check-group>
            <wx-check v-for="site in useSitesStore().sites" :key="`site_${site.id}`" :name="props.name + '[]'" :checked="currentValue?.filter(item => item.id === site.id).length > 0" :value="site.id" :label="site.title" />
        </wx-check-group>

        <div v-if="getErrors().length > 0" class="d-flex flex-column fs-12px text-danger mt-2 gap-2 ps-12">
            <div v-for="message in getErrors()" :key="message">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
