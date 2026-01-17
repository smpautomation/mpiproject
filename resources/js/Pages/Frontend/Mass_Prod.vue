<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-6 py-10 bg-gray-100">
        <!-- Top Action Bar -->
        <div class="flex items-center justify-between w-full mb-6 max-w-7xl">
            <h1 class="text-2xl font-bold text-gray-800">Mass Production Records</h1>
            <button
                @click="showModalCreate = true"
                class="px-5 py-2 font-semibold text-white transition-all bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                + Create New
            </button>
        </div>

        <!-- Card -->
        <div class="w-full p-8 space-y-6 bg-white border border-gray-200 shadow-xl max-w-7xl rounded-2xl">

            <!-- Filters -->
            <div class="flex flex-wrap items-end gap-6">

                <!-- Cycle No Search -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">
                        Cycle No Filter
                    </label>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search cycle no"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm w-72 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>

                <!-- Estimated Completion Date Range -->
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">
                        Estimated Completion Date Range
                    </label>

                    <div class="flex items-center gap-2">
                        <input
                            v-model="dateFrom"
                            type="date"
                            class="px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <span class="text-sm text-gray-500">to</span>
                        <input
                            v-model="dateTo"
                            type="date"
                            class="px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                </div>

            </div>


            <!-- Table -->
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Est. Date Time Finish</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Mass Prod</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Cycle No</th>
                            <th class="px-6 py-3 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="item in paginatedItems" :key="item.id" class="transition duration-150 hover:bg-gray-50">
                            <td class="px-6 py-4 text-center text-gray-800">{{ item.estimated_completion }}</td>
                            <td class="px-6 py-4 font-semibold text-center text-gray-900">{{ item.mass_prod }}</td>
                            <td class="px-6 py-4 text-center text-gray-800">{{ item.cycle_no }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <button
                                        @click="viewControlSheet(item.mass_prod, item.furnace)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500"
                                    >
                                        Control Sheet
                                    </button>
                                    <button
                                        @click="viewHTGraph(item.mass_prod, item.furnace)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500"
                                    >
                                        HT Graph
                                    </button>
                                    <button
                                        @click="viewSMPData(item.mass_prod, item.furnace)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 focus:ring-2 focus:ring-purple-500"
                                    >
                                        SMP Data
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="paginatedItems.length === 0">
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">No results found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4 text-sm">
                <div class="text-gray-600">
                    Showing {{ startIndex + 1 }}–{{ Math.min(endIndex, filteredItems.length) }} of {{ filteredItems.length }}
                </div>
                <div class="flex gap-2">
                    <button
                        @click="prevPage"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <button
                        @click="nextPage"
                        :disabled="endIndex >= filteredItems.length"
                        class="px-3 py-1 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Modern HT Mass Pro Dashboard - Compact Version -->
        <div class="flex flex-row gap-10">
                <div class="relative max-w-2xl p-8 mx-auto mt-10 overflow-hidden transition-all duration-300 bg-white border-t-4 shadow-xl rounded-2xl hover:shadow-2xl border-cyan-500">
                    <div class="absolute top-0 right-0 w-40 h-40 -mt-20 -mr-20 rounded-full opacity-50 bg-gradient-to-br from-cyan-100 to-teal-100"></div>
                    <div class="relative flex items-center gap-3 mb-8">
                        <div class="p-3 shadow-lg bg-gradient-to-br from-cyan-500 to-teal-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-transparent bg-gradient-to-r from-cyan-600 to-teal-600 bg-clip-text">
                                Upload HT Mass Pro
                            </h3>
                            <p class="text-sm text-slate-500">Import your production data</p>
                        </div>
                    </div>

                    <form @submit.prevent="submitExcelUpload" class="relative space-y-6">
                        <div class="space-y-2">
                            <label for="file" class="block text-sm font-bold tracking-wide uppercase text-slate-700">
                                Excel File
                            </label>
                            <input
                                id="file"
                                type="file"
                                accept=".xlsx,.xls,.csv"
                                @change="handleExcelFile"
                                class="block w-full text-slate-700 file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-cyan-500 file:to-teal-500 file:text-white hover:file:from-cyan-600 hover:file:to-teal-600 file:cursor-pointer file:transition-all file:duration-300 file:shadow-md hover:file:shadow-lg border-2 border-dashed border-slate-300 hover:border-cyan-400 rounded-xl p-3 transition-all duration-300 cursor-pointer bg-slate-50 hover:bg-cyan-50/50"
                            />
                            <div class="flex items-start gap-2 p-3 border-l-4 rounded-lg bg-cyan-50 border-cyan-400">
                                <svg class="w-4 h-4 text-cyan-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-cyan-800">
                                    Select your Excel file (.xlsx). Make sure it is not password-protected.
                                </p>
                            </div>
                        </div>

                        <button
                            type="submit"
                            :disabled="isUploadLoading"
                            class="w-full relative group bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-600 hover:to-teal-600 text-white font-bold rounded-xl px-6 py-3.5 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 overflow-hidden disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            <svg v-if="isUploadLoading" class="absolute w-5 h-5 text-white -translate-y-1/2 left-4 top-1/2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>

                            <span class="relative flex items-center justify-center gap-2">
                                <svg v-if="!isUploadLoading" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span>{{ isUploadLoading ? 'Uploading...' : 'Upload File' }}</span>
                            </span>
                        </button>
                    </form>
                </div>

            <!-- Monthly Summary Card -->
            <div class="relative max-w-2xl p-8 mx-auto mt-10 overflow-hidden transition-all duration-300 bg-white border-t-4 border-teal-500 shadow-xl rounded-2xl hover:shadow-2xl">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-40 h-40 -mt-20 -mr-20 rounded-full opacity-50 bg-gradient-to-br from-teal-100 to-cyan-100"></div>

                <!-- Header Section -->
                <div class="relative flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 shadow-lg bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-transparent bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text">
                                Monthly Summary
                            </h3>
                            <p class="text-sm text-slate-500">Generate reports</p>
                        </div>
                    </div>

                    <button
                        @click="downloadCsvMonthlySummary"
                        class="flex items-center gap-2 px-4 py-2.5 font-semibold text-white transition-all duration-300 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </button>
                </div>

                <!-- Latest Upload Highlight -->
                <div class="relative p-4 mb-6 shadow-sm rounded-xl"
                    :class="latestUploadDate && latestUploadCode
                            ? 'bg-gradient-to-r from-teal-50 to-cyan-50 border-l-4 border-teal-500'
                            : 'bg-gray-100 border-l-4 border-gray-400'">

                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" :class="latestUploadDate && latestUploadCode ? 'text-teal-600' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="latestUploadDate && latestUploadCode
                                        ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                        : 'M6 18L18 6M6 6l12 12'" />
                            </svg>
                            <span :class="latestUploadDate && latestUploadCode ? 'text-teal-800 font-bold' : 'text-gray-600 font-medium'" class="text-sm">
                                Latest HT Mass Pro Upload
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <template v-if="latestUploadDate && latestUploadCode">
                                <span class="bg-white text-teal-700 font-semibold px-3 py-1.5 rounded-lg shadow-sm border border-teal-200 text-sm">
                                    {{ latestUploadDate }}
                                </span>
                                <span class="bg-white text-cyan-700 font-semibold px-3 py-1.5 rounded-lg shadow-sm border border-cyan-200 text-sm">
                                    {{ latestUploadCode }}
                                </span>
                            </template>

                            <template v-else>
                                <span class="text-sm italic text-gray-500">No upload data available yet</span>
                            </template>
                        </div>
                    </div>
                </div>


                <!-- Selection Section -->
                <div class="relative flex flex-wrap gap-4 mb-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="mb-2 text-xs font-bold text-slate-700 uppercase tracking-wide flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Month
                        </label>
                        <div class="relative">
                            <select
                                v-model="selectedMonth"
                                class="w-full appearance-none px-4 py-2.5 bg-white border-2 border-slate-200 hover:border-cyan-400 focus:border-cyan-500 rounded-lg focus:ring-2 focus:ring-cyan-100 transition-all duration-300 cursor-pointer text-slate-700 font-semibold shadow-sm hover:shadow-md">
                                <option v-for="(month, index) in months" :key="index" :value="index + 1">
                                    {{ month }}
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <label class="mb-2 text-xs font-bold text-slate-700 uppercase tracking-wide flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Year
                        </label>
                        <div class="relative">
                            <select
                                v-model="selectedYear"
                                class="w-full appearance-none px-4 py-2.5 bg-white border-2 border-slate-200 hover:border-teal-400 focus:border-teal-500 rounded-lg focus:ring-2 focus:ring-teal-100 transition-all duration-300 cursor-pointer text-slate-700 font-semibold shadow-sm hover:shadow-md">
                                <option v-for="year in years" :key="year" :value="year">
                                    {{ year }}
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Text -->
                <div class="relative flex items-start gap-2 p-3 border-l-4 rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-400">
                    <svg class="w-4 h-4 text-cyan-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs leading-relaxed text-cyan-800">
                        Select the month and year to generate the summary. Download the Excel after reviewing.
                    </p>
                </div>
            </div>

        </div>

        <!-- Modal -->
        <Modal :show="showModalCreate" @close="showModalCreate = false">
            <div class="relative flex flex-col w-full max-w-2xl p-8 mx-auto bg-white shadow-2xl rounded-2xl">

                <!-- Exit -->
                <button
                    @click="showModalCreate = false"
                    class="absolute p-2 text-gray-400 transition rounded-full top-4 right-4 hover:bg-gray-100 hover:text-gray-600"
                >
                    ✕
                </button>

                <!-- Header -->
                <div class="flex flex-col mb-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Create New Mass Production</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Fill out the form below to register a new mass production record.
                        Ensure accuracy, as these records will be used for tracking furnaces, control sheets, and HT graphs.
                    </p>
                </div>

                <!-- Form -->
                <div class="flex flex-col w-full gap-6 mb-8">
                    <!-- Mass Prod Name -->
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Mass Production Name</label>
                        <input
                            type="text"
                            v-model="massProd_name"
                            @input="massProd_name = massProd_name.toUpperCase()"
                            placeholder="ex. 541ST"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <p class="mt-1 text-xs text-gray-400">Unique identifier for the mass production batch.</p>
                    </div>

                    <!-- Furnace No -->
                    <div>
                        <label class="block mb-1 text-sm font-semibold text-gray-700">Furnace No</label>

                        <div v-if="furnace_lists.length > 0">
                            <select
                            v-model="massProd_furnace"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <option disabled value="">Select a furnace...</option>
                            <option
                                v-for="furnace in furnace_lists"
                                :key="furnace.id ?? furnace"
                                :value="furnace.furnace_no ?? furnace"
                            >
                                {{ furnace.furnace_no ?? furnace }}
                            </option>
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Choose from available furnaces (e.g., K-40, K-42).</p>
                        </div>

                        <div v-else class="flex flex-col w-full gap-2 px-4 py-3 text-sm text-red-600 border border-red-300 rounded-lg shadow-sm bg-red-50">
                            <span class="font-medium">⚠ No furnaces available.</span>
                            <p class="text-gray-600">
                            You can add furnaces by going to
                            <span class="font-semibold text-blue-600">Options → Furnace</span> in the navigation bar.
                            </p>
                            <div class="flex gap-3 mt-2">
                            <button
                                @click="getFurnaceLists"
                                class="px-3 py-1 text-xs font-semibold text-white transition-colors bg-red-500 rounded-md hover:bg-red-600"
                            >
                                Retry
                            </button>
                            <button
                                @click="Inertia.visit('/furnace')"
                                class="px-3 py-1 text-xs font-semibold text-white transition-colors bg-blue-500 rounded-md hover:bg-blue-600"
                            >
                                Go to Furnace Page
                            </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation -->
                <div v-if="!showConfirmation" class="flex justify-end">
                <button
                    @click="submitForm"
                    :disabled="!massProd_furnace || furnace_lists.length === 0"
                    class="px-6 py-3 text-sm font-semibold text-white transition bg-blue-600 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-400 hover:bg-blue-700 disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed disabled:shadow-none"
                >
                    Submit
                </button>
                </div>


                <div v-else class="flex flex-col items-center gap-6">
                    <p class="text-base font-medium text-gray-800">Confirm your input before saving.</p>
                    <div class="flex gap-4">
                        <button
                            @click="showConfirmation = false"
                            class="px-6 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
                        >
                            Cancel
                        </button>
                        <button
                            @click="saveToDatabase"
                            class="px-6 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg shadow hover:bg-green-700"
                        >
                            Confirm & Save
                        </button>
                    </div>
                </div>

                <!-- Loading Overlay -->
                <DotsLoader
                    v-if="loadingState"
                    class="absolute inset-0 z-50 flex items-center justify-center bg-white bg-opacity-70 rounded-2xl"
                />
            </div>
        </Modal>

    </div>

    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted, nextTick, watch, computed, watchEffect, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3'
import DotsLoader from '@/Components/DotsLoader.vue';
import Modal from '@/Components/Modal.vue'; // adjust path if needed
import axios from 'axios';
import * as XLSX from 'xlsx';
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

const userManageLogging = async (logEvent) => {
    try{
        const responseUserLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Mass Production',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userManageLogging post request failed: ',error);
    }
}

