<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ApiResponse } from '@/types/api';
import { WxButton, WxButtons, WxCard, WxForm, WxFormControl, WxInput, WxPage, WxSelect, WxTextarea } from '@/ui';
import { api, wxSnackbar } from '@/utils';

const route = useRoute();
const router = useRouter();

const form = ref<Record<string, any>>({});
const loaded = ref<boolean>(false);

onBeforeMount(async () => {
    const response = await api.get<ApiResponse<any>>(`inbox/form/${route.params.id}`);

    form.value = response.data;
    loaded.value = true;
});

const success = () => {
    router.push({ name: 'inbox.form.applications', params: { id: form.value.id } });

    wxSnackbar($t('inbox.forms.application.saved'), { type: 'success' });
};

const prepareOptions = (options) => {
    return options.map((item) => ({
        label: useLocalesStore().selectLocalizedValue(item.option),
        value: useLocalesStore().selectLocalizedValue(item.option),
    }));
};
</script>

<template>
    <wx-page :heading="$t('inbox.forms.application.create')" :back="{ name: 'inbox.forms' }" v-if="loaded">
        <wx-form action="inbox/application" method="post" id="inbox-application-form" @success="() => success()">
            <input type="hidden" name="form_id" :value="form.id" />
            <wx-card class="inbox-application mx-auto" :title="useLocalesStore().selectLocalizedValue(form.title)">
                <div class="row">
                    <div
                        class="col-12"
                        :class="{ 'col-lg-6': !(field as Record<string, any>).is_fullsize }"
                        v-for="field in (form as any).fields"
                        :key="`form-item-${field.id}`"
                    >
                        <wx-form-control :title="useLocalesStore().selectLocalizedValue(field.title)">
                            <template v-if="field.type.value === 'text'">
                                <wx-input :name="`field_${field.id}`" :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)" />
                            </template>
                            <template v-if="field.type.value === 'date'">
                                <wx-input
                                    type="date"
                                    :name="`field_${field.id}`"
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                />
                            </template>
                            <template v-if="field.type.value === 'email'">
                                <wx-input
                                    type="email"
                                    :name="`field_${field.id}`"
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                />
                            </template>
                            <template v-if="field.type.value === 'tel'">
                                <wx-input
                                    type="tel"
                                    :name="`field_${field.id}`"
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                />
                            </template>
                            <template v-if="field.type.value === 'textarea'">
                                <wx-textarea :name="`field_${field.id}`" :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)" />
                            </template>
                            <template v-if="field.type.value === 'select'">
                                <wx-select
                                    :name="`field_${field.id}`"
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                    value=""
                                    :options="prepareOptions(field.options)"
                                />
                            </template>
                        </wx-form-control>
                    </div>
                </div>
                <wx-buttons class="justify-content-end">
                    <wx-button theme="success" type="submit">{{
                        useLocalesStore().selectLocalizedValue(form.options['design.submit-button-text'], $t('send'))
                    }}</wx-button>
                </wx-buttons>
            </wx-card>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss">
.inbox-application {
    max-width: 800px;
}
</style>
