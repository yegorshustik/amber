<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxAlerts } from '@/ui';
import { codeExamples } from './examples';
import WxAlert from '@/ui/components/WxAlert/WxAlert.vue';

</script>

<template>
    <wx-page heading="Guideline - Alerts">
        <h2>Inline alert messages</h2>
        <wx-card>
            <wx-alert type="info" class="mb-16">
                Info alert message
            </wx-alert>
            <wx-alert type="success" class="mb-16">
                Success alert message
            </wx-alert>
            <wx-alert type="warning" class="mb-16">
                Warning alert message
            </wx-alert>
            <wx-alert type="danger">
                Danger alert message
            </wx-alert>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['alerts-inline'] }}</wx-code>

    </wx-page>
</template>

<style scoped lang="scss"></style>
