<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { Site } from '@/templates/Sites/types';
import type { ApiResponse } from '@/types/api';
import { WxButtons, WxButton, WxPage, WxActions, WxDatatable, WxEntityCard, WxDatatableColumn, WxAction, WxIcon } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';
import { useSitesStore } from '../../stores/sites';

const sites = ref();

const router = useRouter();
const selected = ref([]);

const remove = async (item) => {
    try {
        await api.delete<ApiResponse<Site[]>>(`sites/${item.id}`).then((response: ApiResponse<Site[]>) => {
            wxSnackbar($t('sites.deleted'));
            useSitesStore().reload(response.data);
            setTimeout(() => sites.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveSorting = (items: Site[]) => {
    const ids = items.map((item) => item.id);

    api.post(`sites/sorting`, { ids: ids }).then((response: ApiResponse<Site[]>) => useSitesStore().reload(response.data));
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item: Site) => item.id);

        api.delete(`sites/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('sites.deleted'));

            setTimeout(() => sites.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('sites.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button :route="{ name: 'sites.edit' }" theme="primary">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="sites"
            endpoint="sites"
            searchable
            sortable
            selectable="checkbox"
            persist="sites"
            @selected="(items) => (selected = items)"
            @sorted="(items) => saveSorting(items)"
        >
            <template #selected="{ item }: { item: Site }">
                {{ useLocalesStore().selectLocalizedValue(item.title) }}
            </template>

            <template #row="{ item }: { item: Site }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="item.title"
                        :params="[
                            {
                                value: [item.is_published ? $t('is-published') : null].filter((p) => p !== null).join(', '),
                            },
                        ]"
                        @click="() => router.push({ name: 'sites.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="edit" :route="{ name: 'sites.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
