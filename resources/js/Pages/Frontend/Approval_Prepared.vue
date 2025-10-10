<template>
    <Frontend>
        <div class="p-6">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6 pb-5 border-b-2 border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            Approval Page
                        </h1>
                        <p class="text-sm font-medium text-teal-600 mt-0.5">(Preparation Approver)</p>
                    </div>
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
                <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
                    <thead class="bg-gradient-to-r from-teal-500 to-cyan-500">
                        <tr>
                            <th class="border border-teal-400 p-3 w-12 text-center">
                                <input type="checkbox" v-model="checkAll" @change="onCheckAllChange"
                                    class="w-4 h-4 rounded border-white/30 bg-white/20 text-cyan-600 focus:ring-2 focus:ring-white/50" />
                            </th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Mass Production</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Layer</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Furnace No</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Serial No</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Model Name</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Lot No</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Tracer No</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">SMP Judgement</th>
                            <th class="border border-teal-400 p-3 text-left text-white font-semibold text-sm">Status</th>
                            <th class="border border-teal-400 p-3 text-center text-white font-semibold text-sm w-24">Action</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        <tr v-if="paginatedReports.length === 0">
                            <td colspan="12" class="p-6 text-center text-gray-400 bg-gray-50">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="font-medium">No reports found.</p>
                            </td>
                        </tr>

                        <tr
                            v-for="report in paginatedReports"
                            :key="report.Serial_No"
                            class="border-b border-gray-200 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 transition-colors duration-150"
                        >
                            <td class="border-x border-gray-200 p-3 text-center">
                                <input
                                    type="checkbox"
                                    :value="String(report.Serial_No)"
                                    v-model="selectedRows"
                                    :disabled="!!report.Status"
                                    class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-2 focus:ring-cyan-400 disabled:opacity-30 disabled:cursor-not-allowed"
                                />
                            </td>
                            <td class="border-x border-gray-200 p-3 text-gray-700 text-sm">{{ report.Mass_Production }}</td>
                            <td class="border-x border-gray-200 p-3 text-gray-700 text-sm">{{ report.Layer }}</td>
                            <td class="border-x border-gray-200 p-3 text-gray-700 text-sm">{{ report.Furnace_No }}</td>
                            <td class="border-x border-gray-200 p-3 font-semibold text-gray-800 text-sm">{{ report.Serial_No }}</td>
                            <td class="border-x border-gray-200 p-3 text-gray-700 text-sm">{{ report.Model_Name }}</td>
                            <td class="border-x border-gray-200 p-3 text-gray-700 text-sm">{{ report.Lot_No }}</td>
                            <td class="border-x border-gray-200 p-3 text-gray-700 text-sm">{{ report.Tracer_No }}</td>
                            <td
                                class="border-x border-gray-200 p-3 font-bold text-sm"
                                :class="{
                                    'text-green-600': report.SMP_Judgement === 'PASSED',
                                    'text-red-600': report.SMP_Judgement === 'REJECT'
                                }"
                            >
                                {{ report.SMP_Judgement }}
                            </td>
                            <td class="border-x border-gray-200 p-3 text-center"
                                :class="{
                                    'text-green-600': report.Status === true,
                                    'text-red-600': report.Status === false
                                }"
                            >
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                    :class="{
                                        'bg-green-100': report.Status === true,
                                        'bg-red-100': report.Status === false
                                    }">
                                    {{ report.Status ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td class="border-x border-gray-200 p-3 text-center">
                                <button
                                    @click="viewReport(report.Serial_No)"
                                    class="bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-xs font-semibold px-4 py-1.5 rounded-lg hover:from-teal-600 hover:to-cyan-600 hover:shadow-md transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex justify-between items-center mt-6 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-teal-50 hover:text-teal-600 hover:border-teal-300 transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-700 disabled:hover:border-gray-300"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Previous
                </button>

                <span class="text-sm font-semibold text-gray-700">
                    Page <span class="text-teal-600">{{ currentPage }}</span> of <span class="text-gray-500">{{ totalPages }}</span>
                </span>

                <button
                    @click="nextPage"
                    :disabled="currentPage >= totalPages"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-teal-50 hover:text-teal-600 hover:border-teal-300 transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-700 disabled:hover:border-gray-300"
                >
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- APPROVAL BUTTONS -->
            <div class="mt-6 text-center">
                <transition name="fade">
                    <button
                        v-if="showApproveButton"
                        @click="approveSelected"
                        class="bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold px-8 py-3 rounded-lg hover:from-teal-600 hover:to-cyan-600 hover:shadow-lg transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none disabled:active:scale-100"
                        :disabled="selectedRows.length === 0"
                    >
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Approve Selected ({{ selectedRows.length }})
                        </span>
                    </button>
                </transition>

                <transition name="fade">
                    <div v-if="showApproveConfirmation" class="mt-6 p-6 bg-white rounded-xl border border-cyan-200 shadow-lg">
                        <div class="mb-5">
                            <svg class="w-12 h-12 mx-auto mb-3 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                            </svg>
                            <p class="text-lg font-semibold text-gray-800">Confirm Approval</p>
                            <p class="text-sm text-gray-600 mt-1">You are about to approve <span class="font-bold text-teal-600">{{ selectedRows.length }}</span> report(s)</p>
                        </div>
                        <div class="flex justify-center gap-3">
                            <button
                                @click="confirmationApprove"
                                class="bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold px-6 py-2.5 rounded-lg hover:from-teal-600 hover:to-cyan-600 hover:shadow-md transition-all active:scale-95"
                            >
                                Confirm Approval
                            </button>
                            <button
                                @click="cancelApprove"
                                class="bg-gray-100 text-gray-700 font-medium px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all active:scale-95 border border-gray-300"
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
const checkAll = ref(false);

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

const eligibleSerialsForPage = computed(() =>
  paginatedReports.value
    .filter(report => !report.Status) // only unapproved rows
    .map(report => String(report.Serial_No))
);


/* ---------------- CHECK ALL ---------------- */
// called when header checkbox is toggled
const onCheckAllChange = () => {
  if (checkAll.value) {
    // add eligible serials from current page
    selectedRows.value = Array.from(new Set([...selectedRows.value, ...eligibleSerialsForPage.value]));
  } else {
    // remove eligible serials of current page
    selectedRows.value = selectedRows.value.filter(serial => !eligibleSerialsForPage.value.includes(serial));
  }
};

// keep header checkbox state in sync when user toggles individual rows or when page changes
watch([selectedRows, paginatedReports], () => {
  const eligible = eligibleSerialsForPage.value;
  checkAll.value = eligible.length > 0 && eligible.every(s => selectedRows.value.includes(s));
});

/* ---------------- FETCH FLATTENED DATA ---------------- */
const showReportData = async () => {
    try {
        const response = await axios.get(`/api/approve-list-prepared`);
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
        data: { serialParam: serial, fromApproval_prepared: true },
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
            prepared_by_firstname: state.user.firstName,
            prepared_by_surname: state.user.surname,
            prepared_by_date: dateNow,
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
