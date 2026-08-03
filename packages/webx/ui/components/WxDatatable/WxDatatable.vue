<script setup lang="ts">
import { onBeforeMount, onMounted, provide, ref, watch } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import { $t } from '@/locales';
import type { WxOrderDirection } from '@/types/order';
import type { WxLengthAwarePagination, WxLengthAwarePaginationLinks } from '@/types/pagination';
import { api } from '@/utils';
import WxAlert from '../../components/WxAlert/WxAlert.vue';
import WxButton from '../../components/WxButton/WxButton.vue';
import WxCheck from '../../components/WxCheck/WxCheck.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import WxIcon from '../WxIcon/WxIcon.vue';
import WxPagination from '../WxPagination/WxPagination.vue';
import type { WxDatatableProps, WxDatatableContext, WxDatatableColumn } from './types';

const props = withDefaults(defineProps<WxDatatableProps>(), {
    selectable: 'none',
    compact: false,
});
const emit = defineEmits(['sorted', 'selected', 'search', 'pagination']);

provide('wx-form-errors', null);

const datalist = ref<WxLengthAwarePagination<any>>();
const schema = ref<WxDatatableColumn[]>([]);
const searchQuery = ref<string>(props.search || '');
const sortColumn = ref<string | null>();
const sortOrder = ref<WxOrderDirection>();
const currentPage = ref<number>(1);
const selectedRows = ref([]);
const id = ref<string | number | null>();

const fetchData = async (query = {}) => {
    if (props.endpoint) {
        const response = await api.get<WxLengthAwarePagination<any>>(props.endpoint, {
            ...props.endpointQuery,
            ...query,
            page: currentPage.value,
            q: searchQuery.value?.trim(),
            sortBy: sortColumn.value,
            sortOrder: sortOrder.value,
        });
        datalist.value = response as WxLengthAwarePagination<any>;
    } else if (props.data) {
        datalist.value = props.data;
    }
    id.value = `${Math.random().toString(36).substring(2, 9)}`;
};

let interval;
watch([searchQuery], () => {
    if (interval) {
        clearTimeout(interval);
    }

    interval = setTimeout(() => {
        currentPage.value = 1;
        saveSettings();
        fetchData();
        emit('search', searchQuery.value?.trim());
    }, 500);
});
watch([currentPage], () => {
    saveSettings();
    fetchData();
});
watch([sortOrder, sortColumn], () => {
    currentPage.value = 1;
    saveSettings();
    fetchData();
});
watch([selectedRows], () => {
    emit('selected', selectedRows.value);
});
watch(
    () => props.data,
    () => {
        fetchData();
    },
);

onBeforeMount(() => {
    restoreSettings();
});
onMounted(() => {
    fetchData();
});

const reload = (q = {}) => {
    fetchData(q);
    selectedRows.value = [];
};

const resetSearch = () => {
    searchQuery.value = '';
};

defineExpose({
    reload,
    resetSearch,
});

const saveSettings = () => {
    if (props.persist) {
        localStorage.setItem(
            'dt-' + props.persist,
            JSON.stringify({
                currentPage: currentPage.value,
                searchQuery: searchQuery.value,
                sortColumn: sortColumn.value,
                sortOrder: sortOrder.value,
            }),
        );
    }
};

const restoreSettings = () => {
    if (props.persist) {
        const settings = localStorage.getItem('dt-' + props.persist);
        if (settings) {
            const params = JSON.parse(settings);
            currentPage.value = params.currentPage;
            searchQuery.value = params.searchQuery;
            sortColumn.value = params.sortColumn;
            sortOrder.value = params.sortOrder;
        }
    }
};

const handleSort = (column: WxDatatableColumn) => {
    if (!column.sortable) {
        return;
    }

    sortColumn.value = column.id;

    if (!sortOrder.value) {
        sortOrder.value = 'asc';
    } else if (sortOrder.value === 'asc') {
        sortOrder.value = 'desc';
    } else if (sortOrder.value === 'desc') {
        sortOrder.value = 'asc';
    }
};

const registerColumn = (column: WxDatatableColumn) => {
    if (!schema.value.some((item) => item.id === column.id)) {
        schema.value.push(column);
    }
};
const unregisterColumn = (column: WxDatatableColumn) => {
    schema.value = schema.value.filter((item) => item.id !== column.id);
};

