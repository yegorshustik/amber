<script setup lang="ts">
import { WxCode, WxCard, WxPage, WxDropdown, WxDropdownLink, WxIcon } from '@/ui';
import { codeExamples } from './examples';
</script>

<template>
    <wx-page heading="Guideline - Dropdowns">

        <h2>Simple dropdown</h2>
        <wx-card>
            <wx-dropdown>
                <template #trigger>
                    <strong>Click here</strong>
                </template>
                <template #body>
                    <div class="fw-semibold">
                        ...Dropdown body
                    </div>
                </template>
            </wx-dropdown>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['dropdown'] }}</wx-code>

        <h2>Dropdowns with links</h2>
        <wx-card>
            <wx-dropdown>
                <template #trigger>
                    <strong>Click here</strong>
                </template>
                <template #body>
                    <wx-dropdown-link>Link example</wx-dropdown-link>
                    <wx-dropdown-link>Link example</wx-dropdown-link>
                </template>
            </wx-dropdown>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['dropdown-links'] }}</wx-code>

        <h2>Dropdowns with link and icons</h2>
        <wx-card>
            <wx-dropdown>
                <template #trigger>
                    <strong>Click here</strong>
                </template>
                <template #body>
                    <wx-dropdown-link>
                        <template #icon>
                            <wx-icon name="person" />
                        </template>

                        Link example
                    </wx-dropdown-link>
                    <wx-dropdown-link>
                        <template #icon>
                            <wx-icon name="box-arrow-right" />
                        </template>
                        Link example
                    </wx-dropdown-link>
                </template>
            </wx-dropdown>
        </wx-card>

        <wx-code lang="vue">{{ codeExamples['dropdown-link-icon'] }}</wx-code>

    </wx-page>
</template>

<style scoped lang="scss"></style>
