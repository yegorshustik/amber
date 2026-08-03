<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import type { ApiResponse } from '@/types/api';
import {
    WxButtons,
    WxButton,
    WxPage,
    WxForm,
    WxCard,
    WxGrid,
    WxInput,
    WxFormControl,
    WxCheck,
    WxGridCol,
    WxSeo,
    WxTab,
    WxTabs,
    WxTextarea,
    WxInputImage,
    WxAction,
    WxActions,
    WxSortable,
    WxSelect,
    WxCheckGroup,
} from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';
import type { Catalog, CatalogFaqItems } from './types';

const route = useRoute();
const router = useRouter();

const catalog = ref<Catalog>(null);
const loaded = ref<boolean>(false);
const faq = ref<CatalogFaqItems[]>([]);

onBeforeMount(async () => {
    if (route.params.id) {
        const response = await api.get<ApiResponse<Catalog>>(`catalog/${route.params.id}`);

        catalog.value = response.data;
        faq.value = response.data.faq || [];

        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const heading = computed(() => (catalog.value ? $t('catalog.edit') : $t('catalog.create')));

const success = (response: ApiResponse<Catalog>) => {
    if (!route.params.id) {
        router.push({ name: 'catalog.edit', params: { id: response.data.id } });
    }
    catalog.value = response.data;

    wxSnackbar($t('catalog.saved'), { type: 'success' });
};

const addFaqItem = () => {
    faq.value.push({
        question: null,
        answer: null,
    });
};
const removeFaqItem = (index: number) => {
    wxConfirm().then(() => faq.value.splice(index, 1));
};
</script>

<template>
    <wx-page v-if="loaded" :heading="heading" :back="{ name: 'catalog' }">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="catalog-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="catalog ? `catalog/${catalog.id}` : 'catalog'"
            :method="catalog ? 'put' : 'post'"
            id="catalog-form"
            @success="(response) => success(response)"
        >
            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <wx-card class="mb-16">
                        <template #sidebar>
                            <wx-form-control :title="$t('image')">
                                <wx-input-image name="image" :value="catalog?.image || null" />
                            </wx-form-control>
                        </template>

                        <wx-grid>
                            <wx-grid-col :md="9">
                                <wx-form-control :title="$t('title')">
                                    <wx-input name="title" :value="catalog?.title || null" localized />

                                    <template #footer>
                                        <wx-check-group>
                                            <wx-check
                                                name="is_published"
                                                :checked="!catalog || (catalog && catalog.is_published)"
                                                :label="$t('is-published')"
                                            />
                                            <wx-check
                                                name="is_visible"
                                                :checked="!catalog || (catalog && catalog.is_visible)"
                                                :label="$t('is-visible')"
                                            />
                                        </wx-check-group>
                                    </template>
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3">
                                <wx-form-control :title="$t('url')">
                                    <wx-input name="slug" :value="catalog?.slug || null" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                        <wx-form-control :title="$t('short-details')">
                            <wx-textarea name="short_details" :value="catalog?.short_details || null" localized />
                        </wx-form-control>
                        <wx-form-control :title="$t('details')">
                            <wx-textarea name="details" :value="catalog?.details || null" localized />
                        </wx-form-control>
                    </wx-card>
                    <wx-card :title="$t('features')">
                        <wx-grid>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('type')">
                                    <wx-select
                                        name="type"
                                        :value="catalog?.type?.value || 'school'"
                                        :options="[
                                            { label: $t('catalog.type.school'), value: 'school' },
                                            { label: $t('catalog.type.university'), value: 'university' },
                                        ]"
                                    />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.country')">
                                    <wx-input name="country" :value="catalog?.country || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.city')">
                                    <wx-input name="city" :value="catalog?.city || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.age-range')">
                                    <wx-input name="age_range" :value="catalog?.age_range || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.gender')">
                                    <wx-input name="gender" :value="catalog?.gender || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.boarding')">
                                    <wx-input name="boarding" :value="catalog?.boarding || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.curriculum')">
                                    <wx-input name="curriculum" :value="catalog?.curriculum || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.size')">
                                    <wx-input name="size" :value="catalog?.size || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.campus_style')">
                                    <wx-input name="campus_style" :value="catalog?.campus_style || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.acceptance')">
                                    <wx-input name="acceptance" :value="catalog?.acceptance || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.programs')">
                                    <wx-input name="programs" :value="catalog?.programs || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.degrees')">
                                    <wx-input name="degrees" :value="catalog?.degrees || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3" :lg="4">
                                <wx-form-control :title="$t('catalog.features.established')">
                                    <wx-input name="established" :value="catalog?.established || null" localized />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                    </wx-card>
                    <p>&nbsp;</p>
                </wx-tab>
                <wx-tab :name="$t('content')" id="content">
                    <wx-card>
                        <wx-form-control :title="$t('pre-heading')">
                            <wx-input name="pre_heading" :value="catalog?.pre_heading || null" localized />
                        </wx-form-control>
                        <wx-form-control :title="$t('heading')">
                            <wx-input name="heading" :value="catalog?.heading || null" localized />
                        </wx-form-control>
                        <wx-form-control :title="$t('text')">
                            <wx-textarea wysiwyg name="content" :value="catalog?.content || null" localized />
                        </wx-form-control>
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('faq.heading')" id="faq">
                    <wx-card :title="$t('faq.heading')">
                        <template #actions>
                            <wx-actions>
                                <wx-action :data-tooltip="$t('add')" type="add" @click="() => addFaqItem()" />
                            </wx-actions>
                        </template>

                        <template v-for="(item, index) in faq" :key="'input-' + index">
                            <template v-for="(question, locale) in item.question" :key="locale + index + '-q'">
                                <input type="hidden" :name="`faq[${index}][question][${locale}]`" :value="question" />
                            </template>
                            <template v-for="(answer, locale) in item.answer" :key="locale + index + '-a'">
                                <input type="hidden" :name="`faq[${index}][answer][${locale}]`" :value="answer" />
                            </template>
                        </template>

                        <wx-sortable v-model="faq">
                            <template #content="{ item }: { item: CatalogFaqItems }">
                                <wx-form-control :title="$t('faq.question')">
                                    <wx-input v-model="item.question" localized />
                                </wx-form-control>
                                <wx-form-control :title="$t('faq.answer')">
                                    <wx-textarea v-model="item.answer" localized wysiwyg />
                                </wx-form-control>
                            </template>
                            <template #actions="{ index }: { index: number }">
                                <wx-actions>
                                    <wx-action type="sort" class="handle" />
                                    <wx-action type="remove" @click="removeFaqItem(index)" />
                                </wx-actions>
                            </template>
                        </wx-sortable>

                        <div class="d-flex justify-content-center mt-8">
                            <wx-actions>
                                <wx-action :data-tooltip="$t('add')" type="add" @click="() => addFaqItem()" />
                            </wx-actions>
                        </div>
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('seo.heading')" id="seo">
                    <wx-card>
                        <wx-seo name="seo" :value="catalog?.seo || null" />
                    </wx-card>
                </wx-tab>
            </wx-tabs>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
