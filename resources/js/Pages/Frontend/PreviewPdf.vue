<template>
<div class="mx-auto print-container">
  <!-- A4 Layout Box -->
  <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page px-7">
    <div class="flex flex-row justify-between">
        <button class="px-2 py-1 mt-2 text-white bg-gray-600 rounded-lg" @click="$inertia.visit('/reports')">back</button>
        <button class="px-2 py-1 mt-2 text-black bg-gray-300 rounded-lg">Serial#: {{ printSerialNo }}</button>
        <button
        @click="window.print()"
        class="px-2 py-1 mt-2 text-white transition bg-yellow-500 rounded-lg hover:bg-yellow-600"
        >
        Print This Report
    </button>
    </div>
    <!-- Report Title -->
    <h1 class="my-6 text-2xl font-bold text-center bg-gray-300">GBDP MAGNETIC PROPERTY INSPECTION REPORT</h1>
    <!-- Oven Heating Information -->
    <div class="flex flex-row justify-between">
        <p class="mb-4 text-xl font-extrabold">Oven Heating Information</p>
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
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printOvenMachineNo_ovenInfo }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Time Loading:</span>
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printTimeLoading }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Temperature:</span>
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printTimeLoading_temp }}
                </span>
            </div>
        </div>

        <!-- Second Column -->
        <div class="space-y-1">
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Date:</span>
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printDate_ovenInfo }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Time Unloading:</span>
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printTimeUnloading }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Temperature:</span>
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printTimeUnloading_temp }}
                </span>
            </div>
        </div>

        <!-- Third Column -->
        <div class="space-y-1">
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Shift:</span>
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printShift_ovenInfo }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-sm font-semibold">Operator:</span>
                <span class="flex-grow inline-block text-sm border-b border-gray-500">
                    {{ printOperator_ovenInfo }}
                </span>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <hr class="my-4" />

    <p class="mb-4 text-xl font-extrabold">Magnetic Property Inspection Information</p>

    <!-- Single Row Information -->
    <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-4">
        <!-- First Column -->
        <div class="flex items-center">
            <span class="mr-2 text-sm font-semibold">Model:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printActualModel }}
            </span>
        </div>

        <!-- Second Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-[10px]">MATERIAL&nbsp;CODE:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printMaterialCode }}
            </span>
        </div>

        <!-- Third Column -->
        <div class="flex items-center">
            <span class="mr-2 text-sm font-semibold">Partial&nbsp;No.:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printPartialNo }}
            </span>
        </div>

        <!-- Fourth Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-[10px]">TOTAL&nbsp;QUANTITY:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printTotalQuantity }}
            </span>
        </div>
        <!-- Fifth Column -->
        <div class="flex items-center">
            <span class="mr-2 text-sm font-semibold">Pulse&nbsp;Tracer&nbsp;No:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printPulseTracer }}
            </span>
        </div>

        <!-- Sixth Column -->
        <div class="flex items-center">
            <span class="mr-2 text-sm font-semibold">Date:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printDate }}
            </span>
        </div>

        <!-- Seventh Column -->
        <div class="flex items-center">
            <span class="mr-2 text-sm font-semibold">Shift:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printShift }}
            </span>
        </div>

        <!-- Eighth Column -->
        <div class="flex items-center">
            <span class="mr-2 text-sm font-semibold">Operator:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
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
            <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                <thead>
                    <tr class="text-center bg-gray-200">
                        <th colspan="7" class="border border-black px-2 py-[9.5px] text-sm">Magnetic Property Data</th>
                    </tr>
                    <tr class="text-center bg-gray-200">
                        <th rowspan="2" class="border border-black px-2 py-[3.04px]">Items</th>
                        <th rowspan="2" class="border border-black px-2 py-[3.04px]">Standard</th>
                        <th colspan="4" class="border border-black px-2 py-[3.04px]">Actual Data</th>
                    </tr>
                    <tr class="text-center bg-gray-200">
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
            <span class="mr-2 text-sm font-semibold">Remarks:</span>
            <span class="inline-block w-full text-sm border-b border-gray-500">
                {{ printRemarks }}
            </span>
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
    <hr class="my-4" />

    <div class="mb-5">
        <p class="mb-4 text-xl font-extrabold">SMP Judgement:</p>
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
                <div class="relative flex items-center justify-center flex-grow w-full bg-white">
                    <!-- The stamp -->
                    <span
                        class="absolute w-[100px] h-[100px] items-center flex text-red-600 font-extrabold bg-center bg-no-repeat justify-center"
                        :style="{
                            backgroundImage: printPreparedBy != '' && printPreparedBy != null
                                ? 'url(\'/photo/Prepared_by_stamp.png\')'
                                : printPreparedBy === 'HOLD'
                                ? 'url(\'/photo/cancel.png\')'
                                : 'url(\'/photo/cancel.png\')',
                            backgroundSize: 'contain'
                        }">
                        {{ printPreparedBy_date }}
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
                <div class="relative flex items-center justify-center flex-grow w-full bg-white">
                    <!-- The stamp -->
                    <span
                        class="absolute w-[100px] h-[100px] bg-center bg-no-repeat items-center flex text-red-600 font-extrabold justify-center"
                        :style="{
                            backgroundImage: printCheckedBy != '' && printCheckedBy != null
                                ? 'url(\'/photo/Checked_by_stamp.png\')'
                                : printCheckedBy === 'HOLD'
                                ? 'url(\'/photo/cancel.png\')'
                                : 'url(\'/photo/cancel.png\')',
                            backgroundSize: 'contain'
                        }">
                        {{ printCheckedBy_date }}
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
                <div class="relative flex items-center justify-center flex-grow w-full bg-white">
                    <!-- The stamp -->
                    <span
                        class="absolute w-[100px] h-[100px] bg-center bg-no-repeat items-center flex text-red-600 font-extrabold justify-center"
                        :style="{
                            backgroundImage: printApprovedBy != '' && printApprovedBy != null
                                ? 'url(\'/photo/Approved_by_stamp.png\')'
                                : printApprovedBy === 'HOLD'
                                ? 'url(\'/photo/cancel.png\')'
                                : 'url(\'/photo/cancel.png\')',
                            backgroundSize: 'contain'
                        }">
                        {{ printApprovedBy_date }}
                    </span>
                </div>
            </div>
            <div class="ml-2">
                <div class="flex flex-col p-1 border border-black">
                    <div class="text-[10px] pb-1 font-extrabold">Note: (REASON OF HOLD/REJECT)</div>
                    <p class="text-[10px] text-red-600 font-extrabold" v-if="noteReasonForReject.length">
                        <span v-for="(reason, index) in noteReasonForReject" :key="index" class="block">
                            {{ reason }}
                        </span>
                    </p>
                    <span v-else class="text-sm text-gray-500">No rejection notes available.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <hr class="my-4" />


    <div>
        <p class="mb-4 text-xl font-extrabold">SEC / C-Mark Judgement:</p>
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
    <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page px-7">
        <!-- Report Title -->
        <h1 class="my-6 text-2xl font-bold text-center bg-gray-300">PROPERTY&nbsp;DATA</h1>
        <!-- Information Grid -->
        <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-5">
            <!-- First Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Code&nbsp;No:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printCodeNo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Sintering#:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printSinteringNo }}
                    </span>
                </div>
            </div>
            <!-- Second Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Type&nbsp;Code:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printTypeCode }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">coating:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printCoating }}
                    </span>
                </div>
            </div>
            <!-- Third Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Judge&nbsp;Code:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printJudgeCode }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Pass#:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printPassNo }}
                    </span>
                </div>
            </div>
            <!-- Fourth Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Press#:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printPressNo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Mias.&nbsp;Employee:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printMiasEmployee }}
                    </span>
                </div>
            </div>
            <!-- Fifth Column -->
            <div class="space-y-1">
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Sintering&nbsp;Furnace#:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printSinteringFurnaceNo }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs font-semibold">Factor&nbsp;Employee:</span>
                    <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                        {{ printFactoryEmployee }}
                    </span>
                </div>
            </div>
        </div>
        <div>
            <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                <thead>
                    <tr class="text-center bg-gray-200">
                        <th class="px-1 py-1 text-xs border border-black">Zone</th>
                        <th class="px-1 py-1 text-xs border border-black">Br</th>
                        <th class="px-1 py-1 text-xs border border-black">iHc</th>
                        <th class="px-1 py-1 text-xs border border-black">iHk</th>
                        <th class="px-1 py-1 text-xs border border-black">[BH]m</th>
                        <th class="px-1 py-1 text-xs border border-black">Hr95</th>
                        <th class="px-1 py-1 text-xs border border-black">Hr98</th>
                        <th class="px-1 py-1 text-xs border border-black">iHc&#8209;iHk</th>
                        <th class="px-1 py-1 text-xs border border-black">Br&#960;Ia</th>
                        <th class="px-1 py-1 text-xs border border-black">bHc</th>
                        <th class="px-1 py-1 text-xs border border-black">Squareness</th>
                        <th class="px-1 py-1 text-xs border border-black">4&#960;Id</th>
                        <th class="px-1 py-1 text-xs border border-black">4&#960;Is</th>
                        <th class="px-1 py-1 text-xs border border-black">4&#960;Ia</th>
                        <th class="px-1 py-1 text-xs border border-black">Temperature</th>
                        <th class="px-1 py-1 text-xs border border-black">Data&nbsp;Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in printTPMData"
                                            :key="item.id"
                                            class="text-center whitespace-nowrap text-[10px]">
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.zone }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.Br }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.iHc }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.iHk }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.BHMax }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.iHr95 }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.iHr98 }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.iHkiHc }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.Br4pai }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.bHc }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.Squareness }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item["4paiIa"] }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item["4paiId"] }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item["4paiIs"] }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.temperature }}</td>
                        <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ item.data_status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                <thead>
                    <tr class="text-center bg-gray-200">
                        <th class="px-1 py-1 text-xs border border-black"></th>
                        <th class="px-1 py-1 text-xs border border-black">Br</th>
                        <th class="px-1 py-1 text-xs border border-black">iHc</th>
                        <th class="px-1 py-1 text-xs border border-black">iHk</th>
                        <th class="px-1 py-1 text-xs border border-black">[BH]m</th>
                        <th class="px-1 py-1 text-xs border border-black">Hr95</th>
                        <th class="px-1 py-1 text-xs border border-black">Hr98</th>
                        <th class="px-1 py-1 text-xs border border-black">iHc&#8209;iHk</th>
                        <th class="px-1 py-1 text-xs border border-black">Br&#960;Ia</th>
                        <th class="px-1 py-1 text-xs border border-black">bHc</th>
                        <th class="px-1 py-1 text-xs border border-black">Squareness</th>
                        <th class="px-1 py-1 text-xs border border-black">4&#960;Id</th>
                        <th class="px-1 py-1 text-xs border border-black">4&#960;Is</th>
                        <th class="px-1 py-1 text-xs border border-black">4&#960;Ia</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- AVERAGE ROW -->
                    <tr class="text-center">
                        <th class="px-1 py-1 text-xs bg-gray-200 border border-black">Average</th>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printBrAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printiHcAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printiHkAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printBHMaxAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printHr95Average }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printHr98Average }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printiHciHkAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printBrpaiIaAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printbHcAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ printSquarenessAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ print4paiIdAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ print4paiIsAverage }}</td>
                        <td class="border border-black px-1 py-[2px] text-[10px]">{{ print4paiIaAverage }}</td>
                    </tr>

                    <!-- MAXIMUM ROW -->
                    <tr class="text-center">
                        <th class="px-1 py-1 text-xs bg-gray-200 border border-black">Maximum</th>
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
                        <th class="px-1 py-1 text-xs bg-gray-200 border border-black">Minimum</th>
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
                            <p class="font-extrabold">Note:</p>
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
                                <p class="text-[10px] text-red-600 font-extrabold">
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
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Date</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Code&nbsp;No</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Order&nbsp;No</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Type</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">LOT#</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Furnace#</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Coating#</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Zone</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Pass#</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Br</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">iHc</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">iHk</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">BHMax</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Hr95</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Hr98</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">iHkiHc</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Br4pai</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">bHc</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Squareness</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">4paiId</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">4paiIs</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">4paiIa</th>
                            <th class="border border-black px-[2px] py-1 text-[7.5px]">Tracer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in printTPMData"
                                            :key="item.id"
                                            class="text-center whitespace-nowrap text-[10px]">
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.date }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.code_no }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.order_no }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.type }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.press_1 + " " + item.press_2 + " " + item.machine_no }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.sintering_furnace_no }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.furnace_no }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.zone }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.pass_no }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.Br }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.iHc }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.iHk }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.BHMax }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.iHr95 }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.iHr98 }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.iHkiHc }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.Br4pai }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.bHc }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.Squareness }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item["4paiId"] }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item["4paiIs"] }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item["4paiIa"] }}</td>
                            <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ item.Tracer }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- A4 Layout Box -->
    <button>pagesData length: {{ numberOfSet }}</button>

    <div v-for="index in numberOfSet" :key="index">
        <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page px-7">
            <!-- Report Title -->
            <h1 class="mt-6 text-2xl font-bold text-center bg-gray-300">PROPERTY&nbsp;DATA <button>Set #:{{ index }}</button></h1>
            <div class="flex flex-row items-center justify-center mt-2 mb-5 text-blue-600">
                <p class="text-sm">{{ additionalRemarks[index - 1] }}</p>
            </div>
            <!-- Information Grid -->
            <div class="grid grid-cols-1 gap-4 mb-2 sm:grid-cols-5">
                <!-- First Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Code&nbsp;No:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.codeNo }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Sintering#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.sinteringNo }}
                        </span>
                    </div>
                </div>
                <!-- Second Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Type&nbsp;Code:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.typeCode }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">coating:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.coating }}
                        </span>
                    </div>
                </div>
                <!-- Third Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Judge&nbsp;Code:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.judgeCode }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Pass#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.passNo }}
                        </span>
                    </div>
                </div>
                <!-- Fourth Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Press#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.pressNo }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Mias.&nbsp;Employee:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.miasEmployee }}
                        </span>
                    </div>
                </div>
                <!-- Fifth Column -->
                <div class="space-y-1">
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Sintering&nbsp;Furnace#:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.sinteringFurnaceNo }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-semibold">Factor&nbsp;Employee:</span>
                        <span class="flex-grow inline-block text-[10px] border-b border-gray-500">
                            {{ pagesData[index-1]?.factoryEmployee }}
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                    <thead>
                        <tr class="text-center bg-gray-200">
                            <th class="px-1 py-1 text-xs border border-black">Zone</th>
                            <th class="px-1 py-1 text-xs border border-black">Br</th>
                            <th class="px-1 py-1 text-xs border border-black">iHc</th>
                            <th class="px-1 py-1 text-xs border border-black">iHk</th>
                            <th class="px-1 py-1 text-xs border border-black">[BH]m</th>
                            <th class="px-1 py-1 text-xs border border-black">Hr95</th>
                            <th class="px-1 py-1 text-xs border border-black">Hr98</th>
                            <th class="px-1 py-1 text-xs border border-black">iHc&#8209;iHk</th>
                            <th class="px-1 py-1 text-xs border border-black">Br&#960;Ia</th>
                            <th class="px-1 py-1 text-xs border border-black">bHc</th>
                            <th class="px-1 py-1 text-xs border border-black">Squareness</th>
                            <th class="px-1 py-1 text-xs border border-black">4&#960;Id</th>
                            <th class="px-1 py-1 text-xs border border-black">4&#960;Is</th>
                            <th class="px-1 py-1 text-xs border border-black">4&#960;Ia</th>
                            <th class="px-1 py-1 text-xs border border-black">Temperature</th>
                            <th class="px-1 py-1 text-xs border border-black">Data&nbsp;Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Assume this loop is inside your templating engine's structure, e.g., Vue `v-for` or similar -->
                        <tr  v-for="(TPM, tpmIndex) in pagesData[index - 1]?.tableTPM"
                            :key="tpmIndex"
                            class="text-center whitespace-nowrap text-[10px]"
                            >
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.zone }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Br }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHc }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHk }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.BHMax }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHr95 }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHr98 }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.iHkiHc }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Br4pai }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.bHc }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.Squareness }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM["4paiIa"] }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM["4paiId"] }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM["4paiIs"] }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.temperature }}</td>
                            <td class="border border-black px-[2px] py-[2px] text-[10px]">{{ TPM.data_status }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <table class="w-full mb-6 text-sm border border-collapse border-gray-400" style="border-spacing: 0;">
                    <thead>
                        <tr class="text-center bg-gray-200">
                            <th class="px-1 py-1 text-xs border border-black"></th>
                            <th class="px-1 py-1 text-xs border border-black">Br</th>
                            <th class="px-1 py-1 text-xs border border-black">iHc</th>
                            <th class="px-1 py-1 text-xs border border-black">iHk</th>
                            <th class="px-1 py-1 text-xs border border-black">[BH]m</th>
                            <th class="px-1 py-1 text-xs border border-black">Hr95</th>
                            <th class="px-1 py-1 text-xs border border-black">Hr98</th>
                            <th class="px-1 py-1 text-xs border border-black">iHc&#8209;iHk</th>
                            <th class="px-1 py-1 text-xs border border-black">Br&#960;Ia</th>
                            <th class="px-1 py-1 text-xs border border-black">bHc</th>
                            <th class="px-1 py-1 text-xs border border-black">Squareness</th>
                            <th class="px-1 py-1 text-xs border border-black">4&#960;Id</th>
                            <th class="px-1 py-1 text-xs border border-black">4&#960;Is</th>
                            <th class="px-1 py-1 text-xs border border-black">4&#960;Ia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AVERAGE ROW -->
                        <tr class="text-center">
                            <th class="px-1 py-1 text-xs bg-gray-200 border border-black">Average</th>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.brAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.iHcAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.iHkAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.bHMaxAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.hr95Average }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.hr98Average }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.iHciHkAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.brpaiIaAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.bHcAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.squarenessAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.fourpaiIdAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.fourpaiIsAverage }}</td>
                            <td class="border border-black px-1 py-[2px] text-[10px]">{{ pagesData[index-1]?.fourpaiIaAverage }}</td>
                        </tr>

                        <!-- MAXIMUM ROW -->
                        <tr class="text-center">
                            <th class="px-1 py-1 text-xs bg-gray-200 border border-black">Maximum</th>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.brMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.iHcMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.iHkMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.bHMaxMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.hr95Maximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.hr98Maximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.iHciHkMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.brpaiIaMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.bHcMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.squarenessMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.fourpaiIdMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.fourpaiIsMaximum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.fourpaiIaMaximum }}</td>
                        </tr>

                        <!-- MINIMUM ROW -->
                        <tr class="text-center">
                            <th class="px-1 py-1 text-xs bg-gray-200 border border-black">Minimum</th>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.brMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.iHcMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.iHkMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.bHMaxMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.hr95Minimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.hr98Minimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.iHciHkMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.brpaiIaMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.bHcMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.squarenessMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.fourpaiIdMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.fourpaiIsMinimum }}</td>
                            <td class="border border-black px-1 py-1 text-[10px]">{{ pagesData[index-1]?.fourpaiIaMinimum }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div v-for="index in numberOfSet" :key="index">
        <div class="max-w-5xl mx-auto text-black bg-white border border-gray-300 shadow-lg a4-page p-7">
            <div class="flex flex-col my-6">
                <button>Set #{{ index }}</button>
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
                                <p class="font-extrabold">Note:</p>
                                <p>
                                    SMP Lot (
                                    <span>{{ pagesData[index - 1].massProd }}</span>
                                    Mass Production )
                                </p>
                                <p>
                                    Furnace Cycle No. : {{ pagesData[index - 1].sinteringFurnaceNo + "-" + pagesData[index - 1].sinteringNo }}
                                </p>
                                <p>
                                    <span>{{ pagesData[index - 1].actualModel }}</span>
                                    ( {{ pagesData[index - 1].codeNo }} )
                                </p>
                                <p>
                                    Lot # {{ pagesData[index - 1].jhcurveLotNo }}
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
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Date</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Code&nbsp;No</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Order&nbsp;No</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Type</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">LOT#</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Furnace#</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Coating#</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Zone</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Pass#</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Br</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">iHc</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">iHk</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">BHMax</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Hr95</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Hr98</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">iHkiHc</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Br4pai</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">bHc</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Squareness</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">4paiId</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">4paiIs</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">4paiIa</th>
                                <th class="border border-black px-[2px] py-1 text-[7.5px]">Tracer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(TPM, tpmIndex) in pagesData[index - 1]?.tableTPM"
                                :key="tpmIndex" class="text-center whitespace-nowrap text-[10px]"
                            >
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.date }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.codeNo }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.orderNo }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.type }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.lotNo }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.furnaceNo }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.coatingNo }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.zone }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.passNo }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.Br }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.iHc }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.iHk }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.BHMax }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.iHr95 }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.iHr98 }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.iHkiHc }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.Br4pai }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.bHc }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.Squareness }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM["4paiId"] }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM["4paiIs"] }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM["4paiIa"] }}</td>
                                <td class="border border-black px-[2px] py-1 text-[7.5px]">{{ TPM.tracer }}</td>
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
// Register all Chart.js components using registerables
Chart.register(...registerables);

