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
      <div class="flex flex-row items-center align-middle space-x-4">
        <label>Status: </label>
      <!-- Status Filter -->
        <select v-model="statusFilter" class="w-[200px] p-2 border rounded shadow-sm">
          <option value="">All</option>
          <option value="COMPLETED">Completed</option>
          <option value="PENDING">Approved by Pending</option>
          <option value="PREPARED_PENDING">Prepared by Pending</option>
          <option value="CHECKED_PENDING">Checked by Pending</option>
        </select>

      </div>
      <!-- No Data -->
      <div v-if="filteredData.length === 0" class="text-lg font-semibold text-gray-500">
            No matching data found.
      </div>

      <!-- Table -->
      <div v-else class="w-full overflow-x-auto">
        <table class="w-full border-collapse rounded-lg shadow-lg">
            <thead>
                <tr class="text-white bg-gradient-to-r from-green-400 via-black to-blue-400">
                <th class="px-2 py-2 whitespace-nowrap">Serial No</th>
                <th class="px-2 py-2 whitespace-nowrap">Model Name</th>
                <th class="px-2 py-2 whitespace-nowrap">Lot No</th>
                <th class="px-2 py-2 whitespace-nowrap">Furnace No</th>
                <th class="px-2 py-2 whitespace-nowrap">Tracer No</th>
                <th class="px-2 py-2 whitespace-nowrap">SMP Judgement</th>
                <th class="px-2 py-2 whitespace-nowrap">Status</th>
                <th v-if="state.user && state.user.access_type !== 'Basic User'" class="px-2 py-2 whitespace-nowrap">Action</th> <!-- New Action Column -->
                </tr>
            </thead>
            <tbody>
                <tr
                v-for="(item, index) in paginatedData"
                :key="index"
                class="bg-gradient-to-r from-green-400 via-black to-blue-400"
                >
                <td class="p-[1px]">
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                    {{ item.category[0].tpm_data_serial || "NO DATA" }}
                    </div>
                </td>
                <td class="p-[1px]">
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                    {{ item.category[0].actual_model || "NO DATA" }}
                    </div>
                </td>
                <td class="p-[1px]">
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                    {{ item.category[0].jhcurve_lotno || "NO DATA" }}
                    </div>
                </td>
                <td class="p-[1px]">
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                    {{ item.tpm[0].sintering_furnace_no || "NO DATA" }}
                    </div>
                </td>
                <td class="p-[1px]">
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                    {{ item.tpm[0].Tracer || "NO DATA" }}
                    </div>
                </td>
                <td class="p-[1px]">
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                    {{ item.report[0]?.smp_judgement || "NO DATA" }}
                    </div>
                </td>
                <td class="p-[1px]">
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                    {{ item.report[0]?.approved_by_firstname ? "COMPLETED" : "PENDING" }}
                    </div>
                </td>
                <td v-if="state.user && state.user.access_type !== 'Basic User'" class="p-[1px] text-center"> <!-- New Cell -->
                    <div class="px-2 py-1 text-sm text-center bg-white rounded-sm">
                        <div>
                            <template v-if="confirmDeleteFor === item.category[0].tpm_data_serial">
                              <button
                                @click="deleteRow(item.category[0].tpm_data_serial)"
                                class="bg-green-600 text-white rounded-sm w-[40px] mr-2
                                      hover:bg-green-500 active:bg-green-700
                                      transition-colors duration-200 ease-in-out
                                      focus:outline-none focus:ring-2 focus:ring-green-400"
                              >
                                Yes
                              </button>
                              <button
                                @click="confirmDeleteFor = null"
                                class="bg-gray-300 text-gray-800 rounded-sm w-[60px]
                                      hover:bg-gray-400 active:bg-gray-500
                                      transition-colors duration-200 ease-in-out
                                      focus:outline-none focus:ring-2 focus:ring-gray-400"
                              >
                                Cancel
                              </button>
                            </template>
                            <template v-else>
                              <button
                                @click="confirmDeleteFor = item.category[0].tpm_data_serial"
                                class="bg-red-700 text-white rounded-sm w-[100px]
                                      hover:bg-red-600 active:bg-red-800
                                      transition-colors duration-200 ease-in-out
                                      focus:outline-none focus:ring-2 focus:ring-red-500"
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
          class="px-3 py-1 text-white bg-green-300 rounded hover:bg-green-600 disabled:opacity-50"
        >
          Prev
        </button>
        <span class="text-gray-400">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-3 py-1 text-white bg-blue-300 rounded hover:bg-blue-600 disabled:opacity-50"
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

