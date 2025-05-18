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
                    {{ item.report[0]?.approved_by ? 'COMPLETED' : 'PENDING' }}
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
import axios from 'axios';

const tpmData = ref([]);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 20;

// Fetch data
const viewAllSerialedLayers = async () => {
  try {
    const response = await axios.get('/api/tpmdata');
    const rawData = response.data.data?.tpmData || {};
    console.log("Show respone raw data: ",response.data);
    tpmData.value = Object.values(rawData);
    console.log('[Fetched Data]:', tpmData.value);
  } catch (error) {
    console.error('[Error Fetching Data]:', error);
  }
};

onMounted(viewAllSerialedLayers);

// Search + filter
const filteredData = computed(() => {
  if (!searchQuery.value) {
    console.log('[Filtered Data]: No search query. Returning full data.');
    return tpmData.value;
  }

  const query = searchQuery.value.toLowerCase();
  const filtered = tpmData.value.filter(item => {
    const model = item.category?.[0]?.actual_model?.toLowerCase?.() || '';
    const lot = item.category?.[0]?.jhcurve_lotno?.toLowerCase?.() || '';
    return model.includes(query) || lot.includes(query);
  });

  console.log('[Filtered Data]: Query =', searchQuery.value, '| Results =', filtered.length);
  return filtered;
});

// Pagination
const totalPages = computed(() => {
  const pages = Math.ceil(filteredData.value.length / itemsPerPage);
  console.log('[Pagination]: Total Pages =', pages);
  return pages;
});

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const paginated = filteredData.value.slice(start, start + itemsPerPage);
  console.log(`[Paginated Data]: Page ${currentPage.value} | Items =`, paginated.length);
  return paginated;
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    console.log('[Pagination]: Next Page →', currentPage.value);
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    console.log('[Pagination]: Previous Page ←', currentPage.value);
  }
};

// Watchers for debugging
watch(searchQuery, (newVal) => {
  console.log('[Search Query Changed]:', newVal);
  currentPage.value = 1; // Reset to page 1 on new search
});
</script>