const numberOfSet = ref(0); // or whatever number you want
const testing = ref("");
const isAutomotive = ref(false);
// You could pass these via props or retrieve via an API
const printSerialNo = ref('');
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
const printRemarksResult = ref('N/A');

const printSMPJudgement = ref('');
const printPreparedBy = ref('');
const printPreparedBy_date = ref('');
const printCheckedBy = ref('');
const printCheckedBy_date = ref('');
const printApprovedBy = ref('');
const printApprovedBy_date = ref('');

const noteReasonForReject = ref([]);
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

        isAutomotive.value = rd.withCarmark == 1;

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
        nsa_printActualModel.value = rd.model;
        printMaterialCode.value = rd.material_code;
        printPartialNo.value = rd.partial_number;
        printPulseTracer.value = rd.pulse_tracer_machine_number;
        printDate.value = rd.date;
        printShift.value = rd.shift;
        printOperator.value = rd.operator;
        printTotalQuantity.value = rd.total_quantity;
        printRemarks.value = rd.remarks;
        printRemarksResult.value = rd.remarks_display;

        //Under Standard Sample Dimention and Material Grade
        printLength.value = rd.length;
        printWidth.value = rd.width;
        printThickness.value = rd.thickness;
        printMaterialGrade.value = rd.material_grade;
        printMPISampleQty.value = rd.mpi_sample_quantity;
        //console.log("StandardSampleDimention array: ",standardSampleDimention.value);

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
        printPreparedBy_date.value = rd.prepared_by_date ? rd.prepared_by_date.split(' ')[0] : '';
        printCheckedBy_date.value = rd.checked_by_date ? rd.checked_by_date.split(' ')[0] : '';
        printApprovedBy_date.value = rd.approved_by_date ? rd.approved_by_date.split(' ')[0] : '';

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

