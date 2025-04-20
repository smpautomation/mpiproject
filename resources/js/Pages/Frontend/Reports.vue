<template>
    <Frontend>
      <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">

        <div v-show="showSelectionPanel">
            <div> <!-- Selection Panel -->
            <div class="flex flex-row items-center justify-center mt-10 align-baseline">
                <p>Select Serial No: </p>
                <select
                v-model="currentSerialSelected"
                class="py-2 m-4 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm px-auto focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option v-for="serial in serialList" :key="serial" :value="serial">{{ serial }}</option>
                </select>
            </div>
            <div class="flex flex-row items-center justify-center m-10 space-x-8">
            <!-- Button -->
            <button
                @click="generateReport"
                class="px-6 py-3 text-xl font-extrabold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                View Report
            </button>

            <!-- Checkbox + Label -->
            <label class="flex items-center space-x-3 text-lg">
                <input
                v-model="isTTM_model"
                type="checkbox"
                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
                <span>(Tick this box if the TTM model applies.)</span>
            </label>
            </div>
            <div v-show="showNotif2" class="flex flex-row items-center justify-center py-2 mx-auto my-10 text-white bg-yellow-500 shadow-lg rounded-2xl px-28">
                <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
            </div>
        </div>
        </div>

        <div v-show="showReportContent">
             <!-- Report Content -->
             <div class="flex flex-col justify-center py-10 mx-20 mt-10 mb-20 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="w-full max-w-4xl px-4 mx-auto mb-10">
                    <div class="flex flex-col items-center justify-between w-full gap-4 p-6 transition border shadow-lg sm:flex-row bg-white/30 border-white/50 rounded-xl backdrop-blur-md hover:shadow-xl hover:border-white/70">

                        <!-- Serial -->
                        <div class="text-center sm:text-left">
                        <p class="text-sm font-bold text-blue-800">Serial</p>
                        <p class="text-xl font-extrabold text-blue-900">{{ currentSerialSelected }}</p>
                        </div>

                        <!-- Furnace -->
                        <div class="text-center sm:text-left">
                        <p class="text-sm font-bold text-blue-800">Furnace</p>
                        <p class="text-lg font-semibold text-blue-900">{{ currentFurnaceName }}</p>
                        </div>

                        <!-- Layer -->
                        <div class="text-center sm:text-left">
                        <p class="text-sm font-bold text-blue-800">Layer</p>
                        <p class="text-lg font-semibold text-blue-900">{{ currentLayerName }}</p>
                        </div>

                    </div>
                </div>
                <div class="p-5 mx-10 border-2 border-white rounded-lg shadow-xl">
                    <div class="flex flex-row mb-4 justify-evenly">
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-extrabold">Model:</label>&nbsp;
                            <span
                                class="px-4 py-1 text-gray-800 transition duration-200 ease-in-out bg-white rounded-md cursor-default hover:ring-1 hover:ring-blue-500 hover:shadow-md"
                                >
                                {{ reportModel }}
                            </span>
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Material Code:</label>&nbsp;
                            <input
                                v-model="reportMaterialCode"
                                type="text"
                                name="date"
                                class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                            />
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Partial No.:</label>&nbsp;
                            <input v-model="reportPartialNo" type="text" name="shift" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out">
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Total Quantity:</label>&nbsp;
                            <input v-model="reportTotalQuantity" type="number" name="operator" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out">
                        </div>
                    </div>
                    <div class="flex flex-row mb-4 justify-evenly">
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Pulse Tracer Machine No:</label>&nbsp;
                            <span class="px-4 py-1 text-gray-800 transition duration-200 ease-in-out bg-white rounded-md cursor-default hover:ring-1 hover:ring-blue-500 hover:shadow-md">{{ reportPulseTracerMachineNo }}</span>
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Date:</label>&nbsp;
                            <input v-model="reportDate" type="date" name="date" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out">
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Shift:</label>&nbsp;
                            <input v-model="reportShift" type="text" name="shift" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out">
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Operator:</label>&nbsp;
                            <input v-model="reportOperator" type="text" name="operator" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out">
                        </div>
                    </div>

                    <div class="flex flex-row items-center justify-center align-middle">
                        <table class="mt-[1.1rem] border-collapse table-auto border-4 border-white">
                            <thead>
                                <tr class="bg-blue-400">
                                    <th colspan="2" class="px-2 py-2 text-lg font-semibold text-center text-white">STANDARD&nbsp;SAMPLE&nbsp;DIMENSION&nbsp;and&nbsp;Material&nbsp;Grade<br>標準サンプル寸法および材料グレード</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">LENGTH&nbsp;(mm)</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ reportLength }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">WIDTH&nbsp;(mm)</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ reportWidth }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">THICKNESS&nbsp;(mm)</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ reportThickness }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">MATERIAL&nbsp;GRADE</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ reportMaterialGrade }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">MPI&nbsp;SAMPLE&nbsp;QTY.</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ reportMPISampleQty }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="ml-2 mt-[1rem] border-collapse table-auto border-4 border-white">
                            <thead>
                                <tr class="bg-blue-400">
                                    <th colspan="7" class="py-4 text-lg font-semibold text-center text-white border-4 border-white">MAGNETIC&nbsp;PROPERTY&nbsp;DATA(磁気特性データ)</th>
                                </tr>
                                <tr class="bg-blue-300">
                                    <th rowspan="2" class="px-4 text-white border-4 border-white">ITEMS</th>
                                    <th rowspan="2" class="px-4 text-white border-4 border-white">STANDARD</th>
                                    <th colspan="5" class="px-4 text-center text-white border-4 border-white">ACTUAL DATA</th>
                                </tr>
                                <tr class="bg-blue-300">
                                    <th class="px-4 text-white border-4 border-white">AVERAGE</th>
                                    <th class="px-4 text-white border-4 border-white">MAXIMUM</th>
                                    <th class="px-4 text-white border-4 border-white">MINIMUM</th>
                                    <th colspan="2" class="px-4 text-white border-4 border-white">VARIANCE<br>(max-min)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">Br (G)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrVariance }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">iHc (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHcVariance }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">iHk (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHkVariance }}</td>
                                </tr>
                                <tr v-show="isTTM_model" class="bg-blue-300">
                                    <th rowspan="2" class="px-4 py-2 text-xl text-white border-4 border-white">Br Cpk</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">STD DEV</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">Cpu</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">Cpl</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">Cpk</th>
                                    <th colspan="2" class="px-4 py-2 text-white border-4 border-white">Remarks</th>
                                </tr>
                                <tr v-show="isTTM_model" class="text-center">
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                </tr>
                                <tr v-show="show1x1x1Data" class="text-center bg-blue-300">
                                    <td colspan="2" class="px-4 py-2 font-extrabold text-white border-4 border-white">Data of 1x1x1 mm samples</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">AVERAGE</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">MAXIMUM</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">MINIMUM</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">Cpk</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">Remarks</td>
                                </tr>
                                <tr v-show="show1x1x1Data" class="text-center">
                                    <th class="px-4 py-2 font-extrabold text-white bg-blue-300 border-4 border-white">Corner</th>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                </tr>
                                <tr v-show="show1x1x1Data" class="text-center">
                                    <th class="px-4 py-2 font-extrabold text-white bg-blue-300 border-4 border-white">Surface</th>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                </tr>
                                <tr v-show="show1x1x1Data" class="text-center">
                                    <th class="px-4 py-2 font-extrabold text-white bg-blue-300 border-4 border-white">Core</th>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">NA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-row items-center justify-center mx-5 mt-5 align-middle">
                        <p class="m-5 text-lg font-extrabold">Remarks:</p>
                        <input
                            v-model="reportRemarks"
                            type="text"
                            class="w-full px-2 py-1 text-sm transition duration-200 ease-in-out bg-transparent bg-white border border-white rounded-md hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white"
                        />
                        <div class="px-0 py-5 mx-5 bg-blue-300 shadow-xl rounded-2xl">
                            <p
                                class="mx-10 text-5xl font-extrabold transition-opacity duration-1000 animate-pulse"
                                :class="{
                                    'text-red-500': reportRemarksDisplay === 'E' || reportRemarksDisplay === 'HOLD',
                                    'text-green-500': reportRemarksDisplay !== 'E' && reportRemarksDisplay !== 'HOLD'
                                }"
                                >
                                {{ reportRemarksDisplay }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE -->

                <div class="flex flex-row items-start justify-center p-10 mx-10 mt-10 mb-10 bg-blue-100 border-2 border-white rounded-lg shadow-xl gap-x-10">

                <!-- SMP Judgement -->
                <div class="flex flex-col mr-16">
                <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white">
                    SMP Judgement
                </p>
                <p class="p-2 text-center border-b-4 border-l-4 border-r-4 border-white">
                    <span
                    class="inline-block w-40 h-40 bg-center bg-no-repeat"
                    :style="{
                        backgroundImage: reportRemarksDisplay === 'E'
                        ? 'url(\'/photo/reject_stamp.png\')'
                        : reportRemarksDisplay === 'HOLD'
                        ? 'url(\'/photo/hold_stamp.png\')'
                        : 'url(\'/photo/pass_stamp.png\')',
                        backgroundSize: '101%'
                    }">
                    </span>
                </p>
                </div>

                <!-- Prepared By -->
                <div class="flex flex-col">
                <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white">Prepared By:</p>
                <div class="p-1 text-center border-b-4 border-l-4 border-r-4 border-white">
                    <div v-show="showPreparedByDefault" class="px-2 py-[67px]">
                    <span class="font-extrabold text-blue-700 opacity-100 animate-pulse">
                        Waiting for stamp...
                    </span>
                    </div>
                    <button
                    @click="preparedByStamp"
                    v-show="preparedByButton"
                    class="px-6 py-[70px] m-0 font-semibold text-blue-400 bg-white/30 hover:bg-white/80 rounded-lg shadow-md hover:shadow-blue-400 hover:shadow-lg hover:text-blue-700 transition duration-300 ease-in-out backdrop-blur-md border border-white/40 hover:border-white/70 relative overflow-hidden group"
                    >
                    <span class="inline-block transition-all duration-300 ease-in-out transform group-hover:scale-110 group-hover:opacity-90">
                        Click&nbsp;to&nbsp;Stamp
                    </span>
                    </button>

                    <!-- Confirmation Buttons -->
                    <div class="flex flex-col px-[11px] py-5 justify-center gap-4" v-show="preparedByStampConfirmation">
                    <button
                        @click="confirmPreparedByStamp"
                        class="px-3 py-4 font-medium text-white transition duration-300 ease-in-out border rounded-md shadow-md bg-green-500/40 hover:bg-green-500/90 border-white/30 hover:border-white/60 hover:shadow-green-400 hover:shadow-lg backdrop-blur-md"
                    >
                        Confirm Stamp
                    </button>
                    <button
                        @click="cancelPreparedByStamp"
                        class="px-3 py-4 font-medium text-white transition duration-300 ease-in-out border rounded-md shadow-md bg-red-500/40 hover:bg-red-500/90 border-white/30 hover:border-white/60 hover:shadow-red-400 hover:shadow-lg backdrop-blur-md"
                    >
                        Cancel
                    </button>
                    </div>

                    <span
                    v-show="preparedByStampPhoto"
                    class="flex items-center justify-center w-40 h-40 text-2xl font-extrabold text-center text-red-600 bg-center bg-no-repeat"
                    :style="{
                        backgroundImage: 'url(\'/photo/Prepared_by_stamp.png\')',
                        backgroundSize: '101%'
                    }">
                    {{ reportPreparedByDate }}
                    </span>
                </div>
                </div>

                <!-- Checked By -->
                <div class="flex flex-col">
                <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white">Checked By:</p>
                <div class="p-1 text-center border-b-4 border-l-4 border-r-4 border-white">
                    <div v-show="showCheckedByDefault" class="px-2 py-[67px]">
                    <span class="font-extrabold text-blue-700 opacity-100 animate-pulse">
                        Waiting for stamp...
                    </span>
                    </div>
                    <button
                    @click="checkedByStamp"
                    v-show="checkedByButton"
                    class="px-6 py-[70px] m-0 font-semibold text-blue-400 bg-white/30 hover:bg-white/80 rounded-lg shadow-md hover:shadow-blue-400 hover:shadow-lg hover:text-blue-700 transition duration-300 ease-in-out backdrop-blur-md border border-white/40 hover:border-white/70 relative overflow-hidden group"
                    >
                    <span class="inline-block transition-all duration-300 ease-in-out transform group-hover:scale-110 group-hover:opacity-90">
                        Click&nbsp;to&nbsp;Stamp
                    </span>
                    </button>

                    <!-- Confirmation Buttons -->
                    <div class="flex flex-col px-[11px] py-5 justify-center gap-4" v-show="checkedByStampConfirmation">
                    <button
                        @click="confirmCheckedByStamp"
                        class="px-3 py-4 font-medium text-white transition duration-300 ease-in-out border rounded-md shadow-md bg-green-500/40 hover:bg-green-500/90 border-white/30 hover:border-white/60 hover:shadow-green-400 hover:shadow-lg backdrop-blur-md"
                    >
                        Confirm Stamp
                    </button>
                    <button
                        @click="cancelCheckedByStamp"
                        class="px-3 py-4 font-medium text-white transition duration-300 ease-in-out border rounded-md shadow-md bg-red-500/40 hover:bg-red-500/90 border-white/30 hover:border-white/60 hover:shadow-red-400 hover:shadow-lg backdrop-blur-md"
                    >
                        Cancel
                    </button>
                    </div>

                    <span
                    v-show="checkedByStampPhoto"
                    class="flex items-center justify-center w-40 h-40 text-2xl font-extrabold text-center text-red-600 bg-center bg-no-repeat"
                    :style="{
                        backgroundImage: 'url(\'/photo/Checked_by_stamp.png\')',
                        backgroundSize: '101%'
                    }">
                    {{ reportCheckedByDate }}
                    </span>
                </div>
                </div>

                <!-- Approved By -->
                <div class="flex flex-col">
                <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white">Approved By:</p>
                <div class="p-2 text-center border-b-4 border-l-4 border-r-4 border-white">
                    <div v-show="showApprovedByDefault" class="px-2 py-[64px]">
                    <span class="font-extrabold text-blue-700 opacity-100 animate-pulse">
                        Waiting for stamp...
                    </span>
                    </div>
                    <span
                    v-show="approvedByStampPhoto"
                    class="flex items-center justify-center w-40 h-40 text-2xl font-extrabold text-center text-red-600 bg-center bg-no-repeat"
                    :style="{
                        backgroundImage: 'url(\'/photo/Approved_by_stamp.png\')',
                        backgroundSize: '101%'
                    }">
                    {{ reportApprovedByDate }}
                    </span>
                </div>
                </div>

                </div>

                <!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here -->

                <div v-show="showNotif3" class="flex flex-row items-center justify-center max-w-xs px-4 py-2 mx-auto mt-10 text-white bg-green-700 rounded-md shadow-lg">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
                <div class="flex flex-row items-center justify-center">
                    <button v-show="showReportSaveButton" @click="saveReport" class="px-6 py-4 mt-4 font-extrabold text-white transition duration-300 ease-in-out transform bg-green-500 shadow-xl rounded-xl hover:bg-green-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-600 active:scale-95">
                        {{ reportExistingSMPJudgement !== null ? 'OVERWRITE' : 'SAVE' }}
                    </button>
                    <button @click="viewPropertyData(currentSerialSelected)" class="px-6 py-4 mt-4 ml-5 font-extrabold text-blue-700 transition duration-300 ease-in-out transform border border-blue-700 shadow-xl hover:text-white rounded-xl hover:bg-blue-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-600 active:scale-95">
                        View Property Data
                    </button>
                    <!-- Apply Data 1x1x1 Button (New Styled Button) -->
                    <button
                        @click="applyData1x1x1"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-yellow-700 transition duration-300 ease-in-out transform border border-yellow-700 shadow-xl rounded-xl hover:text-white hover:bg-yellow-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-600 active:scale-95"
                        >
                        Apply Data 1x1x1
                    </button>
                    <button v-show="showExitButton" @click="exitReport" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK
                    </button>
                </div>
                <div v-show="showNotif" class="flex flex-row items-center justify-center max-w-xs px-4 py-2 mx-auto mt-10 text-white bg-green-700 rounded-md shadow-lg">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
            </div>

        </div>
      </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, toRaw  } from 'vue';
