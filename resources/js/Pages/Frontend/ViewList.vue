<template>
    <Frontend>
        <!-- System Banner -->
        <div class="relative w-full overflow-hidden bg-gray-900 border-b border-cyan-500">
            <div class="flex w-max animate-marquee">
                <div class="flex whitespace-nowrap py-2 text-sm font-semibold text-cyan-100">
                    <span class="mx-6">⚙️ This system is developed and maintained by the Automation Engineering Software Team</span>
                    <span class="mx-6">📞 For concerns contact Local 206 / 619</span>
                    <span class="mx-6">📜 System usage and modification are restricted without prior coordination</span>
                </div>
                <!-- duplicate (this is the magic) -->
                <div class="flex whitespace-nowrap py-2 text-sm font-semibold text-cyan-100">
                    <span class="mx-6">⚙️ This system is developed and maintained by the Automation Engineering Software Team</span>
                    <span class="mx-6">📞 For concerns contact Local 206 / 619</span>
                    <span class="mx-6">📜 System usage and modification are restricted without prior coordination</span>
                </div>
            </div>
        </div>
        <div
            class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100"
        >
            <!-- Search Box -->
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search model name or lot no..."
                class="w-full max-w-md p-2 border rounded shadow-sm"
            />
            <div class="flex flex-row items-center space-x-4 align-middle">
                <label>Furnace: </label>
                <!-- Status Filter -->
                <select
                    v-model="selectedFurnace"
                    class="w-[200px] p-2 border rounded shadow-sm"
                >
                    <!--option value="COATING_PENDING">Coating Pending</!--option>
                <option-- value="HEAT_TREATMENT_PENDING">Heat Treatment Pending</option-->
                    <option value="">All</option>
                    <!-- new line -->
                    <option
                        v-for="item in furnace_lists"
                        :key="item"
                        :value="item"
                    >
                        {{ item }}
                    </option>
                </select>

                <label>Mass Prod: </label>
                <!-- Status Filter -->
                <select
                    v-model="selectedMassProd"
                    class="w-[200px] p-2 border rounded shadow-sm"
                >
                    <!--option value="COATING_PENDING">Coating Pending</!--option>
                <option-- value="HEAT_TREATMENT_PENDING">Heat Treatment Pending</option-->
                    <option value="">All</option>
                    <!-- new line -->
                    <option
                        v-for="item in massProd_names"
                        :key="item"
                        :value="item"
                    >
                        {{ item }}
                    </option>
                </select>

                <label>Status: </label>
                <!-- Status Filter -->
                <select
                    v-model="statusFilter"
                    class="w-[200px] p-2 border rounded shadow-sm"
                >
                    <option value="">All</option>
                    <option value="COMPLETED">Completed</option>
                    <option value="PENDING">Approved by Pending</option>
                    <option value="PREPARED_PENDING">
                        Prepared by Pending
                    </option>
                    <option value="CHECKED_PENDING">Checked by Pending</option>
                    <option value="FINALIZED_PENDING">Finalize Pending</option>
                    <!--option value="COATING_PENDING">Coating Pending</!--option>
                <option-- value="HEAT_TREATMENT_PENDING">Heat Treatment Pending</option-->
                </select>

                <label>Date From:</label>
                <input
                    type="date"
                    v-model="vl_dateFrom"
                    :max="vl_dateTo"
                    class="w-[180px] p-2 border rounded shadow-sm"
                />

                <label>Date To:</label>
                <input
                    type="date"
                    v-model="vl_dateTo"
                    :min="vl_dateFrom"
                    class="w-[180px] p-2 border rounded shadow-sm"
                />
            </div>

            <!-- No Data -->
            <div
                v-if="tpmData.length === 0"
                class="text-lg font-semibold text-gray-500"
            >
                No matching data found.
            </div>

            <!-- Table -->
            <div v-else class="w-full overflow-x-auto">
                <table class="w-full border-collapse rounded-lg shadow-lg">
                    <thead>
                        <tr
                            class="text-white bg-gradient-to-r from-cyan-600 via-cyan-900 to-teal-600"
                        >
                            <th class="px-2 py-2 whitespace-nowrap">Date</th>
                            <th class="px-2 py-2 whitespace-nowrap">
                                Mass Production
                            </th>
                            <th class="px-2 py-2 whitespace-nowrap">Layer</th>
                            <th class="px-2 py-2 whitespace-nowrap">
                                Serial No
                            </th>
                            <th class="px-2 py-2 whitespace-nowrap">
                                Model Name
                            </th>
                            <th class="px-2 py-2 whitespace-nowrap">Lot No</th>
                            <th class="px-2 py-2 whitespace-nowrap">
                                Furnace No
                            </th>
                            <th class="px-2 py-2 whitespace-nowrap">
                                Tracer No
                            </th>
                            <th class="px-2 py-2 whitespace-nowrap">
                                SMP Judgement
                            </th>
                            <th class="px-2 py-2 whitespace-nowrap">Status</th>
                            <th class="px-2 py-2 whitespace-nowrap">Email</th>
                            <th
                                v-if="
                                    state.user &&
                                    state.user.access_type !== 'Basic User'
                                "
                                class="px-2 py-2 whitespace-nowrap"
                            >
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="item in tpmData"
                            :key="item.serial_no"
                            class="bg-gradient-to-r from-cyan-600 via-cyan-900 to-teal-600"
                        >
                            <!-- DATE -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{
                                        formatDate(item.report?.updated_at) ||
                                        "NO DATA"
                                    }}
                                </div>
                            </td>

                            <!-- MASS PROD -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{
                                        item.latest_tpm?.mass_prod || "NO DATA"
                                    }}
                                </div>
                            </td>

                            <!-- LAYER -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{ item.latest_tpm?.layer_no || "NO DATA" }}
                                </div>
                            </td>

                            <!-- SERIAL -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{ item.serial_no || "NO DATA" }}
                                </div>
                            </td>

                            <!-- MODEL -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{
                                        item.category?.actual_model || "NO DATA"
                                    }}
                                </div>
                            </td>

                            <!-- LOT -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{
                                        item.category?.jhcurve_lotno ||
                                        "NO DATA"
                                    }}
                                </div>
                            </td>

                            <!-- FURNACE -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{
                                        item.latest_tpm?.sintering_furnace_no ||
                                        "NO DATA"
                                    }}
                                </div>
                            </td>

                            <!-- TRACER -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{ item.latest_tpm?.Tracer || "NO DATA" }}
                                </div>
                            </td>

                            <!-- SMP -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{
                                        item.report?.modified_smp_judgement?.trim()
                                            ? item.report.modified_smp_judgement
                                            : (item.report?.smp_judgement?.trim() || "NO DATA")
                                    }}
                                </div>
                            </td>

                            <!-- STATUS -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm text-center bg-white rounded-sm"
                                >
                                    {{
                                        item.report?.approved_by_firstname
                                            ? "COMPLETED"
                                            : "PENDING"
                                    }}
                                </div>
                            </td>

                            <!-- EMAIL STATUS -->
                            <td class="p-[1px]">
                                <div
                                    class="px-2 py-1 text-sm font-medium text-center rounded-sm"
                                    :class="{
                                        'bg-green-100 text-green-800':
                                            item.report?.is_emailed,
                                        'bg-blue-100 text-blue-800':
                                            item.report?.is_finalized &&
                                            !item.report?.is_emailed,
                                        'bg-yellow-100 text-yellow-800':
                                            !item.report?.is_finalized &&
                                            !item.report?.is_emailed,
                                    }"
                                >
                                    {{
                                        item.report?.is_emailed
                                            ? "EMAIL SENT"
                                            : item.report?.is_finalized
                                              ? "READY FOR EMAIL"
                                              : "PENDING"
                                    }}
                                </div>
                            </td>

                            <!-- ACTION -->
                            <td
                                v-if="
                                    state.user &&
                                    state.user.access_type !== 'Basic User'
                                "
                                class="p-[1px] text-center"
                            >
                                <div
                                    class="flex justify-center px-0 py-1 bg-white rounded-sm space-x-2 overflow-hidden"
                                >
                                     <!-- Future Feature Button (interactive-looking placeholder) -->
                                    <div class="relative group">
                                        <button
                                            title="Under Development: streamlined reporting to Automation Engineering for faster issue handling."
                                            class="w-[160px] bg-gray-200 text-gray-500 rounded-md font-medium text-sm shadow-sm transition-all duration-200 hover:bg-gray-300 hover:text-gray-600 active:scale-[0.98]"
                                        >
                                            Report to Automation
                                        </button>
                                    </div>

                                    <!-- VIEW -->
                                    <button
                                        @click="viewReport(item.serial_no)"
                                        class="w-[110px] bg-cyan-600 text-white rounded-md font-medium text-sm shadow-sm hover:bg-cyan-500 transition-all"
                                    >
                                        View Report
                                    </button>

                                    <!-- DELETE -->
                                    <template
                                        v-if="
                                            confirmDeleteFor === item.serial_no
                                        "
                                    >
                                        <button
                                            @click="deleteRow(item.serial_no)"
                                            class="w-[40px] bg-green-600 text-white rounded-sm"
                                        >
                                            Yes
                                        </button>

                                        <button
                                            @click="confirmDeleteFor = null"
                                            class="w-[60px] bg-gray-300 text-gray-800 rounded-sm"
                                        >
                                            Cancel
                                        </button>
                                    </template>

                                    <template v-else>
                                        <button
                                            @click="
                                                confirmDeleteFor =
                                                    item.serial_no
                                            "
                                            class="w-[110px] bg-red-700 text-white rounded-md font-medium text-sm"
                                        >
                                            Delete
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center mt-4 space-x-2">
                <button
                    @click="prevPage"
                    :disabled="currentPage === 1 || isLoading"
                    class="px-3 py-1 text-white rounded bg-cyan-400 hover:bg-cyan-600 disabled:opacity-50"
                >
                    Prev
                </button>

                <span class="text-gray-400">
                    Page {{ currentPage }} of {{ totalPages }}
                </span>

                <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages || isLoading"
                    class="px-3 py-1 text-white rounded bg-cyan-400 hover:bg-cyan-600 disabled:opacity-50"
                >
                    Next
                </button>
            </div>

            <!--

            <button
                v-if="cantFindData == false"
                @click="cantFindData = true"
                class="px-4 py-2 text-sm font-semibold text-white bg-gray-600 rounded-lg hover:bg-gray-700"
            >
                Can't find report?
            </button>

            -->

            <div v-if="cantFindData" class="p-4 space-y-4">
                <!-- 🔍 Search Panel -->
                <div class="p-4 bg-white border shadow-sm rounded-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-gray-700">
                            Search Old Reports
                        </h2>

                        <button
                            @click="cantFindData = false"
                            class="text-xs text-gray-500 hover:text-gray-700"
                        >
                            Close
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Mass Production -->
                        <div>
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Mass Production Name
                            </label>
                            <input
                                v-model="searchMassProd"
                                type="text"
                                @input="
                                    searchMassProd =
                                        searchMassProd.toUpperCase()
                                "
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500"
                                placeholder="Enter mass production"
                            />
                        </div>

                        <!-- Furnace -->
                        <div>
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Furnace
                            </label>
                            <input
                                v-model="searchFurnace"
                                type="text"
                                @input="
                                    searchFurnace = searchFurnace.toUpperCase()
                                "
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500"
                                placeholder="Enter furnace"
                            />
                        </div>

                        <!-- Action -->
                        <div class="flex items-end">
                            <button
                                @click="getSearchedData"
                                type="button"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition rounded-lg bg-cyan-500 hover:bg-cyan-600"
                            >
                                Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 📊 Table -->
                <div
                    class="overflow-hidden bg-white border shadow-sm rounded-xl"
                >
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full text-sm text-left text-gray-700"
                        >
                            <!-- Header -->
                            <thead
                                class="text-xs text-gray-600 uppercase bg-gray-100"
                            >
                                <tr>
                                    <th class="px-4 py-3">Serial No</th>
                                    <th class="px-4 py-3">Furnace</th>
                                    <th class="px-4 py-3">Mass Production</th>
                                    <th class="px-4 py-3">Layer</th>
                                    <th class="px-4 py-3">Model Name</th>
                                    <th class="px-4 py-3">Lot No</th>
                                    <th class="px-4 py-3 text-center">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <!-- Body -->
                            <tbody class="divide-y">
                                <!-- Empty State -->
                                <tr v-if="searchedDataList.length === 0">
                                    <td
                                        colspan="7"
                                        class="py-6 text-center text-gray-400"
                                    >
                                        No data found
                                    </td>
                                </tr>

                                <!-- Rows -->
                                <tr
                                    v-for="item in searchedDataList"
                                    :key="item.serial_no"
                                    class="transition hover:bg-gray-50"
                                >
                                    <td
                                        class="px-4 py-2 font-medium text-gray-800"
                                    >
                                        {{ item.serial_no }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ item.furnace }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ item.mass_prod }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ item.layer ?? "-" }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ item.model_name ?? "-" }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ item.lot_no ?? "-" }}
                                    </td>

                                    <!-- Action -->
                                    <td class="px-4 py-2 text-center">
                                        <button
                                            @click="viewReport(item.serial_no)"
                                            class="px-3 py-1 text-xs font-semibold text-white transition rounded-md bg-cyan-600 hover:bg-cyan-400"
                                        >
                                            View Report
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from "@/Layouts/FrontendLayout.vue";
import { ref, computed, onMounted, watch } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import { useAuth } from "@/Composables/useAuth.js";

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {
        // still loading → do nothing
        if (state.loading) return false;

        // not authenticated → safe redirect
        if (!state.isAuthenticated) {
            router.visit("/");
            return false;
        }

        console.warn("USER AUTHENTICATED!");

        const user = state.user;

        if (user) {
            console.warn(
                "Name:",
                `${user.firstName ?? ""} ${user.surname ?? ""}`,
            );
            console.warn("Access:", user.access_type);
        }

        return true;
    } catch (error) {
        console.error("Error checking authentication:", error);
        router.visit("/");
        return false;
    }
};

