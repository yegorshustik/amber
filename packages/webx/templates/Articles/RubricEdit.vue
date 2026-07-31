<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import type { ArticleRubric } from '@/templates/Articles/types';
import type { ApiResponse } from '@/types/api';
import {
    WxButtons,
    WxButton,
    WxPage,
    WxForm,
    WxTabs,
    WxTab,
    WxSeo,
    WxCard,
    WxGrid,
    WxInput,
    WxFormControl,
    WxCheck,
    WxGridCol,
    WxTextarea,
    WxInputImage,
} from '@/ui';
import { api, wxSnackbar } from '@/utils';

const route = useRoute();
const router = useRouter();

const rubric = ref<ArticleRubric>(null);
const loaded = ref<boolean>(false);

onBeforeMount(async () => {
    if (route.params.id) {
        const response = await api.get<ApiResponse<ArticleRubric>>(`articles/rubrics/${route.params.id}`);

        rubric.value = response.data;
        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const heading = computed(() => (rubric.value ? $t('articles.rubrics.edit') : $t('articles.rubrics.create')));

const success = (response: ApiResponse<ArticleRubric>) => {
    if (!route.params.id) {
        router.push({ name: 'articles.rubrics.edit', params: { id: response.data.id } });
    }
    rubric.value = response.data;

    wxSnackbar($t('articles.rubrics.saved'), { type: 'success' });
};
</script>

<template>
    <wx-page v-if="loaded" :heading="heading" :back="{ name: 'articles.rubrics' }">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="articles-rubric-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="rubric ? `articles/rubrics/${rubric.id}` : 'articles/rubrics'"
            :method="rubric ? 'put' : 'post'"
            id="articles-rubric-form"
            @success="(response: ApiResponse<ArticleRubric>) => success(response)"
        >
            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <wx-card class="mb-16">
                        <template #sidebar>
                            <wx-form-control :title="$t('image')">
                                <wx-input-image name="image" :value="rubric?.image || null" />
                            </wx-form-control>
                        </template>

                        <wx-form-control :title="$t('pre-heading')">
                            <wx-input name="pre_heading" :value="rubric?.pre_heading || null" localized />
                        </wx-form-control>

                        <wx-grid>
                            <wx-grid-col :md="9">
                                <wx-form-control :title="$t('title')">
                                    <wx-input name="title" :value="rubric?.title || null" localized />

                                    <template #footer>
                                        <wx-check
                                            name="is_published"
                                            :checked="!rubric || (rubric && (rubric.is_published as boolean))"
                                            :label="$t('is-published')"
                                        />
                                    </template>
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3">
                                <wx-form-control :title="$t('url')">
                                    <wx-input name="slug" :value="rubric?.slug || null" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>

                        <wx-form-control :title="$t('details')">
                            <wx-textarea name="details" :value="rubric?.details || null" localized />
                        </wx-form-control>
                    </wx-card>

                    <wx-card>
                        <wx-textarea name="content" :value="rubric?.content || null" wysiwyg localized />
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('seo.heading')" id="seo">
                    <wx-card>
                        <wx-seo name="seo" :value="rubric?.seo || null" />
                    </wx-card>
                </wx-tab>
            </wx-tabs>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
