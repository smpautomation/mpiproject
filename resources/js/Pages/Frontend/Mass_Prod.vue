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

            <!-- Filters -->
            <div class="flex flex-wrap items-end gap-6">

                <!-- Cycle No Search -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">
                        Cycle No Filter
                    </label>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search cycle no"
                        class="w-72 px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>

                <!-- Estimated Completion Date Range -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">
                        Estimated Completion Date Range
                    </label>

                    <div class="flex items-center gap-2">
                        <input
                            v-model="dateFrom"
                            type="date"
                            class="px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <span class="text-sm text-gray-500">to</span>
                        <input
                            v-model="dateTo"
                            type="date"
                            class="px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                </div>

            </div>


            <!-- Table -->
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Est. Date Time Finish</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Mass Prod</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Cycle No</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="item in paginatedItems" :key="item.id" class="transition duration-150 hover:bg-gray-50">
                            <td class="px-6 py-4 text-center text-gray-800">{{ item.estimated_completion }}</td>
                            <td class="px-6 py-4 font-semibold text-center text-gray-900">{{ item.mass_prod }}</td>
                            <td class="px-6 py-4 text-center text-gray-800">{{ item.cycle_no }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <button
                                        @click="viewControlSheet(item.mass_prod, item.furnace)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        Control Sheet
                                    </button>
                                    <button
                                        @click="viewHTGraph(item.mass_prod, item.furnace)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500"
                                    >
                                        HT Graph
                                    </button>
                                    <button
                                        @click="viewSMPData(item.mass_prod, item.furnace)"
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

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mt-10">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Monthly Data Summary</h3>
                </div>

                <button
                    @click="downloadCsvMonthlySummary"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Excel
                </button>
            </div>

            <!-- Selection Section -->
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Month
                    </label>
                    <select
                        v-model="selectedMonth"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer hover:border-gray-400">
                        <option v-for="(month, index) in months" :key="index" :value="index + 1">
                            {{ month }}
                        </option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Year
                    </label>
                    <select
                        v-model="selectedYear"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer hover:border-gray-400">
                        <option v-for="year in years" :key="year" :value="year">
                            {{ year }}
                        </option>
                    </select>
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
import * as XLSX from 'xlsx';
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

const userErrorLogging = async (details, triggerFunction, title) => {
    try{
        const response = await axios.post('/api/error-logs', {
            user: state.user.firstName + " " + state.user.surname,
            title: title,
            details: details,
            trigger_function: triggerFunction,
            section: 'Mass Production',
        });

        //console.log('userErrorLogging-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userErrorLogging post request failed: ',error);
    }
}

const showModalCreate = ref(false);
const showConfirmation = ref(false);
const loadingState = ref(false);

const massProd_name = ref('');
const massProd_furnace = ref();

const massProd_list = ref([]);
const furnace_lists = ref([]);

const selectedMonth = ref(new Date().getMonth() + 1); // default current month
const selectedYear = ref(new Date().getFullYear());   // default current year

const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

const years = ref([]);

const dateFrom = ref('');
const dateTo = ref('');

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

const getMassProdData = async () => {
    try {
        const responseMassProd = await axios.get('/api/mass-production/all-duplicates');
        massProd_list.value = responseMassProd.data;
        console.log("Mass Prod data list:  ",massProd_list.value);
    } catch (error) {
        console.error('Failed to fetch all mass production data:', error);
        toast.error('Failed to fetch mass production data.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getMassProdData",
            "Failed to fetch mass production data."
        );
    }
};

const getFurnaceLists = async() => {
    try{
        const response = await axios.get('/api/furnace-data');
        const furnaceData = response.data;
        furnace_lists.value = furnaceData.map(item => item.furnace_name);
        console.log("Furnace Lists: ", furnace_lists.value);
    }catch(error){
        console.error('Failed to fetch furnace data lists: ',error);
        toast.error('Furnace Data List error.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getFurnaceLists",
            "Furnace Data List error."
        );
    }
}

const filteredItems = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    const from = dateFrom.value;
    const to = dateTo.value;

    return massProd_list.value.filter(item => {

        /* ---------------- Cycle No filter ---------------- */
        let cycleMatch = true;
        if (q) {
            cycleMatch = item.cycle_no
                ? item.cycle_no.toLowerCase().includes(q)
                : false;
        }

        /* -------- Estimated Completion date filter -------- */
        let dateMatch = true;

        if (from || to) {
            // date filter is active → item MUST have a date
            if (!item.estimated_completion) return false;

            const itemDate = new Date(item.estimated_completion)
                .toISOString()
                .split('T')[0];

            if (from && itemDate < from) return false;
            if (to && itemDate > to) return false;
        }

        return cycleMatch && dateMatch;
    });
});


