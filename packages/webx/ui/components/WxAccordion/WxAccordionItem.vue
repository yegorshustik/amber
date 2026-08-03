<script setup lang="ts">
import { ref, inject, watch } from 'vue';
import WxIcon from '../WxIcon/WxIcon.vue';

const props = defineProps<{
    title: string;
    active?: boolean;
}>();

const emit = defineEmits(['update:active']);

const activeLocal = ref(props.active || false);

const toggleAccordionItem = inject<any>('toggleAccordionItem');

const handleToggle = () => {
    if (toggleAccordionItem) {
        toggleAccordionItem(activeLocal);
    } else {
        activeLocal.value = !activeLocal.value;
    }
    emit('update:active', activeLocal.value);
};

watch(
    () => props.active,
    (newVal) => {
        activeLocal.value = !!newVal;
    },
);
</script>

<template>
    <div class="wx-accordion-item" :class="{ active: activeLocal }">
        <button type="button" class="wx-accordion-header d-flex align-items-center fw-semibold fs-16px px-16 py-12 text-start" @click="handleToggle">
            {{ title }}

            <wx-icon name="angle-down" width="10" class="wx-accordion-icon ms-auto" />
        </button>

        <div class="wx-accordion-collapse" :class="{ 'is-active': activeLocal }">
            <div class="wx-accordion-body">
                <div class="p-16">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-accordion-item {
    border-bottom: 1px solid var(--wx-border-color);

    &:last-child {
        border-bottom: none;
    }
}

.wx-accordion-header {
    width: 100%;
    cursor: pointer;
    border: none;
    background-color: var(--wx-light-gray);
    transition:
        color 200ms var(--wx-easing),
        background-color 200ms var(--wx-easing);

    &:hover {
        background-color: var(--wx-light-gray-hover);
    }

    .wx-accordion-item.active & {
        background-color: var(--wx-primary-active);
        color: var(--wx-white);
    }
}

.wx-accordion-icon {
    transition: transform 200ms var(--wx-easing);

    .wx-accordion-item.active & {
        transform: rotate(-180deg);
    }
}

.wx-accordion-collapse {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.3s var(--wx-easing);

    &.is-active {
        grid-template-rows: 1fr;
    }
}

.wx-accordion-body {
    overflow: hidden;
}
</style>
