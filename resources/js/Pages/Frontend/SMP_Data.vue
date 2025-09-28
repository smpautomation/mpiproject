<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
            <div class="w-full overflow-x-auto">
                <div class="flex items-center justify-center w-full p-4 mb-4 bg-gradient-to-r from-cyan-600 to-teal-600 rounded-lg shadow-md">
  <p class="text-lg font-bold text-white tracking-wide">
    SMP DATA for <span class="px-2 py-1 bg-white text-cyan-700 rounded-md shadow-sm">{{ redirectedMassPro }}</span> Mass Production
  </p>
</div>
                <div>
                    <table class="table-auto w-full border border-gray-300 border-collapse rounded-lg shadow-sm">
                        <thead class="bg-gradient-to-r from-cyan-600 to-teal-600 text-white text-sm uppercase">
                            <tr class="text-[10px]">
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Layer</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">MPI Date <br> (E-mail sending)</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Pulse Tracer <br> Machine</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Furnace <br> Cycle No.</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Mass <br> Production</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Cycle <br> Pattern</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Model</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Lot No.</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Layer</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Batches</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Total Lot <br> Qty</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Date</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">M/C</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Magnet Weight <br> (Kg)</th>
                                <th colspan="4" class="px-1 text-center border border-gray-300">Coating Amount <br> (μ/mm²)</th>
                                <th colspan="4" class="px-1 text-center border border-gray-300">iHc</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Remarks</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Status</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">HT Trouble</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Special <br> Instruction</th>
                            </tr>
                            <tr class="text-[10px]">
                                <th class="px-1 text-center border border-gray-300">Target</th>
                                <th class="px-1 text-center border border-gray-300">Max</th>
                                <th class="px-1 text-center border border-gray-300">Min</th>
                                <th class="px-1 text-center border border-gray-300">Ave</th>
                                <th class="px-1 text-center border border-gray-300">Target</th>
                                <th class="px-1 text-center border border-gray-300">Max</th>
                                <th class="px-1 text-center border border-gray-300">Min</th>
                                <th class="px-1 text-center border border-gray-300">Ave</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200 text-center">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-1 border border-gray-300">1</td>
                                <td class="px-1 border border-gray-300">21-Apr</td>
                                <td class="px-1 border border-gray-300">PG</td>
                                <td class="px-1 border border-gray-300">K40-0581</td>
                                <td class="px-1 border border-gray-300">541ST</td>
                                <td class="px-1 border border-gray-300">31</td>
                                <td class="px-1 border border-gray-300">TIC-0755G</td>
                                <td class="px-1 border border-gray-300">1711-2</td>
                                <td class="px-1 border border-gray-300">1st</td>
                                <td class="px-1 border border-gray-300">10</td>
                                <td class="px-1 border border-gray-300">14391</td>
                                <td class="px-1 border border-gray-300">11-Apr</td>
                                <td class="px-1 border border-gray-300">C-33</td>
                                <td class="px-1 border border-gray-300">144.80</td>
                                <td class="px-1 border border-gray-300">70</td>
                                <td class="px-1 border border-gray-300">79.72</td>
                                <td class="px-1 border border-gray-300">70.34</td>
                                <td class="px-1 border border-gray-300">75.16</td>
                                <td class="px-1 border border-gray-300">19500</td>
                                <td class="px-1 border border-gray-300">20116</td>
                                <td class="px-1 border border-gray-300">19187</td>
                                <td class="px-1 border border-gray-300">21202</td>
                                <td class="px-1 border border-gray-300">test</td>
                                <td class="px-1 border border-gray-300">REJECT</td>
                                <td class="px-1 border border-gray-300">YES</td>
                                <td class="px-1 border border-gray-300">test</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-row justify-end mt-3">
                    <button
                        @click="exportToExcel"
                        class="flex items-center gap-2 px-4 py-2 font-semibold text-white transition duration-150 ease-in-out bg-green-600 rounded shadow-sm hover:bg-green-700 active:bg-green-800 hover:shadow-md"
                    >
                        <img
                            src="/photo/download.png"
                            alt="Download"
                            style="height: 20px; width: 20px; filter: brightness(0) invert(1);"
                        >
                        Download Excel
                    </button>
                </div>
                <div class="flex justify-start mt-4">
                    <button
                        @click="Inertia.visit('/massprod')"
                        class="flex items-center gap-2 px-4 py-2 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-100 hover:text-black"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Mass Production Lists
                    </button>
                </div>

            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import * as XLSX from 'xlsx';
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
        console.error('Error checking authentication: ', error);
        Inertia.visit('/'); // Redirect on error
        return false; // Indicate not authenticated
    }
};

const redirectedMassPro = ref();
const controlSheet_props = defineProps({
    massProd: String
});
redirectedMassPro.value = controlSheet_props.massProd;
console.log("Redirected Mass Pro: ", redirectedMassPro.value);

</script>
