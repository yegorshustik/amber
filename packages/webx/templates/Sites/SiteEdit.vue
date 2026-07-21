<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import type { ApiResponse } from '@/types/api';
import { WxButtons, WxButton, WxPage, WxForm, WxCard, WxGrid, WxInput, WxFormControl, WxCheck, WxGridCol, WxCheckGroup } from '@/ui';
import { api, wxSnackbar } from '@/utils';
import { useSitesStore } from '../../stores/sites';
import type { Site } from './types';

const route = useRoute();
const router = useRouter();

const site = ref(null);
const loaded = ref<boolean>(false);

onBeforeMount(async () => {
    if (route.params.id) {
        const response = await api.get<ApiResponse<Site>>(`sites/${route.params.id}`);

        site.value = response.data;
        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const heading = computed(() => (site.value ? $t('sites.edit') : $t('sites.create')));

const success = (response: ApiResponse<Site>) => {
    if (!route.params.id) {
        router.push({ name: 'sites.edit', params: { id: response.data.id } });
    }
    site.value = response.data;

    useSitesStore().load();

    wxSnackbar($t('sites.saved'), { type: 'success' });
};
</script>

<template>
    <wx-page v-if="loaded" :heading="heading" :back="{ name: 'sites' }">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="sites-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="site ? `sites/${site.id}` : 'sites'"
            :method="site ? 'put' : 'post'"
            id="sites-form"
            @success="(response) => success(response)"
        >
            <wx-card>
                <wx-form-control :title="$t('title')">
                    <wx-input name="title" :value="site?.title || null" />
                    <template #footer>
                        <wx-check-group>
                            <wx-check name="is_published" :checked="!site || (site && site.is_published)" :label="$t('is-published')" />
                        </wx-check-group>
                    </template>
                </wx-form-control>

                <wx-grid>
                    <wx-grid-col :md="4">
                        <wx-form-control :title="$t('domain')">
                            <wx-input name="domain" :value="site?.domain || null" />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :md="4">
                        <wx-form-control :title="$t('domain-alternative')">
                            <wx-input name="domain_alternative" :value="site?.domain_alternative || null" />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :md="4">
                        <wx-form-control :title="$t('slug')">
                            <wx-input name="slug" :value="site?.slug || null" />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>
            </wx-card>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
