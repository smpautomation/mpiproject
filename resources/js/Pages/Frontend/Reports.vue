<template>
    <Frontend>
        <div v-if="onTestServer" class="flex flex-row items-center justify-center text-xl font-extrabold text-green-500">
            YOU ARE ON TEST SERVER
        </div>
      <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">
        <div v-if="!isFromApproval && !isFromViewList">
            <div v-if="serialList.length == 0"> <!-- default div -->
                <div class="flex flex-col items-center justify-center mt-10 align-baseline">
                    <div
                        class="w-32 h-32 transition duration-300 bg-center bg-no-repeat bg-cover"
                        :style="{
                            backgroundImage: 'url(\'/photo/no_data.png\')',
                            backgroundSize: '80%'
                        }"
                    ></div>
                    <p class="text-xl text-center animate-pulse"> (No data available yet) <br> Please ensure that the data is created in the Manage section of the website before proceeding.</p>
                </div>
            </div>
        </div>
        <div v-if="showNotif2 && isFromApproval" class="flex flex-row items-center justify-center px-4 py-2 my-10 text-white bg-yellow-500 shadow-lg rounded-2xl">
            <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
        </div>
        <div v-show="showSelectionPanel">
            <div v-if="serialList.length > 0">
                <div> <!-- Selection Panel -->
                    <div class="flex flex-row items-center justify-center mt-10 align-baseline">
                        <p>Select Serial No: </p>
                        <select
                        v-model="currentSerialSelected"
                        class="py-2 m-4 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm px-auto focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option v-for="serial in serialList" :key="serial" :value="serial">{{ serial }}</option>
                        </select>
                    </div>
                    <div class="flex flex-col items-center justify-center space-x-8">
                        <!-- Button -->
                        <button
                            @click="generateReport"
                            :disabled="showNotif2"
                            class="px-6 py-3 mt-10 mb-10 text-xl font-extrabold text-white bg-blue-500 rounded-lg shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        >
                            Generate Report
                        </button>
                    </div>
                    <div v-show="showNotif2" class="flex flex-row items-center justify-center px-4 py-2 my-10 text-white bg-yellow-500 shadow-lg rounded-2xl">
                        <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div v-show="showReportContent">

            <!---

            <div v-show="showReportProceedButtons">
                <div v-if="isLoading">Generating Report...</div>
                <div v-else>
                    <div v-if="isFromApproval">
                        <p class="mb-10 text-xl font-extrabold animate-pulse">Report Data is ready for viewing...</p>
                    </div>
                    <div class="flex flex-row justify-center">
                        <button @click="showReportButton" class="px-3 py-2 text-white bg-blue-500 shadow-xl rounded-xl">{{ isFromApproval ? 'Click to Show' : 'Show Report' }}</button>
                    </div>
                </div>
            </div>

            -->

            <!-- Report Content -->
            <div v-if="reportErrorMessage" class="p-4 mt-4 text-red-700 bg-red-100 border border-red-400 rounded">
                {{ reportErrorMessage }}
            </div>
            <DotsLoader v-show="showReportLoading" class="z-10 mt-8"/>
            <div v-show="showReportMain" class="flex flex-col justify-center py-10 mx-20 mt-10 mb-20 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="flex flex-row w-full max-w-4xl px-4 mx-auto mb-10">
                    <div
                        v-if="state.user && state.user.employee_id === '005009'"
                        class="flex flex-col bg-blue-200 mr-10 w-[250px] h-[135px] rounded-xl shadow-xl justify-center items-start px-5 py-4 text-white space-y-1"
                    >
                        <p class="text-sm font-medium">Good {{ timeOfDay }},</p>
                        <p class="text-xl font-bold whitespace-nowrap">{{ greetingsWindowFirstName }} {{ greetingsWindowLastName }} san</p>
                        <p v-if="state.user" class="text-xs text-blue-800 animate-pulse">[{{ state.user.access_type }}]</p>
                        <p v-if="report_isFinalized == true">FINALIZED
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="green">
                                    <circle cx="12" cy="12" r="10" fill="#4CAF50"/>
                                    <path fill="#fff" d="M10 14.5l-2.5-2.5-1.4 1.4L10 17.3l7.9-7.9-1.4-1.4z"/>
                                </svg>
                            </span>
                        </p>
                    </div>
                    <div v-else class="flex flex-col bg-blue-200 mr-10 w-[250px] h-[135px] rounded-xl shadow-xl justify-center items-start px-5 py-4 text-white space-y-1">
                        <p class="text-sm font-medium">Good {{ timeOfDay }},</p>
                        <p class="text-xl font-bold whitespace-nowrap">{{ greetingsWindowFirstName }} {{ greetingsWindowLastName }}</p>
                        <p v-if="state.user" class="text-xs text-blue-800 animate-pulse">[{{ state.user.access_type }}]</p>
                        <p v-if="report_isFinalized == true" class="flex items-center gap-2 p-2 text-sm font-extrabold text-green-400 bg-green-100 rounded-lg">
                            FINALIZED
                            <span class="inline-block w-4 h-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="green" class="w-full h-full">
                                    <circle cx="12" cy="12" r="10" fill="#4CAF50"/>
                                    <path fill="#fff" d="M10 14.5l-2.5-2.5-1.4 1.4L10 17.3l7.9-7.9-1.4-1.4z"/>
                                </svg>
                            </span>
                        </p>
                    </div>
                    <div class="flex flex-col items-center justify-between w-full gap-4 p-6 transition border shadow-lg sm:flex-row bg-white/30 border-white/50 rounded-xl backdrop-blur-md hover:shadow-xl hover:border-white/70">

                        <!-- Serial -->
                        <div class="text-center sm:text-left">
                            <p class="text-sm font-bold text-blue-800">Serial</p>
                            <p class="text-xl font-semibold text-blue-900">{{ currentSerialSelected }}</p>
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
                        <div class="flex flex-col">
                            <div v-show="showCarMarkButton && !isAutomotive" class="items-center p-1 text-center border-4 border-white">
                                <button
                                    disabled
                                    class="w-[160px] h-[80px] m-0 font-semibold text-blue-400 bg-white/30 hover:bg-white/80 rounded-lg shadow-md hover:shadow-blue-400 hover:shadow-lg hover:text-blue-700 transition duration-300 ease-in-out backdrop-blur-md border border-white/40 hover:border-white/70 relative overflow-hidden group"
                                    >
                                    <span class="inline-block text-sm transition-all duration-300 ease-in-out transform text-md group-hover:scale-110 group-hover:opacity-90">
                                        NON-AUTOMOTIVE
                                    </span>
                                </button>
                            </div>
                            <div>
                                <span
                                    v-show="isAutomotive"
                                    class="flex items-center justify-center w-[150px] h-[82px] text-center  bg-center bg-no-repeat"
                                    :style="{
                                        backgroundImage: 'url(\'/photo/carmark_logo.png\')',
                                        backgroundSize: '100%'
                                    }">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 mx-10 mb-10 border-2 border-white rounded-lg shadow-xl">
                    <div class="flex flex-row gap-4 mt-4 justify-evenly">
                        <!-- Model (display-only) -->
                        <div class="flex flex-col w-[12rem]">
                            <label class="mb-1 text-sm font-medium text-gray-700">
                                Model:
                            </label>
                            <span
                                class="flex items-center px-3 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md cursor-default h-9 hover:ring-1 hover:ring-blue-400 hover:shadow-sm"
                            >
                                {{ jhCurveActualModel }}
                            </span>
                        </div>

                        <!-- Material Code -->
                        <div class="flex flex-col w-[12rem]">
                            <label for="materialCode" class="mb-1 text-sm font-medium text-gray-700">
                                Material Code:
                            </label>
                            <input
                                v-model="reportMaterialCode"
                                @input="reportMaterialCode = reportMaterialCode.toUpperCase()"
                                type="text"
                                name="materialCode"
                                id="materialCode"
                                class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            />
                        </div>

                        <!-- Partial No. -->
                        <div class="flex flex-col w-[12rem]">
                            <label for="partialNo" class="mb-1 text-sm font-medium text-gray-700">
                                Partial No.:
                            </label>
                            <input
                                v-model="reportPartialNo"
                                @input="reportPartialNo = reportPartialNo.toUpperCase()"
                                type="text"
                                name="partialNo"
                                id="partialNo"
                                class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            />
                        </div>

                        <!-- Total Quantity -->
                        <div class="flex flex-col w-[12rem]">
                            <label for="totalQuantity" class="mb-1 text-sm font-medium text-gray-700">
                                Total Quantity:
                            </label>
                            <input
                                v-model="reportTotalQuantity"
                                type="number"
                                name="totalQuantity"
                                id="totalQuantity"
                                class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            />
                        </div>
                    </div>
                </div>

                <div class="p-5 mx-10 mb-10 border-2 border-white rounded-lg shadow-xl">
                    <!-- Coating Information Section -->
                    <div class="flex flex-col mb-4 space-y-4">
                        <p class="mb-2 text-xl font-semibold text-gray-700">Coating Information</p>

                        <!-- Coating Row 1 -->
                        <div class="flex flex-row gap-4 mt-4 justify-evenly">
                            <!-- Coating Date -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingDate" class="mb-1 text-sm font-medium text-gray-700">
                                    Coating Date:
                                </label>
                                <input
                                    v-model="reportCoatingDate"
                                    type="date"
                                    name="coatingDate"
                                    id="coatingDate"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Coating Machine No -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingMachineNo" class="mb-1 text-sm font-medium text-gray-700">Coating Machine No:</label>
                                <input
                                    v-model="reportCoatingMachineNo"
                                    type="text"
                                    name="coatingMachineNo"
                                    id="coatingMachineNo"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Slurry Lot No -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingSlurryLotNo" class="mb-1 text-sm font-medium text-gray-700">Slurry Lot No:</label>
                                <input
                                    v-model="reportCoatingSlurryLotNo"
                                    type="text"
                                    name="coatingSlurryLotNo"
                                    id="coatingSlurryLotNo"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Loader Operator -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingLoaderOperator" class="mb-1 text-sm font-medium text-gray-700">Loader Operator:</label>
                                <input
                                    v-model="reportCoatingLoaderOperator"
                                    @input="reportCoatingLoaderOperator = reportCoatingLoaderOperator.toUpperCase()"
                                    type="text"
                                    name="coatingLoaderOperator"
                                    id="coatingLoaderOperator"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Time Start -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingTimeStart" class="mb-1 text-sm font-medium text-gray-700">Time Start:</label>
                                <input
                                    v-model="reportCoatingTimeStart"
                                    type="time"
                                    name="coatingTimeStart"
                                    id="coatingTimeStart"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Time Finish -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingTimeFinish" class="mb-1 text-sm font-medium text-gray-700">Time Finish:</label>
                                <input
                                    v-model="reportCoatingTimeFinish"
                                    type="time"
                                    name="coatingTimeFinish"
                                    id="coatingTimeFinish"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                        </div>

                        <!-- Coating Row 2 -->
                        <div class="flex flex-row gap-4 mt-4 justify-evenly">
                            <!-- MIN. TB CONTENT -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingMinTbContent" class="mb-1 text-sm font-medium text-gray-700">MIN. TB CONTENT (µg/mm²):</label>
                                <input
                                    v-model="reportCoatingMinTbContent"
                                    type="text"
                                    name="coatingMinTbContent"
                                    id="coatingMinTbContent"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Sample Quantity -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingSampleQuantity" class="mb-1 text-sm font-medium text-gray-700">Sample Quantity (pcs):</label>
                                <input
                                    v-model="reportCoatingSampleQuantity"
                                    type="text"
                                    name="coatingSampleQuantity"
                                    id="coatingSampleQuantity"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Total Magnet Weight -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingTotalMagnetWeight" class="mb-1 text-sm font-medium text-gray-700">Total Magnet Weight:</label>
                                <input
                                    v-model="reportCoatingTotalMagnetWeight"
                                    type="text"
                                    name="coatingTotalMagnetWeight"
                                    id="coatingTotalMagnetWeight"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Unloader Operator -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingUnloaderOperator" class="mb-1 text-sm font-medium text-gray-700">Unloader Operator:</label>
                                <input
                                    v-model="reportCoatingUnloaderOperator"
                                    @input="reportCoatingUnloaderOperator = reportCoatingUnloaderOperator.toUpperCase()"
                                    type="text"
                                    name="coatingUnloaderOperator"
                                    id="coatingUnloaderOperator"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Checker Operator -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="coatingCheckerOperator" class="mb-1 text-sm font-medium text-gray-700">Checker Operator:</label>
                                <input
                                    v-model="reportCoatingCheckerOperator"
                                    @input="reportCoatingCheckerOperator = reportCoatingCheckerOperator.toUpperCase()"
                                    type="text"
                                    name="coatingCheckerOperator"
                                    id="coatingCheckerOperator"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (coatingAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>
                        </div>
                    </div>
                    <p class="mb-2 text-md font-semibold text-gray-700">Coating Data (UNIT: µ g/mm2)</p>

                    <div class="flex flex-row flex-wrap gap-2 text-[0.7rem]">
                        <template v-for="group in 6" :key="'group-' + group">
                            <div class="w-[200px] p-1 border rounded bg-white shadow-sm">
                                <div class="mb-1 font-semibold text-gray-700 text-center text-[0.7rem]">
                                    {{ (group - 1) * 5 + 1 }} – {{ group * 5 }}<br />(M-01 ~ M-05)
                                </div>

                                <template v-for="j in 5" :key="'input-' + (group - 1) * 5 + j">
                                    <div class="mb-1">
                                        <label class="block text-[0.65rem] text-gray-600 mb-[1px]">
                                            {{ (group - 1) * 5 + j }} (M-{{ String(j).padStart(2, '0') }})
                                        </label>
                                        <input
                                            v-model="reportCoatingAmounts[(group - 1) * 5 + (j - 1)]"
                                            type="number"
                                            placeholder="Amt"
                                            :disabled="!(coatingAccess || automationAcess)"
                                            :class="[
                                                'w-full h-6 px-1 text-[0.65rem] border rounded mb-[2px]',
                                                (coatingAccess || automationAcess)
                                                    ? 'text-gray-800 bg-white border-gray-300 focus:ring-blue-400 focus:ring-1'
                                                    : 'text-gray-400 bg-gray-100 border-gray-300 cursor-not-allowed'
                                            ]"
                                        />
                                        <input
                                            v-model="reportConcentrationValues[(group - 1) * 5 + (j - 1)]"
                                            type="number"
                                            placeholder="Conc."
                                            :disabled="!(coatingAccess || automationAcess)"
                                            :class="[
                                                'w-full h-6 px-1 text-[0.65rem] border rounded',
                                                (coatingAccess || automationAcess)
                                                    ? 'text-gray-800 bg-white border-gray-300 focus:ring-blue-400 focus:ring-1'
                                                    : 'text-gray-400 bg-gray-100 border-gray-300 cursor-not-allowed'
                                            ]"
                                        />
                                    </div>
                                </template>

                                <div class="pt-1 mt-1 border-t">
                                    <div class="font-semibold text-yellow-700 text-[0.65rem] mb-1 text-center">
                                        M-06<br />(for {{ (group - 1) * 5 + 1 }}–{{ group * 5 }})
                                    </div>

                                    <template v-for="k in 2" :key="'m06-' + group + '-' + k">
                                        <input
                                            v-model="reportConcentrationValues[35 + (group - 1) * 2 + (k - 1)]"
                                            type="number"
                                            placeholder="M-06"
                                            :disabled="!(coatingAccess || automationAcess)"
                                            :class="[
                                                'w-full h-6 px-1 text-[0.65rem] border rounded mb-1',
                                                (coatingAccess || automationAcess)
                                                    ? 'border-yellow-400 bg-yellow-50 focus:ring-yellow-500 focus:ring-1 text-gray-800'
                                                    : 'border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed'
                                            ]"
                                        />
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>


                    <div class="flex flex-row">

                        <table class="mt-4 border-4 border-collapse border-white table-auto">
                            <thead class="text-center text-white bg-blue-400">
                                <tr>
                                    <th rowspan="2" class="px-2 border-2 border-white">MODULE</th>
                                    <th colspan="3" class="px-2 border-2 border-white">ADDITIONAL SLURRY</th>
                                    <th rowspan="2" class="px-2 border-2 border-white">LITERS</th>
                                </tr>
                                <tr>
                                    <th class="px-2 border-2 border-white">NEW</th>
                                    <th class="px-2 border-2 border-white">HOMO</th>
                                    <th class="px-2 border-2 border-white">TIME</th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-blue-600">
                                <tr v-for="(module, index) in modules" :key="index">
                                    <td class="px-2 border-2 border-white">{{ module.name }}</td>

                                    <td class="px-2 border-2 border-white">
                                        <input
                                            v-model="module.new"
                                            type="text"
                                            :disabled="!(coatingAccess || automationAcess)"
                                            :class="[
                                                'w-[4rem] h-[2rem] text-sm border rounded-md transition duration-200 ease-in-out',
                                                (coatingAccess || automationAcess)
                                                    ? 'bg-white text-gray-800 border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                    : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                            ]"
                                        />
                                    </td>

                                    <td class="px-2 border-2 border-white">
                                        <input
                                            v-model="module.homo"
                                            type="text"
                                            :disabled="!(coatingAccess || automationAcess)"
                                            :class="[
                                                'w-[4rem] h-[2rem] text-sm border rounded-md transition duration-200 ease-in-out',
                                                (coatingAccess || automationAcess)
                                                    ? 'bg-white text-gray-800 border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                    : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                            ]"
                                        />
                                    </td>

                                    <td class="px-2 border-2 border-white">
                                        <input
                                            v-model="module.time"
                                            type="text"
                                            :disabled="!(coatingAccess || automationAcess)"
                                            :class="[
                                                'w-[4rem] h-[2rem] text-sm border rounded-md transition duration-200 ease-in-out',
                                                (coatingAccess || automationAcess)
                                                    ? 'bg-white text-gray-800 border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                    : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                            ]"
                                        />
                                    </td>

                                    <td class="px-2 border-2 border-white">
                                        <input
                                            v-model="module.liters"
                                            type="text"
                                            :disabled="!(coatingAccess || automationAcess)"
                                            :class="[
                                                'w-[4rem] h-[2rem] text-sm border rounded-md transition duration-200 ease-in-out',
                                                (coatingAccess || automationAcess)
                                                    ? 'bg-white text-gray-800 border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                    : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                            ]"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <div class="flex flex-col mt-4 ml-20">

                            <div class="flex flex-row">
                                <div class="mt-10 mr-10">
                                    <table class="border-4 border-white table-auto">
                                        <tbody>
                                            <tr>
                                                <th class="px-2 text-white bg-blue-400 border-2 border-white">MAXIMUM:</th>
                                                <td class="font-semibold text-blue-600"> {{ reportCoatingModuleMaximum }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-10 mr-10">
                                    <table class="border-4 border-white table-auto">
                                        <tbody>
                                            <tr>
                                                <th class="px-2 text-white bg-blue-400 border-2 border-white">MINIMUM:</th>
                                                <td class="font-semibold text-blue-600"> {{ reportCoatingModuleMinimum }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-10 mr-10">
                                    <table class="border-4 border-white table-auto">
                                        <tbody>
                                            <tr>
                                                <th class="px-2 text-white bg-blue-400 border-2 border-white">AVERAGE:</th>
                                                <td class="font-semibold text-blue-600"> {{ reportCoatingModuleAverage }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex flex-row mt-10">
                            <!-- Coating Remarks -->
                            <div class="flex flex-col w-[47rem]">
                                <label for="reportCoatingRemarks" class="mb-1 text-sm font-medium text-gray-700">
                                    Remarks:
                                </label>
                                <input
                                    v-model="reportCoatingRemarks"
                                    @input="reportCoatingRemarks = reportCoatingRemarks.toUpperCase()"
                                    type="text"
                                    name="coatingRemarks"
                                    id="coatingRemarks"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm rounded-md h-9 transition duration-200 ease-in-out border',
                                        (coatingAccess || automationAcess)
                                            ? 'bg-white text-gray-800 border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>
                            </div>
                            <div
                                :class="[
                                    'flex items-center mt-4 space-x-2 rounded-md px-3 py-2 shadow-sm border',
                                    coatingCompleted
                                        ? 'bg-green-50 border-green-300'
                                        : 'bg-gray-100 border-gray-300'
                                ]"
                            >
                                <input
                                    id="coatingCheckbox"
                                    type="checkbox"
                                    v-model="coatingCompleted"
                                    :disabled="!(coatingAccess || automationAcess)"
                                    class="w-5 h-5 transition duration-200 ease-in-out border rounded"
                                    :class="[
                                        coatingCompleted
                                            ? 'text-green-600 focus:ring-green-500'
                                            : 'text-gray-500 focus:ring-gray-400',
                                        !(coatingAccess || automationAcess)
                                            ? 'bg-gray-100 cursor-not-allowed'
                                            : ''
                                    ]"
                                />

                                <label for="coatingCheckbox" class="flex items-center text-sm font-semibold"
                                    :class="coatingCompleted ? 'text-green-700' : 'text-gray-600'">
                                    <svg v-if="coatingCompleted" class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>
                                        {{ coatingCompleted ? 'Coating inspection completed' : '✓ Tick the box to finalize Coating' }}
                                    </span>
                                </label>
                            </div>
                        </div>



                    </div>


                </div>

                <div class="p-5 mx-10 mb-10 border-2 border-white rounded-lg shadow-xl">
                    <!-- Heat Treatment Information Section -->
                    <div class="flex flex-col mb-4 space-y-4">
                        <p class="mb-2 text-xl font-semibold text-gray-700">Heat Treatment Information</p>

                        <!-- Row 1 -->
                        <div class="flex flex-row gap-4 justify-evenly">

                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenMachineNo" class="mb-1 text-sm font-medium text-gray-700">
                                    Furnace Machine:
                                </label>
                                <input
                                    v-model="reportHTFurnaceMachine"
                                    @input="reportHTFurnaceMachine = reportHTFurnaceMachine.toUpperCase()"
                                    type="text"
                                    name="ovenMachineNo"
                                    id="ovenMachineNo"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- CYCLE No -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="timeLoading" class="mb-1 text-sm font-medium text-gray-700">
                                    CYCLE No:
                                </label>
                                <input
                                    v-model="reportHTCycleNo"
                                    @input="reportHTCycleNo = reportHTCycleNo.toUpperCase()"
                                    type="text"
                                    name="timeLoading"
                                    id="timeLoading"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- BATCH CYCLE No -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="temperature_timeLoading" class="mb-1 text-sm font-medium text-gray-700">
                                    BATCH CYCLE No:
                                </label>
                                <input
                                    v-model="reportHTBatchCycleNo"
                                    @input="reportHTBatchCycleNo = reportHTBatchCycleNo.toUpperCase()"
                                    type="text"
                                    name="temperature_timeLoading"
                                    id="temperature_timeLoading"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- LOADER -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_date" class="mb-1 text-sm font-medium text-gray-700">
                                    LOADER:
                                </label>
                                <input
                                    v-model="reportHTLoader"
                                    @input="reportHTLoader = reportHTLoader.toUpperCase()"
                                    type="text"
                                    name="ovenInfo_date"
                                    id="ovenInfo_date"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- DATE START -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_shift" class="mb-1 text-sm font-medium text-gray-700">
                                    DATE START:
                                </label>
                                <input
                                    v-model="reportHTDateStart"
                                    type="date"
                                    name="ovenInfo_shift"
                                    id="ovenInfo_shift"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- TIME START -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_shift" class="mb-1 text-sm font-medium text-gray-700">
                                    TIME START:
                                </label>
                                <input
                                    v-model="reportHTTimeStart"
                                    type="time"
                                    name="ovenInfo_shift"
                                    id="ovenInfo_shift"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                        </div>

                        <!-- Row 2 -->
                        <div class="flex flex-row gap-4 mt-4 justify-evenly">

                            <!-- PATTERN No -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_shift" class="mb-1 text-sm font-medium text-gray-700">
                                    PATTERN No:
                                </label>
                                <input
                                    v-model="reportHTPatternNo"
                                    @input="reportHTPatternNo = reportHTPatternNo.toUpperCase()"
                                    type="text"
                                    name="ovenInfo_shift"
                                    id="ovenInfo_shift"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Cycle Pattern -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_operator" class="mb-1 text-sm font-medium text-gray-700">
                                    Cycle Pattern:
                                </label>
                                <input
                                    v-model="reportHTCyclePattern"
                                    @input="reportHTCyclePattern = reportHTCyclePattern.toUpperCase()"
                                    type="text"
                                    name="ovenInfo_operator"
                                    id="ovenInfo_operator"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- Current Pattern -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_operator" class="mb-1 text-sm font-medium text-gray-700">
                                    Current Pattern:
                                </label>
                                <input
                                    v-model="reportHTCurrentPattern"
                                    @input="reportHTCurrentPattern = reportHTCurrentPattern.toUpperCase()"
                                    type="text"
                                    name="ovenInfo_operator"
                                    id="ovenInfo_operator"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- UNLOADER -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_operator" class="mb-1 text-sm font-medium text-gray-700">
                                    UNLOADER:
                                </label>
                                <input
                                    v-model="reportHTUnloader"
                                    @input="reportHTUnloader = reportHTUnloader.toUpperCase()"
                                    type="text"
                                    name="ovenInfo_operator"
                                    id="ovenInfo_operator"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- DATE FINISH -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="timeUnloading" class="mb-1 text-sm font-medium text-gray-700">
                                    DATE FINISH:
                                </label>
                                <input
                                    v-model="reportHTDateFinish"
                                    type="date"
                                    name="timeUnloading"
                                    id="timeUnloading"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                            <!-- TIME FINISH -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="temperature_TimeUnloading" class="text-[13px] font-medium text-gray-700 mb-1">
                                    TIME FINISH:
                                </label>
                                <input
                                    v-model="reportHTTimeFinish"
                                    type="time"
                                    name="temperature_TimeUnloading"
                                    id="temperature_TimeUnloading"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>

                        </div>

                            <table class="mt-[1.1rem] border-collapse table-auto border-4 border-white">
                                <thead>
                                    <tr class="bg-blue-400">
                                        <th colspan="11" class="px-2 py-2 text-lg font-semibold text-center text-white">MAGNET BOX LOCATION</th>
                                    </tr>
                                    <tr class="bg-blue-400">
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">BOX No.</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">A</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">B</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">C</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">D</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">E</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">F</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">G</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">H</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">J</th>
                                        <th class="px-1 py-1 text-md font-semibold text-center text-white border-4 border-white">K</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">{{ currentLayerName }}</td>
                                        <!-- MBL A to K -->
                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLA"
                                                @input="reportHT_MBLA = reportHT_MBLA.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLA"
                                                id="reportHT_MBLA"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLB"
                                                @input="reportHT_MBLB = reportHT_MBLB.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLB"
                                                id="reportHT_MBLB"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLC"
                                                @input="reportHT_MBLC = reportHT_MBLC.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLC"
                                                id="reportHT_MBLC"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLD"
                                                @input="reportHT_MBLD = reportHT_MBLD.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLD"
                                                id="reportHT_MBLD"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLE"
                                                @input="reportHT_MBLE = reportHT_MBLE.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLE"
                                                id="reportHT_MBLE"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLF"
                                                @input="reportHT_MBLF = reportHT_MBLF.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLF"
                                                id="reportHT_MBLF"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLG"
                                                @input="reportHT_MBLG = reportHT_MBLG.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLG"
                                                id="reportHT_MBLG"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLH"
                                                @input="reportHT_MBLH = reportHT_MBLH.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLH"
                                                id="reportHT_MBLH"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLJ"
                                                @input="reportHT_MBLJ = reportHT_MBLJ.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLJ"
                                                id="reportHT_MBLJ"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>

                                        <td class="px-1 py-1 text-md font-semibold text-center text-blue-600 border-2 border-white">
                                            <input
                                                v-model="reportHT_MBLK"
                                                @input="reportHT_MBLK = reportHT_MBLK.toUpperCase()"
                                                type="text"
                                                name="reportHT_MBLK"
                                                id="reportHT_MBLK"
                                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                                :class="[
                                                    'w-[100px] text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                                    (heatTreatmentAccess || automationAcess)
                                                        ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                ]"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="flex flex-row mt-10">
                            <!-- Heat Treatment Remarks -->
                            <div class="flex flex-col w-[62rem]">
                                <label for="reportHeatTreatmentRemarks" class="mb-1 text-sm font-medium text-gray-700">
                                    Remarks:
                                </label>
                                <input
                                    v-model="reportHTRemarks"
                                    @input="reportHTRemarks = reportHTRemarks.toUpperCase()"
                                    type="text"
                                    name="reportHeatTreatmentRemarks"
                                    id="reportHeatTreatmentRemarks"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    :class="[
                                        'px-3 text-sm transition duration-200 ease-in-out border rounded-md h-9',
                                        (heatTreatmentAccess || automationAcess)
                                            ? 'text-gray-800 bg-white border-gray-300 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent'
                                            : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                    ]"
                                />
                            </div>
                            <button
                                @click="showModal = true"
                                :disabled="!(heatTreatmentAccess || automationAcess)"
                                :class="[
                                    'px-4 py-1 ml-2 rounded-lg transition-all duration-200',
                                    heatTreatmentAccess || automationAcess
                                        ? 'bg-blue-600 text-white hover:bg-blue-700'
                                        : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                ]"
                            >
                                Fill Mass Production Control Sheet
                            </button>
                            </div>

                            <div
                                :class="[
                                    'flex items-center mt-4 mx-10 space-x-2 rounded-md px-3 py-2 shadow-sm border',
                                    heatTreatmentCompleted
                                        ? 'bg-green-50 border-green-300'
                                        : 'bg-gray-100 border-gray-300'
                                ]"
                            >
                                <input
                                    id="heatTreatmentCheckbox"
                                    type="checkbox"
                                    v-model="heatTreatmentCompleted"
                                    :disabled="!(heatTreatmentAccess || automationAcess)"
                                    class="w-5 h-5 rounded border-gray-300 transition duration-150 ease-in-out"
                                    :class="[
                                        heatTreatmentCompleted
                                            ? 'text-green-600 focus:ring-green-500'
                                            : 'text-gray-500 focus:ring-gray-400',
                                        !(heatTreatmentAccess || automationAcess)
                                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                            : ''
                                    ]"
                                />

                                <label
                                    for="heatTreatmentCheckbox"
                                    class="flex items-center text-sm font-semibold"
                                    :class="heatTreatmentCompleted ? 'text-green-700' : 'text-gray-600'"
                                >
                                    <svg
                                        v-if="heatTreatmentCompleted"
                                        class="w-4 h-4 mr-1 text-green-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    {{ heatTreatmentCompleted ? 'Heat Treatment completed' : '✓ Tick the box to finalize Heat Treatment' }}
                                </label>
                            </div>
                    </div>
                </div>

                <Modal :show="showModal" @close="showModal = false">
                    <div class="flex flex-col items-center justify-center bg-blue-300 p-5 rounded-lg w-full max-w-3xl mx-auto shadow-xl mb-0">
                        <p class="mb-6 text-lg font-bold text-white text-center">Mass Production Control Sheet</p>

                        <div class="flex flex-row justify-between w-full mb-4">
                            <span class="text-white font-semibold text-xl">Box: <span class="underline text-yellow-200">{{ massProd_letter[currentBoxIndex] }}</span></span>
                            <span class="text-white font-semibold">Current Layer: {{ currentLayerName }}</span>
                        </div>

                        <!-- Input Fields Group 1 -->
                        <div class="flex flex-wrap gap-4 w-full mb-4">
                            <div class="flex flex-col w-[30%]">
                                <label class="text-sm font-semibold text-white mb-1">Quantity</label>
                                <input type="number" v-model="massProd_qty"
                                    placeholder="e.g. 1600"
                                    class="bg-white text-gray-800 px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-white" />
                            </div>

                            <div class="flex flex-col w-[30%]">
                                <label class="text-sm font-semibold text-white mb-1">Box No.</label>
                                <input type="text" v-model="massProd_boxNo" @input="massProd_boxNo = massProd_boxNo.toUpperCase()"
                                    placeholder="e.g. UBP8 5071"
                                    class="bg-white text-gray-800 px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-white" />
                            </div>

                            <div class="flex flex-col w-[30%]">
                                <label class="text-sm font-semibold text-white mb-1">WT</label>
                                <input type="number" v-model="massProd_WT"
                                    placeholder="e.g. 15.23"
                                    class="bg-white text-gray-800 px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-white" />
                            </div>
                        </div>

                        <!-- Input Fields Group 2 -->
                        <div class="flex flex-wrap gap-4 w-full mb-6">
                            <div class="flex flex-col w-[48%]">
                                <label class="text-sm font-semibold text-white mb-1">Coating</label>
                                <input type="number" v-model="massProd_coating"
                                    placeholder="e.g. 4"
                                    class="bg-white text-gray-800 px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-white" />
                            </div>

                            <div class="flex flex-col w-[48%]">
                                <label class="text-sm font-semibold text-white mb-1">Raw Material Code</label>
                                <input type="text" v-model="massProd_rawMatCode" @input="massProd_rawMatCode = massProd_rawMatCode.toUpperCase()"
                                    placeholder="e.g. 0G0V755"
                                    class="bg-white text-gray-800 px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-white" />
                            </div>
                        </div>

                        <!-- TPM Boxes Preview Table -->
                        <div class="w-full bg-white rounded-md shadow-md overflow-x-auto mb-6 select-none pointer-events-none">
                            <table class="min-w-full text-sm text-left text-gray-800 table-fixed border border-gray-300">
                                <thead class="bg-gray-100 text-xs uppercase font-semibold text-gray-600">
                                    <tr>
                                        <th class="px-4 py-2 w-12">Box</th>
                                        <th class="px-4 py-2 w-20">Layer</th>
                                        <th class="px-4 py-2 w-20">Qty</th>
                                        <th class="px-4 py-2 w-20">WT</th>
                                        <th class="px-4 py-2 w-32">Box No</th>
                                        <th class="px-4 py-2 w-20">Coating</th>
                                        <th class="px-4 py-2 w-32">Raw Mat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(box, index) in allBoxesData"
                                        :key="box.id"
                                        class="border-t border-gray-200 bg-white"
                                    >
                                        <td class="px-4 py-2">{{ box.box_letter }}</td>
                                        <td class="px-4 py-2">{{ box.layer_no ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ box.quantity ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ box.weight ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ box.box_no ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ box.coating ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ box.raw_mat_code ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-row justify-end w-full gap-4">
                            <button
                                @click="showModal = false"
                                class="px-4 py-2 font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-white transition">
                                Close
                            </button>
                            <button
                                @click="revert_boxLetter"
                                class="px-4 py-2 font-semibold text-white bg-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-white transition">
                                Redo Previous
                            </button>
                            <button
                                @click="saveToTpmBoxes"
                                class="px-4 py-2 font-semibold bg-white text-blue-600 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white transition">
                                Next
                            </button>
                        </div>
                    </div>
                </Modal>


                <div class="p-5 mx-10 mb-10 border-2 border-white rounded-lg shadow-xl">
                    <!-- Oven Heating Information Section -->
                    <div class="flex flex-col mb-4 space-y-4">
                        <p class="mb-2 text-xl font-semibold text-gray-700">Oven Heating Information</p>

                        <!-- Row 1 -->
                        <div class="flex flex-row gap-4 justify-evenly">
                            <!-- Oven Machine No -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenMachineNo" class="mb-1 text-sm font-medium text-gray-700">
                                    Oven Machine No:
                                </label>
                                <input
                                    v-model="reportOvenMachineNo"
                                    type="text"
                                    name="ovenMachineNo"
                                    id="ovenMachineNo"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>

                            <!-- Time Loading -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="timeLoading" class="mb-1 text-sm font-medium text-gray-700">
                                    Time Loading:
                                </label>
                                <input
                                    v-model="reportTimeLoading"
                                    @input="reportTimeLoading = reportTimeLoading.toUpperCase()"
                                    type="text"
                                    name="timeLoading"
                                    id="timeLoading"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>

                            <!-- Temperature (Time Loading) -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="temperature_timeLoading" class="mb-1 text-sm font-medium text-gray-700">
                                    Temperature (Time Loading):
                                </label>
                                <input
                                    v-model="reportTemperature_TimeLoading"
                                    type="text"
                                    name="temperature_timeLoading"
                                    id="temperature_timeLoading"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>
                            <!-- Shift -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_shift" class="mb-1 text-sm font-medium text-gray-700">
                                    Shift:
                                </label>
                                <input
                                    v-model="reportShift_OvenInfo"
                                    @input="reportShift_OvenInfo = reportShift_OvenInfo.toUpperCase()"
                                    type="text"
                                    name="ovenInfo_shift"
                                    id="ovenInfo_shift"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="flex flex-row gap-4 mt-4 justify-evenly">
                            <!-- Date -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_date" class="mb-1 text-sm font-medium text-gray-700">
                                    Date:
                                </label>
                                <input
                                    v-model="reportDate_OvenInfo"
                                    type="date"
                                    name="ovenInfo_date"
                                    id="ovenInfo_date"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>

                            <!-- Time Unloading -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="timeUnloading" class="mb-1 text-sm font-medium text-gray-700">
                                    Time Unloading:
                                </label>
                                <input
                                    v-model="reportTimeUnloading"
                                    @input="reportTimeUnloading = reportTimeUnloading.toUpperCase()"
                                    type="text"
                                    name="timeUnloading"
                                    id="timeUnloading"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>

                            <!-- Temperature (Time Unloading) -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="temperature_TimeUnloading" class="text-[13px] font-medium text-gray-700 mb-1">
                                    Temperature (Time Unloading):
                                </label>
                                <input
                                    v-model="reportTemperature_TimeUnloading"
                                    type="text"
                                    name="temperature_TimeUnloading"
                                    id="temperature_TimeUnloading"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>
                            <!-- Operator -->
                            <div class="flex flex-col w-[12rem]">
                                <label for="ovenInfo_operator" class="mb-1 text-sm font-medium text-gray-700">
                                    Operator:
                                </label>
                                <input
                                    v-model="reportOperator_OvenInfo"
                                    @input="reportOperator_OvenInfo = reportOperator_OvenInfo.toUpperCase()"
                                    type="text"
                                    name="ovenInfo_operator"
                                    id="ovenInfo_operator"
                                    class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 mx-10 border-2 border-white rounded-lg shadow-xl">
                    <p class="mb-2 text-xl font-semibold text-gray-700">Magnetic Property Inspection Information</p>
                    <!-- Pulse Tracer Info Row -->
                    <div class="flex flex-row gap-4 mt-4 justify-evenly">
                        <!-- Pulse Tracer Machine No (display-only) -->
                        <div class="flex flex-col w-[12rem]">
                            <label class="mb-1 text-sm font-medium text-gray-700">
                                Pulse Tracer Machine No:
                            </label>
                            <span
                                class="flex items-center px-3 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md cursor-default h-9 hover:ring-1 hover:ring-blue-400 hover:shadow-sm"
                            >
                                {{ reportPulseTracerMachineNo }}
                            </span>
                        </div>

                        <!-- Date -->
                        <div class="flex flex-col w-[12rem]">
                            <label for="reportDate" class="mb-1 text-sm font-medium text-gray-700">
                                Date:
                            </label>
                            <input
                                v-model="reportDate"
                                type="date"
                                name="reportDate"
                                id="reportDate"
                                class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            />
                        </div>

                        <!-- Shift -->
                        <div class="flex flex-col w-[12rem]">
                            <label for="reportShift" class="mb-1 text-sm font-medium text-gray-700">
                                Shift:
                            </label>
                            <input
                                v-model="reportShift"
                                @input="reportShift = reportShift.toUpperCase()"
                                type="text"
                                name="reportShift"
                                id="reportShift"
                                class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            />
                        </div>

                        <!-- Operator -->
                        <div class="flex flex-col w-[12rem]">
                            <label for="reportOperator" class="mb-1 text-sm font-medium text-gray-700">
                                Operator:
                            </label>
                            <input
                                v-model="reportOperator"
                                @input="reportOperator = reportOperator.toUpperCase()"
                                type="text"
                                name="reportOperator"
                                id="reportOperator"
                                class="px-3 text-sm text-gray-800 transition duration-200 ease-in-out bg-white border border-gray-300 rounded-md h-9 hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            />
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
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ inspectionLength }} mm</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">WIDTH&nbsp;(mm)</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ inspectionWidth }} mm</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">THICKNESS&nbsp;(mm)</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ inspectionThickness }} mm</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">MATERIAL&nbsp;GRADE</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ inspectionMaterialGrade }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">MPI&nbsp;SAMPLE&nbsp;QTY.</td>
                                    <td class="px-4 py-1 text-blue-600 border-4 border-white">{{ inspectionMpiSampleQty }}</td>
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
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white whitespace-nowrap">Br (G)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ inspectionBrStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrVariance }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td :rowspan="(noteReasonForReject.includes('- N.G iHc')) && showGX ? 2 : 1" class="px-4 py-2 text-blue-600 border-4 border-white whitespace-nowrap">iHc (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><span v-if="showGX">GM </span>{{ inspectioniHcStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHcVariance }}</td>
                                </tr>
                                <tr v-if="showGX && (noteReasonForReject.includes('- N.G iHc'))" class="text-center">
                                    <td class="text-blue-600 border-4 border-white "><span v-if="showGX">GX </span>
                                        <input type="number" v-model="reportGX_iHcStandard" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="text-blue-600 border-4 border-white "><input type="number" v-model="reportGX_iHcAverage" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="text-blue-600 border-4 border-white "><input type="number" v-model="reportGX_iHcMaximum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="text-blue-600 border-4 border-white "><input type="number" v-model="reportGX_iHcMinimum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="text-blue-600 border-4 border-white ">{{ reportGX_iHcVariance }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td :rowspan="(noteReasonForReject.includes('- N.G iHc')) && showGX ? 2 : 1" class="px-4 py-2 text-blue-600 border-4 border-white whitespace-nowrap">iHk (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><span v-if="showGX">GM </span>{{ inspectioniHkStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHkVariance }}</td>
                                </tr>
                                <tr v-if="showGX && (noteReasonForReject.includes('- N.G iHc'))" class="text-center">
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><span v-if="showGX">GX </span> - - - - - </td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportGX_iHkAverage" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportGX_iHkMaximum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportGX_iHkMinimum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportGX_iHkVariance }}</td>
                                </tr>

                                <template v-if="showROB">
                                    <tr class="bg-blue-400">
                                        <th colspan="7" class="py-1 font-semibold text-center text-white border-4 border-white text-md">BH Tracer Measurement</th>
                                    </tr>
                                    <tr class="bg-blue-400">
                                        <th rowspan="2" class="px-4 text-white border-4 border-white">ITEMS</th>
                                        <th rowspan="2" class="px-4 text-white border-4 border-white">SPECS</th>
                                        <th class="px-4 text-white border-4 border-white">Br Min</th>
                                        <th class="px-4 text-white border-4 border-white">Br Max</th>
                                        <th class="px-4 text-white border-4 border-white">iHc Min</th>
                                        <th class="px-4 text-white border-4 border-white">iHc Max</th>
                                        <th rowspan="2" class="px-4 text-white border-4 border-white">Result</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white"><input type="text" v-model="reportROB_brMin" @input="reportROB_brMin = reportROB_brMin?.toUpperCase()" name="robBrMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></th>
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white"><input type="text" v-model="reportROB_brMax" @input="reportROB_brMax = reportROB_brMax?.toUpperCase()" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></th>
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white"><input type="text" v-model="reportROB_iHcMin" @input="reportROB_iHcMin = reportROB_iHcMin?.toUpperCase()" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></th>
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white"><input type="text" v-model="reportROB_iHcMax" @input="reportROB_iHcMax = reportROB_iHcMax?.toUpperCase()" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white">Br @ RT</th>
                                        <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_BrRTstandard }} kg</td>
                                        <td class="px-4 text-white border-4 border-white"><input type="number" v-model="reportROB_BrRT_brMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_BrRT_brMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_BrRT_iHcMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_BrRT_iHcMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_remarks }}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white">Br @ VT (180°C)</th>
                                        <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_BrVTstandard }} kg</td>
                                        <td class="px-4 text-white border-4 border-white"><input type="number" v-model="reportROB_BrVT_brMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_BrVT_brMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_BrVT_iHcMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_BrVT_iHcMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_remarks }}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white">HD5 (180°C)</th>
                                        <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_HD5standard }} kOe</td>
                                        <td class="px-4 text-white border-4 border-white"><input type="number" v-model="reportROB_HD5_brMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_HD5_brMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_HD5_iHcMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_HD5_iHcMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_remarks }}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="px-4 py-2 text-blue-600 border-4 border-white">JD5 (180°C)</th>
                                        <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_JD5standard }} kG</td>
                                        <td class="px-4 text-white border-4 border-white"><input type="number" v-model="reportROB_JD5_brMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_JD5_brMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_JD5_iHcMin" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white"><input type="number" v-model="reportROB_JD5_iHcMax" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                            <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_remarks }}</td>
                                    </tr>
                                </template>

                                <template v-if="isTTM_model">
                                    <tr class="bg-blue-300">
                                        <th rowspan="2" colspan="2" class="text-xl text-white border-4 border-white whitespace-nowrap">Computation of Cpk from Br</th>
                                        <th class="text-white border-4 border-white ">STD DEV</th>
                                        <th class="text-white border-4 border-white ">Cp</th>
                                        <th class="text-white border-4 border-white whitespace-nowrap">Cpk &#8805; 1.00</th> <!-- &#8805; is greater than equal to symbol -->
                                        <th colspan="2" class="px-4 py-2 text-white border-4 border-white">Remarks</th>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="px-1 py-[2px] text-blue-600 border-4 text-center border-white"><input type="number" v-model="reportStdDev" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCp" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCpk" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                        <td colspan="2" class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportCpkRemarks }}</td>
                                    </tr>
                                </template>

                                <tr v-show="show1x1x1Data_withoutCorner" class="text-center bg-blue-300">
                                    <td colspan="2" class="px-4 py-2 font-extrabold text-white border-4 border-white">Data of 1x1x1 mm samples</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">AVERAGE</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">MAXIMUM</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">MINIMUM</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white whitespace-nowrap">Cpk &#8805; {{ cpkStandardValue }}</td> <!-- &#8805; is greater than equal to symbol -->
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">Remarks</td>
                                </tr>
                                <tr v-show="show1x1x1Data_Corner" class="text-center">
                                    <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Corner</th>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">&#8805; <input type="number" v-model="reportCorner" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCorner_average" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCorner_maximum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCorner_minimum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCorner_cpk" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportCorner_remarks }}</td>
                                </tr>
                                <tr v-show="show1x1x1Data_withoutCorner" class="text-center">
                                    <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Surface</th>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">&#8805; <input type="number" v-model="reportSurface" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportSurface_average" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportSurface_maximum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportSurface_minimum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportSurface_cpk" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportSurface_remarks }}</td>
                                </tr>
                                <tr v-show="show1x1x1Data_withoutCorner" class="text-center">
                                    <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Core</th>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">&#8805; <input type="number" v-model="reportCore" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCore_average" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCore_maximum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCore_minimum" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCore_cpk" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportCore_remarks }}</td>
                                </tr>
                                <template v-if="showVTData && (noteReasonForReject.includes('- N.G iHc'))">
                                    <!-- VT Row 1: Sample + Remarks (Vertical Repeating Inputs) -->
                                    <tr class="text-center">
                                        <th colspan="2" class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">VT Data</th>
                                        <td colspan="3" class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300">
                                            <div class="flex flex-col gap-[6px] items-center">
                                                <div
                                                    v-for="i in reportVT_samplesQty"
                                                    :key="'sample-remarks-' + i"
                                                    class="flex items-center gap-1"
                                                >{{ i + ")" }}
                                                    <input
                                                    type="text"
                                                    v-model="reportVT_samples[i - 1]"
                                                    @input="reportVT_samples[i - 1] = reportVT_samples[i - 1]?.toUpperCase()"
                                                    class="w-[6.5rem] h-[1.5rem] py-[14px] text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                            transition duration-200 ease-in-out placeholder-opacity-30 placeholder-gray-500"
                                                    placeholder="12AB"
                                                    />
                                                    (
                                                    <input
                                                    type="text"
                                                    v-model="reportVT_sampleRemarks[i - 1]"
                                                    @input="reportVT_sampleRemarks[i - 1] = reportVT_sampleRemarks[i - 1]?.toUpperCase()"
                                                    class="w-[6.5rem] h-[1.5rem] py-[14px] text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                            transition duration-200 ease-in-out placeholder-opacity-30 placeholder-gray-500"
                                                    placeholder="N.G iHc"
                                                    />
                                                    )
                                                </div>
                                            </div>
                                        </td>
                                        <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Result</th>
                                    </tr>

                                    <!-- VT Row 2: Temperature, iHc, Result, Remarks -->
                                    <tr class="text-center">
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">
                                        <div class="flex items-center">
                                        <input
                                            type="number"
                                            v-model="reportVT_temp"
                                            class="w-[4.5rem] h-[1.5rem] py-[14px] text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                transition duration-200 ease-in-out"
                                        />
                                        <span class="ml-1 align-baseline">°C</span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white whitespace-nowrap">
                                        iHc (kOe) &#8805;
                                        <input
                                        type="number"
                                        v-model="reportVT_iHc"
                                        class="w-[5.5rem] h-[1.5rem] py-[14px] text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                transition duration-200 ease-in-out"
                                        />
                                        (kOe)
                                    </td>
                                    <td colspan="3" class="px-1 py-[2px] text-blue-600 border-4 border-white">
                                        <div class="flex flex-col gap-[6px] items-center">
                                        <div
                                            v-for="i in reportVT_samplesQty"
                                            :key="'result-' + i"
                                            class="flex items-center gap-1"
                                        >{{ i + ")" }}
                                            <input
                                            type="number"
                                            v-model="reportVT_iHcResults[i - 1]"
                                            class="w-[6.5rem] h-[1.5rem] py-[14px] text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                    hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                    focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                    transition duration-200 ease-in-out placeholder-opacity-30 placeholder-gray-500"
                                            placeholder="0"
                                            />
                                            <span class="text-gray-600">kOe</span>
                                        </div>
                                        </div>
                                    </td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportVT_remarks }}</td>
                                    </tr>
                                </template>
                                <template v-if="showVTData_default && (noteReasonForReject.includes('- N.G iHc'))">
                                    <tr class="text-center">
                                        <th colspan="2" class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">VT Data</th>
                                        <td colspan="3" class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300">Enter the number of samples first and then save</td>
                                        <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Result</th>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white">
                                            <div class="flex items-center">
                                                <input type="number" v-model="reportVT_temp" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                    hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                    focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                    transition duration-200 ease-in-out">
                                                <span class="ml-1 align-baseline">°C</span>
                                            </div>
                                        </td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white whitespace-nowrap">iHc (kOe) &#8805; <input type="number" v-model="reportVT_iHc" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"> (kOe)</td>
                                        <td colspan="3" class="px-1 py-[2px] text-blue-600 border-4 border-white"> Quantity of Samples:<input type="number" v-model="reportVT_samplesQty" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportVT_remarks }}</td>
                                    </tr>
                                </template>
                                <tr v-show="showCpkFrom_iHc" class="bg-blue-300">
                                    <th rowspan="2" colspan="2" class="px-4 py-2 text-xl text-white border-4 border-white whitespace-nowrap">Computation of Cpk from iHc</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">STD DEV</th>
                                    <th colspan="2" class="px-4 py-2 text-white border-4 border-white whitespace-nowrap">Cpk &#8805; {{ cpkStandardValue }}</th> <!-- &#8805; is greater than equal to symbol -->
                                    <th colspan="2" class="px-4 py-2 text-white border-4 border-white">Remarks</th>
                                </tr>
                                <tr v-show="showCpkFrom_iHc" class="text-center">
                                    <td class="px-1 py-[2px] text-blue-600 border-4 text-center border-white"><input type="number" v-model="reportCpkFrom_iHc_StdDev" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td colspan="2" class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCpkFrom_iHc_Cpk" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportCpkFrom_iHc_remarks }}</td>
                                </tr>
                                <template v-if="showBHData && (noteReasonForReject.includes('- N.G iHc'))">
                                    <!-- VT Row 1: Sample + Remarks (Vertical Repeating Inputs) -->
                                    <tr class="text-center">
                                        <th colspan="2" class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">BH Data @ <input type="number" v-model="reportBH_temp" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> °C</th>
                                        <td colspan="3" class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300">
                                            <div class="flex flex-col gap-[6px] items-center">
                                                <div
                                                    v-for="i in reportBH_sampleQty"
                                                    :key="'sample-remarks-' + i"
                                                    class="flex items-center gap-1"
                                                >{{ i + ")" }}
                                                    <input
                                                    type="text"
                                                    v-model="reportBH_samples[i - 1]"
                                                    @input="reportBH_samples[i - 1] = reportBH_samples[i - 1]?.toUpperCase()"
                                                    class="w-[6.5rem] h-[1.5rem] py-[14px] text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                            transition duration-200 ease-in-out placeholder-opacity-30 placeholder-gray-500"
                                                    placeholder="18 AF"
                                                    />
                                                </div>
                                            </div>
                                        </td>
                                        <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Result</th>
                                    </tr>

                                    <!-- VT Row 2: Temperature, iHc, Result, Remarks -->
                                    <tr class="text-center">
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white">
                                            <div class="flex items-center">
                                            <input type="text" v-model="reportBH_data" @input="reportBH_data = reportBH_data.toUpperCase()" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                transition duration-200 ease-in-out">
                                            <span class="ml-1 align-baseline"> kOe</span>
                                            </div>
                                        </td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white whitespace-nowrap">
                                             &#8805; <input type="number" v-model="reportBH_dataStandard" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"> (kOe)
                                        </td>
                                        <td colspan="3" class="px-1 py-[2px] text-blue-600 border-4 border-white">
                                            <div class="flex flex-col gap-[6px] items-center">
                                                <div
                                                    v-for="i in reportBH_sampleQty"
                                                    :key="'result-' + i"
                                                    class="flex items-center gap-1"
                                                >{{ i + ")" }}
                                                    <input
                                                    type="number"
                                                    v-model="reportBH_results[i - 1]"
                                                    class="w-[6.5rem] h-[1.5rem] py-[14px] text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                            transition duration-200 ease-in-out placeholder-opacity-30 placeholder-gray-500"
                                                    placeholder="0"
                                                    />
                                                    <span class="text-gray-600">kOe</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportBH_remarks }}</td>
                                    </tr>
                                    <!-- Reset Button with Confirmation -->
                                    <div v-if="!confirmReset" class="text-center">
                                    <button
                                        @click="confirmReset = true"
                                        class="px-2 py-1 m-1 text-white transition-transform duration-200 ease-in-out bg-red-700 rounded-lg shadow-lg whitespace-nowrap active:scale-95 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                                    >
                                        Wrong Quantity Reset Button
                                    </button>
                                    </div>

                                    <!-- Confirmation Prompt -->
                                    <div v-else class="flex items-center justify-center gap-2 mt-1">
                                    <span class="p-1 text-sm text-blue-600 whitespace-nowrap">This action will reset all BH Data Input. Are you sure?</span>
                                    <button
                                        @click="resetReportBHData(); confirmReset = false"
                                        class="px-2 py-1 text-xs text-white transition bg-green-600 rounded hover:bg-green-500"
                                    >
                                        Yes
                                    </button>
                                    <button
                                        @click="confirmReset = false"
                                        class="px-2 py-1 text-xs text-white transition bg-gray-400 rounded hover:bg-gray-500"
                                    >
                                        Cancel
                                    </button>
                                    </div>
                                </template>


                                <template v-if="showBHData_default && (noteReasonForReject.includes('- N.G iHc'))">
                                    <tr class="text-center">
                                        <th colspan="2" class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">BH Data @ <input type="number" v-model="reportBH_temp" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"> °C</th>
                                        <td colspan="3" class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300">Enter the number of samples first and then save</td>
                                        <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Result</th>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white">
                                            <div class="flex items-center">
                                                <input type="text" v-model="reportBH_data" @input="reportBH_data = reportBH_data.toUpperCase()" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                    hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                    focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                    transition duration-200 ease-in-out">
                                                <span class="ml-1 align-baseline"> kOe</span>
                                            </div>
                                        </td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white whitespace-nowrap"> &#8805; <input type="number" v-model="reportBH_dataStandard" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"> (kOe)</td>
                                        <td colspan="3" class="px-1 py-[2px] text-blue-600 border-4 border-white">Quantity of Samples: <input type="number" v-model="reportBH_sampleQty" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                            hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                            focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                            transition duration-200 ease-in-out"></td>
                                        <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportBH_remarks }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-row items-center justify-center mx-5 mt-5 align-middle">
                        <p class="m-5 text-lg font-semibold">Remarks:</p>
                        <div class="flex flex-col w-full">
                            <span class="text-blue-600">
                                {{ reportRemarks2 }}
                            </span>
                            <span class="text-blue-600">
                                {{ reportRemarks3 }}
                            </span>
                            <input
                                v-model="reportRemarks"
                                type="text"
                                @input="reportRemarks = reportRemarks.toUpperCase()"
                                class="px-2 py-1 text-sm transition duration-200 ease-in-out bg-transparent bg-white border border-white rounded-md hover:border-blue-400 hover:ring-1 hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white"
                            />
                        </div>
                        <div class="px-0 py-5 mx-5 bg-blue-300 shadow-xl rounded-2xl">
                            <p
                                class="mx-10 text-xl font-extrabold transition-opacity duration-1000 animate-pulse"
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
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white whitespace-nowrap">
                        SMP Judgement
                    </p>
                    <p v-if="modifiedSMPJudgement" class="p-2 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <span
                        class="inline-block w-40 h-40 bg-center bg-no-repeat"
                        :style="{
                            backgroundImage:
                                modifiedSMPJudgement === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : modifiedSMPJudgement === 'HOLD'
                                ? 'url(\'/photo/hold_stamp.png\')'
                                : modifiedSMPJudgement === null ||
                                    modifiedSMPJudgement === undefined ||
                                    modifiedSMPJudgement === '' ||
                                    modifiedSMPJudgement === 'null'
                                ? 'url(\'/photo/template.png\')'
                                : 'url(\'/photo/pass_stamp.png\')',
                            backgroundSize: '101%'
                        }">
                        </span>
                    </p>
                    <p v-else class="p-2 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <span
                        class="inline-block w-40 h-40 bg-center bg-no-repeat"
                        :style="{
                            backgroundImage:
                                reportSMPJudgement === 'REJECT'
                                ? 'url(\'/photo/reject_stamp.png\')'
                                : reportSMPJudgement === 'HOLD'
                                ? 'url(\'/photo/hold_stamp.png\')'
                                : reportSMPJudgement === null ||
                                    reportSMPJudgement === undefined ||
                                    reportSMPJudgement === '' ||
                                    reportSMPJudgement === 'null'
                                ? 'url(\'/photo/template.png\')'
                                : 'url(\'/photo/pass_stamp.png\')',
                            backgroundSize: '101%'
                        }">
                        </span>
                    </p>
                    <div v-if="state.user && (state.user.access_type == 'Proxy Approver' || state.user.access_type == 'Automation')" class="flex flex-row items-center justify-center mt-2">
                        <label class="mr-2 text-xs">Change Judgement: </label>
                        <select v-model="modifiedSMPJudgement" class="text-blue-700 input text-xs w-[100px] h-[35px]" required>
                            <option disabled value="" selected>Change Judgement</option>
                            <option class="text-blue-700" value="HOLD">HOLD</option>
                            <option class="text-blue-700" value="REJECT">REJECT</option>
                            <option class="text-blue-700" value="PASSED">PASSED</option>
                        </select>
                    </div>
                    <div v-if="(modifiedSMPJudgement && state.user) && (state.user.access_type == 'Proxy Approver' || state.user.access_type == 'Automation')" class="p-2 mt-2 text-sm font-extrabold text-blue-500 border-4 border-white">Origin Judgement: {{ reportSMPJudgement }}</div>
                    </div>

                    <!-- Prepared By -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white whitespace-nowrap">Prepared By:</p>
                    <div class="items-center p-1 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <div v-show="showPreparedByDefault" class="w-[153px] h-[153px]">
                            <span class="font-semibold text-blue-700 opacity-100 animate-pulse">
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
                            class="px-3 py-4 font-medium text-white transition duration-300 ease-in-out border rounded-md shadow-md whitespace-nowrap bg-green-500/40 hover:bg-green-500/90 border-white/30 hover:border-white/60 hover:shadow-green-400 hover:shadow-lg backdrop-blur-md"
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
                        class="flex flex-col items-center justify-center w-40 h-40 text-2xl font-extrabold text-center text-red-600 bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: 'url(\'/photo/template.png\')',
                            backgroundSize: '101%'
                        }">
                        <span :class="preparedByPerson_firstNameFontSize">{{ preparedByPerson_firstName }}</span>
                        <span class="my-[6.5px]">{{ reportPreparedByDate }}</span>
                        <span :class="preparedByPerson_lastNameFontSize">{{ preparedByPerson_lastName }}</span>
                        </span>
                    </div>
                    </div>

                    <!-- Checked By -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white whitespace-nowrap">Checked By:</p>
                    <div class="items-center p-1 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <div v-show="showCheckedByDefault" class="w-[153px] h-[153px]">
                        <span class="font-semibold text-blue-700 opacity-100 animate-pulse whitespace-nowrap">
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
                            class="px-3 py-4 font-medium text-white transition duration-300 ease-in-out border rounded-md shadow-md whitespace-nowrap bg-green-500/40 hover:bg-green-500/90 border-white/30 hover:border-white/60 hover:shadow-green-400 hover:shadow-lg backdrop-blur-md"
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
                        class="flex flex-col items-center justify-center w-40 h-40 text-2xl font-extrabold text-center text-red-600 bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: 'url(\'/photo/template.png\')',
                            backgroundSize: '101%'
                        }">
                        <span :class="getFontSize(checkedByPerson_firstName)">{{ checkedByPerson_firstName }}</span>
                        <span class="my-[9px]">{{ reportCheckedByDate }}</span>
                        <span :class="getFontSize(checkedByPerson_lastName)">{{ checkedByPerson_lastName }}</span>
                        </span>
                    </div>
                    </div>

                    <!-- Approved By -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white whitespace-nowrap">Approved By:</p>
                    <div class="items-center p-2 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <div v-show="showApprovedByDefault" class="w-[153px] h-[153px]">
                        <span class="font-semibold text-blue-700 opacity-100 animate-pulse whitespace-nowrap">
                            Waiting for stamp...
                        </span>
                        </div>
                        <span
                        v-show="approvedByStampPhoto"
                        class="flex flex-col items-center justify-center w-40 h-40 text-2xl font-extrabold text-center text-red-600 bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: 'url(\'/photo/template.png\')',
                            backgroundSize: '101%'
                        }">
                        <span :class="getFontSize(approvedByPerson_firstName)">{{ approvedByPerson_firstName }}</span>
                        <span class="my-[9px]">{{ reportApprovedByDate }}</span>
                        <span :class="getFontSize(approvedByPerson_lastName)">{{ approvedByPerson_lastName }}</span>
                        </span>
                    </div>
                    </div>

                    <!-- Note: -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white">Note: Reason of REJECT/HOLD</p>
                    <div class="p-2 border-4 border-white w-[320px] h-[190px] box-border">
                        <div class="flex flex-col items-start w-full h-full overflow-auto bg-blue-100">
                            <span
                                v-for="(note, index) in sortedNotes"
                                :key="index"
                                class="font-semibold text-red-700 opacity-100"
                            >
                                {{ note }}
                            </span>
                        </div>
                    </div>
                    </div>

                </div>

                <!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here -->

                <div v-show="showNotif3" class="flex flex-row items-center justify-center max-w-xs px-4 py-2 mx-auto mt-10 text-white bg-green-700 rounded-md shadow-lg">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
                <div class="flex flex-row items-center justify-center mb-8">
                    <button v-if="(!approvedByPerson_firstName && state.user ) && (state.user.access_type === 'Preparation Approver' || state.user.access_type === 'Checking Approver' || state.user.access_type === 'Hybrid Approver' || state.user.access_type === 'Bypass Approver' || state.user.access_type === 'Proxy Approver')" @click="saveReport" class="px-6 py-4 mt-4 font-extrabold text-white transition duration-300 ease-in-out transform bg-green-500 shadow-xl rounded-xl hover:bg-green-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-600 active:scale-95">
                        {{ reportExistingSMPJudgement !== null ? 'OVERWRITE' : 'SAVE' }}
                    </button>
                    <button @click="viewPropertyData(currentSerialSelected)" class="px-6 py-4 mt-4 ml-5 font-extrabold text-blue-700 transition duration-300 ease-in-out transform border border-blue-700 shadow-xl hover:text-white rounded-xl hover:bg-blue-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-600 active:scale-95">
                        View Property Data
                    </button>
                    <!-- Apply Data 1x1x1 Button (New Styled Button) -->
                    <button
                        @click="sec_additional_redirect(currentSerialSelected)"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-red-700 transition duration-300 ease-in-out transform border border-red-700 shadow-xl rounded-xl hover:text-white hover:bg-red-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-600 active:scale-95"
                        >
                        Apply Additional
                    </button>
                    <button
                        v-if="approvedByPerson_firstName"
                        @click="finalizeReport(currentSerialSelected)"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-yellow-600 transition duration-300 ease-in-out transform border border-yellow-400 shadow-xl rounded-xl hover:text-white hover:bg-yellow-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-600 active:scale-95"
                    >
                        View PDF Report
                    </button>

                    <button v-if="showExitButton && !isFromApproval && !isFromViewList" @click="exitReport()" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK
                    </button>

                    <button v-if="isFromApproval && !isFromViewList" @click="backToApprovalFunction" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK TO APPROVAL
                    </button>

                    <button v-if="isFromViewList && !isFromApproval" @click="$inertia.visit('/view')" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK TO VIEW LIST
                    </button>

                </div>
                <div>
                    <button v-if="onTestServer" @click="finalizeReport(currentSerialSelected)" class="p-2 bg-blue-100 rounded-lg">
                        View PDF Report BYPASS
                    </button>
                </div>
                <div v-show="showNotif" class="flex flex-row items-center justify-center max-w-xs px-4 py-2 mx-auto mt-10 text-white bg-green-700 rounded-md shadow-lg">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
                <div v-if="report_isFinalized" class="relative flex items-center w-full h-10 overflow-hidden text-yellow-700 bg-yellow-100">
                    <div class="marquee animate-marquee">
                    <span class="mx-8">This report has already been finalized.</span>
                    <span class="mx-8">This report has already been finalized.</span>
                    <span class="mx-8">This report has already been finalized.</span>
                    <span class="mx-8">This report has already been finalized.</span>
                    </div>
                </div>
            </div>

        </div>
      </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted, nextTick, watch, computed, watchEffect, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3'
