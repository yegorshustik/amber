<script setup lang="ts">
import { ref } from 'vue';
import { $t } from '@/locales';
import type { PageTree } from '@/templates/Pages/types';
import { WxButtons, WxButton, WxPage, WxDropdownLink, WxActions, WxTree, WxAction } from '@/ui';
import { api, wxConfirm } from '@/utils';

const tree = ref();

const removeItem = (node: PageTree) => {
    wxConfirm().then(() => api.delete(`page/${node.id}`).then(() => tree.value.reloadTree()));
};
</script>

<template>
    <wx-page :heading="$t('pages.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button :route="{ name: 'pages.edit' }" theme="primary">{{ $t('create') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-tree :parent-id="1" ref="tree" state-id="pages-tree" type="data-tree" endpoint="/page" draggable>
            <template #title="{ node }: { node: PageTree; stat: any }"> {{ node.title }} (id: {{ node.id }}) </template>
            <template #actions="{ node }: { node: PageTree; stat: any }">
                <wx-actions type="adaptive" align="end">
                    <template #desktop>
                        <wx-action type="add" :route="{ name: 'pages.edit', query: { parent_id: node.id } }" />
                        <wx-action type="edit" :route="{ name: 'pages.edit', params: { id: node.id } }" />
                        <wx-action type="remove" @click="removeItem(node)" />
                    </template>
                    <template #mobile>
                        <wx-dropdown-link :route="{ name: 'pages.edit', query: { parent_id: node.id } }">{{ $t('create') }}</wx-dropdown-link>
                        <wx-dropdown-link :route="{ name: 'pages.edit', params: { id: node.id } }">{{ $t('edit') }}</wx-dropdown-link>
                        <wx-dropdown-link @click="removeItem(node)">{{ $t('remove') }}</wx-dropdown-link>
                    </template>
                </wx-actions>
            </template>
        </wx-tree>
    </wx-page>
</template>

<style scoped lang="scss"></style>
