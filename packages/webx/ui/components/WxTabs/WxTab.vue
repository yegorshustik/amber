<script setup lang="ts">
import { inject, onMounted, computed, onUnmounted } from 'vue';
import type { WxTabsContext } from './types';

interface Props {
    name: string;
    id?: string;
}

const props = defineProps<Props>();

const tabId = props.id || `tab-${Math.random().toString(36).substring(2, 9)}`;

const context = inject<WxTabsContext>('tabsContext');

if (!context) {
    throw new Error('WxTab must be used inside WxTabs');
}

onMounted(() => {
    context.registerTab({ id: tabId, name: props.name });
});
onUnmounted(() => {
    context.unregisterTab(tabId);
});
// Используем context.active
const isActive = computed(() => context.active.value === tabId);
</script>

<template>
    <div v-show="isActive" class="wx-tab-panel">
        <slot />
    </div>
</template>
