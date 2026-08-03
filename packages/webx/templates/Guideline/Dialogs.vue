<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxDialog, WxButton, WxSidePanel, WxButtons } from '@/ui';
import { wxConfirm, wxAlert, wxSnackbar } from '@/utils';

import { codeExamples } from './examples';

import { ref } from 'vue';
const showModal = ref(false);
const showSidePanel = ref(false);

const yes = () => alert('Yes')

</script>

<template>
    <wx-page heading="Guideline - Dialogs">
        <h2>Dialog</h2>
        <p>All slots are exactly the same as in the cards</p>
        <wx-card>
            <wx-button theme="primary" @click="showModal = true">Open dialog</wx-button>

            <wx-dialog size="1000" v-model="showModal" title="Dialog title">
                <template #sidebar>
                    This is optional sidebar
                </template>
                <template #footer>
                    This is optional footer
                </template>
                <template #actions>
                    ...Actions
                </template>

                <p v-for="i in 10">This is dialog content</p>
            </wx-dialog>

        </wx-card>

        <wx-code lang="vue">{{ codeExamples['dialog'] }}</wx-code>

        <h2>Side panel</h2>
        <p>All slots are exactly the same as in the cards</p>
        <wx-card>
            <wx-button theme="primary" @click="showSidePanel = true">Open side panel</wx-button>

            <wx-side-panel size="800" v-model="showSidePanel" title="Side panel title">
                <template #sidebar>
                    This is optional sidebar
                </template>
                <template #footer>
                    This is optional footer
                </template>
                <template #actions>
                    ...Actions
                </template>

                <p v-for="i in 100">This is side panel content</p>
            </wx-side-panel>

        </wx-card>

        <wx-code lang="vue">{{ codeExamples['side-panel'] }}</wx-code>

        <h2>Confirm + Alerts</h2>
        <wx-card>
            <wx-buttons>
                <wx-button theme="primary" @click="wxConfirm('Are you sure?').then(yes)">Confirm dialog</wx-button>
                <wx-button theme="danger" @click="wxAlert('Something was happened').then(yes)">Alert dialog</wx-button>
                <wx-button theme="warning" @click="wxSnackbar('Saved')">Snackbar alert</wx-button>
            </wx-buttons>
        </wx-card>
        <wx-code lang="ts">{{ codeExamples['confirm-alert-dialog'] }}</wx-code>

        <h2>Snackbar</h2>
        <wx-card>
            <wx-buttons>
                <wx-button theme="info" @click="wxSnackbar('Saved', { type : 'info' })">Snackbar info</wx-button>
                <wx-button theme="success" @click="wxSnackbar('Saved', { type : 'success' })">Snackbar success</wx-button>
                <wx-button theme="warning" @click="wxSnackbar('Saved', { type : 'warning' })">Snackbar warning</wx-button>
                <wx-button theme="danger" @click="wxSnackbar('Saved', { type : 'danger' })">Snackbar danger</wx-button>
            </wx-buttons>
        </wx-card>
        <wx-code lang="ts">{{ codeExamples['snackbar'] }}</wx-code>


    </wx-page>
</template>

<style scoped lang="scss"></style>
