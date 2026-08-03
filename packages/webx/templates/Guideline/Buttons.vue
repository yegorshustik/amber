<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxButton } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Guideline - Buttons">
        <h2>Default button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button>Initial</wx-button>
                <wx-button theme="default" class="hover">Hover</wx-button>
                <wx-button theme="default" class="active">Active</wx-button>
                <wx-button theme="default" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-default'] }}</wx-code>

        <h2>Primary button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button theme="primary">Initial</wx-button>
                <wx-button theme="primary" class="hover">Hover</wx-button>
                <wx-button theme="primary" class="active">Active</wx-button>
                <wx-button theme="primary" disabled>Disabled</wx-button>
                <wx-button theme="outline-primary">Initial</wx-button>
                <wx-button theme="outline-primary" class="hover">Hover</wx-button>
                <wx-button theme="outline-primary" class="active">Active</wx-button>
                <wx-button theme="outline-primary" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-primary'] }}</wx-code>

        <h2>Secondary button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button theme="secondary">Initial</wx-button>
                <wx-button theme="secondary" class="hover">Hover</wx-button>
                <wx-button theme="secondary" class="active">Active</wx-button>
                <wx-button theme="secondary" disabled>Disabled</wx-button>
                <wx-button theme="outline-secondary">Initial</wx-button>
                <wx-button theme="outline-secondary" class="hover">Hover</wx-button>
                <wx-button theme="outline-secondary" class="active">Active</wx-button>
                <wx-button theme="outline-secondary" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-secondary'] }}</wx-code>

        <h2>Success button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button theme="success">Initial</wx-button>
                <wx-button theme="success" class="hover">Hover</wx-button>
                <wx-button theme="success" class="active">Active</wx-button>
                <wx-button theme="success" disabled>Disabled</wx-button>
                <wx-button theme="outline-success">Initial</wx-button>
                <wx-button theme="outline-success" class="hover">Hover</wx-button>
                <wx-button theme="outline-success" class="active">Active</wx-button>
                <wx-button theme="outline-success" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-success'] }}</wx-code>

        <h2>Danger button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button theme="danger">Initial</wx-button>
                <wx-button theme="danger" class="hover">Hover</wx-button>
                <wx-button theme="danger" class="active">Active</wx-button>
                <wx-button theme="danger" disabled>Disabled</wx-button>
                <wx-button theme="outline-danger">Initial</wx-button>
                <wx-button theme="outline-danger" class="hover">Hover</wx-button>
                <wx-button theme="outline-danger" class="active">Active</wx-button>
                <wx-button theme="outline-danger" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-danger'] }}</wx-code>

        <h2>Warning button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button theme="warning">Initial</wx-button>
                <wx-button theme="warning" class="hover">Hover</wx-button>
                <wx-button theme="warning" class="active">Active</wx-button>
                <wx-button theme="warning" disabled>Disabled</wx-button>
                <wx-button theme="outline-warning">Initial</wx-button>
                <wx-button theme="outline-warning" class="hover">Hover</wx-button>
                <wx-button theme="outline-warning" class="active">Active</wx-button>
                <wx-button theme="outline-warning" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-warning'] }}</wx-code>

        <h2>Light button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button theme="light">Initial</wx-button>
                <wx-button theme="light" class="hover">Hover</wx-button>
                <wx-button theme="light" class="active">Active</wx-button>
                <wx-button theme="light" disabled>Disabled</wx-button>
                <wx-button theme="outline-light">Initial</wx-button>
                <wx-button theme="outline-light" class="hover">Hover</wx-button>
                <wx-button theme="outline-light" class="active">Active</wx-button>
                <wx-button theme="outline-light" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-light'] }}</wx-code>

        <h2>Info button</h2>
        <wx-card>
            <div class="d-flex flex-wrap gap-16">
                <wx-button theme="info">Initial</wx-button>
                <wx-button theme="info" class="hover">Hover</wx-button>
                <wx-button theme="info" class="active">Active</wx-button>
                <wx-button theme="info" disabled>Disabled</wx-button>
                <wx-button theme="outline-info">Initial</wx-button>
                <wx-button theme="outline-info" class="hover">Hover</wx-button>
                <wx-button theme="outline-info" class="active">Active</wx-button>
                <wx-button theme="outline-info" disabled>Disabled</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-info'] }}</wx-code>

        <h2>Button size</h2>

        <wx-card>
            <div class="d-flex align-items-center flex-wrap gap-16">
                <wx-button theme="primary" size="sm">SM</wx-button>
                <wx-button theme="primary" size="md">MD</wx-button>
                <wx-button theme="primary" size="lg">LG (default)</wx-button>
                <wx-button theme="primary" size="xl">XL</wx-button>
            </div>
        </wx-card>
        <wx-code lang="vue">{{ codeExamples['button-size'] }}</wx-code>
    </wx-page>
</template>

<style scoped lang="scss"></style>
