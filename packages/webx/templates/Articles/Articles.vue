<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { Article } from '@/templates/Articles/types';
import { WxButtons, WxButton, WxPage, WxDatatable, WxActions, WxAction, WxDatatableColumn, WxEntityCard, WxIcon } from '@/ui';
import type { WxEntityCardParam } from '@/ui/components/WxEntityCard';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const articles = ref();

const router = useRouter();
const selected = ref([]);

const remove = async (item: Article) => {
    try {
        await api.delete(`articles/article/${item.id}`).then(() => {
            wxSnackbar($t('articles.article.deleted'));

            setTimeout(() => articles.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item: Article) => item.id);

        api.delete(`articles/article/mass-destroy`, { ids: ids }).then(() => {
            wxSnackbar($t('articles.article.deleted'));

            setTimeout(() => articles.value.reload(), 0);
        });
    });
};

const articleParams = (item: Article): WxEntityCardParam[] => {
    const param: WxEntityCardParam[] = [];

    if (item?.rubrics) {
        param.push({
            option: $t('articles.article.rubrics'),
            value: item.rubrics.map((rubric) => useLocalesStore().selectLocalizedValue(rubric.title)).join(', '),
        });
    }
    if (item?.tags) {
        param.push({
            option: $t('articles.article.tags'),
            value: item.tags.map((tag) => useLocalesStore().selectLocalizedValue(tag.title)).join(', '),
        });
    }

    return param;
};
</script>

<template>
    <wx-page :heading="$t('articles.article.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button :route="{ name: 'articles.edit' }" theme="primary">{{ $t('create') }}</wx-button>
                <wx-button @click="removeSelected()" v-if="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <wx-datatable
            ref="articles"
            endpoint="articles/article"
            searchable
            selectable="checkbox"
            persist="articles-articles"
            @selected="(items) => (selected = items)"
        >
            <template #selected="{ item }: { item: Article }">
                {{ useLocalesStore().selectLocalizedValue(item.title) }}
            </template>

            <template #row="{ item }: { item: Article }">
                <wx-datatable-column size="max-content" id="id" title="ID">
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card
                        :title="useLocalesStore().selectLocalizedValue(item.title)"
                        :image="item.image?.src.url"
                        :params="articleParams(item)"
                        @click="() => router.push({ name: 'articles.edit', params: { id: item.id } })"
                    />
                </wx-datatable-column>
                <wx-datatable-column size="max-content" id="actions">
                    <wx-actions>
                        <wx-action type="copy" :route="{ name: 'articles.edit', query: { copy: item.id } }" />
                        <wx-action type="edit" :route="{ name: 'articles.edit', params: { id: item.id } }" />
                        <wx-action type="remove" @click="() => wxConfirm().then(() => remove(item))" />
                    </wx-actions>
                </wx-datatable-column>
            </template>
        </wx-datatable>
    </wx-page>
</template>

<style scoped lang="scss"></style>
