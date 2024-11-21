
import { useSanctumClient } from '#imports';

export function useProfile() {
    const sanctumFetch = useSanctumClient();
    const profile = ref(null);
    const isOwnProfile = ref(false);
    const loading = ref(true);
    const route = useRoute();
    const userId = route.params.hash;
    const userStore = useUserStore();

    async function fetchProfile(url = `/profile/${userId}`) {
        try {
            const response = await sanctumFetch(url);
            if (response.data) {
                profile.value = response.data;
                if (userStore.user.id && userStore.user.id === response.data.id) {
                    isOwnProfile.value = true;
                }
            }
        } catch (e) {
            console.error('Error fetching user:', e);
        } finally {
            loading.value = false;
        }
    }


    onMounted(() => {
        if (route.name === 'profile-edit' && userId !== userStore.user.id) {
            navigateTo('/profile');
        }
        fetchProfile(`/profile/${userId}`);
    });

   
    async function updateProfile(formBody) {
        const response = await sanctumFetch(`/profile/${userStore.user.id}`, {
            method: 'POST',
            body: formBody
        });
        if (response.data) {
            profile.value = response.data;
        }
        return response;
    }

    watch(() => userStore.user, (newUser) => {
        if (profile.value && profile.value.id === newUser.id) {
            isOwnProfile.value = true;
        } else {
            isOwnProfile.value = false;
        }
    });

    return {
        profile,
        isOwnProfile,
        loading,
        updateProfile
    };
}
