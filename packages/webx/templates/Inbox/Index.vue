<script setup lang="ts">
import { onBeforeMount, provide, type Ref, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ApiResponse } from '@/types/api';
import { WxButton, WxButtons, WxIcon, WxListGroup, WxListGroupItem, WxPage } from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';

const router = useRouter();
const route = useRoute();

const forms = ref<Record<string, any>>({});
const loaded = ref<boolean>(false);
const selected = ref<Record<string, any>[]>([]);
const form = ref();
const applicationsRef = ref();

onBeforeMount(async () => {
    const response = await api.get<ApiResponse<any>>(`inbox/form/list`);

    forms.value = response.data;

    if (!route.params.id && forms.value[0]) {
        await router.push({ name: 'inbox.form.applications', params: { id: forms.value[0].id } });
    } else {
        loaded.value = true;
    }
});

watch(
    () => route.params.id,
    () => {
        if (route.params.id && forms.value[0]) {
           // router.push({ name: 'inbox.form.applications', params: { id: forms.value[0].id } });
        }
    },
);

const isActive = (form) => {
    return parseInt(<string>route.params.id) === parseInt(form.id);
};

provide<{
    selectApplications: (ref, currentForm, applications) => void;
}>('inbox-form', {
    selectApplications(ref, currentForm, applications) {
        applicationsRef.value = ref.value;
        form.value = currentForm;
        selected.value = applications;
    },
});

const removeSelected = () => {
    wxConfirm().then(() => {
        const ids = selected.value.map((item) => item.id);

        api.delete(`inbox/application/mass-destroy`, { form_id: form.value.id, ids: ids }).then(() => {
            wxSnackbar($t('inbox.forms.application.deleted'));
            selected.value = [];
            setTimeout(() => applicationsRef.value.reload(), 0);
        });
    });
};

const exportApplications = () => {
    wxConfirm().then(() => {
        api.downloadStream('post', `inbox/application/export`, {
            body: { form_id: route.params.id },
        }).then((response) => {
            const url = window.URL.createObjectURL(response as any);
            const a = document.createElement('a');
            a.href = url;

            a.download = `export_${new Date().toISOString().split('T')[0]}.csv`;

            document.body.appendChild(a);
            a.click();

            window.URL.revokeObjectURL(url);
            a.remove();
        });
    });
};
</script>

<template>
    <wx-page :heading="$t('menu.inbox')" size="full">
        <template #actions>
            <wx-buttons>
                <wx-button v-if="route.params.id" theme="primary" :route="{ name: 'inbox.forms.application', params: { id: route.params.id } }">{{
                    $t('create')
                }}</wx-button>
                <wx-button v-if="route.params.id" theme="warning" @click="() => exportApplications()">{{ $t('export') }}</wx-button>

                <wx-button @click="removeSelected()" v-show="selected.length > 0" theme="danger" square>
                    <wx-icon name="remove" />
                </wx-button>
            </wx-buttons>
        </template>

        <template #sidebar>
            <wx-list-group :title="$t('inbox.forms.heading')">
                <wx-list-group-item
                    v-for="form in forms"
                    :key="form.id"
                    @click="() => router.push({ name: 'inbox.form.applications', params: { id: form.id } })"
                    :active="isActive(form)"
                >
                    {{ useLocalesStore().selectLocalizedValue(form.title) }}
                </wx-list-group-item>
            </wx-list-group>
        </template>

        <router-view />
    </wx-page>
</template>

<style scoped lang="scss"></style>
