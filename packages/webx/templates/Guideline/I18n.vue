<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxLocales } from '@/ui';
import { codeExamples } from './examples';

const updateLocale = (locale) => {
    console.log(locale);
}
</script>

<template>
    <wx-page heading="Guideline - Localization">
        <h2>Locale switcher</h2>
        <wx-card>
            <wx-locales class="rounded border" @change="(locale) => updateLocale(locale)">
                <template #item="{ locale }">
                    {{ locale.name }}
                </template>
            </wx-locales>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['localization-switcher'] }}</wx-code>

        <h2>Locale tabs switcher</h2>
        <wx-card>
            <wx-locales type="tabs">
                <template #item="{ locale }">
                    {{ locale.name }}
                </template>
            </wx-locales>
        </wx-card>

        <h2>Locale vertical switcher</h2>
        <wx-card>
            <wx-locales type="vertical">
                <template #item="{ locale }">
                    {{ locale.name }}
                </template>
            </wx-locales>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['localization-tabs'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