const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage)
const endIndex = computed(() => startIndex.value + itemsPerPage)
const paginatedItems = computed(() =>
    filteredItems.value.slice(startIndex.value, endIndex.value)
);

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

const viewControlSheet = (massprod, furnace) => {
    Inertia.visit('/control_sheet',{
        method: 'get',
        data: { massProd: massprod, furnace: furnace },
        preserveState: true,
        preserveScroll: true,
    });
}

const viewHTGraph = (massprod, furnace) => {
    Inertia.visit('/htgraph',{
        method: 'get',
        data: { massProd: massprod, furnace: furnace },
        preserveScroll: true,
        preserveState: true,
    });
}

const viewSMPData = (massprod, furnace) => {
    Inertia.visit('/smpdata',{
        method: 'get',
        data: { massProd: massprod, furnace: furnace },
        preserveState: true,
        preserveScroll: true,
    });
}

const isValidMassProdName = (value) => {
    if (!value) return false;

    const match = value.match(/^([0-9]+)(ST|ND|RD|TH)$/);
    if (!match) return false;

    const num = parseInt(match[1], 10);
    const suffix = match[2];

    const lastTwo = num % 100;
    const lastOne = num % 10;

    let correctSuffix;

    if (lastTwo >= 11 && lastTwo <= 13) {
        correctSuffix = 'TH';
    } else {
        if (lastOne === 1) correctSuffix = 'ST';
        else if (lastOne === 2) correctSuffix = 'ND';
        else if (lastOne === 3) correctSuffix = 'RD';
        else correctSuffix = 'TH';
    }

    return suffix === correctSuffix;
};

const submitForm = async () => {
    const name = massProd_name.value?.trim();

    if (!name || !massProd_furnace.value || !isValidMassProdName(name)) {
        toast.error('Invalid format. Example: 221ST, 222ND, 223RD, 111TH');
        showModalCreate.value = false;

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
        await userErrorLogging(
            {
                message: error.message,
                endpoint: error.config?.url,
                status: error.response?.status ?? null,
                payload: error.response?.data ?? null,
            },
            "saveToDatabase",
            "An error occurred while creating Mass Production."
        );
    } finally {
        loadingState.value = false // stop loading
        await userManageLogging('created '+ massProd_name.value +' Mass Production | Furnace : ' + massProd_furnace.value);
        massProd_name.value = '';
        massProd_furnace.value = '';
        showConfirmation.value = false;
    }
};

const downloadCsvMonthlySummary = async () => {
    try {
        const month = selectedMonth.value;
        const year  = selectedYear.value;

        // basic guard
        if (!month || !year) return;

        const { data } = await axios.get('/api/monthly-summary', {
        params: { month, year }
        });

        if (!Array.isArray(data) || data.length === 0) {
            const monthName = months[month - 1];
            toast.warning(`No data available for the month of ${monthName}`);
            return;
        }

        // build excel
        const worksheet = XLSX.utils.json_to_sheet(data);
        const workbook  = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Monthly Summary');

        const filename = `GBDP MPI SYSTEM ${months[month - 1]} HT SUMMARY MONTHLY ${year}.xlsx`;
        XLSX.writeFile(workbook, filename);

    } catch (error) {
        console.error('Failed to generate Excel:', error);
        toast.error('Failed to generate monthly summary');
    }
};



onMounted(async () => {
    await checkAuthentication();
    await getMassProdData();
    await getFurnaceLists();
    const currentYear = new Date().getFullYear();
    for (let y = currentYear; y >= 2020; y--) {
        years.value.push(y);
    }
});

</script>
