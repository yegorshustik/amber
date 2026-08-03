<script setup lang="ts">
import { onBeforeMount, ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ApiResponse } from '@/types/api';
import { WxButtons, WxFormControl, WxGrid, WxGridCol, WxHeading, WxInput, WxSelect, WxTextarea } from '@/ui';
import { api } from '@/utils';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});
const emit = defineEmits(['update:edit']);

const component = ref<WxPageComposerComponent>(props.component);
const editMode = ref<boolean>(props.edit);
const forms = ref([]);

onBeforeMount(async () => {
    const response = await api.get<ApiResponse<any>>(`inbox/form/list`);
    forms.value = response.data;
});

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);

const selectedForm = () => {
    return forms.value.find((item) => item.id === component.value.content.form_id);
};

const prepareOptions = (options) => {
    return options.map((item) => ({
        label: useLocalesStore().selectLocalizedValue(item.option),
        value: useLocalesStore().selectLocalizedValue(item.option),
    }));
};
</script>

<template>
    <wx-grid>
        <wx-grid-col :lg="6">
            <div class="d-flex flex-column justify-content-center">
                <div v-if="useLocalesStore().selectLocalizedValue(component.content.pre_heading)" class="fs-14px text-uppercase mb-6">
                    {{ useLocalesStore().selectLocalizedValue(component.content.pre_heading) }}
                </div>
                <wx-heading
                    v-if="useLocalesStore().selectLocalizedValue(component.content.heading?.text)"
                    preview
                    v-model="component.content.heading"
                    class="mb-16 mt-0"
                />
                <div
                    class=""
                    v-if="useLocalesStore().selectLocalizedValue(component.content.text)"
                    v-html="useLocalesStore().selectLocalizedValue(component.content.text)"
                />
            </div>
        </wx-grid-col>
        <wx-grid-col :lg="6">
            <div class="bg-lightest rounded p-16" v-if="selectedForm()">
                <div class="row">
                    <div
                        class="col-12"
                        :class="{ 'col-lg-6': !(field as Record<string, any>).is_fullsize }"
                        v-for="field in (selectedForm() as any).fields"
                        :key="`form-item-${field.id}`"
                    >
                        <wx-form-control :title="useLocalesStore().selectLocalizedValue(field.title)">
                            <template v-if="field.type.value === 'text'">
                                <wx-input :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)" />
                            </template>
                            <template v-if="field.type.value === 'date'">
                                <wx-input
                                    type="date"
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                />
                            </template>
                            <template v-if="field.type.value === 'email'">
                                <wx-input
                                    type="email"
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                />
                            </template>
                            <template v-if="field.type.value === 'tel'">
                                <wx-input
                                    type="tel"
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                />
                            </template>
                            <template v-if="field.type.value === 'textarea'">
                                <wx-textarea :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)" />
                            </template>
                            <template v-if="field.type.value === 'select'">
                                <wx-select
                                    :placeholder="useLocalesStore().selectLocalizedValue(field.placeholder)"
                                    :options="prepareOptions(field.options)"
                                />
                            </template>
                        </wx-form-control>
                    </div>
                </div>
                <wx-buttons class="justify-content-end">
                    <wx-button theme="success" type="button">
                        {{ useLocalesStore().selectLocalizedValue(selectedForm().options['design.submit-button-text'], $t('send')) }}
                    </wx-button>
                </wx-buttons>
            </div>
        </wx-grid-col>
    </wx-grid>

    <wx-dialog :size="1400" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-form-control :title="$t('pre-heading')">
            <wx-input localized v-model="component.content.pre_heading" />
        </wx-form-control>

        <wx-heading v-model="component.content.heading" />

        <wx-form-control :title="$t('text')">
            <wx-textarea localized v-model="component.content.text" />
        </wx-form-control>

        <wx-grid>
            <wx-grid-col :md="3">
                <wx-form-control :title="$t('inbox.forms.select-form')">
                    <wx-select
                        v-if="forms"
                        v-model="component.content.form_id"
                        :options="forms.map((form) => ({ label: useLocalesStore().selectLocalizedValue(form.title), value: form.id }))"
                    />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
