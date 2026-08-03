<script setup lang="ts">
import type { WxVideoProps, WxVideoContent, WxVideoSource } from './types';
import WxGrid from '../../components/WxGrid/WxGrid.vue';
import WxGridCol from '../../components/WxGrid/WxGridCol.vue';
import WxFormControl from '../../components/WxFormControl/WxFormControl.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import WxInputImage from '../../components/WxInputImage/WxInputImage.vue';
import WxInputFile from '../../components/WxInputFile/WxInputFile.vue';
import WxLocales from '../../components/WxLocales/WxLocales.vue';
import type { WxSingleImage } from '../../components/WxInputImage/types';
import type { WxSingleFile } from '../../components/WxInputFile/types';
import WxSelect from '../../components/WxSelect/WxSelect.vue';
import { $t } from '@/locales';
import { onBeforeMount, ref, watch } from 'vue';
import type { WxLocalesList, WxLocalizedValue } from '@/types/locale';
import { useLocalesStore } from '@/stores';

const props = withDefaults(defineProps<WxVideoProps>(), {
    preview: false,
    localized: false,
});

const emit = defineEmits(['update:modelValue']);

const currentValue = ref<WxVideoContent | WxLocalizedValue<WxVideoContent>>(props.modelValue || props.value);

onBeforeMount(() => {
    if (props.localized) {
        let defaults = currentValue.value || {};
        for (const locale of useLocalesStore().list) {
            if (!defaults[locale.code]) {
                defaults = { ...defaults, [locale.code]: { source: 'mp4' } };
            }
        }
        currentValue.value = defaults;
    } else {
        if (!currentValue.value) {
            currentValue.value = {
                source: 'mp4',
            };
        }
    }
});
watch(
    () => props.modelValue,
    () => {
        currentValue.value = props.modelValue || props.value;
    },
);
watch(
    () => props.value,
    () => {
        currentValue.value = props.modelValue || props.value;
    },
);

const videoSources = ref([
    { value: 'mp4', label: $t('video.type.mp4') },
    { value: 'youtube', label: $t('video.type.youtube') },
]);

const handleInput = (key: keyof WxVideoContent, value: any) => {
    currentValue.value = { ...currentValue.value, [key]: value };

    emit('update:modelValue', currentValue.value);
};

const handleInputLocalized = (locale: WxLocalesList, key: keyof WxVideoContent, value: any) => {
    currentValue.value = { ...currentValue.value, [locale.code]: { ...currentValue.value[locale.code], [key]: value } };

    emit('update:modelValue', currentValue.value);
};

const previewVideoFrame = (video: WxVideoContent) => {
    if (video.source === 'mp4') {
        return `<video width="560" height="315" class="rounded" poster="${video.poster?.src?.url || null}" controls><source src="${(video.file as WxSingleFile)?.src.url}"></video>`;
    } else if (video.source === 'youtube') {
        return `<iframe width="560" height="315" src="https://www.youtube.com/embed/${video.id}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>`;
    }
};
</script>

