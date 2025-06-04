<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-500">
            <!-- User Access Control Panel -->
            <div class="w-full max-w-5xl p-6 bg-gray-700 rounded-2xl shadow-lg">
                <h2 class="text-xl font-semibold text-gray-200">User Access Control</h2>
                <div class="mt-4 space-y-4">
                <!-- Manage Roles Section -->
                <div class="bg-gray-600 rounded-lg shadow-sm">
                    <h3 class="px-4 py-2 text-lg font-medium text-gray-300">Manage Roles</h3>
                    <div class="p-4 space-y-2">
                    <div class="overflow-auto max-h-64">
                        <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-gray-400 uppercase bg-gray-700">
                            <tr>
                            <th scope="col" class="px-4 py-3">First Name</th>
                            <th scope="col" class="px-4 py-3">Last Name</th>
                            <th scope="col" class="px-4 py-3">Username</th>
                            <th scope="col" class="px-4 py-3">Employee ID</th>
                            <th scope="col" class="px-4 py-3">Plant</th>
                            <th scope="col" class="px-4 py-3">Current Role</th>
                            <th scope="col" class="px-4 py-3">Actions</th>
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
                                    <option value="Hybrid Approver">Hybrid Approver</option>
                                    <option value="Bypass Approver">Bypass Approver</option>
                                    <option value="Proxy Approver">Proxy Approver</option>
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
                    <div class="mt-4 p-4 max-h-40 overflow-y-auto bg-gray-700 text-gray-300 text-xs rounded-lg border border-gray-600">
                    <strong class="block mb-2 text-sm text-yellow-300">Role Instructions</strong>
                    <ul class="list-disc list-inside space-y-1 leading-tight">
                        <li><strong>Basic User</strong> → Manage, Inspection, and View List (Cannot stamp)</li>
                        <li><strong>Preparation Approver</strong> → All except Approval/Admin (Prepared By Stamping)</li>
                        <li><strong>Checking Approver</strong> → All except Approval/Admin (Checked By Stamping)</li>
                        <li><strong>Hybrid Approver</strong> → All except Approval/Admin (Prepared By & Checked By Stamping)</li>
                        <li><strong>Bypass Approver</strong> → All except Admin (All stamps: Prepared, Checked, Approved)</li>
                        <li><strong>Proxy Approver</strong> → Approval and Admin only (Approved By Stamp)</li>
                    </ul>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Logs Panel -->
            <div class="w-full max-w-4xl p-6 bg-gray-700 rounded-2xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-200">System Logs</h3>

                <!-- Filters Section -->
                <div class="flex flex-wrap items-center justify-between gap-4 mt-4">
                    <input type="text" placeholder="Search logs..." class="flex-grow px-4 py-2 text-sm border border-gray-600 rounded-lg bg-gray-600 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <select class="px-4 py-2 text-sm border border-gray-600 rounded-lg bg-gray-600 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Users</option>
                        <option value="JohnDoe">JohnDoe</option>
                        <option value="JaneDoe">JaneDoe</option>
                    </select>
                    <select class="px-4 py-2 text-sm border border-gray-600 rounded-lg bg-gray-600 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sort by</option>
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                    <input type="date" class="px-4 py-2 text-sm border border-gray-600 rounded-lg bg-gray-600 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Manual Log Buttons -->
                <div class="flex flex-wrap items-center gap-4 mt-4">
                    <button class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Register Bug Fix</button>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Register System Update</button>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">Register Maintenance</button>
                </div>

                <!-- Logs Section -->
                <div class="mt-4 space-y-2 max-h-64 overflow-auto">
                    <div class="p-3 bg-gray-600 rounded-lg shadow-sm">
                        <span class="text-sm text-gray-300">System initialized at 09:45 AM</span>
                    </div>
                    <div class="p-3 bg-gray-600 rounded-lg shadow-sm">
                        <span class="text-sm text-gray-300">User JohnDoe added at 10:15 AM</span>
                    </div>
                    <div class="p-3 bg-gray-600 rounded-lg shadow-sm">
                        <span class="text-sm text-gray-300">Server updated at 11:30 AM</span>
                    </div>
                    <div class="p-3 bg-gray-600 rounded-lg shadow-sm">
                        <span class="text-sm text-gray-300">User JaneDoe logged in at 09:50 AM</span>
                    </div>
                    <div class="p-3 bg-gray-600 rounded-lg shadow-sm">
                        <span class="text-sm text-gray-300">Password changed by User JohnDoe at 10:20 AM</span>
                    </div>
                    <div class="p-3 bg-gray-600 rounded-lg shadow-sm">
                        <span class="text-sm text-gray-300">Database backup completed at 12:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { onMounted, ref } from "vue";
import axios from 'axios';

const users = ref([]);
const editingUser = ref(null);
const tempRole = ref("");

const startEditing = (user) => {
  editingUser.value = user.employee_id; // Updated to match API response
  tempRole.value = user.access_type;   // Updated to match API response
};

const cancelEditing = () => {
  editingUser.value = null;
  tempRole.value = "";
};


const saveRole = async (user) => {
  try {
    const response = await axios.put(`/api/users/${user.id}`, {
        access_type: tempRole.value
    });

    console.log(`Response from server:`, response.data);

    // Update the user's role in the local state if the update succeeds
    user.access_type = tempRole.value;
    editingUser.value = null;
    tempRole.value = "";

    console.log(`Role for ${user.username} updated to: ${user.access_type}`);
  } catch (error) {
    console.error(`Error updating role for ${user.username}:`, error);
    // Optional: Add error handling logic, e.g., show a toast notification
  }
};

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

onMounted(async() => {
  await fetchAllUsers();
});

</script>
