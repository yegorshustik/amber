<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { WxLocalizedValue } from '@/types/locale';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import WxTextarea from '../../../components/WxTextarea/WxTextarea.vue';
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
    <div class="text-preview"
        v-html="
            useLocalesStore().selectLocalizedValue(
                component.content.text as WxLocalizedValue,
                '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
            )
        "
    />

    <wx-dialog :title="$t('edit')" v-model="editMode" :size="1000" @close="() => emit('update:edit', false)">
        <wx-textarea class="mb-16" v-model="component.content.text" wysiwyg localized />

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">
.text-preview {
    >*:last-child {
        margin-bottom: 0!important;
    }

    :deep() {
        img {
            max-width: 100%;
            width: auto;
            height: auto;
            border-radius: var(--wx-border-radius);
        }
    }
}
</style>
