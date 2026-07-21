<script setup lang="ts">
import WxButton from '../WxButton';
import WxButtons from '../WxButtons';
import WxDialog from '../WxDialog';
import type { WxAlertDialogProps } from './types';

const props = withDefaults(defineProps<WxAlertDialogProps>(), {
    modelValue: true,
    title: '',
    message: '',
    okText: 'OK',
    size: 500,
    persistent: false,
    closeOnOverlay: true,
    closeOnEscape: true,
});

const emit = defineEmits<{
    (e: 'ok'): void;
    (e: 'cancel'): void;
    (e: 'update:modelValue', value: boolean): void;
}>();

const onOk = () => emit('ok');

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
            <wx-buttons class="justify-content-center">
                <wx-button theme="primary" @click="onOk" class="w-100 max-w-128">
                    {{ props.okText }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