import { Inertia } from '@inertiajs/inertia';

//UI Control start

const isOn = ref(false);

function toggleSwitch() {
    isOn.value = !isOn.value;
}

const showNotif = ref(false);
const showNotif2 = ref(false);
const showNotif3 = ref(false);
const showReportContent = ref(false);
const showSelectionPanel = ref(true);
const showReportSaveButton = ref(true);
const showExitButton = ref(true);

const showApprovedByDefault = ref(true);
const showCheckedByDefault = ref(true);
const showPreparedByDefault = ref(true);

const preparedByStampConfirmation = ref(false);
const checkedByStampConfirmation = ref(false);

const preparedByButton = ref(false);
const checkedByButton = ref(false);
const approvedByButton = ref(false);
const preparedByStampPhoto = ref(false);
const checkedByStampPhoto = ref(false);
const approvedByStampPhoto = ref(false);

const exitReport = () => {
    showReportContent.value = false;
    showSelectionPanel.value = true;
    fetchSerial();
}


const isTTM_model = ref(false);
const show1x1x1Data = ref(true);

//UI Control end

//general variables start

const currentFurnaceName = ref('');
const currentLayerName = ref('');
const tpmData = ref([]);
const getTpmModel = ref('');
const inspectionDataList = ref([]);
const serialList = ref([]); // Stores all fetched furnaces
const currentSerialSelected = ref('');
const reportRemarksDisplay = ref('');
const ipAddress = ref('');
const preparedByPerson = ref('');
const checkedByPerson = ref('');
const approvedByPerson = ref('');

