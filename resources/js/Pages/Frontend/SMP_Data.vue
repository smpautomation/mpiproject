<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
            <div class="w-full overflow-x-auto">
                <div class="flex items-center justify-center w-full p-4 mb-4 rounded-lg shadow-md bg-gradient-to-r from-cyan-600 to-teal-600">
                <p class="text-lg font-bold tracking-wide text-white">
                    SMP DATA for <span class="px-2 py-1 bg-white rounded-md shadow-sm text-cyan-700">{{ redirectedFurnace }} {{ redirectedMassPro }}</span> Mass Production
                </p>
                </div>
                <div>
                    <table class="w-full border border-collapse border-gray-300 rounded-lg shadow-sm table-auto">
                        <thead class="text-sm text-white uppercase bg-gradient-to-r from-cyan-600 to-teal-600">
                            <tr class="text-[10px]">
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
                                <th colspan="4" class="px-1 text-center border border-gray-300">
                                    {{ filmPastingFormat ? 'Film Pasting Information' : 'Coating Amount (μ/mm²)' }}
                                </th>
                                <th colspan="4" class="px-1 text-center border border-gray-300">iHc</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Remarks</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Status</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">HT Trouble</th>
                                <th rowspan="2" class="px-1 text-center border border-gray-300">Special <br> Instruction</th>
                            </tr>
                            <tr class="text-[10px]">
                                <template v-if="!filmPastingFormat">
                                    <th class="px-1 text-center border border-gray-300">Target</th>
                                    <th class="px-1 text-center border border-gray-300">Max</th>
                                    <th class="px-1 text-center border border-gray-300">Min</th>
                                    <th class="px-1 text-center border border-gray-300">Ave</th>
                                </template>

                                <template v-else>
                                    <th class="px-1 text-center border border-gray-300">Film Coating Amount</th>
                                    <th class="px-1 text-center border border-gray-300">Film type (Tb or Dy)</th>
                                    <th class="px-1 text-center border border-gray-300">Film Class</th>
                                    <th class="px-1 text-center border border-gray-300"></th>
                                </template>
                                <th class="px-1 text-center border border-gray-300">Target</th>
                                <th class="px-1 text-center border border-gray-300">Max</th>
                                <th class="px-1 text-center border border-gray-300">Min</th>
                                <th class="px-1 text-center border border-gray-300">Ave</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-center text-gray-700 divide-y divide-gray-200 whitespace-nowrap">
                            <tr
                                v-for="(layer, index) in massProdData.layersData"
                                :key="index"
                                class="transition-colors hover:bg-gray-50"
                            >
                                <td class="px-1 border border-gray-300">{{ layer.MPI_Date }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Pulse_Tracer_Machine }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Furnace_Cycle_No }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Mass_Production }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Cycle_Pattern }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Model }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Lot_No }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Layer }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Batches }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Total_Lot_Qty }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Date }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.M_C }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Magnet_Weight }}</td>

                                <!-- Conditionally render Coating or Film Pasting fields -->
                                <template v-if="!filmPastingFormat">
                                    <td class="px-1 border border-gray-300">{{ layer.Coating_Target }}</td>
                                    <td class="px-1 border border-gray-300">{{ layer.Coating_Max }}</td>
                                    <td class="px-1 border border-gray-300">{{ layer.Coating_Min }}</td>
                                    <td class="px-1 border border-gray-300">{{ layer.Coating_Ave }}</td>
                                </template>
                                <template v-else>
                                    <td class="px-1 border border-gray-300">{{ layer.Film_Coating_Amount }}</td>
                                    <td class="px-1 border border-gray-300">{{ layer.Film_Type }}</td>
                                    <td class="px-1 border border-gray-300">{{ layer.Film_Class }}</td>
                                    <td class="px-1 border border-gray-300">{{ layer.blank }}</td>
                                </template>

                                <td class="px-1 border border-gray-300">{{ layer.iHc_Target }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.iHc_Max }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.iHc_Min }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.iHc_Ave }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Remarks }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Status }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.HT_Trouble }}</td>
                                <td class="px-1 border border-gray-300">{{ layer.Special_Instruction }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 mt-10 bg-white border shadow-md border-cyan-100 rounded-xl">
                    <div class="pb-3 mb-4 border-b border-teal-100">
                        <h3 class="flex items-center text-base font-semibold text-gray-800">
                            <span class="w-1 h-5 mr-2 rounded-full bg-gradient-to-b from-teal-500 to-cyan-500"></span>
                            SMP Data Entry
                        </h3>
                    </div>

                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
                        <label class="flex flex-col w-full sm:w-1/3">
                        <span class="mb-1 text-xs font-medium text-teal-600">Remarks:</span>
                        <input
                            v-model="smp_data_remarks"
                            type="text"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-50
                            focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white
                            placeholder-gray-400 transition-all"
                            placeholder="Enter remarks"
                        />
                        </label>

                        <label class="flex flex-col w-full sm:w-1/3">
                        <span class="mb-1 text-xs font-medium text-teal-600">Special Instruction:</span>
                        <input
                            v-model="smp_data_special_instructions"
                            type="text"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-50
                            focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white
                            placeholder-gray-400 transition-all"
                            placeholder="Enter instructions"
                        />
                        </label>

                        <label class="flex flex-col w-full sm:w-1/4">
                        <span class="mb-1 text-xs font-medium text-teal-600">Layer:</span>
                        <select
                            v-model="smp_data_selectedLayer"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-50
                                focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white
                                transition-all cursor-pointer"
                        >
                            <option value="" disabled>Select Layer</option>
                            <option v-for="items in layers" :key="items" :value="items">
                            {{ items }}
                            </option>
                        </select>
                        </label>

                        <button
                        @click="confirmDataSubmit()"
                        class="mt-3 sm:mt-5 w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-white
                        bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg
                        hover:from-teal-600 hover:to-cyan-600 active:scale-95 transition-all shadow-md
                        hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2"
                        >
                        Submit
                        </button>
                    </div>
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

                <Modal :show="showConfirmation" @close="showConfirmation = false">
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
                            @click="showConfirmation = false"
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
                            @click="showConfirmation = false"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 transform hover:scale-[0.98] active:scale-95 flex items-center justify-center space-x-2"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <span>Cancel</span>
                        </button>

                        <!-- Confirm Button with Animation -->
                        <button
                            @click="saveToDatabase()"
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
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import * as XLSX from 'xlsx';
import Modal from '@/Components/Modal.vue';
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

//Toggle variables
const showConfirmation = ref(false);
const filmPastingFormat = ref(false);
//Toggle variables end

const smp_data_remarks = ref('');
const smp_data_special_instructions = ref('');
const smp_data_selectedLayer = ref('');
const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);
const redirectedMassPro = ref();
const redirectedFurnace = ref();
const massProdData = ref([]);
const controlSheet_props = defineProps({
    massProd: String,
    furnace: String
});
redirectedMassPro.value = controlSheet_props.massProd;
redirectedFurnace.value = controlSheet_props.furnace;
console.log("Redirected Mass Pro: ", redirectedMassPro.value);

