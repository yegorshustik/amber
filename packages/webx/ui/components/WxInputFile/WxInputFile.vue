<script setup lang="ts">
import { computed, onBeforeMount, ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { WxLocalesList, WxLocalizedValue } from '@/types/locale';

import { wxConfirm } from '@/utils';
import { wxFilemanager, wxLoadFile } from '@/utils/filemanager';
import type { WxFilemanagerFile } from '../../widgets/WxFilemanager/types';
import WxAction from '../WxAction/WxAction.vue';
import WxActions from '../WxActions/WxActions.vue';
import WxButton from '../WxButton/WxButton.vue';
import WxButtons from '../WxButtons/WxButtons.vue';
import WxDialog from '../WxDialog/WxDialog.vue';
import WxFormControl from '../WxFormControl/WxFormControl.vue';
import WxInput from '../WxInput/WxInput.vue';
import WxLocales from '../WxLocales/WxLocales.vue';
import WxSortable from '../WxSortable/WxSortable.vue';

import type { WxInputFileProps, WxSingleFile } from './types';

const props = withDefaults(defineProps<WxInputFileProps>(), {
    localized: false,
    multiple: false,
});

const emit = defineEmits(['update:modelValue', 'change']);

const currentValue = ref<WxSingleFile | WxSingleFile[] | WxLocalizedValue<WxSingleFile> | WxLocalizedValue<WxSingleFile[]>>(
    props.modelValue ?? props.value,
);
const editDialog = ref<boolean>(false);
const editFile = ref<WxSingleFile | WxSingleFile[] | WxLocalizedValue<WxSingleFile> | WxLocalizedValue<WxSingleFile[]>>(null);

const reloadFiles = () => {
    if (props.multiple) {
        if (props.localized) {
            let ids = [];

            if (!currentValue.value) {
                currentValue.value = {};
            }

            for (const localeCode in currentValue.value) {
                if (currentValue.value[localeCode].length > 0) {
                    currentValue.value[localeCode].map((image) => {
                        if (image.src.id > 0) {
                            ids.push(image.src.id);
                        }
                    });
                }
            }

            ids = ids.filter((item, index) => ids.indexOf(item) === index);

            if (ids.length > 0) {
                wxLoadFile(ids).then((files: WxFilemanagerFile[]) => {
                    for (const localeCode in currentValue.value) {
                        if (currentValue.value[localeCode].length > 0) {
                            for (let i = 0; i < currentValue.value[localeCode].length; i++) {
                                const file_index = files.findIndex((item) => item.id === currentValue.value[localeCode][i].src.id);

                                currentValue.value[localeCode][i] = {
                                    ...currentValue.value[localeCode][i],
                                    src: files[file_index],
                                };
                            }
                        }
                    }
                });
            }
        } else {
            const ids = [];

            if (!currentValue.value) {
                currentValue.value = [];
            }

            Object.keys(currentValue.value).map((index) =>
                currentValue.value[index]?.src?.id > 0 ? ids.push(currentValue.value[index].src.id) : null,
            );

            if (ids.length > 0) {
                wxLoadFile(ids).then((files: WxFilemanagerFile | WxFilemanagerFile[]) => {
                    if ((files as WxFilemanagerFile).id) {
                        files = [files] as WxFilemanagerFile[];
                    }

                    Object.keys(currentValue.value).map((index) => {
                        const file_index = (files as WxFilemanagerFile[]).findIndex((item) => item.id === currentValue.value[index].src.id);
                        currentValue.value[index].src = files[file_index];

                        if (!currentValue.value[index].title) {
                            currentValue.value[index].title = null;
                        }
                    });
                });
            }
        }
    } else {
        if (props.localized) {
            const ids = [];

            if (!currentValue.value) {
                currentValue.value = {};
            }

            Object.keys(currentValue.value).map((locale) =>
                currentValue.value[locale].src.id > 0 ? ids.push(currentValue.value[locale].src.id) : null,
            );

            if (ids.length > 0) {
                wxLoadFile(ids).then((files: WxFilemanagerFile[]) => {
                    Object.keys(currentValue.value).map((locale) => {
                        const index = files.findIndex((item) => item.id === currentValue.value[locale].src.id);
                        currentValue.value[locale].src = files[index];
                        if (!currentValue.value[locale].alt) {
                            currentValue.value[locale].alt = null;
                        }

                        if (!currentValue.value[locale].title) {
                            currentValue.value[locale].title = null;
                        }
                    });
                });
            }
        } else {
            const file = currentValue.value as WxSingleFile;

            if (file?.src?.id && file?.src?.id > 0) {
                wxLoadFile(file?.src?.id).then((file: WxFilemanagerFile) => {
                    (currentValue.value as WxSingleFile) = {
                        src: file,
                        title: (currentValue.value as WxSingleFile)?.title || null,
                    };
                });
            }
        }
    }
};

onBeforeMount(async () => reloadFiles());

watch(
    () => props.modelValue,
    async (value) => {
        currentValue.value = value;
        reloadFiles();
    },
);
watch(
    () => props.value,
    async (value) => {
        currentValue.value = value;
        reloadFiles();
    },
);

const isFilledLocale = (locale: WxLocalesList) => {
    const current = currentValue.value as WxLocalizedValue<WxSingleFile>;

    if (!current) {
        return false;
    }

    return current[locale.code] != null;
};

const isFilled = computed(() => {
    if (props.multiple) {
        return (currentValue.value as WxSingleFile[]).length > 0;
    }

    if (props.localized) {
        const val = currentValue.value as WxLocalizedValue<WxSingleFile>;
        return val && Object.values(val).some((v) => v && String(v).length > 0);
    }

    return String(currentValue.value ?? '').length > 0;
});

const cssClasses = computed(() => ({
    'cursor-pointer': !isFilled.value,
    filled: isFilled.value,
}));

const getPlaceholder = (locale: WxLocalesList = null): string => {
    if (locale) {
        const current = currentValue.value as WxLocalizedValue<WxSingleFile>;

        if (!current) {
            return props.placeholder || $t('select-file');
        }

        if (current[locale.code]) {
            return (current[locale.code].title || current[locale.code].src?.name) as string;
        }
    } else {
        if (currentValue.value) {
            const current = currentValue.value as WxSingleFile;
            return useLocalesStore().selectLocalizedValue(current.title as WxLocalizedValue, current.src?.name);
        }
    }

    return props.placeholder || $t('select-file');
};

const getPlaceholderFromFile = (file: WxSingleFile): string => {
    if (props.localized) {
        return (file.title || file.src?.name) as string;
    }

    return useLocalesStore().selectLocalizedValue(file.title as WxLocalizedValue, file.src?.name);
};

const callFilemanager = (locale: WxLocalesList = null) => {
    wxFilemanager({
        multiple: props.multiple,
    }).then((file: WxFilemanagerFile | WxFilemanagerFile[]) => {
        if (props.multiple) {
            if (locale) {
                if (!currentValue.value) {
                    currentValue.value = {};
                }

                const files: WxSingleFile[] = (currentValue.value[locale.code] as WxSingleFile[]) || [];

                for (const item of file as WxFilemanagerFile[]) {
                    files.push({
                        src: item,
                        title: null,
                    } as WxSingleFile);
                }

                currentValue.value[locale.code] = files;
            } else {
                if (!currentValue.value) {
                    currentValue.value = [];
                }
                const files: WxSingleFile[] = currentValue.value as WxSingleFile[];
                for (const item of file as WxFilemanagerFile[]) {
                    files.push({
                        src: item,
                        title: null,
                    } as WxSingleFile);
                }

                currentValue.value = files;
            }
        } else {
            if (locale) {
                if (!currentValue.value) {
                    currentValue.value = {};
                }

                currentValue.value[locale.code] = {
                    src: file,
                    title: (currentValue.value as WxLocalizedValue<WxSingleFile>)[locale.code]?.title || null,
                } as WxSingleFile;
            } else {
                currentValue.value = {
                    src: file,
                    title: (currentValue.value as WxSingleFile)?.title || null,
                } as WxSingleFile;
            }
        }

        emit('update:modelValue', currentValue.value);
        emit('change', currentValue.value);
    });
};

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const handleSorting = (items: WxSingleFile[], locale: WxLocalesList = null) => {
    if (items) {
        emit('update:modelValue', currentValue.value);
        emit('change', currentValue.value);
    }
};

const handleEditFile = (
    file: WxSingleFile | WxSingleFile[] | WxLocalizedValue<WxSingleFile> | WxLocalizedValue<WxSingleFile[]>,
    locale: WxLocalesList = null,
) => {
    editDialog.value = true;
    if (locale) {
        editFile.value = file[locale.code];
    } else {
        editFile.value = file;
    }
};

const handleRemoveFile = (locale: WxLocalesList = null) => {
    wxConfirm().then(() => {
        if (locale) {
            if (currentValue.value[locale.code]) {
                currentValue.value[locale.code] = null;
            }
        } else {
            currentValue.value = null;
        }

        emit('update:modelValue', currentValue.value);
        emit('change', currentValue.value);
    });
};
const handleRemoveMultipleFile = (file: WxSingleFile, locale: WxLocalesList = null) => {
    wxConfirm().then(() => {
        if (locale) {
            if (currentValue.value[locale.code]) {
                const index = (currentValue.value[locale.code] as WxSingleFile[]).findIndex((item) => item.src.id === file.src.id);

                if (index !== -1) {
                    (currentValue.value[locale.code] as WxSingleFile[]).splice(index, 1);
                }
            }
        } else {
            const index = (currentValue.value as WxSingleFile[]).findIndex((item) => item.src.id === file.src.id);

            if (index !== -1) {
                (currentValue.value as WxSingleFile[]).splice(index, 1);
            }
        }

        emit('update:modelValue', currentValue.value);
        emit('change', currentValue.value);
    });
};

const handleFileUrl = (file: WxSingleFile) => {
    window.open(file.src.url, '_blank');
};
</script>

<template>
    <template v-if="props.multiple">
        <template v-if="props.localized">
            <wx-locales type="vertical">
                <template #item="{ locale }">
                    <div class="wx-input-files d-flex flex-column gap-8">
                        <div class="wx-input-files__items">
                            <wx-sortable v-model="currentValue[locale.code] as WxSingleFile" @sorted="(items) => handleSorting(items, locale)">
                                <template #content="{ item, index }: { item: any; index: number }">
                                    <template v-if="props.name">
                                        <input
                                            :name="`${props.name}[${locale.code}][${index}][src][id]`"
                                            :value="(item as WxSingleFile).src.id"
                                            type="hidden"
                                        />
                                        <input
                                            :name="`${props.name}[${locale.code}][${index}][src][path]`"
                                            :value="(item as WxSingleFile).src.path"
                                            type="hidden"
                                        />
                                        <input
                                            :name="`${props.name}[${locale.code}][${index}][title]`"
                                            :value="(item as WxSingleFile).title"
                                            type="hidden"
                                        />
                                    </template>

                                    <div
                                        class="wx-input-files__placeholder cursor-pointer"
                                        :title="getPlaceholderFromFile(item)"
                                        @click="handleEditFile(item)"
                                    >
                                        {{ getPlaceholderFromFile(item) }}
                                    </div>
                                </template>
                                <template #actions="{ item }">
                                    <wx-actions>
                                        <wx-action type="sort" class="handle" />
                                        <wx-action type="link" @click="handleFileUrl(item)" />
                                        <wx-action type="edit" @click="handleEditFile(item)" />
                                        <wx-action type="remove" @click="handleRemoveMultipleFile(item, locale)" />
                                    </wx-actions>
                                </template>
                            </wx-sortable>
                        </div>
                        <div class="wx-input-files__browse">
                            <wx-button @click="callFilemanager(locale)" theme="create"></wx-button>
                        </div>
                    </div>
                </template>
            </wx-locales>
        </template>
        <template v-else>
            <div class="wx-input-files d-flex flex-column gap-8">
                <div class="wx-input-files__items">
                    <wx-sortable v-model="currentValue" @sorted="(items) => handleSorting(items)">
                        <template #content="{ item, index }: { item: any; index: number }">
                            <template v-if="props.name">
                                <input :name="`${props.name}[${index}][src][id]`" :value="(item as WxSingleFile).src.id" type="hidden" />
                                <input :name="`${props.name}[${index}][src][path]`" :value="(item as WxSingleFile).src.path" type="hidden" />
                                <template v-for="(title, locale) in (item as WxSingleFile).title" :key="locale">
                                    <input :name="`${props.name}[${index}][title][${locale}]`" :value="title" type="hidden" />
                                </template>
                            </template>

                            <div
                                class="wx-input-file__placeholder flex-grow-1 cursor-pointer"
                                :title="getPlaceholderFromFile(item)"
                                @click="handleEditFile(item)"
                            >
                                {{ getPlaceholderFromFile(item) }}
                            </div>
                        </template>
                        <template #actions="{ item }">
                            <wx-actions>
                                <wx-action type="sort" class="handle" />
                                <wx-action type="link" @click="handleFileUrl(item)" />
                                <wx-action type="edit" @click="handleEditFile(item)" />
                                <wx-action type="remove" @click="handleRemoveMultipleFile(item)" />
                            </wx-actions>
                        </template>
                    </wx-sortable>
                </div>
                <div class="wx-input-files__browse">
                    <wx-button @click="callFilemanager()" theme="create"></wx-button>
                </div>
            </div>
        </template>
    </template>
    <template v-else>
        <template v-if="props.localized">
            <wx-locales>
                <template #item="{ locale }">
                    <template v-if="props.name">
                        <template v-if="isFilled">
                            <input
                                :name="`${props.name}[${locale.code}][src][id]`"
                                :value="(currentValue as WxLocalizedValue<WxSingleFile>)[locale.code].src.id"
                                type="hidden"
                            />
                            <input
                                :name="`${props.name}[${locale.code}][src][path]`"
                                :value="(currentValue as WxLocalizedValue<WxSingleFile>)[locale.code].src.path"
                                type="hidden"
                            />
                            <input
                                :name="`${props.name}[${locale.code}][title]`"
                                :value="(currentValue as WxLocalizedValue<WxSingleFile>)[locale.code].title"
                                type="hidden"
                            />
                        </template>
                        <template v-else>
                            <input :name="`${props.name}[${locale.code}]`" value="" type="hidden" />
                        </template>
                    </template>

                    <div
                        class="wx-input-file d-flex align-items-center"
                        :class="{ filled: isFilledLocale(locale) }"
                        @click="() => (!isFilledLocale(locale) ? callFilemanager(locale) : null)"
                    >
                        <div
                            class="wx-input-file__placeholder flex-grow-1"
                            :class="{ 'cursor-pointer': isFilledLocale(locale) }"
                            :title="getPlaceholder(locale)"
                            @click="() => (isFilledLocale(locale) ? callFilemanager(locale) : null)"
                        >
                            {{ getPlaceholder(locale) }}
                        </div>
                        <div class="wx-input-file__actions ms-auto">
                            <wx-actions>
                                <wx-action type="upload" @click="callFilemanager(locale)" />
                                <wx-action type="edit" v-if="isFilledLocale(locale)" @click="handleEditFile(currentValue, locale)" />
                                <wx-action type="remove" v-if="isFilledLocale(locale)" @click="handleRemoveFile(locale)" />
                            </wx-actions>
                        </div>
                    </div>
                </template>
            </wx-locales>
        </template>
        <template v-else>
            <template v-if="props.name">
                <template v-if="isFilled">
                    <input :name="`${props.name}[src][id]`" :value="(currentValue as WxSingleFile).src.id" type="hidden" />
                    <input :name="`${props.name}[src][path]`" :value="(currentValue as WxSingleFile).src.path" type="hidden" />
                    <template v-for="(title, locale) in (currentValue as WxSingleFile).title" :key="locale">
                        <input :name="`${props.name}[title][${locale}]`" :value="title" type="hidden" />
                    </template>
                </template>
                <template v-else>
                    <input :name="`${props.name}`" value="" type="hidden" />
                </template>
            </template>

            <div class="wx-input-file d-flex align-items-center" :class="cssClasses" @click="() => (!isFilled ? callFilemanager() : null)">
                <div
                    class="wx-input-file__placeholder flex-grow-1"
                    :class="{ 'cursor-pointer': isFilled }"
                    @click="() => (isFilled ? callFilemanager() : null)"
                >
                    {{ getPlaceholder() }}
                </div>
                <div class="wx-input-file__actions ms-auto">
                    <wx-actions class="wx-input-file__actions ms-auto">
                        <wx-action type="upload" @click="isFilled ? callFilemanager() : null" />
                        <wx-action type="edit" v-if="isFilled" @click="handleEditFile(currentValue)" />
                        <wx-action type="remove" v-if="isFilled" @click="handleRemoveFile()" />
                    </wx-actions>
                </div>
            </div>
        </template>
    </template>

    <wx-dialog v-model="editDialog" :title="$t('input.file.edit-file')">
        <div class="mb-16">
            {{ $t('filemanager.file') }}:
            <a :href="(editFile as WxSingleFile).src.url" target="_blank" class="fw-semibold">
                {{ (editFile as WxSingleFile).src.name }}
            </a>
        </div>

        <wx-form-control :title="$t('title')">
            <wx-input type="text" v-model="(editFile as WxSingleFile).title" :localized="!props.localized" />
        </wx-form-control>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button type="button" theme="blank" @click="editDialog = false">{{ $t('cancel') }}</wx-button>
                <wx-button type="button" theme="primary" @click="editDialog = false">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">
.wx-input-file {
    --wx-input-padding-y: 4px;

    width: 100%;
    gap: var(--wx-input-padding-y);
    min-height: var(--wx-input-size);
    padding: var(--wx-input-padding-y) var(--wx-input-padding-y) var(--wx-input-padding-y) var(--wx-input-padding-x);
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
        padding-right: calc(52px + var(--wx-input-padding-y));
    }

    :deep(.wx-action) {
        --wx-action-size: 32px;
    }

    &__placeholder {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;

        .wx-input-file:not(.filled) & {
            color: var(--wx-input-placeholder);
        }
    }

    &:hover {
        --wx-input-border: var(--wx-input-hover-border);
        --wx-input-background: var(--wx-input-hover-background);
        --wx-input-color: var(--wx-input-hover-color);
    }

    &.focus {
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

.wx-input-files {
    min-width: 0;

    &__items,
    :deep(.wx-sortable),
    :deep(.wx-sortable__content) {
        min-width: 0;
    }

    &__placeholder {
        min-width: 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
}
</style>
