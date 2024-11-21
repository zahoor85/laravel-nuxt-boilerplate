import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    persist: true, 
    state: () => ({
        user: null,
    }),
    actions: {
        setUser(user) {
            this.user = user;
        },
        clearUser() {
            this.user = null;
        }
    },
});