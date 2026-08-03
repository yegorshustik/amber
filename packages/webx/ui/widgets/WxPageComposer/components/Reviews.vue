<script setup lang="ts">
import { Navigation } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { onMounted, ref, watch } from 'vue';
import { $t } from '@/locales';
import { useLocalesStore } from '@/stores';
import type { Review } from '@/templates/Reviews';
import type { ApiResponse } from '@/types/api';
import { WxEntityCard } from '@/ui';
import { api } from '@/utils';
import type { WxPageComposerContentProps } from '../types';

const props = withDefaults(defineProps<WxPageComposerContentProps>(), {});

const editMode = ref<boolean>(props.edit);
const reviews = ref<Review[]>([]);

watch(
    () => props.edit,
    (value) => (editMode.value = value),
);

onMounted(() => {
    api.get<ApiResponse<Review[]>>('reviews/list?limit=3').then((response) => {
        reviews.value = response.data;
    });
});
</script>

<template>
    <swiper v-if="reviews?.length > 0" :slides-per-view="1" :space-between="16" :modules="[Navigation]" :navigation="{ enabled: true }">
        <swiper-slide v-for="review in reviews" :key="`slide-${review.id}`">
            <div class="px-64">
                <div class="h3 fst-italic mt-0">
                    {{ useLocalesStore().selectLocalizedValue(review.content) }}
                </div>
                <wx-entity-card
                    :title="useLocalesStore().selectLocalizedValue(review.name)"
                    :image="review.image?.src.url"
                    :params="[{ option: $t('job'), value: useLocalesStore().selectLocalizedValue(review.job) }]"
                />
            </div>
        </swiper-slide>
    </swiper>

    <!--
    <div v-if="reviews?.length > 0" class="reviews">
        <div
            v-for="review in reviews"
            :key="review.id"
            class="d-flex flex-column align-items-center justify-content-center gap-16 rounded border bg-white p-8"
        >
            <img v-if="review.image" :src="review.image.src.url" alt="" class="w-100 aspect-ratio-1x1 object-fit-scale rounded" />
            <div class="h4">{{ useLocalesStore().selectLocalizedValue(review.name) }}</div>
        </div>
    </div>
    -->
</template>

<style scoped lang="scss">
.text-preview {
    > *:last-child {
        margin-bottom: 0 !important;
    }

    :deep() {
        img {
            max-width: 100%;
            width: auto;
            height: auto;
            border-radius: var(--wx-border-radius);
        }
    }
}

.reviews {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 20px;

    @include media-breakpoint-up(md) {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
