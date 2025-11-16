<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center min-h-screen px-4 py-2 bg-gray-100">
            <div class="flex flex-row space-x-8 whitespace-nowrap">
                <div class="w-full max-w-4xl mb-12">
                    <div class="px-8 py-6 border border-gray-200 shadow-lg bg-gradient-to-br from-gray-50 to-white rounded-2xl">
                        <!-- Header -->
                        <h2 class="pb-3 mb-5 text-xl font-semibold tracking-wide text-gray-800 border-b border-gray-200">
                            Options
                        </h2>

                        <!-- Body -->
                        <div class="flex items-center space-x-4">
                        <!-- Icon -->
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-full shadow-inner">
                            <svg
                                class="w-6 h-6 text-teal-600"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"
                                />
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <p class="mb-4 text-sm leading-relaxed text-gray-600">
                            This button allows data to be fetched automatically for
                            <span class="font-semibold text-gray-800">1st GBDP</span>. Use this
                            option when you want the system <br> to retrieve relevant information
                            without manual input.
                            </p>
                            <button
                                @click="autoFetch"
                                class="px-5 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-300 rounded-lg shadow-md bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-400"
                            >
                                Auto Fetch
                            </button>
                            <input v-model="selectedFurnace_fetch" type="text" @input="selectedFurnace_fetch = selectedFurnace_fetch.toUpperCase()" placeholder="e.g. K-40 (furnace)" class="ml-5 w-[8rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <input v-model="selectedMassProd_fetch" type="text" @input="selectedMassProd_fetch = selectedMassProd_fetch.toUpperCase()" placeholder="e.g. 101ST (mass prod)" class="ml-5 w-[9.5rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Header Selection Section -->
                <div class="w-full max-w-4xl mb-12">
                    <div class="px-6 py-6 bg-white border border-gray-200 shadow-xl rounded-2xl">
                        <!-- Header -->
                        <div class="mb-5">
                        <h2 class="text-xl font-bold tracking-wide text-gray-800">
                            Heat Treatment Information
                        </h2>
                        <!-- Left-aligned accent bar under the title -->
                        <div class="mt-1 h-1 w-40 rounded-full bg-gradient-to-r from-teal-500 to-cyan-400 shadow-[0_0_6px_rgba(34,211,238,0.6)]"></div>
                        </div>
                        <!-- Body -->
                        <div class="flex flex-row space-x-5">
                            <div>
                                <label class="block mb-1 text-xs font-semibold text-gray-700">
                                    Selected Furnace
                                </label>
                                <span class="px-2 py-1 text-sm font-semibold text-teal-700 border border-teal-200 rounded-md shadow-sm bg-teal-50">
                                    {{ selectedFurnace }}
                                </span>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-semibold text-gray-700">
                                    Selected Mass Prod
                                </label>
                                <span class="px-2 py-1 text-sm font-semibold text-teal-700 border border-teal-200 rounded-md shadow-sm bg-teal-50">
                                    {{ selectedMassProd }}
                                </span>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-semibold text-gray-700">
                                    Selected Layer
                                </label>
                                <span class="px-2 py-1 text-sm font-semibold text-teal-700 border border-teal-200 rounded-md shadow-sm bg-teal-50">
                                    {{ selectedLayer }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <!-- GBDP Sections -->
            <div class="flex flex-col w-full max-w-6xl gap-8 md:flex-row">
                <!-- 1ST GBDP -->
                <div class="flex-1 px-6 py-6 bg-white border border-gray-200 shadow-xl rounded-2xl">
                    <h2 class="pb-2 mb-4 text-lg font-bold text-gray-800 border-b">1ST GBDP</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Left Column -->
                        <div class="space-y-3">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Furnace Machine <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.furnaceNo" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Batch Cycle No</label>
                                <input v-model="gbdp_1st.batchCycleNo" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Date Start <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.dateStart" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Time Start <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.timeStart" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Cycle Pattern <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.cyclePattern" type="text" @input="gbdp_1st.cyclePattern = gbdp_1st.cyclePattern.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="space-y-3">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Cycle No <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.cycleNo" type="text" @input="gbdp_1st.cycleNo = gbdp_1st.cycleNo.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Pattern No <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.patternNo" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Date Finish <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.dateFinish" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Time Finish <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.timeFinish" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Current Pattern <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.currentPattern" type="text" @input="gbdp_1st.currentPattern = gbdp_1st.currentPattern.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2ND GBDP -->
                <div class="flex-1 px-6 py-6 bg-white border border-gray-200 shadow-xl rounded-2xl">
                    <h2 class="pb-2 mb-4 text-lg font-bold text-gray-800 border-b">2ND GBDP</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Left Column -->
                        <div class="space-y-3">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Furnace Machine <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.furnaceNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Batch Cycle No</label>
                                <input v-model="selectedMassProd" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Date Start <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.dateStart" type="date" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Time Start <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.timeStart" type="time" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Cycle Pattern <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.cyclePattern" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="space-y-3">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Cycle No <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.cycleNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Pattern No <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.patternNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Date Finish <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.dateFinish" type="date" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Time Finish <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.timeFinish" type="time" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Current Pattern <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_2nd.currentPattern" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="mt-10 mx-auto bg-gradient-to-br from-slate-800 via-slate-900 to-black border border-teal-500 shadow-[0_10px_25px_rgba(20,184,166,0.4),0_4px_6px_rgba(0,0,0,0.4)] rounded-3xl px-6 py-10 flex flex-row items-center justify-between space-x-10 ring-1 ring-teal-400 backdrop-blur-md"
            >
                <!-- Buttons container (row layout) -->
                <div class="flex flex-row items-center space-x-4">
                    <!-- Submit -->
                    <button
                        @click="finalize"
                        class="px-4 py-2 text-sm font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-white hover:text-black hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-teal-400 focus:ring-opacity-50"
                    >
                        SUBMIT
                    </button>

                    <!-- Cancel -->
                    <button
                        @click="Inertia.visit('heat_treatment'), clearAll()"
                        class="px-4 py-2 text-sm font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-slate-500 to-slate-700 hover:from-slate-700 hover:to-white hover:text-black hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-slate-400 focus:ring-opacity-50"
                    >
                        CANCEL
                    </button>

                    <!-- Clear All -->
                    <button
                        @click="clearAll()"
                        class="px-4 py-2 text-sm font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-700 hover:from-purple-700 hover:to-white hover:text-black hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-fuchsia-400 focus:ring-opacity-50"
                    >
                        CLEAR ALL
                    </button>
                </div>
            </div>
            <Modal :show="showModalCreate" @close="showModalCreate = false">
                <div class="relative">
                    <!-- Header with Danger Gradient -->
                    <div class="relative px-6 py-6 bg-gradient-to-r from-red-600 via-orange-600 to-red-700">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="hexagon" width="12" height="12" patternUnits="userSpaceOnUse">
                            <polygon points="6,1 11,4.5 11,9.5 6,13 1,9.5 1,4.5" fill="none" stroke="currentColor" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#hexagon)" />
                        </svg>
                    </div>
                    <!-- Header Content -->
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-25 border border-white rounded-xl backdrop-blur-sm border-opacity-40 animate-pulse">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.584c.75 1.334-.213 2.983-1.742 2.983H3.48c-1.53 0-2.492-1.65-1.742-2.983L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        </div>

                        <button @click="showModalCreate = false"
                        class="p-2 text-white transition-all duration-200 rounded-lg hover:text-gray-200 hover:bg-white hover:bg-opacity-20 hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        </button>
                    </div>
                    </div>
                    <!-- Main Content -->
                    <div class="px-6 py-6 border border-red-200 rounded-lg shadow-inner bg-red-50">
                    <div class="mb-6 text-center">
                        <h3 class="flex items-center justify-center mb-2 space-x-2 text-xl font-bold text-red-700">
                        <svg class="w-5 h-5 text-red-600 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>⚠️ Confirm Submission Carefully</span>
                        </h3>
                        <p class="text-sm font-semibold text-red-700">
                        This action is <span class="underline">irreversible</span>! Please double-check all inputs.
                        </p>
                    </div>
                    <!-- Validation Checklist -->
                    <div class="p-4 mb-6 bg-red-100 border border-red-300 rounded-lg">
                        <h4 class="flex items-center mb-3 space-x-2 text-sm font-semibold text-red-800">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Checklist</span>
                        </h4>

                        <div class="space-y-2 text-xs text-red-800">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>All required fields completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>Data validated successfully</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>Submission is permanent. Proceed only if confident!</span>
                        </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button
                        @click="showModalCreate = false"
                        class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-all duration-200 transform hover:scale-[0.98] flex items-center justify-center space-x-2">
                        Cancel
                        </button>

                        <button
                        @click="saveToDatabase()"
                        class="group flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
                        <div class="absolute inset-0 transition-transform transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-20 group-hover:translate-x-full duration-600"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4 transition-all duration-300 group-hover:rotate-12 group-hover:scale-110 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>⚠️ Submit Now</span>
                        </span>
                        </button>
                    </div>
                    </div>
                    <!-- Bottom accent line -->
                    <div class="h-1 bg-gradient-to-r from-red-500 via-orange-400 to-red-500"></div>
                </div>
            </Modal>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import Modal from '@/Components/Modal.vue'
import { useAuth } from '@/Composables/useAuth.js'
import { useToast } from 'vue-toast-notification';
const toast = useToast();

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

const userManageLogging = async (logEvent) => {
    try{
        const responseUserLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Heat Treatment',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userManageLogging post request failed: ',error);
    }
}

// Utility: Save and load to sessionStorage
function useSessionStorage(key, state) {
  // Load existing session value
  const saved = sessionStorage.getItem(key)
  if (saved !== null) {
    try {
      const parsed = JSON.parse(saved)
      if (typeof state === 'object' && 'value' in state) {
        state.value = parsed
      } else {
        Object.assign(state, parsed)
      }
    } catch {
      /* ignore parse errors */
    }
  }

  // Watch and persist changes
  watch(
    state,
    (val) => {
      sessionStorage.setItem(key, JSON.stringify(val))
    },
    { deep: true }
  )
}

//Dev Controls ----------------- Allow Commands
const bypassValidation = ref(false);
const overwriteHeatTreatment = ref(false);
//Dev Controls ----------------- Allow Commands

const heatTreatmentInformationDetected = ref(false);

const showModalCreate = ref(false);
const selectedLayer_fetch = ref();
const selectedMassProd_fetch = ref();
const selectedFurnace_fetch = ref();
const selectedFurnace = ref();
const selectedMassProd = ref();
const fetchedMassProdData = ref(null);
const selectedLayer = ref();
const graph_patterns = ref([]);
const furnace_lists = ref([]);
const massProd_names = ref([]);
const firstSecondGBDP_models = ref([]);

// HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!!

const gbdp_1st = reactive({
    batchCycleNo: '',
    furnaceNo: '',
    cycleNo: '',
    patternNo: '',
    cyclePattern: '',
    currentPattern: '',
    dateStart: '',
    timeStart: '',
    dateFinish: '',
    timeFinish: '',
});

const gbdp_2nd = reactive({
    furnaceNo: '',
    cycleNo: '',
    patternNo: '',
    cyclePattern: '',
    currentPattern: '',
    dateStart: '',
    timeStart: '',
    dateFinish: '',
    timeFinish: '',
});

// HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! END

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getFurnaceLists = async() => {
    try{
        const response = await axios.get('/api/furnace-data/');
        const furnaceData = response.data;
        furnace_lists.value = furnaceData.map(item => item.furnace_name);
        console.log("Furnace Lists: ", furnace_lists.value);
    }catch(error){
        console.error('Failed to fetch furnace data lists: ',error);
        toast.error('Furnace Data List error.');
    }
}

const getGraphPatterns = async () => {
    try{
        const response = await axios.get('/api/ht-graph-patterns/');
        const patternsList = response.data;
        graph_patterns.value = patternsList.map(item => item.pattern_no);
        console.log('Graph Patterns: ',graph_patterns.value);
    }catch(error){
        console.error('Error fetching model names', error);
        toast.error('Failed to get graph patterns.');
    }
}

const getMassProdLists = async () => {
    try{
        const response = await axios.get('/api/mass-production/');
        const massProdList = response.data;
        massProd_names.value = massProdList.map(item => item.mass_prod);
        //console.log("List of mass prods: ",massProd_names.value);
    }catch(error){
        console.error('Error fetching mass prod lists',error);
        toast.error('Failed to get the mass prod lists api error');
    }
}

const get1st2ndGBDPModels = async () => {
    try {
        const response = await axios.get(`/api/second-gbdp-models`);
        const models = response.data;

        // Extract only the model_name
        const modelNames = models.map(model => model.model_name);
        firstSecondGBDP_models.value = modelNames;
        //console.log("1st & 2nd GBDP model names: ", modelNames);
    } catch (error) {
        console.error('Failed to get 1st & 2nd GBDP Models: ', error);
    }
}

const getCurrentMassProdData = async () => {
    try{
        const response = await axios.get(`/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}`);
        const massProdData = response.data;
        if (!massProdData) {
            toast.error(`No data found for Mass Production: ${selectedMassProd.value}`);
            return;
        }
        gbdp_2nd.furnaceNo = massProdData.machine_no;
        gbdp_2nd.batchCycleNo = massProdData.mass_prod;
        gbdp_2nd.cycleNo = massProdData.cycle_no;
        gbdp_2nd.patternNo = massProdData.pattern_no;
        gbdp_2nd.cyclePattern = massProdData.cycle_pattern;
        gbdp_2nd.currentPattern = massProdData.current_pattern;
        gbdp_2nd.dateStart = massProdData.date_start;
        gbdp_2nd.timeStart = massProdData.time_start;
        gbdp_2nd.dateFinish = massProdData.date_finished;
        gbdp_2nd.timeFinish = massProdData.time_finished;

        toast.success(`Data fetched successfully for ${selectedMassProd.value} Mass Production`);
    }catch(error){
        console.error('Error fetching current mass prod data',error);
        toast.error('Failed to get current mass prod data api error');
    }
}

const props = defineProps(['furnace','massProd', 'layer']);
selectedFurnace.value = props.furnace;
selectedMassProd.value = props.massProd;
selectedLayer.value = props.layer;

// Trigger Based Fetching below ----- Trigger Based Fetching below ----- Trigger Based Fetching below

const autoFetch = async () => {
    if(!selectedFurnace_fetch.value){
        toast.error("Please enter Mass Production name to fetch data.");
        return;
    }
    if(!selectedMassProd_fetch.value){
        toast.error("Please enter Mass Production name to fetch data.");
        return;
    }
    try{
        const response = await axios.get(`/api/mass-production/${selectedFurnace_fetch.value}/${selectedMassProd_fetch.value}`);
        const massProdData = response.data;
        if (!massProdData) {
            toast.error(`No data found for Mass Production: ${selectedMassProd_fetch.value}`);
            return;
        }
        gbdp_1st.furnaceNo = massProdData.machine_no;
        gbdp_1st.batchCycleNo = massProdData.mass_prod;
        gbdp_1st.cycleNo = massProdData.cycle_no;
        gbdp_1st.patternNo = massProdData.pattern_no;
        gbdp_1st.cyclePattern = massProdData.cycle_pattern;
        gbdp_1st.currentPattern = massProdData.current_pattern;
        gbdp_1st.dateStart = massProdData.date_start;
        gbdp_1st.timeStart = massProdData.time_start;
        gbdp_1st.dateFinish = massProdData.date_finished;
        gbdp_1st.timeFinish = massProdData.time_finished;

        toast.success(`Data fetched successfully for ${selectedMassProd_fetch.value} Mass Production`);
        //console.log('Fetched Mass Production Data: ', fetchedMassProdData.value);
    }catch(error){
        console.error("Error fetching mass prod data:", error);

        if (error.response) {
            if (error.response.status === 404) {
                toast.error(`Mass Production "${selectedMassProd_fetch.value}" does not exists.`);
            } else {
                toast.error(`Server error (${error.response.status}): Unable to fetch data.`);
            }
        } else if (error.request) {
            toast.error("No response from server. Please check your connection.");
        } else {
            toast.error("Unexpected error occurred while fetching data.");
        }
    }
}

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE END

const clearAll = () => {

    Object.keys(gbdp_1st).forEach(key => {
        gbdp_1st[key] = typeof gbdp_1st[key] === 'number' ? 0 : '';
    });

}

const cancelProceed = () => {
    showModalCreate.value = false;
}

const finalize = () => {
    if (!bypassValidation.value) {
        // validate HTI fields only if not detected already
        if (!heatTreatmentInformationDetected.value) {
            if (
                !gbdp_1st.cycleNo || !gbdp_1st.patternNo ||
                !gbdp_1st.cyclePattern || !gbdp_1st.currentPattern || !gbdp_1st.dateStart ||
                !gbdp_1st.timeStart || !gbdp_1st.dateFinish || !gbdp_1st.timeFinish
            ) {
                toast.error("Please fill in all required Heat Treatment Info fields.");
                return;
            }
        }
    }

    showModalCreate.value = true;
}

const saveToDatabase = async () => {

    // Base payload
    const dataPayload = {
        furnace: selectedFurnace.value,
        mass_prod: selectedMassProd.value,
        layer: selectedLayer.value,
        gbdp_1st: {
            batch_cycle_no: gbdp_1st.batchCycleNo,
            furnace_machine: gbdp_1st.furnaceNo,
            cycle_no: gbdp_1st.cycleNo,
            pattern_no: gbdp_1st.patternNo,
            cycle_pattern: gbdp_1st.cyclePattern,
            current_pattern: gbdp_1st.currentPattern,
            date_start: gbdp_1st.dateStart,
            time_start: gbdp_1st.timeStart,
            date_finished: gbdp_1st.dateFinish,
            time_finished: gbdp_1st.timeFinish,
            layer: selectedLayer_fetch.value
        },
        gbdp_2nd: {
            batch_cycle_no: selectedMassProd.value,
            furnace_machine: gbdp_2nd.furnaceNo,
            cycle_no: gbdp_2nd.cycleNo,
            pattern_no: gbdp_2nd.patternNo,
            cycle_pattern: gbdp_2nd.cyclePattern,
            current_pattern: gbdp_2nd.currentPattern,
            date_start: gbdp_2nd.dateStart,
            time_start: gbdp_2nd.timeStart,
            date_finished: gbdp_2nd.dateFinish,
            time_finished: gbdp_2nd.timeFinish,
            layer: selectedLayer.value
        }
    };

    console.log('Data Payload:', dataPayload);

    try {
        const response = await axios.post(`/api/second_heat_treatment`, dataPayload);
        console.log('Data saved successfully:', response.data);
        toast.success('Data saved successfully!');
        showModalCreate.value = false;
        await userManageLogging('created 2nd Gbdp HT Data for Mass Prod: '+ selectedMassProd.value +' Layer: ' + selectedLayer.value + ' successfully.');
        clearAll(); // Clear all fields after successful save
    } catch (error) {
        console.error('Error saving data:', error);
        toast.error('Failed to save data. Please try again.');
    } finally {
        await updateFormatType();
    }
};

const updateFormatType = async () => { // Update format type of Mass Productions Table
    const layerKey = selectedLayer.value === '9.5' ? 'layer_9_5_format_type' : `layer_${selectedLayer.value}_format_type`;

    const dataPayload = {
        furnace: selectedFurnace.value,
        mass_prod: selectedMassProd.value,
        [layerKey]: '1st and 2nd Gbdp',
    }

    try{
        const responseUpdate = await axios.patch(`/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}`, dataPayload);
        console.log('Response Update: ', responseUpdate.data);
    }catch(error){
        console.log('Failed to update format type');
    }
}

// APPLYING Browser Session ----------------- APPLYING Browser Session

useSessionStorage('gbdp_1st',gbdp_1st);
useSessionStorage('gbdp_2nd',gbdp_2nd);

// APPLYING Browser Session ----------------- APPLYING Browser Session

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    //await getMassProdLists();
    await getFurnaceLists();
    await getGraphPatterns();
    await getCurrentMassProdData();
    //await get1st2ndGBDPModels();
});

</script>

<style scoped>
input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.finalize-btn {
    position: relative;
    overflow: hidden;
    width: 100%;
    max-width: 20rem;
    margin: 0 auto;
    display: block;
    background: linear-gradient(to right, #16a34a, #22c55e);
    color: white;
    font-weight: 800;
    font-size: 1.25rem;
    border-radius: 0.75rem;
    padding: 1rem 0;
    box-shadow: 0 10px 15px -3px rgba(34,197,94,0.5), 0 4px 6px -2px rgba(34,197,94,0.25);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    padding: 10px;
}
.finalize-btn:hover {
    transform: scale(1.05);
    background: linear-gradient(to right, #15803d, #16a34a);
    box-shadow: 0 15px 25px -5px rgba(22,163,74,0.6), 0 6px 8px -3px rgba(22,163,74,0.3);
}
.finalize-btn:active {
    transform: scale(0.95);
}
.finalize-btn:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(34,197,94,0.5);
}
.text {
    position: relative;
    z-index: 10;
}
.shine {
    position: absolute;
    inset: 0;
    background: white;
    opacity: 0.1;
    border-radius: 0.75rem;
    filter: blur(8px);
    animation: shine 2s ease-in-out infinite;
    z-index: 5;
}

</style>
