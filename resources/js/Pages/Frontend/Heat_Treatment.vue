<template>
    <Frontend>
        <div class="flex flex-row items-start gap-10 p-5 text-green-600 bg-black">
            <div v-if="state.user && state.user.access_type == 'Automation'" class="space-x-2">
                <p>Dev Controls:</p>
                <button @click="bypassValidation = true" class="p-1 bg-gray-200 rounded-lg" :class="[bypassValidation ? 'bg-yellow-400' : '']">ByPass Validation</button>
                <button @click="bypassValidation = false" class="p-1 bg-gray-300 rounded-lg"> x </button>
            </div>
        </div>
        <div class="flex flex-col justify-start min-h-screen px-4 py-12 bg-gray-100">
            <div class="flex items-center justify-between p-4 mb-4 text-sm text-yellow-800 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg">
                <div>
                    <strong>Note:</strong> For 1st & 2nd GBDP formats, select <span class="font-semibold">Mass Prod</span>, <span class="font-semibold">Layer</span>, and <span class="font-semibold">Model</span>, then click the
                    <span class="bg-orange-500 text-white px-2 py-0.5 rounded">Apply 1st & 2nd GBDP</span> button in the <span class="font-semibold">Control Panel</span>. If button is disabled, the model is not yet registered.
                </div>
                <button
                    class="ml-4 font-semibold text-blue-600 hover:underline"
                    @click.prevent="$inertia.visit('/second_gbdp_models')"
                >
                    Register Here
                </button>
            </div>
            <div class="flex flex-row justify-center gap-0">
                <div v-if="!overwriteMode" class="w-full px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-1 h-8 rounded-full bg-gradient-to-b from-cyan-500 to-teal-500"></div>
                            <div>
                            <h2 class="text-xl font-bold text-gray-900">Mass Production Control Sheet</h2>
                             <p class="text-sm text-gray-500">Fill up all details below. Fields with <span class="font-semibold text-red-500">*</span> are required</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="relative">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">
                                Furnace Name <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="mpcs.selectedFurnace"
                                :disabled="isDataShown"
                                :class="isDataShown
                                    ? 'w-full text-xs font-semibold text-gray-400 border-2 border-gray-300 rounded-lg bg-gray-100 opacity-70 cursor-not-allowed'
                                    : 'w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50'
                                "
                            >
                                <option v-for="item in furnace_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>

                        <div class="relative">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">
                                Mass Prod. Name <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="mpcs.selectedMassProd"
                                :disabled="isDataShown"
                                :class="isDataShown
                                    ? 'w-full text-xs font-semibold text-gray-400 border-2 border-gray-300 rounded-lg bg-gray-100 opacity-70 cursor-not-allowed'
                                    : 'w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50'
                                "
                            >
                                <option v-for="item in massProd_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Layer<span class="text-red-500"> *</span>
                            </label>
                            <select
                                v-model="mpcs.selectedLayer"
                                :disabled="isDataShown"
                                :class="isDataShown
                                    ? 'w-full text-xs font-semibold text-gray-400 border-2 border-gray-300 rounded-lg bg-gray-100 opacity-70 cursor-not-allowed'
                                    : 'w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50'
                                "
                            >
                                <option
                                    v-for="item in layers"
                                    :key="item"
                                    :value="item"
                                    :disabled="item === '9.5' && Number(totalBoxes) !== 10"
                                >
                                    {{ item }}
                                </option>
                            </select>
                        </div>

                        <!-- 9.5 Layer Radios: Full width row -->
                        <div v-if="isLayerNinePointFive" class="col-span-1 md:col-span-4">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">9.5 Layer Set <span class="text-red-500">*</span></label>
                            <div class="flex flex-col gap-4 md:flex-row">
                                <label
                                    class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-800 transition-colors duration-150 border rounded-lg cursor-pointer hover:bg-yellow-50"
                                    :class="{
                                        'border-yellow-500 bg-yellow-50': mpcs.nineHalfSet === 'SET1',
                                        'opacity-50 cursor-not-allowed': isDataShown
                                    }"
                                >
                                    <input
                                        type="radio"
                                        value="SET1"
                                        v-model="mpcs.nineHalfSet"
                                        class="accent-yellow-500"
                                        :disabled="isDataShown"
                                    />
                                    First Half: A, C, E, G, J
                                </label>

                                <label
                                    class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-800 transition-colors duration-150 border rounded-lg cursor-pointer hover:bg-yellow-50"
                                    :class="{
                                        'border-yellow-500 bg-yellow-50': mpcs.nineHalfSet === 'SET2',
                                        'opacity-50 cursor-not-allowed': isDataShown
                                    }"
                                >
                                    <input
                                        type="radio"
                                        value="SET2"
                                        v-model="mpcs.nineHalfSet"
                                        class="accent-yellow-500"
                                        :disabled="isDataShown"
                                    />
                                    Second Half: B, D, F, H, K
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- Group: Basic Info -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Lot No.<span class="text-red-500"> *</span>
                            </label>
                            <select
                                v-model="mpcs.lotNo"
                                :disabled="isDataShown"
                                :class="[
                                'w-full text-xs font-semibold transition-all duration-150 rounded-lg shadow-lg focus:ring-2',
                                isDataShown
                                    ? 'bg-gray-100 border-gray-300 text-gray-500 cursor-not-allowed focus:ring-0 focus:border-gray-300'
                                    : 'text-yellow-900 border-2 border-yellow-500 bg-yellow-50 focus:ring-yellow-400 focus:border-yellow-600'
                                ]"
                            >
                                <option value="" disabled>Select Lot No</option>
                                <option v-for="(lot, index) in lotNoLists" :key="index" :value="lot.lot_no">
                                {{ lot.lot_no }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Model<span class="text-red-500"> *</span>
                            </label>
                            <select
                                v-model="mpcs.selectedModel"
                                :disabled="isDataShown"
                                :class="[
                                'w-full text-xs font-semibold transition-all duration-150 rounded-lg shadow-lg focus:ring-2',
                                isDataShown
                                    ? 'bg-gray-100 border-gray-300 text-gray-500 cursor-not-allowed focus:ring-0 focus:border-gray-300'
                                    : 'text-yellow-900 border-2 border-yellow-500 bg-yellow-50 focus:ring-yellow-400 focus:border-yellow-600'
                                ]"
                            >
                                <option v-for="(lot, index) in model_names" :key="index" :value="lot.model_name">
                                {{ lot.model_name }}
                                </option>
                            </select>
                        </div>

                        <div class="mt-5">
                            <button
                                v-if="!isDataShown"
                                @click="fetchAllLotDataBoxDetails()"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 rounded-lg shadow-md bg-cyan-600 hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                            >
                                Show Data
                            </button>
                            <button
                                v-else
                                @click="changeData()"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-yellow-600 rounded-lg shadow-md hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            >
                                Change Data
                            </button>
                        </div>
                    </div>

                    <!-- Group: Prepared By -->
                    <div v-if="(isExisting || isExisting_2ndGBDP) && !isDataShown" class="grid items-end grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="mt-5">
                            <button
                                @click="deleteLayerData()"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-red-600 rounded-lg shadow-md hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                            >
                                Clear Layer Data
                            </button>
                        </div>
                    </div>


                    <!-- Group: Prepared By -->
                    <div class="grid items-end grid-cols-1 gap-6 pt-10 md:grid-cols-3">
                        <div
                            class="w-full px-4 py-3 mt-4 bg-gray-100 border-l-4 rounded-md shadow-sm border-cyan-600"
                        >
                            <p class="text-sm font-semibold text-gray-800">
                                Detected Total Boxes:
                                <span class="text-blue-700">{{ totalBoxes }}</span>
                            </p>
                        </div>
                        <!-- Button -->
                        <div v-if="isDataShown">
                            <button
                                @click="applyHTLT()"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-1"
                            >
                                Apply HT and LT
                            </button>
                        </div>
                        <div v-if="isDataShown && mpcs.moreThanTenBoxes">
                            <label class="block mb-1 text-xs font-medium text-gray-700">Excess Layer<span class="text-red-500"> *</span></label>
                            <select v-model="mpcs.selectedExcessLayer" class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50">
                            <option v-for="item in excessLayers" :key="item" :value="item">
                                {{ item }}
                            </option>
                            </select>
                        </div>
                    </div>

                    <div class="p-4 bg-white border border-gray-300 shadow-lg rounded-xl">

                        <h3 class="mb-2 text-sm font-bold tracking-wide text-gray-800">
                            Manual Layer Allocation
                        </h3>

                        <div class="h-px mb-4 bg-gray-200"></div>

                        <p
                            class="mb-3 text-xs tracking-wide"
                            :class="(!mpcs.selectedFurnace || !mpcs.selectedMassProd || isDataShown || noMassProdData)
                                ? 'text-red-600'
                                : 'text-gray-700'"
                        >
                            {{
                                noMassProdData
                                    ? 'The selected furnace and mass production does not exist.'
                                    : isDataShown
                                        ? 'Click Change Data button to enable Manual Allocation.'
                                        : 'Select a Furnace and Mass Production Name to enable Manual Layer Allocation.'
                            }}
                        </p>

                        <button
                            @click="showBreakLotForm()"
                            :disabled="!mpcs.selectedFurnace || !mpcs.selectedMassProd || isDataShown || noMassProdData"
                            class="w-full py-3 text-sm font-bold tracking-wide text-white transition-all duration-300 rounded-lg bg-gradient-to-r from-teal-600 to-blue-600 hover:from-indigo-500 hover:to-cyan-500 hover:shadow-xl hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:shadow-none"
                        >
                            Manual Layer Allocation
                        </button>

                    </div>

                </div>

                <div v-if="!overwriteMode" class="w-full px-8 py-8 ml-10 mr-5 space-y-6 bg-white border border-gray-200 shadow-xl rounded-2xl md:px-12">

                    <div v-if="isDataShown">
                        <div v-if="!mpcs.moreThanTenBoxes" class="w-full px-5">
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
                                            {{ mpcs.selectedModel }}
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
                                            v-for="(box) in visibleBoxes"
                                            :key="box"
                                            class="px-2 py-1 text-center border border-gray-300"
                                        >
                                            {{ qtyValues[box] }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS):</td>
                                        <td
                                            v-for="box in visibleBoxes"
                                            :key="box"
                                            class="px-2 py-1 text-center border border-gray-300"
                                        >
                                        {{ htValues[box] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS):</td>
                                        <td
                                            v-for="box in visibleBoxes"
                                            :key="box"
                                            class="px-2 py-1 text-center border border-gray-300"
                                        >
                                        {{ ltValues[box] }}
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

                        <div v-else class="w-full px-5">

                            <!-- ================= MAIN 10 BOXES ================= -->
                            <p class="mb-2 font-semibold text-gray-700">Main Boxes - Layer {{ mpcs.selectedLayer }}</p>
                            <table class="min-w-full mb-6 text-xs border border-collapse border-gray-200">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-1 text-left border border-gray-300"></th>
                                    <th v-for="box in visibleBoxes" :key="'main-header-' + box" class="px-2 py-1 font-semibold text-center border border-gray-300">
                                    {{ box }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Model:</td>
                                    <td v-for="box in visibleBoxes" :key="'main-model-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ mpcs.selectedModel }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating M/C No:</td>
                                    <td v-for="box in visibleBoxes" :key="'main-coatingMC-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ mpcs.coatingMCNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Lot No:</td>
                                    <td v-for="box in visibleBoxes" :key="'main-lot-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ mpcs.lotNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Qty (PCS):</td>
                                    <td v-for="box in visibleBoxes" :key="'main-qty-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ qtyValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS):</td>
                                    <td v-for="box in visibleBoxes" :key="'main-ht-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ htValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS):</td>
                                    <td v-for="box in visibleBoxes" :key="'main-lt-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ ltValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">WT (KG):</td>
                                    <td v-for="box in visibleBoxes" :key="'main-weight-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ weightValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box No.:</td>
                                    <td v-for="box in visibleBoxes" :key="'main-boxno-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ boxNoValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating:</td>
                                    <td v-for="box in visibleBoxes" :key="'main-coating-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ mpcs.coating }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Magnet Prepared By:</td>
                                    <td v-for="box in visibleBoxes" :key="'main-magnet-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ mpcs.magnetPreparedBy }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box Prepared By:</td>
                                    <td v-for="box in visibleBoxes" :key="'main-boxprep-' + box" class="px-2 py-1 text-center border border-gray-300">
                                    {{ mpcs.boxPreparedBy }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <!-- ================= EXCESS BOXES ================= -->
                            <div>
                                <p class="mb-2 font-semibold text-gray-700">Excess Boxes - Layer {{ mpcs.selectedExcessLayer }}</p>
                                <table class="min-w-full text-xs border border-collapse border-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                    <th class="px-2 py-1 text-left border border-gray-300"></th>
                                    <th v-for="box in visibleExcessBoxes" :key="'excess-header-' + box" class="px-2 py-1 font-semibold text-center border border-gray-300">
                                        {{ box }}
                                    </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Model:</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-model-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ mpcs.selectedModel }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating M/C No:</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-coatingMC-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ mpcs.coatingMCNo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Lot No:</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-lot-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ mpcs.lotNo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Qty (PCS):</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-qty-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ qtyValuesExcess[box] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS):</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-ht-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ htValuesExcess[box] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS):</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-lt-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ ltValuesExcess[box] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">WT (KG):</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-weight-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ weightValuesExcess[box] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box No.:</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-boxno-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ boxNoValuesExcess[box] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating:</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-coating-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ mpcs.coating }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Magnet Prepared By:</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-magnet-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ mpcs.magnetPreparedBy }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box Prepared By:</td>
                                        <td v-for="box in visibleExcessBoxes" :key="'excess-boxprep-' + box" class="px-2 py-1 text-center border border-gray-300">
                                            {{ mpcs.boxPreparedBy }}
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div v-else class="relative w-full max-w-4xl px-8 py-0 mx-auto overflow-hidden">
                         <!-- Animated Background Pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="dot-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                                <circle cx="5" cy="5" r="1" fill="currentColor" class="text-cyan-400"/>
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#dot-pattern)" />
                            </svg>
                        </div>

                        <!-- Main Card -->
                        <div class="relative overflow-hidden border-2 border-gray-200 shadow-2xl bg-gradient-to-br from-white via-gray-50 to-white rounded-3xl">

                            <!-- Top Gradient Accent -->
                            <div class="h-2 bg-gradient-to-r from-cyan-500 via-teal-400 to-cyan-500 bg-[length:200%_100%] animate-gradient-flow"></div>

                            <!-- Content Container -->
                            <div class="flex flex-col items-center justify-center px-8 py-10 space-y-6">

                            <!-- Animated Icon Container -->
                            <div class="relative">
                                <!-- Outer rotating ring -->
                                <div class="absolute inset-0 w-24 h-24 -m-3">
                                <div class="absolute inset-0 border-transparent rounded-full border-3 border-t-cyan-400 border-r-teal-400 animate-spin-slow"></div>
                                </div>

                                <!-- Glowing background -->
                                <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/20 to-teal-400/20 rounded-2xl blur-xl animate-pulse"></div>

                                <!-- Icon Container -->
                                <div class="relative flex items-center justify-center transition-transform duration-500 border-2 border-gray-200 shadow-lg w-18 h-18 bg-gradient-to-br from-slate-50 to-gray-100 rounded-xl group hover:scale-110">
                                <!-- Inner glow on hover -->
                                <div class="absolute inset-0 transition-all duration-500 bg-gradient-to-br from-cyan-400/0 to-teal-400/0 group-hover:from-cyan-400/30 group-hover:to-teal-400/30 rounded-xl"></div>

                                <!-- Main Icon -->
                                <svg class="relative w-10 h-10 text-gray-400 transition-colors duration-500 group-hover:text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                </div>
                            </div>

                            <!-- Title Section -->
                            <div class="space-y-2 text-center">
                                <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800">
                                No Data Available
                                </h2>
                                <div class="flex items-center justify-center space-x-2">
                                <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent rounded-full"></div>
                                <div class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></div>
                                <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-teal-400 to-transparent rounded-full"></div>
                                </div>
                            </div>

                            <!-- Description Card -->
                            <div class="max-w-xl">
                                <div class="p-5 border border-gray-200 shadow-lg bg-gradient-to-br from-gray-50 to-white rounded-xl">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 mt-1">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg shadow-md bg-gradient-to-br from-cyan-500 to-teal-500">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    </div>
                                    <div class="flex-1 space-y-2">
                                    <p class="text-sm leading-relaxed text-gray-700">
                                        No heat treatment data has been loaded yet. Click <span class="p-1 font-bold text-white rounded-lg bg-cyan-600">Show Data</span> button.
                                    </p>
                                    <div class="space-y-1.5">
                                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-cyan-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Select a <span class="font-semibold text-gray-800">Model</span></span>
                                        </div>
                                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Choose a <span class="font-semibold text-gray-800">Lot Number</span></span>
                                        </div>
                                    </div>
                                    <p class="pt-1 text-xs italic text-gray-500">
                                        Once loaded, the table will display all relevant information including main and excess boxes.
                                    </p>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- Decorative Elements -->
                            <div class="flex items-center justify-center pt-2 space-x-2">
                                <div class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                                <div class="w-1.5 h-1.5 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                <div class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="isDataShown"
                        :class="isGrandTotalLimitReached
                            ? 'inline-flex flex-col gap-1 px-5 py-3 rounded-xl border bg-gradient-to-br from-red-50 to-rose-50 border-red-400 shadow-md shadow-red-300/40'
                            : 'inline-flex flex-col gap-1 px-5 py-3 rounded-xl border bg-gradient-to-br from-cyan-50 to-teal-50 border-cyan-300 shadow-md shadow-cyan-200/40'
                        "
                    >
                        <div
                            :class="isGrandTotalLimitReached
                                ? 'text-[8px] font-semibold tracking-widest uppercase text-red-700'
                                : 'text-[8px] font-semibold tracking-widest uppercase text-teal-700'
                            "
                        >
                            Expected Grand Total Weight
                        </div>

                        <div
                            :class="isGrandTotalLimitReached
                                ? 'text-xl font-bold leading-none text-red-600'
                                : 'text-xl font-bold leading-none text-cyan-500'
                            "
                        >
                            {{ grandTotalWeight }}
                        </div>

                        <!-- Warning text -->
                        <div
                            v-if="isGrandTotalLimitReached"
                            class="text-[8px] font-semibold tracking-wide text-red-700"
                        >
                            ⚠ LIMIT EXCEEDED — Max 1425
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex flex-row mt-12">
                <div v-if="!heatTreatmentInformationDetected" class="w-full px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-8">
                    <div>
                        <div v-if="overwriteMode">
                            <h2 class="pb-1 mb-4 font-bold text-gray-800 border-b text-md">
                                Overwriting Heat Treatment Information
                            </h2>

                            <p class="px-3 py-1 mb-3 text-xs font-medium text-teal-900 border rounded-md bg-cyan-100 border-cyan-300">
                                Note: Only
                                <span class="font-semibold">Cycle Pattern, Current Pattern, Date Finish, Time Finish, Remarks</span>
                                and
                                <span class="font-semibold">Graph Uploads</span>
                                can be overwritten.
                            </p>

                            <p class="px-3 py-1 pl-2 mb-3 text-xs font-medium text-yellow-800 border-l-2 border-yellow-500" v-if="!isEditingExpired">
                                ⚠ Time Start and Date Start can only be edited within 24 hours.
                            </p>

                            <p class="px-3 py-1 mb-3 text-xs font-medium text-red-700" v-if="isEditingExpired">
                                ⚠ Edit window has expired.
                            </p>
                            <p class="px-3 py-1 mb-3 text-xs font-medium text-teal-900" v-else>
                                ⏱ Hours left until expiration: {{ hoursLeftDisplay }}
                            </p>
                        </div>
                        <div v-else><h2 class="pb-1 mb-4 font-bold text-gray-800 border-b text-md">Heat Treatment Information</h2></div>
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
                                    <input v-model="hti.cycleNo" type="text" :disabled="overwriteMode" @input="hti.cycleNo = hti.cycleNo.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Pattern No<span class="text-red-500"> *</span></label>
                                    <select v-model="hti.patternNo" :disabled="overwriteMode" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed">
                                        <option v-for="item in graph_patterns" :key="item" :value="item">
                                            {{ item }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Cycle Pattern</label>
                                    <input v-model="hti.cyclePattern" type="text" @input="hti.cyclePattern = hti.cyclePattern.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Current Pattern</label>
                                    <input v-model="hti.currentPattern" type="text" @input="hti.currentPattern = hti.currentPattern.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">
                                        Date Start<span class="text-red-500"> *</span>
                                    </label>
                                    <input
                                        v-model="hti.dateStart"
                                        :disabled="overwriteMode && isEditingExpired"
                                        type="date"
                                        class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
                                    />
                                </div>

                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">
                                        Time Start<span class="text-red-500"> *</span>
                                    </label>
                                    <input
                                        v-model="hti.timeStart"
                                        :disabled="overwriteMode && isEditingExpired"
                                        type="time"
                                        class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
                                    />
                                </div>

                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Loader<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.loader" :disabled="overwriteMode" type="text" @input="hti.loader = hti.loader.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Partial No.<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.partialNo" :disabled="overwriteMode" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Date Finish</label>
                                    <input v-model="hti.dateFinish" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Time Finish</label>
                                    <input v-model="hti.timeFinish" type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Unloader</label>
                                    <input v-model="hti.unloader" type="text" @input="hti.unloader = hti.unloader.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Box Condition<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.boxCondition" type="text" :disabled="overwriteMode" @input="hti.boxCondition = hti.boxCondition.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Box Cover<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.boxCover" type="text" :disabled="overwriteMode" @input="hti.boxCover = hti.boxCover.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Box Arrangement<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.boxArrangement" type="text" :disabled="overwriteMode" @input="hti.boxArrangement = hti.boxArrangement.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">Encoded By<span class="text-red-500"> *</span></label>
                                    <input v-model="hti.encodedBy" type="text" :disabled="overwriteMode" @input="hti.encodedBy = hti.encodedBy.toUpperCase()" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed" />
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
                <div
                    v-else
                    class="w-full px-2 mx-auto mr-10 space-y-4 shadow-xl bg-gray-50 rounded-2xl py-7 md:px-8 whitespace-nowrap"
                    :class="activate2ndGBDP ? 'border border-red-600' : 'border border-yellow-400'"
                    >
                    <div class="flex flex-col items-center space-y-3 text-center"
                    :class="activate2ndGBDP ? 'px-72' : 'px-96'"
                    >
                        <!-- Icon -->
                        <div
                        class="flex items-center justify-center w-12 h-12 border rounded-full"
                        :class="activate2ndGBDP
                            ? 'bg-red-100 border-red-300'
                            : 'bg-yellow-100 border-yellow-300'"
                        >
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

                        <!-- Extra note if activate2ndGBDP -->
                        <div v-if="activate2ndGBDP" class="space-y-2">
                        <p class="text-sm font-medium text-red-700">
                            The selected model is subjected to 1st and 2nd GBDP format.
                            Please click the orange button called
                            <span class="font-bold text-orange-600">APPLY 1ST 2ND GBDP</span>.
                        </p>
                        <!-- Status preview -->
                        <div>
                        <p class="mb-2 text-sm font-semibold text-gray-800">Status Preview:</p>
                            <div class="flex flex-wrap justify-center gap-2">
                                <div
                                v-for="layer in layers"
                                :key="layer"
                                class="flex items-center justify-center w-12 h-12 text-xs font-bold rounded-lg"
                                :class="completedLayers.includes(layer)
                                    ? 'bg-green-500 text-white shadow-md'
                                    : 'bg-gray-300 text-gray-700'"
                                >
                                {{ layer }}
                                </div>
                            </div>
                        </div>
                        <!-- Explanation -->
                        <div class="flex justify-center">
                            <p class="mt-3 text-xs text-center text-gray-500">
                            Each box represents a process layer (1 – 9.5).
                            <span class="font-semibold text-green-600">Green</span> indicates the layer is complete,
                            while <span class="font-semibold text-gray-600">gray</span> indicates it is pending.
                            </p>
                        </div>

                        </div>

                        <!-- Button -->
                        <button
                        v-if="overwriteHeatTreatment"
                        @click="updateHeatTreatmentInfo()"
                        class="flex items-center justify-center px-6 py-2 space-x-2 font-bold text-white transition-all duration-200 transform bg-red-600 border-2 border-red-900 rounded-lg shadow-lg hover:scale-110 hover:shadow-2xl hover:bg-red-800 active:scale-95 active:bg-red-900"
                        >
                        <span>OVERWRITE</span>
                        </button>
                    </div>
                </div>
                <div v-if="!heatTreatmentInformationDetected" class="w-[30%] px-2 py-8 mx-auto ml-10 mr-10 space-y-8 bg-white border border-gray-300 shadow-xl rounded-2xl md:px-8">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Heat Treatment Graph Upload <span class="text-xs text-gray-300">(PNG, JPG and JPEG)</span></h2>
                    <div class="flex flex-col p-6 space-y-8 bg-white border border-gray-300 rounded-lg shadow-sm">
                    <div class="flex flex-col pb-4 space-y-2 border-b border-gray-200">
                        <label for="cycleGraph" class="text-sm font-semibold text-gray-800">Cycle Graph</label>
                        <input
                        id="cycleGraph"
                        @change="handleCycleGraphUpload"
                        accept=".png, .jpg, .jpeg"
                        type="file"
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        />
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="actualGraph" class="text-sm font-semibold text-gray-800">Actual Graph</label>
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


                <!-- Professional Sleek Control Panel - Design Improved, Logic Intact -->
                <div class="w-[20%] mr-5 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border-2 border-cyan-500/40 shadow-[0_20px_50px_rgba(6,182,212,0.3),0_4px_15px_rgba(0,0,0,0.5)] rounded-3xl px-6 py-10 space-y-10 flex flex-col items-center ring-2 ring-cyan-400/20 backdrop-blur-xl">
                    <!-- Header: Cogwheel + Label -->
                    <div class="flex items-center justify-center space-x-4">
                        <!-- Glowing spinning cogwheel -->
                        <div class="relative w-14 h-14">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-cyan-400 to-teal-400 opacity-60 blur-xl animate-pulse"></div>
                        <div class="absolute inset-0 flex items-center justify-center border-2 bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl border-cyan-400/50">
                            <img
                            src="photo/cogwheel.png"
                            alt="Settings"
                            class="relative z-10 object-contain w-10 h-10 animate-spin drop-shadow-lg"
                            style="animation-duration: 3s;"
                            />
                        </div>
                        </div>

                        <!-- Label with gradient text -->
                        <div>
                        <span class="block text-2xl font-extrabold tracking-wider text-transparent whitespace-nowrap bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-400 to-cyan-500 drop-shadow-lg">
                            Control Panel
                        </span>
                        <span class="block text-xs tracking-wide text-cyan-300/60">System Operations</span>
                        </div>
                    </div>

                    <!-- Finalize Button Section -->
                    <div class="w-full space-y-4">
                        <!-- PROCEED OVERWRITE Button -->
                        <button
                        v-if="overwriteMode"
                        @click="overwriteDatabase()"
                        class="group relative w-full py-3.5 text-sm font-bold text-white transition-all duration-300 transform shadow-lg rounded-xl bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-500 hover:to-teal-500 hover:shadow-cyan-500/60 hover:shadow-2xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-cyan-400 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 overflow-hidden"
                        >
                        <div class="absolute inset-0 transition-transform duration-700 transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-30 group-hover:translate-x-full"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-12 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <span class="drop-shadow-md">PROCEED OVERWRITE</span>
                        </span>
                        </button>

                        <button
                            v-else
                            @click="finalize"
                            :disabled="
                                noMassProdData ||
                                isExisting ||
                                isExisting_2ndGBDP ||
                                isExistingExcess ||
                                !isDataShown ||
                                isGrandTotalLimitReached
                            "
                            :class="[
                                'group relative w-full py-3.5 text-sm font-bold transition-all duration-300 transform shadow-lg rounded-xl focus:outline-none focus:ring-4 focus:ring-opacity-50 overflow-hidden',

                                (noMassProdData || isExisting || isExisting_2ndGBDP || isExistingExcess || !isDataShown || isGrandTotalLimitReached)
                                    ? 'bg-gradient-to-r from-red-600 to-red-700 cursor-not-allowed opacity-70 focus:ring-red-400 text-white'
                                    : 'bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-500 hover:to-cyan-500 focus:ring-cyan-400 text-white hover:shadow-teal-500/60 hover:shadow-2xl hover:scale-105 active:scale-95'
                            ]"
                        >
                            <!-- Shine (only when enabled) -->
                            <div
                                v-if="!(
                                    noMassProdData ||
                                    isExisting ||
                                    isExisting_2ndGBDP ||
                                    isExistingExcess ||
                                    !isDataShown ||
                                    isGrandTotalLimitReached
                                )"
                                class="absolute inset-0 transition-transform duration-700 transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-30 group-hover:translate-x-full"
                            ></div>

                            <span class="relative flex items-center justify-center space-x-2">

                                <!-- Disabled icon -->
                                <svg
                                    v-if="
                                        noMassProdData ||
                                        isExisting ||
                                        isExisting_2ndGBDP ||
                                        isExistingExcess ||
                                        !isDataShown ||
                                        isGrandTotalLimitReached
                                    "
                                    class="w-5 h-5 drop-shadow-md"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>

                                <!-- Active icon -->
                                <svg
                                    v-else
                                    class="w-5 h-5 transition-transform group-hover:rotate-12 drop-shadow-md"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>

                                <!-- Button Label -->
                                <span class="drop-shadow-md">
                                    {{
                                        noMassProdData
                                            ? 'NO MASS PROD DATA'
                                            : isExisting || isExisting_2ndGBDP || isExistingExcess
                                                ? 'LAYER OCCUPIED'
                                                : !isDataShown
                                                    ? 'SHOW DATA FIRST'
                                                    : isGrandTotalLimitReached
                                                        ? 'LIMIT REACHED'
                                                        : 'FINALIZE'
                                    }}
                                </span>
                            </span>

                            <!-- Pulse for disabled -->
                            <div
                                v-if="noMassProdData || isExisting || isExisting_2ndGBDP || isExistingExcess || !isDataShown || isGrandTotalLimitReached"
                                class="absolute inset-0 bg-red-400 opacity-10 animate-pulse">
                            </div>
                        </button>


                        <!-- CANCEL OVERWRITE Button -->
                        <button
                        v-if="overwriteMode"
                        @click="cancelOverwrite"
                        class="group relative w-full py-3.5 text-sm font-bold text-white transition-all duration-300 transform shadow-lg rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 hover:shadow-red-500/60 hover:shadow-2xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50 overflow-hidden"
                        >
                        <div class="absolute inset-0 transition-transform duration-700 transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-30 group-hover:translate-x-full"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-90 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="drop-shadow-md">CANCEL OVERWRITE</span>
                        </span>
                        </button>
                    </div>

                    <!-- Other Buttons Section -->
                    <div class="w-full mt-4 space-y-4">

                        <!-- APPLY 1ST 2ND GBDP Button -->
                        <button
                        @click="second_heat_treatment()"
                        :disabled="!(activate2ndGBDP && heatTreatmentInformationDetected) || isExisting_2ndGBDP"
                        class="group relative w-full py-3.5 text-sm font-bold text-white transition-all duration-300 transform shadow-lg rounded-xl bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 hover:shadow-orange-500/60 hover:shadow-2xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-orange-400 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:from-orange-600 disabled:hover:to-amber-600 overflow-hidden"
                        >
                        <div class="absolute inset-0 transition-transform duration-700 transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-30 group-hover:translate-x-full"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                            </svg>
                            <span class="drop-shadow-md">APPLY 1ST 2ND GBDP</span>
                        </span>
                        </button>

                        <!-- GRAPH PATTERNS Button -->
                        <button
                        @click="Inertia.visit('ht_graph_patterns')"
                        class="group relative w-full py-3.5 text-sm font-bold text-white transition-all duration-300 transform shadow-lg rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 hover:shadow-emerald-500/60 hover:shadow-2xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-emerald-400 focus:ring-opacity-50 overflow-hidden"
                        >
                        <div class="absolute inset-0 transition-transform duration-700 transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-30 group-hover:translate-x-full"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                            <span class="drop-shadow-md">GRAPH PATTERNS</span>
                        </span>
                        </button>

                        <!-- CLEAR ALL Button -->
                        <button
                        @click="clearAll()"
                        class="group relative w-full py-3.5 text-sm font-bold text-white transition-all duration-300 transform shadow-lg rounded-xl bg-gradient-to-r from-slate-600 to-gray-700 hover:from-slate-500 hover:to-gray-600 hover:shadow-gray-500/60 hover:shadow-2xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50 overflow-hidden"
                        >
                        <div class="absolute inset-0 transition-transform duration-700 transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-30 group-hover:translate-x-full"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-180 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            <span class="drop-shadow-md">CLEAR ALL</span>
                        </span>
                        </button>
                    </div>
                </div>


            </div>

            <Modal :show="showHTLTPanel" maxWidth="none" @close="showHTLTPanel = false">
                <div
                    class="relative flex flex-col items-start bg-white p-8 rounded-2xl shadow-2xl w-[60vw] h-[55vh] overflow-auto mx-auto"
                >
                    <div class="flex items-center px-4 py-3 mb-2 text-white rounded-lg shadow-md bg-gradient-to-r from-cyan-500 to-teal-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1 4v-4h1m-1 0V8m-6 8h6m-6 0h6"/>
                        </svg>
                        <p class="text-sm font-medium">
                            Note: When finished, click the <span class="font-bold">(×)</span> button at the top-right corner to close this panel.
                        </p>
                    </div>
                    <!-- Close Button -->
                    <button
                        @click="showHTLTPanel = false"
                        class="absolute flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-200 rounded-full shadow-md top-4 right-4 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <div class="w-full max-w-6xl px-10 py-8 mx-auto space-y-6 bg-white border border-gray-200 shadow-lg rounded-2xl">

                        <!-- MAIN Boxes Table -->
                        <div v-if="visibleBoxes.length">
                            <p class="pb-2 text-sm font-semibold text-gray-800 border-b">HT (PCS) - Main Boxes</p>
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
                                        :value="htValues[box]"
                                        @input="htValues[box] = $event.target.value.toUpperCase()"
                                        type="text"
                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>

                            <p class="pt-4 pb-2 text-sm font-semibold text-gray-800 border-b">LT (PCS) - Main Boxes</p>
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
                                        :value="ltValues[box]"
                                        @input="ltValues[box] = $event.target.value.toUpperCase()"
                                        type="text"
                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <!-- EXCESS Boxes Table -->
                        <div v-if="visibleExcessBoxes.length">
                            <p class="pb-2 text-sm font-semibold text-gray-800 border-b">HT (PCS) - Excess Boxes</p>
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
                                        :value="htValuesExcess[box]"
                                        @input="htValuesExcess[box] = $event.target.value.toUpperCase()"
                                        type="text"
                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>

                            <p class="pt-4 pb-2 text-sm font-semibold text-gray-800 border-b">LT (PCS) - Excess Boxes</p>
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
                                        :value="ltValuesExcess[box]"
                                        @input="ltValuesExcess[box] = $event.target.value.toUpperCase()"
                                        type="text"
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
            </Modal>


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

                    <div v-if="!mpcs.moreThanTenBoxes" class="w-full px-5">
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
                                        {{ mpcs.selectedModel }}
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
                                        v-for="(box) in visibleBoxes"
                                        :key="box"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                        {{ qtyValues[box] }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS):</td>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                    {{ htValues[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS):</td>
                                    <td
                                        v-for="box in visibleBoxes"
                                        :key="box"
                                        class="px-2 py-1 text-center border border-gray-300"
                                    >
                                    {{ ltValues[box] }}
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

                    <div v-else class="w-full px-5">

                        <!-- ================= MAIN 10 BOXES ================= -->
                        <p class="mb-2 font-semibold text-gray-700">Main Boxes (Selected Layer - {{ mpcs.selectedLayer }})</p>
                        <table class="min-w-full mb-6 text-xs border border-collapse border-gray-200">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="px-2 py-1 text-left border border-gray-300"></th>
                                <th v-for="box in visibleBoxes" :key="'main-header-' + box" class="px-2 py-1 font-semibold text-center border border-gray-300">
                                {{ box }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Model:</td>
                                <td v-for="box in visibleBoxes" :key="'main-model-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ mpcs.selectedModel }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating M/C No:</td>
                                <td v-for="box in visibleBoxes" :key="'main-coatingMC-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ mpcs.coatingMCNo }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Lot No:</td>
                                <td v-for="box in visibleBoxes" :key="'main-lot-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ mpcs.lotNo }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Qty (PCS):</td>
                                <td v-for="box in visibleBoxes" :key="'main-qty-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ qtyValues[box] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS):</td>
                                <td v-for="box in visibleBoxes" :key="'main-ht-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ htValues[box] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS):</td>
                                <td v-for="box in visibleBoxes" :key="'main-lt-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ ltValues[box] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">WT (KG):</td>
                                <td v-for="box in visibleBoxes" :key="'main-weight-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ weightValues[box] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box No.:</td>
                                <td v-for="box in visibleBoxes" :key="'main-boxno-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ boxNoValues[box] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating:</td>
                                <td v-for="box in visibleBoxes" :key="'main-coating-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ mpcs.coating }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Magnet Prepared By:</td>
                                <td v-for="box in visibleBoxes" :key="'main-magnet-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ mpcs.magnetPreparedBy }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box Prepared By:</td>
                                <td v-for="box in visibleBoxes" :key="'main-boxprep-' + box" class="px-2 py-1 text-center border border-gray-300">
                                {{ mpcs.boxPreparedBy }}
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <!-- ================= EXCESS BOXES ================= -->
                        <div>
                            <p class="mb-2 font-semibold text-gray-700">Excess Boxes (Selected Layer - {{ mpcs.selectedExcessLayer }})</p>
                            <table class="min-w-full text-xs border border-collapse border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                <th class="px-2 py-1 text-left border border-gray-300"></th>
                                <th v-for="box in visibleExcessBoxes" :key="'excess-header-' + box" class="px-2 py-1 font-semibold text-center border border-gray-300">
                                    {{ box }}
                                </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Model:</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-model-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ mpcs.selectedModel }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating M/C No:</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-coatingMC-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ mpcs.coatingMCNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Lot No:</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-lot-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ mpcs.lotNo }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Qty (PCS):</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-qty-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ qtyValuesExcess[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS):</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-ht-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ htValuesExcess[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS):</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-lt-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ ltValuesExcess[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">WT (KG):</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-weight-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ weightValuesExcess[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box No.:</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-boxno-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ boxNoValuesExcess[box] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating:</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-coating-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ mpcs.coating }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Magnet Prepared By:</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-magnet-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ mpcs.magnetPreparedBy }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box Prepared By:</td>
                                    <td v-for="box in visibleExcessBoxes" :key="'excess-boxprep-' + box" class="px-2 py-1 text-center border border-gray-300">
                                        {{ mpcs.boxPreparedBy }}
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
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
import { ref, computed, onMounted, watch, reactive, onUnmounted } from 'vue';
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

//Dev Controls ----------------- Allow Commands
const isEditingExpired = ref(false); //This is for Time Start and Date Start ONLYY!!!

let intervalId = null;

const bypassValidation = ref(false);
const overwriteHeatTreatment = ref(true);
const overwriteMode = ref(false);
const showHTLTPanel = ref(false);
const manualQtyMode = ref(false);
const isDataShown = ref(false);
const isExisting = ref(false);
const isExisting_2ndGBDP = ref(false);
const isExistingExcess = ref(false);
const noMassProdData = ref(false);
//Dev Controls ----------------- Allow Commands

const heatTreatmentInformationDetected = ref(false);

const cycleGraphFile = ref(null);
const cycleGraphPreview = ref(null);
const actualGraphFile = ref(null);
const actualGraphPreview = ref(null);
const showModalCreate = ref(false);
const initialFurnaceData = ref();
const furnace_names = ref([]);
const massProd_names = ref([]);
const model_names = ref([]);
const lotNoLists = ref([]);
const graph_patterns = ref([]);
const firstSecondGBDP_models = ref([]);
const existingLayers = ref([]);
const existingLayers_2ndGBDP = ref([]);
const completedLayers = ref(['1','2']);
const isLayerNinePointFive = computed(() => mpcs.selectedLayer === '9.5');
const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);
const excessLayers = ref([]);
const existingExcessLayers = ref([]);
const allBoxes = ['A','B','C','D','E','F','G','H','J','K'];
const totalBoxes = ref();

const visibleBoxes = ref([]);
const visibleExcessBoxes = ref([]);

// For normal/main boxes
const qtyDataMain = ref({});
// For excess boxes
const qtyDataExcess = ref({});

const grandTotalWeight = ref();
const grandTotalLimit = ref(1425);
const isGrandTotalLimitReached = computed(() => {
    return Number(grandTotalWeight.value) > grandTotalLimit.value;
});

const boxNoValues = ref({});
const weightValues = ref({});
const qtyValues = ref({});
const ltValues = ref({});
const htValues = ref({});
const boxNoValuesExcess = ref({});
const weightValuesExcess = ref({});
const qtyValuesExcess = ref({});
const htValuesExcess = ref({});
const ltValuesExcess = ref({});

allBoxes.forEach(box => {
    boxNoValues.value[box] = '';
    weightValues.value[box] = '';
    qtyValues.value[box] = '';
    htValues.value[box] = '';
    ltValues.value[box] = '';
    boxNoValuesExcess.value[box] = '';
    weightValuesExcess.value[box] = '';
    qtyValuesExcess.value[box] = '';
    htValuesExcess.value[box] = '';
    ltValuesExcess.value[box]= '';
});

// MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!! // MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!!

const mpcs = reactive({
    selectedFurnace: '',
    selectedMassProd: '',
    selectedLayer: '1',
    selectedExcessLayer: '2',
    selectedModel: '',
    selectedBoxEndList: '',
    selectedExcessBoxEndList: '',
    coatingMCNo: '',
    rawMaterialCode: '',
    lotNo: '',
    qty: 0,
    qty_lastBox: 0,
    coating: 0,
    magnetPreparedBy: '',
    boxPreparedBy: '',
    nineHalfSet: '', // 'SET1' for A,C,E,G,J | 'SET2' for B,D,F,H,K
    moreThanTenBoxes: false,
});

// Watch for changes to selectedMassProd
watch(
    [() => mpcs.selectedMassProd, () => mpcs.selectedFurnace],
    async ([newMassProd, newFurnace], [oldMassProd, oldFurnace]) => {
        await getMassProdData();
    }
);

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
    partialNo: 0,
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

const activate2ndGBDP = computed(() => {
    return firstSecondGBDP_models.value.includes(mpcs.selectedModel);
});


// HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!!

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getMassProdData = async () => { //Function for getting the current seleceted Massprod
    if (!mpcs.selectedMassProd){
        initialFurnaceData.value = null; // Reset if no mass production selected
        return; // skip if empty
    }
    try {
        const response = await axios.get(`/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}`);
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
        console.log("Heat treatment info detected:", heatTreatmentInformationDetected.value);
        console.log("Activate 2nd GBDP:", activate2ndGBDP.value);
        noMassProdData.value = false;
    } catch (error) {
        initialFurnaceData.value = null; // Reset if no mass production selected
        heatTreatmentInformationDetected.value = false;
        noMassProdData.value = true;
        console.error('Error fetching mass production data:', error);
        toast.error('Failed to load mass production data.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getMassProdData",
            "Failed to load mass production data"
        );
    }
}

const get1st2ndGBDPModels = async () => {
    try {
        const response = await axios.get(`/api/second-gbdp-models`);
        const models = response.data;

        // Extract only the model_name
        const modelNames = models.map(model => model.model_name);
        firstSecondGBDP_models.value = modelNames;
        //console.log("1st & 2nd GBDP model names: ", modelNames);
    } catch (error) {
        console.error('Failed to get 1st & 2nd GBDP Models: ', error);
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "get1st2ndGBDPModels",
            "Failed to get 1st & 2nd GBDP Models"
        );
    }
}

