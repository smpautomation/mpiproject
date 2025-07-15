<template>
        <div class="flex flex-row justify-center px-4 py-2 mb-4 bg-gray-200 no-print">
            <button class="px-2 py-1 mt-2 text-white bg-gray-600 rounded-lg" @click="$inertia.visit('/reports')">back</button>
            <button class="px-2 py-1 mt-2 ml-16 text-black bg-gray-300 rounded-lg">Serial#: {{ printSerialNo }}</button>
            <button
                @click="handlePrint"
                class="px-2 py-1 mt-2 ml-16 text-white transition bg-yellow-500 rounded-lg hover:bg-yellow-600"
                >
                Print This Report
            </button>
        </div>
    <!-- Overlay -->
<!-- Fullscreen Overlay -->
    <div
    v-show="adjustStyling"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <!-- Loader Centered -->
        <DotsLoader />
    </div>
    <div ref="pdfContent" id="pdf-content" class="mx-auto print-container">
      <!-- A4 Layout Box -->
      <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page px-7">
        <!-- Report Title -->
        <div :class="adjustStyling ? 'mt-6' : 'mt-4 mb-2'" class="overflow-visible text-center bg-gray-300">
            <h1
                :class="{
                    'relative -top-3': adjustStyling // Forces the text outside the top bounds
                }"
                class="text-2xl font-bold"
            >
                GBDP&nbsp;MAGNETIC&nbsp;PROPERTY&nbsp;INSPECTION&nbsp;REPORT
            </h1>
        </div>
        <!-- Oven Heating Information -->
        <div class="flex flex-row justify-between">
            <p class="mb-2 text-xl font-semibold">Oven Heating Information</p>
            <span
                v-show="isAutomotive"
                class="flex items-center justify-center w-[70px] h-[40px] text-center bg-center bg-no-repeat"
                :style="{
                    backgroundImage: 'url(\'/photo/carmark_logo.png\')',
                    backgroundSize: '100%'
                }">
            </span>
        </div>

        <!-- Information Grid -->
        <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-3">
            <!-- First Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Oven Machine No:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printOvenMachineNo_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Time Loading:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printTimeLoading }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Temperature:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printTimeLoading_temp }}
                    </span>
                </div>
            </div>

            <!-- Second Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Date:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printDate_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Time Unloading:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printTimeUnloading }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Temperature:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printTimeUnloading_temp }}
                    </span>
                </div>
            </div>

            <!-- Third Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Shift:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printShift_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-sm font-semibold">Operator:</span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-sm border-b border-gray-500">
                        {{ printOperator_ovenInfo }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="mt-2" />

        <p class="mb-2 text-xl font-semibold">Magnetic Property Inspection Information</p>

        <!-- Single Row Information -->
        <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-4">
            <!-- First Column -->
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Model:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printActualModel }}
                </span>
            </div>

            <!-- Second Column -->
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-[10px]">MATERIAL&nbsp;CODE:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printMaterialCode }}
                </span>
            </div>

            <!-- Third Column -->
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Partial&nbsp;No.:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printPartialNo }}
                </span>
            </div>

            <!-- Fourth Column -->
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-[10px]">TOTAL&nbsp;QUANTITY:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printTotalQuantity }}
                </span>
            </div>
            <!-- Fifth Column -->
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Pulse&nbsp;Tracer&nbsp;No:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printPulseTracer }}
                </span>
            </div>

            <!-- Sixth Column -->
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Date:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printDate }}
                </span>
            </div>

            <!-- Seventh Column -->
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Shift:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printShift }}
                </span>
            </div>

            <!-- Eighth Column -->
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Operator:</span>
                <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                    {{ printOperator }}
                </span>
            </div>
        </div>

        <div class="flex flex-row gap-2">
            <div>
                <!-- Data Table -->
                <table class="w-full mb-6 text-sm border border-collapse border-gray-400">
                <thead>
                    <tr class="text-center bg-gray-200">
                        <th colspan="2" :class="{ 'leading-loose': adjustStyling }" class="border border-black px-2 py-[3.04px]">Standard&nbsp;Sample&nbsp;Dimention and&nbsp;Material&nbsp;Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(value, key) in standardSampleDimention" :key="key">
                        <td :class="{ 'leading-loose': adjustStyling }" class="border-l border-b border-r border-black px-4 py-[3.04px] text-xs whitespace-nowrap">{{ key }}</td>
                        <td :class="{ 'leading-loose': adjustStyling }" class="border-l border-b border-r border-black px-4 py-[3.04px] text-xs whitespace-nowrap">{{ value }}</td>
                    </tr>
                </tbody>
                </table>
            </div>
            <div>
                <!-- Data Table gggg-->
                <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                    <thead>
                        <tr class="text-center bg-gray-200">
                            <th colspan="7" :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[4.5px] text-sm">Magnetic Property Data</th>
                        </tr>
                        <tr class="text-center bg-gray-200">
                            <th class="border-l border-r border-black px-[3.04px] py-[3.04px]"></th> <!-- empty under Items -->
                            <th class="border-r border-black px-[3.04px] py-[3.04px]"></th> <!-- empty under Standard -->
                            <th colspan="5" :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[2.04px]">Actual Data</th>
                        </tr>
                        <tr class="text-center bg-gray-200">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-l border-r border-b border-black px-[3.04px] py-[3.04px]">Items</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px]">Standard</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px]">Average</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px]">Maximum</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px]">Minimum</th>
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border-b border-r border-black px-[3.04px] py-[3.04px]">Variance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td class="border-l border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">Br&nbsp;(G)</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printBrStandard }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printBrAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printBrMaximum }}</td>
                            <td class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printBrMinimum }}</td>
                            <td colspan="2" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printBrVariance }}</td>
                        </tr>
                        <tr class="text-center">
                            <td :rowspan="(noteReasonForReject.includes('- N.G iHc')) && showGX ? 2 : 1" :class="{ 'leading-loose': adjustStyling }" class="border-l border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">iHc (Oe)</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap"><span v-if="showGX">GM </span>{{ printiHcStandard }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHcAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHcMaximum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHcMinimum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printiHcVariance }}</td>
                        </tr>
                        <tr v-if="showGX && (noteReasonForReject.includes('- N.G iHc'))" class="text-center">
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-l border-b border-r border-black px-[3.04px] py-[3.04px] text-xs"><span v-if="showGX">GX </span>
                                {{ printGX_iHcStandard }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printGX_iHcAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printGX_iHcMaximum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printGX_iHcMinimum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printGX_iHcVariance }}</td>
                        </tr>
                        <tr class="text-center">
                            <td :class="{ 'leading-loose': adjustStyling }" :rowspan="(noteReasonForReject.includes('- N.G iHc')) && showGX ? 2 : 1" class="border-l border-b border-r border-black px-2 py-[3.04px] text-xs whitespace-nowrap">iHk (Oe)</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap"><span v-if="showGX">GM </span>{{ printiHkStandard }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHkAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHkMaximum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHkMinimum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border-b border-r border-black px-2 py-[3.04px] text-xs whitespace-nowrap">{{ printiHkVariance }}</td>
                        </tr>
                        <tr v-if="showGX && (noteReasonForReject.includes('- N.G iHc'))" class="text-center">
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-l border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap"><span v-if="showGX">GX </span> - - - - - </td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printGX_iHkAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printGX_iHkMaximum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printGX_iHkMinimum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printGX_iHkVariance }}</td>
                        </tr>
                        <tr v-if="showROB" class="text-center bg-gray-200">
                            <th colspan="7" :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[4.5px] text-sm">BH Tracer Measurement</th>
                        </tr>
                        <tr v-if="showROB" class="text-center bg-gray-200">
                            <th class="border-l border-r border-black px-[3.04px] py-[3.04px]"></th> <!-- empty under Items -->
                            <th class="border-r border-black px-[3.04px] py-[3.04px]"></th> <!-- empty under Standard -->
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Br Min</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Br Max</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">iHc Min</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">iHc Max</th>
                            <th class="border-r border-black px-[3.04px] py-[3.04px]"></th> <!-- empty under Standard -->
                        </tr>
                        <tr v-if="showROB" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-l border-r border-b border-black px-[3.04px] py-[3.04px] bg-gray-200 text-xs">ITEMS</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] bg-gray-200 text-xs">SPECS</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_brMin }}</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_brMax }}</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_iHcMin }}</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_iHcMax }}</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] bg-gray-200 text-xs">Result</th>
                        </tr>
                        <tr v-if="showROB" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling, 'p-0': true }" class="border-l border-r border-b border-black px-[3.04px] py-[3.04px] bg-gray-200 text-xs whitespace-nowrap">Br @ RT</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrRTstandard }} kg</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrRT_brMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrRT_brMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrRT_iHcMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrRT_iHcMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_remarks }}</td>
                        </tr>
                        <tr v-if="showROB" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-l border-r border-b border-black px-[3.04px] py-[3.04px] bg-gray-200 text-xs whitespace-nowrap">Br @ VT (180°C)</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrVTstandard }} kg</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrVT_brMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrVT_brMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrVT_iHcMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_BrVT_iHcMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_remarks }}</td>
                        </tr>
                        <tr v-if="showROB" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-l border-r border-b border-black px-[3.04px] py-[3.04px] bg-gray-200 text-xs whitespace-nowrap">HD5 (180°C)</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_HD5standard }} kOe</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_HD5_brMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_HD5_brMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_HD5_iHcMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_HD5_iHcMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_remarks }}</td>
                        </tr>
                        <tr v-if="showROB" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-l border-r border-b border-black px-[3.04px] py-[3.04px] bg-gray-200 text-xs whitespace-nowrap">JD5 (180°C)</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_JD5standard }} kG</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_JD5_brMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_JD5_brMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_JD5_iHcMin }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_JD5_iHcMax }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs">{{ printROB_remarks }}</td>
                        </tr>
                        <tr v-if="isTTM_model" class="text-center bg-gray-200">
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border-l border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap"></th> <!-- empty slot for rowspan -->
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">STD DEV</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Cp</th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Cpk &#8805; 1.00</th> <!-- &#8805; is greater than equal to symbol -->
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Remarks</th>
                        </tr>
                        <tr v-if="isTTM_model" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border-l border-b border-r border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap bg-gray-200">Computation of Cpk from Br</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs">{{ printStdDev }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs">{{ printCpk }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs">{{ printCp }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border border-black px-[3.04px] py-[3.04px] text-xs">{{ printCpkRemarks }}</td>
                        </tr>
                        <tr v-show="show1x1x1Data_withoutCorner" class="text-center bg-gray-200">
                            <td :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border border-black px-2 py-[3.04px] text-xs whitespace-nowrap">Data of 1x1x1 mm samples</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">AVERAGE</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">MAXIMUM</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">MINIMUM</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Cpk &#8805; {{ cpkStandardValue }}</td> <!-- &#8805; is a greater than equal to symbol -->
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs">Remarks</td>
                        </tr>
                        <tr v-show="show1x1x1Data_Corner" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap bg-gray-200">Corner</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">&#8805; {{ printCorner }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCornerAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCornerMaximum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCornerMinimum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCornerCpk }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCornerRemarks }}</td>
                        </tr>
                        <tr v-show="show1x1x1Data_withoutCorner" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap bg-gray-200">Surface</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">&#8805; {{ printSurface }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printSurfaceAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printSurfaceMaximum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printSurfaceMinimum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printSurfaceCpk }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printSurfaceRemarks }}</td>
                        </tr>
                        <tr v-show="show1x1x1Data_withoutCorner" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap bg-gray-200">Core</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">&#8805; {{ printCore }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCoreAverage }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCoreMaximum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCoreMinimum }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCoreCpk }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printCoreRemarks }}</td>
                        </tr>
                        <!-- VT Row 1: Sample + Remarks (Grid layout with 3 columns) -->
                        <tr v-if="showVTData && noteReasonForReject.includes('- N.G iHc')" class="text-center bg-gray-200">
                        <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="px-2 py-1 text-xs bg-gray-200 border border-black whitespace-nowrap">VT Data</th>

                        <th :class="{ 'leading-loose': adjustStyling }" colspan="3" class="px-2 py-1 text-xs bg-gray-200 border border-black whitespace-nowrap">
                            Samples
                        </th>

                        <th :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs bg-gray-200 border border-black whitespace-nowrap">Remarks</th>
                        </tr>

                        <tr v-if="showVTData && noteReasonForReject.includes('- N.G iHc')" class="text-center">
                        <td :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs border border-black whitespace-nowrap">
                            {{ printVT_Temp }} <span class="align-baseline">°C</span>
                        </td>
                        <td :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs border border-black whitespace-nowrap">
                            iHc (kOe) &#8805; {{ printVT_iHc }} (kOe)
                        </td>
                        <td colspan="3" class="px-0 py-0 text-xs border border-black whitespace-nowrap">
                            <table class="w-full border-collapse table-fixed">
                                <tr v-for="rowIndex in Math.ceil(printVT_samples.length / 3)" :key="'row-' + rowIndex">
                                <td
                                    v-for="colIndex in 3"
                                    :key="'col-' + colIndex"
                                    class="border border-gray-400 align-top px-[2.5px] font-extrabold py-[2.5px] text-center text-[9px] w-1/3"
                                >
                                    <template v-if="printVT_samples[(rowIndex - 1) * 3 + (colIndex - 1)]">
                                    <div class="bg-gray-100">
                                        {{ printVT_samples[(rowIndex - 1) * 3 + (colIndex - 1)] }}
                                        ({{ printVT_sampleRemarks[(rowIndex - 1) * 3 + (colIndex - 1)] }})
                                    </div>
                                    <div class="text-gray-700 border-t border-gray-300">
                                        {{ printVT_iHcResults[(rowIndex - 1) * 3 + (colIndex - 1)] }} kOe
                                    </div>
                                    </template>
                                </td>
                                </tr>
                            </table>
                        </td>
                        <td :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs border border-black whitespace-nowrap">{{ printVT_remarks }}</td>
                        </tr>
                        <tr v-show="showCpkFrom_iHc" class="bg-gray-200">
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap"></th>
                            <th :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">STD DEV</th>
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Cpk &#8805; {{ cpkStandardValue }}</th> <!-- &#8805; is greater than equal to symbol -->
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">Remarks</th>
                        </tr>
                        <tr v-show="showCpkFrom_iHc" class="text-center">
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap bg-gray-200">Computation of Cpk from iHc</th>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHc_cpk_info_stdDev }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" colspan="2" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHc_cpk_info_cpk }}</td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="border border-black px-[3.04px] py-[3.04px] text-xs whitespace-nowrap">{{ printiHc_cpk_info_remarks }}</td>
                        </tr>
                        <tr v-if="showBHData && noteReasonForReject.includes('- N.G iHc')" class="text-center bg-gray-200">
                            <th :class="{ 'leading-loose': adjustStyling }" colspan="2" class="px-2 py-1 text-xs bg-gray-200 border border-black whitespace-nowrap">BH Data @ {{ printBH_temp }} °C</th>

                            <th :class="{ 'leading-loose': adjustStyling }" colspan="3" class="px-2 py-1 text-xs bg-gray-200 border border-black whitespace-nowrap">
                                Samples
                            </th>

                            <th :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs bg-gray-200 border border-black whitespace-nowrap">Result</th>
                        </tr>

                        <tr v-if="showBHData && noteReasonForReject.includes('- N.G iHc')" class="text-center">
                            <td :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs border border-black whitespace-nowrap">
                                {{ printBH_data }} <span class="align-baseline"> kOe</span>
                            </td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs border border-black whitespace-nowrap">
                                &#8805; {{ printBH_dataStandard }} (kOe)
                            </td>
                            <td colspan="3" class="px-0 py-0 text-xs border border-black whitespace-nowrap">
                                <table class="w-full border-collapse table-fixed">
                                    <tr v-for="rowIndex in Math.ceil(printBH_sample.length / 3)" :key="'row-' + rowIndex">
                                    <td
                                        v-for="colIndex in 3"
                                        :key="'col-' + colIndex"
                                        class="border border-gray-400 align-top px-[2.5px] font-extrabold py-[2.5px] text-center text-[9px] w-1/3"
                                    >
                                        <template v-if="printBH_sample[(rowIndex - 1) * 3 + (colIndex - 1)]">
                                        <div class="bg-gray-100">
                                            {{ printBH_sample[(rowIndex - 1) * 3 + (colIndex - 1)] }}
                                        </div>
                                        <div class="text-gray-700 border-t border-gray-300">
                                            {{ printBH_result[(rowIndex - 1) * 3 + (colIndex - 1)] }} kOe
                                        </div>
                                        </template>
                                    </td>
                                    </tr>
                                </table>
                            </td>
                            <td :class="{ 'leading-loose': adjustStyling }" class="px-2 py-1 text-xs border border-black whitespace-nowrap">{{ printBH_remarks }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-2">
            <div class="flex flex-row items-center justify-center">
                <span :class="{ 'mb-14': adjustStyling }" class="mb-10 mr-2 text-sm font-semibold align-top">Remarks:</span>
                <div class="flex flex-col w-full">
                    <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                        {{ printRemarks2 }}
                    </span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                        {{ printRemarks3 }}
                    </span>
                    <span :class="{ 'leading-loose': adjustStyling }" class="inline-block w-full text-sm border-b border-gray-500">
                        {{ printRemarks }}
                    </span>
                </div>
                <div class="mx-10">
                    <span class="font-extrabold text-center"
                    :class="{
                        'text-red-500': printRemarksResult === 'E' || printRemarksResult === 'HOLD',
                        'text-blue-500': printRemarksResult !== 'E' && printRemarksResult !== 'HOLD'
                    }">{{ printRemarksResult }}</span>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="mt-2" />

        <div class="mb-2">
            <p :class="{ 'leading-loose': adjustStyling }" class="mb-2 text-xl font-semibold">SMP Judgement:</p>
            <div class="flex flex-row">
                <!-- SMP Judgement -->
                <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200">
                    <!-- Header acting as the title -->
                    <p class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                        SMP Judgement
                    </p>
                    <!-- Container for the stamp -->
                    <div class="relative flex items-center justify-center flex-grow w-full bg-white">
                        <!-- The stamp -->
                        <span
                            class="absolute w-[100px] h-[100px] bg-center bg-no-repeat"
                            :style="{
                                backgroundImage: printSMPJudgement === 'REJECT'
                                    ? 'url(\'/photo/reject_stamp.png\')'
                                    : printSMPJudgement === 'HOLD'
                                    ? 'url(\'/photo/hold_stamp.png\')'
                                    : printSMPJudgement == null ||
                                        printSMPJudgement == undefined ||
                                        printSMPJudgement == '' ||
                                        printSMPJudgement == 'null'
                                    ? 'url(\'/photo/template.png\')'
                                    : 'url(\'/photo/pass_stamp.png\')',
                                backgroundSize: 'contain'
                            }">
                        </span>
                    </div>
                </div>
                <!-- Prepared By -->
                <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200 ml-3">
                    <!-- Header acting as the title -->
                    <p :class="{ 'leading-loose': adjustStyling }" class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                        Prepared By
                    </p>
                    <!-- Container for the stamp -->
                    <div class="relative flex items-center justify-center flex-grow w-full bg-white">
                        <!-- The stamp -->
                        <span
                            class="absolute w-[100px] h-[100px] items-center flex flex-col text-red-600 font-extrabold bg-center bg-no-repeat justify-center"
                            :style="{
                                backgroundImage: (printPreparedBy_firstName != null && printPreparedBy_firstName !== '') || (printPreparedBy != null && printPreparedBy !== '')
                                    ? 'url(\'/photo/template.png\')'
                                    : 'none',
                                backgroundSize: 'contain'
                            }">
                            <span :class="getFontSize(printPreparedBy_firstName)">{{ printPreparedBy_firstName }}</span>
                            <span class="my-[0px]">{{ printPreparedBy_date }}</span>
                            <span :class="[dynamicClass_preparedby, getFontSize(printPreparedBy_lastName)]">
                                {{ printPreparedBy_lastName }}
                            </span>
                        </span>
                    </div>
                </div>
                <!-- Checked By -->
                <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200">
                    <!-- Header acting as the title -->
                    <p :class="{ 'leading-loose': adjustStyling }" class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                        Checked By
                    </p>
                    <!-- Container for the stamp -->
                    <div class="relative flex items-center justify-center flex-grow w-full bg-white">
                        <!-- The stamp -->
                        <span
                            class="absolute w-[100px] h-[100px] items-center flex flex-col text-red-600 font-extrabold bg-center bg-no-repeat justify-center"
                            :style="{
                                backgroundImage: (printCheckedBy_firstName != null && printCheckedBy_firstName !== '') || (printCheckedBy != null && printCheckedBy !== '')
                                    ? 'url(\'/photo/template.png\')'
                                    : 'none',
                                backgroundSize: 'contain'
                            }">
                            <span :class="getFontSize(printCheckedBy_firstName)">{{ printCheckedBy_firstName }}</span>
                            <span class="my-[0px]">{{ printCheckedBy_date }}</span>
                            <span :class="[dynamicClass_approvedby, getFontSize(printCheckedBy_lastName)]">
                                {{ printCheckedBy_lastName }}
                            </span>
                        </span>
                    </div>
                </div>
                <!-- Approved By -->
                <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200">
                    <!-- Header acting as the title -->
                    <p :class="{ 'leading-loose': adjustStyling }" class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                        Approved By
                    </p>
                    <!-- Container for the stamp -->
                    <div class="relative flex items-center justify-center flex-grow w-full bg-white">
                        <!-- The stamp -->
                        <span
                            class="absolute w-[100px] h-[100px] bg-center bg-no-repeat items-center flex flex-col text-red-600 font-extrabold justify-center"
                            :style="{
                                backgroundImage: (printApprovedBy_firstName != null && printApprovedBy_firstName !== '') || (printApprovedBy != null && printApprovedBy !== '')
                                    ? 'url(\'/photo/template.png\')'
                                    : 'none',
                                backgroundSize: 'contain'
                            }">
                            <span :class="getFontSize(printApprovedBy_firstName)">{{ printApprovedBy_firstName }}</span>
                            <span class="my-[0px]">{{ printApprovedBy_date }}</span>
                            <span :class="[dynamicClass_checkedby, getFontSize(printApprovedBy_lastName)]">
                                {{ printApprovedBy_lastName }}
                            </span>
                        </span>
                    </div>
                </div>
                <div class="ml-2">
                    <div class="flex flex-col p-1 border border-black">
                        <div class="text-[10px] pb-1 font-extrabold">Note: (REASON OF HOLD/REJECT)</div>
                        <p class="text-[10px] text-red-600 font-extrabold" v-if="noteReasonForReject.length">
                            <span v-for="(reason, index) in sortedNotes" :key="index" class="block">
                                {{ reason }}
                            </span>
                        </p>
                        <span v-else class="text-sm text-gray-500">No rejection notes available.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="mt-2" />


        <div>
            <p class="mb-2 text-xl font-semibold">SEC / C-Mark Judgement:</p>
            <div class="flex flex-row">
                <div class="min-w-[300px] min-h-[80px] border border-black">
                    <!-- empty -->
                </div>
                <div class="min-w-[400px] min-h-[80px] border border-black ml-3">
                    <p class="text-[10px] ml-1">
                        Note: (Reason of REJECTED/Content of Condition Approved)
                    </p>
                </div>
            </div>
        </div>

      </div>

        <!-- A4 Layout Box -->
        <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page px-7">
            <!-- Report Title -->
            <div :class="adjustStyling ? 'mt-6' : 'mt-4 mb-2'" class="overflow-visible text-center bg-gray-300">
                <h1
                    :class="{
                        'relative -top-3': adjustStyling // Forces the text outside the top bounds
                    }"
                    class="text-2xl font-bold"
                >
                    PROPERTY&nbsp;DATA
                </h1>
            </div>
            <!-- Information Grid -->
            <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-5">
                <!-- First Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Code&nbsp;No:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printCodeNo }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Sintering#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printSinteringNo }}
                        </span>
                    </div>
                </div>
                <!-- Second Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Type&nbsp;Code:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printTypeCode }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">coating:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printCoating }}
                        </span>
                    </div>
                </div>
                <!-- Third Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Judge&nbsp;Code:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printJudgeCode }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Pass#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printPassNo }}
                        </span>
                    </div>
                </div>
                <!-- Fourth Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Press#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printPressNo }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Mias.&nbsp;Employee:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printMiasEmployee }}
                        </span>
                    </div>
                </div>
                <!-- Fifth Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Sintering&nbsp;Furnace#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printSinteringFurnaceNo }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Factor&nbsp;Employee:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500 leading-loose">
                            {{ printFactoryEmployee }}
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                    <thead>
                        <tr class="text-center bg-gray-200">
                            <th class="px-1 py-1 text-[10px] border border-black">Zone</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Br</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">iHc</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">iHk</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">[BH]m</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Hr95</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Hr98</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">iHc&#8209;iHk</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Br&#960;Ia</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">bHc</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Squareness</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">4&#960;Id</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">4&#960;Is</th>
                            <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">4&#960;Ia</th>
                            <th class="px-1 py-1 text-[8px] border-r border-t border-b  border-black">Temperature</th>
                            <th class="px-1 py-1 text-[8px] border-r border-t border-b  border-black">Data&nbsp;Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in printTPMData"
                                                :key="item.id"
                                                class="text-center whitespace-nowrap text-[10px]">
                            <td class="border-l border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.zone }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.Br }}</td>
                            <td v-if="item.remark.Br_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.Br_remarks == '0' ? '' : item.remark.Br_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.iHc }}</td>
                            <td v-if="item.remark.iHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.iHc_remarks == '0' ? '' : item.remark.iHc_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.iHk }}</td>
                            <td v-if="item.remark.iHk_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.iHk_remarks == '0' ? '' : item.remark.iHk_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.BHMax }}</td>
                            <td v-if="item.remark.BHMax_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.BHMax_remarks == '0' ? '' : item.remark.BHMax_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.iHr95 }}</td>
                            <td v-if="item.remark.iHr95_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.iHr95_remarks == '0' ? '' : item.remark.iHr95_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.iHr98 }}</td>
                            <td v-if="item.remark.iHr98_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.iHr98_remarks == '0' ? '' : item.remark.iHr98_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.iHkiHc }}</td>
                            <td v-if="item.remark.iHkiHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.iHkiHc_remarks == '0' ? '' : item.remark.iHkiHc_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.Br4pai }}</td>
                            <td v-if="item.remark.Br4pai_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.Br4pai_remarks == '0' ? '' : item.remark.Br4pai_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.bHc }}</td>
                            <td v-if="item.remark.bHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.bHc_remarks == '0' ? '' : item.remark.bHc_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.Squareness }}</td>
                            <td v-if="item.remark.Squareness_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark.Squareness_remarks == '0' ? '' : item.remark.Squareness_remarks }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item["4paiIa"] }}</td>
                            <td v-if="item.remark['4paiIa_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark['4paiIa_remarks'] == '0' ? '' : item.remark['4paiIa_remarks'] }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item["4paiId"] }}</td>
                            <td v-if="item.remark['4paiId_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark['4paiId_remarks'] == '0' ? '' : item.remark['4paiId_remarks'] }}</td>
                            <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item["4paiIs"] }}</td>
                            <td v-if="item.remark['4paiIs_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                            <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ item.remark['4paiIs_remarks'] == '0' ? '' : item.remark['4paiIs_remarks'] }}</td>
                            <td class="border-r border-b border-black px-[2px] py-[2px] text-[10px]">{{ item.temperature }}</td>
                            <td class="border-r border-b border-black px-[2px] py-[2px] text-[10px]">{{ item.data_status }}</td>
                        </tr>
                        <tr>
                            <th class="px-1 py-1 text-xs bg-gray-200 border-b border-l border-r border-black">Average</th>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printBrAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printiHcAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printiHkAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printBHMaxAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printHr95Average }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printHr98Average }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printiHciHkAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printBrpaiIaAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printbHcAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ printSquarenessAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ print4paiIdAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px] text-center">{{ print4paiIsAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-[2px] text-[10px]">{{ print4paiIaAverage }}</td>
                            <td class="border-b border-r border-black"></td>
                        </tr>
                        <tr>
                            <th class="px-1 py-1 text-xs bg-gray-200 border-b border-l border-r border-black">Maximum</th>
                            <td class="border-b  border-black px-1 py-1 text-[10px] text-center">{{ printBrMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b  border-black px-1 py-1 text-[10px] text-center">{{ printiHcMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printiHkMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printBHMaxMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printHr95Maximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printHr98Maximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printiHciHkMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printBrpaiIaMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printbHcaxMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printSquarenessaxMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ print4paiIdMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ print4paiIsMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ print4paiIaMaximum }}</td>
                            <td class="border-b border-r border-black"></td>
                        </tr>
                        <tr>
                            <th class="px-1 py-1 text-xs bg-gray-200 border-b border-l border-r border-black">Minimum</th>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printBrMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printiHcMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printiHkMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printBHMaxMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printHr95Minimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printHr98Minimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printiHciHkMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printBrpaiIaMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printbHcaxMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ printSquarenessaxMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ print4paiIdMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ print4paiIsMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                            <td class="border-b border-black px-1 py-1 text-[10px]">{{ print4paiIaMinimum }}</td>
                            <td class="border-b border-r border-black"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- A4 Layout Box -->
        <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page p-7">
            <div class="flex flex-col my-6">
                <div class="flex flex-row">
                    <div class="mb-10 border border-black w-[480px] h-[360px]">
                        <canvas ref="myChartCanvas" width="750" height="550" style="transform: scale(1); transform-origin: top left;"></canvas>
                    </div>
                    <div>
                        <!-- Side Content -->
                        <div class="w-[250px] h-[360px] border border-black flex justify-center items-start ml-2 p-4">
                            <div class="flex flex-col items-start space-y-2">
                                <p class="font-semibold">Note:</p>
                                <p>
                                    SMP Lot (
                                    <span>{{ printMassProdName }}</span>
                                    Mass Production )
                                </p>
                                <p>
                                    Furnace Cycle No. : {{ printSinteringFurnaceNo + "-" + printSinteringNo }}
                                </p>
                                <p>
                                    <span>{{ printActualModel }}</span>
                                    ( {{ printCodeNo }} )
                                </p>
                                <p>
                                    Lot # {{ printJhCurveLotno }}
                                </p>
                                <div class="text-sm" v-if="noteReasonForReject.length">
                                    Remarks Encountered:
                                    <p class="text-[10px] text-red-600 font-semibold">
                                        <span>{{ noteReasonForReject.map(r => r.replace(/^\s*-\s*/, '')).join(', ') }}</span>
                                    </p>
                                </div>
                                <span v-else class="text-sm text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <table>
                        <thead>
                            <tr class="text-center bg-gray-200">
                                <th class="border  border-black px-[2px] py-1 text-[7px]">Date</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Code&nbsp;No</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Order&nbsp;No</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Type</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">LOT#</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Furnace#</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Coating#</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Zone</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Pass#</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Br</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">iHc</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">iHk</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">BHMax</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Hr95</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Hr98</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">iHkiHc</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Br4pai</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">bHc</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Squareness</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">4paiId</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">4paiIs</th>
                                <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">4paiIa</th>
                                <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7.5px]">Tracer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in printTPMData"
                                                :key="item.id"
                                                class="text-center whitespace-nowrap text-[10px]">
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-l border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.date }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r  border-black px-[2px] py-1 text-[7px]">{{ item.code_no }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.order_no }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.type }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.press_1 + " " + item.press_2 + " " + item.machine_no }}</td>
                                <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.sintering_furnace_no }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.furnace_no }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.zone }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.pass_no }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.Br }}</td>
                                <td v-if="item.remark.Br_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.Br_remarks == '0' ? '' : item.remark.Br_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.iHc }}</td>
                                <td v-if="item.remark.iHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.iHc_remarks == '0' ? '' : item.remark.iHc_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.iHk }}</td>
                                <td v-if="item.remark.iHk_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.iHk_remarks == '0' ? '' : item.remark.iHk_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.BHMax }}</td>
                                <td v-if="item.remark.BHMax_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.BHMax_remarks == '0' ? '' : item.remark.BHMax_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.iHr95 }}</td>
                                <td v-if="item.remark.iHr95_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.iHr95_remarks == '0' ? '' : item.remark.iHr95_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.iHr98 }}</td>
                                <td v-if="item.remark.iHr98_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.iHr98_remarks == '0' ? '' : item.remark.iHr98_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.iHkiHc }}</td>
                                <td v-if="item.remark.iHkiHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.iHkiHc_remarks == '0' ? '' : item.remark.iHkiHc_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.Br4pai }}</td>
                                <td v-if="item.remark.Br4pai_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.Br4pai_remarks == '0' ? '' : item.remark.Br4pai_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.bHc }}</td>
                                <td v-if="item.remark.bHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.bHc_remarks == '0' ? '' : item.remark.bHc_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.Squareness }}</td>
                                <td v-if="item.remark.Squareness_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark.Squareness_remarks == '0' ? '' : item.remark.Squareness_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item["4paiId"] }}</td>
                                <td v-if="item.remark['4paiId_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark['4paiId_remarks'] == '0' ? '' : item.remark['4paiId_remarks'] }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item["4paiIs"] }}</td>
                                <td v-if="item.remark['4paiIs_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark['4paiIs_remarks'] == '0' ? '' : item.remark['4paiIs_remarks'] }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item["4paiIa"] }}</td>
                                <td v-if="item.remark['4paiIa_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ item.remark['4paiIa_remarks'] == '0' ? '' : item.remark['4paiIa_remarks'] }}</td>
                                <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ item.Tracer }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- A4 Layout Box -->

        <div v-for="index in numberOfSet" :key="index">
                <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page px-7">
                    <!-- Report Title -->
                                <div :class="adjustStyling ? 'mt-6' : 'mt-4 mb-2'" class="overflow-visible text-center bg-gray-300">
                    <h1
                        :class="{
                            'relative -top-3': adjustStyling // Forces the text outside the top bounds
                        }"
                        class="text-2xl font-bold"
                    >
                        PROPERTY&nbsp;DATA <button v-if="!adjustStyling">Set #:{{ index }}</button>
                    </h1>
                </div>
                <div class="flex flex-row items-center justify-center mt-2 mb-5 text-blue-600">
                    <p class="text-sm">{{ additionalRemarks[index - 1] }}</p>
                </div>
                <!-- Information Grid -->
                <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-5">
                    <!-- First Column -->
                    <div class="space-y-1">
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Code&nbsp;No:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.codeNo }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Sintering#:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.sinteringNo }}
                            </span>
                        </div>
                    </div>
                    <!-- Second Column -->
                    <div class="space-y-1">
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Type&nbsp;Code:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.typeCode }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">coating:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.coating }}
                            </span>
                        </div>
                    </div>
                    <!-- Third Column -->
                    <div class="space-y-1">
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Judge&nbsp;Code:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.judgeCode }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Pass#:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.passNo }}
                            </span>
                        </div>
                    </div>
                    <!-- Fourth Column -->
                    <div class="space-y-1">
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Press#:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.pressNo }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Mias.&nbsp;Employee:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index - 1]?.tableTPM?.[0]?.mias_emp }}
                            </span>
                        </div>
                    </div>
                    <!-- Fifth Column -->
                    <div class="space-y-1">
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Sintering&nbsp;Furnace#:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index-1]?.sinteringFurnaceNo }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 text-xs font-semibold">Factor&nbsp;Employee:</span>
                            <span :class="{ 'leading-loose': adjustStyling }" class="flex-grow inline-block text-[10px] border-b border-gray-500">
                                {{ pagesData[index - 1]?.tableTPM?.[0]?.factorEmp }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                        <thead>
                            <tr class="text-center bg-gray-200">
                                <th class="px-1 py-1 text-[10px] border border-black">Zone</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Br</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">iHc</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">iHk</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">[BH]m</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Hr95</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Hr98</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">iHc&#8209;iHk</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Br&#960;Ia</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">bHc</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">Squareness</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">4&#960;Id</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">4&#960;Is</th>
                                <th colspan="2" class="px-1 py-1 text-[10px] border-r border-t border-b  border-black">4&#960;Ia</th>
                                <th class="px-1 py-1 text-[8px] border-r border-t border-b  border-black">Temperature</th>
                                <th class="px-1 py-1 text-[8px] border-r border-t border-b  border-black">Data&nbsp;Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(TPM, tpmIndex) in pagesData[index - 1]?.tableTPM"
                                                    :key="tpmIndex"
                                                    class="text-center whitespace-nowrap text-[10px]">
                                <td class="border-l border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.zone }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Br }}</td>
                                <td v-if="TPM.Br_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Br_remarks == '0' ? '' : TPM.Br_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHc }}</td>
                                <td v-if="TPM.iHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHc_remarks == '0' ? '' : TPM.iHc_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHk }}</td>
                                <td v-if="TPM.iHk_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHk_remarks == '0' ? '' : TPM.iHk_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.BHMax }}</td>
                                <td v-if="TPM.BHMax_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.BHMax_remarks == '0' ? '' : TPM.BHMax_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHr95 }}</td>
                                <td v-if="TPM.iHr95_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHr95_remarks == '0' ? '' : TPM.iHr95_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHr98 }}</td>
                                <td v-if="TPM.iHr98_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHr98_remarks == '0' ? '' : TPM.iHr98_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHkiHc }}</td>
                                <td v-if="TPM.iHkiHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHkiHc_remarks == '0' ? '' : TPM.iHkiHc_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Br4pai }}</td>
                                <td v-if="TPM.Br4pai_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Br4pai_remarks == '0' ? '' : TPM.Br4pai_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.bHc }}</td>
                                <td v-if="TPM.bHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.bHc_remarks == '0' ? '' : TPM.bHc_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Squareness }}</td>
                                <td v-if="TPM.Squareness_remarks == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Squareness_remarks == '0' ? '' : TPM.Squareness_remarks }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM["4paiIa"] }}</td>
                                <td v-if="TPM['4paiIa_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM['4paiIa_remarks'] == '0' ? '' : TPM['4paiIa_remarks'] }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM["4paiId"] }}</td>
                                <td v-if="TPM['4paiId_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM['4paiId_remarks'] == '0' ? '' : TPM['4paiId_remarks'] }}</td>
                                <td class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM["4paiIs"] }}</td>
                                <td v-if="TPM['4paiIs_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-[2px] text-[10px] text-red-500">E</td>
                                <td v-else class="border-b border-r border-black px-[2px] py-[2px] text-[10px]">{{ TPM['4paiIs_remarks'] == '0' ? '' : TPM['4paiIs_remarks'] }}</td>
                                <td class="border-r border-b border-black px-[2px] py-[2px] text-[10px]">{{ TPM.temperature }}</td>
                                <td class="border-r border-b border-black px-[2px] py-[2px] text-[10px]">{{ TPM.data_status }}</td>
                            </tr>
                            <!-- AVERAGE ROW -->
                            <tr>
                                <th class="px-1 py-1 text-xs bg-gray-200 border-b border-l border-r border-black">Average</th>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.brAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.iHcAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.iHkAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.bHMaxAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.hr95Average }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.hr98Average }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.iHciHkAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.brpaiIaAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.bHcAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.squarenessAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIdAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIsAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-[2px] text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIaAverage }}</td>
                                <td class="border-b border-r border-black"></td>
                            </tr>

                            <!-- MAXIMUM ROW -->
                            <tr>
                                <th class="px-1 py-1 text-xs bg-gray-200 border-b border-l border-r border-black">Maximum</th>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.brMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.iHcMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.iHkMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.bHMaxMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.hr95Maximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.hr98Maximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.iHciHkMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.brpaiIaMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.bHcMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.squarenessMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIdMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIsMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIaMaximum }}</td>
                                <td class="border-b border-r border-black"></td>
                            </tr>

                            <!-- MINIMUM ROW -->
                            <tr>
                                <th class="px-1 py-1 text-xs bg-gray-200 border-b border-l border-r border-black">Minimum</th>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.brMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.iHcMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.iHkMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.bHMaxMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.hr95Minimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.hr98Minimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.iHciHkMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.brpaiIaMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.bHcMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.squarenessMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIdMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIsMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                                <td class="border-b border-black px-1 py-1 text-[10px] text-center">{{ pagesData[index-1]?.fourpaiIaMinimum }}</td>
                                <td class="border-b border-r border-black"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-for="index in numberOfSet" :key="index">
            <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page p-7">
                <div class="flex flex-col my-6">
                    <button v-if="!adjustStyling" :class="{ 'leading-loose': adjustStyling }">Set #{{ index }}</button>
                    <div class="flex flex-row">
                        <div class="mb-10 border border-black w-[480px] h-[360px]">
                            <canvas
                                :ref="el => nsa_myChartCanvasRefs[index - 1] = el"
                                width="750"
                                height="550"
                                style="transform: scale(1); transform-origin: top left;"
                            ></canvas>
                        </div>
                        <div>
                            <!-- Side Content -->
                            <div class="w-[250px] h-[360px] border border-black flex justify-center items-start ml-2 p-4">
                                <div class="flex flex-col items-start space-y-2">
                                    <p class="font-semibold">Note:</p>
                                    <p>
                                        SMP Lot (
                                         <span>{{ pagesData[index - 1]?.massProd ?? 'N/A' }}</span>
                                        Mass Production )
                                    </p>
                                    <p>
                                        Furnace Cycle No. : {{ pagesData[index - 1]?.sinteringFurnaceNo  ?? 'N/A' + "-" + pagesData[index - 1]?.sinteringNo ?? 'N/A' }}
                                    </p>
                                    <p>
                                        <span>{{ pagesData[index - 1]?.actualModel ?? 'N/A' }}</span>
                                        ( {{ pagesData[index - 1]?.codeNo ?? 'N/A' }} )
                                    </p>
                                    <p>
                                        Lot # {{ pagesData[index - 1]?.jhcurveLotNo ?? 'N/A' }}
                                    </p>
                                    <div class="text-sm" v-if="nsa_noteReasonForReject.length">
                                        Remarks Encountered:
                                        <p class="text-[10px] text-red-600 font-extrabold">
                                            <span>{{ nsa_noteReasonForReject.map(r => r.replace(/^\s*-\s*/, '')).join(', ') }}</span>
                                        </p>
                                    </div>
                                    <span v-else class="text-sm text-gray-500"></span>
                                    <div>
                                        <p class="mt-2 text-sm text-blue-600">{{ additionalRemarks[index - 1] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <table>
                            <thead>
                                <tr class="text-center bg-gray-200">
                                    <th class="border  border-black px-[2px] py-1 text-[7px]">Date</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Code&nbsp;No</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Order&nbsp;No</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Type</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">LOT#</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Furnace#</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Coating#</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Zone</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Pass#</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Br</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">iHc</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">iHk</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">BHMax</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Hr95</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Hr98</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">iHkiHc</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Br4pai</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">bHc</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">Squareness</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">4paiId</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">4paiIs</th>
                                    <th colspan="2" class="border-b border-t border-r border-black px-[2px] py-1 text-[7px]">4paiIa</th>
                                    <th class="border-b border-t border-r border-black px-[2px] py-1 text-[7.5px]">Tracer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(TPM, tpmIndex) in pagesData[index - 1]?.tableTPM"
                                                    :key="tpmIndex"
                                                    class="text-center whitespace-nowrap text-[10px]">
                                    <td :class="{ 'leading-loose': adjustStyling }" class="border-l border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.date }}</td>
                                    <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r  border-black px-[2px] py-1 text-[7px]">{{ TPM.codeNo }}</td>
                                    <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.orderNo }}</td>
                                    <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.type }}</td>
                                    <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.lotNo }}</td>
                                    <td :class="{ 'leading-loose': adjustStyling }" class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.furnaceNo }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.coatingNo }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.zone }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.passNo }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.Br }}</td>
                                    <td v-if="TPM.Br_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.Br_remarks == '0' ? '' : TPM.Br_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.iHc }}</td>
                                    <td v-if="TPM.iHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.iHc_remarks == '0' ? '' : TPM.iHc_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.iHk }}</td>
                                    <td v-if="TPM.iHk_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.iHk_remarks == '0' ? '' : TPM.iHk_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.BHMax }}</td>
                                    <td v-if="TPM.BHMax_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.BHMax_remarks == '0' ? '' : TPM.BHMax_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.iHr95 }}</td>
                                    <td v-if="TPM.iHr95_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.iHr95_remarks == '0' ? '' : TPM.iHr95_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.iHr98 }}</td>
                                    <td v-if="TPM.iHr98_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.iHr98_remarks == '0' ? '' : TPM.iHr98_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.iHkiHc }}</td>
                                    <td v-if="TPM.iHkiHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.iHkiHc_remarks == '0' ? '' : TPM.iHkiHc_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.Br4pai }}</td>
                                    <td v-if="TPM.Br4pai_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.Br4pai_remarks == '0' ? '' : TPM.Br4pai_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.bHc }}</td>
                                    <td v-if="TPM.bHc_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.bHc_remarks == '0' ? '' : TPM.bHc_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.Squareness }}</td>
                                    <td v-if="TPM.Squareness_remarks == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM.Squareness_remarks == '0' ? '' : TPM.Squareness_remarks }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM["4paiId"] }}</td>
                                    <td v-if="TPM['4paiId_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM['4paiId_remarks'] == '0' ? '' : TPM['4paiId_remarks'] }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM["4paiIs"] }}</td>
                                    <td v-if="TPM['4paiIs_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM['4paiIs_remarks'] == '0' ? '' : TPM['4paiIs_remarks'] }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM["4paiIa"] }}</td>
                                    <td v-if="TPM['4paiIa_remarks'] == '1'" class="border-b border-r border-black px-[2px] py-1 text-[7px] text-red-500 font-semibold">E</td>
                                    <td v-else class="border-b border-r border-black px-[2px] py-py-1 text-[7px]">{{ TPM['4paiIa_remarks'] == '0' ? '' : TPM['4paiIa_remarks'] }}</td>
                                    <td class="border-b border-r border-black px-[2px] py-1 text-[7px]">{{ TPM.tracer }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Chart, registerables } from 'chart.js'; // Import all required components
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';
import DotsLoader from '@/Components/DotsLoader.vue';
import { useAuth } from '@/Composables/useAuth.js'

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

