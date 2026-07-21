import { defineStore } from 'pinia';
import type { User } from '@/types/user';

export const useUserStore = defineStore('userStore', {
    state: () => ({
        user: null,
    }),

    actions: {
        profile() : User {
            return this.user as User;
        }
    }
});
