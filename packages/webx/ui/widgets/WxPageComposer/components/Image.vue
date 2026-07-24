<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import type { WxLocalizedValue } from '@/types/locale';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import WxFormControl from '../../../components/WxFormControl/WxFormControl.vue';
import WxGrid from '../../../components/WxGrid/WxGrid.vue';
import WxGridCol from '../../../components/WxGrid/WxGridCol.vue';
import WxIcon from '../../../components/WxIcon/WxIcon.vue';
import WxInput from '../../../components/WxInput/WxInput.vue';
import type { WxSingleImage } from '../../../components/WxInputImage/types';
import WxInputImage from '../../../components/WxInputImage/WxInputImage.vue';
import WxLocales from '../../../components/WxLocales/WxLocales.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';

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
    <wx-locales v-if="component.content.image">
        <template #item="{ locale }">
            <template v-if="component.content.image && component.content.image[locale.code]">
                <wx-input-image v-model="component.content.image[locale.code]" preview />

                <div v-if="component.content.signature && component.content.signature[locale.code]" class="fst-italic mt-12 text-center">
                    {{ component.content.signature[locale.code] }}
                </div>
            </template>
            <template v-else>
                <div class="wx-image-placeholder text-secondary mx-auto rounded border bg-white p-32"><wx-icon name="image" /></div>
            </template>
        </template>
    </wx-locales>
    <div v-else>
        <div class="wx-image-placeholder text-secondary mx-auto rounded border bg-white p-32"><wx-icon name="image" /></div>
    </div>

    <wx-dialog :title="$t('edit')" :size="800" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-grid>
            <wx-grid-col :sm="3">
                <wx-form-control :title="$t('image')">
                    <wx-input-image v-model="component.content.image as WxLocalizedValue<WxSingleImage>" localized />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :sm="9">
                <wx-form-control :title="$t('signature')">
                    <wx-input v-model="component.content.signature as WxLocalizedValue" localized />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">
.wx-image-placeholder {
    max-width: 300px;
}

.wx-image-preview {
    border-radius: var(--wx-border-radius);

    :deep(img) {
        width: 100%;
        border-radius: var(--wx-border-radius);
        max-height: 400px;
        object-fit: cover;
    }
}
</style>
