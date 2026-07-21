<script setup lang="ts">
import { useRouter } from 'vue-router';
import { config } from '@/config/api';
import { $t } from '@/locales';
import { useUserStore } from '@/stores';
import { wxUserDialog } from '@/templates/Users';
import type { ApiResponse } from '@/types/api';
import { api, wxConfirm } from '@/utils';
import WxDropdown from '../../../components/WxDropdown/WxDropdown.vue';
import WxDropdownLink from '../../../components/WxDropdown/WxDropdownLink/WxDropdownLink.vue';
import WxIcon from '../../../components/WxIcon/WxIcon.vue';

const editProfile = () => {
    wxUserDialog(useUserStore().user).then((response) => {
        if (response.id === useUserStore().user.id) {
            useUserStore().user = response;
        }
    });
};

const router = useRouter();

const signOut = () => {
    wxConfirm().then(() =>
        api.post<ApiResponse<null>>('sign-out').then(() => {
            localStorage.removeItem(config.token);
            router.push({ name: 'sign-in' });
        }),
    );
};
</script>

<template>
    <wx-dropdown>
        <template #trigger>
            <div class="profile d-flex align-items-center justify-content-center text-secondary shadow">
                <img v-if="useUserStore().profile().image?.src.url" :src="useUserStore().profile().image.src.url" alt="" />
                <wx-icon name="user" width="24" height="24" />
            </div>
        </template>
        <template #body>
            <div class="d-flex flex-column fw-semibold fs-14px gap-4">
                <wx-dropdown-link @click="() => editProfile()">
                    <template #icon>
                        <wx-icon name="person" />
                    </template>
                    {{ $t('user.profile') }}
                </wx-dropdown-link>
                <wx-dropdown-link @click="() => signOut()">
                    <template #icon>
                        <wx-icon name="box-arrow-right" />
                    </template>
                    {{ $t('user.sign-out') }}
                </wx-dropdown-link>
            </div>
        </template>
    </wx-dropdown>
</template>

<style scoped lang="scss">
.profile {
    width: 40px;
    height: 40px;
    border-radius: var(--wx-border-radius);
    border: 1px solid var(--wx-white);

    img {
        width: 38px;
        height: 38px;
        border-radius: var(--wx-border-radius);
        object-fit: cover;
    }
}
</style>
