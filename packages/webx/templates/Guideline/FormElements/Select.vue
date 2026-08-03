<script setup lang="ts">
import { ref } from 'vue';
import { WxCard, WxCode, WxPage, WxSelect, WxSelectTree } from '@/ui';
import { codeExamples } from '@/templates/Guideline/examples';

const testSelectOptions = [
    { value: 1, label: 'Электроника' },
    { value: 2, label: 'Одежда' },
    { value: 3, label: 'Дом и сад' },
    { value: 4, label: 'Автотовары' },
];
const testSelectDefault = ref(2);
const testSelectMultipleDefault = ref([2, 3]);
const testSelectModel = ref(3);
const testSelectMultipleModel = ref([]);

const testSelectTreeModel = ref();
const testSelectTreeMultipleModel = ref([]);

const testSelectTreeModelDefault = ref(4);
const testSelectTreeMultipleModelDefault = ref([6,4]);
const testSelectTreeDefault = ref(4);
const testSelectTreeMultipleDefault = ref([6,4]);
</script>

<template>
    <wx-card>
        <h3>Default</h3>
        <wx-select name="test-select" :options="testSelectOptions" />

        <h3>Selected</h3>
        <wx-select :options="testSelectOptions" :value="testSelectDefault" />

        <h3>With v-model</h3>
        <wx-select :options="testSelectOptions" v-model="testSelectModel" />
        <div class="mt-4">Result: {{ testSelectModel }}</div>

        <h3>Multiple</h3>
        <wx-select multiple :options="testSelectOptions" />

        <h3>Multiple with default</h3>
        <wx-select multiple :options="testSelectOptions" :value="testSelectMultipleDefault" />

        <h3>Multiple with v-model</h3>
        <wx-select multiple :options="testSelectOptions" v-model="testSelectMultipleModel" />
        <div class="mt-4">Result: {{ testSelectMultipleModel }}</div>

        <h3>Searchable</h3>
        <wx-select searchable :options="testSelectOptions" />
    </wx-card>

    <wx-code lang="ts">
        {{ codeExamples['form-select-ts'] }}
    </wx-code>
    <wx-code lang="vue">
        {{ codeExamples['form-select-vue'] }}
    </wx-code>



    <wx-card>
        <h3>Select tree single</h3>
        <wx-select-tree state-id="select-tree" endpoint="filemanager/directories"/>

        <h3>Select tree multiple</h3>
        <wx-select-tree state-id="select-tree-2" multiple endpoint="filemanager/directories"/>

        <h3>Select tree single v-model</h3>
        <wx-select-tree state-id="select-tree" v-model="testSelectTreeModel" endpoint="filemanager/directories"/>
        <div class="mt-4">Result: {{ testSelectTreeModel }}</div>

        <h3>Select tree multiple v-model</h3>
        <wx-select-tree state-id="select-tree-2" v-model="testSelectTreeMultipleModel" multiple endpoint="filemanager/directories"/>
        <div class="mt-4">Result: {{ testSelectTreeMultipleModel }}</div>

        <h3>Select tree single v-model with default</h3>
        <wx-select-tree state-id="select-tree-3" v-model="testSelectTreeModelDefault" endpoint="filemanager/directories"/>
        <div class="mt-4">Result: {{ testSelectTreeModelDefault }}</div>

        <h3>Select tree multiple v-model with default</h3>
        <wx-select-tree state-id="select-tree-4" v-model="testSelectTreeMultipleModelDefault" multiple endpoint="filemanager/directories"/>
        <div class="mt-4">Result: {{ testSelectTreeMultipleModelDefault }}</div>

        <h3>Select tree single with default</h3>
        <wx-select-tree state-id="select-tree-5" name="test_select_tree" :value="testSelectTreeDefault" endpoint="filemanager/directories"/>

        <h3>Select tree multiple with default</h3>
        <wx-select-tree state-id="select-tree-6" name="test_select_tree[]" :value="testSelectTreeMultipleDefault" multiple endpoint="filemanager/directories"/>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-select-tree'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
