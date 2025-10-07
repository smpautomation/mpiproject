<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-6 py-10 bg-gray-100">
        <!-- Top Action Bar -->
        <div class="flex items-center justify-between w-full mb-6 max-w-7xl">
            <h1 class="text-2xl font-bold text-gray-800">Mass Production Records</h1>
            <button
                @click="showModalCreate = true"
                class="px-5 py-2 font-semibold text-white transition-all bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                + Create New
            </button>
        </div>

        <!-- Card -->
        <div class="w-full p-8 space-y-6 bg-white border border-gray-200 shadow-xl max-w-7xl rounded-2xl">

            <!-- Search -->
            <div class="flex items-center justify-between">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="🔍 Search Mass Prod..."
                    class="px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm w-72 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <!-- Table -->
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Date</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Mass Prod</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Furnace</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="item in paginatedItems" :key="item.id" class="transition duration-150 hover:bg-gray-50">
                            <td class="px-6 py-4 text-center text-gray-800">{{ new Date(item.created_at).toLocaleDateString() }}</td>
                            <td class="px-6 py-4 font-semibold text-center text-gray-900">{{ item.mass_prod }}</td>
                            <td class="px-6 py-4 text-center text-gray-800">{{ item.furnace }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <button
                                        @click="viewControlSheet(item.mass_prod)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        Control Sheet
                                    </button>
                                    <button
                                        @click="viewHTGraph(item.mass_prod)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500"
                                    >
                                        HT Graph
                                    </button>
                                    <button
                                        @click="viewSMPData(item.mass_prod)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 focus:ring-2 focus:ring-purple-500"
                                    >
                                        SMP Data
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="paginatedItems.length === 0">
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">No results found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4 text-sm">
                <div class="text-gray-600">
                    Showing {{ startIndex + 1 }}–{{ Math.min(endIndex, filteredItems.length) }} of {{ filteredItems.length }}
                </div>
                <div class="flex gap-2">
                    <button
                        @click="prevPage"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <button
                        @click="nextPage"
                        :disabled="endIndex >= filteredItems.length"
                        class="px-3 py-1 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Modal :show="showModalCreate" @close="showModalCreate = false">
            <div class="relative flex flex-col w-full max-w-2xl p-8 mx-auto bg-white shadow-2xl rounded-2xl">

                <!-- Exit -->
                <button
                    @click="showModalCreate = false"
                    class="absolute p-2 text-gray-400 transition rounded-full top-4 right-4 hover:bg-gray-100 hover:text-gray-600"
                >
                    ✕
                </button>

                <!-- Header -->
                <div class="flex flex-col mb-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Create New Mass Production</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Fill out the form below to register a new mass production record.
                        Ensure accuracy, as these records will be used for tracking furnaces, control sheets, and HT graphs.
                    </p>
                </div>

                <!-- Form -->
                <div class="flex flex-col w-full gap-6 mb-8">
                    <!-- Mass Prod Name -->
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Mass Production Name</label>
                        <input
                            type="text"
                            v-model="massProd_name"
                            @input="massProd_name = massProd_name.toUpperCase()"
                            placeholder="ex. 541ST"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <p class="mt-1 text-xs text-gray-400">Unique identifier for the mass production batch.</p>
                    </div>

                    <!-- Furnace No -->
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Furnace No</label>

                        <div v-if="furnace_lists.length > 0">
                            <select
                            v-model="massProd_furnace"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <option disabled value="">Select a furnace...</option>
                            <option
                                v-for="furnace in furnace_lists"
                                :key="furnace.id ?? furnace"
                                :value="furnace.furnace_no ?? furnace"
                            >
                                {{ furnace.furnace_no ?? furnace }}
                            </option>
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Choose from available furnaces (e.g., K-40, K-42).</p>
                        </div>

                        <div v-else class="flex flex-col w-full gap-2 px-4 py-3 text-sm text-red-600 border border-red-300 rounded-lg shadow-sm bg-red-50">
                            <span class="font-medium">⚠ No furnaces available.</span>
                            <p class="text-gray-600">
                            You can add furnaces by going to
                            <span class="font-semibold text-blue-600">Options → Furnace</span> in the navigation bar.
                            </p>
                            <div class="flex gap-3 mt-2">
                            <button
                                @click="getFurnaceLists"
                                class="px-3 py-1 text-xs font-semibold text-white transition-colors bg-red-500 rounded-md hover:bg-red-600"
                            >
                                Retry
                            </button>
                            <button
                                @click="Inertia.visit('/furnace')"
                                class="px-3 py-1 text-xs font-semibold text-white transition-colors bg-blue-500 rounded-md hover:bg-blue-600"
                            >
                                Go to Furnace Page
                            </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation -->
                <div v-if="!showConfirmation" class="flex justify-end">
                <button
                    @click="submitForm"
                    :disabled="!massProd_furnace || furnace_lists.length === 0"
                    class="px-6 py-3 text-sm font-semibold text-white transition bg-blue-600 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-400 hover:bg-blue-700 disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed disabled:shadow-none"
                >
                    Submit
                </button>
                </div>


                <div v-else class="flex flex-col items-center gap-6">
                    <p class="text-base font-medium text-gray-800">Confirm your input before saving.</p>
                    <div class="flex gap-4">
                        <button
                            @click="showConfirmation = false"
                            class="px-6 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                        >
                            Cancel
                        </button>
                        <button
                            @click="saveToDatabase"
                            class="px-6 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg shadow hover:bg-green-700"
                        >
                            Confirm & Save
                        </button>
                    </div>
                </div>

                <!-- Loading Overlay -->
                <DotsLoader
                    v-if="loadingState"
                    class="absolute inset-0 z-50 flex items-center justify-center bg-white bg-opacity-70 rounded-2xl"
                />
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

const userManageLogging = async (logEvent) => {
    try{
        const responseUserLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Mass Production',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userManageLogging post request failed: ',error);
    }
}

const showModalCreate = ref(false);
const showConfirmation = ref(false);
const loadingState = ref(false);

const massProd_name = ref('');
const massProd_furnace = ref();

const massProd_list = ref([]);
const furnace_lists = ref([]);

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

const getMassProdData = async() => {
    const responseMassProd = await axios.get('/api/mass-production');
    massProd_list.value = responseMassProd.data;
    //console.log(massProd_list.value);
}

const getFurnaceLists = async() => {
    try{
        const response = await axios.get('/api/furnace-data/');
        const furnaceData = response.data;
        furnace_lists.value = furnaceData.map(item => item.furnace_name);
        console.log("Furnace Lists: ", furnace_lists.value);
    }catch(error){
        console.error('Failed to fetch furnace data lists: ',error);
        toast.error('Furnace Data List error.');
    }
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
            getMassProdData();
        } else {
            toast.error('Failed to create Mass Production.');
        }
    } catch (error) {
        console.error('Error creating Mass Production:', error);
        toast.error('An error occurred while creating Mass Production.');
    } finally {
        loadingState.value = false // stop loading
        await userManageLogging('created '+ massProd_name.value +' Mass Production | Furnace : ' + massProd_furnace.value);
        massProd_name.value = '';
        massProd_furnace.value = '';
    }
};



onMounted(async () => {
    await checkAuthentication();
    await getMassProdData();
    await getFurnaceLists();
});

</script>
