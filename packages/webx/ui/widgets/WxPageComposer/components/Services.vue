<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { useLocalesStore } from '@/stores';
import type { Service } from '@/templates/Services';
import type { ApiResponse } from '@/types/api';
import { api } from '@/utils';
import type { WxPageComposerContentProps } from '../types';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});

const editMode = ref<boolean>(props.edit);
const services = ref<Service[]>([]);

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);

onMounted(() => {
    api.get<ApiResponse<Service[]>>('services/list?limit=3').then((response) => {
        services.value = response.data;
    });
});
</script>

<template>
    <div v-if="services?.length > 0" class="services">
        <div v-for="service in services" :key="service.id" class="d-flex flex-column gap-16 rounded border bg-white p-16">
            <div class="h4 m-0">{{ useLocalesStore().selectLocalizedValue(service.title) }}</div>
            <div class="text-secondary">{{ useLocalesStore().selectLocalizedValue(service.details) }}</div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.text-pservice {
    > *:last-child {
        margin-bottom: 0 !important;
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

.services {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 20px;

    @include media-breakpoint-up(md) {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
