<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import type { WxMenuItem, WxMenuMode } from '../../../../types/menu';
import WxIcon from '../../../components/WxIcon/WxIcon.vue';

const props = withDefaults(
    defineProps<{
        items: WxMenuItem[];
        mode?: WxMenuMode;
        depth?: number;
    }>(),
    {
        mode: 'dropdown',
        depth: 0,
    },
);

const route = useRoute();
const currentDepth = computed(() => props.depth || 0);

const hasActiveChild = (item: WxMenuItem): boolean => {
    if (item.route && item.route === route.name) return true;

    if (item.active && item.active.length > 0 && item.active.includes(route.name as string)) {
        return true;
    }

    if (item.children) {
        return item.children.some(child => hasActiveChild(child));
    }
    return false;
};

const openStates = ref<Record<string, boolean>>({});

const syncOpenStates = () => {
    props.items.forEach((item) => {
        if (item.children && hasActiveChild(item)) {
            openStates.value[item.title] = true;
        }
    });
};

onMounted(syncOpenStates);
watch(() => route.name, syncOpenStates);

const toggle = (title: string) => {
    openStates.value[title] = !openStates.value[title];
};

</script>

<template>
    <ul v-if="mode === 'accordion'" class="wx-menu-accordion" :style="{ paddingLeft: currentDepth > 0 ? '16px' : '0' }">
        <li v-for="item in items" :key="item.title">

            <router-link
                v-if="!item.children"
                :to="{ name: item.route }"
                class="menu-item"
                active-class="is-active"
            >
                {{ (item.title) }}
            </router-link>

            <template v-else>
                <div
                    class="menu-item has-children"
                    :class="{ 'is-open': openStates[item.title], 'is-parent-active': hasActiveChild(item) }"
                    @click="toggle(item.title)"
                >
                    <span>{{ (item.title) }}</span>
                    <span class="chevron">
                        <wx-icon name="angle-right" width="12" height="12" />
                    </span>
                </div>

                <div class="accordion-wrapper" :class="{ 'is-expanded': openStates[item.title] }">
                    <div class="accordion-content">
                        <wx-menu :items="item.children" :mode="props.mode" :depth="currentDepth + 1" />
                    </div>
                </div>
            </template>

        </li>
    </ul>

    <ul v-else class="wx-menu" :class="[ `depth-${depth}`]">
        <li v-for="(item, index) in items" :key="index">
            <router-link v-if="item.route || (item.children && item.children.length > 0 && item.children[0].route)"
                         :class="{ 'active': hasActiveChild(item) }"
                         :to="{ name: item.route || item.children[0].route, params: item.params }">
                {{ (item.title) }}
            </router-link>
            <button v-else>{{ item.title }}</button>
            <template v-if="item.children">
                <wx-menu :items="item.children" :mode="props.mode" :depth="depth + 1" />
            </template>
        </li>
    </ul>
</template>

<style scoped lang="scss">
.wx-menu-accordion {
    list-style: none;
    margin: 0;
    padding: 0;

    .accordion-wrapper {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.3s ease-out, opacity 0.2s;
        opacity: 0;
        overflow: hidden;

        &.is-expanded {
            grid-template-rows: 1fr;
            opacity: 1;
        }

        .accordion-content {
            min-height: 0;
        }
    }

    .menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
        padding: 8px 12px;
        cursor: pointer;
        color: #333;
        text-decoration: none;
        border-radius: 4px;

        &:hover {
            background-color: var(--wx-light-gray);
        }

        &.router-link-exact-active {
            background-color: var(--wx-light-gray);
            font-weight: bold;
        }

        &.is-open {
            background-color: var(--wx-light-gray);
            font-weight: bold;

            .chevron {
                rotate: 90deg;
            }
        }

        &.has-children {
            font-weight: 600;
        }

        &.is-parent-active:not(.is-open) {
            background-color: var(--wx-light-gray);
            font-weight: bold;
        }

        .chevron {
            font-size: 10px;
            margin-left: 8px;
            transition: rotate 200ms ease-out;
        }
    }
}

