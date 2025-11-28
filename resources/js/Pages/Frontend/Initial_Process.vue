<template>
    <div class="flex min-h-screen bg-cyan-50">
        <!-- Sidebar -->
        <aside class="flex flex-col w-64 text-white bg-teal-600">
            <!-- Logo -->
            <div class="flex items-center px-6 py-4 space-x-3 cursor-pointer group">
                <!-- Magnetic Logo Container -->
                <div class="relative flex-shrink-0 w-16 h-16">
                    <!-- Outer glow -->
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-xl blur-md group-hover:opacity-80"></div>

                    <!-- Main magnet box -->
                    <div class="relative w-full h-full p-2 transition-all duration-300 shadow-lg bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-2xl">
                        <svg viewBox="0 0 100 100" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <!-- Magnetic field lines (brighter) -->
                            <g class="animate-pulse">
                                <path d="M20 50 Q30 30, 50 30 Q70 30, 80 50" fill="none" stroke="rgba(0,255,255,0.6)" stroke-width="2"/>
                                <path d="M20 50 Q30 70, 50 70 Q70 70, 80 50" fill="none" stroke="rgba(0,255,255,0.6)" stroke-width="2"/>
                            </g>

                            <!-- Magnet poles (stronger colors) -->
                            <g class="transition-transform group-hover:scale-105">
                                <rect x="15" y="35" width="20" height="30" rx="3" fill="#ffffff" opacity="1"/>
                                <text x="25" y="55" font-size="16" font-weight="bold" fill="#00ffff" text-anchor="middle">N</text>

                                <rect x="65" y="35" width="20" height="30" rx="3" fill="#ffffff" opacity="1"/>
                                <text x="75" y="55" font-size="16" font-weight="bold" fill="#00e5b4" text-anchor="middle">S</text>
                            </g>

                            <!-- Center attraction particles -->
                            <circle cx="50" cy="50" r="3" fill="cyan" opacity="0.9" class="animate-ping"/>
                            <circle cx="50" cy="50" r="2" fill="cyan"/>
                        </svg>
                    </div>
                </div>

                <!-- Text -->
                <div class="flex flex-col justify-center">
                    <span class="text-xl font-extrabold leading-none text-transparent whitespace-nowrap bg-clip-text bg-gradient-to-r from-cyan-400 to-teal-300 group-hover:from-cyan-300 group-hover:to-teal-200 drop-shadow-md">
                        GBDP MPI
                    </span>
                    <span class="text-sm font-semibold text-gray-100 whitespace-nowrap group-hover:text-white">
                        Online System
                    </span>
                </div>
            </div>


            <!-- Navigation -->
            <nav class="flex-1 px-4 space-y-2">
                <button @click="activeSection = 'control_sheet'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Control Sheet</button>
                <button @click="activeSection = 'coating'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Coating Summary</button>
                <button @click="activeSection = 'film_pasting'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Film Pasting Summary</button>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
        <!-- Topbar -->
        <div class="flex items-center justify-between p-4 mb-6 text-white rounded-lg shadow bg-cyan-500">
            <h1 class="text-lg font-semibold">{{ sectionTitle }}</h1>
            <div class="flex space-x-4">
                <button @click="Inertia.visit('/')" class="px-3 py-1 rounded bg-cyan-400 hover:bg-cyan-300">Return Home</button>
            </div>
        </div>

        <!-- Section Content -->
        <div v-if="activeSection === 'control_sheet'">
            <div class="flex flex-row justify-center gap-0">
                <div class="max-w-4xl px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-1 h-8 rounded-full bg-gradient-to-b from-cyan-500 to-teal-500"></div>
                            <div>
                            <h2 class="text-xl font-bold text-gray-900">Mass Production Control Sheet</h2>
                             <p class="text-sm text-gray-500">Fill up all details below. Fields with <span class="font-semibold text-red-500">*</span> are required</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group: Box Selection -->
                    <div class="grid grid-cols-1 gap-4 md:gap-6">

                    <!-- Main Boxes -->
                    <div class="grid items-end grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
                        <!-- Start Box -->
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Start Box <span class="text-red-500">*</span></label>
                        <select v-model="initial_mpcs.selectedBoxStart" class="w-full text-xs border-gray-300 rounded-lg shadow-sm">
                            <option v-for="item in allBoxes" :key="item" :value="item">{{ item }}</option>
                        </select>
                        </div>

                        <!-- End Box -->
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">End Box <span class="text-red-500">*</span></label>
                        <select v-model="initial_mpcs.selectedBoxEnd" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option v-for="item in boxesEndList" :key="item" :value="item">{{ item }}</option>
                        </select>
                        </div>

                        <!-- Toggle Excess -->
                        <div class="flex items-end">
                            <button
                                @click="initial_mpcs.moreThanTenBoxes = !initial_mpcs.moreThanTenBoxes"
                                type="button"
                                :class="[
                                'w-full px-3 py-2 text-xs font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2',
                                initial_mpcs.moreThanTenBoxes
                                    ? 'bg-blue-600 text-white border-blue-700 hover:bg-blue-700 focus:ring-blue-500'
                                    : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200 focus:ring-gray-400'
                                ]"
                            >
                                {{ initial_mpcs.moreThanTenBoxes ? 'Excess Boxes Enabled' : '> 10 Boxes' }}
                            </button>
                        </div>

                    </div>

                    <!-- Excess Boxes -->
                    <div v-if="initial_mpcs.moreThanTenBoxes" class="grid grid-cols-1 gap-4 pt-2 border-t border-gray-200 sm:grid-cols-2 md:gap-6">
                        <!-- Excess Start Box -->
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Excess Start Box <span class="text-red-500">*</span></label>
                        <select v-model="initial_mpcs.selectedExcessBoxStart" class="w-full text-xs border-gray-300 rounded-lg shadow-sm">
                            <option v-for="item in allBoxes" :key="item" :value="item">{{ item }}</option>
                        </select>
                        </div>

                        <!-- Excess End Box -->
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Excess End Box <span class="text-red-500">*</span></label>
                        <select v-model="initial_mpcs.selectedExcessBoxEnd" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option v-for="item in excessBoxesEndList" :key="item" :value="item">{{ item }}</option>
                        </select>
                        </div>
                    </div>

                    </div>


                    <!-- Group: Basic Info -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Model<span class="text-red-500"> *</span></label>
                            <select v-model="initial_mpcs.selectedModel" class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50">
                                <option v-for="item in model_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating M/C No.<span class="text-red-500"> *</span></label>
                            <input v-model="initial_mpcs.coatingMCNo" type="text" @input="initial_mpcs.coatingMCNo = initial_mpcs.coatingMCNo.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Raw Material Code<span class="text-red-500"> *</span></label>
                            <input v-model="initial_mpcs.rawMaterialCode" type="text" @input="initial_mpcs.rawMaterialCode = initial_mpcs.rawMaterialCode.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>

                    <!-- Group: Lot + Qty -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot No.<span class="text-red-500"> *</span></label>
                            <input v-model="initial_mpcs.lotNo" type="text"
                                @input="initial_mpcs.lotNo = initial_mpcs.lotNo.toUpperCase()"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Qty (PCS)<span class="text-red-500"> *</span>
                            </label>
                            <input
                                v-model="initial_mpcs.qty"
                                type="number"
                                :disabled="manualQtyMode"
                                :class="[
                                    'w-full text-xs border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500',
                                    manualQtyMode
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-300'
                                        : 'border-gray-300'
                                ]"
                            />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700 whitespace-nowrap">
                                Qty (PCS) - Last Box<span class="text-red-500"> *</span>
                            </label>
                            <input
                                v-model="initial_mpcs.qty_lastBox"
                                type="number"
                                :disabled="manualQtyMode"
                                :class="[
                                    'w-full text-xs border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500',
                                    manualQtyMode
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-300'
                                        : 'border-gray-300'
                                ]"
                            />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating<span class="text-red-500"> *</span></label>
                            <input v-model="initial_mpcs.coating" type="number"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>

                    <!-- Group: Prepared By -->
                    <div class="grid items-end grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Magnet Prepared By -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Magnet Prepared By<span class="text-red-500"> *</span>
                            </label>
                            <input
                                v-model="initial_mpcs.magnetPreparedBy"
                                type="text"
                                @input="initial_mpcs.magnetPreparedBy = initial_mpcs.magnetPreparedBy.toUpperCase()"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>

                        <!-- Box Prepared By -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Box Prepared By
                            </label>
                            <input
                                v-model="initial_mpcs.boxPreparedBy"
                                type="text"
                                @input="initial_mpcs.boxPreparedBy = initial_mpcs.boxPreparedBy.toUpperCase()"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                    </div>
                </div>

                <div class="max-w-4xl px-8 py-8 mx-auto space-y-6 bg-white border border-gray-200 shadow-xl rounded-2xl md:px-12">

                    <!-- BOX No. table -->
                    <p class="pb-2 text-sm font-semibold text-gray-800 border-b">
                        BOX No. <span class="text-gray-300">(example: UBP85172)</span><span class="text-red-500"> *</span>
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-collapse border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                            <th v-for="box in visibleBoxes" :key="box" class="px-4 py-2 text-xs border border-gray-300">
                                {{ box }}
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td v-for="box in visibleBoxes" :key="box" class="px-2 py-1 border border-gray-300">
                                <input
                                v-model="boxNoValues[box]"
                                @input="boxNoValues[box] = boxNoValues[box].toUpperCase()"
                                type="text"
                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                />
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>

                    <!-- Weight table -->
                    <p class="pb-2 text-sm font-semibold text-gray-800 border-b">
                        Weight <span class="text-gray-300">(KG)</span><span class="text-red-500"> *</span>
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-collapse border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                            <th v-for="box in visibleBoxes" :key="box" class="px-4 py-2 text-xs border border-gray-300">
                                {{ box }}
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td v-for="box in visibleBoxes" :key="box" class="px-2 py-1 border border-gray-300">
                                <input
                                v-model="weightValues[box]"
                                type="number"
                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                />
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>

                    <!-- Excess Boxes Section -->
                    <div v-if="initial_mpcs.moreThanTenBoxes" class="mt-8">
                        <!-- BOX No. table -->
                        <p class="pb-2 text-sm font-semibold text-gray-800 border-b">
                            Excess BOX No. <span class="text-gray-300">(example: UBP85172)</span>
                        </p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-center border border-collapse border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th v-for="box in visibleExcessBoxes" :key="box" class="px-4 py-2 text-xs border border-gray-300">
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td v-for="box in visibleExcessBoxes" :key="box" class="px-2 py-1 border border-gray-300">
                                            <input
                                                v-model="boxNoValuesExcess[box]"
                                                @input="boxNoValuesExcess[box] = boxNoValuesExcess[box].toUpperCase()"
                                                type="text"
                                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Weight table -->
                        <p class="pb-2 mt-4 text-sm font-semibold text-gray-800 border-b">
                            Excess Weight <span class="text-gray-300">(KG)</span>
                        </p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-center border border-collapse border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th v-for="box in visibleExcessBoxes" :key="box" class="px-4 py-2 text-xs border border-gray-300">
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td v-for="box in visibleExcessBoxes" :key="box" class="px-2 py-1 border border-gray-300">
                                            <input
                                                v-model="weightValuesExcess[box]"
                                                type="number"
                                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Manual Quantity override button -->
                    <div class="mt-4">
                        <button @click="manualQtyMode = !manualQtyMode"
                                class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                        {{ manualQtyMode ? 'Cancel Manual Qty' : 'Edit Qty per Box' }}
                        </button>
                    </div>

                    <!-- Main Boxes Manual Qty -->
                    <div v-if="manualQtyMode" class="mt-6">
                    <p class="pb-2 text-sm font-semibold text-gray-800 border-b">
                        Quantity (PCS) per Box - Main
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-collapse border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th v-for="box in visibleBoxes" :key="box" class="px-4 py-2 text-xs border border-gray-300">
                                    {{ box }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td v-for="box in visibleBoxes" :key="box" class="px-2 py-1 border border-gray-300">
                                <input
                                    v-model="qtyValues[box]"
                                    type="number"
                                    class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                />
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>

                    <!-- Excess Boxes Manual Qty -->
                    <div v-if="manualQtyMode && initial_mpcs.moreThanTenBoxes" class="mt-6">
                    <p class="pb-2 text-sm font-semibold text-gray-800 border-b">
                        Quantity (PCS) per Box - Excess
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-collapse border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                            <th v-for="box in visibleExcessBoxes" :key="box" class="px-4 py-2 text-xs border border-gray-300">
                                {{ box }}
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td v-for="box in visibleExcessBoxes" :key="box" class="px-2 py-1 border border-gray-300">
                                <input
                                v-model="qtyValuesExcess[box]"
                                type="number"
                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                />
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="flex my-10 space-x-4">
                <button
                    @click="dataValidation()"
                    class="bg-cyan-600 text-white rounded-md w-[120px]
                        hover:bg-cyan-500 active:bg-cyan-700
                        transition-colors duration-200 ease-in-out
                        focus:outline-none focus:ring-2 focus:ring-cyan-400"
                >
                    Finalize
                </button>

                <button
                    @click="clearAll()"
                    class="bg-teal-600 text-white rounded-md w-[140px]
                        hover:bg-teal-500 active:bg-teal-700
                        transition-colors duration-200 ease-in-out
                        focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
                    Reset Fields
                </button>
            </div>

            <Modal :show="showModalPreview" @close="showModalPreview = false">
                <div
                    class="relative flex flex-col items-start bg-white p-6 rounded-xl shadow-2xl max-w-[95vw] max-h-[90vh] overflow-auto pr-12"
                >

                    <!-- Exit Button -->
                    <button
                    @click="showModalPreview = false"
                    class="absolute text-gray-400 transition top-4 right-4 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <!-- Header -->
                    <p class="mb-6 text-lg font-bold text-gray-800">
                        Review everything <span class="text-red-700">carefully</span> before submitting.
                    </p>

                    <!-- ================= MAIN BOXES ================= -->
                    <div class="px-5 overflow-auto">
                    <table class="min-w-full text-xs border border-collapse border-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-2 py-1 text-left border border-gray-300"></th>
                            <th
                            v-for="box in visibleBoxes"
                            :key="box"
                            class="px-2 py-1 font-semibold text-center border border-gray-300"
                            >
                            {{ box }}
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="row in [
                            { title: 'Model:', data: initial_mpcs.selectedModel },
                            { title: 'Coating M/C No:', data: initial_mpcs.coatingMCNo },
                            { title: 'Lot No:', data: initial_mpcs.lotNo },
                            { title: 'Qty (PCS):', data: qtyDataMain },
                            { title: 'WT (KG):', data: weightValues },
                            { title: 'Box No.:', data: boxNoValues },
                            { title: 'Coating:', data: initial_mpcs.coating },
                            { title: 'Magnet Prepared By:', data: initial_mpcs.magnetPreparedBy },
                            { title: 'Box Prepared By:', data: initial_mpcs.boxPreparedBy }
                        ]" :key="row.title">
                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">
                            {{ row.title }}
                        </td>
                        <td
                            v-for="box in visibleBoxes"
                            :key="row.title + '-' + box"
                            class="px-2 py-1 text-center border border-gray-300"
                        >
                            {{ typeof row.data === 'object' ? row.data[box] : row.data }}
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>

                    <!-- ========== EXCESS BOXES ========== -->
                    <div v-if="initial_mpcs.moreThanTenBoxes" class="px-5 mt-10 overflow-auto">
                    <p class="mb-2 font-semibold text-gray-700">Excess Boxes</p>

                    <table class="min-w-full text-xs border border-collapse border-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-2 py-1 text-left border border-gray-300"></th>
                            <th
                            v-for="box in visibleExcessBoxes"
                            :key="'excess-header-' + box"
                            class="px-2 py-1 font-semibold text-center border border-gray-300"
                            >
                            {{ box }}
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="row in [
                            { title: 'Model:', data: initial_mpcs.selectedModel },
                            { title: 'Coating M/C No:', data: initial_mpcs.coatingMCNo },
                            { title: 'Lot No:', data: initial_mpcs.lotNo },
                            { title: 'Qty (PCS):', data: qtyDataExcess },
                            { title: 'WT (KG):', data: weightValuesExcess },
                            { title: 'Box No.:', data: boxNoValuesExcess },
                            { title: 'Coating:', data: initial_mpcs.coating },
                            { title: 'Magnet Prepared By:', data: initial_mpcs.magnetPreparedBy },
                            { title: 'Box Prepared By:', data: initial_mpcs.boxPreparedBy }
                        ]" :key="row.title + '-excess'">
                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">
                            {{ row.title }}
                        </td>
                        <td
                            v-for="box in visibleExcessBoxes"
                            :key="row.title + '-' + box + '-excess'"
                            class="px-2 py-1 text-center border border-gray-300"
                        >
                            {{ typeof row.data === 'object' ? row.data[box] : row.data }}
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex mt-4 space-x-3">
                    <button
                        @click="cancelProceed"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 rounded-lg shadow hover:bg-gray-400"
                    >
                        Cancel
                    </button>
                    <button
                        v-if="initial_mpcs.moreThanTenBoxes"
                        @click="saveExcessCase"
                        class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700"
                    >
                        Proceed
                        <img src="/photo/arrow_proceed.png" alt="Proceed" class="w-4 h-4 ml-2">
                    </button>
                    <button
                        v-else
                        @click="saveNormalCase"
                        class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700"
                    >
                        Proceed
                        <img src="/photo/arrow_proceed.png" alt="Proceed" class="w-4 h-4 ml-2">
                    </button>
                    </div>
                </div>
            </Modal>

            <Modal :show="showModalDuplicateWarning" @close="showModalDuplicateWarning = false">
                <div
                    class="relative flex flex-col items-start bg-white border-l-8 border-red-600 p-6 rounded-xl shadow-lg max-w-[95vw] max-h-[90vh] overflow-auto"
                >
                    <!-- Exit Button -->
                    <button
                    @click="showModalDuplicateWarning = false"
                    class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <!-- Header with icon -->
                    <div class="flex items-center mb-4 space-x-3">
                    <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.58c.75 1.333-.213 3.02-1.742 3.02H3.48c-1.53 0-2.492-1.687-1.742-3.02L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-.993.883L9 8v3a1 1 0 001.993.117L11 11V8a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                    </svg>
                    <h2 class="text-2xl font-bold text-red-700">Duplicate Lot Detected</h2>
                    </div>

                    <!-- Body -->
                    <p class="text-base leading-relaxed text-gray-700">
                    A record with the same <span class="font-semibold underline">Lot No</span> already exists in the system.
                    You <span class="font-semibold">cannot proceed</span> until this conflict is resolved. Please verify your entry and correct the Lot No.
                    </p>

                    <!-- Close Button -->
                    <div class="flex justify-end w-full mt-6">
                    <button
                        @click="showModalDuplicateWarning = false"
                        class="px-6 py-2 font-semibold text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                    >
                        Close
                    </button>
                    </div>
                </div>
            </Modal>

        </div>

        <!-- Section Content -->
        <div v-if="activeSection === 'coating'">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
            <div class="p-4 text-teal-700 bg-white rounded-lg shadow">
                <div class="text-sm font-medium">Total Tasks</div>
                <div class="text-2xl font-bold">123</div>
            </div>
            <div class="p-4 text-teal-700 bg-white rounded-lg shadow">
                <div class="text-sm font-medium">Active Machines</div>
                <div class="text-2xl font-bold">45</div>
            </div>
            <div class="p-4 text-teal-700 bg-white rounded-lg shadow">
                <div class="text-sm font-medium">Pending Reports</div>
                <div class="text-2xl font-bold">7</div>
            </div>
            </div>

            <!-- Charts & Tables (placeholders) -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="mb-4 text-lg font-semibold text-teal-700">Performance Chart</h2>
                <div class="flex items-center justify-center h-64 rounded-lg bg-cyan-100">[Chart here]</div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="mb-4 text-lg font-semibold text-teal-700">Recent Activity</h2>
                <div class="flex items-center justify-center h-64 rounded-lg bg-cyan-100">[Table here]</div>
            </div>
            </div>
        </div>
        <!-- Add more sections as needed -->
        </main>
    </div>
