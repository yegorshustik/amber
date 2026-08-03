<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { ApiResponse } from '@/types/api';
import {
    WxButtons,
    WxButton,
    WxPage,
    WxForm,
    WxCard,
    WxGrid,
    WxInput,
    WxFormControl,
    WxCheck,
    WxGridCol,
    WxInputImage,
    WxCheckGroup,
    WxTextarea,
    WxCoordinates,
    WxActions,
    WxAction,
    WxDialog,
    WxDatatable,
    WxDatatableColumn,
    WxEntityCard,
    WxSortable,
    WxSelect,
    WxSitesSelector,
} from '@/ui';
import { api, wxConfirm, wxSnackbar } from '@/utils';
import type { Store, StoreCity, StoreContact } from './types';

const route = useRoute();
const router = useRouter();
const forms = ref([]);

const cities = ref();
const store = ref<Store>(null);
const loaded = ref<boolean>(false);
const currentCity = ref<StoreCity>();
const currentContacts = ref<StoreContact[]>([]);

const cityFinderDialog = ref<boolean>(false);
const selectedCity = ref<StoreCity>();

const cityDialog = ref<boolean>(false);
const editingCity = ref<StoreCity>();

onBeforeMount(async () => {
    const response = await api.get<ApiResponse<any>>(`inbox/form/list`);
    forms.value = response.data;

    if (route.params.id) {
        const response = await api.get<ApiResponse<Store>>(`stores/store/${route.params.id}`);

        store.value = response.data;
        loaded.value = true;
        currentCity.value = store.value?.city;
        currentContacts.value = store.value?.contacts || [];
    } else {
        loaded.value = true;
    }
});

const heading = computed(() => (store.value ? $t('stores.edit') : $t('stores.create')));

const success = (response: ApiResponse<Store>) => {
    if (!route.params.id) {
        router.push({ name: 'stores.edit', params: { id: response.data.id } });
    }
    store.value = response.data;

    wxSnackbar($t('stores.saved'), { type: 'success' });
};

const saveCitiesSorting = (items) => {
    const ids = items.map((item) => item.id);

    api.post(`stores/city/sorting`, { ids: ids });
};

const removeCity = async (item) => {
    try {
        await api.delete(`stores/city/${item.id}`).then(() => {
            wxSnackbar($t('stores.cities.deleted'));

            if (currentCity.value?.id === item.id) {
                currentCity.value = null;
            }

            setTimeout(() => cities.value.reload(), 0);
        });
    } catch (e) {
        wxSnackbar(e.message, { type: 'danger' });
    }
};

const addContact = () => {
    currentContacts.value.push({
        title: null,
        content: null,
    });
};
const removeContact = (index) => wxConfirm().then(() => currentContacts.value.splice(index, 1));
</script>

