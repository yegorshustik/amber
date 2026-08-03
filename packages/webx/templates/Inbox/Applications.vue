<script setup lang="ts">
import { inject, onBeforeMount, provide, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ApiResponse } from '@/types/api';
import { WxAction, WxActions, WxButton, WxButtons, WxDatatable, WxDatatableColumn, WxDialog, WxFormControl } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const route = useRoute();

const form = ref<Record<string, any>>({});
const selected = ref();
const applications = ref();
const application = ref<Record<string, any>>();
const applicationDialog = ref<boolean>(false);
const loaded = ref<boolean>(false);

const inboxContext = inject<{
    selectApplications: (ref, currentForm, applications) => void;
}>('inbox-form');

provide('wx-form-errors', null);

onBeforeMount(async () => {
    const response = await api.get<ApiResponse<any>>(`inbox/form/${route.params.id}`);

    form.value = response.data;
    loaded.value = true;
});

watch(
    () => route.params.id,
    async () => {
        const response = await api.get<ApiResponse<any>>(`inbox/form/${route.params.id}`);

        form.value = response.data;
    },
);

watch(
    () => selected.value,
    (items) => {
        inboxContext.selectApplications(applications, form.value, items);
    },
);

const remove = async (item) => {
    try {
        await api.delete(`inbox/application/${item.id}`).then(() => {
            wxSnackbar($t('inbox.forms.application.deleted'));

            setTimeout(() => applications.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};
</script>

<template>
    <wx-datatable
        v-if="loaded && form"
        :key="'form_' + form.id"
        ref="applications"
        endpoint="inbox/application"
        :endpoint-query="{ form_id: form.id }"
        searchable
        selectable="checkbox"
        persist="inbox-forms"
        adaptive="md"
        @selected="(items) => (selected = items)"
    >
        <template #selected="{ item }: { item: Record<any, any> }">
            {{ $t('inbox.forms.application.selected-item', item) }}
        </template>

        <template #row="{ item }: { item: Record<any, any> }">
            <wx-datatable-column size="max-content" id="id" title="ID">
                {{ item.id }}
            </wx-datatable-column>

            <wx-datatable-column
                v-for="field in (form as any).fields.filter((i) => i.in_table)"
                :key="form.id + '-field-' + field.id"
                size="auto"
                :id="'field-' + field.id"
                :title="useLocalesStore().selectLocalizedValue(field.title)"
            >
                {{ item['field_' + field.id] }}
            </wx-datatable-column>
            <wx-datatable-column size="max-content" id="created_at" :title="$t('created-at')">
                {{ item.created_at }}
            </wx-datatable-column>
            <wx-datatable-column size="max-content" id="actions">
                <wx-actions>
                    <wx-action
                        type="details"
                        @click="
                            () => {
                                applicationDialog = true;
                                application = item;
                            }
                        "
                    />
                    <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                </wx-actions>
            </wx-datatable-column>
        </template>
    </wx-datatable>

    <wx-dialog :title="$t('inbox.forms.application.heading', application)" :size="600" v-model="applicationDialog">
        <wx-form-control :title="$t('id')">
            <strong class="fw-semibold ms-8">{{ application.id }}</strong>
        </wx-form-control>

        <wx-form-control :title="$t('created-at')">
            <strong class="fw-semibold ms-8">{{ application.created_at }}</strong>
        </wx-form-control>

        <wx-form-control
            v-for="field in (form as any).fields"
            :key="form.id + '-application-' + field.id"
            :title="useLocalesStore().selectLocalizedValue(field.title)"
        >
            <strong class="fw-semibold ms-8">{{ application['field_' + field.id] || '-' }}</strong>
        </wx-form-control>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button
                    type="button"
                    theme="default"
                    @click="
                        () => {
                            applicationDialog = false;
                            application = null;
                        }
                    "
                    >{{ $t('close') }}</wx-button
                >
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
