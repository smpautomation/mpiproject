<template>
  <Frontend>
    <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
        <!-- Search Box -->
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Search model name or lot no..."
            class="w-full max-w-md p-2 border rounded shadow-sm"
        />
        <div class="flex flex-row items-center space-x-4 align-middle">

            <label>Furnace: </label>
            <!-- Status Filter -->
            <select v-model="selectedFurnace" class="w-[200px] p-2 border rounded shadow-sm">
                <!--option value="COATING_PENDING">Coating Pending</!--option>
                <option-- value="HEAT_TREATMENT_PENDING">Heat Treatment Pending</option-->
                <option value="">All</option> <!-- new line -->
                <option v-for="item in furnace_lists" :key="item" :value="item">
                    {{ item }}
                </option>
            </select>

            <label>Mass Prod: </label>
            <!-- Status Filter -->
            <select v-model="selectedMassProd" class="w-[200px] p-2 border rounded shadow-sm">
                <!--option value="COATING_PENDING">Coating Pending</!--option>
                <option-- value="HEAT_TREATMENT_PENDING">Heat Treatment Pending</option-->
                <option value="">All</option> <!-- new line -->
                <option v-for="item in massProd_names" :key="item" :value="item">
                    {{ item }}
                </option>
            </select>

            <label>Status: </label>
            <!-- Status Filter -->
            <select v-model="statusFilter" class="w-[200px] p-2 border rounded shadow-sm">
                <option value="">All</option>
                <option value="COMPLETED">Completed</option>
                <option value="PENDING">Approved by Pending</option>
                <option value="PREPARED_PENDING">Prepared by Pending</option>
                <option value="CHECKED_PENDING">Checked by Pending</option>
                <option value="FINALIZED_PENDING">Finalize Pending</option>
                <!--option value="COATING_PENDING">Coating Pending</!--option>
                <option-- value="HEAT_TREATMENT_PENDING">Heat Treatment Pending</option-->
            </select>

            <label>Date From:</label>
            <input
                type="date"
                v-model="vl_dateFrom"
                :max="vl_dateTo"
                class="w-[180px] p-2 border rounded shadow-sm"
            />

            <label>Date To:</label>
            <input
                type="date"
                v-model="vl_dateTo"
                :min="vl_dateFrom"
                class="w-[180px] p-2 border rounded shadow-sm"
            />

        </div>

    <!-- No Data -->
    <div v-if="filteredData.length === 0" class="text-lg font-semibold text-gray-500">
            No matching data found.
    </div>

      <!-- Table -->
      <div v-else class="w-full overflow-x-auto">
        <table class="w-full border-collapse rounded-lg shadow-lg">
            <thead>
                <tr class="text-white bg-gradient-to-r from-cyan-600 via-cyan-900 to-teal-600">
                    <th class="px-2 py-2 whitespace-nowrap">Date</th>
                    <th class="px-2 py-2 whitespace-nowrap">Mass Production</th>
                    <th class="px-2 py-2 whitespace-nowrap">Layer</th>
                    <th class="px-2 py-2 whitespace-nowrap">Serial No</th>
                    <th class="px-2 py-2 whitespace-nowrap">Model Name</th>
                    <th class="px-2 py-2 whitespace-nowrap">Lot No</th>
                    <th class="px-2 py-2 whitespace-nowrap">Furnace No</th>
                    <th class="px-2 py-2 whitespace-nowrap">Tracer No</th>
                    <th class="px-2 py-2 whitespace-nowrap">SMP Judgement</th>
                    <th class="px-2 py-2 whitespace-nowrap">Status</th>
                    <th class="px-2 py-2 whitespace-nowrap">Email</th>
                    <th v-if="state.user && state.user.access_type !== 'Basic User'" class="px-2 py-2 whitespace-nowrap">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in paginatedData" :key="index" class="bg-gradient-to-r from-cyan-600 via-cyan-900 to-teal-600">
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.created_at || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.mass_prod || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.layer_no || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.serial_no || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.model || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.lot_no || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.sintering_furnace_no || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.tracer || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.smp_judgement || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                            {{ item.status || "NO DATA" }}
                        </div>
                    </td>
                    <td class="p-[1px]">
                        <div
                            class="px-2 py-1 text-sm font-medium text-center rounded-sm"
                            :class="{
                                'bg-green-100 text-green-800': item.email === 'EMAIL SENT',
                                'bg-blue-100 text-blue-800': item.email === 'READY FOR EMAIL',
                                'bg-yellow-100 text-yellow-800': item.email === 'PENDING'
                            }"
                        >
                            {{ item.email || "NO DATA" }}
                        </div>
                    </td>
                    <td v-if="state.user && state.user.access_type !== 'Basic User'" class="p-[1px] text-center">
                        <div class="flex flex-row justify-center px-0 py-1 text-sm text-center bg-white rounded-sm space-x-7 whitespace-nowrap">
                            <div>
                                <button
                                    @click="viewReport(item.serial_no)"
                                    class="w-[110px] bg-cyan-600 text-white rounded-md font-medium text-sm shadow-sm hover:bg-cyan-500 active:bg-cyan-700 active:shadow-none transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                                >
                                    View Report
                                </button>
                            </div>
                            <div>
                                <template v-if="confirmDeleteFor === item.serial_no">
                                    <button
                                        @click="deleteRow(item.serial_no)"
                                        class="bg-gradient-to-b from-green-600 to-green-500 text-white rounded-sm w-[40px] mr-2 shadow-md hover:from-green-500 hover:to-green-400 active:shadow-inner active:from-green-700 active:to-green-600 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
                                    >
                                        Yes
                                    </button>
                                    <button
                                        @click="confirmDeleteFor = null"
                                        class="bg-gradient-to-b from-gray-300 to-gray-200 text-gray-800 rounded-sm w-[60px] shadow-md hover:from-gray-400 hover:to-gray-300 active:shadow-inner active:from-gray-500 active:to-gray-400 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1"
                                    >
                                        Cancel
                                    </button>
                                </template>
                                <template v-else>
                                    <button
                                        @click="confirmDeleteFor = item.serial_no"
                                        class="w-[110px] bg-red-700 text-white rounded-md font-medium text-sm shadow-sm hover:bg-red-600 active:bg-red-800 active:shadow-none transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    >
                                        Delete
                                    </button>
                                </template>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center mt-4 space-x-2">
        <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-3 py-1 text-white rounded bg-cyan-400 hover:bg-cyan-600 disabled:opacity-50"
        >
            Prev
        </button>
        <span class="text-gray-400">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-3 py-1 text-white rounded bg-cyan-400 hover:bg-cyan-600 disabled:opacity-50"
        >
            Next
        </button>
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

