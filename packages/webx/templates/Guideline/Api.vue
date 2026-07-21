<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxButton, WxButtons } from '@/ui';
import { codeExamples } from './examples';
import { ref } from 'vue';
import { api, wxConfirm } from '@/utils';



const response = ref({ });

const makeRequest = async () => {
    await api.get('test')
        .then(data => {
            response.value = data;
        })
        .catch(err => {
            console.error(err);
        });
}

</script>

<template>
    <wx-page heading="Guideline - API">
        <wx-card>
            <wx-buttons>
                <wx-button theme="primary" @click="() => makeRequest()">Simple request</wx-button>
                <wx-button theme="outline-primary" @click="() => wxConfirm().then(api_class => api_class.get('test').then((data : any) => response = data))">Request with confirmation</wx-button>
            </wx-buttons>
        </wx-card>
        <wx-code title="Response" lang="js" :key="JSON.stringify(response)">
            {{ response }}
        </wx-code>
        <wx-code title="Example" lang="ts">
            {{ codeExamples['api-request'] }}
        </wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
