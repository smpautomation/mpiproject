<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-500">
            <!-- User Access Control Panel -->
            <div class="w-full max-w-5xl p-6 bg-gray-700 shadow-lg rounded-2xl">
                <div class="flex flex-row items-center self-start justify-start mb-4 space-x-1">
                    <span
                        class="w-12 h-12 transition duration-300 bg-center bg-no-repeat bg-cover"
                        :style="{
                        backgroundImage: 'url(\'/photo/cogwheel.png\')',
                        backgroundSize: '80%'
                        }"
                    ></span>
                    <h2 class="text-xl font-semibold text-gray-200">User Access Control</h2>
                </div>
                <div class="mt-2 space-y-2">
                <!-- Manage Roles Section -->
                <div class="bg-gray-600 rounded-lg shadow-sm">
                    <div class="flex flex-row items-center self-start justify-start pt-3 pl-4 space-x-1">
                        <span
                            class="w-8 h-8 transition duration-300 bg-center bg-no-repeat bg-cover"
                            :style="{
                            backgroundImage: 'url(\'/photo/user-gear.png\')',
                            backgroundSize: '80%'
                            }"
                        ></span>
                        <h3 class="text-lg font-medium text-gray-300">Manage Roles</h3>
                        <div>
                            <input
                                v-model="users_searchQuery"
                                type="text"
                                placeholder="Search name or employee ID..."
                                class="w-[14rem] px-3 py-1 text-sm text-white bg-gray-800 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                            />
                        </div>
                    </div>
                    <div class="p-4 space-y-2">
                   <div class="overflow-auto border border-gray-700 rounded-lg max-h-64">
                        <table class="w-full text-sm text-left text-gray-300 border-collapse">
                            <thead class="sticky top-0 z-10 text-center text-gray-400 uppercase bg-gray-700">
                                <tr class="text-xs">
                                    <th class="px-1 py-3 whitespace-nowrap">First&nbsp;Name</th>
                                    <th class="px-1 py-3 whitespace-nowrap">Last&nbsp;Name</th>
                                    <th class="px-1 py-3 whitespace-nowrap">Username</th>
                                    <th class="px-1 py-3 whitespace-nowrap">Employee&nbsp;ID</th>
                                    <th class="px-1 py-3 whitespace-nowrap">Plant</th>
                                    <th class="px-1 py-3 whitespace-nowrap">Access&nbsp;Type</th>
                                    <th class="px-1 py-3 whitespace-nowrap">Role</th>
                                    <th v-if="state.user.access_type && state.user.access_type === 'Automation'" class="px-1 py-3 text-center whitespace-nowrap">
                                        Change&nbsp;Judgement
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-700">
                                <tr
                                    v-for="user in filteredUsers"
                                    :key="user.employee_id"
                                    class="text-xs text-center transition-colors duration-150 hover:bg-gray-700/40"
                                >
                                    <td class="px-1 py-2 whitespace-nowrap">{{ user.firstName }}</td>
                                    <td class="px-1 py-2 whitespace-nowrap">{{ user.surname }}</td>
                                    <td class="px-1 py-2 whitespace-nowrap">{{ user.username }}</td>
                                    <td class="px-1 py-2 whitespace-nowrap">{{ user.employee_id }}</td>
                                    <td class="px-1 py-2 whitespace-nowrap">{{ user.plant }}</td>

                                    <td class="px-1 py-2 whitespace-nowrap">
                                        <span v-if="editingUser !== user.employee_id">
                                            {{ user.access_type }}
                                        </span>

                                        <select
                                            v-else
                                            v-model="tempRole"
                                            class="w-full px-3 py-1 text-sm text-gray-800 bg-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="Basic User">Basic User</option>
                                            <option value="Checking Approver">Checking Approver</option>
                                            <option value="Preparation Approver">Preparation Approver</option>
                                            <option value="Proxy Approver">Proxy Approver</option>
                                            <option value="Coating">Coating</option>
                                            <option value="Heat Treatment">Heat Treatment</option>
                                        </select>
                                    </td>

                                    <td class="px-1 py-2 whitespace-nowrap">
                                        <button
                                            v-if="editingUser !== user.employee_id"
                                            @click="startEditing(user)"
                                            :class="[
                                                'px-3 py-1 text-xs font-medium rounded-md transition',
                                                (user.access_type === 'Automation' || user.access_type === 'Final Approver')
                                                    ? 'bg-yellow-500 text-white opacity-60 cursor-not-allowed'
                                                    : 'bg-blue-500 text-white hover:bg-blue-600'
                                            ]"
                                            :disabled="user.access_type === 'Automation' || user.access_type === 'Final Approver'"
                                        >
                                            {{ (user.access_type === 'Automation' || user.access_type === 'Final Approver')
                                                ? 'Restricted'
                                                : 'Change Role'
                                            }}
                                        </button>

                                        <div v-else class="flex gap-2">
                                            <button
                                                @click="saveRole(user)"
                                                class="px-3 py-1 text-sm font-medium text-white transition bg-green-600 rounded-md hover:bg-green-700"
                                            >
                                                Save
                                            </button>
                                            <button
                                                @click="cancelEditing"
                                                class="px-3 py-1 text-sm font-medium text-white transition bg-red-600 rounded-md hover:bg-red-700"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </td>

                                    <td v-if="state.user && state.user.access_type === 'Automation'" class="flex justify-center gap-1 px-1 py-2 text-center whitespace-nowrap">
                                        <!-- Allow button -->
                                        <button
                                            @click="allowChangeJudgement(user)"
                                            :class="[
                                                'px-3 py-1 text-xs font-semibold rounded-md transition focus:outline-none focus:ring-2',
                                                user.change_judgement_access === 'yes'
                                                    ? 'bg-gray-500 text-white cursor-not-allowed focus:ring-gray-400'
                                                    : 'bg-purple-600 text-white hover:bg-purple-700 focus:ring-purple-400'
                                            ]"
                                            :disabled="user.change_judgement_access === 'yes'"
                                        >
                                            {{ user.change_judgement_access === 'yes' ? 'Access Granted' : 'Allow' }}
                                        </button>

                                        <!-- Revoke button -->
                                        <button
                                            v-if="user.change_judgement_access === 'yes'"
                                            @click="revokeChangeJudgement(user)"
                                            class="px-3 py-1 text-xs font-semibold text-white transition bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                                            :disabled="user.change_judgement_access !== 'yes'"
                                        >
                                            Revoke
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Role Instructions Panel -->
                    <div class="p-4 mt-4 overflow-y-auto text-xs text-gray-300 bg-gray-700 border border-gray-600 rounded-lg max-h-40">
                    <strong class="block mb-2 text-sm text-yellow-300">Role Instructions (Navbar Access)</strong>
                    <ul class="space-y-1 leading-tight list-disc list-inside text-[10px]">
                        <li><strong>Basic User (default)</strong> → Home (Cannot stamp)</li>
                        <li><strong>Heat Treatment</strong> → Home, Heat Treatment, Coating, Film Pasting, MPI Reports, 1st & 2nd GBDP, Furnace, HT Graph Patterns (Cannot stamp)</li>
                        <li><strong>Coating</strong> → Home, Heat Treatment, Coating, Film Pasting, MPI Reports, 1st & 2nd GBDP, Furnace, HT Graph Patterns (Cannot stamp)</li>
                        <li><strong>Preparation Approver</strong> → Home, Mass Production JH Curve, MPI Reports, View List, Inspection, Sppecial Instructions, Send Email, Mias Factor Emp (Prepared By Stamping only)</li>
                        <li><strong>Checking Approver</strong> → Home, Mass Production JH Curve, MPI Reports, View List, Inspection, Sppecial Instructions, Send Email, Mias Factor Emp (Checked By Stamping only)</li>
                        <li><strong>Proxy Approver</strong> → Home, Mass Production, JH Curve, MPI Reports, View List, Inspection, Approval, Sppecial Instructions, Send Email, Mias Factor Emp, Admin (Approved By Stamp only)</li>
                        <li><strong>Final Approver</strong> → Home, Mass Production, MPI Reports, Approval, View List, Admin (Approved By Stamp only)</li>
                        <!--
                        <li><strong>Hybrid Approver</strong> → All except Approval/Admin (Prepared By & Checked By Stamping)</li>
                        <li><strong>Bypass Approver</strong> → All except Admin (All stamps: Prepared, Checked, Approved)</li>
                        -->

                    </ul>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Logs Panel -->
            <div class="w-full max-w-5xl p-6 bg-gray-700 shadow-lg rounded-2xl">
                <div>
                    <!-- Filters Section -->
                    <div class="flex flex-wrap items-end gap-4">

                        <!-- Search -->
                        <div class="flex flex-col flex-grow gap-1">
                            <span class="text-xs text-gray-400">Search</span>
                            <input
                                type="text"
                                placeholder="Search logs..."
                                v-model="searchQuery"
                                class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <!-- User -->
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-gray-400">User</span>
                            <select
                                v-model="selectedUser"
                                class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">All Users</option>
                                <option v-for="user in uniqueUsers" :key="user" :value="user">{{ user }}</option>
                            </select>
                        </div>

                        <!-- Section -->
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-gray-400">Section</span>
                            <select
                                v-model="selectedSection"
                                class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">All Section</option>
                                <option v-for="section in uniqueSections" :key="section" :value="section">{{ section }}</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-gray-400">Sort</span>
                            <select
                                v-model="sortOrder"
                                class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">None</option>
                                <option value="newest">Newest</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-gray-400">From</span>
                            <input
                                type="date"
                                v-model="startDate"
                                :max="startMax"
                                class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <!-- End Date -->
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-gray-400">To</span>
                            <input
                                type="date"
                                v-model="endDate"
                                :min="endMin"
                                class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                    </div>


                    <!-- Logs Section -->
                    <div class="mt-4 space-y-2 overflow-auto max-h-64">
                    <div
                        v-for="log in filteredLogs"
                        :key="log.id"
                        class="p-3 bg-gray-600 rounded-lg shadow-sm"
                    >
                        <span class="text-xs">
                        <span class="mr-2 text-orange-600">[{{ log.section }}]</span>
                        <span class="mr-2 text-gray-200">[{{ formatDate(log.created_at) }}]</span>
                        <span class="mr-2 text-green-400">{{ log.user }}</span>
                        <span class="mr-2 text-blue-400">{{ log.event }}</span>
                        <span class="mr-2 text-gray-400">at {{ formatTime(log.created_at) }}</span>
                        </span>
                    </div>
                    </div>
                </div>
            </div>
            <!-- End of Logs Panel
             <button v-if="state.user && state.user.access_type == 'Automation'" @click="fixStampIssue" class="p-2 font-extrabold text-red-900 transition-transform duration-75 bg-yellow-500 rounded-lg active:scale-95 hover:bg-yellow-400 active:shadow-xl active:brightness-100">Fix Stamp Issue</button> -->
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { onMounted, ref, computed } from "vue";
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js'

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {
        const start = Date.now();
        const maxWait = 5000; // 5 seconds

        while (!state.user) {
            if (Date.now() - start > maxWait) {
                console.error('User data failed to load in time. Redirecting...');
                Inertia.visit('/'); // Redirect if user never loads
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50));
        }

        if (!state.isAuthenticated) {
            console.warn('User is not authenticated. Redirecting...');
            Inertia.visit('/');
            return false;
        }

        console.log("USER AUTHENTICATED!", `${state.user.firstName} ${state.user.surname}`);
        return true;

    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/');
        return false;
    }
};