<template>
    <template v-if="props.preview">
        <wx-locales v-if="props.localized" type="tabs">
            <template #item="{ locale }">
                <div class="video-preview" v-if="currentValue[locale.code]">
                    <div class="video-preview__frame rounded" v-html="previewVideoFrame(currentValue[locale.code] as WxVideoContent)" />
                    <div v-if="currentValue[locale.code].signature" class="fst-italic mt-12 text-center">
                        {{ currentValue[locale.code].signature }}
                    </div>
                </div>
            </template>
        </wx-locales>
        <div v-else>
            <div class="video-preview">
                <div class="video-preview__frame rounded" v-html="previewVideoFrame(currentValue as WxVideoContent)" />
                <div v-if="useLocalesStore().selectLocalizedValue(currentValue.signature as WxLocalizedValue)" class="fst-italic mt-12 text-center">
                    {{ useLocalesStore().selectLocalizedValue(currentValue.signature as WxLocalizedValue) }}
                </div>
            </div>
        </div>
    </template>
    <template v-else>
        <wx-locales type="tabs" v-if="props.localized">
            <template #item="{ locale }">
                <wx-grid>
                    <wx-grid-col :md="3">
                        <wx-form-control :title="$t('video.type.title')">
                            <wx-select
                                :name="`${props.name}[${locale.code}][source]}`"
                                :options="videoSources"
                                :value="currentValue[locale.code]?.source || 'mp4'"
                                @change="(value) => handleInputLocalized(locale, 'source', value as WxVideoSource)"
                            />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :md="4">
                        <wx-form-control :title="$t('video.poster')">
                            <wx-input-image
                                :name="`${props.name}[${locale.code}][poster]}`"
                                :value="currentValue[locale.code]?.poster"
                                @change="(value) => handleInputLocalized(locale, 'poster', value as WxSingleImage)"
                            />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :md="5">
                        <wx-form-control v-if="currentValue[locale.code]?.source === 'youtube'" :title="$t('video.id')">
                            <wx-input
                                :name="`${props.name}[${locale.code}][id]}`"
                                :value="currentValue[locale.code]?.id"
                                @input="(value) => handleInputLocalized(locale, 'id', value as string)"
                            />
                        </wx-form-control>
                        <wx-form-control v-else-if="currentValue[locale.code]?.source === 'mp4'" :title="$t('video.file')">
                            <wx-input-file
                                :name="`${props.name}[${locale.code}][file]}`"
                                :value="currentValue[locale.code]?.file"
                                @change="(value) => handleInputLocalized(locale, 'file', value as WxSingleFile)"
                            />
                        </wx-form-control>
                        <wx-form-control :title="$t('signature')">
                            <wx-input
                                :name="`${props.name}[${locale.code}][signature]}`"
                                :value="currentValue[locale.code]?.signature"
                                @input="(value) => handleInputLocalized(locale, 'signature', value as WxSingleFile)"
                            />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>
            </template>
        </wx-locales>

        <wx-grid v-else>
            <wx-grid-col :md="3">
                <wx-form-control :title="$t('video.type.title')">
                    <wx-select
                        :name="`${props.name}[source]}`"
                        :options="videoSources"
                        :value="currentValue?.source || 'mp4'"
                        @change="(value) => handleInput('source', value as WxVideoSource)"
                    />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('video.poster')">
                    <wx-input-image
                        :name="`${props.name}[poster]}`"
                        :value="currentValue?.poster as WxSingleImage"
                        @change="(value) => handleInput('poster', value as WxSingleImage)"
                    />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :md="5">
                <wx-form-control v-if="currentValue?.source === 'youtube'" :title="$t('video.id')">
                    <wx-input
                        :name="`${props.name}[id]}`"
                        :value="currentValue?.id as string"
                        @input="(value) => handleInput('id', value as string)"
                    />
                </wx-form-control>
                <wx-form-control v-else-if="currentValue?.source === 'mp4'" :title="$t('video.file')">
                    <wx-input-file
                        :name="`${props.name}[file]}`"
                        :value="currentValue?.file as WxSingleFile"
                        @change="(value) => handleInput('file', value as WxSingleFile)"
                    />
                </wx-form-control>
                <wx-form-control :title="$t('signature')">
                    <wx-input
                        :name="`${props.name}[signature]}`"
                        :value="currentValue?.signature as WxLocalizedValue"
                        localized
                        @input="(value) => handleInput('signature', value as WxSingleFile)"
                    />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>
    </template>
</template>

<style scoped lang="scss">
.video-preview {
    &__frame {
        aspect-ratio: 16 / 9;

        :deep(video),
        :deep(iframe) {
            display: block;
            width: 100%;
            height: 100%;
            border-radius: var(--wx-border-radius);
        }
    }
}
</style>
