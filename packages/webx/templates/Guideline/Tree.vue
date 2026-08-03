<script setup lang="ts">
import { WxAction, WxActions, WxCard, WxCode, WxDropdownLink, WxPage, WxTree } from '@/ui';
import { codeExamples } from './examples';
import { ref } from 'vue';

const treeExample = ref([
    /*{ "id": 3, "parent_id": 2, "title": "Уровень 2: Проекты" },
    { "id": 4, "parent_id": 3, "title": "Уровень 3: WebX" },
    { "id": 5, "parent_id": 4, "title": "Уровень 4: Спецификации" },*/
    { id: 6, parent_id: 4, title: 'Уровень 4: Дизайн-система' },
]);

const treeExample2 = ref({ id: 6, parent_id: 4, title: 'Уровень 4: Дизайн-система' });
</script>

<template>
    <wx-page heading="Guideline - Tree">
        <h2>Tree example</h2>
        <wx-card class="mb-16">
            <wx-tree state-id="tree-example" endpoint="/filemanager/directories"></wx-tree>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['tree-simple'] }}</wx-code>

        <h2>Tree example with checkboxes</h2>
        <wx-card class="mb-16">
            <wx-tree state-id="tree-example-2" v-model="treeExample" endpoint="/filemanager/directories" draggable checkable></wx-tree>
            <div class="mt-4">Result: {{ treeExample }}</div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['tree-checkable'] }}</wx-code>

        <h2>Selectable tree example</h2>
        <wx-card>
            <wx-tree state-id="tree-example-3" v-model="treeExample2" endpoint="/filemanager/directories" draggable selectable></wx-tree>
            <div class="mt-4">Result: {{ treeExample2 }}</div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['tree-selectable'] }}</wx-code>

        <h2>Data tree example</h2>

        <wx-tree state-id="tree-example-4" type="data-tree" v-model="treeExample2" endpoint="/filemanager/directories" draggable>
            <template #title="{ node, stat }">
                {{ node.title }} (id: {{ node.id }})
            </template>
            <template #actions="{ node, stat }">
                <wx-actions type="adaptive" align="end">
                    <template #desktop>
                        <wx-action type="add" />
                        <wx-action type="edit" />
                        <wx-action type="remove" />
                    </template>
                    <template #mobile>
                        <wx-dropdown-link>Create</wx-dropdown-link>
                        <wx-dropdown-link>Edit</wx-dropdown-link>
                        <wx-dropdown-link>Remove</wx-dropdown-link>
                    </template>
                </wx-actions>
            </template>
        </wx-tree>

        <wx-code lang="vue">{{ codeExamples['tree-data-tree'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
