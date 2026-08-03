<script setup lang="ts">
import { onMounted } from 'vue';
import { useLocalesStore } from '@/stores';
import type { WxLocalesList } from '@/types/locale';
import WxButton from '../WxButton/WxButton.vue';
import type { WxLocalesProps } from './types';

const { type = 'default' } = defineProps<WxLocalesProps>();
const emit = defineEmits<{
    (e: 'loaded', locale: WxLocalesList): void
    (e: 'change', locale: WxLocalesList): void
}>();

onMounted(() => {
    const locale = useLocalesStore().activeLocale;
    emit('loaded', locale);
})
</script>

<template>
    <div v-if="type === 'default'" class="wx-locales-inline">
        <template v-for="locale in useLocalesStore().list" :key="locale.code">
            <div v-show="useLocalesStore().activeCode === locale.code">
                <slot name="item" :locale="locale" />
            </div>
        </template>
        <div class="wx-locales-inline__selector d-flex flex-column align-items-center gap-2">
            <div class="wx-locales-inline__locale active d-flex justify-content-center align-items-center">
                {{ useLocalesStore().activeLocale.code }}
            </div>
            <div
                v-for="locale in useLocalesStore().list"
                :key="locale.code"
                @click="() => { useLocalesStore().setLocale(locale.code); emit('change', locale)}"
                :class="locale.code === useLocalesStore().activeCode ? 'selected' : ''"
                class="wx-locales-inline__locale"
            >
                {{ locale.code }}
            </div>
        </div>
    </div>
    <div v-if="type === 'tabs'" class="wx-locales-tabs">
        <div class="d-flex align-items-center mb-4 flex-wrap gap-4">
            <template v-for="locale in useLocalesStore().list" :key="locale.code">
                <wx-button
                    size="sm"
                    class="text-uppercase"
                    :class="locale.code === useLocalesStore().activeCode ? 'active' : ''"
                    @click="() => {useLocalesStore().setLocale(locale.code); emit('change', locale)}"
                >
                    {{ locale.short }}
                </wx-button>
            </template>
        </div>
        <template v-for="locale in useLocalesStore().list" :key="locale.code">
            <div v-show="useLocalesStore().activeCode === locale.code">
                <slot name="item" :locale="locale" />
            </div>
        </template>
    </div>
    <div v-if="type === 'vertical'" class="wx-locales-tabs d-flex gap-4">
        <template v-for="locale in useLocalesStore().list" :key="locale.code">
            <div v-show="useLocalesStore().activeCode === locale.code" class="flex-grow-1 min-w-0">
                <slot name="item" :locale="locale" />
            </div>
        </template>
        <div class="d-flex flex-column gap-4">
            <template v-for="locale in useLocalesStore().list" :key="locale.code">
                <wx-button
                    size="sm"
                    :theme="locale.code === useLocalesStore().activeCode ? 'primary' : 'default'"
                    class="text-uppercase"
                    :class="locale.code === useLocalesStore().activeCode ? 'active' : ''"
                    @click="() => {useLocalesStore().setLocale(locale.code); emit('change', locale)}"
                >
                    {{ locale.short }}
                </wx-button>
            </template>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-locales-tabs {
    &__toggle {
        &.active {
            background-color: var(--wx-light-gray);
        }
    }
}

.wx-locales-inline {
    --wx-locales-selector-width: 48px;
    --wx-locales-selector-offset: 6px;

    position: relative;
    min-height: 40px;

    &__selector {
        z-index: 100;
        position: absolute;
        top: var(--wx-locales-selector-offset);
        right: var(--wx-locales-selector-offset);
        width: var(--wx-locales-selector-width);
        min-height: 28px;
        padding: 2px;
        border-radius: 10px;
        transition:
            background-color 0.1s var(--wx-easing),
            box-shadow 0.1s var(--wx-easing);

        &:hover {
            z-index: 120;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
            background-color: var(--wx-white);
        }
    }

    &__locale {
        display: flex;
        justify-content: center;
        align-items: center;
        width: calc(100% - 2px);
        height: 26px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;

        &.selected,
        &.active {
            background-color: var(--wx-light-gray);
        }

        &:not(.active) {
            display: none;
            transition:
                background-color 0.2s var(--wx-easing),
                color 0.2s var(--wx-easing);

            &:hover {
                background-color: var(--wx-light-gray);
            }

            .wx-locales-inline__selector:hover & {
                display: flex;
            }
        }
    }
}
</style>