const sumWeightMap = (map, boxes) => {
    return boxes.reduce((sum, box) => {
        const v = Number(map[box]);
        return sum + (isNaN(v) ? 0 : v);
    }, 0);
};

const getCurrentMainWeightTotal = () => {
    return sumWeightMap(weightValues.value, visibleBoxes.value);
};

const getCurrentExcessWeightTotal = () => {
    if (!mpcs.moreThanTenBoxes) return 0;
    return sumWeightMap(weightValuesExcess.value, visibleExcessBoxes.value);
};

const computeTrueGrandTotal = async () => {
    const dbTotal = await getGrandTotalWeightData();

    const mainTotal = getCurrentMainWeightTotal();
    const excessTotal = getCurrentExcessWeightTotal();

    // integer-safe addition
    const totalInt = Math.round(dbTotal * 1000)
                   + Math.round(mainTotal * 1000)
                   + Math.round(excessTotal * 1000);

    // convert back to 2 decimals safely
    return Number((totalInt / 1000).toFixed(2));
};

const getGrandTotalWeightData = async () => {
    try{
        const response = await axios.post('/api/mass-production/grand-total-weight', {
            mass_prod: mpcs.selectedMassProd,
            furnace: mpcs.selectedFurnace,
        });

        return Number(response.data.grand_total) || 0;

    }catch(error){
        console.error('Failed to get grand total weight data',error);
    }
}