function useSessionStorage(key, state) {
    // Load existing session value
    const saved = sessionStorage.getItem(key);
    if (saved !== null) {
        try {
            const parsed = JSON.parse(saved);
            if (typeof state === "object" && "value" in state) {
                state.value = parsed;
            } else {
                Object.assign(state, parsed);
            }
        } catch {
            /* ignore parse errors */
        }
    }

    // Watch and persist changes
    watch(
        state,
        (val) => {
            sessionStorage.setItem(key, JSON.stringify(val));
        },
        { deep: true },
    );
}

const formatDate = (isoString) => {
    if (!isoString) return null;
    const date = new Date(isoString);
    return date.toLocaleString(undefined, {
        year: "numeric",
        month: "short",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false, // or true if you want AM/PM
    });
};

const userSerialDeleteLogging = async (logEvent) => {
    try {
        const responseSerialDeleteLogging = await axios.post("/api/userlogs", {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: "View List",
        });

        //console.log('responseSerialDeleteLogging-data: ',responseSerialDeleteLogging.data);
    } catch (error) {
        console.error("userSerialDeleteLogging post request failed: ", error);
    }
};

const viewReport = (serial) => {
    router.visit("/reports", {
        method: "get", // You can keep 'get' since we are not modifying any data
        data: { serialParam: serial, fromViewList: true }, // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};

const today = new Date();

// Helper to format date as YYYY-MM-DD without converting to UTC
const formatDateForInput = (date) => {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, "0"); // months 0-11
    const dd = String(date.getDate()).padStart(2, "0");
    return `${yyyy}-${mm}-${dd}`;
};

// First day of last month
const firstDayLastMonth = new Date(
    today.getFullYear(),
    today.getMonth() - 1,
    1,
);

// Last day of current month
const lastDayCurrentMonth = new Date(
    today.getFullYear(),
    today.getMonth() + 1,
    0,
);

let isLoading = false;

// Reactive refs for your date inputs
const vl_dateFrom = ref(formatDateForInput(firstDayLastMonth));
const vl_dateTo = ref(formatDateForInput(lastDayCurrentMonth));

const tpmData = ref([]);
const searchQuery = ref("");
const currentPage = ref(1);
const itemsPerPage = 20;
const statusFilter = ref("");
const confirmDeleteFor = ref(null);
const massProd_names = ref([]);
const furnace_lists = ref([]);
const selectedMassProd = ref("");
const selectedFurnace = ref("");

const searchMassProd = ref();
const searchFurnace = ref();
const searchedDataList = ref([]);
const cantFindData = ref(false);

const totalPages = ref(1);

// Fetch data
const viewAllSerialedLayers = async () => {
    if (isLoading) return;

    try {
        isLoading = true;
        console.time("API view data");

        const response = await axios.get("/api/tpmdata/view-list", {
            params: {
                search: searchQuery.value,
                status: statusFilter.value,
                mass_prod: selectedMassProd.value,
                furnace: selectedFurnace.value,
                from: vl_dateFrom.value,
                to: vl_dateTo.value,
                per_page: itemsPerPage,
                page: currentPage.value,
            },
        });

        console.timeEnd("API view data");

        const result = response.data;

        // ─────────────────────────────
        // DATA (server source of truth)
        // ─────────────────────────────
        tpmData.value = result.data ?? {};

        // ─────────────────────────────
        // PAGINATION SYNC
        // ─────────────────────────────
        const pagination = result.pagination;

        currentPage.value = pagination?.current_page ?? currentPage.value;
        totalPages.value = pagination?.last_page ?? 1;

        //console.log("TPM DATA RAW:", tpmData.value);
    } catch (error) {
        console.error("[ViewList Error]", error);
    } finally {
        isLoading = false;
    }
};

const getFurnaceLists = async () => {
    try {
        const response = await axios.get("/api/furnace-data");
        const furnaceData = response.data;
        furnace_lists.value = furnaceData.map((item) => item.furnace_name);
        //console.log("Furnace Lists: ", furnace_lists.value);
    } catch (error) {
        console.error("Failed to fetch furnace data lists: ", error);
        toast.error("Furnace Data List error.");
    }
};

const getMassProdLists = async () => {
    try {
        const response = await axios.get("/api/mass-production/");
        const massProdList = response.data;
        massProd_names.value = massProdList.map((item) => item.mass_prod);
        //console.log("List of mass prods: ",massProd_names.value);
    } catch (error) {
        console.error("Error fetching mass prod lists", error);
        toast.error("Failed to get the mass prod lists api error");
    }
};

const getSearchedData = async () => {
    try {
        const response = await axios.get("/api/search-old-view-list", {
            params: {
                search_mass_prod: searchMassProd.value,
                search_furnace: searchFurnace.value,
            },
        });
        searchedDataList.value = response.data.data || [];
    } catch (error) {
        console.error("Failed to get searched data", error);
        searchedDataList.value = [];
    }
};

let debounceTimer = null;

watch(
    [
        selectedFurnace,
        selectedMassProd,
        statusFilter,
        vl_dateFrom,
        vl_dateTo,
        searchQuery,
    ],
    () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            currentPage.value = 1; // reset page on filter change
            viewAllSerialedLayers();
        }, 300); // 300ms buffer
    },
);