const userErrorLogging = async (details, triggerFunction, title) => {
    try{
        const response = await axios.post('/api/error-logs', {
            user: state.user.firstName + " " + state.user.surname,
            title: title,
            details: details,
            trigger_function: triggerFunction,
            section: 'Mass Production',
        });

        //console.log('userErrorLogging-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userErrorLogging post request failed: ',error);
    }
}

const isUploadLoading = ref(false);

const latestUploadCode = ref(null);
const latestUploadDate = ref(null);

const showModalCreate = ref(false);
const showConfirmation = ref(false);
const loadingState = ref(false);

const massProd_name = ref('');
const massProd_furnace = ref();

const massProd_list = ref([]);
const furnace_lists = ref([]);

const selectedMonth = ref(new Date().getMonth() + 1); // default current month
const selectedYear = ref(new Date().getFullYear());   // default current year

const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

const years = ref([]);

const dateFrom = ref('');
const dateTo = ref('');

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 5;

const excelFile = ref(null)

// Handle file selection
function handleExcelFile(e) {
    excelFile.value = e.target.files[0] || null;
}

// Submit handler
const submitExcelUpload = async () => {
    if (!excelFile.value) return toast.error('Please select an Excel file');

    const formData = new FormData();
    formData.append('file', excelFile.value);

    try {
        isUploadLoading.value = true;
        const res = await axios.post('/api/upload-htmasspro-excel', formData);
        toast.success(res.data.message || 'Upload successful');
        await checkLatestUpload();
    } catch (err) {
        console.error(err);
        const msg = err.response?.data?.message || 'Upload failed';
        toast.error(msg);
    } finally {
        isUploadLoading.value = false;
    }
}