const adjustStyling = ref(false);

watch(adjustStyling, (newVal, oldVal) => {
  console.log('adjustStyling changed:', oldVal, '→', newVal);
  // react to the change here
}, { immediate: true });

const printMassProd = ref('')
const pdfContent = ref(null)
const success = ref(false)

// Register all Chart.js components using registerables
Chart.register(...registerables);

const pdfFinalizeLogging = async (logEvent) => {
    try{
        const responseUserLogging = await axios.post('/api/userlogs', {
            user: 'System',
            event: logEvent,
            section: 'PDF File',
        });

        //console.log('responseUserLogging-data: ',responseUserLogging.data);
    }catch(error){
        console.error('pdfFinalizeLogging post request failed: ',error);
    }
}

const numberOfSet = ref(0); // or whatever number you want
// Watcher with immediate: true
watch(numberOfSet, (newVal, oldVal) => {
  console.log('numberOfSet changed:', oldVal, '→', newVal);
}, {
  immediate: true
});
const testing = ref("");
const isAutomotive = ref(false);
// You could pass these via props or retrieve via an API

const MODELS_SHOW_VT_DATA = ref([]) ;
const MODELS_1X1X1_NO_CORNER = ref([]);
const MODELS_SHOW_CPK = ref([]);
const MODELS_SHOW_GX = ref([]);
const MODELS_SHOW_BH = ref([]);
const MODELS_SHOW_ROB = ref([]);

