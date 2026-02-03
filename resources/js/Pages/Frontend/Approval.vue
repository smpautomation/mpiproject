<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">
            <div>
                <div v-if="reportDataList == null || reportDataList.length <= 0" class="flex flex-col items-center justify-center animate-pulse">
                    <div
                        class="w-32 h-32 transition duration-300 bg-center bg-no-repeat bg-cover"
                        :style="{
                            backgroundImage: 'url(\'/photo/no_data.png\')',
                            backgroundSize: '80%'
                        }"
                    ></div>
                    <p class="text-lg font-extrabold">No data available yet for approval</p>
                </div>
                <div v-else>
                    <!-- Status Filter -->
                    <div class="flex items-center justify-end mb-4 align-middle">
                    <label for="statusFilter" class="mr-2 font-semibold">Filter by Status:</label>
                    <select id="statusFilter" v-model="statusFilter" class="p-2 w-[125px] border rounded">
                        <option value="ALL">ALL</option>
                        <option value="APPROVED">APPROVED</option>
                        <option value="PENDING">PENDING</option>
                    </select>
                    <button
                        @click="checkAllToggle"
                        :disabled="filteredReports.filter(r => r.checked === 1).length === 0"
                        :class="[
                            'px-4 py-2 ml-2 mr-4 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-opacity-50',
                            filteredReports.filter(r => r.checked === 1 && (!r.approved_by_firstname)).length === 0
                                ? 'bg-gray-400 text-white cursor-not-allowed focus:ring-gray-400'
                                : 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500'
                        ]"
                    >
                        {{
                            filteredReports.filter(r => r.checked === 1 && (!r.approved_by_firstname)).length === 0
                                ? 'No eligible data to approve'
                                : filteredReports
                                    .filter(r => r.checked === 1)
                                    .every(r => selectedRows.includes(r.tpm_data_serial))
                                    ? 'Uncheck All Eligible'
                                    : 'Check All Eligible'
                        }}
                    </button>
                    </div>
                    <div class="m-10">
                        <table class="w-full overflow-hidden text-sm text-left bg-white border-separate rounded-lg shadow-lg border-spacing-0">
                            <thead class="text-white bg-gray-800">
                                <tr>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase border-b border-gray-700">Serial No</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase border-b border-gray-700">Magnet Model</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase border-b border-gray-700">Material Code</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase border-b border-gray-700">Partial No</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase border-b border-gray-700">Total Quantity</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase bg-blue-600 border-b border-gray-700">SMP Judgement</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center text-black uppercase bg-yellow-500 border-b border-gray-700">Prepared By</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center text-black uppercase bg-yellow-500 border-b border-gray-700">Checked By</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase bg-green-600 border-b border-gray-700">Action</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase bg-green-800 border-b border-gray-700">Status</th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wide text-center uppercase bg-red-500 border-b border-gray-700">Approval</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800 bg-white">
                                <tr v-for="(report) in paginatedReports" :key="report.tpm_data_serial"
                                    class="transition-colors duration-150 border-b border-gray-200 hover:bg-gray-100">
                                <td class="px-4 py-2 text-center whitespace-nowrap">{{ report.tpm_data_serial }}</td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">{{ report.model }}</td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">{{ report.material_code }}</td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">{{ report.partial_number }}</td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">{{ report.total_quantity }}</td>
                                <td class="px-4 py-2 text-lg font-semibold text-center whitespace-nowrap"
                                    :class="{
                                        'text-red-600': report.smp_judgement === 'REJECT' || report.smp_judgement === 'HOLD',
                                        'text-green-600': report.smp_judgement === 'OK',
                                        'text-gray-600': !['REJECT', 'HOLD', 'OK'].includes(report.smp_judgement)
                                    }">
                                    {{ report.smp_judgement }}
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    {{ report.prepared_by ?? `${report.prepared_by_firstname} ${report.prepared_by_surname}` }}
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    {{ report.checked_by ?? `${report.checked_by_firstname} ${report.checked_by_surname}` }}
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    <button @click="viewReport(report.tpm_data_serial)"
                                            class="inline-block px-3 py-1 text-sm font-medium text-blue-600 transition duration-150 border border-blue-500 rounded hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    View
                                    </button>
                                </td>
                                <td class="px-4 py-2 text-sm font-bold text-center whitespace-nowrap">
                                    <span v-if="report.approved_by_firstname" class="text-green-700">APPROVED</span>
                                    <span v-else class="text-yellow-600">PENDING</span>
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    <input v-if="!report.approved_by_firstname && report.checked == 1" type="checkbox"
                                        :value="report.tpm_data_serial"
                                        v-model="selectedRows"
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded cursor-pointer focus:ring focus:ring-blue-300">
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-center mb-10 space-y-6">
                        <DotsLoader v-show="isLoadingApproval" class="z-10 mt-8"/>
                        <!-- Approve Button -->
                        <div v-if="!approveNotif && !blockedNotif">
                            <button
                                v-show="showApproveButton"
                                @click="approveSelected"
                                class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-md shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1 transition duration-150 ease-in-out">
                                Approve Selected
                            </button>
                        </div>
                        <!-- Confirmation Box -->
                        <div v-show="showApproveConfirmation" class="max-w-md p-6 mx-auto text-center bg-white border border-gray-200 rounded-lg shadow">
                            <p class="mb-4 text-lg font-semibold text-gray-800"><span class="font-extrabold text-blue-500">Warning: </span>Are you sure?</p>
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
                        <!-- Notification with close button -->
                        <div v-show="blockedNotif" class="w-[1000px] h-[180px] items-center p-4 mx-auto bg-red-200 rounded-md shadow-md text-red-800 relative">
                            <!-- Close Button -->
                            <button @click="closeNotification" class="absolute font-extrabold text-red-800 underline bg-transparent border-none cursor-pointer top-2 right-2 text-md">
                                close
                            </button>

                            <!-- Notification Content -->
                            <div>
                            <div v-for="(line, index) in notificationMessageLines" :key="index" v-html="line"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                    <button
                        class="px-4 py-2 text-gray-700 bg-gray-300 rounded disabled:opacity-50"
                        @click="prevPage"
                        :disabled="currentPage === 1"
                    >
                        Previous
                    </button>
                    <span class="text-gray-600">Page {{ currentPage }} of {{ totalPages }}</span>
                    <button
                        class="px-4 py-2 text-gray-700 bg-gray-300 rounded disabled:opacity-50"
                        @click="nextPage"
                        :disabled="currentPage === totalPages"
                    >
                        Next
                    </button>
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
import { useAuth } from '@/Composables/useAuth.js'
import DotsLoader from '@/Components/DotsLoader.vue';

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

