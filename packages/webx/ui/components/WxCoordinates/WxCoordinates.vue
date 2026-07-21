<script setup lang="ts">
import { setOptions, importLibrary } from '@googlemaps/js-api-loader';
import { nextTick, onBeforeMount, onMounted, ref, shallowRef, watch } from 'vue';
import { $t } from '@/locales';
import { useConfigurationStore } from '@/stores/configuration';
import WxAction from '../WxAction/WxAction.vue';
import WxAlert from '../WxAlert/WxAlert.vue';
import WxButton from '../WxButton/WxButton.vue';
import WxButtons from '../WxButtons/WxButtons.vue';
import WxDialog from '../WxDialog/WxDialog.vue';
import WxInput from '../WxInput/WxInput.vue';
import type { WxCoordinatesProps, WxCoordinatesType } from './types';

const props = withDefaults(defineProps<WxCoordinatesProps>(), {});

const mapRef = ref();

const findCoordinatesDialog = ref<boolean>(false);
const isInitialized = ref<boolean>(false);
const latitude = ref();
const longitude = ref();
const coordinates = ref<WxCoordinatesType>(props.modelValue || props.value);

const map = shallowRef(null);
const marker = shallowRef(null);
const DEFAULT_CENTER = { lat: 48.4647, lng: 35.0462 };

watch(
    () => [props.modelValue, props.value],
    () => (coordinates.value = props.modelValue || props.value),
);

watch(
    () => coordinates,
    (item) => {
        latitude.value = item.value?.latitude;
        longitude.value = item.value?.longitude;
    },
);

watch(
    () => findCoordinatesDialog.value,
    (state) => {
        if (!state) {
            marker.value = null;
        }
    },
);

onBeforeMount(() => {});

onMounted(() => {
    latitude.value = coordinates.value?.latitude;
    longitude.value = coordinates.value?.longitude;
});

const initMap = async () => {
    if (!mapRef.value) return;

    if (!isInitialized.value) {
        setOptions({ key: useConfigurationStore().getRaw('google-maps.api-key') });
    }

    isInitialized.value = true;

    const { Map } = await importLibrary('maps');
    const { AdvancedMarkerElement } = await importLibrary('marker');

    map.value = new Map(mapRef.value, {
        center:
            coordinates.value && coordinates.value?.latitude && coordinates.value?.longitude
                ? { lat: coordinates.value.latitude, lng: coordinates.value.longitude }
                : DEFAULT_CENTER,
        zoom: 13,
        mapId: 'DEMO_MAP_ID',
    });

    const addMarker = (position) => {
        if (!marker.value) {
            marker.value = new AdvancedMarkerElement({
                map: map.value,
                position: position,
                gmpDraggable: true,
            });

            marker.value.addListener('dragend', () => {
                if (marker.value && marker.value.position) {
                    const draggedPosition = {
                        lat: typeof marker.value.position.lat === 'function' ? marker.value.position.lat() : marker.value.position.lat,
                        lng: typeof marker.value.position.lng === 'function' ? marker.value.position.lng() : marker.value.position.lng,
                    };

                    coordinates.value = { latitude: draggedPosition.lat, longitude: draggedPosition.lng };
                }
            });
        } else {
            marker.value.position = position;
        }
    };

    if (coordinates.value && coordinates.value?.latitude && coordinates.value?.longitude) {
        addMarker({ lat: coordinates.value.latitude, lng: coordinates.value.longitude });
    }

    map.value.addListener('click', (event) => {
        if (!event.latLng) return;

        coordinates.value = {
            latitude: event.latLng.lat(),
            longitude: event.latLng.lng(),
        };

        addMarker({ lat: event.latLng.lat(), lng: event.latLng.lng() });
    });
};

const showMap = async () => {
    findCoordinatesDialog.value = true;

    await nextTick();
    await initMap();
};

const saveCoordinates = () => {
    latitude.value = coordinates.value?.latitude;
    longitude.value = coordinates.value?.longitude;
    findCoordinatesDialog.value = false;
    marker.value = null;
};
</script>

<template>
    <input type="hidden" :name="`${props.name}[latitude]`" v-model="latitude" />
    <input type="hidden" :name="`${props.name}[longitude]`" v-model="longitude" />

    <div class="d-flex gap-16">
        <div class="flex-grow-1 flex-basis-0">
            <wx-input v-model="latitude" :placeholder="$t('coordinates.latitude')" />
        </div>
        <div class="flex-grow-1 flex-basis-0">
            <wx-input v-model="longitude" :placeholder="$t('coordinates.longitude')" />
        </div>
        <div class="d-flex align-items-center">
            <wx-action type="map" @click="showMap()" />
        </div>
    </div>

    <wx-dialog :size="800" v-model="findCoordinatesDialog" :title="$t('coordinates.find-coordinates')">
        <wx-alert v-if="!useConfigurationStore().getRaw('google-maps.api-key')" type="warning">{{ $t('coordinates.api-key-warning') }}</wx-alert>
        <template v-else>
            <div class="wx-map">
                <div ref="mapRef" class="wx-map__container"></div>
            </div>
        </template>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button theme="blank" @click="findCoordinatesDialog = false">
                    {{ $t('cancel') }}
                </wx-button>
                <wx-button theme="primary" @click="saveCoordinates()" class="w-100 max-w-128" :disabled="!coordinates">
                    {{ $t('save') }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">
.wx-map {
    &__container {
        height: 500px;
    }
}
</style>