import DotsLoader from '@/Components/DotsLoader.vue';
import Modal from '@/Components/Modal.vue' // adjust path if needed
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js';

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

        greetingsWindowFirstName.value = state.user.firstName;
        greetingsWindowLastName.value = state.user.surname;

        if(state.user.access_type === 'Coating') {
            coatingAccess.value = true;
        } else if(state.user.access_type === 'Heat Treatment') {
            heatTreatmentAccess.value = true;
        } else {
            coatingAccess.value = false;
            heatTreatmentAccess.value = false;
        }

        if(state.user.access_type === 'Automation') {
            automationAcess.value = true;
        } else {
            automationAcess.value = false;
        }

        return true; // Indicate authenticated
    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/'); // Redirect on error
        return false; // Indicate not authenticated
    }
};

const userReportLogging = async (logEvent) => {
    try{
        const responseReportLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Report',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userReportLogging post request failed: ',error);
    }
}

    const userFinalizedLogging = async (logEvent) => {
    try{
        const responseFinalizedLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Report',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userFinalizedLogging post request failed: ',error);
    }
}

const saveReportButtonVisibility = () => {
    if(!checkedByPerson_firstName.value && (state.user.access_type === 'Preparation Approver' || state.user.access_type === 'Checking Approver' || state.user.access_type === 'Hybrid Approver' || state.user.access_type === 'Bypass Approver')){
        showReportSaveButton.value = true;
    }
}