.wx-menu {
    &,
    ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    ul {
        visibility: hidden;
        opacity: 0;
        transition:
            opacity 200ms ease-in-out,
            translate 200ms ease-in-out;
    }

    button {
        border:none;
        background: transparent;
        padding: 0;
    }

    li {
        position: relative;

        ul {
            z-index: 10;
            position: absolute;
            min-width: 12rem;
            background-color: var(--wx-white);
            box-shadow: var(--wx-box-shadow);
            border-radius: 0.5rem;

            > li {
                &:first-child {
                    > a {
                        border-top-left-radius: 0.5rem;
                        border-top-right-radius: 0.5rem;
                    }
                }
                &:last-child {
                    > a {
                        border-bottom-left-radius: 0.5rem;
                        border-bottom-right-radius: 0.5rem;
                    }
                }
            }
        }

        &:hover {
            > ul {
                opacity: 1;
                translate: 0 !important;
                visibility: visible;
            }
        }

        &:has(> ul) {
            > button,
            > a {
                position: relative;
                padding-right: 1.75rem !important;

                &:after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    right: 0.5rem;
                    translate: 0 -50%;
                    width: 16px;
                    height: 16px;
                    background: no-repeat center / 110%
                        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 640'%3E%3Cpath d='M297.4 438.6C309.9 451.1 330.2 451.1 342.7 438.6L502.7 278.6C515.2 266.1 515.2 245.8 502.7 233.3C490.2 220.8 469.9 220.8 457.4 233.3L320 370.7L182.6 233.4C170.1 220.9 149.8 220.9 137.3 233.4C124.8 245.9 124.8 266.2 137.3 278.7L297.3 438.7z'/%3E%3C/svg%3E");
                }
            }
        }
    }

    &.depth-0 {
        display: flex;
        align-items: center;
        gap: 0.5rem;

        > li {
            > button,
            > a {
                --wx-link-color-rgb: var(--wx-dark-rgb);
                display: block;
                padding: 6px 12px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                transition:
                    color 0.2s ease-in-out,
                    background-color 0.2s ease-in-out;

                &.active,
                &.router-link-exact-active,
                &.active {
                    background-color: var(--wx-primary);
                    color: var(--wx-white);
                }
            }

            &:hover {
                > :not(.active),
                > :not(.router-link-exact-active),
                > :not(.active) {
                    //background-color: var(--wx-light);
                }
            }

            &:has(.active),
            &:has(.router-link-exact-active) {
                > button,
                > a {
                    background-color: var(--wx-primary);
                    color: var(--wx-white);

                    &:after {
                        background-image:url("data:image/svg+xml,%3Csvg fill='white' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 640'%3E%3Cpath d='M297.4 438.6C309.9 451.1 330.2 451.1 342.7 438.6L502.7 278.6C515.2 266.1 515.2 245.8 502.7 233.3C490.2 220.8 469.9 220.8 457.4 233.3L320 370.7L182.6 233.4C170.1 220.9 149.8 220.9 137.3 233.4C124.8 245.9 124.8 266.2 137.3 278.7L297.3 438.7z'/%3E%3C/svg%3E");
                    }
                }
            }

            > ul {
                top: 100%;
                left: 0;
                translate: 0 1rem;
            }
        }
    }

    &:not(.depth-0) {
        > li {
            &:has(> ul) {
                button,
                a {
                    &::after {
                        rotate: -90deg;
                    }
                }
            }

            > ul {
                top: 50%;
                left: 50%;
                translate: -1rem 0;
            }

            &:hover {
                > button,
                > a {
                    background-color: var(--wx-light-gray);
                    color: var(--wx-dark);
                }
            }
        }

        button,
        a {
            --wx-link-color-rgb: var(--wx-dark-rgb);

            display: block;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s ease-in-out;

            &.router-link-exact-active,
            &.active {
                background-color: var(--wx-light-gray);
            }
        }
    }
}
</style>
