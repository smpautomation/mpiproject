<template>
<div class="print-container mx-auto">
  <!-- A4 Layout Box -->
  <div class="a4-page shadow-lg bg-white text-black border border-gray-300 max-w-5xl mx-auto px-7">
    <div class="flex flex-row justify-between">
        <button class="mt-2 bg-gray-600 text-white py-1 px-2 rounded-lg" @click="$inertia.visit('/reports')">back</button>
        <button class="mt-2 bg-gray-300 text-black py-1 px-2 rounded-lg">Serial: </button>
        <button
        @click="window.print()"
        class="py-1 px-2 mt-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
        >
        Print This Report
    </button>
    </div>
    <!-- Report Title -->
    <h1 class="text-2xl font-bold my-6 text-center bg-gray-300">GBDP MAGNETIC PROPERTY INSPECTION REPORT</h1>
    <!-- Oven Heating Information -->
    <p class="text-xl font-extrabold mb-4">Oven Heating Information</p>

    <!-- Information Grid -->
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- First Column -->
        <div class="space-y-1">
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Oven Machine No:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printOvenMachineNo_ovenInfo }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Time Loading:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printTimeLoading }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Temperature:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printTimeLoading_temp }}
                </span>
            </div>
        </div>

        <!-- Second Column -->
        <div class="space-y-1">
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Date:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printDate_ovenInfo }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Time Unloading:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printTimeUnloading }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Temperature:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printTimeUnloading_temp }}
                </span>
            </div>
        </div>

        <!-- Third Column -->
        <div class="space-y-1">
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Shift:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printShift_ovenInfo }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="font-semibold mr-2 text-sm">Operator:</span>
                <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                    {{ printOperator_ovenInfo }}
                </span>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <hr class="my-4" />

    <p class="text-xl font-extrabold mb-4">Magnetic Property Inspection Information</p>

    <!-- Single Row Information -->
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-4 gap-4">
        <!-- First Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Model:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printActualModel }}
            </span>
        </div>

        <!-- Second Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-[10px]">MATERIAL&nbsp;CODE:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printMaterialCode }}
            </span>
        </div>

        <!-- Third Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Partial&nbsp;No.:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printPartialNo }}
            </span>
        </div>

        <!-- Fourth Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-[10px]">TOTAL&nbsp;QUANTITY:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printTotalQuantity }}
            </span>
        </div>
        <!-- Fifth Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Pulse&nbsp;Tracer&nbsp;No:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printPulseTracer }}
            </span>
        </div>

        <!-- Sixth Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Date:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printDate }}
            </span>
        </div>

        <!-- Seventh Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Shift:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printShift }}
            </span>
        </div>

        <!-- Eighth Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Operator:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printOperator }}
            </span>
        </div>
    </div>

    <div class="flex flex-row gap-2">
        <div>
            <!-- Data Table -->
            <table class="w-full border border-collapse border-gray-400 text-sm mb-6">
            <thead>
                <tr class="bg-gray-200 text-center">
                    <th colspan="2" class="border border-black px-2 py-[3.04px]">Standard&nbsp;Sample&nbsp;Dimention and&nbsp;Material&nbsp;Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value, key) in standardSampleDimention" :key="key">
                    <td class="border border-black px-4 py-[3.04px] text-xs">{{ key }}</td>
                    <td class="border border-black px-4 py-[3.04px] text-xs">{{ value }}</td>
                </tr>
            </tbody>
            </table>
        </div>
        <div>
            <!-- Data Table -->
            <table class="w-full border-collapse border border-gray-400 text-sm mb-6" style="border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th colspan="7" class="border border-black px-2 py-[9.5px] text-sm">Magnetic Property Data</th>
                    </tr>
                    <tr class="bg-gray-200 text-center">
                        <th rowspan="2" class="border border-black px-2 py-[3.04px]">Items</th>
                        <th rowspan="2" class="border border-black px-2 py-[3.04px]">Standard</th>
                        <th colspan="4" class="border border-black px-2 py-[3.04px]">Actual Data</th>
                    </tr>
                    <tr class="bg-gray-200 text-center">
                        <th class="border border-black px-2 py-[3.04px]">Average</th>
                        <th class="border border-black px-2 py-[3.04px]">Maximum</th>
                        <th class="border border-black px-2 py-[3.04px]">Minimum</th>
                        <th class="border border-black px-2 py-[3.04px]">Variance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="border border-black px-2 py-[3.04px] text-xs">Br&nbsp;(G)</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrStandard }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrAverage }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrMaximum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrMinimum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrVariance }}</td>
                    </tr>
                    <tr class="text-center">
                        <td class="border border-black px-2 py-[3.04px] text-xs">iHc&nbsp;(Oe)</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcStandard }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcAverage }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcMaximum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcMinimum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcVariance }}</td>
                    </tr>
                    <tr class="text-center">
                        <td class="border border-black px-2 py-[3.04px] text-xs">iHk&nbsp;(Oe)</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHkStandard }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHkAverage }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHkMaximum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHkMinimum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHkVariance }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-5">
        <div class="flex flex-row">
            <span class="font-semibold mr-2 text-sm">Remarks:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printRemarks }}
            </span>
            <div class="mx-10">
                <span class="text-center">{{ printRemarksResult }}</span>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <hr class="my-4" />

    <div class="mb-5">
        <p class="text-xl font-extrabold mb-4">SMP Judgement:</p>
        <div class="flex flex-row">
            <!-- SMP Judgement -->
            <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200">
                <!-- Header acting as the title -->
                <p class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                    SMP Judgement
                </p>
                <!-- Container for the stamp -->
                <div class="relative flex items-center justify-center flex-grow bg-white w-full">
                    <!-- The stamp -->
                    <span
                        class="absolute w-[100px] h-[100px] bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: printSMPJudgement === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : printSMPJudgement === 'HOLD'
                                ? 'url(\'/photo/hold_stamp.png\')'
                                : 'url(\'/photo/pass_stamp.png\')',
                            backgroundSize: 'contain'
                        }">
                    </span>
                </div>
            </div>
            <!-- Prepared By -->
            <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200 ml-3">
                <!-- Header acting as the title -->
                <p class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                    Prepared By
                </p>
                <!-- Container for the stamp -->
                <div class="relative flex items-center justify-center flex-grow bg-white w-full">
                    <!-- The stamp -->
                    <span
                        class="absolute w-[100px] h-[100px] bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: printPreparedBy != '' && printPreparedBy != null
                                ? 'url(\'/photo/Prepared_by_stamp.png\')'
                                : printPreparedBy === 'HOLD'
                                ? 'url(\'/photo/cancel.png\')'
                                : 'url(\'/photo/cancel.png\')',
                            backgroundSize: 'contain'
                        }">
                    </span>
                </div>
            </div>
            <!-- Checked By -->
            <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200">
                <!-- Header acting as the title -->
                <p class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                    Checked By
                </p>
                <!-- Container for the stamp -->
                <div class="relative flex items-center justify-center flex-grow bg-white w-full">
                    <!-- The stamp -->
                    <span
                        class="absolute w-[100px] h-[100px] bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: printCheckedBy != '' && printCheckedBy != null
                                ? 'url(\'/photo/Checked_by_stamp.png\')'
                                : printCheckedBy === 'HOLD'
                                ? 'url(\'/photo/cancel.png\')'
                                : 'url(\'/photo/cancel.png\')',
                            backgroundSize: 'contain'
                        }">
                    </span>
                </div>
            </div>
            <!-- Approved By -->
            <div class="flex flex-col items-center justify-start w-[130px] h-[100px] border border-black bg-gray-200">
                <!-- Header acting as the title -->
                <p class="w-full p-1 text-sm font-extrabold text-center text-black bg-gray-300 border-b border-black">
                    Approved By
                </p>
                <!-- Container for the stamp -->
                <div class="relative flex items-center justify-center flex-grow bg-white w-full">
                    <!-- The stamp -->
                    <span
                        class="absolute w-[100px] h-[100px] bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: printApprovedBy != '' && printApprovedBy != null
                                ? 'url(\'/photo/Approved_by_stamp.png\')'
                                : printApprovedBy === 'HOLD'
                                ? 'url(\'/photo/cancel.png\')'
                                : 'url(\'/photo/cancel.png\')',
                            backgroundSize: 'contain'
                        }">
                    </span>
                </div>
            </div>
            <div class="ml-2">
                <div class="flex flex-col border p-1 border-black">
                    <p class="text-[10px] pb-1 font-extrabold">Note: (REASON OF HOLD/REJECT)</p>
                    <div class="text-[10px] text-red-600 font-extrabold" v-if="noteReasonForReject.length">
                        <span v-for="(reason, index) in noteReasonForReject" :key="index" class="block">
                            {{ reason }}
                        </span>
                    </div>
                    <span v-else class="text-sm text-gray-500">No rejection notes available.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <hr class="my-4" />


    <div>
        <p class="text-xl font-extrabold mb-4">SEC / C-Mark Judgement:</p>
        <div class="flex flex-row">
            <div class="min-w-[300px] min-h-[150px] border border-black">
                <!-- empty -->
            </div>
            <div class="min-w-[400px] min-h-[150px] border border-black ml-3">
                <p class="text-[10px] ml-1">
                    Note: (Reason of REJECTED/Content of Condition Approved)
                </p>
            </div>
        </div>
    </div>

  </div>

    <!-- A4 Layout Box -->
    <div class="a4-page shadow-lg bg-white text-black border border-gray-300 max-w-5xl mx-auto px-7">
        <!-- Report Title -->
        <h1 class="text-2xl font-bold my-6 text-center bg-gray-300">PROPERTY&nbsp;DATA</h1>
        <!-- Information Grid -->
        <div class="mb-2 grid grid-cols-1 sm:grid-cols-5 gap-4">
            <!-- First Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Code&nbsp;No:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printCodeNo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Sintering#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printSinteringNo }}
                    </span>
                </div>
            </div>
            <!-- Second Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Type&nbsp;Code:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printTypeCode }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">coating:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printCoating }}
                    </span>
                </div>
            </div>
            <!-- Third Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Judge&nbsp;Code:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printJudgeCode }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Pass#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printPassNo }}
                    </span>
                </div>
            </div>
            <!-- Fourth Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Press#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printPressNo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Mias.&nbsp;Employee:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printMiasEmployee }}
                    </span>
                </div>
            </div>
            <!-- Fifth Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Sintering&nbsp;Furnace#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printSinteringFurnaceNo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Factor&nbsp;Employee:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printFactoryEmployee }}
                    </span>
                </div>
            </div>
        </div>
        <div>
            <table class="w-full border-collapse border border-gray-400 text-sm mb-6" style="border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th class="border border-black px-1 py-1 text-xs">Zone</th>
                        <th class="border border-black px-1 py-1 text-xs">Br</th>
                        <th class="border border-black px-1 py-1 text-xs">iHc</th>
                        <th class="border border-black px-1 py-1 text-xs">iHk</th>
                        <th class="border border-black px-1 py-1 text-xs">[BH]m</th>
                        <th class="border border-black px-1 py-1 text-xs">Hr95</th>
                        <th class="border border-black px-1 py-1 text-xs">Hr98</th>
                        <th class="border border-black px-1 py-1 text-xs">iHc&#8209;iHk</th>
                        <th class="border border-black px-1 py-1 text-xs">Br&#960;Ia</th>
                        <th class="border border-black px-1 py-1 text-xs">bHc</th>
                        <th class="border border-black px-1 py-1 text-xs">Squareness</th>
                        <th class="border border-black px-1 py-1 text-xs">4&#960;Id</th>
                        <th class="border border-black px-1 py-1 text-xs">4&#960;Is</th>
                        <th class="border border-black px-1 py-1 text-xs">4&#960;Ia</th>
                        <th class="border border-black px-1 py-1 text-xs">Temperature</th>
                        <th class="border border-black px-1 py-1 text-xs">Data&nbsp;Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printZone }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBr }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHc }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHk }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBHMax }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr95 }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr98 }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHkiHc }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBrpaiIa }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBhc }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printSquareness }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiId }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIs }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIa }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printTemperature }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printDataStatus }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="w-full border-collapse border border-gray-400 text-sm mb-6" style="border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th class="border border-black px-1 py-1 text-xs"></th>
                        <th class="border border-black px-1 py-1 text-xs">Br</th>
                        <th class="border border-black px-1 py-1 text-xs">iHc</th>
                        <th class="border border-black px-1 py-1 text-xs">iHk</th>
                        <th class="border border-black px-1 py-1 text-xs">[BH]m</th>
                        <th class="border border-black px-1 py-1 text-xs">Hr95</th>
                        <th class="border border-black px-1 py-1 text-xs">Hr98</th>
                        <th class="border border-black px-1 py-1 text-xs">iHc&#8209;iHk</th>
                        <th class="border border-black px-1 py-1 text-xs">Br&#960;Ia</th>
                        <th class="border border-black px-1 py-1 text-xs">bHc</th>
                        <th class="border border-black px-1 py-1 text-xs">Squareness</th>
                        <th class="border border-black px-1 py-1 text-xs">4&#960;Id</th>
                        <th class="border border-black px-1 py-1 text-xs">4&#960;Is</th>
                        <th class="border border-black px-1 py-1 text-xs">4&#960;Ia</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- AVERAGE ROW -->
                    <tr class="text-center">
                        <th class="border border-black px-1 py-1 text-xs bg-gray-200">Average</th>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBrAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHcAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHkAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBHMaxAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr95Average }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr98Average }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHciHkAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBrpaiIaAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printbHcAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printSquarenessAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIdAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIsAverage }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIaAverage }}</td>
                    </tr>

                    <!-- MAXIMUM ROW -->
                    <tr class="text-center">
                        <th class="border border-black px-1 py-1 text-xs bg-gray-200">Maximum</th>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBrMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHcMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHkMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBHMaxMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr95Maximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr98Maximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHciHkMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBrpaiIaMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printbHcaxMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printSquarenessaxMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIdMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIsMaximum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIaMaximum }}</td>
                    </tr>

                    <!-- MINIMUM ROW -->
                    <tr class="text-center">
                        <th class="border border-black px-1 py-1 text-xs bg-gray-200">Minimum</th>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBrMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHcMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHkMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBHMaxMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr95Minimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printHr98Minimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printiHciHkMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printBrpaiIaMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printbHcaxMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ printSquarenessaxMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIdMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIsMinimum }}</td>
                        <td class="border border-black px-1 py-1 text-[10px]">{{ print4paiIaMinimum }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- A4 Layout Box -->
    <div class="a4-page shadow-lg bg-white text-black border border-gray-300 max-w-5xl mx-auto p-7">
        <div class="flex flex-col my-6">
            <div class="mb-10 border border-black w-[400px] h-[400px]">
                graph here
            </div>
            <div>
                <table>
                    <thead>
                        <tr class="bg-gray-200 text-center">
                            <th class="border border-black px-1 py-1 text-[7.5px]">Date</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Code&nbsp;No</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Order&nbsp;No</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Type</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">LOT#</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Furnace#</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Coating#</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Zone</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Pass#</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Br</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">iHc</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">iHk</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">BHMax</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Hr95</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Hr98</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">iHkiHc</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Br4pai</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">bHc</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Squareness</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">4paiId</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">4paiIs</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">4paiIa</th>
                            <th class="border border-black px-1 py-1 text-[7.5px]">Tracer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from 'vue';

// You could pass these via props or retrieve via an API
const printSerialNo = ref('2505000001');
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

const printRemarks = ref('N/A');
const printRemarksResult = ref('N/A');

const printSMPJudgement = ref('');
const printPreparedBy = ref('');
const printPreparedBy_date = ref('');
const printCheckedBy = ref('');
const printCheckedBy_date = ref('');
const printApprovedBy = ref('');
const printApprovedBy_date = ref('');

const noteReasonForReject = ref([]);

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

const printZone = ref("");
const printBr = ref("");
const printiHc = ref("");
const printiHk = ref("");
const printBHMax = ref("");
const printHr95 = ref("");
const printHr98 = ref("");
const printiHkiHc = ref("");
const printBrpaiIa = ref("");
const printBhc = ref("");
const printSquareness = ref("");
const print4paiId = ref("");
const print4paiIs = ref("");
const print4paiIa = ref("");
const printTemperature = ref("");
const printDataStatus = ref("");

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


const standardSampleDimention = computed(() => ({
  'LENGTH (mm)': printLength.value,
  'WIDTH (mm)': printWidth.value,
  'THICKNESS (mm)': printThickness.value,
  'MATERIAL GRADE': printMaterialGrade.value,
  'MPI SAMPLE QTY.': printMPISampleQty.value,
}));


const dataFrom_reportdata = async () => {
    try{
        const responseReport = await axios.get("/api/reportdata");
        //console.log('API GET request-responseReport',responseReport.data);
        const reportData = responseReport.data.data.filter(column => column.tpm_data_serial == printSerialNo.value); // filter by serial
        console.log("reportData-data: ",reportData);
        const rd = reportData[0];

        //Under Oven Heating Information
        printOvenMachineNo_ovenInfo.value = rd.oven_machine_no;
        //console.log("printOvenMachineNo: ",printOvenMachineNo_ovenInfo.value);
        printTimeLoading.value = rd.time_loading;
        printTimeLoading_temp.value = rd.temp_time_loading;
        printTimeUnloading.value = rd.time_unloading;
        printTimeUnloading_temp.value = rd.temp_time_unloading;
        printDate_ovenInfo.value = rd.date_oven_info;
        printShift_ovenInfo.value = rd.shift_oven_info;
        printOperator_ovenInfo.value = rd.operator_oven_info;

        //Under Magnetic Property Inspection
        printActualModel.value = rd.model;
        printMaterialCode.value = rd.material_code;
        printPartialNo.value = rd.partial_number;
        printPulseTracer.value = rd.pulse_tracer_machine_number;
        printDate.value = rd.date;
        printShift.value = rd.shift;
        printOperator.value = rd.operator;
        printTotalQuantity.value = rd.total_quantity;
        printRemarks.value = rd.remarks;

        //Under Standard Sample Dimention and Material Grade
        printLength.value = rd.length;
        printWidth.value = rd.width;
        printThickness.value = rd.thickness;
        printMaterialGrade.value = rd.material_grade;
        printMPISampleQty.value = rd.mpi_sample_quantity;
        console.log("StandardSampleDimention array: ",standardSampleDimention.value);

        //Under Magnetic Property Data
        const mPD = JSON.parse(rd.magnetic_property_data);
        const nRR = JSON.parse(rd.note_reason_reject);

        printBrStandard.value = mPD.brStandard;
        printBrAverage.value = mPD.brAverage;
        printBrMaximum.value = mPD.brMaximum;
        printBrMinimum.value = mPD.brMinimum;

        printiHcStandard.value = mPD.ihcStandard;
        printiHcAverage.value = mPD.ihcAverage;
        printiHcMaximum.value = mPD.ihcMaximum;
        printiHcMinimum.value = mPD.ihcMinimum;

        printiHkStandard.value = mPD.ihkStandard;
        printiHkAverage.value = mPD.ihkAverage;
        printiHkMaximum.value = mPD.ihkMaximum;
        printiHkMinimum.value = mPD.ihkMinimum;

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
        printPreparedBy_date.value = rd.prepared_by_date;
        printCheckedBy_date.value = rd.checked_by_date;
        printApprovedBy_date.value = rd.approved_by_date;
        
        // Debug output
        console.log("SMP Judgement Data:", {
            smpJudgement: printSMPJudgement.value,
            preparedBy: printPreparedBy.value,
            checkedBy: printCheckedBy.value,
            approvedBy: printApprovedBy.value,
            preparedByDate: printPreparedBy_date.value,
            checkedByDate: printCheckedBy_date.value,
            approvedByDate: printApprovedBy_date.value
        });

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
    }catch(error){
        console.error("Error on API GET REQUEST-dataFrom_inspectiondata function",error);
    }
}

// Optional: auto print on page load
onMounted(() => {
  // window.print()
  dataFrom_reportdata();
  dataFrom_inspectiondata();
})
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

  .a4-page {
    width: 210mm;
    height: 297mm;
    page-break-after: always;
    background: white;
    overflow: hidden;
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
}

@page {
  size: A4;
  margin: 0;
}

</style>