const userAdminLogging = async (logEvent) => {
    try{
        const responseAdminLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Admin',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('responseAdminLogging post request failed: ',error);
    }
}

const getToday = () => {
    const now = new Date()
    const year = now.getFullYear()
    const month = String(now.getMonth() + 1).padStart(2, '0')
    const day = String(now.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
};

const users = ref([]);
const users_searchQuery = ref('');
const editingUser = ref(null);
const tempRole = ref("");
const allUserLogs = ref([]);
const searchQuery = ref('');
const selectedUser = ref('');
const selectedSection = ref('');
const sortOrder = ref('');
const uniqueUsers = ref([]);
const uniqueSections = ref([]);
const startDate = ref(null);
const endDate = ref(null);

// Set default: yesterday → tomorrow
const today = new Date();

const yesterday = new Date(today);
yesterday.setDate(today.getDate() - 1);

const tomorrow = new Date(today);
tomorrow.setDate(today.getDate() + 1);

const toISODate = (d) => d.toISOString().slice(0, 10);

startDate.value = toISODate(yesterday);
endDate.value = toISODate(tomorrow);

const shiftDate = (dateStr, days) => {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    d.setDate(d.getDate() + days);
    return d.toISOString().slice(0, 10);
};

const startMax = computed(() => {
    return endDate.value ? shiftDate(endDate.value, -1) : "";
});

const endMin = computed(() => {
    return startDate.value ? shiftDate(startDate.value, 1) : "";
});

//just for fixing stamp
const reportData = ref([]);

const startEditing = (user) => {
    editingUser.value = user.employee_id; // Updated to match API response
    tempRole.value = user.access_type;   // Updated to match API response
};

const cancelEditing = () => {
    editingUser.value = null;
    tempRole.value = "";
};

const formatDate = (isoString) => {
  if (!isoString) return '';
  const date = new Date(isoString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',  // Jun, Jul etc.
    day: 'numeric',
    timeZone: 'Asia/Manila'  // Set your timezone here
  });
};


