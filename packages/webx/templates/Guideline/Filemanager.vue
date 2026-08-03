<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxButton, WxButtons } from '@/ui';
import { codeExamples } from './examples';
import { wxFilemanager } from '@/utils/filemanager';
import { ref } from 'vue';
import type { WxFilemanagerFile } from '@/ui/widgets/WxFilemanager';

const selectedFile = ref<WxFilemanagerFile>();
const selectedFiles = ref<WxFilemanagerFile[]>();

const showFM = () => {
    wxFilemanager().then((file) => {
        selectedFile.value = file
    })
}

const showFMMultiple = () => {
    wxFilemanager({
        multiple: true
    }).then((files) => {
        selectedFiles.value = files
    })
}

</script>

<template>
    <wx-page heading="Guideline - Filemanager">
        <wx-card class="mb-16">
            <wx-buttons>
                <wx-button theme="primary" @click="() => showFM()">Open FM</wx-button>
            </wx-buttons>

            <div class="mt-4">
                Result: {{ selectedFile }}
            </div>
        </wx-card>

        <wx-card>
            <wx-buttons>
                <wx-button theme="primary" @click="() => showFMMultiple()">Open FM - multiple</wx-button>
            </wx-buttons>

            <div class="mt-4">
                Result: {{ selectedFiles }}
            </div>
        </wx-card>

        <wx-code lang="ts">
            {{ codeExamples['filemanager'] }}
        </wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
