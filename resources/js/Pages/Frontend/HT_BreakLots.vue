<template>
    <Frontend>
        <div class="flex flex-col justify-start min-h-screen px-4 pt-6 pb-12 bg-gray-100">
            <div class="flex flex-col justify-start min-h-screen px-4 pb-12 bg-gray-100">
                <div class="flex justify-center mb-6">
                    <div class="inline-flex flex-col items-center px-8 py-4 space-y-2 border-2 border-teal-200 shadow-lg bg-gradient-to-r from-teal-50 to-cyan-50 rounded-2xl">

                        <!-- Main Title -->
                        <div class="flex items-center space-x-3">
                        <!-- Furnace Badge -->
                        <div class="px-4 py-1.5 bg-gradient-to-r from-teal-600 to-cyan-600 rounded-lg shadow-md">
                            <span class="text-sm font-bold tracking-wide text-white">{{ mpcsbl.selectedFurnace }}</span>
                        </div>

                        <!-- Separator -->
                        <div class="w-1 h-6 rounded-full bg-gradient-to-b from-teal-400 to-cyan-400"></div>

                        <!-- Mass Production Badge -->
                        <div class="px-4 py-1.5 bg-white border-2 border-teal-300 rounded-lg shadow-sm">
                            <span class="text-sm font-bold text-gray-800">{{ mpcsbl.selectedMassProd }}</span>
                        </div>
                        </div>

                        <!-- Subtitle -->
                        <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-sm font-semibold tracking-wide text-gray-700">
                            Mass Production Control Sheet
                        </h3>
                        </div>
                    </div>
                </div>
                <!-- Enhanced Break Lot Selection Grid -->
                <div class="p-6 mx-auto bg-white border-2 border-gray-200 shadow-xl w-full max-w-[100rem] rounded-2xl">

                    <!-- Header Section -->
                    <div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Manual Allocation Selection Grid</h2>
                                <p class="text-sm text-gray-500">Select production slots for processing</p>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="flex items-center space-x-4 text-xs">
                            <div class="flex items-center space-x-1.5">
                                <div class="w-4 h-4 border border-teal-600 rounded bg-gradient-to-br from-teal-500 to-cyan-500"></div>
                                <span class="font-medium text-gray-600">Selected</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <div class="w-4 h-4 bg-gray-300 border border-gray-400 rounded"></div>
                                <span class="font-medium text-gray-600">Occupied</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <div class="w-4 h-4 bg-white border border-gray-300 rounded"></div>
                                <span class="font-medium text-gray-600">Available</span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Table Container -->
                    <div class="relative overflow-x-auto border border-gray-200 rounded-lg">

                        <!-- Validation Overlay -->
                        <div
                            v-if="validationPassed"
                            class="absolute inset-0 z-20 flex items-center justify-center rounded-lg pointer-events-none bg-white/80 backdrop-blur-sm"
                        >
                            <div class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-300 rounded shadow">
                                ✅ Data has been validated — grid is locked
                            </div>
                        </div>

                        <table class="min-w-full text-sm text-center">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <th class="sticky left-0 z-10 w-32 px-4 py-3 text-xs font-bold text-gray-700 bg-gray-100 border-b-2 border-r-2 border-gray-300">
                                        <div class="flex items-center justify-center space-x-1">
                                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Layer / Box</span>
                                        </div>
                                    </th>
                                    <th
                                        v-for="box in boxes"
                                        :key="box"
                                        class="px-4 py-3 text-sm font-semibold text-gray-700 border-b-2 border-gray-300"
                                    >
                                        <span class="inline-flex items-center justify-center w-8 h-8 font-bold text-gray-800 bg-white border border-gray-300 rounded-lg">
                                            {{ box }}
                                        </span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="layer in layers" :key="layer" class="transition-colors hover:bg-gray-50/50">
                                    <!-- Layer Header Cell -->
                                    <td class="sticky left-0 z-10 px-2 py-2 font-semibold border-b border-r-2 border-gray-300 bg-gradient-to-r from-gray-100 to-gray-50">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="inline-flex items-center justify-center w-8 h-8 font-bold text-gray-800 bg-white border-2 border-gray-300 rounded-lg">
                                                {{ layer }}
                                            </span>

                                            <!-- First Layer Radio -->
                                            <div class="flex items-center space-x-1">
                                                <input
                                                    type="radio"
                                                    :value="layer"
                                                    v-model="firstLayerSelected"
                                                    :disabled="validationPassed || !selectedCoordinates.some(coord => coord.startsWith(layer))"
                                                    id="first-layer-{{ layer }}"
                                                />
                                                <label :for="'first-layer-' + layer" class="text-xs text-gray-600">Main</label>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex gap-1">
                                                <button
                                                    type="button"
                                                    @click.stop="!validationPassed && selectLayer(layer)"
                                                    :disabled="validationPassed"
                                                    class="px-2 py-1 text-xs font-semibold text-white transition-all duration-200 transform rounded-md shadow-sm group bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 hover:shadow-md hover:scale-105 active:scale-95"
                                                    title="Select all available boxes in this layer"
                                                >
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <button
                                                    type="button"
                                                    @click.stop="!validationPassed && unselectLayer(layer)"
                                                    :disabled="validationPassed"
                                                    class="px-2 py-1 text-xs font-semibold text-white transition-all duration-200 transform rounded-md shadow-sm group bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-md hover:scale-105 active:scale-95"
                                                    title="Unselect all selected boxes in this layer"
                                                >
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Coordinate Cells -->
                                    <td
                                        v-for="box in boxes"
                                        :key="box"
                                        @click="!validationPassed && !isCoordinateOccupied(layer, box) && toggleCoordinate(layer, box)"
                                        :class="{
                                            'bg-gray-200 text-gray-400 cursor-not-allowed': isCoordinateOccupied(layer, box) || validationPassed,
                                            'bg-gradient-to-br from-teal-500 to-cyan-500 text-white font-bold shadow-md cursor-pointer': !validationPassed && selectedCoordinates.includes(`${layer}${box}`),
                                            'bg-white hover:bg-gradient-to-br hover:from-cyan-50 hover:to-teal-50 cursor-pointer hover:shadow-sm': !validationPassed && !selectedCoordinates.includes(`${layer}${box}`) && !isCoordinateOccupied(layer, box)
                                        }"
                                        class="relative px-3 py-3 transition-all duration-200 border-b border-gray-200 group"
                                    >
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="text-sm font-semibold">{{ layer }}{{ box }}</span>

                                            <svg
                                                v-if="selectedCoordinates.includes(`${layer}${box}`)"
                                                class="absolute w-4 h-4 text-white top-1 right-1"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>

                                            <svg
                                                v-if="isCoordinateOccupied(layer, box)"
                                                class="absolute w-4 h-4 text-gray-500 top-1 right-1"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <!-- Footer Info -->
                    <div class="p-3 mt-4 border rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-200">
                        <div class="flex items-center space-x-2">
                            <svg class="flex-shrink-0 w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-gray-700">
                                <span class="font-semibold">Tip:</span> Click on available cells to select/deselect. Use the <span class="inline-flex items-center">
                                <svg class="w-3 h-3 text-teal-600 mx-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                </span> (select all) or <span class="inline-flex items-center">
                                <svg class="w-3 h-3 text-red-600 mx-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                </span> (clear all) buttons to quickly manage entire layers.
                            </p>
                        </div>
                    </div>

                    <div v-if="state.user && state.user.access_type === 'Automation'" class="p-4 my-10 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <!-- Panel Header -->
                        <h3 class="mb-3 text-sm font-bold text-gray-700">Selection Summary</h3>

                        <!-- Default system info -->
                        <div v-if="!selectedCoordinates.length && !layersInvolvedUserPick.length" class="mb-4 text-sm italic text-gray-500">
                            No selection yet. Please pick boxes from the grid to see the summary.
                        </div>

                        <!-- Selected Coordinates -->
                        <div v-else-if="selectedCoordinates.length" class="mb-4 text-sm text-gray-700">
                            <p class="mb-2 font-semibold text-gray-600">
                                Selected Coordinates: <span class="font-bold">{{ selectedCoordinates.length }} slots</span>
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="coord in selectedCoordinates"
                                    :key="coord"
                                    class="px-2 py-1 text-xs font-medium text-teal-900 bg-teal-100 border border-teal-200 rounded shadow-sm"
                                >
                                    {{ coord }}
                                </span>
                            </div>
                        </div>

                        <!-- Layers Involved -->
                        <div v-if="layersInvolvedUserPick.length" class="text-sm text-gray-700">
                            <p class="mb-2 font-semibold text-gray-600">Layers Involved:</p>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="layer in layersInvolvedUserPick"
                                    :key="layer"
                                    :class="[
                                        'px-3 py-1 text-xs font-medium rounded-full shadow',
                                        String(layer) === String(firstLayerSelected)
                                            ? 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white'
                                            : 'bg-gradient-to-r from-cyan-500 to-teal-500 text-white'
                                    ]"
                                >
                                    Layer {{ layer }}
                                    <span v-if="String(layer) === String(firstLayerSelected)" class="ml-1 font-bold">(Main)</span>
                                </span>
                            </div>
                        </div>
                    </div>


                     <!-- General Info Form -->
                    <div class="p-6 mb-6 border-2 border-gray-200 shadow-md bg-gradient-to-br from-gray-50 to-white rounded-xl">
                        <!-- Section Header -->
                        <div class="flex items-center pb-4 mb-5 space-x-3 border-b-2 border-gray-200">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-teal-500 to-cyan-500">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            </div>
                            <div>
                            <h3 class="text-base font-bold text-gray-900">General Information</h3>
                            <p class="text-xs text-gray-500">Shared data across all layers and boxes</p>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-5">

                            <!-- Row 1: Model, Coating M/C, Raw Material -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <!-- Lot No -->
                                <div class="group">
                                    <label class="flex items-center mb-2 text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 mr-1.5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        Lot No.
                                        <span class="ml-1 font-bold text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="mpcsbl.lotNo"
                                        :disabled="validationPassed"
                                        :class="['w-full text-sm transition-all duration-200 border-2 rounded-lg shadow-sm focus:ring-2 hover:border-gray-400', validationPassed ? 'bg-gray-100 border-gray-300 cursor-not-allowed' : 'border-gray-300 focus:ring-teal-500 focus:border-teal-500']"
                                    >
                                        <option value="" disabled>Select Lot No</option>
                                        <option v-for="(lot, index) in lotNoLists" :key="index" :value="lot.lot_no">
                                            {{ lot.lot_no }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Model -->
                                <div class="group">
                                    <label class="flex items-center mb-2 text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 mr-1.5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Model
                                        <span class="ml-1 font-bold text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="mpcsbl.selectedModel"
                                        :disabled="validationPassed"
                                        :class="['w-full text-sm font-semibold transition-all duration-200 border-2 rounded-lg shadow-sm', validationPassed ? 'bg-gray-100 border-gray-300 cursor-not-allowed' : 'border-amber-400 bg-amber-50 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-amber-500']"
                                    >
                                        <option v-for="(lot, index) in model_names" :key="index" :value="lot.model_name">
                                            {{ lot.model_name }}
                                        </option>
                                    </select>
                                </div>


                            </div>

                            <!-- Divider -->
                            <div class="border-t border-gray-200"></div>

                            <div class="grid items-start grid-cols-1 gap-4 mb-10 md:grid-cols-3">

                                <!-- Total Boxes Card -->
                                <div class="flex items-center justify-between w-full gap-2 px-4 py-3 mt-4 border-l-4 rounded-md shadow-sm bg-gray-50 border-cyan-600">
                                    <div class="flex items-center gap-2">
                                        <!-- Icon -->
                                        <svg class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 3h14v2H3V3zm0 4h14v2H3V7zm0 4h14v2H3v-2zm0 4h14v2H3v-2z"/>
                                        </svg>
                                        <p class="text-sm font-semibold text-gray-800">
                                            Detected Total Boxes:
                                            <span class="text-blue-700">{{ totalBoxes }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Buttons Column -->
                                <div class="flex flex-col w-full gap-3 mt-4">
                                    <!-- Validate Data Button -->
                                    <button
                                        v-if="!validationPassed"
                                        :disabled="!totalBoxes || !selectedCoordinates.length"
                                        @click="validateData()"
                                        class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-400 hover:to-teal-400 active:from-cyan-600 active:to-teal-600 focus:outline-none focus:ring-2 focus:ring-cyan-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z" clip-rule="evenodd"/>
                                        </svg>
                                        Validate Data
                                    </button>

                                    <!-- Reset Data Button -->
                                    <button
                                        v-else
                                        @click="resetData()"
                                        class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-400 hover:to-pink-400 active:from-red-600 active:to-pink-600 focus:outline-none focus:ring-2 focus:ring-red-300"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Reset Data
                                    </button>

                                    <!-- Warning Message -->
                                    <p v-if="validationAttempted && !validationPassed" class="flex items-center gap-1 mt-1 text-xs text-red-500">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.366-.756 1.42-.756 1.786 0l6.518 13.462c.36.745-.08 1.639-.893 1.639H2.632c-.813 0-1.253-.894-.893-1.639L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v3a1 1 0 01-1 1z" clip-rule="evenodd"/>
                                        </svg>
                                        Validation failed: Selected boxes ({{ selectedCoordinates.length }}) do not match total boxes ({{ totalBoxes }}).
                                    </p>
                                </div>

                                <!-- Show Data Button Column -->
                                <div class="w-full mt-4">
                                    <button
                                        v-if="validationPassed"
                                        :disabled="isDataShown"
                                        @click="fetchAllLotDataBoxDetails()"
                                        class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-400 hover:to-cyan-400 active:from-teal-600 active:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-teal-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v4h2V7zm0 6H9v2h2v-2z"/>
                                        </svg>
                                        Show Data
                                    </button>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <!-- Current Grand Total Weight -->
                                <div
                                    v-if="isDataShown"
                                    class="inline-flex flex-col gap-1 px-5 py-3 border border-gray-300 shadow-md rounded-xl bg-gradient-to-br from-gray-50 to-slate-50 shadow-gray-200/40"
                                >
                                    <div class="text-[8px] font-semibold tracking-widest uppercase text-gray-600">
                                        Current Grand Total Weight
                                    </div>

                                    <div class="text-xl font-bold leading-none text-gray-600">
                                        {{ currentGrandTotal }} (Kgs)
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
                                        {{ expectedTotalWeight }} (Kgs)
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
                    </div>

                    <!-- Dynamic Layer Inputs (Per-box unique inputs and global outputs) -->
                    <div v-for="layer in layersInvolvedUserPick" :key="layer" class="pt-4 mb-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-semibold text-gray-700">Layer {{ layer }}</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-center border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs border border-gray-300">Input</th>
                                        <th v-for="box in groupedByLayer[layer]" :key="box" class="px-4 py-2 text-xs border border-gray-300">
                                            {{ box }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Model -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Model</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ mpcsbl.selectedModel }}
                                        </td>
                                    </tr>

                                    <!-- Coating M/C No -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating M/C No.</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ mpcsbl.coatingMCNo }}
                                        </td>
                                    </tr>

                                    <!-- Lot No -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Lot No.</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ mpcsbl.lotNo }}
                                        </td>
                                    </tr>

                                    <!-- QTY (PCS) -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">QTY (PCS)</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ layerInputs[layer][box].qty }}
                                        </td>
                                    </tr>

                                    <!-- HT (PCS) - Input -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">HT (PCS)</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            <input
                                                type="text"
                                                v-model="layerInputs[layer][box].ht"
                                                @input="layerInputs[layer][box].ht = layerInputs[layer][box].ht.toUpperCase()"
                                                class="w-full text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-400"
                                            />
                                        </td>
                                    </tr>

                                    <!-- LT (PCS) - Input -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">LT (PCS)</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            <input
                                                type="text"
                                                v-model="layerInputs[layer][box].lt"
                                                @input="layerInputs[layer][box].lt = layerInputs[layer][box].lt.toUpperCase()"
                                                class="w-full text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-400"
                                            />
                                        </td>
                                    </tr>

                                    <!-- Weight (KG) -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Weight (KG)</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ layerInputs[layer][box].weight }}
                                        </td>
                                    </tr>

                                    <!-- Box No. -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box No.</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ layerInputs[layer][box].boxNo }}
                                        </td>
                                    </tr>

                                    <!-- Coating -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Coating</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ mpcsbl.coating }}
                                        </td>
                                    </tr>

                                    <!-- Magnet Prepared By -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Magnet Prepared By</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ mpcsbl.magnetPreparedBy }}
                                        </td>
                                    </tr>

                                    <!-- Box Prepared By -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border border-gray-300 whitespace-nowrap">Box Prepared By</td>
                                        <td v-for="box in groupedByLayer[layer]" :key="box" class="px-2 py-1 border border-gray-300">
                                            {{ mpcsbl.boxPreparedBy }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div v-if="layersInvolvedUserPick.length" class="flex justify-center mt-20 mb-10">
                        <button
                            @click="Inertia.visit('/heat_treatment')"
                            class="group relative mr-10 px-10 py-4 bg-gradient-to-r from-gray-600 via-gray-700 to-gray-800 bg-[length:200%_100%]
                                hover:bg-[length:100%_100%] text-white font-bold text-lg rounded-2xl shadow-xl
                                hover:shadow-[0_20px_45px_rgba(0,0,0,0.45)] transition-all duration-500
                                transform hover:scale-105 active:scale-95 overflow-hidden border-2 border-gray-500"
                        >
                            <!-- Animated background shimmer -->
                            <div class="absolute inset-0 transition-transform duration-1000 transform -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-20 group-hover:translate-x-full"></div>

                            <!-- Subtle pulsing aura -->
                            <div class="absolute inset-0 transition-opacity duration-500 opacity-0 bg-gradient-to-r from-gray-400 to-gray-500 blur-xl group-hover:opacity-30 animate-pulse"></div>

                            <span class="relative flex items-center space-x-3">
                                <!-- Icon -->
                                <svg class="w-6 h-6 transition-all duration-500 group-hover:rotate-180 group-hover:scale-125"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                    </svg>

                                <!-- Text -->
                                <span class="tracking-wide drop-shadow-md">CANCEL</span>

                                <!-- Arrow retract -->
                                <svg class="w-5 h-5 transition-all duration-500 group-hover:-translate-x-2 group-hover:scale-110"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>

                            <!-- Corner pulses -->
                            <div class="absolute top-0 left-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping"></div>
                            <div class="absolute top-0 right-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping" style="animation-delay: 0.1s"></div>
                            <div class="absolute bottom-0 left-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping" style="animation-delay: 0.2s"></div>
                            <div class="absolute bottom-0 right-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping" style="animation-delay: 0.3s"></div>

                            <!-- Bottom accent -->
                            <div class="absolute bottom-0 w-0 h-1 transition-all duration-500 transform -translate-x-1/2 left-1/2 bg-gradient-to-r from-gray-300 via-gray-400 to-gray-300 group-hover:w-full"></div>
                        </button>
                        <button
                            v-if="isDataShown && !isGrandTotalLimitReached"
                            @click="confirmValidate()"
                            class="group relative px-10 py-4 bg-gradient-to-r from-teal-600 via-cyan-600 to-teal-600 bg-[length:200%_100%] hover:bg-[length:100%_100%] text-white font-bold text-lg rounded-2xl shadow-2xl hover:shadow-[0_20px_50px_rgba(20,184,166,0.5)] transition-all duration-500 transform hover:scale-105 active:scale-95 overflow-hidden border-2 border-teal-400"
                        >
                            <!-- Animated background gradient -->
                            <div class="absolute inset-0 transition-transform duration-1000 transform -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-30 group-hover:translate-x-full"></div>

                            <!-- Pulsing glow effect -->
                            <div class="absolute inset-0 transition-opacity duration-500 opacity-0 bg-gradient-to-r from-teal-400 to-cyan-400 blur-xl group-hover:opacity-50 animate-pulse"></div>

                            <!-- Button content -->
                            <span class="relative flex items-center space-x-3">
                            <!-- Animated check icon that rotates -->
                            <svg class="w-6 h-6 transition-all duration-500 group-hover:rotate-[360deg] group-hover:scale-125" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>

                            <!-- Text -->
                            <span class="tracking-wide drop-shadow-md">FINALIZE SUBMIT</span>

                            <!-- Animated arrow that slides right -->
                            <svg class="w-5 h-5 transition-all duration-500 group-hover:translate-x-2 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            </span>

                            <!-- Particles effect on corners -->
                            <div class="absolute top-0 left-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping"></div>
                            <div class="absolute top-0 right-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping" style="animation-delay: 0.1s"></div>
                            <div class="absolute bottom-0 left-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping" style="animation-delay: 0.2s"></div>
                            <div class="absolute bottom-0 right-0 w-2 h-2 bg-white rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping" style="animation-delay: 0.3s"></div>

                            <!-- Bottom accent line that expands -->
                            <div class="absolute bottom-0 w-0 h-1 transition-all duration-500 transform -translate-x-1/2 rounded-full left-1/2 bg-gradient-to-r from-cyan-300 via-teal-300 to-cyan-300 group-hover:w-full"></div>
                        </button>
                    </div>
                </div>
            </div>

            <Modal :show="showConfirmationPanel" @close="showConfirmationPanel = false">
                <div class="relative">
                    <!-- Header with Danger Gradient -->
                    <div class="relative px-6 py-6 bg-gradient-to-r from-red-600 via-orange-600 to-red-700">
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
                        <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-25 border border-white rounded-xl backdrop-blur-sm border-opacity-40 animate-pulse">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.584c.75 1.334-.213 2.983-1.742 2.983H3.48c-1.53 0-2.492-1.65-1.742-2.983L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        </div>

                        <button @click="showConfirmationPanel = false"
                        class="p-2 text-white transition-all duration-200 rounded-lg hover:text-gray-200 hover:bg-white hover:bg-opacity-20 hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        </button>
                    </div>
                    </div>
                    <!-- Main Content -->
                    <div class="px-6 py-6 border border-red-200 rounded-lg shadow-inner bg-red-50">
                    <div class="mb-6 text-center">
                        <h3 class="flex items-center justify-center mb-2 space-x-2 text-xl font-bold text-red-700">
                        <svg class="w-5 h-5 text-red-600 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>⚠️ Confirm Submission Carefully</span>
                        </h3>
                        <p class="text-sm font-semibold text-red-700">
                        This action is <span class="underline">irreversible</span>! Please double-check all inputs.
                        </p>
                    </div>
                    <!-- Validation Checklist -->
                    <div class="p-4 mb-6 bg-red-100 border border-red-300 rounded-lg">
                        <h4 class="flex items-center mb-3 space-x-2 text-sm font-semibold text-red-800">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Checklist</span>
                        </h4>

                        <div class="space-y-2 text-xs text-red-800">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>All required fields completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>Data validated successfully</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>Submission is permanent. Proceed only if confident!</span>
                        </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button
                        @click="showConfirmationPanel = false"
                        class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-all duration-200 transform hover:scale-[0.98] flex items-center justify-center space-x-2">
                        Cancel
                        </button>

                        <button
                        @click="saveToDatabase()"
                        class="group flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
                        <div class="absolute inset-0 transition-transform transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-20 group-hover:translate-x-full duration-600"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4 transition-all duration-300 group-hover:rotate-12 group-hover:scale-110 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>⚠️ Submit Now</span>
                        </span>
                        </button>
                    </div>
                    </div>
                    <!-- Bottom accent line -->
                    <div class="h-1 bg-gradient-to-r from-red-500 via-orange-400 to-red-500"></div>
                </div>
            </Modal>

            <Modal :show="showPreviewPanel" @close="showPreviewPanel = false">
                <div
                    class="relative flex flex-col items-start bg-white p-6 rounded-xl shadow-2xl max-w-[95vw] max-h-[90vh] overflow-auto pr-12"
                >
                        <!-- Exit Button -->
                        <button
                        @click="showPreviewPanel = false"
                        class="text-gray-400 transition duration-150 hover:text-gray-600"
                        aria-label="Close modal"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        </button>

                        <p class="text-lg font-bold text-gray-800">Please review your inputs <span class="text-red-700">carefully</span> before submitting.</p>
                    <div v-for="layer in layersInvolvedUserPick" :key="layer">
                        <div v-if="layer !== firstLayerSelected">
                            <h2 class="mt-8 mb-2 text-lg font-bold">
                                Layer {{ layer }}
                            </h2>

                            <table class="w-full text-xs border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-1 border">Field</th>
                                        <th
                                            v-for="box in previewBoxes(layer)"
                                            :key="box"
                                            class="px-2 py-1 text-center border"
                                        >{{ box }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- MODEL -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Model:</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box" class="px-2 py-1 text-center border">
                                            {{ mpcsbl.selectedModel }}
                                        </td>
                                    </tr>

                                    <!-- COATING MC NO -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Coating M/C No:</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box" class="px-2 py-1 text-center border">
                                            {{ mpcsbl.coatingMCNo }}
                                        </td>
                                    </tr>

                                    <!-- LOT NO -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Lot No:</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box" class="px-2 py-1 text-center border">
                                            {{ mpcsbl.lotNo }}
                                        </td>
                                    </tr>

                                    <!-- QTY -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Qty (PCS):</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'q'" class="px-2 py-1 text-center border">
                                            {{ layerInputs[layer][box]?.qty }}
                                        </td>
                                    </tr>

                                    <!-- HT -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">HT (PCS):</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'ht'" class="px-2 py-1 text-center border">
                                            {{ layerInputs[layer][box]?.ht }}
                                        </td>
                                    </tr>

                                    <!-- LT -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">LT (PCS):</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'lt'" class="px-2 py-1 text-center border">
                                            {{ layerInputs[layer][box]?.lt }}
                                        </td>
                                    </tr>

                                    <!-- WEIGHT -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">WT (KG):</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'wt'" class="px-2 py-1 text-center border">
                                            {{ layerInputs[layer][box]?.weight }}
                                        </td>
                                    </tr>

                                    <!-- BOX NO -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Box No.:</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'bn'" class="px-2 py-1 text-center border">
                                            {{ layerInputs[layer][box]?.boxNo }}
                                        </td>
                                    </tr>

                                    <!-- COATING -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Coating:</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'ct'" class="px-2 py-1 text-center border">
                                            {{ mpcsbl.coating }}
                                        </td>
                                    </tr>

                                    <!-- MAGNET PREPARED BY -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Magnet Prepared By:</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'mpb'" class="px-2 py-1 text-center border">
                                            {{ mpcsbl.magnetPreparedBy }}
                                        </td>
                                    </tr>

                                    <!-- BOX PREPARED BY -->
                                    <tr>
                                        <td class="px-2 py-1 font-medium border bg-gray-50 whitespace-nowrap">Box Prepared By:</td>
                                        <td v-for="box in previewBoxes(layer)" :key="box + 'bpb'" class="px-2 py-1 text-center border">
                                            {{ mpcsbl.boxPreparedBy }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex my-8 space-x-3">
                        <button
                            @click="showPreviewPanel = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 rounded-lg shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1"
                        >
                            Cancel
                        </button>
                        <button
                            @click="saveToDatabase()"
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

const isDataShown = ref(false);

const showConfirmationPanel = ref(false);
const showPreviewPanel = ref(false);
const firstLayerSelected = ref('');
const totalBoxes = ref();
const model_names = ref([]);
const lotNoLists = ref([]);
const validationPassed = ref(false); // true only after successful validation
const validationAttempted = ref(false); // to track if validation has been attempted
const layers = ref([9, 8, 7, 6, 5, 4, 3, 2, 1]);
const boxes = ['A','B','C','D','E','F','G','H','J','K'];
const currentGridData = ref();
const currentGrandTotal = ref();
const expectedTotalWeight = ref();
const grandTotalLimit = ref(1425);
const isGrandTotalLimitReached = computed(() => {
    return Number(expectedTotalWeight.value) > grandTotalLimit.value;
});

// Holds selected coordinates (e.g. ['9B','8D','6F'])
const selectedCoordinates = ref([]);

// Toggle a coordinate (select/unselect)
function toggleCoordinate(layer, box) {
    const key = `${layer}${box}`;
    if (selectedCoordinates.value.includes(key)) {
        // Deselect
        selectedCoordinates.value = selectedCoordinates.value.filter(c => c !== key);

        // Clear first layer if no boxes remain in that layer
        const remainingBoxesInLayer = selectedCoordinates.value.filter(c => c.startsWith(layer));
        if (firstLayerSelected.value === layer && remainingBoxesInLayer.length === 0) {
            firstLayerSelected.value = '';
        }

        // Remove from reactive layerInputs
        removeLayerInputBox(layer, box);
    } else {
        // Select
        selectedCoordinates.value.push(key);

        // If first layer not set, set it
        if (!firstLayerSelected.value) firstLayerSelected.value = layer;

        // Initialize layerInputs for this box
        initializeLayerInputs(layer, [box]);
    }
}

// Reactive array of layers involved in current selection
const layersInvolvedUserPick = computed(() => {
    // Extract layer numbers from selectedCoordinates
    const layersSet = new Set(
        selectedCoordinates.value.map(coord => coord.match(/^\d+/)[0])
    );
    return Array.from(layersSet).sort((a, b) => b - a); // optional: sort descending like your layers
});

// Group selections by layer — used later to build dynamic forms
const groupedByLayer = computed(() => {
    const groups = {};
    for (const layer in layerInputs) {
        groups[layer] = Object.keys(layerInputs[layer]);
    }
    return groups;
});

// MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!! // MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!!

const mpcsbl = reactive({
    selectedFurnace: '',
    selectedMassProd: '',
    selectedModel: '',
    coatingMCNo: '',
    rawMaterialCode: '',
    lotNo: '',
    coating: '',
    magnetPreparedBy: '',
    boxPreparedBy: '',
});

const layerInputs = reactive({});

// Initialize structure when user selects coordinates
const initializeLayerInputs = (layer, boxes) => {
    if (!layerInputs[layer]) layerInputs[layer] = {};
    boxes.forEach(box => {
        if (!layerInputs[layer][box]) {
            layerInputs[layer][box] = {
                boxNo: '',
                weight: '',
                ht: '',
                lt: '',
                qty: ''
            };
        }
    });
};

// Remove box from reactive object if deselected
const removeLayerInputBox = (layer, box) => {
    if (layerInputs[layer] && layerInputs[layer][box]) {
        delete layerInputs[layer][box];
        // Optional: delete layer if empty
        if (Object.keys(layerInputs[layer]).length === 0) {
            delete layerInputs[layer];
        }
    }
};

const mainBoxes = computed(() =>
    selectedCoordinates.value
        .filter(c => c.startsWith(firstLayerSelected.value))
        .map(c => c.replace(firstLayerSelected.value, ''))
);

const previewBoxes = (layer) =>
    selectedCoordinates.value
        .filter(c => c.startsWith(layer))
        .map(c => c.replace(layer, ''));

const validateData = async () => {
    validationAttempted.value = true;

    // --- First validation: total boxes ---
    if (selectedCoordinates.value.length !== Number(totalBoxes.value)) {
        validationPassed.value = false;
        toast.warning(
            `Validation failed: You have selected ${selectedCoordinates.value.length} boxes while the fetched data contains ${totalBoxes.value} boxes for Model ${mpcsbl.selectedModel}, Lot #${mpcsbl.lotNo}. You cannot proceed.`
        );
        return; // stop further validation
    }

    // --- Ensure main layer is selected ---
    if (!firstLayerSelected.value) {
        validationPassed.value = false;
        toast.warning('Please select the main layer to continue validation.');
        return;
    }

    // --- Compute main and excess layer counts ---
    const mainLayer = firstLayerSelected.value;
    const mainCoordinates = selectedCoordinates.value.filter(coord => coord.startsWith(mainLayer));
    const excessCoordinates = selectedCoordinates.value.filter(coord => !coord.startsWith(mainLayer));

    const mainCount = mainCoordinates.length;
    const excessCount = excessCoordinates.length;

    // --- Second validation: call server to check layer/excess boxes ---
    try {
        const response = await axios.post(
            '/api/initial-control-sheets/second-validation',
            {
                model_name: mpcsbl.selectedModel,
                lot_no: mpcsbl.lotNo,
                main_count: mainCount,
                excess_count: excessCount,
            }
        );

        if (response.data.validated) {
            // Both validations passed
            validationPassed.value = true;
            toast.success('All validations passed. You may now proceed to fetch data.');
        } else {
            validationPassed.value = false;
            toast.warning(
                'Second validation failed: The main and/or excess layer box selection does not match the system data. You cannot proceed.'
            );
        }
    } catch (error) {
        validationPassed.value = false;
        console.error('Failed to perform second validation', error);
        toast.error('An error occurred while validating layers. Please try again.');
    }
};


watch(
    () => mpcsbl.lotNo,
    async (newVal) => {
        if (!newVal) return;
        await getModelLists();
        console.log("Lot No: ",mpcsbl.lotNo);
        console.log("Model: ",mpcsbl.selectedModel);
    }
);

// Automatically create or clean up form structures when user selection changes
watch(layersInvolvedUserPick, (newLayers, oldLayers) => {
    // Add new layers
    newLayers.forEach(layer => {
        const boxes = groupedByLayer.value[layer] || [];
        initializeLayerInputs(layer, boxes);
    });

    // Remove layers that are no longer involved
    oldLayers.forEach(layer => {
        if (!newLayers.includes(layer)) {
            delete layerInputs[layer];
        }
    });
});

// Also track changes inside groupedByLayer, since boxes per layer can change
watch(groupedByLayer, (newGroups) => {
    // Ensure layerInputs matches exactly what's selected
    Object.entries(newGroups).forEach(([layer, boxes]) => {
        initializeLayerInputs(layer, boxes);

        // Cleanup boxes that were unselected
        const existingBoxes = Object.keys(layerInputs[layer] || {});
        existingBoxes.forEach(box => {
            if (!boxes.includes(box)) {
                removeLayerInputBox(layer, box);
            }
        });
    });
});

watch(
    () => [mpcsbl.selectedModel, mpcsbl.lotNo],
    async ([model, lot]) => {
        if (model && lot) {
            await getTotalBoxes();
        }
    }
);

// MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!! // MASS PRODUCTION VARIABLES //!!!!!!!!!!!!!!!!

// HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!! // HEAT TREATMENT INFORMATION VARIABLES !!!!!!!!!!!!!

const props = defineProps({
    furnace: String,  // Expecting the serialParam to be a string
    massprod: String,
});

mpcsbl.selectedFurnace = props.furnace;
mpcsbl.selectedMassProd = props.massprod;
console.log("Furnace: ", mpcsbl.selectedFurnace);
console.log("Mass Prod: ", mpcsbl.selectedMassProd);

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getTotalBoxes = async () => {
    try{
        const response = await axios.post('/api/initial-control-sheets/lot-total-boxes', {
            model_name: mpcsbl.selectedModel,
            lot_no: mpcsbl.lotNo
        });
        //console.log("Total Boxes data: ", response.data);
        const tbox = response.data;
        totalBoxes.value = tbox.total_boxes || 'Loading...';
        console.log('Total Boxes: ', totalBoxes.value);
    }catch(error){
        console.error('Failed to get total boxes: ', error);
    }
}


// Fetch current data grid (availability)
const getCurrentDataGrid = async () => {
    try {
        const response = await axios.get(
            `/api/break-lot-monitoring/${mpcsbl.selectedFurnace}/${mpcsbl.selectedMassProd}`
        )
        currentGridData.value = response.data;
        console.log("Current Data Grid: ", currentGridData.value);
    } catch (error) {
        console.error('Error fetching grid data:', error)
    }
}

const getModelLists = async () => {
    try{
        const response = await axios.post(
            '/api/initial-control-sheets/lot-all-model',
            {
                lot_no: mpcsbl.lotNo,
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

const fetchAllLotNoData = async () => {
    try {
        const response = await axios.get('/api/initial-control-sheets/lot-all');
        //console.log(response.data); // All records with lot_no, newest first
        lotNoLists.value = response.data;
    } catch (err) {
        console.error('Failed to fetch lot_no data:', err);
    }
};


// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE END

const getGrandTotalWeightData = async () => {
    try{
        const response = await axios.post('/api/mass-production/grand-total-weight', {
            mass_prod: mpcsbl.selectedMassProd,
            furnace: mpcsbl.selectedFurnace,
        });

        return Number(response.data.grand_total) || 0;

    }catch(error){
        console.error('Failed to get grand total weight data',error);
    }
}

// Check if a coordinate already has data
const isCoordinateOccupied = (layer, box) => {
    if (!currentGridData.value) return false;

    const availableBoxes = currentGridData.value.available_slots[`layer_${layer}`];
    if (!availableBoxes) return false; // safety check

    // If the box is in the available array, it is **not occupied**.
    return !availableBoxes.includes(box);
};

const selectLayer = (layer) => {
    if (!currentGridData.value) return;

    const availableBoxes = currentGridData.value.available_slots[`layer_${layer}`];
    if (!availableBoxes) return;

    availableBoxes.forEach((box) => {
        const key = `${layer}${box}`;
        if (!selectedCoordinates.value.includes(key)) {
            selectedCoordinates.value.push(key);
            // Initialize reactive layerInputs
            initializeLayerInputs(layer, [box]);
        }
    });

    // Set first layer if not already set
    if (!firstLayerSelected.value) firstLayerSelected.value = layer;
};

const unselectLayer = (layer) => {
    // Remove selected coordinates
    const coordsToRemove = selectedCoordinates.value.filter(key => key.startsWith(layer));
    coordsToRemove.forEach(coord => {
        const box = coord.replace(layer, '');
        removeLayerInputBox(layer, box);
    });
    selectedCoordinates.value = selectedCoordinates.value.filter(key => !key.startsWith(layer));

    // Clear first/last if affected
    if (firstLayerSelected.value === layer) firstLayerSelected.value = '';
};

const resetData = () => {
    // Reset all mpcsbl fields except selectedFurnace and selectedMassProd
    Object.keys(mpcsbl).forEach(key => {
        if (key === 'selectedFurnace' || key === 'selectedMassProd') return;

        // Reset numbers to 0, strings to empty
        mpcsbl[key] = typeof mpcsbl[key] === 'number' ? 0 : '';
    });

    // Clear selected coordinates in the grid
    selectedCoordinates.value = [];

    // Reset first layer selection radio
    firstLayerSelected.value = null;

    // Reset validation flags
    validationAttempted.value = false;
    validationPassed.value = false;

    isDataShown.value = false;

    // Optionally reset layer inputs if you are storing per-box data
    Object.keys(layerInputs).forEach(layer => {
        Object.keys(layerInputs[layer]).forEach(box => {
            layerInputs[layer][box] = {
                qty: 0,
                ht: 0,
                lt: 0,
                weight: 0,
                boxNo: ''
            };
        });
    });
};



const confirmValidate = () => {
    //showConfirmationPanel.value = true;
    showPreviewPanel.value = true;
};

const formatLayerDataForDatabase = (layer) => {
    if (!layerInputs[layer]) return [];

    const boxesInLayer = Object.keys(layerInputs[layer]);
    if (!boxesInLayer.length) return [];

    const qtyData = {};
    const htData = {};
    const ltData = {};
    const wtData = {};
    const boxNoData = {};

    boxesInLayer.forEach(box => {
        const values = layerInputs[layer][box];

        qtyData[box] = values.qty || 0;
        htData[box] = values.ht || 0;
        ltData[box] = values.lt || 0;
        wtData[box] = values.weight || 0;
        boxNoData[box] = values.boxNo || '';
    });

    const totalQty = Object.values(qtyData).reduce((acc, val) => acc + Number(val), 0);

    return [
        { rowTitle: 'MODEL:', data: Object.fromEntries(boxesInLayer.map(box => [box, mpcsbl.selectedModel || ''])) },
        { rowTitle: 'COATING M/C No.:', data: Object.fromEntries(boxesInLayer.map(box => [box, mpcsbl.coatingMCNo || ''])) },
        { rowTitle: 'LT. No.:', data: Object.fromEntries(boxesInLayer.map(box => [box, mpcsbl.lotNo || ''])) },
        { rowTitle: 'QTY (PCS):', data: qtyData },
        { rowTitle: 'HT (PCS):', data: htData },
        { rowTitle: 'LT (PCS):', data: ltData },
        { rowTitle: 'COATING:', data: Object.fromEntries(boxesInLayer.map(box => [box, mpcsbl.coating || 0])) },
        { rowTitle: 'WT (KG):', data: wtData },
        { rowTitle: 'BOX No.:', data: boxNoData },
        { rowTitle: 'Magnet prepared by:', data: Object.fromEntries(boxesInLayer.map(box => [box, mpcsbl.magnetPreparedBy || ''])) },
        { rowTitle: 'Box prepared by:', data: Object.fromEntries(boxesInLayer.map(box => [box, mpcsbl.boxPreparedBy || ''])) },
        { rowTitle: 'RAW MATERIAL CODE:', data: Object.fromEntries(boxesInLayer.map(box => [box, mpcsbl.rawMaterialCode || ''])) },
        { rowTitle: 'TOTAL QTY', data: Object.fromEntries(boxesInLayer.map(box => [box, totalQty])) }
    ];
};

const changeData = () => {

}

const fetchAllLotDataBoxDetails = async () => {
    if (!mpcsbl.selectedModel || !mpcsbl.lotNo) {
        toast.warning('Please select Model and Lot number first');
        return;
    }

    try {
        isDataShown.value = true;
        const response = await axios.post(
            '/api/initial-control-sheets/fetch-layer-excess-data',
            {
                model_name: mpcsbl.selectedModel,
                lot_no: mpcsbl.lotNo,
            }
        );

        const { layer_data, excess_data, total_boxes } = response.data;
        totalBoxes.value = total_boxes ?? 0;

        const mainLayer = firstLayerSelected.value;

        // ---- Helper: normalize row titles for easy mapping ----
        const normalizeTitle = (title) =>
            title.toLowerCase().replace(/[^a-z]/g, '');

        // ---- Populate global mpcsbl fields from first box of main layer ----
        const mainBoxes = selectedCoordinates.value
            .filter(c => c.startsWith(mainLayer))
            .map(c => c.replace(mainLayer, ''));

        if (mainBoxes.length) {
            const firstBoxIndex = 0; // first selected box
            layer_data.forEach(row => {
                const title = normalizeTitle(row.rowTitle);
                const dataValues = Object.values(row.data);
                const value = dataValues[firstBoxIndex] ?? '';

                if (title.includes('model')) mpcsbl.selectedModel = value;
                else if (title.includes('coatingmcno')) mpcsbl.coatingMCNo = value;
                else if (title.includes('lot')) mpcsbl.lotNo = value;
                else if (title.includes('coating')) mpcsbl.coating = value;
                else if (title.includes('magnetpreparedby')) mpcsbl.magnetPreparedBy = value;
                else if (title.includes('boxpreparedby')) mpcsbl.boxPreparedBy = value;
            });
        }

        // ---- Clear previous layerInputs ----
        Object.keys(layerInputs).forEach(layer => delete layerInputs[layer]);

        // ---- Populate layerInputs for all selected layers ----
        layersInvolvedUserPick.value.forEach(layer => {
            const boxesInLayer = selectedCoordinates.value
                .filter(c => c.startsWith(layer))
                .map(c => c.replace(layer, ''));

            if (!layerInputs[layer]) layerInputs[layer] = {};

            const sourceRows = (String(layer) === String(mainLayer)) ? layer_data : excess_data;

            // Sequential mapping: map i-th fetched data to i-th selected box
            boxesInLayer.forEach((box, i) => {
                const qtyRow = sourceRows.find(r => normalizeTitle(r.rowTitle).includes('qty'));
                const weightRow = sourceRows.find(r => normalizeTitle(r.rowTitle).includes('wt'));
                const boxNoRow = sourceRows.find(r => normalizeTitle(r.rowTitle).includes('box'));

                const qtyValue = qtyRow ? Object.values(qtyRow.data)[i] ?? 0 : 0;
                const weightValue = weightRow ? Object.values(weightRow.data)[i] ?? 0 : 0;
                const boxNoValue = boxNoRow ? Object.values(boxNoRow.data)[i] ?? '' : '';

                layerInputs[layer][box] = {
                    qty: qtyValue,
                    weight: weightValue,
                    boxNo: boxNoValue,
                    ht: layerInputs[layer]?.[box]?.ht ?? '',
                    lt: layerInputs[layer]?.[box]?.lt ?? ''
                };
            });
        });

        currentGrandTotal.value = Number((await getGrandTotalWeightData()).toFixed(2));

        // ---- Helper to sum the weights in a data array ----
        const sumWeightRow = (dataArray) => {
            if (!dataArray?.length) return 0;

            const weightRow = dataArray.find(r => normalizeTitle(r.rowTitle).includes('wt'));
            if (!weightRow) return 0;

            return Object.values(weightRow.data).reduce((sum, val) => sum + (Number(val) || 0), 0);
        };

        // ---- Compute total weights ----
        const totalMainWeight = sumWeightRow(layer_data);
        const totalExcessWeight = sumWeightRow(excess_data);

        // ---- Combine and round ----
        expectedTotalWeight.value = Number((totalMainWeight + totalExcessWeight + currentGrandTotal.value).toFixed(2));


        toast.success('Box details fetched successfully');
    } catch (error) {
        console.error('Failed to fetch box details', error);
        toast.error('Failed to fetch box details');
    }
};


const saveToDatabase = async () => {
    if (!selectedCoordinates.value.length) return;

    try {
        // --- Normalize & identify layers ---
        const sortedLayers = [...layersInvolvedUserPick.value]
            .map(l => Number(l))
            .sort((a, b) => a - b);

        const mainLayer = Number(firstLayerSelected.value) || sortedLayers[0];
        const excessLayers = sortedLayers.filter(l => l !== mainLayer);

        // --- MAIN LAYER SAVE ---
        const mainLayerData = formatLayerDataForDatabase(mainLayer);

        const mainPayload = {
            layer: `layer_${mainLayer}`,
            layer_data: mainLayerData,
        };

        const mainSaveRes = await axios.patch(
            `/api/mass-production/${mpcsbl.selectedFurnace}/${mpcsbl.selectedMassProd}/merge`,
            mainPayload
        );

        console.log('Main layer merged:', mainSaveRes.data);

        // --- EXCESS LAYERS SAVE ---
        for (const layer of excessLayers) {
            if (!layerInputs[layer]) continue;

            const excessLayerData = formatLayerDataForDatabase(layer);

            const excessPayload = {
                furnace: mpcsbl.selectedFurnace,
                mass_prod: mpcsbl.selectedMassProd,
                layer,
                layer_data: excessLayerData,
            };

            const excessSaveRes = await axios.post('/api/excess-layers/merge', excessPayload);

            console.log(`Excess layer ${layer} merged:`, excessSaveRes.data);
        }

    } catch (err) {
        console.error('Save failed:', err);
        toast.error('Database save failed!');
    } finally {
        showConfirmationPanel.value = false;
        resetData();
        toast.success('Saved Successfully!');
        Inertia.visit('/heat_treatment');
    }
};





// APPLYING Browser Session ----------------- APPLYING Browser Session

//useSessionStorage('mpcsbl',mpcsbl);


// APPLYING Browser Session ----------------- APPLYING Browser Session

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
    await getCurrentDataGrid();
    await fetchAllLotNoData();
});

</script>

<style scoped>
input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

@keyframes ping {
  75%, 100% {
    transform: scale(4);
    opacity: 0;
  }
}

.group-hover\:animate-ping:hover {
  animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}


</style>