provide<WxDatatableContext>('datatableContext', {
    registerColumn,
    unregisterColumn,
});

const getSchema = () => {
    const columns = schema.value.map((column) => column.size);

    if (props.selectable !== 'none') {
        columns.unshift('max-content');
    }

    return columns.join(' ');
};

const checkRowsSorting = (event) => {
    if (event.related.classList.contains('wx-datatable__row--head')) {
        return false;
    }
};

const handleSorted = (event) => {
    emit(
        'sorted',
        datalist.value.data.filter((item) => item !== undefined),
        event,
    );
};

const handlePaging = (link: WxLengthAwarePaginationLinks) => {
    currentPage.value = link.page;
    emit('pagination', link);
};

const handleSelectAll = () => {
    if (props.selectable === 'checkbox') {
        if (isCheckedAll()) {
            const ids = datalist.value.data.map((item) => item.id);
            selectedRows.value = selectedRows.value.filter((item) => !ids.includes(item.id));
        } else {
            for (const row of datalist.value.data) {
                if (!selectedRows.value.includes(row)) {
                    selectedRows.value.push(row);
                }
            }
        }
        emit('selected', selectedRows.value);
    }
};

const isCheckedAll = () => {
    const ids = datalist.value.data.filter((item) => item !== undefined).map((item) => item.id);

    return ids.every((id) => selectedRows.value.findIndex((item) => item.id === id) !== -1);
};

const handleSelect = (row: any) => {
    if (props.selectable === 'checkbox') {
        console.log(row);
        selectedRows.value = selectedRows.value.some((item) => item.id === row.id)
            ? selectedRows.value.filter((item) => item.id !== row.id)
            : [...selectedRows.value, row];
    } else if (props.selectable === 'radio') {
        selectedRows.value = [row];
    }
};

const unselectRow = (row: any) => {
    selectedRows.value = selectedRows.value.filter((item) => item !== row);
};

const handleChecked = (row: any) => {
    return selectedRows.value.some((item) => item.id === row.id);
};

const getRowStyle = (row: any) => {
    if (props.rowStyle) {
        return props.rowStyle(row);
    }

    return [];
};

const getRowClass = (row: any) => {
    if (props.rowClass) {
        return props.rowClass(row);
    }

    return [];
};
</script>

