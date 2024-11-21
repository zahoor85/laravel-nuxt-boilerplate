<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-6 py-12">
      <main-navigation />
      <div class="max-w-2xl text-center">
        <p class="text-2xl font-semibold text-primary-600">{{ error.statusCode }}</p>
        <h1 class="mt-4 text-6xl font-bold text-gray-600">Oops!</h1>
        <h2 class="mt-4 text-3xl font-bold text-gray-600">{{ error.message }}</h2>
        <div class="mt-10">
          <button 
            @click="handleClearError" 
            class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600">
            Go home
          </button>
        </div>
      </div>
    </div>
  </template>
  
<script setup>
// defineProps({
//   error: Object,
// });
const error = useError();

function handleClearError() {
  clearError({ redirect: "/" });
}
</script>