const getMassProdData = async () => {
    try {
        const responseMassProd = await axios.get('/api/mass-production/all-duplicates');
        massProd_list.value = responseMassProd.data;
        console.log("Mass Prod data list:  ",massProd_list.value);
    } catch (error) {
        console.error('Failed to fetch all mass production data:', error);
        toast.error('Failed to fetch mass production data.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getMassProdData",
            "Failed to fetch mass production data."
        );
    }
};

const getFurnaceLists = async() => {
    try{
        const response = await axios.get('/api/furnace-data');
        const furnaceData = response.data;
        furnace_lists.value = furnaceData.map(item => item.furnace_name);
        console.log("Furnace Lists: ", furnace_lists.value);
    }catch(error){
        console.error('Failed to fetch furnace data lists: ',error);
        toast.error('Furnace Data List error.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getFurnaceLists",
            "Furnace Data List error."
        );
    }
}

const filteredItems = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    const from = dateFrom.value;
    const to = dateTo.value;

    return massProd_list.value.filter(item => {

        /* ---------------- Cycle No filter ---------------- */
        let cycleMatch = true;
        if (q) {
            cycleMatch = item.cycle_no
                ? item.cycle_no.toLowerCase().includes(q)
                : false;
        }

        /* -------- Estimated Completion date filter -------- */
        let dateMatch = true;

        if (from || to) {
            // date filter is active → item MUST have a date
            if (!item.estimated_completion) return false;

            const itemDate = new Date(item.estimated_completion)
                .toISOString()
                .split('T')[0];

            if (from && itemDate < from) return false;
            if (to && itemDate > to) return false;
        }

        return cycleMatch && dateMatch;
    });
});


