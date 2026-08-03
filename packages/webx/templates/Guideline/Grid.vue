<script setup lang="ts">
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxFieldset, WxGrid, WxGridCol, WxPage, WxSeo } from '@/ui';

</script>

<template>
    <wx-page heading="Guideline - grid">
        <wx-card>

            <wx-grid class="mb-16">
                <wx-grid-col md="7">Column 1</wx-grid-col>
                <wx-grid-col md="5">Column 2</wx-grid-col>
            </wx-grid>

            <wx-grid>
                <wx-grid-col md="6">Column 1</wx-grid-col>
                <wx-grid-col md="6">Column 2</wx-grid-col>
            </wx-grid>

        </wx-card>

        <wx-code lang="vue">
            {{ codeExamples['grid'] }}
        </wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
