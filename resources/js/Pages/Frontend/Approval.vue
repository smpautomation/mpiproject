<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">
            <div v-if="reportDataList == null || reportDataList.length <= 0" class="flex flex-col items-center justify-center animate-pulse">
                <div
                    class="w-32 h-32 transition duration-300 bg-center bg-no-repeat bg-cover"
                    :style="{
                        backgroundImage: 'url(\'/photo/no_data.png\')',
                        backgroundSize: '80%'
                    }"
                ></div>
                <p class="text-lg font-extrabold">No data available yet</p>
            </div>
            <div v-else>
                <div class="m-10">
                    <table class="w-full overflow-hidden border-collapse rounded-lg shadow-lg table-auto">
                        <thead class="text-white bg-gray-800 ">
                            <tr>
                                <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300">Date</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300 whitespace-nowrap">Serial No</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300 whitespace-nowrap">Magnet Model</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300 whitespace-nowrap">Material Code</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300 whitespace-nowrap">Partial No</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center border-b border-gray-300 whitespace-nowrap">Total Quantity</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center text-white bg-blue-500 border-b border-gray-300 whitespace-nowrap">SMP Judgement</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center text-white bg-yellow-500 border-b border-gray-300">Prepared By</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center text-white bg-yellow-500 border-b border-gray-300">Checked By</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center bg-green-600 border-b border-gray-300">Action</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center bg-green-800 border-b border-gray-300">Status</th>
                                <th class="px-6 py-3 text-lg font-extrabold text-center bg-red-400 border-b border-gray-300">Approval</th>
                            </tr>
                        </thead>
                        <tbody class="text-center bg-white">
                            <tr v-for="(report, index) in reportDataList" :key="report.tpm_data_serial" class="transition-colors duration-200 hover:bg-gray-100">
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300 whitespace-nowrap">{{ report.date }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.tpm_data_serial }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.model }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.material_code }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.partial_number }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300">{{ report.total_quantity }}</td>
                                <td class="px-6 py-3 text-xl font-extrabold border-b border-gray-300" :class="{'text-red-500': report.smp_judgement === 'REJECT' || report.smp_judgement === 'HOLD', 'text-green-500': report.smp_judgement === 'OK'}">
                                    {{ report.smp_judgement }}
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300 whitespace-nowrap">{{ report.prepared_by }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700 border-b border-gray-300 whitespace-nowrap">{{ report.checked_by }}</td>
                                <td class="px-6 py-3 text-sm text-center border-b border-gray-300">
                                    <button @click="viewReport(report.tpm_data_serial)"
                                            class="px-4 py-2 text-blue-500 border border-blue-500 rounded-md whitespace-nowrap hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                                        View Report
                                    </button>
                                </td>
                                <td class="px-6 py-3 text-lg font-extrabold text-gray-700 border-b border-gray-300">
                                    <span v-if="report.approved_by === 'ITADANI KAZUYA'" class="text-green-600">APPROVED</span>
                                    <span v-else class="text-yellow-600">PENDING</span>
                                </td>
                                <td class="px-6 py-3 text-sm text-center border-b border-gray-300">
                                    <input type="checkbox"
                                        :value="report.tpm_data_serial"
                                        v-model="selectedRows"
                                        :disabled="report.checked == 0 || report.checked == null || report.approved_by == 'ITADANI KAZUYA'"
                                        class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded-md cursor-pointer focus:ring-blue-500 focus:ring-2">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center mb-10 space-y-6">
                    <!-- Approve Button -->
                    <button v-show="showApproveButton" @click="approveSelected"
                        class="px-6 py-3 text-white transition duration-200 ease-in-out bg-green-500 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-50">
                        Approve Selected
                    </button>

                    <!-- Confirmation Box -->
                    <div v-show="showApproveConfirmation" class="max-w-md p-6 mx-auto text-center bg-white border border-gray-200 rounded-lg shadow">
                        <p class="mb-4 text-lg font-semibold text-gray-800">Are you sure?</p>
                        <div class="flex justify-center gap-4">
                            <button @click="confirmationApprove" class="px-5 py-2 text-white transition bg-green-500 rounded-md hover:bg-green-600">
                                Yes
                            </button>
                            <button @click="cancelApprove" class="px-5 py-2 text-white transition bg-red-500 rounded-md hover:bg-red-600">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Notification -->
                    <div v-show="approveNotif"
                        class="flex items-center max-w-md p-4 mx-auto text-green-800 bg-green-100 rounded-md shadow-md">
                        <p class="text-sm font-medium">{{ reportNotificationMessage }}</p>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, toRaw, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