function useSessionStorage(key, state) {
    // Load existing session value
    const saved = sessionStorage.getItem(key)
    if (saved !== null) {
        try {
            const parsed = JSON.parse(saved)
            if (typeof state === 'object' && 'value' in state) {
                state.value = parsed
            } else {
                Object.assign(state, parsed)
            }
        } catch {
        /* ignore parse errors */
        }
    }

    // Watch and persist changes
    watch(
        state,
        (val) => {
        sessionStorage.setItem(key, JSON.stringify(val))
        },
        { deep: true }
    )
}

const formatDate = (isoString) => {
    if (!isoString) return null;
    const date = new Date(isoString);
    return date.toLocaleString(undefined, {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false, // or true if you want AM/PM
    });
};

const userSerialDeleteLogging = async (logEvent) => {
    try{
        const responseSerialDeleteLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'View List',
        });

        //console.log('responseSerialDeleteLogging-data: ',responseSerialDeleteLogging.data);
    }catch(error){
        console.error('userSerialDeleteLogging post request failed: ',error);
    }
}

const viewReport = (serial) => {
    Inertia.visit('/reports', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { serialParam: serial, fromViewList: true },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};

const today = new Date();

// Helper to format date as YYYY-MM-DD without converting to UTC
const formatDateForInput = (date) => {
  const yyyy = date.getFullYear();
  const mm = String(date.getMonth() + 1).padStart(2, '0'); // months 0-11
  const dd = String(date.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
};

// First day of last month
const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);

// Last day of current month
const lastDayCurrentMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

// Reactive refs for your date inputs
const vl_dateFrom = ref(formatDateForInput(firstDayLastMonth));
const vl_dateTo = ref(formatDateForInput(lastDayCurrentMonth));

const tpmData = ref([]);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 20;
const statusFilter = ref('');
const totalPages = ref(0);
const maxPagesAllowed = ref(20);
const confirmDeleteFor = ref(null);
const massProd_names = ref([]);
const furnace_lists = ref([]);
const selectedMassProd = ref('');
const selectedFurnace = ref('');

// Fetch data
const viewAllSerialedLayers = async () => {
    try {
        const response = await axios.post('/api/viewlist-remastered');
        const rawData = response.data.data || [];

        // No mapping needed, backend is already flat and structured
        tpmData.value = [...rawData].sort((a, b) => b.created_at.localeCompare(a.created_at)); // DESC by created_at

        console.log('TPM DATA: ', tpmData.value);

        totalPages.value = Math.ceil(tpmData.value.length / itemsPerPage);
    } catch (error) {
        console.error('[Error Fetching Data]:', error);
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
    }
}

const getMassProdLists = async () => {
    try{
        const response = await axios.get('/api/mass-production/');
        const massProdList = response.data;
        massProd_names.value = massProdList.map(item => item.mass_prod);
        //console.log("List of mass prods: ",massProd_names.value);
    }catch(error){
        console.error('Error fetching mass prod lists',error);
        toast.error('Failed to get the mass prod lists api error');
    }
}

useSessionStorage("selectedMassProd", selectedMassProd);
useSessionStorage("selectedFurnace", selectedFurnace);
useSessionStorage("statusFilter", statusFilter);
useSessionStorage("vl_dateFrom", vl_dateFrom);
useSessionStorage("vl_dateTo", vl_dateTo);

onMounted(async ()=>{
    await checkAuthentication();
    await viewAllSerialedLayers();
    await getMassProdLists();
    await getFurnaceLists();
});

// Search + filter
const filteredData = computed(() => {
    const query = searchQuery.value.toLowerCase();
    const status = statusFilter.value;
    const massProd = selectedMassProd.value;
    const furnace = selectedFurnace.value;
    const from = vl_dateFrom.value;
    const to = vl_dateTo.value;

    return tpmData.value.filter(item => {
        // Search query
        const model = item.model?.toLowerCase() || '';
        const lot = item.lot_no?.toLowerCase() || '';
        const matchesQuery = !query || model.includes(query) || lot.includes(query);

        // Status filter (already precomputed)
        let matchesStatus = true;
        if (status === 'COMPLETED') matchesStatus = item.status === 'COMPLETED';
        else if (status === 'PENDING') matchesStatus = item.status === 'PENDING';

        // Mass production filter
        const matchesMassProd = !massProd || item.mass_prod === massProd;

        // Furnace filter with flexible matching (e.g., K40-0581 vs K40)
        const matchesFurnace = !furnace || item.sintering_furnace_no.replace('-', '').startsWith(furnace.replace('-', ''));

        // Date range filter
        let matchesDate = true;
        if (from || to) {
            const recordDate = item.created_at?.split(' ')[0]; // Date only
            if (from && recordDate < from) matchesDate = false;
            if (to && recordDate > to) matchesDate = false;
        }

        return matchesQuery && matchesStatus && matchesMassProd && matchesFurnace && matchesDate;
    });
});

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return filteredData.value.slice(start, start + itemsPerPage);
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};

// Watchers for debugging
watch(searchQuery, (newVal) => {
  //console.log('[Search Query Changed]:', newVal);
  currentPage.value = 1; // Reset to page 1 on new search
});

watch(statusFilter, () => {
  currentPage.value = 1;
});

const deleteRow = async (serial) => {
    try {
        // Tell backend to delete the chart image
        await axios.delete(`/api/tpmdata/${serial}/delete-chart`);

        // Delete the database row
        await axios.delete(`/api/tpmdata/${serial}`);

        // Update UI
        tpmData.value = tpmData.value.filter(item => item.serial !== serial);
        confirmDeleteFor.value = null;

        console.log(`[Row + Chart Deleted]: Serial ${serial}`);
        userSerialDeleteLogging(`deleted Serial ${serial} and its chart image`);
        await viewAllSerialedLayers();
        await getMassProdLists();
        await getFurnaceLists();
    } catch (error) {
        console.error(`[Error Deleting Serial ${serial}]`, error);
        confirmDeleteFor.value = null;
    }
};

</script>
