<script setup lang="ts">
import type { WxListGroupProps } from './types';

const { title = null } = defineProps<WxListGroupProps>();
</script>

<template>
    <div class="wx-list-group rounded bg-white p-4 shadow d-flex flex-column gap-4">
        <div v-if="title" class="fw-semibold fs-18px px-16 py-8">
            {{ title }}
        </div>
        <slot></slot>
    </div>
</template>

<style scoped lang="scss"></style>
