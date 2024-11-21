<template>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-center mb-8">User Settings</h2>

        <!-- Error Message -->
        <div v-if="errorMsg" class="text-red-600 bg-red-100 border border-red-400 rounded p-4 mt-4 mb-4">
            {{ errorMsg }}
        </div>

        <!-- Success Message -->
        <div v-if="successMsg" class="text-green-600 bg-green-100 border border-green-400 rounded p-4 mt-4 mb-4">
            {{ successMsg }}
        </div>

        <!-- Update name -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-4">Update name</h3>
            <form @submit.prevent="handleUpdateUsername">
                <div class="mb-4">
                    <label for="name" class="sr-only">name</label>
                    <input type="text" id="name" placeholder="Enter your new name" v-model="form.name"
                        :class="{ 'bg-red-100': errors?.name }"
                        class="w-full p-4 border-2 rounded-lg focus:ring-teal-500 focus:border-teal-500" />
                    <div v-if="errors?.name" class="text-red-500 text-sm mt-2">
                        {{ errors.name[0] }}
                    </div>
                </div>
                <div>
                    <button type="submit" :disabled="processingName" :class="{ 'opacity-50': processingName }"
                        class="py-3 px-4 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">
                        Update name
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Email -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-4">Change Email</h3>
            <p>If you'd like to change your email address, we'll send you a verification link to the new email.</p>

            <!-- Email Input and Button -->
            <form @submit.prevent="handleChangeEmail">
                <div class="mb-4">
                    <label for="newEmail" class="sr-only">New Email</label>
                    <input type="email" id="newEmail" placeholder="Enter your new email" v-model="form.newEmail"
                        :class="{ 'bg-red-100': errors?.newEmail }"
                        class="w-full p-4 border-2 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
                    <div v-if="errors?.newEmail" class="text-red-500 text-sm mt-2">
                        {{ errors.newEmail[0] }}
                    </div>
                </div>
                <div>
                    <button type="submit" :disabled="processingEmail" :class="{ 'opacity-50': processingEmail }"
                        class="py-3 px-4 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">
                        Change Email
                    </button>
                </div>
            </form>
        </div>

        <!-- Reset Password -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-4">Reset Password</h3>
            <p class="mt-4 mb-4">If you want to reset your password, we'll send you a password reset link.</p>

            <button type="button" @click="handlePasswordReset" :disabled="processingPassword"
                :class="{ 'opacity-50': processingPassword }"
                class="py-3 px-4 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">
                Reset Password
            </button>


        </div>

    </div>
</template>

<script setup>
definePageMeta({
    middleware: ['sanctum:auth', 'email-verified']
});

const userStore = useUserStore();
const form = ref({
    name: userStore.user?.name || '',
    newEmail: ''
});

const errors = ref({})
const errorMsg = ref()
const successMsg = ref()

// Separate processing flags
const processingName = ref(false);
const processingEmail = ref(false);
const processingPassword = ref(false);

const { updateUserName, requestChangeMail, requestResetPassword } = useUser();

const handleUpdateUsername = async () => {
    processingName.value = true; // Only set the flag for this action
    errors.value = {};
    errorMsg.value = '';
    successMsg.value = '';

    try {
        await updateUserName({ name: form.value.name });
        userStore.setUser({
            ...userStore.user,
            name: form.value.name
        });
        successMsg.value = 'Name updated successfully!';
    } catch (e) {
        errors.value = e.data.errors;
        errorMsg.value = e.data.message;
    } finally {
        processingName.value = false;
    }
};

const handleChangeEmail = async () => {
    processingEmail.value = true; // Only set the flag for this action
    errors.value = {};
    errorMsg.value = '';
    successMsg.value = '';

    try {
        await requestChangeMail({ new_email: form.value.newEmail });
        successMsg.value = 'Email change confirmation link has been sent to your registered email';
    } catch (e) {
        errors.value = e.data.errors;
        errorMsg.value = e.data.message;
    } finally {
        processingEmail.value = false;
    }
};

const handlePasswordReset = async () => {
    processingPassword.value = true; // Only set the flag for this action
    errors.value = {};
    errorMsg.value = '';
    successMsg.value = '';

    let form = {
        email: userStore.user.email,
    };
    try {
        await requestResetPassword(form);
        successMsg.value = 'Password reset confirmation link has been sent to your registered email';
    } catch (e) {
        errors.value = e.data.errors;
        errorMsg.value = e.data.message;
    } finally {
        processingPassword.value = false;
    }
};
</script>
