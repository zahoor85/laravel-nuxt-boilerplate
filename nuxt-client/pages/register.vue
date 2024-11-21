<template>
    <div class="flex justify-center items-center justify-center px-6">
        <div class="w-full max-w-sm p-6 bg-white rounded-md shadow-md">

            <div class="mb-8">
                <h2 class="text-4xl font-bold tracking-tight mb-2">Sign in.</h2>
                <p class="text-sm text-gray-800">Sign in with providing following details.</p>
            </div>

            <form @submit.prevent="handleRegister" class="mb-4">


                <div class="mb-4">
                    <label for="name" class="sr-only">Name</label>
                    <input type="text" name="name" v-model="form.name" id="name" placeholder="Enter your name"
                        class="bg-gray-100 border-2 w-full p-4 rounded-lg" :class="{ 'bg-red-100': errors?.name }" />


                    <div v-if="errors?.name" class="text-red-500 text-sm mt-2">
                        {{ errors.name[0] }}
                    </div>


                </div>

                <div class="mb-4">
                    <label for="email" class="sr-only">Email</label>
                    <input type="text" name="email" id="email" :class="{ 'bg-red-100': errors?.email }"
                        v-model="form.email" placeholder="Enter your email"
                        class="bg-gray-100 border-2 w-full p-4 rounded-lg " />
                    <div v-if="errors?.email" class="text-red-500 text-sm mt-2">
                        {{ errors.email[0] }}
                    </div>

                </div>

                <div class="mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" v-model="form.password" id="password"
                        :class="{ 'bg-red-100': errors?.password }" placeholder="choose your password"
                        class="bg-gray-100 border-2 w-full p-4 rounded-lg " />
                    <div v-if="errors?.password" class="text-red-500 text-sm mt-2">
                        {{ errors.password[0] }}
                    </div>

                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="sr-only">Password Confirmation</label>
                    <input type="password" name="password_confirmation" v-model="form.password_confirmation"
                        :class="{ 'bg-red-100': errors?.password_confirmation }" id="password_confirmation"
                        placeholder="Password Confirmation" class="bg-gray-100 border-2 w-full p-4 rounded-lg" />
                    <div v-if="errors?.password_confirmation" class="text-red-500 text-sm mt-2">
                        {{ errors.password_confirmation[0] }}
                    </div>
                </div>

                <div>
                    <button type="submit" name="register"
                        class="bg-teal-500 text-white py-3 px-4 rounded font-medium w-full"
                        :class="{ 'opacity-25': processing }" :disabled="processing">Register</button>
                </div>
            </form>
            <div class="mt-4 text-center text-sm">
                <p class="text-gray-700">
                    Already registered?
                    <NuxtLink to="/login" class="text-blue-500 hover:underline">Login here</NuxtLink>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref } from 'vue';
definePageMeta({
    middleware: ['sanctum:guest']
});


const { register } = useAuth()
const { refreshIdentity } = useSanctumAuth()

const form = reactive({
    name: '',
    email: '',
    password: ''
})
const errors = ref({})
const processing = ref(false)

const handleRegister = async () => {
    processing.value = true
    errors.value = {}
    try {

        const response = await register(form);
        // await refreshIdentity()
        await navigateTo('/')
        // toast.success('User registered successfully.', {
        //     position: "top-right"
        // });


        // await navigateTo('/dashboard')

    } catch (e) {
        if (e.statusCode === 422) {
            console.log(errors);
            errors.value = e.data.errors
            //     toast.error('Failed to registered ...', {
            //     position: "top-right"
            // });
        }
    } finally {
        processing.value = false

    }
}




</script>