<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';

import { useRoute } from 'vue-router';
import { menu } from '@/config/menu';

import { api, wxBuffer, wxConfirm } from '@/utils';
import WxIcon from '../../components/WxIcon/WxIcon.vue';
import WxLoader from '../../components/WxLoader/WxLoader.vue';
import WxLogo from '../../layouts/WxLayout/WxLogo/WxLogo.vue';
import WxMenu from '../../layouts/WxLayout/WxMenu/WxMenu.vue';
import WxUser from '../../layouts/WxLayout/WxUser/WxUser.vue';

import type { WxScrollDirection } from './types';
import WxSiteSelector from '@/ui/widgets/WxSiteSelector/WxSiteSelector.vue';

const route = useRoute();

const layout = ref<HTMLElement>(null);
const head = ref<HTMLElement>(null);
const headLogo = ref<HTMLElement>(null);
const headMenu = ref<HTMLElement>(null);
const headProfile = ref<HTMLElement>(null);

const isCompact = ref(false);
const isHeaderHidden = ref(false);
const isScrolled = ref(false);
const showMobileMenu = ref(false);
const buffer = ref();

const lastScrollPosition = ref(0);
const direction = ref<WxScrollDirection>('top');

const { pull, clear } = wxBuffer();

const calculateDimensions = () => {
    layout.value.style.setProperty('--wx-layout-header-height', `${head.value.clientHeight}px`);

    const headWidth = head.value.offsetWidth;
    const logoWidth = headLogo.value.offsetWidth;
    const menuWidth = headMenu.value.offsetWidth;
    const profileWidth = headProfile.value.offsetWidth;

    const gap = 16;
    const padding = 8;
    const totalInnerWidth = logoWidth + menuWidth + profileWidth + gap * 2 + padding * 2;

    isCompact.value = totalInnerWidth > headWidth - 20;
};

const mutationObserver: MutationObserver = new MutationObserver(() => calculateDimensions());
const resizeObserver: ResizeObserver = new ResizeObserver(() => calculateDimensions());

const onScroll = () => {
    const currentScrollPosition = window.scrollY || document.documentElement.scrollTop;

    if (currentScrollPosition < 0) return;

    isScrolled.value = true;

    if (currentScrollPosition > 0) {
        isHeaderHidden.value = currentScrollPosition > lastScrollPosition.value;
    } else {
        isHeaderHidden.value = false;
    }

    if (currentScrollPosition > lastScrollPosition.value) {
        direction.value = 'down';
    } else if (currentScrollPosition < lastScrollPosition.value) {
        direction.value = 'up';
    }

    if (currentScrollPosition <= 0) {
        direction.value = 'top';
    }

    lastScrollPosition.value = currentScrollPosition;
};

watch(
    () => route.path,
    () => (showMobileMenu.value = false),
);

const bufferUpdated = () => {
    buffer.value = pull();
};

onMounted(() => {
    calculateDimensions();

    mutationObserver.observe(layout.value, {
        childList: true,
        subtree: true,
        characterData: true,
    });

    resizeObserver.observe(layout.value);

    window.addEventListener('scroll', onScroll);

    bufferUpdated();
    window.addEventListener('wxBufferUpdated', () => bufferUpdated());
    window.addEventListener('storage', () => bufferUpdated());
});

onUnmounted(() => {
    mutationObserver?.disconnect();
    resizeObserver?.disconnect();

    window.removeEventListener('scroll', onScroll);

    window.removeEventListener('storage', () => bufferUpdated());
    window.removeEventListener('wxBufferUpdated', () => bufferUpdated());
});

const reloadPage = () => location.reload();
</script>

