<template>
    <div :data-card-id="card.id" draggable="true" @dragstart="onDragStart($event, card, listType, index)" @dragend="onDragEnd" @dragover.prevent @drop.stop="onCardDrop($event, listType, index)" :style="{ borderLeft: `4px solid ${card.color || '#333'}` }" class="bg-white p-3 rounded shadow cursor-move hover:opacity-70">
        <div class="flex justify-between items-start">
            <div class="cursor-pointer flex-grow" @click.stop="showCardModal(card)">
                <h3 class="font-medium">{{ card.title }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ card.description }}</p>
            </div>
            <div class="flex space-x-2">
                <button @click.stop="editCard(listType, card)" class="text-gray-500 hover:text-blue-500 cursor-pointer transition">
                    <PencilSquareIcon class="size-4" />
                </button>
                <button @click.stop="deleteCard(listType, card.id)" class="text-gray-500 hover:text-red-500 cursor-pointer transition">
                    <TrashIcon class="size-4" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    card: Object,
    listType: String,
    index: Number,
    onDragStart: Function,
    onDragEnd: Function,
    onCardDrop: Function,
    showCardModal: Function,
    editCard: Function,
    deleteCard: Function,
});
</script>