<template>


    <div class="flex justify-center items-center justify-center px-6">
        <div class="w-full max-w-sm p-6 bg-white rounded-md shadow-md">

            <div class="mb-8">
                <h2 class="text-4xl font-bold tracking-tight mb-2">Log in.</h2>
                <p class="text-sm text-gray-800">Log in with email and password you were provided.</p>
            </div>

            <form @submit.prevent="handleLogin" class="mb-4">

                <div class="mb-4">
                    <label for="email" class="sr-only">Email</label>
                    <input type="text" name="email" id="email" placeholder="Enter your email"
                        class="bg-gray-100 border-2 w-full p-4 rounded-lg" v-model="form.email"
                        :class="{ 'bg-red-100': errors?.email }" />
                    <div v-if="errors?.email" class="text-red-500 text-sm mt-2">
                        {{ errors.email[0] }}
                    </div>

                </div>

                <div class="mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password"
                        class="bg-gray-100 border-2 w-full p-4 rounded-lg " v-model="form.password"
                        :class="{ 'bg-red-100': errors?.password }" />
                    <div v-if="errors?.password" class="text-red-500 text-sm mt-2">
                        {{ errors.password[0] }}
                    </div>

                </div>

               

                <div>
                    <button type="submit" name="login" :class="{ 'opacity-25': processing }" :disabled="processing"
                        class="bg-teal-500 text-white py-3 px-4 rounded font-medium w-full">Login</button>
                </div>
            </form>

            <div class="mt-4 text-center text-sm">
                    <p class="text-gray-700">
                        <NuxtLink to="/forgot-password" class="text-blue-500 hover:underline">Forgot Password</NuxtLink>
                    </p>
                </div>

            <div class="mt-4 text-center text-sm">
                <p class="text-gray-700">
                    Register for Free
                    <NuxtLink to="/register" class="text-blue-500 hover:underline">Register</NuxtLink>
                </p>
            </div>

        </div>
    </div>

</template>

<script setup>

definePageMeta({
    middleware: ['sanctum:guest']
});

const { login } = useSanctumAuth()
const user = useSanctumUser()
const userStore = useUserStore();

const form = reactive({
    email: '',
    password: ''
})

const errors = ref({})
const processing = ref(false)

const handleLogin = async () => {
    processing.value = true
    errors.value = {}
    try {
        await login(form)

        userStore.setUser(user.value);
        if (user.value && !user.value.email_verified_at) {
            navigateTo('/verification-notice')
        } else {
            navigateTo('/')
        }

    } catch (e) {
        if (e.statusCode === 422) {
            errors.value = e.data.errors
        }
    } finally {
        processing.value = false
    }
}




</script>