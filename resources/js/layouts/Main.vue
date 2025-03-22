<!-- src/layouts/Main.vue -->
<template>
    <div class="main-layout">
        <Navbar />
        <main>
            <slot></slot>
        </main>
    </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue';

</script>

<style scoped></style>