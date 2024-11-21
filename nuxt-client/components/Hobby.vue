<template>
    <div class="mb-4">
        <label for="hobbies" class="block text-gray-700">Hobbies</label>

        <div class="mt-2 relative">
            <input
                type="text"
                v-model="searchQuery"
                @input="filterHobbies"
                class="w-full px-3 py-2 border border-gray-300 rounded"
                placeholder="Type a hobby..."
            />

            <div
                v-if="filteredHobbies.length && searchQuery"
                class="absolute z-10 w-full bg-white border border-gray-300 shadow-lg mt-1 rounded max-h-60 overflow-y-auto">
                <ul>
                    <li
                        v-for="hobby in filteredHobbies"
                        :key="hobby.id"
                        @click="addHobby(hobby)"
                        :class="{
                            'bg-teal-200': isSelected(hobby),
                            'cursor-pointer p-2 hover:bg-teal-200': true,
                        }"
                    >
                        {{ hobby.name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <span
                v-for="(hobby, index) in selectedHobbies"
                :key="hobby.id"
                class="flex items-center bg-teal-200 text-teal-800 px-3 py-1 rounded-full"
            >
                {{ hobby.name }}
                <button @click="removeHobby(index)" class="ml-2 text-teal-500 hover:text-teal-700">
                    &times;
                </button>
            </span>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    modelValue: { type: Array, default: [] }, 
});

const sanctumFetch = useSanctumClient()
const emit = defineEmits(['update:modelValue']); 
const searchQuery = ref('');
const hobbiesList = ref([]);
const filteredHobbies = ref([]);
const selectedHobbies = ref([...props.modelValue]);

watch(
    () => props.modelValue,
    (newValue) => {
        selectedHobbies.value = [...newValue];
    },
    { immediate: true }
);

const fetchHobbies = async () => {
    try {
        const response = await sanctumFetch('/hobbies');
        const data = response;
        hobbiesList.value = data;
        filteredHobbies.value = hobbiesList.value;
    } catch (error) {
        console.error('Error fetching hobbies:', error);
    }
};

const filterHobbies = () => {
    if (searchQuery.value) {
        filteredHobbies.value = hobbiesList.value.filter(hobby =>
            hobby.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    } else {
        filteredHobbies.value = hobbiesList.value;
    }
};

const addHobby = (hobby) => {
    if (!selectedHobbies.value.some(selected => selected.id === hobby.id)) {
        selectedHobbies.value.push(hobby);
    }
    emit('update:modelValue', selectedHobbies.value);
    searchQuery.value = '';
};

const removeHobby = (index) => {
    selectedHobbies.value.splice(index, 1);
    emit('update:modelValue', selectedHobbies.value);
};

const isSelected = (hobby) => {
    return selectedHobbies.value.some(selected => selected.id === hobby.id);
};

fetchHobbies();
</script>


<style scoped>
input {
    transition: border-color 0.3s ease;
}

input:focus {
    border-color: #4fd1c5;
}

button {
    cursor: pointer;
    font-size: 16px;
    padding: 0;
}

button:hover {
    color: #1a202c;
}
</style>