const reportNotificationMessage = ref('');

//general variables end


/// to be put in the form
const reportModel = ref('NA');
const reportPulseTracerMachineNo = ref('NA');
const reportMaterialCode = ref('NA');
const reportDate = ref('');
const reportPartialNo = ref('NA');
const reportShift = ref('NA');
const reportTotalQuantity = ref('0');
const reportOperator = ref('NA');
const reportRemarks = ref('NA');

const reportLength = ref('NA');
const reportWidth = ref('NA');
const reportThickness = ref('NA');
const reportMaterialGrade = ref('NA');
const reportMPISampleQty = ref('NA');

const reportBrStandard = ref('NA');
const reportBrAverage = ref('NA');
const reportBrMaximum = ref('NA');
const reportBrMinimum = ref('NA');
const reportihcStandard = ref('NA');
const reportihcAverage = ref('NA');
const reportihcMaximum = ref('NA');
const reportihcMinimum = ref('NA');
const reportihkStandard = ref('NA');
const reportihkAverage = ref('NA');
const reportihkMaximum = ref('NA');
const reportihkMinimum = ref('NA');

const reportBrVariance = ref('NA');
const reportiHcVariance = ref('NA');
const reportiHkVariance = ref('NA');

const reportSMPJudgement = ref('');
const reportExistingSMPJudgement = ref(null);
const reportPreparedByDate = ref(null);
const reportCheckedByDate = ref(null);
const reportApprovedByDate = ref(null);

