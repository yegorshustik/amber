<script setup lang="ts">
import { ref } from 'vue';
import { WxCard, WxCode, WxInput } from '@/ui';
import { codeExamples } from '@/templates/Guideline/examples';

const testModel = ref('');
const testModelLocalized = ref({});
const testModelLocalizedDefault = ref({ en: 'English value' });
</script>

<template>
    <wx-card>
        <h3>Simple</h3>
        <wx-input name="test" placeholder="Placeholder" value="Test value" />

        <h3>Simple v-model</h3>
        <wx-input name="test" placeholder="Placeholder" v-model="testModel" />
        <div class="mt-4">Result: {{ testModel }}</div>

        <h3>Localized</h3>
        <wx-input name="test" placeholder="Placeholder" localized />

        <h3>Localized default value</h3>
        <wx-input name="test" placeholder="Placeholder" localized :value="testModelLocalizedDefault" />

        <h3>Localized v-model</h3>
        <wx-input name="test" placeholder="Placeholder" localized v-model="testModelLocalized" />
        <div class="mt-4">Result: {{ testModelLocalized }}</div>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-inputs-text'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
