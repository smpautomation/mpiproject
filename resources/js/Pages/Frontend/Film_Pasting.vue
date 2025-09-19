<template>
    <Frontend>
        <div class="flex flex-row items-start gap-10 p-5 m-0 text-green-600 bg-black">
            <div v-if="state.user && state.user.access_type == 'Automation'" class="space-x-2">
                <p>Dev Controls:</p>
                <button @click="bypassValidation = true" class="p-1 bg-gray-200 rounded-lg" :class="[bypassValidation ? 'bg-yellow-400' : '']">ByPass Validation</button>
                <button @click="bypassValidation = false" class="p-1 bg-gray-300 rounded-lg"> x </button>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center min-h-screen px-4 py-4 bg-gray-100">
            <div class="flex flex-row gap-10 mt-10">
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">Film Pasting Information</h2>
                    <!-- Group: Film Pasting Date, Machine No, Total Magnet Weight -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Pasting Date<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_pasting_date" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Machine No<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.machine_no" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.total_magnet_weight" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Loader Operator, Unloader Operator, Checker Operator -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Loader Operator<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.loader_operator" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Unloader Operator<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.unloader_operator" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Checker Operator<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.checker_operator" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Film Coating Amount, Time Start, Time Finished -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Coating Amount<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_coating_amount" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Start<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.time_start" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Finished<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.time_finished" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Remarks<span class="text-red-500"> *</span></label>
                        <input v-model="filmPastingInfo.remarks" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">-</h2>
                    <!-- Group: Film Pasting Date, Machine No, Total Magnet Weight -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Type (Tb or Dy)<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_type" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Class<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_class" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-10 mt-5">
                <div class="flex flex-col gap-10 mt-5">
                    <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">H-Line Parameters</h2>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sprayer Water amount<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.h_line_parameters['sprayer_water_amount']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film paste Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.h_line_parameters['film_paste_lot_no_1']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <input v-model="filmPastingInfo.h_line_parameters['film_paste_lot_no_2']" type="number" class="w-full text-xs mt-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine Humidity %<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.h_line_parameters['film_machine_humidity']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine temperature<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.h_line_parameters['film_machine_temperature']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Dryer Temp setting<span class="text-red-500"> *</span></label>
                            <input  v-model="filmPastingInfo.h_line_parameters['dryer_temp_setting']" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-10 mt-5">
                    <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">T-Line Parameters</h2>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sprayer Water amount<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.t_line_parameters['sprayer_water_amount']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film paste Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.t_line_parameters['film_paste_lot_no_1']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <input v-model="filmPastingInfo.t_line_parameters['film_paste_lot_no_2']" type="number" class="w-full text-xs mt-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine Humidity %<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.t_line_parameters['film_machine_humidity']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine temperature<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.t_line_parameters['film_machine_temperature']" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Dryer Temp setting<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.t_line_parameters['dryer_temp_setting']" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-10 mt-5">
                    <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">
                            Setter Sand Application per Layer <span class="text-red-500">*</span>
                        </h2>

                        <div class="flex flex-col space-y-3">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <input
                                type="radio"
                                name="setter_sand"
                                value="1"
                                v-model="filmPastingInfo.is_setter_sand"
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <span>With Setter Sand</span>
                            </label>

                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <input
                                type="radio"
                                name="setter_sand"
                                value="0"
                                v-model="filmPastingInfo.is_setter_sand"
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <span>Without Setter Sand</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-20 mt-10">
                <div class="bg-gradient-to-br from-black via-gray-900 to-gray-800 border border-cyan-600 shadow-[0_10px_25px_rgba(99,102,241,0.4),0_4px_6px_rgba(0,0,0,0.4)] rounded-3xl px-6 py-6 flex items-center gap-16 ring-1 ring-teal-400 backdrop-blur-md whitespace-nowrap">
                    <!-- Glowing Spinning Cogwheel -->
                    <div class="relative flex-shrink-0 w-12 h-12">
                        <div class="absolute inset-0 rounded-full opacity-100 bg-cyan-400 blur-xl animate-pulse"></div>
                        <img
                            src="photo/cogwheel.png"
                            alt="Settings"
                            class="relative z-10 object-contain w-full h-full animate-spin"
                            style="animation-duration: 3s;"
                        />
                    </div>

                    <!-- Buttons Row -->
                    <div class="flex w-full gap-10">
                        <!-- Finalize -->
                        <button
                            @click="submit()"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-cyan-500 to-teal-600 hover:from-cyan-600 hover:to-teal-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-50"
                        >
                            SUBMIT
                        </button>

                        <!-- Cancel -->
                        <button
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50"
                        >
                            CANCEL
                        </button>

                        <!-- Clear All -->
                        <button
                            @click="clearAll()"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
                        >
                            CLEAR ALL
                        </button>
                    </div>
                </div>
            </div>
            <Modal :show="showModalSubmit" @close="showModalSubmit = false">
                <!-- Modal Content - goes directly in the slot -->
                <div class="relative">

                <!-- Header with Gradient -->
                <div class="relative px-6 py-6 bg-gradient-to-r from-cyan-600 via-teal-600 to-cyan-700">
                    <!-- Geometric Pattern Background -->
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
                    <!-- Create Icon -->
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-25 border border-white rounded-xl backdrop-blur-sm border-opacity-40">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Close Button -->
                    <button
                        @click="showModalSubmit = false"
                        class="p-2 text-white transition-all duration-200 rounded-lg hover:text-gray-200 hover:bg-white hover:bg-opacity-20 hover:scale-110"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="px-6 py-6">

                    <!-- Title -->
                    <div class="mb-6 text-center">
                    <h3 class="flex items-center justify-center mb-2 space-x-2 text-xl font-bold text-gray-900">
                        <svg class="w-5 h-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Confirm Submission</span>
                    </h3>
                    <p class="text-sm leading-relaxed text-gray-600">
                        Are you sure? Please ensure all inputs are correct before proceeding.
                    </p>
                    </div>

                    <!-- Validation Checklist -->
                    <div class="p-4 mb-6 border rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-200">
                    <h4 class="flex items-center mb-3 space-x-2 text-sm font-semibold text-gray-800">
                        <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Validation Summary</span>
                    </h4>

                    <div class="space-y-2 text-xs text-gray-700">
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-cyan-500 rounded-full"></div>
                        <span>All required fields completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-teal-500 rounded-full"></div>
                        <span>Data validation passed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-cyan-500 rounded-full"></div>
                        <span>Ready for database insertion</span>
                        </div>
                    </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">

                    <!-- Cancel Button -->
                    <button
                        @click="showModalSubmit = false"
                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 transform hover:scale-[0.98] active:scale-95 flex items-center justify-center space-x-2"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Cancel</span>
                    </button>

                    <!-- Confirm Button with Animation -->
                    <button
                        @click="saveToDatabase_1st2ndgbdp()"
                        class="group flex-1 px-4 py-3 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-700 hover:to-teal-700 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-cyan-300 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 relative overflow-hidden"
                    >
                        <!-- Shine effect -->
                        <div class="absolute inset-0 transition-transform transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-20 group-hover:translate-x-full duration-600"></div>

                        <span class="relative flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Submit Now</span>
                        </span>
                    </button>
                    </div>
                </div>

                <!-- Bottom accent line -->
                <div class="h-1 bg-gradient-to-r from-cyan-500 via-teal-400 to-teal-500"></div>
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
import Modal from '@/Components/Modal.vue';
import { useAuth } from '@/Composables/useAuth.js';
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

