<template>
    <Frontend>
        <div class="flex flex-col justify-start min-h-screen px-4 py-12 bg-gray-100">
            <div class="flex flex-row items-start gap-10 mb-5">
                <p class="text-gray-400">Fill all the required fields (<span class="text-red-500"> * </span>)</p>
                <div v-if="state.user && state.user.access_type == 'Automation'" class="space-x-2">
                    <p>Dev Controls:</p>
                    <button @click="bypassValidation = true" class="p-1 bg-gray-200 rounded-lg" :class="[bypassValidation ? 'bg-yellow-400' : '']">ByPass Validation</button>
                    <button @click="bypassValidation = false" class="p-1 bg-gray-300 rounded-lg"> x </button>
                    <button @click="overwriteHeatTreatment = true" class="p-1 bg-gray-200 rounded-lg" :class="[overwriteHeatTreatment ? 'bg-yellow-400' : '']">Allow Overwrite</button>
                    <button @click="overwriteHeatTreatment = false" class="p-1 bg-gray-300 rounded-lg"> x </button>
                </div>
            </div>
            <div class="flex flex-row justify-center gap-0">
                <div class="max-w-4xl px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Mass Production Control Sheet</h2>

                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="relative">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">Mass Prod. Name <span class="text-red-500">*</span></label>
                            <select
                                v-model="mpcs.selectedMassProd"
                                class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50"
                            >
                                <option v-for="item in massProd_names" :key="item" :value="item">
                                {{ item }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Layer<span class="text-red-500"> *</span></label>
                            <select v-model="mpcs.selectedLayer" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option v-for="item in layers" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Boxes: A to<span class="text-red-500"> *</span></label>
                            <select v-model="mpcs.selectedBoxEndList" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option v-for="item in boxesEndList" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Group: Basic Info -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Model<span class="text-red-500"> *</span></label>
                            <select v-model="mpcs.selectedModel" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option v-for="item in model_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating M/C No.<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.coatingMCNo" type="text" @input="mpcs.coatingMCNo = mpcs.coatingMCNo.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>

                    <!-- Group: Lot + Qty -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot No.<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.lotNo" type="text" @input="mpcs.lotNo = mpcs.lotNo.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Qty (PCS)<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.qty" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700 whitespace-nowrap">Qty (PCS) - Last Box<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.qty_lastBox" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.coating" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>

                    <!-- Group: Prepared By -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">HT (PCS)</label>
                            <input v-model="mpcs.ht" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">LT (PCS)</label>
                            <input v-model="mpcs.lt" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Magnet Prepared By<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.magnetPreparedBy" type="text" @input="mpcs.magnetPreparedBy = mpcs.magnetPreparedBy.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Box Prepared By</label>
                            <input v-model="mpcs.boxPreparedBy" type="text" @input="mpcs.boxPreparedBy = mpcs.boxPreparedBy.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>

                <div class="max-w-4xl px-8 py-8 mx-auto space-y-6 bg-white border border-gray-200 shadow-xl rounded-2xl md:px-12">
                    <p class="pb-2 text-sm font-semibold text-gray-800 border-b">BOX No. <span class="text-gray-300">(example: UBP85172)</span><span class="text-red-500"> *</span></p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-collapse border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                v-for="box in visibleBoxes"
                                :key="box"
                                class="px-4 py-2 text-xs border border-gray-300"
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
                                class="px-2 py-1 border border-gray-300"
                                >
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
                    <p class="pb-2 text-sm font-semibold text-gray-800 border-b">Weight <span class="text-gray-300">(KG)</span><span class="text-red-500"> *</span></p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-collapse border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                v-for="box in visibleBoxes"
                                :key="box"
                                class="px-4 py-2 text-xs border border-gray-300"
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
                                class="px-2 py-1 border border-gray-300"
                                >
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
                </div>
            </div>
            <div class="flex flex-row mt-12">
                <div v-if="!heatTreatmentInformationDetected" class="max-w-5xl px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-8">
                    <div>
                        <h2 class="pb-1 mb-4 font-bold text-gray-800 border-b text-md">Heat Treatment Information</h2>
                        <div class="flex flex-row space-x-3">
                            <div class="flex flex-col">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Batch Cycle No</label>
                                    <input v-model="mpcs.selectedMassProd" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Machine No</label>
                                    <input v-model="initialFurnaceData" type="text" disabled class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Cycle No<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.cycleNo" type="text" @input="hti.cycleNo = hti.cycleNo.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Pattern No<span class="text-red-500"> *</span></label>
                                    <select v-model="hti.patternNo" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option v-for="item in graph_patterns" :key="item" :value="item">
                                            {{ item }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Cycle Pattern<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.cyclePattern" type="text" @input="hti.cyclePattern = hti.cyclePattern.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Current Pattern<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.currentPattern" type="text" @input="hti.currentPattern = hti.currentPattern.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Date Start<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.dateStart" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Time Start<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.timeStart" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Loader<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.loader" type="text" @input="hti.loader = hti.loader.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Date Finish<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.dateFinish" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Time Finish<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.timeFinish" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Unloader<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.unloader" type="text" @input="hti.unloader = hti.unloader.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Box Condition<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.boxCondition" type="text" @input="hti.boxCondition = hti.boxCondition.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Box Cover<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.boxCover" type="text" @input="hti.boxCover = hti.boxCover.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Box Arrangement<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.boxArrangement" type="text" @input="hti.boxArrangement = hti.boxArrangement.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Encoded By<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.encodedBy" type="text" @input="hti.encodedBy = hti.encodedBy.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <div class="flex flex-col w-[40rem]">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Remarks1</label>
                                    <input v-model="hti.remarks1" type="text" @input="hti.remarks1 = hti.remarks1.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Remarks2</label>
                                    <input v-model="hti.remarks2" type="text" @input="hti.remarks2 = hti.remarks2.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Remarks3</label>
                                    <input v-model="hti.remarks3" type="text" @input="hti.remarks3 = hti.remarks3.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="px-2 mx-auto space-y-4 border border-yellow-400 shadow-xl max-w-8xl bg-gray-50 rounded-2xl py-7 md:px-8">
                    <div class="flex flex-col items-center space-y-3 text-center px-96">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 border border-yellow-300 rounded-full">
                            ⚠️
                        </div>

                        <!-- Title -->
                        <h2 class="text-lg font-semibold text-gray-800">
                            Heat Treatment Information
                        </h2>

                        <!-- Message -->
                        <p class="text-sm text-gray-600 whitespace-nowrap">
                            Heat Treatment Information data and graph already exists for this Mass Production.
                        </p>

                        <button
                            v-if="overwriteHeatTreatment"
                            @click="heatTreatmentInformationDetected = false"
                            class="flex items-center justify-center px-6 py-2 space-x-2 font-bold text-white transition-all duration-200 transform bg-red-600 border-2 border-red-900 rounded-lg shadow-lg hover:scale-110 hover:shadow-2xl hover:bg-red-800 active:scale-95 active:bg-red-900"
                        >
                            <span>OVERWRITE</span>
                        </button>
                    </div>
                </div>
                <div v-if="!heatTreatmentInformationDetected" class="max-w-2xl px-2 py-8 mx-auto space-y-8 bg-white border border-gray-300 shadow-xl rounded-2xl md:px-8">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Heat Treatment Graph Upload <span class="text-xs text-gray-300">(PNG, JPG and JPEG)</span></h2>
                    <div class="flex flex-col p-6 space-y-8 bg-white border border-gray-300 rounded-lg shadow-sm">
                    <div class="flex flex-col pb-4 space-y-2 border-b border-gray-200">
                        <label for="cycleGraph" class="text-sm font-semibold text-gray-800">Cycle Graph<span class="text-red-500"> *</span></label>
                        <input
                        id="cycleGraph"
                        @change="handleCycleGraphUpload"
                        accept=".png, .jpg, .jpeg"
                        type="file"
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        />
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="actualGraph" class="text-sm font-semibold text-gray-800">Actual Graph<span class="text-red-500"> *</span></label>
                        <input
                        id="actualGraph"
                        @change="handleActualGraphUpload"
                        accept=".png, .jpg, .jpeg"
                        type="file"
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        />
                    </div>

                </div>

                </div>
                <div
                    class="w-72 mx-auto bg-gradient-to-br from-black via-gray-900 to-gray-800 border border-blue-600 shadow-[0_10px_25px_rgba(99,102,241,0.4),0_4px_6px_rgba(0,0,0,0.4)] rounded-3xl px-6 py-10 space-y-10 flex flex-col items-center ring-1 ring-indigo-400 backdrop-blur-md"
                >
                <!-- Glowing Spinning Cogwheel -->
                <div class="relative w-20 h-20">
                    <div class="absolute inset-0 bg-blue-400 rounded-full opacity-100 blur-xl animate-pulse"></div>
                    <img
                    src="photo/cogwheel.png"
                    alt="Settings"
                    class="relative z-10 object-contain w-full h-full animate-spin"
                    style="animation-duration: 3s;"
                    />
                </div>

                <!-- Finalize Button -->
                <div class="w-full">
                    <button
                        @click="finalize"
                        class="w-full py-2 text-sm font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-50"
                    >
                        FINALIZE
                    </button>
                </div>

                <!-- Other Buttons -->
                <div class="w-full mt-4 space-y-4">
                    <!-- Cancel Button -->
                    <button
                        @click="Inertia.visit('ht_graph_patterns')"
                        class="w-full py-2 text-sm font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-green-400 focus:ring-opacity-50"
                    >
                        GRAPH PATTERNS
                    </button>

                    <!-- Cancel Button -->
                    <button
                        class="w-full py-2 text-sm font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50"
                    >
                        CANCEL
                    </button>

                    <!-- Clear All Button -->
                    <button
                        @click="clearAll()"
                        class="w-full py-2 text-sm font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
                    >
                        CLEAR ALL
                    </button>
                </div>

                </div>


            </div>
            <Modal :show="showModalCreate" @close="showModalCreate = false">
                <div
                    class="relative flex flex-col items-start bg-white p-6 rounded-xl shadow-2xl max-w-[95vw] max-h-[90vh] overflow-auto pr-12"
                >

                    <!-- Exit Button -->
                    <button
                    @click="showModalCreate = false"
                    class="text-gray-400 transition duration-150 hover:text-gray-600"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <p class="mb-6 text-lg font-bold text-gray-800">Please review your inputs <span class="text-red-700">carefully</span> before submitting.</p>

                    <div class="px-5 overflow-auto">
                        <table class="min-w-full text-xs border border-collapse border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-1 text-left border border-gray-300"></th>
                                    <th
                                        v-for="item in visibleBoxes"
                                        :key="item"
                                        class="px-2 py-1 font-semibold text-center border border-gray-300"
                                    >
                                        {{ item }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Model:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.model }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating M/C No:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.coatingMCNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Lot No:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.lotNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Qty (PCS):</td>
                                    <td
                                        v-for="n in visibleBoxes.length - 1"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.qty }}
                                    </td>
                                    <td class="px-2 py-1 text-center border border-gray-300">
                                        {{ mpcs.qty_lastBox }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS):</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.ht }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS):</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.lt }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">WT (KG):</td>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ weightValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box No.:</td>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ boxNoValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.coating }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Magnet Prepared By:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.magnetPreparedBy }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box Prepared By:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ mpcs.boxPreparedBy }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="!heatTreatmentInformationDetected" class="p-4 my-2 border border-gray-200">
                        <div class="flex flex-row text-xs whitespace-nowrap">
                            <div class="flex flex-col items-end gap-1 font-semibold">
                                <label>Batch Cycle No.: </label>
                                <label>Machine No.: </label>
                                <label>Cycle No.: </label>
                                <label>Pattern No.: </label>
                                <label>Cycle Pattern: </label>
                                <label>Current Pattern: </label>
                            </div>
                            <div class="flex flex-col gap-1 ml-5">
                                <span>{{ mpcs.selectedMassProd || 'NA' }}</span>
                                <span>{{ initialFurnaceData || 'NA' }}</span>
                                <span>{{ hti.cycleNo || 'NA' }}</span>
                                <span>{{ hti.patternNo || 'NA' }}</span>
                                <span>{{ hti.cyclePattern || 'NA' }}</span>
                                <span>{{ hti.currentPattern || 'NA' }}</span>
                            </div>
                            <div class="flex flex-col items-end gap-1 pl-2 ml-2 font-semibold border-l border-gray-200">
                                <label>Date Start: </label>
                                <label>Time Start: </label>
                                <label>Loader: </label>
                            </div>
                            <div class="flex flex-col gap-1 ml-5">
                                <span>{{ hti.dateStart || 'NA' }}</span>
                                <span>{{ hti.timeStart || 'NA' }}</span>
                                <span>{{ hti.loader || 'NA' }}</span>
                            </div>
                            <div class="flex flex-col items-end gap-1 pl-2 ml-2 font-semibold border-l border-gray-200">
                                <label>Date Finished: </label>
                                <label>Time Finished: </label>
                                <label>Unloader: </label>
                            </div>
                            <div class="flex flex-col gap-1 ml-5">
                                <span>{{ hti.dateFinish || 'NA' }}</span>
                                <span>{{ hti.timeFinish || 'NA' }}</span>
                                <span>{{ hti.unloader || 'NA' }}</span>
                            </div>
                            <div class="flex flex-col items-end gap-1 pl-2 ml-2 font-semibold border-l border-gray-200">
                                <label>Box Condition: </label>
                                <label>Box Cover: </label>
                                <label>Box Arrangement: </label>
                                <label>Encoded By: </label>
                            </div>
                            <div class="flex flex-col gap-1 ml-5">
                                <span>{{ hti.boxCondition || 'NA' }}</span>
                                <span>{{ hti.boxCover || 'NA' }}</span>
                                <span>{{ hti.boxArrangement || 'NA' }}</span>
                                <span>{{ hti.encodedBy || 'NA' }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="!heatTreatmentInformationDetected"
                        class="flex flex-row items-center justify-center gap-6 mb-2 whitespace-nowrap">

                    <div class="flex flex-col items-center text-xs text-center">
                        <p>Cycle Graph File selected:</p>
                        <span class="font-semibold">{{ cycleGraphFile && cycleGraphFile.name || 'NA' }}</span>
                        <img :src="cycleGraphPreview" alt="Cycle Graph Preview"
                            class="object-contain w-32 h-32 rounded-lg shadow" />
                    </div>

                    <div class="flex flex-col items-center text-xs text-center">
                        <p>Actual Graph File selected:</p>
                        <span class="font-semibold">{{ actualGraphFile && actualGraphFile.name || 'NA' }}</span>
                        <img :src="actualGraphPreview" alt="Actual Graph Preview"
                            class="object-contain w-32 h-32 rounded-lg shadow" />
                    </div>

                    </div>

                    <div class="flex mt-4 space-x-3">
                        <button
                            @click="cancelProceed"
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
const overwriteHeatTreatment = ref(false);
//Dev Controls ----------------- Allow Commands

const heatTreatmentInformationDetected = ref(false);

const cycleGraphFile = ref(null);
const cycleGraphPreview = ref(null);
const actualGraphFile = ref(null);
const actualGraphPreview = ref(null);
const showModalCreate = ref(false);
const initialFurnaceData = ref();
const massProd_names = ref([]);
const model_names = ref([]);
const graph_patterns = ref([]);
const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);
const allBoxes = ['A','B','C','D','E','F','G','H','J','K','L'];
const boxesEndList = ref(['B','C','D','E','F','G','H','J','K','L']);
const visibleBoxes = computed(() => {
  const endIndex = allBoxes.indexOf(mpcs.selectedBoxEndList); //Looks for the position of selected Letter in the array.
  return allBoxes.slice(0, endIndex + 1);
});
const boxNoValues = ref({});
allBoxes.forEach(box => {
    boxNoValues.value[box] = '';
});
const weightValues = ref({});
allBoxes.forEach(box => {
    weightValues.value[box] = '';
});

