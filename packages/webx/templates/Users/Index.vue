<script setup lang="ts">
import { ref } from 'vue';
import { $t } from '@/locales';
import { useUserStore } from '@/stores';
import { wxUserDialog } from '@/templates/Users/userDialog';
import type { User } from '@/types/user';
import { WxAction, WxActions, WxButton, WxButtons, WxDatatable, WxDatatableColumn, WxEntityCard, WxPage } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const users = ref();

const userCreated = () => {
    users.value.reload();
};

const userUpdated = (response: User) => {
    if (response.id === useUserStore().user.id) {
        useUserStore().user = response;
    }

    users.value.reload();
};

const userRemove = async (user: User) => {
    try {
        await api.delete(`user/${user.id}`).then(() => {
            wxSnackbar($t('user.deleted'));
            setTimeout(() => users.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};
</script>

<template>
    <wx-page :heading="$t('users')">
        <template #actions>
            <wx-buttons>
                <wx-button @click="() => wxUserDialog().then(() => userCreated())" theme="primary">{{ $t('create') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-datatable ref="users" endpoint="user" searchable persist="users">
            <template #selected="{ item }: { item: User }">
                {{ item.name }}
            </template>

            <template #row="{ item }: { item: User }">
                <wx-datatable-column sortable size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column sortable size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="item.name"
                        :image="item.image?.src.url"
                        @click="() => wxUserDialog(item).then((response) => userUpdated(response))"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="edit" @click="() => wxUserDialog(item).then((response) => userUpdated(response))" />
                        <wx-action
                            :hidden="useUserStore().profile().id === item.id"
                            type="remove"
                            @click="() => wxConfirm().then(() => userRemove(item))"
                        />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