const getMassProdLists = async () => {
    try{
        const response = await axios.get('/api/mass-production/');
        const massProdList = response.data;

        massProd_names.value = massProdList
            .map(item => item.mass_prod)
            .filter(Boolean)
            .map(v =>
                v
                    .trim()
                    .replace(/[–—-]/g, '-') // normalize weird dashes
            )
            .sort((a, b) => {
                const [aPrefix, aNum = 0] = a.split('-')
                const [bPrefix, bNum = 0] = b.split('-')

                if (aPrefix !== bPrefix) {
                    return aPrefix.localeCompare(bPrefix)
                }

                return Number(aNum) - Number(bNum)
            })

        console.log("List of mass prods: ", massProdList);
    }catch(error){
        //console.error('Error fetching mass prod lists', error);
        toast.error('Failed to get the mass prod lists api error');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getMassProdLists",
            "Failed to get the mass prod lists api error"
        );
    }
};


const getFurnaceLists = async () => {
    try{
        const response = await axios.get('/api/furnace-data');
        const furnaceList = response.data;
        furnace_names.value = furnaceList.map(item => item.furnace_name);
        //console.log("List of mass prods: ",furnace_names.value);
    }catch(error){
        //console.error('Error fetching mass prod lists',error);
        toast.error('Failed to get the furnace lists api error');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getFurnaceLists",
            "Failed to get the furnace lists api error"
        );
    }
}

