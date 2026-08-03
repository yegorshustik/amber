<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { Service } from '@/templates/Services/types';
import type { ApiResponse } from '@/types/api';
import { WxButtons, WxButton, WxPage, WxActions, WxDatatable, WxEntityCard, WxDatatableColumn, WxAction, WxIcon } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const services = ref();

const router = useRouter();
const selected = ref([]);

const remove = async (item) => {
    try {
        await api.delete<ApiResponse<Service[]>>(`services/${item.id}`).then(() => {
            wxSnackbar($t('services.deleted'));
            setTimeout(() => services.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveSorting = (items: Service[]) => {
    const ids = items.map((item) => item.id);

    api.post(`services/sorting`, { ids: ids });
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item: Service) => item.id);

        api.delete(`services/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('services.deleted'));

            setTimeout(() => services.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('services.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button :route="{ name: 'services.edit' }" theme="primary">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="services"
            endpoint="services"
            searchable
            sortable
            selectable="checkbox"
            persist="services"
            @selected="(items) => (selected = items)"
            @sorted="(items) => saveSorting(items)"
        >
            <template #selected="{ item }: { item: Service }">
                {{ useLocalesStore().selectLocalizedValue(item.title) }}
            </template>

            <template #row="{ item }: { item: Service }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="useLocalesStore().selectLocalizedValue(item.title)"
                        :params="[
                            {
                                value: [item.is_published ? $t('is-published') : null].filter((p) => p !== null).join(', '),
                            },
                        ]"
                        @click="() => router.push({ name: 'services.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="edit" :route="{ name: 'services.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