const tpmData = ref([]);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 20;
const statusFilter = ref('');
const totalPages = ref(0);
const maxPagesAllowed = ref(20);
const confirmDeleteFor = ref(null);


// Fetch data
const viewAllSerialedLayers = async () => {
  try {
    const response = await axios.get('/api/tpmdata');
    const rawData = response.data.data?.tpmData || {};
    //console.log("Show respone raw data: ",response.data);
    tpmData.value = Object.entries(rawData)
        .map(([serial, data]) => ({ serial: Number(serial), ...data }))
        .sort((a, b) => b.serial - a.serial); // Sort DESC by serial
    //console.log('[Fetched Data]:', tpmData.value);
    totalPages.value = Math.ceil(tpmData.value.length / itemsPerPage);
    //console.log(totalPages.value);
  } catch (error) {
    console.error('[Error Fetching Data]:', error);
  }
};

onMounted(async ()=>{
    await checkAuthentication();
    await viewAllSerialedLayers();
});

// Search + filter
const filteredData = computed(() => {
  const query = searchQuery.value.toLowerCase();
  const status = statusFilter.value;

  return tpmData.value.filter(item => {
    const model = item.category?.[0]?.actual_model?.toLowerCase?.() || '';
    const lot = item.category?.[0]?.jhcurve_lotno?.toLowerCase?.() || '';
    const matchesQuery = !query || model.includes(query) || lot.includes(query);

    const report = item.report?.[0] || {};
    //console.log('TPM',tpmData.value);
    //console.log('[REPORT]:', report);

    const isEmpty = (val) =>
      val === null ||
      val === undefined ||
      (typeof val === 'string' && (val.trim() === '' || val === 'null'));

    let matchesStatus = true;

    if (status === 'COMPLETED') {
      matchesStatus = !isEmpty(report.approved_by_firstname);
    } else if (status === 'PENDING') {
      matchesStatus = isEmpty(report.approved_by_firstname) &&
                     !isEmpty(report.prepared_by_firstname) &&
                     !isEmpty(report.checked_by_firstname);
    } else if (status === 'PREPARED_PENDING') {
        // console.log('[REPORT]:', report);
            //console.log('[prepared_by_firstname]:', report.prepared_by_firstname);
            //console.log('[isEmpty]:', isEmpty(report.prepared_by_firstname));
      matchesStatus = isEmpty(report.prepared_by_firstname) &&
                     isEmpty(report.checked_by_firstname) &&
                     isEmpty(report.approved_by_firstname);
    } else if (status === 'CHECKED_PENDING') {
      matchesStatus = isEmpty(report.checked_by_firstname) &&
                     isEmpty(report.approved_by_firstname) &&
                     !isEmpty(report.prepared_by_firstname);
    }

    return matchesQuery && matchesStatus;
  });
});

watch(statusFilter, val => console.log('[Filter Selected]:', val));

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const paginated = filteredData.value.slice(start, start + itemsPerPage);
  //console.log(`[Paginated Data]: Page ${currentPage.value} | Items =`, paginated.length);
  return paginated;
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    //console.log('[Pagination]: Next Page →', currentPage.value);
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    //console.log('[Pagination]: Previous Page ←', currentPage.value);
  }
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
    await axios.delete(`/api/tpmdata/${serial}`);
    tpmData.value = tpmData.value.filter(item => item.serial !== serial);
    confirmDeleteFor.value = null; // reset confirm after delete
    console.log(`[Row Deleted]: Serial ${serial}`);
  } catch (error) {
    console.error(`[Error Deleting Row]: Serial ${serial}`, error);
    confirmDeleteFor.value = null; // reset confirm on error too
  }
};
</script>
