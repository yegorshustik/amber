<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { config } from '@/config/api';
import { $t } from '@/locales';
import { useConfigurationStore } from '@/stores/configuration';
import type { ApiResponse } from '@/types/api';
import type { ApiTokenResponse } from '@/types/user';
import { WxBlank, WxButton, WxButtons, WxCard, WxForm, WxFormControl, WxInput } from '@/ui';
import { api } from '@/utils';

onMounted(() => {
    api.csrf('/sanctum/csrf-cookie');
});

const router = useRouter();

const handleSuccess = (response: ApiResponse<ApiTokenResponse>) => {
    const token = response.data.token;

    localStorage.setItem(config.token, token);
    useConfigurationStore().load();
    setTimeout(() => router.push({ name: 'inbox' }));
};
</script>

<template>
    <wx-blank class="d-flex align-items-center justify-content-center">
        <wx-card class="w-100 max-w-384">
            <wx-form action="sign-in" method="post" @success="(response: ApiResponse<ApiTokenResponse>) => handleSuccess(response)">
                <wx-form-control>
                    <wx-input type="email" name="email" :placeholder="$t('login.email')" />
                </wx-form-control>
                <wx-form-control>
                    <wx-input type="password" name="password" :placeholder="$t('login.password')" />
                </wx-form-control>
                <wx-buttons>
                    <wx-button type="submit" theme="primary">{{ $t('login.sign-in') }}</wx-button>
                </wx-buttons>
            </wx-form>
        </wx-card>
    </wx-blank>
</template>

<style scoped lang="scss"></style>
