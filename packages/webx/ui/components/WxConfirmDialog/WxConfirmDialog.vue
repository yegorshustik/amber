<script setup lang="ts">
import WxButton from '../WxButton';
import WxButtons from '../WxButtons';
import WxDialog from '../WxDialog';
import type { WxConfirmDialogProps } from './types';

const props = withDefaults(defineProps<WxConfirmDialogProps>(), {
    modelValue: true,
    title: '',
    confirmText: 'Yes',
    cancelText: 'No',
    size: 450,
    persistent: false,
    closeOnOverlay: true,
    closeOnEscape: true,
});

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'cancel'): void;
    (e: 'update:modelValue', value: boolean): void;
}>();

const onConfirm = () => emit('confirm');
const onCancel = () => emit('cancel');

const onUpdateModelValue = (v: boolean) => {
    emit('update:modelValue', v);
    if (!v) emit('cancel');
};
</script>

<template>
    <wx-dialog
        :model-value="props.modelValue"
        :title="props.title"
        :size="props.size"
        :persistent="props.persistent"
        :close-on-overlay="props.closeOnOverlay"
        :close-on-escape="props.closeOnEscape"
        @update:modelValue="onUpdateModelValue"
    >
        <div class="text-center h3 mb-24 fw-semibold fs-24px" v-text="props.message" />

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button theme="blank" @click="onCancel">
                    {{ props.cancelText }}
                </wx-button>
                <wx-button theme="primary" @click="onConfirm" class="w-100 max-w-128">
                    {{ props.confirmText }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
