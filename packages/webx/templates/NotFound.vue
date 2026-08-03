<script setup lang="ts">
import { useRouter } from 'vue-router';
import { $t } from '@/locales';
import { WxButtons, WxButton, WxPage } from '@/ui';

const router = useRouter();

const goBack = () => router.back();

</script>

<template>
    <wx-page :heading="$t('not-found.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button type="submit" theme="success" @click="goBack()">{{ $t('not-found.go-back') }}</wx-button>
            </wx-buttons>
        </template>
    </wx-page>
</template>

<style scoped lang="scss"></style>
