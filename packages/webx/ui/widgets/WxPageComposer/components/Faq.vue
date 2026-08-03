<script setup lang="ts">
import { onBeforeMount, ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import { useConfigurationStore } from '@/stores/configuration';
import { WxAccordion, WxAccordionItem, WxFormControl, WxGrid, WxGridCol, WxSelect, WxTab, WxTabs } from '@/ui';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});
const emit = defineEmits(['update:edit']);

const component = ref<WxPageComposerComponent>(props.component);
const editMode = ref<boolean>(props.edit);
const categories = ref([]);

onBeforeMount(async () => {
    await useConfigurationStore().load();

    const items = useConfigurationStore().getRaw('faq.categories');

    for (const item of items) {
        if (!item.items) {
            item.items = [];
        }

        categories.value.push(item);
    }
});

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);

const getCategoriesOptions = () => {
    const items = [
        {
            value: '',
            label: $t('faq.all-categories'),
        },
    ];

    categories.value.map((category) =>
        items.push({
            value: category.slug,
            label: useLocalesStore().selectLocalizedValue(category.title),
        }),
    );

    return items;
};

const selectedCategory = (slug) => {
    return categories.value.find((category) => category.slug === slug);
};
</script>

<template>
    <template v-if="component.content.category == ''">
        <wx-tabs>
            <wx-tab
                v-for="(category, index) in categories"
                :id="category.slug"
                :key="category.slug"
                :name="useLocalesStore().selectLocalizedValue(category.title)"
            >
                <wx-accordion v-if="category.items.length > 0">
                    <wx-accordion-item
                        v-for="(item, itemIndex) in category.items"
                        :key="`answer-${index}-${itemIndex}`"
                        :title="useLocalesStore().selectLocalizedValue(item.question, '-')"
                        :active="itemIndex === 0"
                    >
                        <div v-html="useLocalesStore().selectLocalizedValue(item.answer)" />
                    </wx-accordion-item>
                </wx-accordion>
            </wx-tab>
        </wx-tabs>
    </template>
    <template v-else-if="selectedCategory(component.content.category)">
        <h3 class="mt-0">{{ useLocalesStore().selectLocalizedValue(selectedCategory(component.content.category).title) }}</h3>
        <wx-accordion v-if="selectedCategory(component.content.category).items.length > 0">
            <wx-accordion-item
                v-for="(item, itemIndex) in selectedCategory(component.content.category).items"
                :key="`answer-${component.content.category}-${itemIndex}`"
                :title="useLocalesStore().selectLocalizedValue(item.question, '-')"
                :active="itemIndex === 0"
            >
                <div v-html="useLocalesStore().selectLocalizedValue(item.answer)" />
            </wx-accordion-item>
        </wx-accordion>
    </template>

    <wx-dialog :size="800" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <wx-grid>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('color')">
                    <wx-select v-model="component.content.category" :options="getCategoriesOptions()" />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
