<template>
<div class="print-container mx-auto">
  <!-- A4 Layout Box -->
  <div class="a4-page shadow-lg bg-white text-black border border-gray-300 max-w-5xl mx-auto px-7">
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
                    <tr>
                        <td class="border border-black px-2 py-[3.04px] text-xs">Br&nbsp;(G)</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrStandard }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrAverage }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrMaximum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrMinimum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printBrVariance }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-2 py-[3.04px] text-xs">iHc&nbsp;(Oe)</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcStandard }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcAverage }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcMaximum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcMinimum }}</td>
                        <td class="border border-black px-2 py-[3.04px] text-xs">{{ printiHcVariance }}</td>
                    </tr>
                    <tr>
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
                            backgroundImage: printPreparedBy === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : printPreparedBy === 'HOLD'
                                ? 'url(\'/photo/hold_stamp.png\')'
                                : 'url(\'/photo/Prepared_by_stamp.png\')',
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
                            backgroundImage: printCheckedBy === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : printCheckedBy === 'HOLD'
                                ? 'url(\'/photo/hold_stamp.png\')'
                                : 'url(\'/photo/Checked_by_stamp.png\')',
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
                            backgroundImage: printApprovedBy === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : printApprovedBy === 'HOLD'
                                ? 'url(\'/photo/hold_stamp.png\')'
                                : 'url(\'/photo/Approved_by_stamp.png\')',
                            backgroundSize: 'contain'
                        }">
                    </span>
                </div>
            </div>
            <div class="ml-2">
                <div class="flex flex-col">
                    <p class="text-[10px]">Note: (REASON OF HOLD/REJECT)</p>
                    <span class="text-sm">
                        sample reject here
                    </span>
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
                        {{ printOvenMachineNo_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Sintering#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printTimeLoading }}
                    </span>
                </div>
            </div>
            <!-- Second Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Type&nbsp;Code:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printOvenMachineNo_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">coating:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printTimeLoading }}
                    </span>
                </div>
            </div>
            <!-- Third Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Judge&nbsp;Code:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printOvenMachineNo_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Pass#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printTimeLoading }}
                    </span>
                </div>
            </div>
            <!-- Fourth Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Press#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printOvenMachineNo_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Mias.&nbsp;Employee:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printTimeLoading }}
                    </span>
                </div>
            </div>
            <!-- Fifth Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Sintering&nbsp;Furnace#:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printOvenMachineNo_ovenInfo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="font-semibold mr-2 text-xs">Factor&nbsp;Employee:</span>
                    <span class="inline-block border-b border-gray-500 flex-grow text-sm">
                        {{ printTimeLoading }}
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
                        <td class="border border-black px-1 py-1 text-[10px]">15AE</td>
                        <td class="border border-black px-1 py-1 text-[10px]">14157</td>
                        <td class="border border-black px-1 py-1 text-[10px]">22475</td>
                        <td class="border border-black px-1 py-1 text-[10px]">21107</td>
                        <td class="border border-black px-1 py-1 text-[10px]">48.68</td>
                        <td class="border border-black px-1 py-1 text-[10px]">21210</td>
                        <td class="border border-black px-1 py-1 text-[10px]">20858</td>
                        <td class="border border-black px-1 py-1 text-[10px]">1368</td>
                        <td class="border border-black px-1 py-1 text-[10px]">700</td>
                        <td class="border border-black px-1 py-1 text-[10px]">13688</td>
                        <td class="border border-black px-1 py-1 text-[10px]">0.972</td>
                        <td class="border border-black px-1 py-1 text-[10px]">13940</td>
                        <td class="border border-black px-1 py-1 text-[10px]">14566</td>
                        <td class="border border-black px-1 py-1 text-[10px]">13458</td>
                        <td class="border border-black px-1 py-1 text-[10px]">22.8</td>
                        <td class="border border-black px-1 py-1 text-[10px]">Normal</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="w-full border-collapse border border-gray-400 text-sm mb-6" style="border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th class="border border-black px-1 py-1 text-xs"></th>
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
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <th class="border border-black px-1 py-1 text-xs bg-gray-200 text-center">Average</th>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                    </tr>
                    <tr class="text-center">
                        <th class="border border-black px-1 py-1 text-xs bg-gray-200 text-center">Maximum</th>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                    </tr>
                    <tr class="text-center">
                        <th class="border border-black px-1 py-1 text-xs bg-gray-200 text-center">Minimum</th>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
                        <td class="border border-black px-1 py-1 text-[10px]">N/A</td>
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


<!-- Print Button Outside -->
<div class="text-start ml-10">
    <button
        @click="window.print()"
        class="px-6 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition"
        >
        Print This Report
    </button>
</div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from 'vue';

// You could pass these via props or retrieve via an API
const printSerialNo = ref('2504000001');
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
const printCheckedBy = ref('');
const printApprovedBy = ref('');


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
        printPulseTracer.value = rd.pulse_tracer_machine_number;
        printDate.value = rd.date;
        printShift.value = rd.shift;
        printOperator.value = rd.operator;

        //Under Standard Sample Dimention and Material Grade
        printLength.value = rd.length;
        printWidth.value = rd.width;
        printThickness.value = rd.thickness;
        printMaterialGrade.value = rd.material_grade;
        printMPISampleQty.value = rd.mpi_sample_quantity;
        console.log("StandardSampleDimention array: ",standardSampleDimention.value);

        //Under Magnetic Property Data
        const mPD = JSON.parse(rd.magnetic_property_data);

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