/* debugging weightValues
watch(
  weightValues,
  (newVal) => {
    console.log('weightValues changed:', newVal);
  },
  { deep: true }
);
*/

/* debugging boxNoValues
watch(
  boxNoValues,
  (newVal) => {
    console.log('boxNoValues changed:', newVal);
  },
  { deep: true }
);
*/

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getMassProdData = async () => { //Function for getting the current seleceted Massprod
    if (!mpcs.selectedMassProd){
        initialFurnaceData.value = null; // Reset if no mass production selected
        return; // skip if empty
    }
    try {
        const response = await axios.get(`/api/mass-production/by-mass-prod/${mpcs.selectedMassProd}`);
        //console.log('Mass Production Data:', response.data);
        const massProdData = response.data;
        initialFurnaceData.value = massProdData.furnace;
        //console.log('Initial Furnace Data:', initialFurnaceData.value);
        // detect existing heat treatment info
        const hasExisting = !!(massProdData.batch_cycle_no && massProdData.machine_no);

        heatTreatmentInformationDetected.value = hasExisting;
        if(heatTreatmentInformationDetected.value){
            toast.warning('Existing Data has been detected for Heat Treatment Information and Graph');
        }
        //console.log("Heat treatment info detected:", heatTreatmentInformationDetected.value);
    } catch (error) {
        initialFurnaceData.value = null; // Reset if no mass production selected
        console.error('Error fetching mass production data:', error);
        toast.error('Failed to load mass production data.');
    }
}

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

