<template>
    <Main>
        <div>
            <div class="max-w-7xl mx-auto py-4 px-4 md:px-0">
                <div class="flex space-x-4 overflow-x-auto pb-4">
                    <template v-for="taskType in localTaskTypes" :key="taskType.id">
                        <div class="flex-shrink-0 lg:flex-shrink-1 w-full md:w-1/3 bg-gray-200 rounded-lg p-3 border border-gray-300 relative min-h-[50vh] overflow-auto column-drop-zone"
                            @dragover.prevent @drop="onDrop($event, taskType.slug)">
                            <h2 class="font-bold text-lg mb-3 text-gray-700">{{ taskType.name }}</h2>
                            <div class="min-h-[200px] space-y-2 mb-20">
                                <div v-for="(task, index) in taskType.tasks" :key="task.id">
                                    <TaskCard :card="task" :listType="taskType.slug" :index="index"
                                        :onDragStart="onDragStart" :onDragEnd="onDragEnd" :onCardDrop="onCardDrop"
                                        :showCardModal="showCardModal" :editCard="editCard" :deleteCard="deleteCard" />
                                </div>
                            </div>
                            <button @click="openSidebar(taskType.slug)"
                                class="absolute bottom-2 left-2 mt-3 text-left p-2 text-gray-600 flex items-center bg-gray-300 hover:opacity-70  rounded transition cursor-pointer gap-x-1">
                                <PlusIcon class="size-5" /> <strong class="text-sm">Add a Card</strong>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <FormSidebar :isOpen="isSidebarOpen" :isEditMode="isEditMode" :activeColumn="activeColumn"
                :cardData="newCard" :taskTypes="taskTypeName" :colorOptions="colorOptions" :serverErrors="serverErrors"
                :isSubmitting="isSubmitting" @close="closeSidebar" @submit="submitCard" />

            <Modal :isOpen="isCardModalOpen" :cardData="selectedCard" @close="closeCardModal" />

        </div>
    </Main>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, watch, computed } from 'vue';
import { PlusIcon } from '@heroicons/vue/24/solid';
import { router } from '@inertiajs/vue3';
import Main from '@/Layouts/Main.vue';
import TaskCard from "@/Components/TaskCard.vue";
import FormSidebar from "@/Components/FormSidebar.vue";
import Modal from '@/Components/Modal.vue';

// Simple type definitions
type TaskType = {
    id: number;
    name: string;
    slug: string;
    tasks: any[];
}

// PROPS
const props = defineProps({
    taskTypes: {
        type: Array as () => TaskType[],
        required: true
    },
    errors: {
        type: Object,
        default: () => ({})
    }
});

// STATES
const localTaskTypes = ref([]);
const serverErrors = ref({});
const isSubmitting = ref(false);
const colorOptions = [
    { name: 'Red', value: '#F87171' },
    { name: 'Green', value: '#4ADE80' },
    { name: 'Blue', value: '#60A5FA' },
    { name: 'Yellow', value: '#FBBF24' },
    { name: 'Purple', value: '#A78BFA' },
    { name: 'Pink', value: '#F472B6' },
    { name: 'Gray', value: '#9CA3AF' }
];
const isSidebarOpen = ref(false);
const activeColumn = ref(null);
const isEditMode = ref(false);
const newCard = reactive({
    id: null,
    type_id: props.taskTypes[0]?.id,
    title: '',
    description: '',
    color_code: colorOptions[0].value
});
const isCardModalOpen = ref(false);
const selectedCard = ref({});
const draggedCard = ref(null);
const draggedCardColumn = ref(null);
const draggedCardIndex = ref(null);

onMounted(() => {
    localTaskTypes.value = JSON.parse(JSON.stringify(props.taskTypes));
});

// WATCHERS
watch(() => props.taskTypes, (newTaskTypes) => {
    localTaskTypes.value = JSON.parse(JSON.stringify(newTaskTypes));
}, { deep: true });

watch(() => props.errors, (newErrors) => {
    if (newErrors && Object.keys(newErrors).length > 0) {
        serverErrors.value = newErrors;
        isSubmitting.value = false;
    } else {
        serverErrors.value = {};
    }
});