const printSetNo = ref ('');
const printOvenMachineNo_ovenInfo = ref('N/A');
const printTimeLoading = ref('N/A');
const printTimeLoading_temp = ref('N/A');
const printTimeUnloading = ref('N/A');
const printTimeUnloading_temp = ref('N/A');
const printDate_ovenInfo = ref('N/A');
const printShift_ovenInfo = ref('N/A');
const printOperator_ovenInfo = ref('N/A');

const printActualModel = ref('N/A');
const printMaterialCode = ref('N/A');
const printPartialNo = ref('N/A');
const printTotalQuantity = ref('N/A');
const printPulseTracer = ref('N/A');
const printDate = ref('N/A');
const printShift = ref('N/A');
const printOperator = ref('N/A');

const printLength = ref('N/A');
const printWidth = ref('N/A');
const printThickness = ref('N/A');
const printMaterialGrade = ref('N/A');
const printMPISampleQty = ref('N/A');

const printBrStandard = ref('N/A');
const printiHcStandard = ref('N/A');
const printiHkStandard = ref('N/A');
const printBrAverage = ref('N/A');
const printiHcAverage = ref('N/A');
const printiHkAverage = ref('N/A');
const printBrMaximum = ref('N/A');
const printiHcMaximum = ref('N/A');
const printiHkMaximum = ref('N/A');
const printBrMinimum = ref('N/A');
const printiHcMinimum = ref('N/A');
const printiHkMinimum = ref('N/A');
const printBrVariance = ref('N/A');
const printiHcVariance = ref('N/A');
const printiHkVariance = ref('N/A');

