<script setup lang="ts">
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxFieldset, WxHeading, WxVideo } from '@/ui';
import { ref } from 'vue';
import { WxVideoContent } from '@/ui/widgets/WxVideo';
import { WxLocalizedValue } from '@/types/locale';

const videoDefault = ref<WxVideoContent>({ "source": "youtube", "poster": { "src": { "id": 7, "name": "pizduk.jpg", "file_name": "pizduk.jpg", "mime": "image/jpeg", "size": 57110, "path": "43f/f8/pizduk.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/43f/f8/pizduk.jpg" }, "alt": null, "title": null }, "signature": { "en": "qweqweq" }, "id": "weqweqweqwe" })
const videoModel = ref<WxVideoContent>({})

const videoLocalizedDefault = ref<WxLocalizedValue<WxVideoContent>>({
    en : { "source": "youtube", "poster": { "src": { "id": 7, "name": "pizduk.jpg", "file_name": "pizduk.jpg", "mime": "image/jpeg", "size": 57110, "path": "43f/f8/pizduk.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/43f/f8/pizduk.jpg" }, "alt": null, "title": null }, "signature":"qweqweq", "id": "weqweqweqwe" }
})

const videoLocalizedModel = ref<WxLocalizedValue<WxVideoContent>>({
    en : { "source": "youtube", "poster": { "src": { "id": 7, "name": "pizduk.jpg", "file_name": "pizduk.jpg", "mime": "image/jpeg", "size": 57110, "path": "43f/f8/pizduk.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/43f/f8/pizduk.jpg" }, "alt": null, "title": null }, "signature":"qweqweq", "id": "weqweqweqwe" }
})

const videoPreview = ref<WxVideoContent>({
    "source": "youtube",
    "poster": { "src": { "id": 7, "name": "pizduk.jpg", "file_name": "pizduk.jpg", "mime": "image/jpeg", "size": 57110, "path": "43f/f8/pizduk.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/43f/f8/pizduk.jpg" }, "alt": null, "title": null },
    "signature":{ "en": "qweqweq" },
    "id": "cE0wfjsybIQ"
})
const videoPreviewLocalized = ref<WxLocalizedValue<WxVideoContent>>({
    "en": {
        "source": "youtube",
        "poster": { "src": { "id": 7, "name": "pizduk.jpg", "file_name": "pizduk.jpg", "mime": "image/jpeg", "size": 57110, "path": "43f/f8/pizduk.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/43f/f8/pizduk.jpg" }, "alt": null, "title": null },
        "signature": "qweqweq",
        "id": "cE0wfjsybIQ"
    },
    "uk": {
        "poster": { "src": { "id": 8, "name": "f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "file_name": "f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "mime": "image/jpeg", "size": 130619, "path": "123/e1/f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/123/e1/f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg" }, "alt": null, "title": null },
        "file": { "src": { "id": 9, "name": "file_example_MP4_1280_10MG.mp4", "file_name": "file_example_MP4_1280_10MG.mp4", "mime": "video/mp4", "size": 9840497, "path": "10c/91/file-example-mp4-1280-10mg.mp4", "extension": "mp4", "url": "https://cdn.alexx.group/buckets/webx/10c/91/file-example-mp4-1280-10mg.mp4" }, "title": null },
        "signature": "qweqweq3",
        "source": "mp4"
    }
})

</script>

<template>
    <h2 class="mt-0">Default</h2>
    <wx-card>
        <wx-fieldset legend="Default">
            <wx-video name="video" />
        </wx-fieldset>
        <wx-fieldset legend="With value">
            <wx-video name="video" :value="videoDefault" />
        </wx-fieldset>
        <wx-fieldset legend="Width v-model">
            <wx-video name="video" v-model="videoModel" />

            <div class="mt-4">
                Result: {{ videoModel }}
            </div>
        </wx-fieldset>

    </wx-card>

    <h2>Localized</h2>

    <wx-card>
        <wx-fieldset legend="Default">
            <wx-video name="video" localized />
        </wx-fieldset>

        <wx-fieldset legend="With value">
            <wx-video name="video" localized :value="videoLocalizedDefault" />
        </wx-fieldset>
        <wx-fieldset legend="With v-model">
            <wx-video name="video" localized v-model="videoLocalizedModel" />
            <div class="mt-4">
                Result: {{ videoLocalizedModel }}
            </div>
        </wx-fieldset>


    </wx-card>

    <h2>Preview</h2>

    <wx-card>
        <wx-fieldset legend="Default">
            <wx-video name="video" preview :value="videoPreview" />
        </wx-fieldset>

        <wx-fieldset legend="Localized">
            <wx-video name="video" preview localized :value="videoPreviewLocalized" />
        </wx-fieldset>

    </wx-card>

    <wx-code lang="vue">{{ codeExamples['video'] }}</wx-code>
</template>

<style scoped lang="scss"></style>
