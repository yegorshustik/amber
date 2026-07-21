<script setup lang="ts">
import { WxAccordion, WxAccordionItem, WxCode, WxPage } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Guideline - Accordion">
        <h2>Default accordion</h2>
        <wx-accordion>
            <wx-accordion-item title="Section 1" active> Section 1 content </wx-accordion-item>
            <wx-accordion-item title="Section 2"> Section 2 content </wx-accordion-item>
        </wx-accordion>

        <wx-code lang="vue" title="Accordion">{{ codeExamples['accordion'] }}</wx-code>

        <h2>Accordion with multiple opened panels</h2>
        <wx-accordion multiple>
            <wx-accordion-item title="Section 1" active> Section 1 content </wx-accordion-item>
            <wx-accordion-item title="Section 2"> Section 2 content </wx-accordion-item>
        </wx-accordion>
        <wx-code lang="vue" title="Multiple accordion">{{ codeExamples['accordion-multiple'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