const getModelLists = async () => {
    try{
        const response = await axios.post(
            '/api/initial-control-sheets/lot-all-model',
            {
                lot_no: mpcs.lotNo,
            }
        );

        model_names.value = response.data;
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

const getTotalBoxes = async () => {
    try{
        const response = await axios.post('/api/initial-control-sheets/lot-total-boxes', {
            model_name: mpcs.selectedModel,
            lot_no: mpcs.lotNo
        });
        //console.log("Total Boxes data: ", response.data);
        const tbox = response.data;
        totalBoxes.value = tbox.total_boxes || 'Loading...';
        console.log('Total Boxes: ', totalBoxes.value);
    }catch(error){
        console.error('Failed to get total boxes: ', error);
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
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getGraphPatterns",
            "Failed to get graph patterns"
        );
    }
}

const fetchExistingLayers = async () => {
    if (!mpcs.selectedMassProd) {
        console.warn("Mass Production not selected yet.");
        return;
    }

    try {
        // 1. Completed initial layers
        const response = await axios.get(
            `/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}/completed-layers`
        );
        existingLayers.value = response.data.completed_layers;

        // 2. Completed 2nd GBDP layers
        const response2 = await axios.get(
            `/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}/second-ht-completed-layers`
        );
        existingLayers_2ndGBDP.value = response2.data['2nd_gbdp_layers'];

        // 3. NEW — excess layers
        const response3 = await axios.get(
            `/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}/excess-existing-layers`
        );
        existingExcessLayers.value = response3.data.excess_layers.map(String);
        console.log('Existing excess layers: ', existingExcessLayers.value);
        // Check current selected layer
        if (mpcs.selectedLayer) {
            isExisting.value =
                existingLayers.value.includes(mpcs.selectedLayer);

            isExisting_2ndGBDP.value =
                existingLayers_2ndGBDP.value.includes(mpcs.selectedLayer);

            isExistingExcess.value =
                existingExcessLayers.value.includes(mpcs.selectedLayer);
        }

        if (isExisting.value) {
            toast.warning('Selected layer already contains existing data.');
        }

        if (isExisting_2ndGBDP.value) {
            toast.warning('Selected layer already contains existing 1st and 2nd gbdp data.');
        }

        if (isExistingExcess.value) {
            toast.warning('Selected layer already contains excess data.');
        }

    } catch (error) {
        console.error("Error fetching existing layers:", error);
        toast.error('Failed to fetch existing layers.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "fetchExistingLayers",
            "Failed to fetch existing layers"
        );
    }
};

const getCompletedLayers = async () => {
    try {
        const response = await axios.get(`/api/second-heat-treatment-data/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}/layers`);
        completedLayers.value = response.data.layers.map(String);
        console.log(completedLayers.value);
    } catch (error) {
        console.error(error);
        toast.error('Failed to fetch layers');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getCompletedLayers",
            "Failed to fetch layers"
        );
    }
};