const saveRole = async (user) => {
    try {
        const response = await axios.put(`/api/users/${user.id}`, {
            access_type: tempRole.value
        });

        //console.log(`Response from server:`, response.data);

        // Update the user's role in the local state if the update succeeds
        user.access_type = tempRole.value;
        editingUser.value = null;
        tempRole.value = "";

        //console.log(`Role for ${user.username} updated to: ${user.access_type}`);
        await userAdminLogging(`Updated role for ${user.firstName} ${user.surname} to: ${user.access_type}`);
    } catch (error) {
        console.error(`Error updating role for ${user.username}:`, error);
        // Optional: Add error handling logic, e.g., show a toast notification
    }
};

const allowChangeJudgement = async (user) => {
    try {
        const response = await axios.put(`/api/users/${user.id}`, {
            change_judgement_access: 'yes'
        });

        // Update local state immediately
        user.change_judgement_access = 'yes';

        await userAdminLogging(
            `Granted change judgement access to ${user.firstName} ${user.surname}`
        );
    } catch (error) {
        console.error(
            `Error granting change judgement access for ${user.username}:`,
            error
        );
    }
};

const revokeChangeJudgement = async (user) => {
    try {
        await axios.put(`/api/users/${user.id}`, {
            change_judgement_access: 'no'
        });

        // Update local state immediately
        user.change_judgement_access = 'no';

        await userAdminLogging(
            `Revoked change judgement access for ${user.firstName} ${user.surname}`
        );
    } catch (error) {
        console.error(
            `Error revoking change judgement access for ${user.username}:`,
            error
        );
    }
};

