<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import WxIcon from '../WxIcon';
import type { WxCheckProps } from './types';

const props = withDefaults(defineProps<WxCheckProps>(), {
    value: '1',
    type: 'checkbox',
    checked: false,
    binary: false,
});

const emit = defineEmits(['update:modelValue', 'change']);

const currentState = ref(props.modelValue || props.checked);

watch(
    () => props.checked,
    (state) => {
        currentState.value = state;
    },
    { deep: true },
);

const isChecked = computed(() => {
    if (props.type === 'radio') {
        if (props.checked === true || props.checked === false) {
            return props.checked;
        }

        return props.modelValue === props.value;
    }

    if (props.type === 'checkbox' && props.modelValue !== undefined && props.modelValue !== null) {
        return props.modelValue == props.value;
    }

    return currentState.value;
});

const currentValue = () => {
    return props.value;
};

const changeEvent = (e: Event) => {
    if (props.disabled) return;

    if (props.type === 'radio') {
        emit('update:modelValue', props.value);
        emit('change', props.value);
    } else {
        const state = e.target instanceof HTMLInputElement && e.target.checked ? (props.value === '1' ? true : props.value) : '';
        emit('update:modelValue', state);
        emit('change', state);
    }
};
</script>

<template>
    <label v-if="type === 'checkbox'" class="wx-checkbox" :class="[props.class, { checked: isChecked, disabled: disabled }]">
        <input
            type="checkbox"
            :name="name"
            :value="currentValue()"
            :checked="isChecked"
            class="wx-native-hidden"
            @change="changeEvent"
            tabindex="-1"
        />
        <span class="wx-checkbox__box">
            <wx-icon name="check" />
        </span>
        <span v-if="label" class="wx-checkbox__label">{{ label }}</span>
    </label>
    <label v-if="type === 'radio'" class="wx-radio" :class="[props.class, { checked: isChecked, disabled: disabled }]">
        <input type="radio" :name="name" :value="currentValue()" :checked="isChecked" class="wx-native-hidden" @change="changeEvent" tabindex="-1" />
        <span class="wx-radio__box">
            <wx-icon name="circle" />
        </span>
        <span v-if="label" class="wx-radio__label">{{ label }}</span>
    </label>
    <label v-if="type === 'switch'" class="wx-switch" :class="[props.class, { checked: isChecked, disabled: disabled }]">
        <input
            type="checkbox"
            :name="name"
            :value="currentValue()"
            :checked="isChecked"
            class="wx-native-hidden"
            @change="changeEvent"
            tabindex="-1"
        />
        <span class="wx-switch__box">
            <wx-icon name="circle" />
        </span>
        <span v-if="label" class="wx-switch__label">{{ label }}</span>
    </label>
</template>

<style scoped lang="scss">
.wx-switch,
.wx-radio,
.wx-checkbox {
    --wx-check-size: 22px;
    --wx-check-padding: 4px;
    --wx-check-radius: 8px;

    --wx-check-border: var(--wx-check-default-border);
    --wx-check-background: var(--wx-check-default-background);
    --wx-check-color: var(--wx-check-default-color);

    --wx-check-default-border: var(--wx-input-border);
    --wx-check-default-background: var(--wx-white);
    --wx-check-default-color: var(--wx-dark);

    --wx-check-hover-border: var(--wx-input-hover-border);
    --wx-check-hover-background: var(--wx-white);
    --wx-check-hover-color: var(--wx-dark);

    --wx-check-checked-border: var(--wx-primary);
    --wx-check-checked-background: var(--wx-white);
    --wx-check-checked-color: var(--wx-primary);

    display: inline-flex;
    column-gap: 4px;
    align-items: center;
    cursor: pointer;
    user-select: none;

    &:hover {
        --wx-check-border: var(--wx-check-hover-border);
        --wx-check-background: var(--wx-check-hover-background);
        --wx-check-color: var(--wx-check-hover-color);
    }

    &:has(:checked) {
        --wx-check-border: var(--wx-check-checked-border);
        --wx-check-background: var(--wx-check-checked-background);
        --wx-check-color: var(--wx-check-checked-color);
    }

    .wx-native-hidden {
        appearance: auto;
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    &__box {
        display: inline-block;
        width: var(--wx-check-size);
        height: var(--wx-check-size);
        border: 1px solid var(--wx-check-border);
        border-radius: var(--wx-check-radius);
        background: var(--wx-check-background);
        color: var(--wx-check-color);
        transition: 200ms var(--wx-easing);

        svg {
            display: block;
            width: 100%;
            height: 100%;
            scale: 0;
            transition:
                scale 100ms var(--wx-easing),
                left 100ms var(--wx-easing);
        }

        .wx-switch:has(:checked) &,
        .wx-radio:has(:checked) &,
        .wx-checkbox:has(:checked) & {
            svg {
                scale: 1;
            }
        }
    }

    &__label {
        font-size: 14px;
        font-weight: 500;
    }
}

.wx-radio {
    &__box {
        --wx-check-radius: var(--wx-check-size);

        svg {
            border: var(--wx-check-padding) solid transparent;
        }
    }
}

.wx-switch {
    --wx-check-padding: 1px;
    --wx-check-default-color: var(--wx-secondary);
    --wx-check-hover-color: var(--wx-secondary);

    &__box {
        --wx-check-radius: var(--wx-check-size);

        position: relative;
        width: calc(var(--wx-check-size) * 1.75);

        svg {
            position: absolute;
            left: 0;
            scale: 1;
            width: calc(var(--wx-check-size) - var(--wx-check-padding) * 2);
            height: calc(var(--wx-check-size) - var(--wx-check-padding) * 2);
            border: var(--wx-check-padding) solid transparent;
        }

        .wx-switch:has(:checked) & {
            svg {
                left: calc(var(--wx-check-size) * 1.75 - (var(--wx-check-size) + var(--wx-check-padding)));
            }
        }
    }
}
</style>
