<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center min-h-screen px-4 py-2 bg-gray-100">
            <div class="flex flex-row space-x-8 whitespace-nowrap mt-10">
                <!-- Header Selection Section -->
                <div class="w-full max-w-4xl mb-12">
                    <div class="px-8 py-6 bg-white border border-gray-200 shadow-lg rounded-xl">
                        <!-- Header -->
                        <div class="mb-6 pb-4 border-b border-gray-200">
                            <div class="flex items-start gap-3">
                                <div class="w-1 h-14 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full flex-shrink-0"></div>
                                <div class="flex-1">
                                    <h2 class="text-2xl font-bold text-gray-800 mb-1">
                                        GBDP Heat Treatment & Coating Information
                                    </h2>
                                    <p class="text-sm text-gray-600">
                                        Select mass production and layer to view detailed process information
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <!-- Mass Production -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Mass Production Name <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="selectedMassProd"
                                    class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white transition-all cursor-pointer"
                                >
                                    <option value="" disabled>Select Mass Production</option>
                                    <option v-for="items in massProd_names" :key="items" :value="items">
                                        {{ items }}
                                    </option>
                                </select>
                            </div>

                            <!-- Layer -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">
                                    Layer <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="selectedLayer"
                                    class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white transition-all cursor-pointer"
                                >
                                    <option value="" disabled>Select Layer</option>
                                    <option v-for="items in layers" :key="items" :value="items">
                                        {{ items }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="my-6">
                <h2 class="relative text-2xl font-bold text-gray-800 pb-2 tracking-wide uppercase inline-block">
                    Heat Treatment
                    <span class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full shadow-[0_0_8px_rgba(20,184,166,0.5)]"></span>
                </h2>
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
                                <input v-model="gbdp_1st.furnaceNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Batch Cycle No</label>
                                <input v-model="gbdp_1st.batchCycleNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Date Start <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.dateStart" type="date" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Time Start <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.timeStart" type="time" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Cycle Pattern <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.cyclePattern" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="space-y-3">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Cycle No <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.cycleNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Pattern No <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.patternNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Date Finish <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.dateFinish" type="date" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Time Finish <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.timeFinish" type="time" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-700">Current Pattern <span class="text-red-500">*</span></label>
                                <input v-model="gbdp_1st.currentPattern" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
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
                                <input v-model="gbdp_2nd.batchCycleNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed" />
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

            <div class="my-6">
                <h2 class="relative text-2xl font-bold text-gray-800 pb-2 tracking-wide uppercase inline-block">
                    Coating
                    <span class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full shadow-[0_0_8px_rgba(20,184,166,0.5)]"></span>
                </h2>
            </div>
            <!-- GBDP Sections -->
            <div class="flex flex-col w-full max-w-6xl gap-8 md:flex-row whitespace-nowrap">
                <!-- 1ST GBDP -->
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">1st GBDP</h2>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Date<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.coatingDate" type="date" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Machine No<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.coatingMachineNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Slurry Lot No<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.slurryLotNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">MIN TB CONTENT (μg/mm²)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.minTbContent" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.lotNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight (KG)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.totalMagnetWeight" type="number" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                        <!-- Remarks Input -->
                        <div class="flex flex-col">
                            <label class="block mb-2 text-xs font-semibold text-gray-700">
                            Remarks <span class="text-red-500">*</span>
                            </label>
                            <input
                            v-model="coatingInfo_1stgbdp.remarks"
                            type="text"
                            disabled
                            class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter remarks here..."
                            />
                        </div>
                    </div>
                </div>

                <!-- 2ND GBDP -->
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">2nd GBDP</h2>

                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Date<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_2ndgbdp.coatingDate" type="date" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Machine No<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_2ndgbdp.coatingMachineNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Slurry Lot No<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_2ndgbdp.slurryLotNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">MIN TB CONTENT (μg/mm²)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_2ndgbdp.minTbContent" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_2ndgbdp.lotNo" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight (KG)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_2ndgbdp.totalMagnetWeight" type="number" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>


                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                        <!-- Remarks Input -->
                        <div class="flex flex-col">
                            <label class="block mb-2 text-xs font-semibold text-gray-700">
                            Remarks <span class="text-red-500">*</span>
                            </label>
                            <input
                            v-model="coatingInfo_2ndgbdp.remarks"
                            type="text"
                            disabled
                            class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter remarks here..."
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-row gap-10 mt-10 items">
                <div class="px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl max-w-8xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">1st GBDP Coating Data (Unit: µ/mm²)</h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">

                    <!-- Coating Amount Tables -->
                    <div class="flex flex-row gap-4">
                        <div
                            v-for="(slice, colIndex) in Math.ceil(coatingsTable_1stgbdp.length / 10)"
                            :key="colIndex"
                            class="overflow-x-auto"
                        >
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="px-2 py-1 text-left border-r border-gray-300">No.</th>
                                <th class="px-2 py-1 text-left">Coating</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white">
                            <tr
                                v-for="item in coatingsTable_1stgbdp.slice(colIndex * 10, (colIndex + 1) * 10)"
                                :key="item.no"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-3 py-1 border-b border-r border-gray-200">{{ item.no }}</td>
                                <td class="px-3 py-1 border-b border-gray-200">{{ item.coating ?? '-' }}</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- Concentration Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                        <thead class="text-center bg-gray-100">
                            <tr>
                            <th colspan="8" class="text-center border-b border-gray-300">Concentration Amount</th>
                            </tr>
                            <tr>
                            <th class="px-2 py-1 border-r border-gray-300"></th>
                            <th v-for="module in modules" :key="module" class="px-2 py-1 border-r border-gray-300">
                                {{ module }}
                            </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr v-for="(range, rowIndex) in visibleRanges_1stGBDP" :key="range" class="hover:bg-gray-50">
                            <td class="px-2 py-1 border-b border-r border-gray-200">{{ range }}</td>
                            <td v-for="(module, colIndex) in modules" :key="colIndex" class="px-2 py-1 border-b border-r border-gray-200">
                                {{ visibleConcentrationData_1stGBDP[rowIndex][colIndex] ?? '-' }}
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>


            <div class="flex flex-row gap-10 my-10 items">
                <div class="px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl max-w-8xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">2nd GBDP Coating Data (Unit: µ/mm²)</h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">

                    <!-- Coating Amount Tables -->
                    <div class="flex flex-row gap-4">
                        <div
                        v-for="(slice, colIndex) in Math.ceil(coatingsTable_2ndgbdp.length / 10)"
                        :key="colIndex"
                        class="overflow-x-auto"
                        >
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="px-2 py-1 text-left border-r border-gray-300">No.</th>
                                <th class="px-2 py-1 text-left">Coating</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white">
                            <tr
                                v-for="item in coatingsTable_2ndgbdp.slice(colIndex * 10, (colIndex + 1) * 10)"
                                :key="item.no"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-3 py-1 border-b border-r border-gray-200">{{ item.no }}</td>
                                <td class="px-3 py-1 border-b border-gray-200">{{ item.coating ?? '-' }}</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- Concentration Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                        <thead class="text-center bg-gray-100">
                            <tr>
                            <th colspan="8" class="text-center border-b border-gray-300">Concentration Amount</th>
                            </tr>
                            <tr>
                            <th class="px-2 py-1 border-r border-gray-300"></th>
                            <th v-for="module in modules" :key="module" class="px-2 py-1 border-r border-gray-300">
                                {{ module }}
                            </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr v-for="(range, rowIndex) in visibleRanges_2ndGBDP" :key="range" class="hover:bg-gray-50">
                            <td class="px-2 py-1 border-b border-r border-gray-200">{{ range }}</td>
                            <td
                                v-for="(module, colIndex) in modules"
                                :key="colIndex"
                                class="px-2 py-1 border-b border-r border-gray-200"
                            >
                                {{ visibleConcentrationData_2ndGBDP[rowIndex][colIndex] ?? '-' }}
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
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

//Dev Controls ----------------- Allow Commands


const showModalCreate = ref(false);
const selectedMassProd = ref();
const massProd_names = ref([]);
const selectedLayer = ref();
const layers = ref([]);
const graph_patterns = ref([]);
const furnace_lists = ref([]);

// 1st 2nd gbdp INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!!

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

const coatingInfo_1stgbdp = reactive({
    coatingDate: '',
    coatingMachineNo: '',
    slurryLotNo: '',
    minTbContent: '',
    totalMagnetWeight: '',
    lotNo: '',
    remarks: ''
});

const coatingInfo_2ndgbdp = reactive({
    coatingDate: '',
    coatingMachineNo: '',
    slurryLotNo: '',
    minTbContent: '',
    totalMagnetWeight: '',
    lotNo: '',
    remarks: ''
});

const coatingsTable_1stgbdp = ref(
    Array.from({ length: 20 }, (_, i) => ({
        no: i + 1,
        coating: null
    }))
);

const coatingsTable_2ndgbdp = ref(
    Array.from({ length: 20 }, (_, i) => ({
        no: i + 1,
        coating: null
    }))
);

const modules = ["M-01", "M-02", "M-03", "M-04", "M-05", "M-06", "M-06"];
const ranges_1stgbdp = ["1-5", "6-10", "11-15", "16-20"];
const ranges_2ndgbdp = ["1-5", "6-10", "11-15", "16-20"];

// 1stGBDP reactive table
const concentrationData_1stGBDP = ref(
  ranges_1stgbdp.map(() => modules.map(() => null))
);
const concentrationData_2ndGBDP = ref(
  ranges_2ndgbdp.map(() => modules.map(() => null))
);

// 1stGBDP version (limited to 1–20)
const visibleRanges_1stGBDP = computed(() => ranges_1stgbdp);
const visibleConcentrationData_1stGBDP = computed(() => concentrationData_1stGBDP.value);
const visibleRanges_2ndGBDP = computed(() => ranges_2ndgbdp);
const visibleConcentrationData_2ndGBDP = computed(() => concentrationData_2ndGBDP.value);

// 1st 2nd gbdp INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! END

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

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

const getMassProdLayers = async () => {
    try{
        // ✅ Fetch available layers for the selected mass production
        const layersResponse = await axios.get(`/api/second-heat-treatment-data/${selectedMassProd.value}/layers`);
        layers.value = layersResponse.data.layers || [];

        console.log('Available Layers: ', layers.value);
    }catch(error){
        console.error('Layers for this mass production does not exists.', error);
    }
}

const getCurrentMassProdData = async () => {
    try {
        // Fetch both the specific mass prod + layer data
        const response = await axios.get(`/api/second-ht-data/${selectedMassProd.value}/layer/${selectedLayer.value}`);
        const massProdData = response.data?.data || response.data;

        if (!massProdData) {
            toast.error(`No data found for Mass Production: ${selectedMassProd.value}`);
            return;
        }

        console.log('Data:', massProdData);

        // Safely parse JSON fields
        const gbdp1 = JSON.parse(massProdData.gbdp_1st || '{}');
        const gbdp2 = JSON.parse(massProdData.gbdp_2nd || '{}');

        // Map to reactivity objects
        Object.assign(gbdp_1st, {
            batchCycleNo: gbdp1.batch_cycle_no || '',
            furnaceNo: gbdp1.furnace_machine || '',
            cycleNo: gbdp1.cycle_no || '',
            patternNo: gbdp1.pattern_no || '',
            cyclePattern: gbdp1.cycle_pattern || '',
            currentPattern: gbdp1.current_pattern || '',
            dateStart: gbdp1.date_start || '',
            timeStart: gbdp1.time_start || '',
            dateFinish: gbdp1.date_finished || '',
            timeFinish: gbdp1.time_finished || ''
        });

        Object.assign(gbdp_2nd, {
            batchCycleNo: gbdp2.batch_cycle_no || '',
            furnaceNo: gbdp2.furnace_machine || '',
            cycleNo: gbdp2.cycle_no || '',
            patternNo: gbdp2.pattern_no || '',
            cyclePattern: gbdp2.cycle_pattern || '',
            currentPattern: gbdp2.current_pattern || '',
            dateStart: gbdp2.date_start || '',
            timeStart: gbdp2.time_start || '',
            dateFinish: gbdp2.date_finished || '',
            timeFinish: gbdp2.time_finished || ''
        });

        console.log('GBDP 1st:', gbdp_1st);
        console.log('GBDP 2nd:', gbdp_2nd);

        const response2 = await axios.get(`/api/second-coating-data/${selectedMassProd.value}/layer/${selectedLayer.value}`);
        const coatingData = response2.data?.data || response2.data;

        if (!coatingData) {
            toast.error(`No data found for Mass Production: ${selectedMassProd.value}`);
            return;
        }

        console.log('Coating Data:', coatingData);

        // ✅ Map Coating Info (1st GBDP)
        Object.assign(coatingInfo_1stgbdp, {
            coatingDate: coatingData.coating_info_1stgbdp?.date || '',
            coatingMachineNo: coatingData.coating_info_1stgbdp?.machine_no || '',
            slurryLotNo: coatingData.coating_info_1stgbdp?.slurry_lot_no || '',
            minTbContent: coatingData.coating_info_1stgbdp?.min_tb_content || '',
            totalMagnetWeight: coatingData.coating_info_1stgbdp?.total_magnet_weight || '',
            lotNo: coatingData.coating_data_1stgbdp?.['Lot no'] || '',
            remarks: coatingData.coating_info_1stgbdp?.remarks || ''
        });

        // ✅ Map Coating Info (2nd GBDP)
        Object.assign(coatingInfo_2ndgbdp, {
            coatingDate: coatingData.coating_info_2ndgbdp?.date || '',
            coatingMachineNo: coatingData.coating_info_2ndgbdp?.machine_no || '',
            slurryLotNo: coatingData.coating_info_2ndgbdp?.slurry_lot_no || '',
            minTbContent: coatingData.coating_info_2ndgbdp?.min_tb_content || '',
            totalMagnetWeight: coatingData.coating_info_2ndgbdp?.total_magnet_weight || '',
            lotNo: coatingData.coating_data_2ndgbdp?.['Lot no'] || '',
            remarks: coatingData.coating_info_2ndgbdp?.remarks || ''
        });

        console.log('Coating Info 1st GBDP:', coatingInfo_1stgbdp);
        console.log('Coating Info 2nd GBDP:', coatingInfo_2ndgbdp);

        // ✅ Parse coating data JSON (if string)
        const coatingData1 = typeof coatingData.coating_data_1stgbdp === 'string'
            ? JSON.parse(coatingData.coating_data_1stgbdp)
            : coatingData.coating_data_1stgbdp || {};

        const coatingData2 = typeof coatingData.coating_data_2ndgbdp === 'string'
            ? JSON.parse(coatingData.coating_data_2ndgbdp)
            : coatingData.coating_data_2ndgbdp || {};


        // ✅ Map Coating Amount Data → coatingsTable_1stgbdp
        if (Array.isArray(coatingData1['Coating Amount Data'])) {
            coatingsTable_1stgbdp.value = coatingData1['Coating Amount Data'].map(item => ({
                no: item.no ?? null,
                coating: item.coating ?? null
            }));
        }

        // ✅ Map Coating Amount Data → coatingsTable_2ndgbdp
        if (Array.isArray(coatingData2['Coating Amount Data'])) {
            coatingsTable_2ndgbdp.value = coatingData2['Coating Amount Data'].map(item => ({
                no: item.no ?? null,
                coating: item.coating ?? null
            }));
        }


        // ✅ Map Concentration Data → concentrationData_1stGBDP
        if (Array.isArray(coatingData1['Concentration Data'])) {
            concentrationData_1stGBDP.value = coatingData1['Concentration Data'].map(rangeGroup =>
                rangeGroup.modules.map(m => m.value ?? null)
            );
        }

        // ✅ Map Concentration Data → concentrationData_2ndGBDP
        if (Array.isArray(coatingData2['Concentration Data'])) {
            concentrationData_2ndGBDP.value = coatingData2['Concentration Data'].map(rangeGroup =>
                rangeGroup.modules.map(m => m.value ?? null)
            );
        }


        // ✅ Debug logs
        console.log('Coating Table 1st GBDP:', coatingsTable_1stgbdp.value);
        console.log('Coating Table 2nd GBDP:', coatingsTable_2ndgbdp.value);
        console.log('Concentration Data 1st GBDP:', concentrationData_1stGBDP.value);
        console.log('Concentration Data 2nd GBDP:', concentrationData_2ndGBDP.value);

        toast.success(`Data fetched successfully for ${selectedMassProd.value}`);
    } catch (error) {
        console.error('Error fetching current mass prod data', error);
        //toast.error('Failed to get current mass prod data — API error');
    }
};


watch([selectedMassProd, selectedLayer], async ([newProd, newLayer], [oldProd, oldLayer]) => {
    if (!newProd && !newLayer) return;

    if (newProd !== oldProd) {
        console.log(`Mass Production changed from ${oldProd} → ${newProd}`);
    }

    if (newLayer !== oldLayer) {
        console.log(`Layer changed from ${oldLayer} → ${newLayer}`);
    }

    await getCurrentMassProdData();
});

watch(selectedMassProd, async (newValue, oldValue) => {
    if (!newValue) return;
    console.log(`Mass Production changed from ${oldValue} → ${newValue}`);
    await getMassProdLayers();
});

// Trigger Based Fetching below ----- Trigger Based Fetching below ----- Trigger Based Fetching below

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE END


onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    await getMassProdLists();
    //await getFurnaceLists();
    //await getGraphPatterns();
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
