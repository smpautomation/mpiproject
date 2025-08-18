<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
            <button @click="showModalCreate = true" class="px-4 py-2 mb-5 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                Create New
            </button>

            <div class="max-w-7xl mx-auto bg-white border border-gray-200 shadow-xl rounded-2xl p-16 md:p-14 space-y-6">
                <!-- Search Bar -->
                <div class="mb-4 flex items-center justify-between">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search Mass Prod..."
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm w-64 text-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <div class="overflow-x-auto shadow-lg border border-gray-200 bg-white">
                    <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wide text-center">Date</th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wide text-center">Mass Prod</th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wide text-center">Furnace</th>
                            <th class="px-6 py-4 font-bold text-gray-700 uppercase tracking-wide text-center">Action (View)</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="item in paginatedItems"
                            :key="item.id"
                            class="hover:bg-gray-50 transition duration-150 text-center"
                        >
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ new Date(item.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ item.mass_prod }}</td>
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ item.furnace }}</td>
                            <td class="px-6 py-4 flex justify-center items-center gap-3 flex-wrap">
                            <button
                                @click="viewControlSheet(item.mass_prod)"
                                class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >
                                Control Sheet
                            </button>
                            <button
                                @click="viewHTGraph(item.mass_prod)"
                                class="px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                            >
                                Heat Treatment Graph
                            </button>
                            <button
                                @click="viewSMPData(item.mass_prod)"
                                class="px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            >
                                SMP Data
                            </button>
                            </td>
                        </tr>
                        <tr v-if="paginatedItems.length === 0">
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">No results found.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Controls -->
                <div class="flex justify-between items-center mt-4 text-sm">
                    <div class="text-gray-600">
                        Showing {{ startIndex + 1 }}–{{ Math.min(endIndex, filteredItems.length) }} of {{ filteredItems.length }}
                    </div>
                    <div class="space-x-2">
                        <button
                        @click="prevPage"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 disabled:opacity-50"
                        >
                        Previous
                        </button>
                        <button
                        @click="nextPage"
                        :disabled="endIndex >= filteredItems.length"
                        class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 disabled:opacity-50"
                        >
                        Next
                        </button>
                    </div>
                </div>
            </div>


            <Modal :show="showModalCreate" @close="showModalCreate = false">
                <div class="relative flex flex-col items-center justify-center bg-white p-6 rounded-xl w-full max-w-3xl mx-auto shadow-2xl">

                    <!-- Exit Button -->
                    <button
                    @click="showModalCreate = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition duration-150"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <p class="text-gray-800 text-lg font-bold mb-6">Please fill out all the fields</p>

                    <div class="flex flex-col md:flex-row w-full gap-6 mb-8">
                    <div class="flex flex-col w-full">
                        <label class="text-sm font-semibold text-gray-700 mb-1">Mass Production Name</label>
                        <input
                        type="text"
                        v-model="massProd_name"
                        @input="massProd_name = massProd_name.toUpperCase()"
                        placeholder="ex. 541ST"
                        class="w-full px-4 py-2 text-sm text-gray-800 font-medium placeholder-gray-400 uppercase bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="text-sm font-semibold text-gray-700 mb-1">Furnace No</label>
                        <input
                        type="text"
                        v-model="massProd_furnace"
                        @input="massProd_furnace = massProd_furnace.toUpperCase()"
                        placeholder="ex. K-40"
                        class="w-full px-4 py-2 text-sm text-gray-800 font-medium placeholder-gray-400 uppercase bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        />
                    </div>
                    </div>
                    <div v-if="!showConfirmation">
                        <button
                        @click="submitForm"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-md shadow-sm transition duration-200"
                        >
                            Submit
                        </button>
                    </div>
                    <div v-else class="flex flex-col justify-center items-center gap-4">
                        <p class="font-semibold">Are you <span class="text-red-600">sure</span> that input is correct?</p>
                        <div class="flex gap-4">
                            <button
                                @click="showConfirmation = false;"
                                class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold text-sm rounded-md shadow-sm transition duration-200"
                            >
                                No
                            </button>
                            <button
                                @click="saveToDatabase"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-md shadow-sm transition duration-200"
                            >
                                Yes
                            </button>
                        </div>
                    </div>
                    <DotsLoader v-if="loadingState" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-50" />
                </div>
            </Modal>



        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted, nextTick, watch, computed, watchEffect, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3'
import DotsLoader from '@/Components/DotsLoader.vue';
import Modal from '@/Components/Modal.vue' // adjust path if needed
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js';
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

const showModalCreate = ref(false);
const showConfirmation = ref(false);
const loadingState = ref(false);

const massProd_name = ref('');
const massProd_furnace = ref('');

const massProd_list = ref([]);

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

const getMassProdData = async() => {
    const responseMassProd = await axios.get('/api/mass-production');
    massProd_list.value = responseMassProd.data;
    console.log(massProd_list.value);
}


const filteredItems = computed(() => {
  return massProd_list.value.filter((item) =>
    item.mass_prod.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage)
const endIndex = computed(() => startIndex.value + itemsPerPage)
const paginatedItems = computed(() =>
  filteredItems.value.slice(startIndex.value, endIndex.value)
)

const nextPage = () => {
  if (endIndex.value < filteredItems.value.length) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const viewControlSheet = (massprod) => {
    Inertia.visit('/control_sheet',{
        method: 'get',
        data: { massProd: massprod },
        preserveState: true,
        preserveScroll: true,
    });
}

const viewHTGraph = (massprod) => {
    Inertia.visit('/htgraph',{
        method: 'get',
        data: { massProd: massprod },
        preserveScroll: true,
        preserveState: true,
    });
}

const viewSMPData = (massprod) => {
    Inertia.visit('/smpdata',{
        method: 'get',
        data: { massProd: massprod },
        preserveState: true,
        preserveScroll: true,
    });
}

const submitForm = async () => {
    if (!massProd_name.value || !massProd_furnace.value) {
        toast.error('Please fill out all fields.');
        showModalCreate.value = false; // Keep the modal open
        massProd_name.value = '';
        massProd_furnace.value = '';
        return;
    }
    showConfirmation.value = true;
};

const saveToDatabase = async () => {
    loadingState.value = true // start loading
    try {
        const response = await axios.post('/api/mass-production', {
            mass_prod: massProd_name.value,
            furnace: massProd_furnace.value
        });

        if (response.status >= 200 && response.status < 300) {
            toast.success('Mass Production created successfully!');
            showModalCreate.value = false;
            massProd_name.value = '';
            massProd_furnace.value = '';
            getMassProdData();
        } else {
            toast.error('Failed to create Mass Production.');
        }
    } catch (error) {
        console.error('Error creating Mass Production:', error);
        toast.error('An error occurred while creating Mass Production.');
    } finally {
        loadingState.value = false // stop loading
    }
};

onMounted(async () => {
    await checkAuthentication();
    getMassProdData();
});

</script>
