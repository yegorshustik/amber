<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { WxCard, WxListGroup, WxListGroupItem, WxPage } from '@/ui';
import { computed } from 'vue';

const route = useRoute();
const router = useRouter();

const isActive = (item: string) => {
    return route.matched.some(({ path }) => path.endsWith(item));
};

const children = computed(() => {
    return route.matched[2].children;
});
</script>

<template>
    <wx-page :heading="`Form elements - ${route.meta.title}`">
        <template #sidebar>
            <wx-list-group title="Contents">
                <wx-list-group-item
                    v-for="child in children"
                    :key="child.name"
                    @click="() => router.push({ name: child.name })"
                    :active="isActive(child.path)"
                >
                    {{ child.meta.title }}
                </wx-list-group-item>
            </wx-list-group>
        </template>

        <router-view />
    </wx-page>
</template>

<style scoped lang="scss"></style>
