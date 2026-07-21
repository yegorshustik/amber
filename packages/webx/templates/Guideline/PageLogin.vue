<script setup lang="ts">
import { WxBlank, WxButton, WxButtons, WxCard, WxCode, WxForm, WxFormControl, WxInput } from '@/ui';
import { $t } from '@/locales';
</script>

<template>
    <wx-blank class="d-flex align-items-center justify-content-center">
        <wx-card class="w-100 max-w-384">
            <wx-form action="sign-in" method="post">
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
