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
            <div v-if="!coatingInfo.selectedMassProd" class="mb-6">
                <div class="flex items-center p-4 space-x-3 border rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-200">
                <!-- Info Icon -->
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Message -->
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">
                        Please select a Mass Production Name to begin.
                    </p>
                    <p class="mt-1 text-xs text-gray-600">
                        Choose from the dropdown above to view layer status information.
                    </p>
                </div>
                </div>
            </div>
            <div v-else class="mb-6">
                <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">

                    <!-- Header -->
                    <div class="px-4 py-3 bg-gradient-to-r from-teal-600 to-cyan-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-sm font-semibold text-white">
                            Layer Status Preview ( {{ coatingInfo.selectedMassProd }} Mass Production )
                        </h3>
                    </div>
                    </div>

                    <!-- Content with Layer Boxes -->
                    <div class="px-6 py-5">

                    <!-- Title and Legend -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-800">
                            Processing Layers (1 - 9.5)
                        </span>
                        <div class="flex items-center space-x-3 text-xs">
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-gray-600">Complete</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                <span class="text-gray-600">Pending</span>
                            </div>
                        </div>
                    </div>

                    <!-- Single Row of Layer Boxes -->
                    <div class="flex flex-wrap justify-center gap-2">
                        <div
                        v-for="layer in layers"
                        :key="layer"
                        class="relative group"
                        >
                        <!-- Layer Box -->
                        <div
                            class="flex flex-col items-center justify-center transition-all duration-200 border-2 rounded-lg h-14 w-14 hover:scale-105"
                            :class="completedLayers.includes(layer)
                            ? 'bg-emerald-50 border-emerald-400 shadow-sm'
                            : 'bg-gray-50 border-gray-300 hover:border-gray-400'"
                        >
                            <!-- Status Icon -->
                            <div class="mb-0.5">
                            <svg
                                v-if="completedLayers.includes(layer)"
                                class="w-3 h-3 text-emerald-600"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <div
                                v-else
                                class="w-3 h-3 border-2 border-gray-400 rounded-full"
                            ></div>
                            </div>

                            <!-- Layer Number -->
                            <span
                            class="text-xs font-semibold"
                            :class="completedLayers.includes(layer)
                                ? 'text-emerald-700'
                                : 'text-gray-500'"
                            >
                            {{ layer }}
                            </span>
                        </div>

                        <!-- Simple Hover Tooltip -->
                        <div class="absolute z-10 mb-2 transition-opacity duration-200 transform -translate-x-1/2 opacity-0 pointer-events-none bottom-full left-1/2 group-hover:opacity-100">
                            <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap">
                            Layer {{ layer }}
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Simple Status Text -->
                    <p class="mt-4 text-xs text-center text-gray-500">
                        Current layer status for selected mass production
                    </p>

                    </div>
                </div>
            </div>
            <div v-if="activate2ndGBDP" class="p-4 mt-6 border rounded-lg shadow-sm bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-200">
                <div class="flex items-start space-x-3">
                    <!-- Info Icon -->
                    <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    </div>

                    <!-- Message Content -->
                    <div class="flex-1">
                    <h4 class="mb-1 text-sm font-semibold text-gray-800">
                        Model Detected: {{ fetchedModelValue }} (1st & 2nd GBDP Format)
                    </h4>
                    <p class="text-sm leading-relaxed text-gray-700">
                        The selected layer in the current mass production contains model that follows the <span class="font-semibold text-cyan-600">1st and 2nd GBDP format</span>.
                    </p>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 border border-teal-200">
                            Active
                        </span>
                    </div>
                </div>
            </div>
            <div v-if="activate2ndGBDP" class="w-full max-w-4xl mt-12">
                <div class="px-8 py-6 border border-gray-200 shadow-lg bg-gradient-to-br from-gray-50 to-white rounded-2xl">
                    <!-- Header -->
                    <h2 class="pb-3 mb-5 text-xl font-semibold tracking-wide text-gray-800 border-b border-gray-200">
                        Options
                    </h2>

                    <!-- Body -->
                    <div class="flex items-center space-x-4">
                    <!-- Icon -->
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-full shadow-inner">
                        <svg
                            class="w-6 h-6 text-teal-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"
                            />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <p class="mb-4 text-sm leading-relaxed text-gray-600">
                        This button allows data to be fetched automatically for
                        <span class="font-semibold text-gray-800">1st GBDP</span>. Use this
                        option when you want the system <br> to retrieve relevant information
                        without manual input.
                        </p>
                        <button
                            @click="autoFetch"
                            class="px-5 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-300 rounded-lg shadow-md bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-400"
                        >
                            Auto Fetch
                        </button>
                        <input v-model="selectedMassProd_fetch" type="text" @input="selectedMassProd_fetch = selectedMassProd_fetch.toUpperCase()" placeholder="Enter Mass Production name..." class="ml-5 w-[12rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        <input v-model="selectedLayer_fetch" type="number" placeholder="Enter Layer number..." class="ml-5 w-[9rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-10 mt-10">
                <div v-if="activate2ndGBDP" class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">1st GBDP Coating Information</h2>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Date<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.coatingDate" type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Machine No<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.coatingMachineNo" @input="coatingInfo_1stgbdp.coatingMachineNo = coatingInfo_1stgbdp.coatingMachineNo.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Slurry Lot No<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.slurryLotNo" @input="coatingInfo_1stgbdp.slurryLotNo = coatingInfo_1stgbdp.slurryLotNo.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">MIN TB CONTENT (μg/mm²)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.minTbContent" @input="coatingInfo_1stgbdp.minTbContent = coatingInfo_1stgbdp.minTbContent.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="lotNo_1stGBDP" @input="lotNo_1stGBDP = lotNo_1stGBDP.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight (KG)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo_1stgbdp.totalMagnetWeight" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
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
                            @input="coatingInfo_1stgbdp.remarks = coatingInfo_1stgbdp.remarks.toUpperCase()"
                            type="text"
                            class="w-[36rem] text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                            placeholder="Enter remarks here..."
                            />
                        </div>
                    </div>
                </div>

                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 v-if="!activate2ndGBDP" class="pb-1 font-bold text-gray-800 border-b text-md">Coating Information</h2>
                    <h2 v-else class="pb-1 font-bold text-gray-800 border-b text-md">2nd GBDP Coating Information</h2>
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
                            <select v-model="coatingInfo.selectedLayer" class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50">
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
                        <div v-if="!activate2ndGBDP">
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sample Quantity (pcs)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo.sampleQuantity" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div v-else>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Lot no<span class="text-red-500"> *</span></label>
                            <input v-model="lotNo" @input="lotNo = lotNo.toUpperCase()" type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight (KG)<span class="text-red-500"> *</span></label>
                            <input v-model="coatingInfo.totalMagnetWeight" type="number" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div v-if="!activate2ndGBDP" class="grid grid-cols-1 gap-6 md:grid-cols-3">
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
                    <div v-if="!activate2ndGBDP" class="grid grid-cols-1 gap-6 md:grid-cols-3">
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

                <div v-if="!activate2ndGBDP" class="px-4 py-4 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl md:px-8">
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
            <div v-if="activate2ndGBDP" class="flex flex-row gap-10 mt-10 items">
                <div class="px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl max-w-8xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">1st GBDPCoating Data (Unit: µ/mm²)</h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">
                        <div class="flex flex-row gap-4">
                            <div v-for="(slice, colIndex) in Math.ceil(coatingsTable_1stgbdp.length / 10)" :key="colIndex" class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 text-left border-r border-gray-300">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr
                                            v-for="(item) in coatingsTable_1stgbdp.slice(colIndex * 10, (colIndex + 1) * 10)"
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
                                <tr v-for="(range, rowIndex) in visibleRanges_1stGBDP" :key="range" class="hover:bg-gray-50">
                                <td class="px-2 py-1 border-b border-r border-gray-200">{{ range }}</td>
                                <td
                                    v-for="(module, colIndex) in modules"
                                    :key="colIndex"
                                    class="px-2 py-1 border-b border-r border-gray-200"
                                >
                                    <input
                                    type="number"
                                    v-model="visibleConcentrationData_1stGBDP[rowIndex][colIndex]"
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
            <div class="flex flex-row gap-10 mt-10 items">
                <div v-if="!activate2ndGBDP" class="px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl max-w-8xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Data (Unit: µ/mm²)</h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">
                        <div class="flex flex-row gap-4">
                            <div v-for="(slice, colIndex) in Math.ceil(visibleCoatings.length / 10)" :key="colIndex" class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 text-left border-r border-gray-300">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr
                                            v-for="(item) in visibleCoatings.slice(colIndex * 10, (colIndex + 1) * 10)"
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

                <div v-else class="px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl max-w-8xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">2nd GBDP Coating Data (Unit: µ/mm²)</h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">
                        <div class="flex flex-row gap-4">
                            <div v-for="(slice, colIndex) in Math.ceil(visibleCoatings_2ndGBDP.length / 10)" :key="colIndex" class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 text-left border-r border-gray-300">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr
                                            v-for="(item) in visibleCoatings_2ndGBDP.slice(colIndex * 10, (colIndex + 1) * 10)"
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
                                <tr v-for="(range, rowIndex) in visibleRanges_2ndGBDP" :key="range" class="hover:bg-gray-50">
                                <td class="px-2 py-1 border-b border-r border-gray-200">{{ range }}</td>
                                <td
                                    v-for="(module, colIndex) in modules"
                                    :key="colIndex"
                                    class="px-2 py-1 border-b border-r border-gray-200"
                                >
                                    <input
                                    type="number"
                                    v-model="visibleConcentrationData_2ndGBDP[rowIndex][colIndex]"
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
            <div class="flex flex-row mt-10 gap-14">
                <div v-if="activate2ndGBDP" class="px-4 py-4 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl md:px-8">
                    <p class="font-semibold text-center">1ST GBDP</p>
                    <!-- Stats: Average / Max / Min -->
                    <div class="flex flex-row gap-20">
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Average</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingAverage_1stgbdp != null ? Number(coatingAverage_1stgbdp).toFixed(2) : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Maximum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMaximum_1stgbdp !== null ? coatingMaximum_1stgbdp : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Minimum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMinimum_1stgbdp !== null ? coatingMinimum_1stgbdp : '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl md:px-8">
                    <p v-if="activate2ndGBDP" class="font-semibold text-center">2ND GBDP</p>
                    <!-- Stats: Average / Max / Min -->
                    <div class="flex flex-row gap-20">
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Average</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingAverage_2ndgbdp != null ? Number(coatingAverage_2ndgbdp).toFixed(2) : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Maximum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMaximum_2ndgbdp !== null ? coatingMaximum_2ndgbdp : '-' }}</div>
                        </div>
                        <div class="flex-1 px-4 py-3 text-center border border-gray-300 rounded-lg shadow-inner bg-gray-50">
                            <div class="text-xs font-medium text-gray-500">Minimum</div>
                            <div class="text-sm font-semibold text-gray-800">{{ coatingMinimum_2ndgbdp !== null ? coatingMinimum_2ndgbdp : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DIV BELOW (BOTTOM PART) -->
            <!-- LAST BOTTOM DIV -->
            <div class="flex flex-row gap-20 mt-10">
                <div class="bg-gradient-to-br from-black via-gray-900 to-gray-800 border border-cyan-600 shadow-[0_10px_25px_rgba(99,102,241,0.4),0_4px_6px_rgba(0,0,0,0.4)] rounded-3xl px-6 py-6 flex items-center gap-16 ring-1 ring-teal-400 backdrop-blur-md whitespace-nowrap">
                    <!-- Glowing Spinning Cogwheel -->
                    <div class="relative flex-shrink-0 w-12 h-12">
                        <div class="absolute inset-0 rounded-full opacity-100 bg-cyan-400 blur-xl animate-pulse"></div>
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
                            v-if="!activate2ndGBDP"
                            @click="finalize"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-cyan-500 to-teal-600 hover:from-cyan-600 hover:to-teal-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-50"
                        >
                            FINALIZE
                        </button>
                        <button
                            v-else
                            @click="finalize_1st2ndGbdp"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-cyan-500 to-teal-600 hover:from-cyan-600 hover:to-teal-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-50"
                        >
                            SUBMIT
                        </button>

                        <!-- Cancel -->
                        <button
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50"
                        >
                            CANCEL
                        </button>

                        <!-- Clear All -->
                        <button
                            v-if="!activate2ndGBDP"
                            @click="clearAll()"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
                        >
                            CLEAR ALL
                        </button>
                        <!-- Clear All -->
                        <button
                            v-else
                            @click="clearAllTransition()"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
                        >
                            CLEAR ALL
                        </button>

                        <!-- Film Pasting -->
                        <button
                            @click="Inertia.visit('/film_pasting')"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-orange-400 focus:ring-opacity-50"
                        >
                            FILM PASTING FORMAT
                        </button>
                    </div>
                </div>
            </div>
            <Modal :show="showModalSubmit" @close="showModalSubmit = false">
                <!-- Modal Content - goes directly in the slot -->
                <div class="relative">

                <!-- Header with Gradient -->
                <div class="relative px-6 py-6 bg-gradient-to-r from-cyan-600 via-teal-600 to-cyan-700">
                    <!-- Geometric Pattern Background -->
                    <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="hexagon" width="12" height="12" patternUnits="userSpaceOnUse">
                                <polygon points="6,1 11,4.5 11,9.5 6,13 1,9.5 1,4.5" fill="none" stroke="currentColor" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#hexagon)" />
                    </svg>
                    </div>

                    <!-- Header Content -->
                    <div class="relative flex items-center justify-between">
                    <!-- Create Icon -->
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-25 border border-white rounded-xl backdrop-blur-sm border-opacity-40">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Close Button -->
                    <button
                        @click="showModalSubmit = false"
                        class="p-2 text-white transition-all duration-200 rounded-lg hover:text-gray-200 hover:bg-white hover:bg-opacity-20 hover:scale-110"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="px-6 py-6">

                    <!-- Title -->
                    <div class="mb-6 text-center">
                    <h3 class="flex items-center justify-center mb-2 space-x-2 text-xl font-bold text-gray-900">
                        <svg class="w-5 h-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Confirm Submission</span>
                    </h3>
                    <p class="text-sm leading-relaxed text-gray-600">
                        Are you sure? Please ensure all inputs are correct before proceeding.
                    </p>
                    </div>

                    <!-- Validation Checklist -->
                    <div class="p-4 mb-6 border rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-200">
                    <h4 class="flex items-center mb-3 space-x-2 text-sm font-semibold text-gray-800">
                        <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Validation Summary</span>
                    </h4>

                    <div class="space-y-2 text-xs text-gray-700">
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-cyan-500 rounded-full"></div>
                        <span>All required fields completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-teal-500 rounded-full"></div>
                        <span>Data validation passed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-cyan-500 rounded-full"></div>
                        <span>Ready for database insertion</span>
                        </div>
                    </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">

                    <!-- Cancel Button -->
                    <button
                        @click="showModalSubmit = false"
                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 transform hover:scale-[0.98] active:scale-95 flex items-center justify-center space-x-2"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Cancel</span>
                    </button>

                    <!-- Confirm Button with Animation -->
                    <button
                        @click="saveToDatabase_1st2ndgbdp()"
                        class="group flex-1 px-4 py-3 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-700 hover:to-teal-700 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-cyan-300 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 relative overflow-hidden"
                    >
                        <!-- Shine effect -->
                        <div class="absolute inset-0 transition-transform transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-20 group-hover:translate-x-full duration-600"></div>

                        <span class="relative flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Submit Now</span>
                        </span>
                    </button>
                    </div>
                </div>

                <!-- Bottom accent line -->
                <div class="h-1 bg-gradient-to-r from-cyan-500 via-teal-400 to-teal-500"></div>
                </div>
            </Modal>
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
                        <div v-if="!activate2ndGBDP">
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
                        <div v-if="!activate2ndGBDP">
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

                    <div v-if="!activate2ndGBDP">
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
                    <div v-else>
                        <div class="flex flex-row mt-5 text-xs whitespace-nowrap">
                            <div class="flex flex-col items-end gap-1 font-semibold">
                                <label>Coating Date: </label>
                                <label>MIN TB CONTENT: </label>
                                <label>Coating Machine No: </label>
                            </div>
                            <div class="flex flex-col gap-1 ml-5">
                                <span>{{ coatingInfo.coatingDate || 'NA' }}</span>
                                <span>{{ coatingInfo.minTbContent || 'NA' }}</span>
                                <span>{{ coatingInfo.coatingMachineNo || 'NA' }}</span>
                            </div>
                            <div class="flex flex-col items-end gap-1 pl-2 ml-2 font-semibold border-l border-gray-200">
                                <label>Slurry Lot No: </label>
                                <label>Total Magnet Weight: </label>
                            </div>
                            <div class="flex flex-col gap-1 ml-5">
                                <span>{{ coatingInfo.slurryLotNo || 'NA' }}</span>
                                <span>{{ coatingInfo.totalMagnetWeight || 'NA' }}</span>
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
const showModalSubmit = ref(false);
//Toggle Control

const massProd_names = ref([]);
const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);
const completedLayers = ref(['1','2']);
const lotNo = ref();
const lotNo_1stGBDP = ref();
const firstSecondGBDP_models = ref(['TIC0755G','DNS0A54G']);
const fetchedModelValue = ref();
const selectedMassProd_fetch = ref();
const selectedLayer_fetch = ref();
const fetchedCoatingData = ref([]);

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

const coatingInfo_1stgbdp = reactive({
    selectedMassProd: '',
    coatingDate: '',
    coatingMachineNo: '',
    slurryLotNo: '',
    minTbContent: '',
    totalMagnetWeight: '',
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

//coatingsTable[4].coating = 'Red'; // sets No. 5
// Computed for full table (respects activate2ndGBDP if needed)
const visibleCoatings = computed(() => coatingsTable.value);

// Computed for 1stGBDP table (always 1–20)
const visibleCoatings_1stGBDP = computed(() => coatingsTable_1stgbdp.value);
// Computed for 1stGBDP table (always 1–20)
const visibleCoatings_2ndGBDP = computed(() => coatingsTable_2ndgbdp.value);


// For calculations (clean numbers only)
const coatingValues = computed(() =>
  coatingsTable.value
    .map(row => row.coating)
    .filter(val => val !== null && val !== '' && !isNaN(val))
    .map(Number)
);

const coatingValues_1stgbdp = computed(() =>
  coatingsTable_1stgbdp.value
    .map(row => row.coating)
    .filter(val => val !== null && val !== '' && !isNaN(val))
    .map(Number)
);

const coatingValues_2ndgbdp = computed(() =>
  coatingsTable_2ndgbdp.value
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

const coatingMaximum_1stgbdp = computed(() =>
    coatingValues_1stgbdp.value.length ? Math.max(...coatingValues_1stgbdp.value) : null
);

const coatingMinimum_1stgbdp = computed(() =>
    coatingValues_1stgbdp.value.length ? Math.min(...coatingValues_1stgbdp.value) : null
);

const coatingAverage_1stgbdp = computed(() =>
  coatingValues_1stgbdp.value.length
    ? coatingValues_1stgbdp.value.reduce((sum, val) => sum + val, 0) / coatingValues_1stgbdp.value.length
    : null
);

const coatingMaximum_2ndgbdp = computed(() =>
    coatingValues_2ndgbdp.value.length ? Math.max(...coatingValues_2ndgbdp.value) : null
);

const coatingMinimum_2ndgbdp = computed(() =>
    coatingValues_2ndgbdp.value.length ? Math.min(...coatingValues_2ndgbdp.value) : null
);

const coatingAverage_2ndgbdp = computed(() =>
  coatingValues_2ndgbdp.value.length
    ? coatingValues_2ndgbdp.value.reduce((sum, val) => sum + val, 0) / coatingValues_2ndgbdp.value.length
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
const ranges_1stgbdp = ["1-5", "6-10", "11-15", "16-20"];
const ranges_2ndgbdp = ["1-5", "6-10", "11-15", "16-20"];
// Reactive table data
const concentrationData = ref(
  ranges.map(() => modules.map(() => null)) // 7x8 grid of nulls
);

// 1stGBDP reactive table
const concentrationData_1stGBDP = ref(
  ranges_1stgbdp.map(() => modules.map(() => null))
);
const concentrationData_2ndGBDP = ref(
  ranges_2ndgbdp.map(() => modules.map(() => null))
);
// Example: set some values
//concentrationAmount.value[0]['M-01'] = 10;   // first row, M-01 column
//concentrationAmount.value[3]['M-04'] = 7;    // fourth row, M-04 column

// Full version (all ranges 1–35)
const visibleRanges = computed(() => ranges);
const visibleConcentrationData = computed(() => concentrationData.value);

// 1stGBDP version (limited to 1–20)
const visibleRanges_1stGBDP = computed(() => ranges_1stgbdp);
const visibleConcentrationData_1stGBDP = computed(() => concentrationData_1stGBDP.value);
const visibleRanges_2ndGBDP = computed(() => ranges_2ndgbdp);
const visibleConcentrationData_2ndGBDP = computed(() => concentrationData_2ndGBDP.value);

// Coating Information Variables --------- Coating Information Variables END

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
            !coatingInfo.remarks || !lotNo.value
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

const finalize_1st2ndGbdp = async() => {
    if(!bypassValidation.value){
        if (
            !coatingInfo_1stgbdp.coatingDate ||
            !coatingInfo_1stgbdp.coatingMachineNo ||
            !coatingInfo_1stgbdp.slurryLotNo ||
            !coatingInfo_1stgbdp.minTbContent ||
            !coatingInfo_1stgbdp.totalMagnetWeight ||
            !coatingInfo_1stgbdp.remarks || !lotNo_1stGBDP.value ||
            !coatingInfo.selectedLayer || !coatingInfo.selectedMassProd ||
            !coatingInfo.coatingDate || !coatingInfo.coatingMachineNo ||
            !coatingInfo.slurryLotNo || !coatingInfo.minTbContent ||
            !coatingInfo.totalMagnetWeight || !coatingInfo.remarks || !lotNo.value
        ) {
            //console.log('Coating Table: ', coatingValues.value);
            toast.error("Please fill in all required Coating Info fields.");
            return;
        }
    }

    showModalSubmit.value = true;

    //console.log("Concentration Data: ", concentrationData.value);
    //console.log("Coatings Data: ", coatingsTable.value);
    //console.log("Additional Slurry Data: ", additionalSlurry.value);
    //console.log("Coating Info: ", coatingInfo);
}

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

const clearAllTransition = () => {
    // Reset coatingInfo
    Object.assign(coatingInfo, {
        coatingDate: '',
        coatingMachineNo: '',
        slurryLotNo: '',
        minTbContent: '',
        totalMagnetWeight: '',
        remarks: ''
    });

    Object.assign(coatingInfo_1stgbdp, {
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

    coatingsTable_1stgbdp.value = Array.from({ length: 20 }, (_, i) => ({
        no: i + 1,
        coating: null
    }));

    coatingsTable_2ndgbdp.value = Array.from({ length: 20 }, (_, i) => ({
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

    lotNo.value = null;
    lotNo_1stGBDP.value = null;

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

    const exists = await checkExisting(
        coatingInfo.selectedMassProd,
        coatingInfo.selectedLayer
    );

    if (exists) {
        toast.error("Record already exists for this Mass Prod and Layer.");
        return;
    }

    try{
        const response = await axios.post('/api/coating-data/', coatingDataPayload);
        console.log('Saved Successfully: ', response.data);
        toast.success('Saved Successfully');
    }catch(error){
        toast.error('Failed to save to database. ',error);
    }finally{
        clearAll();
        showModalFinalize.value = false;
    }
}

const saveToDatabase_1st2ndgbdp = async() => {
    try{
        console.log('Clicked Submit!!!!!!!');
        toast.success('1st and 2nd GBDP Data Saved Successfully');
    }catch(error){
        toast.error('Failed to save to database. ',error);
    }finally{
        showModalSubmit.value = false;
    }
}

const checkExisting = async(massprod, layer) => {
    try {
        const response = await axios.get('/api/coating-data/check', {
            params: { mass_prod: massprod, layer }
        });
        return response.data.exists; // true = already exists
    } catch (error) {
        console.error("Check failed:", error);
        toast.error("Failed to check existing record.");
        return false;
    }
}

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

// Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger

const fetchLayerModel = async(massProd, layerNumber) => {
    try {
        const response = await axios.get(
        `/api/mass-productions/${massProd}/layer/${layerNumber}/model`
        )
        console.log("Fetched Model: ", response.data.model);
        fetchedModelValue.value = response.data.model
        console.log("Activation: ", activate2ndGBDP.value);
    } catch (error) {
        fetchedModelValue.value = null;
        console.error(error.response?.data || error.message)
    }
}

watch(
    () => [coatingInfo.selectedMassProd, coatingInfo.selectedLayer],
    ([mp, layer]) => {
        if (mp && layer) {
        fetchLayerModel(mp, layer)
        }
    }
)

const activate2ndGBDP = computed(() => {
    const model = fetchedModelValue.value;
    console.log("Recomputing GBDP check for model:", model);
    if (!model) return false;
    return firstSecondGBDP_models.value.includes(model.trim());
});

/*watch(activate2ndGBDP, (newVal) => {
    if (newVal) {
        clearAllTransition();
        toast.success('1st and 2nd GBDP format enabled');
    }
}); */

const autoFetch = async () => {
    if(!selectedMassProd_fetch.value){
        toast.error("Please enter Mass Production name to fetch data.");
        return;
    }
    if(!selectedLayer_fetch.value){
        toast.error("Please enter layer number to fetch data.");
        return;
    }
    try{
        const response = await axios.get(`/api/coating/get-data`, {
            params: {
                mass_prod: selectedMassProd_fetch.value,
                layer: selectedLayer_fetch.value
            }
        });
        const coatingData = response.data;
        if (!coatingData) {
            toast.error(`No coating data found for Mass Production: ${selectedMassProd_fetch.value}, Layer ${selectedLayer_fetch.value}`);
            return;
        }
        fetchedCoatingData.value = coatingData;

        //Fetched data allocation --- Fetched data allocation
        const cd = fetchedCoatingData.value;
        const cdJson = JSON.parse(cd.coating_data);
        const coatingAmounts = cdJson['Coating Amount Data'] ?? [];
        const concentrationData = cdJson['Concentration Data']

        coatingInfo_1stgbdp.selectedMassProd = selectedMassProd_fetch.value;
        coatingInfo_1stgbdp.coatingDate = cd.date;
        coatingInfo_1stgbdp.coatingMachineNo = cd.machine_no;
        coatingInfo_1stgbdp.slurryLotNo = cd.slurry_lot_no;
        coatingInfo_1stgbdp.minTbContent = cd.min_tb_content;
        coatingInfo_1stgbdp.totalMagnetWeight = cd.total_magnet_weight;
        coatingInfo_1stgbdp.remarks = cd.remarks;

        lotNo_1stGBDP.value = cdJson['Lot no'] ?? null;

        coatingAverage_1stgbdp.value = cd.average;
        coatingMaximum_1stgbdp.value = cd.maximum;
        coatingMinimum_1stgbdp.value = cd.minimum;

        // Fill the table
        coatingsTable_1stgbdp.value = coatingsTable_1stgbdp.value.map((row, index) => {
            const fetched = coatingAmounts[index]?.coating;
            return {
                ...row,
                coating: fetched !== undefined ? fetched : null
            };
        });

        // 1stGBDP grid allocation (1–20 only)
        concentrationData_1stGBDP.value = ranges_1stgbdp.map(rangeLabel => {
            const rangeData = concentrationData.find(r => r.range === rangeLabel);
            return modules.map(moduleName => {
                const mod = rangeData?.modules.find(m => m.module === moduleName);
                return mod?.value ?? null;
            });
        });

        //Fetched data allocation --- Fetched data allocation END

        toast.success(`Data fetched successfully for ${selectedMassProd_fetch.value} Mass Production`);
        console.log('Fetched Mass Production Data: ', fetchedCoatingData.value);
    }catch(error){
        console.error("Error fetching mass prod data:", error);

        if (error.response) {
            if (error.response.status === 404) {
                toast.error(`Mass Production "${selectedMassProd_fetch.value}" does not exists.`);
            } else {
                toast.error(`Server error (${error.response.status}): Unable to fetch data.`);
            }
        } else if (error.request) {
            toast.error("No response from server. Please check your connection.");
        } else {
            toast.error("Unexpected error occurred while fetching data.");
        }
    }
}

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE


// APPLYING Browser Session ----------------- APPLYING Browser Session
useSessionStorage("activate2ndGBDP", activate2ndGBDP);
useSessionStorage("coatingInfo", coatingInfo);
useSessionStorage("coatingInfo_1stgbdp", coatingInfo_1stgbdp);
useSessionStorage("concentrationData", concentrationData);
useSessionStorage("coatingsTable", coatingsTable);
useSessionStorage("additionalSlurry", additionalSlurry);
useSessionStorage("lotNo", lotNo);

// APPLYING Browser Session ----------------- APPLYING Browser Session

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return;
    }
    await getMassProdLists();
});

</script>

<style scoped>

    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
