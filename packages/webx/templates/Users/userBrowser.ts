import { h, ref } from 'vue';
import UserBrowser from '@/templates/Users/UserBrowser.vue';
import type { User } from '@/types/user';
import { mountTemporary } from '@/utils/mount';

export function wxUserBrowser(profile : User = null) : Promise<User> {
    return new Promise<User>((resolve) => {
        const open = ref(true);
        const { unmount } = mountTemporary(() =>

            h(UserBrowser as any, {
                modelValue: open.value,
                profile,
                'onUpdate:modelValue': (state: boolean) => open.value = state,
                onCancel: () => {
                    open.value = false;
                    unmount();
                },
                onSelect: (user : User) => {
                    open.value = false;
                    unmount();
                    resolve(user);
                }
            })
        )
    });
}
