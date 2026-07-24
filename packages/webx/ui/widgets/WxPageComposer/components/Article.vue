<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import { WxSelect, WxTruncate } from '@/ui';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import WxFormControl from '../../../components/WxFormControl/WxFormControl.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';
import WxPageComposerCanvas from '../WxPageComposerCanvas.vue';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});
const emit = defineEmits(['update:edit']);

const component = ref<WxPageComposerComponent>(props.component);
const editMode = ref<boolean>(props.edit);

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);
</script>

<template>
    <wx-truncate :enabled="component.content.is_truncated === '1'">
        <wx-page-composer-canvas v-model:children="component.children" />
    </wx-truncate>
</template>

<style scoped lang="scss"></style>
