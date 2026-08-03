<script setup lang="ts">

import type { WxSeoProps, WxSeo } from './types';
import { ref, watch } from 'vue';
import WxInputImage from '../../components/WxInputImage/WxInputImage.vue';
import WxFormControl from '../../components/WxFormControl/WxFormControl.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import WxTextarea from '../../components/WxTextarea/WxTextarea.vue';
import WxSingleImage from '../WxSingleImage/WxSingleImage.vue';
import { $t } from '@/locales';
import { WxLocalizedValue } from '@/types/locale';

const props = withDefaults(defineProps<WxSeoProps>(), {});
const emit = defineEmits(['update:modelValue']);

const currentValue = ref<WxSeo>(props.modelValue || props.value);

watch(() => props.modelValue, () => {
    currentValue.value = props.modelValue;
})

watch(() => props.value, () => {
    currentValue.value = props.value;
})

const handleInput = (key: keyof WxSeo, value: WxLocalizedValue|WxSingleImage) => {

    currentValue.value = {...currentValue.value, [key]: value}

    emit('update:modelValue', currentValue.value);
}

</script>

<template>

<div class="row">
    <div class="col-12 col-md-3">
        <wx-form-control :title="$t('seo.og')">
            <wx-input-image :name="`${props.name}[og]`" :value="currentValue?.og" @change="(value) => handleInput('og', value as WxSingleImage)" />
        </wx-form-control>
    </div>
    <div class="col-12 col-md">
        <wx-form-control :title="$t('seo.title')">
            <wx-input :name="`${props.name}[title]`" :value="currentValue?.title" localized @input="(value) => handleInput('title', value as WxLocalizedValue)" />
        </wx-form-control>
        <wx-form-control :title="$t('seo.h1')">
            <wx-input :name="`${props.name}[h1]`" :value="currentValue?.h1" localized  @input="(value) => handleInput('h1', value as WxLocalizedValue)" />
        </wx-form-control>
        <wx-form-control :title="$t('seo.keywords')">
            <wx-textarea :name="`${props.name}[keywords]`" :value="currentValue?.keywords" localized  @input="(value) => handleInput('keywords', value as WxLocalizedValue)" />
        </wx-form-control>
        <wx-form-control :title="$t('seo.description')">
            <wx-textarea :name="`${props.name}[description]`" :value="currentValue?.description" localized  @input="(value) => handleInput('description', value as WxLocalizedValue)" />
        </wx-form-control>
    </div>
</div>

</template>

<style scoped lang="scss">

</style>
