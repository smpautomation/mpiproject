<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">
            <div v-show="showMainUI" class="flex flex-col items-center justify-center">
                <div>
                    <button @click="insertBtn" class="relative flex px-6 py-3 m-10 overflow-hidden font-semibold text-white transition duration-300 ease-in-out bg-green-600 shadow-md rounded-xl hover:brightness-110 hover:shadow-lg active:scale-95">
                        Insert New Record
                    </button>
                </div>
                <div class="flex items-center justify-center mb-10 bg-gray-100">
                    <table class="w-11/12 mx-auto border border-collapse border-gray-300 table-auto md:w-3/4">
                        <thead class="text-white bg-gradient-to-l from-blue-600 to-green-600 ">
                            <tr class="text-sm">
                                <th class="px-2 py-1 text-center border">Model</th>
                                <th class="px-2 py-1 text-center border">L</th>
                                <th class="px-2 py-1 text-center border">W</th>
                                <th class="px-2 py-1 text-center border">T</th>
                                <th class="px-2 py-1 text-center border">Material&nbsp;Grade</th>
                                <th class="px-2 py-1 text-center border">Br</th>
                                <th class="px-2 py-1 text-center border">iHc</th>
                                <th class="px-2 py-1 text-center border">iHk</th>
                                <th class="px-2 py-1 text-center border">Oven&nbsp;Machine&nbsp;No</th>
                                <th class="px-2 py-1 text-center border">Time&nbsp;Loading</th>
                                <th class="px-2 py-1 text-center border">Temperature</th>
                                <th class="px-2 py-1 text-center border">Date</th>
                                <th class="px-2 py-1 text-center border">Time&nbsp;Unloading</th>
                                <th class="px-2 py-1 text-center border">Temperature</th>
                                <th class="px-2 py-1 text-center border">Shift</th>
                                <th class="px-2 py-1 text-center border">Operator</th>
                                <th class="px-2 py-1 text-center border">MPI&nbsp;Sample</th>
                                <th class="px-2 py-1 text-center border">iHc&#8209;iHk</th>
                                <th class="px-2 py-1 text-center border">Br&#8209;4πla</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in inspectionDataList"
                                :key="item.id"
                                class="text-xs bg-gray-50"
                            >
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.model || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.length || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.width || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.thickness || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.material_grade || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.br || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.ihc || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.ihk || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.oven_machine_no || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.time_loading || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.temperature_1 || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.date || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.time_unloading || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.temperature_2 || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.shift || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.operator || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.mpi_sample || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item.ihc_ihk || 'null' }}</td>
                                <td class="px-2 py-1 text-center border whitespace-nowrap">{{ item["br_4-pie-la"] || 'null' }}</td>
                            </tr>
                        </tbody>
                    </table>
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
                    <p class="mb-6 text-3xl font-bold text-center text-gray-800">Register New Data</p>

                    <!-- Model Input -->
                    <div class="w-full mb-6">
                        <label class="block mb-1 font-medium text-gray-600">Model:</label>
                        <input @input="convertToUppercase" v-model="formData.model" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                    </div>

                    <!-- Grid for Inputs -->
                    <div class="grid w-full grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Length:</label>
                                <input v-model="formData.length" type="number" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Width:</label>
                                <input v-model="formData.width" type="number" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Thickness:</label>
                                <input v-model="formData.thickness" type="number" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Material&nbsp;Grade:</label>
                                <input @input="convertToUppercase" v-model="formData.material_grade" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Br:&nbsp;(ex:&nbsp;1234&nbsp;~&nbsp;5678&nbsp;)</label>
                                <input @input="convertToUppercase" v-model="formData.br" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">iHc:</label>
                                <input @input="convertToUppercase" v-model="formData.ihc" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>

                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">iHk:</label>
                                <input @input="convertToUppercase" v-model="formData.ihk" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Oven&nbsp;Machine&nbsp;No:</label>
                                <input @input="convertToUppercase" v-model="formData.oven_machine_no" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Time&nbsp;Loading:</label>
                                <input v-model="formData.time_loading" type="time" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Temperature(load):</label>
                                <input v-model="formData.temperature_1" type="number" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Date:</label>
                                <input v-model="formData.date" type="date" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Time&nbsp;Unloading:</label>
                                <input v-model="formData.time_unloading" type="time" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Temperature(unload):</label>
                                <input v-model="formData.temperature_2" type="number" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Shift:</label>
                                <input @input="convertToUppercase" v-model="formData.shift" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Operator:</label>
                                <input @input="convertToUppercase" v-model="formData.operator" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">MPI&nbsp;Sample:</label>
                                <input @input="convertToUppercase" v-model="formData.mpi_sample" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">iHc&#8209;iHk:</label>
                                <input @input="convertToUppercase" v-model="formData.ihc_ihk" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Br&#8209;4πla:</label>
                                <input @input="convertToUppercase" v-model="formData['br_4-pie-la']" type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button @click="submitData" class="flex flex-row items-center px-6 py-3 m-10 overflow-hidden text-xl font-semibold text-white transition duration-300 ease-in-out bg-green-600 shadow-md rounded-xl hover:brightness-110 hover:shadow-lg active:scale-95">
                        Register
                    </button>
                </div>

            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted } from 'vue';
import axios from "axios";

//UI Visibility Variables

const showMainUI = ref(true);
const showInputData = ref(false);

//UI Visibility Variables end
// UI visibility functions
const insertBtn = () => {
    showMainUI.value = false;
    showInputData.value = true;
}

const registerBackBtn = () => {
    showMainUI.value = true;
    showInputData.value = false;
}

// UI visibility functions end

function convertToUppercase() { //make sure textinputs are upper case
    const fields = [
    'model',
    'material_grade',
    'br',
    'ihc',
    'ihk',
    'oven_machine_no',
    'shift',
    'operator',
    'mpi_sample',
    'ihc_ihk',
    'br_4-pie-la'
  ];

  fields.forEach(field => {
    if (formData.value[field] && typeof formData.value[field] === 'string') {
      formData.value[field] = formData.value[field].toUpperCase();
    }
  });
}

// Reactive state for user input
const formData = ref({
    model: null,
    length: null,
    width: null,
    thickness: null,
    material_grade: null,
    br: null,
    ihc: null,
    ihk: null,
    oven_machine_no: null,
    time_loading: null,
    temperature_1: null,
    date: null,
    time_unloading: null,
    temperature_2: null,
    shift: null,
    operator: null,
    mpi_sample: null,
    ihc_ihk: null,
    "br_4-pie-la": null,
});

// Function to send data using Axios
const submitData = async () => {
    console.log("Form Data: ", formData.value);
    try {
        const response = await axios.post("/api/inspectiondata", formData.value);
        // Handle response
        console.log("Insection api post Data submitted successfully!");
        console.log("API Response:", response.data);
        showMainUI.value = true;
        showInputData.value = false;
        showInspectionData();
    } catch (error) {
        console.log("Error submitting data!");
        console.error("API Error:", error);
    }
};

const inspectionDataList = ref([]);

const showInspectionData = async () => {
    try{
        const response = await axios.get("/api/inspectiondata");
        console.log("Show All inspection data API respone: ",response.data);
        inspectionDataList.value = response.data.data || [];
        console.log("Show All inspection data list : ",inspectionDataList.value);
    }catch{
        console.error("API get request showInspectionData Error:", error);
    }
}

onMounted(showInspectionData);

</script>
