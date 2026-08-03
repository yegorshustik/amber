<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxAlerts, WxFieldset } from '@/ui';
import { codeExamples } from './examples';
import WxAlert from '@/ui/components/WxAlert/WxAlert.vue';

</script>

<template>
    <wx-page heading="Guideline - Alerts">
        <h2>Inline alert messages</h2>
        <wx-card>
            <wx-fieldset legend="Legend" class="mb-16">
                <p>1</p>
                <p>2</p>
                <p>3</p>
            </wx-fieldset>

            <wx-fieldset>
                <p>1</p>
                <p>2</p>
                <p>3</p>
            </wx-fieldset>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['fieldset'] }}</wx-code>

    </wx-page>
</template>

<style scoped lang="scss"></style>
