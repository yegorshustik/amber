<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { WxLocalizedValue } from '@/types/locale';
import WxFormControl from '../../components/WxFormControl/WxFormControl.vue';
import WxGrid from '../../components/WxGrid/WxGrid.vue';
import WxGridCol from '../../components/WxGrid/WxGridCol.vue';
import WxInput from '../../components/WxInput/WxInput.vue';
import type { WxSingleImage } from '../../components/WxInputImage/types';
import type { WxQuoteProps, WxQuoteContent } from './types';
import { nl2br } from '@/utils';
import { WxTextarea } from '@/ui';

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
    <div v-if="props.preview" class="quote bg-lightest p-md-16 p-lg-24 gap-16 rounded p-8">
        <div v-if="useLocalesStore().selectLocalizedValue(currentValue?.pre_heading)" class="fs-14px text-uppercase mb-6">
            {{ useLocalesStore().selectLocalizedValue(currentValue.pre_heading) }}
        </div>
        <div class="h3 m-0 fst-italic" v-html="nl2br(useLocalesStore().selectLocalizedValue(currentValue?.text as WxLocalizedValue))" />
    </div>

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
