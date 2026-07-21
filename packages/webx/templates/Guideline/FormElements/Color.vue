<script setup lang="ts">
import { ref } from 'vue';
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxInput } from '@/ui';

const testColorDefault = ref('#dd00ff');
const testColorModel = ref('#ddee22');
</script>

<template>
    <wx-card>
        <h3>Default</h3>
        <wx-input type="color" name="test" value="" />

        <h3>With value</h3>
        <wx-input type="color" name="test" :value="testColorDefault" />

        <h3>With v-model</h3>
        <wx-input type="color" v-model="testColorModel" />
        <div class="mt-4">Result: {{ testColorModel }}</div>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-inputs-color'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