const timeOfDay = computed(() => {
  const hour = new Date().getHours();
  if (hour < 12) return 'Morning';
  if (hour < 18) return 'Afternoon';
  return 'Evening';
});

//Debug mode --
const onTestServer = ref(false);

const page = usePage();

console.log('Current page props:', page.props)
//UI Control start

const isOn = ref(false);
const cpkStandardValue = ref(1.33);
function toggleSwitch() {
    isOn.value = !isOn.value;
}
const showReportLoading = ref(false);

const isFromApproval = ref(false);
const backToApproval = ref(false);
const isFromViewList = ref(false);

const reportStatusReminder = ref(false);
const report_isFinalized = ref(false);
const report_isEmailed = ref(false);

const automationAcess = ref(false);

const greetingsWindowFirstName = ref('');
const greetingsWindowLastName = ref('');

const coatingAccess = ref(false);
const heatTreatmentAccess = ref(false);

const showNotif = ref(false);
const showNotif2 = ref(false);
const showNotif3 = ref(false);
const showReportContent = ref(false);
const showReportMain = ref(false);
const showReportProceedButtons = ref(true);
const showSelectionPanel = ref(true);
const showReportSaveButton = ref(false);
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

const isReportDataReady = ref(false);

// models with special instructions

const isTTM_model = ref(false);
const isAutomotive = ref(false);
watch(isAutomotive, (newVal, oldVal) => {
  //console.log(`isAutomotive changed from ${oldVal} to ${newVal}`);
  // Your reactive logic here
}, { immediate: true });
const showCarMarkButton = ref(true);
const show1x1x1Data_withoutCorner = ref(false);
const show1x1x1Data_Corner = ref(false);
const showVTData = ref(false);
const showVTData_default = ref(false);
const showCpkFrom_iHc = ref(false);
const showGX = ref(false);
const showBHData = ref(false);
const showBHData_default = ref(false);
const showROB = ref(false);

