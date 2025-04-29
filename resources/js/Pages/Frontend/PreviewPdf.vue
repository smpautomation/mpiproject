<template>
<div class="print-container mx-auto mt-10">
  <!-- A4 Layout Box -->
  <div class="a4-page shadow-lg p-8 bg-white text-black border border-gray-300 max-w-5xl">
    <!-- Report Title -->
    <h1 class="text-2xl font-bold mb-6 text-center bg-gray-300">GBDP MAGNETIC PROPERTY INSPECTION REPORT</h1>

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
            <span class="font-semibold mr-2 text-sm">Pulse&nbsp;Tracer&nbsp;No:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printPulseTracer }}
            </span>
        </div>

        <!-- Second Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Date:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printDate }}
            </span>
        </div>

        <!-- Third Column -->
        <div class="flex items-center">
            <span class="font-semibold mr-2 text-sm">Shift:</span>
            <span class="inline-block border-b border-gray-500 w-full text-sm">
                {{ printShift }}
            </span>
        </div>

        <!-- Fourth Column -->
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
                    <th colspan="2" class="border border-black px-2 py-1">Standard Sample Dimention and Material Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value, key) in standardSampleDimention" :key="key">
                    <td class="border border-black px-4 py-[6.04px] text-xs">{{ key }}</td>
                    <td class="border border-black px-4 py-[6.04px] text-xs">{{ value }}</td>
                </tr>
            </tbody>
            </table>
        </div>
        <div>
            <!-- Data Table -->
            <table class="w-full border-collapse border border-gray-400 text-sm mb-6" style="border-spacing: 0;">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th colspan="7" class="border border-black px-2 py-2">Magnetic Property Data</th>
                    </tr>
                    <tr class="bg-gray-200 text-center">
                        <th rowspan="2" class="border border-black px-2 py-1">Items</th>
                        <th rowspan="2" class="border border-black px-2 py-1">Standard</th>
                        <th colspan="4" class="border border-black px-2 py-1">Actual Data</th>
                    </tr>
                    <tr class="bg-gray-200 text-center">
                        <th class="border border-black px-2 py-1">Average</th>
                        <th class="border border-black px-2 py-1">Maximum</th>
                        <th class="border border-black px-2 py-1">Minimum</th>
                        <th class="border border-black px-2 py-1">Variance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black px-2 py-2 text-xs">Br&nbsp;(G)</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printBrStandard }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printBrAverage }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printBrMaximum }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printBrMinimum }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printBrVariance }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-2 py-2 text-xs">iHc&nbsp;(Oe)</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHcStandard }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHcAverage }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHcMaximum }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHcMinimum }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHcVariance }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-2 py-2 text-xs">iHk&nbsp;(Oe)</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHkStandard }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHkAverage }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHkMaximum }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHkMinimum }}</td>
                        <td class="border border-black px-2 py-2 text-xs">{{ printiHkVariance }}</td>
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
                            backgroundImage: reportSMPJudgement === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : reportSMPJudgement === 'HOLD'
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
                            backgroundImage: reportSMPJudgement === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : reportSMPJudgement === 'HOLD'
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
                            backgroundImage: reportSMPJudgement === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : reportSMPJudgement === 'HOLD'
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
                            backgroundImage: reportSMPJudgement === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : reportSMPJudgement === 'HOLD'
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


    <div class="mb-5">
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

    <div class="break-after-page"></div>
  </div>

    <!-- A4 Layout Box -->
    <div class="a4-page shadow-lg p-8 bg-white text-black border border-gray-300 max-w-5xl">
        <!-- Report Title -->
        <h1 class="text-2xl font-bold mb-6 text-center bg-gray-300">PROPERTY&nbsp;DATA</h1>
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
import { ref, onMounted, nextTick } from 'vue';

// You could pass these via props or retrieve via an API
const printSerialNo = ref('2503000001');
const printSetNo = ref ('');
const printOvenMachineNo_ovenInfo = ref('N/A');
const printTimeLoading = ref('N/A');
const printTimeLoading_temp = ref('N/A');
const printTimeUnloading = ref('N/A');
const printTimeUnloading_temp = ref('N/A');
const printDate_ovenInfo = ref('N/A');
const printShift_ovenInfo = ref('N/A');
const printOperator_ovenInfo = ref('N/A');

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


const standardSampleDimention = ref({
  'LENGTH (mm)': printLength.value,
  'WIDTH (mm)': printWidth.value,
  'THICKNESS (mm)': printThickness.value,
  'MATERIAL GRADE': printMaterialGrade.value,
  'MPI SAMPLE QTY.': printMPISampleQty.value,
});



// Optional: auto print on page load
onMounted(() => {
  // window.print()
})
</script>

<style scoped>
/* A4 size container */
.a4-page {
  width: 210mm;
  min-height: 297mm;
  box-sizing: border-box;
  page-break-after: always;
  background: white;
}

/* Print-specific tweaks */
@media print {
  body {
    margin: 0;
    background: white;
  }

  .a4-page {
    box-shadow: none !important;
    margin: 0 auto;
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
