<script setup lang="ts">
import WxIcon from '../../../components/WxIcon/WxIcon.vue';
import { useConfigurationStore } from '@/stores/configuration';
</script>

<template>
    <router-link :to="{ name: 'inbox' }">
        <img
            v-if="useConfigurationStore().getRaw('branding.project-logo')?.src.url"
            class="logo rounded-3"
            :src="useConfigurationStore().getRaw('branding.project-logo')?.src.url"
            alt=""
        />
        <wx-icon v-else name="logo" class="logo" />
    </router-link>
</template>

<style scoped lang="scss">
.logo {
    width: auto;
    height: 32px;
}
</style>
