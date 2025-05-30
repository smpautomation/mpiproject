<!-- Login.vue -->
<template>
    <Frontend>
        <div class="flex flex-col justify-center items-center">
            <p class="pt-10 font-extrabold text-xl">Login</p>
            <form @submit.prevent="login" class="flex flex-col gap-4 max-w-sm mx-auto mt-10">
                <input v-model="username" type="text" placeholder="Username" required class="p-2 border" />
                <input v-model="password" type="password" placeholder="Password" required class="p-2 border" />
                <button type="submit" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Submit</button>
                <p v-if="error" class="text-red-500">{{ error }}</p>
            </form>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted, nextTick, watch, computed, watchEffect } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DotsLoader from '@/Components/DotsLoader.vue';
import axios from 'axios'

const email = ref('')
const username = ref('')
const password = ref('')
const error = ref('')

// CSRF token setup (only needed once globally, ideally)
axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const login = async () => {
  try {
    // Make sure CSRF cookie is set (handled by Laravel)
    await axios.get('/sanctum/csrf-cookie') // Even for session-based login, this initializes CSRF

    await axios.post('/login', {
      username: username.value,
      password: password.value,
    })

    // Redirect or reload after login
    window.location.href = '/dashboard' // Or your desired route
  } catch (e) {
    error.value = e.response?.data?.message || 'Login failed'
  }
}
</script>
