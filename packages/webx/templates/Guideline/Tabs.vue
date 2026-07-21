<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxTabs, WxTab } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Guideline - Tabs">
        <h2>Tabs</h2>

        <wx-tabs class="mb-16" type="horizontal">
            <wx-tab name="General">
                <wx-card>...General</wx-card>
            </wx-tab>
            <wx-tab name="Secondary">
                <wx-card>...Secondary</wx-card>
            </wx-tab>
            <wx-tab name="Secondary some">
                <wx-card>...Secondary some</wx-card>
            </wx-tab>
            <wx-tab name="Secondary another">
                <wx-card>...Secondary another</wx-card>
            </wx-tab>
        </wx-tabs>

        <h2>Vertical Tabs</h2>
        <wx-tabs type="vertical">
            <wx-tab name="General">
                <wx-card>
                    <p v-for="i in 10">...General {{ i }}</p>
                </wx-card>
            </wx-tab>
            <wx-tab name="Secondary">2</wx-tab>
            <wx-tab name="Secondary some">3</wx-tab>
            <wx-tab name="Secondary another">4</wx-tab>
        </wx-tabs>

        <wx-code lang="vue" title="Horizontal tabs">{{ codeExamples['tabs'] }}</wx-code>
        <wx-code lang="vue" title="Vertical tabs">{{ codeExamples['tabs-vertical'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