const fetchAvailableLayersForExcessList = async (excessData) => {
    if (!mpcs.selectedFurnace || !mpcs.selectedMassProd) return;

    try {
        const response = await axios.get(
            `/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}/${excessData}/excess-layers-list`
        );

        const layers = Object.keys(response.data.availableLayers);

        // selectedLayer might be number — convert to string for clean comparison
        const blocked = String(mpcs.selectedLayer);

        excessLayers.value = layers.filter(l => l !== blocked);

        console.log('excess layer lists:', excessLayers.value);

    } catch (error) {
        console.error('Failed to fetch excess layers list', error);
        excessLayers.value = [];
    }
};

const fetchAllLotNoData = async () => {
    try {
        const response = await axios.get('/api/initial-control-sheets/lot-all');
        //console.log(response.data); // All records with lot_no, newest first
        lotNoLists.value = response.data;

    } catch (err) {
        console.error('Failed to fetch lot_no data:', err);
    }
};

const changeData = () => {
    const preserveKeys = [
        'selectedFurnace',
        'selectedMassProd',
        'selectedLayer',
        'selectedModel',
        'lotNo'
    ];

    Object.keys(mpcs).forEach(key => {
        if (!preserveKeys.includes(key)) {
        mpcs[key] = typeof mpcs[key] === 'number' ? 0 : '';
        }
    });

    isDataShown.value = false;

    grandTotalWeight.value = null;
};

