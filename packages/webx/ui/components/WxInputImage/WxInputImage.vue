<script setup lang="ts">
import { onBeforeMount, ref, watch } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import { $t } from '@/locales';
import type { WxLocalizedValue } from '@/types/locale';
import type { WxLocalesList } from '@/types/locale';
import { api, wxConfirm } from '@/utils';
import { wxFilemanager, wxLoadFile } from '@/utils/filemanager';
import type { WxFilemanagerFile } from '../../widgets/WxFilemanager/types';
import { useFileUploader } from '../../widgets/WxFilemanager/useFileUploader';
import WxAction from '../WxAction/WxAction.vue';
import WxButton from '../WxButton/WxButton.vue';
import WxButtons from '../WxButtons/WxButtons.vue';
import WxDialog from '../WxDialog/WxDialog.vue';
import WxFormControl from '../WxFormControl/WxFormControl.vue';
import WxIcon from '../WxIcon/WxIcon.vue';
import WxInput from '../WxInput/WxInput.vue';
import WxLocales from '../WxLocales/WxLocales.vue';
import type { WxInputImageProps, WxSingleImage } from './types';

const props = withDefaults(defineProps<WxInputImageProps>(), {
    localized: false,
    multiple: false,
});

const emit = defineEmits(['update:modelValue', 'change']);

const currentValue = ref<WxSingleImage | WxSingleImage[] | WxLocalizedValue<WxSingleImage> | WxLocalizedValue<WxSingleImage[]>>(
    props.modelValue ?? props.value ?? [],
);

const editImageDialog = ref(false);
const editImage = ref<WxSingleImage>();

const directUploadInput = ref();
const selectedLocale = ref<WxLocalesList>();