const isLoading = ref(true);

//UI Control end

// variables for models with special instructions

const MODELS_SHOW_VT_DATA = ref([]) ;
const MODELS_1X1X1_NO_CORNER = ref([]);
const MODELS_SHOW_CPK = ref([]);
const MODELS_SHOW_GX = ref([]);
const MODELS_SHOW_BH = ref([]);
const MODELS_SHOW_ROB = ref([]);

const reportStdDev = ref(0);
const reportCp = ref(0);
const reportCpk = ref(0);
const reportCpkRemarks = ref('NA');
const reportCorner = ref(0);
const reportCorner_average = ref(0);
const reportCorner_maximum = ref(0);
const reportCorner_minimum = ref(0);
const reportCorner_cpk = ref(0);
const reportCorner_remarks = ref('NA');
const reportSurface = ref(0);
const reportSurface_average = ref(0);
const reportSurface_maximum = ref(0);
const reportSurface_minimum = ref(0);
const reportSurface_cpk = ref(0);
const reportSurface_remarks = ref('NA');
const reportCore = ref(0);
const reportCore_average = ref(0);
const reportCore_maximum = ref(0);
const reportCore_minimum = ref(0);
const reportCore_cpk = ref(0);
const reportCore_remarks = ref('NA');

const reportVT_temp = ref(0);
const reportVT_iHc = ref(0);
const reportVT_remarks = ref('NA');
const reportVT_samplesQty = ref();
const reportVT_samples = ref([]);
const reportVT_sampleRemarks = ref([]);
const reportVT_iHcResults = ref([]);
const reportVT_sampleQtyConfirmation = ref(false);

const reportCpkFrom_iHc_StdDev = ref(0);
const reportCpkFrom_iHc_Cpk = ref(0);
const reportCpkFrom_iHc_remarks = ref('NA');
const reportGX_iHcStandard = ref(0);
const reportGX_iHcAverage = ref(0);
const reportGX_iHcMaximum = ref(0);
const reportGX_iHcMinimum = ref(0);
const reportGX_iHcVariance =  ref(0);
const reportGX_iHkAverage = ref(0);
const reportGX_iHkMaximum = ref(0);
const reportGX_iHkMinimum = ref(0);
const reportGX_iHkVariance =  ref(0);

const reportBH_data = ref('NA');
const reportBH_dataStandard = ref(0);
const reportBH_remarks = ref('NA');
const reportBH_samples = ref([]);
const reportBH_sampleQty = ref();
const reportBH_temp = ref(0);
const reportBH_results = ref([]);

const reportROB_brMax = ref('');
const reportROB_brMin = ref('');
const reportROB_iHcMax = ref('');
const reportROB_iHcMin = ref('');
const reportROB_BrRTstandard = ref(13.0);
const reportROB_BrVTstandard = ref(10.5);
const reportROB_HD5standard = ref(10.053);
const reportROB_HD5rejectstandard = ref(9.425);
const reportROB_JD5standard = ref(9.6);
const reportROB_BrRT_brMin = ref(0);
const reportROB_BrRT_brMax = ref(0);
const reportROB_BrRT_iHcMin = ref(0);
const reportROB_BrRT_iHcMax = ref(0);
const reportROB_BrVT_brMin = ref(0);
const reportROB_BrVT_brMax = ref(0);
const reportROB_BrVT_iHcMin = ref(0);
const reportROB_BrVT_iHcMax = ref(0);
const reportROB_HD5_brMin = ref(0);
const reportROB_HD5_brMax = ref(0);
const reportROB_HD5_iHcMin = ref(0);
const reportROB_HD5_iHcMax = ref(0);
const reportROB_JD5_brMin = ref(0);
const reportROB_JD5_brMax = ref(0);
const reportROB_JD5_iHcMin = ref(0);
const reportROB_JD5_iHcMax = ref(0);
const reportROB_remarks = ref('NA');

//general variables start

const runShowAllData = ref(false);
const currentFurnaceName = ref('');
const currentLayerName = ref('');
const tpmData = ref([]);
const getTpmModel = ref('');
const inspectionDataList = ref([]);
const serialList = ref([]); // Stores all fetched furnaces
const currentSerialSelected = ref('');
const reportRemarksDisplay = ref('');
const ipAddress = ref('');
const filterStatus = ref('');
const currentUserData = ref([]);
const loggedUserData = ref([]);
const currentUserApproverStage = ref('');
const currentUserIP = ref('');
const preparedByPerson = ref('');
const preparedByPerson_firstName = ref('');
const preparedByPerson_lastName = ref('');
const checkedByPerson = ref('');
const checkedByPerson_firstName = ref('');
const checkedByPerson_lastName = ref('');
const approvedByPerson = ref('');
const approvedByPerson_firstName = ref('');
const approvedByPerson_lastName = ref('');

const reportNotificationMessage = ref('');

const tpmDataQuantity = ref(0);
const withAdditionalSampleForRemarks = ref(false);

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
const reportRemarks2 = ref(' - ');
const reportRemarks3 = ref(' - ');

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
const reportihr95Minimum = ref('');
const reportihr98Minimum = ref('');