const getExcessDataRange = (boxes) => {
    if (!boxes || !boxes.length) return '';

    // Sort alphabetically just in case
    const sorted = [...boxes].sort();

    const first = sorted[0];
    const last = sorted[sorted.length - 1];

    // Single box case
    if (first === last) return first;

    return `${first}-${last}`;
};

const fetchAllLotDataBoxDetails = async () => {

    if(!mpcs.selectedModel || !mpcs.lotNo){
        toast.warning('Please select Model and Lot number first');
        return;
    }

    try{

        const response = await axios.post(
            '/api/initial-control-sheets/lot-all-data',
            {
                model_name: mpcs.selectedModel,
                lot_no: mpcs.lotNo,
            }
        );

        const sheet = response.data?.data;

        if (!sheet) {
            toast.warning('The selected Model does not exist in the selected Lot No');
            return;
        }

        const layer = sheet.layer_data;   // array of objects
        const total = sheet.total_boxes;

        // 10 or less → normal
        if (total === 10) {
            mpcs.moreThanTenBoxes = false;
            loadNormalBoxData(layer);

            if (mpcs.selectedLayer === '9.5' && mpcs.nineHalfSet) {
                const allowedBoxes = mpcs.nineHalfSet === 'SET1'
                    ? ['A', 'C', 'E', 'G', 'J']
                    : ['B', 'D', 'F', 'H', 'K'];

                // Filter visibleBoxes to allowed boxes only
                visibleBoxes.value = visibleBoxes.value.filter(box => allowedBoxes.includes(box));

                // Remove values for boxes not in 9.5 set
                Object.keys(qtyValues.value).forEach(box => {
                    if (!visibleBoxes.value.includes(box)) qtyValues.value[box] = '';
                });
                Object.keys(weightValues.value).forEach(box => {
                    if (!visibleBoxes.value.includes(box)) weightValues.value[box] = '';
                });
                Object.keys(boxNoValues.value).forEach(box => {
                    if (!visibleBoxes.value.includes(box)) boxNoValues.value[box] = '';
                });
                Object.keys(htValues.value).forEach(box => {
                    if (!visibleBoxes.value.includes(box)) htValues.value[box] = '';
                });
                Object.keys(ltValues.value).forEach(box => {
                    if (!visibleBoxes.value.includes(box)) ltValues.value[box] = '';
                });

                // Adjust end box for UI
                mpcs.selectedBoxEndList = visibleBoxes.value.at(-1);
            }

            toast.info('Data loaded into table');
            grandTotalWeight.value = await computeTrueGrandTotal();
            isDataShown.value = true;
            return;
        }

        if (total < 10) {
            mpcs.moreThanTenBoxes = false;

            const getRow = (title) => layer.find(r => r.rowTitle === title)?.data || {};

            const qtyRow      = getRow("QTY (PCS):");
            const weightRow   = getRow("WT (KG):");
            const boxNoRow    = getRow("BOX No.:");

            const modelRow    = getRow("MODEL:");
            const coatingRow  = getRow("COATING:");
            const lotRow      = getRow("LT. No.:");
            const magnetRow   = getRow("Magnet prepared by:");
            const boxPrepRow  = getRow("Box prepared by:");

            // Only the keys that exist (less than 10)
            visibleBoxes.value = Object.keys(boxNoRow || {}).filter(k => boxNoRow[k] !== "" && boxNoRow[k] !== null);

            // Clear previous reactive maps
            qtyValues.value = {};
            weightValues.value = {};
            boxNoValues.value = {};
            htValues.value = {};
            ltValues.value = {};

            // Populate values
            visibleBoxes.value.forEach(box => {
                qtyValues.value[box]    = qtyRow[box] || '';
                weightValues.value[box] = weightRow[box] || '';
                boxNoValues.value[box]  = boxNoRow[box] || '';
                htValues.value[box]     = '';
                ltValues.value[box]     = '';
            });

            // Top-level MPCS
            const firstBox = visibleBoxes.value[0];
            mpcs.selectedModel       = modelRow[firstBox] || '';
            mpcs.coatingMCNo         = coatingRow[firstBox] || '';
            mpcs.lotNo               = lotRow[firstBox] || '';
            mpcs.coating             = coatingRow[firstBox] || 0;
            mpcs.magnetPreparedBy    = magnetRow[firstBox] || '';
            mpcs.boxPreparedBy       = boxPrepRow[firstBox] || '';

            mpcs.selectedBoxEndList = visibleBoxes.value[visibleBoxes.value.length - 1];

            toast.info('Less than 10 boxes, data loaded into table');
            grandTotalWeight.value = await computeTrueGrandTotal();
            isDataShown.value = true;
            return;
        }

        // ==========================
        // MORE THAN 10 CASE
        // ==========================
        mpcs.moreThanTenBoxes = true;

        // --- 1. Extract MAIN row data ---
        const getRow = (title) =>
            layer.find(r => r.rowTitle === title)?.data || {};

        const qtyRow        = getRow("QTY (PCS):");
        const weightRow     = getRow("WT (KG):");
        const boxNoRow      = getRow("BOX No.:");
        const modelRow      = getRow("MODEL:");
        const coatingRow    = getRow("COATING:");
        const lotRow        = getRow("LT. No.:");
        const magnetRow     = getRow("Magnet prepared by:");
        const boxPrepRow    = getRow("Box prepared by:");
        const rawMatRow     = getRow("RAW MATERIAL CODE:");

        console.log("Main Rows Extracted:", { qtyRow, weightRow, boxNoRow });

        // --- 2. Extract EXCESS row data (if exists) ---
        let excessQtyRow = {};
        let excessWeightRow = {};
        let excessBoxNoRow = {};

        if (sheet.excess_data && Array.isArray(sheet.excess_data) && sheet.excess_data.length) {
            const getExcessRow = (title) =>
                sheet.excess_data.find(r => r.rowTitle === title)?.data || {};

            excessQtyRow       = getExcessRow("QTY (PCS):");
            excessWeightRow    = getExcessRow("WT (KG):");
            excessBoxNoRow     = getExcessRow("BOX No.:");

            console.log("Excess Rows Extracted:", { excessQtyRow, excessWeightRow, excessBoxNoRow });
        } else {
            console.log("No excess data found in sheet.excess_data");
        }

        // --- 3. Determine main/excess box keys ---
        const allFoundMain = Object.keys(boxNoRow || {});
        const allFoundExcess = Object.keys(excessBoxNoRow || {});

        console.log("All Main Boxes:", allFoundMain);
        console.log("All Excess Boxes:", allFoundExcess);

        visibleBoxes.value = allFoundMain.slice(0, 10);
        visibleExcessBoxes.value =
            allFoundExcess.length ? allFoundExcess : allFoundMain.slice(10);

        console.log("Visible Main Boxes (first 10):", visibleBoxes.value);
        console.log("Visible Excess Boxes:", visibleExcessBoxes.value);

        // --- 4. Clear reactive maps ---
        qtyValues.value = {};
        weightValues.value = {};
        boxNoValues.value = {};
        qtyValuesExcess.value = {};
        weightValuesExcess.value = {};
        boxNoValuesExcess.value = {};

        // --- 5. Populate MAIN box values ---
        visibleBoxes.value.forEach(box => {
            qtyValues.value[box]       = qtyRow[box] || '';
            weightValues.value[box]    = weightRow[box] || '';
            boxNoValues.value[box]     = boxNoRow[box] || '';
        });

        console.log("Populated MAIN box values:", {
            qtyValues: qtyValues.value,
            weightValues: weightValues.value,
            boxNoValues: boxNoValues.value
        });

        // --- 6. Populate EXCESS box values ---
        visibleExcessBoxes.value.forEach(box => {
            qtyValuesExcess.value[box]    = excessQtyRow[box] || '';
            weightValuesExcess.value[box] = excessWeightRow[box] || '';
            boxNoValuesExcess.value[box]  = excessBoxNoRow[box] || '';
        });

        console.log("Populated EXCESS box values:", {
            qtyValuesExcess: qtyValuesExcess.value,
            weightValuesExcess: weightValuesExcess.value,
            boxNoValuesExcess: boxNoValuesExcess.value
        });

        if (mpcs.moreThanTenBoxes && visibleExcessBoxes.value.length) {
            const excessData = getExcessDataRange(visibleExcessBoxes.value); // e.g., "A-E"
            console.log('Excess Data Range for API call:', excessData);
            await fetchAvailableLayersForExcessList(excessData);
        }

        // --- 7. Assign top-level MPCS values ---
        const firstBox = visibleBoxes.value[0];

        mpcs.selectedModel        = modelRow[firstBox] || '';
        mpcs.coatingMCNo          = coatingRow[firstBox] || '';
        mpcs.lotNo                = lotRow[firstBox] || '';
        mpcs.coating              = coatingRow[firstBox] || 0;
        mpcs.magnetPreparedBy     = magnetRow[firstBox] || '';
        mpcs.boxPreparedBy        = boxPrepRow[firstBox] || '';
        mpcs.rawMaterialCode      = rawMatRow[firstBox] || '';

        console.log("Assigned top-level MPCS values:", {
            selectedModel: mpcs.selectedModel,
            coatingMCNo: mpcs.coatingMCNo,
            lotNo: mpcs.lotNo,
            coating: mpcs.coating,
            magnetPreparedBy: mpcs.magnetPreparedBy,
            boxPreparedBy: mpcs.boxPreparedBy,
            rawMaterialCode: mpcs.rawMaterialCode
        });

        // --- 8. UI end letters ---
        mpcs.selectedBoxEndList = visibleBoxes.value.at(-1);
        mpcs.selectedExcessBoxEndList = visibleExcessBoxes.value.at(-1) || null;

        console.log("Selected Box End List:", mpcs.selectedBoxEndList);
        console.log("Selected Excess Box End List:", mpcs.selectedExcessBoxEndList);

        toast.info("Loaded more than 10 boxes data");
        grandTotalWeight.value = await computeTrueGrandTotal();
        isDataShown.value = true;



    } catch(error) {
        toast.error('Failed to fetch initial process data via lot number and model');
        console.log(error);
    }
};

