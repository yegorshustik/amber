<script setup lang="ts">
import { SelectionArea } from '@viselect/vue';
import type { SelectionEvent } from '@viselect/vue';
import { reactive, ref, watch } from 'vue';
import  WxAction from '../../components/WxAction';
import type { WxFilelistingProps, WxFilemanagerFile } from './types';

const props = withDefaults(defineProps<WxFilelistingProps>(), {
    multiple: false,
});

const emit = defineEmits(['update:modelValue', 'update:selected', 'select', 'remove']);

const filesListing = ref<WxFilemanagerFile[]>(props.modelValue);
const selectedFiles = ref<WxFilemanagerFile[]>(props.selected);
const filesItem = ref();
const selectedItems = reactive<Set<number>>(new Set());

watch(
    () => props.modelValue,
    () => {
        filesListing.value = props.modelValue;
    },
);
watch(
    () => props.selected,
    () => {
        selectedFiles.value = props.selected;
    },
);

const extractIds = (els: Element[]): number[] => {
    return els
        .map((v) => v.getAttribute('data-id'))
        .filter(Boolean)
        .map(Number);
};

const onStart = ({ event, selection }: SelectionEvent) => {
    if (!event?.ctrlKey && !event?.metaKey) {
        selection.clearSelection();
        selectedItems.clear();
    }
    updateSelected(selectedItems);
};

const onMove = ({
    store: {
        changed: { added, removed },
    },
}: SelectionEvent) => {
    extractIds(added).forEach((id) => selectedItems.add(id));
    extractIds(removed).forEach((id) => selectedItems.delete(id));

    updateSelected(selectedItems);
};

const updateSelected = (selected: Set<number>) => {
    selectedFiles.value = [...filesListing.value.filter((f) => selected.has(f.id))];
    emit('update:selected', selectedFiles.value);
};

const filePreviewUrl = (file: WxFilemanagerFile) => {
    switch (file.extension) {
        case 'png':
        case 'svg':
        case 'jpg':
        case 'jpeg':
        case 'webp':
        case 'gif':
            return file.url;

        case 'aac':
        case 'ai':
        case 'bmp':
        case 'cs':
        case 'css':
        case 'csv':
        case 'doc':
        case 'docx':
        case 'exe':
        case 'heic':
        case 'html':
        case 'java':
        case 'js':
        case 'json':
        case 'jsx':
        case 'key':
        case 'm4p':
        case 'md':
        case 'mdx':
        case 'mov':
        case 'mp3':
        case 'mp4':
        case 'otf':
        case 'pdf':
        case 'php':
        case 'ppt':
        case 'psd':
        case 'py':
        case 'raw':
        case 'rb':
        case 'sass':
        case 'scss':
        case 'sh':
        case 'sql':
        case 'tiff':
        case 'tsx':
        case 'ttf':
        case 'txt':
        case 'wav':
        case 'woff':
        case 'xls':
        case 'xlsx':
        case 'xml':
        case 'yml':
            return '/backend/icons/filetype-' + file.extension + '.svg';
    }
};

const handleClick = (file: WxFilemanagerFile) => {
    emit('select', file);
};

const handleRemove = (file: WxFilemanagerFile) => {
    emit('remove', file);
};
</script>

<template>
    <SelectionArea class="files" :options="{ selectables: '.files__overlay' }" :onMove="onMove" :onStart="onStart">
        <div
            class="files__item"
            ref="filesItem"
            :class="{ selected: selectedFiles.includes(file) }"
            v-for="file in filesListing"
            :key="file.id"
            :data-id="file.id"
            :title="file.name"
        >
            <div class="files__preview d-flex align-items-center justify-content-center">
                <img v-if="filePreviewUrl(file)" class="files__preview__img" :src="filePreviewUrl(file)" alt="" />
            </div>
            <div class="files__name fw-semibold fs-12px">
                {{ file.name }}
            </div>
            <div class="files__overlay" @click="() => (!props.multiple ? handleClick(file) : null)" :data-id="file.id"></div>
            <div class="files__actions d-flex flex-column gap-4">
                <wx-action type="remove" @click="handleRemove(file)" />
            </div>
        </div>
    </SelectionArea>
</template>

<style lang="scss" scoped>
.files {
    //height: 100%;
    user-select: none;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 8px;
    align-items: start;

    &__item {
        position: relative;
        user-select: none;
        border: 1px solid var(--wx-light-gray);
        border-radius: 8px;
        padding: 4px;
        cursor: pointer;
        transition: border-color 200ms var(--wx-easing);

        &.selected,
        &:hover {
            border-color: var(--wx-primary-hover);
        }
    }

    &__preview {
        aspect-ratio: 1;
        border-radius: 4px;
        margin-bottom: 4px;
        background-color: var(--wx-light-gray);

        &__img {
            width: 80%;
            height: 80%;
            aspect-ratio: 1;
            object-fit: contain;
        }
    }

    &__name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
    }

    &__actions {
        z-index: 3;
        position: absolute;
        top: 1px;
        right: 1px;
        opacity: 0;
        padding: 4px;
        transition: opacity 200ms var(--wx-easing);

        .files__item:hover & {
            opacity: 1;
        }

        .wx-action {
            --wx-action-size: 28px;
        }
    }
}
</style>