const reportBrVariance = ref('NA');
const reportiHcVariance = ref('NA');
const reportiHkVariance = ref('NA');

const reportSMPJudgement = ref('');
const modifiedSMPJudgement = ref('');
// Immediate watch
watch(
  reportSMPJudgement,
  (newVal, oldVal) => {
    console.log('reportSMPJudgement changed:', oldVal, '→', newVal);
    // Add your reactive logic here
  },
  { immediate: true } // Triggers on component mount
);
const reportExistingSMPJudgement = ref(null);
const reportPreparedByDate = ref(null);
const reportCheckedByDate = ref(null);
const reportApprovedByDate = ref(null);
const reportRemarksDisplayNG_ihcihk = ref(false);
const reportRemarksDisplayNG_br4pia = ref(false);
const reportRemarksDisplayNG_bhMax = ref(false);
const reportRemarksDisplayNG_bhc = ref(false);

const reportCoatingDate = ref(null);
const reportCoatingLoaderOperator = ref('NA');
const reportCoatingUnloaderOperator = ref('NA');
const reportCoatingMachineNo = ref('NA');
const reportCoatingTotalMagnetWeight = ref('NA');
const reportCoatingMinTbContent = ref('NA');
const reportCoatingSlurryLotNo = ref('NA');
const reportCoatingSampleQuantity = ref('NA');
const reportCoatingRemarks = ref('NA');
const reportCoatingCheckerOperator = ref('NA');
const reportCoatingTimeStart = ref(null);
const reportCoatingTimeFinish = ref(null);
const reportCoatingAmounts = ref([]);
const reportConcentrationValues = ref([]);
const reportCoatingModuleMaximum = ref(0);
const reportCoatingModuleMinimum = ref(0);
const reportCoatingModuleAverage = ref(0);

const reportHTFurnaceMachine = ref('NA');
const reportHTCycleNo = ref('NA');
const reportHTBatchCycleNo = ref('NA');
const reportHTPatternNo = ref('NA');
const reportHTDateStart = ref(null);
const reportHTTimeStart = ref(null);
const reportHTLoader = ref('NA');
const reportHTDateFinish = ref(null);
const reportHTTimeFinish = ref(null);
const reportHTUnloader = ref('NA');
const reportHTCyclePattern = ref('NA');
const reportHTCurrentPattern = ref('NA');
const reportHTRemarks = ref('NA');

const reportHT_MBLA = ref('NA');
const reportHT_MBLB = ref('NA');
const reportHT_MBLC = ref('NA');
const reportHT_MBLD = ref('NA');
const reportHT_MBLE = ref('NA');
const reportHT_MBLF = ref('NA');
const reportHT_MBLG = ref('NA');
const reportHT_MBLH = ref('NA');
const reportHT_MBLJ = ref('NA');
const reportHT_MBLK = ref('NA');

const reportOvenMachineNo = ref('NA');
const reportTimeLoading = ref('NA');
const reportTemperature_TimeLoading = ref('NA');
const reportDate_OvenInfo = ref(null);
const reportTimeUnloading = ref('NA');
const reportTemperature_TimeUnloading = ref('NA');
const reportShift_OvenInfo = ref('NA');
const reportOperator_OvenInfo = ref('NA');

/// to be put in the form end

const inspectionLength = ref('');
const inspectionWidth = ref('');
const inspectionThickness = ref('');
const inspectionMaterialGrade = ref('');
const inspectionMpiSampleQty = ref('');
const inspectionModel = ref('');
const inspectionBrStandard = ref('');
const inspectionBrStandard_higher = ref('');
const inspectionBrStandard_lower = ref('');
const inspectioniHcStandard = ref('');
const inspectioniHkStandard = ref('');
const inspectionOvenMachineNo = ref(0);
const inspectionTimeLoading = ref('');
const inspectionTemperature_TimeLoading = ref('');
const inspectionDate_OvenInfo = ref('');
const inspectionTimeUnloading = ref('');
const inspectionTemperature_TimeUnloading = ref('');
const inspectionShift_OvenInfo = ref('');
const inspectionOperator_OvenInfo = ref('');
const inspectionAutomotive = ref(0);

const tpmData_brAve = ref('');
const tpmData_brMax = ref('');
const tpmData_brMin = ref('');
const tpmData_ihcAve = ref('');
const tpmData_ihcMax = ref('');
const tpmData_ihcMin = ref('');
const tpmData_ihkAve = ref('');
const tpmData_ihkMax = ref('');
const tpmData_ihkMin = ref('');
const tpmData_ihr95Min = ref('');
const tpmData_ihr98Min = ref('');
const tpmData_tracerNo = ref('');

const jhCurveActualModel = ref('');

const fetchMaterialCode = ref('');
const fetchActualModel = ref('');
//remarks checking
const getAllBrNG = ref('');
const getAlliHcNG = ref('');
const getAlliHkNG = ref('');
const getAll4paildNG = ref('');
const getAll4pailsNG = ref('');
const getAll4pailaNG = ref('');
const getAllbHcNG = ref('');
const getAllBHMaxNG = ref('');
const getAllSquarenessNG = ref('');
const getAllDensityNG = ref('');
const getAlliHkiHcNG = ref('');
const getAllBr4paiNG = ref('');
const getAlliHr95NG = ref('');
const getAlliHr98NG = ref('');

const showModal = ref(false);

const coatingCompleted = ref(false);
const heatTreatmentCompleted = ref(false);

const massProd_qty = ref('');
const massProd_WT = ref('');
const massProd_boxNo = ref('');
const massProd_coating = ref('');
const massProd_rawMatCode = ref('');
const currentBoxIndex = ref(0);
const allBoxesData = ref([]);

const massProd_letter = ref(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K']);

const revert_boxLetter = () => {
    if (currentBoxIndex.value > 0) {
        currentBoxIndex.value--;
    }
};

const saveToTpmBoxes = async () => {
    try {
        await axios.patch(`/api/updateboxes/${currentSerialSelected.value}`, {
            layer_no: parseFloat(currentLayerName.value.replace(/[^0-9.]/g, '')),
            box_letter: massProd_letter.value[currentBoxIndex.value],
            quantity: massProd_qty.value,
            weight: massProd_WT.value,
            coating: massProd_coating.value,
            box_no: massProd_boxNo.value,
            raw_mat_code: massProd_rawMatCode.value,
        });

        // Reset inputs
        massProd_qty.value = '';
        massProd_WT.value = '';
        massProd_boxNo.value = '';
        massProd_coating.value = '';
        massProd_rawMatCode.value = '';

        // Step forward or close modal
        if (currentBoxIndex.value < massProd_letter.value.length - 1) {
            currentBoxIndex.value++;
        } else {
            showModal.value = false; // ✅ Replace the confirmation step
        }

        await getTpmBoxes(currentSerialSelected.value); // re-fetch to refresh

    } catch (error) {
        console.error("❌ Failed to save TPM Box:", error);
    }
};

const getTpmBoxes = async (serial) => {
    try {
        const responseTPMBoxes = await axios.get(`/api/tpmboxes/${serial}`);
        allBoxesData.value = responseTPMBoxes.data.data; // assign array directly
        console.log('getTpmBoxes response:', responseTPMBoxes.data);
    } catch (error) {
        console.error("Failed to fetch TPM Boxes:", error);
        isLoading.value = false;
    }
};

watch(showModal, (newVal) => {
    if (newVal && currentSerialSelected.value) {
        getTpmBoxes(currentSerialSelected.value);
    }
});


const modules = reactive([
    { name: 'M-01', new: '', homo: '', time: '', liters: '' },
    { name: 'M-02', new: '', homo: '', time: '', liters: '' },
    { name: 'M-03', new: '', homo: '', time: '', liters: '' },
    { name: 'M-04', new: '', homo: '', time: '', liters: '' },
    { name: 'M-05', new: '', homo: '', time: '', liters: '' },
    { name: 'M-06', new: '', homo: '', time: '', liters: '' },
]);

// Method to dynamically adjust font size based on string length
const getFontSize = (name) => {
    if (!name || name.trim() === '') {
        return '';  // Or 'text-[14px]' if you want a default size
    }

    const length = name.length;

    if (length <= 4) return 'text-[37px]';         // Very short names
    if (length === 5) return 'text-[30px]';
    if (length === 6) return 'text-[26px]';
    if (length === 7) return 'text-[24px]';
    if (length === 8) return 'text-[22px]';         // Mid point
    if (length === 9) return 'text-[21px]';
    if (length === 10) return 'text-[20px]';
    if (length === 11) return 'text-[19px]';
    if (length === 12) return 'text-[19px]';
    if (length === 13) return 'text-[18px]';
    return 'text-[14px]';                          // 12 or more characters
};

// Use computed to reactively return the right class when value changes
const preparedByPerson_firstNameFontSize = computed(() => {
  return getFontSize(preparedByPerson_firstName.value);
});

const preparedByPerson_lastNameFontSize = computed(() => {
  return getFontSize(preparedByPerson_lastName.value);
});

const exitReport = () => {
    //fetchAllData();
    //showReportData();
    showReportContent.value = false;
    showSelectionPanel.value = true;
    showReportProceedButtons.value = true;
    showReportMain.value = false;
    fetchSerial();
    reportReset();
}

const showReportButton = async () => {
    showReportProceedButtons.value = false;
    showReportLoading.value = true;
    await showReportData();
    // Poll until isReportDataReady is true
    const waitForFlag = () =>
        new Promise(resolve => {
        const check = () => {
            if (isReportDataReady.value) resolve();
            else setTimeout(check, 50); // check every 50ms
        };
        check();
        });

    await waitForFlag();
    showReportMain.value = true;
}
const confirmReset = ref(false);
const resetReportBHData = async() => {
    reportBH_data.value = 'NA';
    reportBH_dataStandard.value = 0;
    reportBH_remarks.value = 'NA';
    reportBH_samples.value = [];
    reportBH_sampleQty.value = 0;
    reportBH_temp.value = 0;
    reportBH_results.value = [];
    showBHData.value = false;
    showBHData_default.value = true;
    await saveReport();
};

watchEffect(() => {
  const data = reportCoatingAmounts.value.filter(n => typeof n === 'number' && !isNaN(n));

  if (data.length) {
    reportCoatingModuleMaximum.value = Math.max(...data);
    reportCoatingModuleMinimum.value = Math.min(...data);
    reportCoatingModuleAverage.value = (data.reduce((sum, val) => sum + val, 0) / data.length).toFixed(2);
  } else {
    reportCoatingModuleMaximum.value = 0;
    reportCoatingModuleMinimum.value = 0;
    reportCoatingModuleAverage.value = 0;
  }
});

// special judgement conditions logic

//FOR ROB - models ROB-0A70G
watchEffect(() => {
    if (showROB.value === true) {
        const belowStandard = (
            reportROB_BrRT_brMin.value < reportROB_BrRTstandard.value ||
            reportROB_BrRT_brMax.value < reportROB_BrRTstandard.value ||
            reportROB_BrRT_iHcMin.value < reportROB_BrRTstandard.value ||
            reportROB_BrRT_iHcMax.value < reportROB_BrRTstandard.value ||

            reportROB_BrVT_brMin.value < reportROB_BrVTstandard.value ||
            reportROB_BrVT_brMax.value < reportROB_BrVTstandard.value ||
            reportROB_BrVT_iHcMin.value < reportROB_BrVTstandard.value ||
            reportROB_BrVT_iHcMax.value < reportROB_BrVTstandard.value ||

            reportROB_HD5_brMin.value < reportROB_HD5standard.value ||
            reportROB_HD5_brMax.value < reportROB_HD5standard.value ||
            reportROB_HD5_iHcMin.value < reportROB_HD5standard.value ||
            reportROB_HD5_iHcMax.value < reportROB_HD5standard.value ||

            reportROB_JD5_brMin.value < reportROB_JD5standard.value ||
            reportROB_JD5_brMax.value < reportROB_JD5standard.value ||
            reportROB_JD5_iHcMin.value < reportROB_JD5standard.value ||
            reportROB_JD5_iHcMax.value < reportROB_JD5standard.value
        );

        if (belowStandard) {
            reportROB_remarks.value = 'NG';
            reportSMPJudgement.value = 'HOLD';
        } else {
            reportROB_remarks.value = 'OK';
            reportSMPJudgement.value = 'PASSED';
        }
    }
});

//FOR VT - models DNS-0A54G, MIE-0751G, MIS-0766G
watch(
  [reportVT_iHcResults, reportVT_iHc, reportVT_remarks, reportSMPJudgement, showVTData],
  () => {
    if (
      noteReasonForReject.value.includes('- N.G iHc') &&
      showVTData.value === true
    ) {
      if (reportVT_iHcResults.value.length == 0 || reportVT_iHcResults.value.some(sample => sample < reportVT_iHc.value)) {
        //console.log('One or more samples below threshold — NG');
        reportVT_remarks.value = 'NG';
        reportSMPJudgement.value = 'REJECT';
      } else {
        //console.log('All samples OK');
        reportVT_remarks.value = 'OK';
        reportSMPJudgement.value = 'HOLD';
      }
    } else {
      //console.warn('Conditions not met: skipping check');
    }
  },
  { immediate: true }
);

//For Data 1x1x1 without corner - models AAW-0935G, AAW-0934G
watch(
  [reportSurface_cpk, reportCore_cpk, reportSurface_remarks, reportCore_remarks, reportSMPJudgement, show1x1x1Data_withoutCorner],
  () => {
    //console.log('Watch triggered for 1x1x1 without corner evaluation');
    //console.log('noteReasonForReject:', noteReasonForReject.value);
    //console.log('show1x1x1Data_withoutCorner:', show1x1x1Data_withoutCorner.value);
    //console.log('reportSurface_cpk:', reportSurface_cpk.value);
    //console.log('reportCore_cpk:', reportCore_cpk.value);

    if (
      noteReasonForReject.value.includes('- N.G iHc') &&
      show1x1x1Data_withoutCorner.value === true && reportSurface_cpk.value !== null && reportCore_cpk.value !== null
    ) {
      if (reportSurface_cpk.value < cpkStandardValue.value || reportCore_cpk.value < cpkStandardValue.value) {
        //console.log('Surface and Core CPK below 1.33 — setting NG/REJECT');
        reportSurface_remarks.value = 'NG';
        reportCore_remarks.value = 'NG';
        reportSMPJudgement.value = 'REJECT';
      } else {
        //console.log('Surface and Core CPK 1.33 or higher — setting OK/HOLD');
        reportCore_remarks.value = 'OK';
        reportSurface_remarks.value = 'OK';
        reportSMPJudgement.value = 'HOLD';
      }
    } else {
      //console.log('Conditions not met for 1x1x1 CPK check — skipping');
    }
  },
  { immediate: true }
);

watch(
  [reportCpkFrom_iHc_Cpk, reportCpkFrom_iHc_remarks, reportSMPJudgement, showCpkFrom_iHc],
  () => {
    //console.log('Watch triggered for CPK from iHc evaluation');
    //console.log('noteReasonForReject:', noteReasonForReject.value);
    //console.log('showCpkFrom_iHc:', showCpkFrom_iHc.value);
    //console.log('reportCpkFrom_iHc_Cpk:', reportCpkFrom_iHc_Cpk.value);
    //console.log('cpkStandardValue:', cpkStandardValue.value);

    if (
      noteReasonForReject.value.includes('- N.G iHc') &&
      showCpkFrom_iHc.value === true && reportCpkFrom_iHc_Cpk.value !== null
    ) {
      if (reportCpkFrom_iHc_Cpk.value < cpkStandardValue.value) {
        //console.log('reportCpkFrom_iHc_Cpk is below standard — setting NG/REJECT');
        reportCpkFrom_iHc_remarks.value = 'NG';
        reportSMPJudgement.value = 'REJECT';
      } else {
        //console.log('reportCpkFrom_iHc_Cpk meets or exceeds standard — setting OK/HOLD');
        reportCpkFrom_iHc_remarks.value = 'OK';
        reportSMPJudgement.value = 'HOLD';
      }
    } else {
      //console.log('Conditions not met for CPK from iHc check — skipping');
    }
  },
  { immediate: true }
);

//FOR GX - models
watch(
  [reportGX_iHcMinimum, reportGX_iHcStandard, reportSMPJudgement, showGX, reportGX_iHcAverage, reportGX_iHcMaximum],
  () => {
    //console.log('Watch triggered for GX iHc evaluation');
    //console.log('showGX:', showGX.value);
    //console.log('reportGX_iHcMinimum:', reportGX_iHcMinimum.value);
    //console.log('reportGX_iHcStandard:', reportGX_iHcStandard.value);

    if (noteReasonForReject.value.includes('- N.G iHc') && showGX.value === true &&
    reportGX_iHcMinimum.value !== null && reportGX_iHcStandard.value !== null) {
      if (reportGX_iHcMinimum.value < reportGX_iHcStandard.value) {
        //console.log('GX iHc minimum is below standard — setting NG/REJECT');
        reportSMPJudgement.value = 'REJECT';
      } else {
        //console.log('GX iHc minimum meets or exceeds standard — setting OK/HOLD');
        reportSMPJudgement.value = 'HOLD';
      }
    } else {
      //console.log('GX display not active — skipping GX iHc check');
    }
  },
  { immediate: true }
);

// FOR BH - models
watch(
  [reportBH_results, reportBH_dataStandard, reportSMPJudgement, reportBH_remarks, showBHData],
  () => {
    if (
      noteReasonForReject.value.includes('- N.G iHc') &&
      showBHData.value === true
    ) {
      if (
        reportBH_results.value.length === 0 ||
        reportBH_results.value.some(sample => sample < reportBH_dataStandard.value)
      ) {
        reportBH_remarks.value = 'NG';
        reportSMPJudgement.value = 'REJECT';
      } else {
        reportBH_remarks.value = 'OK';
        reportSMPJudgement.value = 'HOLD';
      }
    } else {
      //console.warn('Conditions not met: skipping BH check');
    }
  },
  { immediate: true }
);
//For TTM Models
watch(
  [reportCpk, reportCpkRemarks, reportSurface_cpk, reportCore_cpk, reportCorner_cpk, reportSMPJudgement, reportSurface_remarks, reportCore_remarks, reportCorner_remarks, isTTM_model, show1x1x1Data_withoutCorner, show1x1x1Data_Corner],
  () => {
    //console.log('Watch triggered for isTTM_model or reportCpk');
    //console.log('isTTM_model:', isTTM_model.value);
    //console.log('reportCpk:', reportCpk.value);


    if (isTTM_model.value === true && reportCpk.value !== null) {
        if (reportCpk.value < 1.00) {
            //console.log('reportCpk is below 1.00 — setting NG/REJECT');
            reportCpkRemarks.value = 'NG';
        } else {
            //console.log('reportCpk is 1.00 or higher — setting OK/HOLD');
            reportCpkRemarks.value = 'OK';
        }

        if(noteReasonForReject.value.includes('- N.G iHc') && show1x1x1Data_withoutCorner.value === true && show1x1x1Data_Corner.value === false){
            if(reportSurface_cpk.value < cpkStandardValue.value || reportCore_cpk.value < cpkStandardValue.value){
            //console.log('Surface and Core CPK below 1.33 — setting NG/REJECT');
            reportSurface_remarks.value = 'NG';
            reportCore_remarks.value = 'NG';
            reportSMPJudgement.value = 'REJECT';
            }else{
                //console.log('Surface and Core CPK 1.33 or higher — setting OK/HOLD');
                reportCore_remarks.value = 'OK';
                reportSurface_remarks.value = 'OK';
                reportSMPJudgement.value = 'HOLD';
            }
        }else if(noteReasonForReject.value.includes('- N.G iHc') && show1x1x1Data_withoutCorner.value === true && show1x1x1Data_Corner.value === true){
            if(reportCorner_cpk.value < cpkStandardValue.value || reportSurface_cpk.value < cpkStandardValue.value || reportCore_cpk.value < cpkStandardValue.value){
                //console.log('Surface, Core and Corner CPK below 1.33 — setting NG/REJECT');
                reportSurface_remarks.value = 'NG';
                reportCore_remarks.value = 'NG';
                reportCorner_remarks.value = 'NG';
                reportSMPJudgement.value = 'REJECT';
            }else{
                //console.log('Surface, Core and Corner CPK 1.33 or higher — setting OK/HOLD');
                reportCore_remarks.value = 'OK';
                reportSurface_remarks.value = 'OK';
                reportCorner_remarks.value = 'OK';
                reportSMPJudgement.value = 'HOLD';
            }
        }
    } else {
      //console.log('isTTM_model is false — skipping TTM CPK check');
    }
  },
  { immediate: true }
);


// special judgement conditions logic end

const reportReset = () => {
    reportRemarksDisplayNG_ihcihk.value = false;
    reportRemarksDisplayNG_br4pia.value = false;
    reportRemarksDisplayNG_bhMax.value = false;
    reportRemarksDisplayNG_bhc.value = false;
    isTTM_model.value = false;
    isAutomotive.value = false;
    noteReasonForReject.value = [];
    show1x1x1Data_Corner.value = false;
    show1x1x1Data_withoutCorner.value = false;
    showBHData.value = false;
    showGX.value = false;
    showVTData.value = false;
    showCpkFrom_iHc.value = false;
    preparedByStampConfirmation.value = false;
    checkedByStampConfirmation.value = false;
    preparedByStampPhoto.value = false;
    checkedByStampPhoto.value = false;
    approvedByStampPhoto.value = false;
    report_isFinalized.value = false;
    reportExistingSMPJudgement.value = null;
}

const showNotification = (message) => {
    // Show notification and set the message
    showNotif.value = true;
    reportNotificationMessage.value = message;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        showNotif.value = false;
    }, 2000);  // 2000ms = 3 seconds
}

