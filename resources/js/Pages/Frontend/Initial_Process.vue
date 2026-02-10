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
                <button @click="activeSection = 'overall_summary'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Overall Summary</button>
                <button @click="activeSection = 'control_sheet'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Control Sheet</button>
                <button @click="activeSection = 'coating'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Coating Summary</button>
                <button @click="activeSection = 'film_pasting'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Film Pasting Summary</button>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
        <!-- Topbar -->
        <div class="flex items-center justify-between p-4 mb-6 text-white rounded-lg shadow bg-cyan-500">

            <!-- Section Title -->
            <h1 class="text-lg font-semibold">{{ sectionTitle }}</h1>

            <!-- Right-hand controls -->
            <div class="flex items-center space-x-4">

                <!-- Authenticated User Panel -->
                <div v-if="state.isAuthenticated && state.user"
                    class="flex items-center px-3 py-1 space-x-3 rounded-full shadow-sm bg-cyan-400 bg-opacity-20 backdrop-blur-sm">

                    <!-- Avatar -->
                    <div class="flex items-center justify-center w-8 h-8 text-xs font-bold rounded-full bg-gradient-to-br from-blue-400 via-blue-500 to-cyan-500">
                        {{ state.user.firstName.charAt(0).toUpperCase() }}
                    </div>

                    <!-- Name + Access -->
                    <div class="flex flex-col leading-tight text-white">
                        <span class="truncate max-w-[6rem]">{{ state.user.firstName }}</span>
                        <span class="text-xs text-blue-200 truncate max-w-[6rem]">({{ state.user.access_type }})</span>
                    </div>

                    <!-- Return Home Button -->
                    <button @click="Inertia.visit('/')"
                            class="px-3 py-1 text-xs font-semibold transition-colors duration-200 bg-blue-400 rounded-full hover:bg-blue-500">
                        Return Home
                    </button>
                </div>

            </div>
        </div>

        <!-- Section Content -->
        <div v-if="activeSection === 'overall_summary'">
            <div class="flex flex-row justify-center gap-0 whitespace-nowrap">
                <div class="w-full px-6 py-8 mx-auto">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <!-- Control Sheet -->
                        <section class="flex flex-col p-6 bg-white border border-gray-300 shadow-xl rounded-2xl">
                            <h2 class="mb-4 text-xl font-semibold text-center">Control Sheet</h2>

                            <!-- Search -->
                            <div class="mb-3">
                                <input
                                    v-model="controlSheetSearch"
                                    type="text"
                                    placeholder="Search Lot No..."
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div class="flex-1 overflow-y-auto border rounded-lg max-h-[420px]">
                                <table class="min-w-full text-center border-collapse table-fixed">
                                    <thead class="sticky top-0 z-10 bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 border">Model Name</th>
                                            <th class="px-4 py-3 border">Lot No</th>
                                            <th class="px-4 py-3 border">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in filteredControlSheetData" :key="item.id">
                                            <td class="px-4 py-3 border">{{ item.model_name }}</td>
                                            <td class="px-4 py-3 border">{{ item.lot_no }}</td>
                                            <td class="px-4 py-3 border">
                                                <button
                                                    @click="openDeleteModal(item.id, 'control')"
                                                    class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700"
                                                >
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="filteredControlSheetData.length === 0">
                                            <td colspan="3" class="py-6 text-gray-500">
                                                No matching lot found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <!-- Coating Summary -->
                        <section class="flex flex-col p-6 bg-white border border-gray-300 shadow-xl rounded-2xl">
                            <h2 class="mb-4 text-xl font-semibold text-center">Coating Summary</h2>

                            <!-- Search -->
                            <div class="mb-3">
                                <input
                                    v-model="coatingSummarySearch"
                                    type="text"
                                    placeholder="Search Lot No..."
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div class="flex-1 overflow-y-auto border rounded-lg max-h-[420px]">
                                <table class="min-w-full text-center border-collapse table-fixed">
                                    <thead class="sticky top-0 z-10 bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 border">Model Name</th>
                                            <th class="px-4 py-3 border">Lot No</th>
                                            <th class="px-4 py-3 border">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in filteredCoatingSummaryData" :key="item.id">
                                            <td class="px-4 py-3 border">{{ item.model_name }}</td>
                                            <td class="px-4 py-3 border">{{ item.lot_no }}</td>
                                            <td class="px-4 py-3 border">
                                                <button
                                                    @click="openDeleteModal(item.id, 'coating')"
                                                    class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700"
                                                >
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="filteredCoatingSummaryData.length === 0">
                                            <td colspan="3" class="py-6 text-gray-500">
                                                No matching lot found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>


                        <!-- Film Pasting Summary -->
                        <section class="flex flex-col p-6 bg-white border border-gray-300 shadow-xl rounded-2xl">
                            <h2 class="mb-4 text-xl font-semibold text-center">Film Pasting Summary</h2>

                            <!-- Search -->
                            <div class="mb-3">
                                <input
                                    v-model="filmPastingSearch"
                                    type="text"
                                    placeholder="Search Lot No..."
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div class="flex-1 overflow-y-auto border rounded-lg max-h-[420px]">
                                <table class="min-w-full text-center border-collapse table-fixed">
                                    <thead class="sticky top-0 z-10 bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 border">Model Name</th>
                                            <th class="px-4 py-3 border">Lot No</th>
                                            <th class="px-4 py-3 border">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in filteredFilmPastingSummaryData" :key="item.id">
                                            <td class="px-4 py-3 border">{{ item.model_name }}</td>
                                            <td class="px-4 py-3 border">{{ item.lot_no }}</td>
                                            <td class="px-4 py-3 border">
                                                <button
                                                    @click="openDeleteModal(item.id, 'film')"
                                                    class="px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700"
                                                >
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="filteredFilmPastingSummaryData.length === 0">
                                            <td colspan="3" class="py-6 text-gray-500">
                                                No matching lot found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>


                    </div>
                </div>

            </div>


            <Modal :show="showDeleteModal" @close="closeDeleteModal">
                <div
                    class="relative flex flex-col items-start bg-white border-l-8 border-red-600 p-6 rounded-xl shadow-lg max-w-[95vw] max-h-[90vh] overflow-auto"
                >
                    <!-- Exit Button -->
                    <button
                        @click="closeDeleteModal"
                        class="absolute text-gray-400 transition top-4 right-4 hover:text-gray-600"
                        aria-label="Close modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Title -->
                    <p class="mb-4 text-xl font-semibold text-gray-900">
                        Confirm Deletion
                    </p>

                    <!-- Body -->
                    <p class="mb-6 text-sm text-gray-600">
                        This action is permanent. The selected record will be deleted and cannot be recovered.
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <button
                            @click="closeDeleteModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300"
                        >
                            Cancel
                        </button>

                        <button
                            @click="confirmDelete"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </Modal>

        </div>

        <!-- Section Content -->
        <div v-if="activeSection === 'control_sheet'">
            <div class="flex flex-row justify-center gap-0">
                <div class="max-w-4xl px-2 mx-auto mr-10 space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-1 h-8 rounded-full bg-gradient-to-b from-cyan-500 to-teal-500"></div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Mass Production Control Sheet</h2>
                                <p class="text-sm text-gray-500">
                                    Fill up all details below. Fields with
                                    <span class="font-semibold text-red-500">*</span> are required
                                </p>
                                <p class="px-3 py-1 mt-1 text-sm text-yellow-700 bg-yellow-100 rounded-md">
                                    ⚠️ If you need to add a new model, please coordinate with the QA MPI Department and request them to add it officially.
                                </p>
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
                        <select v-model="initial_mpcs.selectedBoxStart" :disabled="initial_mpcs.moreThanTenBoxes" class="w-full text-xs border-gray-300 rounded-lg shadow-sm">
                            <option v-for="item in allBoxes" :key="item" :value="item">{{ item }}</option>
                        </select>
                        </div>

                        <!-- End Box -->
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">End Box <span class="text-red-500">*</span></label>
                        <select v-model="initial_mpcs.selectedBoxEnd" :disabled="initial_mpcs.moreThanTenBoxes" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option v-for="item in boxesEndList" :key="item" :value="item">{{ item }}</option>
                        </select>
                        </div>

                        <!-- Toggle Excess -->
                        <div class="flex items-end">
                            <button
                                @click="initial_mpcs.moreThanTenBoxes = !initial_mpcs.moreThanTenBoxes"
                                type="button"
                                :disabled="!canEnableExcessBoxes"
                                :class="[
                                    'w-full px-3 py-2 text-xs font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 transition',
                                    !canEnableExcessBoxes
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-300'
                                        : initial_mpcs.moreThanTenBoxes
                                            ? 'bg-blue-600 text-white border-blue-700 hover:bg-blue-700 focus:ring-blue-500'
                                            : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200 focus:ring-gray-400'
                                ]"
                            >
                                {{ initial_mpcs.moreThanTenBoxes ? 'Click again to cancel' : '> 10 Boxes' }}
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
                            <input
                                v-model="initial_mpcs.coatingMCNo"
                                type="text"
                                @input="formatCoatingMCNo"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Raw Material Code<span class="text-red-500"> *</span></label>
                            <input v-model="initial_mpcs.rawMaterialCode" type="text"
                                @input="initial_mpcs.rawMaterialCode = initial_mpcs.rawMaterialCode
                                    .toUpperCase()
                                    .slice(0, 8)"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
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
                                step="1"
                                min="0"
                                :disabled="manualQtyMode"
                                @input="initial_mpcs.qty = Math.trunc(initial_mpcs.qty || 0)"
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
                                step="1"
                                min="0"
                                :disabled="manualQtyMode"
                                @input="initial_mpcs.qty_lastBox = Math.trunc(initial_mpcs.qty_lastBox || 0)"
                                :class="[
                                    'w-full text-xs border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500',
                                    manualQtyMode
                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-300'
                                        : 'border-gray-300'
                                ]"
                            />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Frequency<span class="text-red-500"> *</span></label>
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

                <div class="max-w-4xl px-6 py-6 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl">

                    <!-- BOX No. table -->
                    <p class="pb-1 text-[11px] font-semibold text-gray-800 border-b">
                        BOX No. <span class="text-gray-300">(example: UBP85172)</span><span class="text-red-500"> *</span>
                    </p>

                    <div class="overflow-x-auto">
                        <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        {{ box }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        <input
                                            v-model="boxNoValues[box]"
                                            @input="(() => {
                                                let v = boxNoValues[box].toUpperCase().replace(/\s+/g, '');

                                                const letters = v.slice(0, 3).replace(/[^A-Z]/g, '');
                                                const numbers = v.slice(3).replace(/[^0-9]/g, '');

                                                boxNoValues[box] = (letters + numbers).slice(0, 8);
                                            })()"
                                            type="text"
                                            class="w-full px-1 py-[2px] text-[10px] border border-gray-300 rounded-sm
                                                focus:outline-none focus:ring-1 focus:ring-blue-400"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Weight table -->
                    <p class="pb-1 text-[11px] font-semibold text-gray-800 border-b">
                        Weight <span class="text-gray-300">(KG)</span>
                        <span class="text-red-500"> *</span>
                        <span class="text-gray-300">
                            Enter the Box Weight and Box with Magnet Weight to calculate the resulting value.
                        </span>
                    </p>

                    <div class="overflow-x-auto">
                        <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        {{ box }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        <input
                                            :value="weightValues[box]"
                                            type="number"
                                            disabled
                                            class="w-full px-1 py-[2px] text-[10px]
                                                bg-gray-100 border border-gray-300 rounded-sm
                                                cursor-not-allowed"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Box Weight table -->
                    <p class="pb-1 text-[11px] font-semibold text-gray-800 border-b">
                        Box Weight <span class="text-red-500"> *</span>
                    </p>

                    <div class="overflow-x-auto">
                        <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        {{ box }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        <input
                                            v-model="boxWeight[box]"
                                            type="number"
                                            class="w-full px-1 py-[2px] text-[10px]
                                                border border-gray-300 rounded-sm
                                                focus:outline-none focus:ring-1 focus:ring-blue-400"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Box with Magnet Weight table -->
                    <p class="pb-1 text-[11px] font-semibold text-gray-800 border-b">
                        Box with Magnet Weight <span class="text-red-500"> *</span>
                    </p>

                    <div class="overflow-x-auto">
                        <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        {{ box }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                    >
                                        <input
                                            v-model="boxWithMagnetWeight[box]"
                                            type="number"
                                            class="w-full px-1 py-[2px] text-[10px]
                                                border border-gray-300 rounded-sm
                                                focus:outline-none focus:ring-1 focus:ring-blue-400"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Excess Boxes Section -->
                    <div v-if="initial_mpcs.moreThanTenBoxes" class="mt-8">
                        <!-- Excess BOX No. table -->
                        <p class="pb-1 text-[11px] font-semibold text-gray-800 border-b">
                            Excess BOX No. <span class="text-gray-300">(example: UBP85172)</span>
                        </p>

                        <div class="overflow-x-auto">
                            <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            <input
                                                v-model="boxNoValuesExcess[box]"
                                                @input="(() => {
                                                    let v = boxNoValuesExcess[box].toUpperCase().replace(/\s+/g, '');

                                                    const letters = v.slice(0, 3).replace(/[^A-Z]/g, '');
                                                    const numbers = v.slice(3).replace(/[^0-9]/g, '');

                                                    boxNoValuesExcess[box] = (letters + numbers).slice(0, 8);
                                                })()"
                                                type="text"
                                                class="w-full px-1 py-[2px] text-[10px]
                                                    border border-gray-300 rounded-sm
                                                    focus:outline-none focus:ring-1 focus:ring-blue-400"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Excess Weight table -->
                        <p class="pb-1 mt-3 text-[11px] font-semibold text-gray-800 border-b">
                            Excess Weight <span class="text-gray-300">(KG)</span>
                        </p>

                        <div class="overflow-x-auto">
                            <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            <input
                                                :value="weightValuesExcess[box]"
                                                type="number"
                                                disabled
                                                class="w-full px-1 py-[2px] text-[10px]
                                                    bg-gray-100 border border-gray-300 rounded-sm
                                                    cursor-not-allowed focus:outline-none"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Excess Box Weight table -->
                        <p class="pb-1 mt-3 text-[11px] font-semibold text-gray-800 border-b">
                            Excess Box Weight
                        </p>

                        <div class="overflow-x-auto">
                            <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            <input
                                                v-model="boxWeightExcess[box]"
                                                type="number"
                                                class="w-full px-1 py-[2px] text-[10px]
                                                    border border-gray-300 rounded-sm
                                                    focus:outline-none focus:ring-1 focus:ring-blue-400"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Excess Box with Magnet Weight table -->
                        <p class="pb-1 mt-3 text-[11px] font-semibold text-gray-800 border-b">
                            Excess Box with Magnet Weight
                        </p>

                        <div class="overflow-x-auto">
                            <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            <input
                                                v-model="boxWithMagnetWeightExcess[box]"
                                                type="number"
                                                class="w-full px-1 py-[2px] text-[10px]
                                                    border border-gray-300 rounded-sm
                                                    focus:outline-none focus:ring-1 focus:ring-blue-400"
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
                        <!-- Quantity (PCS) per Box - Main table -->
                        <p class="pb-1 mt-3 text-[11px] font-semibold text-gray-800 border-b">
                            Quantity (PCS) per Box - Main
                        </p>

                        <div class="overflow-x-auto">
                            <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            v-for="box in visibleBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            v-for="box in visibleBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            <input
                                                v-model="qtyValues[box]"
                                                type="number"
                                                step="1"
                                                min="0"
                                                @input="qtyValues[box] = Math.trunc(qtyValues[box] || 0)"
                                                class="w-full px-1 py-[2px] text-[10px]
                                                    border border-gray-300 rounded-sm
                                                    focus:outline-none focus:ring-1 focus:ring-blue-400"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Excess Boxes Manual Qty -->
                    <div v-if="manualQtyMode && initial_mpcs.moreThanTenBoxes" class="mt-6">
                        <!-- Quantity (PCS) per Box - Excess table -->
                        <p class="pb-1 mt-3 text-[11px] font-semibold text-gray-800 border-b">
                            Quantity (PCS) per Box - Excess
                        </p>

                        <div class="overflow-x-auto">
                            <table class="text-center border border-collapse border-gray-300 min-w-max text-[10px]">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            v-for="box in visibleExcessBoxes"
                                            :key="box"
                                            class="px-0.5 py-0.5 border border-gray-300 w-[5rem] min-w-[3rem]"
                                        >
                                            <input
                                                v-model="qtyValuesExcess[box]"
                                                type="number"
                                                step="1"
                                                min="0"
                                                @input="qtyValuesExcess[box] = Math.trunc(qtyValuesExcess[box] || 0)"
                                                class="w-full px-1 py-[2px] text-[10px]
                                                    border border-gray-300 rounded-sm
                                                    focus:outline-none focus:ring-1 focus:ring-blue-400"
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
                    @click="dataValidation"
                    class="px-4 py-2 font-semibold text-white transition-colors bg-teal-600 rounded shadow hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
                    Finalize
                </button>

                <button
                    @click="clearAll"
                    class="px-4 py-2 font-semibold text-teal-600 transition-colors bg-white border border-teal-600 rounded shadow hover:bg-teal-50 focus:outline-none focus:ring-2 focus:ring-teal-200"
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
                            { title: 'Box Weight:', data: boxWeight },
                            { title: 'Box with Magnet Weight:', data: boxWithMagnetWeight },
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
                            { title: 'Box Weight:', data: boxWeightExcess },
                            { title: 'Box with Magnet Weight:', data: boxWithMagnetWeightExcess },
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

                    <!-- ======= TOTAL SUMMARY ======= -->
                    <div class="flex justify-end gap-6 px-5 py-2 mt-4 border border-gray-200 rounded-lg bg-gray-50">
                        <p class="text-sm font-medium text-gray-700">
                            Total Qty: <span class="font-bold text-gray-900">{{ totalQty }}</span>
                        </p>
                        <p class="text-sm font-medium text-gray-700">
                            Total WT (KG): <span class="font-bold text-gray-900">{{ totalWt }}</span>
                        </p>
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
                    class="relative flex flex-col items-start bg-white border-l-4 border-yellow-500 p-6 rounded-xl shadow-md max-w-[95vw] max-h-[90vh] overflow-auto"
                >
                    <!-- Exit Button -->
                    <button
                    @click="showModalDuplicateWarning = false"
                    class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <!-- Header -->
                    <div class="flex items-center mb-4 space-x-3">
                    <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.58c.75 1.333-.213 3.02-1.742 3.02H3.48c-1.53 0-2.492-1.687-1.742-3.02L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-.993.883L9 8v3a1 1 0 001.993.117L11 11V8a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-800">Duplicate Lot Detected</h2>
                    </div>

                    <!-- Body -->
                    <p class="text-base leading-relaxed text-gray-700">
                    A record with the same <span class="font-semibold underline">Lot No</span> already exists in the system.
                    You <span class="font-semibold">may proceed</span>, but please be fully aware that performing actions on
                    duplicate lot data can lead to conflicts if done incorrectly. Ensure that you are making the correct
                    decision before continuing.
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end w-full mt-6 space-x-3">
                    <button
                        @click="showModalDuplicateWarning = false"
                        class="px-6 py-2 font-semibold text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                    <button
                        @click="proceedDuplicate"
                        class="px-6 py-2 font-semibold text-white transition-colors bg-yellow-500 rounded-lg hover:bg-yellow-600"
                    >
                        Proceed
                    </button>
                    </div>
                </div>
            </Modal>

            <Modal :show="showModalDuplicatePrevent" @close="showModalDuplicatePrevent = false">
            <div
                class="relative flex flex-col items-start bg-white border-l-8 border-red-600 p-6 rounded-xl shadow-lg max-w-[95vw] max-h-[90vh] overflow-auto"
            >
                <!-- Exit Button -->
                <button
                @click="showModalDuplicatePrevent = false"
                class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                aria-label="Close modal"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <h2 class="text-2xl font-bold text-red-700">Duplicate Model + Lot Detected</h2>
                </div>

                <!-- Body -->
                <p class="text-base leading-relaxed text-gray-700">
                A record with the same <span class="font-semibold underline">Model Name</span> and <span class="font-semibold underline">Lot No</span> already exists in the system.
                You <span class="font-semibold text-red-700">cannot proceed</span> with this entry as it will cause errors and conflicts.
                Please verify and correct your entry before continuing.
                </p>

                <!-- Close Button -->
                <div class="flex justify-end w-full mt-6">
                <button
                    @click="showModalDuplicatePrevent = false"
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
            <div class="flex flex-row justify-center gap-0">
                <div class="w-full px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <div class="flex flex-row mt-5 mb-10">
                        <div class="w-full px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                            <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Information</h2>
                            <!-- Group: Selection -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Coating Date<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.coatingDate" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Coating Machine No<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.coatingMachineNo" @input="coatingInfo.coatingMachineNo = coatingInfo.coatingMachineNo.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Slurry Lot No<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.slurryLotNo" @input="coatingInfo.slurryLotNo = coatingInfo.slurryLotNo.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <!-- Group: Selection -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">MIN TB CONTENT (μg/mm²)<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.minTbContent" @input="coatingInfo.minTbContent = coatingInfo.minTbContent.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Sample Quantity (pcs)<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.sampleQuantity" type="number" step="1" min="0" @input="coatingInfo.sampleQuantity = Math.trunc(coatingInfo.sampleQuantity || 0)" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight (KG)<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.totalMagnetWeight" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <!-- Group: Selection -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Loader Operator<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.loaderOperator" @input="coatingInfo.loaderOperator = coatingInfo.loaderOperator.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Unloader Operator<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.unloaderOperator" @input="coatingInfo.unloaderOperator = coatingInfo.unloaderOperator.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Checker Operator<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.checkerOperator" @input="coatingInfo.checkerOperator = coatingInfo.checkerOperator.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <!-- Group: Selection -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Model<span class="text-red-500"> *</span></label>
                                    <select v-model="coatingModel" class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50">
                                        <option v-for="item in model_names" :key="item" :value="item">
                                            {{ item }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Lot no<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingLotNo" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Time Start<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.timeStart" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Time Finished<span class="text-red-500"> *</span></label>
                                    <input v-model="coatingInfo.timeFinished" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
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
                                        v-model="coatingInfo.remarks"
                                        @input="coatingInfo.remarks = coatingInfo.remarks.toUpperCase()"
                                        type="text"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter remarks here..."
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-4 ml-10 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl md:px-8">
                            <h2 class="pb-1 font-bold text-gray-800 border-b text-md">
                                Additional Slurry
                            </h2>

                            <div class="overflow-x-auto">
                                <table class="w-[30rem] text-sm text-center border border-gray-200 rounded-lg">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-1 py-1 border-r border-gray-300">MODULE</th>
                                        <th class="px-1 py-1 border-r border-gray-300">NEW</th>
                                        <th class="px-1 py-1 border-r border-gray-300">HOMO</th>
                                        <th class="px-1 py-1 border-r border-gray-300">TIME</th>
                                        <th class="px-1 py-1 border-r border-gray-300">LITERS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr
                                        v-for="(row) in additionalSlurry"
                                        :key="row.module"
                                        class="hover:bg-gray-50"
                                    >
                                    <td class="px-1 py-1 border-b border-r border-gray-200">{{ row.module }}</td>
                                    <td class="px-1 py-1 border-b border-r border-gray-200">
                                        <input
                                            type="text"
                                            v-model="row.new"
                                            @input="row.new = row.new.toUpperCase()"
                                            class="w-full text-center border rounded text-xs px-1 py-0.5 border-gray-300"
                                        />
                                    </td>
                                    <td class="px-1 py-1 border-b border-r border-gray-200">
                                        <input
                                            type="text"
                                            v-model="row.homo"
                                            @input="row.homo = row.homo.toUpperCase()"
                                            class="w-full text-center border rounded text-xs px-1 py-0.5 border-gray-300"
                                        />
                                    </td>
                                    <td class="px-1 py-1 border-b border-r border-gray-200">
                                        <input
                                            type="time"
                                            v-model="row.time"
                                            class="w-full text-center border rounded text-xs px-1 py-0.5 border-gray-300"
                                        />
                                    </td>
                                    <td class="px-1 py-1 border-b border-gray-200">
                                        <input
                                            type="number"
                                            v-model="row.liters"
                                            class="w-full text-center border rounded text-xs px-1 py-0.5 border-gray-300"
                                        />
                                    </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row gap-10 my-10 items">
                        <div class="px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl max-w-8xl rounded-2xl py-7 md:px-12">
                            <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Data (Unit: µ/mm²)</h2>
                            <div class="flex flex-row gap-5 whitespace-nowrap">
                                <div class="flex flex-col gap-4">
                                    <!-- Multi-line input for pasting multiple coatings -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">Paste Coatings (one per line)</span>
                                        <button
                                            @click="resetCoatingTableOnly"
                                            type="button"
                                            class="px-3 py-1 text-xs border border-red-300 text-red-600 rounded-lg
                                                bg-white shadow-sm
                                                hover:bg-red-50
                                                active:translate-y-[1px] active:shadow-none
                                                transition-all"
                                        >
                                            Clear Field
                                        </button>
                                    </div>
                                    <div>
                                    <textarea
                                        v-model="coatingsInput"
                                        @input="handlePasteInput"
                                        rows="6"
                                        class="w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Paste values like:&#10;55.01&#10;59.33&#10;59.03"
                                    ></textarea>
                                    </div>

                                    <div class="flex flex-row gap-4 overflow-x-auto">
                                        <div
                                            v-for="colIndex in Math.ceil(coatingsTable.length / 10)"
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
                                                    v-for="item in coatingsTable.slice((colIndex - 1) * 10, colIndex * 10)"
                                                    :key="item.no"
                                                    class="hover:bg-gray-50"
                                                    >
                                                    <td class="px-3 py-1 border-b border-r border-gray-200">{{ item.no }}</td>
                                                    <td class="px-3 py-1 border-b border-gray-200">
                                                        <input
                                                        type="number"
                                                        v-model.number="item.coating"
                                                        class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                        />
                                                    </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                                    <thead class="text-center bg-gray-100">
                                        <tr>
                                        <th colspan="8" class="text-center border-b border-gray-300">
                                            Concentration Amount
                                        </th>
                                        </tr>
                                        <tr>
                                        <th class="px-2 py-1 border-r border-gray-300"></th>
                                        <th v-for="module in modules" :key="module" class="px-2 py-1 border-r border-gray-300">
                                            {{ module }}
                                        </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr v-for="(range, rowIndex) in visibleRanges" :key="range" class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">{{ range }}</td>
                                        <td
                                            v-for="(module, colIndex) in modules"
                                            :key="colIndex"
                                            class="px-2 py-1 border-b border-r border-gray-200"
                                        >
                                            <input
                                            type="number"
                                            v-model="visibleConcentrationData[rowIndex][colIndex]"
                                            class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row gap-20">
                        <div class="flex-1 px-4 py-3 my-10 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Maximum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMaximum !== null ? coatingMaximum : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 my-10 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Minimum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMinimum !== null ? coatingMinimum : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 my-10 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Average</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingAverage != null ? Number(coatingAverage).toFixed(2) : '-' }}</div>
                        </div>
                    </div>

                    <div>
                        <button
                            @click="finalizeCoatingSummary"
                            class="px-4 py-2 mr-5 font-semibold text-white transition-colors bg-teal-600 rounded shadow hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500"
                        >
                            FINALIZE
                        </button>
                        <button
                            @click="resetCoatingSummaryData"
                            class="px-4 py-2 font-semibold text-teal-600 transition-colors bg-white border border-teal-600 rounded shadow hover:bg-teal-50 focus:outline-none focus:ring-2 focus:ring-teal-200"
                        >
                            Clear All Fields
                        </button>
                    </div>

                    <Modal :show="showCoatingPreview" @close="showCoatingPreview = false">
                        <div
                            class="relative flex flex-col items-start bg-white p-6 rounded-xl shadow-2xl max-w-[95vw] max-h-[90vh] overflow-auto pr-12"
                        >
                            <!-- Exit Button -->
                            <button
                            @click="showCoatingPreview = false"
                            class="text-gray-400 transition duration-150 hover:text-gray-600"
                            aria-label="Close modal"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            </button>

                            <p class="mb-6 text-lg font-bold text-gray-800">Please review your inputs <span class="text-red-700">carefully</span> before submitting.</p>

                            <div class="flex flex-row gap-2">
                                <div>
                                    <table class="table-auto text-[10px] text-center border border-gray-300 w-full whitespace-nowrap">
                                        <thead class="bg-gray-100">
                                            <tr>
                                            <th class="px-1 border border-gray-300">Lot No.</th>
                                            <th class="px-1 border border-gray-300">No.</th>
                                            <th class="px-1 border border-gray-300">Coating</th>
                                            <th class="px-1 border border-gray-300" colspan="2">Concentration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="rowIndex in 10" :key="rowIndex">
                                            <!-- Main coating row -->
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-1 border border-gray-300">{{ coatingLotNo }}</td>
                                                <td class="px-1 border border-gray-300">{{ rowIndex }}</td>
                                                <td class="px-1 border border-gray-300">{{ displayCoatings[rowIndex - 1] ?? '-' }}</td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex - 1) / 5)][(rowIndex - 1) % 5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">{{ modules[(rowIndex - 1) % 5] }}</td>
                                            </tr>

                                            <!-- Extra module-only rows after 5th and 10th coating -->
                                            <tr v-if="rowIndex === 5 || rowIndex === 10">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex - 1) / 5)][5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            <tr v-if="rowIndex === 5 || rowIndex === 10">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex - 1) / 5)][6] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="table-auto text-[10px] text-center border border-gray-300 w-full whitespace-nowrap">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-1 border border-gray-300">Lot No.</th>
                                                <th class="px-1 border border-gray-300">No.</th>
                                                <th class="px-1 border border-gray-300">Coating</th>
                                                <th class="px-1 border border-gray-300" colspan="2">Concentration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="rowIndex in 10" :key="'t2-' + rowIndex">
                                            <!-- Main coating row -->
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-1 border border-gray-300">{{ coatingLotNo }}</td>
                                                <td class="px-1 border border-gray-300">{{ rowIndex + 10 }}</td>
                                                <td class="px-1 border border-gray-300">{{ displayCoatings[rowIndex + 9] ?? '-' }}</td>
                                                <td class="px-1 border border-gray-300">
                                                    {{ concentrationData[Math.floor((rowIndex + 9) / 5)][(rowIndex + 9) % 5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">{{ modules[(rowIndex + 9) % 5] }}</td>
                                            </tr>

                                            <!-- Extra M-06 rows -->
                                            <tr v-if="rowIndex + 10 === 15 || rowIndex + 10 === 20">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 9) / 5)][5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            <tr v-if="rowIndex + 10 === 15 || rowIndex + 10 === 20">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 9) / 5)][6] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="table-auto text-[10px] text-center border border-gray-300 w-full whitespace-nowrap">
                                        <thead class="bg-gray-100">
                                            <tr>
                                            <th class="px-1 border border-gray-300">Lot No.</th>
                                            <th class="px-1 border border-gray-300">No.</th>
                                            <th class="px-1 border border-gray-300">Coating</th>
                                            <th class="px-1 border border-gray-300" colspan="2">Concentration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="rowIndex in 10" :key="'t3-' + rowIndex">
                                            <!-- Main coating row -->
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-1 border border-gray-300">{{ coatingLotNo }}</td>
                                                <td class="px-1 border border-gray-300">{{ rowIndex + 20 }}</td>
                                                <td class="px-1 border border-gray-300">{{ displayCoatings[rowIndex + 19] ?? '-' }}</td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 19) / 5)][(rowIndex + 19) % 5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">{{ modules[(rowIndex + 19) % 5] }}</td>
                                            </tr>

                                            <!-- Extra M-06 rows -->
                                            <tr v-if="rowIndex + 20 === 25 || rowIndex + 20 === 30">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 19) / 5)][5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            <tr v-if="rowIndex + 20 === 25 || rowIndex + 20 === 30">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 19) / 5)][6] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="hasRows31to35">
                                    <table class="table-auto text-[10px] text-center border border-gray-300 w-full whitespace-nowrap">
                                        <thead class="bg-gray-100">
                                            <tr>
                                            <th class="px-1 border border-gray-300">Lot No.</th>
                                            <th class="px-1 border border-gray-300">No.</th>
                                            <th class="px-1 border border-gray-300">Coating</th>
                                            <th class="px-1 border border-gray-300" colspan="2">Concentration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="rowIndex in 5" :key="'t4-' + rowIndex">
                                            <!-- Main coating row -->
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-1 border border-gray-300">{{ coatingLotNo }}</td>
                                                <td class="px-1 border border-gray-300">{{ rowIndex + 30 }}</td>
                                                <td class="px-1 border border-gray-300">{{ displayCoatings[rowIndex + 29] ?? '-' }}</td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 29) / 5)][(rowIndex + 29) % 5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">{{ modules[(rowIndex + 29) % 5] }}</td>
                                            </tr>

                                            <!-- Extra M-06 rows -->
                                            <tr v-if="rowIndex + 30 === 35">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 29) / 5)][5] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            <tr v-if="rowIndex + 30 === 35">
                                                <td colspan="3"></td>
                                                <td class="px-1 border border-gray-300">
                                                {{ concentrationData[Math.floor((rowIndex + 29) / 5)][6] ?? '-' }}
                                                </td>
                                                <td class="px-1 border border-gray-300">M-06</td>
                                            </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="table-auto text-[10px] text-center border border-gray-300 w-full whitespace-nowrap">
                                        <thead class="bg-gray-100">
                                            <tr>
                                            <th rowspan="2" class="px-1 border border-gray-300">MODULE</th>
                                            <th colspan="3" class="px-1 border border-gray-300">ADDITIONAL SLURRY</th>
                                            <th rowspan="2" class="px-1 border border-gray-300">LITERS</th>
                                            </tr>
                                            <tr>
                                            <th class="px-1 border border-gray-300">NEW</th>
                                            <th class="px-1 border border-gray-300">HOMO</th>
                                            <th class="px-1 border border-gray-300">TIME</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="row in additionalSlurry" :key="row.module" class="hover:bg-gray-50">
                                            <td class="px-1 border border-gray-300">{{ row.module }}</td>
                                            <td class="px-1 border border-gray-300">{{ row.new }}</td>
                                            <td class="px-1 border border-gray-300">{{ row.homo }}</td>
                                            <td class="px-1 border border-gray-300">{{ row.time }}</td>
                                            <td class="px-1 border border-gray-300">{{ row.liters }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div>
                                <div class="flex flex-row items-center gap-6 mt-5 text-[10px] whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        <label class="font-medium text-gray-600">Maximum:</label>
                                        <span class="text-gray-800">{{ coatingMaximum ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <label class="font-medium text-gray-600">Minimum:</label>
                                        <span class="text-gray-800">{{ coatingMinimum ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <label class="font-medium text-gray-600">Average:</label>
                                        <span class="text-gray-800">
                                            {{ coatingAverage != null ? coatingAverage.toFixed(2) : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex flex-row mt-5 text-xs whitespace-nowrap">
                                    <div class="flex flex-col items-end gap-1 font-semibold">
                                        <label>Coating Date: </label>
                                        <label>MIN TB CONTENT: </label>
                                        <label>Loader Operator: </label>
                                    </div>
                                    <div class="flex flex-col gap-1 ml-5">
                                        <span>{{ coatingInfo.coatingDate || 'NA' }}</span>
                                        <span>{{ coatingInfo.minTbContent || 'NA' }}</span>
                                        <span>{{ coatingInfo.loaderOperator || 'NA' }}</span>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 pl-2 ml-2 font-semibold border-l border-gray-200">
                                        <label>Coating Machine No: </label>
                                        <label>Sample Quantity: </label>
                                        <label>Unloader Operator: </label>
                                        <label>Time Start: </label>
                                    </div>
                                    <div class="flex flex-col gap-1 ml-5">
                                        <span>{{ coatingInfo.coatingMachineNo || 'NA' }}</span>
                                        <span>{{ coatingInfo.sampleQuantity || 'NA' }}</span>
                                        <span>{{ coatingInfo.unloaderOperator || 'NA' }}</span>
                                        <span>{{ coatingInfo.timeStart || 'NA' }}</span>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 pl-2 ml-2 font-semibold border-l border-gray-200">
                                        <label>Slurry Lot No: </label>
                                        <label>Total Magnet Weight: </label>
                                        <label>Checker Operator: </label>
                                        <label>Time Finished: </label>
                                    </div>
                                    <div class="flex flex-col gap-1 ml-5">
                                        <span>{{ coatingInfo.slurryLotNo || 'NA' }}</span>
                                        <span>{{ coatingInfo.totalMagnetWeight || 'NA' }}</span>
                                        <span>{{ coatingInfo.checkerOperator || 'NA' }}</span>
                                        <span>{{ coatingInfo.timeFinished || 'NA' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex mt-4 space-x-3">
                                <button
                                    @click="showCoatingPreview = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 rounded-lg shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="saveCoatingSummaryToDatabase"
                                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                >
                                    Proceed
                                    <img src="/photo/arrow_proceed.png" alt="Proceed" class="w-4 h-4 ml-2">
                                </button>
                            </div>

                        </div>
                    </Modal>

                    <Modal :show="showModalCoatingDuplicateWarning" @close="showModalCoatingDuplicateWarning = false">
                        <div
                            class="relative flex flex-col items-start bg-white border-l-4 border-yellow-500 p-6 rounded-xl shadow-md max-w-[95vw] max-h-[90vh] overflow-auto"
                        >
                            <!-- Exit Button -->
                            <button
                            @click="showModalCoatingDuplicateWarning = false"
                            class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                            aria-label="Close modal"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            </button>

                            <!-- Header -->
                            <div class="flex items-center mb-4 space-x-3">
                            <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.58c.75 1.333-.213 3.02-1.742 3.02H3.48c-1.53 0-2.492-1.687-1.742-3.02L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-.993.883L9 8v3a1 1 0 001.993.117L11 11V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                            </svg>
                            <h2 class="text-2xl font-bold text-gray-800">Duplicate Lot Detected</h2>
                            </div>

                            <!-- Body -->
                            <p class="text-base leading-relaxed text-gray-700">
                            A record with the same <span class="font-semibold underline">Lot No</span> already exists in the system.
                            You <span class="font-semibold">may proceed</span>, but please be fully aware that performing actions on
                            duplicate lot data can lead to conflicts if done incorrectly. Ensure that you are making the correct
                            decision before continuing.
                            </p>

                            <!-- Actions -->
                            <div class="flex justify-end w-full mt-6 space-x-3">
                            <button
                                @click="showModalCoatingDuplicateWarning = false"
                                class="px-6 py-2 font-semibold text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700"
                            >
                                Cancel
                            </button>
                            <button
                                @click="proceedDuplicateCoating"
                                class="px-6 py-2 font-semibold text-white transition-colors bg-yellow-500 rounded-lg hover:bg-yellow-600"
                            >
                                Proceed
                            </button>
                            </div>
                        </div>
                    </Modal>

                    <Modal :show="showModalCoatingDuplicatePrevent" @close="showModalCoatingDuplicatePrevent = false">
                        <div
                            class="relative flex flex-col items-start bg-white border-l-8 border-red-600 p-6 rounded-xl shadow-lg max-w-[95vw] max-h-[90vh] overflow-auto"
                        >
                            <!-- Exit Button -->
                            <button
                            @click="showModalCoatingDuplicatePrevent = false"
                            class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                            aria-label="Close modal"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <h2 class="text-2xl font-bold text-red-700">Duplicate Model + Lot Detected</h2>
                            </div>

                            <!-- Body -->
                            <p class="text-base leading-relaxed text-gray-700">
                            A record with the same <span class="font-semibold underline">Model Name</span> and <span class="font-semibold underline">Lot No</span> already exists in the system.
                            You <span class="font-semibold text-red-700">cannot proceed</span> with this entry as it will cause errors and conflicts.
                            Please verify and correct your entry before continuing.
                            </p>

                            <!-- Close Button -->
                            <div class="flex justify-end w-full mt-6">
                            <button
                                @click="showModalCoatingDuplicatePrevent = false"
                                class="px-6 py-2 font-semibold text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                            >
                                Close
                            </button>
                            </div>
                        </div>
                    </Modal>

                    <Modal :show="showEmptyDataWarning" @close="showEmptyDataWarning = false">
                        <div
                            class="relative flex flex-col items-start bg-white border-l-8 border-yellow-500 p-6 rounded-xl shadow-lg max-w-[95vw] max-h-[90vh] overflow-auto"
                        >
                            <!-- Exit Button -->
                            <button
                                @click="showEmptyDataWarning = false"
                                class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                                aria-label="Close modal"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <!-- Header -->
                            <div class="flex items-center mb-4 space-x-3">
                                <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.58c.75 1.333-.213 3.02-1.742 3.02H3.48c-1.53 0-2.492-1.687-1.742-3.02L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-.993.883L9 8v3a1 1 0 001.993.117L11 11V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <h2 class="text-2xl font-bold text-yellow-600">
                                    Empty Data Warning
                                </h2>
                            </div>

                            <!-- Body -->
                            <p class="text-base leading-relaxed text-gray-700">
                                The following datasets are completely empty:
                            </p>
                            <ul class="pl-6 mt-2 text-gray-700 list-disc">
                                <li v-for="context in emptyDataContexts" :key="context.type" class="font-semibold text-yellow-700">
                                    {{ context.label }}
                                </li>
                            </ul>

                            <p class="mt-4 text-gray-700">
                                You can proceed, but leaving them empty may result in incomplete or unusable records.
                            </p>

                            <!-- Buttons -->
                            <div class="flex justify-end w-full mt-6 space-x-3">
                                <button
                                    @click="showEmptyDataWarning = false"
                                    class="px-6 py-2 font-semibold text-gray-700 transition-colors bg-gray-200 rounded-lg hover:bg-gray-300"
                                >
                                    Go Back
                                </button>

                                <button
                                    @click="proceedFinalizeEvenEmpty"
                                    class="px-6 py-2 font-semibold text-white transition-colors bg-yellow-500 rounded-lg hover:bg-yellow-600"
                                >
                                    Proceed Anyway
                                </button>
                            </div>
                        </div>
                    </Modal>


                </div>
            </div>
        </div>





        <!-- Section Content -->
        <div v-if="activeSection === 'film_pasting'">
            <div class="flex flex-row justify-center gap-0">
                <div class="w-full px-2 mx-auto mr-10 space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">Film Pasting Information</h2>
                    <div  class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Model<span class="text-red-500"> *</span></label>
                            <select v-model="filmPastingInfo.selected_model" class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50">
                                <option v-for="item in model_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.lot_no" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Film Pasting Date, Machine No, Total Magnet Weight -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Pasting Date<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_pasting_date" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Machine No<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.machine_no" @input="filmPastingInfo.machine_no = filmPastingInfo.machine_no.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.total_magnet_weight" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Loader Operator, Unloader Operator, Checker Operator -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Loader Operator<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.loader_operator" @input="filmPastingInfo.loader_operator = filmPastingInfo.loader_operator.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Unloader Operator<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.unloader_operator" @input="filmPastingInfo.unloader_operator = filmPastingInfo.unloader_operator.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Checker Operator<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.checker_operator" @input="filmPastingInfo.checker_operator = filmPastingInfo.checker_operator.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Film Coating Amount, Time Start, Time Finished -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Coating Amount<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_coating_amount" @input="filmPastingInfo.film_coating_amount = filmPastingInfo.film_coating_amount.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Start<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.time_start" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Finished<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.time_finished" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Remarks<span class="text-red-500"> *</span></label>
                        <input v-model="filmPastingInfo.remarks" @input="filmPastingInfo.remarks = filmPastingInfo.remarks.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>
                <div class="w-full px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">-</h2>
                    <!-- Group: Film Pasting Date, Machine No, Total Magnet Weight -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Type (Tb or Dy)<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_type" @input="filmPastingInfo.film_type = filmPastingInfo.film_type.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Class<span class="text-red-500"> *</span></label>
                            <input v-model="filmPastingInfo.film_class" @input="filmPastingInfo.film_class = filmPastingInfo.film_class.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 mt-10 bg-white shadow-lg rounded-2xl">
                <!-- Header -->
                <h2 class="pb-2 mb-6 text-lg font-bold text-gray-800 border-b-2 border-cyan-400">
                HOURLY CHECKING
                </h2>

                <!-- Horizontal scrollable blocks container -->
                <div class="flex gap-4 pb-2 overflow-x-auto">
                <div
                    v-for="(block, index) in hLineTLineBlocks"
                    :key="block.id"
                    class="min-w-[320px] p-4 rounded-xl border border-cyan-300 shadow-sm bg-cyan-50 flex-shrink-0"
                >
                    <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-teal-800">Block {{ index + 1 }}</h3>
                    <div class="space-x-2">
                        <button
                            v-if="index !== 0"
                            @click="removeBlock(block.id)"
                            class="px-2 py-1 text-xs font-bold text-white transition bg-red-500 rounded hover:bg-red-600"
                        >
                            Remove
                        </button>
                        <button
                            v-if="index === hLineTLineBlocks.length - 1 && hLineTLineBlocks.length < 20"
                            @click="addBlock"
                            class="px-2 py-1 text-xs font-bold text-white transition bg-teal-500 rounded hover:bg-teal-600"
                        >
                            Add Block
                        </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="font-semibold text-teal-800 bg-cyan-200">
                            <th class="p-2 text-left border border-cyan-300">ITEMS</th>
                            <th class="p-2 text-left border border-cyan-300">H-LINE</th>
                            <th class="p-2 text-left border border-cyan-300">T-LINE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="(row, i) in block.values"
                            :key="i"
                            class="transition-colors hover:bg-cyan-100"
                            >
                            <td class="p-2 font-medium border border-cyan-300">{{ row.item }}</td>
                            <td class="p-2 border border-cyan-300" :colspan="row.item === 'Water Level (mm)' || row.item === 'Time Check' || row.item === 'Check By' ? 2 : 1">
                            <!-- H-LINE -->
                                <input
                                    v-if="row.item === 'Time Check'"
                                    v-model="row.hLine"
                                    type="time"
                                    class="w-full px-2 py-1 text-xs border rounded border-cyan-300 focus:ring-2 focus:ring-teal-400 focus:border-teal-500"
                                />

                                <input
                                    v-else-if="row.item === 'Check By'"
                                    v-model="row.hLine"
                                    @input="row.hLine = row.hLine.toUpperCase()"
                                    type="text"
                                    class="w-full px-2 py-1 text-xs border rounded border-cyan-300 focus:ring-2 focus:ring-teal-400 focus:border-teal-500"
                                />

                                <input
                                    v-else-if="row.item === 'Water Level (mm)'"
                                    v-model.number="row.value"
                                    type="number"
                                    class="w-full px-2 py-1 text-xs border rounded border-cyan-300 focus:ring-2 focus:ring-teal-400 focus:border-teal-500"
                                />

                                <input
                                    v-else-if="row.item === 'Dryer Temp Setting (°C)'"
                                    v-model.number="row.hLine"
                                    type="number"
                                    class="w-full px-2 py-1 text-xs border rounded border-cyan-300 focus:ring-2 focus:ring-teal-400 focus:border-teal-500"
                                />

                                <input
                                    v-else
                                    v-model.number="row.hLine"
                                    type="number"
                                    step="0.01"
                                    @blur="row.hLine = Number(row.hLine).toFixed(2)"
                                    class="w-full px-2 py-1 text-xs border rounded border-cyan-300 focus:ring-2 focus:ring-teal-400 focus:border-teal-500"
                                />
                            </td>
                            <td
                                v-if="row.item !== 'Water Level (mm)' && row.item !== 'Time Check' && row.item !== 'Check By'"
                                class="p-2 border border-cyan-300">
                                <!-- T-LINE -->
                                <input
                                    v-if="row.item === 'Dryer Temp Setting (°C)'"
                                    v-model.number="row.tLine"
                                    type="number"
                                    class="w-full px-2 py-1 text-xs border rounded border-cyan-300 focus:ring-2 focus:ring-teal-400 focus:border-teal-500"
                                />

                                <input
                                    v-else
                                    v-model.number="row.tLine"
                                    type="number"
                                    step="0.01"
                                    @blur="row.tLine = Number(row.tLine).toFixed(2)"
                                    class="w-full px-2 py-1 text-xs border rounded border-cyan-300 focus:ring-2 focus:ring-teal-400 focus:border-teal-500"
                                />
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <div class="flex flex-row gap-10 mt-5">
                <div class="flex flex-col gap-10 mt-5">
                    <div class="w-full px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">H-Line Parameters</h2>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sprayer Water amount</label>
                            <input v-model="filmPastingInfo.h_line_parameters['sprayer_water_amount']" disabled type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm cursor-not-allowed disabled:bg-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film paste Lot no</label>
                            <input v-model="filmPastingInfo.h_line_parameters['film_paste_lot_no_1']" type="text" @input="filmPastingInfo.h_line_parameters['film_paste_lot_no_1'] = filmPastingInfo.h_line_parameters['film_paste_lot_no_1'].toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <input v-model="filmPastingInfo.h_line_parameters['film_paste_lot_no_2']" type="text" @input="filmPastingInfo.h_line_parameters['film_paste_lot_no_2'] = filmPastingInfo.h_line_parameters['film_paste_lot_no_2'].toUpperCase()" class="w-full mt-2 text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine Humidity %</label>
                            <input v-model="filmPastingInfo.h_line_parameters['film_machine_humidity']" disabled type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm cursor-not-allowed disabled:bg-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine temperature</label>
                            <input v-model="filmPastingInfo.h_line_parameters['film_machine_temperature']" disabled type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm cursor-not-allowed disabled:bg-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Dryer Temp setting (°C)</label>
                            <input  v-model="filmPastingInfo.h_line_parameters['dryer_temp_setting']" @input="filmPastingInfo.h_line_parameters['dryer_temp_setting'] = filmPastingInfo.h_line_parameters['dryer_temp_setting'].toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-10 mt-5">
                    <div class="w-full px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">T-Line Parameters</h2>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sprayer Water amount</label>
                            <input v-model="filmPastingInfo.t_line_parameters['sprayer_water_amount']" type="number" disabled class="w-full text-xs border-gray-300 rounded-lg shadow-sm cursor-not-allowed disabled:bg-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film paste Lot no</label>
                            <input v-model="filmPastingInfo.t_line_parameters['film_paste_lot_no_1']" type="text" @input="filmPastingInfo.t_line_parameters['film_paste_lot_no_1'] = filmPastingInfo.t_line_parameters['film_paste_lot_no_1'].toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <input v-model="filmPastingInfo.t_line_parameters['film_paste_lot_no_2']" type="text" @input="filmPastingInfo.t_line_parameters['film_paste_lot_no_2'] = filmPastingInfo.t_line_parameters['film_paste_lot_no_2'].toUpperCase()" class="w-full mt-2 text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine Humidity (%)</label>
                            <input v-model="filmPastingInfo.t_line_parameters['film_machine_humidity']" disabled type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm cursor-not-allowed disabled:bg-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine temperature (°C)</label>
                            <input v-model="filmPastingInfo.t_line_parameters['film_machine_temperature']" disabled type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm cursor-not-allowed disabled:bg-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Dryer Temp setting (°C)</label>
                            <input v-model="filmPastingInfo.t_line_parameters['dryer_temp_setting']" @input="filmPastingInfo.t_line_parameters['dryer_temp_setting'] = filmPastingInfo.t_line_parameters['dryer_temp_setting'].toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-10 mt-5">
                    <div class="w-full px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">
                            Setter Sand Application per Layer <span class="text-red-500">*</span>
                        </h2>

                        <div class="flex flex-col space-y-3">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <input
                                type="radio"
                                name="setter_sand"
                                value="1"
                                v-model="filmPastingInfo.is_setter_sand"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <span>With Setter Sand</span>
                            </label>

                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <input
                                type="radio"
                                name="setter_sand"
                                value="0"
                                v-model="filmPastingInfo.is_setter_sand"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <span>Without Setter Sand</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-row space-x-5">
                        <!-- Finalize button (primary) -->
                        <button
                            @click="finalizeFilmPasting"
                            class="px-4 py-2 font-semibold text-white transition-colors bg-teal-600 rounded shadow hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500"
                        >
                            Finalize
                        </button>

                        <!-- Reset Fields button (secondary) -->
                        <button
                            @click="resetFilmPastingFields"
                            class="px-4 py-2 font-semibold text-teal-600 transition-colors bg-white border border-teal-600 rounded shadow hover:bg-teal-50 focus:outline-none focus:ring-2 focus:ring-teal-200"
                        >
                            Reset Fields
                        </button>
                    </div>

                </div>
            </div>

            <Modal :show="showModalFilmPastingPreview" @close="showModalFilmPastingPreview = false">
                <div
                    class="relative flex flex-col items-start bg-white p-6 rounded-xl shadow-2xl max-w-[95vw] max-h-[90vh] overflow-auto pr-12"
                >
                    <!-- Exit Button -->
                    <button
                    @click="showModalFilmPastingPreview = false"
                    class="text-gray-400 transition duration-150 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <p class="mb-6 text-xl font-semibold text-gray-900">
                        Final Review — Confirm All Details Before Submission
                    </p>

                    <div class="space-y-10">

                        <!-- GROUP 1 -->
                        <section>
                            <h2 class="pb-1 mb-4 text-lg font-bold text-gray-800 border-b">
                                General Information
                            </h2>

                            <table class="w-full text-sm border border-gray-300">
                                <tbody class="divide-y">

                                    <tr>
                                        <th class="w-64 p-2 font-medium bg-gray-50">Model</th>
                                        <td class="p-2">{{ filmPastingInfo.selected_model }}</td>
                                    </tr>

                                    <tr>
                                        <th class="w-64 p-2 font-medium bg-gray-50">Lot No</th>
                                        <td class="p-2">{{ filmPastingInfo.lot_no }}</td>
                                    </tr>

                                    <tr>
                                        <th class="w-64 p-2 font-medium bg-gray-50">Film Pasting Date</th>
                                        <td class="p-2">{{ filmPastingInfo.film_pasting_date }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Machine No.</th>
                                        <td class="p-2">{{ filmPastingInfo.machine_no }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Total Magnet Weight</th>
                                        <td class="p-2">{{ filmPastingInfo.total_magnet_weight }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Loader Operator</th>
                                        <td class="p-2">{{ filmPastingInfo.loader_operator }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Unloader Operator</th>
                                        <td class="p-2">{{ filmPastingInfo.unloader_operator }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Checker Operator</th>
                                        <td class="p-2">{{ filmPastingInfo.checker_operator }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Film Coating Amount</th>
                                        <td class="p-2">{{ filmPastingInfo.film_coating_amount }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Time Start</th>
                                        <td class="p-2">{{ filmPastingInfo.time_start }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Time Finished</th>
                                        <td class="p-2">{{ filmPastingInfo.time_finished }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Remarks</th>
                                        <td class="p-2">{{ filmPastingInfo.remarks }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Film Type</th>
                                        <td class="p-2">{{ filmPastingInfo.film_type }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Film Class</th>
                                        <td class="p-2">{{ filmPastingInfo.film_class }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </section>

                        <!-- GROUP 2A -->
                        <section>
                            <h2 class="pb-1 mb-4 text-lg font-bold text-gray-800 border-b">
                                H-Line Parameters
                            </h2>

                            <table class="w-full text-sm border border-gray-300">
                                <tbody class="divide-y">

                                    <tr>
                                        <th class="w-64 p-2 font-medium bg-gray-50">Sprayer Water Amount</th>
                                        <td class="p-2">{{ filmPastingInfo.h_line_parameters.sprayer_water_amount }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Film Paste Lot #1</th>
                                        <td class="p-2">{{ filmPastingInfo.h_line_parameters['film_paste_lot_no_1'] }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Film Paste Lot #2</th>
                                        <td class="p-2">{{ filmPastingInfo.h_line_parameters['film_paste_lot_no_2'] }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Machine Humidity</th>
                                        <td class="p-2">{{ filmPastingInfo.h_line_parameters.film_machine_humidity }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Machine Temperature</th>
                                        <td class="p-2">{{ filmPastingInfo.h_line_parameters.film_machine_temperature }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Dryer Setting</th>
                                        <td class="p-2">{{ filmPastingInfo.h_line_parameters.dryer_temp_setting }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </section>

                        <!-- GROUP 2B -->
                        <section>
                            <h2 class="pb-1 mb-4 text-lg font-bold text-gray-800 border-b">
                                T-Line Parameters
                            </h2>

                            <table class="w-full text-sm border border-gray-300">
                                <tbody class="divide-y">

                                    <tr>
                                        <th class="w-64 p-2 font-medium bg-gray-50">Sprayer Water Amount</th>
                                        <td class="p-2">{{ filmPastingInfo.t_line_parameters.sprayer_water_amount }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Film Paste Lot #1</th>
                                        <td class="p-2">{{ filmPastingInfo.t_line_parameters['film_paste_lot_no_1'] }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Film Paste Lot #2</th>
                                        <td class="p-2">{{ filmPastingInfo.t_line_parameters['film_paste_lot_no_2'] }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Machine Humidity</th>
                                        <td class="p-2">{{ filmPastingInfo.t_line_parameters.film_machine_humidity }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Machine Temperature</th>
                                        <td class="p-2">{{ filmPastingInfo.t_line_parameters.film_machine_temperature }}</td>
                                    </tr>

                                    <tr>
                                        <th class="p-2 font-medium bg-gray-50">Dryer Setting</th>
                                        <td class="p-2">{{ filmPastingInfo.t_line_parameters.dryer_temp_setting }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </section>

                        <!-- GROUP 3 -->
                        <section>
                            <h2 class="pb-1 mb-4 text-lg font-bold text-gray-800 border-b">
                                Additional Settings
                            </h2>

                            <table class="w-full text-sm border border-gray-300">
                                <tbody class="divide-y">
                                    <tr>
                                        <th class="w-64 p-2 font-medium text-left bg-gray-50">Setter Sand</th>
                                        <td class="p-2">
                                            {{ filmPastingInfo.is_setter_sand === '1' ? 'Yes' : 'No' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>

                        <div class="flex justify-end mt-6 space-x-3">
                            <!-- Cancel -->
                            <button
                                @click="showModalFilmPastingPreview = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100">
                                Cancel
                            </button>

                            <!-- Proceed -->
                            <button
                                @click="saveToDataBaseFilmPasting"
                                class="px-4 py-2 text-sm font-semibold text-white transition bg-teal-600 rounded-md shadow-md hover:bg-teal-700">
                                Proceed
                            </button>
                        </div>

                    </div>


                </div>
            </Modal>

            <Modal :show="showModalFilmPasteDuplicateWarning" @close="showModalFilmPasteDuplicateWarning = false">
                <div
                    class="relative flex flex-col items-start bg-white border-l-4 border-yellow-500 p-6 rounded-xl shadow-md max-w-[95vw] max-h-[90vh] overflow-auto"
                >
                    <!-- Exit Button -->
                    <button
                    @click="showModalFilmPasteDuplicateWarning = false"
                    class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <!-- Header -->
                    <div class="flex items-center mb-4 space-x-3">
                    <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.58c.75 1.333-.213 3.02-1.742 3.02H3.48c-1.53 0-2.492-1.687-1.742-3.02L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-.993.883L9 8v3a1 1 0 001.993.117L11 11V8a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-800">Duplicate Lot Detected</h2>
                    </div>

                    <!-- Body -->
                    <p class="text-base leading-relaxed text-gray-700">
                    A record with the same <span class="font-semibold underline">Lot No</span> already exists in the system.
                    You <span class="font-semibold">may proceed</span>, but please be fully aware that performing actions on
                    duplicate lot data can lead to conflicts if done incorrectly. Ensure that you are making the correct
                    decision before continuing.
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end w-full mt-6 space-x-3">
                    <button
                        @click="showModalFilmPasteDuplicateWarning = false"
                        class="px-6 py-2 font-semibold text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                    <button
                        @click="proceedDuplicateFilmPaste"
                        class="px-6 py-2 font-semibold text-white transition-colors bg-yellow-500 rounded-lg hover:bg-yellow-600"
                    >
                        Proceed
                    </button>
                    </div>
                </div>
            </Modal>

            <Modal :show="showModalFilmPasteDuplicatePrevent" @close="showModalFilmPasteDuplicatePrevent = false">
                <div
                    class="relative flex flex-col items-start bg-white border-l-8 border-red-600 p-6 rounded-xl shadow-lg max-w-[95vw] max-h-[90vh] overflow-auto"
                >
                    <!-- Exit Button -->
                    <button
                    @click="showModalFilmPasteDuplicatePrevent = false"
                    class="absolute text-gray-400 transition-colors top-4 right-4 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    <h2 class="text-2xl font-bold text-red-700">Duplicate Model + Lot Detected</h2>
                    </div>

                    <!-- Body -->
                    <p class="text-base leading-relaxed text-gray-700">
                    A record with the same <span class="font-semibold underline">Model Name</span> and <span class="font-semibold underline">Lot No</span> already exists in the system.
                    You <span class="font-semibold text-red-700">cannot proceed</span> with this entry as it will cause errors and conflicts.
                    Please verify and correct your entry before continuing.
                    </p>

                    <!-- Close Button -->
                    <div class="flex justify-end w-full mt-6">
                    <button
                        @click="showModalFilmPasteDuplicatePrevent = false"
                        class="px-6 py-2 font-semibold text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700"
                    >
                        Close
                    </button>
                    </div>
                </div>
            </Modal>

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
import { useAuth } from '@/Composables/useAuth.js';

const toast = useToast();

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {
        const start = Date.now();
        const maxWait = 5000; // 5 seconds

        while (!state.user) {
            if (Date.now() - start > maxWait) {
                console.error('User data failed to load in time. Redirecting...');
                Inertia.visit('/'); // Redirect if user never loads
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50));
        }

        if (!state.isAuthenticated) {
            console.warn('User is not authenticated. Redirecting...');
            Inertia.visit('/');
            return false;
        }

        console.log("USER AUTHENTICATED!", `${state.user.firstName} ${state.user.surname}`);
        return true;

    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/');
        return false;
    }
};

const userManageLogging = async (logEvent) => {
    try{
        const responseUserLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Initial Process',
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
            section: 'Initial Process',
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
const showModalDuplicatePrevent = ref(false);
const showModalEmptyConcentrationWarning = ref(false);
const showDeleteModal = ref(false);
const showEmptyDataWarning = ref(false);
const emptyDataContexts = ref([]);

const controlSheetData = ref([]);
const coatingSummaryData = ref([]);
const filmPastingSummaryData = ref([]);
const controlSheetSearch = ref('');
const coatingSummarySearch = ref('');
const filmPastingSearch = ref('');

const activeSection = ref('overall_summary');
const allBoxes = ['A','B','C','D','E','F','G','H','J','K'];
const boxesEndList = ref(['A','B','C','D','E','F','G','H','J','K']);
const excessBoxesEndList = ref(['A','B','C','D','E','F','G','H','J','K']);
const model_names = ref([]);
const boxNoValues = ref({});
//const weightValues = ref({});
const boxWithMagnetWeight = ref({});
const boxWeight = ref({});
const boxWithMagnetWeightExcess = ref({});
const boxWeightExcess = ref({});
const qtyValues = ref({});
const boxNoValuesExcess = ref({});
//const weightValuesExcess = ref({});
const qtyValuesExcess = ref({});

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

const canEnableExcessBoxes = computed(() => {
    return (
        initial_mpcs.selectedBoxStart === 'A' &&
        initial_mpcs.selectedBoxEnd === 'K'
    );
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

const weightValues = computed(() => {
    const result = {};

    visibleBoxes.value.forEach(box => {
        const withMagnetRaw = parseFloat(boxWithMagnetWeight.value[box]) || 0;
        const boxWtRaw = parseFloat(boxWeight.value[box]) || 0;

        const diff = withMagnetRaw - boxWtRaw;

        // use roundToTwo for consistent 2-decimal rounding
        result[box] = roundToTwo(diff);

        // optional debug log
        //console.log(`${box}: withMagnet=${withMagnetRaw}, box=${boxWtRaw}, diff=${diff}, rounded=${result[box]}`);
    });

    return result;
});


const weightValuesExcess = computed(() => {
    const result = {};

    visibleExcessBoxes.value.forEach(box => {
        const withMagnetRaw = parseFloat(boxWithMagnetWeightExcess.value[box]) || 0;
        const boxWtRaw = parseFloat(boxWeightExcess.value[box]) || 0;

        //console.log(`[${box}] Raw inputs: withMagnet=${boxWithMagnetWeightExcess.value[box]} (${withMagnetRaw}), boxWeight=${boxWeightExcess.value[box]} (${boxWtRaw})`);

        const diffRaw = withMagnetRaw - boxWtRaw;
        //console.log(`[${box}] Raw diff:`, diffRaw);

        // safe 2-digit rounding
        const rounded = roundToTwo(diffRaw);
        //console.log(`[${box}] Rounded diff:`, rounded);

        result[box] = rounded;
    });

    return result;
});

const roundToTwo = (num) => {
    const rounded = Math.round((num + Number.EPSILON) * 100) / 100;
    //console.log(`roundToTwo(${num}) = ${rounded}`);
    return rounded;
};

allBoxes.forEach(box => {
    boxNoValues.value[box] = '';
    //weightValues.value[box] = '';
    boxWeight.value[box] = '';
    boxWithMagnetWeight.value[box] = '';
    qtyValues.value[box] = '';
    boxNoValuesExcess.value[box] = '';
    weightValuesExcess.value[box] = '';
    qtyValuesExcess.value[box] = '';
    boxWithMagnetWeightExcess.value[box] = '';
    boxWeightExcess.value[box] = '';
});

const sectionTitle = computed(() => {
    switch (activeSection.value) {
        case 'overall_summary': return 'Overall Summary';
        case 'control_sheet': return 'Mass Production Control Sheet';
        case 'coating': return 'Coating Summary';
        case 'film_pasting': return 'Film Pasting Summary';
        default: return '';
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

const totalWt = computed(() => {
    const mainTotal = Object.values(weightValues.value).reduce((acc, val) => acc + Number(val || 0), 0);
    const excessTotal = Object.values(weightValuesExcess.value).reduce((acc, val) => acc + Number(val || 0), 0);
    return Number((mainTotal + excessTotal).toFixed(2)); // keep 2 decimals like your per-box weights
});

const numberOfBoxes = computed(() => {
    if (initial_mpcs.moreThanTenBoxes) {
        return visibleBoxes.value.length + visibleExcessBoxes.value.length;
    }
    return visibleBoxes.value.length;
});

const formatCoatingMCNo = () => {
    let val = initial_mpcs.coatingMCNo
        .toUpperCase()
        .replace(/[^A-Z0-9-]/g, '');

    // Block numeric-first input
    if (/^\d/.test(val)) {
        initial_mpcs.coatingMCNo = '';
        return;
    }

    // Auto-expand F → FP
    if (val === 'F') {
        initial_mpcs.coatingMCNo = 'FP';
        return;
    }

    // Allow clean prefixes
    if (val === 'C' || val === 'FP') {
        initial_mpcs.coatingMCNo = val;
        return;
    }

    // Strict structure
    const match = val.match(/^(C|FP)\-?(\d{0,3})$/);
    if (!match) return;

    const prefix = match[1];
    const numRaw = match[2];

    if (!numRaw) {
        initial_mpcs.coatingMCNo = prefix;
        return;
    }

    const num = parseInt(numRaw, 10);

    if (num < 1) {
        initial_mpcs.coatingMCNo = `${prefix}-01`;
        return;
    }

    if (num > 99) {
        initial_mpcs.coatingMCNo = `${prefix}-99`;
        return;
    }

    initial_mpcs.coatingMCNo = `${prefix}-${String(num).padStart(2, '0')}`;
};


//Data fetching zone ------- Data fetching zone ------- Data fetching zone ------- Data fetching zone ------- Data fetching zone

const getControlSheetData = async () => {
    try{
        const response = await axios.get(`/api/initial_control_sheet`);
        controlSheetData.value = response.data;
        //console.log('Initial Control Sheet Data ref array: ', controlSheetData.value);
    }catch(error){
        console.error('Failed to fetch initial control sheet data');
    }
}

const getCoatingSummary = async () => {
    try{
        const response = await axios.get(`/api/initial-coating`);
        coatingSummaryData.value = response.data;
        //console.log('Initial Coating Summary Data ref array: ', coatingSummaryData.value);
    }catch(error){
        console.error('Failed to fetch initial coating summary data', error);
    }
}

const getFilmPastingSummary = async () => {
    try{
        const response = await axios.get(`/api/initial-film-pasting`);
        filmPastingSummaryData.value = response.data;
        //console.log('Initial Film Pasting Summary Data ref array: ', filmPastingSummaryData.value);
    }catch(error){
        console.error('Failed to fetch initial film pasting summary data');
    }
}

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


// data deletion section

const deleteTarget = ref({
    id: null,
    type: null, // 'control' | 'coating' | 'film'
});

const openDeleteModal = (id, type) => {
    deleteTarget.value = { id, type };
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deleteTarget.value = { id: null, type: null };
};

const confirmDelete = async () => {
    const { id, type } = deleteTarget.value;

    try {
        if (type === 'control') {
            const response = await axios.delete(`/api/initial_control_sheet/${id}`);
            const deleted = response.data.deletedData;

            controlSheetData.value = controlSheetData.value.filter(i => i.id !== id);
            toast.info('Data Deleted Successfully');

            await userManageLogging(
                `has deleted Control Sheet: Model: ${deleted.model_name}, Lot: ${deleted.lot_no}`
            );
        }

        if (type === 'coating') {
            const response = await axios.delete(`/api/initial-coating/${id}`);
            const deleted = response.data.deletedData;

            coatingSummaryData.value = coatingSummaryData.value.filter(i => i.id !== id);
            toast.info('Data Deleted Successfully');

            await userManageLogging(
                `has deleted Coating Summary: Model: ${deleted.model_name}, Lot: ${deleted.lot_no}`
            );
        }

        if (type === 'film') {
            const response = await axios.delete(`/api/initial-film-pasting/${id}`);
            const deleted = response.data.deletedData;

            filmPastingSummaryData.value = filmPastingSummaryData.value.filter(i => i.id !== id);
            toast.info('Data Deleted Successfully');

            await userManageLogging(
                `has deleted Film Pasting Summary: Model: ${deleted.model_name}, Lot: ${deleted.lot_no}`
            );
        }

    } catch (error) {
        console.error('Delete failed', error);
    } finally {
        closeDeleteModal();
    }
};

// data deletion section END

const filteredControlSheetData = computed(() => {
    if (!controlSheetSearch.value) return controlSheetData.value;

    return controlSheetData.value.filter(item =>
        item.lot_no
            ?.toString()
            .toLowerCase()
            .includes(controlSheetSearch.value.toLowerCase())
    );
});

const filteredCoatingSummaryData = computed(() => {
    if (!coatingSummarySearch.value) return coatingSummaryData.value;

    return coatingSummaryData.value.filter(item =>
        item.lot_no
            ?.toString()
            .toLowerCase()
            .includes(coatingSummarySearch.value.toLowerCase())
    );
});

const filteredFilmPastingSummaryData = computed(() => {
    if (!filmPastingSearch.value) return filmPastingSummaryData.value;

    return filmPastingSummaryData.value.filter(item =>
        item.lot_no
            ?.toString()
            .toLowerCase()
            .includes(filmPastingSearch.value.toLowerCase())
    );
});

const cancelProceed = () => {
    showModalPreview.value = false;
}

const clearAll = () => {
    // Reset all mpcs fields except box selection
    const skipKeys = ['selectedBoxStart', 'selectedBoxEnd', 'selectedExcessBoxStart', 'selectedExcessBoxEnd', 'selectedModel','moreThanTenBoxes'];

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

    Object.keys(boxWeight.value).forEach(key => {
        boxWeight.value[key] = '';
    });

    Object.keys(boxWithMagnetWeight.value).forEach(key => {
        boxWithMagnetWeight.value[key] = '';
    });

    Object.keys(boxWithMagnetWeightExcess.value).forEach(key => {
        boxWithMagnetWeightExcess.value[key] = '';
    });

    Object.keys(boxWeightExcess.value).forEach(key => {
        boxWeightExcess.value[key] = '';
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
        if (item.name === 'Raw Material Code' && item.field.length < 8) {
            toast.warning('Raw Material Code must be 8 characters.');
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
        if (boxNoValues.value[box].length < 8) {
            toast.warning(`Box No for main box ${box} must be at least 8 characters.`);
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
            if (boxNoValuesExcess.value[box].length < 8) {
                toast.warning(`Box No for excess box ${box} must be at least 8 characters.`);
                return;
            }
        }
    }

    // All checks passed → open modal
    await lotNoValidation();
};

const proceedDuplicate = () => {
    showModalDuplicateWarning.value = false;
    showModalPreview.value = true;
}

const lotNoValidation = async () => {
    try {
        if (!initial_mpcs.lotNo) {
            toast.error('Lot No. is required.');
            return false;
        }

        const response = await axios.post('/api/initial-control-sheet/check-duplicate', {
            lot_no: initial_mpcs.lotNo,
            model_name: initial_mpcs.selectedModel
        });
        //console.log("Success");
        if(response.data.duplicate_pair) {
            showModalDuplicatePrevent.value = true;
            return false;
        }else if (response.data.duplicate_lot) {
            showModalDuplicateWarning.value = true;
            //console.log("ShowModalDuplicate Warning: ", showModalDuplicateWarning.value);
        }else{
            showModalPreview.value = true;
        }

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
            { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxes.map(box => [box, totalQty.value])) },
            { rowTitle: 'BOX WEIGHT', data: Object.fromEntries(boxes.map(box => [box, boxWeight.value[box] || ''])) },
            { rowTitle: 'BOX WITH MAGNET WEIGHT', data: Object.fromEntries(boxes.map(box => [box, boxWithMagnetWeight.value[box] || ''])) }
        ];

        // Payload for Laravel API
        const payload = {
            model_name: initial_mpcs.selectedModel,
            lot_no: initial_mpcs.lotNo,
            layer_data: layerPayload,
            total_boxes: numberOfBoxes.value,
        };

        //console.log('Payload for Normal Case: ', payload);
        //console.log('Number Of Boxes: ',numberOfBoxes.value);

        // POST to your API
        const response = await axios.post('/api/initial_control_sheet', payload);
        console.log('Normal case saved:', response.data);
        toast.success('Normal Control Sheet saved successfully.');
        clearAll(); // reset form
    } catch (err) {
        console.error('Error saving normal case:', err);
        toast.error('Failed to save normal Control Sheet.');
    } finally {
        showModalPreview.value = false;
        await getControlSheetData();
        await getCoatingSummary();
        await getFilmPastingSummary();
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
            { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxes.map(box => [box, totalQty.value])) },
            { rowTitle: 'BOX WEIGHT', data: Object.fromEntries(boxes.map(box => [box, boxWeight.value[box] || ''])) },
            { rowTitle: 'BOX WITH MAGNET WEIGHT', data: Object.fromEntries(boxes.map(box => [box, boxWithMagnetWeight.value[box] || ''])) }
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
            { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxes2.map(box => [box, totalQty.value])) },
            { rowTitle: 'BOX WEIGHT', data: Object.fromEntries(boxes2.map(box => [box, boxWeightExcess.value[box] || ''])) },
            { rowTitle: 'BOX WITH MAGNET WEIGHT', data: Object.fromEntries(boxes2.map(box => [box, boxWithMagnetWeightExcess.value[box] || ''])) }
        ];

        // Payload for Laravel API
        const payload = {
            model_name: initial_mpcs.selectedModel,
            lot_no: initial_mpcs.lotNo,
            layer_data: layerPayload,
            excess_data: excessPayload,
            total_boxes: numberOfBoxes.value,
        };

        //console.log('Excess payload', payload);
        //console.log('Number Of Boxes: ',numberOfBoxes.value);

        // POST to your API
        const response = await axios.post('/api/initial_control_sheet', payload);
        //console.log('Excess case saved:', response.data);
        toast.success('Excess Control Sheet saved successfully.');
        clearAll(); // reset form
    } catch (err) {
        console.error('Error saving excess case:', err);
        toast.error('Failed to save excess Control Sheet.');
    } finally {
        showModalPreview.value = false;
        await getControlSheetData();
        await getCoatingSummary();
        await getFilmPastingSummary();
    }
};

//COATING SUMMARY PORTION START

const showModalCoatingDuplicatePrevent = ref(false);
const showModalCoatingDuplicateWarning = ref(false);
const showCoatingPreview = ref(false);
const coatingLotNo = ref();
const coatingModel = ref();

const coatingInfo = reactive({
    coatingDate: '',
    coatingMachineNo: '',
    slurryLotNo: '',
    minTbContent: '',
    sampleQuantity: '',
    totalMagnetWeight: '',
    loaderOperator: '',
    unloaderOperator: '',
    checkerOperator: '',
    timeStart: '',
    timeFinished: '',
    remarks: ''
});

const additionalSlurry = ref([
    { module: "M-01", new: null, homo: null, time: null, liters: null },
    { module: "M-02", new: null, homo: null, time: null, liters: null },
    { module: "M-03", new: null, homo: null, time: null, liters: null },
    { module: "M-04", new: null, homo: null, time: null, liters: null },
    { module: "M-05", new: null, homo: null, time: null, liters: null },
    { module: "M-06", new: null, homo: null, time: null, liters: null },
]);

const coatingsTable = ref(
    Array.from({ length: 35 }, (_, i) => ({
        no: i + 1,
        coating: null
    }))
);

// Temporary string bound to textarea
const coatingsInput = ref('');

// Function to handle pasted or typed input
const handlePasteInput = () => {
    if (!coatingsInput.value) return;

    // Split by newlines, filter out empty lines, parse as numbers
    const lines = coatingsInput.value
        .split(/\r?\n/)
        .map(line => line.trim())
        .filter(line => line !== '')
        .map(Number);

    // Assign parsed numbers to coatingsTable in order
    lines.forEach((num, index) => {
        if (index < coatingsTable.value.length) {
        coatingsTable.value[index].coating = isNaN(num) ? null : num;
        }
    });
};

const visibleCoatings = computed(() => coatingsTable.value);

const coatingValues = computed(() =>
  coatingsTable.value
    .map(row => row.coating)
    .filter(val => val !== null && val !== '' && !isNaN(val))
    .map(Number)
);

const coatingMaximum = computed(() =>
    coatingValues.value.length ? Math.max(...coatingValues.value) : null
);

const coatingMinimum = computed(() =>
    coatingValues.value.length ? Math.min(...coatingValues.value) : null
);

const coatingAverage = computed(() =>
    coatingValues.value.length
    ? coatingValues.value.reduce((sum, val) => sum + val, 0) / coatingValues.value.length
    : null
);

// Modules
const modules = ["M-01", "M-02", "M-03", "M-04", "M-05", "M-06", "M-06"];
// Ranges
const ranges = ["1-5", "6-10", "11-15", "16-20", "21-25", "26-30", "31-35"];

const concentrationData = ref(
  ranges.map(() => modules.map(() => null)) // 7x8 grid of nulls
);

const visibleRanges = computed(() => ranges);
const visibleConcentrationData = computed(() => concentrationData.value);

// For display (keeps index alignment with your inputs)
const displayCoatings = computed(() =>
  coatingsTable.value.map(row => row.coating ?? '')
);


const hasRows31to35 = computed(() => {
    return displayCoatings.value.slice(30, 35).some(
        val => val != null && val !== '' && val !== '-'
    )
})

const resetCoatingSummaryData = () => {
    coatingsInput.value = '';

    // Reset coatingInfo
    Object.assign(coatingInfo, {
        selectedMassProd: '',
        selectedLayer: null,
        coatingDate: '',
        coatingMachineNo: '',
        slurryLotNo: '',
        minTbContent: '',
        sampleQuantity: '',
        totalMagnetWeight: '',
        loaderOperator: '',
        unloaderOperator: '',
        checkerOperator: '',
        timeStart: '',
        timeFinished: '',
        remarks: ''
    });

    // Reset arrays
     // Keep the shape intact
    concentrationData.value = Array.from({ length: 7 }, () => Array(7).fill(null));
    coatingsTable.value = Array.from({ length: 35 }, (_, i) => ({
        no: i + 1,
        coating: null
    }));
    additionalSlurry.value = [
        { module: "M-01", new: null, homo: null, time: null, liters: null },
        { module: "M-02", new: null, homo: null, time: null, liters: null },
        { module: "M-03", new: null, homo: null, time: null, liters: null },
        { module: "M-04", new: null, homo: null, time: null, liters: null },
        { module: "M-05", new: null, homo: null, time: null, liters: null },
        { module: "M-06", new: null, homo: null, time: null, liters: null },
    ];

    coatingLotNo.value = null;

    toast.success("All fields cleared.");
};

const resetCoatingTableOnly = () => {
    coatingsInput.value = '';
    coatingsTable.value = Array.from({ length: 35 }, (_, i) => ({
        no: i + 1,
        coating: null
    }));
}

const finalizeCoatingSummary = async () => {
    if (
        !coatingInfo.coatingDate ||
        !coatingInfo.coatingMachineNo ||
        !coatingInfo.slurryLotNo ||
        !coatingInfo.minTbContent ||
        !coatingInfo.sampleQuantity ||
        !coatingInfo.totalMagnetWeight ||
        !coatingInfo.loaderOperator ||
        !coatingInfo.unloaderOperator ||
        !coatingInfo.checkerOperator ||
        !coatingInfo.timeStart ||
        !coatingInfo.timeFinished ||
        !coatingInfo.remarks ||
        !coatingLotNo.value
    ) {
        toast.error("Please fill in all required Coating Info fields.");
        return;
    }

     // Reset previous empty context
    emptyDataContexts.value = [];

    // Check concentrationData
    if (isArrayCompletelyEmpty(concentrationData.value.flat().map(v => ({ value: v })), ['value'])) {
        emptyDataContexts.value.push({
            type: 'concentration',
            label: 'Concentration Data'
        });
    }

    // Check additionalSlurry
    if (isArrayCompletelyEmpty(additionalSlurry.value, ['new','homo','time','liters'])) {
        emptyDataContexts.value.push({
            type: 'additionalSlurry',
            label: 'Additional Slurry Data'
        });
    }

    const coatingAmountData = coatingsTable.value.map(row => ({ no: row.no, coating: row.coating }));
    if (isArrayCompletelyEmpty(coatingAmountData, ['coating'])) {
        emptyDataContexts.value.push({
            type: 'coatingsTable',
            label: 'Coating Amount Data'
        });
    }

    // If any are empty, show modal
    if (emptyDataContexts.value.length > 0) {
        showEmptyDataWarning.value = true;
        return;
    }

    // Proceed normally
    await coatingLotValidation();

};

const proceedFinalizeEvenEmpty = async () => {
    showModalEmptyConcentrationWarning.value = false;
    showEmptyDataWarning.value = false;
    await coatingLotValidation();
};

const isArrayCompletelyEmpty = (arr, keys = []) => {
  return !arr.some(item => {
    if (keys.length) {
      // check only specified keys
      return keys.some(k => item[k] !== null && item[k] !== '' && !isNaN(item[k]));
    }
    // check all values
    return Object.values(item).some(v => v !== null && v !== '' && !isNaN(v));
  });
};


const coatingLotValidation = async() => {
    try{
        if (!coatingLotNo.value) {
            toast.error('Lot No. is required.');
            return false;
        }

        const response = await axios.post('/api/initial-coating/check-duplicate', {
            lot_no: coatingLotNo.value,
            model_name: coatingModel.value
        });
        //console.log("Success");
        if(response.data.duplicate_pair) {
            showModalCoatingDuplicatePrevent.value = true;
            return false;
        }else if (response.data.duplicate_lot) {
            showModalCoatingDuplicateWarning.value = true;
            //console.log("ShowModalDuplicate Warning: ", showModalDuplicateWarning.value);
        }else{
            showCoatingPreview.value = true;
        }
    }catch(error){
        console.error('Error validating Lot No:', error);
        toast.error('Failed to validate Lot No.');
        return false;
    }
}

const proceedDuplicateCoating = () => {
    showModalCoatingDuplicateWarning.value = false;
    showCoatingPreview.value = true;
}

const saveCoatingSummaryToDatabase = async() => {

    // Flatten coatingsTable
    const coatingAmountData = coatingsTable.value.map(row => ({
        no: row.no,
        coating: row.coating
    }));

    // Flatten concentrationData
    const concentrationAmountData = ranges.map((range, i) => ({
        range,
        modules: modules.map((module, j) => ({
            module,
            value: concentrationData.value[i][j]
        }))
    }));

    // Flatten additionalSlurry
    const additionalSlurryAmountData = additionalSlurry.value.map(item => ({
        module: item.module,
        new: item.new,
        homo: item.homo,
        time: item.time,
        liters: item.liters
    }));

    // Final payload
    const coatingDataPayload = {
        model_name: coatingModel.value,
        lot_no: coatingLotNo.value,
        coating_date: coatingInfo.coatingDate,
        machine_no: coatingInfo.coatingMachineNo,
        slurry_lot_no: coatingInfo.slurryLotNo,
        sample_qty: coatingInfo.sampleQuantity.toString(),
        min_tb_content: coatingInfo.minTbContent,
        total_magnet_weight: coatingInfo.totalMagnetWeight.toString(),
        loader_operator: coatingInfo.loaderOperator,
        unloader_operator: coatingInfo.unloaderOperator,
        checker_operator: coatingInfo.checkerOperator,
        time_start: coatingInfo.timeStart,
        time_finished: coatingInfo.timeFinished,
        average: coatingAverage.value,
        maximum: coatingMaximum.value,
        minimum: coatingMinimum.value,
        remarks: coatingInfo.remarks,
        coating_data: {
            "Coating Amount Data": coatingAmountData,
            "Concentration Data": concentrationAmountData,
            "Additional Slurry Data": additionalSlurryAmountData,
            "Lot no": coatingLotNo.value
        }
    };

    console.log('Coating Data Payload: ', coatingDataPayload);

    try{
        const response = await axios.post('/api/initial-coating', coatingDataPayload);
        console.log('Saved Successfully: ', response.data);
        toast.success('Saved Successfully');
        await userManageLogging('created Coating Data for Lot No: '+ coatingLotNo.value +' Model: ' + coatingModel.value + ' successfully.');
    }catch(error){
        toast.error('Failed to save to database. ',error);
    }finally{
        resetCoatingSummaryData();
        showCoatingPreview.value = false;
        await getControlSheetData();
        await getCoatingSummary();
        await getFilmPastingSummary();
    }
}


//Film Pasting Section ---- Film Pasting Section ---- Film Pasting Section ---- Film Pasting Section ---- Film Pasting Section ---- Film Pasting Section ----

const showModalFilmPastingPreview = ref(false);
const showModalFilmPasteDuplicatePrevent = ref(false);
const showModalFilmPasteDuplicateWarning = ref(false);

const filmPastingInfo = reactive({
    selected_model: '',
    lot_no: '',
    film_pasting_date: '',
    machine_no: '',
    total_magnet_weight: 0,
    loader_operator: '',
    unloader_operator: '',
    checker_operator: '',
    film_coating_amount: '',
    time_start: '',
    time_finished: '',
    remarks: '',
    film_type: '',
    film_class: '',
    h_line_parameters: {
        sprayer_water_amount: 0,
        film_paste_lot_no_1: 0,
        film_paste_lot_no_2: 0,
        film_machine_humidity: 0,
        film_machine_temperature: 0,
        dryer_temp_setting: ''
    },
    t_line_parameters: {
        sprayer_water_amount: 0,
        film_paste_lot_no_1: 0,
        film_paste_lot_no_2: 0,
        film_machine_humidity: 0,
        film_machine_temperature: 0,
        dryer_temp_setting: ''
    },
    is_setter_sand: false,
});

const itemsList = [
    'Film Machine Humidity (%)',
    'Film Machine Temperature (°C)',
    'Sprayer Water Amount',
    'Dryer Temp Setting (°C)',
    'Water Level (mm)',
    'Time Check',
    'Check By'
];

// Helper function to create a block
const createBlock = (id) => ({
    id,
    values: itemsList.map(item => {
        if (item === 'Water Level (mm)') {
            return { item, value: null }; // single input for water level
        } else if (item === 'Dryer Temp Setting (°C)') {
            return { item, hLine: 120, tLine: 120 }; // default 120
        } else {
            return { item, hLine: null, tLine: null }; // separate inputs for H-LINE & T-LINE
        }
    })
});

// Reactive array of H-LINE / T-LINE blocks
const hLineTLineBlocks = ref([createBlock(1)]);

// Function to add a new block (up to max 20)
const addBlock = () => {
    if (hLineTLineBlocks.value.length >= 20) return;
    const newBlockId = hLineTLineBlocks.value.length + 1;
    hLineTLineBlocks.value.push(createBlock(newBlockId));
};

// Function to remove a block by id
const removeBlock = (id) => {
    if (hLineTLineBlocks.value.length <= 1) return; // always keep at least one block
    hLineTLineBlocks.value = hLineTLineBlocks.value.filter(block => block.id !== id);
};

// --------------------------
// Computed averages for each parameter
// --------------------------
const hLineAverages = computed(() => {
    const sums = {
        'Film Machine Humidity (%)': 0,
        'Film Machine Temperature (°C)': 0,
        'Sprayer Water Amount': 0,
        'Dryer Temp Setting (°C)': 0,
        'Water Level (mm)': 0
    };
    const counts = { ...sums };

    hLineTLineBlocks.value.forEach(block => {
        block.values.forEach(v => {
        if (v.item === 'Water Level (mm)') {
            if (v.value != null && !isNaN(v.value) && Number(v.value) !== 0) {
            sums[v.item] += Number(v.value);
            counts[v.item]++;
            }
        } else if (v.hLine != null && !isNaN(v.hLine) && Number(v.hLine) !== 0) {
            sums[v.item] += Number(v.hLine);
            counts[v.item]++;
        }
        });
    });

    // Return average only based on non-zero counts
    const avg = {};
    Object.keys(sums).forEach(key => {
        avg[key] = counts[key] > 0 ? sums[key] / counts[key] : 0;
    });
    return avg;
});

const tLineAverages = computed(() => {
    const sums = {
        'Film Machine Humidity (%)': 0,
        'Film Machine Temperature (°C)': 0,
        'Sprayer Water Amount': 0,
        'Dryer Temp Setting (°C)': 0,
        'Water Level (mm)': 0
    };
    const counts = { ...sums };

    hLineTLineBlocks.value.forEach(block => {
        block.values.forEach(v => {
        if (v.item === 'Water Level (mm)') {
            if (v.value != null && !isNaN(v.value) && Number(v.value) !== 0) {
            sums[v.item] += Number(v.value);
            counts[v.item]++;
            }
        } else if (v.tLine != null && !isNaN(v.tLine) && Number(v.tLine) !== 0) {
            sums[v.item] += Number(v.tLine);
            counts[v.item]++;
        }
        });
    });

    const avg = {};
    Object.keys(sums).forEach(key => {
        avg[key] = counts[key] > 0 ? sums[key] / counts[key] : 0;
    });
    return avg;
});


// --------------------------
// Watch computed averages and update filmPastingInfo
// --------------------------
watch([hLineAverages, tLineAverages], ([newH, newT]) => {
    // H-LINE
    filmPastingInfo.h_line_parameters.film_machine_humidity = Math.round(newH['Film Machine Humidity (%)'] * 10) / 10;
    filmPastingInfo.h_line_parameters.film_machine_temperature = Math.round(newH['Film Machine Temperature (°C)'] * 10) / 10;
    filmPastingInfo.h_line_parameters.sprayer_water_amount = Math.round(newH['Sprayer Water Amount'] * 100) / 100;
    filmPastingInfo.h_line_parameters.dryer_temp_setting = newH['Dryer Temp Setting (°C)'];
    filmPastingInfo.h_line_parameters.water_level = newH['Water Level (mm)'];

    // T-LINE
    filmPastingInfo.t_line_parameters.film_machine_humidity = Math.round(newT['Film Machine Humidity (%)'] * 10) / 10;
    filmPastingInfo.t_line_parameters.film_machine_temperature = Math.round(newT['Film Machine Temperature (°C)'] * 10) / 10;
    filmPastingInfo.t_line_parameters.sprayer_water_amount = Math.round(newT['Sprayer Water Amount'] * 100) / 100;
    filmPastingInfo.t_line_parameters.dryer_temp_setting = newT['Dryer Temp Setting (°C)'];
    filmPastingInfo.t_line_parameters.water_level = newT['Water Level (mm)'];
}, { immediate: true });

const resetFilmPastingFields = () => {
    Object.assign(filmPastingInfo,{
        selectedFurnace: '',
        selected_mass_prod: '',
        selected_layer: null,
        film_pasting_date: '',
        machine_no: '',
        total_magnet_weight: 0,
        loader_operator: '',
        unloader_operator: '',
        checker_operator: '',
        film_coating_amount: '',
        time_start: '',
        time_finished: '',
        remarks: '',
        film_type: '',
        film_class: '',
        h_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        t_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        is_setter_sand: false,
    });

    // Reset the blocks — one fresh block, id=1
    hLineTLineBlocks.value = [createBlock(1)];
}

const finalizeFilmPasting = async() => {
    if (
        !filmPastingInfo.lot_no ||
        !filmPastingInfo.selected_model ||
        !filmPastingInfo.film_pasting_date ||
        !filmPastingInfo.machine_no ||
        !filmPastingInfo.total_magnet_weight ||
        !filmPastingInfo.loader_operator ||
        !filmPastingInfo.unloader_operator ||
        !filmPastingInfo.checker_operator ||
        !filmPastingInfo.film_coating_amount ||
        !filmPastingInfo.time_start ||
        !filmPastingInfo.time_finished ||
        !filmPastingInfo.remarks ||
        !filmPastingInfo.film_type ||
        !filmPastingInfo.film_class
    ) {
        //console.log('Coating Table: ', coatingValues.value);
        toast.error("Please fill in all required Film Pasting Info fields.");
        return;
    }else if(
        !filmPastingInfo.is_setter_sand
    ){
        toast.error("Please choose Setter sand application per layer.");
        return;
    }

    await filmPasteLotValidation();
}

const filmPasteLotValidation = async () => {
    try {
        console.log('[VALIDATION] Start');

        if (!filmPastingInfo.selected_model) {
            console.warn('[VALIDATION] Missing lot number.');
            toast.error('Lot No. is required.');
            return false;
        }

        console.log('[REQUEST] Checking duplicate for:', filmPastingInfo.selected_model);

        const response = await axios.post('/api/initial-film-pasting/check-duplicate', {
            lot_no: filmPastingInfo.lot_no,
            model_name: filmPastingInfo.selected_model
        });

        console.log('[RESPONSE]', response.data);

        if (response.data.duplicate_pair) {
            console.warn('[DUPLICATE] Pair duplicate detected.');
            showModalFilmPasteDuplicatePrevent.value = true;
            return false;
        }

        if (response.data.duplicate_lot) {
            console.warn('[WARNING] Lot duplicate detected.');
            showModalFilmPasteDuplicateWarning.value = true;
            return;
        }

        console.log('[OK] No duplicates. Showing preview.');
        showModalFilmPastingPreview.value = true;

    } catch (error) {
        console.error('[ERROR] Lot No validation failed:', error);
        toast.error('Failed to validate Lot No.');
        return false;
    }
};


const proceedDuplicateFilmPaste = () => {
    showModalFilmPasteDuplicateWarning.value = false;
    showModalFilmPastingPreview.value = true;
}

const saveToDataBaseFilmPasting = async() => {
    try {
        const payload = {
            model_name: filmPastingInfo.selected_model,
            lot_no: filmPastingInfo.lot_no,
            film_pasting_date: filmPastingInfo.film_pasting_date,
            machine_no: filmPastingInfo.machine_no,
            total_magnet_weight: filmPastingInfo.total_magnet_weight,
            loader_operator: filmPastingInfo.loader_operator,
            unloader_operator: filmPastingInfo.unloader_operator,
            checker_operator: filmPastingInfo.checker_operator,
            film_coating_amount: filmPastingInfo.film_coating_amount,
            time_start: filmPastingInfo.time_start,
            time_finished: filmPastingInfo.time_finished,
            remarks: filmPastingInfo.remarks,
            film_type: filmPastingInfo.film_type,
            film_class: filmPastingInfo.film_class,
            h_line_parameters: filmPastingInfo.h_line_parameters,
            t_line_parameters: filmPastingInfo.t_line_parameters,
            setter_sand: filmPastingInfo.is_setter_sand,
            hourly_checking: buildHourlyCheckingPayload()
        };

        const response = await axios.post(`/api/initial-film-pasting`, payload);
        console.log('Initial Film Pasting saved successfully: ', response.data);
        toast.success('Saved Successfully!');
    } catch (error) {
        console.error('Failed to save initial film pasting data', error);
    } finally {
        resetFilmPastingFields();
        showModalFilmPastingPreview.value = false;
        await getControlSheetData();
        await getCoatingSummary();
        await getFilmPastingSummary();
    }
};

const buildHourlyCheckingPayload = () => {
    const hLine = [];
    const tLine = [];

    hLineTLineBlocks.value.forEach(block => {
        const hEntry = {};
        const tEntry = {};

        block.values.forEach(row => {
            switch (row.item) {
                case 'Time Check':
                    hEntry.time_check = row.hLine ?? null;
                    tEntry.time_check = row.hLine ?? null;
                    break;

                case 'Check By':
                    hEntry.check_by = row.hLine ?? null;
                    tEntry.check_by = row.hLine ?? null;
                    break;

                case 'Water Level (mm)':
                    hEntry.water_level = row.value != null ? Number(row.value.toFixed(2)) : null;
                    tEntry.water_level = row.value != null ? Number(row.value.toFixed(2)) : null;
                    break;

                case 'Film Machine Humidity (%)':
                    hEntry.film_machine_humidity = row.hLine != null ? Number(row.hLine) : null;
                    tEntry.film_machine_humidity = row.tLine != null ? Number(row.tLine) : null;
                    break;

                case 'Film Machine Temperature (°C)':
                    hEntry.film_machine_temperature = row.hLine != null ? Number(row.hLine) : null;
                    tEntry.film_machine_temperature = row.tLine != null ? Number(row.tLine) : null;
                    break;

                case 'Sprayer Water Amount':
                    hEntry.sprayer_water_amount = row.hLine != null ? Number(row.hLine) : null;
                    tEntry.sprayer_water_amount = row.tLine != null ? Number(row.tLine) : null;
                    break;

                case 'Dryer Temp Setting (°C)':
                    hEntry.dryer_temp_setting = row.hLine != null ? parseInt(row.hLine) : null;
                    tEntry.dryer_temp_setting = row.tLine != null ? parseInt(row.tLine) : null;
                    break;
            }
        });

        hLine.push(hEntry);
        tLine.push(tEntry);
    });

    return {
        h_line: hLine,
        t_line: tLine
    };
};


// Fetch on mount
onMounted( async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    await getModelLists();
    await getControlSheetData();
    await getCoatingSummary();
    await getFilmPastingSummary();
});

// APPLYING Browser Session ----------------- APPLYING Browser Session

useSessionStorage('initial_mpcs',initial_mpcs);
useSessionStorage('weightValues',weightValues);
useSessionStorage('boxNoValues',boxNoValues);
useSessionStorage('qtyValues',qtyValues);
useSessionStorage('boxNoValuesExcess',boxNoValuesExcess);
useSessionStorage('weightValuesExcess',weightValuesExcess);
useSessionStorage('qtyValuesExcess',qtyValuesExcess);
useSessionStorage('boxWeight', boxWeight);
useSessionStorage('boxWithWeight', boxWithMagnetWeight);
useSessionStorage('boxWeightExcess', boxWeightExcess);
useSessionStorage('boxWithMagnetWeightExcess', boxWithMagnetWeightExcess);

useSessionStorage("coatingInfo", coatingInfo);
useSessionStorage("concentrationData", concentrationData);
useSessionStorage("coatingsTable", coatingsTable);
useSessionStorage("additionalSlurry", additionalSlurry);
useSessionStorage("coatingLotNo", coatingLotNo);
useSessionStorage("coatingModel", coatingModel);

useSessionStorage("filmPastingInfo", filmPastingInfo);
useSessionStorage("hLineTLineBlocks", hLineTLineBlocks);


// APPLYING Browser Session ----------------- APPLYING Browser Session

</script>

<style scoped>

    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
