<script setup lang="ts">
import { onBeforeMount } from 'vue';
import { $t } from '@/locales';
import { useSitesStore } from '@/stores';
import { WxDropdown, WxDropdownLink } from '@/ui';
import type { Site } from '../../../templates/Sites/types';

const emit = defineEmits(['select']);

onBeforeMount(async () => {
    await useSitesStore().load();
});

const selectSite = (site: Site) => {
    useSitesStore().setSite(site);

    emit('select', site);
};
</script>

<template>
    <wx-dropdown v-if="useSitesStore().active">
        <template #trigger>
            <div class="d-lg-none text-primary text-decoration-underline fs-14px">{{ $t('sites.choose-site') }}</div>
            <div class="d-none d-lg-block text-primary text-decoration-underline fs-14px">{{ $t('sites.current-site', { site: useSitesStore().active.title }) }}</div>
        </template>
        <template #body>
            <wx-dropdown-link
                v-for="site in useSitesStore().sites"
                :key="`site_${site.id}}`"
                @click="selectSite(site)"
                :class="{ active: useSitesStore().isActive(site) }"
            >
                <div class="fw-semibold">
                    {{ site.title }}
                </div>
            </wx-dropdown-link>
        </template>
    </wx-dropdown>
</template>

<style scoped lang="scss"></style>