/// to be put in the form end

const inspectionLength = ref('');
const inspectionWidth = ref('');
const inspectionThickness = ref('');
const inspectionMaterialGrade = ref('');
const inspectionMpiSampleQty = ref('');
const inspectionModel = ref('');
const inspectionBrStandard = ref('');
const inspectioniHcStandard = ref('');
const inspectioniHkStandard = ref('');

const tpmData_brAve = ref('');
const tpmData_brMax = ref('');
const tpmData_brMin = ref('');
const tpmData_ihcAve = ref('');
const tpmData_ihcMax = ref('');
const tpmData_ihcMin = ref('');
const tpmData_ihkAve = ref('');
const tpmData_ihkMax = ref('');
const tpmData_ihkMin = ref('');
const tpmData_tracerNo = ref('');

const showNotification = (message) => {
    // Show notification and set the message
    showNotif.value = true;
    reportNotificationMessage.value = message;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        showNotif.value = false;
    }, 3000);  // 3000ms = 3 seconds
}

const showNotification2 = (message) => {
    // Show notification and set the message
    showNotif2.value = true;
    reportNotificationMessage.value = message;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        showNotif2.value = false;
    }, 3000);  // 3000ms = 3 seconds
}

const generateReport = async () => {
    showReportContent.value = true;
    showSelectionPanel.value = false;
    await fetchAllData();
    await showReportData();
}

