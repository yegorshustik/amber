<script setup lang="ts">
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxInput } from '@/ui';
</script>

<template>
    <wx-card>
        <h3>Default</h3>
        <wx-input type="tel" name="test" value="" />

        <h3>Localized</h3>
        <wx-input type="tel" name="test" value="" localized />
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-inputs-tel'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
