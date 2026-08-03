<script setup lang="ts">
import { inject, onBeforeMount, type Ref, ref, watch } from 'vue';
import { $t } from '@/locales';
import { wxUserBrowser } from '@/templates/Users/userBrowser';
import type { ValidationError } from '@/types/api';
import type { User } from '@/types/user';
import { wxConfirm } from '@/utils';
import WxAction from '../../components/WxAction/WxAction.vue';
import WxActions from '../../components/WxActions/WxActions.vue';
import WxEntityCard from '../../components/WxEntityCard/WxEntityCard.vue';
import type { WxUserSelectorProps } from './types';

const props = withDefaults(defineProps<WxUserSelectorProps>(), {});

const errors = inject<Ref<ValidationError['errors']>>('wx-form-errors', null);

const emit = defineEmits(['update:modelValue', 'select']);

const currentValue = ref<User>(props.modelValue || props.value);

onBeforeMount(() => {});
watch(
    () => props.modelValue,
    () => {
        currentValue.value = props.modelValue || props.value;
    },
);
watch(
    () => props.value,
    () => {
        currentValue.value = props.modelValue || props.value;
    },
);

const selectUser = (user: User | null) => {
    currentValue.value = user;
    emit('update:modelValue', user);
    emit('select', user);
};
const getErrors = () => {
    return errors?.value[props.name] ?? [];
};
</script>

<template>
    <template v-if="currentValue">
        <wx-entity-card :title="currentValue.name" :image="currentValue.image?.src.url ? currentValue.image?.src.url : 'dummy'">
            <template #actions>
                <wx-actions>
                    <wx-action type="edit" @click="() => wxUserBrowser().then((user: User) => selectUser(user))" />
                    <wx-action type="remove" @click="() => wxConfirm().then(() => selectUser(null))" />
                </wx-actions>
            </template>
        </wx-entity-card>
    </template>
    <template v-else>
        <wx-entity-card :title="$t('user.find-user')" image="dummy" @click="wxUserBrowser().then((user: User) => selectUser(user))">
            <template #actions>
                <wx-actions>
                    <wx-action type="upload" />
                </wx-actions>
            </template>
        </wx-entity-card>
    </template>
    <input v-if="props.name" type="hidden" :name="props.name" :value="currentValue?.id" />

    <div v-if="getErrors().length > 0" class="d-flex flex-column fs-12px text-danger mt-2 gap-2">
        <div v-for="message in getErrors()" :key="message">
            {{ message }}
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
