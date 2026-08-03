<script setup lang="ts">
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { $t } from '@/locales';
import { WxButton } from '@/ui';
import type { WxTruncateProps } from './types';

const props = withDefaults(defineProps<WxTruncateProps>(), {
    enabled: true,
    maxHeight: 200,
});

const contentEl = ref();
const triggerEl = ref();
const truncateEl = ref();
const bodyEl = ref();
const isEnabled = ref(props.enabled);

const truncated = ref<boolean>(false);
const expanded = ref<boolean>(false);

let observer = null;

onMounted(() => {
    if (bodyEl.value) {
        observer = new MutationObserver(init);

        observer.observe(bodyEl.value, {
            childList: true,
            subtree: true,
            characterData: true,
        });
    }
});
onBeforeUnmount(() => {
    if (observer) {
        observer.disconnect();
    }
});
watch(
    () => props.enabled,
    (value) => {
        isEnabled.value = value;
        init();
    },
);
watch(
    () => expanded.value,
    (value) => {
        if (!value) {
            truncateEl.value.classList.add('wx-truncate--truncated');
            contentEl.value.style.height = `${props.maxHeight}px`;
            triggerEl.value.classList.remove('wx-truncate__trigger--hidden');
        }
    },
);

const init = () => {
    setTimeout(() => {
        if (!isEnabled.value) return;
        const contentHeight = parseInt(bodyEl.value.clientHeight);
        truncated.value = contentHeight > props.maxHeight;
    }, 10);
};
</script>

<template>
    <div
        v-if="isEnabled"
        ref="truncateEl"
        class="wx-truncate"
        :class="{ 'wx-truncate--expanded': expanded, 'wx-truncate--truncated': truncated }"
        :style="[`--wx-truncate-max-height: ${maxHeight}px;`]"
    >
        <div ref="contentEl" class="wx-truncate__content">
            <div ref="bodyEl" class="wx-truncate__body px-2 py-32">
                <slot />
            </div>
        </div>
        <div ref="triggerEl" class="truncate__trigger truncate__trigger--hidden mt-24 text-end">
            <wx-button
                theme="outline-info"
                size="sm"
                @click="
                    () => {
                        expanded = !expanded;
                        truncated = !truncated;
                    }
                "
            >
                {{ $t(expanded ? 'text-collapse' : 'text-expand') }}
            </wx-button>
        </div>
    </div>
    <template v-else>
        <slot />
    </template>
</template>

<style scoped lang="scss">
.wx-truncate {
    &__content {
        position: relative;

        .wx-truncate--truncated & {
            overflow: hidden;
            mask-image: linear-gradient(to bottom, black calc(100% - 100px), transparent 100%);
            height: var(--wx-truncate-max-height) !important;
        }

        .wx-truncate--expanded & {
            height: auto !important;
            mask-image: none;
        }
    }

    &__trigger {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        &--hidden {
            display: none;
        }
    }
}
</style>
