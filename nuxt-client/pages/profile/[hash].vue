<template>
    <div class="max-w-lg w-full mx-auto bg-white rounded-xl shadow-lg overflow-hidden">

        <div v-if="profile">
            <!-- Profile Image -->
            <div class="relative">


                <img class="w-full h-64 object-cover"
                    :src="profile.profile.profile_picture || 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp'" alt="Profile Image">

                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-center py-2">
                    <h2 class="text-2xl font-semibold">{{ profile?.profile?.full_name || 'Loading...' }}</h2>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="px-6 py-4" v-if="profile">
            <!-- Name -->
            <div class="mb-2">
                <h3 class="text-xl font-semibold text-gray-800">{{ profile.profile.full_name }}</h3>
            </div>

            <!-- Email -->
            <div class="flex items-center text-gray-700 text-sm mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 12V9a3 3 0 00-3-3h-2a3 3 0 00-3 3v3m6 3H8m4 4a4 4 0 11-4-4 4 4 0 014 4z" />
                </svg>
                <span>{{ profile.email }}</span>
            </div>

            <!-- Gender -->
            <div class="flex items-center text-gray-700 text-sm mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 12l-8 8m0 0l8-8m-8 8l8-8m0 0l8 8" />
                </svg>
                <span>
                    <!-- Conditional rendering based on gender -->
                    <template v-if="profile.profile.gender === 'male'">
                        Male
                    </template>
                    <template v-else-if="profile.profile.gender === 'female'">
                        Female
                    </template>
                    <template v-else>
                        Other
                    </template>
                </span>
            </div>
            <!-- Date of Birth -->
            <div class="flex items-center text-gray-700 text-sm mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>{{ profile.profile.dob }}</span>
            </div>

            <!-- Hobbies -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-teal-600"
                    v-if="profile.profile.hobbies && profile.profile.hobbies.length > 0">Hobbies</h3>
                <ul class="list-disc pl-5 text-sm text-gray-600">
                    <li v-for="hobby in profile.profile.hobbies" :key="hobby">{{ hobby.name }}</li>
                </ul>
            </div>

            <!-- Edit Button (visible only if user is logged in and viewing their own profile) -->
            <div v-if="isOwnProfile && !loading" class="mt-6 text-center">
                <NuxtLink :to="`/profile/${profile.id}/edit`" class="mr-2">
                    <button class="px-6 py-2 bg-teal-500 text-white rounded hover:bg-teal-600">
                        Edit Profile
                    </button>
                </NuxtLink>

                <button @click="goBack" type="button"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                    Back
                </button>
            </div>

            <div v-else class="mt-6 text-center">
                <button @click="goBack" type="button"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                    Back
                </button>
            </div>

            <!-- Loading Spinner (visible when loading) -->
            <div v-if="loading" class="text-center mt-6">
                <p>Loading...</p>
            </div>
        </div>
    </div>
</template>

<script setup>

definePageMeta({
    middleware: ['sanctum:auth', 'email-verified']
});
const { isOwnProfile, profile, loading } = useProfile();

const goBack = () => {
    history.back();
}
</script>