const showNotification2 = async(message) => {
    // Show notification and set the message
    showNotif2.value = true;
    reportNotificationMessage.value = message;
    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        showNotif2.value = false;
        if(isFromApproval.value){
            backToApproval.value = true;
            uncheckReport();
        }
    }, 2000);  // 2000ms = 2 seconds
}

const uncheckReport = async () => {
    try {
        const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, {
            "checked": 0
        });
        //console.log("Patched checked report data: ", response.data);
    } catch (error) {
        console.error("Patch data checking report error:", error);
    }
}

const reportErrorMessage = ref('');

const generateReport = async () => {
    try {
        showReportLoading.value = true;
        showReportContent.value = true;
        showSelectionPanel.value = false;

        await fetchAllData();
        showReportProceedButtons.value = false;
        await showReportData();

        const waitForFlag = (timeout = 8000) => { // 8s timeout
            return new Promise((resolve, reject) => {
                const start = Date.now();
                const check = () => {
                    if (isReportDataReady.value) return resolve();
                    if (Date.now() - start >= timeout) {
                        console.error("Timeout: Report data not ready");
                        return reject(new Error("Timeout waiting for report data"));
                    }
                    setTimeout(check, 50);
                };
                check();
            });
        };

        await waitForFlag();
        await new Promise(r => setTimeout(r, 50)); // tiny tick delay
        showReportLoading.value = false;
        showReportMain.value = true;

    } catch (error) {
        showReportLoading.value = false;
        reportErrorMessage.value = "Failed to generate report. Please try again.";
        setTimeout(() => {
            reportErrorMessage.value = '';
            showReportContent.value = false;
            showSelectionPanel.value = true;
        }, 2000); // Clear after 3 seconds
        console.error("generateReport failed:", error.message);
        // Optional: show user an error message
    }
};

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

    const model = jhCurveActualModel.value;

    const hasNGihc = noteReasonForReject.value.includes('- N.G iHc');
    isTTM_model.value = jhCurveActualModel.value.includes("TTM");
    if (!hasNGihc) return;

    // === Logic Blocks ===

    if (MODELS_SHOW_VT_DATA.value.includes(model) && reportVT_samplesQty.value > 0) { //reportBH_sampleQty
        showVTData.value = true;
        showVTData_default.value = false;
    }else if(MODELS_SHOW_VT_DATA.value.includes(model)){
        showVTData.value = false;
        showVTData_default.value = true;
    }

    if (MODELS_SHOW_BH.value.includes(model) && reportBH_sampleQty.value > 0) {
        showBHData.value = true;
        showBHData_default.value = false;
    }else if(MODELS_SHOW_BH.value.includes(model)){
        showBHData.value = false;
        showBHData_default.value = true;
    }

    if (model.includes("TTM")) {
        show1x1x1Data_withoutCorner.value = true;
        show1x1x1Data_Corner.value = true;

        if (model.includes("TTM") && MODELS_1X1X1_NO_CORNER.value.includes(model)) {
            show1x1x1Data_Corner.value = false;
        }
    }

    if (MODELS_SHOW_CPK.value.includes(model))  showCpkFrom_iHc.value = true;
    if (MODELS_SHOW_GX.value.includes(model))   showGX.value = true;
    if (MODELS_SHOW_ROB.value.includes(model))  showROB.value = true;
};

const autoCheckRemarks = () => {
    //console.log("AUTOCHECK REMARKS WAS ENTERED!")
    if(reportiHcVariance.value > 1500 || tpmDataQuantity.value > 5 || withAdditionalSampleForRemarks.value == true){
        reportRemarks2.value = 'With Additional Samples';
        //console.log("withAdditionalSampleForRemarks is currently: ", withAdditionalSampleForRemarks.value);
    }

    if(reportiHcVariance.value > 1500){
        reportRemarks3.value = 'With iHc Variance above 1500 Oe';
    }
}

const fetchAllData = async () => {
    try {
        const serial = currentSerialSelected.value;
        if (!serial) throw new Error("No serial selected.");

        const responseTpm = await axios.get(`/api/tpmdata?serial=${serial}`);

        // Unwrap nested array if exists
        let rawData = responseTpm.data?.[0];
        if (Array.isArray(rawData)) {
            rawData = rawData[0];
        }
        const modelData = responseTpm.data?.data;

        if (!rawData || !modelData?.length) throw new Error("TPM data missing or malformed.");
        if (!rawData.average || !rawData.minimum || !rawData.maximum) {
            throwError("Missing average/min/max data.");
        }

        tpmData.value = rawData;
        tpmDataQuantity.value = modelData.length;
        //console.log('TPM TOTAL -> ',tpmDataQuantity.value);
        getTpmModel.value = modelData;

        fetchMaterialCode.value = modelData[0].code_no || throwError("Missing material code.");
        jhCurveActualModel.value = modelData[0].category?.actual_model || throwError("Missing actual model.");
        fetchActualModel.value = jhCurveActualModel.value;

        setJudgmentFlags(modelData);

        await resolveFurnaceAndLayer(modelData[0]);

        parseAggregates(rawData);

        const modelMatched = await matchInspectionModel(fetchActualModel.value);
        if (!modelMatched) return;

        // Build the patch data object
        const repData = {
            length: inspectionLength.value,
            magnetic_property_data: JSON.stringify({
                brStandard: inspectionBrStandard.value,
                brAverage: tpmData_brAve.value,
                brMaximum: tpmData_brMax.value,
                brMinimum: tpmData_brMin.value,
                ihcStandard: inspectioniHcStandard.value,
                ihcAverage: tpmData_ihcAve.value,
                ihcMaximum: tpmData_ihcMax.value,
                ihcMinimum: tpmData_ihcMin.value,
                ihkStandard: inspectioniHkStandard.value,
                ihkAverage: tpmData_ihkAve.value,
                ihkMaximum: tpmData_ihkMax.value,
                ihkMinimum: tpmData_ihkMin.value,
                ihr95Minimum: tpmData_ihr95Min.value,
                ihr98Minimum: tpmData_ihr98Min.value
            }),
            material_grade: inspectionMaterialGrade.value,
            material_code: fetchMaterialCode.value,
            model: fetchActualModel.value,
            mpi_sample_quantity: inspectionMpiSampleQty.value,
            pulse_tracer_machine_number: tpmData_tracerNo.value,
            thickness: inspectionThickness.value,
            width: inspectionWidth.value,
            withCarmark: inspectionAutomotive.value,
        };

        await createReport(repData, serial);



        isLoading.value = false;

    } catch (error) {
        console.error("fetchAllData failed:", error);
        showNotification2("Data is incomplete for this serial. Please rerun the data.");
        exitReport();
        throw new Error("fetchAllData returned empty or invalid payload");
    }
};

// SUPPORT FUNCTIONS

const throwError = (msg) => { throw new Error(msg); };

const setJudgmentFlags = (models) => {
    const flag = (arr) => arr.includes("1");

    const extract = (key) => models.map(m => m.remark?.[key] || null);

    getAllBrNG.value         = extract("Br_remarks");
    getAlliHcNG.value        = extract("iHc_remarks");
    getAlliHkNG.value        = extract("iHk_remarks");
    getAll4paildNG.value     = extract("4paild_remarks");
    getAll4pailsNG.value     = extract("4pails_remarks");
    getAll4pailaNG.value     = extract("4paila_remarks");
    getAllbHcNG.value        = extract("bHc_remarks");
    getAllBHMaxNG.value      = extract("BHMax_remarks");
    getAllSquarenessNG.value = extract("Squareness_remarks");
    getAllDensityNG.value    = extract("Density_remarks");
    getAlliHkiHcNG.value     = extract("iHkiHc_remarks");
    getAllBr4paiNG.value     = extract("Br4pai_remarks");
    getAlliHr95NG.value      = extract("iHr95_remarks");
    getAlliHr98NG.value      = extract("iHr98_remarks");

    const primary = flag(getAllBrNG.value) || flag(getAlliHr95NG.value) || flag(getAlliHr98NG.value)
        || flag(getAllSquarenessNG.value) || flag(getAllDensityNG.value) || flag(getAlliHkiHcNG.value)
        || flag(getAllBr4paiNG.value) || flag(getAll4paildNG.value) || flag(getAll4pailsNG.value)
        || flag(getAll4pailaNG.value) || flag(getAllbHcNG.value);

    if (primary) {
        reportRemarksDisplay.value = "E";
        reportSMPJudgement.value = (flag(getAlliHcNG.value) || flag(getAlliHkNG.value) || flag(getAllBHMaxNG.value))
            ? "REJECT"
            : "HOLD";
    } else {
        reportRemarksDisplay.value = "OK";
        reportSMPJudgement.value = "PASSED";
    }
};

const resolveFurnaceAndLayer = async (model) => {
    const [layerRes, furnaceRes] = await Promise.all([
        axios.get(`/api/layerdata/`),
        axios.get(`/api/furnacedata/`)
    ]);

    const layerData = layerRes.data?.data?.["Layer Data"];
    const furnaceData = furnaceRes.data?.data?.["Furnace Data"];
    if (!layerData || !furnaceData) throwError("Layer or Furnace data missing.");

    const furnace = furnaceData.find(f => f.furnace_id === model.furnace_id);
    const layer = layerData.find(l => l.layer_no == model.layer_no && l.furnace_id === model.furnace_id);

    if (!furnace) throwError("Furnace not found.");
    if (!layer) throwError("Layer not found.");

    currentFurnaceName.value = furnace.furnace_name;
    currentLayerName.value = layer.layer_name;

    tpmData_tracerNo.value = model.Tracer;
};

const parseAggregates = (raw) => {
    if (!raw.average || !raw.minimum || !raw.maximum) throwError("Missing average/min/max data.");

    const avg = JSON.parse(raw.average);
    const min = JSON.parse(raw.minimum);
    const max = JSON.parse(raw.maximum);

    tpmData_brAve.value = avg.Br;
    tpmData_brMax.value = max.Br;
    tpmData_brMin.value = min.Br;

    tpmData_ihcAve.value = avg.iHc;
    tpmData_ihcMax.value = max.iHc;
    tpmData_ihcMin.value = min.iHc;

    tpmData_ihkAve.value = avg.iHk;
    tpmData_ihkMax.value = max.iHk;
    tpmData_ihkMin.value = min.iHk;

    tpmData_ihr95Min.value = min.Hr95;
    tpmData_ihr98Min.value = min.Hr98;
};

const matchInspectionModel = async (model) => {
    const res = await axios.get("/api/inspectiondata");
    const data = res.data?.data || [];
    inspectionDataList.value = data;

    const found = data.find(item => item.model === model);
    if (!found) {
        showNotification2("The specified model does not exist in the inspection data. Please create the necessary inspection data first in the Inspection section of the website.");
        showReportContent.value = false;
        showSelectionPanel.value = true;
        return false; // prevent further execution
    }

    inspectionBrStandard.value = found.br;
    inspectioniHcStandard.value = found.ihc;
    inspectioniHkStandard.value = found.ihk;
    inspectionLength.value = found.length;
    inspectionWidth.value = found.width;
    inspectionThickness.value = found.thickness;
    inspectionMaterialGrade.value = found.material_grade;
    inspectionMpiSampleQty.value = found.mpi_sample;
    inspectionOvenMachineNo.value = found.oven_machine_no;
    inspectionTimeLoading.value = found.time_loading;
    inspectionTemperature_TimeLoading.value = found.temperature_1;
    inspectionDate_OvenInfo.value = found.date;
    inspectionTimeUnloading.value = found.time_unloading;
    inspectionTemperature_TimeUnloading.value = found.temperature_2;
    inspectionShift_OvenInfo.value = found.shift;
    inspectionOperator_OvenInfo.value = found.operator;
    inspectionAutomotive.value = found.is_automotive;

    // Parse br bounds
    if (inspectionBrStandard.value.includes("~")) {
        const [lower, upper] = inspectionBrStandard.value.split("~");
        inspectionBrStandard_lower.value = lower;
        inspectionBrStandard_higher.value = upper;
    }

    return true;
};
const createReport = async (reportData, serial) => {
    try {
        //console.log("Rep Data: ",reportData);
        const response = await axios.patch(`/api/reportdata/${serial}`, reportData);
        //console.log("Create report function Patched report data: ", response.data);
    } catch (error) {
        console.error("Patch report data Error:", error);
    }
}