</template>

<script setup>
import { onMounted, ref, computed, reactive, watch } from "vue";
import { Inertia } from '@inertiajs/inertia';
import Modal from '@/Components/Modal.vue'
import { useToast } from 'vue-toast-notification';
import axios from 'axios';

const toast = useToast();

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

const userErrorLogging = async (details, triggerFunction, title) => {
    try{
        const response = await axios.post('/api/error-logs', {
            user: state.user.firstName + " " + state.user.surname,
            title: title,
            details: details,
            trigger_function: triggerFunction,
            section: 'Heat Treatment',
        });

        //console.log('userErrorLogging-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userErrorLogging post request failed: ',error);
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

//Toggle variables
const manualQtyMode = ref(false);
const showModalPreview = ref(false);
const showModalDuplicateWarning = ref(false);

const activeSection = ref('control_sheet');
const allBoxes = ['A','B','C','D','E','F','G','H','J','K'];
const boxesEndList = ref(['B','C','D','E','F','G','H','J','K']);
const excessBoxesEndList = ref(['B','C','D','E','F','G','H','J','K']);
const model_names = ref([]);
const boxNoValues = ref({});
const weightValues = ref({});
const qtyValues = ref({});
const boxNoValuesExcess = ref({});
const weightValuesExcess = ref({});
const qtyValuesExcess = ref({});

allBoxes.forEach(box => {
    boxNoValues.value[box] = '';
    weightValues.value[box] = '';
    qtyValues.value[box] = '';
    boxNoValuesExcess.value[box] = '';
    weightValuesExcess.value[box] = '';
    qtyValuesExcess.value[box] = '';
});

const sectionTitle = computed(() => {
    switch (activeSection.value) {
        case 'control_sheet': return 'Mass Production Control Sheet';
        case 'coating': return 'Coating Summary';
        case 'film_pasting': return 'Film Pasting Summary';
        default: return '';
    }
});

const initial_mpcs = reactive({
    selectedBoxStart: 'A',              // new
    selectedBoxEnd: 'K',
    selectedExcessBoxStart: 'A',        // new
    selectedExcessBoxEnd: 'E',
    moreThanTenBoxes: false,
    selectedModel: '',
    coatingMCNo: '',
    rawMaterialCode: '',
    lotNo: '',
    qty: 0,
    qty_lastBox: 0,
    coating: 0,
    magnetPreparedBy: '',
    boxPreparedBy: '',
});

const visibleBoxes = computed(() => {
    const startIndex = allBoxes.indexOf(initial_mpcs.selectedBoxStart);
    const endIndex = allBoxes.indexOf(initial_mpcs.selectedBoxEnd);
    return allBoxes.slice(startIndex, endIndex + 1);
});

const visibleExcessBoxes = computed(() => {
    const startIndex = allBoxes.indexOf(initial_mpcs.selectedExcessBoxStart);
    const endIndex = allBoxes.indexOf(initial_mpcs.selectedExcessBoxEnd);
    return allBoxes.slice(startIndex, endIndex + 1);
});

const combinedBoxes = computed(() => {
    if (initial_mpcs.moreThanTenBoxes) {
        // merge main and excess boxes only if >10 boxes
        return [...visibleBoxes.value, ...visibleExcessBoxes.value];
    } else {
        // normal scenario: only main boxes
        return visibleBoxes.value;
    }
});

// Initialize qtyValues from mpcs.qty / qty_lastBox when manual mode is enabled
// Watch manualQtyMode and initialize qtyValues for both tables
watch(manualQtyMode, (val) => {
  if (!val) return;

  // MAIN TABLE
  qtyValues.value = {};
  visibleBoxes.value.forEach((box, index) => {
    qtyValues.value[box] = initial_mpcs.moreThanTenBoxes
      ? initial_mpcs.qty              // main boxes never get lastBox if excess exists
      : index === visibleBoxes.value.length - 1
        ? initial_mpcs.qty_lastBox    // last box only if no excess
        : initial_mpcs.qty;
  });

  // EXCESS TABLE (only if moreThanTenBoxes)
  if (initial_mpcs.moreThanTenBoxes) {
    qtyValuesExcess.value = {};
    visibleExcessBoxes.value.forEach((box, index) => {
      qtyValuesExcess.value[box] = index === visibleExcessBoxes.value.length - 1
        ? initial_mpcs.qty_lastBox    // last box of excess gets lastBox
        : initial_mpcs.qty;
    });
  }
});

// Compute the true last box in the combined set
const lastBoxInCombined = computed(() => {
  const boxes = combinedBoxes.value;
  return boxes.length ? boxes[boxes.length - 1] : null;
});

// For MAIN table
const qtyDataMain = computed(() => {
  const data = {};
  visibleBoxes.value.forEach((box, index) => {
    data[box] = manualQtyMode.value
      ? qtyValues.value[box]
      : initial_mpcs.moreThanTenBoxes
        ? initial_mpcs.qty             // main boxes never get lastBox if excess exists
        : index === visibleBoxes.value.length - 1
          ? initial_mpcs.qty_lastBox
          : initial_mpcs.qty;
  });
  return data;
});

// For EXCESS table
const qtyDataExcess = computed(() => {
  if (!initial_mpcs.moreThanTenBoxes) return {};

  const data = {};
  visibleExcessBoxes.value.forEach((box, index) => {
    data[box] = manualQtyMode.value
      ? qtyValuesExcess.value[box]
      : index === visibleExcessBoxes.value.length - 1
        ? initial_mpcs.qty_lastBox    // last box of excess
        : initial_mpcs.qty;
  });
  return data;
});


// Total quantity
const totalQty = computed(() => {
    const mainTotal = Object.values(qtyDataMain.value).reduce((acc, val) => acc + Number(val || 0), 0);
    const excessTotal = Object.values(qtyDataExcess.value).reduce((acc, val) => acc + Number(val || 0), 0);
    return mainTotal + excessTotal;
});

const numberOfBoxes = computed(() => {
    if (initial_mpcs.moreThanTenBoxes) {
        return visibleBoxes.value.length + visibleExcessBoxes.value.length;
    }
    return visibleBoxes.value.length;
});

//Data fetching zone ------- Data fetching zone ------- Data fetching zone ------- Data fetching zone ------- Data fetching zone

const getModelLists = async () => {
    try{
        const response = await axios.get('/api/inspectiondata/');
        const inspectionDataList = response.data.data;
        model_names.value = inspectionDataList.map(item => item.model);
        //console.log('Model lists: ',model_names.value);
    }catch(error){
        console.error('Error fetching model names', error);
        toast.error('Failed to get the model names.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getModelLists",
            "Failed to get the model names"
        );
    }
}

//Data fetching zone ------- Data fetching zone ------- Data fetching zone ------- Data fetching zone ------- Data fetching zone END

const cancelProceed = () => {
    showModalPreview.value = false;
}

const clearAll = () => {
    // Reset all mpcs fields except box selection
    const skipKeys = ['selectedBoxStart', 'selectedBoxEnd', 'selectedExcessBoxStart', 'selectedExcessBoxEnd', 'selectedModel'];

    Object.keys(initial_mpcs).forEach(key => {
        if (!skipKeys.includes(key)) {
            initial_mpcs[key] = typeof initial_mpcs[key] === 'number' ? 0 : '';
        }
    });

    Object.keys(weightValues.value).forEach(key => {
        weightValues.value[key] = '';
    });

    Object.keys(boxNoValues.value).forEach(key => {
        boxNoValues.value[key] = '';
    });

    Object.keys(qtyValues.value).forEach(key => {
        qtyValues.value[key] = '';
    });

    Object.keys(boxNoValuesExcess.value).forEach(key => {
        boxNoValuesExcess.value[key] = '';
    });

    Object.keys(weightValuesExcess.value).forEach(key => {
        weightValuesExcess.value[key] = '';
    });

    Object.keys(qtyValuesExcess.value).forEach(key => {
        qtyValuesExcess.value[key] = '';
    });

}

const dataValidation = async() => {
    // Check required main fields
    const requiredMainFields = [
        { field: initial_mpcs.selectedModel, name: 'Model' },
        { field: initial_mpcs.lotNo, name: 'Lot No' },
        { field: initial_mpcs.coatingMCNo, name: 'Coating M/C No' },
        { field: initial_mpcs.coating, name: 'Coating' },
        { field: initial_mpcs.magnetPreparedBy, name: 'Magnet Prepared By' },
        { field: initial_mpcs.rawMaterialCode, name: 'Raw Material Code' },
        { field: initial_mpcs.qty, name: 'Qty (PCS)' },
        { field: initial_mpcs.qty_lastBox, name: 'Qty (PCS) - Last Box' },
    ];

    for (const item of requiredMainFields) {
        if (!item.field || item.field.toString().trim() === '') {
            toast.error(`${item.name} is required.`);
            return;
        }
    }

    // Check main boxes for empty Qty, WT, Box No
    for (const box of visibleBoxes.value) {
        if ((manualQtyMode.value && (!qtyValues.value[box] && qtyValues.value[box] !== 0)) || (!manualQtyMode.value && (initial_mpcs.qty === '' || initial_mpcs.qty_lastBox === ''))) {
            toast.error(`Qty for main box ${box} is required.`);
            return;
        }
        if (!weightValues.value[box] && weightValues.value[box] !== 0) {
            toast.error(`Weight for main box ${box} is required.`);
            return;
        }
        if (!boxNoValues.value[box]) {
            toast.error(`Box No for main box ${box} is required.`);
            return;
        }
    }

    // Check excess boxes if moreThanTenBoxes
    if (initial_mpcs.moreThanTenBoxes) {
        for (const box of visibleExcessBoxes.value) {
            if ((manualQtyMode.value && (!qtyValuesExcess.value[box] && qtyValuesExcess.value[box] !== 0)) || (!manualQtyMode.value && (initial_mpcs.qty_lastBox === '' || initial_mpcs.qty === ''))) {
                toast.error(`Qty for excess box ${box} is required.`);
                return;
            }
            if (!weightValuesExcess.value[box] && weightValuesExcess.value[box] !== 0) {
                toast.error(`Weight for excess box ${box} is required.`);
                return;
            }
            if (!boxNoValuesExcess.value[box]) {
                toast.error(`Box No for excess box ${box} is required.`);
                return;
            }
        }
    }

    // All checks passed → open modal
    await lotNoValidation();
};

const lotNoValidation = async () => {
    try {
        if (!initial_mpcs.lotNo) {
            toast.error('Lot No. is required.');
            return false;
        }

        const response = await axios.post('/api/initial_control_sheet/check-duplicate', {
            lot_no: initial_mpcs.lotNo
        });

        if (response.data.duplicate_detected) {
            showModalDuplicateWarning.value = true;
            return false;
        }
        showModalPreview.value = true;
        return true; // no duplicate, validation passed
    } catch (error) {
        console.error('Error validating Lot No:', error);
        toast.error('Failed to validate Lot No.');
        return false;
    }
};


const saveNormalCase = async () => {
    try {
        const boxes = visibleBoxes.value; // ['A','B',…,'K']

        // Construct JSON structure for layer_data
        const layerPayload = [
            { rowTitle: 'MODEL:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.selectedModel || ''])) },
            { rowTitle: 'COATING M/C No.:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.coatingMCNo || ''])) },
            { rowTitle: 'LT. No.:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.lotNo || ''])) },
            { rowTitle: 'QTY (PCS):', data: qtyDataMain.value },
            { rowTitle: 'COATING:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.coating])) },
            { rowTitle: 'WT (KG):', data: Object.fromEntries(boxes.map(box => [box, weightValues.value[box] || ''])) },
            { rowTitle: 'BOX No.:', data: Object.fromEntries(boxes.map(box => [box, boxNoValues.value[box] || ''])) },
            { rowTitle: 'Magnet prepared by:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.magnetPreparedBy || ''])) },
            { rowTitle: 'Box prepared by:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.boxPreparedBy || ''])) },
            { rowTitle: 'RAW MATERIAL CODE:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.rawMaterialCode || ''])) },
            { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxes.map(box => [box, totalQty.value])) }
        ];

        // Payload for Laravel API
        const payload = {
            model_name: initial_mpcs.selectedModel,
            lot_no: initial_mpcs.lotNo,
            layer_data: layerPayload,
        };

        console.log('Payload for Normal Case: ', payload);
        console.log('Number Of Boxes: ',numberOfBoxes.value);

        // POST to your API
        //const response = await axios.post('/api/initial_control_sheet', payload);
        //console.log('Normal case saved:', response.data);
        toast.success('Normal Control Sheet saved successfully.');
        //clearAll(); // reset form
    } catch (err) {
        console.error('Error saving normal case:', err);
        toast.error('Failed to save normal Control Sheet.');
    } finally {
        showModalPreview.value = false;
    }
};

const saveExcessCase = async () => {
    try {
        const boxes = visibleBoxes.value; // ['A','B',…,'K']
        const boxes2 = visibleExcessBoxes.value;

        // Construct JSON structure for layer_data
        const layerPayload = [
            { rowTitle: 'MODEL:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.selectedModel || ''])) },
            { rowTitle: 'COATING M/C No.:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.coatingMCNo || ''])) },
            { rowTitle: 'LT. No.:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.lotNo || ''])) },
            { rowTitle: 'QTY (PCS):', data: qtyDataMain.value },
            { rowTitle: 'COATING:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.coating])) },
            { rowTitle: 'WT (KG):', data: Object.fromEntries(boxes.map(box => [box, weightValues.value[box] || ''])) },
            { rowTitle: 'BOX No.:', data: Object.fromEntries(boxes.map(box => [box, boxNoValues.value[box] || ''])) },
            { rowTitle: 'Magnet prepared by:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.magnetPreparedBy || ''])) },
            { rowTitle: 'Box prepared by:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.boxPreparedBy || ''])) },
            { rowTitle: 'RAW MATERIAL CODE:', data: Object.fromEntries(boxes.map(box => [box, initial_mpcs.rawMaterialCode || ''])) },
            { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxes.map(box => [box, totalQty.value])) }
        ];

        // Construct JSON structure for excess_data
        const excessPayload = [
            { rowTitle: 'MODEL:', data: Object.fromEntries(boxes2.map(box => [box, initial_mpcs.selectedModel || ''])) },
            { rowTitle: 'COATING M/C No.:', data: Object.fromEntries(boxes2.map(box => [box, initial_mpcs.coatingMCNo || ''])) },
            { rowTitle: 'LT. No.:', data: Object.fromEntries(boxes2.map(box => [box, initial_mpcs.lotNo || ''])) },
            { rowTitle: 'QTY (PCS):', data: Object.fromEntries(boxes2.map(box => [box, qtyDataExcess.value[box]])) },
            { rowTitle: 'COATING:', data: Object.fromEntries(boxes2.map(box => [box, initial_mpcs.coating])) },
            { rowTitle: 'WT (KG):', data: Object.fromEntries(boxes2.map(box => [box, weightValuesExcess.value[box] || ''])) },
            { rowTitle: 'BOX No.:', data: Object.fromEntries(boxes2.map(box => [box, boxNoValuesExcess.value[box] || ''])) },
            { rowTitle: 'Magnet prepared by:', data: Object.fromEntries(boxes2.map(box => [box, initial_mpcs.magnetPreparedBy || ''])) },
            { rowTitle: 'Box prepared by:', data: Object.fromEntries(boxes2.map(box => [box, initial_mpcs.boxPreparedBy || ''])) },
            { rowTitle: 'RAW MATERIAL CODE:', data: Object.fromEntries(boxes2.map(box => [box, initial_mpcs.rawMaterialCode || ''])) },
            { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxes2.map(box => [box, totalQty.value])) }
        ];

        // Payload for Laravel API
        const payload = {
            model_name: initial_mpcs.selectedModel,
            lot_no: initial_mpcs.lotNo,
            layer_data: layerPayload,
            excess_data: excessPayload,
        };

        console.log('Excess payload', payload);
        console.log('Number Of Boxes: ',numberOfBoxes.value);

        // POST to your API
        //const response = await axios.post('/api/initial_control_sheet', payload);
        //console.log('Excess case saved:', response.data);
        toast.success('Excess Control Sheet saved successfully.');
        //clearAll(); // reset form
    } catch (err) {
        console.error('Error saving excess case:', err);
        toast.error('Failed to save excess Control Sheet.');
    } finally {
        showModalPreview.value = false;
    }
};


// Fetch on mount
onMounted( async () => {
    await getModelLists();
});

// APPLYING Browser Session ----------------- APPLYING Browser Session

useSessionStorage('initial_mpcs',initial_mpcs);
useSessionStorage('weightValues',weightValues);
useSessionStorage('boxNoValues',boxNoValues);
useSessionStorage('qtyValues',qtyValues);
useSessionStorage('boxNoValuesExcess',boxNoValuesExcess);
useSessionStorage('weightValuesExcess',weightValuesExcess);
useSessionStorage('qtyValuesExcess',qtyValuesExcess);

// APPLYING Browser Session ----------------- APPLYING Browser Session

</script>
