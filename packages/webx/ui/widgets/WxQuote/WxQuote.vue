<script setup lang="ts">
import { onBeforeMount, ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { WxLocalizedValue } from '@/types/locale';
import { WxInputImage, WxSelect, WxTextarea } from '@/ui';
import { nl2br } from '@/utils';
import WxFormControl from '../../components/WxFormControl/WxFormControl.vue';
import WxGrid from '../../components/WxGrid/WxGrid.vue';
import WxGridCol from '../../components/WxGrid/WxGridCol.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import type { WxSingleImage } from '../../components/WxInputImage/types';
import type { WxQuoteProps, WxQuoteContent, WxQuoteType } from './types';

const props = withDefaults(defineProps<WxQuoteProps>(), {
    preview: false,
});

const emit = defineEmits(['update:modelValue']);

const currentValue = ref<WxQuoteContent>(props.modelValue || props.value);

onBeforeMount(() => {
    if (currentValue.value && !currentValue.value?.type) {
        currentValue.value.type = 'default';
        /*currentValue.value = {
            type: 'default',
        };*/
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

const handleInput = (key: keyof WxQuoteContent, value: WxLocalizedValue | WxSingleImage | WxQuoteType) => {
    currentValue.value = { ...currentValue.value, [key]: value };

    emit('update:modelValue', currentValue.value);
};
</script>

<template>
    <div v-if="props.preview" class="quote bg-lightest p-md-16 p-lg-24 gap-16 rounded p-8">
        <template v-if="currentValue?.type === 'full'">
            <div class="h3 fst-italic m-0" v-html="nl2br(useLocalesStore().selectLocalizedValue(currentValue?.text as WxLocalizedValue))" />

            <div class="d-flex gap-16 mt-24">
                <div class="max-w-48">
                    <img :src="currentValue?.image.src.url" alt="" class="w-100 rounded-circle object-fit-cover" />
                </div>

                <div class="flex-grow-1">
                    <div class="fw-semibold fs-18px" v-html="nl2br(useLocalesStore().selectLocalizedValue(currentValue?.name))" />
                    <div class="text-secondary">
                        {{ useLocalesStore().selectLocalizedValue(currentValue?.job) }}
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div v-if="useLocalesStore().selectLocalizedValue(currentValue?.pre_heading)" class="fs-14px text-uppercase mb-6">
                {{ useLocalesStore().selectLocalizedValue(currentValue.pre_heading) }}
            </div>
            <div class="h3 fst-italic m-0" v-html="nl2br(useLocalesStore().selectLocalizedValue(currentValue?.text as WxLocalizedValue))" />
        </template>
    </div>

    <template v-else>
        <wx-grid>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('type')">
                    <wx-select
                        @change="(value: WxQuoteType) => handleInput('type', value)"
                        :value="currentValue?.type || 'default'"
                        :options="[
                            { label: $t('default'), value: 'default' },
                            { label: $t('full'), value: 'full' },
                        ]"
                    />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <template v-if="currentValue?.type === 'full'">
            <wx-form-control :title="$t('text')">
                <wx-textarea
                    :name="`${props.name}[text]}`"
                    :value="currentValue?.text"
                    localized
                    @input="(value) => handleInput('text', value as WxLocalizedValue)"
                />
            </wx-form-control>

            <wx-grid>
                <wx-grid-col :sm="3" :md="2">
                    <wx-form-control :title="$t('image')">
                        <wx-input-image
                            :name="`${props.name}[image]}`"
                            :value="currentValue?.image"
                            @change="(value) => handleInput('image', value as WxLocalizedValue)"
                        />
                    </wx-form-control>
                </wx-grid-col>
                <wx-grid-col :sm="9" :md="10">
                    <wx-form-control :title="$t('name')">
                        <wx-input :value="currentValue?.name" localized @input="(value) => handleInput('name', value as WxLocalizedValue)" />
                    </wx-form-control>
                    <wx-form-control :title="$t('job')">
                        <wx-input :value="currentValue?.job" localized @input="(value) => handleInput('job', value as WxLocalizedValue)" />
                    </wx-form-control>
                </wx-grid-col>
            </wx-grid>

            <div class="d-flex"></div>
        </template>

        <wx-grid v-else>
            <wx-grid-col :md="12" :lg="12">
                <wx-form-control :title="$t('pre-heading')">
                    <wx-input
                        :name="`${props.name}[pre_heading]}`"
                        :value="currentValue?.pre_heading"
                        localized
                        @input="(value) => handleInput('pre_heading', value as WxLocalizedValue)"
                    />
                </wx-form-control>
                <wx-form-control :title="$t('text')">
                    <wx-textarea
                        :name="`${props.name}[text]}`"
                        :value="currentValue?.text"
                        localized
                        @input="(value) => handleInput('text', value as WxLocalizedValue)"
                    />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>
    </template>
</template>

<style scoped lang="scss">
.quote {
    &__content {
        position: relative;
        :deep() {
            img {
                width: 100%;
            }
        }
    }

    &__body {
        position: absolute;
        inset: 0;
    }
}
</style>
