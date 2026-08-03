<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ArticleRubric } from '@/templates/Articles/types';
import { WxButtons, WxButton, WxPage, WxActions, WxDatatable, WxEntityCard, WxDatatableColumn, WxAction, WxIcon } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const rubrics = ref();

const router = useRouter();
const selected = ref([]);

const remove = async (item: ArticleRubric) => {
    try {
        await api.delete(`articles/rubrics/${item.id}`).then(() => {
            wxSnackbar($t('articles.rubrics.deleted'));

            setTimeout(() => rubrics.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const saveSorting = (items: ArticleRubric[]) => {
    const ids = items.map((item: ArticleRubric) => item.id);

    api.post(`articles/rubrics/sorting`, { ids: ids });
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item: ArticleRubric) => item.id);

        api.delete(`articles/rubrics/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('articles.rubrics.deleted'));

            setTimeout(() => rubrics.value.reload(), 0);
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('articles.rubrics.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button :route="{ name: 'articles.rubrics.edit' }" theme="primary">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="rubrics"
            endpoint="articles/rubrics"
            searchable
            sortable
            selectable="checkbox"
            persist="articles-rubrics"
            @selected="(items: ArticleRubric[]) => (selected = items)"
            @sorted="(items: ArticleRubric[]) => saveSorting(items)"
        >
            <template #selected="{ item }: { item: ArticleRubric }">
                {{ useLocalesStore().selectLocalizedValue(item.title) }}
            </template>

            <template #row="{ item }: { item: ArticleRubric }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="useLocalesStore().selectLocalizedValue(item.title)"
                        :image="item.image?.src.url"
                        @click="() => router.push({ name: 'articles.rubrics.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="edit" :route="{ name: 'articles.rubrics.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
