<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { WxLocalizedValue } from '@/types/locale';
import { WxFormControl, WxGrid, WxGridCol, WxInput, WxSelect, WxTextarea } from '@/ui';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import WxHeading from '../../WxHeading/WxHeading.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';
import WxPageComposerCanvas from '../WxPageComposerCanvas.vue';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});
const emit = defineEmits(['update:edit']);

const component = ref<WxPageComposerComponent>(props.component);
const editMode = ref<boolean>(props.edit);

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);
</script>

<template>
    <div class="d-flex flex-column gap-32">
        <div class="d-flex flex-column flex-lg-row column-gap-32">
            <div class="d-flex flex-column justify-content-center flex-grow-1 flex-basis-0">
                <div v-if="useLocalesStore().selectLocalizedValue(component.content.pre_heading)" class="fs-14px text-uppercase mb-6">
                    {{ useLocalesStore().selectLocalizedValue(component.content.pre_heading) }}
                </div>
                <wx-heading
                    v-if="useLocalesStore().selectLocalizedValue(component.content.heading?.text)"
                    preview
                    v-model="component.content.heading"
                    class="mb-16 mt-0"
                    :style="`max-width: ${component.content.heading_max_characters || 1000}ch`"
                />
            </div>

            <div
                class="flex-grow-1 flex-basis-0"
                v-if="useLocalesStore().selectLocalizedValue(component.content.text)"
                v-html="useLocalesStore().selectLocalizedValue(component.content.text)"
            />
        </div>
        <wx-page-composer-canvas v-model:children="component.children" />
    </div>
    <wx-dialog :size="1400" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-form-control :title="$t('pre-heading')">
            <wx-input localized v-model="component.content.pre_heading" />
        </wx-form-control>

        <wx-heading v-model="component.content.heading" />

        <wx-form-control :title="$t('text')">
            <wx-textarea wysiwyg v-model="component.content.text" localized />
        </wx-form-control>

        <wx-grid>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('color')">
                    <wx-select
                        v-model="component.content.color"
                        :options="[
                            { label: $t('default'), value: 'default' },
                            { label: $t('section-navy'), value: 'navy' },
                            { label: $t('section-cream'), value: 'cream' },
                            { label: $t('section-paper'), value: 'paper' },
                        ]"
                    />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('heading-max-characters')">
                    <wx-input v-model="component.content.heading_max_characters" />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('id')">
                    <wx-input v-model="component.content.id" />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