const printROB_brMax = ref('');
const printROB_brMin = ref('');
const printROB_iHcMax = ref('');
const printROB_iHcMin = ref('');
const printROB_BrRTstandard = ref(13.0);
const printROB_BrVTstandard = ref(10.5);
const printROB_HD5standard = ref(10.053);
const printROB_JD5standard = ref(9.6);
const printROB_BrRT_brMin = ref(0);
const printROB_BrRT_brMax = ref(0);
const printROB_BrRT_iHcMin = ref(0);
const printROB_BrRT_iHcMax = ref(0);
const printROB_BrVT_brMin = ref(0);
const printROB_BrVT_brMax = ref(0);
const printROB_BrVT_iHcMin = ref(0);
const printROB_BrVT_iHcMax = ref(0);
const printROB_HD5_brMin = ref(0);
const printROB_HD5_brMax = ref(0);
const printROB_HD5_iHcMin = ref(0);
const printROB_HD5_iHcMax = ref(0);
const printROB_JD5_brMin = ref(0);
const printROB_JD5_brMax = ref(0);
const printROB_JD5_iHcMin = ref(0);
const printROB_JD5_iHcMax = ref(0);
const printROB_remarks = ref('NA');

const nsa_printActualModel = ref('N/A');

const nsa_printBrStandard = ref('N/A');
const nsa_printiHcStandard = ref('N/A');
const nsa_printiHkStandard = ref('N/A');
const nsa_printBrAverage = ref('N/A');
const nsa_printiHcAverage = ref('N/A');
const nsa_printiHkAverage = ref('N/A');
const nsa_printBrMaximum = ref('N/A');
const nsa_printiHcMaximum = ref('N/A');
const nsa_printiHkMaximum = ref('N/A');
const nsa_printBrMinimum = ref('N/A');
const nsa_printiHcMinimum = ref('N/A');
const nsa_printiHkMinimum = ref('N/A');

