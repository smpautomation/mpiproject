<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 space-y-8 bg-gradient-to-b from-gray-50 to-gray-100">

            <!-- Header -->
            <div class="flex flex-col items-center space-y-3">
            <!-- Icon -->
            <div class="flex items-center justify-center w-16 h-16 rounded-full shadow-lg bg-gradient-to-tr from-blue-500 to-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 2c.5 1.5 1.5 3 3 4.5s2 3.5 2 5.5c0 3.5-2.5 6-5 8.5-2.5-2.5-5-5-5-8.5 0-2 .5-3.5 2-5.5S11.5 3.5 12 2z" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold tracking-tight text-gray-800">
                1st and 2nd GBDP Model Records
            </h1>
            <p class="text-sm text-gray-500">
                View all created models for 1st and 2nd GBDP with their creation date.
            </p>
            </div>



            <!-- Action Bar -->
            <div class="flex justify-between w-full max-w-5xl">
                <button
                @click="showModalCreate = true"
                class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1 transition"
                >
                + Create New Model
                </button>

                <input
                v-model="searchQuery"
                type="text"
                placeholder="Search models..."
                class="w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>

            <!-- Table -->
            <div class="w-full max-w-5xl overflow-x-auto bg-white rounded-lg shadow-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700 uppercase">Date</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700 uppercase">Model</th>
                            <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                        v-for="model in filteredModelData"
                        :key="model.id"
                        class="transition hover:bg-indigo-50"
                        >
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ new Date(model.created_at).toLocaleDateString() }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ model.model_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <button
                                    @click="openDeleteConfirm(model.id)"
                                    class="p-2 text-red-600 rounded-full hover:bg-red-100"
                                    title="Delete"
                                >
                                    <!-- Trash Can Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 h-5">
                                    <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6 7h12M9 7v10m6-10v10M4 7h16l-1 12a2 2 0 01-2 2H7a2 2 0 01-2-2L4 7zM9 7V5a2 2 0 012-2h2a2 2 0 012 2v2" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Confirmation Modal -->
            <div
                v-if="showConfirm"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            >
                <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-lg">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">Delete Furnace?</h2>
                <p class="mb-6 text-sm text-gray-600">
                    This action cannot be undone. Are you sure you want to remove this furnace record?
                </p>
                <div class="flex justify-end space-x-3">
                    <button
                    @click="closeDeleteConfirm"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                    >
                    Cancel
                    </button>
                    <button
                    @click="deleteFurnace"
                    class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-md hover:bg-red-700"
                    >
                    Delete
                    </button>
                </div>
                </div>
            </div>


            <!-- Empty State -->
            <div
                v-if="filteredModelData.length === 0"
                class="flex flex-col items-center justify-center w-full max-w-5xl p-12 mt-6 text-center bg-white rounded-lg shadow-md"
            >
                <p class="text-gray-500">No model records found.</p>
            </div>

            <Modal :show="showModalCreate" @close="showModalCreate = false">
            <div
                class="relative flex flex-col items-center justify-center w-full max-w-2xl p-12 mx-auto bg-white shadow-2xl rounded-2xl animate-fadeIn"
            >
                <!-- Exit Button -->
                <button
                @click="showModalCreate = false, showConfirmation = false"
                class="absolute text-gray-400 transition duration-150 top-4 right-4 hover:text-gray-600"
                aria-label="Close modal"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                </button>

                <!-- Header -->
                <div class="flex flex-col items-center mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800">➕ New Model</h2>
                <p class="text-sm text-gray-500">Fill in the details to create a new model record.</p>
                </div>

                <!-- Form Fields -->
                <div class="flex flex-col w-full gap-6 mb-8 md:flex-row">
                <!-- Furnace No -->
                <div class="flex flex-col w-full">
                    <label class="mb-1 text-sm font-semibold text-gray-700">Model Name</label>
                    <input
                    type="text"
                    v-model="modelName"
                    @input="modelName = modelName.toUpperCase()"
                    placeholder="ex. TIC0755G"
                    class="w-full px-4 py-2 text-sm font-medium text-gray-800 placeholder-gray-400 uppercase transition border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>

                </div>

                <!-- Actions -->
                <div v-if="!showConfirmation" class="w-full text-center">
                    <div class="mt-10">
                        <button
                            @click="submitForm"
                            class="px-8 py-2 text-sm font-semibold text-white transition duration-200 bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-400"
                        >
                            Submit
                        </button>
                    </div>
                </div>

                <!-- Confirmation State -->
                <div v-else class="flex flex-col items-center justify-center gap-5">
                <p class="text-base font-semibold text-gray-800">
                    Are you <span class="text-red-600">sure</span> this information is correct?
                </p>
                <div class="flex gap-4">
                    <button
                    @click="showConfirmation = false"
                    class="px-6 py-2 text-sm font-semibold text-white transition duration-200 bg-gray-600 rounded-lg shadow-sm hover:bg-gray-700"
                    >
                        No
                    </button>
                    <button
                    @click="saveToDatabase"
                    class="px-6 py-2 text-sm font-semibold text-white transition duration-200 bg-green-600 rounded-lg shadow-sm hover:bg-green-700"
                    >
                        Yes
                    </button>
                </div>
                </div>

                <!-- Loading Overlay -->
                <DotsLoader
                v-if="loadingState"
                class="absolute inset-0 z-50 flex items-center justify-center bg-white bg-opacity-75 rounded-2xl"
                />
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
import DotsLoader from '@/Components/DotsLoader.vue';
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

