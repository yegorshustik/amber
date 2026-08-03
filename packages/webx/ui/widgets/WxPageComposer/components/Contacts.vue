<script setup lang="ts">
import { onBeforeMount, ref, watch } from 'vue';
import { $t } from '@/locales';
import { WxButtons, WxFormControl, WxGrid, WxGridCol, WxInput, WxInputImage, WxTextarea } from '@/ui';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';

import 'swiper/css';
import 'swiper/css/navigation';
import { useLocalesStore } from '@/stores';
import { useConfigurationStore } from '@/stores/configuration';
import { nl2br } from '@/utils';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});
const emit = defineEmits(['update:edit']);

const component = ref<WxPageComposerComponent>(props.component);
const editMode = ref<boolean>(props.edit);

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);

const param = (slug: string) => {
    return useConfigurationStore().get(slug);
};
</script>

<template>
    <wx-grid>
        <wx-grid-col :md="6">
            <wx-form-control title="Company">
                <div class="fw-semibold fs-18px" v-if="param('contacts.company-name')" v-html="param('contacts.company-name')" />
                <div class="fw-semibold fs-18px" v-if="param('contacts.address')" v-html="param('contacts.address')" />
                <div class="fw-semibold fs-18px" v-if="param('contacts.registration-numbers')" v-html="param('contacts.registration-numbers')" />
            </wx-form-control>

            <wx-form-control title="Direct">
                <div class="fw-semibold fs-18px" v-if="param('contacts.phone')" v-html="param('contacts.phone')" />
                <div class="fw-semibold fs-18px" v-if="param('contacts.email')" v-html="param('contacts.email')" />
            </wx-form-control>

            <wx-form-control title="Hours">
                <div class="fw-semibold fs-18px" v-if="param('contacts.opening-hours')" v-html="nl2br(param('contacts.opening-hours'))" />
            </wx-form-control>
        </wx-grid-col>
        <wx-grid-col :md="6">
            <wx-form-control title="Message us">
                <wx-buttons>
                    <wx-button v-if="param('contacts.whatsapp')" theme="primary">WhatsApp</wx-button>
                    <wx-button v-if="param('contacts.telegram')" theme="primary">Telegram</wx-button>
                </wx-buttons>
            </wx-form-control>
            <wx-form-control title="Follow">
                <wx-buttons>
                    <wx-button v-if="param('contacts.linkedin')" theme="primary">LinkedIn</wx-button>
                    <wx-button v-if="param('contacts.instagram')" theme="primary">Instagram</wx-button>
                </wx-buttons>
            </wx-form-control>
            <wx-form-control title="Take it with you">
                <wx-buttons>
                    <wx-button v-if="param('contacts.vcf')" theme="primary">Save contact</wx-button>
                </wx-buttons>
            </wx-form-control>
        </wx-grid-col>
    </wx-grid>
</template>

<style scoped lang="scss"></style>
