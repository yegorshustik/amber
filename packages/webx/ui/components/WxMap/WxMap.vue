<script setup lang="ts">
import { setOptions, importLibrary } from '@googlemaps/js-api-loader';

import { onMounted, ref, shallowRef, watch } from 'vue';
import { useConfigurationStore } from '@/stores/configuration';
import type { WxMapProps } from '@/ui/components/WxMap/types';

const props = withDefaults(defineProps<WxMapProps>(), {
    zoom: 13,
});

const isInitialized = ref<boolean>(false);
const mapRef = ref();
const map = shallowRef(null);
const marker = shallowRef(null);
const DEFAULT_CENTER = { lat: 48.4647, lng: 35.0462 };

onMounted(async () => {
    await initMap();
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
            props.coordinates && props.coordinates?.latitude && props.coordinates?.longitude
                ? { lat: props.coordinates.latitude, lng: props.coordinates.longitude }
                : DEFAULT_CENTER,
        zoom: props.zoom,

        mapId: 'DEMO_MAP_ID',
    });

    const addMarker = (position) => {
        if (!marker.value) {
            marker.value = new AdvancedMarkerElement({
                map: map.value,
                position: position,
                gmpDraggable: false,
            });
        }
    };

    if (props.coordinates && props.coordinates?.latitude && props.coordinates?.longitude) {
        addMarker({ lat: props.coordinates.latitude, lng: props.coordinates.longitude });
    }
};
</script>

<template>
    <div class="wx-map">
        <div ref="mapRef" class="wx-map__container"></div>
    </div>
</template>

<style scoped lang="scss">
.wx-map {
    &__container {
        border-radius: var(--wx-border-radius);
        height: 500px;
    }
}
</style>
