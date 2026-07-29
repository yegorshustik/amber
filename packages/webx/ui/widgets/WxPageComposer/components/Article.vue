<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { WxGrid, WxGridCol, WxHeading, WxInput, WxSelect, WxTextarea, WxTruncate } from '@/ui';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import WxFormControl from '../../../components/WxFormControl/WxFormControl.vue';
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
    <wx-truncate :enabled="component.content.is_truncated === '1'">
        <wx-page-composer-canvas v-model:children="component.children" />
    </wx-truncate>

    <wx-dialog :size="400" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-grid>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('hide-toc')">
                    <wx-select
                        v-model="component.content.hide_toc"
                        :options="[
                            { label: $t('no'), value: '0' },
                            { label: $t('yes'), value: '1' },
                        ]"
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
