<script setup lang="ts">
import { ref, provide } from 'vue';
import type { ValidationError } from '@/types/api';
import { api, wxSnackbar } from '@/utils';
import type { WxFormProps } from '../WxForm/types';

const props = withDefaults(defineProps<WxFormProps>(), {
    method: 'post',
});

const errors = ref<ValidationError['errors']>({});
const emit = defineEmits(['success', 'error']);

provide('wx-form-errors', errors);

const handleSubmit = async (e: Event) => {
    const form = e.target as HTMLFormElement;
    const data = new FormData(form);

    errors.value = {};

    try {
        const method = props.method || 'post';

        const response = await api[method](props.action, data);

        emit('success', response);
    } catch (err: any) {
        if (err.status === 422) {
            errors.value = err.errors as ValidationError['errors'];
            emit('error', err as ValidationError);

            wxSnackbar(err.message, { type: 'danger' });

        } else {
            console.error('Submit error:', err);
        }
    } finally {
    }
};
</script>

<template>
    <form @submit.prevent="handleSubmit" :method="props.method" :action="props.action" novalidate v-bind="$attrs">
        <slot :errors="errors"></slot>
    </form>
</template>

<style scoped lang="scss"></style>
