<template>
    <div v-if="isSidebarOpen" class="bg-[#00000060] bg-opacity-50 absolute inset-0"></div>
    <Transition name="slide">
        <div v-if="isSidebarOpen" class="fixed inset-0 z-10 flex justify-end">
            <div class="bg-white w-full max-w-md p-6 h-full overflow-y-auto relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">{{ isEditMode ? 'Edit Card' : 'Add New Card' }}</h2>
                    <button @click="closeSidebar" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitCard">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input id="title" v-model="newCard.title" type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required />
                    </div>

                    <div class="mb-4">
                        <label for="cardColor" class="block text-sm font-medium text-gray-700 mb-1">Card
                            Color</label>
                        <div class="flex flex-wrap gap-2">
                            <div v-for="(color, index) in colorOptions" :key="index" @click="selectColor(color.value)"
                                class="w-8 h-8 rounded-md cursor-pointer border border-gray-300 flex items-center justify-center"
                                :style="{ backgroundColor: color.value }">
                                <svg v-if="newCard.color === color.value" xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5" viewBox="0 0 20 20" fill="white">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" v-model="newCard.description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition">
                        {{ isEditMode ? 'Update Card' : 'Add Card' }}
                    </button>
                </form>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';

const props = defineProps({
    isSidebarOpen: {
        type: Boolean,
        required: true
    },
    isEditMode: {
        type: Boolean,
        default: false
    },
    newCard: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close-sidebar', 'submit-card']);

const taskData = ref({
    id: null,
    title: '',
    description: '',
    color: '#333333',
    task_type_id: null
});

const colorOptions = ref([
    { value: '#FF5252' }, // Red
    { value: '#FF9800' }, // Orange
    { value: '#FFEB3B' }, // Yellow
    { value: '#4CAF50' }, // Green
    { value: '#2196F3' }, // Blue
    { value: '#9C27B0' }, // Purple
    { value: '#795548' }, // Brown
    { value: '#607D8B' }  // Gray-Blue
]);

// Watch for changes in the newCard prop
watch(() => props.newCard, (newVal) => {
    taskData.value = { ...newVal };
}, { immediate: true, deep: true });

const closeSidebar = () => {
    emit('close-sidebar');
};

const selectColor = (color) => {
    taskData.value.color = color;
};

const submitForm = () => {
    emit('submit-card', taskData.value);
};
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}
</style>