const showReportData = async () => {
    try {
        isReportDataReady.value = false;
        await getTpmBoxes(currentSerialSelected.value);
        const response = await axios.get(`/api/reportdata/`);
        //console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        //console.log("Filtered data: ", filterBySerial);

        const responseCoating = await axios.get(`/api/coating-data/`);
        //console.log('get responseCoatingData: ', responseCoating.data);
        const coating_filterBySerial = responseCoating.data.filter(column => column.serial == currentSerialSelected.value); // filter by serial
        console.log("Filtered coating data: ", coating_filterBySerial);
        const c = coating_filterBySerial[0];

        const responseHeatTreatment = await axios.get(`/api/heat-treatment-data/`);
        //console.log('get responseHeatTreatmentData: ',responseHeatTreatment.data);
        const heatTreatment_filterBySerial = responseHeatTreatment.data.filter(column => column.serial == currentSerialSelected.value); // filter by serial
        console.log("Filtered heat treatment data: ", heatTreatment_filterBySerial);
        const ht = heatTreatment_filterBySerial[0];

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
        reportRemarks2.value = filterBySerial[0].remarks2;
        reportRemarks3.value = filterBySerial[0].remarks3;
        reportExistingSMPJudgement.value = filterBySerial[0].smp_judgement;
        modifiedSMPJudgement.value = filterBySerial[0].modified_smp_judgement;
        preparedByPerson.value = filterBySerial[0].prepared_by;
        checkedByPerson.value = filterBySerial[0].checked_by;
        approvedByPerson.value = filterBySerial[0].approved_by;
        reportPreparedByDate.value = filterBySerial[0].prepared_by_date
        ? filterBySerial[0].prepared_by_date.split(' ')[0]
        : '';
        reportCheckedByDate.value = filterBySerial[0].checked_by_date
        ? filterBySerial[0].checked_by_date.split(' ')[0]
        : '';
        reportApprovedByDate.value = filterBySerial[0].approved_by_date
        ? filterBySerial[0].approved_by_date.split(' ')[0]
        : '';

        report_isFinalized.value = filterBySerial[0].is_finalized == 1;
        console.log("Report is finalized: ", report_isFinalized.value);

        isAutomotive.value = filterBySerial[0].withCarmark == 1;
        //console.log("With carmark value: ",isAutomotive.value);
        coatingCompleted.value = filterBySerial[0].coating_completed == 1;
        //console.log("Coating completed value: ",coatingCompleted.value);
        heatTreatmentCompleted.value = filterBySerial[0].heat_treatment_completed == 1;
        //console.log("Heat treatment completed value: ",heatTreatmentCompleted.value);

        //Request to AUTO 'N/A' all oven fields if no oven - 5/31/2025

        reportOvenMachineNo.value = filterBySerial[0].oven_machine_no;
        reportTimeLoading.value = filterBySerial[0].time_loading;
        reportTimeUnloading.value = filterBySerial[0].time_unloading;
        reportTemperature_TimeLoading.value = filterBySerial[0].temp_time_loading;
        reportTemperature_TimeUnloading.value = filterBySerial[0].temp_time_unloading;
        reportDate_OvenInfo.value = filterBySerial[0].date_oven_info;
        reportShift_OvenInfo.value = filterBySerial[0].shift_oven_info;
        reportOperator_OvenInfo.value = filterBySerial[0].operator_oven_info;


        reportStdDev.value = filterBySerial[0].std_dev;
        //console.log('reportStdDev:', reportStdDev.value);
        reportCp.value = filterBySerial[0].cp;
        //console.log('reportCp:', reportCp.value);
        reportCpk.value = filterBySerial[0].cpk;
        //console.log('reportCpk:', reportCpk.value);
        reportCpkRemarks.value = filterBySerial[0].br_cpk_remarks;
        //console.log('reportCpkRemarks:', reportCpkRemarks.value);

        const noteRejectReasons = JSON.parse(filterBySerial[0].note_reason_reject);
        //console.log("Parsed noteRejectReasons from DB:", noteRejectReasons);

        if (noteRejectReasons && Array.isArray(noteRejectReasons)) {
            noteReasonForReject.value = []; // Clear existing values
            noteRejectReasons.forEach(reason => {
                noteReasonForReject.value.push(reason);
            });
        } else {
            console.warn("No valid noteRejectReasons found (null or not an array).");
        }

        //console.log("Model value: ",reportModel.value);

        //console.log("Report Data Model", reportModel.value);

        const magneticProperty = JSON.parse(filterBySerial[0].magnetic_property_data);
        //console.log("Magnetic Property: ", magneticProperty);
         // Extracting BR, IHC, and IHK properties

        // BR values
        reportBrStandard.value = inspectionBrStandard.value;
        reportBrAverage.value = magneticProperty.brAverage;
        reportBrMaximum.value = magneticProperty.brMaximum;
        reportBrMinimum.value = magneticProperty.brMinimum;

        // IHC values
        reportihcStandard.value = inspectioniHcStandard.value;
        reportihcAverage.value = magneticProperty.ihcAverage;
        reportihcMaximum.value = magneticProperty.ihcMaximum;
        reportihcMinimum.value = magneticProperty.ihcMinimum;

        // IHK values
        reportihkStandard.value = inspectioniHkStandard.value;
        reportihkAverage.value = magneticProperty.ihkAverage;
        reportihkMaximum.value = magneticProperty.ihkMaximum;
        reportihkMinimum.value = magneticProperty.ihkMinimum;

        //IHR95 and IHR98 value
        reportihr95Minimum.value = magneticProperty.ihr95Minimum;
        reportihr98Minimum.value = magneticProperty.ihr98Minimum;

        // Convert strings to numbers before performing subtraction
        reportBrVariance.value = parseFloat(reportBrMaximum.value) - parseFloat(reportBrMinimum.value);
        reportiHcVariance.value = parseFloat(reportihcMaximum.value) - parseFloat(reportihcMinimum.value);
        reportiHkVariance.value = parseFloat(reportihkMaximum.value) - parseFloat(reportihkMinimum.value);

        const oneby = JSON.parse(filterBySerial[0].data_1x1x1_info || '{}');
        const VT = JSON.parse(filterBySerial[0].data_VT_info || '{}');
        //console.log("VT Data: ",VT);
        const iHc_cpk = JSON.parse(filterBySerial[0].data_iHc_cpk_info || '{}');
        const GX = JSON.parse(filterBySerial[0].data_GX_info || '{}');
        const bh = JSON.parse(filterBySerial[0].data_bh_info || '{}');
        const ROB = JSON.parse(filterBySerial[0].data_ROB_info || '{}');

        reportCorner.value = oneby.corner || '';
        reportCorner_average.value = oneby.corner_average || '';
        reportCorner_maximum.value = oneby.corner_maximum || '';
        reportCorner_minimum.value = oneby.corner_minimum || '';
        reportCorner_cpk.value = oneby.corner_cpk || '';
        reportCorner_remarks.value = oneby.corner_remarks || '';
        reportSurface.value = oneby.surface || '';
        reportSurface_average.value = oneby.surface_average || '';
        reportSurface_maximum.value = oneby.surface_maximum || '';
        reportSurface_minimum.value = oneby.surface_minimum || '';
        reportSurface_cpk.value = oneby.surface_cpk || '';
        reportSurface_remarks.value = oneby.surface_remarks || '';
        reportCore.value = oneby.core || '';
        reportCore_average.value = oneby.core_average || '';
        reportCore_maximum.value = oneby.core_maximum || '';
        reportCore_minimum.value = oneby.core_minimum || '';
        reportCore_cpk.value = oneby.core_cpk || '';
        reportCore_remarks.value = oneby.core_remarks || '';

        reportVT_samples.value = Array.isArray(VT.sample) ? VT.sample : [];
        //console.log("reportVT_samples:", reportVT_samples.value);
        reportVT_sampleRemarks.value = Array.isArray(VT.sample_remarks) ? VT.sample_remarks : [];
        //console.log("reportVT_sampleRemarks:", reportVT_sampleRemarks.value);
        reportVT_iHcResults.value = Array.isArray(VT.iHcResult) ? VT.iHcResult : [];
        //console.log("reportVT_iHcResults:", reportVT_iHcResults.value);
        reportVT_samplesQty.value = VT.sample_qty ?? 0;
        //console.log("reportVT_samplesQty:", reportVT_samplesQty.value);
        reportVT_temp.value = VT.temp ?? '';
        //console.log("reportVT_temp:", reportVT_temp.value);
        reportVT_iHc.value = VT.iHc ?? '';
        //console.log("reportVT_iHc:", reportVT_iHc.value);
        reportVT_remarks.value = VT.remarks ?? '';
        //console.log("reportVT_remarks:", reportVT_remarks.value);

        reportCpkFrom_iHc_StdDev.value = iHc_cpk.std_dev || '';
        reportCpkFrom_iHc_Cpk.value = iHc_cpk.cpk || '';
        reportCpkFrom_iHc_remarks.value = iHc_cpk.remarks || '';

        reportGX_iHcStandard.value = GX.iHcStandard || 0;
        reportGX_iHcAverage.value = GX.iHcAverage || 0;
        reportGX_iHcMaximum.value = GX.iHcMaximum || 0;
        reportGX_iHcMinimum.value = GX.iHcMinimum || 0;
        reportGX_iHcVariance.value = (GX.iHcMaximum - GX.iHcMinimum) || 0;
        reportGX_iHkAverage.value = GX.iHkAverage || 0;
        reportGX_iHkMaximum.value = GX.iHkMaximum || 0;
        reportGX_iHkMinimum.value = GX.iHkMinimum || 0;
        reportGX_iHkVariance.value = (GX.iHkMaximum - GX.iHkMinimum) || 0;

        reportBH_data.value = bh.data || '';
        reportBH_dataStandard.value = bh.dataStandard || '';
        reportBH_remarks.value = bh.remarks || '';
        reportBH_samples.value = Array.isArray(bh.sample) ? bh.sample : [];
        reportBH_sampleQty.value = bh.sample_qty ?? 0;
        reportBH_temp.value = bh.temp || '';
        reportBH_results.value = Array.isArray(bh.result) ? bh.result : [];

        reportROB_brMin.value = ROB.brMin || '';
        reportROB_brMax.value = ROB.brMax || '';
        reportROB_iHcMin.value = ROB.iHcMin || '';
        reportROB_iHcMax.value = ROB.iHcMax || '';
        reportROB_BrRT_brMin.value = ROB.brRT_brMin || '';
        reportROB_BrRT_brMax.value = ROB.brRT_brMax || '';
        reportROB_BrRT_iHcMin.value = ROB.brRT_iHcMin || '';
        reportROB_BrRT_iHcMax.value = ROB.brRT_iHcMax || '';
        reportROB_BrVT_brMax.value = ROB.brVT_brMax || '';
        reportROB_BrVT_brMin.value = ROB.brVT_brMin || '';
        reportROB_BrVT_iHcMax.value = ROB.brVT_iHcMax || '';
        reportROB_BrVT_iHcMin.value = ROB.brVT_iHcMin || '';
        reportROB_HD5_brMax.value = ROB.HD5_brMax || '';
        reportROB_HD5_brMin.value = ROB.HD5_brMin || '';
        reportROB_HD5_iHcMax.value = ROB.HD5_iHcMax || '';
        reportROB_HD5_iHcMin.value = ROB.HD5_iHcMin || '';
        reportROB_JD5_brMax.value = ROB.JD5_brMax || '';
        reportROB_JD5_brMin.value = ROB.JD5_brMin || '';
        reportROB_JD5_iHcMax.value = ROB.JD5_iHcMax || '';
        reportROB_JD5_iHcMin.value = ROB.JD5_iHcMin || '';
        reportROB_remarks.value = ROB.result || '';

        //Coating & Heat Treatment update
        //COATING ---
        reportCoatingDate.value = c.date ?? null;
        reportCoatingMachineNo.value = c.machine_no ?? 'NA';
        reportCoatingSlurryLotNo.value = c.slurry_lot_no ?? 'NA';
        reportCoatingLoaderOperator.value = c.loader_operator ?? 'NA';
        reportCoatingMinTbContent.value = c.min_tb_content ?? 'NA';
        reportCoatingSampleQuantity.value = c.sample_qty ?? 'NA';
        reportCoatingTotalMagnetWeight.value = c.total_magnet_weight ?? 'NA';
        reportCoatingUnloaderOperator.value = c.unloader_operator ?? 'NA';
        reportCoatingCheckerOperator.value = c.checker_operator ?? 'NA';
        reportCoatingTimeStart.value = c.time_start ?? null;
        reportCoatingTimeFinish.value = c.time_finish ?? null;
        reportCoatingModuleMaximum.value = c.maximum ?? 0;
        reportCoatingModuleMinimum.value = c.minimum ?? 0;
        reportCoatingModuleAverage.value = c.average ?? 0;
        reportCoatingRemarks.value = c.remarks ?? 'NA';
        const rawDataCoating = (c.coating_data && typeof c.coating_data === 'string')
            ? JSON.parse(c.coating_data)
            : {};

        reportCoatingAmounts.value = Array(30).fill(null);
        reportConcentrationValues.value = Array(30).fill(null);

        for (let i = 0; i < 30; i++) {
            const index = i + 1;
            const mLabel = `M${((i % 5) + 1).toString().padStart(2, '0')}`;

            const amountKey = `${index}_${mLabel}_Amount`;
            const concentrationKey = `${index}_${mLabel}_Concentration`;

            reportCoatingAmounts.value[i] = rawDataCoating[amountKey] ?? null;
            reportConcentrationValues.value[i] = rawDataCoating[concentrationKey] ?? null;
        }

        // M06: starts at index 35
        const M06_START_INDEX = 35;

        for (let i = 0; i < 12; i++) {
            const groupStart = (Math.floor(i / 2) * 5) + 1;
            const groupEnd = groupStart + 4;
            const key = `${groupStart}to${groupEnd}_M06_${i + 1}_Concentration`;

            reportConcentrationValues.value[M06_START_INDEX + i] = rawDataCoating[key] ?? null;
        }

        modules.forEach((mod) => {
            const key = mod.name.toLowerCase(); // e.g., "m-01" — KEEP dash
            mod.new    = rawDataCoating[`add_slurry_${key}_new`]    ?? '';
            mod.homo   = rawDataCoating[`add_slurry_${key}_homo`]   ?? '';
            mod.time   = rawDataCoating[`add_slurry_${key}_time`]   ?? '';
            mod.liters = rawDataCoating[`add_slurry_${key}_liters`] ?? '';
        });

        //HEAT TREATMENT ---

        reportHTFurnaceMachine.value = ht.furnace_machine ?? 'NA';
        reportHTCycleNo.value = ht.cycle_no ?? 'NA';
        reportHTBatchCycleNo.value = ht.batch_cycle_no ?? 'NA';
        reportHTPatternNo.value = ht.pattern_no ?? 'NA';
        reportHTDateStart.value = ht.date_start ?? null;
        reportHTTimeStart.value = ht.time_start ?? null;
        reportHTLoader.value = ht.loader ?? 'NA';
        reportHTDateFinish.value = ht.date_finish ?? null;
        reportHTTimeFinish.value = ht.time_finish ?? null;
        reportHTUnloader.value = ht.unloader ?? 'NA';
        reportHTCyclePattern.value = ht.cycle_pattern ?? 'NA';
        reportHTCurrentPattern.value = ht.current_pattern ?? 'NA';
        reportHTRemarks.value = ht.remarks ?? 'NA';
        const rawDataHT = (ht.magnet_box_location && typeof ht.magnet_box_location === 'string')
            ? JSON.parse(ht.magnet_box_location)
            : {};

        reportHT_MBLA.value = rawDataHT.box_a ?? 'NA';
        reportHT_MBLB.value = rawDataHT.box_b ?? 'NA';
        reportHT_MBLC.value = rawDataHT.box_c ?? 'NA';
        reportHT_MBLD.value = rawDataHT.box_d ?? 'NA';
        reportHT_MBLE.value = rawDataHT.box_e ?? 'NA';
        reportHT_MBLF.value = rawDataHT.box_f ?? 'NA';
        reportHT_MBLG.value = rawDataHT.box_g ?? 'NA';
        reportHT_MBLH.value = rawDataHT.box_h ?? 'NA';
        reportHT_MBLJ.value = rawDataHT.box_j ?? 'NA';
        reportHT_MBLK.value = rawDataHT.box_k ?? 'NA';

        //End of Coating & Heat Treatment update


        //console.log('Entering Evalation for Reject reasons...');
        await evaluateAllRejectReasons();
        await checkApprovalStates();
        await checkSpecialJudgement();
        saveReportButtonVisibility();
        autoCheckRemarks();

        // Final result conditions

        setTimeout(() => {
            isReportDataReady.value = true; //!important this is flag for when date is ready. (it must be always set to true)
        }, 1000); // 1 second delay
    } catch (error) {
        console.error("API get request showReportData Error:", error);
    }
}

const saveReport = async () => {
    // Use default values (empty string) or check for null/undefined before calling toUpperCase
    reportPartialNo.value = (reportPartialNo.value || '').toUpperCase();
    reportShift.value = (reportShift.value || '').toUpperCase();
    reportOperator.value = (reportOperator.value || '').toUpperCase();

    if(coatingCompleted.value == true){
        await userReportLogging(`has successfully marked coating completed | serial ${currentSerialSelected.value}`);
    }

    if(heatTreatmentCompleted.value == true){
        await userReportLogging(`has successfully marked heat treatment completed | serial ${currentSerialSelected.value}`);
    }

    const saveReportData = {
        "oven_machine_no": reportOvenMachineNo.value,
        "time_loading": reportTimeLoading.value,
        "temp_time_loading": reportTemperature_TimeLoading.value,
        "date_oven_info": reportDate_OvenInfo.value,
        "time_unloading": reportTimeUnloading.value,
        "temp_time_unloading": reportTemperature_TimeUnloading.value,
        "shift_oven_info": reportShift_OvenInfo.value,
        "operator_oven_info": reportOperator_OvenInfo.value,
        "date": reportDate.value,
        "material_code": reportMaterialCode.value,
        "partial_number": reportPartialNo.value,
        "shift": reportShift.value,
        "total_quantity": reportTotalQuantity.value,
        "operator": reportOperator.value,
        "remarks": reportRemarks.value,
        "remarks2": reportRemarks2.value,
        "remarks3": reportRemarks3.value,
        "smp_judgement":reportSMPJudgement.value,
        "modified_smp_judgement":modifiedSMPJudgement.value,
        "remarks_display": reportRemarksDisplay.value,
        "note_reason_reject": noteReasonForReject.value,
        "std_dev": reportStdDev.value,
        "cp": reportCp.value,
        "cpk": reportCpk.value,
        "br_cpk_remarks": reportCpkRemarks.value,
        "data_1x1x1_info": JSON.stringify({
            "corner": reportCorner.value,
            "corner_average": reportCorner_average.value,
            "corner_maximum": reportCorner_maximum.value,
            "corner_minimum": reportCorner_minimum.value,
            "corner_cpk": reportCorner_cpk.value,
            "corner_remarks": reportCorner_remarks.value,
            "surface": reportSurface.value,
            "surface_average": reportSurface_average.value,
            "surface_maximum": reportSurface_maximum.value,
            "surface_minimum": reportSurface_minimum.value,
            "surface_cpk": reportSurface_cpk.value,
            "surface_remarks": reportSurface_remarks.value,
            "core": reportCore.value,
            "core_average": reportCore_average.value,
            "core_maximum": reportCore_maximum.value,
            "core_minimum": reportCore_minimum.value,
            "core_cpk": reportCore_cpk.value,
            "core_remarks": reportCore_remarks.value,
        }),
        "data_VT_info": JSON.stringify({
            "sample": reportVT_samples.value, // Dynamic sample array
            "sample_qty": reportVT_samplesQty.value, // Dynamic sample qty
            "sample_remarks": reportVT_sampleRemarks.value, // Dynamic remarks array
            "temp": reportVT_temp.value, // Single temperature value
            "iHc": reportVT_iHc.value, // Single iHc value
            "iHcResult": reportVT_iHcResults.value, // Dynamic iHc results array
            "remarks": reportVT_remarks.value, // Single remarks value
        }),
        "data_iHc_cpk_info": JSON.stringify({
            "std_dev": reportCpkFrom_iHc_StdDev.value,
            "cpk": reportCpkFrom_iHc_Cpk.value,
            "remarks": reportCpkFrom_iHc_remarks.value,
        }),
        "data_GX_info": JSON.stringify({
            "iHcStandard": reportGX_iHcStandard.value,
            "iHcAverage": reportGX_iHcAverage.value,
            "iHcMaximum": reportGX_iHcMaximum.value,
            "iHcMinimum": reportGX_iHcMinimum.value,
            "iHcVariance": reportGX_iHcVariance.value,
            "iHkAverage": reportGX_iHkAverage.value,
            "iHkMaximum": reportGX_iHkMaximum.value,
            "iHkMinimum": reportGX_iHkMinimum.value,
            "iHkVariance": reportGX_iHkVariance.value,
        }),
        "data_bh_info": JSON.stringify({
            "data": reportBH_data.value,
            "dataStandard": reportBH_dataStandard.value,
            "remarks": reportBH_remarks.value,
            "sample": reportBH_samples.value,
            "sample_qty": reportBH_sampleQty.value,
            "temp": reportBH_temp.value,
            "result": reportBH_results.value,
        }),
        "data_ROB_info": JSON.stringify({
            "brMin": reportROB_brMin.value,
            "brMax": reportROB_brMax.value,
            "iHcMin": reportROB_iHcMin.value,
            "iHcMax": reportROB_iHcMax.value,
            "brRTStandard": reportROB_BrRTstandard.value,
            "brVTStandard": reportROB_BrVTstandard.value,
            "hd5Standard": reportROB_HD5standard.value,
            "jd5Standard": reportROB_JD5standard.value,
            "brRT_brMin": reportROB_BrRT_brMin.value,
            "brRT_brMax": reportROB_BrRT_brMax.value,
            "brRT_iHcMin": reportROB_BrRT_iHcMin.value,
            "brRT_iHcMax": reportROB_BrRT_iHcMax.value,
            "brVT_brMin": reportROB_BrVT_brMin.value,
            "brVT_brMax": reportROB_BrVT_brMax.value,
            "brVT_iHcMin": reportROB_BrVT_iHcMin.value,
            "brVT_iHcMax": reportROB_BrVT_iHcMax.value,
            "HD5_brMin": reportROB_HD5_brMin.value,
            "HD5_brMax": reportROB_HD5_brMax.value,
            "HD5_iHcMin": reportROB_HD5_iHcMin.value,
            "HD5_iHcMax": reportROB_HD5_iHcMax.value,
            "JD5_brMin": reportROB_JD5_brMin.value,
            "JD5_brMax": reportROB_JD5_brMax.value,
            "JD5_iHcMin": reportROB_JD5_iHcMin.value,
            "JD5_iHcMax": reportROB_JD5_iHcMax.value,
            "result": reportROB_remarks.value,
        }),
        "coating_completed": coatingCompleted.value ? 1 : 0,
        "heat_treatment_completed": heatTreatmentCompleted.value ? 1 : 0,
    }
    //console.log("Save report data: ", saveReportData);
    const coatingData = {};

    const structuredModules = {};

    modules.forEach((mod) => {
        const keyBase = `add_slurry_${mod.name.toLowerCase()}`; // "add_slurry_m01"
        structuredModules[`${keyBase}_new`] = mod.new;
        structuredModules[`${keyBase}_homo`] = mod.homo;
        structuredModules[`${keyBase}_time`] = mod.time;
        structuredModules[`${keyBase}_liters`] = mod.liters;
    });

    for (let i = 0; i < 30; i++) {
        const mLabel = `M${((i % 5) + 1).toString().padStart(2, '0')}`; // M01 to M05, loops every 5
        coatingData[`${i + 1}_${mLabel}_Amount`] = reportCoatingAmounts.value[i];
        coatingData[`${i + 1}_${mLabel}_Concentration`] = reportConcentrationValues.value[i];
    }

    const M06_START_INDEX = 35;

    for (let i = 0; i < 12; i++) {
        const groupStart = (Math.floor(i / 2) * 5) + 1;
        const groupEnd = groupStart + 4;
        coatingData[`${groupStart}to${groupEnd}_M06_${i + 1}_Concentration`] = reportConcentrationValues.value[M06_START_INDEX + i];
    }
    //console.log('reportConcentrationValues:', reportConcentrationValues.value.length, reportConcentrationValues.value);


    const saveCoatingData = {
        "serial":currentSerialSelected.value,
        "date":reportCoatingDate.value,
        "machine_no":reportCoatingMachineNo.value,
        "slurry_lot_no":reportCoatingSlurryLotNo.value,
        "loader_operator":reportCoatingLoaderOperator.value,
        "min_tb_content":reportCoatingMinTbContent.value,
        "sample_qty":reportCoatingSampleQuantity.value,
        "total_magnet_weight":reportCoatingTotalMagnetWeight.value,
        "unloader_operator":reportCoatingUnloaderOperator.value,
        "checker_operator":reportCoatingCheckerOperator.value,
        "time_start":reportCoatingTimeStart.value,
        "time_finish":reportCoatingTimeFinish.value,
        "maximum":reportCoatingModuleMaximum.value,
        "minimum":reportCoatingModuleMinimum.value,
        "average":reportCoatingModuleAverage.value,
        "remarks":reportCoatingRemarks.value,

        "coating_data": {
            ...coatingData,         // structured key-value object
            ...structuredModules,   // M-01 to M-06 slurry data
        },
    }
    //console.log("Save coating data: ", saveCoatingData);
    await saveCoating_data(saveCoatingData, currentSerialSelected.value);

    const saveHeatTreatmentData = {
        "serial": currentSerialSelected.value,
        "furnace_machine": reportHTFurnaceMachine.value,
        "cycle_no": reportHTCycleNo.value,
        "batch_cycle_no": reportHTBatchCycleNo.value,
        "pattern_no": reportHTPatternNo.value,
        "date_start": reportHTDateStart.value,
        "time_start": reportHTTimeStart.value,
        "loader": reportHTLoader.value,
        "date_finish": reportHTDateFinish.value,
        "time_finish": reportHTTimeFinish.value,
        "unloader": reportHTUnloader.value,
        "cycle_pattern": reportHTCyclePattern.value,
        "current_pattern": reportHTCurrentPattern.value,
        "remarks": reportHTRemarks.value,
        "magnet_box_location":{
            "box_a": reportHT_MBLA.value,
            "box_b": reportHT_MBLB.value,
            "box_c": reportHT_MBLC.value,
            "box_d": reportHT_MBLD.value,
            "box_e": reportHT_MBLE.value,
            "box_f": reportHT_MBLF.value,
            "box_g": reportHT_MBLG.value,
            "box_h": reportHT_MBLH.value,
            "box_j": reportHT_MBLJ.value,
            "box_k": reportHT_MBLK.value,
        },
    }
    console.log("Save heat treatment data: ", saveHeatTreatmentData);
    await saveHeatTreatment_data(saveHeatTreatmentData, currentSerialSelected.value);

    await saveReportUpdate(saveReportData, currentSerialSelected.value);
}

