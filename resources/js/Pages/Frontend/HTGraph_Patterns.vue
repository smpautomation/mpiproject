<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 space-y-6 bg-gray-100">
            <h1 class="text-2xl font-semibold text-gray-800">Heat Treatment Graph Patterns</h1>

            <!-- Blue-themed button -->
            <button
            @click="showModalCreate = true"
            class="px-6 py-3 font-semibold text-white transition-colors bg-blue-600 shadow-lg rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                Create New Pattern
            </button>

            <!-- Search Input -->
            <div class="mb-4">
            <input
                type="number"
                v-model="searchQuery"
                placeholder="Search by pattern number..."
                class="w-full px-4 py-2 text-xs border rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300"
            />
            </div>

            <!-- Empty State Panel -->
            <div v-if="filteredPatterns.length === 0"
                class="flex flex-col items-center justify-center p-8 border border-gray-200 rounded-lg bg-gray-50 animate-fadeIn">

                <!-- Pulsing Icon -->
                <svg class="w-12 h-12 mb-4 text-gray-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6a2 2 0 114 0v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h5l5 5v7a2 2 0 01-2 2z"/>
                </svg>

                <!-- Message -->
                <p class="text-sm text-center text-gray-500 delay-200 animate-fadeIn">
                    No graphs found. Start by creating a new pattern.
                </p>
            </div>


            <!-- Patterns container -->
            <div v-else class="grid grid-cols-2 gap-4 mt-12 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
                <div
                    v-for="(pattern, index) in filteredPatterns"
                    :key="index"
                    class="relative flex flex-col items-center overflow-hidden transition-transform transform bg-white shadow-sm cursor-pointer rounded-xl hover:scale-105 hover:shadow-lg"
                    @click="selectedGraph = pattern.url; selectedPattern = pattern; showModalGraph = true"
                >
                    <!-- Image -->
                    <div class="flex items-center justify-center w-full overflow-hidden aspect-square bg-gray-50">
                        <img
                        :src="pattern.url"
                        alt="Graph pattern"
                        class="object-contain w-full h-full p-2 transition-transform duration-200 hover:scale-110"
                        />
                    </div>

                    <!-- Info Panel -->
                    <div class="flex flex-col items-center w-full px-3 py-2 text-center bg-gray-50">
                        <p class="text-sm font-semibold text-gray-800 truncate">
                        Pattern {{ pattern.pattern_no }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                        Encoded by: {{ pattern.encoded_by }}
                        </p>
                        <button
                        @click.stop="editPattern(pattern)"
                        class="px-2 py-1 mt-2 text-xs font-semibold text-blue-600 rounded bg-blue-50 hover:bg-blue-100 hover:underline"
                        >
                        Edit
                        </button>
                    </div>
                </div>


                <!-- Modal for full-size image -->
                <Modal :show="showModalGraph" @close="showModalGraph = false">
                <div class="relative flex flex-col items-center w-full max-w-3xl p-6 mx-auto bg-white shadow-2xl rounded-2xl">

                    <!-- Close button -->
                    <button
                    @click="showModalGraph = false"
                    class="absolute p-2 text-gray-400 transition rounded-full top-4 right-4 hover:text-gray-600"
                    aria-label="Close"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <!-- Pattern Info -->
                    <div class="mb-4 text-center">
                    <p class="text-lg font-semibold text-gray-800">Pattern {{ selectedPattern?.pattern_no }}</p>
                    <p class="text-sm text-gray-500">Encoded by: {{ selectedPattern?.encoded_by }}</p>
                    </div>

                    <!-- Image -->
                    <img
                    :src="selectedGraph"
                    alt="Graph pattern"
                    class="object-contain max-h-[70vh] max-w-full rounded-lg border border-gray-200 shadow-md"
                    />
                </div>
                </Modal>

            </div>

            <Modal :show="showModalEdit" @close="showModalEdit = false">
                <div class="relative flex flex-col w-full max-w-3xl p-8 mx-auto bg-white shadow-2xl rounded-2xl">

                    <!-- Close Button -->
                    <button @click="showModalEdit = false" class="absolute text-xl text-gray-500 top-4 right-4 hover:text-gray-700">✕</button>

                    <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Edit Pattern</h2>

                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium text-gray-700">Pattern No.</label>
                        <input type="number" v-model="patternNo" class="w-full border-gray-300 rounded-lg shadow-sm form-input focus:ring-2 focus:ring-green-400"/>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium text-gray-700">Furnace No</label>
                        <select v-model="selectedFurnace" class="w-full border-gray-300 rounded-lg shadow-sm form-select focus:ring-2 focus:ring-green-400">
                        <option v-for="item in furnaceNo" :key="item" :value="item">{{ item }}</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium text-gray-700">Encoded By</label>
                        <input type="text" v-model="encodedBy" class="w-full border-gray-300 rounded-lg shadow-sm form-input focus:ring-2 focus:ring-green-400"/>
                    </div>
                    </div>

                    <!-- Replace Graph -->
                    <div class="flex flex-col w-full mb-6">
                    <label class="mb-1 text-sm font-medium text-gray-700">Replace Graph (optional)</label>
                    <input type="file" ref="editFile" @change="handleEditGraph" class="p-2 border rounded-lg file-input"/>
                    </div>

                    <!-- Preview -->
                    <div v-if="uploadedGraphEdited" class="mb-6 text-center">
                    <p class="mb-2 text-xs font-semibold text-gray-600">Preview:</p>
                    <img :src="uploadedGraphEdited" class="inline-block border rounded-lg shadow-sm max-h-48"/>
                    </div>

                    <!-- Save Button -->
                    <button @click="updatePattern"
                    class="w-full py-3 text-white transition-colors bg-green-600 rounded-lg shadow hover:bg-green-700">
                    Save Changes
                    </button>
                </div>
            </Modal>


            <Modal :show="showModalCreate" @close="showModalCreate = false">
                <div class="relative flex flex-col items-center justify-center w-full max-w-3xl p-8 mx-auto bg-white shadow-2xl rounded-2xl">
                    <!-- Close button -->
                    <button
                    @click="showModalCreate = false"
                    class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-100"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Header -->
                <p class="mb-6 text-xl font-bold text-center text-gray-800">Please fill out all the fields</p>

                    <!-- Form fields -->
                    <div class="flex flex-col w-full gap-6 mb-8 md:flex-row">
                        <!-- Pattern No -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 text-sm font-semibold text-gray-700">Pattern No.</label>
                            <input
                                type="number"
                                v-model="patternNo"
                                placeholder="Input Pattern Number..."
                                class="w-full px-3 py-3 text-sm font-medium text-gray-700 placeholder-gray-400 uppercase transition border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>

                        <!-- Furnace No -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 text-sm font-semibold text-gray-700">Furnace No</label>
                            <select
                                v-model="selectedFurnace"
                                class="w-full px-3 py-3 text-sm text-gray-700 transition border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option v-for="item in furnaceNo" :key="item" :value="item">{{ item }}</option>
                            </select>
                        </div>

                        <!-- Encoded By -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1 text-sm font-semibold text-gray-700">Encoded by</label>
                            <input
                                type="text"
                                v-model="encodedBy"
                                class="w-full px-3 py-3 text-sm font-medium text-gray-700 placeholder-gray-400 uppercase transition border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                    </div>

                    <!-- Upload Graph (separate row) -->
                    <div class="flex flex-col w-full mb-8">
                        <label class="mb-1 text-sm font-semibold text-gray-700">Upload Graph</label>
                        <input
                            type="file"
                            id="cycleGraph"
                            @change="uploadGraph"
                            class="w-full px-3 py-2 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100 file:transition-colors file:cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <!-- File preview -->
                    <div v-if="uploadedGraph" class="mt-4 mb-8">
                        <p class="mb-1 text-xs font-semibold text-gray-700">Preview:</p>
                        <img
                            :src="uploadedGraph"
                            alt="Graph Preview"
                            class="object-contain w-full border border-gray-200 rounded-lg shadow-sm max-h-48"
                        />
                    </div>



                    <!-- Submit / Confirmation -->
                    <div v-if="!showConfirmation" class="flex justify-center w-full">
                    <button
                        @click="submitForm"
                        class="px-8 py-3 text-sm font-semibold text-white transition-all duration-200 rounded-lg shadow-lg bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600"
                    >
                        Submit
                    </button>
                    </div>

                    <div v-else class="flex flex-col items-center justify-center gap-4">
                        <p class="font-semibold text-center text-gray-700">
                            Are you <span class="text-red-600">sure</span> that input is correct?
                        </p>
                        <div class="flex gap-4">
                            <button
                            @click="showConfirmation = false"
                            class="px-6 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-gray-600 rounded-lg shadow hover:bg-gray-700"
                            >
                            No
                            </button>
                            <button
                            @click="saveToDatabase"
                            class="px-6 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-green-600 rounded-lg shadow hover:bg-green-700"
                            >
                            Yes
                            </button>
                        </div>
                    </div>
                </div>
            </Modal>


        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
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
            section: 'HT Graph',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userManageLogging post request failed: ',error);
    }
}


