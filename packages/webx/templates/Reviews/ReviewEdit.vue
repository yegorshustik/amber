<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { $t } from '@/locales';
import type { Review } from '@/templates/Reviews/types';
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
    WxTextarea,
    WxInputImage,
    WxCheckGroup,
} from '@/ui';
import { api, wxSnackbar } from '@/utils';

const route = useRoute();
const router = useRouter();

const review = ref(null);
const loaded = ref<boolean>(false);

onBeforeMount(async () => {
    if (route.params.id) {
        const response = await api.get<ApiResponse<Review[]>>(`reviews/${route.params.id}`);

        review.value = response.data;
        loaded.value = true;
    } else {
        loaded.value = true;
    }
});

const heading = computed(() => (review.value ? $t('reviews.edit') : $t('reviews.create')));

const success = (response: ApiResponse<Review>) => {
    if (!route.params.id) {
        router.push({ name: 'reviews.edit', params: { id: response.data.id } });
    }
    review.value = response.data;

    wxSnackbar($t('reviews.saved'), { type: 'success' });
};
const formatedTimestamp = () => {
    const d = new Date();
    const date = d.toISOString().split('T')[0];
    const time = d.toTimeString().split(' ')[0];
    return `${date} ${time}`;
};
</script>

<template>
    <wx-page v-if="loaded" :heading="heading" :back="{ name: 'reviews' }">
        <template #actions>
            <wx-buttons>
                <wx-button theme="success" type="submit" form="reviews-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            :action="review ? `reviews/${review.id}` : 'reviews'"
            :method="review ? 'put' : 'post'"
            id="reviews-form"
            @success="(response) => success(response)"
        >
            <wx-card>
                <template #sidebar>
                    <wx-form-control :title="$t('image')">
                        <wx-input-image name="image" :value="review?.image || null" />
                    </wx-form-control>
                </template>

                <wx-form-control :title="$t('name')">
                    <wx-input name="name" :value="review?.name || null" localized />

                    <template #footer>
                        <wx-check-group>
                            <wx-check
                                name="is_published"
                                :checked="!review || (review && (review.is_published as boolean))"
                                :label="$t('is-published')"
                            />
                        </wx-check-group>
                    </template>
                </wx-form-control>

                <wx-grid>
                    <wx-grid-col :md="4">
                        <wx-form-control :title="$t('job')">
                            <wx-input name="job" :value="review?.job || null" localized />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :md="4">
                        <wx-form-control :title="$t('published-at')">
                            <wx-input type="datetime" name="published_at" :value="(review?.published_at || formatedTimestamp()) as string" />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>

                <wx-form-control :title="$t('text')">
                    <wx-textarea name="content" :value="review?.content || null" localized />
                </wx-form-control>
            </wx-card>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
