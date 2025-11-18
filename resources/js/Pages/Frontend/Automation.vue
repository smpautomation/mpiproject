<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-500">
            Automation master control page

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
