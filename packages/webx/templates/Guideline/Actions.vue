<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxAction, WxActions, WxButtons, WxButton, WxDropdownLink } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Guideline - Actions">

        <h2>Actions list</h2>
        <p>Can be used in data table or some lists</p>
        <wx-card>

            <wx-actions>
                <wx-action type="add" />
                <wx-action type="edit" />
                <wx-action type="remove" />
                <wx-action type="sort" />
                <wx-action type="link" />
                <wx-action type="details" />
            </wx-actions>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['actions-list'] }}</wx-code>

        <h2>Adaptive actions</h2>
        <p>Try resize window to see adaptive actions</p>

        <wx-card>
            <div class="mb-16">
                <wx-actions type="adaptive">
                    <template #desktop>
                        <wx-buttons>
                            <wx-button theme="primary">Create</wx-button>
                            <wx-button theme="outline-primary">Edit</wx-button>
                            <wx-button theme="outline-success">Update some information</wx-button>
                            <wx-button theme="danger">Remove</wx-button>
                        </wx-buttons>
                    </template>
                    <template #mobile>
                        <wx-dropdown-link>Create</wx-dropdown-link>
                        <wx-dropdown-link>Edit</wx-dropdown-link>
                        <wx-dropdown-link>Update some information</wx-dropdown-link>
                        <wx-dropdown-link>Remove</wx-dropdown-link>
                    </template>
                </wx-actions>
            </div>

            <wx-actions type="adaptive">
                <template #desktop>
                    <wx-action type="add" />
                    <wx-action type="edit" />
                    <wx-action type="remove" />
                    <wx-action type="link" />
                    <wx-action type="sort" />
                    <wx-action type="link" />
                    <wx-action type="details" />
                    <wx-action type="link" />
                    <wx-action type="details" />
                </template>
                <template #mobile>
                    <wx-dropdown-link>Create</wx-dropdown-link>
                    <wx-dropdown-link>Edit</wx-dropdown-link>
                    <wx-dropdown-link>Update some information</wx-dropdown-link>
                    <wx-dropdown-link>Remove</wx-dropdown-link>
                </template>
            </wx-actions>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['actions-adaptive'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss">
</style>
