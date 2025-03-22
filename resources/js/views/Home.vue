<template>
    <div class="min-h-screen bg-gray-100">
        <div class="max-w-7xl mx-auto p-4">
            <div class="flex space-x-4 overflow-x-auto pb-4">
                <div class="flex-shrink-0 lg:flex-shrink-1 w-full md:w-1/3 bg-gray-200 rounded-lg p-3">
                    <h2 class="font-semibold text-lg mb-3 text-gray-700">To Do</h2>
                    <div class="min-h-[200px] space-y-2 column-drop-zone" @dragover.prevent
                        @drop="onDrop($event, 'todo')">
                        <div v-for="(card, index) in todoCards" :key="card.id" :data-card-id="card.id" draggable="true"
                            @dragstart="onDragStart($event, card, 'todo', index)" @dragend="onDragEnd" @dragover.prevent
                            @drop.stop="onCardDrop($event, 'todo', index)"
                            :style="{ borderLeft: `4px solid ${card.color || '#333'}` }"
                            class="bg-white p-3 rounded shadow cursor-move">
                            <div class="flex justify-between items-start">
                                <div class="cursor-pointer flex-grow" @click.stop="showCardModal(card)">
                                    <h3 class="font-medium">{{ card.title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ card.description }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click.stop="editCard('todo', card)"
                                        class="text-gray-500 hover:text-blue-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="deleteCard('todo', card.id)"
                                        class="text-gray-500 hover:text-red-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button @click="openSidebar('todo')"
                        class="mt-3 w-full text-left p-2 text-gray-600 bg-gray-100 hover:bg-gray-300 rounded transition">
                        Add a card
                    </button>
                </div>

                <div class="flex-shrink-0 lg:flex-shrink-1 w-full md:w-1/3 bg-gray-200 rounded-lg p-3">
                    <h2 class="font-semibold text-lg mb-3 text-gray-700">In Progress</h2>
                    <div class="min-h-[200px] space-y-2 column-drop-zone" @dragover.prevent
                        @drop="onDrop($event, 'inProgress')">
                        <div v-for="(card, index) in inProgressCards" :key="card.id" :data-card-id="card.id"
                            draggable="true" @dragstart="onDragStart($event, card, 'inProgress', index)"
                            @dragend="onDragEnd" @dragover.prevent @drop.stop="onCardDrop($event, 'inProgress', index)"
                            :style="{ borderLeft: `4px solid ${card.color || '#333'}` }"
                            class="bg-white p-3 rounded shadow cursor-move">
                            <div class="flex justify-between items-start">
                                <div class="cursor-pointer flex-grow" @click.stop="showCardModal(card)">
                                    <h3 class="font-medium">{{ card.title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ card.description }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click.stop="editCard('inProgress', card)"
                                        class="text-gray-500 hover:text-blue-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="deleteCard('inProgress', card.id)"
                                        class="text-gray-500 hover:text-red-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button @click="openSidebar('inProgress')"
                        class="mt-3 w-full text-left p-2 text-gray-600 bg-gray-100 hover:bg-gray-300 rounded transition">
                        Add a card
                    </button>
                </div>

                <div class="flex-shrink-0 lg:flex-shrink-1 w-full md:w-1/3 bg-gray-200 rounded-lg p-3">
                    <h2 class="font-semibold text-lg mb-3 text-gray-700">Done</h2>
                    <div class="min-h-[200px] space-y-2 column-drop-zone" @dragover.prevent
                        @drop="onDrop($event, 'done')">
                        <div v-for="(card, index) in doneCards" :key="card.id" :data-card-id="card.id" draggable="true"
                            @dragstart="onDragStart($event, card, 'done', index)" @dragend="onDragEnd" @dragover.prevent
                            @drop.stop="onCardDrop($event, 'done', index)"
                            :style="{ borderLeft: `4px solid ${card.color || '#333'}` }"
                            class="bg-white p-3 rounded shadow cursor-move">
                            <div class="flex justify-between items-start">
                                <div class="cursor-pointer flex-grow" @click.stop="showCardModal(card)">
                                    <h3 class="font-medium">{{ card.title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ card.description }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click.stop="editCard('done', card)"
                                        class="text-gray-500 hover:text-blue-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click.stop="deleteCard('done', card.id)"
                                        class="text-gray-500 hover:text-red-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button @click="openSidebar('done')"
                        class="mt-3 w-full text-left p-2 text-gray-600 bg-gray-100 hover:bg-gray-300 rounded transition">
                        Add a card
                    </button>
                </div>
            </div>
        </div>

        <div v-if="isSidebarOpen" class="bg-[#00000060] bg-opacity-50 absolute inset-0" @click="closeSidebar"></div>
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
                                <div v-for="(color, index) in colorOptions" :key="index"
                                    @click="selectColor(color.value)"
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

                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition">
                            {{ isEditMode ? 'Update Card' : 'Add Card' }}
                        </button>
                    </form>
                </div>
            </div>
        </Transition>

        <Transition name="fade">
            <div v-if="isCardModalOpen" class="fixed inset-0 z-20 flex items-center justify-center">
                <div class="absolute inset-0 bg-[#00000060] bg-opacity-50" @click="closeCardModal"></div>
                <div
                    class="relative bg-white rounded-lg shadow-xl max-w-md w-full m-4 p-6 max-h-[80vh] overflow-y-auto" :style="{ borderTop: `5px solid ${selectedCard.color || '#333'}`}">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold flex-grow">{{ selectedCard.title }}</h2>
                        <button @click="closeCardModal" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4 text-gray-700">
                        <p class="whitespace-pre-line">{{ selectedCard.description }}</p>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const todoCards = ref([
    { id: 1, title: 'Research competitors', description: 'Analyze the top 5 competitors in the market', color: '#F87171' }
]);

const inProgressCards = ref([
    { id: 2, title: 'Design homepage mockup', description: 'Create a responsive design for the new homepage', color: '#60A5FA' }
]);

const doneCards = ref([
    { id: 3, title: 'Setup project repository', description: 'Initialize Git repository and configure CI/CD pipeline', color: '#4ADE80' },
    { id: 4, title: 'Create project documentation', description: 'Write initial README and contribution guidelines', color: '#A78BFA' }
]);

const colorOptions = [
    { name: 'Red', value: '#F87171' },
    { name: 'Green', value: '#4ADE80' },
    { name: 'Blue', value: '#60A5FA' },
    { name: 'Yellow', value: '#FBBF24' },
    { name: 'Purple', value: '#A78BFA' },
    { name: 'Pink', value: '#F472B6' },
    { name: 'Gray', value: '#9CA3AF' }
];

const selectColor = (colorValue) => {
    newCard.color = colorValue;
};

const isSidebarOpen = ref(false);
const activeColumn = ref(null);
const isEditMode = ref(false);
const newCard = reactive({
    id: null,
    title: '',
    description: '',
    color: colorOptions[0].value
});

const isCardModalOpen = ref(false);
const selectedCard = ref({});

const draggedCard = ref(null);
const draggedCardColumn = ref(null);
const draggedCardIndex = ref(null);

const openSidebar = (column) => {
    activeColumn.value = column;
    isSidebarOpen.value = true;
    isEditMode.value = false;
    newCard.id = null;
    newCard.title = '';
    newCard.description = '';
    newCard.color = colorOptions[0].value;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
    isEditMode.value = false;
};

const submitCard = () => {
    if (isEditMode.value) {
        if (activeColumn.value === 'todo') {
            const index = todoCards.value.findIndex(card => card.id === newCard.id);
            if (index !== -1) {
                todoCards.value[index] = { ...newCard };
            }
        } else if (activeColumn.value === 'inProgress') {
            const index = inProgressCards.value.findIndex(card => card.id === newCard.id);
            if (index !== -1) {
                inProgressCards.value[index] = { ...newCard };
            }
        } else if (activeColumn.value === 'done') {
            const index = doneCards.value.findIndex(card => card.id === newCard.id);
            if (index !== -1) {
                doneCards.value[index] = { ...newCard };
            }
        }
        isEditMode.value = false;
    } else {
        const id = Date.now();
        const card = {
            id,
            title: newCard.title,
            description: newCard.description,
            color: newCard.color
        };

        if (activeColumn.value === 'todo') {
            todoCards.value.push(card);
        } else if (activeColumn.value === 'inProgress') {
            inProgressCards.value.push(card);
        } else if (activeColumn.value === 'done') {
            doneCards.value.push(card);
        }
    }

    closeSidebar();
};

const editCard = (column, card) => {
    newCard.id = card.id;
    newCard.title = card.title;
    newCard.description = card.description;
    newCard.color = card.color;

    activeColumn.value = column;
    isEditMode.value = true;
    isSidebarOpen.value = true;
};

const deleteCard = (column, cardId) => {
    if (column === 'todo') {
        todoCards.value = todoCards.value.filter(card => card.id !== cardId);
    } else if (column === 'inProgress') {
        inProgressCards.value = inProgressCards.value.filter(card => card.id !== cardId);
    } else if (column === 'done') {
        doneCards.value = doneCards.value.filter(card => card.id !== cardId);
    }
};

const showCardModal = (card) => {
    selectedCard.value = { ...card };
    isCardModalOpen.value = true;
};

const closeCardModal = () => {
    isCardModalOpen.value = false;
};

const onDragStart = (event, card, column, index) => {
    draggedCard.value = card;
    draggedCardColumn.value = column;
    draggedCardIndex.value = index;

    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', card.id);

    setTimeout(() => {
        event.target.classList.add('opacity-50');
    }, 0);
};

const onDragEnd = (event) => {
    event.target.classList.remove('opacity-50');
};

const onDrop = (event, column) => {
    event.preventDefault();

    if (column === draggedCardColumn.value) {
        return;
    }

    if (draggedCard.value) {
        removeCardFromColumn(draggedCardColumn.value, draggedCard.value.id);

        if (column === 'todo') {
            todoCards.value.push(draggedCard.value);
        } else if (column === 'inProgress') {
            inProgressCards.value.push(draggedCard.value);
        } else if (column === 'done') {
            doneCards.value.push(draggedCard.value);
        }

        draggedCard.value = null;
        draggedCardColumn.value = null;
        draggedCardIndex.value = null;
    }
};

const onCardDrop = (event, column, dropIndex) => {
    event.preventDefault();
    event.stopPropagation();

    if (!draggedCard.value) return;

    if (column === draggedCardColumn.value) {
        let columnCards;
        if (column === 'todo') {
            columnCards = todoCards;
        } else if (column === 'inProgress') {
            columnCards = inProgressCards;
        } else {
            columnCards = doneCards;
        }

        const dragCard = columnCards.value.splice(draggedCardIndex.value, 1)[0];

        const adjustedDropIndex = draggedCardIndex.value < dropIndex ? dropIndex - 1 : dropIndex;
        columnCards.value.splice(adjustedDropIndex, 0, dragCard);
    } 
    else {
        removeCardFromColumn(draggedCardColumn.value, draggedCard.value.id);

        let targetCards;
        if (column === 'todo') {
            targetCards = todoCards;
        } else if (column === 'inProgress') {
            targetCards = inProgressCards;
        } else {
            targetCards = doneCards;
        }

        targetCards.value.splice(dropIndex, 0, draggedCard.value);
    }

    draggedCard.value = null;
    draggedCardColumn.value = null;
    draggedCardIndex.value = null;
};

const removeCardFromColumn = (column, cardId) => {
    if (column === 'todo') {
        todoCards.value = todoCards.value.filter(card => card.id !== cardId);
    } 
    else if (column === 'inProgress') {
        inProgressCards.value = inProgressCards.value.filter(card => card.id !== cardId);
    } 
    else if (column === 'done') {
        doneCards.value = doneCards.value.filter(card => card.id !== cardId);
    }
};
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from {
    transform: translateX(100%);
}

.slide-leave-to {
    transform: translateX(100%);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.column-drop-zone {
    min-height: 200px;
    transition: background-color 0.2s ease;
}

.column-drop-zone.drag-over {
    background-color: rgba(96, 165, 250, 0.1);
}
</style>