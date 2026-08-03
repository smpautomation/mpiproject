<template>
    <Frontend>
        <div
            class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-500"
        >
            <!-- User Access Control Panel -->
            <div class="w-full max-w-5xl p-6 bg-gray-700 shadow-lg rounded-2xl">
                <div
                    class="flex flex-row items-center self-start justify-start mb-4 space-x-1"
                >
                    <span
                        class="w-12 h-12 transition duration-300 bg-center bg-no-repeat bg-cover"
                        :style="{
                            backgroundImage: 'url(\'/photo/cogwheel.png\')',
                            backgroundSize: '80%',
                        }"
                    ></span>
                    <h2 class="text-xl font-semibold text-gray-200">
                        User Access Control
                    </h2>
                </div>
                <div class="mt-2 space-y-2">
                    <!-- Manage Roles Section -->
                    <div class="bg-gray-600 rounded-lg shadow-sm">
                        <div
                            class="flex flex-row items-center self-start justify-start pt-3 pl-4 space-x-1"
                        >
                            <span
                                class="w-8 h-8 transition duration-300 bg-center bg-no-repeat bg-cover"
                                :style="{
                                    backgroundImage:
                                        'url(\'/photo/user-gear.png\')',
                                    backgroundSize: '80%',
                                }"
                            ></span>
                            <h3 class="text-lg font-medium text-gray-300">
                                Manage Roles
                            </h3>
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
                            <div
                                class="overflow-auto border border-gray-700 rounded-lg max-h-64"
                            >
                            <table
                                class="w-full text-sm text-left text-gray-300 border-collapse"
                            >
                                <thead
                                    class="sticky top-0 z-10 text-center text-gray-400 uppercase bg-gray-700"
                                >
                                    <tr class="text-xs">
                                        <th class="px-1 py-3 whitespace-nowrap">
                                            Employee&nbsp;ID
                                        </th>
                                        <th class="px-1 py-3 whitespace-nowrap">
                                            Last&nbsp;Name
                                        </th>
                                        <th class="px-1 py-3 whitespace-nowrap">
                                            Actions
                                        </th>
                                        <th
                                            v-if="
                                                state.user &&
                                                state.user?.access_type ===
                                                    'Automation' || state.user?.is_authorized_admin == 1
                                            "
                                            class="px-1 py-3 text-center whitespace-nowrap"
                                        >
                                            Change&nbsp;Judgement
                                        </th>
                                        <th
                                            v-if="
                                                state.user &&
                                                state.user?.access_type ===
                                                    'Automation'
                                            "
                                            class="px-1 py-3 text-center whitespace-nowrap"
                                        >
                                            HT&nbsp;Admin&nbsp;Access
                                        </th>
                                        <th
                                            v-if="
                                                state.user &&
                                                state.user?.access_type ===
                                                    'Automation' || state.user?.is_authorized_admin == 1
                                            "
                                            class="px-1 py-3 text-center whitespace-nowrap"
                                        >
                                            MPI&nbsp;Delete&nbsp;Access
                                        </th>
                                        <th
                                            v-if="
                                                state.user &&
                                                state.user?.access_type ===
                                                    'Automation'
                                            "
                                            class="px-1 py-3 text-center whitespace-nowrap"
                                        >
                                            Authorized&nbsp;Admin
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-700">
                                    <tr
                                        v-for="user in filteredUsers"
                                        :key="user.employee_id"
                                        class="text-xs text-center transition-colors duration-150 hover:bg-gray-700/40"
                                    >
                                        <td class="px-1 py-2 whitespace-nowrap">
                                            {{ user.employee_id }}
                                        </td>
                                        <td class="px-1 py-2 whitespace-nowrap">
                                            {{ user.surname }}
                                        </td>

                                        <td
                                            class="px-2 py-2 whitespace-nowrap"
                                        >
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- View Details Button -->
                                                <button
                                                    @click="openDetailsModal(user)"
                                                    title="View Details"
                                                    class="p-1.5 text-gray-300 transition-all duration-200 bg-gray-800 rounded-lg hover:bg-gray-700 hover:text-white border border-gray-700 shadow-sm"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                <div
                                                    v-if="state.user?.access_type === 'Automation' || state.user?.is_authorized_admin == 1"
                                                >
                                                    <!-- Edit / Change Role Toggle -->
                                                    <template v-if="editingUser !== user.employee_id">
                                                        <button
                                                            @click="startEditing(user)"
                                                            :disabled="user.access_type === 'Automation' || user.access_type === 'Final Approver'"
                                                            :class="[
                                                                'px-2.5 py-1 text-xs font-medium rounded-lg transition-all duration-200 shadow-sm border',
                                                                user.access_type === 'Automation' || user.access_type === 'Final Approver'
                                                                    ? 'bg-gray-800/50 text-gray-500 border-gray-800 cursor-not-allowed'
                                                                    : 'bg-blue-600/20 text-blue-400 border-blue-500/30 hover:bg-blue-600/30 hover:text-blue-300'
                                                            ]"
                                                        >
                                                            {{ user.access_type === "Automation" || user.access_type === "Final Approver" ? "Locked" : "Edit Role" }}
                                                        </button>
                                                    </template>

                                                    <!-- Save / Cancel Inline Actions with Role Select -->
                                                    <template v-else>
                                                        <div class="flex items-center gap-1.5">
                                                            <select
                                                                v-model="tempRole"
                                                                class="px-2 py-1 text-xs text-gray-200 bg-gray-900 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            >
                                                                <option value="Basic User">Basic User</option>
                                                                <option value="PrepCheck Approver">PrepCheck Approver</option>
                                                                <option value="Checking Approver">Checking Approver</option>
                                                                <option value="Preparation Approver">Preparation Approver</option>
                                                                <option value="Proxy Approver">Proxy Approver</option>
                                                                <option value="Coating">Coating</option>
                                                                <option value="Heat Treatment">Heat Treatment</option>
                                                            </select>

                                                            <button
                                                                @click="saveRole(user)"
                                                                title="Save"
                                                                class="p-1.5 text-green-400 transition-all duration-200 bg-green-600/20 rounded-lg hover:bg-green-600/30 border border-green-500/30"
                                                            >
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </button>
                                                            <button
                                                                @click="cancelEditing"
                                                                title="Cancel"
                                                                class="p-1.5 text-red-400 transition-all duration-200 bg-red-600/20 rounded-lg hover:bg-red-600/30 border border-red-500/30"
                                                            >
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            v-if="
                                                state.user &&
                                                state.user?.access_type === 'Automation' || state.user?.is_authorized_admin == 1
                                            "
                                            class="px-2 py-2 text-center whitespace-nowrap"
                                        >
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    :checked="user.change_judgement_access === 'yes'"
                                                    @change="user.change_judgement_access === 'yes' ? revokeChangeJudgement(user) : allowChangeJudgement(user)"
                                                    class="sr-only peer"
                                                >
                                                <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                                            </label>
                                        </td>

                                        <td
                                            v-if="
                                                state.user &&
                                                state.user?.access_type === 'Automation'
                                            "
                                            class="px-2 py-2 text-center whitespace-nowrap"
                                        >
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    :checked="user.is_editing_allowed == 1"
                                                    @change="user.is_editing_allowed == 1 ? revokeAdminEditor(user) : allowAdminEditor(user)"
                                                    class="sr-only peer"
                                                >
                                                <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </td>

                                        <td
                                            v-if="
                                                state.user &&
                                                state.user?.access_type === 'Automation' || state.user?.is_authorized_admin == 1
                                            "
                                            class="px-2 py-2 text-center whitespace-nowrap"
                                        >
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    :checked="user.allow_mpi_delete == 1"
                                                    @change="user.allow_mpi_delete == 1 ? revokeMpiDelete(user) : allowMpiDelete(user)"
                                                    class="sr-only peer"
                                                >
                                                <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </td>

                                        <td
                                            v-if="
                                                state.user &&
                                                state.user?.access_type === 'Automation'
                                            "
                                            class="px-2 py-2 text-center whitespace-nowrap"
                                        >
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    :checked="user.is_authorized_admin == 1"
                                                    @change="user.is_authorized_admin == 1 ? revokeAuthorizeAdmin(user) : allowAuthorizeAdmin(user)"
                                                    class="sr-only peer"
                                                >
                                                <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            <!-- Role Instructions Panel -->
                            <div
                                class="p-4 mt-4 overflow-y-auto text-xs text-gray-300 bg-gray-700 border border-gray-600 rounded-lg max-h-40"
                            >
                                <strong
                                    class="block mb-2 text-sm text-yellow-300"
                                    >Role Instructions (Navbar Access)</strong
                                >
                                <ul
                                    class="space-y-1 leading-tight list-disc list-inside text-[10px]"
                                >
                                    <li>
                                        <strong>Basic User (default)</strong> →
                                        Home (Cannot stamp)
                                    </li>
                                    <li>
                                        <strong>Heat Treatment</strong> → Home,
                                        Heat Treatment, Coating, Film Pasting,
                                        MPI Reports, 1st & 2nd GBDP, Furnace, HT
                                        Graph Patterns (Cannot stamp)
                                    </li>
                                    <li>
                                        <strong>Coating</strong> → Home, Heat
                                        Treatment, Coating, Film Pasting, MPI
                                        Reports, 1st & 2nd GBDP, Furnace, HT
                                        Graph Patterns (Cannot stamp)
                                    </li>
                                    <li>
                                        <strong>Preparation Approver</strong> →
                                        Home, Mass Production JH Curve, MPI
                                        Reports, View List, Inspection, Sppecial
                                        Instructions, Send Email, Mias Factor
                                        Emp (Prepared By Stamping only)
                                    </li>
                                    <li>
                                        <strong>Checking Approver</strong> →
                                        Home, Mass Production JH Curve, MPI
                                        Reports, View List, Inspection, Sppecial
                                        Instructions, Send Email, Mias Factor
                                        Emp (Checked By Stamping only)
                                    </li>
                                    <li>
                                        <strong>Proxy Approver</strong> → Home,
                                        Mass Production, JH Curve, MPI Reports,
                                        View List, Inspection, Approval,
                                        Sppecial Instructions, Send Email, Mias
                                        Factor Emp, Admin (Approved By Stamp
                                        only)
                                    </li>
                                    <li>
                                        <strong>Final Approver</strong> → Home,
                                        Mass Production, MPI Reports, Approval,
                                        View List, Admin (Approved By Stamp
                                        only)
                                    </li>
                                    <li>
                                        <strong>PrepCheck Approver</strong> → All except Approval/Admin (Prepared By & Checked By Stamping)
                                    </li>

                                    <!--
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
                                <option
                                    v-for="user in uniqueUsers"
                                    :key="user"
                                    :value="user"
                                >
                                    {{ user }}
                                </option>
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
                                <option
                                    v-for="section in uniqueSections"
                                    :key="section"
                                    :value="section"
                                >
                                    {{ section }}
                                </option>
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
                                <span class="mr-2 text-orange-600"
                                    >[{{ log.section }}]</span
                                >
                                <span class="mr-2 text-gray-200"
                                    >[{{ formatDate(log.created_at) }}]</span
                                >
                                <span class="mr-2 text-green-400">{{
                                    log.user
                                }}</span>
                                <span class="mr-2 text-blue-400">{{
                                    log.event
                                }}</span>
                                <span class="mr-2 text-gray-400"
                                    >at {{ formatTime(log.created_at) }}</span
                                >
                            </span>
                        </div>
                    </div>
                </div>
            </div>




            <!-- View Details Modal -->
            <div v-if="isDetailsModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div class="w-full max-w-lg p-6 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl text-gray-200">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                        <h3 class="text-lg font-semibold text-white">User Details</h3>
                        <button @click="closeDetailsModal" class="text-gray-400 hover:text-white">&times;</button>
                    </div>

                    <div v-if="selectedUser" class="grid grid-cols-2 gap-4 py-4 text-sm">
                        <div>
                            <span class="block text-xs uppercase text-gray-400">First Name</span>
                            <span class="font-medium text-white">{{ selectedUser.firstName || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400">Last Name</span>
                            <span class="font-medium text-white">{{ selectedUser.surname || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400">Username</span>
                            <span class="font-medium text-white">{{ selectedUser.username || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400">Employee ID</span>
                            <span class="font-medium text-white">{{ selectedUser.employee_id || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400">Plant</span>
                            <span class="font-medium text-white">{{ selectedUser.plant || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400">Access Type</span>
                            <span class="font-medium text-white">{{ selectedUser.access_type || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400">Email</span>
                            <span class="font-medium text-white">{{ selectedUser.email || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase text-gray-400">Created At</span>
                            <span class="font-medium text-white">{{ selectedUser.created_at ? new Date(selectedUser.created_at).toLocaleDateString() : 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-700">
                        <button @click="closeDetailsModal" class="px-4 py-2 text-xs font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>


        </div>
    </Frontend>
</template>

<script setup>
import Frontend from "@/Layouts/FrontendLayout.vue";
import { onMounted, ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import { useAuth } from "@/Composables/useAuth.js";

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {
        const start = Date.now();
        const maxWait = 5000; // 5 seconds

        while (!state.user) {
            if (Date.now() - start > maxWait) {
                console.error(
                    "User data failed to load in time. Redirecting...",
                );
                router.visit("/"); // Redirect if user never loads
                return false;
            }
            await new Promise((resolve) => setTimeout(resolve, 50));
        }

        if (!state.isAuthenticated) {
            console.warn("User is not authenticated. Redirecting...");
            router.visit("/");
            return false;
        }

        /*console.log(
            "USER AUTHENTICATED!",
            `${state.user.firstName} ${state.user.surname}`,
        );*/
        return true;
    } catch (error) {
        console.error("Error checking authentication:", error);
        router.visit("/");
        return false;
    }
};

const userAdminLogging = async (logEvent) => {
    try {
        const responseAdminLogging = await axios.post("/api/userlogs", {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: "Admin",
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    } catch (error) {
        console.error("responseAdminLogging post request failed: ", error);
    }
};

const users = ref([]);
const users_searchQuery = ref("");
const editingUser = ref(null);
const tempRole = ref("");
const allUserLogs = ref([]);
const searchQuery = ref("");
const selectedUser = ref("");
const selectedSection = ref("");
const sortOrder = ref("");
const uniqueUsers = ref([]);
const uniqueSections = ref([]);
const startDate = ref(null);
const endDate = ref(null);

const isDetailsModalOpen = ref(false);

const openDetailsModal = (user) => {
    selectedUser.value = user;
    isDetailsModalOpen.value = true;
};

const closeDetailsModal = () => {
    isDetailsModalOpen.value = false;
    selectedUser.value = null;
};

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
    tempRole.value = user.access_type; // Updated to match API response
};

const cancelEditing = () => {
    editingUser.value = null;
    tempRole.value = "";
};

const formatDate = (isoString) => {
    if (!isoString) return "";
    const date = new Date(isoString);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short", // Jun, Jul etc.
        day: "numeric",
        timeZone: "Asia/Manila", // Set your timezone here
    });
};

const saveRole = async (user) => {
    try {
        const response = await axios.put(`/api/users/${user.id}`, {
            access_type: tempRole.value,
        });

        //console.log(`Response from server:`, response.data);

        // Update the user's role in the local state if the update succeeds
        user.access_type = tempRole.value;
        editingUser.value = null;
        tempRole.value = "";

        //console.log(`Role for ${user.username} updated to: ${user.access_type}`);
        await userAdminLogging(
            `Updated role for ${user.firstName} ${user.surname} to: ${user.access_type}`,
        );
    } catch (error) {
        console.error(`Error updating role for ${user.username}:`, error);
        // Optional: Add error handling logic, e.g., show a toast notification
    }
};

const allowChangeJudgement = async (user) => {
    try {
        const response = await axios.put(`/api/users/${user.id}`, {
            change_judgement_access: "yes",
        });

        // Update local state immediately
        user.change_judgement_access = "yes";

        await userAdminLogging(
            `Granted change judgement access to ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error granting change judgement access for ${user.username}:`,
            error,
        );
    }
};

const allowAdminEditor = async (user) => {
    try {
        const response = await axios.put(`/api/users/${user.id}`, {
            is_editing_allowed: 1,
        });

        // Update local state immediately
        user.is_editing_allowed = 1;

        await userAdminLogging(
            `Granted admin editor access to ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error granting admin editor access for ${user.username}:`,
            error,
        );
    }
};

const allowMpiDelete = async (user) => {
    try {
        const response = await axios.put(`/api/users/${user.id}`, {
            allow_mpi_delete: 1,
        });

        // Update local state immediately
        user.allow_mpi_delete = 1;

        await userAdminLogging(
            `Granted admin editor access to ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error granting admin editor access for ${user.username}:`,
            error,
        );
    }
};

const allowAuthorizeAdmin = async (user) => {
    try {
        const response = await axios.put(`/api/users/${user.id}`, {
            is_authorized_admin: 1,
        });

        // Update local state immediately
        user.is_authorized_admin = 1;

        await userAdminLogging(
            `Granted admin editor access to ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error granting admin editor access for ${user.username}:`,
            error,
        );
    }
};

const revokeChangeJudgement = async (user) => {
    try {
        await axios.put(`/api/users/${user.id}`, {
            change_judgement_access: "no",
        });

        // Update local state immediately
        user.change_judgement_access = "no";

        await userAdminLogging(
            `Revoked change judgement access for ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error revoking change judgement access for ${user.username}:`,
            error,
        );
    }
};

const revokeAdminEditor = async (user) => {
    //console.log("clicked revoke", user.id);
    try {
        await axios.put(`/api/users/${user.id}`, {
            is_editing_allowed: 0,
        });

        // Update local state immediately
        user.is_editing_allowed = 0;

        await userAdminLogging(
            `Revoked admin editor access for ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error revoking admin editor access for ${user.username}:`,
            error,
        );
    }
};

const revokeMpiDelete = async (user) => {
    //console.log("clicked revoke", user.id);
    try {
        await axios.put(`/api/users/${user.id}`, {
            allow_mpi_delete: 0,
        });

        // Update local state immediately
        user.allow_mpi_delete = 0;

        await userAdminLogging(
            `Revoked admin editor access for ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error revoking admin editor access for ${user.username}:`,
            error,
        );
    }
};

const revokeAuthorizeAdmin = async (user) => {
    //console.log("clicked revoke", user.id);
    try {
        await axios.put(`/api/users/${user.id}`, {
            is_authorized_admin: 0,
        });

        // Update local state immediately
        user.is_authorized_admin = 0;

        await userAdminLogging(
            `Revoked admin editor access for ${user.firstName} ${user.surname}`,
        );
    } catch (error) {
        console.error(
            `Error revoking admin editor access for ${user.username}:`,
            error,
        );
    }
};


const formatTime = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const fetchAllUsers = async () => {
    try {
        const response = await axios.get("/api/users");
        //console.log('Fetched users:', response.data);
        users.value = response.data.data; // Assign the array to users
        console.log('Fetched users in my users array:', users.value);
    } catch (error) {
        console.error("Error fetching users:", error);
    }
};

const fetchAllLogs = async () => {
    try {
        const response = await axios.get("/api/userlogs");
        allUserLogs.value = response.data;
        //console.log('allUserLogs-array: ',allUserLogs.value);
        // Extract unique users dynamically
        const usersSet = new Set(
            allUserLogs.value.map((log) => log.user).filter(Boolean),
        );
        uniqueUsers.value = Array.from(usersSet).sort();
        const sectionsSet = new Set(
            allUserLogs.value.map((log) => log.section).filter(Boolean),
        );
        uniqueSections.value = Array.from(sectionsSet).sort();
        //console.log('uniqueUsers array: ',uniqueUsers.value);
        //console.log('uniqueSections array: ',uniqueSections.value);
    } catch (error) {
        console.error("Failed to fetch logs:", error);
    }
};

const filteredUsers = computed(() => {
    if (!users_searchQuery.value) return users.value;

    const q = users_searchQuery.value.toLowerCase();

    return users.value.filter((u) => {
        return (
            (u.firstName ?? "").toLowerCase().includes(q) ||
            (u.surname ?? "").toLowerCase().includes(q) ||
            (u.employee_id ?? "").toLowerCase().includes(q)
        );
    });
});

// Computed: filtered and sorted logs
const filteredLogs = computed(() => {
    let logs = [...allUserLogs.value];

    // Filter by search query (event or user)
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase();
        logs = logs.filter(
            (log) =>
                log.event?.toLowerCase().includes(query) ||
                log.user?.toLowerCase().includes(query),
        );
    }

    // Filter by selected user
    if (selectedUser.value) {
        logs = logs.filter((log) => log.user === selectedUser.value);
    }

    // Filter by selected section
    if (selectedSection.value) {
        logs = logs.filter((log) => log.section === selectedSection.value);
    }

    // Filter by date range
    if (startDate.value || endDate.value) {
        logs = logs.filter((log) => {
            if (!log.created_at) return false;

            const logDate = new Date(log.created_at.replace(" ", "T"));

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
    if (sortOrder.value === "newest") {
        logs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    } else if (sortOrder.value === "oldest") {
        logs.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    }

    return logs;
});

onMounted(async () => {
    await checkAuthentication();
    await fetchAllUsers();
    await fetchAllLogs();
});
</script>
