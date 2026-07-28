<script setup lang="ts">
import { onBeforeMount } from 'vue';
import { $t } from '@/locales';
import { useConfigurationStore } from '@/stores/configuration';
import { WxButtons, WxButton, WxPage, WxForm, WxTabs, WxTab, WxCard, WxFormControl, WxInput, WxGrid, WxGridCol, WxTextarea, WxInputFile } from '@/ui';
import WxInputImage from '../../ui/components/WxInputImage/WxInputImage.vue';

onBeforeMount(() => {
    useConfigurationStore().load();
});
</script>

<template>
    <wx-page :heading="$t('configuration.heading')">
        <template #actions>
            <wx-buttons>
                <wx-button type="submit" theme="success" form="configuration-form">{{ $t('save') }}</wx-button>
            </wx-buttons>
        </template>

        <wx-form
            action="configuration/store"
            method="post"
            id="configuration-form"
            @success="(response) => (useConfigurationStore().params = response.data)"
        >
            <wx-tabs>
                <wx-tab :name="$t('general')" id="general">
                    <wx-card>
                        <wx-form-control :title="$t('configuration.project-name')">
                            <wx-input name="param[general.project-name]" :value="useConfigurationStore().getRaw('general.project-name')" localized />
                        </wx-form-control>
                        <wx-form-control :title="$t('coordinates.api-key')">
                            <wx-input name="param[google-maps.api-key]" :value="useConfigurationStore().getRaw('google-maps.api-key')" />
                        </wx-form-control>
                    </wx-card>
                    <wx-card :title="$t('contacts.contact-details')">
                        <wx-grid>
                            <wx-grid-col :sm="6">
                                <wx-form-control :title="$t('contacts.phone-number')">
                                    <wx-input name="param[contacts.phone]" type="tel" :value="useConfigurationStore().getRaw('contacts.phone')" />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :sm="6">
                                <wx-form-control :title="$t('contacts.email')">
                                    <wx-input name="param[contacts.email]" type="email" :value="useConfigurationStore().getRaw('contacts.email')" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                        <wx-grid>
                            <wx-grid-col :sm="6">
                                <wx-form-control :title="$t('contacts.registration-numbers')">
                                    <wx-input
                                        localized
                                        name="param[contacts.registration-numbers]"
                                        type="text"
                                        :value="useConfigurationStore().getRaw('contacts.registration-numbers')"
                                    />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :sm="6">
                                <wx-form-control :title="$t('contacts.company-name')">
                                    <wx-input
                                        name="param[contacts.company-name]"
                                        type="text"
                                        :value="useConfigurationStore().getRaw('contacts.company-name')"
                                    />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>

                        <wx-form-control :title="$t('contacts.address')">
                            <wx-input localized name="param[contacts.address]" type="text" :value="useConfigurationStore().getRaw('contacts.address')" />
                        </wx-form-control>

                        <wx-form-control :title="$t('contacts.google-maps-link')">
                            <wx-textarea
                                name="param[contacts.google-maps-link]"
                                :value="useConfigurationStore().getRaw('contacts.google-maps-link')"
                            />
                        </wx-form-control>

                        <wx-form-control :title="$t('contacts.opening-hours')">
                            <wx-textarea
                                localized
                                name="param[contacts.opening-hours]"
                                :value="useConfigurationStore().getRaw('contacts.opening-hours')"
                            />
                        </wx-form-control>

                        <wx-form-control title="WhatsApp">
                            <wx-input name="param[contacts.whatsapp]" :value="useConfigurationStore().getRaw('contacts.whatsapp')" />
                        </wx-form-control>

                        <wx-form-control title="Telegram">
                            <wx-input name="param[contacts.telegram]" :value="useConfigurationStore().getRaw('contacts.telegram')" />
                        </wx-form-control>

                        <wx-form-control title="Linkedin">
                            <wx-input name="param[contacts.linkedin]" :value="useConfigurationStore().getRaw('contacts.linkedin')" />
                        </wx-form-control>

                        <wx-form-control title="Instagram">
                            <wx-input name="param[contacts.instagram]" :value="useConfigurationStore().getRaw('contacts.instagram')" />
                        </wx-form-control>

                        <wx-form-control title="VCF Card">
                            <wx-input-file name="param[contacts.vcf]" :value="useConfigurationStore().getRaw('contacts.vcf')" />
                        </wx-form-control>
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('seo.heading')" id="seo">
                    <wx-card>
                        <wx-grid>
                            <wx-grid-col :sm="6" :md="4" :lg="3">
                                <wx-form-control :title="$t('seo.og')">
                                    <wx-input-image name="param[seo.default-og]" :value="useConfigurationStore().getRaw('seo.default-og')" />
                                </wx-form-control>
                            </wx-grid-col>
                            <wx-grid-col :sm="6" :md="8" :lg="9">
                                <wx-form-control :title="$t('seo.robots-txt')">
                                    <wx-textarea name="param[seo.robots-txt]" :value="useConfigurationStore().getRaw('seo.robots-txt')" />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                    </wx-card>
                </wx-tab>
                <wx-tab :name="$t('configuration.branding')" id="branding">
                    <wx-card>
                        <wx-grid>
                            <wx-grid-col :sm="6" :md="4" :lg="3">
                                <wx-form-control :title="$t('configuration.branding-logo')">
                                    <wx-input-image
                                        name="param[branding.project-logo]"
                                        :value="useConfigurationStore().getRaw('branding.project-logo')"
                                    />
                                </wx-form-control>
                            </wx-grid-col>
                        </wx-grid>
                    </wx-card>
                </wx-tab>
            </wx-tabs>
        </wx-form>
    </wx-page>
</template>

<style scoped lang="scss"></style>