const dataFrom_tpmData = async () => {
    try{
        const responseTpmData = await axios.get("api/tpmdata?serial=" + printSerialNo.value);
        //console.log("API GET request-responseTpmData",responseTpmData.data);
        printTPMData.value = responseTpmData.data.data;
        console.log("printTPMData-data: ",printTPMData.value);
        const TPM = responseTpmData.data.data[0];
        const [furnaceNo, sinteringNo] = TPM.sintering_furnace_no.split('-');

        const tpmCat = printTPMData.value[0].category;
        console.log("tpmCat-data: ",tpmCat);
        printCodeNo.value = TPM.code_no;
        printTypeCode.value = TPM.type;
        printSinteringNo.value = sinteringNo;
        printSinteringFurnaceNo.value = furnaceNo;
        printCoating.value = TPM.furnace_no;
        printJudgeCode.value = TPM.order_no;
        printPassNo.value = TPM.pass_no;
        printPressNo.value = TPM.press_1 + " " + TPM.press_2 + " " + TPM.machine_no;
        printMiasEmployee.value = tpmCat.mias_emp;
        printFactoryEmployee.value = tpmCat.factor_emp;
        printMassProdName.value = tpmCat.massprod_name;
        printJhCurveLotno.value = tpmCat.jhcurve_lotno;

        const printAggregateData = responseTpmData.data[0][0];
        console.log("AggregateData-data: ",printAggregateData);
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
                    animation: {
                        duration: 1000,
                        easing: "easeOutQuart",
                    },
                    plugins: {
                        legend: {
                            display: false, // 👈 hides the legend completely
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Value: ${context.raw.y}`,
                            },
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
        const responseCheckNSA = await axios.get("api/nsadata?serial=" + printSerialNo.value);
        console.log("API GET REQUEST responseCheckNSA-data:", responseCheckNSA.data);
        const nsadata = responseCheckNSA.data.data;
        if (Object.keys(nsadata).length > 0) {

            const nsaArray = Object.values(nsadata);
            const maxSetNo = Math.max(...nsaArray.map(item => item.set_no));
            numberOfSet.value = maxSetNo;

            additionalRemarks.value = nsadata.map(item => item.set_name);
            console.log('getting all the additional descriptions:',additionalRemarks.value);
            //gggg
        }else{
            console.log('NOT ARRAY!!');
        }
        console.log("NUMBER OF SETS: ",numberOfSet.value);
    } catch (error) {
        console.error("ERROR GET REQUEST responseCheckNSA-data:", error);
    }
};

const pagesData = ref([]);
const nsa_dataFrom_tpmData = async () => {
    console.log("You have entered nsa_dataFrom_tpmData");

    try {
        console.log("You have entered nsa_dataFrom_tpmData TRY CATCH");

        if (numberOfSet.value <= 0) {
            console.warn("numberOfSet.value is zero or negative, skipping loop.");
            return;  // Early exit if no sets to process.
        }

        for (let x = 1; x <= numberOfSet.value; x++) {
            console.log(`Fetching data for set #${x}...`);

            // Fetch data from API
            const responseNsaData = await axios.get("api/nsadata?serial=" + printSerialNo.value);
            console.log(`API response for set #${x}:`, responseNsaData.data);

            const nsadata = responseNsaData.data.data;
            const nsaGeneral = responseNsaData.data;

            if (!nsadata || nsadata.length === 0) {
                console.warn(`No data found for set #${x}`);
                continue; // Skip this iteration if no data is available
            }

            // Filter the data for the current set
            const nsa_filteredData = nsadata.filter(item => item.set_no == x);
            console.log(`Filtered data for set #${x}:`, nsa_filteredData);

            if (nsa_filteredData.length === 0) {
                console.warn(`No filtered data for set #${x}`);
                continue; // Skip this iteration if no filtered data
            }

            const tableTPMRows = [];

            // Loop through the filtered data and populate the table
            for (let i = 0; i < nsa_filteredData.length; i++) {
                const TPM = nsa_filteredData[i];
                const tpmCat = TPM?.category;

                // Skip the iteration if required properties are missing
                if (!TPM || !tpmCat || !TPM.sintering_furnace_no) {
                    console.log(`Skipping TPM #${i} due to missing required properties.`);
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
                    iHc: TPM.iHc ?? "",
                    iHk: TPM.iHk ?? "",
                    BHMax: TPM.BHMax ?? "",
                    iHr95: TPM.iHr95 ?? "",
                    iHr98: TPM.iHr98 ?? "",
                    iHkiHc: TPM.iHkiHc ?? "",
                    Br4pai: TPM.Br4pai ?? "",
                    bHc: TPM.bHc ?? "",
                    Squareness: TPM.Squareness ?? "",
                    "4paiIa": TPM["4paiIa"] ?? "",
                    "4paiId": TPM["4paiId"] ?? "",
                    "4paiIs": TPM["4paiIs"] ?? "",
                    tracer: TPM.Tracer ?? "",
                    temperature: TPM.temperature ?? "",
                    data_status: TPM.data_status ?? ""
                };
                tableTPMRows.push(row);
            }

            // General page-level info
            const firstTPM = nsa_filteredData[0];
            const [furnaceNo, sinteringNo] = firstTPM?.sintering_furnace_no?.split('-') ?? ["", ""];
            console.log(`furnace and sintering no for page:`, { furnaceNo, sinteringNo });

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
            if (nsaGeneral[x-1] && nsaGeneral[x-1][0]) {
                console.log(`Parsing additional data for set #${x} from nsaGeneral:`, nsaGeneral[x-1][0]);

                const average = JSON.parse(nsaGeneral[x-1][0].average);
                const maximum = JSON.parse(nsaGeneral[x-1][0].maximum);
                const minimum = JSON.parse(nsaGeneral[x-1][0].minimum);

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
            } else {
                console.warn(`nsaGeneral[${x}] or nsaGeneral[${x}][0] is undefined!`);
            }

            console.log(`Page contents for set #${x}:`, page);

            // Add page data to the pagesData array
            pagesData.value.push(page);

            console.log("PAGESDATA contents: ",pagesData.value);
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
            const response = await axios.get("api/nsadata?serial=" + printSerialNo.value);
            const nsadata = response.data.data;

            const filtered = nsadata.filter(item => item.set_no == x);

            const datasetsForSet = filtered.map((row, index) => ({
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
            animation: {
                duration: 1000,
                easing: "easeOutQuart",
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => `Value: ${context.raw.y}`,
                    },
                },
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
                },
                y: {
                    type: "linear",
                    title: {
                        display: true,
                        text: "←  kG  →",
                        color: "#333",
                        font: { size: 14, weight: "bold" },
                    },
                },
            },
        },
    });
};

// Define the prop that will receive the serialParam
const props = defineProps({
  serialParam: String,  // Expecting the serialParam to be a string
});
printSerialNo.value = props.serialParam;
console.log('Serial Param in PreviewPdf.vue:', props.serialParam); // You can use this for debugging

// Optional: auto print on page load
onMounted( async () => {
    // window.print()
    await dataFrom_reportdata();
    await dataFrom_inspectiondata();
    await dataFrom_tpmData();
    await fetchDataCreateGraph();
    await checkingNSA();
    await nsa_dataFrom_tpmData();
    await nsa_graph();
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
