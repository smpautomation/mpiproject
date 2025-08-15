<template>
    <Frontend>
        <div class="flex flex-col justify-start min-h-screen px-4 py-12 bg-gray-100">
            <div class="flex flex-row items-start mb-5 gap-10">
                <p class="text-gray-400">Fill all the required fields (<span class="text-red-500"> * </span>)</p>
                <div v-if="state.user && state.user.access_type == 'Automation'" class="space-x-2">
                    <p>Dev Controls:</p>
                    <button @click="bypassValidation = true" class="bg-gray-200 rounded-lg p-1" :class="[bypassValidation ? 'bg-yellow-400' : '']">ByPass Validation</button>
                    <button @click="bypassValidation = false" class="bg-gray-300 rounded-lg p-1"> x </button>
                </div>
            </div>
            <div class="flex flex-row justify-center gap-0">
                <div class="max-w-4xl mx-auto bg-white border border-gray-200 shadow-xl rounded-2xl px-2 py-7 md:px-12 space-y-4">
                    <h2 class="text-md font-bold text-gray-800 border-b pb-1">Mass Production Control Sheet</h2>

                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Mass Prod. Name<span class="text-red-500"> *</span></label>
                            <select v-model="mpcs.selectedMassProd" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs">
                                <option v-for="item in massProd_name" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Layer<span class="text-red-500"> *</span></label>
                            <select v-model="mpcs.selectedLayer" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs">
                                <option v-for="item in layers" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Boxes: A to<span class="text-red-500"> *</span></label>
                            <select v-model="mpcs.selectedBoxEndList" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs">
                                <option v-for="item in boxesEndList" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Group: Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Model<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.model" type="text" @input="mpcs.model = mpcs.model.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Coating M/C No.<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.coatingMCNo" type="text" @input="mpcs.coatingMCNo = mpcs.coatingMCNo.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                    </div>

                    <!-- Group: Lot + Qty -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Lot No.<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.lotNo" type="text" @input="mpcs.lotNo = mpcs.lotNo.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Qty (PCS)<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.qty" type="number" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1 whitespace-nowrap">Qty (PCS) - Last Box<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.qty_lastBox" type="number" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Coating<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.coating" type="number" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                    </div>

                    <!-- Group: Prepared By -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">HT (PCS)</label>
                            <input v-model="mpcs.ht" type="number" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">LT (PCS)</label>
                            <input v-model="mpcs.lt" type="number" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Magnet Prepared By<span class="text-red-500"> *</span></label>
                            <input v-model="mpcs.magnetPreparedBy" type="text" @input="mpcs.magnetPreparedBy = mpcs.magnetPreparedBy.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Box Prepared By</label>
                            <input v-model="mpcs.boxPreparedBy" type="text" @input="mpcs.boxPreparedBy = mpcs.boxPreparedBy.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                        </div>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto bg-white border border-gray-200 shadow-xl rounded-2xl px-8 py-8 md:px-12 space-y-6">
                    <p class="font-semibold text-gray-800 border-b pb-2 text-sm">BOX No. <span class="text-gray-300">(example: UBP85172)</span><span class="text-red-500"> *</span></p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-center">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                v-for="box in visibleBoxes"
                                :key="box"
                                class="border border-gray-300 px-4 py-2 text-xs"
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
                                class="border border-gray-300 px-2 py-1"
                                >
                                <input
                                    v-model="boxNoValues[box]"
                                    @input="boxNoValues[box] = boxNoValues[box].toUpperCase()"
                                    type="text"
                                    class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs"
                                />
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <p class="font-semibold text-gray-800 border-b pb-2 text-sm">Weight <span class="text-gray-300">(KG)</span><span class="text-red-500"> *</span></p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-center">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                v-for="box in visibleBoxes"
                                :key="box"
                                class="border border-gray-300 px-4 py-2 text-xs"
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
                                class="border border-gray-300 px-2 py-1"
                                >
                                <input
                                    v-model="weightValues[box]"
                                    type="number"
                                    class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs"
                                />
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex flex-row mt-12">
                <div class="max-w-4xl mx-auto bg-white border border-gray-200 shadow-xl rounded-2xl px-2 py-7 md:px-10 space-y-4">
                    <h2 class="text-md font-bold text-gray-800 border-b pb-1">Heat Treatment Information</h2>
                    <div class="flex flex-row space-x-5">
                        <div class="flex flex-col">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Batch Cycle No</label>
                                <input v-model="mpcs.selectedMassProd" type="text" disabled class="cursor-not-allowed bg-gray-100 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Machine No</label>
                                <input v-model="initialFurnaceData" type="text" class="cursor-not-allowed bg-gray-100 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Cycle No<span class="text-red-500"> *</span></label>
                                <input v-model="hti.cycleNo" type="text" @input="hti.cycleNo = hti.cycleNo.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pattern No<span class="text-red-500"> *</span></label>
                                <input v-model="hti.patternNo" type="number" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Cycle Pattern<span class="text-red-500"> *</span></label>
                                <input v-model="hti.cyclePattern" type="text" @input="hti.cyclePattern = hti.cyclePattern.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Current Pattern<span class="text-red-500"> *</span></label>
                                <input v-model="hti.currentPattern" type="text" @input="hti.currentPattern = hti.currentPattern.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Date Start<span class="text-red-500"> *</span></label>
                                <input v-model="hti.dateStart" type="date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Time Start<span class="text-red-500"> *</span></label>
                                <input v-model="hti.timeStart" type="time" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Loader<span class="text-red-500"> *</span></label>
                                <input v-model="hti.loader" type="text" @input="hti.loader = hti.loader.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Date Finish<span class="text-red-500"> *</span></label>
                                <input v-model="hti.dateFinish" type="date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Time Finish<span class="text-red-500"> *</span></label>
                                <input v-model="hti.timeFinish" type="time" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Unloader<span class="text-red-500"> *</span></label>
                                <input v-model="hti.unloader" type="text" @input="hti.unloader = hti.unloader.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Box Condition<span class="text-red-500"> *</span></label>
                                <input v-model="hti.boxCondition" type="text" @input="hti.boxCondition = hti.boxCondition.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Box Cover<span class="text-red-500"> *</span></label>
                                <input v-model="hti.boxCover" type="text" @input="hti.boxCover = hti.boxCover.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Box Arrangement<span class="text-red-500"> *</span></label>
                                <input v-model="hti.boxArrangement" type="text" @input="hti.boxArrangement = hti.boxArrangement.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Encoded By<span class="text-red-500"> *</span></label>
                                <input v-model="hti.encodedBy" type="text" @input="hti.encodedBy = hti.encodedBy.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Remarks1</label>
                                <input v-model="hti.remarks1" type="text" @input="hti.remarks1 = hti.remarks1.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Remarks2</label>
                                <input v-model="hti.remarks2" type="text" @input="hti.remarks2 = hti.remarks2.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Remarks3</label>
                                <input v-model="hti.remarks3" type="text" @input="hti.remarks3 = hti.remarks3.toUpperCase()" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="max-w-4xl mx-auto bg-white border border-gray-300 shadow-xl rounded-2xl px-6 py-8 md:px-16 space-y-6 ml-5">
                    <h2 class="text-md font-bold text-gray-800 border-b pb-1">Heat Treatment Graph Upload <span class="text-xs text-gray-300">(PNG, JPG and JPEG)</span></h2>
                    <div class="flex flex-col space-y-8 border border-gray-300 rounded-lg p-6 bg-white shadow-sm">
                    <div class="flex flex-col space-y-2 border-b border-gray-200 pb-4">
                        <label for="cycleGraph" class="text-sm font-semibold text-gray-800">Cycle Graph<span class="text-red-500"> *</span></label>
                        <input
                        id="cycleGraph"
                        @change="handleCycleGraphUpload"
                        accept=".png, .jpg, .jpeg"
                        type="file"
                        class="block w-full text-gray-700 text-sm
                                file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                file:text-sm file:font-semibold file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        />
                    </div>

                    <div class="flex flex-col space-y-2 border-b border-gray-200 pb-4">
                        <label for="standardGraph" class="text-sm font-semibold text-gray-800">Standard Graph<span class="text-red-500"> *</span></label>
                        <input
                        id="standardGraph"
                        @change="handleStandardGraphUpload"
                        accept=".png, .jpg, .jpeg"
                        type="file"
                        class="block w-full text-gray-700 text-sm
                                file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                file:text-sm file:font-semibold file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        />
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="actualGraph" class="text-sm font-semibold text-gray-800">Actual Graph<span class="text-red-500"> *</span></label>
                        <input
                        id="actualGraph"
                        @change="handleActualGraphUpload"
                        accept=".png, .jpg, .jpeg"
                        type="file"
                        class="block w-full text-gray-700 text-sm
                                file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                file:text-sm file:font-semibold file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        />
                    </div>

                </div>

                </div>
                <div
                class="
                    w-64 mx-auto bg-gradient-to-b from-gray-50 to-white
                    border-2 border-indigo-600
                    shadow-[0_10px_25px_rgba(99,102,241,0.3),0_4px_6px_rgba(0,0,0,0.05)]
                    rounded-3xl px-8 py-10 ml-5 md:px-6 space-y-8
                    flex flex-col items-center
                    ring-1 ring-indigo-300
                    backdrop-blur-sm
                "
                >
                    <div class="w-full max-w-xs mt-14">
                        <button @click="finalize" class="finalize-btn w-full">
                        <span class="text">FINALIZE</span>
                        <span class="shine"></span>
                        </button>
                    </div>

                    <div class="w-full max-w-xs space-y-4">
                        <button
                        class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-extrabold text-lg rounded-xl py-3 shadow-md
                                transition transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50"
                        >
                        CANCEL
                        </button>

                        <button
                        @click="clearAll()"
                        class="w-full bg-gray-500 hover:bg-gray-600 active:bg-gray-700 text-white font-extrabold text-lg rounded-xl py-3 shadow-md
                                transition transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
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
                    class="text-gray-400 hover:text-gray-600 transition duration-150"
                    aria-label="Close modal"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>

                    <p class="text-gray-800 text-lg font-bold mb-6">Please review your inputs <span class="text-red-700">carefully</span> before submitting.</p>

                    <div class="px-5 overflow-auto">
                        <table class="min-w-full border border-gray-200 border-collapse text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 px-2 py-1 text-left"></th>
                                    <th
                                        v-for="item in visibleBoxes"
                                        :key="item"
                                        class="border border-gray-300 px-2 py-1 text-center font-semibold"
                                    >
                                        {{ item }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Model:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.model }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Coating M/C No:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.coatingMCNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Lot No:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.lotNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Qty (PCS):</td>
                                    <td
                                        v-for="n in visibleBoxes.length - 1"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.qty }}
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1 text-center">
                                        {{ mpcs.qty_lastBox }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">HT (PCS):</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.ht }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">LT (PCS):</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.lt }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">WT (KG):</td>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ weightValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Box No.:</td>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ boxNoValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Coating:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.coating }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Magnet Prepared By:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.magnetPreparedBy }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 font-medium whitespace-nowrap">Box Prepared By:</td>
                                    <td
                                        v-for="n in visibleBoxes.length"
                                        :key="n"
                                        class="border border-gray-300 px-2 py-1 text-center"
                                    >
                                        {{ mpcs.boxPreparedBy }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="my-2 border-gray-200 border p-4">
                        <div class="flex flex-row text-xs whitespace-nowrap">
                            <div class="flex flex-col items-end gap-1 font-semibold">
                                <label>Batch Cycle No.: </label>
                                <label>Machine No.: </label>
                                <label>Cycle No.: </label>
                                <label>Pattern No.: </label>
                                <label>Cycle Pattern: </label>
                                <label>Current Pattern: </label>
                            </div>
                            <div class="flex flex-col ml-5 gap-1">
                                <span>{{ mpcs.selectedMassProd || 'NA' }}</span>
                                <span>{{ initialFurnaceData || 'NA' }}</span>
                                <span>{{ hti.cycleNo || 'NA' }}</span>
                                <span>{{ hti.patternNo || 'NA' }}</span>
                                <span>{{ hti.cyclePattern || 'NA' }}</span>
                                <span>{{ hti.currentPattern || 'NA' }}</span>
                            </div>
                            <div class="flex flex-col items-end ml-2 gap-1 border-l border-gray-200 pl-2 font-semibold">
                                <label>Date Start: </label>
                                <label>Time Start: </label>
                                <label>Loader: </label>
                            </div>
                            <div class="flex flex-col ml-5 gap-1">
                                <span>{{ hti.dateStart || 'NA' }}</span>
                                <span>{{ hti.timeStart || 'NA' }}</span>
                                <span>{{ hti.loader || 'NA' }}</span>
                            </div>
                            <div class="flex flex-col items-end ml-2 gap-1 border-l border-gray-200 pl-2 font-semibold">
                                <label>Date Finished: </label>
                                <label>Time Finished: </label>
                                <label>Unloader: </label>
                            </div>
                            <div class="flex flex-col ml-5 gap-1">
                                <span>{{ hti.dateFinish || 'NA' }}</span>
                                <span>{{ hti.timeFinish || 'NA' }}</span>
                                <span>{{ hti.unloader || 'NA' }}</span>
                            </div>
                            <div class="flex flex-col items-end ml-2 gap-1 border-l border-gray-200 pl-2 font-semibold">
                                <label>Box Condition: </label>
                                <label>Box Cover: </label>
                                <label>Box Arrangement: </label>
                                <label>Encoded By: </label>
                            </div>
                            <div class="flex flex-col ml-5 gap-1">
                                <span>{{ hti.boxCondition || 'NA' }}</span>
                                <span>{{ hti.boxCover || 'NA' }}</span>
                                <span>{{ hti.boxArrangement || 'NA' }}</span>
                                <span>{{ hti.encodedBy || 'NA' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p>Cycle Graph File selected: <span class="font-semibold">{{ cycleGraphFile || 'NA'}}</span></p>
                        <p>Standard Graph File selected: <span class="font-semibold">{{ standardGraphFile || 'NA' }}</span></p>
                        <p>Actual Graph File selected: <span class="font-semibold">{{ actualGraphFile || 'NA' }}</span></p>
                    </div>
                    <div class="flex space-x-3">
                        <button
                            @click="cancelProceed"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1"
                        >
                            Cancel
                        </button>
                        <button
                            @click="saveToDatabase"
                            class="flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
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

//BYPASS VALIDATION
const bypassValidation = ref(false);
//BYPASS VALIDATION
const cycleGraphFile = ref(null);
const standardGraphFile = ref(null);
const actualGraphFile = ref(null);
const showModalCreate = ref(false);
const initialFurnaceData = ref();
const massProd_name = ref(['523RD', '425TH']);
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

const getMassProdData = async () => {
    if (!mpcs.selectedMassProd){
        initialFurnaceData.value = null; // Reset if no mass production selected
        return; // skip if empty
    }
    try {
        const response = await axios.get(`/api/mass-production/by-mass-prod/${mpcs.selectedMassProd}`);
        console.log('Mass Production Data:', response.data);
        const massProdData = response.data;
        initialFurnaceData.value = massProdData.furnace;
        console.log('Initial Furnace Data:', initialFurnaceData.value);
    } catch (error) {
        initialFurnaceData.value = null; // Reset if no mass production selected
        console.error('Error fetching mass production data:', error);
        toast.error('Failed to load mass production data.');
    }
}

// MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!! // MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!!

const mpcs = reactive({
    selectedMassProd: '',
    selectedLayer: '1',
    selectedBoxEndList: 'K',
    model: '',
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

    mpcs.selectedBoxEndList = 'K';
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
    }
    cycleGraphFile.value = file.name;
    console.log('Cycle Graph File selected:', cycleGraphFile.value);
}

const handleStandardGraphUpload = (event) => {
    const file = event.target.files[0];
    if (!file){
        return;
    }
    if(!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a PNG, JPG or JPEG image.');
        event.target.value = ''; // Clear the input if the file type is not allowed
    }
    standardGraphFile.value = file.name;
    console.log('Standard Graph File selected:', standardGraphFile.value);
}

const handleActualGraphUpload = (event) => {
    const file = event.target.files[0];
    if (!file){
        return;
    }
    if(!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a PNG, JPG or JPEG image.');
        event.target.value = ''; // Clear the input if the file type is not allowed
    }
    actualGraphFile.value = file.name;
    console.log('Actual Graph File selected:', actualGraphFile.value);
}


const cancelProceed = () => {
    showModalCreate.value = false;
}

const finalize = () => {
    // Validate required fields
    if ((bypassValidation.value == false) && (!mpcs.selectedMassProd || !mpcs.selectedLayer || !mpcs.selectedBoxEndList ||
        !mpcs.model || !mpcs.coatingMCNo || !mpcs.lotNo || mpcs.qty <= 0 || mpcs.qty_lastBox <= 0 ||
        !mpcs.coating || !mpcs.magnetPreparedBy ||
        !hti.machineNo || !hti.cycleNo || !hti.patternNo ||
        !hti.cyclePattern || !hti.currentPattern || !hti.dateStart ||
        !hti.timeStart || !hti.loader || !hti.dateFinish || !hti.timeFinish ||
        !hti.unloader || !hti.boxCondition || !hti.boxCover ||
        !hti.boxArrangement || !hti.encodedBy)) {
        toast.error('Please fill in all required fields.');
        return;
    }

    // new per-box check
    for (const box of visibleBoxes.value) {
        if ((bypassValidation.value == false) && (!boxNoValues.value[box] || !weightValues.value[box] || weightValues.value[box] <= 0)) {
            toast.error(`Please fill in box number and weight for box ${box}.`);
            return;
        }
    }

    if((bypassValidation.value == false) && (!cycleGraphFile.value || !standardGraphFile.value || !actualGraphFile.value)) {
        toast.error('Please upload all required graph files.');
        return;
    }

    showModalCreate.value = true;
}

const saveToDatabase = async () => {
   const layerKey = computed(() => `layer_${mpcs.selectedLayer}`);
    const dataPayload = {
        mass_prod: mpcs.selectedMassProd,
        furnace: initialFurnaceData.value,
        batch_cycle_no: mpcs.selectedMassProd,
        machine_no: initialFurnaceData.value,
        cycle_no: hti.cycleNo,
        pattern_no: hti.patternNo,
        cycle_pattern: hti.cyclePattern,
        current_pattern: hti.currentPattern,
        date_start: hti.dateStart,
        time_start: hti.timeStart,
        loader: hti.loader,
        date_finish: hti.dateFinish,
        time_finish: hti.timeFinish,
        unloader: hti.unloader,
        box_condition: hti.boxCondition,
        box_cover: hti.boxCover,
        box_arrangement: hti.boxArrangement,
        encoded_by: hti.encodedBy,
        remarks1: hti.remarks1,
        remarks2: hti.remarks2,
        remarks3: hti.remarks3,
        [layerKey.value]: JSON.stringify({
            model: mpcs.model,
            coating_mc_no: mpcs.coatingMCNo,
            lot_no: mpcs.lotNo,
            qty: mpcs.qty,
            qty_lastBox: mpcs.qty_lastBox,
            ht: mpcs.ht,
            lt: mpcs.lt,
            coating: mpcs.coating,
            boxes: boxNoValues.value,
            weights: weightValues.value,
            magnet_prepared_by: mpcs.magnetPreparedBy,
            box_prepared_by: mpcs.boxPreparedBy
        }),
    }
    console.log('Data Payload:', dataPayload);

    try{
        const response = await axios.patch(`/api/mass-production/by-mass-prod/${mpcs.selectedMassProd}`, dataPayload);
        console.log('Data saved successfully:', response.data);
        toast.success('Data saved successfully!');
        showModalCreate.value = false;
        clearAll(); // Clear all fields after successful save
    }catch (error) {
        console.error('Error saving data:', error);
        toast.error('Failed to save data. Please try again.');
    }
}

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
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
