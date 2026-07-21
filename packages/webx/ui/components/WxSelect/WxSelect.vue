<script setup lang="ts">
import type { Ref } from 'vue';
import { ref, computed, watch, inject } from 'vue';
import { $t } from '@/locales';
import type { ValidationError } from '@/types/api';
import WxDropdown from '../WxDropdown/WxDropdown.vue';
import WxDropdownLink from '../WxDropdown/WxDropdownLink/WxDropdownLink.vue';
import WxInput from '../WxInput/WxInput.vue';
import type { WxSelectOption, WxSelectProps } from './types';

const props = withDefaults(defineProps<WxSelectProps>(), {
    multiple: false,
    searchable: false,
    placeholder: $t('select-placeholder'),
    returnObject: false,
    options: () => []
});

const emit = defineEmits(['update:modelValue', 'change']);
const errors = inject<Ref<ValidationError['errors']>>('wx-form-errors', null);

// Локальное состояние, синхронизированное с внешними пропсами
const innerValue = ref(props.modelValue ?? props.value);

// Следим за изменениями извне
watch(() => [props.modelValue, props.value], () => {
    innerValue.value = props.modelValue ?? props.value;
}, { deep: true });

// Текущее значение для отображения и логики
const currentValue = computed(() => innerValue.value);

const dropdownRef = ref(null);
const searchQuery = ref('');

// Поиск текстовой метки для отображения в поле
const getLabel = (val: any) => {
    const target = typeof val === 'object' ? val.value : val;
    const option = props.options.find(opt => opt.value === target);
    return option ? option.label : val;
};

const isSelected = (option: WxSelectOption) => {
    if (props.multiple) {
        const values = Array.isArray(currentValue.value) ? currentValue.value : [];
        return values.some(v => (typeof v === 'object' ? v.value : v) === option.value);
    }
    const val = currentValue.value;
    return (typeof val === 'object' ? val?.value : val) === option.value;
};

const filteredOptions = computed(() => {
    if (!props.searchable || !searchQuery.value) return props.options;
    const query = searchQuery.value.toLowerCase();
    return props.options.filter(opt => opt.label.toLowerCase().includes(query));
});

const handleSelect = (option: WxSelectOption) => {
    if (option.disabled) return;

    const emitValue = props.returnObject ? option : option.value;

    if (props.multiple) {
        const newValue = Array.isArray(currentValue.value) ? [...currentValue.value] : [];
        const index = newValue.findIndex(v => (typeof v === 'object' ? v.value : v) === option.value);

        if (index > -1) newValue.splice(index, 1);
        else newValue.push(emitValue);

        innerValue.value = newValue;
        emit('update:modelValue', newValue);
        emit('change', newValue);
    } else {
        innerValue.value = emitValue;
        emit('update:modelValue', emitValue);
        emit('change', emitValue);
    }
};
const nativeValue = computed(() => {
    if (props.multiple) {
        const values = Array.isArray(currentValue.value) ? currentValue.value : [];
        return values.map(v => (typeof v === 'object' ? v.value : v));
    }
    const val = currentValue.value;
    return val !== undefined && val !== null ? (typeof val === 'object' ? val.value : val) : '';
});

const getErrors = () => {
    return errors?.value[props.name] ?? [];
};
</script>