const saveCoating_data = async (coatingData, serial) => {
    try{
        const responseCoatingDataPost = await axios.patch(`/api/coating-data/${serial}`,coatingData);
        //console.log('API PATCH REQUEST COATING DATA: ',responseCoatingDataPost.data);
    }catch(error){
        console.error('Error has occured for responseCoatingDataPost: ',error);
    }
}

const saveHeatTreatment_data = async (heatTreatmentData, serial) => {
    try{
        const responseHeatTreatmentDataPost = await axios.patch(`/api/heat-treatment-data/${serial}`,heatTreatmentData);
        //console.log('API PATCH REQUEST HEAT TREATMENT DATA: ',responseHeatTreatmentDataPost.data);
    }catch(error){
        console.error('Error has occured for responseHeatTreatmentDataPost: ',error);
    }
}

const saveReportUpdate = async (saveData, serial) => {
    try{
        const responseSave = await axios.patch(`/api/reportdata/${serial}`, saveData);
        //console.log("Saved Report data: ", responseSave.data);
        checkApprovalStates();
        showNotification2("Saved Successfully");
    }catch (error){
        console.error("Patch report data Error:", error);
    }finally{
        reportReset();
        fetchAllData();
        showReportData();
        showReportContent.value = false;
        showSelectionPanel.value = true;
        showReportMain.value = false;
        if(isFromViewList.value){
            Inertia.visit('/reports');
        }
    }
}

// Fetching the serial start

// Function to fetch serial data
const fetchSerial = async () => {
  try {
    const response = await axios.get("/api/tpmdata");
    //console.log("API Response fetchSerial-data:", response.data);

    // Grab the raw tpmData object
    const rawTpmData = response.data.data["tpmData"] || {};

    // Assign it to tpmData for future access
    tpmData.value = rawTpmData;

    // Extract the serial numbers (object keys)
    serialList.value = Object.keys(rawTpmData);

    // Sort in descending order
    serialList.value = serialList.value.sort((a, b) => Number(b) - Number(a));

    // Set default selected serial
    if (serialList.value.length > 0) {
      currentSerialSelected.value = serialList.value[0];
    }

    //console.log("Serials ↓", serialList.value);
  } catch (error) {
    console.error("Error fetching serial data:", error);
  }
};
// Fetching the serial start end

// Define the prop that will receive the serialParam
const props = defineProps({
  serialParam: String,  // Expecting the serialParam to be a string
  filterStatus: String,
  ipAddress: String,
  fromApproval: [Boolean, String],
  fromViewList: [Boolean, String],
});
// Update value after props are available
isFromApproval.value = props.fromApproval === true || props.fromApproval === 'true';
isFromViewList.value = props.fromViewList === true || props.fromViewList === 'true';
//console.log('isFromApproval:', isFromApproval.value);
ipAddress.value = props.ipAddress;
filterStatus.value = props.filterStatus;

if(ipAddress.value === '127.0.0.1'){
    onTestServer.value = true;
}
//console.log('Current IP address is:', props.ipAddress); // You can use this for debugging
//console.log('Serial Param in Reports.vue:', props.serialParam); // You can use this for debugging

const viewPropertyData = (serial) => {
  //console.log('Navigating to manage with serial:', serial);
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
    preparedByButton.value = false;
    showPreparedByDefault.value = false;
    // Split the full name into first and last name
    preparedByStampPhoto.value = true;
    preparedByStampConfirmation.value = false;
    const dateNow = datenow();
    //console.log("Prepared By Has been stamped by -> ", preparedByPerson.value);
    const preparedByData = {
        prepared_by_firstname: state.user.firstName,
        prepared_by_surname: state.user.surname,
        prepared_by_date: dateNow
    };
    //console.log('preparedBy Payload: ',preparedByData.value);
    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, preparedByData);
    //console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);
    await userReportLogging(`has successfully stamped Prepared By of serial ${currentSerialSelected.value}`);

    try {
        const emailPayload = {
            serial: [currentSerialSelected.value],
            emails: 'rizza@smp.com.ph,qa_trainingp8@smp.com.ph,mpi-engr.p8@smp.com.ph,p7_mpi_ahi@smp.com.ph',
        };

        //console.log('[DEBUG] Sending email payload:', emailPayload);

        const sendEmail = await axios.post('/api/route-email', emailPayload);

        //console.log('[DEBUG] Email API response:', sendEmail.data);
    } catch (error) {
        if (error.response) {
            console.error('[ERROR] Email API failed with response:', {
                status: error.response.status,
                data: error.response.data,
            });
        } else if (error.request) {
            console.error('[ERROR] Email API request made but no response:', error.request);
        } else {
            console.error('[ERROR] Email API setup error:', error.message);
        }
    }

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
    checkedByButton.value = false;
    showCheckedByDefault.value = false;
    // Split the full name into first and last name
    checkedByStampPhoto.value = true;
    checkedByStampConfirmation.value = false;
    const dateNow = datenow();
    //console.log("Prepared By Has been stamped by -> ", preparedByPerson.value);
    const checkedByData = {
        checked_by_firstname: state.user.firstName,
        checked_by_surname: state.user.surname,
        checked_by_date: dateNow
    };
    //console.log('checkedBy Payload: ',checkedByData.value);
    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, checkedByData);
    //console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);

    await userReportLogging(`has successfully stamped Checked By of serial ${currentSerialSelected.value}`);

    try {
        const emailPayload = {
            serial: [currentSerialSelected.value],
            emails: 'itadani@shinetsu.jp',
        };

        //console.log('[DEBUG] Sending email payload:', emailPayload);

        const sendEmail = await axios.post('/api/route-email', emailPayload);

        //console.log('[DEBUG] Email API response:', sendEmail);
    } catch (error) {
        if (error.response) {
            console.error('[ERROR] Email API failed with response:', {
                status: error.response.status,
                data: error.response.data,
            });
        } else if (error.request) {
            console.error('[ERROR] Email API request made but no response:', error.request);
        } else {
            console.error('[ERROR] Email API setup error:', error.message);
        }
    }

    showReportData();
}

const checkApprovalStates = async () => {
    try {

        const response = await axios.get(`/api/reportdata/`);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value);

        const prepared_by_firstname = filterBySerial[0]?.prepared_by_firstname;
        const prepared_by_surname = filterBySerial[0]?.prepared_by_surname;
        const checked_by_firstname = filterBySerial[0]?.checked_by_firstname;
        const checked_by_surname = filterBySerial[0]?.checked_by_surname;
        const approved_by_firstname = filterBySerial[0]?.approved_by_firstname;
        const approved_by_surname = filterBySerial[0]?.approved_by_surname;

        preparedByPerson_firstName.value = prepared_by_firstname ?? '';
        preparedByPerson_lastName.value = prepared_by_surname ?? '';

        checkedByPerson_firstName.value = checked_by_firstname ?? '';
        checkedByPerson_lastName.value = checked_by_surname ?? '';

        approvedByPerson_firstName.value = approved_by_firstname ?? '';
        approvedByPerson_lastName.value = approved_by_surname ?? '';

        if((state.user.access_type) && (state.user.access_type === 'Preparation Approver' || state.user.access_type === 'Hybrid Approver' || state.user.access_type === 'Bypass Approver')){
            preparedByButton.value = true;
            showPreparedByDefault.value = false;
        }

        if((state.user.access_type) && (prepared_by_firstname) && (state.user.access_type === 'Checking Approver' || state.user.access_type === 'Hybrid Approver' || state.user.access_type === 'Bypass Approver')){
            checkedByButton.value = true;
            showCheckedByDefault.value = false;
        }

        if(preparedByPerson_firstName.value && preparedByPerson_lastName){
            preparedByStampPhoto.value = true;
            preparedByButton.value = false;
            showPreparedByDefault.value = false;
        }

        if(checkedByPerson_firstName.value && checkedByPerson_lastName){
            checkedByStampPhoto.value = true;
            checkedByButton.value = false;
            showCheckedByDefault.value = false;
        }

        if(approvedByPerson_firstName.value && approvedByPerson_lastName){
            approvedByStampPhoto.value = true;
            approvedByButton.value = false;
            showApprovedByDefault.value = false;
        }

    } catch (error) {
        console.error("ERROR Getting report data API result: ", error);
    }
};

const evaluateAllRejectReasons = async () => {
    //console.log('Have already entered Evalation for Reject reasons...');

    // force some async delay for testing
    await new Promise(res => setTimeout(res, 100));

    if (!noteReasonForReject.value || noteReasonForReject.value.length === 0) {
        noteReasonForReject.value = []; // Reset before evaluation

        //console.log('Evaluating rejection reasons part1...');

    if (getAlliHkiHcNG.value.includes("1")) {
        //console.log('Condition met: getAlliHkiHcNG includes "1"');
        noteReasonForReject.value.push('- N.G iHc-iHk');
        withAdditionalSampleForRemarks.value =  true;
    }

    if (getAllBr4paiNG.value.includes("1")) {
        //console.log('Condition met: getAllBr4paiNG includes "1"');
        noteReasonForReject.value.push('- N.G Br-4PIa');
        withAdditionalSampleForRemarks.value =  true;
    }

    if (getAllBHMaxNG.value.includes("1")) {
        //console.log('Condition met: getAllBHMaxNG includes "1"');
        noteReasonForReject.value.push('- N.G BH(max)');
        withAdditionalSampleForRemarks.value =  true;
    }

    if (getAllbHcNG.value.includes("1")) {
        //console.log('Condition met: getAllbHcNG includes "1"');
        noteReasonForReject.value.push('- N.G bHc');
        withAdditionalSampleForRemarks.value =  true;
    }

    //console.log('Final Rejection Reasons part 1:', noteReasonForReject.value);

    //console.log('Evaluating rejection reasons part2...');

    if (reportBrMinimum.value < inspectionBrStandard_lower.value) {
      //console.log(`LOW BR: ${reportBrMinimum.value} < ${inspectionBrStandard_lower.value}`);
      noteReasonForReject.value.push('- LOW BR');
    }

    if (reportBrMaximum.value > inspectionBrStandard_higher.value) {
      //console.log(`HIGH BR: ${reportBrMaximum.value} > ${inspectionBrStandard_higher.value}`);
      noteReasonForReject.value.push('- HIGH BR');
    }

    if (reportihcMinimum.value < inspectioniHcStandard.value) {
        //console.log(`N.G iHc: ${reportihcMinimum.value} < ${inspectioniHcStandard.value}`);
        withAdditionalSampleForRemarks.value =  true;
        //console.log("This is NG IHC triggered and the value is currently: ",withAdditionalSampleForRemarks.value);
        noteReasonForReject.value.push('- N.G iHc');
    } else if (reportihcMinimum.value < Number(inspectioniHcStandard.value) + 500) {
        //console.log(`iHc Below Target+500 Oe: ${reportihcMinimum.value} < ${Number(inspectioniHcStandard.value) + 500}`);
        noteReasonForReject.value.push('- iHc Below Target+500 Oe');
        withAdditionalSampleForRemarks.value =  true;
    }

    if (reportihkMinimum.value < inspectioniHkStandard.value) {
        //console.log(`N.G iHk: ${reportihkMinimum.value} < ${inspectioniHkStandard.value}`);
        noteReasonForReject.value.push('- N.G iHk');
        withAdditionalSampleForRemarks.value =  true;
    }

    if ((reportihr95Minimum.value < Number(inspectioniHcStandard.value) - 750) && (getAlliHr95NG.value.includes("1"))) {
        //console.log(`N.G Hr95: ${reportihr95Minimum.value} < ${Number(inspectioniHcStandard.value) - 750}`);
        noteReasonForReject.value.push('- N.G Hr95');
        withAdditionalSampleForRemarks.value =  true;
    }

    if ((reportihr98Minimum.value < Number(inspectioniHcStandard.value) - 1250) && (getAlliHr98NG.value.includes("1"))) {
        //console.log(`N.G Hr98: ${reportihr98Minimum.value} < ${Number(inspectioniHcStandard.value) - 1250}`);
        noteReasonForReject.value.push('- N.G Hr98');
        withAdditionalSampleForRemarks.value =  true;
    }

    //console.log('Final Rejection Reasons part 2:', noteReasonForReject.value);
  } else {
    //console.log('Skipping rejection evaluation: Reasons already exist.');
  }
}

const addCarmark = async () => {
    try{
        const responseCarMark = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, {
            "withCarmark": 1,
        });
        //console.log("Saved carmark data: ", responseCarMark.data);
        isAutomotive.value = true;
        showCarMarkButton.value = false;
    }catch(error){
        //console.log("ERROR API RESPONSE PATCH REQUEST: ",error);
    }
}

const finalizeReport = async (serial) => {
    await userFinalizedLogging(`has finalized report serial: ${serial}`);
    window.open(`/reports/${encodeURIComponent(serial)}/pdf`, '_blank');
};

const sec_additional_redirect = (sec_serial) => {
    try {
        //console.log('Navigating to report with serial:', sec_serial);
        Inertia.visit('/sec_additional', {
            method: 'get',
            data: { sec_serialParam: sec_serial },
            preserveState: true,
            preserveScroll: true,
            onError: (errors) => {
                console.error('Navigation failed with error:', errors);
                alert('Failed to load section additional. Please try again.');
            },
            onCancelToken: (cancelToken) => {
                // Optional: Cancel a previous visit if needed
                console.warn('Navigation was cancelled or interrupted.');
            },
        });
    } catch (error) {
        console.error('Unexpected error during Inertia navigation:', error);
        alert('Something went wrong during navigation.');
    }
};

const backToApprovalFunction = () => {
    //console.log('Navigating to report with serial:', serial);
    Inertia.visit('/approval', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { filterStatus: filterStatus.value, fromReports: true },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};


// onMounted logic to call the function based on serialParam existence
onMounted(async () => {
    await checkAuthentication();
    await checkApprovalStates();
    if (props.serialParam && props.fromApproval || props.fromViewList) {
        await checkAuthentication();
        await checkApprovalStates();
        currentSerialSelected.value = props.serialParam;

        // This ensures reactivity is flushed before running the report logic
        await Promise.resolve();

        generateReport();
    } else {
        fetchSerial();
    }
});
</script>

<style scoped>
@keyframes marquee {
  0%   { transform: translateX(0%); }
  100% { transform: translateX(40%); }
}

.marquee {
  display: inline-block;
  white-space: nowrap;
  animation: marquee 10s linear infinite;
}

</style>
