<template>
    <Frontend>
        <div class="flex flex-row items-start gap-10 p-5 m-0 text-green-600 bg-black">
            <div v-if="state.user && state.user.access_type == 'Automation'" class="space-x-2">
                <p>Dev Controls:</p>
                <button @click="bypassValidation = true" class="p-1 bg-gray-200 rounded-lg" :class="[bypassValidation ? 'bg-yellow-400' : '']">ByPass Validation</button>
                <button @click="bypassValidation = false" class="p-1 bg-gray-300 rounded-lg"> x </button>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center min-h-screen px-4 py-12 bg-gray-100">
            <div class="flex flex-row gap-10 items">
                <div class="px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl max-w-8xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Data (Unit: µ/mm²)</h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">
                        <div class="flex flex-row gap-4">
                            <div v-for="(slice, colIndex) in Math.ceil(coatingsTable.length / 10)" :key="colIndex" class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 text-left border-r border-gray-300">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr
                                            v-for="(item) in coatingsTable.slice(colIndex * 10, (colIndex + 1) * 10)"
                                            :key="item.no"
                                            class="hover:bg-gray-50"
                                        >
                                        <td class="px-3 py-1 border-b border-r border-gray-200">{{ item.no }}</td>
                                        <td class="px-3 py-1 border-b border-gray-200">
                                            <input
                                                type="number"
                                                v-model="item.coating"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                                <tr v-for="(range, rowIndex) in ranges" :key="range" class="hover:bg-gray-50">
                                <td class="px-2 py-1 border-b border-r border-gray-200">{{ range }}</td>
                                <td
                                    v-for="(module, colIndex) in modules"
                                    :key="colIndex"
                                    class="px-2 py-1 border-b border-r border-gray-200"
                                >
                                    <input
                                    type="number"
                                    v-model="concentrationData[rowIndex][colIndex]"
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
            <!-- DIV BELOW (BOTTOM PART) -->
            <div class="flex flex-row gap-20 mt-10">
                <div class="px-4 py-4 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl md:px-8">
                    <!-- Stats: Average / Max / Min -->
                    <div class="flex flex-row gap-20">
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Average</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingAverage !== null ? coatingAverage.toFixed(2) : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Maximum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMaximum !== null ? coatingMaximum : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Minimum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMinimum !== null ? coatingMinimum : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DIV BELOW (BOTTOM PART) -->
            <div class="flex flex-row gap-10 mt-10">
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Information</h2>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="relative">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">Mass Prod. Name <span class="text-red-500">*</span></label>
                            <select
                                v-model="coatingInfo.selectedMassProd"
                                class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50"
                            >
                                <option v-for="items in massProd_names" :key="items" :value="items">
                                    {{ items }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Layer<span class="text-red-500"> *</span></label>
                            <select v-model="coatingInfo.selectedLayer" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option v-for="items in layers" :key="items" :value="items">
                                    {{ items }}
                                </option>
                            </select>
                        </div>
                    </div>
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
                            <input v-model="coatingInfo.sampleQuantity" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
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
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="lotNo" @input="lotNo = lotNo.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
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
                            class="w-[36rem] text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                            placeholder="Enter remarks here..."
                            />
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl md:px-8">
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
            <!-- LAST BOTTOM DIV -->
            <div class="flex flex-row gap-20 mt-10">
                <div class="bg-gradient-to-br from-black via-gray-900 to-gray-800 border border-blue-600 shadow-[0_10px_25px_rgba(99,102,241,0.4),0_4px_6px_rgba(0,0,0,0.4)] rounded-3xl px-6 py-6 flex items-center gap-16 ring-1 ring-indigo-400 backdrop-blur-md whitespace-nowrap">
                    <!-- Glowing Spinning Cogwheel -->
                    <div class="relative flex-shrink-0 w-12 h-12">
                        <div class="absolute inset-0 bg-blue-400 rounded-full opacity-100 blur-xl animate-pulse"></div>
                        <img
                            src="photo/cogwheel.png"
                            alt="Settings"
                            class="relative z-10 object-contain w-full h-full animate-spin"
                            style="animation-duration: 3s;"
                        />
                    </div>

                    <!-- Buttons Row -->
                    <div class="flex w-full gap-10">
                        <!-- Finalize -->
                        <button
                            @click="finalize"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-50"
                        >
                            FINALIZE
                        </button>

                        <!-- Cancel -->
                        <button
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50"
                        >
                            CANCEL
                        </button>

                        <!-- Clear All -->
                        <button
                            @click="clearAll()"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
                        >
                            CLEAR ALL
                        </button>
                    </div>
                </div>
            </div>
            <Modal :show="showModalFinalize" @close="showModalFinalize = false">
                <div
                    class="relative flex flex-col items-start bg-white p-6 rounded-xl shadow-2xl max-w-[95vw] max-h-[90vh] overflow-auto pr-12"
                >
                    <!-- Exit Button -->
                    <button
                    @click="showModalFinalize = false"
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
                                        <td class="px-1 border border-gray-300">{{ lotNo }}</td>
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
                                        <td class="px-1 border border-gray-300">{{ lotNo }}</td>
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
                                        <td class="px-1 border border-gray-300">{{ lotNo }}</td>
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
                                        <td class="px-1 border border-gray-300">{{ lotNo }}</td>
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
                                <label class="font-medium text-gray-600">Average:</label>
                                <span class="text-gray-800">
                                    {{ coatingAverage != null ? coatingAverage.toFixed(2) : '-' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <label class="font-medium text-gray-600">Maximum:</label>
                                <span class="text-gray-800">{{ coatingMaximum ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <label class="font-medium text-gray-600">Minimum:</label>
                                <span class="text-gray-800">{{ coatingMinimum ?? '-' }}</span>
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
                            @click="showModalFinalize = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 rounded-lg shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1"
                        >
                            Cancel
                        </button>
                        <button
                            @click="saveToDatabase"
                            class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        >
                            Proceed
                            <img src="/photo/arrow_proceed.png" alt="Proceed" class="w-4 h-4 ml-2">
                        </button>
                    </div>

                </div>
            </Modal>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
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
const bypassValidation = ref(false);
//Dev Controls ----------------- Allow Commands

// General Variables --------- General Variables

//Toggle Control
const showModalFinalize = ref(false);
//Toggle Control

const massProd_names = ref(['523RD','135TH']);
const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);
const lotNo = ref();

// General Variables --------- General Variables

// Coating Information Variables --------- Coating Information Variables

const coatingInfo = reactive({
    selectedMassProd: '',
    selectedLayer: '1',
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

//additionalSlurry.value[0].new = 10;  // Example M-01 New

const coatingsTable = ref(
    Array.from({ length: 35 }, (_, i) => ({
        no: i + 1,
        coating: null
    }))
);

//coatingsTable[4].coating = 'Red'; // sets No. 5

// For calculations (clean numbers only)
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

// For display (keeps index alignment with your inputs)
const displayCoatings = computed(() =>
  coatingsTable.value.map(row => row.coating ?? '')
);


const hasRows31to35 = computed(() => {
    return displayCoatings.value.slice(30, 35).some(
        val => val != null && val !== '' && val !== '-'
    )
})



// Modules
const modules = ["M-01", "M-02", "M-03", "M-04", "M-05", "M-06", "M-06"];
// Ranges
const ranges = ["1-5", "6-10", "11-15", "16-20", "21-25", "26-30", "31-35"];
// Reactive table data
const concentrationData = ref(
  ranges.map(() => modules.map(() => null)) // 7x8 grid of nulls
);

// Example: set some values
//concentrationAmount.value[0]['M-01'] = 10;   // first row, M-01 column
//concentrationAmount.value[3]['M-04'] = 7;    // fourth row, M-04 column

// Coating Information Variables --------- Coating Information Variables

const finalize = async () => {
    if(!bypassValidation.value){
        if (
            !coatingInfo.selectedMassProd ||
            !coatingInfo.selectedLayer ||
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
            !coatingInfo.remarks
        ) {
            //console.log('Coating Table: ', coatingValues.value);
            toast.error("Please fill in all required Coating Info fields.");
            return;
        }
    }

    showModalFinalize.value = true;

    //console.log("Concentration Data: ", concentrationData.value);
    //console.log("Coatings Data: ", coatingsTable.value);
    //console.log("Additional Slurry Data: ", additionalSlurry.value);
    //console.log("Coating Info: ", coatingInfo);
};

const clearAll = () => {
    // Reset coatingInfo
    Object.assign(coatingInfo, {
        selectedMassProd: '',
        selectedLayer: '1',
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

    toast.success("All fields cleared.");
};

const saveToDatabase = async() => {

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
        mass_prod: coatingInfo.selectedMassProd,
        layer: coatingInfo.selectedLayer,
        date: coatingInfo.coatingDate,
        machine_no: coatingInfo.coatingMachineNo,
        slurry_lot_no: coatingInfo.slurryLotNo,
        sample_qty: coatingInfo.sampleQuantity.toString(),
        min_tb_content: coatingInfo.minTbContent,
        total_magnet_weight: coatingInfo.totalMagnetWeight.toString(),
        loader_operator: coatingInfo.loaderOperator,
        unloader_operator: coatingInfo.unloaderOperator,
        checker_operator: coatingInfo.checkerOperator,
        time_start: coatingInfo.timeStart,
        time_finish: coatingInfo.timeFinished,
        average: coatingAverage.value,
        maximum: coatingMaximum.value,
        minimum: coatingMinimum.value,
        remarks: coatingInfo.remarks,
        coating_data: {
            "Coating Amount Data": coatingAmountData,
            "Concentration Data": concentrationAmountData,
            "Additional Slurry Data": additionalSlurryAmountData,
            "Lot no": lotNo.value
        }
    };

    console.log('Coating Data Payload: ', coatingDataPayload);

    try{
        const response = await axios.post('/api/coating-data/', coatingDataPayload);
        console.log('Saved Successfully: ', response.data);
        toast.success('Saved Successfully');
    }catch(error){
        toast.error('Failed to save to database. ',error);
    }
}

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return;
    }
});

</script>

<style scoped>

    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
