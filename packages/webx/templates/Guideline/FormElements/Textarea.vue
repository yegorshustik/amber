<script setup lang="ts">
import { ref } from 'vue';
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxTab, WxTabs, WxTextarea } from '@/ui';

const testTextareaDefault = ref('Textarea default value');
const testTextareaModel = ref('');
const testTextareaLocalizedDefault = ref({ en: 'English value' });
const testTextareaLocalizedModel = ref({});

const testWysiwygDefault = ref('Textarea default value');
const testWysiwygModel = ref('');
const testWysiwygLocalizedDefault = ref({ en: 'English value' });
const testWysiwygLocalizedModel = ref({});
</script>

<template>
    <wx-tabs>
        <wx-tab name="Default">
            <wx-card>
                <h3>Default</h3>
                <wx-textarea name="test" placeholder="Placeholder" value="" />

                <h3>With value</h3>
                <wx-textarea name="test" placeholder="Placeholder" :value="testTextareaDefault" />

                <h3>With v-model</h3>
                <wx-textarea placeholder="Placeholder" v-model="testTextareaModel" />
                <div class="mt-4">Result: {{ testTextareaModel }}</div>
            </wx-card>
        </wx-tab>

        <wx-tab name="Localized">
            <wx-card>
                <h3>Default localized</h3>
                <wx-textarea name="test" localized placeholder="Placeholder" value="" />

                <h3>Localized with value</h3>
                <wx-textarea name="test" localized placeholder="Placeholder" :value="testTextareaLocalizedDefault" />

                <h3>Localized with v-model</h3>
                <wx-textarea localized placeholder="Placeholder" v-model="testTextareaLocalizedModel" />
                <div class="mt-4">Result: {{ testTextareaLocalizedModel }}</div>
            </wx-card>
        </wx-tab>

        <wx-tab name="Wysiwyg">
            <wx-card>
                <h3>Default</h3>
                <wx-textarea wysiwyg name="test" placeholder="Placeholder" value="" />

                <h3>With value</h3>
                <wx-textarea wysiwyg name="test" placeholder="Placeholder" :value="testWysiwygDefault" />

                <h3>With v-model</h3>
                <wx-textarea wysiwyg name="test" placeholder="Placeholder" v-model="testWysiwygModel" />
                <div class="mt-4">Result: {{ testWysiwygModel }}</div>
            </wx-card>
        </wx-tab>

        <wx-tab name="Wysiwyg Localized">
            <wx-card>
                <h3>Default</h3>
                <wx-textarea wysiwyg localized name="test" placeholder="Placeholder" value="" />

                <h3>With value</h3>
                <wx-textarea wysiwyg localized name="test" placeholder="Placeholder" :value="testWysiwygLocalizedDefault" />

                <h3>With v-model</h3>
                <wx-textarea wysiwyg localized name="test" placeholder="Placeholder" v-model="testWysiwygLocalizedModel" />
                <div class="mt-4">Result: {{ testWysiwygLocalizedModel }}</div>
            </wx-card>
        </wx-tab>

        <wx-tab name="Wysiwyg full">
            <wx-card>
                <wx-textarea preset="maximal" wysiwyg name="test" placeholder="Placeholder" value="" />
            </wx-card>
        </wx-tab>
    </wx-tabs>

    <wx-code lang="vue">
        {{ codeExamples['form-inputs-textarea'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
