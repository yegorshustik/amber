<script setup lang="ts">
import { ref } from 'vue';
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxInput } from '@/ui';

const testNumberDefault = ref(2);
const testNumberModel = ref(3);
</script>

<template>
    <wx-card>
        <h3>Default</h3>
        <wx-input type="number" min="1" max="10" step="1" name="test" value="" />

        <h3>With value</h3>
        <wx-input type="number" name="test" :value="testNumberDefault" />

        <h3>With v-model</h3>
        <wx-input type="number" v-model="testNumberModel" />
        <div class="mt-4">Result: {{ testNumberModel }}</div>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-inputs-number'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
