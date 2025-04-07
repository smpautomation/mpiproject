<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
            <div class="flex flex-col items-center justify-center mt-12 font-bold">This is the Approval page</div>
            <div class="m-10">
                <table class="w-full overflow-hidden border-collapse rounded-lg shadow-lg table-auto">
                    <thead class="text-white bg-gray-800 ">
                        <tr>
                            <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300">Date</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300">Serial No</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300">Magnet Model</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300">Material Code</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300">Partial No</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300">Total Quantity</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center text-white bg-blue-500 border-b border-gray-300">SMP Judgement</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center text-white bg-yellow-500 border-b border-gray-300">Prepared By</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center text-white bg-yellow-500 border-b border-gray-300">Checked By</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center bg-green-600 border-b border-gray-300">Action</th>
                            <th class="px-6 py-3 text-lg font-extrabold text-center bg-red-400 border-b border-gray-300">Approval</th>
                        </tr>
                    </thead>
                    <tbody class="text-center bg-white">
                        <tr v-for="(report, index) in reportDataList" :key="report.tpm_data_serial" class="transition-colors duration-200 hover:bg-gray-100">
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.date }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.tpm_data_serial }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.model }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.material_code }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.partial_number }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.total_quantity }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.smp_judgement }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.prepared_by }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.checked_by }}</td>
                            <td class="px-6 py-3 text-sm text-center border-b border-gray-300">
                                <button @click="viewReport(report.tpm_data_serial)"
                                        class="px-4 py-2 text-blue-500 border border-blue-500 rounded-md hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                                    View Report
                                </button>
                            </td>
                            <td class="px-6 py-3 text-sm text-center border-b border-gray-300">
                                <input type="checkbox"
                                    :value="report.tpm_data_serial"
                                    v-model="selectedRows"
                                    class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded-md cursor-pointer focus:ring-blue-500 focus:ring-2">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-10">
                <button class="px-6 py-3 text-white transition duration-200 ease-in-out bg-green-500 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-50">
                    Approve Selected
                </button>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, toRaw, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const reportDataList = ref([]);
const selectedRows = ref([]); // Track selected rows by their serial numbers

// Watcher to observe changes to selectedRows and log them
watch(selectedRows, (newValue) => {
    console.log("Currently selected rows:", newValue);
}, { deep: true });

const viewReport = (serial) => {
  console.log('Navigating to report with serial:', serial);
  Inertia.visit('/reports', {
    method: 'get',   // You can keep 'get' since we are not modifying any data
    data: { serialParam: serial },   // Passing the serialParam here
    preserveState: true,
    preserveScroll: true,
  });
};

const showReportData = async () => {
    try {
        const response = await axios.get(`/api/reportdata/`);
        console.log("Getting report data API result: ", response.data);

        // Filter out rows where smp_judgement is null or an empty string
        reportDataList.value = response.data.data.filter(report => report.smp_judgement && report.smp_judgement.trim() !== '');

        console.log("Filtered report data arrays: ", reportDataList.value);
    } catch (error) {
        console.error("Error fetching report data:", error);
    }
};

onMounted(showReportData);

</script>
