<script setup lang="ts">
import { ref, watch } from 'vue';
import { $t } from '@/locales';
import type { WxLocalizedValue } from '@/types/locale';
import {
    WxAction,
    WxActions,
    WxButtons,
    WxFormControl,
    WxGrid,
    WxGridCol,
    WxHeading,
    WxInput,
    WxInputImage,
    WxSelect,
    WxSortable,
    WxTextarea,
} from '@/ui';
import type { WxSingleImage } from '@/ui/components/WxInputImage';
import { wxConfirm } from '@/utils';
import WxButton from '../../../components/WxButton/WxButton.vue';
import WxDialog from '../../../components/WxDialog/WxDialog.vue';
import type { WxPageComposerComponent, WxPageComposerContentProps } from '../types';
import { useLocalesStore } from '@/stores';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});
const emit = defineEmits(['update:edit']);

const component = ref<WxPageComposerComponent>(props.component);
const editMode = ref<boolean>(props.edit);
const editingCard = ref<boolean>(false);
const editCard = ref<Card>(null);

interface Card {
    image?: WxSingleImage;
    pre_heading?: WxLocalizedValue;
    heading?: WxLocalizedValue;
    text?: WxLocalizedValue;
}

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);

const addItem = () => {
    component.value.content.items.push({
        image: null,
        pre_heading: null,
        heading: null,
        text: null,
    });
};

const removeItem = (index: number) => {
    wxConfirm().then(() => component.value.content.items.splice(index, 1));
};
</script>