const formatTime = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

const fetchAllUsers = async () => {
  try {
    const response = await axios.get('/api/users');
    //console.log('Fetched users:', response.data);
    users.value = response.data.data; // Assign the array to users
    //console.log('Fetched users in my users array:', users.value);
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

const fetchAllLogs = async () => {
    try {
        const response = await axios.get('/api/userlogs');
        allUserLogs.value = response.data;
        //console.log('allUserLogs-array: ',allUserLogs.value);
        // Extract unique users dynamically
        const usersSet = new Set(allUserLogs.value.map(log => log.user).filter(Boolean));
        uniqueUsers.value = Array.from(usersSet).sort();
        const sectionsSet = new Set(allUserLogs.value.map(log => log.section).filter(Boolean));
        uniqueSections.value = Array.from(sectionsSet).sort();
        //console.log('uniqueUsers array: ',uniqueUsers.value);
        //console.log('uniqueSections array: ',uniqueSections.value);
    } catch (error) {
        console.error('Failed to fetch logs:', error);
    }
}

const filteredUsers = computed(() => {
    if (!users_searchQuery.value) return users.value;

    const q = users_searchQuery.value.toLowerCase();

    return users.value.filter(u => {
        return (
            (u.firstName ?? '').toLowerCase().includes(q) ||
            (u.surname ?? '').toLowerCase().includes(q) ||
            (u.employee_id ?? '').toLowerCase().includes(q)
        );
    });
});


// Computed: filtered and sorted logs
const filteredLogs = computed(() => {
    let logs = [...allUserLogs.value]

    // Filter by search query (event or user)
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase()
        logs = logs.filter(log =>
            (log.event?.toLowerCase().includes(query)) ||
            (log.user?.toLowerCase().includes(query))
        );
    }

    // Filter by selected user
    if (selectedUser.value) {
        logs = logs.filter(log => log.user === selectedUser.value);
    }

    // Filter by selected section
    if (selectedSection.value) {
        logs = logs.filter(log => log.section === selectedSection.value);
    }

    // Filter by date range
    if (startDate.value || endDate.value) {
        logs = logs.filter(log => {
            if (!log.created_at) return false;

            const logDate = new Date(log.created_at.replace(' ', 'T'));

            const start = startDate.value ? new Date(startDate.value) : null;
            const end = endDate.value ? new Date(endDate.value) : null;

            if (start && end) {
                return logDate >= start && logDate <= end;
            } else if (start) {
                return logDate >= start;
            } else if (end) {
                return logDate <= end;
            }
            return true;
        });
    }

    // Sort by date
    if (sortOrder.value === 'newest') {
        logs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    } else if (sortOrder.value === 'oldest') {
        logs.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    }

    return logs;
});

const fixStampIssue = async() => {
    const responseReport = await axios.get(`/api/reportdata`);
    //console.log('responseReport-data get request: ',responseReport.data);
    const rows = responseReport.data.data;
    reportData.value = rows;
    //console.log('reportData array: ',reportData.value);
    //console.log(Array.isArray(rows));
    for (const row of rows){
        const payload = {};

        if(row.prepared_by){
            const parts = row.prepared_by.trim().split(/\s+/); // chop into pieces for each phrase separated by space
            const surname = parts.pop(); //pop removes the last part
            const firstname = parts.join(' ');
            payload.prepared_by_firstname = firstname;
            payload.prepared_by_surname = surname;
        }

        if(row.checked_by){
            const parts = row.checked_by.trim().split(/\s+/);
            const surname = parts.pop();
            const firstname = parts.join(' ');
            payload.checked_by_firstname = firstname;
            payload.checked_by_surname = surname;
        }

        if(row.approved_by){
            const parts = row.checked_by.trim().split(/\s+/);
            const surname = parts.pop();
            const firstname = parts.join(' ');
            payload.approved_by_firstname = firstname;
            payload.approved_by_surname = surname;
        }

        if(Object.keys(payload).length > 0 && row.tpm_data_serial){
            try{
                let numberOfFix = Object.keys(payload).length/2;
                const resultPatchFix = await axios.patch(`/api/reportdata/${row.tpm_data_serial}`, payload);
                console.log(`Detected ${numberOfFix} rows of data affected`);
                console.log('Stamp issue FIXED successfully no errors',resultPatchFix);
            }catch(error){
                console.error('Fix Patch failed: ',error);
            }
        }
    }
}

onMounted(async() => {
    await checkAuthentication();
    await fetchAllUsers();
    await fetchAllLogs();
});

</script>
