<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import WxHeading from '../../WxHeading/WxHeading.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';
import WxQuote from '@/ui/widgets/WxQuote/WxQuote.vue';

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
    <wx-quote preview v-model="component.content.quote" />

    <wx-dialog :size="1000" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-quote v-model="component.content.quote" />

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