// General Variables ---------------------------------- General Variables

//Toggles
const showConfirmation = ref(false);
const showModalCreate = ref(false);
const loadingState = ref(false);
const showConfirm = ref(false);
//Toggles

const modelName = ref();
const modelDataLists = ref([]);
const searchQuery = ref("");
const deleteTargetId = ref(null);

// General Variables ---------------------------------- General Variables

const filteredModelData = computed(() => {
    return modelDataLists.value.filter(model => {
        const name = model?.model_name || '';
        return name.toLowerCase().includes(searchQuery.value.toLowerCase());
    });
});

const submitForm = () => {
    if(modelName.value == '' || modelName.value == null){
        toast.warning('Please fill up all fiends.');
        return;
    }
    showConfirmation.value = true;
}


const saveToDatabase = async () => {
    loadingState.value = true;
    try {
        const response = await axios.post('/api/second-gbdp-models/',{
            model_name: modelName.value,
        });
        console.log('Saved To Database: ', response.data);
        toast.success('Saved Successfully');
    } catch (error) {
        console.error("Failed to save to database", error);
        toast.error('Data not saved successfully');
    } finally {
        loadingState.value = false;
        showModalCreate.value = false;
        showConfirmation.value = false;
        await getModelLists();
    }
};

const getModelLists = async () => {
    try {
        const response = await axios.get('/api/second-gbdp-models/');
        modelDataLists.value = response.data; // keep everything
        console.log("Model Data List: ", modelDataLists.value);
    } catch (error) {
        console.error("Failed to fetch model data", error);
        toast.error("Failed to get model data.");
    }
};

const openDeleteConfirm = (id) => {
  deleteTargetId.value = id;
  showConfirm.value = true;
};

const closeDeleteConfirm = () => {
  deleteTargetId.value = null;
  showConfirm.value = false;
};

const deleteFurnace = async () => {
    if (!deleteTargetId.value) return;
    try {
        await axios.delete(`/api/second-gbdp-models/${deleteTargetId.value}`);
        toast.success("Model deleted successfully.");
        // Option 1: refresh list from API
        await getModelLists();
        // Option 2: or filter locally:
        // modelDataLists.value = modelDataLists.value.filter(f => f.furnace_id !== deleteTargetId.value);
    } catch (error) {
        toast.error("Failed to delete model.");
    } finally {
        closeDeleteConfirm();
    }
};

onMounted(async() => {
    await getModelLists();
});


</script>