const printRemarks = ref('N/A');
const printRemarks2 = ref(' - ');
const printRemarks3 = ref(' - ');
const printRemarksResult = ref('N/A');

const printSMPJudgement = ref('');
const printPreparedBy = ref('');
const printPreparedBy_date = ref('');
const printCheckedBy = ref('');
const printCheckedBy_date = ref('');
const printApprovedBy = ref('');
const printApprovedBy_date = ref('');

const priorityOrder = {
  '- LOW BR': 1,
  '- HIGH BR': 1,
  '- N.G iHc': 2,
  '- iHc Below Target+500 Oe': 2,
  '- N.G iHk': 3,
  '- N.G Hr95': 4,
  '- N.G Hr98': 5,
  '- N.G iHc-iHk': 6,
  '- N.G Br-4PIa': 7,
  '- N.G bHc': 8,
};

const noteReasonForReject = ref([]);

const sortedNotes = computed(() => {
  return noteReasonForReject.value.slice().sort((a, b) => {
    const aPriority = priorityOrder[a] || 99;
    const bPriority = priorityOrder[b] || 99;
    return aPriority - bPriority;
  });
});

const inspectionDataList = ref([]);
const inspectionBrStandard = ref();
const inspectioniHcStandard = ref();
const inspectioniHkStandard = ref();

const nsa_noteReasonForReject = ref([]);

const printTPMData = ref([]);

const printCodeNo = ref('');
const printTypeCode = ref('');
const printJudgeCode = ref('');
const printPressNo = ref('');
const printSinteringFurnaceNo = ref('');
const printSinteringNo = ref('');
const printCoating = ref('');
const printPassNo = ref('');
const printMiasEmployee = ref('');
const printFactoryEmployee = ref('');

const printBHMaxAverage = ref('N/A');
const printBHMaxMaximum = ref('N/A');
const printBHMaxMinimum = ref('N/A');
const printHr95Average = ref('N/A');
const printHr95Maximum = ref('N/A');
const printHr95Minimum = ref('N/A');
const printHr98Average = ref('N/A');
const printHr98Maximum = ref('N/A');
const printHr98Minimum = ref('N/A');
const printiHciHkAverage = ref('N/A');
const printiHciHkMaximum = ref('N/A');
const printiHciHkMinimum = ref('N/A');
const printBrpaiIaAverage = ref('N/A');
const printBrpaiIaMaximum = ref('N/A');
const printBrpaiIaMinimum = ref('N/A');
const printbHcAverage = ref('N/A');
const printbHcaxMaximum = ref('N/A');
const printbHcaxMinimum = ref('N/A');
const printSquarenessAverage = ref('N/A');
const printSquarenessaxMaximum = ref('N/A');
const printSquarenessaxMinimum = ref('N/A');
const print4paiIdAverage = ref('N/A');
const print4paiIdMaximum = ref('N/A');
const print4paiIdMinimum = ref('N/A');
const print4paiIsAverage = ref('N/A');
const print4paiIsMaximum = ref('N/A');
const print4paiIsMinimum = ref('N/A');
const print4paiIaAverage = ref('N/A');
const print4paiIaMaximum = ref('N/A');
const print4paiIaMinimum = ref('N/A');

const printMassProdName = ref('N/A');
const printJhCurveLotno = ref('N/A');

const additionalRemarks = ref([]);

const cpkStandardValue = ref(1.33);
const printStdDev = ref(0);
const printCp = ref(0);
const printCpk = ref(0);
const printCpkRemarks = ref('N/A');

//variables from models with special judgement
//from data 1x1x1
const printCore = ref(0);
const printCorner = ref(0);
const printSurface = ref(0);
const printCoreCpk = ref(0);
const printCornerCpk = ref(0);
const printSurfaceCpk = ref(0);
const printCoreAverage = ref(0);
const printCornerAverage = ref(0);
const printSurfaceAverage = ref(0);
const printCoreMaximum = ref(0);
const printCornerMaximum = ref(0);
const printSurfaceMaximum = ref(0);
const printCoreMinimum = ref(0);
const printCornerMinimum = ref(0);
const printSurfaceMinimum = ref(0);
const printCoreRemarks = ref('N/A');
const printCornerRemarks = ref('N/A');
const printSurfaceRemarks = ref('N/A');

//from data VT
const printVT_iHc = ref(0);
const printVT_Temp = ref(0);
const printVT_samples = ref([]);
const printVT_sampleRemarks = ref([]);
const printVT_iHcResults = ref([]);
const printVT_remarks = ref('N/A');
const printVT_sampleQty = ref(0);

//from data iHc_cpk
const printiHc_cpk_info_cpk = ref(0);
const printiHc_cpk_info_remarks = ref('N/A');
const printiHc_cpk_info_stdDev = ref(0);

//from data GX
const printGX_iHcAverage = ref(0);
const printGX_iHcMaximum = ref(0);
const printGX_iHcMinimum = ref(0);
const printGX_iHcStandard = ref(0);
const printGX_iHcVariance = ref(0);
const printGX_iHkAverage = ref(0);
const printGX_iHkMaximum = ref(0);
const printGX_iHkMinimum = ref(0);
const printGX_iHkVariance = ref(0);

//from data BH
const printBH_data = ref("N/A");
const printBH_dataStandard = ref(0);
const printBH_temp = ref(0);
const printBH_result = ref(0);
const printBH_sample = ref("N/A");
const printBH_remarks = ref("N/A");

//special judgement UI variables

const isTTM_model = ref(false);
const show1x1x1Data_withoutCorner = ref(false);
const show1x1x1Data_Corner = ref(false);
const showVTData = ref(false);
const showVTData_default = ref(false);
const showCpkFrom_iHc = ref(false);
const showGX = ref(false);
const showBHData = ref(false);
const showROB = ref(false);

//special judgement UI variables end

const printPreparedBy_firstName = ref('');
const printPreparedBy_lastName = ref('');
const printCheckedBy_firstName = ref('');
const printCheckedBy_lastName = ref('');
const printApprovedBy_firstName = ref('');
const printApprovedBy_lastName = ref('');

// Method to dynamically adjust font size based on string length
const getFontSize = (name) => {
  const length = name.length;

    if (length <= 4) return 'text-[23px]';         // Very short names
    if (length === 5) return 'text-[22px]';
    if (length === 6) return 'text-[18px]';
    if (length === 7) return 'text-[12px]';
    if (length === 8) return 'text-[14px]';         // Mid point
    if (length === 9) return 'text-[13px]';
    if (length === 10) return 'text-[12px]';
    if (length === 11) return 'text-[12px]';
    if (length === 12) return 'text-[12px] mt-4 mb-1';
    if (length === 13) return 'text-[11px]';
  return 'text-[14px]';                          // 12 or more characters
};

const dynamicClass_preparedby = computed(() => {
  return [
    getFontSize(printPreparedBy_lastName),
    { 'pb-5': adjustStyling.value }
  ];
});

const dynamicClass_checkedby = computed(() => {
  return [
    getFontSize(printCheckedBy_lastName),
    { 'pb-5': adjustStyling.value }
  ];
});

const dynamicClass_approvedby = computed(() => {
  return [
    getFontSize(printApprovedBy_lastName),
    { 'pb-5': adjustStyling.value }
  ];
});

const standardSampleDimention = computed(() => ({
  'LENGTH (mm)': printLength.value,
  'WIDTH (mm)': printWidth.value,
  'THICKNESS (mm)': printThickness.value,
  'MATERIAL GRADE': printMaterialGrade.value,
  'MPI SAMPLE QTY.': printMPISampleQty.value,
}));

const checkSpecialJudgement = async () => {

    const responseGetVTData = await axios.get('/api/vt-models');
    const fetchAllVT = responseGetVTData.data;
    MODELS_SHOW_VT_DATA.value = fetchAllVT.map(item => item.model_name);
    console.log('VT MODELS: ',MODELS_SHOW_VT_DATA.value);
    const responseGetCPKIHCData = await axios.get('/api/cpk-ihc-models');
    const fetchAllCPKIHC = responseGetCPKIHCData.data;
    MODELS_SHOW_CPK.value = fetchAllCPKIHC.map(item => item.model_name);
    console.log('CPK MODELS: ',MODELS_SHOW_CPK.value);
    const responseGetGXData = await axios.get('/api/gx-models');
    const fetchAllGX = responseGetGXData.data;
    MODELS_SHOW_GX.value = fetchAllGX.map(item => item.model_name);
    console.log('GX MODELS: ',MODELS_SHOW_GX.value);
    const responseGetTTMNCData = await axios.get('/api/ttmnc-models');
    const fetchAllTTMNC = responseGetTTMNCData.data;
    MODELS_1X1X1_NO_CORNER.value = fetchAllTTMNC.map(item => item.model_name);
    console.log('TTMNC MODELS: ',MODELS_1X1X1_NO_CORNER.value);
    const responseGetBHData = await axios.get('/api/bh-models');
    const fetchAllBH = responseGetBHData.data;
    MODELS_SHOW_BH.value = fetchAllBH.map(item => item.model_name);
    console.log('BH MODELS: ',MODELS_SHOW_BH.value);
    const responseGetROBData = await axios.get('/api/rob-models');
    const fetchAllROB = responseGetROBData.data;
    MODELS_SHOW_ROB.value = fetchAllROB.map(item => item.model_name);
    console.log('ROB MODELS: ',MODELS_SHOW_ROB.value);

    const model = printActualModel.value;

    if (MODELS_SHOW_ROB.value.includes(model))  showROB.value = true;
    const hasNGihc = noteReasonForReject.value.includes('- N.G iHc');
    if (!hasNGihc) return;


    // === Logic Blocks ===

    if (MODELS_SHOW_VT_DATA.value.includes(model) && printVT_sampleQty.value > 0) {
        showVTData.value = true;
        showVTData_default.value = false;
    }else if(MODELS_SHOW_VT_DATA.value.includes(model)){
        showVTData.value = false;
        showVTData_default.value = true;
    }

    if (model.includes("TTM") || MODELS_1X1X1_NO_CORNER.value.includes(model)) {
        show1x1x1Data_withoutCorner.value = true;
        isTTM_model.value = model.includes("TTM");

        if (model.includes("TTM") && !["TTM0A58D", "TTM0C16D"].includes(model)) {
            show1x1x1Data_Corner.value = true;
        }
    }

    if (MODELS_SHOW_CPK.value.includes(model))  showCpkFrom_iHc.value = true;
    if (MODELS_SHOW_GX.value.includes(model))   showGX.value = true;
    if (MODELS_SHOW_BH.value.includes(model))   showBHData.value = true;
};

