import { ref, onMounted } from 'vue';
import { useSanctumClient } from "#imports";

export function useDashboard() {
    const sanctumFetch = useSanctumClient();

    const users = ref([]);
    const searchQuery = ref('');
    const nextPageUrl = ref('');
    const prevPageUrl = ref('');

    async function fetchUsers(url = '/fetch-users', params = {}) {
        try {
            const response = await sanctumFetch(url, {
                method: 'GET',
                params: {
                    ...params,
                },
            });
            if (response.users) {
                users.value = response.users;
            }
            if (response.users_meta) {
                nextPageUrl.value = response.users_meta.next_page_url;
                prevPageUrl.value = response.users_meta.prev_page_url;
            }
        } catch (e) {
            console.error('Error fetching users:', e);
        }
    }

    const searchUsers = () => {
        users.value = [];
        getUsers();
    };

    const fetchPage = async (url) => {
        if (url) {
            await fetchUsers(url);
        }
    };
    
    onMounted(() => {
        getUsers();
    });
    const getUsers = async () => {
        await fetchUsers('/fetch-users', { search: searchQuery.value });
    };

    return {
        users,
        searchQuery,
        nextPageUrl,
        prevPageUrl,
        searchUsers,
        fetchPage,
        getUsers,
    };
}