// COMPUTED
const taskTypeName = computed(() => {
    return props.taskTypes.map(item => {
        return {
            id: item.id,
            name: item.name,
            slug: item.slug
        }
    });
});

// METHODS
const getTaskTypeId = (column: string) => {
    return localTaskTypes.value.find(type => type.slug === column)?.id;
};

const openSidebar = (column: string) => {
    activeColumn.value = column;
    isSidebarOpen.value = true;
    isEditMode.value = false;
    newCard.id = null;
    newCard.type_id = '';
    newCard.title = '';
    newCard.description = '';
    newCard.color_code = colorOptions[0].value;
    serverErrors.value = {};
    isSubmitting.value = false;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
    isEditMode.value = false;
    serverErrors.value = {};
    isSubmitting.value = false;
};

const submitCard = (formData: any) => {
    isSubmitting.value = true;

    if (isEditMode.value) {
        // Handle edit with Inertia
        router.put(`/tasks/${formData.id}`, {
            title: formData.title,
            description: formData.description,
            color_code: formData.color_code,
            task_type_id: formData.type_id || getTaskTypeId(formData.column)
        },
            {
                onSuccess: () => {
                    closeSidebar();
                    isSubmitting.value = false;
                },
                onError: (errors) => {
                    console.log(errors);
                    serverErrors.value = errors;
                    isSubmitting.value = false;
                }
            });
    } else {
        // Handle create with Inertia
        router.post('/tasks', {
            title: formData.title,
            description: formData.description,
            color_code: formData.color_code,
            task_type_id: formData.type_id || getTaskTypeId(formData.column)
        }, {
            onSuccess: () => closeSidebar(),
            onError: (errors) => {
                console.log(errors);
                serverErrors.value = errors;
                isSubmitting.value = false;
            }
        });
    }
};

const editCard = (column: string, card: any) => {
    newCard.id = card.id;
    newCard.type_id = card.task_type_id;
    newCard.title = card.title;
    newCard.description = card.description;
    newCard.color_code = card.color_code;

    activeColumn.value = column;
    isEditMode.value = true;
    isSidebarOpen.value = true;
};

const deleteCard = (cardId: number) => {
    router.delete(`/tasks/${cardId}`);
};

const showCardModal = (card: any) => {
    selectedCard.value = { ...card };
    isCardModalOpen.value = true;
};

const closeCardModal = () => {
    isCardModalOpen.value = false;
};

const reorderTask = (taskId: number, columnId: number, indexOrder: number) => {
    router.post('/tasks/reorder', {
        task_id: taskId,
        task_type_id: columnId,
        index_order: indexOrder
    });
};

const onDragStart = (event: DragEvent, card: any, column: string, index: number) => {
    draggedCard.value = card;
    draggedCardColumn.value = column;
    draggedCardIndex.value = index;

    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', card.id);

    setTimeout(() => {
        (event.target as HTMLElement).classList.add('opacity-50');
    }, 0);
};

const onDragEnd = (event: DragEvent) => {
    (event.target as HTMLElement).classList.remove('opacity-50');
};

const onDrop = (event: DragEvent, column: string) => {
    event.preventDefault();

    if (!draggedCard.value) return;

    const originalColumn = draggedCardColumn.value;

    if (column === originalColumn) {
        return; // Same column and not onto a specific card (handled by onCardDrop)
    }

    removeCardFromColumn(originalColumn, draggedCard.value.id);

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
    });

    const newIndex = localTaskTypes.value.find(type => type.slug === column)?.tasks.length - 1 || 0;
    reorderTask(draggedCard.value.id, getTaskTypeId(column), newIndex);

    draggedCard.value = null;
    draggedCardColumn.value = null;
    draggedCardIndex.value = null;
};

const onCardDrop = (event: DragEvent, column: string, dropIndex: number) => {
    event.preventDefault();
    event.stopPropagation();

    if (!draggedCard.value) return;

    reorderTask(draggedCard.value.id, getTaskTypeId(column), dropIndex);

    draggedCard.value = null;
    draggedCardColumn.value = null;
    draggedCardIndex.value = null;
};

const removeCardFromColumn = (column: string, cardId: number) => {
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