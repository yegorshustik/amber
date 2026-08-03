<script setup lang="ts">
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxFieldset, WxHeading } from '@/ui';
import { ref } from 'vue';
import { WxHeadingContent } from '@/ui/widgets/WxHeading';

const headingDefault = ref<WxHeadingContent>({ "level": "h2", "text": { "en": "1111222" }, "style": "h5" })
const headingModel = ref<WxHeadingContent>({})

</script>

<template>
    <wx-card>
        <wx-fieldset legend="Default">
            <wx-heading name="heading" />
        </wx-fieldset>
        <wx-fieldset legend="Value">
            <wx-heading name="heading" :value="headingDefault" />
        </wx-fieldset>
        <wx-fieldset legend="v-model">
            <wx-heading name="heading" v-model="headingModel" />
            <div class="mt-4">Result: {{ headingModel }}</div>
        </wx-fieldset>

        <wx-fieldset legend="Preview">
            <wx-heading name="heading" preview v-model="headingModel" />
        </wx-fieldset>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['heading'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