// General Variables ---------------------------------- General Variables

//Toggles
const showModalCreate = ref(false);
const showConfirmation = ref(false);
const showModalGraph = ref(false);
const showModalEdit = ref(false)
//Toggles

const patternNo = ref(0);
const furnaceNo = ref([]);
const selectedFurnace = ref();
const uploadedGraph = ref();
const uploadedGraphEdited = ref();
const encodedBy = ref();
const graphFileLists = ref([]);
const selectedGraph = ref();
const selectedPattern = ref();
const editId = ref(null);
const editFile = ref(null);

// General Variables ---------------------------------- General Variables

const editPattern = (pattern) => {
    editId.value = pattern.id;
    patternNo.value = pattern.pattern_no;
    selectedFurnace.value = pattern.furnace_no;
    encodedBy.value = pattern.encoded_by;
    uploadedGraph.value = pattern.url;
    showModalEdit.value = true;
    console.log('Editing pattern id:', editId.value);
}

const updatePattern = async () => {
    if (!editId.value) return;

    const formData = new FormData();
    formData.append('pattern_no', patternNo.value || '');
    formData.append('furnace_no', selectedFurnace.value || '');
    formData.append('encoded_by', encodedBy.value || '');

    if (editFile.value?.files[0]) {
        formData.append('graph', editFile.value.files[0]);
    }

    // Spoof method for Laravel
    formData.append('_method', 'PATCH');

    try {
        await axios.post(`/api/patterns/${editId.value}/update`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        toast.success('Pattern updated successfully');
        showModalEdit.value = false;
    } catch (error) {
        toast.error('Failed to update pattern');
        console.error(error.response?.data || error);
    } finally {
        await getAllPatterns();
    }
};

const handleEditGraph = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a PNG, JPG or JPEG image.');
        event.target.value = '';
        uploadedGraphEdited.value = null;
        return;
    }

    uploadedGraphEdited.value = URL.createObjectURL(file); // preview
}

