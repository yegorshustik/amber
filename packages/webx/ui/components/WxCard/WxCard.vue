<script setup lang="ts">
import type { WxCardProps } from './types';

const props = withDefaults(defineProps<WxCardProps>(), {
    title: '',
});
</script>

<template>
    <div class="wx-card rounded bg-white shadow">
        <template v-if="!$slots.actions">
            <template v-if="$slots.sidebar">
                <div v-if="props.title" class="wx-card__head d-flex justify-content-between align-items-center rounded-top px-16 pt-16">
                    <h4 class="m-0" v-text="props.title" />
                </div>
            </template>

            <template v-else>
                <div v-if="props.title" class="wx-card__head px-16 pt-16">
                    <h4 class="m-0" v-text="props.title" />
                </div>
            </template>
        </template>
        <template v-else>
            <div class="wx-card__head d-flex justify-content-between align-items-center rounded-top px-16 pt-16">
                <h4 class="m-0" v-if="props.title" v-text="props.title" />
                <div class="wx-card__actions ms-auto">
                    <slot name="actions"></slot>
                </div>
            </div>
        </template>
        <div class="wx-card__body">
            <div class="wx-card__body__inner">
                <template v-if="$slots.sidebar">
                    <div class="d-flex flex-column flex-sm-row">
                        <div class="wx-card__sidebar me-sm-0 w-sm-100 max-w-sm-192 max-w-md-256 flex-grow-1 mx-16 flex-shrink-0 pt-16" :class="{}">
                            <slot name="sidebar"></slot>
                        </div>
                        <div class="wx-card__content pt-sm-16 flex-grow-1 px-16" :class="{ 'pb-16': !$slots.footer }"><slot /></div>
                    </div>
                </template>
                <div v-else :class="{ 'px-16': true, 'pb-16': !$slots.footer, 'pt-16': true }" class="wx-card__content"><slot /></div>
            </div>
        </div>
        <div v-if="$slots.footer" class="wx-card__footer rounded-bottom bg-white px-16 py-16" :class="{ 'pt-16': !$slots.sidebar }">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-card {

    &:has(+ .wx-card) {
        margin-bottom: 16px;
    }
    :deep() {
        .wx-card {
            --wx-box-shadow : none;
            border:1px solid var(--wx-border-color);
        }
    }

    .page__sidebar & {
        @include media-breakpoint-down(lg) {
            margin-bottom: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            border-bottom: 1px solid var(--wx-border-color);
        }
    }

    &__content {
        >*:first-child {
            margin-top: 0;
        }
        >*:last-child {
            margin-bottom: 0!important;
        }

        &:empty {
            padding-top:0!important;
        }
    }
}
</style>