<template>
    <wx-page v-if="loaded" :heading="heading" :back="{ name: 'stores' }">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="stores-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="store ? `stores/store/${store.id}` : 'stores/store'"
            :method="store ? 'put' : 'post'"
            id="stores-form"
            @success="(response) => success(response)"
        >
            <wx-card :title="$t('gallery')">
                <wx-input-image name="images" multiple :value="store?.images || null" />
            </wx-card>
            <wx-card>
                <wx-grid>
                    <wx-grid-col :md="6">
                        <wx-form-control :title="$t('title')">
                            <wx-input name="title" :value="store?.title || null" localized />
                            <template #footer>
                                <wx-check-group>
                                    <wx-check
                                        name="is_published"
                                        :checked="!store || (store && (store.is_published as boolean))"
                                        :label="$t('is-published')"
                                    />
                                </wx-check-group>
                            </template>
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :md="3">
                        <wx-form-control :title="$t('stores.select-form')">
                            <wx-select
                                v-if="forms"
                                name="form_id"
                                :value="store?.form?.id"
                                :options="forms.map((form) => ({ label: useLocalesStore().selectLocalizedValue(form.title), value: form.id }))"
                            />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :md="3">
                        <wx-form-control :title="$t('stores.city')">
                            <div class="d-flex align-items-center gap-16">
                                <div v-if="currentCity">
                                    {{ useLocalesStore().selectLocalizedValue(currentCity.title) }}
                                    <input type="hidden" name="city_id" :value="currentCity.id" />
                                </div>
                                <div v-else>{{ $t('stores.choose-city') }}</div>
                                <wx-actions>
                                    <wx-action v-if="currentCity" type="edit" @click="cityFinderDialog = true" />
                                    <wx-action v-if="currentCity" type="remove" @click="currentCity = null" />
                                    <wx-action v-if="!currentCity" type="upload" @click="cityFinderDialog = true" />
                                </wx-actions>
                            </div>
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>
                <wx-form-control :title="$t('sites.heading')">
                    <wx-sites-selector name="sites" :value="store?.sites || []" />
                </wx-form-control>

                <wx-form-control :title="$t('stores.address')">
                    <wx-textarea name="address" :value="store?.address || null" localized />
                </wx-form-control>
                <wx-form-control :title="$t('stores.coordinates')">
                    <wx-coordinates name="coordinates" :value="store?.coordinates || null" />
                </wx-form-control>
            </wx-card>
            <wx-card :title="$t('stores.contacts')">
                <template #actions>
                    <wx-actions>
                        <wx-action type="add" @click="addContact()" />
                    </wx-actions>
                </template>
                <wx-sortable v-model="currentContacts">
                    <template #content="{ item, index }: { item: StoreContact; index: number }">
                        <template v-for="(title, locale) in item.title" :key="`title-${locale}`">
                            <input type="hidden" :name="`contacts[${index}][title][${locale}]`" :value="title" />
                        </template>
                        <template v-for="(content, locale) in item.content" :key="`content-${locale}`">
                            <input type="hidden" :name="`contacts[${index}][content][${locale}]`" :value="content" />
                        </template>

                        <wx-form-control :title="$t('title')">
                            <wx-input v-model="item.title" localized />
                        </wx-form-control>
                        <wx-form-control :title="$t('text')">
                            <wx-textarea v-model="item.content" localized />
                        </wx-form-control>
                    </template>
                    <template #actions="{ index }: { index: number }">
                        <wx-actions>
                            <wx-action type="sort" class="handle" />
                            <wx-action type="remove" @click="removeContact(index)" />
                        </wx-actions>
                    </template>
                </wx-sortable>
            </wx-card>
            <wx-card :title="$t('text')">
                <wx-textarea name="content" wysiwyg :value="store?.content || null" localized />
            </wx-card>
        </wx-form>

        <wx-dialog v-model="cityFinderDialog" :size="800" :title="$t('stores.choose-city')">
            <wx-datatable
                ref="cities"
                endpoint="stores/city"
                searchable
                sortable
                persist="stores-city-browser"
                selectable="radio"
                @selected="(cities: StoreCity[]) => (selectedCity = cities[0])"
                @sorted="(items) => saveCitiesSorting(items)"
            >
                <template #selected="{ item }: { item: StoreCity }">
                    {{ useLocalesStore().selectLocalizedValue(item.title) }}
                </template>

                <template #row="{ item }: { item: StoreCity }">
                    <wx-datatable-column sortable size="max-content" id="id" title="ID">
                        {{ item.id }}
                    </wx-datatable-column>
                    <wx-datatable-column sortable size="auto" id="name" :title="$t('catalog.brands.title')">
                        <wx-entity-card :title="useLocalesStore().selectLocalizedValue(item.title)" @click="() => (selectedCity = item)" />
                    </wx-datatable-column>
                    <wx-datatable-column size="max-content" id="actions">
                        <wx-actions>
                            <wx-action type="sort" class="handle" />
                            <wx-action
                                type="edit"
                                @click="
                                    () => {
                                        editingCity = item;
                                        cityDialog = true;
                                    }
                                "
                            />
                            <wx-action type="remove" @click="() => wxConfirm().then(() => removeCity(item))" />
                        </wx-actions>
                    </wx-datatable-column>
                </template>
            </wx-datatable>

            <template #footer>
                <wx-buttons class="justify-content-end">
                    <wx-button theme="blank" @click="cityFinderDialog = false">
                        {{ $t('cancel') }}
                    </wx-button>
                    <wx-button type="button" @click="cityDialog = true" theme="outline-primary" class="w-100 max-w-128">
                        {{ $t('create') }}
                    </wx-button>
                    <wx-button
                        type="button"
                        :disabled="!selectedCity"
                        @click="
                            () => {
                                currentCity = selectedCity;
                                selectedCity = null;
                                cityFinderDialog = false;
                            }
                        "
                        theme="primary"
                        class="w-100 max-w-128"
                    >
                        {{ $t('select') }}
                    </wx-button>
                </wx-buttons>
            </template>
        </wx-dialog>

        <wx-dialog
            :size="600"
            v-model="cityDialog"
            :title="$t(editingCity ? 'stores.city-edit' : 'stores.city-create')"
            :action="editingCity ? `stores/city/${editingCity.id}` : 'stores/city'"
            :method="editingCity ? 'put' : 'post'"
            @success="
                (response: ApiResponse<StoreCity>) => {
                    currentCity = response.data;
                    cityDialog = false;
                    cityFinderDialog = false;
                    editingCity = null;
                }
            "
        >
            <wx-form-control :title="$t('title')">
                <wx-input name="title" localized :value="editingCity?.title || null" />

                <template #footer>
                    <wx-check
                        name="is_published"
                        :checked="!editingCity || (editingCity && (editingCity.is_published as boolean))"
                        :label="$t('is-published')"
                    />
                </template>
            </wx-form-control>

            <template #footer>
                <wx-buttons class="justify-content-end">
                    <wx-button
                        theme="blank"
                        @click="
                            cityDialog = false;
                            editingCity = null;
                        "
                    >
                        {{ $t('cancel') }}
                    </wx-button>
                    <wx-button type="submit" theme="primary" class="w-100 max-w-128">
                        {{ $t('select') }}
                    </wx-button>
                </wx-buttons>
            </template>
        </wx-dialog>
    </wx-page>
</template>

<style scoped lang="scss"></style>
