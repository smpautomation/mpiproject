<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
            <div class="flex flex-col items-center justify-center mt-12 font-bold">This is the Approval page</div>
            <div>
                <table class="border border-collapse border-black table-auto">
                    <thead class="text-center bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Date</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Serial No</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Magnet Model</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Material Code</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Partial No</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Total Quantity</th>
                            <th class="px-4 py-2 text-sm font-semibold text-white bg-yellow-600 border border-gray-300">SMP Judgement</th>
                            <th class="px-4 py-2 text-sm font-semibold text-white bg-yellow-600 border border-gray-300">Prepared By</th>
                            <th class="px-4 py-2 text-sm font-semibold text-white bg-yellow-600 border border-gray-300">Checked By</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 bg-green-200 border border-gray-300">Action</th>
                            <th class="px-4 py-2 text-sm font-semibold text-gray-700 bg-yellow-200 border border-gray-300">Approval</th>
                        </tr>
                    </thead>
                    <tbody class="text-center bg-white">
                        <tr v-for="(report, index) in reportDataList" :key="report.tpm_data_serial">
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.date }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.tpm_data_serial }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.model }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.material_code }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.partial_number }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.total_quantity }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.smp_judgement }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.prepared_by }}</td>
                            <td class="px-4 py-2 text-sm border border-gray-300">{{ report.checked_by }}</td>
                            <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                <!-- View Report Button, passing the serial number -->
                                <button @click="viewReport(report.tpm_data_serial)"
                                        class="px-4 py-2 text-blue-500 transition duration-200 border border-blue-500 rounded-md cursor-pointer hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                                    View Report
                                </button>
                            </td>
                            <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                <!-- Use v-model to bind checkbox state to each report's unique serial -->
                                <input type="checkbox"
                                    :value="report.tpm_data_serial"
                                    v-model="selectedRows"
                                    class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded-md cursor-pointer focus:ring-blue-500 focus:ring-2" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, toRaw, watch } from 'vue';

const reportDataList = ref([]);
const selectedRows = ref([]); // Track selected rows by their serial numbers

// Watcher to observe changes to selectedRows and log them
watch(selectedRows, (newValue) => {
    console.log("Currently selected rows:", newValue);
}, { deep: true });

// Function to handle "View Report" button click
const viewReport = (serial) => {
  console.log('Viewing report for serial:', serial);
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