// UI

const showApproveButton = ref(true);
const showApproveConfirmation = ref(false);
const approveNotif = ref(false);
const reportNotificationMessage = ref('');

// UI end

const reportDataList = ref([]);
const selectedRows = ref([]); // Track selected rows by their serial numbers

const showApprovedNotification = (message) => {
    // Show notification and set the message
    approveNotif.value = true;
    reportNotificationMessage.value = message;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        approveNotif.value = false;
    }, 3000);  // 3000ms = 3 seconds
}

// Watcher to observe changes to selectedRows and log them
watch(selectedRows, (newValue) => {
    console.log("Currently selected rows:", newValue);
}, { deep: true });

const viewReport = (serial) => {
    saveReportChecked(serial);
    console.log('Navigating to report with serial:', serial);
    Inertia.visit('/reports', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { serialParam: serial, fromApproval: true },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};

const showReportData = async () => {
    try {
        const response = await axios.get(`/api/reportdata/`);
        console.log("Getting report data API result: ", response.data);

        // Filter out rows where smp_judgement is null or an empty string
        reportDataList.value = response.data.data.filter(report =>
            report.smp_judgement && report.smp_judgement.trim() !== '' &&
            report.checked_by && report.checked_by.trim() !== '' &&
            report.prepared_by && report.prepared_by.trim() !== ''
        );

        console.log("Filtered report data arrays: ", reportDataList.value);
    } catch (error) {
        console.error("Error fetching report data:", error);
    }
};

const saveReportChecked = async (serial) => {
    const reportData = {
        "checked": 1
    }

    try {
        const response = await axios.patch(`/api/reportdata/${serial}`, reportData);
        console.log("Patched checked report data: ", response.data);
    } catch (error) {
        console.error("Patch report data Error:", error);
    }
}

const approveSelected = async () => {
    if (selectedRows.value.length === 0) {
        console.log("No rows selected for approval");
        return;
    }else{
        showApproveButton.value = false;
        showApproveConfirmation.value = true;
    }
};

const cancelApprove = () => {
    showApproveButton.value = true;
    showApproveConfirmation.value = false;
}

const datenow = () => {
    const now = new Date();
    const pad = (n) => n.toString().padStart(2, '0');

    const year = now.getFullYear();
    const month = pad(now.getMonth() + 1); // Months are zero-based
    const day = pad(now.getDate());

    const hours = pad(now.getHours());
    const minutes = pad(now.getMinutes());
    const seconds = pad(now.getSeconds());

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const confirmationApprove = async () => {

    try {
        // Loop through each selected serial number
        for (let serial of selectedRows.value) {
            const dateNow = datenow();
            const reportData = {
                approved_by: "ITADANI KAZUYA", // Set the approved_by field to "ITADANI KAZUYA"
                approved_by_date: dateNow
            };

            // Send a PATCH request to update the 'approved_by' field
            const response = await axios.patch(`/api/reportdata/${serial}`, reportData);
            console.log(`Successfully approved report with serial ${serial}:`, response.data);
            showApprovedNotification("Approved Successfully");
            showReportData();
            showApproveButton.value = true;
            showApproveConfirmation.value = false;
        }

        //await showReportData();

    } catch (error) {
        console.error("Error approving selected reports:", error);
    }
}

onMounted(showReportData);

</script>
