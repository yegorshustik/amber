<script setup lang="ts">
import Editor from '@tinymce/tinymce-vue';
import { ref, watch, nextTick, onUnmounted, onBeforeMount, inject, type Ref } from 'vue';
import type { WxLocalizedValue } from '@/types/locale';
import WxLocales from '../WxLocales/WxLocales.vue';
import type { WxTextareaProps } from './types';
import { useTinyMce } from './useTinyMce';
import type { ValidationError } from '@/types/api';

const props = withDefaults(defineProps<WxTextareaProps>(), {
    localized: false,
    wysiwyg: false,
    preset: 'minimal',
    disabled: false,
});
const errors = inject<Ref<ValidationError['errors']>>('wx-form-errors');

const { editorConfig, focusEditor } = useTinyMce(props);
const getEditorId = (localeCode: string) => `editor-${uid}-${localeCode}`;

const uid = Math.random().toString(36).substring(2, 9);

const emit = defineEmits(['update:modelValue', 'change', 'input', 'focus', 'blur']);

const currentValue = ref();
const inputRefs = ref<HTMLInputElement[]>([]);
const isFocused = ref<boolean>(false);

const initValue = () => {
    currentValue.value = props.modelValue ?? props.value ?? (props.localized ? {} : '');
};

onBeforeMount(() => {
    initValue();
});

onUnmounted(() => {});

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

const onLocaleChange = async (locale: any) => {
    await nextTick();
    if (props.wysiwyg) {
        focusEditor(uid, locale.code);
    } else {
        const el = inputRefs.value[locale.code];
        if (el) el.focus();
    }
};

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

const handleFocus = (e: FocusEvent) => {
    isFocused.value = true;
    emit('focus', e);
};

const handleBlur = (e: FocusEvent) => {
    isFocused.value = false;
    emit('blur', e);
};
const getPlaceholder = () => {
    return props.placeholder;
};
const getErrors = () => {
    return errors?.value[props.name] ?? [];
};
</script>

<template>
    <div class="wx-textarea-wrapper" :class="{ 'is-wysiwyg': wysiwyg }">
        <WxLocales type="tabs" v-if="localized" @change="onLocaleChange">
            <template #item="{ locale }">
                <template v-if="wysiwyg">
                    <textarea
                        class="wx-native-hidden"
                        v-if="props.name"
                        :name="`${props.name}[${locale.code}]`"
                        v-model="(currentValue as WxLocalizedValue)[locale.code]"
                    />
                    <Editor
                        :id="getEditorId(locale.code)"
                        :init="editorConfig"
                        apiKey="gpl"
                        licenseKey="gpl"
                        :disabled="disabled"
                        :model-value="(currentValue as WxLocalizedValue)[locale.code] || ''"
                        @update:model-value="handleInput($event, locale.code)"
                    />
                </template>

                <textarea
                    v-else
                    :ref="(el) => (inputRefs[locale.code] = el as HTMLInputElement)"
                    class="wx-textarea"
                    :name="props.name ? `${props.name}[${locale.code}]` : undefined"
                    :value="(currentValue as WxLocalizedValue)[locale.code] || ''"
                    :placeholder="getPlaceholder()"
                    :disabled="disabled"
                    @input="handleInput(($event.target as HTMLInputElement).value, locale.code)"
                    @focus="handleFocus"
                    @blur="(e) => handleBlur(e)"
                    @change="emit('change', $event)"
                ></textarea>
            </template>
        </WxLocales>

        <template v-else>
            <template v-if="wysiwyg">
                <textarea class="wx-native-hidden" v-if="props.name" :name="props.name" v-model="currentValue" />
                <Editor
                    :init="editorConfig"
                    apiKey="gpl"
                    licenseKey="gpl"
                    :disabled="disabled"
                    :model-value="currentValue as string"
                    @update:model-value="handleInput($event)"
                />
            </template>
            <textarea
                v-else
                ref="textareaRefs.default"
                class="wx-textarea"
                :value="currentValue as string"
                :placeholder="getPlaceholder()"
                :disabled="disabled"
                :name="props.name"
                @input="handleInput(($event.target as HTMLInputElement).value)"
                @focus="handleFocus"
                @blur="(e) => handleBlur(e)"
                @change="emit('change', $event)"
            ></textarea>
        </template>

        <div v-if="getErrors().length > 0" class="wx-input-wrapper__errors d-flex flex-column fs-12px text-danger mt-2 gap-2 ps-12">
            <div v-for="message in getErrors()" :key="message">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-textarea-wrapper {
    width: 100%;

    .wx-textarea {
        width: 100%;
        height: 150px;
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
    }

    /* Убираем стандартную рамку TinyMCE, чтобы заменить на свою */
    :deep(.tox-tinymce) {
        border: 1px solid var(--wx-input-border, #ddd) !important;
        border-radius: var(--wx-input-radius, 8px);
        transition: border-color 0.2s;

        &:focus-within {
            border-color: var(--wx-input-focus-border, #007bff) !important;
        }
    }
}
</style>
