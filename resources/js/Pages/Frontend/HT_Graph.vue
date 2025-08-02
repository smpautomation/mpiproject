<template>
  <Frontend>
    <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 space-y-6 bg-gray-100">
      <h1 class="text-2xl font-semibold text-gray-800"><span>123RD</span> Mass Production Heat Treatment Graphs</h1>

      <div class="flex w-full max-w-6xl gap-6">
        <!-- Standard Graph Panel -->
        <div class="flex-1 bg-white border border-gray-300 rounded shadow-sm p-4">
          <h2 class="text-lg font-medium text-gray-700 mb-4">Standard Graph</h2>
          <div
            class="w-full h-[400px] bg-gray-50 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-4"
          >
          <!-- <img :src="" /> -->
            No image uploaded
          </div>
          <button
            class="p-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded"
            @click="downloadImage('standard')"
          >
            Download Image
          </button>
        </div>

        <!-- Actual Graph Panel -->
        <div class="flex-1 bg-white border border-gray-300 rounded shadow-sm p-4">
          <h2 class="text-lg font-medium text-gray-700 mb-4">Actual Graph</h2>
          <div
            class="w-full h-[400px] bg-gray-50 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-4"
          >
          <!-- <img :src="" /> -->
            No image uploaded
          </div>
          <button
            class="p-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded"
            @click="downloadImage('actual')"
          >
            Download Image
          </button>
        </div>
      </div>
    </div>
  </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js'

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {
        const start = Date.now();
        const timeout = 500; // 5 seconds

        while (!state.user) {
            if (Date.now() - start > timeout) {
                console.error('Auth timeout: user data failed to load within 5 seconds.');
                Inertia.visit('/'); // Redirect if not authenticated
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50)); // small delay
        }

        if (!state.isAuthenticated) {
            Inertia.visit('/'); // Redirect if not authenticated
            return false; // Indicate not authenticated
        }

        console.warn("USER AUTHENTICATED!");
        console.warn("Name: ", state.user.firstName + " " + state.user.surname);
        console.warn("Access: ", state.user.access_type);

        return true; // Indicate authenticated
    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/'); // Redirect on error
        return false; // Indicate not authenticated
    }
};

</script>
