<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="max-w-lg w-full mx-auto bg-white p-6 rounded-xl shadow-lg">
      <h2 class="text-2xl font-semibold text-center mb-4">Edit Profile</h2>

      <!-- Error Message -->
      <div v-if="errorMsg" class="text-red-600 bg-red-100 border border-red-400 rounded p-4 mt-4 mb-4">
        {{ errorMsg }}
      </div>

      <!-- Success Message -->
      <div v-if="successMsg" class="text-green-600 bg-green-100 border border-green-400 rounded p-4 mt-4 mb-4">
        {{ successMsg }}
      </div>

      <form @submit.prevent="handleSubmit">

        <!-- Full Name -->
        <div class="mb-4">
          <label for="full_name" class="block text-gray-700">Full Name</label>
          <input type="text" id="full_name" v-model="form.full_name"  :class="{ 'bg-red-100': errors?.full_name }"
            class="mt-2 w-full px-3 py-2 border border-gray-300 rounded" />
            <div v-if="errors?.full_name" class="text-red-500 text-sm mt-2">
                        {{ errors.full_name[0] }}
                    </div>
        </div>

        <!-- Gender -->
        <div class="mb-4">
          <label for="gender" class="block text-gray-700">Gender</label>
          <select id="gender" v-model="form.gender" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded"
            required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>

        <!-- Date of Birth -->
        <div class="mb-4">
          <label for="dob" class="block text-gray-700">Date of Birth</label>
          <input type="date" id="dob" v-model="form.dob" class="mt-2 w-full px-3 py-2 border border-gray-300 rounded"
            required />
        </div>

        <!-- Hobbies -->
        <Hobby v-model="form.hobbies"></Hobby>

        <!-- Profile Image -->
        <div class="mb-4">
          <label for="profile_image" class="block text-gray-700">Profile Image</label>
          <input type="file" id="profile_image" @change="handleImageChange" accept="image/*"
            class="mt-2 w-full px-3 py-2 border border-gray-300 rounded" />
          <div v-if="imagePreview" class="mt-2 text-center">
            <img :src="imagePreview" alt="Image preview" class="w-32 h-32 object-cover rounded-full mx-auto" />
          </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
          <button type="submit" class="px-6 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 mr-2"
            :disabled="processing" :class="{ 'opacity-50': processing }">
            Save Changes
          </button>

          <button @click="goBack" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            Back
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>

definePageMeta({
  middleware: ['sanctum:auth', 'email-verified']
});
const { profile, updateProfile } = useProfile();
const imagePreview = ref(null);
const form = ref({
  full_name: '',
  email: '',
  gender: '',
  dob: '',
  hobbies: [],
  image: null
});

watch(profile, (newProfile) => {
  if (newProfile && newProfile.profile) {
    form.value.full_name = newProfile.profile.full_name || '';
    form.value.email = newProfile.email || '';
    form.value.gender = newProfile.profile.gender || '';
    form.value.dob = newProfile.profile.dob || '';
    form.value.hobbies = newProfile.profile.hobbies || [];
  }
}, { immediate: true });

const errors = ref({})
const processing = ref(false)
const errorMsg = ref()
const successMsg = ref()

const handleSubmit = async () => {

  const formData = new FormData();
  // Append regular fields
  formData.append('full_name', form.value.full_name);
  formData.append('email', form.value.email);
  formData.append('gender', form.value.gender);
  formData.append('dob', form.value.dob);
  formData.append('hobbies', JSON.stringify(form.value.hobbies));
  // Append image file
  if (form.value.image) {
    formData.append('profile_picture', form.value.image);
  }

  processing.value = true;
  errors.value = {};
  errorMsg.value = ''
  errorMsg.value = ''


  try {
    await updateProfile(formData);
    successMsg.value = 'Your profile has been updated';

  } catch (e) {

    errors.value = e.data.errors;
    errorMsg.value = e.data.message;

  } finally {
    processing.value = false;
  }


};

const handleImageChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    imagePreview.value = URL.createObjectURL(file);
    form.value.image = file;
  }
};

const goBack = () => {
  history.back();
}

</script>