const reloadImages = () => {
    if (props.direct) {
        if (props.multiple) {
            if (props.localized) {
                if (!currentValue.value) {
                    currentValue.value = {};
                }
            }
        }

        return;
    }

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
                currentValue.value = {};
            }

            Object.keys(currentValue.value).map((index) =>
                currentValue.value[index].src.id > 0 ? ids.push(currentValue.value[index].src.id) : null,
            );

            if (ids.length > 0) {
                wxLoadFile(ids).then((files: WxFilemanagerFile[]) => {
                    Object.keys(currentValue.value).map((index) => {
                        const file_index = files.findIndex((item) => item.id === currentValue.value[index].src.id);
                        currentValue.value[index].src = files[file_index];
                        if (!currentValue.value[index].alt) {
                            currentValue.value[index].alt = null;
                        }

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
                currentValue.value[locale]?.src.id > 0 ? ids.push(currentValue.value[locale].src.id) : null,
            );

            if (ids.length > 0) {
                wxLoadFile(ids).then((files: WxFilemanagerFile | WxFilemanagerFile[]) => {
                    Object.keys(currentValue.value).map((locale) => {
                        if (currentValue.value[locale]) {
                            if ((files as WxFilemanagerFile).id) {
                                currentValue.value[locale].src = files;
                            } else {
                                const index = (files as WxFilemanagerFile[]).findIndex((item) => item.id === currentValue.value[locale].src.id);
                                currentValue.value[locale].src = files[index];
                            }

                            if (!currentValue.value[locale].alt) {
                                currentValue.value[locale].alt = null;
                            }

                            if (!currentValue.value[locale].title) {
                                currentValue.value[locale].title = null;
                            }
                        }
                    });
                });
            }
        } else {
            const image = currentValue.value as WxSingleImage;
            if (image?.src?.id && image?.src?.id > 0) {
                wxLoadFile([image.src.id]).then((file: WxFilemanagerFile) => {
                    (currentValue.value as WxSingleImage) = {
                        src: file,
                        alt: image?.alt ?? null,
                        title: image?.title ?? null,
                    };
                });
            }
        }
    }
};

onBeforeMount(async () => reloadImages());

watch(
    () => props.modelValue,
    async (value) => {
        currentValue.value = value;
        reloadImages();
    },
);
watch(
    () => props.value,
    async (value) => {
        currentValue.value = value;
        reloadImages();
    },
);
watch(
    () => editImageDialog.value,
    (state) => {
        if (state === false) {
            if (!props.multiple && !props.localized) {
                emit('update:modelValue', editImage.value);
                emit('change', editImage.value);
            }
        }
    },
);

/*
 * Single image
 */
const applySingleImage = (file: WxFilemanagerFile): WxSingleImage => {
    const currentImage = currentValue.value as WxSingleImage;

    const newImage = {
        src: file,
        alt: currentImage?.alt ?? null,
        title: currentImage?.title ?? null,
    };
    emit('update:modelValue', newImage);
    emit('change', newImage);

    return newImage;
};

const getAppliedSingleImage = (): WxSingleImage => {
    const image = currentValue.value as WxSingleImage;

    if (image && image.src && image.src.path) {
        return image;
    }

    return null;
};

const handleSingleImage = () => {
    if (props.direct) {
        directUploadInput.value.click();
    } else {
        wxFilemanager().then((file: WxFilemanagerFile) => {
            currentValue.value = applySingleImage(file);
        });
    }
};

const handleEditSingleImage = (image: WxSingleImage) => {
    editImageDialog.value = true;
    editImage.value = image;
};

const handleRemoveSingleImage = () => {
    wxConfirm().then(() => {
        currentValue.value = null;
    });
};

/*
 * Single localized image
 */
const getAppliedSingleLocalizedImage = (locale: WxLocalesList): WxSingleImage => {
    const image = currentValue.value as WxLocalizedValue<WxSingleImage>;

    return image?.[locale.code]?.src?.path ? image[locale.code] : null;
};

const handleSingleLocalizedImage = (locale: WxLocalesList) => {
    if (props.direct) {
        directUploadInput.value.click();
    } else {
        wxFilemanager().then((file: WxFilemanagerFile) => {
            currentValue.value = {
                ...(currentValue.value as WxLocalizedValue<WxSingleImage>),
                [locale.code]: applySingleImage(file),
            };
            emit('update:modelValue', currentValue.value);
            emit('change', currentValue.value);
        });
    }
};

const handleEditSingleLocalizedImage = (locale: WxLocalesList) => {
    editImageDialog.value = true;
    editImage.value = currentValue.value[locale.code];
};

const handleRemoveSingleLocalizedImage = (locale: WxLocalesList) => {
    wxConfirm().then(() => {
        currentValue.value[locale.code] = null;
    });
};

/*
 * Multiple image
 */
const handleMultipleImage = (locale: WxLocalesList = null) => {
    if (props.direct) {
        directUploadInput.value.click();
    } else {
        wxFilemanager({
            multiple: true,
        }).then((file: WxFilemanagerFile[]) => {
            if (locale) {
                if (!currentValue.value) {
                    currentValue.value = {};
                }

                const images: WxSingleImage[] = (currentValue.value[locale.code] as WxSingleImage[]) || [];

                for (const item of file as WxFilemanagerFile[]) {
                    images.push({
                        src: item,
                        alt: null,
                        title: null,
                    } as WxSingleImage);
                }

                currentValue.value[locale.code] = images;
            } else {
                currentValue.value = applyMultipleImage(file);
            }
        });
    }
};

const applyMultipleImage = (files: WxFilemanagerFile[]): WxSingleImage[] => {
    const images = (currentValue.value || []) as WxSingleImage[];

    for (const file of files) {
        images.push({
            src: file,
            alt: null,
            title: null,
        });
    }

    emit('update:modelValue', images);
    emit('change', images);

    return images;
};

const checkMoveMultipleImages = (event) => {
    if (event.related.classList.contains('locked')) {
        return false;
    }
};

const handleEditMultipleImage = (image: WxSingleImage) => {
    editImageDialog.value = true;
    editImage.value = image;
};

const handleRemoveMultipleImage = (image: WxSingleImage, index: number, locale: WxLocalesList = null) => {
    wxConfirm().then(() => {
        if (locale) {
            (currentValue.value as WxSingleImage[])[locale.code].splice(index, 1);
        } else {
            (currentValue.value as WxSingleImage[]).splice(index, 1);
        }
    });
};

/*
 * Direct upload
 */
const { uploadFiles } = useFileUploader();

const directUploadBegin = (event: Event) => {
    const target = event.target as HTMLInputElement;

    uploadFiles(target.files, api.prepareUrl('filemanager/temp/upload')).then((response) => {
        if (props.multiple) {
            if (props.localized) {
                if (!currentValue.value) {
                    currentValue.value = {};
                }

                const images: WxSingleImage[] = (currentValue.value[selectedLocale.value.code] as WxSingleImage[]) || [];

                for (const item of response.data as WxFilemanagerFile[]) {
                    images.push({
                        src: item,
                        alt: null,
                        title: null,
                    } as WxSingleImage);
                }

                currentValue.value[selectedLocale.value.code] = images;
                emit('update:modelValue', currentValue.value);
                emit('change', currentValue.value);
            } else {
                const images = (currentValue.value || []) as WxSingleImage[];

                for (const file of response.data) {
                    images.push({
                        src: file,
                        alt: null,
                        title: null,
                    });
                }
                currentValue.value = images;
                emit('update:modelValue', images);
                emit('change', images);
            }
        } else {
            if (props.localized) {
                currentValue.value = {
                    ...(currentValue.value as WxLocalizedValue<WxSingleImage>),
                    [selectedLocale.value.code]: applySingleImage(response.data[0]),
                };
            } else {
                currentValue.value = applySingleImage(response.data[0]);
            }
            emit('update:modelValue', currentValue.value);
            emit('change', currentValue.value);
        }
    });
};
</script>

<template>
    <template v-if="props.preview">
        <div class="wx-image-preview">
            <img :src="getAppliedSingleImage().src.url" />
        </div>
    </template>
    <template v-else>
        <template v-if="!props.multiple">
            <template v-if="!props.localized">
                <!--
                <div v-if="props.direct" class="wx-input-image d-flex align-items-center justify-content-center">
                    <div class="wx-input-image__overlay d-flex align-items-center justify-content-center" @click="() => directUploadInput.click()">
                        <input type="file" ref="directUploadInput" @change="directUploadBegin" class="wx-native-hidden" />
                        <div class="wx-input-image__placeholder">
                            {{ $t('input.image.placeholder') }}
                        </div>
                    </div>
                </div>
                v-else
                -->
                <div class="wx-input-image d-flex align-items-center justify-content-center" :class="{ filled: getAppliedSingleImage() }">
                    <input v-if="props.direct" type="file" ref="directUploadInput" @change="directUploadBegin" class="wx-native-hidden" />

                    <template v-if="props.name">
                        <template v-if="getAppliedSingleImage()">
                            <input :name="`${props.name}[src][id]`" :value="getAppliedSingleImage().src.id" type="hidden" />
                            <input :name="`${props.name}[src][path]`" :value="getAppliedSingleImage().src.path" type="hidden" />

                            <template v-for="(alt, locale) in getAppliedSingleImage().alt" :key="locale">
                                <input :name="`${props.name}[alt][${locale}]`" :value="alt" type="hidden" />
                            </template>
                            <template v-for="(title, locale) in getAppliedSingleImage().title" :key="locale">
                                <input :name="`${props.name}[title][${locale}]`" :value="title" type="hidden" />
                            </template>
                        </template>
                        <template v-else>
                            <input :name="`${props.name}`" value="" type="hidden" />
                        </template>
                    </template>
                    <div class="wx-input-image__overlay d-flex align-items-center justify-content-center" @click="handleSingleImage()">
                        <div v-if="getAppliedSingleImage()" class="wx-input-image__image">
                            <img :src="getAppliedSingleImage().src.url" />
                        </div>
                        <div v-else class="wx-input-image__placeholder">
                            {{ $t('input.image.placeholder') }}
                        </div>
                    </div>
                    <div v-if="getAppliedSingleImage()" class="wx-input-image__actions d-flex align-items-center justify-content-center gap-8 p-8">
                        <wx-action type="edit" @click="handleEditSingleImage(getAppliedSingleImage())" />
                        <wx-action type="remove" @click="handleRemoveSingleImage()" />
                    </div>
                </div>
            </template>
            <template v-else>
                <input v-if="props.direct" type="file" ref="directUploadInput" @change="directUploadBegin" class="wx-native-hidden" />
                <wx-locales @loaded="(locale) => (selectedLocale = locale)" @change="(locale) => (selectedLocale = locale)">
                    <template #item="{ locale }">
                        <div
                            class="wx-input-image d-flex align-items-center justify-content-center"
                            :class="{ filled: getAppliedSingleLocalizedImage(locale) }"
                        >
                            <template v-if="props.name">
                                <template v-if="getAppliedSingleLocalizedImage(locale)">
                                    <input
                                        :name="`${props.name}[${locale.code}][src][id]`"
                                        :value="getAppliedSingleLocalizedImage(locale).src.id"
                                        type="hidden"
                                    />
                                    <input
                                        :name="`${props.name}[${locale.code}][src][path]`"
                                        :value="getAppliedSingleLocalizedImage(locale).src.path"
                                        type="hidden"
                                    />

                                    <input
                                        :name="`${props.name}[${locale.code}][alt]`"
                                        :value="getAppliedSingleLocalizedImage(locale)?.alt"
                                        type="hidden"
                                    />
                                    <input
                                        :name="`${props.name}[${locale.code}][title]`"
                                        :value="getAppliedSingleLocalizedImage(locale)?.title"
                                        type="hidden"
                                    />
                                </template>
                                <template v-else>
                                    <input :name="`${props.name}[${locale.code}]`" value="" type="hidden" />
                                </template>
                            </template>
                            <div
                                class="wx-input-image__overlay d-flex align-items-center justify-content-center"
                                @click="handleSingleLocalizedImage(locale)"
                            >
                                <div v-if="getAppliedSingleLocalizedImage(locale)" class="wx-input-image__image">
                                    <img :src="getAppliedSingleLocalizedImage(locale).src.url" />
                                </div>
                                <div v-else class="wx-input-image__placeholder">
                                    {{ $t('input.image.placeholder') }}
                                </div>
                            </div>
                            <div
                                v-if="getAppliedSingleLocalizedImage(locale)"
                                class="wx-input-image__actions d-flex align-items-center justify-content-center gap-8 p-8"
                            >
                                <wx-action type="edit" @click="handleEditSingleLocalizedImage(locale)" />
                                <wx-action type="remove" @click="handleRemoveSingleLocalizedImage(locale)" />
                            </div>
                        </div>
                    </template>
                </wx-locales>
            </template>
        </template>
        <template v-else>
            <template v-if="props.localized">
                <input
                    v-if="props.direct"
                    type="file"
                    multiple
                    ref="directUploadInput"
                    accept="image/*"
                    @change="directUploadBegin"
                    class="wx-native-hidden"
                />

                <wx-locales type="tabs" @loaded="(locale) => (selectedLocale = locale)" @change="(locale) => (selectedLocale = locale)">
                    <template #item="{ locale }">
                        <template v-if="props.name">
                            <template v-if="currentValue[(locale as WxLocalesList).code]?.length > 0">
                                <template v-for="(image, index) in currentValue[(locale as WxLocalesList).code]" :key="`src_${index}`">
                                    <input
                                        :name="`${props.name}[${locale.code}][${index}][src][id]`"
                                        :value="(image as WxSingleImage).src.id"
                                        type="hidden"
                                    />
                                    <input
                                        :name="`${props.name}[${locale.code}][${index}][src][path]`"
                                        :value="(image as WxSingleImage).src.path"
                                        type="hidden"
                                    />
                                    <input
                                        :name="`${props.name}[${locale.code}][${index}][alt]`"
                                        :value="(image as WxSingleImage).alt"
                                        type="hidden"
                                    />
                                    <input
                                        :name="`${props.name}[${locale.code}][${index}][title]`"
                                        :value="(image as WxSingleImage).title"
                                        type="hidden"
                                    />
                                </template>
                            </template>
                            <template v-else>
                                <input :name="`${props.name}[${locale.code}]`" value="" type="hidden" />
                            </template>
                        </template>

                        <VueDraggableNext
                            class="wx-input-images"
                            @change="
                                () => {
                                    emit('update:modelValue', currentValue);
                                    emit('change', currentValue);
                                }
                            "
                            handle=".wx-input-images__image"
                            :animation="150"
                            :move="checkMoveMultipleImages"
                            v-model="currentValue[(locale as WxLocalesList).code]"
                        >
                            <div class="wx-input-images__image" v-for="(image, index) in currentValue[locale.code]" :key="index">
                                <img class="wx-input-images__placeholder" :src="(image as WxSingleImage).src.url" alt="" />

                                <div class="wx-input-images__actions d-flex align-items-center justify-content-center gap-8 p-8">
                                    <wx-action type="edit" @click="handleEditMultipleImage(image as WxSingleImage)" />
                                    <wx-action type="remove" @click="handleRemoveMultipleImage(image as WxSingleImage, index as number, locale)" />
                                </div>
                            </div>

                            <div
                                @click="() => handleMultipleImage(locale)"
                                class="wx-input-images__add locked d-flex align-items-center justify-content-center"
                            >
                                <wx-icon name="add" />
                            </div>
                        </VueDraggableNext>
                    </template>
                </wx-locales>
            </template>
            <template v-else>
                <template v-if="props.name">
                    <template v-if="(currentValue as []).length > 0">
                        <template v-for="(image, index) in currentValue" :key="`src_${index}`">
                            <input :name="`${props.name}[${index}][src][id]`" :value="(image as WxSingleImage).src.id" type="hidden" />
                            <input :name="`${props.name}[${index}][src][path]`" :value="(image as WxSingleImage).src.path" type="hidden" />
                            <template v-for="(alt, locale) in (image as WxSingleImage).alt" :key="`src_alt_${locale}`">
                                <input :name="`${props.name}[${index}][alt][${locale}]`" :value="alt" type="hidden" />
                            </template>
                            <template v-for="(title, locale) in (image as WxSingleImage).title" :key="`src_title_${locale}`">
                                <input :name="`${props.name}[${index}][title][${locale}]`" :value="title" type="hidden" />
                            </template>
                        </template>
                    </template>
                    <template v-else>
                        <input :name="`${props.name}`" value="" type="hidden" />
                    </template>
                </template>

                <input
                    v-if="props.direct"
                    type="file"
                    multiple
                    ref="directUploadInput"
                    accept="image/*"
                    @change="directUploadBegin"
                    class="wx-native-hidden"
                />

                <VueDraggableNext
                    class="wx-input-images"
                    @change="
                        () => {
                            emit('update:modelValue', currentValue);
                            emit('change', currentValue);
                        }
                    "
                    handle=".wx-input-images__image"
                    :animation="150"
                    :move="checkMoveMultipleImages"
                    v-model="currentValue as any"
                >
                    <div class="wx-input-images__image" v-for="(image, index) in currentValue" :key="index">
                        <img class="wx-input-images__placeholder" :src="(image as WxSingleImage).src.url" alt="" />

                        <div class="wx-input-images__actions d-flex align-items-center justify-content-center gap-8 p-8">
                            <wx-action type="edit" @click="handleEditMultipleImage(image as WxSingleImage)" />
                            <wx-action type="remove" @click="handleRemoveMultipleImage(image as WxSingleImage, index as number)" />
                        </div>
                    </div>

                    <div @click="() => handleMultipleImage()" class="wx-input-images__add locked d-flex align-items-center justify-content-center">
                        <wx-icon name="add" />
                    </div>
                </VueDraggableNext>
            </template>
        </template>

        <wx-dialog v-model="editImageDialog" :title="$t('input.image.edit-image')">
            <wx-form-control :title="$t('title')">
                <wx-input type="text" v-model="editImage.title" :localized="!props.localized" />
            </wx-form-control>
            <wx-form-control :title="$t('alt')">
                <wx-input type="text" v-model="editImage.alt" :localized="!props.localized" />
            </wx-form-control>

            <template #footer>
                <wx-buttons class="justify-content-end">
                    <wx-button type="button" theme="blank" @click="editImageDialog = false">{{ $t('cancel') }}</wx-button>
                    <wx-button type="button" theme="primary" @click="editImageDialog = false">{{ $t('save') }}</wx-button>
                </wx-buttons>
            </template>
        </wx-dialog>
    </template>
</template>

<style scoped lang="scss">
.wx-input-images {
    --wx-input-images-size: 96px;

    display: grid;
    gap: 8px;
    grid-template-columns: repeat(auto-fill, minmax(var(--wx-input-images-size), 1fr));

    &__add {
        aspect-ratio: 1;
        border-radius: var(--wx-input-radius);
        border: 1px solid var(--wx-input-border);
        background-color: var(--wx-input-background);
        color: var(--wx-input-color);
        transition:
            background-color 200ms var(--wx-easing),
            border-color 200ms var(--wx-easing),
            color 200ms var(--wx-easing);
        cursor: pointer;

        &:hover {
            --wx-input-border: var(--wx-input-hover-border);
            --wx-input-background: var(--wx-input-hover-background);
            --wx-input-color: var(--wx-input-hover-color);
        }

        svg {
            width: 24px;
            height: 24px;
            color: var(--wx-input-color);
        }
    }

    &__placeholder {
        position: absolute;
        inset: 4px;
        width: calc(100% - 8px);
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: calc(var(--wx-input-radius) - 4px);
    }

    &__image {
        position: relative;
        aspect-ratio: 1;
        border-radius: var(--wx-input-radius);
        border: 1px solid var(--wx-input-border);
        background-color: var(--wx-input-background);
        color: var(--wx-input-color);
        transition:
            background-color 200ms var(--wx-easing),
            border-color 200ms var(--wx-easing),
            color 200ms var(--wx-easing);
        cursor: pointer;

        &:hover {
            --wx-input-border: var(--wx-input-hover-border);
            --wx-input-background: var(--wx-input-hover-background);
            --wx-input-color: var(--wx-input-hover-color);
        }
    }

    &__actions {
        opacity: 0;
        z-index: 2;
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        border-radius: 0 0 var(--wx-input-radius) var(--wx-input-radius);
        transition: opacity 200ms var(--wx-easing);
        background: linear-gradient(to bottom, rgba(var(--wx-secondary-rgb), 0), rgba(var(--wx-secondary-rgb), 1));

        .wx-input-images__image:hover & {
            opacity: 1;
        }

        :deep(.wx-action) {
            --wx-action-size: 28px;
        }
    }
}

.wx-input-image {
    aspect-ratio: 1;

    position: relative;
    width: 100%;
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

    &__placeholder {
        color: var(--wx-input-placeholder);
    }

    &__overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
        cursor: pointer;
    }

    &__image {
        position: relative;
        padding: 4px;
        width: 100%;
        height: 100%;

        img {
            position: absolute;
            top: 4px;
            left: 4px;
            width: calc(100% - 8px);
            height: calc(100% - 8px);
            object-fit: scale-down;
            border-radius: calc(var(--wx-input-radius) - 4px);
        }
    }

    &__actions {
        opacity: 0;
        z-index: 2;
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        border-radius: 0 0 var(--wx-input-radius) var(--wx-input-radius);
        transition: opacity 200ms var(--wx-easing);
        background: linear-gradient(to bottom, rgba(var(--wx-secondary-rgb), 0), rgba(var(--wx-secondary-rgb), 1));

        .wx-input-image:hover & {
            opacity: 1;
        }
    }
}

.wx-native-hidden {
    appearance: auto;
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
</style>