// General Variables --------- General Variables

// Toggle variable ---------- Toggle Variable

const bypassValidation = ref();
const showModalSubmit = ref();

// Toggle variable ---------- Toggle Variable END

const massProdLists = ref([]);

const filmPastingInfo = reactive({
    selected_mass_prod: '',
    selected_layer: 1,
    film_pasting_date: '',
    machine_no: '',
    total_magnet_weight: 0,
    loader_operator: '',
    unloader_operator: '',
    checker_operator: '',
    film_coating_amount: '',
    time_start: '',
    time_finished: '',
    remarks: '',
    film_type: '',
    film_class: '',
    h_line_parameters: {
        sprayer_water_amount: 0,
        film_paste_lot_no_1: 0,
        film_paste_lot_no_2: 0,
        film_machine_humidity: 0,
        film_machine_temperature: 0,
        dryer_temp_setting: ''
    },
    t_line_parameters: {
        sprayer_water_amount: 0,
        film_paste_lot_no_1: 0,
        film_paste_lot_no_2: 0,
        film_machine_humidity: 0,
        film_machine_temperature: 0,
        dryer_temp_setting: ''
    },
    is_setter_sand: false,
});

// General Variables --------- General Variables END

const submit = async () => {
    if(!bypassValidation.value){
        if (
            !filmPastingInfo.film_pasting_date ||
            !filmPastingInfo.machine_no ||
            !filmPastingInfo.total_magnet_weight ||
            !filmPastingInfo.loader_operator ||
            !filmPastingInfo.unloader_operator ||
            !filmPastingInfo.checker_operator ||
            !filmPastingInfo.film_coating_amount ||
            !filmPastingInfo.time_start ||
            !filmPastingInfo.time_finished ||
            !filmPastingInfo.remarks ||
            !filmPastingInfo.film_type ||
            !filmPastingInfo.film_class
        ) {
            //console.log('Coating Table: ', coatingValues.value);
            toast.error("Please fill in all required Film Pasting Info fields.");
            return;
        }else if(
            filmPastingInfo.h_line_parameters['sprayer_water_amount'] == 0 ||
            filmPastingInfo.h_line_parameters['film_paste_lot_no_1'] == 0 ||
            filmPastingInfo.h_line_parameters['film_paste_lot_no_2'] == 0 ||
            filmPastingInfo.h_line_parameters['film_machine_humidity'] == 0 ||
            filmPastingInfo.h_line_parameters['film_machine_temperature'] == 0 ||
            !filmPastingInfo.h_line_parameters['dryer_temp_setting']
        ){
            toast.error("Please fill in all required H Line Parameters fields.");
            return;
        }else if(
            filmPastingInfo.t_line_parameters['sprayer_water_amount'] == 0 ||
            filmPastingInfo.t_line_parameters['film_paste_lot_no_1'] == 0 ||
            filmPastingInfo.t_line_parameters['film_paste_lot_no_2'] == 0 ||
            filmPastingInfo.t_line_parameters['film_machine_humidity'] == 0 ||
            filmPastingInfo.t_line_parameters['film_machine_temperature'] == 0 ||
            !filmPastingInfo.t_line_parameters['dryer_temp_setting']
        ){
            toast.error("Please fill in all required T Line Parameters fields.");
            return;
        }else if(
            !filmPastingInfo.is_setter_sand
        ){
            toast.error("Please choose Setter sand application per layer.");
            return;
        }
    }

    showModalSubmit.value = true;
}

const confirm = async () => {

};


const clearAll = () => {
    Object.assign(filmPastingInfo,{
        selected_mass_prod: '',
        selected_layer: 1,
        film_pasting_date: '',
        machine_no: '',
        total_magnet_weight: 0,
        loader_operator: '',
        unloader_operator: '',
        checker_operator: '',
        film_coating_amount: '',
        time_start: '',
        time_finished: '',
        remarks: '',
        film_type: '',
        film_class: '',
        h_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        t_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        is_setter_sand: false,
    });
};


const saveToDatabase = async() => {

}


// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getMassProdLists = async () => {

}

// Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger



// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE


// APPLYING Browser Session ----------------- APPLYING Browser Session


// APPLYING Browser Session ----------------- APPLYING Browser Session

onMounted(async () => {

});

</script>

<style scoped>

    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
