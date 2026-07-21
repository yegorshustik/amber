<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxAlerts, WxDatatable, WxDatatableColumn, WxAction } from '@/ui';
import { codeExamples } from '@/templates/Guideline/examples';

interface TestDatatableItem {
    id: number;
    description: string;
    created_at: string;
    updated_at: string;
}

const log = (data) => console.log(data);

</script>

<template>
    <wx-page heading="Guideline - Datatable">
        <h2>Datatable</h2>

        <wx-datatable endpoint="test-datatable" searchable heading="Branches" selectable="checkbox" persist="test" sortable @sorted="(data) => log(data)">
            <template #selected="{ item } : { item : TestDatatableItem }">
                {{ item.description }}
            </template>

            <template #row="{ item } : { item : TestDatatableItem }">
                <wx-datatable-column sortable size="max-content" id="id" title="ID" >
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column sortable size="auto" id="description" title="Description">
                    {{ item.description }}
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-action type="sort" class="handle" />
                </wx-datatable-column>
            </template>
        </wx-datatable>

        <wx-code lang="vue">{{ codeExamples['datatable'] }}</wx-code>

    </wx-page>
</template>

<style scoped lang="scss"></style>
