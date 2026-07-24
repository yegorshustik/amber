<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import { WxAction, WxActions, WxCard, WxInput, WxTextarea } from '@/ui';
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
    <div class="d-flex flex-column flex-lg-row gap-16">
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

        <wx-card :title="$t('additional')">
            <template #actions>
                <wx-actions>
                    <wx-action :type="`${additionalVisible ? 'eye-slash' : 'eye'}`" @click="additionalVisible = !additionalVisible" />
                </wx-actions>
            </template>

            <template v-if="additionalVisible">
                <wx-form-control :title="$t('pre-heading')">
                    <wx-input localized v-model="component.content.additional.pre_heading" />
                </wx-form-control>

                <wx-heading v-model="component.content.additional.heading" />

                <wx-form-control :title="$t('text')">
                    <wx-textarea v-model="component.content.additional.text" localized />
                </wx-form-control>
            </template>
        </wx-card>

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
