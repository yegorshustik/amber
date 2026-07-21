<script setup lang="ts">
import { computed } from 'vue';
import WxButton from '../WxButton';
import WxCard from '../WxCard';
import WxForm from '../WxForm/WxForm.vue';
import WxIcon from '../WxIcon';
import WxOverlay from '../WxOverlay';
import type { WxDialogProps } from './types';

const props = withDefaults(defineProps<WxDialogProps>(), {
    modelValue: false,
    size: 500,
    persistent: false,
    closeOnOverlay: true,
    closeOnEscape: true,
});

const emit = defineEmits([
    'update:modelValue',
    'close',
    'success'
]);

const dialogStyles = computed(() => ({
    '--wx-dialog-size': `${props.size}px`,
}));

const close = () => {
    emit('update:modelValue', false);
    emit('close');
};
</script>

<template>
    <wx-overlay
        :model-value="props.modelValue"
        :persistent="props.persistent"
        :close-on-overlay="props.closeOnOverlay"
        :close-on-escape="props.closeOnEscape"
        :lock-scroll="true"
        overlay-class="wx-dialog-overlay"
        content-class="wx-dialog-container"
        overlay-transition="wx-dialog-overlay-fade"
        content-transition="wx-dialog-content-slide"
        :content-style="dialogStyles"
        :z-index="props.zIndex || null"
        @update:modelValue="
            (v) => {
                emit('update:modelValue', v);
                emit('close');
            }
        "
    >
        <wx-form v-if="props.action" :action="props.action" :method="props.method || 'get'" @success="(response) => emit('success', response)">
            <wx-card :title="title" class="wx-dialog">
                <template v-if="$slots.sidebar" #sidebar>
                    <slot name="sidebar" />
                </template>

                <template v-if="$slots.footer" #footer>
                    <slot name="footer" />
                </template>

                <slot />

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
        </wx-form>


        <wx-card v-else :title="title" class="wx-dialog">
            <template v-if="$slots.sidebar" #sidebar>
                <slot name="sidebar" />
            </template>

            <template v-if="$slots.footer" #footer>
                <slot name="footer" />
            </template>

            <slot />

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
.wx-dialog {
    .wx-card__footer {
        z-index:100;
        position: sticky;
        bottom: -16px;
    }
}

.wx-dialog-overlay {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    z-index: 1000;
    padding: 16px;
    overflow: auto;
    @include scrollbar;
}

.wx-dialog-container {
    width: var(--wx-dialog-size);
    max-width: 100%;

    display: flex;
    flex-direction: column;
    margin-block: auto;
}

.wx-dialog-body {
    flex: 1;
}
.wx-dialog-overlay-fade-enter-active,
.wx-dialog-overlay-fade-leave-active {
    transition: opacity 0.3s ease;
}
.wx-dialog-overlay-fade-enter-from,
.wx-dialog-overlay-fade-leave-to {
    opacity: 0;
}
.wx-dialog-content-slide-enter-active {
    transition: all 0.3s var(--wx-easing);
}
.wx-dialog-content-slide-leave-active {
    transition: all 0.2s ease-in;
}

.wx-dialog-content-slide-enter-from {
    opacity: 0;
    transform: translateY(30px) scale(0.98);
}
.wx-dialog-content-slide-leave-to {
    opacity: 0;
    transform: translateY(15px);
}
</style>
