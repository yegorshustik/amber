<script setup lang="ts">
import { ref } from 'vue';
import { WxCard, WxCode, WxPage, WxPageComposer } from '@/ui';
import type { WxPageComposerComponent } from '@/ui/widgets/WxPageComposer';
import { codeExamples } from './examples';

const components = ref<WxPageComposerComponent[]>([
    {
        id: 'id_123123',
        name: 'Header',
        content: {
        },
        children: [],
    },
    { "id": "h4h9x67", "name": "Section", "children": [
        { "id": "hm10ddk", "name": "Header", "children": [], "content": { "text": null, "level": "h1", "style": "" } }
    ], "content": {} },
    { "id": "iy9mo5k", "name": "Text", "children": [], "content": { "text": {"en": "<p>asdasdasdasdadasd</p>\n<p>asd</p>\n<ol>\n<li>das</li>\n<li>asd</li>\n<li>dfg</li>\n<li>asdasd</li>\n</ol>", "uk": "<p>asdasdasddfsdf</p>" }} },
    { "id": "cyt83fg", "name": "Image", "children": [], "content": { "image": { "uk": { "src": { "id": 8, "name": "f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "file_name": "f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "mime": "image/jpeg", "size": 130619, "path": "123/e1/f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/123/e1/f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg" }, "alt": null, "title": null }, "en": { "src": { "id": 7, "name": "pizduk.jpg", "file_name": "pizduk.jpg", "mime": "image/jpeg", "size": 57110, "path": "43f/f8/pizduk.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/43f/f8/pizduk.jpg" }, "alt": null, "title": null } }, "signature": { "en": "111", "uk": "2222" } } },
    { "id": "amdd6hj", "name": "Video", "children": [], "content": { "video": { "source": "mp4", "en": { "source": "youtube", "poster": { "src": { "id": 8, "name": "f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "file_name": "f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "mime": "image/jpeg", "size": 130619, "path": "123/e1/f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/123/e1/f60378d985e2849d81577e3baacd8f616e8b4f2c.jpg" }, "alt": null, "title": null }, "id": "cE0wfjsybIQ", "signature": "Signature" }, "uk": { "source": "mp4", "poster": { "src": { "id": 7, "name": "pizduk.jpg", "file_name": "pizduk.jpg", "mime": "image/jpeg", "size": 57110, "path": "43f/f8/pizduk.jpg", "extension": "jpg", "url": "https://cdn.alexx.group/buckets/webx/43f/f8/pizduk.jpg" }, "alt": null, "title": null }, "file": { "src": { "id": 9, "name": "file_example_MP4_1280_10MG.mp4", "file_name": "file_example_MP4_1280_10MG.mp4", "mime": "video/mp4", "size": 9840497, "path": "10c/91/file-example-mp4-1280-10mg.mp4", "extension": "mp4", "url": "https://cdn.alexx.group/buckets/webx/10c/91/file-example-mp4-1280-10mg.mp4" }, "title": null }, "signature": "Signature2" } } } }
]);
</script>

<template>
    <wx-page heading="Guideline - Alerts">
        <wx-card>
            <wx-page-composer name="test" v-model="components" />
        </wx-card>

        <div class="mt-4">{{ components }}</div>

        <wx-code lang="vue">{{ codeExamples['page-composer'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
