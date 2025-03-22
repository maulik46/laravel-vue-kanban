<template>
    <div class="bg-white shadow px-4 py-3">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800">Kanban</h1>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue'
    const message = ref("Hello from Vue.js")
</script>

<style scoped>
.message-model {
    text-align: center;
    border-radius: 10px;
    width: 80%;
    margin: auto;
}
.message-model p {
    font-weight: bold;
    font-size: 40px;
}
</style>