<script setup lang="ts">
import { WxAction, WxActions, WxCard, WxCode, WxPage, WxSortable } from '@/ui';
import { codeExamples } from './examples';
import { ref } from 'vue';

interface ListItem {
    id: number;
    title: string;
}

const items = ref<ListItem[]>([]);

for (let i = 0; i < 10; i++) {
    items.value.push({ id: i, title: `Item ${i}` });
}
</script>

<template>
    <wx-page heading="Guideline - Sortable list">

        <wx-card>
            <wx-sortable v-model="items">
                <template #content="{ item }: { item: ListItem }">
                    {{ item.title }}
                </template>
                <template #actions="{ item }">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                    </wx-actions>
                </template>
            </wx-sortable>

            <div class="mt-4">
                Result: {{ items }}
            </div>
        </wx-card>

        <wx-code lang="vue" title="Accordion">{{ codeExamples['sortable'] }}</wx-code>

    </wx-page>
</template>

<style scoped lang="scss"></style>
