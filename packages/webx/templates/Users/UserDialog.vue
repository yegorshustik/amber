<script setup lang="ts">
import { $t } from '@/locales';
import type { ApiResponse } from '@/types/api';
import type { User } from '@/types/user';
import { WxButtons, WxCheck, WxFormControl, WxGrid, WxGridCol, WxInput, WxInputImage, WxInputFile, WxSelect } from '@/ui';
import WxButton from '../../ui/components/WxButton/WxButton.vue';
import WxDialog from '../../ui/components/WxDialog/WxDialog.vue';

const props = defineProps<{
    modelValue: boolean;
    profile?: User;
}>();

const emit = defineEmits(['update:modelValue', 'cancel', 'save']);

const onUpdateModelValue = (state: boolean) => {
    emit('update:modelValue', state);

    if (!state) emit('cancel');
};

const success = (response: ApiResponse<User>) => {
    emit('save', response.data);
};
</script>

<template>
    <wx-dialog
        :action="props.profile ? `user/${props.profile.id}` : 'user'"
        :method="props.profile ? 'put' : 'post'"
        :size="1000"
        :model-value="props.modelValue"
        @update:modelValue="onUpdateModelValue"
        :title="$t('create')"
        @success="(response: ApiResponse<User>) => success(response)"
    >
        <wx-grid>
            <wx-grid-col :md="3">
                <wx-form-control :title="$t('image')">
                    <wx-input-image name="image" :value="props?.profile?.image" />
                </wx-form-control>
                <wx-form-control :title="$t('user.status')">
                    <wx-select
                        name="status"
                        :value="props?.profile?.status || 'user'"
                        :options="[
                            {
                                value: 'admin',
                                label: $t('user.status-admin'),
                            },
                            {
                                value: 'user',
                                label: $t('user.status-user'),
                            },
                        ]"
                    />
                </wx-form-control>
            </wx-grid-col>
            <wx-grid-col :md="9">
                <wx-grid>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('user.first-name')">
                            <wx-input name="first_name" class="mb-8" :value="props?.profile?.first_name" />
                            <wx-check name="is_activated" :checked="props?.profile?.is_activated" :label="$t('is-activated')" />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('user.last-name')">
                            <wx-input name="last_name" :value="props?.profile?.last_name" />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>
                <wx-grid>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('user.email')">
                            <wx-input type="email" name="email" :value="props?.profile?.email" />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('user.phone')">
                            <wx-input name="phone" :value="props?.profile?.phone" />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>

                <wx-grid>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('user.password')">
                            <wx-input type="password" name="password" />
                        </wx-form-control>
                    </wx-grid-col>
                    <wx-grid-col :sm="6">
                        <wx-form-control :title="$t('user.confirm-password')">
                            <wx-input type="password" name="password_confirmation" />
                        </wx-form-control>
                    </wx-grid-col>
                </wx-grid>
            </wx-grid-col>
        </wx-grid>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button theme="blank" @click="onUpdateModelValue(false)">
                    {{ $t('cancel') }}
                </wx-button>
                <wx-button type="submit" theme="primary" class="w-100 max-w-128">
                    {{ $t('save') }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss"></style>
