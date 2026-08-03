<script setup lang="ts">
import { onBeforeMount, provide, ref, watch } from 'vue';
import { $t } from '@/locales';
import type { ApiResponse } from '@/types/api';

import { api, wxConfirm } from '@/utils';
import WxAction from '../../components/WxAction/WxAction.vue';
import WxActions from '../../components/WxActions/WxActions.vue';
import WxButton from '../../components/WxButton/WxButton.vue';
import WxButtons from '../../components/WxButtons/WxButtons.vue';
import WxDialog from '../../components/WxDialog/WxDialog.vue';
import WxForm from '../../components/WxForm/WxForm.vue';
import WxFormControl from '../../components/WxFormControl/WxFormControl.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import WxProgressbar from '../../components/WxProgressbar/WxProgressbar.vue';
import type { WxTreeItem } from '../../components/WxTree';
import WxTree from '../../components/WxTree/WxTree.vue';

import type { WxFilemanagerDialogProps, WxFilemanagerFile } from './types';
import { useFileUploader } from './useFileUploader';
import WxFilesListing from './WxFilesListing.vue';

const props = withDefaults(defineProps<WxFilemanagerDialogProps>(), {
    modelValue: true,
    multiple: false,
    size: 1400,
});

const emit = defineEmits(['update:modelValue', 'cancel', 'select']);

const { uploadFiles, uploadProgress } = useFileUploader();

const selectedDirectory = ref();
const selectedFiles = ref<WxFilemanagerFile[]>([]);
const directoriesTree = ref();
const filesListing = ref<WxFilemanagerFile[]>([]);
const uploadInput = ref();
const searchQuery = ref();
const loadedTree = ref<WxTreeItem[]>([]);
const directoryDialog = ref(false);
const directoryEdit = ref();

provide('wx-form-errors', null);

watch([selectedDirectory], () => {
    localStorage.setItem('fm-current-directory', JSON.stringify(selectedDirectory.value));

    loadFiles(selectedDirectory.value);
});

watch([loadedTree], () => {
    if (!selectedDirectory.value) {
        selectedDirectory.value = loadedTree.value[0];
    }
});

watch(directoryDialog, (newValue) => {
    if (!newValue) {
        directoryEdit.value = null;
    }
});

let searchTimer;
watch([searchQuery], () => {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        if (searchQuery.value) {
            searchFiles(searchQuery.value);
        } else {
            loadFiles(selectedDirectory.value);
        }
    }, 500); // 500ms delay
});

onBeforeMount(() => {
    if (localStorage.getItem('fm-current-directory') !== null) {
        selectedDirectory.value = JSON.parse(localStorage.getItem('fm-current-directory'));
    }
});

const onUpdateModelValue = (state: boolean) => {
    emit('update:modelValue', state);

    if (!state) emit('cancel');
};

const removeDirectory = () => {
    wxConfirm($t('filemanager.directory.remove-confirm')).then((api) => {
        api.post('filemanager/directories/remove', {
            id: selectedDirectory.value.id,
        }).then(() => {
            directoriesTree.value.reloadTree(selectedDirectory.value.parent_id, () => {});
        });
    });
};

const directorySuccess = (directory: WxTreeItem) => {
    directoriesTree.value.reloadTree(directory.id, () => {
        directoryDialog.value = false;
    });
};

const upload = (e: Event) => {
    const inputEl = e.target as HTMLInputElement;

    uploadFiles(inputEl.files, api.prepareUrl('filemanager/files/upload'), { parent_id: selectedDirectory.value.id })
        .then((response) => {
            filesListing.value = response.data;
        })
        .catch((err) => {
            console.error('Ошибка:', err);
        });
};

const loadFiles = (directory: WxTreeItem) => {
    api.get<ApiResponse<WxFilemanagerFile[]>>('filemanager/files', {
        parent_id: directory.id,
    })
        .then((response) => {
            filesListing.value = response.data;
        })
        .catch(() => {});
};
const searchFiles = (query: string) => {
    api.get<ApiResponse<WxFilemanagerFile[]>>('filemanager/files/search', {
        query: query,
    })
        .then((response) => {
            filesListing.value = response.data;
        })
        .catch(() => {});
};

const handleSingleSelect = (file: WxFilemanagerFile) => {
    //console.log('handleSingleSelect - start');
    if (!props.multiple) {
        //console.log('handleSingleSelect - emitting');
        emit('select', file);
        //console.log('handleSingleSelect - emitting - end');
    }
};

