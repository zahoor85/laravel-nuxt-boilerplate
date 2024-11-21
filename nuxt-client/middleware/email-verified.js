export default defineNuxtRouteMiddleware((to, from) => {
    const userStore = useUserStore();
    const { isAuthenticated } = useSanctumAuth();
    if (!isAuthenticated) {
        return navigateTo('/login');
    }
    if (!userStore.user) {
        return navigateTo('/login');
    }
    if (userStore.user && !userStore.user.email_verified_at) {
        return navigateTo('/verification-notice');
    }
});
