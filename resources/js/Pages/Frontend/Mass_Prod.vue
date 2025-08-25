<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
            <button @click="showModalCreate = true" class="px-4 py-2 mb-5 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                Create New
            </button>

            <div class="p-16 mx-auto space-y-6 bg-white border border-gray-200 shadow-xl max-w-7xl rounded-2xl md:p-14">
                <!-- Search Bar -->
                <div class="flex items-center justify-between mb-4">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search Mass Prod..."
                        class="w-64 px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <div class="overflow-x-auto bg-white border border-gray-200 shadow-lg">
                    <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wide text-center text-gray-700 uppercase">Date</th>
                            <th class="px-6 py-4 font-bold tracking-wide text-center text-gray-700 uppercase">Mass Prod</th>
                            <th class="px-6 py-4 font-bold tracking-wide text-center text-gray-700 uppercase">Furnace</th>
                            <th class="px-6 py-4 font-bold tracking-wide text-center text-gray-700 uppercase">Action (View)</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="item in paginatedItems"
                            :key="item.id"
                            class="text-center transition duration-150 hover:bg-gray-50"
                        >
                        <td class="px-6 py-4 font-medium text-gray-900">{{ new Date(item.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ item.mass_prod }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ item.furnace }}</td>
                            <td class="flex flex-wrap items-center justify-center gap-3 px-6 py-4">
                            <button
                                @click="viewControlSheet(item.mass_prod)"
                                class="px-4 py-2 text-xs font-semibold text-white transition bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Control Sheet
                            </button>
                            <button
                                @click="viewHTGraph(item.mass_prod)"
                                class="px-4 py-2 text-xs font-semibold text-white transition bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                                Heat Treatment Graph
                            </button>
                            <button
                                @click="viewSMPData(item.mass_prod)"
                                class="px-4 py-2 text-xs font-semibold text-white transition bg-purple-600 rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500"
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
                <div class="flex items-center justify-between mt-4 text-sm">
                    <div class="text-gray-600">
                        Showing {{ startIndex + 1 }}–{{ Math.min(endIndex, filteredItems.length) }} of {{ filteredItems.length }}
                    </div>
                    <div class="space-x-2">
                        <button
                        @click="prevPage"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 text-gray-800 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                        >
                        Previous
                        </button>
                        <button
                        @click="nextPage"
                        :disabled="endIndex >= filteredItems.length"
                        class="px-3 py-1 text-gray-800 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                        >
                        Next
                        </button>
                    </div>
                </div>
            </div>


            <Modal :show="showModalCreate" @close="showModalCreate = false">
                <div class="relative flex flex-col items-center justify-center w-full max-w-3xl p-6 mx-auto bg-white shadow-2xl rounded-xl">

                    <!-- Exit Button -->
                    <button
                    @click="showModalCreate = false"
                    class="absolute text-gray-400 transition duration-150 top-4 right-4 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <p class="mb-6 text-lg font-bold text-gray-800">Please fill out all the fields</p>

                    <div class="flex flex-col w-full gap-6 mb-8 md:flex-row">
                    <div class="flex flex-col w-full">
                        <label class="mb-1 text-sm font-semibold text-gray-700">Mass Production Name</label>
                        <input
                        type="text"
                        v-model="massProd_name"
                        @input="massProd_name = massProd_name.toUpperCase()"
                        placeholder="ex. 541ST"
                        class="w-full px-4 py-2 text-sm font-medium text-gray-800 placeholder-gray-400 uppercase transition border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 text-sm font-semibold text-gray-700">Furnace No</label>
                        <input
                        type="text"
                        v-model="massProd_furnace"
                        @input="massProd_furnace = massProd_furnace.toUpperCase()"
                        placeholder="ex. K-40"
                        class="w-full px-4 py-2 text-sm font-medium text-gray-800 placeholder-gray-400 uppercase transition border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    </div>
                    <div v-if="!showConfirmation">
                        <button
                        @click="submitForm"
                        class="px-6 py-2 text-sm font-semibold text-white transition duration-200 bg-blue-600 rounded-md shadow-sm hover:bg-blue-700"
                        >
                            Submit
                        </button>
                    </div>
                    <div v-else class="flex flex-col items-center justify-center gap-4">
                        <p class="font-semibold">Are you <span class="text-red-600">sure</span> that input is correct?</p>
                        <div class="flex gap-4">
                            <button
                                @click="showConfirmation = false"
                                class="px-6 py-2 text-sm font-semibold text-white transition duration-200 bg-gray-600 rounded-md shadow-sm hover:bg-gray-700"
                            >
                                No
                            </button>
                            <button
                                @click="saveToDatabase"
                                class="px-6 py-2 text-sm font-semibold text-white transition duration-200 bg-green-600 rounded-md shadow-sm hover:bg-green-700"
                            >
                                Yes
                            </button>
                        </div>
                    </div>
                    <DotsLoader v-if="loadingState" class="absolute inset-0 z-50 flex items-center justify-center bg-white bg-opacity-75" />
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
import Modal from '@/Components/Modal.vue'; // adjust path if needed
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
