<script setup lang="ts">
import { ref } from 'vue';
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxFieldset, WxTags } from '@/ui';
import type { WxTag } from '@/ui/components/WxTags';

const testTagsDefault = ref<WxTag[]>([
    {
        id: 2,
        slug: 'tag-2',
        title: { en: 'Tag 2', uk: 'Тег 2' },
    },
]);

const testTagsModel = ref<WxTag[]>([
    {
        id: 2,
        slug: 'tag-2',
        title: { en: 'Tag 2', uk: 'Тег 2' },
    },
]);
</script>

<template>
    <wx-card>
        <wx-fieldset legend="Default">
            <wx-tags endpoint="test-tags" placeholder="Tags" />
        </wx-fieldset>

        <wx-fieldset legend="With value">
            <wx-tags endpoint="test-tags" name="tags" :value="testTagsDefault" />
        </wx-fieldset>

        <wx-fieldset legend="With v-model">
            <wx-tags endpoint="test-tags" v-model="testTagsModel" />
            <div class="mt-4">Result: {{ testTagsModel }}</div>
        </wx-fieldset>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['tags'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
