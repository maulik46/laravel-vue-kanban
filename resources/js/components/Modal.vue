<template>
    <Transition name="fade">
        <div v-if="isOpen && cardData" class="fixed inset-0 z-20 flex items-center justify-center">
            <div class="absolute inset-0 bg-[#00000060] bg-opacity-50" @click="emit('close')"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-2/4 m-4 p-6"
                :style="{ borderTop: `5px solid ${cardData.color_code || '#333'}` }">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold flex-grow">{{ cardData.title }}</h2>
                    <button @click="emit('close')" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                        <XMarkIcon class="size-5" />
                    </button>
                </div>
                <div class="mt-4 text-gray-700 h-[60vh] overflow-y-auto">
                    <p class="whitespace-pre-line">{{ cardData.description }}</p>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { XMarkIcon } from '@heroicons/vue/24/solid';
import { CardData } from '../types/task.ts';

interface Props {
    isOpen: boolean;
    cardData: CardData | null;
}

defineProps<Props>();

const emit = defineEmits(['close']);

</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
