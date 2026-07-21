<script setup lang="ts">
import { ref, provide } from 'vue';
import type { WxTabItem, WxTabsContext } from './types';

interface Props {
    type?: 'horizontal' | 'vertical';
    active?: string;
}

const props = defineProps<Props>();

const active = ref<string | null>(props.active || null);
const tabs = ref<WxTabItem[]>([]);

const registerTab = (tab: WxTabItem) => {
    if (!tabs.value.some((t) => t.id === tab.id)) {
        tabs.value.push(tab);
    }

    if (!active.value && tabs.value.length === 1) {
        active.value = tab.id;
    }
};

// Используем setActive
const setActive = (id: string) => {
    active.value = id;
};
const unregisterTab = (id: string) => {
    tabs.value = tabs.value.filter((tab) => tab.id !== id);
    if (active.value === id) {
        active.value = tabs.value[0]?.id || null;
    }
};
provide<WxTabsContext>('tabsContext', {
    type: props.type,
    active,
    registerTab,
    unregisterTab,
    setActive,
});
</script>

<template>
    <div class="wx-tabs" :class="`wx-tabs--${type}`">
        <div class="wx-tabs__nav">
            <div class="wx-tabs__nav__inner">
                <button type="button" v-for="tab in tabs" :key="tab.id" class="wx-tabs__button" :class="{ active: tab.id === active }" @click="setActive(tab.id)">
                    {{ tab.name }}
                </button>
            </div>
        </div>

        <div class="wx-tabs__content">
            <slot />
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-tabs {
    --wx-tabs-nav-top-offset: 0px;

    @include media-breakpoint-up(lg) {
        .layout--head-hidden & {
            --wx-tabs-nav-top-offset: calc(var(--wx-page-heading-height) + var(--wx-layout-padding));
        }
        .layout:not(.layout--head-hidden) & {
            --wx-tabs-nav-top-offset: calc(var(--wx-layout-header-height) + var(--wx-page-heading-height) + var(--wx-layout-padding) * 3);
        }
    }

    &__nav {
        &__inner {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
            padding: 4px;
            border-radius: var(--wx-border-radius);
            background-color: var(--wx-white);
            box-shadow: var(--wx-box-shadow);

            @include media-breakpoint-down(lg) {
                @include scrollbar;
                scroll-padding-left: calc(var(--dt-gutter-x) * 0.5);
                scroll-padding-right: calc(var(--dt-gutter-x) * 0.5);
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;

                > * {
                    flex-shrink: 0;
                    flex-grow: 1;
                    scroll-snap-align: start;
                    scroll-snap-stop: always;
                }
            }

            button {
                background-color: transparent;
                border: none;
                color: var(--wx-text-color);
                cursor: pointer;
                padding: 6px 18px;
                white-space: nowrap;
                border-radius: calc(var(--wx-border-radius) - 4px);
                font-size: 16px;
                font-weight: 600;
                transition: all 0.2s var(--wx-easing);

                &:hover {
                    background-color: var(--wx-light-gray);
                }

                &.active {
                    background-color: var(--wx-primary);
                    color: var(--wx-white);
                }
            }
        }
    }

    &--vertical {
        @include media-breakpoint-up(lg) {
            display: grid;
            gap: 16px;
            grid-template-columns: 200px 1fr;

            .wx-tabs {
                &__nav {
                    &__inner {
                        position: sticky;
                        top: calc(var(--wx-tabs-nav-top-offset) + var(--wx-layout-padding) / 2);
                        flex-direction: column;
                        margin-bottom: 0;
                        transition: top 300ms linear;

                        button {
                            width: 100%;
                            text-align: left;
                        }
                    }
                }
            }
        }
        @include media-breakpoint-up(xl) {
            grid-template-columns: 256px 1fr;
        }
    }
}
</style>
