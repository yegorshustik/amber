<script setup lang="ts">

import { ref } from 'vue';
import { $t } from '@/locales';
import type { User } from '@/types/user';
import { WxButtons, WxDatatable,
    WxDatatableColumn, WxEntityCard} from '@/ui';
import WxButton from '../../ui/components/WxButton/WxButton.vue';
import WxDialog from '../../ui/components/WxDialog/WxDialog.vue';

const selectedUsers = ref<User[]>([]);

const props = defineProps<{
    modelValue : boolean
}>()

const emit = defineEmits(['update:modelValue', 'cancel', 'select'])

const onUpdateModelValue = (state: boolean) => {
    emit('update:modelValue', state);

    if (!state) emit('cancel');
};

const selectUser = () => {
    if (selectedUsers.value.length > 0) {
        emit('select', selectedUsers.value[0]);
    }
};

const selectForceUser = (user : User) => {
    emit('select', user);
};

</script>

<template>
    <wx-dialog :size="1000" :model-value="props.modelValue" @update:modelValue="onUpdateModelValue" :title="$t('user.find-user')">
        <wx-datatable  endpoint="user" searchable persist="users-browser" selectable="radio" @selected="(users : User[]) => selectedUsers = users">
            <template #selected="{ item } : { item : User }">
                {{ item.name }}
            </template>

            <template #row="{ item } : { item : User }">
                <wx-datatable-column sortable size="max-content" id="id" title="ID" >
                    {{ item.id }}
                </wx-datatable-column>
                <wx-datatable-column sortable size="auto" id="name" :title="$t('user.name')">
                    <wx-entity-card :title="item.name" :image="item.image?.src.url" @click="() => selectForceUser(item)" />
                </wx-datatable-column>
            </template>
        </wx-datatable>

        <template #footer>
            <wx-buttons class="justify-content-end">
                <wx-button theme="blank" @click="onUpdateModelValue(false)">
                    {{ $t('cancel') }}
                </wx-button>
                <wx-button type="submit" :disabled="selectedUsers.length === 0" @click="selectUser()" theme="primary" class="w-100 max-w-128">
                    {{ $t('user.select') }}
                </wx-button>
            </wx-buttons>
        </template>
    </wx-dialog>
</template>

<style scoped lang="scss">

</style>