const fetchAllData = async () => {
    try {
        const responseTpm = await axios.get("/api/tpmdata?serial=" + currentSerialSelected.value);
        //console.log("Show All tpm data API response: ", responseTpm.data);

        // Filter the data to include only rows with serial_no like '0002'
        tpmData.value = responseTpm.data[0] || [];
        //console.log("Filtered tpm aggregate data list: ", tpmData.value);
        getTpmModel.value = responseTpm.data.data || [];
        //console.log("GetModelValue: ", getTpmModel.value[0].code_no);

        //Remarks checking start
        const getAllBrNG = getTpmModel.value.map(item => item.remark.Br_remarks || null);
        const getAlliHcNG = getTpmModel.value.map(item => item.remark.iHc_remarks || null);
        const getAlliHkNG = getTpmModel.value.map(item => item.remark.iHk_remarks || null);
        const getAll4paildNG = getTpmModel.value.map(item => item.remark["4paild_remarks"] || null);
        const getAll4pailsNG = getTpmModel.value.map(item => item.remark["4pails_remarks"] || null);
        const getAll4pailaNG = getTpmModel.value.map(item => item.remark["4paila_remarks"] || null);
        const getAllbHcNG = getTpmModel.value.map(item => item.remark.bHc_remarks || null);
        const getAllBHMaxNG = getTpmModel.value.map(item => item.remark.BHMax_remarks || null);
        const getAllSquarenessNG = getTpmModel.value.map(item => item.remark.Squareness_remarks || null);
        const getAllDensityNG = getTpmModel.value.map(item => item.remark.Density_remarks || null);
        const getAlliHkiHcNG = getTpmModel.value.map(item => item.remark.iHkiHc_remarks || null);
        const getAllBr4paiNG = getTpmModel.value.map(item => item.remark.Br4pai_remarks || null);
        const getAlliHr95NG = getTpmModel.value.map(item => item.remark.iHr95_remarks || null);
        const getAlliHr98NG = getTpmModel.value.map(item => item.remark.iHr98_remarks || null);
        //console.log("check br remarks: ", getAllBrNG);
        // Check if "1" exists in getAllBrNG
        if (getAllBrNG.includes("1") || getAlliHcNG.includes("1") || getAlliHkNG.includes("1") || getAll4paildNG.includes("1") || getAll4pailsNG.includes("1") || getAll4pailaNG.includes("1") || getAllbHcNG.includes("1") || getAllBHMaxNG.includes("1") || getAllSquarenessNG.includes("1") || getAllDensityNG.includes("1") || getAlliHkiHcNG.includes("1") || getAllBr4paiNG.includes("1") || getAlliHr95NG.includes("1") || getAlliHr98NG.includes("1")) {
            // Perform your action here (leave it blank for now)
            reportRemarksDisplay.value = "E";
            reportSMPJudgement.value = "REJECT";
            if(getAlliHr95NG.includes("1") || getAlliHr98NG.includes("1")){
                reportRemarksDisplay.value = "HOLD";
                reportSMPJudgement.value = reportRemarksDisplay.value;
            }
        }else{
            reportRemarksDisplay.value = "OK";
            reportSMPJudgement.value = "PASSED";
        }
        //Remarks checking end


        const tpm_current_model = getTpmModel.value[0].code_no;
        tpmData_tracerNo.value = getTpmModel.value[0].Tracer;
        const thisLayerId = getTpmModel.value[0].layer_no;
        const thisfurnaceId = getTpmModel.value[0].furnace_id;
        console.log("Finding layer no: ", thisLayerId);
        console.log("Finding furnace id: ",thisfurnaceId);

        const responseFurnaceAPI = await axios.get(`/api/furnacedata/`); //getting furnace Name
        //console.log("Show All furnace id API response: ", responseFurnaceAPI.data);
        const furnaceNames = responseFurnaceAPI.data.data["Furnace Data"];
        //console.log("Furnaces arrays: ",furnaceNames);
        const filteredFurnaceName = furnaceNames.find(f => f.furnace_id === thisfurnaceId);
        //console.log("filtered furnace Names: ",filteredFurnaceName);
        currentFurnaceName.value = filteredFurnaceName.furnace_name;

        const responseLayerAPI = await axios.get(`/api/layerdata/`);
        console.log("Show All layer id API response: ", responseLayerAPI.data);
        const layerNames = responseLayerAPI.data.data["Layer Data"];
        console.log("Layer arrays: ",layerNames);
        const filteredLayerName = layerNames.find(f =>
            f.layer_no == thisLayerId && f.furnace_id == thisfurnaceId
        );
        console.log("filtered Layer Names: ",filteredLayerName);
        currentLayerName.value = filteredLayerName.layer_name;

        //console.log("currently selected serial: ",currentSerialSelected.value);
        //console.log("Aggregate Averages: ",tpmData.value[0].average);
        // Access the 'average' property (which is a stringified JSON)
        const averageJsonString = tpmData.value[0].average;
        const maximumJsonString = tpmData.value[0].maximum;
        const minimumJsonString = tpmData.value[0].minimum;

        // Parse the stringified JSON into an actual JavaScript object
        const averageData = JSON.parse(averageJsonString);
        const maximumData = JSON.parse(maximumJsonString);
        const minimumData = JSON.parse(minimumJsonString);

        // Now you can access the variables inside the parsed object
        //console.log("Parsed Aggregate Averages: ", averageData);
        tpmData_brAve.value = averageData.Br;
        tpmData_brMax.value = maximumData.Br;
        tpmData_brMin.value = minimumData.Br;
        tpmData_ihcAve.value = averageData.iHc;
        tpmData_ihcMax.value = maximumData.iHc;
        tpmData_ihcMin.value = minimumData.iHc;
        tpmData_ihkAve.value = averageData.iHk;
        tpmData_ihkMax.value = maximumData.iHk;
        tpmData_ihkMin.value = minimumData.iHk;

        const responseInsp = await axios.get("/api/inspectiondata");
        //console.log("Show All inspection data API response: ", responseInsp.data);
        inspectionDataList.value = responseInsp.data.data || [];
        //console.log("Show All inspection data list : ", inspectionDataList.value);

        const getAllInspModels = inspectionDataList.value.map(item => item.model);
        console.log("List of models in inspection: ", getAllInspModels);
        console.log("Current model in tpm: ",tpm_current_model);

        // Check if tpm_current_model exists in getAllInspModels
        if (getAllInspModels.includes(tpm_current_model)) {
            const filteredInspectionData = inspectionDataList.value.filter(item => item.model == tpm_current_model);
            //console.log("Filtered inspection data for the selected model: ", filteredInspectionData);
            // Access the `br` value for each item in filteredInspectionData
            filteredInspectionData.forEach(item => {
                inspectionBrStandard.value = item.br;
                inspectioniHcStandard.value = item.ihc;
                inspectioniHkStandard.value = item.ihk;
                inspectionLength.value = item.length;
                inspectionWidth.value = item.width;
                inspectionThickness.value = item.thickness;
                inspectionMaterialGrade.value = item.material_grade;
                inspectionMpiSampleQty.value = item.mpi_sample;
            });

        } else {
            showNotification2("The model does not exist in the inspection data!")
            showReportContent.value = false;
            showSelectionPanel.value = true;
            return;
        }

        //console.log("Getting br value: ", inspectionBrStandard.value);  // Assuming each item has a `br` property


        const repData = {
            "date": reportDate.value,
            "length": inspectionLength.value,
            "magnetic_property_data": JSON.stringify({
                "brStandard": inspectionBrStandard.value,
                "brAverage": tpmData_brAve.value,
                "brMaximum": tpmData_brMax.value,
                "brMinimum": tpmData_brMin.value,
                "ihcStandard": inspectioniHcStandard.value,
                "ihcAverage": tpmData_ihcAve.value,
                "ihcMaximum": tpmData_ihcMax.value,
                "ihcMinimum": tpmData_ihcMin.value,
                "ihkStandard": inspectioniHkStandard.value,
                "ihkAverage": tpmData_ihkAve.value,
                "ihkMaximum": tpmData_ihkMax.value,
                "ihkMinimum": tpmData_ihkMin.value,
            }),
            "material_grade": inspectionMaterialGrade.value,
            "model": tpm_current_model,
            "mpi_sample_quantity": inspectionMpiSampleQty.value,
            "pulse_tracer_machine_number": tpmData_tracerNo.value,
            "thickness": inspectionThickness.value,
            "width": inspectionWidth.value
        }

        //console.log("Rep Data: ",repData);
        createReport(repData, currentSerialSelected.value);

    } catch (error) {
        console.error("API get request showTpmData Error:", error);
    }
};