const getModelLists = async () => {
    try{
        const response = await axios.get('/api/inspectiondata/');
        const inspectionDataList = response.data.data;
        model_names.value = inspectionDataList.map(item => item.model);
        //console.log('Model lists: ',model_names.value);
    }catch(error){
        console.error('Error fetching model names', error);
        toast.error('Failed to get the model names.');
    }
}

const getGraphPatterns = async () => {
    try{
        const response = await axios.get('/api/ht-graph-patterns/');
        const patternsList = response.data;
        graph_patterns.value = patternsList.map(item => item.pattern_no);
        console.log('Graph Patterns: ',graph_patterns.value);
    }catch(error){
        console.error('Error fetching model names', error);
        toast.error('Failed to get graph patterns.');
    }
}

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

// MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!! // MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!!

const mpcs = reactive({
    selectedMassProd: '',
    selectedLayer: '1',
    selectedBoxEndList: 'K',
    selectedModel: '',
    coatingMCNo: '',
    lotNo: '',
    qty: 0,
    qty_lastBox: 0,
    ht: 0,
    lt: 0,
    coating: 0,
    magnetPreparedBy: '',
    boxPreparedBy: ''
});

// Watch for changes to selectedMassProd
watch(() => mpcs.selectedMassProd, async (newVal, oldVal) => {
    await getMassProdData();
});

// MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!! // MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!!

/* debugging massProdControlSheetTest -> mpcs
watch(
  mpcs,
  (newVal) => {
    console.log('massProdControlSheetTest changed:', newVal);
  },
  { deep: true }
);
*/

// HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!!

const hti = reactive({
    cycleNo: '',
    patternNo: '',
    cyclePattern: '',
    currentPattern: '',
    dateStart: '',
    timeStart: '',
    loader: '',
    dateFinish: '',
    timeFinish: '',
    unloader: '',
    boxCondition: '',
    boxCover: '',
    boxArrangement: '',
    encodedBy: '',
    remarks1: '',
    remarks2: '',
    remarks3: ''
});

/* heat treatment data test
watch(
  hti,
  (newVal) => {
    console.log('heatTreatmentInformationTest changed:', newVal);
  },
  { deep: true }
);
*/

// HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!!

const clearAll = () => {
    // Reset all hti fields
    Object.keys(hti).forEach(key => {
        hti[key] = typeof hti[key] === 'number' ? 0 : '';
    });

    // Reset all mpcs fields
    Object.keys(mpcs).forEach(key => {
        mpcs[key] = typeof mpcs[key] === 'number' ? 0 : '';
    });

    // Reset boxNoValues
    Object.keys(boxNoValues.value).forEach(key => {
        boxNoValues.value[key] = '';
    });

    // Reset weightValues
    Object.keys(weightValues.value).forEach(key => {
        weightValues.value[key] = '';
    });

    mpcs.selectedBoxEndList = 'K';
    mpcs.selectedModel = '';
    initialFurnaceData.value = null;
}