const dataFrom_reportdata = async () => {
    try{
        const responseReport = await axios.get("/api/reportdata");
        //console.log('API GET request-responseReport',responseReport.data);
        const reportData = responseReport.data.data.filter(column => column.tpm_data_serial == printSerialNo.value); // filter by serial
        //console.log("reportData-data: ",reportData);
        const rd = reportData[0];

        isAutomotive.value = rd.withCarmark == 1;

        //Under Oven Heating Information
        printOvenMachineNo_ovenInfo.value = rd.oven_machine_no || 'N/A';
        //console.log("printOvenMachineNo: ",printOvenMachineNo_ovenInfo.value);
        printTimeLoading.value = rd.time_loading || 'N/A';
        printTimeLoading_temp.value = rd.temp_time_loading || 'N/A';
        printTimeUnloading.value = rd.time_unloading || 'N/A';
        printTimeUnloading_temp.value = rd.temp_time_unloading || 'N/A';
        printDate_ovenInfo.value = rd.date_oven_info || 'N/A';
        printShift_ovenInfo.value = rd.shift_oven_info || 'N/A';
        printOperator_ovenInfo.value = rd.operator_oven_info || 'N/A';

        //Under Magnetic Property Inspection
        printActualModel.value = rd.model || 'N/A';
        nsa_printActualModel.value = rd.model || 'N/A';
        printMaterialCode.value = rd.material_code || 'N/A';
        printPartialNo.value = rd.partial_number || 'N/A';
        printPulseTracer.value = rd.pulse_tracer_machine_number || 'N/A';
        printDate.value = rd.date || 'N/A';
        printShift.value = rd.shift || 'N/A';
        printOperator.value = rd.operator || 'N/A';
        printTotalQuantity.value = rd.total_quantity || 'N/A';
        printRemarks.value = rd.remarks || 'N/A';
        printRemarks2.value = rd.remarks2 || 'N/A';
        printRemarks3.value = rd.remarks3 || 'N/A';
        printRemarksResult.value = rd.remarks_display || 'N/A';

        //Under Standard Sample Dimention and Material Grade
        printLength.value = rd.length;
        printWidth.value = rd.width;
        printThickness.value = rd.thickness;
        printMaterialGrade.value = rd.material_grade;
        printMPISampleQty.value = rd.mpi_sample_quantity;
        //console.log("StandardSampleDimention array: ",standardSampleDimention.value);

        printStdDev.value = rd.std_dev;
        printCp.value = rd.cp;
        printCpk.value = rd.cpk;
        printCpkRemarks.value = rd.br_cpk_remarks;

        //Under Magnetic Property Data
        const mPD = JSON.parse(rd.magnetic_property_data);
        const nRR = JSON.parse(rd.note_reason_reject);

        printBrStandard.value = mPD.brStandard || '----';
        printBrAverage.value = mPD.brAverage || '----';
        printBrMaximum.value = mPD.brMaximum || '----';
        printBrMinimum.value = mPD.brMinimum || '----';

        printiHcStandard.value = mPD.ihcStandard || '----';
        printiHcAverage.value = mPD.ihcAverage || '----';
        printiHcMaximum.value = mPD.ihcMaximum || '----';
        printiHcMinimum.value = mPD.ihcMinimum || '----';

        printiHkStandard.value = mPD.ihkStandard || '----';
        printiHkAverage.value = mPD.ihkAverage || '----';
        printiHkMaximum.value = mPD.ihkMaximum || '----';
        printiHkMinimum.value = mPD.ihkMinimum || '----';

        //Under special judgements
        const d1x1x1 = JSON.parse(rd.data_1x1x1_info);
        const dVT = JSON.parse(rd.data_VT_info);
        const diHc_cpk = JSON.parse(rd.data_iHc_cpk_info);
        const dGX = JSON.parse(rd.data_GX_info);
        const dBH = JSON.parse(rd.data_bh_info);
        const rob = JSON.parse(rd.data_ROB_info);

        printCore.value = d1x1x1.core || '----';
        printCorner.value = d1x1x1.corner || '----';
        printSurface.value = d1x1x1.surface || '----';
        printCoreCpk.value = d1x1x1.core_cpk || '----';
        printCornerCpk.value = d1x1x1.corner_cpk || '----';
        printSurfaceCpk.value = d1x1x1.surface_cpk || '----';
        printCoreAverage.value = d1x1x1.core_average || '----';
        printCornerAverage.value = d1x1x1.corner_average || '----';
        printSurfaceAverage.value = d1x1x1.surface_average || '----';
        printCoreMaximum.value = d1x1x1.core_maximum || '----';
        printCornerMaximum.value = d1x1x1.corner_maximum || '----';
        printSurfaceMaximum.value = d1x1x1.surface_maximum || '----';
        printCoreMinimum.value = d1x1x1.core_minimum || '----';
        printCornerMinimum.value = d1x1x1.corner_minimum || '----';
        printSurfaceMinimum.value = d1x1x1.surface_minimum || '----';
        printCoreRemarks.value = d1x1x1.core_remarks || '----';
        printCornerRemarks.value = d1x1x1.corner_remarks || '----';
        printSurfaceRemarks.value = d1x1x1.surface_remarks || '----';

        printVT_iHc.value = dVT.iHc || '----';
        printVT_Temp.value = dVT.temp || '----';
        printVT_samples.value = Array.isArray(dVT.sample) ? dVT.sample : [];
        printVT_sampleRemarks.value = Array.isArray(dVT.sample_remarks) ? dVT.sample_remarks : [];
        printVT_iHcResults.value = Array.isArray(dVT.iHcResult) ? dVT.iHcResult : [];
        printVT_remarks.value = dVT.remarks || '----';
        printVT_sampleQty.value = dVT.sample_qty || '----';

        printiHc_cpk_info_cpk.value = diHc_cpk.cpk || '----';
        printiHc_cpk_info_remarks.value = diHc_cpk.remarks || '----';
        printiHc_cpk_info_stdDev.value = diHc_cpk.std_dev || '----';

        printGX_iHcAverage.value = dGX.iHcAverage || '----';
        printGX_iHcMaximum.value = dGX.iHcMaximum || '----';
        printGX_iHcMinimum.value = dGX.iHcMinimum || '----';
        printGX_iHcStandard.value = dGX.iHcStandard || '----';
        printGX_iHcVariance.value = dGX.iHcVariance || '----';
        printGX_iHkAverage.value = dGX.iHkAverage || '----';
        printGX_iHkMaximum.value = dGX.iHkMaximum || '----';
        printGX_iHkMinimum.value = dGX.iHkMinimum || '----';
        printGX_iHkVariance.value = dGX.iHkVariance || '----';

        printBH_data.value = dBH.data || '----';
        printBH_dataStandard.value = dBH.dataStandard || '----';
        printBH_temp.value = dBH.temp || '----';
        printBH_result.value = dBH.result || '----';
        printBH_sample.value = dBH.sample || '----';
        printBH_remarks.value = dBH.remarks || '----';

        printROB_brMin.value = rob.brMin || '----';
        printROB_brMax.value = rob.brMax || '----';
        printROB_iHcMin.value = rob.iHcMin || '----';
        printROB_iHcMax.value = rob.iHcMax || '----';
        printROB_BrRTstandard.value = rob.brRTStandard || '----';
        printROB_BrVTstandard.value = rob.brVTStandard || '----';
        printROB_HD5standard.value = rob.hd5Standard || '----';
        printROB_JD5standard.value = rob.jd5Standard || '----';
        printROB_BrRT_brMin.value = rob.brRT_brMin || '----';
        printROB_BrRT_brMax.value = rob.brRT_brMax || '----';
        printROB_BrRT_iHcMin.value = rob.brRT_iHcMin || '----';
        printROB_BrRT_iHcMax.value = rob.brRT_iHcMax || '----';
        printROB_BrVT_brMin.value = rob.brVT_brMin || '----';
        printROB_BrVT_brMax.value = rob.brVT_brMax || '----';
        printROB_BrVT_iHcMin.value = rob.brVT_iHcMin || '----';
        printROB_BrVT_iHcMax.value = rob.brVT_iHcMax || '----';
        printROB_HD5_brMin.value = rob.HD5_brMin || '----';
        printROB_HD5_brMax.value = rob.HD5_brMax || '----';
        printROB_HD5_iHcMin.value = rob.HD5_iHcMin || '----';
        printROB_HD5_iHcMax.value = rob.HD5_iHcMax || '----';
        printROB_JD5_brMin.value = rob.JD5_brMin || '----';
        printROB_JD5_brMax.value = rob.JD5_brMax || '----';
        printROB_JD5_iHcMin.value = rob.JD5_iHcMin || '----';
        printROB_JD5_iHcMax.value = rob.JD5_iHcMax || '----';
        printROB_remarks.value = rob.result || '----';


        if (nRR && Array.isArray(nRR)) {
            noteReasonForReject.value = []; // Clear existing values
            nRR.forEach(reason => {
                noteReasonForReject.value.push(reason);
            });
        } else {
            console.warn("No valid noteRejectReasons found (null or not an array).");
        }

        printBrVariance.value = Number(printBrMaximum.value) - Number(printBrMinimum.value);
        printiHcVariance.value = Number(printiHcMaximum.value) - Number(printiHcMinimum.value);
        printiHkVariance.value = Number(printiHkMaximum.value) - Number(printiHkMinimum.value);

        //Under SMP Judgement
        printSMPJudgement.value = rd.smp_judgement;
        printPreparedBy.value = rd.prepared_by;
        printCheckedBy.value = rd.checked_by;
        printApprovedBy.value = rd.approved_by;
        printPreparedBy_date.value = rd.prepared_by_date ? rd.prepared_by_date.split(' ')[0] : '';
        printCheckedBy_date.value = rd.checked_by_date ? rd.checked_by_date.split(' ')[0] : '';
        printApprovedBy_date.value = rd.approved_by_date ? rd.approved_by_date.split(' ')[0] : '';

        // Assign the first and last name
        printPreparedBy_firstName.value = rd.prepared_by_firstname ?? '';
        printPreparedBy_lastName.value = rd.prepared_by_surname ?? '';

        // Assign the first and last name
        printCheckedBy_firstName.value = rd.checked_by_firstname ?? '';
        printCheckedBy_lastName.value = rd.checked_by_surname ?? '';

        // Assign the first and last name
        printApprovedBy_firstName.value = rd.approved_by_firstname ?? '';
        printApprovedBy_lastName.value = rd.approved_by_surname ?? '';


        // Debug output
        /*console.log("SMP Judgement Data:", {
            smpJudgement: printSMPJudgement.value,
            preparedBy: printPreparedBy.value,
            checkedBy: printCheckedBy.value,
            approvedBy: printApprovedBy.value,
            preparedByDate: printPreparedBy_date.value,
            checkedByDate: printCheckedBy_date.value,
            approvedByDate: printApprovedBy_date.value
        });*/

        checkSpecialJudgement();

    }catch(error){
        console.error("Error on API GET REQUEST-dataFrom_reportdata function",error);
    }
}

const dataFrom_inspectiondata = async () => {
    try{
        const responseInspection = await axios.get("/api/inspectiondata");
        //console.log("API GET request-responseInspection",responseInspection.data);
        //const inspectionData = responseInspection.data.data.filter(column => column.tpm_data_serial == printSerialNo.value); // filter by serial;
        //console.log("inspectionData-data: ",inspectionData);
        const responseInsp = await axios.get("/api/inspectiondata");
        //console.log("Show All inspection data API response: ", responseInsp.data);
        inspectionDataList.value = responseInsp.data.data || [];
        //console.log("Show All inspection data list : ", inspectionDataList.value);

        const filteredInspectionData = inspectionDataList.value.filter(item => item.model == printActualModel.value);
        //console.log("Filtered inspection data for the selected model: ", filteredInspectionData);
        //console.log("Fetched model:", fetchActualModel.value);
        // Access the `br` value for each item in filteredInspectionData
        filteredInspectionData.forEach(item => {
            inspectionBrStandard.value = item.br;
            inspectioniHcStandard.value = item.ihc;
            inspectioniHkStandard.value = item.ihk;
        });
    }catch(error){
        console.error("Error on API GET REQUEST-dataFrom_inspectiondata function",error);
    }
}

