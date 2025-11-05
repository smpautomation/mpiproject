<template>
    <Frontend>
        <div class="flex flex-col justify-start min-h-screen px-4 py-12 bg-gray-100">
            <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                <!-- Header Section -->
                <div class="mb-6 pb-4 border-b-2 border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Process Monitoring Dashboard</h2>
                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                Select a mass production below to view real-time process completion status across all layers
                            </p>
                        </div>
                    </div>

                    <!-- Mass Prod Selector -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-gray-700">
                            Mass Production Name <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="selectedMassProd"
                            class="w-full max-w-md px-4 py-3 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white transition-all cursor-pointer"
                        >
                            <option value="">Select Mass Production</option>
                            <option v-for="item in massProd_names" :key="item" :value="item">
                                {{ item }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Tree Process -->
                <div v-if="massProductionData.length > 0" class="space-y-6">
                    <div v-for="mpc in massProductionData" :key="mpc.mass_prod">
                        <div class="mb-4 flex items-center gap-2">
                            <h3 class="text-lg font-bold text-gray-800">{{ mpc.mass_prod }}</h3>
                            <span class="px-3 py-1 text-xs font-semibold bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 rounded-full border border-teal-200">
                                {{ mpc.layers.length }} Layers
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="(layer, index) in mpc.layers"
                                :key="index"
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-cyan-300 hover:shadow-md transition-all duration-200"
                            >
                                <!-- Layer Header -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 flex items-center justify-center font-bold text-white bg-gradient-to-br from-teal-500 to-cyan-500 rounded-lg shadow-sm">
                                            {{ index }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">Layer {{ index }}</p>
                                            <p class="text-xs text-gray-500">Type: <span class="font-medium text-gray-700">{{ layer.type || '—' }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Process Status Grid -->
                                <div class="grid grid-cols-3 gap-3">
                                    <!-- Heat Treatment -->
                                    <div
                                        :class="[
                                            'relative px-4 py-3 rounded-lg text-center font-semibold text-sm transition-all duration-200 border-2',
                                            layer.heat_treatment_completed
                                                ? 'bg-green-500 border-green-600 text-white shadow-md'
                                                : 'bg-white border-gray-300 text-gray-500'
                                        ]"
                                    >
                                        <div v-if="layer.heat_treatment_completed" class="absolute -top-2 -right-2 w-6 h-6 bg-green-600 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        Heat Treatment
                                    </div>

                                    <!-- Coating -->
                                    <div
                                        :class="[
                                            'relative px-4 py-3 rounded-lg text-center font-semibold text-sm transition-all duration-200 border-2',
                                            (layer.coating_completed || layer.film_pasting_completed)
                                                ? 'bg-teal-500 border-teal-600 text-white shadow-md'
                                                : 'bg-white border-gray-300 text-gray-500'
                                        ]"
                                    >
                                        <div v-if="layer.coating_completed || layer.film_pasting_completed" class="absolute -top-2 -right-2 w-6 h-6 bg-teal-600 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        {{ layer.type === 'Film Pasting' ? 'Film Pasting' : 'Coating' }}
                                    </div>

                                    <!-- MPI -->
                                    <div
                                        :class="[
                                            'relative px-4 py-3 rounded-lg text-center font-semibold text-sm transition-all duration-200 border-2',
                                            layer.mpi_completed
                                                ? 'bg-cyan-500 border-cyan-600 text-white shadow-md'
                                                : 'bg-white border-gray-300 text-gray-500'
                                        ]"
                                    >
                                        <div v-if="layer.mpi_completed" class="absolute -top-2 -right-2 w-6 h-6 bg-cyan-600 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        MPI
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Select a mass production to view progress</p>
                </div>
            </div>

        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import Modal from '@/Components/Modal.vue'
import { useAuth } from '@/Composables/useAuth.js'
import { useToast } from 'vue-toast-notification';
const toast = useToast();

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

const massProductionData = ref([]);
const selectedMassProd = ref('');
const massProd_names = ref([]);

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getMassProdLists = async () => {
    try {
        const response = await axios.get('/api/mass-production/');
        const massProdList = response.data;
        massProd_names.value = massProdList.map(item => item.mass_prod);

        // Optionally, set default selected mass prod
        if(massProd_names.value.length > 0) {
            selectedMassProd.value = massProd_names.value[0];
            await getMassProductionStatus(selectedMassProd.value);
        }

    } catch(error) {
        console.error('Error fetching mass prod lists', error);
        toast.error('Failed to get the mass prod lists');
    }
};

const getMassProductionStatus = async (massProd) => {
    if(!massProd) return;

    try {
        const response = await axios.get(`/api/mass-production-monitoring/${massProd}`);
        if(response.data.success) {
            massProductionData.value = response.data.data; // should be a single mass prod data
        } else {
            toast.error('Failed to load mass production data.');
        }
    } catch(error) {
        console.error('Failed to get mass production status data', error);
        toast.error('Error fetching mass production data.');
    }
};


// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

watch(selectedMassProd, async (newMassProd) => {
    await getMassProductionStatus(newMassProd);
});

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    await getMassProductionStatus();
    await getMassProdLists();
});

</script>
