<script setup lang="ts">
import { WxCode, WxPage, WxCard } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Guideline - Cards">
        <h2>Minimal card</h2>
        <wx-card class="mb-16"> ...Card content </wx-card>
        <wx-code title="Code" lang="vue">{{ codeExamples['card-minimal'] }}</wx-code>

        <h2>Card with title</h2>
        <wx-card title="Card title" class="mb-16"> ...Card content </wx-card>
        <wx-code title="Code" lang="vue">{{ codeExamples['card-with-title'] }}</wx-code>

        <h2>Card with title and actions</h2>
        <wx-card title="Card title" class="mb-16">
            <template #actions> ...Actions </template>

            ...Card content
        </wx-card>
        <wx-code title="Code" lang="vue">{{ codeExamples['card-with-title-actions'] }}</wx-code>

        <h2>Card with footer</h2>
        <wx-card class="mb-16">
            <template #footer> ...Footer </template>

            ...Card content
        </wx-card>
        <wx-code title="Code" lang="vue">{{ codeExamples['card-with-footer'] }}</wx-code>

        <h2>Card with sidebar</h2>
        <wx-card title="Card title" class="mb-16">
            <template #sidebar>
                ...Sidebar
                <p v-for="i in 5">&nbsp;</p>
            </template>

            ...Card content
        </wx-card>
        <wx-code title="Code" lang="vue">{{ codeExamples['card-with-sidebar'] }}</wx-code>

        <h2>Card with all features</h2>
        <wx-card title="Card title" class="mb-16">
            <template #actions> ...Actions </template>
            <template #sidebar>
                ...Sidebar
                <p v-for="i in 5">&nbsp;</p>
            </template>
            <template #footer> ...Footer </template>

            ...Card content
        </wx-card>
        <wx-code title="Code" lang="vue">{{ codeExamples['card-with-all'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