const submitForm = () => {
    if(selectedFurnace.value == null || patternNo.value == null || selectedFurnace == '' || patternNo.value == 0 || uploadedGraph.value == null || encodedBy.value == null){
        toast.warning('Please fill up all the fields.')
        uploadedGraph.value = null;
        return;
    }
    showConfirmation.value = true;
}

let graph = null;
const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
const uploadGraph = (event) => {
    const file = event.target.files[0];
    if (!file){
        return;
    }
    if(!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a PNG, JPG or JPEG image.');
        event.target.value = ''; // Clear the input if the file type is not allowed
        uploadedGraph.value = null; // Reset preview if invalid file
        return;
    }
    uploadedGraph.value = URL.createObjectURL(file); // Create a preview URL
    graph = file;
    console.log('Pattern Graph File selected:', graph.name);
}

const uploadGraphFinal = async() => {
    // Prepare form data
    const formData = new FormData();
    formData.append('graph', graph);
    formData.append('pattern_no', patternNo.value);

    try {
        const response = await axios.post('/api/ht-graph-patterns/upload-graph', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        });
        toast.success(response.data.message);
        console.log('Upload response:', response.data);
    } catch (error) {
        toast.error('Upload failed.');
        console.error(error.response?.data || error);
    }
}

const saveToDatabase = async () => {
    try {
        const response = await axios.post('/api/ht-graph-patterns', {
            pattern_no: patternNo.value,
            furnace_no: selectedFurnace.value,
            encoded_by: encodedBy.value
        });

        await uploadGraphFinal();

        toast.success('Graph Pattern created successfully!');
        await userManageLogging('created Graph Pattern no: '+ patternNo.value +' Encoded by: ' + encodedBy.value + ' successfully.');
    } catch (error) {
        if (error.response?.status === 422) {
        const errors = error.response.data.errors;
            if (errors.pattern_no) {
                toast.error('This pattern number already exists.');
            } else {
                toast.error('Validation failed. Check input values.');
            }
        console.log('Validation errors:', errors);
        } else {
        toast.error('Failed to create Graph Pattern.');
        console.error(error);
        }
    } finally {
        await getAllPatterns();
        showModalCreate.value = false;
        selectedFurnace.value = '';
        encodedBy.value = '';
        patternNo.value = 0;
        uploadedGraph.value = null;
        showConfirmation.value = false;
    }
};

const searchQuery = ref('');

const filteredPatterns = computed(() => {
  if (!searchQuery.value) return graphFileLists.value;
  const q = searchQuery.value.toLowerCase();
  return graphFileLists.value.filter(
    p =>
      String(p.pattern_no).includes(q) ||
      p.encoded_by.toLowerCase().includes(q)
  );
});

const getAllPatterns = async() => {
    try {
        const response = await axios.get('/api/htgraph-patterns/list');
        graphFileLists.value = response.data; // array of URLs
        console.log('All patterns: ',graphFileLists.value);
    } catch (error) {
        console.error('Failed to fetch graphs', error);
    }
}

const getFurnaceLists = async() => {
    try{
        const response = await axios.get('/api/furnace-data/');
        const furnaceData = response.data;
        furnaceNo.value = furnaceData.map(item => item.furnace_name);
        console.log("Furnace Lists: ", furnaceNo.value);
    }catch(error){
        console.error('Failed to fetch furnace data lists: ',error);
        toast.error('Furnace Data List error.');
    }
}

onMounted(async() => {
    await getAllPatterns();
    await getFurnaceLists();
});


</script>
