<template>
    <Frontend>
        <div class="flex flex-col justify-start min-h-screen px-4 py-12 bg-gray-100">
            <div class="max-w-4xl p-6 mx-auto bg-white border border-gray-200 shadow-lg rounded-xl">
                <!-- Header Section -->
                <div class="pb-4 mb-6 border-b-2 border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Process Monitoring Dashboard</h2>
                            <p class="mt-1 text-sm leading-relaxed text-gray-600">
                                Select a furnace + mass production below to view real-time process completion status across all layers
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-row justify-around">
                        <!-- Furnace Selector -->
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-semibold text-gray-700">
                                Furnace Name <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="selectedFurnace"
                                class="w-full max-w-md px-4 py-3 text-sm font-medium text-gray-700 transition-all border border-gray-300 rounded-lg shadow-sm cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white"
                            >
                                <option value="">Select Mass Furnace</option>
                                <option v-for="item in furnace_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>

                        <!-- Mass Prod Selector -->
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-semibold text-gray-700">
                                Mass Production Name <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="selectedMassProd"
                                class="w-full max-w-md px-4 py-3 text-sm font-medium text-gray-700 transition-all border border-gray-300 rounded-lg shadow-sm cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white"
                            >
                                <option value="">Select Mass Production</option>
                                <option v-for="item in massProd_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                    </div>


                </div>

                <!-- Tree Process -->
                <div v-if="massProductionData.length > 0" class="space-y-6">
                    <div v-for="mpc in massProductionData" :key="mpc.mass_prod">
                        <div class="flex items-center gap-2 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">{{ mpc.mass_prod }}</h3>
                            <span class="px-3 py-1 text-xs font-semibold text-teal-700 border border-teal-200 rounded-full bg-gradient-to-r from-teal-100 to-cyan-100">
                                A total of {{ mpc.numberOfLotsWithData }} Lots detected in this mass production
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="(layer, index) in mpc.layers"
                                :key="index + '-' + layer.model + '-' + layer.lot_no"
                                class="p-4 transition-all duration-200 border border-gray-200 rounded-lg bg-gray-50 hover:border-cyan-300 hover:shadow-md"
                            >
                                <!-- Layer Header -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 font-bold text-white rounded-lg shadow-sm bg-gradient-to-br from-teal-500 to-cyan-500">
                                            {{ layer.layer_no }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                Layer {{ layer.layer_no }}
                                                <span v-if="layer.lot_type === 'main'" class="text-xs font-medium text-teal-700">(Main Lot)</span>
                                                <span v-if="layer.lot_type === 'additional'" class="text-xs font-medium text-cyan-700">(Additional Lot)</span>
                                            </p>
                                            <p class="text-xs text-gray-500">Type: <span class="font-medium text-gray-700">{{ layer.type || '—' }}</span></p>
                                            <p class="text-xs text-gray-500">
                                                Model: <span class="font-medium text-gray-700">{{ layer.model || '—' }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Lot No: <span class="font-medium text-gray-700">{{ layer.lot_no || '—' }}</span>
                                            </p>
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
                                        <div v-if="layer.heat_treatment_completed" class="absolute flex items-center justify-center w-6 h-6 bg-green-600 rounded-full shadow-md -top-2 -right-2">
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
                                            layer.coating_completed
                                                ? 'bg-teal-500 border-teal-600 text-white shadow-md'
                                                : 'bg-white border-gray-300 text-gray-500'
                                        ]"
                                    >
                                        <div v-if="layer.coating_completed" class="absolute flex items-center justify-center w-6 h-6 bg-teal-600 rounded-full shadow-md -top-2 -right-2">
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
                                        <div v-if="layer.mpi_completed" class="absolute flex items-center justify-center w-6 h-6 rounded-full shadow-md -top-2 -right-2 bg-cyan-600">
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
                <div v-else class="py-12 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="font-medium text-gray-500">Select a mass production to view progress</p>
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
const selectedFurnace = ref('');
const massProd_names = ref([]);
const furnace_names = ref([]);

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getMassProdLists = async () => {
    try {
        const response = await axios.get('/api/mass-production/');
        const massProdList = response.data;
        massProd_names.value = massProdList.map(item => item.mass_prod);

        // Optionally, set default selected mass prod
        /*
        if(massProd_names.value.length > 0) {
            selectedMassProd.value = massProd_names.value[0];
            await getMassProductionStatus(selectedMassProd.value);
        }*/

    } catch(error) {
        console.error('Error fetching mass prod lists', error);
        toast.error('Failed to get the mass prod lists');
    }
};

const getFurnaceLists = async () => {
        try{
            const response = await axios.get('/api/furnace-data');
            const furnaceList = response.data;
            furnace_names.value = furnaceList.map(item => item.furnace_name);
            //console.log("List of mass prods: ",furnace_names.value);
        }catch(error){
            console.error('Error fetching mass prod lists',error);
            toast.error('Failed to get the mass prod lists api error');
        }
    }

const getMassProductionStatus = async () => {
    if(!selectedFurnace.value || !selectedMassProd.value) return;

    try {
        const response = await axios.get(`/api/mass-production-monitoring/${selectedFurnace.value}/${selectedMassProd.value}`);
        if(response.data.success) {
            massProductionData.value = response.data.data;

            // Compute total lots for each mass production
            massProductionData.value.forEach(mpc => {
                const lotsSet = new Set();
                mpc.layers.forEach(layer => {
                    if(layer.lot_no) lotsSet.add(layer.lot_no);
                });
                mpc.numberOfLotsWithData = lotsSet.size;
            });

        } else {
            massProductionData.value = [];
            toast.error('Failed to load mass production data.');
        }
    } catch(error) {
        massProductionData.value = [];
        console.error('Failed to get mass production status data', error);
        toast.error('Error fetching mass production data.');
    }
};

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

watch(
    [selectedFurnace, selectedMassProd],
    async ([newFurnace, newMassProd]) => {
        if (newFurnace && newMassProd) {
            await getMassProductionStatus(newFurnace, newMassProd);
        }
    }
);

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    await getMassProdLists();
    await getFurnaceLists();
});

</script>