//Watch for changes in Model Lists
watch(
    () => mpcs.lotNo,
    async (newVal) => {
        if (!newVal) return;
        await getModelLists();
        console.log("Lot No: ",mpcs.lotNo);
        console.log("Model: ",mpcs.selectedModel);
    }
);

watch(
    () => [mpcs.selectedModel, mpcs.lotNo],
    async ([model, lot]) => {
        if (model && lot) {
            await getTotalBoxes();
        }
    }
);

// Watch for changes in selectedLayer
watch(
    [
        () => mpcs.selectedFurnace,
        () => mpcs.selectedMassProd,
        () => mpcs.selectedLayer
    ],
    async ([newFurnace, newMassProd, newLayer]) => {
        if (!newFurnace || !newMassProd || !newLayer) {
            isExisting.value = false;
            isExisting_2ndGBDP.value = false;
            isExistingExcess.value = false;
            return;
        }

        await fetchExistingLayers(); // this function already sets all flags
    }
);

watch(
    [() => mpcs.selectedMassProd, () => activate2ndGBDP.value],
    async ([newMassProd, isSecondGBDP]) => {
        if (!newMassProd) {
            completedLayers.value = [];
            return;
        }

        if (isSecondGBDP) {
            await getCompletedLayers(); // only called if activate2ndGBDP is true
        } else {
            completedLayers.value = []; // or call another function if needed
        }
    }
);

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE END

const loadNormalBoxData = (layerData) => {

    const getRow = (title) => layerData.find(r => r.rowTitle === title)?.data || {};

    const model       = getRow("MODEL:");
    const coatingMC   = getRow("COATING M/C No.:");
    const lotNumber   = getRow("LT. No.:");
    const qtyRow      = getRow("QTY (PCS):");
    const coatingRow  = getRow("COATING:");
    const weightRow   = getRow("WT (KG):");
    const boxNoRow    = getRow("BOX No.:");
    const magnetRow   = getRow("Magnet prepared by:");
    const boxPrepRow  = getRow("Box prepared by:");

    // --- 1. Determine visible boxes ---
    let allBoxes = Object.keys(boxNoRow).filter(key => boxNoRow[key] !== '' && boxNoRow[key] !== null);

    // --- 2. Apply 9.5 layer filter if needed ---
    if (isLayerNinePointFive.value && mpcs.nineHalfSet) {
        const set1 = ['A','C','E','G','J'];
        const set2 = ['B','D','F','H','K'];
        allBoxes = mpcs.nineHalfSet === 'SET1' ? set1 : set2;
    }

    visibleBoxes.value = allBoxes;

    // --- 3. Assign top-level MPCS fields ---
    const firstBox = visibleBoxes.value[0];
    mpcs.selectedModel      = model[firstBox] || "";
    mpcs.coatingMCNo        = coatingMC[firstBox] || "";
    mpcs.lotNo              = lotNumber[firstBox] || "";
    mpcs.coating            = coatingRow[firstBox] || 0;
    mpcs.magnetPreparedBy   = magnetRow[firstBox] || "";
    mpcs.boxPreparedBy      = boxPrepRow[firstBox] || "";
    mpcs.selectedBoxEndList = visibleBoxes.value[visibleBoxes.value.length - 1] || firstBox;

    // --- 4. Populate reactive maps for each visible box ---
    visibleBoxes.value.forEach(box => {
        qtyValues.value[box]    = qtyRow[box] || "";
        weightValues.value[box] = weightRow[box] || "";
        boxNoValues.value[box]  = boxNoRow[box] || "";
    });

    // --- 5. Set first and last quantities for UI/logic ---
    const lastBox = visibleBoxes.value[visibleBoxes.value.length - 1];
    mpcs.qty      = qtyRow[firstBox] || 0;
    mpcs.qty_lastBox = qtyRow[lastBox] || 0;
};


const applyHTLT = () => {
    showHTLTPanel.value = true;
}

const hoursLeft = ref(0); // reactive variable

const checkExpiration = async () => {
    try {
        const response = await axios.get(`/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}`);
        const massprod_data = response.data;

        const dateBaseLine = new Date(massprod_data.created_at);
        const now = new Date();

        const diffMs = now.getTime() - dateBaseLine.getTime();
        const hoursPassed = diffMs / 1000 / 60 / 60;

        const expired = hoursPassed >= 24;
        isEditingExpired.value = expired;

        hoursLeft.value = expired ? 0 : 24 - hoursPassed;
        // console.log(`Hours left: ${hoursLeft.value.toFixed(2)}`);
    } catch (error) {
        console.error('Could not fetch real time expiration tracking', error);
    }
};

// Computed for display in h/m format
const hoursLeftDisplay = computed(() => {
    const totalMinutes = Math.floor(hoursLeft.value * 60);
    const h = Math.floor(totalMinutes / 60);
    const m = totalMinutes % 60;
    return `${h}h ${m}m`;
});

const updateHeatTreatmentInfo = async() => {
    checkExpiration();
    heatTreatmentInformationDetected.value = false;
    overwriteMode.value = true;
    try{
        const response = await axios.get(`/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}`);
        const massprod_data = response.data;
        console.log('Mass Production Data:', response.data);

        hti.cycleNo = massprod_data.cycle_no;
        hti.patternNo = massprod_data.pattern_no;
        hti.cyclePattern = massprod_data.cycle_pattern;
        hti.currentPattern = massprod_data.current_pattern;
        hti.dateStart = massprod_data.date_start;
        hti.dateFinish = massprod_data.date_finished;
        hti.timeStart = massprod_data.time_start;
        hti.timeFinish = massprod_data.time_finished;
        hti.loader = massprod_data.loader;
        hti.unloader = massprod_data.unloader;
        hti.partialNo = massprod_data.partial_no;
        hti.boxCondition = massprod_data.box_condition;
        hti.boxCover = massprod_data.box_cover;
        hti.boxArrangement = massprod_data.box_arrangement;
        hti.encodedBy = massprod_data.encoded_by;
        hti.remarks1 = massprod_data.remarks1;
        hti.remarks2 = massprod_data.remarks2;
        hti.remarks3 = massprod_data.remarks3;
    }catch(error){
        console.error('failed to fetch mass prod data', error);
        toast.error('Failed to fetch Mass Prod data', error);
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "updateHeatTreatmentInfo",
            "Failed to fetch Mass Prod data"
        );
    }

}

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

    // Reset boxNoValues
    Object.keys(ltValues.value).forEach(key => {
        ltValues.value[key] = '';
    });

    // Reset weightValues
    Object.keys(htValues.value).forEach(key => {
        htValues.value[key] = '';
    });

    mpcs.selectedBoxEndList = 'K';
    mpcs.selectedModel = '';
    initialFurnaceData.value = null;
}

const clearAllAfterSave = () => {
    // Reset all hti fields
    Object.keys(hti).forEach(key => {
        hti[key] = typeof hti[key] === 'number' ? 0 : '';
    });

    const preserveKeys = [
        'selectedFurnace',
        'selectedMassProd',
        'selectedLayer',
        'selectedModel',
        'lotNo'
    ];

    Object.keys(mpcs).forEach(key => {
        if (!preserveKeys.includes(key)) {
        mpcs[key] = typeof mpcs[key] === 'number' ? 0 : '';
        }
    });

    // Reset boxNoValues
    Object.keys(boxNoValues.value).forEach(key => {
        boxNoValues.value[key] = '';
    });

    // Reset weightValues
    Object.keys(weightValues.value).forEach(key => {
        weightValues.value[key] = '';
    });

    // Reset boxNoValues
    Object.keys(ltValues.value).forEach(key => {
        ltValues.value[key] = '';
    });

    // Reset weightValues
    Object.keys(htValues.value).forEach(key => {
        htValues.value[key] = '';
    });


    initialFurnaceData.value = mpcs.selectedFurnace;
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
            !mpcs.selectedMassProd || !mpcs.selectedLayer ||
            !mpcs.selectedModel || !mpcs.lotNo
        ) {
            toast.error("Please fill in all required Control Sheet fields.");
            return;
        }

        if(mpcs.moreThanTenBoxes){
            if(!mpcs.selectedExcessLayer){
                toast.error("Please fill in Excess Layer.");
                return;
            }
        }

        // validate HTI fields only if not detected already
        if (!heatTreatmentInformationDetected.value) {
            if (
                !initialFurnaceData.value || !hti.cycleNo || !hti.patternNo || !hti.dateStart ||
                !hti.timeStart || !hti.loader || !hti.boxCondition || !hti.boxCover ||
                !hti.boxArrangement || !hti.encodedBy
            ) {
                toast.error("Please fill in all required Heat Treatment Info fields.");
                return;
            }
        }

        if (isLayerNinePointFive.value && !mpcs.nineHalfSet) {
            toast.error('Please select which half of 9.5 layer to use.');
            return;
        }
    }

    // Determine which boxes to validate
    let boxesToValidate = visibleBoxes.value;

    if (isLayerNinePointFive.value && mpcs.nineHalfSet) {
        if (mpcs.nineHalfSet === 'SET1') {
            boxesToValidate = visibleBoxes.value.filter(b => ['A','C','E','G','J'].includes(b));
        } else if (mpcs.nineHalfSet === 'SET2') {
            boxesToValidate = visibleBoxes.value.filter(b => ['B','D','F','H','K'].includes(b));
        }
    }

    // new per-box check
    for (const box of boxesToValidate) {
        if (!bypassValidation.value && (!boxNoValues.value[box] || !weightValues.value[box] || weightValues.value[box] <= 0)) {
            toast.error(`Please fill in box number and weight for box ${box}.`);
            return;
        }
    }
    showModalCreate.value = true;
}