const handleRemove = (files: WxFilemanagerFile | WxFilemanagerFile[]) => {
    wxConfirm().then((api) => {
        const ids = Array.isArray(files) ? files.map((file) => file.id) : [files.id];

        api.post<ApiResponse<WxFilemanagerFile[]>>('filemanager/files/remove', {
            parent_id: selectedDirectory.value.id,
            id: ids,
        })
            .then((response) => {
                filesListing.value = response.data;
                selectedFiles.value = [];
            })
            .catch(() => {});
    });
};
</script>

<template>
    <wx-dialog
        :z-index="props.zIndex"
        :size="props.size"
        :model-value="props.modelValue"
        @update:modelValue="onUpdateModelValue"
        :title="$t('filemanager.title')"
    >
        <template #sidebar>
            <wx-tree
                ref="directoriesTree"
                state-id="fm-directories"
                v-model="selectedDirectory"
                endpoint="/filemanager/directories"
                @loaded="(data: WxTreeItem[]) => (loadedTree = data)"
                draggable
                selectable
            >
            </wx-tree>
        </template>
        <template #actions>
            <wx-actions>
                <wx-action :title="$t('filemanager.create-folder')" type="folder-add" @click="directoryDialog = !directoryDialog" />
                <wx-action
                    :title="$t('filemanager.edit-folder')"
                    v-if="selectedDirectory"
                    type="edit"
                    @click="
                        () => {
                            directoryEdit = selectedDirectory;
                            directoryDialog = !directoryDialog;
                        }
                    "
                />
                <wx-action
                    :title="$t('filemanager.remove-folder')"
                    v-if="selectedDirectory && selectedDirectory.parent_id >= 1"
                    type="folder-remove"
                    @click="removeDirectory()"
                />
                <wx-action :title="$t('filemanager.upload-files')" v-if="selectedDirectory" type="upload" @click="() => uploadInput.click()" />
                <wx-action
                    :title="$t('filemanager.remove-files')"
                    v-if="selectedFiles.length > 0"
                    type="remove"
                    @click="() => handleRemove(selectedFiles)"
                />
            </wx-actions>
            <input type="file" ref="uploadInput" multiple @change="upload" class="wx-native-hidden" />
        </template>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button theme="blank" @click="() => emit('cancel')">
                    {{ $t('cancel') }}
                </wx-button>

                <wx-button
                    :disabled="selectedFiles.length === 0"
                    theme="primary"
                    @click="() => emit('select', props.multiple ? selectedFiles : selectedFiles[0])"
                >
                    {{ $t('select') }}
                </wx-button>
            </wx-buttons>
        </template>

        <wx-progressbar v-model="uploadProgress" />

        <div class="d-flex justify-content-end mb-16">
            <div><wx-input type="search" v-model="searchQuery" :placeholder="$t('filemanager.search-placeholder')" /></div>
        </div>

        <wx-files-listing
            v-model="filesListing"
            v-model:selected="selectedFiles"
            :multiple="props.multiple"
            @select="(file: WxFilemanagerFile) => handleSingleSelect(file)"
            @remove="(file: WxFilemanagerFile) => handleRemove(file)"
        />
    </wx-dialog>

    <wx-dialog :title="$t(directoryEdit ? 'filemanager.directory.edit' : 'filemanager.directory.add')" v-model="directoryDialog">
        <wx-form
            :action="directoryEdit?.id ? '/filemanager/directories/update' : '/filemanager/directories/store'"
            id="fm-directory-dialog-form"
            class="mb-16"
            @success="(response: ApiResponse<WxTreeItem>) => directorySuccess(response.data)"
        >
            <input type="hidden" name="id" :value="directoryEdit?.id" />
            <input type="hidden" name="parent_id" :value="selectedDirectory.id" />
            <wx-form-control :title="$t('title')">
                <wx-input type="text" name="title" :value="directoryEdit?.title" placeholder="" />
            </wx-form-control>
        </wx-form>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button theme="blank" @click="() => (directoryDialog = false)">
                    {{ $t('cancel') }}
                </wx-button>
                <wx-button theme="primary" type="submit" form="fm-directory-dialog-form" class="w-100 max-w-128">
                    {{ $t('save') }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">
.wx-native-hidden {
    appearance: auto;
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
</style>
