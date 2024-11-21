<template>
    <nav class="p-6 bg-white flex justify-between mb-6">

        <ul class="flex items-center">
            <li>
                <NuxtLink to="/" class="font-bold hover:text-blue-500  p-3">Home</NuxtLink>
            </li>
            <li>
                <NuxtLink v-if="isAuthenticated" to="/dashboard"
                    class="font-bold hover:text-blue-500  p-3">Dashboard</NuxtLink>
            </li>
        </ul>

        <ul v-if="!isAuthenticated" class="flex items-center">
            <li>
                <NuxtLink to="/register" class="font-bold hover:text-blue-500  p-3">Register</NuxtLink>
            </li>
            <li>
                <NuxtLink to="/login" class="font-bold hover:text-blue-500  p-3">Login</NuxtLink>
            </li>
        </ul>


        <ul v-else class="flex items-center space-x-4 relative">

            <div @mouseover="openDropdown()" @mouseleave="closeDropdown()" class="relative">
                <li class="font-bold  cursor-pointer">
                    {{ userStore.user?.name }}
                </li>

                <div v-if="showDropdown"
                    class="absolute right-0 top-full mt-2 w-48 bg-white border rounded shadow-lg z-50">
                    <NuxtLink :to="`/profile/${userStore.user.id}`" class="block px-4 py-2 text-gray-700 hover:bg-indigo-100">Profile</NuxtLink>
                    <NuxtLink to="/users/settings" class="block px-4 py-2 text-gray-700 hover:bg-indigo-100">Settings
                    </NuxtLink>
                    <button @click.prevent="logout"
                        class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-100">
                        Logout
                    </button>
                </div>
            </div>
        </ul>
    </nav>
</template>

<script setup>
const { isAuthenticated, logout: logoutAction } = useSanctumAuth()
const user = useSanctumUser()
const userStore = useUserStore();
const showDropdown = ref(false)

// Drop Down
let hoverTimeout
const openDropdown = () => {
    clearTimeout(hoverTimeout)
    showDropdown.value = true
}
const closeDropdown = () => {
    hoverTimeout = setTimeout(() => {
        showDropdown.value = false
    }, 200)
}


const logout = async () => {
    await logoutAction()
    userStore.clearUser();
    navigateTo('/')
    showDropdown.value = false
}

watchEffect(() => {
    if (isAuthenticated && user.value) {
        userStore.setUser(user.value);
    } else {
        userStore.clearUser();
    }
});
</script>

<style scoped>
nav {
    position: relative;
}
</style>