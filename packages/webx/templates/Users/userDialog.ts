import { h, ref } from 'vue';
import UserDialog from '@/templates/Users/UserDialog.vue';
import type { User } from '@/types/user';
import { mountTemporary } from '@/utils/mount';

export function wxUserDialog(profile : User = null) : Promise<User> {
    return new Promise<User>((resolve) => {
        const open = ref(true);
        const { unmount } = mountTemporary(() =>

            h(UserDialog as any, {
                modelValue: open.value,
                profile,
                'onUpdate:modelValue': (state: boolean) => open.value = state,
                onCancel: () => {
                    open.value = false;
                    unmount();
                },
                onSave: (user : User) => {
                    open.value = false;
                    unmount();
                    resolve(user);
                }
            })
        )
    });
}