const createReport = async (reportData, serial) => {
    try {
        const response = await axios.patch(`/api/reportdata/${serial}`, reportData);
        console.log("Patched report data: ", response.data);
    } catch (error) {
        console.error("Patch report data Error:", error);
    }
}

const showReportData = async () => {
    try {
        const response = await axios.get(`/api/reportdata/`);
        //console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        //console.log("Filtered data: ", filterBySerial);
        reportModel.value = filterBySerial[0].model;
        reportPulseTracerMachineNo.value = filterBySerial[0].pulse_tracer_machine_number;
        reportMaterialCode.value = filterBySerial[0].material_code;
        reportDate.value = filterBySerial[0].date;
        reportPartialNo.value = filterBySerial[0].partial_number;
        reportShift.value = filterBySerial[0]["shift"];
        reportTotalQuantity.value = filterBySerial[0].total_quantity;
        reportOperator.value = filterBySerial[0].operator;
        reportLength.value = filterBySerial[0].length;
        reportWidth.value = filterBySerial[0].width;
        reportThickness.value = filterBySerial[0].thickness;
        reportMaterialGrade.value = filterBySerial[0].material_grade;
        reportMPISampleQty.value = filterBySerial[0].mpi_sample_quantity;
        reportRemarks.value = filterBySerial[0].remarks;
        reportExistingSMPJudgement.value = filterBySerial[0].smp_judgement;
        reportPreparedByDate.value = filterBySerial[0].prepared_by_date
        ? filterBySerial[0].prepared_by_date.split(' ')[0]
        : '';
        reportCheckedByDate.value = filterBySerial[0].checked_by_date
        ? filterBySerial[0].checked_by_date.split(' ')[0]
        : '';
        reportApprovedByDate.value = filterBySerial[0].approved_by_date
        ? filterBySerial[0].approved_by_date.split(' ')[0]
        : '';



        console.log("Model value: ",reportModel.value);

        //console.log("Report Data Model", reportModel.value);

        const magneticProperty = JSON.parse(filterBySerial[0].magnetic_property_data);

         // Extracting BR, IHC, and IHK properties

        // BR values
        reportBrStandard.value = magneticProperty.brStandard;
        reportBrAverage.value = magneticProperty.brAverage;
        reportBrMaximum.value = magneticProperty.brMaximum;
        reportBrMinimum.value = magneticProperty.brMinimum;

        // IHC values
        reportihcStandard.value = magneticProperty.ihcStandard;
        reportihcAverage.value = magneticProperty.ihcAverage;
        reportihcMaximum.value = magneticProperty.ihcMaximum;
        reportihcMinimum.value = magneticProperty.ihcMinimum;

        // IHK values
        reportihkStandard.value = magneticProperty.ihkStandard;
        reportihkAverage.value = magneticProperty.ihkAverage;
        reportihkMaximum.value = magneticProperty.ihkMaximum;
        reportihkMinimum.value = magneticProperty.ihkMinimum;

        // Convert strings to numbers before performing subtraction
        reportBrVariance.value = parseFloat(reportBrMaximum.value) - parseFloat(reportBrMinimum.value);
        reportiHcVariance.value = parseFloat(reportihcMaximum.value) - parseFloat(reportihcMinimum.value);
        reportiHkVariance.value = parseFloat(reportihkMaximum.value) - parseFloat(reportihkMinimum.value);

        checkApprovalStates();
    } catch (error) {
        console.error("API get request showReportData Error:", error);
    }
}

