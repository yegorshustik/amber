<script setup lang="ts">
import { WxAction, WxActions, WxButton, WxButtons, WxDatatable, WxDatatableColumn, WxEntityCard, WxIcon, WxPage } from '@/ui';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import { api, wxConfirm, wxSnackbar } from '@/utils';
import { ref } from 'vue';
import { useRouter } from 'vue-router';


const forms = ref();

const router = useRouter();
const selected = ref([]);

const remove = async (item) => {
    try {
        await api.delete(`inbox/form/${item.id}`).then(() => {
            wxSnackbar($t('inbox.forms.deleted'));

            setTimeout(() => forms.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveSorting = (items) => {
    const ids = items.map((item) => item.id);

    api.post(`inbox/form/sorting`, { ids: ids });
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item) => item.id);

        api.delete(`inbox/form/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('inbox.forms.deleted'));

            setTimeout(() => forms.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('inbox.forms.heading')" :back="{ name: 'inbox' }">
        <template #actions>
            <wx-buttons>
                <wx-button theme="primary" :route="{ name : 'inbox.forms.edit' }">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="forms"
            endpoint="inbox/form"
            searchable
            sortable
            selectable="checkbox"
            persist="inbox-forms"
            @selected="(items) => (selected = items)"
            @sorted="(items) => saveSorting(items)"
        >
            <template #selected="{ item }: { item: Record<any, any> }">
                {{ useLocalesStore().selectLocalizedValue(item.title) }}
            </template>

            <template #row="{ item }: { item: Record<any, any> }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="useLocalesStore().selectLocalizedValue(item.title)"
                        @click="() => router.push({ name: 'inbox.forms.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="send" :route="{ name: 'inbox.forms.application', params: { id: item.id } }" />
                        <wx-action type="edit" :route="{ name: 'inbox.forms.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
