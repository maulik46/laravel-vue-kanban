<template>
    <Transition name="slide">
        <div v-if="isOpen" class="fixed inset-0 z-10 flex justify-end">
            <div class="bg-white border-l border-gray-200 w-full max-w-md p-6 h-full overflow-y-auto relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">{{ isEditMode ? 'Edit Card' : 'Add New Card' }}</h2>
                    <button @click="close" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                        <XMarkIcon class="size-6" />
                    </button>
                </div>

                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2" :class="errors.title ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"  v-model="formData.type_id">
                            <option v-for="taskType in taskTypes" :key="taskType.id" :value="taskType.id">{{ taskType.name }}</option>
                        </select>
                        <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
                    </div>

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
                                
                                <CheckIcon v-if="formData.color_code === color.value" class="size-4" />
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

                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition cursor-pointer"
                        :disabled="isSubmitting">
                        <span v-if="isSubmitting" class="inline-flex items-center">
                            <ArrowPathIcon class="size-5 animate-spin mr-2" />
                            Processing...
                        </span>
                        <span v-else>{{ isEditMode ? 'Update Card' : 'Add Card' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue';
import { XMarkIcon, CheckIcon, ArrowPathIcon } from '@heroicons/vue/24/solid';
import { FormData, FormErrors, TaskSidebarProps as Props} from '../types/task.ts';

const props = withDefaults(defineProps<Props>(), {
    isOpen: false,
    isEditMode: false,
    activeColumn: null,
    isSubmitting: false,
    taskTypes: () => [],
    cardData: () => ({
        id: null,
        title: '',
        description: '',
        color_code: '#F87171'
    }),
    colorOptions: () => [
        { name: 'Red', value: '#F87171' },
        { name: 'Green', value: '#4ADE80' },
        { name: 'Blue', value: '#60A5FA' },
        { name: 'Yellow', value: '#FBBF24' },
        { name: 'Purple', value: '#A78BFA' },
        { name: 'Pink', value: '#F472B6' },
        { name: 'Gray', value: '#9CA3AF' }
    ],
    serverErrors: () => ({})
});

const emit = defineEmits(['close','submit']);

const formData = reactive<FormData>({
    id: null,
    type_id: '',
    title: '',
    description: '',
    color_code: props.colorOptions[0]?.value || '#F87171'
});

const errors = reactive<FormErrors>({
    type_id: '',
    title: '',
    description: '',
    color_code: ''
});

const clearErrors = (): void => {
    errors.type_id = '';
    errors.title = '';
    errors.description = '';
    errors.color_code = '';
};

// WATCHERS

watch(() => props.serverErrors, (newErrors) => {
    if (newErrors && Object.keys(newErrors).length > 0) {
        clearErrors();
        
        if (newErrors.type_id) errors.type_id = newErrors.type_id;
        if (newErrors.title) errors.title = newErrors.title;
        if (newErrors.description) errors.description = newErrors.description;
        if (newErrors.color_code) errors.color_code = newErrors.color_code;
    }
}, { deep: true });

watch(() => props.cardData, (newCardData) => {
    formData.id = newCardData.id;
    formData.type_id = newCardData.type_id || '';
    formData.title = newCardData.title;
    formData.description = newCardData.description;
    formData.color_code = newCardData.color_code;
    
    clearErrors();
}, { immediate: true, deep: true });

watch(() => props.isOpen, (isOpen) => {
    if (isOpen && !props.isEditMode) {
        formData.id = null;
        formData.type_id = props.taskTypes.length > 0 ? props.taskTypes[0].id : '';
        formData.title = '';
        formData.description = '';
        formData.color_code = props.colorOptions.length > 0 ? props.colorOptions[0].value : '#F87171';
        
        clearErrors();
    }
});

// METHODS

const selectColor = (colorValue: string): void => {
    formData.color_code = colorValue;
    errors.color_code = '';
};

const validateForm = (): boolean => {
    clearErrors();
    let isValid = true;
    
    if (!formData.title.trim()) {
        errors.title = 'Title is required';
        isValid = false;
    }
    if (!formData.type_id) {
        errors.type_id = 'Please select task type';
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

const close = (): void => {
    clearErrors();
    emit('close');
};

const submitForm = (): void => {
    if (!validateForm()) return;
    
    emit('submit', {
        id: formData.id,
        type_id: formData.type_id,
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
