<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { WxFormControl, WxGrid, WxGridCol, WxHeading, WxInput, WxInputImage, WxSelect, WxTextarea } from '@/ui';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';

import 'swiper/css';
import 'swiper/css/navigation';
import { useLocalesStore } from '@/stores';

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
    <div class="wx-hero bg-lightest rounded p-16">
        <div class="wx-hero__body d-flex flex-column justify-content-center">
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
        <div class="wx-hero__promo d-flex align-items-center justify-content-end">
            <wx-button theme="primary" v-if="useLocalesStore().selectLocalizedValue(component.content.button)">
                {{ useLocalesStore().selectLocalizedValue(component.content.button) }}
            </wx-button>
        </div>
    </div>

    <wx-dialog :size="1400" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-form-control :title="$t('pre-heading')">
            <wx-input localized v-model="component.content.pre_heading" />
        </wx-form-control>

        <wx-heading v-model="component.content.heading" />

        <wx-form-control :title="$t('text')">
            <wx-textarea localized v-model="component.content.text" />
        </wx-form-control>

        <h3>{{ $t('cta-button.button') }}</h3>
        <wx-grid>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('cta-button.button-text')">
                    <wx-input localized v-model="component.content.button" />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('cta-button.button-link')">
                    <wx-input v-model="component.content.url" />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">
.wx-hero {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;

    @include media-breakpoint-up(lg) {
        grid-template-columns: 1fr 1fr;
    }

    &__promo {
        img {
            height: 200px;
        }
    }
}
</style>
