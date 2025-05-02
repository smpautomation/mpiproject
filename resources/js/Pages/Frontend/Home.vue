<template>
    <Frontend>
        <div class="relative flex flex-col items-center justify-center min-h-screen px-8 py-12 mx-auto bg-cover bg-center"
         :style="{ backgroundImage: 'url(/photo/home_background.jpg)' }">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-40 z-0"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-center mt-12 font-bold text-white text-2xl drop-shadow">
            Welcome! This is the MPI AUTOMATION project home page.
        </div>
    </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
</script>