<template>
    <div class="wx-datatable-container">
        <div class="wx-datatable" :class="[props.adaptive ? `wx-datatable--adaptive-${props.adaptive}` : '', props.compact ? `wx-datatable--compact` : '']">
            <div
                v-if="props.heading || props.searchable || (selectedRows.length > 0 && props.selectable === 'checkbox')"
                class="wx-datatable__header d-flex flex-column flex-md-row mb-8 gap-4"
                :class="{ 'align-items-md-center': props.heading && !(selectedRows.length > 0 && props.selectable === 'checkbox') }"
            >
                <div v-if="props.heading || (selectedRows.length > 0 && props.selectable === 'checkbox')" class="d-flex flex-column gap-16">
                    <h4 v-if="props.heading" class="wx-datatable__heading d-flex align-items-center m-0">{{ props.heading }}</h4>

                    <div v-if="selectedRows.length > 0 && props.selectable === 'checkbox'" class="wx-datatable__selected d-flex mb-8 flex-wrap gap-2">
                        <template v-if="$slots['selected']">
                            <div v-for="item in selectedRows" :key="item.id + '_1'">
                                <wx-button size="md" @click="() => unselectRow(item)"> <slot name="selected" :item="item"></slot> &times; </wx-button>
                            </div>
                        </template>
                        <template v-else>
                            <div v-for="item in selectedRows" :key="item.id + '_2'">
                                <wx-button size="md" @click="() => unselectRow(item)">{{ item.id }} &times;</wx-button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="ms-md-auto" v-if="searchable">
                    <wx-input type="search" :placeholder="$t('search-placeholder')" v-model="searchQuery" />
                </div>
            </div>

            <template v-if="datalist">
                <div v-if="searchQuery && datalist.meta.total === 0">
                    <wx-alert type="info">
                        <div class="d-flex align-items-center flex-wrap gap-16">
                            <div class="me-auto">{{ $t('search-no-results') }}</div>

                            <wx-button theme="outline-info" size="sm" @click="() => (searchQuery = '')">{{
                                $t('search-no-results-reset')
                            }}</wx-button>
                        </div>
                    </wx-alert>
                </div>
                <wx-pagination v-else :pagination="datalist" @change="(link: WxLengthAwarePaginationLinks) => handlePaging(link)">
                    <wx-alert v-if="datalist.meta.total === 0" type="info">{{ $t('datatable-empty') }}</wx-alert>
                    <div v-else class="wx-datatable__content" :style="[`--wx-datatable-schema: ${getSchema()}`]">
                        <div class="wx-datatable__row wx-datatable__row--head mb-8">
                            <template v-if="props.selectable !== 'none'">
                                <div class="wx-datatable__column">
                                    <wx-check
                                        v-if="props.selectable === 'checkbox'"
                                        :type="props.selectable"
                                        :checked="isCheckedAll()"
                                        @change="handleSelectAll()"
                                    />
                                </div>
                            </template>
                            <template v-for="column in schema" :key="column.id">
                                <div
                                    class="wx-datatable__column d-flex align-items-center gap-2"
                                    :class="{ 'cursor-pointer': column.sortable }"
                                    @click="handleSort(column)"
                                >
                                    {{ column.title }}
                                    <div class="wx-datatable__sort d-flex align-items-center justify-content-center" v-if="column.sortable">
                                        <wx-icon :name="sortColumn === column.id ? (sortOrder === 'asc' ? 'sort-asc' : 'sort-desc') : 'sortable'" />
                                    </div>
                                </div>
                            </template>
                        </div>

                        <VueDraggableNext
                            v-if="sortable"
                            class="wx-datatable__body"
                            @change="(event) => handleSorted(event)"
                            :move="checkRowsSorting"
                            handle=".handle"
                            :animation="150"
                            v-model="datalist.data"
                        >
                            <div class="wx-datatable__row wx-datatable__row--body"
                                 :style="getRowStyle(row)"
                                 :class="getRowClass(row)"
                                 v-for="(row, index) in datalist.data" :key="index + '-' + id">
                                <template v-if="props.selectable !== 'none'">
                                    <div class="wx-datatable__column">
                                        <wx-check :type="props.selectable" :checked="handleChecked(row)" @change="handleSelect(row)" />
                                    </div>
                                </template>
                                <slot name="row" :item="row"></slot>
                            </div>
                        </VueDraggableNext>
                        <div v-else class="wx-datatable__body" :style="[`--wx-datatable-schema: ${getSchema()}`]">
                            <div class="wx-datatable__row wx-datatable__row--body"
                                 :style="getRowStyle(row)"
                                 :class="getRowClass(row)"
                                 v-for="(row, index) in datalist.data" :key="index + '-' + id">
                                <template v-if="props.selectable !== 'none'">
                                    <div class="wx-datatable__column">
                                        <wx-check :type="props.selectable" :checked="handleChecked(row)" @change="handleSelect(row)" />
                                    </div>
                                </template>
                                <slot name="row" :item="row"></slot>
                            </div>
                        </div>
                    </div>
                </wx-pagination>
            </template>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-datatable {
    --wx-datatable-gap-y: 8px;
    --wx-datatable-gap-x: 8px;
    --wx-datatable-body-height: 56px;
    --wx-datatable-body-padding-x: 8px;
    --wx-datatable-body-padding-y: 8px;
    --wx-datatable-body-font-size: 14px;
    --wx-datatable-body-radius: var(--wx-border-radius);
    --wx-datatable-body-background: var(--wx-white);
    --wx-datatable-body-shadow: var(--wx-box-shadow);
    --wx-datatable-body-border: var(--wx-white);
    --wx-datatable-body-hover-background: color-mix(in srgb, var(--wx-light-gray) 50%, white);
    --wx-datatable-body-hover-shadow: var(--wx-box-shadow);
    --wx-datatable-body-hover-border: var(--wx-datatable-body-hover-background);
    --wx-datatable-body-active-background: color-mix(in srgb, var(--wx-light-gray) 50%, white);
    --wx-datatable-body-active-shadow: var(--wx-box-shadow);
    --wx-datatable-body-active-border: var(--wx-input-filled-border);

    &.wx-datatable--compact {
        --wx-datatable-body-height: 32px;
        --wx-datatable-body-padding-x: 6px;
        --wx-datatable-body-padding-y: 5px;
        --wx-datatable-body-font-size: 14px;
        --wx-datatable-gap-y: 1px;
        --wx-datatable-body-radius : 8px;
    }

    .wx-card & {
        --wx-datatable-body-shadow: none;
        --wx-datatable-body-hover-shadow: none;
        --wx-datatable-body-active-shadow: none;
        --wx-datatable-body-border: var(--wx-input-border);
        --wx-datatable-body-hover-border: var(--wx-input-hover-border);
    }

    @include media-breakpoint-up(lg) {
        --wx-datatable-gap-x: 12px;
        --wx-datatable-body-padding-x: 12px;
        --wx-datatable-body-padding-y: 12px;
    }

    &__heading {
        @include media-breakpoint-up(md) {
            min-height: var(--wx-input-size);
        }
    }

    &__content {
        display: grid;
        grid-template-columns: var(--wx-datatable-schema);
    }
    &__body {
        display: grid;
        grid-column: 1/-1;
        grid-template-columns: subgrid;
    }

    &__row {
        display: grid;
        grid-column: 1/-1;
        gap: var(--wx-datatable-gap-x);
        grid-template-columns: subgrid;
        align-items: center;

        &--head {
            margin-bottom: calc(var(--wx-datatable-gap-y) / 2);
            padding-left: var(--wx-datatable-body-padding-x);
            padding-right: var(--wx-datatable-body-padding-x);
            font-size: 14px;
            font-weight: 600;
            color: var(--wx-secondary);
        }

        &--body {
            min-height: var(--wx-datatable-body-height);
            margin-bottom: var(--wx-datatable-gap-y);
            padding: var(--wx-datatable-body-padding-y) var(--wx-datatable-body-padding-x);
            border-radius: var(--wx-datatable-body-radius);
            background-color: var(--wx-datatable-body-background);
            border: 1px solid var(--wx-datatable-body-border);
            box-shadow: var(--wx-datatable-body-shadow);
            font-size: var(--wx-datatable-body-font-size);
            transition:
                background-color 200ms var(--wx-easing),
                border-color 200ms var(--wx-easing),
                box-shadow 200ms var(--wx-easing);

            &:hover {
                --wx-datatable-body-background: var(--wx-datatable-body-hover-background);
                --wx-datatable-body-shadow: var(--wx-datatable-body-hover-shadow);
                --wx-datatable-body-border: var(--wx-datatable-body-hover-border);
            }

            &:has(.wx-checkbox.checked) {
                --wx-datatable-body-background: var(--wx-datatable-body-active-background);
                --wx-datatable-body-shadow: var(--wx-datatable-body-active-shadow);
                --wx-datatable-body-border: var(--wx-datatable-body-active-border);
            }
        }
    }

    &__sort {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    &__column {
        display: flex;
        align-items: center;
    }

    @each $name, $width in $grid-breakpoints {
        @container (max-width: #{$width}) {
            &.wx-datatable--adaptive-#{$name} {
                .wx-datatable__content {
                    grid-template-columns: 1fr;
                }
                .wx-datatable__body {
                    grid-column: auto;
                    grid-template-columns: auto;
                }
                .wx-datatable__row {
                    &.wx-datatable__row--head {
                        display: none;
                    }
                    &.wx-datatable__row--body {
                        display: grid;
                        grid-template-columns: 1fr auto;
                        align-items: center;

                        &:has(.wx-actions) {
                            > *:first-child {
                                grid-column: 1;
                                grid-row: 1;
                            }

                            > *:last-child {
                                grid-column: 2;
                                grid-row: 1;
                                justify-self: end;
                            }

                            > *:not(:first-child):not(:last-child) {
                                grid-column: span 2;
                            }
                        }
                    }
                }

                :deep(.wx-datatable__column[data-title]::before) {
                    content: attr(data-title) ':';
                    margin-right: 8px;
                    font-weight: 700;
                }
            }
        }
    }
}

.wx-datatable-container {
    container-type: inline-size;
}
</style>