const saveReport = async () => {
    // Use default values (empty string) or check for null/undefined before calling toUpperCase
    reportMaterialCode.value = (reportMaterialCode.value || '').toUpperCase();
    reportPartialNo.value = (reportPartialNo.value || '').toUpperCase();
    reportShift.value = (reportShift.value || '').toUpperCase();
    reportOperator.value = (reportOperator.value || '').toUpperCase();

    const saveReportData = {
        "material_code": reportMaterialCode.value,
        "date": reportDate.value,
        "partial_number": reportPartialNo.value,
        "shift": reportShift.value,
        "total_quantity": reportTotalQuantity.value,
        "operator": reportOperator.value,
        "remarks": reportRemarks.value,
        "smp_judgement":reportSMPJudgement.value,
    }

    console.log("Save report data: ", saveReportData);

    saveReportUpdate(saveReportData, currentSerialSelected.value);

        showReportContent.value = false;
        showSelectionPanel.value = true;
}

const saveReportUpdate = async (saveData, serial) => {
    try{
        const responseSave = await axios.patch(`/api/reportdata/${serial}`, saveData);
        console.log("Saved Report data: ", responseSave.data);
        checkApprovalStates();
        showNotification2("Saved Successfully");
    }catch (error){
        console.error("Patch report data Error:", error);
    }
}

// Fetching the serial start

// Function to fetch serial data
const fetchSerial = async () => {
  try {
    const response = await axios.get("/api/tpmdata");
    //console.log("API Response:", response.data);

    // Extract furnace data dynamically
    tpmData.value = response.data.data["tpmData"] || [];
    //console.log("TPM DATA response: ", tpmData.value);

    // Extract unique serial numbers by using a Set
    serialList.value = [...new Set(tpmData.value.map(item => item.serial_no))];

    // Sort the serialList in descending order (highest value first)
    serialList.value = serialList.value.sort((a, b) => {
      return Number(b) - Number(a); // Convert to number for proper sorting
    });

    //console.log("Unique Serial lists (Descending):", serialList.value);

    // Set default selection to first serial, if available
    if (serialList.value.length > 0) {
        currentSerialSelected.value = serialList.value[0];
    }

  } catch (error) {
    console.error("Error fetching serial data:", error);
  }
};
// Fetching the serial start end

// Define the prop that will receive the serialParam
const props = defineProps({
  serialParam: String,  // Expecting the serialParam to be a string
  ipAddress: String,
});