<template>
    <div
        :class="[
            'layout',
            `layout--scroll-${direction}`,
            showMobileMenu ? 'layout--mobile-menu' : null,
            isScrolled ? 'layout--scrolled' : null,
            isHeaderHidden ? 'layout--head-hidden' : null,
        ]"
        ref="layout"
    >
        <div
            ref="head"
            class="layout__head d-flex align-items-center gap-md-16 gap-8 rounded bg-white p-8 shadow"
            :class="isCompact ? 'layout--mobile' : ''"
        >
            <div ref="headLogo" class="layout__logo flex-shrink-0">
                <wx-logo />
            </div>
            <div ref="headMenu" class="layout__menu">
                <wx-menu :items="menu" mode="dropdown" />
            </div>
            <div ref="headProfile" class="d-flex align-items-center ms-auto flex-shrink-0 gap-16">
                <wx-site-selector @select="reloadPage()" />
                <wx-user />
            </div>
            <div class="layout__burger" @click="showMobileMenu = !showMobileMenu">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path
                        fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"
                    />
                </svg>
            </div>
        </div>

        <div class="layout__body">
            <router-view></router-view>
        </div>

        <div class="mobile-menu">
            <div class="mobile-menu__overlay" @click="showMobileMenu = false"></div>
            <div class="mobile-menu__content d-flex flex-column gap-8 rounded bg-white p-8 shadow">
                <div class="mobile-menu__head d-flex align-items-center justify-content-between gap-16">
                    <wx-logo class="flex-shrink-0" />

                    <div class="mobile-menu__close" @click="showMobileMenu = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"
                            />
                        </svg>
                    </div>
                </div>
                <div class="mobile-menu__body position-relative flex-grow-1">
                    <div class="mobile-menu__body__inner">
                        <wx-menu :items="menu" mode="accordion" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wx-buffer d-flex align-items-center gap-4 bg-white px-16 py-4" v-if="buffer">
        <div class="fw-semibold">{{ buffer.title }}</div>
        <div class="text-danger d-flex cursor-pointer" @click="wxConfirm().then(() => clear())">
            <wx-icon name="remove" />
        </div>
    </div>

    <wx-loader v-if="api.enableLoading.value && api.isLoading.value" />
</template>

<style scoped lang="scss">
.wx-buffer {
    position: fixed;
    right: 1rem;
    bottom: 0;
    border-top: 1px solid var(--wx-border-color);
    border-right: 1px solid var(--wx-border-color);
    border-left: 1px solid var(--wx-border-color);
    border-radius: var(--wx-border-radius) var(--wx-border-radius) 0 0;
}

.mobile-menu {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 910;
    background-color: rgba(var(--wx-dark-rgb), 0.5);

    .layout:not(.layout--mobile-menu) & {
        display: none;
    }

    &__overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    &__content {
        position: absolute;
        top: var(--wx-layout-padding);
        right: var(--wx-layout-padding);
        bottom: var(--wx-layout-padding);
        width: 320px;
    }

    &__body {
        &__inner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            @include scrollbar;
        }
    }

    &__close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background-color: var(--wx-light);
        cursor: pointer;

        svg {
            width: 65%;
            height: auto;
        }
    }
}

.layout {
    --wx-layout-padding: 8px;

    @include media-breakpoint-up(lg) {
        --wx-layout-padding: 16px;
    }

    &__burger {
        display: none;
        cursor: pointer;

        svg {
            width: 60%;
            height: auto;
        }

        .layout--mobile & {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background-color: var(--wx-light);
        }
    }

    &__menu {
        .layout--mobile & {
            z-index: -1;
            position: absolute;
            visibility: hidden;
        }
    }

    &__head {
        position: fixed;
        z-index: 900;
        top: var(--wx-layout-padding);
        right: var(--wx-layout-padding);
        left: var(--wx-layout-padding);
        transition: translate 0.3s linear;

        .layout--head-hidden & {
            translate: 0 calc(-100% - var(--wx-layout-padding) * 2);
        }
    }

    &__body {
        padding: calc(var(--wx-layout-header-height) + var(--wx-layout-padding) * 2) var(--wx-layout-padding) var(--wx-layout-padding)
            var(--wx-layout-padding);
    }
}
</style>