//Fetch zone -------------- Fetch zone ---------------- Fetch zone

const getMassProdData = async () => { //Function for getting the current selected Massprod
    if (!redirectedMassPro.value || !redirectedFurnace.value){
        return; // skip if empty
    }
    try {
        const response = await axios.get(`/api/mass-production/${redirectedFurnace.value}/${redirectedMassPro.value}/smp-data`);
        //console.log('Mass Production Data:', response.data);
        massProdData.value = response.data;
        if(massProdData.value.format_type == 'Film Pasting'){
            filmPastingFormat.value = true;
        }
    } catch (error) {
        console.error('Error fetching mass production data:', error);
        toast.error('Failed to load mass production data.');
    }
}

//Fetch zone -------------- Fetch zone ---------------- Fetch zone End

const clearAll = async() => {
    smp_data_selectedLayer.value = null;
    smp_data_remarks.value = null;
    smp_data_special_instructions.value = null;
}

const confirmDataSubmit = async () => {
    if (!smp_data_remarks.value || !smp_data_selectedLayer.value || !smp_data_special_instructions.value){
        toast.warning('Please fill all the fields');
        return; // skip if empty
    }
    showConfirmation.value = true;
}

const saveToDatabase = async () => {

    const payload = {
        furnace: redirectedFurnace.value,
        mass_prod: redirectedMassPro.value,
        layer: smp_data_selectedLayer.value,
        remarks: smp_data_remarks.value,
        special_instruction: smp_data_special_instructions.value
    }

    try{
        const response = await axios.post(`/api/smp-data`,payload);
        toast.success('Saved Successfully');
        console.log("Saved Successfully: ", response.data);
    }catch(error){
        console.error('Failed to save smp_data',error);
        toast.error('SMP Data failed to save.', error);
    }finally{
        showConfirmation.value = false;
        await getMassProdData();
        await clearAll();
    }
}

