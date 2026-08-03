<script setup lang="ts">
import { ref } from 'vue';
import { WxButton, WxCard, WxCode, WxForm, WxFormControl, WxInput, WxPage } from '@/ui';
import { codeExamples } from '@/templates/Guideline/examples';

const response = ref({});
</script>

<template>
    <wx-page heading="Guideline - Form">
        <template #actions>
            <wx-button type="submit" form="test-form" theme="success">Submit</wx-button>
        </template>

        <wx-form action="/test-form" id="test-form" class="mb-16" @success="(data: any) => (response = data)">
            <wx-card>
                <wx-form-control title="Field name">
                    <wx-input type="text" name="name" value="" placeholder="Example" />
                </wx-form-control>
            </wx-card>
        </wx-form>
        <wx-code title="Response" lang="js" :key="JSON.stringify(response)">
            {{ response }}
        </wx-code>
        <wx-code title="Example" lang="vue">
            {{ codeExamples['form'] }}
        </wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
