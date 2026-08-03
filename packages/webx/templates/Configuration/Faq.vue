<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import { useConfigurationStore } from '@/stores/configuration';
import type { WxLocalizedValue } from '@/types/locale';
import {
    WxButtons,
    WxButton,
    WxPage,
    WxForm,
    WxTabs,
    WxTab,
    WxCard,
    WxActions,
    WxAction,
    WxDialog,
    WxFormControl,
    WxInput,
    WxAccordion,
    WxAccordionItem,
    WxSortable,
    WxTextarea,
} from '@/ui';
import { wxConfirm, wxSnackbar } from '@/utils';

interface FaqCategoryItems {
    question: WxLocalizedValue;
    answer: WxLocalizedValue;
}

interface FaqCategory {
    title: WxLocalizedValue;
    slug: string;
    items: FaqCategoryItems[];
}

const categories = ref<FaqCategory[]>([]);

const editCategoryDialog = ref<boolean>(false);
const editedCategory = ref<FaqCategory>(null);

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

const addCategory = () => {
    categories.value.push({
        title: null,
        slug: null,
        items: [],
    });
};
const editCategory = (category: FaqCategory) => {
    editCategoryDialog.value = true;
    editedCategory.value = category;
};
const removeCategory = (index: number) => {
    wxConfirm().then(() => {
        categories.value.splice(index, 1);
    });
};

const addItem = (category: FaqCategory) => {
    category.items.push({
        question: null,
        answer: null,
    });
};
const removeItem = (category: FaqCategory, index: number) => {
    wxConfirm().then(() => category.items.splice(index, 1));
};
</script>

<template>
    <wx-page :heading="$t('faq.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button type="button" theme="primary" @click="addCategory()">{{ $t('add') }}</wx-button>
                <wx-button type="submit" theme="success" form="configuration-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            action="configuration/store"
            method="post"
            id="configuration-form"
            @success="
                (response) => {
                    useConfigurationStore().params = response.data;
                    wxSnackbar($t('faq.saved'));
                }
            "
        >
            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <template v-for="(category, index) in categories" :key="index + '-input'">
                        <template v-for="(title, locale) in category.title" :key="locale">
                            <input type="hidden" :name="`param[faq.categories][${index}][title][${locale}]`" :value="title" />
                        </template>
                        <input type="hidden" :name="`param[faq.categories][${index}][slug]`" :value="category.slug" />
                        <template v-for="(item, itemIndex) in category.items" :key="index + '-input-' + itemIndex">
                            <template v-for="(question, locale) in item.question" :key="locale + '-q'">
                                <input
                                    type="hidden"
                                    :name="`param[faq.categories][${index}][items][${itemIndex}][question][${locale}]`"
                                    :value="question"
                                />
                            </template>
                            <template v-for="(answer, locale) in item.answer" :key="locale + '-a'">
                                <input
                                    type="hidden"
                                    :name="`param[faq.categories][${index}][items][${itemIndex}][answer][${locale}]`"
                                    :value="answer"
                                />
                            </template>
                        </template>
                    </template>

                    <VueDraggableNext handle=".handle" :animation="150" v-model="categories">
                        <wx-card
                            v-for="(category, index) in categories"
                            :key="index + '-card'"
                            :title="useLocalesStore().selectLocalizedValue(category.title, $t('faq.category-title-empty'))"
                        >
                            <template #actions>
                                <wx-actions>
                                    <wx-action type="sort" class="handle" />
                                    <wx-action type="edit" @click="editCategory(category)" />
                                    <wx-action type="remove" theme="danger" @click="removeCategory(index)" />
                                </wx-actions>
                            </template>

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
                        </wx-card>
                    </VueDraggableNext>
                </wx-tab>
            </wx-tabs>
        </wx-form>

        <wx-dialog :size="1200" v-model="editCategoryDialog" :title="$t('faq.edit-category')">
            <wx-form-control :title="$t('title')">
                <wx-input name="title" v-model="editedCategory.title" localized />
            </wx-form-control>
            <wx-form-control :title="$t('slug')">
                <wx-input name="slug" v-model="editedCategory.slug" />
            </wx-form-control>

            <h3>{{ $t('faq.heading') }}</h3>
            <wx-sortable v-model="editedCategory.items">
                <template #content="{ item }: { item: { question: string; answer: string } }">
                    <wx-form-control :title="$t('faq.question')">
                        <wx-input v-model="item.question" localized />
                    </wx-form-control>
                    <wx-form-control :title="$t('faq.answer')">
                        <wx-textarea v-model="item.answer" localized wysiwyg />
                    </wx-form-control>
                </template>
                <template #actions="{ index }: { index: number }">
                    <wx-actions>
                        <wx-action type="sort" class="handle" />
                        <wx-action type="remove" @click="removeItem(editedCategory, index)" />
                    </wx-actions>
                </template>
            </wx-sortable>

            <div class="d-flex justify-content-center mt-8">
                <wx-actions>
                    <wx-action :data-tooltip="$t('add')" type="add" @click="() => addItem(editedCategory)" />
                </wx-actions>
            </div>

            <template #footer>
                <wx-buttons class="justify-content-end">
                    <wx-button
                        theme="blank"
                        @click="
                            editCategoryDialog = false;
                            editedCategory = null;
                        "
                    >
                        {{ $t('cancel') }}
                    </wx-button>
                    <wx-button
                        type="submit"
                        theme="primary"
                        class="w-100 max-w-128"
                        @click="
                            editCategoryDialog = false;
                            editedCategory = null;
                        "
                    >
                        {{ $t('save') }}
                    </wx-button>
                </wx-buttons>
            </template>
        </wx-dialog>
    </wx-page>
</template>

<style scoped lang="scss"></style>