const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
const handleCycleGraphUpload = (event) => {
    const file = event.target.files[0];
    if (!file){
        return;
    }
    if(!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a PNG, JPG or JPEG image.');
        event.target.value = ''; // Clear the input if the file type is not allowed
        cycleGraphPreview.value = null; // Reset preview if invalid file
        return;
    }
    cycleGraphFile.value = file;
    cycleGraphPreview.value = URL.createObjectURL(file); // Create a preview URL
    console.log('Cycle Graph File selected:', cycleGraphFile.value);
}

const handleActualGraphUpload = (event) => {
    const file = event.target.files[0];
    if (!file){
        return;
    }
    if(!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a PNG, JPG or JPEG image.');
        event.target.value = ''; // Clear the input if the file type is not allowed
        actualGraphPreview.value = null; // Reset preview if invalid file
        return;
    }
    actualGraphFile.value = file;
    actualGraphPreview.value = URL.createObjectURL(file); // Create a preview URL
    console.log('Actual Graph File selected:', actualGraphFile.value);
}


const cancelProceed = () => {
    showModalCreate.value = false;
}

const finalize = () => {
    if (!bypassValidation.value) {
        // validate MPCS fields
        if (
            !mpcs.selectedMassProd || !mpcs.selectedLayer || !mpcs.selectedBoxEndList ||
            !mpcs.selectedModel || !mpcs.coatingMCNo || !mpcs.lotNo ||
            mpcs.qty <= 0 || mpcs.qty_lastBox <= 0 ||
            !mpcs.coating || !mpcs.magnetPreparedBy
        ) {
            toast.error("Please fill in all required Control Sheet fields.");
            return;
        }

        // validate HTI fields only if not detected already
        if (!heatTreatmentInformationDetected.value) {
            if (
                !initialFurnaceData || !hti.cycleNo || !hti.patternNo ||
                !hti.cyclePattern || !hti.currentPattern || !hti.dateStart ||
                !hti.timeStart || !hti.loader || !hti.dateFinish || !hti.timeFinish ||
                !hti.unloader || !hti.boxCondition || !hti.boxCover ||
                !hti.boxArrangement || !hti.encodedBy
            ) {
                toast.error("Please fill in all required Heat Treatment Info fields.");
                return;
            }
        }
        if (!heatTreatmentInformationDetected.value) {
            if (
                !cycleGraphFile.value || !actualGraphFile.value
            ) {
                toast.error("Please upload all required graph files.");
                return;
            }
        }

    }

    // new per-box check
    for (const box of visibleBoxes.value) {
        if ((bypassValidation.value == false)  && (!boxNoValues.value[box] || !weightValues.value[box] || weightValues.value[box] <= 0)) {
            toast.error(`Please fill in box number and weight for box ${box}.`);
            return;
        }
    }
    showModalCreate.value = true;
}

