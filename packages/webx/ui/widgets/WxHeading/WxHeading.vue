<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { WxLocalizedValue } from '@/types/locale';
import WxFormControl from '../../components/WxFormControl/WxFormControl.vue';
import WxGrid from '../../components/WxGrid/WxGrid.vue';
import WxGridCol from '../../components/WxGrid/WxGridCol.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import WxSelect from '../../components/WxSelect/WxSelect.vue';
import type { WxHeadingProps, WxHeadingType } from './types';
import type { WxHeadingContent } from './types';

const props = withDefaults(defineProps<WxHeadingProps>(), {
    preview: false,
});

const emit = defineEmits(['update:modelValue']);

const currentValue = ref<WxHeadingContent>(props.modelValue || props.value);

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

const options = ref([
    { value: 'none', label: $t('default') },
    { value: 'h1', label: $t('heading-h1') },
    { value: 'h2', label: $t('heading-h2') },
    { value: 'h3', label: $t('heading-h3') },
    { value: 'h4', label: $t('heading-h4') },
    { value: 'h5', label: $t('heading-h5') },
    { value: 'h6', label: $t('heading-h6') },
]);

const handleInput = (key: keyof WxHeadingContent, value: WxLocalizedValue | WxHeadingType) => {
    currentValue.value = { ...currentValue.value, [key]: value };

    emit('update:modelValue', currentValue.value);
};

const renderHeading = () => {
    const level = currentValue.value?.level || 'h2';
    const style = currentValue.value?.style || 'none';

    const text = useLocalesStore().selectLocalizedValue(currentValue.value?.text as WxLocalizedValue, 'Heading example');

    return `<${level === 'none' ? 'div' : level} class="${props.class || ''} ${style !== 'none' ? style : null}">${text}</${level === 'none' ? 'div' : level}>`;
};
</script>

<template>
    <div v-if="props.preview" v-html="renderHeading()" />

    <wx-grid v-else>
        <wx-grid-col :md="4" :lg="6">
            <wx-form-control :title="$t('heading')">
                <wx-input
                    :name="`${props.name}[text]}`"
                    :value="currentValue?.text"
                    localized
                    @input="(value) => handleInput('text', value as WxLocalizedValue)"
                />
            </wx-form-control>
        </wx-grid-col>
        <wx-grid-col :md="4" :lg="3">
            <wx-form-control :title="$t('heading-level')">
                <wx-select
                    :name="`${props.name}[level]}`"
                    :options="options"
                    :value="currentValue?.level || 'h2'"
                    @change="(value) => handleInput('level', value as WxHeadingType)"
                />
            </wx-form-control>
        </wx-grid-col>
        <wx-grid-col :md="4" :lg="3">
            <wx-form-control :title="$t('heading-style')">
                <wx-select
                    :name="`${props.name}[style]}`"
                    :options="options"
                    :value="currentValue?.style"
                    @change="(value) => handleInput('style', value as WxHeadingType)"
                />
            </wx-form-control>
        </wx-grid-col>
    </wx-grid>
</template>

<style scoped lang="scss"></style>
