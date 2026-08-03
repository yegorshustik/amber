<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import { useConfigurationStore } from '@/stores/configuration';
import type { Catalog } from '@/templates/Catalog/types';
import type { ApiResponse } from '@/types/api';
import {
    WxButtons,
    WxButton,
    WxPage,
    WxActions,
    WxDatatable,
    WxEntityCard,
    WxDatatableColumn,
    WxAction,
    WxIcon,
    WxDialog,
    WxSeo,
    WxFormControl,
    WxTextarea,
} from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const catalog = ref();

const router = useRouter();
const selected = ref([]);
const catalogSeoDialog = ref<boolean>(false);

const remove = async (item) => {
    try {
        await api.delete<ApiResponse<Catalog[]>>(`catalog/${item.id}`).then(() => {
            wxSnackbar($t('catalog.deleted'));
            setTimeout(() => catalog.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveSorting = (items: Catalog[]) => {
    const ids = items.map((item) => item.id);

    api.post(`catalog/sorting`, { ids: ids });
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item: Catalog) => item.id);

        api.delete(`catalog/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('catalog.deleted'));

            setTimeout(() => catalog.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('catalog.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button @click="catalogSeoDialog = true" theme="outline-primary">{{ $t('seo.heading') }}</wx-button>
                <wx-button :route="{ name: 'catalog.edit' }" theme="primary">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="catalog"
            endpoint="catalog"
            searchable
            sortable
            selectable="checkbox"
            persist="catalog"
            @selected="(items) => (selected = items)"
            @sorted="(items) => saveSorting(items)"
        >
            <template #selected="{ item }: { item: Catalog }">
                {{ useLocalesStore().selectLocalizedValue(item.title) }}
            </template>

            <template #row="{ item }: { item: Catalog }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="useLocalesStore().selectLocalizedValue(item.title)"
                        :image="item.image?.src.url"
                        :params="[
                            {
                                value: [item.is_published ? $t('is-published') : null].filter((p) => p !== null).join(', '),
                            },
                        ]"
                        @click="() => router.push({ name: 'catalog.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="edit" :route="{ name: 'catalog.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>

        <wx-dialog
            v-model="catalogSeoDialog"
            :size="1000"
            :title="$t('seo.heading')"
            action="configuration/store"
            method="post"
            id="catalog-configuration-form"
            @success="
                (response) => {
                    useConfigurationStore().params = response.data;
                    catalogSeoDialog = false;
                }
            "
        >
            <wx-seo name="param[catalog.seo]" :value="useConfigurationStore().getRaw('catalog.seo')" />

            <wx-form-control :title="$t('text')">
                <wx-textarea wysiwyg name="param[catalog.text]" localized :value="useConfigurationStore().getRaw('catalog.text')" />
            </wx-form-control>

            <template #footer>
                <wx-buttons class="justify-content-end">
                    <wx-button type="button" theme="default" @click="catalogSeoDialog = false">{{ $t('cancel') }}</wx-button>
                    <wx-button type="submit" theme="primary">{{ $t('save') }}</wx-button>
                </wx-buttons>
            </template>
        </wx-dialog>
    </wx-page>
</template>

<style scoped lang="scss"></style>
