<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ApiResponse } from '@/types/api';
import {
    WxAction,
    WxActions,
    WxButton,
    WxButtons,
    WxCard,
    WxCheck,
    WxCheckGroup,
    WxDatatable,
    WxDatatableColumn,
    WxDialog,
    WxEntityCard,
    WxFieldset,
    WxForm,
    WxFormControl,
    WxGrid,
    WxGridCol,
    WxInput,
    WxPage,
    WxSelect,
    WxSortable,
    WxTab,
    WxTabs,
    WxTextarea,
} from '@/ui';
import WxAlert from '@/ui/components/WxAlert/WxAlert.vue';
import type { WxSelectOption } from '@/ui/components/WxSelect';
import { api, wxConfirm, wxSnackbar } from '@/utils';
import { WxLocalizedValue } from '@/types/locale';

const route = useRoute();
const router = useRouter();

const form = ref();
const loaded = ref<boolean>(false);

const fieldsDialog = ref<boolean>(false);
const fields = ref();
const field = ref();
const selectedFields = ref([]);
const currentType = ref();
const currentOptions = ref([]);

onBeforeMount(async () => {
    if (route.params.id) {
        const response = await api.get<ApiResponse<any>>(`inbox/form/${route.params.id}`);

        form.value = response.data;
        loaded.value = true;
    } else if (route.query.copy) {
        const response = await api.get<ApiResponse<any>>(`inbox/form/${route.query.copy}`);

        form.value = response.data;
        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const success = (response: ApiResponse<any>) => {
    if (!route.params.id) {
        router.push({ name: 'inbox.forms.edit', params: { id: response.data.id } });
        form.value = response.data;
    }

    wxSnackbar($t('inbox.forms.saved'), { type: 'success' });
};

const fieldTypes = (): WxSelectOption[] => [
    { label: $t('inbox.forms.fields.types.text'), value: 'text' },
    { label: $t('inbox.forms.fields.types.email'), value: 'email' },
    { label: $t('inbox.forms.fields.types.tel'), value: 'tel' },
    { label: $t('inbox.forms.fields.types.textarea'), value: 'textarea' },
    { label: $t('inbox.forms.fields.types.date'), value: 'date' },
    { label: $t('inbox.forms.fields.types.select'), value: 'select' },
];

const successField = () => {
    field.value = null;
    fieldsDialog.value = false;
    wxSnackbar($t('inbox.forms.fields.saved'), { type: 'success' });
    setTimeout(() => fields.value.reload(), 0);
};
const removeField = async (item) => {
    try {
        await api.delete(`inbox/field/${item.id}`, { form_id: form.value.id }).then(() => {
            wxSnackbar($t('inbox.forms.fields.deleted'));

            setTimeout(() => fields.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveFieldsSorting = (items) => {
    const ids = items.map((item) => item.id);

    api.post(`inbox/field/sorting`, { form_id: form.value.id, ids: ids });
};

const removeSelectedFields = () => {
    wxConfirm().then(() => {
        const ids = selectedFields.value.map((item) => item.id);

        api.delete(`inbox/field/mass-destroy`, { form_id: form.value.id, ids: ids }).then(() => {
            wxSnackbar($t('inbox.forms.fields.deleted'));

            setTimeout(() => fields.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t(form ? (route.query.copy ? 'copy' : 'edit') : 'create')" :back="{ name: 'inbox.forms' }" v-if="loaded">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="inbox-forms-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="form ? `inbox/form/${form.id}` : 'inbox/form'"
            :method="form ? 'put' : 'post'"
            id="inbox-forms-form"
            @success="(response) => success(response)"
        >
            <input type="hidden" name="id" :value="!route.query.copy ? form?.id : null" />
            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <wx-card>
                        <wx-grid>
                            <wx-grid-col :md="8">
                                <wx-form-control :title="$t('title')">
                                    <wx-input name="title" :value="form?.title || null" localized />

                                    <template #footer>
                                        <wx-check
                                            name="is_published"
                                            :checked="!form || (form && (form.is_published as boolean))"
                                            :label="$t('is-published')"
                                        />
                                    </template>
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="4">
                                <wx-form-control :title="$t('url')">
                                    <wx-input name="slug" :value="form?.slug || null" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                    </wx-card>
                    <wx-card>
                        <wx-form-control :title="$t('inbox.forms.recipients')">
                            <wx-textarea name="recipients" :value="form?.recipients || null" />
                        </wx-form-control>
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('settings')" id="settings">
                    <wx-card :title="$t('inbox.forms.thank-you')">
                        <wx-form-control :title="$t('heading')">
                            <wx-input name="options[thank-you.heading]" localized :value="form?.options['thank-you.heading'] || null" />
                        </wx-form-control>
                        <wx-form-control :title="$t('text')">
                            <wx-textarea name="options[thank-you.text]" wysiwyg localized :value="form?.options['thank-you.text'] || null" />
                        </wx-form-control>
                    </wx-card>
                    <wx-card :title="$t('inbox.forms.design')">
                        <wx-form-control :title="$t('inbox.forms.submit-button-text')">
                            <wx-input
                                name="options[design.submit-button-text]"
                                localized
                                :value="form?.options['design.submit-button-text'] || null"
                            />
                        </wx-form-control>
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('inbox.forms.fields.heading')" id="fields">
                    <wx-card v-if="form" :title="$t('inbox.forms.fields.heading')">
                        <template #actions>
                            <wx-actions>
                                <wx-action type="add" @click="fieldsDialog = true" />
                                <wx-action type="remove" v-if="selectedFields.length > 0" @click="removeSelectedFields()" />
                            </wx-actions>
                        </template>

                        <wx-datatable
                            ref="fields"
                            endpoint="inbox/field"
                            :endpoint-query="{ form_id: form.id }"
                            searchable
                            sortable
                            selectable="checkbox"
                            persist="inbox-forms-fields"
                            @selected="(items) => (selectedFields = items)"
                            @sorted="(items) => saveFieldsSorting(items)"
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
                                        :params="[
                                            {
                                                value: [
                                                    item.is_published ? $t('is-published') : null,
                                                    item.is_required ? $t('is-required') : null,
                                                    item.is_fullsize ? $t('inbox.forms.fields.is-fullsize') : null,
                                                    item.in_table ? $t('inbox.forms.fields.in-table') : null,
                                                ]
                                                    .filter((p) => p !== null)
                                                    .join(', '),
                                            },
                                        ]"
                                        @click="() => router.push({ name: 'inbox.forms.edit', params: { id: item.id } })"
                                    />
                                </wx-datatable-column>
                                <wx-datatable-column size="auto" id="type">
                                    {{ item.type.title }}
                                </wx-datatable-column>
                                <wx-datatable-column size="max-content" id="actions">
                                    <wx-actions>
                                        <wx-action type="sort" class="handle" />
                                        <wx-action
                                            type="edit"
                                            @click="
                                                () => {
                                                    field = item as Record<any, any>;
                                                    fieldsDialog = true;
                                                    currentType = field.type.value;
                                                    currentOptions = field.options;
                                                }
                                            "
                                        />
                                        <wx-action type="remove" @click="() => wxConfirm().then(() => removeField(item))" />
                                    </wx-actions>
                                </wx-datatable-column>
                            </template>
                        </wx-datatable>
                    </wx-card>

                    <wx-alert v-else type="info">{{ $t('inbox.forms.fields.warning') }}</wx-alert>
                </wx-tab>
            </wx-tabs>
        </wx-form>

        <wx-dialog
            :action="field ? `inbox/field/${field.id}` : 'inbox/field'"
            :method="field ? 'put' : 'post'"
            v-model="fieldsDialog"
            :title="field ? $t('inbox.forms.fields.edit') : $t('inbox.forms.fields.create')"
            @success="() => successField()"
            :size="800"
        >
            <input name="form_id" type="hidden" :value="form?.id" />

            <template #sidebar>
                <wx-form-control :title="$t('type')">
                    <wx-select name="type" :options="fieldTypes()" @change="(v) => (currentType = v)" :value="field?.type.value || 'text'" />
                </wx-form-control>
                <wx-form-control :title="$t('settings')">
                    <wx-check-group class="flex-column">
                        <wx-check name="is_published" :label="$t('is-published')" :checked="!field || (field && (field.is_published as boolean))" />
                        <wx-check name="is_required" :label="$t('is-required')" :checked="field && (field.is_required as boolean)" />
                        <wx-check
                            name="is_fullsize"
                            :label="$t('inbox.forms.fields.is-fullsize')"
                            :checked="field && (field.is_fullsize as boolean)"
                        />
                        <wx-check name="in_table" :label="$t('inbox.forms.fields.in-table')" :checked="field && (field.in_table as boolean)" />
                    </wx-check-group>
                </wx-form-control>
            </template>

            <wx-grid>
                <wx-grid-col :md="6">
                    <wx-form-control :title="$t('title')">
                        <wx-input name="title" :value="field?.title || null" localized />
                    </wx-form-control>
                </wx-grid-col>
                <wx-grid-col :md="6">
                    <wx-form-control :title="$t('placeholder')">
                        <wx-input name="placeholder" :value="field?.placeholder || null" localized />
                    </wx-form-control>
                </wx-grid-col>
            </wx-grid>

            <wx-fieldset :legend="$t('inbox.forms.fields.options')" v-if="currentType == 'select'">
                <template v-for="(item, index) in currentOptions" :key="index + '-input'">
                    <template v-for="(option, locale) in item.option" :key="locale">
                        <input type="hidden" :name="`options[${index}][option][${locale}]`" :value="option" />
                    </template>
                </template>
                <wx-sortable v-model="currentOptions">
                    <template #content="{ item }: { item: { option: WxLocalizedValue } }">
                        <wx-input v-model="item.option" localized />
                    </template>
                    <template #actions="{ index }: { index: number }">
                        <wx-actions>
                            <wx-action type="sort" class="handle" />
                            <wx-action type="remove" @click="currentOptions.splice(index, 1)" />
                        </wx-actions>
                    </template>
                </wx-sortable>

                <div class="d-flex justify-content-center mt-8">
                    <wx-actions>
                        <wx-action :data-tooltip="$t('add')" type="add" @click="() => currentOptions.push({ option: null })" />
                    </wx-actions>
                </div>
            </wx-fieldset>

            <template #footer>
                <wx-buttons class="justify-content-end">
                    <wx-button
                        type="button"
                        theme="default"
                        @click="
                            () => {
                                fieldsDialog = false;
                                field = null;
                                currentOptions = [];
                                currentType = null;
                            }
                        "
                        >{{ $t('cancel') }}</wx-button
                    >
                    <wx-button type="submit" theme="primary">{{ $t('save') }}</wx-button>
                </wx-buttons>
            </template>
        </wx-dialog>
    </wx-page>
</template>

<style scoped lang="scss"></style>