const dataFrom_tpmData = async () => {
    try{
        const responseTpmData = await axios.get("api/tpmdata?serial=" + printSerialNo.value);
        //console.log("API GET request-responseTpmData",responseTpmData.data);
        printTPMData.value = responseTpmData.data.data;
        //console.log("printTPMData-data: ",printTPMData.value);
        const TPM = responseTpmData.data.data[0];
        const [furnaceNo, sinteringNo] = TPM.sintering_furnace_no.split('-');

        const tpmCat = printTPMData.value[0].category;
        //console.log("tpmCat-data: ",tpmCat);
        printCodeNo.value = TPM.code_no;
        printTypeCode.value = TPM.type;
        printSinteringNo.value = sinteringNo;
        printSinteringFurnaceNo.value = furnaceNo;
        printCoating.value = TPM.furnace_no;
        printJudgeCode.value = TPM.order_no;
        printPassNo.value = TPM.pass_no;
        printPressNo.value = TPM.press_1 + " " + TPM.press_2 + " " + TPM.machine_no;
        printMassProdName.value = tpmCat.massprod_name;
        printJhCurveLotno.value = tpmCat.jhcurve_lotno;
        printFactoryEmployee.value = tpmCat.factor_emp;
        printMiasEmployee.value = tpmCat.mias_emp;
        const printAggregateData = responseTpmData.data[0][0];
        //console.log("AggregateData-data: ",printAggregateData);
        const aggMax = JSON.parse(printAggregateData.maximum);
        const aggAve = JSON.parse(printAggregateData.average);
        const aggMin = JSON.parse(printAggregateData.minimum);
        printBHMaxAverage.value = aggAve.BHMax;
        printBHMaxMaximum.value = aggMax.BHMax;
        printBHMaxMinimum.value = aggMin.BHMax;
        printHr95Average.value = aggAve.Hr95;
        printHr95Maximum.value = aggMax.Hr95;
        printHr95Minimum.value = aggMin.Hr95;
        printHr98Average.value = aggAve.Hr98;
        printHr98Maximum.value = aggMax.Hr98;
        printHr98Minimum.value = aggMin.Hr98;
        printiHciHkAverage.value = aggAve.iHciHk;
        printiHciHkMaximum.value = aggMax.iHciHk;
        printiHciHkMinimum.value = aggMin.iHciHk;
        printBrpaiIaAverage.value = aggAve.Br4pai;
        printBrpaiIaMaximum.value = aggMax.Br4pai;
        printBrpaiIaMinimum.value = aggMin.Br4pai;
        printbHcAverage.value = aggAve.bHc;
        printbHcaxMaximum.value = aggMax.bHc;
        printbHcaxMinimum.value = aggMin.bHc;
        printSquarenessAverage.value = aggAve.Squareness;
        printSquarenessaxMaximum.value = aggMax.Squareness;
        printSquarenessaxMinimum.value = aggMin.Squareness;
        print4paiIdAverage.value = aggAve["4paild"];
        print4paiIdMaximum.value = aggMax["4paild"];
        print4paiIdMinimum.value = aggMin["4paild"];
        print4paiIsAverage.value = aggAve["4pails"];
        print4paiIsMaximum.value = aggMax["4pails"];
        print4paiIsMinimum.value = aggMin["4pails"];
        print4paiIaAverage.value = aggAve["4paila"];
        print4paiIaMaximum.value = aggMax["4paila"];
        print4paiIaMinimum.value = aggMin["4paila"];
        /*console.log("==== Aggregated Print Values ====");

        console.log("BHMax - Avg:", printBHMaxAverage.value, "Max:", printBHMaxMaximum.value, "Min:", printBHMaxMinimum.value);
        console.log("Hr95  - Avg:", printHr95Average.value, "Max:", printHr95Maximum.value, "Min:", printHr95Minimum.value);
        console.log("Hr98  - Avg:", printHr98Average.value, "Max:", printHr98Maximum.value, "Min:", printHr98Minimum.value);
        console.log("iHciHk - Avg:", printiHciHkAverage.value, "Max:", printiHciHkMaximum.value, "Min:", printiHciHkMinimum.value);
        console.log("Br4pai - Avg:", printBrpaiIaAverage.value, "Max:", printBrpaiIaMaximum.value, "Min:", printBrpaiIaMinimum.value);
        console.log("bHc    - Avg:", printbHcAverage.value, "Max:", printbHcaxMaximum.value, "Min:", printbHcaxMinimum.value);
        console.log("Squareness - Avg:", printSquarenessAverage.value, "Max:", printSquarenessaxMaximum.value, "Min:", printSquarenessaxMinimum.value);
        console.log("4paiId - Avg:", print4paiIdAverage.value, "Max:", print4paiIdMaximum.value, "Min:", print4paiIdMinimum.value);
        console.log("4paiIs - Avg:", print4paiIsAverage.value, "Max:", print4paiIsMaximum.value, "Min:", print4paiIsMinimum.value);
        console.log("4paiIa - Avg:", print4paiIaAverage.value, "Max:", print4paiIaMaximum.value, "Min:", print4paiIaMinimum.value);*/

    }catch(error){
        console.error("Error on API GET REQUEST-dataFrom_tpmData function",error);
    }
}

const datasets = ref([]); // Array to hold multiple datasets
const dataReady = ref(false); // Flag to track if data is ready
const myChartCanvas = ref(null); // Ref for the canvas
const fetchDataCreateGraph = async () => {
    try {
        const response = await axios.get("/api/tpmdata?serial=" + printSerialNo.value);

        // Log the response structure to check
        //console.log("API Response:", response.data);

        const tableRows = response.data.data; // Assuming API returns an array of rows

        // Check if tableRows is not undefined or null before proceeding
        if (!tableRows) {
            throw new Error("No data found in tpmData");
        }

        // Parse each row and dynamically generate datasets
        datasets.value = tableRows.map((row, index) => ({
            xAxis: JSON.parse(row.x || "[]"), // Parse x values
            yAxis: JSON.parse(row.y || "[]"), // Parse y values
            color: generateColor(index), // Assign a unique color
        }));
        //showGraphAndTables.value = true;  // Set this to true after data is loaded

        // Set dataReady to true once the data is ready
        dataReady.value = true;
        //console.log("dataReady ", dataReady.value);
        // Ensure DOM updates before rendering
        nextTick(() => {
            //console.log("myChartCanvas reference:", myChartCanvas.value); // Debug the canvas ref
            if (myChartCanvas.value) {
                renderChart();  // Proceed to render the chart if the canvas is available
            } else {
                console.error("Canvas element is not available.");
            }
        });
    } catch (err) {
        console.error("Error fetching data:", err);
    }
};

// Generate a unique color for each dataset
const generateColor = (index) => {
    const colors = [
        "#0d9ae0", // deep blue
        "#a543de", // violet
        "#82bd62", // green
        "#564094", // purple
        "#5cd1ac", // green
        "#bf49d1", // brown
        "#2da5eb", // blue
        "#b469f5", // purple
        "#5cd1ac", // green
        "#946ff7", // purple
        "#393B79", // navy
        "#637939", // olive
        "#8C6D31", // ochre
        "#843C39", // brick
        "#7B4173", // plum
        "#3182BD", // steel blue
        "#E6550D", // burnt orange
        "#31A354", // vivid green
        "#756BB1", // violet
        "#636363"  // dark gray
    ];
    return colors[index % colors.length];
};

const renderChart = () => {
    // Ensure myChartCanvas is not null
    if (!myChartCanvas.value) {
        console.error("Canvas element is not available.");
        return;
    }
    // Ensure the canvas is accessible
    //console.log("Rendering chart with canvas:", myChartCanvas.value);

    // Ensure datasets are available before creating the chart
    //console.log("Datasets available for chart:", datasets.value);

    if (dataReady.value && myChartCanvas.value) {
        const ctx = myChartCanvas.value.getContext("2d");

        // Check if the context is obtained
        if (!ctx) {
            console.error("Failed to get 2D context for canvas.");
            return;
        }

        //console.log("2D Context obtained, proceeding to render the chart.");

        //new updated offset 5/5/2025
        const x_offset = 2000;
        const y_offset = 1700;

        const chartDatasets = datasets.value.map((dataset, index) => {
            return {
                label: `Dataset ${index + 1}`,
                data: dataset.xAxis.map((x, i) => ({
                    x: x + index * x_offset,
                    y: (dataset.yAxis[i] || 0) - index * y_offset,
                })),
                borderColor: dataset.color,
                borderWidth: 2,
                fill: false,
                pointBackgroundColor: dataset.color,
                pointBorderColor: dataset.color,
            };
        });

        //console.log("Final Chart Datasets:", chartDatasets);

        try {
            new Chart(ctx, {
                type: "line",
                data: {
                    datasets: chartDatasets.map(dataset => ({
                        ...dataset,
                        pointRadius: 0,
                        tension: 0.6,
                    })),
                },
                options: {
                    responsive: true,
                    animation: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        x: {
                            type: "linear",
                            position: "bottom",
                            grid: {
                                color: "rgba(0, 0, 0, 0.1)",
                            },
                            title: {
                                display: true,
                                text: "←  kOe  →",
                                color: "#333",
                                font: {
                                    size: 14,  // Increase font size
                                    weight: "bold", // Make it bold
                                },
                            },
                            ticks: {
                                display: false, // ← hides x-axis values
                            },
                        },
                        y: {
                            type: "linear",
                            position: "left",
                            grid: {
                                color: "rgba(0, 0, 0, 0.1)",
                            },
                            title: {
                                display: true,
                                text: "←  kG  →",
                                color: "#333",
                                font: {
                                    size: 14,  // Increase font size
                                    weight: "bold", // Make it bold
                                },
                            },
                            ticks: {
                                display: false, // ← hides x-axis values
                            },
                        },
                    },
                },
            });
        } catch (error) {
            console.error("Error initializing Chart.js:", error);
        }
    } else {
        console.error("Chart cannot be rendered: Missing data or canvas context.");
    }
};

const checkingNSA = async () => {
    try {
        const responseCheckNSA = await axios.get("api/nsadata/");
        //console.log("API GET REQUEST responseCheckNSA-data:", responseCheckNSA.data);
        const nsadata = responseCheckNSA.data.data["NSAData"] || [];
        const nsafilteredData = nsadata.filter(item => item.serial_no == printSerialNo.value);
        if (nsafilteredData.length > 0) {
            const maxSetNo = Math.max(...nsafilteredData.map(item => item.set_no));
            numberOfSet.value = maxSetNo;
            additionalRemarks.value = [...new Set(nsadata.map(item => item.set_name))];
            //console.log('getting all the additional descriptions:',additionalRemarks.value);
        }else{
            //console.log('NOT ARRAY!!');
        }

       // console.log('tpm data filtered by serial: ',nsafilteredData);

        //console.log("NUMBER OF SETS: ",numberOfSet.value);
    } catch (error) {
        console.error("ERROR GET REQUEST responseCheckNSA-data:", error);
    }
};

