<script setup lang="ts">
import { computed, inject, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import type { Ref } from 'vue';
import type { ValidationError } from '@/types/api';
import type { WxLocalizedValue } from '@/types/locale';
import WxLocales from '../WxLocales/WxLocales.vue';
import type { WxInputProps, WxValue } from './types';
import { useDatePicker } from './useDatePicker';
import { useNumberInput } from './useNumberInput';
import { usePhoneMask } from './usePhoneMask';

const props = withDefaults(defineProps<WxInputProps>(), {
    type: 'text',
    localized: false,
    disabled: false,
});

const errors = inject<Ref<ValidationError['errors']>>('wx-form-errors', null);

const emit = defineEmits(['update:modelValue', 'change', 'input', 'focus', 'blur']);

const handleInput = (val: string, localeCode?: string) => {
    if (props.localized && localeCode) {
        const newValue = {
            ...(currentValue.value as WxLocalizedValue),
            [localeCode]: val,
        };
        currentValue.value = newValue;
        emit('update:modelValue', newValue);
        emit('input', newValue);
    } else {
        currentValue.value = val;
        emit('update:modelValue', val);
        emit('input', val);
    }
};

const { onKeyPress, onWheel, onBlur } = useNumberInput(handleInput);
const { initMask, destroyMask } = usePhoneMask(handleInput);
const { initDatepicker, destroyDatepicker } = useDatePicker(emit);

const isFocused = ref<boolean>(false);
const currentValue = ref<WxValue>('');
const inputRefs = ref<HTMLInputElement[]>([]);

const initValue = () => {
    currentValue.value = props.modelValue ?? props.value ?? (props.localized ? {} : '');
};

const initializeAll = () => {
    nextTick(() => {
        initMask(inputRefs.value as any, props.type);
        initDatepicker(inputRefs.value as any, props.type, currentValue.value);
    });
};

onMounted(() => {
    initValue();
    initializeAll();
});

watch(
    () => props.type,
    () => {
        destroyMask();
        destroyDatepicker();
        initializeAll();
    },
);

watch(
    () => props.modelValue,
    (newVal) => {
        if (JSON.stringify(newVal) !== JSON.stringify(currentValue.value)) {
            currentValue.value = newVal ?? (props.localized ? {} : '');
        }
    },
);

watch(
    () => props.value,
    (newVal) => {
        if (JSON.stringify(newVal) !== JSON.stringify(currentValue.value)) {
            currentValue.value = newVal ?? (props.localized ? {} : '');
        }
    },
);

const onLocaleChange = async (activeLocale: { code: string }) => {
    await nextTick();

    const activeInput = inputRefs.value[activeLocale.code];

    if (activeInput) {
        activeInput.focus();

        const len = activeInput.value.length;
        activeInput.setSelectionRange(len, len);
    }
};

onUnmounted(() => {
    destroyMask();
    destroyDatepicker();
});

const isFilled = computed(() => {
    if (props.localized) {
        const val = currentValue.value as WxLocalizedValue;
        return val && Object.values(val).some((v) => v && String(v).length > 0);
    }
    return String(currentValue.value ?? '').length > 0;
});

const inputClasses = computed(() => ({
    'wx-input': true,
    invalid: !!(errors?.value && errors.value[props.name]),
    focused: isFocused.value,
    filled: isFilled.value,
    disabled: props.disabled,
    localized: props.localized,
}));

const handleFocus = (e: FocusEvent) => {
    isFocused.value = true;
    emit('focus', e);
};

const handleBlur = (e: FocusEvent) => {
    isFocused.value = false;
    emit('blur', e);
};
const getPlaceholder = () => {
    return props.placeholder || (props.type === 'tel' ? '+48 ___ ___ ___' : '');
};
const getErrors = () => {
    return errors?.value[props.name] ?? [];
};
</script>

<template>
    <div class="wx-input-wrapper">
        <WxLocales v-if="localized" @change="onLocaleChange">
            <template #item="{ locale }">
                <input
                    :ref="(el) => (inputRefs[locale.code] = el as HTMLInputElement)"
                    :value="(currentValue as WxLocalizedValue)[locale.code] || ''"
                    :type="type"
                    :name="name ? `${name}[${locale.code}]` : undefined"
                    :placeholder="getPlaceholder()"
                    :disabled="disabled"
                    :class="inputClasses"
                    :min="props.min"
                    :max="props.max"
                    :step="props.step"
                    @keypress="type === 'number' ? onKeyPress($event) : null"
                    @wheel="type === 'number' ? onWheel($event, name, props.min as number, props.max as number) : null"
                    @input="handleInput(($event.target as HTMLInputElement).value, locale.code)"
                    @focus="handleFocus"
                    @blur="
                        (e) => {
                            handleBlur(e);
                            if (type === 'number') onBlur(e, locale.code, props.min, props.max);
                        }
                    "
                    @change="emit('change', $event)"
                />
            </template>
        </WxLocales>
        <template v-else>
            <div v-if="props.type === 'color'" class="d-flex align-items-center gap-8">
                <input
                    :ref="(el) => (inputRefs['default'] = el as HTMLInputElement)"
                    :value="currentValue"
                    :type="type"
                    :name="name"
                    :placeholder="getPlaceholder()"
                    :disabled="disabled"
                    :class="inputClasses"
                    @input="handleInput(($event.target as HTMLInputElement).value)"
                    @focus="handleFocus"
                    @change="emit('change', $event)"
                />
                <span v-if="currentValue" class="fs-14px fw-semibold">{{ currentValue }}</span>
            </div>

            <input
                v-else
                :ref="(el) => (inputRefs['default'] = el as HTMLInputElement)"
                :value="currentValue"
                :type="type"
                :name="name"
                :placeholder="getPlaceholder()"
                :disabled="disabled"
                :class="inputClasses"
                :min="props.min"
                :max="props.max"
                :step="props.step"
                @input="handleInput(($event.target as HTMLInputElement).value)"
                @focus="handleFocus"
                @blur="
                    (e) => {
                        handleBlur(e);
                        if (type === 'number') onBlur(e, 'default', props.min, props.max);
                    }
                "
                @keypress="type === 'number' ? onKeyPress($event) : null"
                @wheel="type === 'number' ? onWheel($event, name, props.min as number, props.max as number) : null"
                @change="emit('change', $event)"
            />
        </template>

        <div v-if="getErrors().length > 0" class="wx-input-wrapper__errors d-flex flex-column fs-12px text-danger mt-2 gap-2 ps-12">
            <div v-for="message in getErrors()" :key="message">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-input-wrapper {
    width: 100%;

    &__error {
    }

    :deep(.wx-input) {
        width: 100%;
        height: var(--wx-input-size);
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

        .wx-locales-inline & {
            padding-right: 50px;
        }

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

        &.focus,
        &:focus {
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

        &[type='number'] {
            -moz-appearance: textfield;

            &::-webkit-outer-spin-button,
            &::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        }

        &[type='color'] {
            padding: 2px;
            width: calc(var(--wx-input-size) * 1.5);
            height: var(--wx-input-size);
            cursor: pointer;
            border: 1px solid var(--wx-input-border);
            overflow: hidden;

            &::-webkit-color-swatch-wrapper {
                padding: 2px;
            }
            &::-webkit-color-swatch {
                border: none;
                border-radius: calc(var(--wx-input-radius) - 2px);
            }

            &::-moz-color-swatch {
                border: none;
                border-radius: calc(var(--wx-input-radius) - 2px);
            }
        }
    }
}
</style>