const userApprovalLogging = async (logEvent) => {
    try{
        const responseApprovalLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Approval',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userApprovalLogging post request failed: ',error);
    }
}

const userFinalizedLogging = async (logEvent) => {
    try{
        const responseFinalizedLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Report',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userFinalizedLogging post request failed: ',error);
    }
}

// UI

const statusFilter = ref('ALL');

watch(statusFilter, () => {
  currentPage.value = 1;
});

const currentPage = ref(1);
const itemsPerPage = ref(10); // You can change to 20, 50, etc.

const showApproveButton = ref(true);
const showApproveConfirmation = ref(false);
const approveNotif = ref(false);
const blockedNotif = ref(false);
const reportNotificationMessage = ref('');
const isLoadingApproval = ref(false);

// UI end
const ipAddress = ref('');
const reportDataList = ref([]);

const filteredReports = computed(() => {
  if (statusFilter.value === 'ALL') return reportDataList.value;

  return reportDataList.value.filter(report => {
    const isApproved = report.approved_by_firstname;
    if (statusFilter.value === 'APPROVED') return isApproved;
    if (statusFilter.value === 'PENDING') return !isApproved;
  });
});

const paginatedReports = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredReports.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredReports.value.length / itemsPerPage.value);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const selectedRows = ref([]); // Track selected rows by their serial numbers

const props = defineProps({
  ipAddress: String,
  filterStatus : String,
  fromReports : Boolean
})
ipAddress.value = props.ipAddress;
if(props.fromReports){
    statusFilter.value = props.filterStatus;
}
console.log("Current IP Detected: ",ipAddress.value);

const showApprovedNotification = (message) => {
    // Show notification and set the message
    approveNotif.value = true;
    reportNotificationMessage.value = message;
    showApproveButton.value = false;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        approveNotif.value = false;
        showApproveButton.value = true;
    }, 3000);  // 3000ms = 3 seconds
}

// Notification message content as an array
const notificationMessageLines = ref([]);

// Function to show the blocked notification
const showBlockedNotification = (message) => {
  // Split the message into separate lines (use <br> for line breaks in the message)
  notificationMessageLines.value = message.split('<br><br>');

  // Show the notification and set the message
  blockedNotif.value = true;
  showApproveButton.value = false;
};

// Function to close the notification
const closeNotification = () => {
  blockedNotif.value = false;
  showApproveButton.value = true;
};

// Watcher to observe changes to selectedRows and log them
watch(selectedRows, (newValue) => {
    //console.log("Currently selected rows:", newValue);
}, { deep: true });

