<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { WxLocalizedValue } from '@/types/locale';
import { WxInputImage, WxTextarea } from '@/ui';
import WxFormControl from '../../components/WxFormControl/WxFormControl.vue';
import WxGrid from '../../components/WxGrid/WxGrid.vue';
import WxGridCol from '../../components/WxGrid/WxGridCol.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import type { WxSingleImage } from '../../components/WxInputImage/types';
import type { WxQuoteProps, WxQuoteContent } from './types';
import { nl2br } from '@/utils';

const props = withDefaults(defineProps<WxQuoteProps>(), {
    preview: false,
});

const emit = defineEmits(['update:modelValue']);

const currentValue = ref<WxQuoteContent>(props.modelValue || props.value);

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

const handleInput = (key: keyof WxQuoteContent, value: WxLocalizedValue | WxSingleImage) => {
    currentValue.value = { ...currentValue.value, [key]: value };

    emit('update:modelValue', currentValue.value);
};
</script>

<template>
    <div v-if="props.preview" class="quote bg-lightest d-flex p-md-16 p-lg-24 gap-16 rounded p-8">
        <div class="max-w-96">
            <img :src="currentValue?.image.src.url" alt="" class="w-100" />
        </div>

        <div class="flex-grow-1">
            <div class="h3" v-html="nl2br(useLocalesStore().selectLocalizedValue(currentValue?.text as WxLocalizedValue))" />
            <div class="mt-12 text-secondary">
                {{ useLocalesStore().selectLocalizedValue(currentValue?.signature as WxLocalizedValue) }}
            </div>
        </div>
    </div>

    <wx-grid v-else>
        <wx-grid-col :md="3" :lg="4">
            <wx-form-control :title="$t('image')">
                <wx-input-image
                    :name="`${props.name}[image]}`"
                    :value="currentValue?.image"
                    @change="(value) => handleInput('image', value as WxLocalizedValue)"
                />
            </wx-form-control>
        </wx-grid-col>
        <wx-grid-col :md="9" :lg="8">
            <wx-form-control :title="$t('text')">
                <wx-textarea
                    :name="`${props.name}[text]}`"
                    :value="currentValue?.text"
                    localized
                    @input="(value) => handleInput('text', value as WxLocalizedValue)"
                />
            </wx-form-control>
            <wx-form-control :title="$t('signature')">
                <wx-input
                    :name="`${props.name}[signature]}`"
                    :value="currentValue?.signature"
                    localized
                    @input="(value) => handleInput('signature', value as WxLocalizedValue)"
                />
            </wx-form-control>
        </wx-grid-col>
    </wx-grid>
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