const saveToDatabase = async () => {
    // Determine layer key
    const layerKey = mpcs.selectedLayer === '9.5' ? 'layer_9_5' : `layer_${mpcs.selectedLayer}`;

    // --- 1️⃣ Determine which boxes to save ---
    let mainBoxes = [...visibleBoxes.value];

    // Handle 9.5 layer: only save the selected half
    if (mpcs.selectedLayer === '9.5' && mpcs.nineHalfSet) {
        const set1 = ['A','C','E','G','J'];
        const set2 = ['B','D','F','H','K'];
        mainBoxes = mpcs.nineHalfSet === 'SET1' ? set1 : set2;

        // Filter only boxes that exist in visibleBoxes
        mainBoxes = mainBoxes.filter(box => visibleBoxes.value.includes(box));
    }

    // --- 2️⃣ Prepare main layer payload ---
    const mainLayerPayload = formatLayerDataForDatabase(mainBoxes, false);

    // --- 3️⃣ Base payload for mass production ---
    const dataPayload = {
        mass_prod: mpcs.selectedMassProd,
        furnace: initialFurnaceData.value,
        [layerKey]: JSON.stringify(mainLayerPayload),
    };

    // Include heat treatment info if not already detected
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
            partial_no: hti.partialNo,
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

    try {
        const response = await axios.patch(`/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}`, dataPayload);
        console.log('Main layer saved:', response.data);

        if (mpcs.moreThanTenBoxes && visibleExcessBoxes.value.length) {
            const excessLayerPayload = formatLayerDataForDatabase(visibleExcessBoxes.value, true);
            const excessPayload = {
                furnace: mpcs.selectedFurnace,
                mass_prod: mpcs.selectedMassProd,
                layer: mpcs.selectedExcessLayer,
                layer_data: excessLayerPayload,
            };
            const saveExcess = await axios.post('/api/excess-layers/merge', excessPayload);
            console.log('Excess layer saved:', saveExcess.data);
        }

        if(!heatTreatmentInformationDetected.value){
            await uploadGraphs();
        }
        toast.success('Data saved successfully!');
        changeData();

        const logMsg = !heatTreatmentInformationDetected.value
            ? `created ${mpcs.selectedMassProd} Heat Treatment Info | Control Sheet Layer: ${mpcs.selectedLayer}`
            : `created ${mpcs.selectedMassProd} Control Sheet Layer: ${mpcs.selectedLayer}`;
        await userManageLogging(logMsg);

        showModalCreate.value = false;

    } catch (error) {
        console.error('Error saving data:', error);
        toast.error('Failed to save data. Please try again.');
        await userErrorLogging({
            message: error.message,
            code: error.code ?? null,
            response: error.response?.data ?? null,
            payload: error.response?.data ?? null,
        }, "saveToDatabase", "Failed to save data. Please try again.");
    } finally {
        await updateFormatType();
        clearAllAfterSave();
    }
};

const formatLayerDataForDatabase = (boxes, isExcess = false) => {
    if (!boxes || !boxes.length) return [];

    const qtyMap    = isExcess ? qtyValuesExcess.value    : qtyValues.value;
    const weightMap = isExcess ? weightValuesExcess.value : weightValues.value;
    const boxNoMap  = isExcess ? boxNoValuesExcess.value  : boxNoValues.value;
    const htMap     = htValues.value;
    const ltMap     = ltValues.value;

    const totalQty = boxes.reduce((acc, box) => acc + Number(qtyMap[box] || 0), 0);

    return [
        { rowTitle: 'MODEL:', data: Object.fromEntries(boxes.map(box => [box, mpcs.selectedModel || ''])) },
        { rowTitle: 'COATING M/C No.:', data: Object.fromEntries(boxes.map(box => [box, mpcs.coatingMCNo || ''])) },
        { rowTitle: 'LT. No.:', data: Object.fromEntries(boxes.map(box => [box, mpcs.lotNo || ''])) },
        { rowTitle: 'QTY (PCS):', data: Object.fromEntries(boxes.map(box => [box, qtyMap[box] || 0])) },
        { rowTitle: 'HT (PCS):', data: Object.fromEntries(boxes.map(box => [box, htMap[box] || 0])) },
        { rowTitle: 'LT (PCS):', data: Object.fromEntries(boxes.map(box => [box, ltMap[box] || 0])) },
        { rowTitle: 'COATING:', data: Object.fromEntries(boxes.map(box => [box, mpcs.coating || 0])) },
        { rowTitle: 'WT (KG):', data: Object.fromEntries(boxes.map(box => [box, weightMap[box] || 0])) },
        { rowTitle: 'BOX No.:', data: Object.fromEntries(boxes.map(box => [box, boxNoMap[box] || ''])) },
        { rowTitle: 'Magnet prepared by:', data: Object.fromEntries(boxes.map(box => [box, mpcs.magnetPreparedBy || ''])) },
        { rowTitle: 'Box prepared by:', data: Object.fromEntries(boxes.map(box => [box, mpcs.boxPreparedBy || ''])) },
        { rowTitle: 'RAW MATERIAL CODE:', data: Object.fromEntries(boxes.map(box => [box, mpcs.rawMaterialCode || ''])) },
        { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxes.map(box => [box, totalQty])) }
    ];
};

const cancelOverwrite = () => {
    overwriteMode.value = false;
    heatTreatmentInformationDetected.value = false;
    clearAllAfterSave();
}

const overwriteDatabase = async () => {
    // Base payload
    const dataPayload = {
        mass_prod: mpcs.selectedMassProd,
        furnace: initialFurnaceData.value,
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
            partial_no: hti.partialNo,
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
        const response = await axios.patch(`/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}`, dataPayload);
        await uploadGraphs();
        console.log('Data overwritten successfully:', response.data);
        toast.success('Data overwritten successfully!');
        if(!heatTreatmentInformationDetected.value){
            await userManageLogging('overwritten ' + mpcs.selectedMassProd +' Heat Treatment Info successfully | Control Sheet Layer: ' + mpcs.selectedLayer);
        }else{
            await userManageLogging('overwritten '+ mpcs.selectedMassProd +' Control Sheet Layer: ' + mpcs.selectedLayer + ' successfully.');
        }
        showModalCreate.value = false;
    } catch (error) {
        console.error('Error saving data:', error);
        toast.error('Failed to overwrite data. Please try again.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "overwriteDatabase",
            "Failed to overwrite data. Please try again."
        );
    } finally {
        //await updateFormatType();
        clearAll(); // Clear all fields after successful save
        overwriteMode.value = false;
        heatTreatmentInformationDetected.value = true;
    }
};

const showBreakLotForm = () => {
    if(!mpcs.selectedFurnace || !mpcs.selectedMassProd){
        toast.warning('Please select Furnace and Mass Production');
        return;
    }

    Inertia.visit('/ht_breaklots', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { furnace: mpcs.selectedFurnace, massprod: mpcs.selectedMassProd },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
}

const updateFormatType = async () => { // Update format type of Mass Productions Table
    const layerKey = mpcs.selectedLayer === '9.5' ? 'layer_9_5_format_type' : `layer_${mpcs.selectedLayer}_format_type`;

    const dataPayload = {
        furnace: mpcs.selectedFurnace,
        mass_prod: mpcs.selectedMassProd,
        [layerKey]: 'Normal',
    }

    try{
        const responseUpdate = await axios.patch(`/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}`, dataPayload);
        console.log('Response Update: ', responseUpdate.data);
    }catch(error){
        console.log('Failed to update format type');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "updateFormatType",
            "Failed to update format type"
        );
    }
}

const uploadGraphs = async () => {
    if (!mpcs.selectedMassProd) return;

    const formData = new FormData();

    if (cycleGraphFile.value) {
        formData.append('cycle_graph', cycleGraphFile.value);
    }
    if (actualGraphFile.value) {
        formData.append('actual_graph', actualGraphFile.value);
    }

    // Pass patternNo so backend fetches standard graph
    formData.append('pattern_no', hti.patternNo);

    try {
        const response = await axios.post(
            `/api/mass-production/${mpcs.selectedFurnace}/${mpcs.selectedMassProd}/upload-graphs`,
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
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "uploadGraphs",
            "Failed to upload graphs. Please try again."
        );
    }
};

const second_heat_treatment = () => {
    Inertia.visit('/second_heat_treatment', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { furnace: mpcs.selectedFurnace, massProd: mpcs.selectedMassProd, layer: mpcs.selectedLayer },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};

const deleteLayerData = async () => {
    try {
        const response = await axios.post('/api/mass-production/delete-layer-data', {
            massprod: mpcs.selectedMassProd,
            furnace: mpcs.selectedFurnace,
            layer: mpcs.selectedLayer
        });

        if (response.data.success) {
            toast.success(`Layer ${mpcs.selectedLayer} data deleted successfully.`);
            await fetchExistingLayers();
        } else {
            toast.warning('No matching data found.');
        }
    } catch (error) {
        console.error('Failed to delete layer data', error);
        toast.error('Failed to delete layer data.');
    }
};

// APPLYING Browser Session ----------------- APPLYING Browser Session

useSessionStorage('bypassValidation',bypassValidation);
//useSessionStorage('showModalCreate',showModalCreate);
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
    await getFurnaceLists();
    await getMassProdLists();
    await fetchAllLotNoData();
    await getGraphPatterns();
    await get1st2ndGBDPModels();

    // Initial check
    checkExpiration();

    // Update every minute (60000ms) for real-time display
    intervalId = setInterval(() => {
        checkExpiration();
    }, 60000);
});

onUnmounted(() => {
    clearInterval(intervalId);
});

</script>

<style scoped>
input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

@keyframes gradient-flow {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
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

.animate-gradient-flow {
  animation: gradient-flow 4s ease infinite;
}

.animate-spin-slow {
  animation: spin-slow 8s linear infinite;
}

</style>