<template>
    <template v-if="component.content.type == 'stage'">
        <wx-sortable cards v-model="component.content.items">
            <template #content="{ item }: { item: Card }">
                <div class="d-flex flex-column justify-content-center">
                    <div v-if="useLocalesStore().selectLocalizedValue(item.pre_heading)" class="fs-14px text-uppercase mb-6">
                        {{ useLocalesStore().selectLocalizedValue(item.pre_heading) }}
                    </div>
                    <div v-if="useLocalesStore().selectLocalizedValue(item.heading)" class="fs-20px fw-semibold mb-6">
                        {{ useLocalesStore().selectLocalizedValue(item.heading) }}
                    </div>
                    <div
                        class=""
                        v-if="useLocalesStore().selectLocalizedValue(item.text)"
                        v-html="useLocalesStore().selectLocalizedValue(item.text)"
                    />
                </div>
            </template>
            <template #actions="{ item, index }: { item: Card; index: number }">
                <wx-actions>
                    <wx-action type="sort" class="handle" />
                    <wx-action
                        type="edit"
                        @click="
                            () => {
                                editingCard = true;
                                editCard = item;
                            }
                        "
                    />
                    <wx-action type="remove" @click="removeItem(index)" />
                </wx-actions>
            </template>
        </wx-sortable>

        <div class="d-flex justify-content-center mt-8">
            <wx-actions>
                <wx-action :data-tooltip="$t('add')" type="add" @click="() => addItem()" />
            </wx-actions>
        </div>
    </template>

    <template v-if="component.content.type == 'stat'">
        <wx-sortable cards v-model="component.content.items">
            <template #content="{ item }: { item: Card }">
                <div class="d-flex flex-column justify-content-center">
                    <div v-if="useLocalesStore().selectLocalizedValue(item.pre_heading)" class="fs-14px text-uppercase mb-6">
                        {{ useLocalesStore().selectLocalizedValue(item.pre_heading) }}
                    </div>
                    <div v-if="useLocalesStore().selectLocalizedValue(item.heading)" class="fs-20px fw-semibold mb-6">
                        {{ useLocalesStore().selectLocalizedValue(item.heading) }}
                    </div>
                </div>
            </template>
            <template #actions="{ item, index }: { item: Card; index: number }">
                <wx-actions>
                    <wx-action type="sort" class="handle" />
                    <wx-action
                        type="edit"
                        @click="
                            () => {
                                editingCard = true;
                                editCard = item;
                            }
                        "
                    />
                    <wx-action type="remove" @click="removeItem(index)" />
                </wx-actions>
            </template>
        </wx-sortable>

        <div class="d-flex justify-content-center mt-8">
            <wx-actions>
                <wx-action :data-tooltip="$t('add')" type="add" @click="() => addItem()" />
            </wx-actions>
        </div>
    </template>

    <template v-else-if="component.content.type == 'feature'">
        <wx-sortable cards v-model="component.content.items">
            <template #content="{ item }: { item: Card }">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-6 gap-4">
                        <img v-if="item.image?.src?.url" :src="item.image?.src?.url" alt="" width="24" />

                        <div v-if="useLocalesStore().selectLocalizedValue(item.heading)" class="fs-20px fw-semibold">
                            {{ useLocalesStore().selectLocalizedValue(item.heading) }}
                        </div>
                    </div>
                    <div
                        class=""
                        v-if="useLocalesStore().selectLocalizedValue(item.text)"
                        v-html="useLocalesStore().selectLocalizedValue(item.text)"
                    />
                </div>
            </template>
            <template #actions="{ item, index }: { item: Card; index: number }">
                <wx-actions>
                    <wx-action type="sort" class="handle" />
                    <wx-action
                        type="edit"
                        @click="
                            () => {
                                editingCard = true;
                                editCard = item;
                            }
                        "
                    />
                    <wx-action type="remove" @click="removeItem(index)" />
                </wx-actions>
            </template>
        </wx-sortable>

        <div class="d-flex justify-content-center mt-8">
            <wx-actions>
                <wx-action :data-tooltip="$t('add')" type="add" @click="() => addItem()" />
            </wx-actions>
        </div>
    </template>

    <template v-else-if="component.content.type == 'step'">
        <wx-grid>
            <wx-grid-col :md="6">
                <img v-if="component.content.image?.src?.url" :src="component.content.image?.src?.url" alt="" class="w-100 rounded" />
            </wx-grid-col>
            <wx-grid-col :md="6">
                <wx-sortable v-model="component.content.items">
                    <template #content="{ item }: { item: Card }">
                        <div class="d-flex flex-column justify-content-center">
                            <div v-if="useLocalesStore().selectLocalizedValue(item.pre_heading)" class="fs-14px text-uppercase mb-6">
                                {{ useLocalesStore().selectLocalizedValue(item.pre_heading) }}
                            </div>
                            <div v-if="useLocalesStore().selectLocalizedValue(item.heading)" class="fs-20px fw-semibold mb-6">
                                {{ useLocalesStore().selectLocalizedValue(item.heading) }}
                            </div>
                            <div
                                class=""
                                v-if="useLocalesStore().selectLocalizedValue(item.text)"
                                v-html="useLocalesStore().selectLocalizedValue(item.text)"
                            />
                        </div>
                    </template>
                    <template #actions="{ item, index }: { item: Card; index: number }">
                        <wx-actions>
                            <wx-action type="sort" class="handle" />
                            <wx-action
                                type="edit"
                                @click="
                                    () => {
                                        editingCard = true;
                                        editCard = item;
                                    }
                                "
                            />
                            <wx-action type="remove" @click="removeItem(index)" />
                        </wx-actions>
                    </template>
                </wx-sortable>
                <div class="d-flex justify-content-center mt-8">
                    <wx-actions>
                        <wx-action :data-tooltip="$t('add')" type="add" @click="() => addItem()" />
                    </wx-actions>
                </div>
            </wx-grid-col>
        </wx-grid>
    </template>

    <div class="mt-16" v-if="useLocalesStore().selectLocalizedValue(component.content.button)">
        <a href="#" @click.prevent="() => null">{{ useLocalesStore().selectLocalizedValue(component.content.button) }}</a>
    </div>

    <wx-dialog
        :size="800"
        :title="$t('edit')"
        v-model="editingCard"
        @close="
            () => {
                editingCard = false;
                editCard = null;
            }
        "
    >
        <template #sidebar v-if="component.content.type == 'feature'">
            <wx-form-control :title="$t('image')">
                <wx-input-image v-model="editCard.image" />
            </wx-form-control>
        </template>

        <template v-if="component.content.type == 'stage' || component.content.type == 'step'">
            <wx-form-control :title="$t('pre-heading')">
                <wx-input localized v-model="editCard.pre_heading" />
            </wx-form-control>

            <wx-form-control :title="$t('heading')">
                <wx-input localized v-model="editCard.heading" />
            </wx-form-control>

            <wx-form-control :title="$t('text')">
                <wx-textarea localized v-model="editCard.text" />
            </wx-form-control>
        </template>

        <template v-else-if="component.content.type == 'stat'">
            <wx-form-control :title="$t('pre-heading')">
                <wx-input localized v-model="editCard.pre_heading" />
            </wx-form-control>

            <wx-form-control :title="$t('heading')">
                <wx-input localized v-model="editCard.heading" />
            </wx-form-control>
        </template>

        <template v-else-if="component.content.type == 'feature'">
            <wx-form-control :title="$t('heading')">
                <wx-input localized v-model="editCard.heading" />
            </wx-form-control>

            <wx-form-control :title="$t('text')">
                <wx-textarea localized v-model="editCard.text" />
            </wx-form-control>
        </template>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button
                    theme="blank"
                    @click="
                        () => {
                            editingCard = false;
                            editCard = null;
                        }
                    "
                >
                    {{ $t('cancel') }}
                </wx-button>
                <wx-button
                    @click="
                        () => {
                            editingCard = false;
                            editCard = null;
                        }
                    "
                    type="button"
                    theme="primary"
                    class="w-100 max-w-128"
                >
                    {{ $t('save') }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>

    <wx-dialog :size="600" :title="$t('edit')" v-model="editMode" @close="() => emit('update:edit', false)">
        <template #sidebar v-if="component.content.type == 'step'">
            <wx-form-control :title="$t('image')">
                <wx-input-image v-model="component.content.image" />
            </wx-form-control>
        </template>
        <wx-grid>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('type')">
                    <wx-select
                        v-model="component.content.type"
                        :options="[
                            { label: $t('cards-component.stage'), value: 'stage' },
                            { label: $t('cards-component.step'), value: 'step' },
                            { label: $t('cards-component.stat'), value: 'stat' },
                            { label: $t('cards-component.feature'), value: 'feature' },
                        ]"
                    />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>

        <h3>{{ $t('cta-button.button') }}</h3>
        <wx-grid>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('cta-button.button-text')">
                    <wx-input localized v-model="component.content.button" />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :md="4">
                <wx-form-control :title="$t('cta-button.button-link')">
                    <wx-input v-model="component.content.url" />
                </wx-form-control>
            </wx-grid-col>
        </wx-grid>
        <template #footer>
            <wx-button theme="success" @click="emit('update:edit', false)">{{ $t('save') }}</wx-button>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