const exportToExcel = () => {
    if (!massProdData.value?.layersData || Object.keys(massProdData.value.layersData).length === 0) {
        toast.error('No data available for export.');
        return;
    }

    const sheetData = [];

    // Header rows
    sheetData.push([
        'MPI Date (E-mail sending)',
        'Pulse Tracer Machine',
        'Furnace Cycle No.',
        'Mass Production',
        'Cycle Pattern',
        'Model',
        'Lot No.',
        'Layer',
        'Batches',
        'Total Lot Qty',
        'Date',
        'M/C',
        'Magnet Weight (Kg)',
        'Coating Target',
        'Coating Max',
        'Coating Min',
        'Coating Ave',
        'iHc Target',
        'iHc Max',
        'iHc Min',
        'iHc Ave',
        'Remarks',
        'Status',
        'HT Trouble',
        'Special Instruction'
    ]);

    // Rows
    for (const [key, layer] of Object.entries(massProdData.value.layersData)) {
        sheetData.push([
            layer.MPI_Date ?? '',
            layer.Pulse_Tracer_Machine ?? '',
            layer.Furnace_Cycle_No ?? '',
            layer.Mass_Production ?? '',
            layer.Cycle_Pattern ?? '',
            layer.Model ?? '',
            layer.Lot_No ?? '',
            layer.Layer ?? '',
            layer.Batches ?? '',
            layer.Total_Lot_Qty ?? '',
            layer.Date ?? '',
            layer.M_C ?? '',
            layer.Magnet_Weight ?? '',
            layer.Coating_Target ?? '',
            layer.Coating_Max ?? '',
            layer.Coating_Min ?? '',
            layer.Coating_Ave ?? '',
            layer.iHc_Target ?? '',
            layer.iHc_Max ?? '',
            layer.iHc_Min ?? '',
            layer.iHc_Ave ?? '',
            layer.Remarks ?? '',
            layer.Status ?? '',
            layer.HT_Trouble ?? '',
            layer.Special_Instruction ?? ''
        ]);
    }

    // Generate sheet + workbook
    const ws = XLSX.utils.aoa_to_sheet(sheetData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'SMP Data');

    // Set some basic column widths for readability
    const colWidths = sheetData[0].map(() => ({ wch: 18 }));
    ws['!cols'] = colWidths;

    XLSX.writeFile(wb, `${redirectedMassPro.value || 'SMP_DATA'}_SMP_Data.xlsx`);
    toast.success('Excel file generated successfully.');
};

onMounted(async()=>{
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    await getMassProdData();
    console.log('MassProdData:', massProdData.value);
});

</script>
