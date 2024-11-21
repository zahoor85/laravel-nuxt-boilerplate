<template>
    <div class="flex justify-center py-8 bg-gray-100 min-h-screen">
        <div class="w-8/12 bg-white p-8 rounded-lg shadow-md">

            <div class="max-w-3xl mx-auto space-y-8">

                <!-- Header and Description -->
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-teal-600">Email Change Confirmation</h1>
                    <p class="mt-4 text-gray-700 leading-relaxed text-left">
                        We are processing your request to update your email address. If the token is valid, your email
                        will be updated shortly. Please wait a moment for the confirmation message below.
                    </p>

                    <!-- Error Message -->
                    <div v-if="errorMsg" class="text-red-600 bg-red-100 border border-red-400 rounded p-4 mt-4">
                        {{ errorMsg }}
                    </div>

                    <!-- Success Message -->
                    <div v-if="successMsg" class="text-green-600 bg-green-100 border border-green-400 rounded p-4 mt-4">
                        {{ successMsg }}
                    </div>



                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
definePageMeta({
    middleware: ['sanctum:auth'],
})

const userStore = useUserStore();
const { confirmedChangeEmail } = useUser()
const route = useRoute();

const errorMsg = ref()
const successMsg = ref()
onMounted(async () => {

    const token = route.params.token
    errorMsg.value = '';
    successMsg.value = '';
    if (token) {
        try {
            const response = await confirmedChangeEmail(token);
            const user = useSanctumUser()
            userStore.setUser(user.value)
            successMsg.value = ' Your email has been successfully updated!'
        } catch (e) {
            errorMsg.value = e.data?.message || 'An error occurred while updating your email.';
            console.error('An error occurred while updating your email:', e)
        }
    }
})
</script>