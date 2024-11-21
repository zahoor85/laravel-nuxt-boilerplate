<template>
    <div class="flex justify-center py-8 bg-gray-100">
        <div class="w-full max-w-7xl p-6 rounded-lg">
            <h2 class="text-2xl font-semibold text-center text-teal-600 mb-8">User Dashboard</h2>

            <div class="mb-6">
                <input v-model="searchQuery" type="text" placeholder="Search users..."
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600"
                    @input="searchUsers" />
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div v-for="user in users" :key="user.id" class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="flex flex-col items-center p-6">
                        <img :src="user.profile.profile_picture || 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp'" alt="Profile Picture"
                            class="w-24 h-24 rounded-full object-cover mb-4">

                        <h3 class="text-lg font-semibold text-gray-800">{{ user.profile.full_name }}</h3>
                        <p class="text-gray-500">{{ user.email }}</p>

                        <p v-if="user.is_verified" class="text-teal-600 mt-2 font-semibold">
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-teal-800 bg-teal-100 rounded-full">
                                Verified
                            </span>
                        </p>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-teal-600"
                                v-if="user.profile.hobbies && user.profile.hobbies.length > 0">Hobbies</h3>
                            <ul class="list-disc pl-5 text-sm text-gray-600">
                                <li v-for="hobby in user.profile.hobbies" :key="hobby">{{ hobby.name }}</li>
                            </ul>
                        </div>

                        <NuxtLink :to="`/profile/${user.id}`"
                            class="mt-4 px-4 py-2 bg-teal-500 text-white text-sm font-medium rounded hover:bg-teal-600">
                            View Profile
                        </NuxtLink>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between mt-8" v-if="nextPageUrl || prevPageUrl">
                <button :disabled="!prevPageUrl" @click="fetchPage(prevPageUrl)" :class="[
                    'px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600',
                    { 'bg-gray-400 cursor-not-allowed': !prevPageUrl }
                ]">
                    Previous
                </button>
                <button :disabled="!nextPageUrl" @click="fetchPage(nextPageUrl)" :class="[
                    'px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600',
                    { 'bg-gray-400 cursor-not-allowed': !nextPageUrl }
                ]">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>

definePageMeta({
    middleware: ['sanctum:auth', 'email-verified']
});
const { users, searchQuery, nextPageUrl, prevPageUrl, searchUsers, fetchPage } = useDashboard();

</script>