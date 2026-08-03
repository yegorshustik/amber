<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { Store } from '@/templates/Stores/types';
import { WxButtons, WxButton, WxPage, WxActions, WxDatatable, WxEntityCard, WxDatatableColumn, WxAction, WxIcon } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const stores = ref();

const router = useRouter();
const selected = ref([]);

const remove = async (item) => {
    try {
        await api.delete(`stores/store/${item.id}`).then(() => {
            wxSnackbar($t('stores.deleted'));

            setTimeout(() => stores.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveSorting = (items) => {
    const ids = items.map((item) => item.id);

    api.post(`stores/store/sorting`, { ids: ids });
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item) => item.id);

        api.delete(`stores/store/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('stores.deleted'));

            setTimeout(() => stores.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('stores.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button :route="{ name: 'stores.edit' }" theme="primary">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="stores"
            endpoint="stores/store"
            searchable
            sortable
            selectable="checkbox"
            persist="stores"
            @selected="(items) => (selected = items)"
            @sorted="(items) => saveSorting(items)"
        >
            <template #selected="{ item }: { item: Store }">
                {{ useLocalesStore().selectLocalizedValue(item.title) }}
            </template>

            <template #row="{ item }: { item: Store }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="useLocalesStore().selectLocalizedValue(item.title)"
                        @click="() => router.push({ name: 'stores.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="edit" :route="{ name: 'stores.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
