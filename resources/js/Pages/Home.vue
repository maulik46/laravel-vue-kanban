<template>
    <Main>
        <div class="min-h-screen bg-gray-100">
            <div class="max-w-7xl mx-auto p-4">
                <div class="flex space-x-4 overflow-x-auto pb-4">
                    <template v-for="(taskType, index) in localTaskTypes" :key="taskType.id">
                        <div
                            class="flex-shrink-0 lg:flex-shrink-1 w-full md:w-1/3 bg-gray-200 rounded-lg p-3 border border-gray-300">
                            <h2 class="font-bold text-lg mb-3 text-gray-700">{{ taskType.name }}</h2>
                            <div class="min-h-[200px] space-y-2 column-drop-zone" @dragover.prevent
                                @drop="onDrop($event, taskType.slug)">
                                <div v-for="(task, index) in taskType.tasks" :key="task.id">
                                    <TaskCard :card="task" :listType="taskType.slug" :index="index"
                                        :onDragStart="onDragStart" :onDragEnd="onDragEnd" :onCardDrop="onCardDrop"
                                        :showCardModal="showCardModal" :editCard="editCard" :deleteCard="deleteCard" />
                                </div>
                            </div>
                            <button @click="openSidebar(taskType.slug)"
                                class="mt-3 text-left px-2 py-1 text-gray-600 flex items-center hover:bg-gray-300 rounded transition cursor-pointer gap-x-1">
                                <PlusIcon class="size-5" /> <strong class="text-sm">Add a card</strong>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

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
                    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full m-4 p-6 max-h-[80vh] overflow-y-auto"
                        :style="{ borderTop: `5px solid ${selectedCard.color || '#333'}` }">
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
    </Main>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import Main from '@/Layouts/Main.vue';
import TaskCard from "@/Components/TaskCard.vue";
import { PlusIcon } from '@heroicons/vue/24/solid'
import { router } from '@inertiajs/vue3';

const props = defineProps({
    taskTypes: Array
});

const localTaskTypes = ref([]);

onMounted(() => {
    localTaskTypes.value = JSON.parse(JSON.stringify(props.taskTypes));
});

watch(() => props.taskTypes, (newTaskTypes) => {
    localTaskTypes.value = JSON.parse(JSON.stringify(newTaskTypes));
}, { deep: true });


const getTaskTypeId = (column) => {
    return localTaskTypes.value.find(type => type.slug === column)?.id;
};

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
        // Handle edit with Inertia
        router.put(`/tasks/${newCard.id}`, {
            title: newCard.title,
            description: newCard.description,
            color_code: newCard.color,
            task_type_id: getTaskTypeId(activeColumn.value)
        }, {
            onSuccess: () => closeSidebar()
        });
    } else {
        // Handle create with Inertia
        router.post('/tasks', {
            title: newCard.title,
            description: newCard.description,
            color_code: newCard.color,
            task_type_id: getTaskTypeId(activeColumn.value)
        }, {
            onSuccess: () => closeSidebar()
        });
    }
};

const editCard = (column, card) => {
    newCard.id = card.id;
    newCard.title = card.title;
    newCard.description = card.description;
    newCard.color = card.color_code;

    activeColumn.value = column;
    isEditMode.value = true;
    isSidebarOpen.value = true;
};

const deleteCard = (cardId) => {
    router.delete(`/tasks/${cardId}`);
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

        localTaskTypes.value = localTaskTypes.value.map(item => {
            if (item.slug === column) {
                return {
                    ...item,
                    tasks: [...item.tasks, draggedCard.value]
                }
            }
            else {
                return item;
            }
        })

        const newIndex = localTaskTypes.value.find(type => type.slug === column)?.tasks.length - 1 || 0;
        reorderTask(draggedCard.value.id, getTaskTypeId(column), newIndex);
    
        draggedCard.value = null;
        draggedCardColumn.value = null;
        draggedCardIndex.value = null;
    }
};

const onCardDrop = (event, column, dropIndex) => {
    event.preventDefault();
    event.stopPropagation();
    if (!draggedCard.value) return;

    reorderTask(draggedCard.value.id, getTaskTypeId(column), dropIndex);


    draggedCard.value = null;
    draggedCardColumn.value = null;
    draggedCardIndex.value = null;
};

const reorderTask = (taskId, columnId, indexOrder) => {
    router.post('/tasks/reorder', {
        task_id: taskId,
        task_type_id: columnId,
        index_order: indexOrder
    });
};

const removeCardFromColumn = (column, cardId) => {
    localTaskTypes.value = localTaskTypes.value.map(item => {
        if (item.slug === column) {
            return {
                ...item,
                tasks: item.tasks.filter(card => card.id !== cardId)
            }
        }
        else {
            return item;
        }
    })
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