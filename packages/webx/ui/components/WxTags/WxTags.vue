<script setup lang="ts">
import debounce from 'lodash/debounce';
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ApiResponse } from '@/types/api';
import type { WxLocalizedValue } from '@/types/locale';
import { api, wxConfirm } from '@/utils';

import WxButton from '../WxButton/WxButton.vue';
import WxButtons from '../WxButtons/WxButtons.vue';
import WxDialog from '../WxDialog/WxDialog.vue';
import WxFormControl from '../WxFormControl/WxFormControl.vue';
import WxInput from '../WxInput/WxInput.vue';
import type { WxTag, WxTagsProps } from './types';

const props = withDefaults(defineProps<WxTagsProps>(), {});

const emit = defineEmits(['update:modelValue']);

const currentValue = ref<WxTag[]>(props.modelValue || props.value || []);
const searchQuery = ref('');
const suggestions = ref<WxTag[]>([]);
const isLoading = ref<boolean>(false);
const editTagDialog = ref<boolean>(false);
const editedTag = ref<WxTag>();
const activeIndex = ref(-1);

watch(searchQuery, (newVal) => fetchTags(newVal));

const fetchTags = debounce(async (query: string) => {
    const trimmedQuery = query.trim();
    if (trimmedQuery.length < 2) {
        suggestions.value = [];
        return;
    }

    isLoading.value = true;
    try {
        api.enableLoading.value = false;
        const response = await api.get<ApiResponse<WxTag[]>>(props.endpoint, { action: 'search', q: trimmedQuery });
        api.enableLoading.value = true;

        const tags = response.data as WxTag[];

        suggestions.value = tags.filter((tag: WxTag) => !currentValue.value.some((selected) => selected.id === tag.id));

        activeIndex.value = -1;
    } catch (error) {
        console.error('Ошибка при поиске тегов:', error);
    } finally {
        isLoading.value = false;
    }
}, 300);

const addTag = (tag: WxTag | string) => {
    if (typeof tag === 'string') {
        api.enableLoading.value = false;
        api.get<ApiResponse<WxTag>>(props.endpoint, { action: 'create', title: tag }).then((response) => {
            addTag(response.data as WxTag);
        });
        api.enableLoading.value = true;
    } else {
        if (!currentValue.value.some((t) => t.id === tag.id)) {
            currentValue.value.push(tag);
            emit('update:modelValue', currentValue.value);
        }

        searchQuery.value = '';
        suggestions.value = [];
        activeIndex.value = -1;
    }
};

const saveTag = (tag: WxTag) => {
    api.post<ApiResponse<WxTag>>(props.endpoint, { action: 'update', ...tag }).then(() => {
        editTagDialog.value = false;
        editedTag.value = null;
    });
};

const deleteTag = (tag: WxTag) => {
    wxConfirm().then(() => {
        api.post<ApiResponse<WxTag>>(props.endpoint, { action: 'destroy', ...tag }).then(() => {
            removeTag(tag);
            editTagDialog.value = false;
            editedTag.value = null;
        });
    });
};

const editTag = (tag: WxTag) => {
    editTagDialog.value = true;
    editedTag.value = tag;
};

const removeTag = (tag: WxTag) => {
    currentValue.value = currentValue.value.filter((t) => t.id !== tag.id);
    emit('update:modelValue', currentValue.value);
};

const handleKeyDown = (e: KeyboardEvent) => {
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeIndex.value = (activeIndex.value + 1) % (suggestions.value.length + 1);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeIndex.value = (activeIndex.value - 1 + (suggestions.value.length + 1)) % (suggestions.value.length + 1);
    } else if (e.key === 'Enter') {
        e.preventDefault();

        if (activeIndex.value >= 0 && activeIndex.value < suggestions.value.length) {
            addTag(suggestions.value[activeIndex.value]);
        } else if (searchQuery.value.trim().length >= 2) {
            addTag(searchQuery.value.trim());
        }
    } else if (e.key === 'Escape') {
        suggestions.value = [];
        activeIndex.value = -1;
    } else if (e.key === 'Backspace' && searchQuery.value === '' && currentValue.value.length > 0) {
        const newTags = [...currentValue.value];
        newTags.pop();

        currentValue.value = newTags;

        emit('update:modelValue', currentValue.value);
    }
};

const handleBlur = () => {
    setTimeout(() => (suggestions.value = []), 200);
};
</script>