const saveToDatabase = async () => {
    const layerKey = `layer_${mpcs.selectedLayer.replace('.', '_')}`;
    const visibleBoxesData = visibleBoxes.value; // e.g., ['A','B','C']

    // QTY (PCS) with last box logic
    const qtyData = {};
    visibleBoxesData.forEach((box, index) => {
        if (index === visibleBoxesData.length - 1) {
            qtyData[box] = mpcs.qty_lastBox; // last box
        } else {
            qtyData[box] = mpcs.qty; // all others
        }
    });

    // Construct layer payload
    const layerPayload = [
        { rowTitle: 'MODEL:', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.selectedModel || ''])) },
        { rowTitle: 'COATING M/C No.:', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.coatingMCNo || ''])) },
        { rowTitle: 'LT. No.:', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.lotNo || ''])) },
        { rowTitle: 'QTY (PCS):', data: qtyData },
        { rowTitle: 'HT (PCS):', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.ht])) },
        { rowTitle: 'LT (PCS):', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.lt])) },
        { rowTitle: 'COATING:', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.coating])) },
        { rowTitle: 'WT (KG):', data: Object.fromEntries(visibleBoxesData.map(box => [box, weightValues.value[box] || ''])) },
        { rowTitle: 'BOX No.:', data: Object.fromEntries(visibleBoxesData.map(box => [box, boxNoValues.value[box] || ''])) },
        { rowTitle: 'Magnet prepared by:', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.magnetPreparedBy])) },
        { rowTitle: 'Box prepared by:', data: Object.fromEntries(visibleBoxesData.map(box => [box, mpcs.boxPreparedBy])) },
    ];

    // Base payload
    const dataPayload = {
        mass_prod: mpcs.selectedMassProd,
        furnace: initialFurnaceData.value,
        [layerKey]: JSON.stringify(layerPayload),
    };

    // Only include heat treatment info if not detected
    if (!heatTreatmentInformationDetected.value) {
        Object.assign(dataPayload, {
            batch_cycle_no: mpcs.selectedMassProd,
            machine_no: initialFurnaceData.value,
            cycle_no: hti.cycleNo,
            pattern_no: hti.patternNo,
            cycle_pattern: hti.cyclePattern,
            current_pattern: hti.currentPattern,
            date_start: hti.dateStart,
            time_start: hti.timeStart,
            loader: hti.loader,
            date_finished: hti.dateFinish,
            time_finished: hti.timeFinish,
            unloader: hti.unloader,
            box_condition: hti.boxCondition,
            box_cover: hti.boxCover,
            box_arrangement: hti.boxArrangement,
            encoded_by: hti.encodedBy,
            remarks1: hti.remarks1,
            remarks2: hti.remarks2,
            remarks3: hti.remarks3,
        });
    }

    console.log('Data Payload:', dataPayload);

    try {
        const response = await axios.patch(`/api/mass-production/by-mass-prod/${mpcs.selectedMassProd}`, dataPayload);
        await uploadGraphs();
        console.log('Data saved successfully:', response.data);
        toast.success('Data saved successfully!');
        showModalCreate.value = false;
        clearAll(); // Clear all fields after successful save
    } catch (error) {
        console.error('Error saving data:', error);
        toast.error('Failed to save data. Please try again.');
    }
};

