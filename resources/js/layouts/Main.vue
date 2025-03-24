<template>
    <div class="main-layout min-h-screen bg-gray-100">
        <Navbar />
        <main>
            <slot></slot>
        </main>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';

</script>
