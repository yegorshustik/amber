<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import { WxButtons, WxButton, WxPage, WxActions, WxDatatable, WxEntityCard, WxDatatableColumn, WxAction, WxIcon } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';
import { Review } from '@/templates/Reviews/types';

const reviews = ref();

const router = useRouter();
const selected = ref([]);

const remove = async (item) => {
    try {
        await api.delete(`reviews/${item.id}`).then(() => {
            wxSnackbar($t('reviews.deleted'));

            setTimeout(() => reviews.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveSorting = (items) => {
    const ids = items.map((item) => item.id);

    api.post(`reviews/sorting`, { ids: ids });
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item) => item.id);

        api.delete(`reviews/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('reviews.deleted'));

            setTimeout(() => reviews.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('reviews.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button :route="{ name: 'reviews.edit' }" theme="primary">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="reviews"
            endpoint="reviews"
            searchable
            sortable
            selectable="checkbox"
            persist="reviews"
            @selected="(items) => (selected = items)"
            @sorted="(items) => saveSorting(items)"
        >
            <template #selected="{ item }: { item: Review }">
                {{ useLocalesStore().selectLocalizedValue(item.name) }}
            </template>

            <template #row="{ item }: { item: Review }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="useLocalesStore().selectLocalizedValue(item.name)"
                        :image="item.image?.src.url"
                        :params="[
                            {
                                option: $t('job'),
                                value: useLocalesStore().selectLocalizedValue(item.job),
                            },
                            {
                                option: $t('published-at'),
                                value: item.published_at,
                            },
                        ]"
                        @click="() => router.push({ name: 'reviews.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="edit" :route="{ name: 'reviews.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
