<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 space-y-6 bg-gray-100">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between pb-6 border-b border-gray-100">
                <!-- Title Section -->
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        Heat Treatment <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-cyan-500">Graph Patterns</span>
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage, review, and encode industrial furnace configurations.
                    </p>
                </div>

                <!-- Actions Container (Search + Create Button) -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center w-full lg:w-auto">
                    <!-- Modern Minimalist Search Input -->
                    <div class="relative flex-1 sm:w-64 lg:w-72">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.603 10.603Z" />
                            </svg>
                        </span>
                        <input
                            type="number"
                            v-model="searchQuery"
                            placeholder="Search by pattern number..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 bg-white border border-gray-200 rounded-xl shadow-sm transition-all focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        />
                    </div>

                    <!-- Cyan/Teal Modern Button -->
                    <button
                        @click="showModalCreate = true"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 bg-gradient-to-r from-teal-600 to-teal-500 rounded-xl shadow-sm shadow-teal-200 hover:from-teal-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 active:scale-[0.98] whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create New Pattern
                    </button>
                </div>
            </div>

            <!-- Empty State Panel -->
            <div v-if="graphFileLists.length === 0"
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
            <div v-else class="w-full mt-12 overflow-x-auto bg-white border border-gray-100 shadow-sm rounded-xl">
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/90">
                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-teal-800 uppercase">Pattern</th>
                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-teal-800 uppercase">Furnace</th>
                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-teal-800 uppercase">Encoded By</th>
                            <th class="px-6 py-4 text-right text-xs font-bold tracking-wider text-teal-800 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr
                            v-for="(pattern, index) in graphFileLists"
                            :key="pattern.id || index"
                            class="relative transition-colors cursor-pointer group hover:bg-cyan-50/30"
                            @click="selectedGraph = pattern.url; selectedPattern = pattern; showModalGraph = true"
                        >
                            <!-- Pattern Number (First Column) -->
                            <td class="px-6 py-4.5 whitespace-nowrap border-l-2 border-transparent group-hover:border-cyan-500 transition-colors">
                                <span class="text-sm font-semibold text-gray-800 group-hover:text-cyan-700 transition-colors">
                                    {{ pattern.pattern_no }}
                                </span>
                            </td>
                            
                            <!-- Furnace Number -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="text-sm text-gray-600">
                                    {{ pattern.furnace_no }}
                                </span>
                            </td>
                            
                            <!-- Encoded By -->
                            <td class="px-6 py-5 max-w-[200px] truncate">
                                <span class="text-sm text-gray-500">
                                    {{ pattern.encoded_by }}
                                </span>
                            </td>
                            
                            <!-- Action Button -->
                            <td class="px-6 py-5 text-right whitespace-nowrap" @click.stop>
                                <button
                                    @click="editPattern(pattern)"
                                    class="px-3.5 py-1.5 text-xs font-semibold tracking-wide text-teal-700 bg-teal-50 rounded-lg hover:bg-teal-600 hover:text-white transition-all duration-200"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination Interface Footer -->
                <div class="flex items-center justify-between px-6 py-2 bg-white border-t border-gray-100 rounded-b-xl">
                    <!-- Meta Info: Left Side -->
                    <div class="text-sm text-gray-500">
                        Showing 
                        <span class="font-semibold text-gray-800">{{ paginationMeta.from }}</span> 
                        to 
                        <span class="font-semibold text-gray-800">{{ paginationMeta.to }}</span> 
                        of 
                        <span class="font-semibold text-gray-800">{{ paginationMeta.total }}</span> 
                        patterns
                    </div>

                    <!-- Direction Navigators: Right Side -->
                    <div class="inline-flex items-center gap-2">
                        <!-- Previous Page Button -->
                        <button
                            @click="currentPage > 1 ? currentPage-- : null"
                            :disabled="currentPage === 1"
                            class="inline-flex items-center justify-center p-2 text-gray-500 transition-colors bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>

                        <!-- Concise Page Tracker pills -->
                        <span class="px-3 py-1.5 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-100 rounded-lg">
                            Page {{ currentPage }} of {{ paginationMeta.last_page }}
                        </span>

                        <!-- Next Page Button -->
                        <button
                            @click="currentPage < paginationMeta.last_page ? currentPage++ : null"
                            :disabled="currentPage === paginationMeta.last_page"
                            class="inline-flex items-center justify-center p-2 text-gray-500 transition-colors bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-500 disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>


            </div>

            <!-- Modal for full-size image -->
            <Modal :show="showModalGraph" @close="showModalGraph = false">
                <div class="relative flex flex-col items-center w-full max-w-3xl p-6 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">
                    <!-- Close button -->
                    <button
                        @click="showModalGraph = false"
                        class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-50"
                        aria-label="Close"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Header & Pattern Info -->
                    <div class="w-full text-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">
                            Pattern {{ selectedPattern?.pattern_no }}
                        </h3>
                        
                        <!-- Metadata Badges -->
                        <div class="flex flex-wrap items-center justify-center gap-2 text-xs font-medium text-gray-600">
                            <span class="px-3 py-1 bg-gray-50 border border-gray-100 rounded-full">
                                <span class="text-gray-400 font-normal">Furnace:</span> {{ selectedPattern?.furnace_no }}
                            </span>
                            <span class="px-3 py-1 bg-gray-50 border border-gray-100 rounded-full">
                                <span class="text-gray-400 font-normal">Encoded by:</span> {{ selectedPattern?.encoded_by }}
                            </span>
                            <span class="px-3 py-1 bg-teal-50/50 border border-teal-100/50 text-teal-800 rounded-full">
                                <span class="text-teal-600/70 font-normal">Duration:</span> {{ selectedPattern?.pattern_no_hours }} hrs
                            </span>
                        </div>
                    </div>

                    <!-- Image Display Container (Strict Fixed Dimension Wrapper) -->
                    <div class="w-full h-[450px] overflow-hidden border border-gray-200 rounded-xl bg-gray-50/50 shadow-inner p-2 flex items-center justify-center">
                        <img
                            :src="selectedGraph"
                            alt="Graph pattern"
                            class="w-full h-full object-contain rounded-lg mix-blend-multiply"
                        />
                    </div>
                </div>
            </Modal>

            <Modal :show="showModalEdit" @close="showModalEdit = false">
                <div class="relative flex flex-col w-full max-w-3xl p-8 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">
                    <!-- Close Button -->
                    <button 
                        @click="showModalEdit = false" 
                        class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-50"
                        aria-label="Close modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h3 class="mb-6 text-lg font-bold text-center text-gray-900">Edit Pattern</h3>

                    <!-- Form Fields Row 1 -->
                    <div class="grid grid-cols-1 gap-5 mb-5 md:grid-cols-3">
                        <!-- Pattern No -->
                        <div class="flex flex-col">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Pattern No.</label>
                            <input 
                                type="number" 
                                v-model="patternNo" 
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>

                        <!-- Furnace No -->
                        <div class="flex flex-col">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Furnace No</label>
                            <select 
                                v-model="selectedFurnace" 
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            >
                                <option v-for="item in furnaceNo" :key="item" :value="item">{{ item }}</option>
                            </select>
                        </div>

                        <!-- Encoded By -->
                        <div class="flex flex-col">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Encoded By</label>
                            <input 
                                type="text" 
                                v-model="encodedBy" 
                                @input="encodedBy = encodedBy.toUpperCase()" 
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            />
                        </div>
                    </div>

                    <!-- Form Fields Row 2 -->
                    <div class="flex flex-col w-full gap-5 mb-6 md:flex-row">
                        <!-- Replace Graph -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Replace Graph (optional)</label>
                            <input 
                                type="file" 
                                ref="editFile" 
                                accept=".jpeg,.jpg,.png"
                                @change="handleEditGraph" 
                                class="w-full px-3 py-2 text-sm text-gray-900 placeholder-gray-400 bg-white border border-gray-200 rounded-xl shadow-sm cursor-pointer file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-teal-50 file:text-teal-700 file:font-semibold hover:file:bg-teal-100 file:transition-colors file:cursor-pointer focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            />
                            <p class="mt-1.5 text-xs text-gray-400">Supported formats: JPEG, JPG, or PNG only.</p>
                        </div>

                        <!-- Time (Hours) -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Time (Hours)</label>
                            <input
                                type="number"
                                v-model="patternNoHours"
                                placeholder="Input Hours..."
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 transition bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div v-if="uploadedGraphEdited" class="w-full mb-6">
                        <p class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Preview:</p>
                        <div class="text-center border border-gray-200 rounded-xl bg-gray-50/50 shadow-inner p-2">
                            <img :src="uploadedGraphEdited" alt="Edited Graph Preview" class="inline-block object-contain max-h-48"/>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-center w-full">
                        <button 
                            @click="updatePattern"
                            class="px-8 py-2.5 text-sm font-semibold text-white transition-all duration-200 rounded-xl shadow-sm bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-500/20"
                        >
                            Save Changes
                        </button>
                    </div>
                </div>
            </Modal>


            <Modal :show="showModalCreate" @close="showModalCreate = false">
                <div class="relative flex flex-col items-center justify-center w-full max-w-3xl p-8 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">
                    <!-- Close button -->
                    <button
                        @click="showModalCreate = false"
                        class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-50"
                        aria-label="Close modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Header -->
                    <h3 class="mb-6 text-lg font-bold text-center text-gray-900">Please fill out all the fields</h3>

                    <!-- Form fields row 1 -->
                    <div class="flex flex-col w-full gap-5 mb-5 md:flex-row">
                        <!-- Pattern No -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Pattern No.</label>
                            <input
                                type="number"
                                v-model="patternNo"
                                placeholder="Input Pattern Number..."
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 uppercase transition bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>

                        <!-- Furnace No -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Furnace No</label>
                            <select
                                v-model="selectedFurnace"
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            >
                                <option v-for="item in furnaceNo" :key="item" :value="item">{{ item }}</option>
                            </select>
                        </div>

                        <!-- Encoded By -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Encoded by</label>
                            <input
                                type="text"
                                v-model="encodedBy"
                                @input="encodedBy = encodedBy.toUpperCase()"
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 uppercase transition bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            />
                        </div>
                    </div>

                    <!-- Form fields row 2 -->
                    <div class="flex flex-col w-full gap-5 mb-6 md:flex-row">
                        <!-- Upload Graph -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Upload Graph</label>
                            <input
                                type="file"
                                id="cycleGraph"
                                accept=".jpeg,.jpg,.png"
                                @change="uploadGraph"
                                class="w-full px-3 py-1 text-sm text-gray-900 placeholder-gray-400 bg-white border border-gray-200 rounded-xl shadow-sm cursor-pointer file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-teal-50 file:text-teal-700 file:font-semibold hover:file:bg-teal-100 file:transition-colors file:cursor-pointer focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            />
                            <p class="mt-1.5 text-xs text-gray-400">Supported formats: JPEG, JPG, or PNG only.</p>
                        </div>

                        <!-- Time (Hours) -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Time (Hours)</label>
                            <input
                                type="number"
                                v-model="patternNoHours"
                                placeholder="Input Hours..."
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 transition bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>
                    </div>

                    <!-- File preview -->
                    <div v-if="uploadedGraph" class="w-full mt-2 mb-6">
                        <p class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Preview:</p>
                        <img
                            :src="uploadedGraph"
                            alt="Graph Preview"
                            class="object-contain w-full border border-gray-200 rounded-xl bg-gray-50/50 shadow-inner max-h-48 p-2"
                        />
                    </div>

                    <!-- Submit / Confirmation -->
                    <div v-if="!showConfirmation" class="flex justify-center w-full">
                        <button
                            @click="submitForm"
                            class="px-8 py-2.5 text-sm font-semibold text-white transition-all duration-200 rounded-xl shadow-sm bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-500/20"
                        >
                            Submit
                        </button>
                    </div>

                    <div v-else class="flex flex-col items-center justify-center gap-4 w-full bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                        <p class="text-sm font-medium text-center text-gray-700">
                            Are you <span class="font-bold text-rose-600">sure</span> that input is correct?
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="showConfirmation = false"
                                class="px-6 py-2 text-sm font-semibold text-gray-700 transition-colors duration-200 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 hover:text-gray-900"
                            >
                                No
                            </button>
                            <button
                                @click="saveToDatabase"
                                class="px-6 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-teal-600 rounded-xl shadow-sm hover:bg-teal-700"
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
import { router } from '@inertiajs/vue3';
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
                router.visit('/'); // Redirect if not authenticated
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50)); // small delay
        }

        if (!state.isAuthenticated) {
            router.visit('/'); // Redirect if not authenticated
            return false; // Indicate not authenticated
        }

        console.warn("USER AUTHENTICATED!");
        console.warn("Name: ", state.user.firstName + " " + state.user.surname);
        console.warn("Access: ", state.user.access_type);

        return true; // Indicate authenticated
    } catch (error) {
        console.error('Error checking authentication:', error);
        router.visit('/'); // Redirect on error
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
const patternNoHours = ref(0);
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
const editedGraphFile = ref(null);

// General Variables ---------------------------------- General Variables

const editPattern = (pattern) => {
    editId.value = pattern.id;
    patternNo.value = pattern.pattern_no;
    patternNoHours.value = pattern.pattern_no_hours;
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
    formData.append('pattern_no_hours', patternNoHours.value || '');
    formData.append('furnace_no', selectedFurnace.value || '');
    formData.append('encoded_by', encodedBy.value || '');

    if (editedGraphFile.value) {
        formData.append('graph', editedGraphFile.value);
    }

    // Spoof method for Laravel
    formData.append('_method', 'PATCH');

    try {
        const updateResponse = await axios.post(`/api/patterns/${editId.value}/update`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        toast.success('Pattern updated successfully');
        showModalEdit.value = false;
        console.log("Success: ", updateResponse.data);
        await userManageLogging('edited Graph Pattern no: '+ patternNo.value +' Encoded by: ' + encodedBy.value + ' successfully.');
    } catch (error) {
        toast.error('Failed to update pattern');
        console.error(error.response?.data || error);
    } finally {
        await getAllPatterns();
        editedGraphFile.value = null;
        uploadedGraphEdited.value = null;
    }
};

const handleEditGraph = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type.');
        event.target.value = '';
        uploadedGraphEdited.value = null;
        editedGraphFile.value = null;
        return;
    }

    editedGraphFile.value = file; // store file properly
    uploadedGraphEdited.value = URL.createObjectURL(file); // preview
}

