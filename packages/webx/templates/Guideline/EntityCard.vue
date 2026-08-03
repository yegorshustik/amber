<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxEntityCard, WxFieldset, WxActions, WxAction } from '@/ui';
import { codeExamples } from './examples';

</script>

<template>
    <wx-page heading="Guideline - Entity card">
        <wx-card>
            <wx-fieldset legend="Default">
                <wx-entity-card
                    title="Entity heading"
                    image="https://picsum.photos/48"
                    :params="[{ option : 'Option', value : 'Value' }, { option : 'Option 2', value : 'Value 2' }]" />
            </wx-fieldset>

            <wx-fieldset legend="With actions">
                <wx-entity-card
                    title="Entity heading"
                    image="https://picsum.photos/48"
                    :params="[{ option : 'Option', value : 'Value' }, { option : 'Option 2', value : 'Value 2' }]">
                    <template #actions>
                        <wx-actions>
                            <wx-action type="edit" />
                            <wx-action type="remove" />
                        </wx-actions>
                    </template>
                </wx-entity-card>
            </wx-fieldset>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['entity-card'] }}</wx-code>

    </wx-page>
</template>

<style scoped lang="scss"></style>