<template>
    <template v-if="props.name">
        <template v-if="currentValue.length > 0">
            <template v-for="(tag, index) in currentValue" :key="`t_${index}`">
                <input :name="`${props.name}[${index}][id]`" :value="(tag as WxTag).id" type="hidden" />
                <input :name="`${props.name}[${index}][slug]`" :value="(tag as WxTag).slug" type="hidden" />
                <template v-for="(title, locale) in (tag as WxTag).title" :key="locale">
                    <input :name="`${props.name}[${index}][name][${locale}]`" :value="title" type="hidden" />
                </template>
            </template>
        </template>
        <template v-else>
            <input :name="`${props.name}`" value="" type="hidden" />
        </template>
    </template>

    <div class="wx-tags" :class="{ filled: currentValue.length > 0 }">
        <div class="wx-tags__inner d-flex flex-wrap gap-4">
            <div v-for="tag in currentValue" :key="tag.id" class="wx-tag">
                <span class="cursor-pointer" @click="editTag(tag)">
                    {{ useLocalesStore().selectLocalizedValue(tag.title as WxLocalizedValue) }}
                </span>
                <button type="button" class="wx-tag__remove" @click.stop="removeTag(tag)">&times;</button>
            </div>

            <input
                ref="input"
                v-model="searchQuery"
                class="wx-tags-input"
                :placeholder="props.placeholder"
                @keydown="handleKeyDown"
                @blur="handleBlur"
            />
        </div>

        <ul v-if="searchQuery.trim().length >= 2 && suggestions.length > 0" class="wx-tags__suggestions rounded bg-white shadow">
            <li v-for="(tag, index) in suggestions" :key="tag.id" :class="{ active: index === activeIndex }" @click="addTag(tag)">
                {{ useLocalesStore().selectLocalizedValue(tag.title as WxLocalizedValue) }}
            </li>

            <li v-if="isLoading" class="p-4">{{ $t('searching') }}</li>
        </ul>
    </div>

    <wx-dialog v-model="editTagDialog" :title="$t('edit')" :size="500">
        <wx-form-control :title="$t('title')">
            <wx-input v-model="editedTag.title" localized />
        </wx-form-control>
        <wx-form-control :title="$t('slug')">
            <wx-input v-model="editedTag.slug" />
        </wx-form-control>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button
                    theme="blank"
                    @click="
                        () => {
                            editTagDialog = false;
                            editedTag = null;
                        }
                    "
                >
                    {{ $t('cancel') }}
                </wx-button>
                <wx-button theme="danger" @click="() => deleteTag(editedTag)" class="w-100 max-w-128">
                    {{ $t('remove') }}
                </wx-button>
                <wx-button theme="primary" @click="() => saveTag(editedTag)" class="w-100 max-w-128">
                    {{ $t('save') }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">
.wx-tags {
    --wx-input-padding-y: 4px;
    --wx-input-padding-x: 4px;

    position: relative;

    &__suggestions {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        padding: 4px;
        margin: 0;
        list-style: none;

        :deep(li) {
            padding: 3px 8px;
            cursor: pointer;
            transition: background-color 200ms var(--wx-easing);
            border-radius: calc(var(--wx-border-radius) - 4px);

            &.active {
                background-color: var(--wx-light-gray);
            }
        }
    }

    &__inner {
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

        input {
            min-width: 64px;
            flex-grow: 1;
            border: none;
            padding-left: 8px;
            background-color: transparent;
            color: var(--wx-input-color);
        }

        input::placeholder {
            color: var(--wx-input-placeholder);
        }

        .wx-tags:hover & {
            --wx-input-border: var(--wx-input-hover-border);
            --wx-input-background: var(--wx-input-hover-background);
            --wx-input-color: var(--wx-input-hover-color);
        }

        .wx-tags:has(input:focus) &,
        .wx-tags.focus & {
            --wx-input-border: var(--wx-input-focus-border);
            --wx-input-background: var(--wx-input-focus-background);
            --wx-input-color: var(--wx-input-focus-color);
        }

        .wx-tags.filled & {
            --wx-input-border: var(--wx-input-filled-border);
            --wx-input-background: var(--wx-input-filled-background);
            --wx-input-color: var(--wx-input-filled-color);
        }

        .wx-tags.invalid & {
            --wx-input-border: var(--wx-input-invalid-border);
            --wx-input-background: var(--wx-input-invalid-background);
            --wx-input-color: var(--wx-input-invalid-color);
        }
    }
}

.wx-tag {
    align-items: center;
    background: var(--wx-primary-light, #eef6ff);
    color: var(--wx-primary, #007bff);
    padding: 3px 12px;
    border-radius: calc(var(--wx-input-radius) - 2px);
    margin-right: 2px;

    &__remove {
        border: none;
        background: none;
        margin-left: 6px;
        cursor: pointer;
        color: var(--wx-primary, #007bff);

        &:hover {
            color: var(--wx-primary-hover, #007bff);
        }
    }
}
</style>
