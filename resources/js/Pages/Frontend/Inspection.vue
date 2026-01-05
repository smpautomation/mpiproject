<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">
            <div v-show="showMainUI" class="flex flex-col items-center justify-center">
                <div>
                    <button @click="insertBtn" class="relative flex px-6 py-3 m-10 overflow-hidden font-semibold text-white transition duration-300 ease-in-out bg-green-600 shadow-md rounded-xl hover:brightness-110 hover:shadow-lg active:scale-95">
                        Insert New Record
                    </button>
                </div>
                <div class="flex justify-start w-11/12 mx-auto mb-3 md:w-3/4">
                    <input
                        v-model="searchModel"
                        type="text"
                        @input="searchModel = searchModel.toUpperCase()"
                        placeholder="Search model..."
                        class="w-64 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                    />
                </div>
                <div v-if="inspectionDataList.length > 0" class="flex items-center justify-center mb-10 bg-gray-100">
                    <table class="w-11/12 mx-auto border border-collapse border-gray-300 table-auto md:w-3/4">
                        <thead class="text-white bg-gradient-to-l from-blue-600 to-green-600 ">
                            <tr class="text-sm">
                                <th class="px-2 py-1 text-center border whitespace-nowrap">Model</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">L</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">W</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">T</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">Material&nbsp;Grade</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">Br</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">iHc</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">iHk</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">Oven</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">MPI&nbsp;Sample</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">Carmark</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">Encoded By</th>
                                <th class="px-2 py-1 text-center border whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in filteredInspectionData"
                                :key="item.id"
                                class="text-xs bg-gray-50"
                            >
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.model || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.length || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.width || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.thickness || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.material_grade || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.br || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.ihc || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.ihk || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">
                                    {{ item.oven_machine_no === 1 ? 'With Oven' : 'N/A' }}
                                </td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.mpi_sample || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.is_automotive === 1 ? 'Automotive' : 'Non-Automotive' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.encoded_by || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">
                                    <button @click="editRecord(item)" class="text-blue-600 hover:underline">Edit</button>
                                </td>

                                                                <!--
                                                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.model || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.length || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.width || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.thickness || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.material_grade || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.br || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.ihc || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.ihk || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.oven_machine_no || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.time_loading || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.temperature_1 || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.date || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.time_unloading || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.temperature_2 || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.shift || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.operator || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.mpi_sample || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">
                                    <input
                                        type="checkbox"
                                        :checked="item.with_carmark === true || item.with_carmark === 1"
                                        @change="item.with_carmark = $event.target.checked ? 1 : 0"
                                    />
                                </td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.encoded_by || 'N/A' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">
                                    <button @click="editRecord(item)" class="text-blue-600 hover:underline">Edit</button>
                                </td>

                                -->
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <div v-if="inspectionDataList.length <= 0" class="flex flex-col items-center justify-center p-6 align-middle shadow-xl rounded-xl bg-gradient-to-l from-blue-300 to-green-300">
                        <label class="pb-6 text-lg font-extrabold text-white">Upload Bulk Initial Data specs</label>
                        <div class="flex flex-row">
                            <input type="file" accept=".csv" @change="handleFileChange" />
                            <button class="p-2 font-extrabold text-white bg-blue-500 rounded-md hover:shadow-xl hover:brightness-110 active:scale-95" @click="uploadCsv">Upload csv</button>
                        </div>
                        <p :class="{'pt-6 text-red-500':uploadStatusType == 'failed',
                                    'pt-6 text-green-500':uploadStatusType == 'success',
                                    'pt-6 text-blue-500':uploadStatusType == 'info',
                        }">{{ uploadStatus }}</p>
                    </div>
                </div>
            </div>

            <div v-show="showInputData" class="flex flex-col w-full p-8 m-10 mx-auto bg-white shadow-lg max-w-7xl rounded-2xl">
                <div class="flex flex-row items-start justify-start">
                    <button @click="registerBackBtn" class="px-3 py-1 font-semibold text-gray-700 transition duration-300 ease-in-out bg-gray-300 rounded-lg shadow-md hover:bg-gray-400 hover:shadow-lg active:scale-95">
                        Back
                    </button>
                </div>

                <div class="flex-col items-center justify-center">
                    <!-- Title -->
                    <p class="mb-6 text-3xl font-bold text-center text-gray-800">
                      {{ isEditMode ? 'Update Data' : 'Register New Data' }}
                    </p>

                    <!-- Model Input -->
                    <div class="w-full mb-6">
                        <label class="block mb-1 font-medium text-gray-600">Model:</label>
                        <input @input="convertToUppercase" v-model="formData.model" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                    </div>

                    <!-- Grid for Inputs -->
                    <div class="grid w-full grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-5">
                        <!-- Column 1 -->
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">Length:</label>
                                <input v-model="formData.length" type="number"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">Width:</label>
                                <input v-model="formData.width" type="number"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Column 2 -->
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">Thickness:</label>
                                <input v-model="formData.thickness" type="number"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">Material&nbsp;Grade:</label>
                                <input @input="convertToUppercase" v-model="formData.material_grade" type="text"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Column 3 -->
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">
                                    Br:&nbsp;<span class="text-xs text-gray-500">(ex: 1234 ~ 5678)</span>
                                </label>
                                <input @input="convertToUppercase" v-model="formData.br" type="text"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">iHc:</label>
                                <input @input="convertToUppercase" v-model="formData.ihc" type="text"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Column 4 -->
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">iHk:</label>
                                <input @input="convertToUppercase" v-model="formData.ihk" type="text"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">Encoded&nbsp;By:</label>
                                <input @input="convertToUppercase" v-model="formData.encoded_by" type="text"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Column 5 -->
                        <div class="space-y-5">
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-gray-700">MPI&nbsp;Sample:</label>
                                <input @input="convertToUppercase" v-model="formData.mpi_sample" type="text"
                                    class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div class="pt-6">
                                <label class="inline-flex items-center space-x-2 text-sm font-semibold text-gray-700">
                                    <input type="checkbox" v-model="formData.is_automotive"
                                        class="text-blue-600 form-checkbox" />
                                    <span>Is it Automotive?</span>
                                </label>
                                <label class="inline-flex items-center space-x-2 text-sm font-semibold text-gray-700">
                                    <input type="checkbox" v-model="formData.oven_machine_no"
                                        class="text-blue-600 form-checkbox" />
                                    <span>With Oven?</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center mt-8">
                        <button
                            @click="submitData"
                            class="relative flex px-6 py-3 font-semibold text-white transition duration-300 ease-in-out bg-blue-600 shadow-md rounded-xl hover:brightness-110 hover:shadow-lg active:scale-95"
                        >
                            {{ isEditMode ? 'Update' : 'Register' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Papa from 'papaparse';
import axios from "axios";
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

const userInspectionLogging = async (logEvent) => {
    try{
        const responseInspectionLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Inspection',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('responseInspectionLogging post request failed: ',error);
    }
}

const searchModel = ref('');

// UI visibility reactive states
const showMainUI = ref(true);
const showInputData = ref(false);

// Edit mode and currently editing record ID
const isEditMode = ref(false);
const currentEditId = ref(null);

// Reactive state for form data
const formData = ref({
    model: null,
    length: null,
    width: null,
    thickness: null,
    material_grade: null,
    br: null,
    ihc: null,
    ihk: null,
    oven_machine_no: 0,
    mpi_sample: null,
    is_automotive: 0,
    encoded_by: null,
});

// Inspection data list fetched from backend
const inspectionDataList = ref([]);

const filteredInspectionData = computed(() => {
    if (!searchModel.value) return inspectionDataList.value;

    return inspectionDataList.value.filter(item =>
        item.model?.toLowerCase().includes(searchModel.value.toLowerCase())
    );
});

// Functions to toggle UI
const insertBtn = () => {
  showMainUI.value = false;
  showInputData.value = true;
};

const registerBackBtn = () => {
  showMainUI.value = true;
  showInputData.value = false;
  resetAllField();
  isEditMode.value = false;
  currentEditId.value = null;
};

// Reset form fields
const resetAllField = () => {
    formData.value = {
        model: null,
        length: null,
        width: null,
        thickness: null,
        material_grade: null,
        br: null,
        ihc: null,
        ihk: null,
        oven_machine_no: 0,
        mpi_sample: null,
        is_automotive: 0,
        encoded_by: null,
    };
};

// Convert input fields to uppercase for specific keys
function convertToUppercase() {
  const fields = [
    'model',
    'material_grade',
    'br',
    'ihc',
    'ihk',
    'mpi_sample',
    'encoded_by',
  ];

  fields.forEach(field => {
    if (formData.value[field] && typeof formData.value[field] === 'string') {
      formData.value[field] = formData.value[field].toUpperCase();
    }
  });
}

// Submit form data — POST for new, PATCH for update
const submitData = async () => {
    try {
        //Validate first
        const isDuplicate = await checkDuplicateModel();

        if (isDuplicate) {
            toast.warning('Model already exists.');
            return;
        }

        if (isEditMode.value) {
            await axios.patch(
                `/api/inspectiondata/${currentEditId.value}`,
                formData.value
            );

            userInspectionLogging(
                `has successfully updated existing data specs model ${formData.value.model}`
            );
        } else {
            await axios.post(
                "/api/inspectiondata",
                formData.value
            );

            userInspectionLogging(
                `has successfully inserted new data specs model ${formData.value.model}`
            );
        }

        isEditMode.value = false;
        currentEditId.value = null;
        showMainUI.value = true;
        showInputData.value = false;

        await showInspectionData();
        resetAllField();

    } catch (error) {
        console.warn("Error submitting inspection data");

        console.group("❌ submitData error breakdown");

        console.error("Message:", error.message);
        console.error("Code:", error.code ?? "N/A");

        if (error.response) {
            console.error("Status:", error.response.status);
            console.error("Response data:", error.response.data);
            console.error("Response headers:", error.response.headers);
        } else if (error.request) {
            console.error("Request sent but no response:", error.request);
        } else {
            console.error("Error config:", error.config);
        }

        console.error("Payload sent:", formData.value);

        console.groupEnd();
    }
};


// Fetch all inspection data records
const showInspectionData = async () => {
  try {
    const response = await axios.get("/api/inspectiondata");
    inspectionDataList.value = response.data.data || [];
  } catch (error) {
    console.error("API get request showInspectionData Error:", error);
  }
};

// Edit button handler — load record into form and show input UI
const editRecord = (item) => {
  isEditMode.value = true;
  currentEditId.value = item.id;

    formData.value = {
        model: item.model,
        length: item.length,
        width: item.width,
        thickness: item.thickness,
        material_grade: item.material_grade,
        br: item.br,
        ihc: item.ihc,
        ihk: item.ihk,
        oven_machine_no: Boolean(Number(item.oven_machine_no)),
        mpi_sample: item.mpi_sample,
        is_automotive: Boolean(Number(item.is_automotive)),
        encoded_by: item.encoded_by
    };

  showMainUI.value = false;
  showInputData.value = true;
};

const csvFile = ref(null);
const uploadStatus = ref('');
const uploadStatusType = ref('');

const handleFileChange = (event) => {
    csvFile.value = event.target.files[0];
};

const uploadCsv = () => {
    if (!csvFile.value) {
        uploadStatus.value = 'Please select a CSV file first.';
        setTimeout(() => {
            uploadStatus.value = '';
        },3000);
        uploadStatusType.value = 'info';
        return;
    }

    Papa.parse(csvFile.value, {
        header: true,
        skipEmptyLines: true,
        complete: async (results) => {
            try {
                const response = await axios.post('/api/inspection/bulk-upload', {
                data: results.data,
                });
                uploadStatus.value = response.data.message + ` (${response.data.inserted} records inserted)`;
                uploadStatusType.value = 'success';
                setTimeout(() => {
                    uploadStatus.value = '';
                    showInspectionData();
                },1000);
            } catch (err) {
                uploadStatus.value = 'Upload failed: ' + (err.response?.data?.message || err.message);
            }
        },
        error: (err) => {
        uploadStatus.value = 'CSV parse error: ' + err.message;
        uploadStatusType.value = 'failed';
        setTimeout(() => {
            uploadStatus.value = '';
        },3000);
        },
    });
};

const checkDuplicateModel = async() => {
    try{
         const response = await axios.post('/api/inspection-data/check-duplicate', {
            model: formData.value.model
        });

        return response.data.duplicate === true;

    }catch(error){
        console.error('Failed to check duplication',error);
    }
}

// Load data on mount
onMounted(async () => {
    await checkAuthentication();
    await showInspectionData();
});

</script>
