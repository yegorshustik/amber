<script setup lang="ts">
import { onMounted, onUnmounted, ref, useSlots, computed } from 'vue';

import type { WxPageProps } from './types';
import WxIcon from '../../components/WxIcon/WxIcon.vue';
import WxButton from '../../components/WxButton/WxButton.vue';

const props = withDefaults(defineProps<WxPageProps>(), {
    size: 'default',
});

const slots = useSlots();
const hasSidebar = computed(() => !!slots.sidebar);

const page = ref<HTMLElement>(null);
const pageHeading = ref<HTMLElement>(null);
const scrollSentinel = ref<HTMLElement | null>(null);
const isStuck = ref(false);
const toggleSidebar = ref(false);

const calculateDimensions = () => {
    if (pageHeading.value) {
        page.value.style.setProperty('--wx-page-heading-height', `${pageHeading.value.clientHeight}px`);
    }
};

const mutationObserver: MutationObserver = new MutationObserver(() => calculateDimensions());
const resizeObserver: ResizeObserver = new ResizeObserver(() => calculateDimensions());
const scrollObserver: IntersectionObserver = new IntersectionObserver(
    ([entry]) => {
        isStuck.value = !entry.isIntersecting;
    },
    {
        threshold: [1.0],
    },
);

onMounted(() => {
    if (pageHeading.value) {
        calculateDimensions();
        mutationObserver.observe(pageHeading.value, {
            childList: true,
            subtree: true,
            characterData: true,
        });
    }
    resizeObserver.observe(page.value);
    scrollObserver.observe(scrollSentinel.value);
});

onUnmounted(() => {
    mutationObserver?.disconnect();
    resizeObserver?.disconnect();
    scrollObserver?.disconnect();
});
</script>

<template>
    <div
        :class="[
            'page',
            toggleSidebar ? 'page--show-sidebar' : '',
            hasSidebar ? 'page--with-sidebar' : '',
            `page--${size}`,
            `${isStuck ? 'page--stuck' : ''}`,
        ]"
        ref="page"
    >
        <div ref="scrollSentinel" class="scroll-sentinel"></div>
        <div class="page__heading">
            <div class="page__heading__inner" ref="pageHeading">
                <div class="page__heading__inner-content d-flex align-items-center gap-8">
                    <div v-if="$slots['sidebar']" class="d-lg-none cursor-pointer" @click="toggleSidebar = !toggleSidebar">
                        <wx-icon name="list" />
                    </div>
                    <div v-if="props.back">
                        <wx-button theme="back" :route="props.back" size="md">
                            <wx-icon name="back" />
                        </wx-button>
                    </div>

                    <h1 class="m-0">{{ props.heading }}</h1>

                    <div v-if="$slots.actions" class="ms-auto">
                        <slot name="actions" />
                    </div>
                </div>
            </div>
        </div>
        <div class="page__content">
            <div v-if="$slots.sidebar" class="page__sidebar">
                <div class="page__sidebar__inner d-flex flex-column flex-lg-grow-0 flex-grow-1">
                    <div class="page__sidebar__head d-flex justify-content-end d-lg-none">
                        <div class="cursor-pointer" @click="toggleSidebar = !toggleSidebar">
                            <wx-icon name="x" />
                        </div>
                    </div>
                    <div class="page__sidebar__content flex-grow-1">
                        <div class="page__sidebar__body">
                            <slot name="sidebar" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="page__body">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.page {
    --wx-page-sidedar-top-offset: 0px;

    @include media-breakpoint-up(lg) {
        .layout--head-hidden & {
            --wx-page-sidedar-top-offset: calc(var(--wx-page-heading-height) + var(--wx-layout-padding));
        }
        .layout:not(.layout--head-hidden) & {
            --wx-page-sidedar-top-offset: calc(var(--wx-layout-header-height) + var(--wx-page-heading-height) + var(--wx-layout-padding) * 3);
        }
    }

    &--default {
        &:not(.page--with-sidebar) {
        }
    }

    &__heading {
        z-index: 200;
        position: relative;
        height: var(--wx-page-heading-height);
        margin-bottom: var(--wx-layout-padding);
        transition: top 0.3s linear;

        .layout--scroll-up & {
            top: calc(var(--wx-layout-header-height) + var(--wx-layout-padding) * 2);
        }

        &__inner {
            position: fixed;
            top: calc(var(--wx-layout-header-height) + var(--wx-layout-padding) * 2);
            right: var(--wx-layout-padding);
            left: var(--wx-layout-padding);
            transition:
                top 0.3s linear,
                background-color 150ms linear,
                border-radius 150ms linear,
                box-shadow 150ms linear,
                padding 150ms linear;

            &-content {
                min-height: 38px;
                .page--default & {
                    width: 100%;
                    max-width: 1300px;
                    margin-inline: auto;
                }
            }

            .layout--head-hidden & {
                top: 0;
            }

            .layout:not(.layout--scroll-top) & {
                background-color: var(--wx-white);
                padding: 0.5rem;
                box-shadow: var(--wx-box-shadow);
                border-radius: var(--wx-border-radius);
            }

            .layout:not(.layout--scroll-top).layout--head-hidden & {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        }
    }

    &__content {
        .page--with-sidebar & {
            @include media-breakpoint-up(lg) {
                display: grid;
                column-gap: var(--wx-layout-padding);
                grid-template-columns: 280px 1fr;
            }
        }
    }

    &__body {
        .page--with-sidebar & {
            > *:first-child {
                @include media-breakpoint-up(lg) {
                    margin-top: 0;
                }
            }
        }

        .page--default & {
            width: 100%;
            max-width: 1300px;
            margin-inline: auto;
        }
    }

    &__sidebar {
        @include media-breakpoint-down(lg) {
            display: none;
            position: fixed;
            top: var(--wx-layout-padding);
            right: var(--wx-layout-padding);
            left: var(--wx-layout-padding);
            bottom: var(--wx-layout-padding);
            z-index: 1000;
            background-color: var(--wx-white);
            padding: var(--wx-layout-padding);
            box-shadow: var(--wx-box-shadow);
            border-radius: var(--wx-border-radius);

            .page--show-sidebar & {
                display: flex;
                flex-direction: column;
            }
        }

        @include media-breakpoint-up(lg) {
            z-index: 1;
        }

        &__inner {
            @include media-breakpoint-up(lg) {
                position: sticky;
                top: calc(var(--wx-page-sidedar-top-offset));
                transition: top 300ms linear;

                .page--stuck & {
                    top: calc(var(--wx-page-sidedar-top-offset) + var(--wx-layout-padding) / 2);
                }
            }
        }

        &__content {
            position: relative;
        }

        &__body {
            @include media-breakpoint-down(lg) {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                @include scrollbar;
            }

            @include media-breakpoint-up(lg) {
                max-height: calc(100vh - var(--wx-page-sidedar-top-offset));
                @include scrollbar;
                margin: -8px;
                padding: 8px;
            }
        }
    }
}
</style>
