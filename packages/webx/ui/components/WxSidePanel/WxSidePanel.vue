<script setup lang="ts">
import { computed } from 'vue';
import WxButton from '../WxButton/WxButton.vue';
import WxCard from '../WxCard/WxCard.vue';
import WxIcon from '../WxIcon/WxIcon.vue';
import WxOverlay from '../WxOverlay/WxOverlay.vue';
import type { WxSidePanelProps } from './types';

const props = withDefaults(defineProps<WxSidePanelProps>(), {
    modelValue: false,
    size: 400,
    persistent: false,
    closeOnOverlay: true,
    closeOnEscape: true,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void
}>();

// Передаем размер через CSS-переменную
const panelStyles = computed(() => ({
    '--wx-side-panel-size': `${props.size}px`
}));

const close = () => emit('update:modelValue', false);
</script>

<template>
    <wx-overlay
        :model-value="props.modelValue"
        :persistent="props.persistent"
        :close-on-overlay="props.closeOnOverlay"
        :close-on-escape="props.closeOnEscape"
        :lock-scroll="true"
        overlay-class="wx-side-panel-overlay"
        content-class="wx-side-panel-container"
        overlay-transition="wx-side-panel-overlay-fade"
        content-transition="wx-side-panel-slide"
        :content-style="panelStyles"
        @update:modelValue="(v) => emit('update:modelValue', v)"
    >
        <wx-card :title="title">
            <template v-if="$slots.sidebar" #sidebar><slot name="sidebar" /></template>
            <template v-if="$slots.footer" #footer><slot name="footer" /></template>

            <div class="wx-side-panel-content">
                <slot />
            </div>

            <template #actions>
                <div class="d-flex align-items-center gap-16">
                    <div v-if="$slots.actions">
                        <slot name="actions" />
                    </div>
                    <wx-button square theme="blank" @click="close">
                        <wx-icon name="x" />
                    </wx-button>
                </div>
            </template>
        </wx-card>
    </wx-overlay>
</template>

<style lang="scss">
.wx-side-panel-overlay {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    z-index: 1000;
    padding: 8px;

    overflow-y: auto;
    overflow-x: hidden;
}

.wx-side-panel-container {
    position: absolute;
    top: 8px;
    right: 8px;
    bottom: 8px;
    width: var(--wx-side-panel-size);
    max-width: calc(100% - 16px);
    display: flex;
    flex-direction: column;
    margin-block: auto;

    .wx-card {
        display: flex;
        flex-direction: column;
        flex-grow: 1;

        &:has(.wx-card__sidebar) {
            @include media-breakpoint-down(md) {
                .wx-card__body {
                    position: relative;
                    flex-grow: 1;

                    &__inner {
                        position: absolute;
                        inset: 0;
                        @include scrollbar-colored-y
                    }
                }

                .wx-card__sidebar {
                    flex-grow: 0!important;
                }
            }
            @include media-breakpoint-up(md) {
                .wx-card__body {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    flex-grow: 1;

                    &__inner {
                        display: flex;
                        flex-direction: column;
                        flex-grow: 1;

                        > * {
                            flex-grow: 1;
                        }
                    }
                }

                .wx-card__content {
                    position: relative;

                    .wx-side-panel-content {
                        position: absolute;
                        inset: 0 16px;
                        @include scrollbar-colored-y
                    }
                }

            }
        }
        &:not(:has(.wx-card__sidebar)) {

            .wx-card__body {
                position: relative;
                display: flex;
                flex-direction: column;
                flex-grow: 1;

                &__inner {
                    display: flex;
                    flex-direction: column;
                    flex-grow: 1;
                }
            }


            .wx-card__content {
                position: relative;
                flex-grow: 1;

                .wx-side-panel-content {
                    position: absolute;
                    inset: 0 16px;
                    @include scrollbar-colored-y
                }
            }
        }
    }
}

.wx-side-panel-overlay-fade-enter-active,
.wx-side-panel-overlay-fade-leave-active {
    transition: opacity 0.3s ease;
}
.wx-side-panel-overlay-fade-enter-from,
.wx-side-panel-overlay-fade-leave-to {
    opacity: 0;
}

.wx-side-panel-slide-enter-active {
    transition: transform 0.4s var(--wx-easing);
}
.wx-side-panel-slide-leave-active {
    transition: transform 0.3s ease-in;
}
.wx-side-panel-slide-enter-from,
.wx-side-panel-slide-leave-to {
    transform: translateX(100%);
}
</style>