const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage)
const endIndex = computed(() => startIndex.value + itemsPerPage)
const paginatedItems = computed(() =>
    filteredItems.value.slice(startIndex.value, endIndex.value)
);

const nextPage = () => {
  if (endIndex.value < filteredItems.value.length) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

watch([searchQuery, dateFrom, dateTo], () => {
    currentPage.value = 1;
});

const viewControlSheet = (massprod, furnace) => {
    Inertia.visit('/control_sheet',{
        method: 'get',
        data: { massProd: massprod, furnace: furnace },
        preserveState: true,
        preserveScroll: true,
    });
}

const viewHTGraph = (massprod, furnace) => {
    Inertia.visit('/htgraph',{
        method: 'get',
        data: { massProd: massprod, furnace: furnace },
        preserveScroll: true,
        preserveState: true,
    });
}

const viewSMPData = (massprod, furnace) => {
    Inertia.visit('/smpdata',{
        method: 'get',
        data: { massProd: massprod, furnace: furnace },
        preserveState: true,
        preserveScroll: true,
    });
}

const isValidMassProdName = (value) => {
    if (!value) return false;

    const match = value.match(/^([0-9]+)(ST|ND|RD|TH)$/);
    if (!match) return false;

    const num = parseInt(match[1], 10);
    const suffix = match[2];

    const lastTwo = num % 100;
    const lastOne = num % 10;

    let correctSuffix;

    if (lastTwo >= 11 && lastTwo <= 13) {
        correctSuffix = 'TH';
    } else {
        if (lastOne === 1) correctSuffix = 'ST';
        else if (lastOne === 2) correctSuffix = 'ND';
        else if (lastOne === 3) correctSuffix = 'RD';
        else correctSuffix = 'TH';
    }

    return suffix === correctSuffix;
};

const submitForm = async () => {
    const name = massProd_name.value?.trim();

    if (!name || !massProd_furnace.value || !isValidMassProdName(name)) {
        toast.error('Invalid format. Example: 221ST, 222ND, 223RD, 111TH');
        showModalCreate.value = false;

        massProd_name.value = '';
        massProd_furnace.value = '';

        return;
    }

    showConfirmation.value = true;
};

const saveToDatabase = async () => {
    loadingState.value = true // start loading
    try {
        const response = await axios.post('/api/mass-production', {
            mass_prod: massProd_name.value,
            furnace: massProd_furnace.value
        });

        if (response.status >= 200 && response.status < 300) {
            toast.success('Mass Production created successfully!');
            showModalCreate.value = false;
            getMassProdData();
        } else {
            toast.error('Failed to create Mass Production.');
        }
    } catch (error) {
        console.error('Error creating Mass Production:', error);
        toast.error('An error occurred while creating Mass Production.');
        await userErrorLogging(
            {
                message: error.message,
                endpoint: error.config?.url,
                status: error.response?.status ?? null,
                payload: error.response?.data ?? null,
            },
            "saveToDatabase",
            "An error occurred while creating Mass Production."
        );
    } finally {
        loadingState.value = false // stop loading
        await userManageLogging('created '+ massProd_name.value +' Mass Production | Furnace : ' + massProd_furnace.value);
        massProd_name.value = '';
        massProd_furnace.value = '';
        showConfirmation.value = false;
    }
};

const checkLatestUpload = async () => {
    try {
        const { data } = await axios.get('/api/check-latest-masspro-upload');

        if (data?.data) {
            latestUploadDate.value = data.data.upload_date || 'N/A';
            latestUploadCode.value = data.data.upload_code || 'N/A';
        } else {
            latestUploadDate.value = 'N/A';
            latestUploadCode.value = 'N/A';
        }

    } catch (error) {
        console.error('Failed to fetch the latest mass prod upload', error);
        latestUploadDate.value = 'N/A';
        latestUploadCode.value = 'N/A';
        toast.error('Failed to fetch the latest mass prod upload');
    }
};

const downloadCsvMonthlySummary = async () => {
    try {
        const month = selectedMonth.value;
        const year  = selectedYear.value;

        // basic guard
        if (!month || !year) return;

        const { data } = await axios.get('/api/monthly-summary', {
        params: { month, year }
        });

        if (!Array.isArray(data) || data.length === 0) {
            const monthName = months[month - 1];
            toast.warning(`No data available for the month of ${monthName}`);
            return;
        }

        // build excel
        const worksheet = XLSX.utils.json_to_sheet(data);
        const workbook  = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Monthly Summary');

        const filename = `GBDP MPI SYSTEM ${months[month - 1]} HT SUMMARY MONTHLY ${year}.xlsx`;
        XLSX.writeFile(workbook, filename);

    } catch (error) {
        console.error('Failed to generate Excel:', error);
        toast.error('Failed to generate monthly summary');
    }
};



onMounted(async () => {
    await checkAuthentication();
    await getMassProdData();
    await getFurnaceLists();
    await checkLatestUpload();
    const currentYear = new Date().getFullYear();
    for (let y = currentYear; y >= 2020; y--) {
        years.value.push(y);
    }
});

</script>
