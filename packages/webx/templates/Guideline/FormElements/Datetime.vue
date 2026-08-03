<script setup lang="ts">
import { ref } from 'vue';
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCode, WxInput } from '@/ui';

const testDateModel = ref('2026-03-03');
const testDatetimeModel = ref('2026-03-03 20:35:00');
const testTimeModel = ref('20:35:00');
const testDateDefault = ref('2026-03-03');
const testDatetimeDefault = ref('2026-03-03 20:35:00');
const testTimeDefault = ref('20:35:00');
</script>

<template>
    <wx-card>
        <h3>Datetime</h3>
        <wx-input type="datetime" name="test" value="" />

        <h3>Date</h3>
        <wx-input type="date" name="test" value="" />

        <h3>Time</h3>
        <wx-input type="time" name="test" value="" />

        <h3>Datetime with default</h3>
        <wx-input type="datetime" name="test" :value="testDatetimeDefault" />

        <h3>Date with default</h3>
        <wx-input type="date" name="test" :value="testDateDefault" />

        <h3>Time with default</h3>
        <wx-input type="time" name="test" :value="testTimeDefault" />

        <h3>Datetime v-model</h3>
        <wx-input type="datetime" name="test" v-model="testDatetimeModel" />
        <div class="mt-4">Result: {{ testDatetimeModel }}</div>

        <h3>Date v-model</h3>
        <wx-input type="date" name="test" v-model="testDateModel" />
        <div class="mt-4">Result: {{ testDateModel }}</div>

        <h3>Time v-model</h3>
        <wx-input type="time" name="test" v-model="testTimeModel" />
        <div class="mt-4">Result: {{ testTimeModel }}</div>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-inputs-datetime'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
