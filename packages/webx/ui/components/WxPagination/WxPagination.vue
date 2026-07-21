<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import type { WxLengthAwarePagination } from '@/types/pagination';
import WxActions from '../WxActions/WxActions.vue';
import WxButton from '../WxButton/WxButton.vue';
import WxButtons from '../WxButtons/WxButtons.vue';
import WxDropdownLink from '../WxDropdown/WxDropdownLink/WxDropdownLink.vue';
import type { WxPaginationProps } from './types';

const props = withDefaults(defineProps<WxPaginationProps>(), {});

const emit = defineEmits(['change']);

const pagination = ref<WxLengthAwarePagination<any>>(props.pagination);

watch(
    () => props.pagination,
    (value) => {
        pagination.value = value;
    },
);
</script>

<template>
    <div v-if="pagination" class="pagination">
        <div class="pagination__body">
            <slot />
        </div>
        <div v-if="pagination.meta.last_page !== 1" class="pagination__footer mt-8">
            <wx-actions type="adaptive" align="end">
                <template #desktop>
                    <wx-buttons>
                        <template v-for="(link, index) in pagination.meta.links" :key="`l-${index}`">
                            <div v-if="link.label === '...'" class="px-16" v-html="link.label" />
                            <wx-button v-else-if="link.url" size="md" :theme="link.active ? 'primary' : 'default'" @click="emit('change', link)">
                                <span v-html="link.label" />
                            </wx-button>
                        </template>
                    </wx-buttons>
                </template>
                <template #mobile-trigger>
                    <wx-button size="md" theme="primary">{{ $t('select-page') }}</wx-button>
                </template>
                <template #mobile>
                    <template v-for="(link, index) in pagination.meta.links" :key="`l2-${index}`">
                        <div v-if="link.label === '...'" class="px-16 py-4" v-html="link.label" />
                        <wx-dropdown-link v-else @click="emit('change', link)"><span v-html="link.label" /></wx-dropdown-link>
                    </template>
                </template>
            </wx-actions>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
