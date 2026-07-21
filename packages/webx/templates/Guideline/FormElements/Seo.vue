<script setup lang="ts">
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxFieldset, WxSeo } from '@/ui';
import { ref } from 'vue';

const testSeo = ref<WxSeo>({
    og : {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
    title: { en: '12312312', uk: 'qweqweqwe' },
    description: { en: '12312312', uk: 'qweqweqwe' },
    keywords: { en: '12312312', uk: 'qweqweqwe' },
});

const testSeoModel = ref<WxSeo>({
    og : {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
    title: { en: '12312312', uk: 'qweqweqwe' },
    description: { en: '12312312', uk: 'qweqweqwe' },
    keywords: { en: '12312312', uk: 'qweqweqwe' },
});

</script>

<template>
    <wx-card>
        <wx-fieldset legend="Default">
            <wx-seo name="seo" />
        </wx-fieldset>

        <wx-fieldset legend="With value">
            <wx-seo name="seo" :value="testSeo" />
        </wx-fieldset>

        <wx-fieldset legend="With v-model">
            <wx-seo name="seo" v-model="testSeoModel" />

            <div class="mt-4">Result: {{ testSeoModel }}</div>
        </wx-fieldset>

    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['seo'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