const submitForm = () => {
    if(selectedFurnace.value == null || patternNo.value == null || patternNo.value == 0 || uploadedGraph.value == null || encodedBy.value == null){
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
    formData.append('furnace_no', selectedFurnace.value);

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
            pattern_no_hours: patternNoHours.value,
            furnace_no: selectedFurnace.value,
            encoded_by: encodedBy.value
        });

        await uploadGraphFinal();

        toast.success('Graph Pattern created successfully!');
        await userManageLogging('created Graph Pattern no: '+ patternNo.value +' Encoded by: ' + encodedBy.value + ' successfully.');
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors || {};

            if (errors.pattern_no || errors.furnace_no) {
                toast.error('This Pattern No + Furnace No combination already exists.');
            } else {
                toast.error('Validation failed. Check input values.');
            }

            console.log('Validation errors:', errors);
        } else if (error.response?.status === 404) {
            toast.error('Pattern + Furnace combination not found.');
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
        patternNoHours.value = 0;
        uploadedGraph.value = null;
        showConfirmation.value = false;
    }
};

const searchQuery = ref('');
const currentPage = ref(1);

const paginationMeta = ref({
    total: 0,
    per_page: 10,
    last_page: 1,
    from: 0,
    to: 0
});

const filteredPatterns = computed(() => {
  const q = String(searchQuery.value || '').toLowerCase(); // <-- coerce safely
  if (!q) return graphFileLists.value;

  return graphFileLists.value.filter(
    p =>
      String(p.pattern_no || '').includes(q) ||
      String(p.encoded_by || '').toLowerCase().includes(q)
  );
});

const getAllPatterns = async (page = 1) => {
    try {
        const response = await axios.get('/api/htgraph-patterns/list', {
            params: {
                page: page,
                search: searchQuery.value
            }
        });

        // Laravel pagination results nest data arrays inside response.data.data
        const backendResponse = response.data;
        
        graphFileLists.value = Array.isArray(backendResponse.data) 
            ? backendResponse.data 
            : Object.values(backendResponse.data || {});

        // Store pagination metrics safely
        currentPage.value = backendResponse.current_page;
        paginationMeta.value = {
            total: backendResponse.total || 0,
            per_page: backendResponse.per_page || 15,
            last_page: backendResponse.last_page || 1,
            from: backendResponse.from || 0,
            to: backendResponse.to || 0
        };

        console.log('Normalized Page Data:', graphFileLists.value);
    } catch (error) {
        console.error('Failed to fetch graphs', error);
    }
};

// Auto-fetch data if user switches pages
watch(currentPage, (newPage) => {
    getAllPatterns(newPage);
});

// Auto-reset to page 1 and fetch data when user searches
watch(searchQuery, () => {
    currentPage.value = 1;
    getAllPatterns(1);
});

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
