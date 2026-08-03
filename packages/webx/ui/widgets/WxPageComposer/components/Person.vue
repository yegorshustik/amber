<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { WxFormControl, WxGrid, WxGridCol, WxHeading, WxInput, WxInputImage, WxTextarea } from '@/ui';
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
    <wx-grid>
        <wx-grid-col :md="3">
            <img v-if="component.content.image?.src?.url" :src="component.content.image?.src?.url" alt="" class="w-100 rounded" />
        </wx-grid-col>
        <wx-grid-col :md="9">
            <div v-if="useLocalesStore().selectLocalizedValue(component.content.job)" class="fs-14px text-uppercase mb-6">
                {{ useLocalesStore().selectLocalizedValue(component.content.job) }}
            </div>
            <h3 v-if="useLocalesStore().selectLocalizedValue(component.content.name)">
                {{ useLocalesStore().selectLocalizedValue(component.content.name) }}
            </h3>
            <div
                class=""
                v-if="useLocalesStore().selectLocalizedValue(component.content.about)"
                v-html="useLocalesStore().selectLocalizedValue(component.content.about)"
            />
            <wx-button v-if="component.content.linkedin" theme="primary">
                Linkedin
            </wx-button>
        </wx-grid-col>
    </wx-grid>

    <wx-dialog :size="1400" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <template #sidebar>
            <wx-form-control :title="$t('image')">
                <wx-input-image v-model="component.content.image" />
            </wx-form-control>
        </template>

        <wx-form-control :title="$t('job')">
            <wx-input localized v-model="component.content.job" />
        </wx-form-control>

        <wx-form-control :title="$t('name')">
            <wx-input localized v-model="component.content.name" />
        </wx-form-control>

        <wx-form-control :title="$t('text')">
            <wx-textarea localized v-model="component.content.about" wysiwyg />
        </wx-form-control>

        <wx-form-control title="Linkedin">
            <wx-input v-model="component.content.linkedin" />
        </wx-form-control>

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
