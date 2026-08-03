<script setup lang="ts">
import { ref } from 'vue';
import { codeExamples } from '@/templates/Guideline/examples';
import type { WxLocalizedValue } from '@/types/locale';
import { WxCard, WxCode, WxFieldset, WxInputFile, WxInputImage } from '@/ui';
import type { WxSingleImage } from '@/ui/components/WxInputImage';
import { WxSingleFile } from '@/ui/components/WxInputFile';

const testSingleFile = ref({
    src: {
        id: 5,
        path: 'be4/83/7k5a0760.jpg',
    },
    title: { en: '12312312', uk: 'qweqweqwe' },
});
const testSingleFileModel = ref({
    src: {
        id: 5,
        path: 'be4/83/7k5a0760.jpg',
    },
    title: { en: '12312312', uk: 'qweqweqwe' },
});

const testMultipleFiles = ref([
    {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
    {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
]);
const testMultipleFilesModel = ref([
    {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
    {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
]);
const testMultipleLocalizedFiles = ref<WxLocalizedValue<WxSingleFile[]>>({
    en : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            title: '12312312',
        },
        {
            src: {
                id: 7,
                path: '43f/f8/pizduk.jpg',
            },
            title: 'qweqweqwe',
        },
    ],

    uk : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            title:  'qweqweqwe',
        },
    ],
});


const testMultipleLocalizedFilesModel = ref<WxLocalizedValue<WxSingleFile[]>>({
    en : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            title: '12312312',
        },
        {
            src: {
                id: 7,
                path: '43f/f8/pizduk.jpg',
            },
            title: 'qweqweqwe',
        },
    ],

    uk : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            title:  'qweqweqwe',
        },
    ],
});





const testSingleLocalizedFile = ref({
    en: {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        title: 'qweqweqwe',
    },
    uk: {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
    },
});
const testSingleLocalizedFileModel = ref({
    en: {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        title: 'qweqweqwe',
    },
    uk: {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
    },
});

const testSingleImage = ref({
    src: {
        id: 5,
        path: 'be4/83/7k5a0760.jpg',
    },
    alt: { uk: '34435345' },
    title: { en: '12312312', uk: 'qweqweqwe' },
});

const testSingleLocalizedImage = ref<{}>({
    en: {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        alt: '34435345',
        title: '12312312',
    },
    uk: {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
        alt: '34435345',
        title: '12312312',
    },
});

const testSingleImageModel = ref();
const testSingleLocalizedImageModel = ref<WxLocalizedValue<WxSingleImage>>({});

const testSingleImageModelDefault = ref({
    src: {
        id: 5,
        path: 'be4/83/7k5a0760.jpg',
    },
    alt: { uk: '34435345' },
    title: { en: '12312312', uk: 'qweqweqwe' },
});
const testSingleLocalizedImageModelDefault = ref({
    en: {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        alt: '34435345',
        title: '12312312',
    },
    uk: {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
        alt: '34435345',
        title: '12312312',
    },
});

const testMultipleImageDefault = ref<WxSingleImage[]>([
    {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        alt: { uk: '34435345' },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
    {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
        alt: { uk: '34435345' },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
]);

const testMultipleImageModelDefault = ref([
    {
        src: {
            id: 5,
            path: 'be4/83/7k5a0760.jpg',
        },
        alt: { uk: '34435345' },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
    {
        src: {
            id: 7,
            path: '43f/f8/pizduk.jpg',
        },
        alt: { uk: '34435345' },
        title: { en: '12312312', uk: 'qweqweqwe' },
    },
]);

const testMultipleImageLocalizedDefault = ref<WxLocalizedValue<WxSingleImage[]>>({
    en : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            alt: '34435345' ,
            title: '12312312'
        },
        {
            src: {
                id: 7,
                path: '43f/f8/pizduk.jpg',
            },

            alt: '34435345' ,
            title: '12312312'
        },
    ],
    uk : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            alt: '34435345' ,
            title: '12312312'
        }
    ]
});

const testMultipleImageLocalizedModel = ref<WxLocalizedValue<WxSingleImage[]>>({
    en : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            alt: '34435345' ,
            title: '12312312'
        },
        {
            src: {
                id: 7,
                path: '43f/f8/pizduk.jpg',
            },

            alt: '34435345' ,
            title: '12312312'
        },
    ],
    uk : [
        {
            src: {
                id: 5,
                path: 'be4/83/7k5a0760.jpg',
            },
            alt: '34435345' ,
            title: '12312312'
        }
    ]
});

</script>

