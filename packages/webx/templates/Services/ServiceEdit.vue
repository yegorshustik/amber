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
    WxCheckGroup,
    WxPageComposer,
    WxSeo,
    WxTab,
    WxTabs,
    WxTextarea,
} from '@/ui';
import { api, wxSnackbar } from '@/utils';
import type { Service } from './types';

const route = useRoute();
const router = useRouter();

const service = ref(null);
const loaded = ref<boolean>(false);

onBeforeMount(async () => {
    if (route.params.id) {
        const response = await api.get<ApiResponse<Service>>(`services/${route.params.id}`);

        service.value = response.data;
        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const heading = computed(() => (service.value ? $t('services.edit') : $t('services.create')));

const success = (response: ApiResponse<Service>) => {
    if (!route.params.id) {
        router.push({ name: 'services.edit', params: { id: response.data.id } });
    }
    service.value = response.data;

    wxSnackbar($t('services.saved'), { type: 'success' });
};
</script>

<template>
    <wx-page v-if="loaded" :heading="heading" :back="{ name: 'services' }">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="services-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="service ? `services/${service.id}` : 'services'"
            :method="service ? 'put' : 'post'"
            id="services-form"
            @success="(response) => success(response)"
        >
            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <wx-card class="mb-16">
                        <wx-grid>
                            <wx-grid-col :md="9">
                                <wx-form-control :title="$t('title')">
                                    <wx-input name="title" :value="service?.title || null" localized />

                                    <template #footer>
                                        <wx-check
                                            name="is_published"
                                            :checked="!service || (service && service.is_published)"
                                            :label="$t('is-published')"
                                        />
                                    </template>
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :md="3">
                                <wx-form-control :title="$t('url')">
                                    <wx-input name="slug" :value="service?.slug || null" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                        <wx-form-control :title="$t('details')">
                            <wx-textarea name="details" :value="service?.details || null" localized />
                        </wx-form-control>
                    </wx-card>

                    <wx-card>
                        <wx-page-composer name="content" :value="service?.content?.raw || null" />
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('seo.heading')" id="seo">
                    <wx-card>
                        <wx-seo name="seo" :value="service?.seo || null" />
                    </wx-card>
                </wx-tab>
            </wx-tabs>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
