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
                    </div>
                    <div class="p-4 space-y-2">
                    <div class="overflow-auto max-h-64">
                        <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-gray-400 uppercase bg-gray-700">
                            <tr>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">First&nbsp;Name</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Last&nbsp;Name</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Username</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Employee&nbsp;ID</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Plant</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Current&nbsp;Role</th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                            <tbody>
                            <tr
                                v-for="user in users"
                                :key="user.employee_id"
                                class="border-b border-gray-600"
                            >
                                <td class="px-4 py-2">{{ user.firstName }}</td> <!-- Correct -->
                                <td class="px-4 py-2">{{ user.surname }}</td> <!-- Corrected -->
                                <td class="px-4 py-2">{{ user.username }}</td>
                                <td class="px-4 py-2">{{ user.employee_id }}</td>
                                <td class="px-4 py-2">{{ user.plant }}</td>

                                <td class="px-4 py-2">
                                <span v-if="editingUser !== user.employee_id">
                                    {{ user.access_type }}
                                </span>
                                <select
                                    v-else
                                    v-model="tempRole"
                                    class="px-3 py-1 text-sm text-gray-800 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="Basic User">Basic User</option>
                                    <option value="Checking Approver">Checking Approver</option>
                                    <option value="Preparation Approver">Preparation Approver</option>
                                    <option value="Proxy Approver">Proxy Approver</option>
                                    <option value="Coating">Coating</option>
                                    <option value="Heat Treatment">Heat Treatment</option>
                                    <!-- hidden for now

                                    <option value="Hybrid Approver">Hybrid Approver</option>
                                    <option value="Bypass Approver">Bypass Approver</option>

                                    -->
                                </select>
                                </td>

                                <td class="px-4 py-2">
                                <button
                                    v-if="editingUser !== user.employee_id"
                                    @click="startEditing(user)"
                                    :class="[
                                        'px-3 py-1 text-sm font-medium rounded-lg',
                                        (user.access_type === 'Automation' || user.access_type === 'Final Approver')
                                        ? 'bg-yellow-500 text-white cursor-not-allowed'
                                        : 'bg-blue-500 text-white hover:bg-blue-600',
                                    ]"
                                    :disabled="user.access_type === 'Automation' || user.access_type === 'Final Approver'"
                                >
                                    {{ (user.access_type === 'Automation' || user.access_type === 'Final Approver') ? 'Restricted' : 'Change Role' }}
                                </button>

                                <div v-else class="space-x-2 whitespace-nowrap">
                                    <button
                                    @click="saveRole(user)"
                                    class="px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                                    >
                                    Save
                                    </button>
                                    <button
                                    @click="cancelEditing"
                                    class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                                    >
                                    Cancel
                                    </button>
                                </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Role Instructions Panel -->
                    <div class="p-4 mt-4 overflow-y-auto text-xs text-gray-300 bg-gray-700 border border-gray-600 rounded-lg max-h-40">
                    <strong class="block mb-2 text-sm text-yellow-300">Role Instructions</strong>
                    <ul class="space-y-1 leading-tight list-disc list-inside">
                        <li><strong>Basic User (default)</strong> → Manage, Inspection, and View List (Cannot stamp)</li>
                        <li><strong>Preparation Approver</strong> → All except Approval/Admin (Prepared By Stamping only)</li>
                        <li><strong>Checking Approver</strong> → All except Approval/Admin (Checked By Stamping only)</li>
                        <li><strong>Proxy Approver</strong> → Access to ALL (Approved By Stamp only)</li>
                        <!-- hidden for now

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
            <div class="w-full max-w-4xl p-6 bg-gray-700 shadow-lg rounded-2xl">
                <div>
                    <!-- Filters Section -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mt-4">
                    <!-- Search Logs -->
                    <input
                        type="text"
                        placeholder="Search logs..."
                        v-model="searchQuery"
                        class="flex-grow px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />

                    <!-- Filter by User -->
                    <select
                        v-model="selectedUser"
                        class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">All Users</option>
                        <option v-for="user in uniqueUsers" :key="user" :value="user">{{ user }}</option>
                    </select>

                    <!-- Filter by Section -->
                    <select
                        v-model="selectedSection"
                        class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">All Section</option>
                        <option v-for="section in uniqueSections" :key="section" :value="section">{{ section }}</option>
                    </select>

                    <!-- Sort Logs -->
                    <select
                        v-model="sortOrder"
                        class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Sort by</option>
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>

                    <!-- Filter by Date -->
                    <input
                        type="date"
                        v-model="selectedDate"
                        class="px-4 py-2 text-sm text-gray-200 bg-gray-600 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    </div>

                    <!-- Logs Section -->
                    <div class="mt-4 space-y-2 overflow-auto max-h-64">
                    <div
                        v-for="log in filteredLogs"
                        :key="log.id"
                        class="p-3 bg-gray-600 rounded-lg shadow-sm"
                    >
                        <span class="text-sm">
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
const editingUser = ref(null);
const tempRole = ref("");
const allUserLogs = ref([]);
const searchQuery = ref('');
const selectedUser = ref('');
const selectedSection = ref('');
const sortOrder = ref('');
const selectedDate = ref(getToday());
const uniqueUsers = ref([]);
const uniqueSections = ref([]);

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

    // Filter by selected date (exact date match)
    if (selectedDate.value) {
        logs = logs.filter(log => {
        if (!log.created_at) return false;
            const date = new Date(log.created_at.replace(' ', 'T')) // convert to ISO format
            const localDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
            return localDate === selectedDate.value
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
