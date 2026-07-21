<script setup lang="ts">
import { api } from '@/utils';
import WxLoader from '../../components/WxLoader/WxLoader.vue';
import type { WxLayoutBlankProps } from './types';

const props = withDefaults(defineProps<WxLayoutBlankProps>(), {
    size: 'default',
});

</script>

<template>
    <div class="wx-blank p-16">
        <slot />
        <wx-loader v-if="api.isLoading.value" />
    </div>
</template>

<style scoped lang="scss">
.wx-blank {
    min-height: 100dvh;
}
</style>