watch(currentPage, () => {
    if (!isLoading) {
        viewAllSerialedLayers();
    }
});

const nextPage = () => {
    if (isLoading) return;
    if (currentPage.value >= totalPages.value) return;

    currentPage.value++;
};

const prevPage = () => {
    if (isLoading) return;
    if (currentPage.value <= 1) return;

    currentPage.value--;
};

const deleteRow = async (serial) => {
    try {
        await axios.delete(`/api/tpmdata/${serial}/delete-chart`);
        await axios.delete(`/api/tpmdata/${serial}`);

        confirmDeleteFor.value = null;

        console.log(`[Deleted]: Serial ${serial}`);
        userSerialDeleteLogging(`deleted Serial ${serial} and its chart image`);

        // 🔁 refresh current view from server (single source of truth)
        await viewAllSerialedLayers();
    } catch (error) {
        console.error(`[Error Deleting Serial ${serial}]`, error);
        confirmDeleteFor.value = null;
    }
};

useSessionStorage("searchMassProd", searchMassProd);
useSessionStorage("searchFurnace", searchFurnace);
useSessionStorage("selectedMassProd", selectedMassProd);
useSessionStorage("selectedFurnace", selectedFurnace);
useSessionStorage("statusFilter", statusFilter);
useSessionStorage("vl_dateFrom", vl_dateFrom);
useSessionStorage("vl_dateTo", vl_dateTo);

onMounted(async () => {
    console.time("TOTAL LOAD TIME");

    await checkAuthentication();

    console.time("Initial API batch");

    await Promise.all([
        viewAllSerialedLayers(),
        getMassProdLists(),
        getFurnaceLists(),
    ]);

    console.timeEnd("Initial API batch");
    console.timeEnd("TOTAL LOAD TIME");
});
</script>

<style scoped>
@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-marquee {
    animation: marquee 120s linear infinite;
}

</style>