<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import type { Page } from '@/templates/Pages/types';
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
    WxSelect,
} from '@/ui';
import { api, wxSnackbar } from '@/utils';

const route = useRoute();
const router = useRouter();

const page = ref<Page>(null);
const loaded = ref<boolean>(false);

onBeforeMount(async () => {
    if (route.params.id) {
        const response = await api.get<ApiResponse<Page>>(`page/${route.params.id}`);

        page.value = response.data;
        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const success = (response: ApiResponse<Page>) => {
    if (!route.params.id) {
        router.push({ name: 'pages.edit', params: { id: response.data.id } });
    }
    wxSnackbar($t('pages.saved'));
};
</script>

<template>
    <wx-page :heading="$t(page ? 'edit' : 'create')" :back="{ name: 'pages' }" v-if="loaded">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="pages-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form action="page/store" method="post" id="pages-form" @success="(response) => success(response)">
            <input type="hidden" name="id" :value="page?.id" />
            <input type="hidden" name="parent_id" :value="route.query?.parent_id || null" />

            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <wx-card class="mb-16">
                        <wx-grid>
                            <wx-grid-col :md="9">
                                <wx-form-control :title="$t('title')">
                                    <wx-input name="title" :value="page?.title || null" localized />

                                    <template #footer>
                                        <wx-check name="is_published" :checked="!page || (page && page.is_published)" :label="$t('is-published')" />
                                    </template>
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3">
                                <wx-form-control :title="$t('url')">
                                    <wx-input name="slug" :value="page?.slug || null" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                    </wx-card>

                    <wx-card>
                        <wx-page-composer name="content" :value="page?.content?.raw || null" />
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('seo.heading')" id="seo">
                    <wx-card>
                        <wx-seo name="seo" :value="page?.seo || null" />
                    </wx-card>
                </wx-tab>
            </wx-tabs>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
