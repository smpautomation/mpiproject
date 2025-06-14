<template>
<Frontend>
    <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
        <div class="flex flex-col justify-center items-center bg-blue-300">
            <p class="pt-10 font-extrabold text-xl">Mias Factor Upload</p>
            <input type="file" @change="handleFileUpload" />
            <button @click="uploadFile">Upload</button>
            <DotsLoader v-if="loading" />
            <p v-if="message">{{ message }}</p>
        </div>
    </div>
</Frontend>
</template>


<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted, nextTick, watch, computed, watchEffect } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DotsLoader from '@/Components/DotsLoader.vue';
import axios from 'axios'

const file = ref(null);
const loading = ref(false);
const message = ref('');
const handleFileUpload = (event) => {
    file.value = event.target.files[0];
};



</script>
