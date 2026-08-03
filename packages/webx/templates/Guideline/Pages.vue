<script setup lang="ts">
import { WxCode, WxPage } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Guideline - Layouts">

        <h2>Using layout in routes</h2>
        <wx-code title="Code example" lang="js">
            {{ codeExamples['layout-routes'] }}
        </wx-code>

        <h2>General page</h2>

        <h3>Default page</h3>
        <wx-code title="Code example" lang="vue">
            {{ codeExamples['layout-page'] }}
        </wx-code>

        <h3>Full width page</h3>
        <wx-code title="Code example" lang="vue">
            {{ codeExamples['layout-page-full'] }}
        </wx-code>

        <h3>Page with actions</h3>
        <wx-code title="Code example" lang="vue">
            {{ codeExamples['layout-page-actions'] }}
        </wx-code>

        <h3>Page with sidebar</h3>
        <wx-code title="Code example" lang="vue">
            {{ codeExamples['layout-page-sidebar'] }}
        </wx-code>

        <h3>Page with all features</h3>
        <wx-code title="Code example" lang="vue">
            {{ codeExamples['layout-page-full-example'] }}
        </wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