const pagesData = ref([]);
const nsa_dataFrom_tpmData = async () => {
    //console.log("You have entered nsa_dataFrom_tpmData");

    try {
        //console.log("You have entered nsa_dataFrom_tpmData TRY CATCH");

        if (numberOfSet.value <= 0) {
            console.warn("numberOfSet.value is zero or negative, skipping loop.");
            return;  // Early exit if no sets to process.
        }

        for (let x = 1; x <= numberOfSet.value; x++) {
            //console.log(`Fetching data for set #${x}...`);

            // Fetch data from API
            const responseNsaData = await axios.get("api/nsadata?serial=" + printSerialNo.value + "&set=" + x);
            //console.log(`API response for set #${x}:`, responseNsaData.data);

            const nsadata = responseNsaData.data.data;
            const nsaGeneral = responseNsaData.data;
            //console.log("NSA GENERAL DATA: ",nsaGeneral);

            if (!nsadata || nsadata.length === 0) {
                console.warn(`No data found for set #${x}`);
                continue; // Skip this iteration if no data is available
            }

            //console.log(`NSA data for set #${x}:`, nsadata);

            if (nsadata.length === 0) {
                console.warn(`No data found for set #${x}`);
                continue;
            }

            const tableTPMRows = [];

            // Loop through the filtered data and populate the table
            for (let i = 0; i < nsadata.length; i++) {
                const TPM = nsadata[i];
                const tpmCat = TPM?.category;
                const tpmRem = TPM?.remark;

                // Skip the iteration if required properties are missing
                if (!TPM || !tpmCat || !TPM.sintering_furnace_no) {
                    //console.log(`Skipping TPM #${i} due to missing required properties.`);
                    continue;
                }

                // Prepare the row for the table
                const row = {
                    date: TPM.date ?? "",
                    codeNo: TPM.code_no ?? "",
                    orderNo: TPM.order_no ?? "",
                    type: TPM.type ?? "",
                    lotNo: TPM.press_1 + " " + TPM.press_2 + " " + TPM.machine_no,
                    furnaceNo: TPM.sintering_furnace_no ?? "",
                    coatingNo: TPM.furnace_no ?? "",
                    zone: TPM.zone ?? "",
                    passNo: TPM.pass_no,
                    Br: TPM.Br ?? "",
                    Br_remarks: tpmRem?.Br_remarks ?? "",
                    iHc: TPM.iHc ?? "",
                    iHc_remarks: tpmRem?.iHc_remarks ?? "",
                    iHk: TPM.iHk ?? "",
                    iHk_remarks: tpmRem?.iHk_remarks ?? "",
                    BHMax: TPM.BHMax ?? "",
                    BHMax_remarks: tpmRem?.BHMax_remarks ?? "",
                    iHr95: TPM.iHr95 ?? "",
                    iHr95_remarks: tpmRem?.iHr95_remarks ?? "",
                    iHr98: TPM.iHr98 ?? "",
                    iHr98_remarks: tpmRem?.iHr98_remarks ?? "",
                    iHkiHc: TPM.iHkiHc ?? "",
                    iHkiHc_remarks: tpmRem?.iHkiHc_remarks ?? "",
                    Br4pai: TPM.Br4pai ?? "",
                    Br4pai_remarks: tpmRem?.Br4pai_remarks ?? "",
                    bHc: TPM.bHc ?? "",
                    bHc_remarks: tpmRem?.bHc_remarks ?? "",
                    Squareness: TPM.Squareness ?? "",
                    Squareness_remarks: tpmRem?.Squareness_remarks ?? "",
                    "4paiIa": TPM["4paiIa"] ?? "",
                    "4paiIa_remarks": tpmRem?.["4paiIa_remarks"] ?? "",
                    "4paiId": TPM["4paiId"] ?? "",
                    "4paiId_remarks": tpmRem?.["4paiId_remarks"] ?? "",
                    "4paiIs": TPM["4paiIs"] ?? "",
                    "4paiIs_remarks": tpmRem?.["4paiIs_remarks"] ?? "",
                    tracer: TPM.Tracer ?? "",
                    temperature: TPM.temperature ?? "",
                    data_status: TPM.data_status ?? "",
                    mias_emp: TPM.mias_emp ?? "",
                    factorEmp: TPM.factor_emp ?? "",
                };
                tableTPMRows.push(row);
            }

            // General page-level info
            const firstTPM = nsadata[0];
            const [furnaceNo, sinteringNo] = firstTPM?.sintering_furnace_no?.split('-') ?? ["", ""];
            //console.log(`furnace and sintering no for page:`, { furnaceNo, sinteringNo });

            const page = {
                codeNo: firstTPM?.code_no ?? "",
                typeCode: firstTPM?.type ?? "",
                sinteringNo: sinteringNo,
                sinteringFurnaceNo: furnaceNo,
                coating: firstTPM?.furnace_no ?? "",
                judgeCode: firstTPM?.order_no ?? "",
                passNo: firstTPM?.pass_no ?? "",
                pressNo: firstTPM?.press_1 + " " + firstTPM?.press_2 + " " + firstTPM?.machine_no,
                tableTPM: tableTPMRows,
                actualModel: firstTPM?.category.actual_model,
                jhcurveLotNo: firstTPM?.category.jhcurve_lotno,
                massProd: firstTPM?.category.massprod_name,
            };

            // Check if nsaGeneral exists and parse additional data
            if (nsaGeneral[0]?.[x - 1]?.average && nsaGeneral[0]?.[x - 1]?.maximum && nsaGeneral[0]?.[x - 1]?.minimum) {
                //console.log(`✅ Parsing additional data for set #${x}`);
                //console.log("Raw data from nsaGeneral[0][x-1]:", nsaGeneral[0][x - 1]);

                const average = JSON.parse(nsaGeneral[0][x - 1].average);
                const maximum = JSON.parse(nsaGeneral[0][x - 1].maximum);
                const minimum = JSON.parse(nsaGeneral[0][x - 1].minimum);

                //console.debug("✅ Parsed averages:", average);
                //console.debug("✅ Parsed maximums:", maximum);
                //console.debug("✅ Parsed minimums:", minimum);

                // Populate page with the parsed averages, max, min
                page.brAverage = average.Br ?? "";
                page.brMaximum = maximum.Br ?? "";
                page.brMinimum = minimum.Br ?? "";

                page.iHcAverage = average.iHc ?? "";
                page.iHcMaximum = maximum.iHc ?? "";
                page.iHcMinimum = minimum.iHc ?? "";

                page.iHkAverage = average.iHk ?? "";
                page.iHkMaximum = maximum.iHk ?? "";
                page.iHkMinimum = minimum.iHk ?? "";

                page.bHMaxAverage = average.BHMax ?? "";
                page.bHMaxMaximum = maximum.BHMax ?? "";
                page.bHMaxMinimum = minimum.BHMax ?? "";

                page.hr95Average = average.Hr95 ?? "";
                page.hr95Maximum = maximum.Hr95 ?? "";
                page.hr95Minimum = minimum.Hr95 ?? "";

                page.hr98Average = average.Hr98 ?? "";
                page.hr98Maximum = maximum.Hr98 ?? "";
                page.hr98Minimum = minimum.Hr98 ?? "";

                page.iHciHkAverage = average.iHciHk ?? "";
                page.iHciHkMaximum = maximum.iHciHk ?? "";
                page.iHciHkMinimum = minimum.iHciHk ?? "";

                page.brpaiIaAverage = average.Br4pai ?? "";
                page.brpaiIaMaximum = maximum.Br4pai ?? "";
                page.brpaiIaMinimum = minimum.Br4pai ?? "";

                page.bHcAverage = average.bHc ?? "";
                page.bHcMaximum = maximum.bHc ?? "";
                page.bHcMinimum = minimum.bHc ?? "";

                page.squarenessAverage = average.Squareness ?? "";
                page.squarenessMaximum = maximum.Squareness ?? "";
                page.squarenessMinimum = minimum.Squareness ?? "";

                page.fourpaiIdAverage = average["4paild"] ?? "";
                page.fourpaiIdMaximum = maximum["4paild"] ?? "";
                page.fourpaiIdMinimum = minimum["4paild"] ?? "";

                page.fourpaiIsAverage = average["4pails"] ?? "";
                page.fourpaiIsMaximum = maximum["4pails"] ?? "";
                page.fourpaiIsMinimum = minimum["4pails"] ?? "";

                page.fourpaiIaAverage = average["4paila"] ?? "";
                page.fourpaiIaMaximum = maximum["4paila"] ?? "";
                page.fourpaiIaMinimum = minimum["4paila"] ?? "";

                //console.log(`✅ Completed parsing and assigning NSA data to page for set #${x}`);
            } else {
                console.warn(`⚠️ nsaGeneral[0] or nsaGeneral[0][${x - 1}] is missing or incomplete.`);
                console.debug("nsaGeneral dump:", nsaGeneral);
                console.debug(`x = ${x}, x - 1 = ${x - 1}`);
            }

            //console.log(`Page contents for set #${x}:`, page);

            // Add page data to the pagesData array
            pagesData.value.push(page);

            //console.log("PAGESDATA contents: ",pagesData.value);
        }
    } catch (error) {
        console.error(`Error processing!}:`, error);
    }
};

const nsa_datasets = ref([]); // Array to hold multiple datasets
const nsa_myChartCanvasRefs = ref([]);
const nsa_dataReady = ref(false); // Flag to track if data is ready
const nsa_myChartCanvas = ref(null); // Ref for the canvas
const nsa_graph = async () => {
    if (numberOfSet.value <= 0) {
        console.warn("numberOfSet.value is zero or negative, skipping loop. This is on the graph function of NSA.");
        return;  // Early exit if no sets to process.
    }

    nsa_datasets.value = [];

    for (let x = 1; x <= numberOfSet.value; x++) {
        try {
            const response = await axios.get("api/nsadata?serial=" + printSerialNo.value + "&set=" + x);
            const nsadata = response.data.data;

            const datasetsForSet = nsadata.map((row, index) => ({
                xAxis: JSON.parse(row.x || "[]"),
                yAxis: JSON.parse(row.y || "[]"),
                color: generateColor(index),
            }));

            nsa_datasets.value[x - 1] = datasetsForSet;

        } catch (err) {
            console.error(`Error processing set #${x}:`, err);
        }
    }

    nsa_dataReady.value = true;

    // Wait for DOM updates (canvases to be rendered), then draw
    nextTick(() => {
        for (let i = 0; i < nsa_datasets.value.length; i++) {
            nsa_renderChart(i);
        }
    });
}

const nsa_renderChart = (setIndex) => {
    const canvas = nsa_myChartCanvasRefs.value[setIndex];
    if (!canvas) {
        console.error(`Canvas for set #${setIndex + 1} not found.`);
        return;
    }

    const ctx = canvas.getContext("2d");
    if (!ctx) {
        console.error("Failed to get canvas context.");
        return;
    }

    const x_offset = 2000;
    const y_offset = 1700;

    const datasetGroup = nsa_datasets.value[setIndex];

    const chartDatasets = datasetGroup.map((dataset, index) => ({
        label: `Set ${setIndex + 1} - Dataset ${index + 1}`,
        data: dataset.xAxis.map((x, i) => ({
            x: x + index * x_offset,
            y: (dataset.yAxis[i] || 0) - index * y_offset,
        })),
        borderColor: dataset.color,
        borderWidth: 2,
        fill: false,
        pointRadius: 0,
        tension: 0.6,
    }));

    new Chart(ctx, {
        type: "line",
        data: { datasets: chartDatasets },
        options: {
            responsive: true,
            animation: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: {
                    type: "linear",
                    title: {
                        display: true,
                        text: "←  kOe  →",
                        color: "#333",
                        font: { size: 14, weight: "bold" },
                    },
                    ticks: {
                        display: false, // ← hides x-axis values
                    },
                },
                y: {
                    type: "linear",
                    title: {
                        display: true,
                        text: "←  kG  →",
                        color: "#333",
                        font: { size: 14, weight: "bold" },
                    },
                    ticks: {
                        display: false, // ← hides x-axis values
                    },
                },
            },
        },
    });
};


const exportMultiPagePdf = async () => {
  try {
    const pages = document.querySelectorAll('.a4-page');
    const pdf = new jsPDF('p', 'mm', 'a4');
    const pageWidth = pdf.internal.pageSize.getWidth();

    const allCanvases = document.querySelectorAll('.a4-page canvas');
    const canvasToImageMap = [];

    allCanvases.forEach((canvas) => {
      const img = document.createElement('img');
      img.src = canvas.toDataURL('image/png');
      img.style.width = canvas.style.width;
      img.style.height = canvas.style.height;
      canvasToImageMap.push({ original: canvas, replacement: img });
      canvas.parentNode.replaceChild(img, canvas);
    });

    const canvasOutputs = await Promise.all(
      Array.from(pages).map((page) =>
        html2canvas(page, {
          scale: 1.5,
          useCORS: true,
          backgroundColor: '#FFFFFF',
          logging: false,
        })
      )
    );

    canvasOutputs.forEach((canvas, i) => {
      const imgData = canvas.toDataURL('image/jpeg', pages.length > 5 ? 0.45 : 0.6);
      const imgProps = pdf.getImageProperties(imgData);
      const imgHeight = (imgProps.height * pageWidth) / imgProps.width;
      if (i > 0) pdf.addPage();
      pdf.addImage(imgData, 'JPEG', 0, 0, pageWidth, imgHeight);
    });

    canvasToImageMap.forEach(({ original, replacement }) => {
      replacement.parentNode.replaceChild(original, replacement);
    });

    const blob = pdf.output('blob');

    const massProd_forPDF = printMassProdName.value;
    if (!massProd_forPDF || !massProd_forPDF.trim()) {
      console.error('[PDF Export] ERROR: massProd_forPDF is empty or invalid. Cannot upload.');
      return Promise.reject(new Error('Invalid massProd name'));
    }

    const sanitizeFilename = (str) => str.replace(/\//g, ' ');
    const rawFilename = `(${printSMPJudgement.value}) ${printActualModel.value} Lot No ${printJhCurveLotno.value}`;
    const filename = sanitizeFilename(rawFilename) + '.pdf';

    const formData = new FormData();
    formData.append('massProd', massProd_forPDF);
    formData.append('pdf', blob, filename);

    console.log('[PDF Export] PDF file info:', {
      filename: filename,
      sizeMB: (blob.size / (1024 * 1024)).toFixed(2),
      type: blob.type,
    });

    try {
    //console.log('🟡 Uploading PDF...');
    const response = await axios.post('/upload-pdf', formData);
    //console.log('🟢 Upload complete, now finalizing report...');

    await finalizeReport(printSerialNo.value);
    //console.log('🟢 Report finalized, now logging...');

    await pdfFinalizeLogging(`has finalized Serial ${printSerialNo.value}`);
    //console.log('✅ Log saved.');

    success.value = true;
    } catch (error) {
    console.error('❌ Report Finalization Failed:', error);
    return Promise.reject(error);
    }

    return Promise.resolve(); // Explicit full-completion signal
  } catch (error) {
    console.error('[PDF Export] Error during multi-page export:', error);
    alert('Failed to generate/upload PDF.');
    return Promise.reject(error);
  }
};

const finalizeReport = async (serial) => {
  try {
    const responseFinalize = await axios.patch(`/api/reportdata/${serial}`, {
      is_finalized: 1,
    });
    //console.log('[Finalize Report] Response:', responseFinalize.data);
  } catch (error) {
    console.error('[Finalize Report] Error finalizing report:', error);
  }
};

// Define the prop that will receive the serialParam
const props = defineProps({
  serialParam: String,  // Expecting the serialParam to be a string
});
const printSerialNo = computed(() => props.serialParam)
//console.log('Serial Param in PreviewPdf.vue:', props.serialParam); // You can use this for debugging
const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));
// Optional: auto print on page load
onMounted(async () => {
  adjustStyling.value = true;

  await checkingNSA();

  await Promise.all([
    dataFrom_reportdata(),
    dataFrom_inspectiondata(),
    dataFrom_tpmData(),
    fetchDataCreateGraph(),
    nsa_dataFrom_tpmData(),
    nsa_graph(),
  ]);

  // Ensure Vue has flushed DOM updates
  await nextTick();

  // Allow time for canvas/chart/image rendering to complete
  await delay(1500); // tune as needed

  await exportMultiPagePdf();

  adjustStyling.value = false;
});



</script>

<style scoped>
/* A4 size container */
.a4-page {
  width: 210mm;
  height: 297mm;
  box-sizing: border-box;
  page-break-after: always;
  background: white;
  overflow: hidden;
}

/* Print-specific tweaks */
@media print {
  body {
    margin: 0;
    background: white;
  }

  .print-container {
    padding: 0;
  }

  .break-after-page {
    page-break-after: always;
  }

  button {
    display: none !important;
  }

.no-print {
    display: none !important;
  }
}

@page {
  size: A4;
  margin: 0;
}

</style>
