<template>
    <div v-if="isOpen" class="bg-[#00000060] bg-opacity-50 absolute inset-0"></div>
    <Transition name="slide">
        <div v-if="isOpen" class="fixed inset-0 z-10 flex justify-end">
            <div class="bg-white w-full max-w-md p-6 h-full overflow-y-auto relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">{{ isEditMode ? 'Edit Card' : 'Add New Card' }}</h2>
                    <button @click="close" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input id="title" v-model="formData.title" type="text"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2"
                            :class="errors.title ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                             />
                        <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="cardColor" class="block text-sm font-medium text-gray-700 mb-1">Card
                            Color</label>
                        <div class="flex flex-wrap gap-2">
                            <div v-for="(color, index) in colorOptions" :key="index"
                                @click="selectColor(color.value)"
                                class="w-8 h-8 rounded-md cursor-pointer border border-gray-300 flex items-center justify-center"
                                :style="{ backgroundColor: color.value }">
                                <svg v-if="formData.color_code === color.value" xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5" viewBox="0 0 20 20" fill="white">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <p v-if="errors.color_code" class="mt-1 text-sm text-red-600">{{ errors.color_code }}</p>
                    </div>

                    <div class="mb-6">
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" v-model="formData.description" rows="4"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2"
                            :class="errors.description ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"></textarea>
                        <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition"
                        :disabled="isSubmitting">
                        <span v-if="isSubmitting" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                        <span v-else>{{ isEditMode ? 'Update Card' : 'Add Card' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    isEditMode: {
        type: Boolean,
        default: false
    },
    activeColumn: {
        type: String,
        default: null
    },
    isSubmitting: {
        type: Boolean,
        default: false
    },
    cardData: {
        type: Object,
        default: () => ({
            id: null,
            title: '',
            description: '',
            color_code: '#F87171'
        })
    },
    colorOptions: {
        type: Array,
        default: () => [
            { name: 'Red', value: '#F87171' },
            { name: 'Green', value: '#4ADE80' },
            { name: 'Blue', value: '#60A5FA' },
            { name: 'Yellow', value: '#FBBF24' },
            { name: 'Purple', value: '#A78BFA' },
            { name: 'Pink', value: '#F472B6' },
            { name: 'Gray', value: '#9CA3AF' }
        ]
    },
    serverErrors: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['close', 'submit']);

const formData = reactive({
    id: null,
    title: '',
    description: '',
    color_code: props.colorOptions[0].value
});

const errors = reactive({
    general: '',
    title: '',
    description: '',
    color_code: ''
});

const clearErrors = () => {
    errors.general = '';
    errors.title = '';
    errors.description = '';
    errors.color_code = '';
};

// WATCHERS

watch(() => props.serverErrors, (newErrors) => {
    if (newErrors && Object.keys(newErrors).length > 0) {
        clearErrors();
        
        if (newErrors.title) errors.title = newErrors.title;
        if (newErrors.description) errors.description = newErrors.description;
        if (newErrors.color_code) errors.color_code = newErrors.color_code;
        
        if (newErrors.message) errors.general = newErrors.message;
        else if (newErrors.error) errors.general = newErrors.error;
    }
}, { deep: true });

watch(() => props.cardData, (newCardData) => {
    formData.id = newCardData.id;
    formData.title = newCardData.title;
    formData.description = newCardData.description;
    formData.color_code = newCardData.color_code;
    
    clearErrors();
}, { immediate: true, deep: true });

watch(() => props.isOpen, (isOpen) => {
    if (isOpen && !props.isEditMode) {
        formData.id = null;
        formData.title = '';
        formData.description = '';
        formData.color_code = props.colorOptions[0].value;
        
        clearErrors();
    }
});

// METHODS

const selectColor = (colorValue) => {
    formData.color_code = colorValue;
    errors.color_code = '';
};

const validateForm = () => {
    clearErrors();
    let isValid = true;
    
    if (!formData.title.trim()) {
        errors.title = 'Title is required';
        isValid = false;
    }
    
    if (!formData.description) {
        errors.description = 'Please enter task description';
        isValid = false;
    }

    if (!formData.color_code) {
        errors.color_code = 'Please select a color';
        isValid = false;
    }
    
    return isValid;
};

const close = () => {
    clearErrors();
    emit('close');
};

const submitForm = () => {
    if (!validateForm()) return;
    
    emit('submit', {
        id: formData.id,
        title: formData.title,
        description: formData.description,
        color_code: formData.color_code,
        column: props.activeColumn
    });
};
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease-out;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}
</style>
