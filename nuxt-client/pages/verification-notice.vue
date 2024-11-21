<template>
    <div class="flex justify-center py-8 bg-gray-100 min-h-screen">
        <div class="w-8/12 bg-white p-8 rounded-lg shadow-md">

            <div class="max-w-3xl mx-auto space-y-8">

                <!-- Header and Description -->
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-teal-600">Verify your Email</h1>
                    <p class="mt-4 text-gray-700 leading-relaxed text-left">
                        Thanks for signing up! Before getting started, could you verify your email
                        address by clicking on the link we just emailed to you? If you didn't
                        receive the email, we will gladly send you another.
                    </p>

                    <!-- Error Message -->
                    <div v-if="errorMsg" class="text-red-600 bg-red-100 border border-red-400 rounded p-4 mt-4">
                        {{ errorMsg }}
                    </div>

                    <!-- Success Message -->
                    <div v-if="successMsg" class="text-green-600 bg-green-100 border border-green-400 rounded p-4 mt-4">
                        {{ successMsg }}
                    </div>

                    <!-- Resend Verification Email Button -->
                    <div class="mt-6">
                        <button
                            @click="handleResend"
                            class="px-4 py-2 bg-teal-600 text-white font-semibold rounded hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                            :disabled="processing"
                        >
                            <span v-if="processing" class="animate-spin mr-2 border-t-2 border-b-2 border-white rounded-full w-4 h-4"></span>
                            Resend Verification Email
                        </button>
                    </div>

                    <!-- Logout Button -->
                    <div v-if="isAuthenticated" class="mt-4">
                        <button
                            @click.prevent="logout"
                            class="px-4 py-2 bg-gray-500 text-white font-semibold rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                        >
                            Logout
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>



<script setup>
import { ref } from 'vue'

// Page metadata
definePageMeta({
    middleware: ['sanctum:auth'],
})

const { isAuthenticated, logout: logoutAction } = useSanctumAuth()
const { resendEmail } = useAuth()

const logout = async () => {
    await logoutAction()
    navigateTo('/')
}

const processing = ref(false)
const errorMsg = ref()
const successMsg = ref()

const handleResend = async () => {
    processing.value = true
    errorMsg.value = '';
    successMsg.value = '';
    try {
        const response = await resendEmail();
        successMsg.value = 'A new verification link has been sent to the email address you provided during registration.'

    } catch (e) {
        if (e.has('status')) {
            errorMsg.value = 'Error sending verification email';
        }
        console.error('Error sending verification email:', e)
    } finally {
        processing.value = false
    }
}


</script>

<style scoped></style>