const viewReport = (serial) => {
    saveReportChecked(serial);
    //console.log('Navigating to report with serial:', serial);
    Inertia.visit('/reports', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { serialParam: serial, fromApproval: true, filterStatus: statusFilter.value },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};

const checkAllToggle = () => {
    const eligibleSerials = filteredReports.value
        .filter(report => report.checked === 1 && !report.approved_by_firstname)
        .map(report => report.tpm_data_serial);

    const allEligibleSelected = eligibleSerials.every(serial => selectedRows.value.includes(serial));

    if (allEligibleSelected) {
        selectedRows.value = selectedRows.value.filter(serial => !eligibleSerials.includes(serial));
    } else {
        selectedRows.value = Array.from(new Set([...selectedRows.value, ...eligibleSerials]));
    }
};

const showReportData = async () => {
    try {
        const response = await axios.get(`/api/reportdata/`);
        //console.log("Getting report data API result: ", response.data);

        // Filter out rows where smp_judgement is null or an empty string
        reportDataList.value = response.data.data
            .filter(report =>
                report.smp_judgement && report.smp_judgement.trim() !== '' &&
                (
                    (report.checked_by && report.checked_by.trim() !== '' &&
                     report.prepared_by && report.prepared_by.trim() !== '') ||
                    (report.checked_by_firstname && report.checked_by_firstname.trim() !== '' &&
                     report.prepared_by_firstname && report.prepared_by_firstname.trim() !== '')
                )
            )
            .sort((a, b) => {
                // Ensure both values are treated as numbers for natural ordering
                return Number(b.tpm_data_serial) - Number(a.tpm_data_serial);
            });

        //console.log("Filtered and sorted report data: ", reportDataList.value);
    } catch (error) {
        console.error("Error fetching report data:", error);
    }
};

const saveReportChecked = async (serial) => {
    try {
        const response = await axios.patch(`/api/reportdata/${serial}`, {
            "checked": 1
        });
        //console.log("Patched checked report data: ", response.data);
    } catch (error) {
        console.error("Patch data checking report error:", error);
    }
}

const approveSelected = async () => {
    if (selectedRows.value.length === 0) {
        //console.log("No rows selected for approval");
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

const finalizeReport = async (serial) => {
  try {
    const responseFinalize = await axios.patch(`/api/reportdata/${serial}`, {
      is_finalized: 1,
    });
    await userFinalizedLogging(`has finalized report serial: ${serial}`);
    //console.log('[Finalize Report] Response:', responseFinalize.data);
  } catch (error) {
    console.error('[Finalize Report] Error finalizing report:', error);
  }
};

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
    isLoadingApproval.value = true;
    showApproveConfirmation.value = false;

    try {
        console.log("== Starting approval process ==");
        const dateNow = datenow();

        const reportData = {
            approved_by_firstname: state.user.firstName,
            approved_by_surname: state.user.surname,
            approved_by_date: dateNow,
        };

        // 1. Stamp approval
        for (const serial of selectedRows.value) {
            await userApprovalLogging(`has successfully stamped Approved by of serial ${serial}`);
            await axios.patch(`/api/reportdata/${serial}`, reportData);
        }

        // 2. Generate + save PDFs
        for (const serial of selectedRows.value) {
            try {
                await axios.get(`/api/reports/${encodeURIComponent(serial)}/generate-and-save`);
                //console.log(`✅ PDF generated and saved for serial: ${serial}`);
            } catch (pdfErr) {
                //console.error(`❌ PDF generation failed for serial ${serial}`, pdfErr);
                await rollbackApproval(); // ⬅ safe undo
                throw pdfErr; // bubble up to outer catch
            }
        }

        // 2a. Send emails
        try {
            await axios.post('/api/notify-email', {
                serial: selectedRows.value,
                emails: 'edzel@smp.com.ph, automation3@smp.com.ph, automation5@smp.com.ph, myke@smp.com.ph',
                //emails: 'qa_trainingp8@smp.com.ph, qa-mpiengr@smp.com.ph, rizza@smp.com.ph, p7_mpi_ahi@smp.com.ph, mpi-engr.p8@smp.com.ph, edzel@smp.com.ph, automation3@smp.com.ph, automation5@smp.com.ph, myke@smp.com.ph',
            });
            //console.log("Emails sent successfully");
        } catch (emailErr) {
            //console.error("❌ Failed to send emails", emailErr);
            showBlockedNotification("Emails could not be sent. Please try again later.");
        }

        // 3. Finalize report
        for (const serial of selectedRows.value) {
            await finalizeReport(serial);
        }

        showApprovedNotification("Approved and PDF saved successfully.");

    } catch (error) {
        console.error("Error during approval:", error);
        await rollbackApproval(); // failsafe backup
        showBlockedNotification(`
            An error occurred while saving the PDF reports.<br>
            Please try again later or contact support.
        `);
    } finally {
        isLoadingApproval.value = false;
        await showReportData(); // refresh
        showApproveButton.value = true;
    }
};



onMounted( async () => {
    await checkAuthentication();
    await showReportData();
});

</script>