<template>
    <div class="wx-input-wrapper">
        <select
            v-if="name"
            :name="name"
            :multiple="multiple"
            :value="!multiple ? nativeValue : null"
            class="wx-native-hidden-select"
        >
            <option v-if="!multiple" value=""></option>
            <option
                v-for="opt in filteredOptions"
                :key="opt.value"
                :value="opt.value"
                :selected="multiple ? nativeValue?.includes(opt.value) : nativeValue === opt.value"
            >
                {{ opt.label }}
            </option>
        </select>
        <wx-dropdown
            ref="dropdownRef"
            :disabled="disabled"
            :closeOnClick="!props.multiple"
            class="wx-select"
        >
            <template #trigger>
                <div class="wx-select-trigger" :class="{ 'disabled': disabled, 'invalid' : getErrors().length > 0, 'filled': multiple ? (currentValue?.length > 0) : currentValue }">
                    <div class="wx-select-values">
                        <template v-if="multiple">
                            <template v-if="currentValue?.length > 0">
                                <div class="wx-select-tags">
                                    <span v-for="val in currentValue" :key="typeof val === 'object' ? val.value : val" class="wx-select-tag">
                                        {{ getLabel(val) }}
                                        <i class="remove" @click.stop="handleSelect(options.find(o => o.value === (typeof val === 'object' ? val.value : val))!)">×</i>
                                    </span>
                                </div>
                            </template>
                            <span v-else class="placeholder">{{ placeholder }}</span>
                        </template>
                        <template v-else-if="currentValue !== undefined && currentValue !== null && currentValue !== ''">
                            {{ getLabel(currentValue) }}
                        </template>
                        <span v-else class="placeholder">{{ placeholder }}</span>
                    </div>
                    <div class="wx-select-icon">
                        <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </template>

            <template #body>
                <div class="wx-select-menu">
                    <div v-if="searchable" class="mb-4" @click.stop>
                        <wx-input v-model="searchQuery" type="text" />
                    </div>

                    <div class="wx-select-list d-flex flex-column gap-2">
                        <div
                            v-for="option in filteredOptions"
                            :key="option.value"
                            @click="handleSelect(option)"
                        >
                            <slot name="option" :option="option">
                                <wx-dropdown-link :class="isSelected(option) ? 'active' : ''">
                                    {{ option.label }}
                                    <template v-if="isSelected(option)" #after>✓</template>
                                </wx-dropdown-link>
                            </slot>
                        </div>
                        <div v-if="filteredOptions.length === 0" class="no-results">Ничего не найдено</div>
                    </div>
                </div>
            </template>
        </wx-dropdown>


        <div v-if="getErrors().length > 0" class="wx-input-wrapper__errors mt-2 ps-12 d-flex flex-column gap-2 fs-12px text-danger">
            <div v-for="message in getErrors()" :key="message">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-select {
    --wx-input-padding-y : 2px;

    width: 100%;

    &-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        width: 100%;
        min-height: var(--wx-input-size);
        padding: var(--wx-input-padding-y) var(--wx-input-padding-x);
        border-radius: var(--wx-input-radius);
        border: 1px solid var(--wx-input-border);
        background-color: var(--wx-input-background);
        box-shadow: none;
        color: var(--wx-input-color);
        transition:
            background-color 200ms var(--wx-easing),
            border-color 200ms var(--wx-easing),
            color 200ms var(--wx-easing);

        @at-root textarea#{&} {
            height: 200px;
        }

        &::placeholder {
            color: var(--wx-input-placeholder);
        }

        &:hover {
            --wx-input-border: var(--wx-input-hover-border);
            --wx-input-background: var(--wx-input-hover-background);
            --wx-input-color: var(--wx-input-hover-color);
        }

        .wx-dropdown.opened & {
            --wx-input-border: var(--wx-input-focus-border);
            --wx-input-background: var(--wx-input-focus-background);
            --wx-input-color: var(--wx-input-focus-color);
        }

        &.filled {
            --wx-input-border: var(--wx-input-filled-border);
            --wx-input-background: var(--wx-input-filled-background);
            --wx-input-color: var(--wx-input-filled-color);
        }

        .form-control-container.invalid &,
        &.invalid {
            --wx-input-border: var(--wx-input-invalid-border);
            --wx-input-background: var(--wx-input-invalid-background);
            --wx-input-color: var(--wx-input-invalid-color);
        }
    }

    &-tags {
        display: flex;
        flex-wrap: wrap;
        gap:2px;
        margin-left: calc((var(--wx-input-padding-x) - 4px) * -1);
    }

    &-tag {
        align-items: center;
        background: var(--wx-primary-light, #eef6ff);
        color: var(--wx-primary, #007bff);
        padding: 6px 12px;
        border-radius: calc(var(--wx-input-radius) - 2px);
        margin-right: 2px;
        font-size: 14px;

        .remove { margin-left: 6px; cursor: pointer; &:hover { color: #000; } }
    }

    &-menu {
        min-width: 200px;
        overflow: hidden;
    }

    &-list {
        max-height: 250px;

        @include scrollbar-colored;
    }
}
</style>
