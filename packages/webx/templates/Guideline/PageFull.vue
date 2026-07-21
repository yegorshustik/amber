<script setup lang="ts">
import { WxCard, WxCode, WxPage } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Page full example">

        <template #actions>...Actions</template>

        <template #sidebar>
            <wx-card title="Card heading" class="mb-16">
                ...Card content
            </wx-card>
            <wx-card title="Another card heading" class="mb-16">
                ...Card content
            </wx-card>
        </template>

        <wx-code title="Code example" lang="vue">
            {{ codeExamples['layout-page-full-example-2'] }}
        </wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
