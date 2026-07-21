<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { Article, ArticleRubric } from '@/templates/Articles/types';
import type { ApiResponse } from '@/types/api';
import {
    WxButtons,
    WxButton,
    WxPage,
    WxForm,
    WxTabs,
    WxTab,
    WxCard,
    WxSeo,
    WxGrid,
    WxGridCol,
    WxFormControl,
    WxInput,
    WxCheck,
    WxPageComposer,
    WxInputImage,
    WxTextarea,
    WxCheckGroup,
    WxTags,
} from '@/ui';
import { api, wxSnackbar } from '@/utils';

const route = useRoute();
const router = useRouter();

const article = ref<Article>(null);
const rubrics = ref<ArticleRubric[]>([]);
const loaded = ref<boolean>(false);

onBeforeMount(async () => {
    const rubricsResponse = await api.get<ApiResponse<ArticleRubric[]>>('articles/rubrics/list');

    rubrics.value = rubricsResponse.data;

    if (route.params.id) {
        const response = await api.get<ApiResponse<Article>>(`articles/article/${route.params.id}`);

        article.value = response.data;
        loaded.value = true;
    } else if (route.query.copy) {
        const response = await api.get<ApiResponse<Article>>(`articles/article/${route.query.copy}`);

        article.value = response.data;
        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const success = (response: ApiResponse<Article>) => {
    if (!route.params.id) {
        router.push({ name: 'articles.edit', params: { id: response.data.id } });
    }

    wxSnackbar($t('articles.article.saved'), { type: 'success' });
};

const formatedTimestamp = () => {
    const d = new Date();
    const date = d.toISOString().split('T')[0];
    const time = d.toTimeString().split(' ')[0];
    return `${date} ${time}`;
};
</script>

<template>
    <wx-page :heading="$t(article ? (route.query.copy ? 'copy' : 'edit') : 'create')" :back="{ name: 'articles' }" v-if="loaded">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="articles-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="article && !route.query.copy ? `articles/article/${article.id}` : 'articles/article'"
            :method="article && !route.query.copy ? 'put' : 'post'"
            id="articles-form"
            @success="(response) => success(response)"
        >
            <input type="hidden" name="id" :value="!route.query.copy ? article?.id : null" />

            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <wx-card class="mb-16">
                        <template #sidebar>
                            <wx-form-control :title="$t('image')">
                                <wx-input-image name="image" :value="article?.image || null" />
                            </wx-form-control>
                        </template>

                        <wx-grid>
                            <wx-grid-col :md="8">
                                <wx-form-control :title="$t('title')">
                                    <wx-input name="title" :value="article?.title || null" localized />

                                    <template #footer>
                                        <wx-check
                                            name="is_published"
                                            :checked="!article || (article && (article.is_published as boolean))"
                                            :label="$t('is-published')"
                                        />
                                    </template>
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="4">
                                <wx-form-control :title="$t('url')">
                                    <wx-input name="slug" :value="article?.slug || null" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>

                        <wx-form-control :title="$t('published-at')">
                            <wx-input type="datetime" name="published_at" :value="(article?.published_at || formatedTimestamp()) as string" />
                        </wx-form-control>

                        <wx-form-control :title="$t('tags')">
                            <wx-tags name="tags" endpoint="articles/tags" :value="article?.tags" />
                        </wx-form-control>

                        <wx-form-control :title="$t('articles.article.rubrics')" data-name="rubrics">
                            <wx-check-group v-if="rubrics">
                                <wx-check
                                    v-for="rubric in rubrics"
                                    :key="`rubric_${rubric.id}`"
                                    name="rubrics[]"
                                    :value="rubric.id"
                                    :checked="
                                        ((article as Article)?.rubrics as ArticleRubric[])?.map((item) => item.id).includes(rubric.id) ||
                                        false
                                    "
                                    :label="useLocalesStore().selectLocalizedValue(rubric.title)"
                                />
                            </wx-check-group>
                        </wx-form-control>
                    </wx-card>
                    <wx-card>
                        <wx-form-control :title="$t('short-text')">
                            <wx-textarea name="announcement" :value="article?.announcement || null" localized />
                        </wx-form-control>
                    </wx-card>
                    <wx-card>
                        <wx-page-composer name="content" :value="article?.content?.raw || null" />
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('seo.heading')" id="seo">
                    <wx-card>
                        <wx-seo name="seo" :value="article?.seo || null" />
                    </wx-card>
                </wx-tab>
            </wx-tabs>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
