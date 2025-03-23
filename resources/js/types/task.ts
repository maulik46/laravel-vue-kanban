export interface TaskType {
    id: number;
    name: string;
}

export interface ColorOption {
    name: string;
    value: string;
}

export interface CardData {
    id: number | null;
    type_id?: number | string;
    title: string;
    description: string;
    color_code: string;
}

export interface FormData {
    id: number | null;
    type_id: number | string;
    title: string;
    description: string;
    color_code: string;
}

export interface FormErrors {
    type_id: string;
    title: string;
    description: string;
    color_code: string;
}

export interface ServerErrors {
    [key: string]: string;
}

export interface TaskSidebarProps {
    isOpen: boolean;
    isEditMode: boolean;
    activeColumn: string | null;
    isSubmitting: boolean;
    taskTypes: TaskType[];
    cardData: CardData;
    colorOptions: ColorOption[];
    serverErrors: ServerErrors;
}