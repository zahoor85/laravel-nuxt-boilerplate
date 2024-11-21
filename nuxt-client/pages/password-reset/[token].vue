<template>
  <div class="flex justify-center items-center px-6 bg-gray-100">
    <div class="w-full max-w-sm p-6 bg-white rounded-md shadow-md">
      <div class="mb-8 text-center">
        <h2 class="text-4xl font-bold tracking-tight mb-2">Reset Password</h2>
        <p class="text-sm text-gray-700">
          Enter your new password below.
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

      <form @submit.prevent="handleResetPassword" class="mb-4">
        <!-- Password Input -->
        <div class="mb-4">
          <label for="password" class="sr-only">Password</label>
          <input type="password" id="password" placeholder="Enter your new password" v-model="form.password"
            :class="{ 'bg-red-100': errors?.password }" class="bg-gray-100 border-2 w-full p-4 rounded-lg" />
          <div v-if="errors?.password" class="text-red-500 text-sm mt-2">
            {{ errors.password[0] }}
          </div>
        </div>

        <!-- Password Confirmation Input -->
        <div class="mb-4">
          <label for="password_confirmation" class="sr-only">Confirm Password</label>
          <input type="password" id="password_confirmation" placeholder="Confirm your new password"
            v-model="form.password_confirmation" :class="{ 'bg-red-100': errors?.password_confirmation }"
            class="bg-gray-100 border-2 w-full p-4 rounded-lg" />
          <div v-if="errors?.password_confirmation" class="text-red-500 text-sm mt-2">
            {{ errors.password_confirmation[0] }}
          </div>
        </div>

        <!-- Submit Button -->
        <div>
          <button type="submit" :disabled="processing" :class="{ 'opacity-50': processing }"
            class="bg-teal-500 text-white py-3 px-4 rounded font-medium w-full">
            Reset Password
          </button>
        </div>

        <div class="mt-4 text-center text-sm">
          <p class="text-gray-700">
            <NuxtLink to="/login" class="text-blue-500 hover:underline">Login</NuxtLink>
          </p>
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: ['sanctum:guest'],
});

const route = useRoute();
const form = reactive({
  password: '',
  password_confirmation: '',
});

const { restPassword } = useAuth();

const errors = ref({});
const processing = ref(false);
const errorMsg = ref()
const successMsg = ref()

const handleResetPassword = async () => {
  processing.value = true;
  errors.value = {};
  errorMsg.value = '';

  try {
    // Get token and email from query
    const token = route.params.token;
    const email = route.query.email;

    const formBody = {
      token: token,
      email: email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    };

    // Replace with your actual API endpoint
    await restPassword(formBody);
    successMsg.value = 'Your password has been reset successfully';

  } catch (e) {
    if (e?.status === 422) {
      errors.value = e.data.errors;
      if (e.data.errors.email) {
        errorMsg.value = e.data.message;
      }

    } else {
      errorMsg.value = e.data.errors;
      console.error(e.data.errors);
    }
  } finally {
    processing.value = false;
  }
};
</script>