const uploadGraphs = async () => {
    if (!mpcs.selectedMassProd) return;

    const formData = new FormData();

    if (cycleGraphFile.value) {
        formData.append('cycle_graph', cycleGraphFile.value);
    }
    if (actualGraphFile.value) {
        formData.append('actual_graph', actualGraphFile.value);
    }

    // 🔑 Pass patternNo so backend fetches standard graph
    formData.append('pattern_no', hti.patternNo);

    try {
        const response = await axios.post(
            `/api/mass-production/by-mass-prod/${mpcs.selectedMassProd}`,
            formData,
            {
                headers: { 'Content-Type': 'multipart/form-data' }
            }
        );
        console.log('Graphs uploaded successfully:', response.data);
        toast.success('Graphs uploaded successfully!');
    } catch (error) {
        console.error('Error uploading graphs:', error);
        toast.error('Failed to upload graphs. Please try again.');
    }
};


// APPLYING Browser Session ----------------- APPLYING Browser Session

useSessionStorage('bypassValidation',bypassValidation);
useSessionStorage('showModalCreate',showModalCreate);
useSessionStorage('initialFurnaceData',initialFurnaceData);
useSessionStorage('massProd_names',massProd_names);
useSessionStorage('model_names',model_names);
useSessionStorage('visibleBoxes',visibleBoxes);
useSessionStorage('boxNoValues',boxNoValues);
useSessionStorage('weightValues',weightValues);
useSessionStorage('mpcs',mpcs);
useSessionStorage('hti',hti);

// APPLYING Browser Session ----------------- APPLYING Browser Session

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    await getMassProdLists();
    await getModelLists();
    await getGraphPatterns();
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
