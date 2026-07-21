<script setup lang="ts">
import { codeExamples } from '@/templates/Guideline/examples';
import { WxCard, WxCheck, WxCheckGroup, WxCode } from '@/ui';
import { ref } from 'vue';

const selectedTags = ref(['news']);
const gender = ref('male');
const testSwitch = ref(true);
const isPublished = ref(true);
const isPublished2 = ref('test_4');
</script>

<template>
    <wx-card>
        <h3>Default checkbox</h3>
        <wx-check-group>
            <wx-check name="is_published" checked label="Checkbox label (checked)" />
            <wx-check name="is_published_2" label="Checkbox label" />
        </wx-check-group>

        <h3>Checkbox v-model</h3>
        <wx-check-group>
            <wx-check v-model="isPublished" label="Checkbox label v-model (checked)" />
        </wx-check-group>
        <div class="mt-4">Result: {{ isPublished }}</div>


        <h3>Checkbox with value</h3>
        <wx-check-group>
            <wx-check name="is_published_3" value="test_3" checked label="Checkbox label (checked)" />
        </wx-check-group>

        <h3>Checkbox with value and v-model</h3>
        <wx-check-group>
            <wx-check v-model="isPublished2" value="test_4" label="Checkbox with value and v-model" />
        </wx-check-group>
        <div class="mt-4">Result: {{ isPublished2 }}</div>

        <h3>Default radio</h3>
        <wx-check-group>
            <wx-check name="gender" value="male" type="radio" label="Male" />
            <wx-check name="gender" value="female" type="radio" label="Female" />
        </wx-check-group>

        <h3>Checked radio</h3>
        <wx-check-group>
            <wx-check name="gender_2" value="male" checked type="radio" label="Male" />
            <wx-check name="gender_2" value="female" type="radio" label="Female" />
        </wx-check-group>

        <h3>Radio v-model</h3>
        <wx-check-group>
            <wx-check v-model="gender" value="male" type="radio" label="Male" />
            <wx-check v-model="gender" value="female" type="radio" label="Female" />
        </wx-check-group>
        <div class="mt-4">Result: {{ gender }}</div>

        <h3>Default switch</h3>
        <wx-check-group>
            <wx-check type="switch" name="is_published" checked label="Checkbox label (checked)" />
        </wx-check-group>

        <h3>Switch v-model</h3>
        <wx-check-group>
            <wx-check type="switch" v-model="testSwitch" label="Switch v-model label (checked)" />
        </wx-check-group>
        <div class="mt-4">Result: {{ testSwitch }}</div>
    </wx-card>

    <wx-code lang="vue">
        {{ codeExamples['form-checkboxes'] }}
    </wx-code>
</template>

<style scoped lang="scss"></style>
