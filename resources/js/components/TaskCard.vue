<template>
    <div :data-card-id="card.id" draggable="true" @dragstart="onDragStart($event, card, listType, index)" @dragend="onDragEnd" @dragover.prevent @drop.stop="onCardDrop($event, listType, index)" :style="{ borderLeft: `4px solid ${card.color_code || '#333'}` }" class="bg-white p-3 rounded shadow cursor-move hover:opacity-70">
        <div class="flex justify-between items-start">
            <div class="cursor-pointer flex-grow" @click.stop="showCardModal(card)">
                <h3 class="font-medium">{{ card.title }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ card.description }}</p>
            </div>
            <div class="flex space-x-2">
                <button @click.stop="editCard(listType, card)" class="text-gray-500 hover:text-blue-500 cursor-pointer transition">
                    <PencilSquareIcon class="size-5" />
                </button>
                <button @click="isShowDelete = true" class="text-gray-500 hover:text-red-500 cursor-pointer transition">
                    <TrashIcon class="size-5" />
                </button>
            </div>
        </div>
    </div>
    <div class="mt-1 p-2 bg-white rounded shadow" v-if="isShowDelete">
        <div class="flex justify-between items-center">
            <strong>Are you sure?</strong>
            <div class="flex space-x-2">
                <button @click.stop="deleteCard(card.id)" class="text-gray-500 hover:text-green-500 cursor-pointer transition">
                    <CheckIcon class="size-5" />
                </button>
                <button @click="isShowDelete = false" class="text-gray-500 hover:text-red-500 cursor-pointer transition">
                    <XMarkIcon class="size-5" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, ref } from "vue";
import { TrashIcon, PencilSquareIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import { CardData } from '../types/task.ts';

interface Props {
  card: CardData;
  listType: string;
  index: number;
  onDragStart: (event: DragEvent, card: CardData, fromList: string, index: number) => void;
  onDragEnd: () => void;
  onCardDrop: (event: DragEvent, toList: string) => void;
  showCardModal: (card: CardData) => void;
  editCard: (card: CardData) => void;
  deleteCard: (card: CardData) => void;
}

defineProps<Props>();

const isShowDelete = ref<boolean>(false);
</script>
