<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import WxAction from '../WxAction/WxAction.vue';
import WxDropdown from '../WxDropdown/WxDropdown.vue';
import type { WxActionsProps } from './types';

const props = withDefaults(defineProps<WxActionsProps>(), {
    type: 'default',
    align: 'start',
});

const actions = ref<HTMLElement>();
const actionsDesktop = ref<HTMLElement>();
const actionsMobile = ref<HTMLElement>();
const isAdaptiveView = ref<boolean>(false);

const elementWidth = (el) => {
    const computedStyle = getComputedStyle(el);

    return el.clientWidth - (parseFloat(computedStyle.paddingLeft) + parseFloat(computedStyle.paddingRight));
};

const calculateDimensions = () => {
    const actionsDesktopWidth = actionsDesktop.value?.offsetWidth || 0;

    const parentEl = props.parent?.value || actions.value?.parentElement;

    isAdaptiveView.value = elementWidth(parentEl) <= actionsDesktopWidth;
};

const resizeObserver: ResizeObserver = new ResizeObserver(() => calculateDimensions());

onMounted(() => {
    if (props.type === 'adaptive') {
        resizeObserver.observe(actions.value);
    }
});
onUnmounted(() => (props.type === 'adaptive' ? resizeObserver?.disconnect() : null));
</script>

<template>
    <div
        v-show="type === 'adaptive'"
        class="wx-actions d-flex"
        :class="[`wx-actions--${props.align}`, { 'justify-content-end' : props.align === 'end', 'wx-actions--adaptive': isAdaptiveView }]"
        ref="actions"
    >
        <div class="wx-actions__desktop" ref="actionsDesktop">
            <slot name="desktop"></slot>
        </div>
        <div class="wx-actions__mobile" ref="actionsMobile">
            <wx-dropdown>
                <template #trigger>
                    <template v-if="$slots['mobile-trigger']">
                        <slot name="mobile-trigger"></slot>
                    </template>
                    <wx-action v-else type="more">...</wx-action>
                </template>
                <template #body>
                    <slot name="mobile"></slot>
                </template>
            </wx-dropdown>
        </div>
    </div>

    <div v-show="type === 'default'" class="d-flex align-items-center gap-6">
        <slot />
    </div>
</template>

<style scoped lang="scss">
.wx-actions {
    &__desktop {
        .wx-actions--adaptive & {
            z-index: -1;
            position: absolute;
            visibility: hidden;
            height: 0;
            overflow: hidden;
        }

        &:has(.wx-action) {
            display: flex;
            align-items: center;
            gap: 6px;
        }
    }

    &__mobile {
        .wx-actions:not(.wx-actions--adaptive) & {
            display: none;
        }
    }
}
</style>