ipAddress.value = props.ipAddress;
console.log('Current IP address is:', props.ipAddress); // You can use this for debugging
console.log('Serial Param in Reports.vue:', props.serialParam); // You can use this for debugging

const viewPropertyData = (serial) => {
  console.log('Navigating to manage with serial:', serial);
  Inertia.visit('/manage', {
    method: 'get',   // You can keep 'get' since we are not modifying any data
    data: { manageSerialParam: serial },   // Passing the serialParam here
    preserveState: true,
    preserveScroll: true,
  });
};

const preparedByStamp = async () => {
    try{
        const response = await axios.get(`/api/reportdata/`);
        //console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        //console.log("Filtered data: ", filterBySerial);

        const smpJudgement = filterBySerial[0].smp_judgement;
        if(smpJudgement == null || smpJudgement == ""){
            showNotification("Please save the report first before stamping!");
            return;
        }else{
            preparedByButton.value = false;
            preparedByStampConfirmation.value = true;
        }
    }catch(error){
        console.error("Error fetching report data by preparedByStamp function:", error);
    }
}

const cancelPreparedByStamp = () => {
    preparedByButton.value = true;
    preparedByStampConfirmation.value = false;
}

const confirmPreparedByStamp = async () => {
    preparedByPerson.value = "IRISH MERCADO"; //Temporary Hardcode. Replace with Assigned IP address linked to a person
    preparedByStampPhoto.value = true;
    preparedByStampConfirmation.value = false;
    const dateNow = datenow();
    console.log("Prepared By Has been stamped by -> ", preparedByPerson.value);
    const preparedByData = {
        prepared_by: preparedByPerson.value, // Set the approved_by field to "ITADANI KAZUYA"
        prepared_by_date: dateNow
    };

    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, preparedByData);
    console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);
    showReportData();
}

const datenow = () => {
    const now = new Date();
    const pad = (n) => n.toString().padStart(2, '0');

    const year = now.getFullYear();
    const month = pad(now.getMonth() + 1); // Months are zero-based
    const day = pad(now.getDate());

    const hours = pad(now.getHours());
    const minutes = pad(now.getMinutes());
    const seconds = pad(now.getSeconds());

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const checkedByStamp = () => {
    checkedByButton.value = false;
    checkedByStampConfirmation.value = true;
}

const cancelCheckedByStamp = () => {
    checkedByButton.value = true;
    checkedByStampConfirmation.value = false;
}

const confirmCheckedByStamp = async () => {
    checkedByPerson.value = "CHECKED BY PERSON"; //Temporary Hardcode. Replace with Assigned IP address linked to a person
    checkedByStampPhoto.value = true;
    checkedByStampConfirmation.value = false;
    const dateNow = datenow();
    console.log("Checked By Has been stamped by -> ", checkedByPerson.value);
    const checkedByData = {
        checked_by: checkedByPerson.value,
        checked_by_date: dateNow
    };

    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, checkedByData);
    console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);
    showReportData();
}

const checkApprovalStates = async () => {
    try{
        console.log("Checking approval states...");
        const response = await axios.get(`/api/reportdata/`);
        //console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        //console.log("Filtered data: ", filterBySerial);

        const prepared_by = filterBySerial[0].prepared_by;
        const checked_by = filterBySerial[0].checked_by;
        const approved_by = filterBySerial[0].approved_by;

        console.log("prepared by: ",prepared_by);
        console.log("checked by: ",checked_by);
        console.log("approved by: ",approved_by);

        if(prepared_by == "" || prepared_by == null){ //if not yet approved
            //preparedByButton.value = true; //uncomment on designated ip address
            showPreparedByDefault.value = true;
            preparedByStampPhoto.value = false;
        }else{
            preparedByButton.value = false;
            showPreparedByDefault.value = false;
            preparedByStampPhoto.value = true;
        }
        if(checked_by == "" || checked_by == null){
            //checkedByButton.value = true; //uncomment on designated ip address
            showCheckedByDefault.value = true;
            checkedByStampPhoto.value = false;
        }else{
            checkedByButton.value = false;
            showCheckedByDefault.value = false;
            checkedByStampPhoto.value = true;
        }
        if(approved_by == "" || approved_by == null){
            //approvedByButton.value = true; //uncomment on designated ip address
            showApprovedByDefault.value = true;
            approvedByStampPhoto.value = false;
        }else{
            approvedByButton.value = false;
            showApprovedByDefault.value = false;
            approvedByStampPhoto.value = true;
            showReportSaveButton.value = false;
        }
    }catch(error){

    }
}

// onMounted logic to call the function based on serialParam existence
onMounted(() => {

  if (props.serialParam) {
    // If serialParam has a value, do not fetch serial
    // Placeholder for additional actions when serialParam exists
    currentSerialSelected.value = props.serialParam;
    showReportContent.value = true;
    showSelectionPanel.value = false;
    showReportSaveButton.value = false;
    showExitButton.value = false;
    fetchAllData();
    showReportData();
    console.log('serialParam is provided, skipping fetchSerial.');
  } else {
    // If serialParam does not have a value, proceed with fetchSerial
    fetchSerial();
  }
});
</script>
