<template>
    <Frontend>
        <div class="p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Approval Page</h1>

                <div class="flex gap-3 items-center">
                    <label class="font-medium">Status:</label>
                    <select v-model="statusFilter" class="border rounded px-3 py-1">
                        <option value="ALL">All</option>
                        <option value="APPROVED">Approved</option>
                        <option value="PENDING">Pending</option>
                    </select>
                </div>
            </div>

            <!-- NOTIFICATIONS -->
            <transition name="fade">
                <div v-if="approveNotif" class="bg-green-500 text-white p-3 rounded mb-4 text-center">
                    {{ reportNotificationMessage }}
                </div>
            </transition>

            <transition name="fade">
                <div v-if="blockedNotif" class="bg-red-500 text-white p-4 rounded mb-4 text-center">
                    <div v-for="(line, index) in notificationMessageLines" :key="index" v-html="line"></div>
                    <button class="mt-3 bg-white text-red-600 px-3 py-1 rounded" @click="closeNotification">
                        Close
                    </button>
                </div>
            </transition>

            <!-- LOADER -->
            <div v-if="isLoadingApproval" class="flex justify-center items-center py-10">
                <DotsLoader />
            </div>

            <!-- REPORT TABLE -->
            <div v-else class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2 w-12 text-center">
                                <input type="checkbox" @change="checkAllToggle" />
                            </th>
                            <th class="border p-2 text-left">Mass Production</th>
                            <th class="border p-2 text-left">Layer</th>
                            <th class="border p-2 text-left">Furnace No</th>
                            <th class="border p-2 text-left">Serial No</th>
                            <th class="border p-2 text-left">Model Name</th>
                            <th class="border p-2 text-left">Lot No</th>
                            <th class="border p-2 text-left">Tracer No</th>
                            <th class="border p-2 text-left">Prepared By</th>
                            <th class="border p-2 text-left">SMP Judgement</th>
                            <th class="border p-2 text-left">Status</th>
                            <th class="border p-2 text-center w-24">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="paginatedReports.length === 0">
                            <td colspan="12" class="p-4 text-center text-gray-500">
                                No reports found.
                            </td>
                        </tr>

                        <tr
                            v-for="report in paginatedReports"
                            :key="report.Serial_No"
                            class="hover:bg-gray-50"
                        >
                            <td class="border p-2 text-center">
                                <input
                                    type="checkbox"
                                    :value="report.Serial_No"
                                    v-model="selectedRows"
                                    :disabled="!!report.prepared_by_firstname"
                                />
                            </td>
                            <td class="border p-2">{{ report.Mass_Production }}</td>
                            <td class="border p-2">{{ report.Layer }}</td>
                            <td class="border p-2">{{ report.Furnace_No }}</td>
                            <td class="border p-2 font-medium">{{ report.Serial_No }}</td>
                            <td class="border p-2">{{ report.Model_Name }}</td>
                            <td class="border p-2">{{ report.Lot_No }}</td>
                            <td class="border p-2">{{ report.Tracer_No }}</td>
                            <td class="border p-2">{{ report.Prepared_By }}</td>
                            <td
                                class="border p-2 font-semibold"
                                :class="{
                                    'text-green-600': report.SMP_Judgement === 'PASSED',
                                    'text-red-600': report.SMP_Judgement === 'FAILED'
                                }"
                            >
                                {{ report.SMP_Judgement }}
                            </td>
                            <td class="border p-2 font-semibold text-center"
                                :class="{
                                    'text-green-600': report.Status === true,
                                    'text-red-600': report.Status === false
                                }"
                            >
                                {{ report.Status ? 'Approved' : 'Pending' }}
                            </td>
                            <td class="border p-2 text-center">
                                <button
                                    @click="viewReport(report.Serial_No)"
                                    class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex justify-between items-center mt-4">
                <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                >
                    Previous
                </button>

                <span>Page {{ currentPage }} of {{ totalPages }}</span>

                <button
                    @click="nextPage"
                    :disabled="currentPage >= totalPages"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                >
                    Next
                </button>
            </div>

            <!-- APPROVAL BUTTONS -->
            <div class="mt-6 text-center">
                <transition name="fade">
                    <button
                        v-if="showApproveButton"
                        @click="approveSelected"
                        class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700"
                        :disabled="selectedRows.length === 0"
                    >
                        Approve Selected ({{ selectedRows.length }})
                    </button>
                </transition>

                <transition name="fade">
                    <div v-if="showApproveConfirmation" class="mt-6">
                        <p class="mb-4 font-medium">Confirm approval for {{ selectedRows.length }} reports?</p>
                        <div class="flex justify-center gap-3">
                            <button
                                @click="confirmationApprove"
                                class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700"
                            >
                                Confirm
                            </button>
                            <button
                                @click="cancelApprove"
                                class="bg-gray-300 text-gray-700 px-4 py-1 rounded hover:bg-gray-400"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </Frontend>
</template>


<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useAuth } from '@/Composables/useAuth.js';
import DotsLoader from '@/Components/DotsLoader.vue';

const { state } = useAuth();

/* ---------------- AUTH CHECK ---------------- */
const checkAuthentication = async () => {
    try {
        const start = Date.now();
        const timeout = 5000;
        while (!state.user) {
            if (Date.now() - start > timeout) {
                console.error('Auth timeout: user data failed to load.');
                Inertia.visit('/');
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50));
        }

        if (!state.isAuthenticated) {
            Inertia.visit('/');
            return false;
        }

        console.warn("USER AUTHENTICATED!");
        console.warn("Name:", state.user.firstName + " " + state.user.surname);
        console.warn("Access:", state.user.access_type);
        return true;
    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/');
        return false;
    }
};

