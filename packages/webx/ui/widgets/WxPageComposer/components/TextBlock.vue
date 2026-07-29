<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import { WxAction, WxActions, WxCard, WxGrid, WxGridCol, WxInput, WxSelect, WxTextarea } from '@/ui';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import WxFormControl from '../../../components/WxFormControl/WxFormControl.vue';
import WxHeading from '../../WxHeading/WxHeading.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});
const emit = defineEmits(['update:edit']);

const component = ref<WxPageComposerComponent>(props.component);
const editMode = ref<boolean>(props.edit);
const additionalVisible = ref<boolean>(false);

watch(
    () => props.edit,
    (value) => {
        editMode.value = value;

        if (value) {
            additionalVisible.value = isAdditionalFilled();
        }
    },
);

const isAdditionalFilled = () => {
    const isFilledPreHeading = !!useLocalesStore().selectLocalizedValue(component.value.content.additional?.pre_heading);
    const isFilledHeading = !!useLocalesStore().selectLocalizedValue(component.value.content.additional?.heading?.text);
    const isFilledText = !!useLocalesStore().selectLocalizedValue(component.value.content.additional?.text);

    return isFilledPreHeading || isFilledHeading || isFilledText;
};
</script>

<template>
    <div :class="['d-flex flex-column gap-16', component.content.reverse == '1' ? 'flex-lg-row-reverse' : 'flex-lg-row']">
        <div class="flex-grow-1 flex-basis-0">
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
                v-if="useLocalesStore().selectLocalizedValue(component.content.text)"
                v-html="useLocalesStore().selectLocalizedValue(component.content.text)"
            />

            <wx-button class="mt-24" theme="primary" v-if="useLocalesStore().selectLocalizedValue(component.content.button)">
                {{ useLocalesStore().selectLocalizedValue(component.content.button) }}
            </wx-button>
        </div>
        <div class="flex-grow-1 flex-basis-0" v-if="isAdditionalFilled()">
            <div class="bg-lightest rounded border p-16">
                <div v-if="useLocalesStore().selectLocalizedValue(component.content.additional?.pre_heading)" class="fs-14px text-uppercase mb-6">
                    {{ useLocalesStore().selectLocalizedValue(component.content.additional.pre_heading) }}
                </div>

                <wx-heading
                    v-if="useLocalesStore().selectLocalizedValue(component.content.additional?.heading?.text)"
                    preview
                    v-model="component.content.additional.heading"
                    class="mb-16 mt-0"
                />

                <div
                    v-if="useLocalesStore().selectLocalizedValue(component.content.additional?.text)"
                    v-html="useLocalesStore().selectLocalizedValue(component.content.additional.text)"
                />

                <wx-button class="mt-24" theme="primary" v-if="useLocalesStore().selectLocalizedValue(component.content.additional.button)">
                    {{ useLocalesStore().selectLocalizedValue(component.content.additional.button) }}
                </wx-button>
            </div>
        </div>
    </div>

    <wx-dialog :size="1200" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-form-control :title="$t('pre-heading')">
            <wx-input localized v-model="component.content.pre_heading" />
        </wx-form-control>

        <wx-heading v-model="component.content.heading" />

        <wx-form-control :title="$t('text')">
            <wx-textarea v-model="component.content.text" localized />
        </wx-form-control>

        <wx-grid>
            <wx-grid-col :sm="6">
                <wx-form-control :title="$t('cta-button.button-text')">
                    <wx-input localized v-model="component.content.button" />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :sm="6">
                <wx-form-control :title="$t('cta-button.button-link')">
                    <wx-input v-model="component.content.button_url" />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <wx-card :title="$t('additional')">
            <template #actions>
                <wx-actions>
                    <wx-action :type="`${additionalVisible ? 'eye-slash' : 'eye'}`" @click="additionalVisible = !additionalVisible" />
                </wx-actions>
            </template>

            <template v-if="additionalVisible">
                <wx-grid>
                    <wx-grid-col :md="4">
                        <wx-form-control :title="$t('reverse')">
                            <wx-select
                                v-model="component.content.reverse"
                                :options="[
                                    { label: $t('no'), value: '0' },
                                    { label: $t('yes'), value: '1' },
                                ]"
                            />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>

                <wx-form-control :title="$t('pre-heading')">
                    <wx-input localized v-model="component.content.additional.pre_heading" />
                </wx-form-control>

                <wx-heading v-model="component.content.additional.heading" />

                <wx-form-control :title="$t('text')">
                    <wx-textarea v-model="component.content.additional.text" localized />
                </wx-form-control>

                <wx-grid>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('cta-button.button-text')">
                            <wx-input localized v-model="component.content.additional.button" />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('cta-button.button-link')">
                            <wx-input v-model="component.content.additional.button_url" />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>
            </template>
        </wx-card>

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