<template>
    <h2 class="mt-0">Single image from filemanager</h2>
    <wx-card>
        <wx-fieldset legend="Single">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <wx-input-image name="image" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="Single With value">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <wx-input-image name="image" :value="testSingleImage" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="Localized">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <wx-input-image name="image" localized />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="Localized with value">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <wx-input-image name="image" localized :value="testSingleLocalizedImage" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>

        <h2>Single image v-model</h2>
        <wx-fieldset legend="Single">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <wx-input-image v-model="testSingleImageModel" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="Localized">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <wx-input-image localized v-model="testSingleLocalizedImageModel" />
                </div>
                <div class="col-lg col-12">Result: {{ testSingleLocalizedImageModel }}</div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="Localized with value">
            <div class="row">
                <div class="col-lg-2 col-12">
                    <wx-input-image localized v-model="testSingleLocalizedImageModelDefault" />
                </div>
                <div class="col-lg col-12">Result: {{ testSingleLocalizedImageModelDefault }}</div>
            </div>
        </wx-fieldset>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-single-image'] }}
    </wx-code>

    <h2>Multiple images from filemanager</h2>
    <wx-card>
        <wx-fieldset legend="Default">
            <wx-input-image name="image" multiple />
        </wx-fieldset>

        <wx-fieldset legend="Default with value">
            <wx-input-image name="image" multiple :value="testMultipleImageDefault" />
        </wx-fieldset>

        <wx-fieldset legend="Default with v-model">
            <wx-input-image multiple v-model="testMultipleImageModelDefault" />
            <div class="mt-4">Result: {{ testMultipleImageModelDefault }}</div>
        </wx-fieldset>
    </wx-card>
    <wx-code lang="vue">
        {{ codeExamples['form-multiple-image'] }}
    </wx-code>

    <h2>Multiple localized images from filemanager</h2>
    <wx-card>
        <wx-fieldset legend="Default">
            <wx-input-image name="image" multiple localized />
        </wx-fieldset>

        <wx-fieldset legend="Default with value">
            <wx-input-image name="image" multiple localized :value="testMultipleImageLocalizedDefault" />
        </wx-fieldset>

        <wx-fieldset legend="Default with v-model">
            <wx-input-image name="image" multiple localized :value="testMultipleImageLocalizedModel" />
            <div class="mt-4">Result: {{ testMultipleImageLocalizedModel }}</div>
        </wx-fieldset>
    </wx-card>
    <wx-code lang="vue">
        {{ codeExamples['form-multiple-image-localized'] }}
    </wx-code>


    <h2>Single image direct upload</h2>
    <wx-card>
        <div class="row">
            <div class="col-lg-3 col-12">
                <h3 class="mt-0">Single</h3>
                <wx-input-image direct name="image" />
            </div>
            <div class="col-lg-3 col-12">
                <h3 class="mt-0">Localized</h3>
                <wx-input-image direct name="image" localized />
            </div>
        </div>
    </wx-card>

    <h2>Files</h2>
    <wx-card title="Single file">
        <wx-fieldset legend="File">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <wx-input-file name="file" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>

        <wx-fieldset legend="File with default">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <wx-input-file name="file" :value="testSingleFile" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>

        <wx-fieldset legend="File with v-model">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <wx-input-file v-model="testSingleFileModel" />
                </div>
                <div class="col-lg col-12">
                    <div>Result: {{ testSingleFileModel }}</div>
                </div>
            </div>
        </wx-fieldset>
    </wx-card>
    <wx-code lang="vue">
        {{ codeExamples['form-single-file'] }}
    </wx-code>

    <wx-card title="Single localized file">
        <wx-fieldset legend="File">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <wx-input-file localized name="file" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="File with default">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <wx-input-file localized name="file" :value="testSingleLocalizedFile" />
                </div>
                <div class="col-lg col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="File with v-model">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <wx-input-file localized name="file" v-model="testSingleLocalizedFileModel" />
                </div>
                <div class="col-lg col-12">
                    <div>Result: {{ testSingleLocalizedFileModel }}</div>
                </div>
            </div>
        </wx-fieldset>
    </wx-card>
    <wx-code lang="vue">
        {{ codeExamples['form-single-file-localized'] }}
    </wx-code>

    <wx-card title="Multiple files">
        <wx-fieldset legend="Files">
            <div class="row">
                <div class="col-xl-6 col-12">
                    <wx-input-file multiple name="file" />
                </div>
                <div class="col-xl col-12"></div>
            </div>
        </wx-fieldset>

        <wx-fieldset legend="Files with default">
            <div class="row">
                <div class="col-xl-6 col-12">
                    <wx-input-file multiple name="file" :value="testMultipleFiles" />
                </div>
                <div class="col-xl col-12"></div>
            </div>
        </wx-fieldset>

        <wx-fieldset legend="Files with v-model">
            <div class="row">
                <div class="col-xl-6 col-12">
                    <wx-input-file multiple v-model="testMultipleFilesModel" />
                </div>
                <div class="col-xl col-12">Result: {{ testMultipleFilesModel  }}</div>
            </div>
        </wx-fieldset>
    </wx-card>
    <wx-code lang="vue">
        {{ codeExamples['form-multiple-files'] }}
    </wx-code>

    <wx-card title="Multiple localized files">
        <wx-fieldset legend="Files">
            <div class="row">
                <div class="col-xl-6 col-12">
                    <wx-input-file multiple localized name="file" />
                </div>
                <div class="col-xl col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="Files with default">
            <div class="row">
                <div class="col-xl-6 col-12">
                    <wx-input-file multiple
                                   localized
                                   name="file"
                                   :value="testMultipleLocalizedFiles" />
                </div>
                <div class="col-xl col-12"></div>
            </div>
        </wx-fieldset>
        <wx-fieldset legend="Files with v-model">
            <div class="row">
                <div class="col-xl-6 col-12">
                    <wx-input-file multiple
                                   localized
                                   name="file"
                                   v-model="testMultipleLocalizedFilesModel" />
                </div>
                <div class="col-xl col-12">
                    Result: {{ testMultipleLocalizedFilesModel }}
                </div>
            </div>
        </wx-fieldset>
    </wx-card>
    <wx-code lang="vue">
        {{ codeExamples['form-multiple-files-localized'] }}
    </wx-code>

</template>

<style scoped lang="scss"></style>
