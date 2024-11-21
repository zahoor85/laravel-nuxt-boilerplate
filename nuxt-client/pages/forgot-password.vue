<template>
    <div class="flex justify-center items-center px-6 bg-gray-100">
        <div class="w-full max-w-sm p-6 bg-white rounded-md shadow-md">
            <div class="mb-8 text-center">
                <h2 class="text-4xl font-bold tracking-tight mb-2">Forgot Password</h2>
                <p class="text-sm text-gray-700">
                    Enter your registered email to receive a password reset link.
                </p>
            </div>

            <!-- Error Message -->
            <div v-if="errorMsg" class="text-red-600 bg-red-100 border border-red-400 rounded p-4 mt-4 mb-4">
                {{ errorMsg }}
            </div>

            <!-- Success Message -->
            <div v-if="successMsg" class="text-green-600 bg-green-100 border border-green-400 rounded p-4 mt-4 mb-4">
                {{ successMsg }}
            </div>


            <form @submit.prevent="handleForgotPassword" class="mb-4">
                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="sr-only">Email</label>
                    <input type="email" id="email" placeholder="Enter your email" v-model="form.email"
                        :class="{ 'bg-red-100': errors?.email }" class="bg-gray-100 border-2 w-full p-4 rounded-lg" />
                    <div v-if="errors?.email" class="text-red-500 text-sm mt-2">
                        {{ errors.email[0] }}
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" :disabled="processing" :class="{ 'opacity-50': processing }"
                        class="bg-teal-500 text-white py-3 px-4 rounded font-medium w-full">
                        Send Reset Link
                    </button>
                </div>
            </form>

            <!-- Links -->
            <div class="mt-4 text-center text-sm">
                <p class="text-gray-700">
                    <NuxtLink to="/login" class="text-blue-500 hover:underline">Login</NuxtLink>
                </p>
            </div>
            <div class="mt-4 text-center text-sm">
                <p class="text-gray-700">
                    Register for free
                    <NuxtLink to="/register" class="text-blue-500 hover:underline">Register</NuxtLink>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
definePageMeta({
    middleware: ['sanctum:guest'],
});

const form = reactive({
    email: '',
});

const errors = ref({});
const processing = ref(false);
const errorMsg = ref()
const successMsg = ref()

const { forgotPassword } = useAuth()
const handleForgotPassword = async () => {
    processing.value = true;
    errors.value = {};
    errorMsg.value = '';
    successMsg.value = '';

    try {
        await forgotPassword(form);
        successMsg.value = 'Password rest email has been sent'
        // navigateTo('/login'); 
    } catch (e) {
        if (e?.status === 422) {
            errors.value = e.data.errors;
        } else {
            errorMsg.value = e.data.errors;
            console.error(e.data.errors);
        }
    } finally {
        processing.value = false;
    }
};
</script>