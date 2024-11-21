<template>
    <div class="flex justify-center py-8 bg-gray-100 min-h-screen">
        <div class="w-8/12 bg-white p-8 rounded-lg shadow-md">

            <div class="max-w-3xl mx-auto space-y-8">

                <!-- Header and Description -->
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-teal-600">Email Verification</h1>


                    <!-- Error Message -->
                    <div v-if="errorMsg" class="text-red-600 bg-red-100 border border-red-400 rounded p-4 mt-4">
                        {{ errorMsg }}
                    </div>

                    <!-- Success Message -->
                    <div v-if="successMsg" class="text-green-600 bg-green-100 border border-green-400 rounded p-4 mt-4">
                        {{ successMsg }}
                    </div>

                    <!-- Resend Verification Email Button -->
                    <div class="mt-6" v-if="errorMsg">
                        <button @click="handleResend"
                            class="px-4 py-2 bg-teal-600 text-white font-semibold rounded hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                            :disabled="processing">
                            <span v-if="processing"
                                class="animate-spin mr-2 border-t-2 border-b-2 border-white rounded-full w-4 h-4"></span>
                            Resend Verification Email
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>


<script setup>

// Page metadata
definePageMeta({
    middleware: ['sanctum:auth'],
})

const { resendEmail } = useAuth()
const userStore = useUserStore()

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

const route = useRoute()
const { verifyEmail, refreshUser } = useAuth()
onMounted(async () => {

    // Get the `verification_url` from the query parameters
    const verificationUrl = decodeURIComponent(route.query.verification_url)
    errorMsg.value = '';
    successMsg.value = '';
    if (verificationUrl) {
        try {
            const response = await verifyEmail(verificationUrl);
            const latestUser = await refreshUser();
            userStore.setUser(latestUser);
            successMsg.value = ' Email successfully verified!'
        } catch (e) {
            errorMsg.value = 'Error verification email';
            console.error('Error verification email:', e)
        }
    }
})
</script>