/* ---------------- LOGGING ---------------- */
const userApprovalLogging = async (logEvent) => {
    try {
        await axios.post('/api/userlogs', {
            user: `${state.user.firstName} ${state.user.surname}`,
            event: logEvent,
            section: 'Approval',
        });
    } catch (error) {
        console.error('userApprovalLogging failed:', error);
    }
};

/* ---------------- UI STATES ---------------- */
const statusFilter = ref('ALL');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const showApproveButton = ref(true);
const showApproveConfirmation = ref(false);
const approveNotif = ref(false);
const blockedNotif = ref(false);
const reportNotificationMessage = ref('');
const isLoadingApproval = ref(false);
const notificationMessageLines = ref([]);
const selectedRows = ref([]);
const ipAddress = ref('');
const reportDataList = ref([]);

const props = defineProps({
    ipAddress: String,
    filterStatus_checked: String,
    fromReports: Boolean
});
ipAddress.value = props.ipAddress;
if (props.fromReports) {
    statusFilter.value = props.filterStatus_checked;
}

/* ---------------- FILTERING ---------------- */
watch(statusFilter, () => {
    currentPage.value = 1;
});

const filteredReports = computed(() => {
    if (statusFilter.value === 'ALL') return reportDataList.value;
    if (statusFilter.value === 'APPROVED')
        return reportDataList.value.filter(r => r.Status === true);
    if (statusFilter.value === 'PENDING')
        return reportDataList.value.filter(r => r.Status === false);
    return reportDataList.value;
});

const paginatedReports = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredReports.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredReports.value.length / itemsPerPage.value));

const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };
const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };

/* ---------------- NOTIFICATIONS ---------------- */
const showApprovedNotification = (message) => {
    approveNotif.value = true;
    reportNotificationMessage.value = message;
    showApproveButton.value = false;
    setTimeout(() => {
        approveNotif.value = false;
        showApproveButton.value = true;
    }, 3000);
};

const showBlockedNotification = (message) => {
    notificationMessageLines.value = message.split('<br><br>');
    blockedNotif.value = true;
    showApproveButton.value = false;
};

const closeNotification = () => {
    blockedNotif.value = false;
    showApproveButton.value = true;
};

/* ---------------- CHECK ALL ---------------- */
const checkAllToggle = () => {
    const eligibleSerials = filteredReports.value
        .filter(report => !report.prepared_by_firstname)
        .map(report => report.serial_no);

    const allEligibleSelected = eligibleSerials.every(serial =>
        selectedRows.value.includes(serial)
    );

    if (allEligibleSelected) {
        selectedRows.value = selectedRows.value.filter(
            serial => !eligibleSerials.includes(serial)
        );
    } else {
        selectedRows.value = Array.from(
            new Set([...selectedRows.value, ...eligibleSerials])
        );
    }
};

/* ---------------- FETCH FLATTENED DATA ---------------- */
const showReportData = async () => {
    try {
        const response = await axios.get(`/api/approve-list-checked`);
        console.log("Raw approval response:", response.data);

        const data = Array.isArray(response.data)
            ? response.data
            : response.data?.data || [];

        console.log("Normalized approval data:", data.length, data);

        reportDataList.value = data
            .filter(item => item.SMP_Judgement && item.SMP_Judgement.trim() !== '')
            .sort((a, b) => Number(b.Serial_No) - Number(a.Serial_No));

        console.log('Approval View List fetched:', reportDataList.value.length);
    } catch (error) {
        console.error("Error fetching flattened approval data:", error);
    }
};

/* ---------------- APPROVAL ACTIONS ---------------- */
const viewReport = (serial) => {
    Inertia.visit('/reports', {
        method: 'get',
        data: { serialParam: serial, fromApproval_checked: true, filterStatus_checked: statusFilter.value },
        preserveState: true,
        preserveScroll: true,
    });
};

const approveSelected = async () => {
    if (selectedRows.value.length === 0) return;
    showApproveButton.value = false;
    showApproveConfirmation.value = true;
};

const cancelApprove = () => {
    showApproveButton.value = true;
    showApproveConfirmation.value = false;
};

const datenow = () => {
    const now = new Date();
    const pad = n => n.toString().padStart(2, '0');
    return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
};

const confirmationApprove = async () => {
    isLoadingApproval.value = true;
    showApproveConfirmation.value = false;

    try {
        const dateNow = datenow();
        const reportData = {
            checked_by_firstname: state.user.firstName,
            checked_by_surname: state.user.surname,
            checked_by_date: dateNow,
        };

        for (const serial of selectedRows.value) {
            await userApprovalLogging(`has successfully stamped Checked by of serial ${serial}`);
            await axios.patch(`/api/reportdata/${serial}`, reportData);
        }

        showApprovedNotification("Approved successfully.");
    } catch (error) {
        console.error("Error during approval:", error);
        showBlockedNotification(`
            An error occurred while saving the PDF reports.<br>
            Please try again later or contact support.
        `);
    } finally {
        // reset checkboxes
        selectedRows.value = [];
        checkAll.value = false; // only if you have a "select all" checkbox

        await showReportData(); // refresh list after reset
        showApproveButton.value = true;
        isLoadingApproval.value = false;
    }
};

/* ---------------- INIT ---------------- */
onMounted(async () => {
    await checkAuthentication();
    await showReportData();
});
</script>
