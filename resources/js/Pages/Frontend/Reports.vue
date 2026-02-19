<template>
    <Frontend>
        <div v-if="onTestServer" class="flex flex-row items-center justify-center text-xl font-extrabold text-green-500">
            YOU ARE ON TEST SERVER
        </div>
      <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">
        <div v-if="!isFromApproval && !isFromViewList && !isFromApproval_checked && !isFromApproval_prepared">
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
                <div class="max-w-2xl p-8 mx-auto mt-8 bg-white border border-gray-200 shadow-lg rounded-xl">
                    <!-- Header -->
                    <div class="mb-6 text-center">
                        <div class="inline-flex items-center justify-center mb-3 rounded-full shadow-md w-14 h-14 bg-gradient-to-br from-teal-500 to-cyan-500">
                            <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-1 text-2xl font-bold text-gray-800">Generate Report</h3>
                        <p class="text-sm text-gray-500">Select a serial number to proceed</p>
                    </div>

                    <!-- Selection Panel -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Serial Number</label>
                        <select
                            v-model="currentSerialSelected"
                            class="w-full px-4 py-3 text-base font-medium text-gray-700 transition-all border border-gray-300 rounded-lg shadow-sm cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white">
                            <option v-for="serial in serialList" :key="serial" :value="serial">{{ serial }}</option>
                        </select>
                    </div>

                    <!-- Button -->
                    <button
                        @click="generateReport"
                        :disabled="showNotif2"
                        class="w-full px-6 py-3.5 text-base font-semibold text-white bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg shadow-md hover:from-teal-600 hover:to-cyan-600 hover:shadow-lg transition-all duration-200 active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md disabled:active:scale-100 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2"
                    >
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Generate Report
                        </span>
                    </button>

                    <!-- Notification -->
                    <div v-show="showNotif2" class="p-4 mt-6 border-l-4 border-yellow-400 rounded-lg shadow-sm bg-gradient-to-r from-amber-50 to-yellow-50">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="flex-1 text-sm font-semibold text-gray-800">{{ reportNotificationMessage }}</p>
                        </div>
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
                        <!-- Mass Prod (display-only) -->
                        <div class="flex flex-col w-[12rem]">
                            <label class="mb-1 text-sm font-medium text-gray-700">
                                Mass Production:
                            </label>
                            <span
                                class="flex items-center px-3 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md cursor-default h-9 hover:ring-1 hover:ring-blue-400 hover:shadow-sm"
                            >
                                {{ selectedMassProd }}
                            </span>
                        </div>

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
                            <span
                                class="flex items-center px-3 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md cursor-default h-9 hover:ring-1 hover:ring-blue-400 hover:shadow-sm"
                            >
                                {{ reportMaterialCode }}
                            </span>
                        </div>

                        <!-- Total Quantity -->
                        <div class="flex flex-col w-[12rem]">
                            <label for="totalQuantity" class="mb-1 text-sm font-medium text-gray-700">
                                Total Quantity:
                            </label>
                            <span
                                class="flex items-center px-3 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-md cursor-default h-9 hover:ring-1 hover:ring-blue-400 hover:shadow-sm"
                            >
                                {{ reportTotalQuantity }}
                            </span>
                        </div>
                    </div>
                </div>

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
                                    <td :rowspan="(noteReasonForReject.includes('- N.G iHc') || specialModelsForGxFormat.includes(jhCurveActualModel)) && showGX ? 2 : 1" class="px-4 py-2 text-blue-600 border-4 border-white whitespace-nowrap">iHc (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><span v-if="showGX">GM </span>{{ inspectioniHcStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHcVariance }}</td>
                                </tr>
                                <tr v-if="showGX && (noteReasonForReject.includes('- N.G iHc') || specialModelsForGxFormat.includes(jhCurveActualModel))" class="text-center">
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
                                    <td :rowspan="(noteReasonForReject.includes('- N.G iHc') || specialModelsForGxFormat.includes(jhCurveActualModel)) && showGX ? 2 : 1" class="px-4 py-2 text-blue-600 border-4 border-white whitespace-nowrap">iHk (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white"><span v-if="showGX">GM </span>{{ inspectioniHkStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHkVariance }}</td>
                                </tr>
                                <tr v-if="showGX && (noteReasonForReject.includes('- N.G iHc') || specialModelsForGxFormat.includes(jhCurveActualModel))" class="text-center">
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
                    <div v-if="state.user && (state.user.access_type == 'Proxy Approver' || state.user.access_type == 'Automation' || state.user.change_judgement_access == 'yes')" class="flex flex-row items-center justify-center mt-2">
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
                    <button
                        v-if="!checkedByStampPhoto && preparedByStampPhoto"
                        @click="openUndoModal('prepared')"
                        class="bg-red-500 rounded-xl shadow-xl text-white font-bold p-2 text-xs mt-6
                            transition-all duration-200 ease-out
                            hover:bg-red-600
                            hover:shadow-2xl
                            hover:-translate-y-0.5
                            active:translate-y-0
                            active:shadow-lg"
                    >
                        UNDO PREPARED BY STAMP
                    </button>
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
                    <button
                        v-if="!approvedByStampPhoto && checkedByStampPhoto"
                        @click="openUndoModal('checked')"
                        class="bg-red-500 rounded-xl shadow-xl text-white font-bold p-2 text-xs mt-6
                            transition-all duration-200 ease-out
                            hover:bg-red-600
                            hover:shadow-2xl
                            hover:-translate-y-0.5
                            active:translate-y-0
                            active:shadow-lg"
                    >
                        UNDO CHECKED BY STAMP
                    </button>
                    </div>

                    <!-- Approved By -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white whitespace-nowrap">Approved By:</p>
                    <div class="items-center p-1 text-center border-b-4 border-l-4 border-r-4 border-white">
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
                    <button
                        v-if="approvedByStampPhoto"
                        @click="openUndoModal('approved')"
                        class="bg-red-500 rounded-xl shadow-xl text-white font-bold p-2 text-xs mt-6
                            transition-all duration-200 ease-out
                            hover:bg-red-600
                            hover:shadow-2xl
                            hover:-translate-y-0.5
                            active:translate-y-0
                            active:shadow-lg"
                    >
                        UNDO APPROVED BY STAMP
                    </button>
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
                    <button v-if="(!approvedByPerson_firstName && state.user ) && (state.user.access_type === 'Preparation Approver' || state.user.access_type === 'Checking Approver' || state.user.access_type === 'Hybrid Approver' || state.user.access_type === 'Bypass Approver' || state.user.access_type === 'Proxy Approver' || state.user.access_type === 'Automation')" @click="saveReport" class="px-6 py-4 mt-4 font-extrabold text-white transition duration-300 ease-in-out transform bg-green-500 shadow-xl rounded-xl hover:bg-green-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-600 active:scale-95">
                        {{ reportExistingSMPJudgement !== null ? 'OVERWRITE' : 'SAVE' }}
                    </button>
                    <!--button @click="viewPropertyData(currentSerialSelected, selectedMassProd, selectedLayer, currentFurnaceName)" class="px-6 py-4 mt-4 ml-5 font-extrabold text-blue-700 transition duration-300 ease-in-out transform border border-blue-700 shadow-xl hover:text-white rounded-xl hover:bg-blue-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-600 active:scale-95">
                        View Property Data
                    </!--button-->
                    <!-- Apply Data 1x1x1 Button (New Styled Button) -->
                    <button
                        @click="sec_additional_redirect(currentSerialSelected, selectedMassProd, selectedLayer, selectedFurnace)"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-red-700 transition duration-300 ease-in-out transform border border-red-700 shadow-xl rounded-xl hover:text-white hover:bg-red-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-600 active:scale-95"
                        >
                        Apply Additional
                    </button>
                    <button
                        v-if="approvedByPerson_firstName"
                        @click="finalizeReport(currentSerialSelected, report_isFinalized)"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-yellow-600 transition duration-300 ease-in-out transform border border-yellow-400 shadow-xl rounded-xl hover:text-white hover:bg-yellow-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-600 active:scale-95"
                    >
                        View PDF Report
                    </button>

                    <button v-if="showExitButton && !isFromApproval && !isFromViewList && !isFromApproval_checked && !isFromApproval_prepared" @click="exitReport()" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK
                    </button>

                    <button v-if="isFromApproval && !isFromViewList" @click="backToApprovalFunction" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK TO APPROVAL
                    </button>

                    <button v-if="isFromApproval_checked && !isFromViewList" @click="backToApprovalFunction_checked" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK TO APPROVAL (Checked)
                    </button>

                    <button v-if="isFromApproval_prepared && !isFromViewList" @click="backToApprovalFunction_prepared" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK TO APPROVAL (Prepared)
                    </button>

                    <button v-if="isFromViewList && !isFromApproval" @click="$inertia.visit('/view')" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK TO VIEW LIST
                    </button>

                    <button @click="async () => {
                            await getUndoHistory();
                            showModalUndoHistory = true;
                        }" class="px-6 py-4 mt-4 ml-5 font-extrabold text-orange-700 transition duration-300 ease-in-out transform border border-orange-700 shadow-xl rounded-xl hover:text-white hover:bg-orange-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-600 active:scale-95">
                        STAMP UNDO HISTORY
                    </button>

                </div>
                <div>
                    <button v-if="onTestServer" @click="finalizeReport(currentSerialSelected, report_isFinalized)" class="p-2 bg-blue-100 rounded-lg">
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
                <div class="flex flex-col mt-10">
                    <div class="flex flex-row justify-center mt-5 space-x-4">
                        <div
                            class="w-[600px] h-[460px] bg-gray-50 rounded-xl flex items-center pr-5 border-2 border-blue-900 justify-center"
                        >
                            <img
                                v-if="currentSerialSelected"
                                :src="`/charts/chart_${currentSerialSelected}.png`"
                                alt="Chart"
                                class="object-contain w-full h-full"
                                style="transform: scale(1); transform-origin: top left;"
                            />
                        </div>
                        <!-- Side Content -->
                        <div class="w-[350px] h-[420px] bg-gray-50 rounded-xl border-2 border-blue-900 flex justify-center items-start p-4">
                            <div class="flex flex-col items-start space-y-2">
                                <p>
                                    SMP Lot (
                                    <span>{{ selectedMassProd }}</span>
                                    Mass Production )
                                </p>
                                <p>
                                    Furnace Cycle No. : {{ propD_cycleNo }}
                                </p>
                                <p>
                                    <span>{{ jhCurveActualModel }}</span>
                                    ( {{ propD_codeNo }} )
                                </p>
                                <p>
                                    Lot # {{ jhCurveLotNo }}
                                </p>
                                <p class="font-semibold text-red-600">
                                    <template v-if="propD_rejectReasons && propD_rejectReasons.length">
                                        <span
                                            v-for="(note, index) in sortedNotes"
                                            :key="index"
                                            class="font-semibold text-red-700 opacity-100"
                                        >
                                            {{ note }}
                                        </span>
                                    </template>
                                    <template v-else>
                                        <span class="text-xs italic text-gray-400">
                                        Reject remarks will appear here once the report data has been saved, if any exist.
                                        </span>
                                    </template>
                                </p>
                            </div>

                        </div>
                    </div>
                    <div class="p-6 mt-4 mb-10 border-2 border-gray-500 rounded-lg shadow-lg bg-gray-50">
                        <div class="mb-4">
                            <p class="text-center">PROPERTY DATA</p>
                        </div>
                        <div class="flex flex-row justify-center">
                            <p class="mr-10">Mias. Employee: <span>{{ propD_miasEmp }}</span> </p>
                            <p>Factor Employee: <span>{{ propD_factorEmp }}</span></p>
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <div class="flex flex-row items-start justify-center">
                                <div class="flex flex-row flex-[3] mb-10 shadow-2xl">
                                    <div class="min-w-[700px]">
                                        <table class="border border-gray-300 table-auto rounded-xl">
                                            <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                                <tr>
                                                    <th v-for="tableLayerColumnHeader in tableLayerColumnHeaders"
                                                        :key="tableLayerColumnHeader.name"
                                                        :colspan="tableLayerColumnHeader.colspan"
                                                        class="px-[2px] py-[3px] text-[10px] font-medium text-center border border-white">
                                                        {{ tableLayerColumnHeader.name }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                            <tr
                                                v-for="item in propD_tpmData"
                                                :key="item.id"
                                                class="border-b hover:bg-gray-50"
                                            >
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.date }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.code_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.order_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.type }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.press_1 }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.press_2 }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.machine_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.sintering_furnace_no }}</td>
                                                <td class="whitespace-nowrap px-[0px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.furnace_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.zone }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.pass_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.Br }}</td>
                                                <td v-if="item.remark.Br_remarks == '1'" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.Br_remarks == '0' ? '' : item.remark.Br_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.iHc }}</td>
                                                <td v-if="item.remark.iHc_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.iHc_remarks == 0 ? '' : item.remark.iHc_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.iHk }}</td>
                                                <td v-if="item.remark.iHk_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.iHk_remarks == 0 ? '' : item.remark.iHk_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.BHMax }}</td>
                                                <td v-if="item.remark.BHMax_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.BHMax_remarks == 0 ? '' : item.remark.BHMax_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.iHr95 }}</td>
                                                <td v-if="item.remark.iHr95_remarks == '1'" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.iHr95_remarks == '0' ? '' : item.remark.iHr95_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.iHr98 }}</td>
                                                <td v-if="item.remark.iHr98_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.iHr98_remarks == 0 ? '' : item.remark.iHr98_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.iHkiHc }}</td>
                                                <td v-if="item.remark.iHkiHc_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.iHkiHc_remarks == 0 ? '' : item.remark.iHkiHc_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.Br4pai }}</td>
                                                <td v-if="item.remark.Br4pai_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.Br4pai_remarks == 0 ? '' : item.remark.Br4pai_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.bHc }}</td>
                                                <td v-if="item.remark.bHc_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.bHc_remarks == 0 ? '' : item.remark.bHc_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.Squareness }}</td>
                                                <td v-if="item.remark.Squareness_remarks == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark.Squareness_remarks == 0 ? '' : item.remark.Squareness_remarks }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item['4paiId'] }}</td>
                                                <td v-if="item.remark['4paiId_remarks'] == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark['4paiId_remarks'] == 0 ? '' : item.remark['4paiId_remarks'] }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item['4paiIs'] }}</td>
                                                <td v-if="item.remark['4paiIs_remarks'] == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark['4paiIs_remarks'] == 0 ? '' : item.remark['4paiIs_remarks'] }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item['4paiIa'] }}</td>
                                                <td v-if="item.remark['4paiIa_remarks'] == 1" class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 bg-red-500 text-white">E</td>
                                                <td v-else class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center border border-blue-500 text-gray-700">{{ item.remark['4paiIa_remarks'] == 0 ? '' : item.remark['4paiIa_remarks'] }}</td>
                                                <td class="whitespace-nowrap px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.Tracer }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="flex flex-row flex-[1]"> <!-- DIV 2 -->
                                    <table class="border border-gray-300 rounded-lg shadow-2xl table-auto">
                                        <thead class="text-white bg-blue-400">
                                            <tr>
                                                <th class="px-[3px] py-[3px] text-[10px] font-extrabold text-center border border-white">Sample&nbsp;with&nbsp;Variance</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr v-for="(variance, index) in sampleWithVariances" :key="index">
                                                <td class="px-[2px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">
                                                    {{ variance }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex flex-row flex-[1]"> <!-- DIV 2 -->
                                    <table class="border border-gray-300 rounded-lg shadow-2xl table-auto">
                                        <thead class="text-white bg-blue-400">
                                            <tr>
                                                <th class="px-[3px] py-[3px] text-[10px] font-extrabold text-center border border-white">Temp</th>
                                                <th class="px-[3px] py-[3px] text-[10px] font-extrabold text-center border border-white">Data&nbsp;Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr
                                                v-for="item in propD_tpmData"
                                                :key="item.id"
                                                class="border-b hover:bg-gray-50"
                                            >
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.temperature }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.data_status }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex flex-row"> <!-- DIV 5 -->
                                <div class="mb-16">
                                    <table class="border border-gray-300 rounded-lg shadow-2xl table-auto">
                                        <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                            <tr>
                                                <th v-for="secondTableLayerColumnHeader in secondTableLayerColumnHeaders" :key="secondTableLayerColumnHeader.name" class="px-[3px] py-[2px] text-[12px] font-medium text-center">{{ secondTableLayerColumnHeader.name }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-[2px] py-[2px] text-[12px] font-extrabold text-center text-white bg-blue-700">AVERAGE</td>
                                                <td v-for="(aggAveValue, index) in aggAveValues" :key="index" class="px-[4px] py-[2px] text-[10px] text-center text-gray-700 border border-blue-500">
                                                    {{ aggAveValue.value }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-[2px] py-[2px] text-[12px] font-extrabold text-center text-white bg-blue-700">MAXIMUM</td>
                                                <td v-for="(aggMaxValue, index) in aggMaxValues" :key="index" class="px-[4px] py-[2px] text-[10px] text-center text-gray-700 border border-blue-500">
                                                    {{ aggMaxValue.value }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-[2px] py-[2px] text-[12px] font-extrabold text-center text-white bg-blue-700">MINIMUM</td>
                                                <td v-for="(aggMinValue, index) in aggMinValues" :key="index" class="px-[4px] py-[2px] text-[10px] text-center text-gray-700 border border-blue-500">
                                                    {{ aggMinValue.value }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-[2px] py-[2px] text-[12px] font-extrabold text-center text-white bg-red-800">NG&#160;COUNTER</td>
                                                <td v-for="(ngCount, index) in aggNGCounts" :key="index" class="px-[4px] py-[2px] text-[10px] text-center text-gray-700 border border-blue-500">
                                                    {{ ngCount }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex flex-col items-center justify-center p-4 mb-4 ml-6 space-y-3 text-sm bg-blue-200 rounded-lg shadow-md"> <!-- DIV 6 -->

                                    <!-- Header -->
                                    <div>
                                        <p class="px-4 py-1 text-xs font-bold text-white bg-blue-900 rounded-md shadow">Data Results:</p>
                                    </div>

                                    <div class="w-[600px] h-[50px] px-4 py-2 bg-white rounded-2xl text-xs space-y-1">
                                        <p class="flex gap-1 font-bold whitespace-nowrap">
                                            <span
                                                v-for="(rejectOKNGlist, index) in rejectOKNG"
                                                :key="index"
                                                :class="adjustColor_rejectOKNG(rejectOKNGlist)"
                                            >
                                                {{ rejectOKNGlist }}
                                            </span>
                                        </p>
                                        <p class="whitespace-nowrap">
                                            <span :class="adjustColor_rejectInstructions">
                                                {{ rejectInstruction }}
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Additional Info Row -->
                                    <div class="flex items-stretch justify-center w-full space-x-2">

                                        <!-- iHc Remarks -->
                                        <div class="flex flex-col items-center justify-center w-1/2 px-4 py-2 text-center bg-white rounded-2xl">
                                        <p class="text-xs font-semibold">
                                            <span :class="adjustColor_rejectiHc">{{ rejectiHcRemarks }}</span>
                                        </p>
                                        <p class="text-sm font-bold">
                                            <span :class="adjustColor_iHcValue">{{ getHighestSampleVariance }}</span>
                                        </p>
                                        </div>

                                        <!-- Lot Remarks -->
                                        <div class="flex flex-wrap items-center justify-center w-1/2 px-4 py-2 text-xs font-bold bg-white rounded-2xl">
                                        <span
                                            v-for="(rejectLotRemarksList, index) in rejectLotRemarks"
                                            :key="index"
                                            :class="adjustColor_rejectLotRemarks(rejectLotRemarksList)"
                                        >
                                            {{ rejectLotRemarksList }}
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div v-if="isSecAdditional" class="space-y-4">
                        <!-- Section Header -->
                        <p class="text-2xl font-bold text-center">SEC ADDITIONAL SECTION</p>

                        <!-- Set Selector -->
                        <div class="flex items-center justify-center space-x-2">
                            <label for="setSelect" class="font-medium">Select Set:</label>
                            <select
                                id="setSelect"
                                v-model.number="current_setNo"
                                @change="nsa_showData(current_setNo)"
                                class="px-2 w-[6rem] py-1 border border-gray-400 rounded"
                            >
                                <option
                                    v-for="setNo in availableSetNumbers"
                                    :key="setNo"
                                    :value="setNo"
                                >
                                    Set {{ setNo }}
                                </option>
                            </select>
                        </div>

                        <!-- Chart and Side Info -->
                        <div class="flex justify-center mt-5 space-x-4">
                            <!-- Chart -->
                            <div class="w-[600px] h-[480px] bg-gray-50 rounded-xl flex items-center justify-center pr-5 border-2 border-blue-900">
                                <img
                                    v-if="currentSerialSelected"
                                    :src="`/sec_charts/chart_${currentSerialSelected}_set${current_setNo}.png`"
                                    alt="Chart"
                                    class="object-contain w-full h-full"
                                    style="transform: scale(1); transform-origin: top left;"
                                />
                            </div>

                            <!-- Side Content -->
                            <div class="w-[350px] h-[420px] bg-gray-50 rounded-xl border-2 border-blue-900 flex flex-col justify-start p-4">
                                <div class="flex flex-col space-y-2">
                                    <p>SMP Lot (<span>{{ selectedMassProd }}</span>) Mass Production</p>
                                    <p>Furnace Cycle No.: {{ secAdd_propD_jhCurveFurnaceName }}</p>
                                    <p><span>{{ jhCurveActualModel }}</span> ({{ secAdd_propD_jhCurveCodeNo }})</p>
                                    <p>Lot #: {{ jhCurveLotNo }}</p>
                                    <p class="font-bold text-blue-600">{{ secAdd_propD_jhCurveRemarks }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="p-6 border-2 border-gray-500 rounded-lg shadow-lg bg-gray-50">
                            <div class="flex justify-center">
                                <div class="min-w-[700px] shadow-2xl">
                                    <table class="border border-gray-300 table-auto rounded-xl">
                                        <!-- Table Header -->
                                        <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                            <tr>
                                                <th
                                                    v-for="header in nsa_tableLayerColumnHeaders"
                                                    :key="header.name"
                                                    :colspan="header.colspan"
                                                    class="px-[4.8px] py-[3px] text-sm font-medium text-center border border-white"
                                                >
                                                    {{ header.name }}
                                                </th>
                                            </tr>
                                        </thead>

                                        <!-- Table Body -->
                                        <tbody class="bg-white">
                                            <tr
                                                v-for="item in nsa_combinedData"
                                                :key="item.id"
                                                class="border-b hover:bg-gray-50"
                                            >
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.date }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.code_no }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.order_no }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.type }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.press_1 }} {{ item.press_2 }} {{ item.machine_no }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.sintering_furnace_no }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[0px] py-[3px]">{{ item.furnace_no }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.zone }}</td>
                                                <td class="text-center text-[10px] border border-blue-500 px-[3px] py-[3px]">{{ item.pass_no }}</td>

                                                <!-- Repeat for all measured properties with conditional E -->
                                                <template v-for="prop in propsList" :key="prop">
                                                    <td class="text-center text-[10px] border border-blue-500 px-[2px] py-[3px]">{{ item[prop] }}</td>
                                                    <td
                                                        v-if="item.remark && (item.remark[prop + '_remarks'] == 1 || item.remark[prop + '_remarks'] == '1')"
                                                        class="text-center text-[10px] border border-blue-500 px-[2px] py-[3px] bg-red-500 text-white"
                                                    >
                                                        E
                                                    </td>
                                                    <td
                                                        v-else
                                                        class="text-center text-[10px] border border-blue-500 px-[2px] py-[3px]"
                                                    >
                                                        {{ item.remark ? (item.remark[prop + '_remarks'] == 0 || item.remark[prop + '_remarks'] == '0' ? '' : item.remark[prop + '_remarks']) : '' }}
                                                    </td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <Modal :show="showModalUndoStamp" @close="showModalUndoStamp = false">
            <div
                class="relative flex flex-col items-start
                    bg-gradient-to-br from-white via-cyan-50 to-teal-50
                    border-l-4 border-cyan-500
                    p-6 rounded-xl shadow-xl
                    max-w-[95vw] max-h-[90vh] overflow-auto"
            >

                <!-- Exit Button -->
                <button
                    @click="showModalUndoStamp = false"
                    class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-cyan-600"
                    aria-label="Close modal"
                >
                    ✕
                </button>

                <!-- Header -->
                <div class="flex items-center mb-4 space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-cyan-100">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-cyan-800 leading-snug">
                        Undo {{ undoStampLabel }}
                    </h2>
                </div>

                <!-- Body -->
                <p class="text-sm leading-relaxed text-gray-700 mb-4">
                    This action will remove the stamp permanently from the report.
                    Please provide a clear reason for logs tracking.
                </p>

                <div class="w-full">
                    <textarea
                        v-model="stampUndoRemarks"
                        rows="3"
                        placeholder="Enter reason for undo..."
                        required
                        class="w-full px-3 py-2 text-sm
                            bg-white border border-cyan-200 rounded-lg
                            focus:outline-none focus:ring-2 focus:ring-cyan-400
                            focus:border-cyan-400
                            shadow-sm transition-all duration-200"
                    ></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-end w-full mt-6 space-x-3">
                    <button
                        @click="showModalUndoStamp = false"
                        class="px-5 py-2 text-sm font-semibold text-gray-600
                            bg-gray-100 rounded-lg
                            hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>

                    <button
                        @click="undoStamp"
                        class="px-6 py-2 text-sm font-semibold text-white
                            bg-gradient-to-r from-cyan-500 to-teal-500
                            rounded-lg shadow-md
                            hover:from-cyan-600 hover:to-teal-600
                            hover:shadow-lg
                            active:scale-[0.98]
                            transition-all duration-200"
                    >
                        Confirm Undo
                    </button>
                </div>
            </div>
        </Modal>

        <Modal :show="showModalUndoHistory" @close="showModalUndoHistory = false">
            <div
                class="relative flex flex-col items-start
                    bg-gradient-to-br from-white via-cyan-50 to-teal-50
                    border-l-4 border-cyan-500
                    p-6 rounded-xl shadow-xl
                    max-w-[95vw] max-h-[90vh] overflow-auto"
            >

                <!-- Exit Button -->
                <button
                    @click="showModalUndoHistory = false"
                    class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-cyan-600"
                    aria-label="Close modal"
                >
                    ✕
                </button>

                <!-- Header -->
                <div class="flex items-center mb-5 space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-cyan-100">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-6h6v6m-7 4h8a2 2 0 002-2V7l-6-4-6 4v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-cyan-800">
                        Stamp Undo History
                    </h2>
                </div>

                <!-- Body -->
                <div class="w-full space-y-4">

                    <!-- Loading -->
                    <div v-if="undoHistoryLoading" class="text-sm text-gray-600">
                        Loading history...
                    </div>

                    <!-- Error -->
                    <div v-if="undoHistoryError" class="text-sm text-red-600">
                        {{ undoHistoryError }}
                    </div>

                    <!-- Empty State -->
                    <div v-if="!undoHistoryLoading && undoHistory.length === 0"
                        class="text-sm text-gray-500 italic">
                        No undo history found for this serial number.
                    </div>

                    <!-- History List -->
                    <div
                        v-for="(item, index) in undoHistory"
                        :key="item.id || index"
                        class="p-4 bg-white border border-cyan-100 rounded-lg shadow-sm"
                    >
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-semibold text-cyan-700">
                                {{ item.stamp_undone }}
                            </span>

                            <span class="text-xs text-gray-500">
                                {{ formatDateTime(item.created_at) }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-700">
                            <span class="font-semibold">Undo By:</span>
                            {{ item.undo_by }}
                        </div>

                        <div class="mt-1 text-sm text-gray-700 whitespace-break-spaces">
                            <span class="font-semibold">Remarks:</span>
                            {{ item.undo_remarks }}
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="flex justify-end w-full mt-6">
                    <button
                        @click="showModalUndoHistory = false"
                        class="px-5 py-2 text-sm font-semibold text-gray-600
                            bg-gray-100 rounded-lg
                            hover:bg-gray-200 transition"
                    >
                        Close
                    </button>
                </div>

            </div>
        </Modal>


      </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted, nextTick, watch, computed, watchEffect, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3'
import DotsLoader from '@/Components/DotsLoader.vue';
import Modal from '@/Components/Modal.vue'
import { useToast } from 'vue-toast-notification';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js';

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
            section: 'MPI Reports',
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
            section: 'MPI Reports',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userFinalizedLogging post request failed: ',error);
    }
}

const userErrorLogging = async (details, triggerFunction, title) => {
    try{
        const response = await axios.post('/api/error-logs', {
            user: state.user.firstName + " " + state.user.surname,
            title: title,
            details: details,
            trigger_function: triggerFunction,
            section: 'MPI Reports',
        });

        //console.log('userErrorLogging-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userErrorLogging post request failed: ',error);
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


const onTestServer = ref(false);

const page = usePage();

//console.log('Current page props:', page.props)
//UI Control start

const isOn = ref(false);
const cpkStandardValue = ref(1.33);
function toggleSwitch() {
    isOn.value = !isOn.value;
}
const showReportLoading = ref(false);

const isFromApproval = ref(false);
const isFromApproval_checked = ref(false);
const isFromApproval_prepared = ref(false);
const backToApproval = ref(false);
const isFromViewList = ref(false);
const isSecAdditional = ref(false);
const showModalUndoStamp = ref(false);
const showModalUndoHistory = ref(false);

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

const MODELS_SHOW_VT_DATA = ref([]);
const MODELS_1X1X1_NO_CORNER = ref([]);
const MODELS_SHOW_CPK = ref([]);
const MODELS_SHOW_GX = ref([]);
const MODELS_SHOW_BH = ref([]);
const MODELS_SHOW_ROB = ref([]);

const undoHistory = ref([]);
const undoHistoryLoading = ref(false);
const undoHistoryError = ref(null);

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
const stampUndoRemarks = ref('');
const currentUndoType = ref(null); // 'prepared' | 'checked' | 'approved'
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
const filterStatus_checked = ref('');
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
        //console.log('reportSMPJudgement changed:', oldVal, '→', newVal);
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
const jhCurveLotNo = ref('');

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

const selectedFurnace = ref();
const selectedMassProd = ref();
const selectedLayer = ref();

const propD_tpmData = ref([]);
const propD_cycleNo = ref();
const propD_codeNo = ref();
const propD_rejectReasons = ref();
const propD_miasEmp = ref();
const propD_factorEmp = ref();

const secAdd_propD_nsaData = ref([]);
const secAdd_propD_cycleNo = ref();
const secAdd_propD_codeNo = ref();
const secAdd_propD_rejectReasons = ref();
const secAdd_propD_miasEmp = ref();
const secAdd_propD_factorEmp = ref();
const filteredAggregateID = ref();
const nsaData_aggID = ref();
const secAdd_propD_jhCurveFurnaceName = ref();
const secAdd_propD_jhCurveCodeNo = ref();
const secAdd_propD_jhCurveRemarks = ref();

const current_setNo = ref(1);

const ok = "Status OK";
const reject = "REJECT, N.G";
const hold = "HOLD, N.G";
const within = "within internal specs";

const perform = "Perform additional samples from 2nd layer & beside the normal samples of all boxes.";
const noRejIns = "No reject instructions";

const lotReject = "THIS LOT IS REJECT";
const lotPass = "THIS LOT IS PASS";
const lotHold = "THIS LOT IS HOLD";

const iHcOK = "iHc variance OK";
const iHcNG = "Process right side with iHc variance more than 1500 Oe";

const rejectOKNG = ref([]);
const rejectInstruction = ref(null);
const rejectLotRemarks = ref([]);
const rejectiHcRemarks = ref(null);

const sampleWithVariances = ref([]);
const getHighestSampleVariance = ref();

//Variables for ave max min ng counter

const aveBr = ref(null);
const aveiHc = ref(null);
const aveiHk = ref(null);
const aveBHMax = ref(null);
const aveiHr95 = ref(null);
const aveiHr98 = ref(null);
const aveiHciHk = ref(null);
const aveBr4pai = ref(null);
const avebHc = ref(null);
const aveSquareness = ref(null);
const ave4paild = ref(null);
const ave4pails = ref(null);
const ave4paila = ref(null);
const minBr = ref(null);
const miniHc = ref(null);
const miniHk = ref(null);
const minBHMax = ref(null);
const miniHr95 = ref(null);
const miniHr98 = ref(null);
const miniHciHk = ref(null);
const minBr4pai = ref(null);
const minbHc = ref(null);
const minSquareness = ref(null);
const min4paild = ref(null);
const min4pails = ref(null);
const min4paila = ref(null);
const maxBr = ref(null);
const maxiHc = ref(null);
const maxiHk = ref(null);
const maxBHMax = ref(null);
const maxiHr95 = ref(null);
const maxiHr98 = ref(null);
const maxiHciHk = ref(null);
const maxBr4pai = ref(null);
const maxbHc = ref(null);
const maxSquareness = ref(null);
const max4paild = ref(null);
const max4pails = ref(null);
const max4paila = ref(null);
const ngBr = ref(null);
const ngiHc = ref(null);
const ngiHk = ref(null);
const ngBHMax = ref(null);
const ngiHr95 = ref(null);
const ngiHr98 = ref(null);
const ngiHciHk = ref(null);
const ngBr4pai = ref(null);
const ngbHc = ref(null);
const ngSquareness = ref(null);
const ng4paild = ref(null);
const ng4pails = ref(null);
const ng4paila = ref(null);

// Grouping variables into arrays
const aggAveValues = ref([
    aveBr,
    aveiHc,
    aveiHk,
    aveBHMax,
    aveiHr95,
    aveiHr98,
    aveiHciHk,
    aveBr4pai,
    avebHc,
    aveSquareness,
    ave4paild,
    ave4pails,
    ave4paila,
]);

const aggMaxValues = ref([
    maxBr,
    maxiHc,
    maxiHk,
    maxBHMax,
    maxiHr95,
    maxiHr98,
    maxiHciHk,
    maxBr4pai,
    maxbHc,
    maxSquareness,
    max4paild,
    max4pails,
    max4paila,
]);

const aggMinValues = ref([
    minBr,
    miniHc,
    miniHk,
    minBHMax,
    miniHr95,
    miniHr98,
    miniHciHk,
    minBr4pai,
    minbHc,
    minSquareness,
    min4paild,
    min4pails,
    min4paila,
]);

const aggNGCounts = ref([
    ngBr,
    ngiHc,
    ngiHk,
    ngBHMax,
    ngiHr95,
    ngiHr98,
    ngiHciHk,
    ngBr4pai,
    ngbHc,
    ngSquareness,
    ng4paild,
    ng4pails,
    ng4paila,
]);

const getAllIDValues = ref([]);

// Variables for aggregate

const getAllBrValues = ref([]);
const getAllBrRemarks = ref([]);
const getAlliHcValues = ref([]);
const getAlliHcRemarks = ref([]);
const getAlliHkValues = ref([]);
const getAlliHkRemarks = ref([]);
const getAllBHMaxValues = ref([]);
const getAllBHMaxRemarks = ref([]);
const getAlliHr95Values = ref([]);
const getAlliHr95Remarks = ref([]);
const getAlliHr98Values = ref([]);
const getAlliHr98Remarks = ref([]);
const getAlliHciHkValues = ref([]);
const getAlliHciHkRemarks = ref([]);
const getAllBr4paiValues = ref([]);
const getAllBr4paiRemarks = ref([]);
const getAllbHcValues = ref([]);
const getAllbHcRemarks = ref([]);
const getAllSquarenessValues = ref([]);
const getAllSquarenessRemarks = ref([]);
const getAll4paildValues = ref([]);
const getAll4paildRemarks = ref([]);
const getAll4pailsValues = ref([]);
const getAll4pailsRemarks = ref([]);
const getAll4pailaValues = ref([]);
const getAll4pailaRemarks = ref([]);

const nsa_getAllIDValues = ref([]);

// Variables for aggregate

const nsa_getAllBrValues = ref([]);
const nsa_getAllBrRemarks = ref([]);
const nsa_getAlliHcValues = ref([]);
const nsa_getAlliHcRemarks = ref([]);
const nsa_getAlliHkValues = ref([]);
const nsa_getAlliHkRemarks = ref([]);
const nsa_getAllBHMaxValues = ref([]);
const nsa_getAllBHMaxRemarks = ref([]);
const nsa_getAlliHr95Values = ref([]);
const nsa_getAlliHr95Remarks = ref([]);
const nsa_getAlliHr98Values = ref([]);
const nsa_getAlliHr98Remarks = ref([]);
const nsa_getAlliHciHkValues = ref([]);
const nsa_getAlliHciHkRemarks = ref([]);
const nsa_getAllBr4paiValues = ref([]);
const nsa_getAllBr4paiRemarks = ref([]);
const nsa_getAllbHcValues = ref([]);
const nsa_getAllbHcRemarks = ref([]);
const nsa_getAllSquarenessValues = ref([]);
const nsa_getAllSquarenessRemarks = ref([]);
const nsa_getAll4paildValues = ref([]);
const nsa_getAll4paildRemarks = ref([]);
const nsa_getAll4pailsValues = ref([]);
const nsa_getAll4pailsRemarks = ref([]);
const nsa_getAll4pailaValues = ref([]);
const nsa_getAll4pailaRemarks = ref([]);

const tableLayerColumnHeaders = ref([
    {name: 'Date', colspan: 1},
    {name: 'Code\u00A0No', colspan: 1},
    {name: 'Order\u00A0No', colspan: 1},
    {name: 'Type', colspan: 1},
    {name: 'Lot\u00A0No', colspan: 1},
    {name: ' ', colspan: 1},
    {name: ' ', colspan: 1},
    {name: 'Furnace\u00A0No', colspan: 1},
    {name: 'Coating\u00A0No', colspan: 1},
    {name: 'Zone', colspan: 1},
    {name: 'Pass\u00A0No', colspan: 1},
    {name: 'Br', colspan: 2},
    {name: 'iHc', colspan: 2},
    {name: 'iHk', colspan: 2},
    {name: 'BHMax', colspan: 2},
    {name: 'Hr95', colspan: 2},
    {name: 'Hr98', colspan: 2},
    {name: 'iHciHk', colspan: 2},
    {name: 'Br4pai', colspan: 2},
    {name: 'bHc', colspan: 2},
    {name: 'Squareness', colspan: 2},
    {name: '4paild', colspan: 2},
    {name: '4pails', colspan: 2},
    {name: '4paila', colspan: 2},
    {name: 'Tracer', colspan: 1},
]);

 //2nd table cell data dynamic
const secondTableLayerColumnHeaders = ref([
    {name: '', colspan: 1},
    {name: 'Br', colspan: 1},
    {name: 'iHc', colspan: 1},
    {name: 'iHk', colspan: 1},
    {name: 'BHMax', colspan: 1},
    {name: 'iHr95', colspan: 1},
    {name: 'iHr98', colspan: 1},
    {name: 'iHciHk', colspan: 1},
    {name: 'Br4pai', colspan: 1},
    {name: 'bHc', colspan: 1},
    {name: 'Squareness', colspan: 1},
    {name: '4paild', colspan: 1},
    {name: '4pails', colspan: 1},
    {name: '4paila', colspan: 1},
]);

const nsa_tableLayerColumnHeaders = ref([
    {name: 'Date', colspan: 1},
    {name: 'Code\u00A0No', colspan: 1},
    {name: 'Order\u00A0No', colspan: 1},
    {name: 'Type', colspan: 1},
    {name: 'Lot\u00A0No', colspan: 1},
    {name: 'Furnace\u00A0No', colspan: 1},
    {name: 'Coating\u00A0No', colspan: 1},
    {name: 'Zone', colspan: 1},
    {name: 'Pass\u00A0No', colspan: 1},
    {name: 'Br', colspan: 2},
    {name: 'iHc', colspan: 2},
    {name: 'iHk', colspan: 2},
    {name: 'BHMax', colspan: 2},
    {name: 'Hr95', colspan: 2},
    {name: 'Hr98', colspan: 2},
    {name: 'iHciHk', colspan: 2},
    {name: 'Br4pai', colspan: 2},
    {name: 'bHc', colspan: 2},
    {name: 'Squareness', colspan: 2},
    {name: '4paild', colspan: 2},
    {name: '4pails', colspan: 2},
    {name: '4paila', colspan: 2},
    {name: 'Tracer', colspan: 1},
]);

//Variables for ave max min ng counter

const secAdd_aveBr = ref(null);
const secAdd_aveiHc = ref(null);
const secAdd_aveiHk = ref(null);
const secAdd_aveBHMax = ref(null);
const secAdd_aveiHr95 = ref(null);
const secAdd_aveiHr98 = ref(null);
const secAdd_aveiHciHk = ref(null);
const secAdd_aveBr4pai = ref(null);
const secAdd_avebHc = ref(null);
const secAdd_aveSquareness = ref(null);
const secAdd_ave4paild = ref(null);
const secAdd_ave4pails = ref(null);
const secAdd_ave4paila = ref(null);
const secAdd_minBr = ref(null);
const secAdd_miniHc = ref(null);
const secAdd_miniHk = ref(null);
const secAdd_minBHMax = ref(null);
const secAdd_miniHr95 = ref(null);
const secAdd_miniHr98 = ref(null);
const secAdd_miniHciHk = ref(null);
const secAdd_minBr4pai = ref(null);
const secAdd_minbHc = ref(null);
const secAdd_minSquareness = ref(null);
const secAdd_min4paild = ref(null);
const secAdd_min4pails = ref(null);
const secAdd_min4paila = ref(null);
const secAdd_maxBr = ref(null);
const secAdd_maxiHc = ref(null);
const secAdd_maxiHk = ref(null);
const secAdd_maxBHMax = ref(null);
const secAdd_maxiHr95 = ref(null);
const secAdd_maxiHr98 = ref(null);
const secAdd_maxiHciHk = ref(null);
const secAdd_maxBr4pai = ref(null);
const secAdd_maxbHc = ref(null);
const secAdd_maxSquareness = ref(null);
const secAdd_max4paild = ref(null);
const secAdd_max4pails = ref(null);
const secAdd_max4paila = ref(null);
const secAdd_ngBr = ref(null);
const secAdd_ngiHc = ref(null);
const secAdd_ngiHk = ref(null);
const secAdd_ngBHMax = ref(null);
const secAdd_ngiHr95 = ref(null);
const secAdd_ngiHr98 = ref(null);
const secAdd_ngiHciHk = ref(null);
const secAdd_ngBr4pai = ref(null);
const secAdd_ngbHc = ref(null);
const secAdd_ngSquareness = ref(null);
const secAdd_ng4paild = ref(null);
const secAdd_ng4pails = ref(null);
const secAdd_ng4paila = ref(null);

//Variables for ave max min ng counter end

const specialModelsForGxFormat = ref(['ROB0C79G']); //ROB MODEL SPECIAL GX

//UI Dynamic Color adjustments

const adjustColor_rejectOKNG = (arrayString_OKNG) => {
    //console.log(arrayString_OKNG); // Log the value to see what it is
    if (arrayString_OKNG && arrayString_OKNG.includes) {
        return arrayString_OKNG.includes("Status OK") ? "text-green-500" : "text-red-500";
    }
    return "text-yellow-500"; // or any fallback class
}

const adjustColor_rejectLotRemarks = (arrayString_lotRem) => {
    return arrayString_lotRem.includes("THIS LOT IS PASS") ? "text-green-600" : "text-red-500";
}

const adjustColor_rejectInstructions = computed(() => {
    // Check if rejectInstruction is not null and is a string before calling includes
    return rejectInstruction.value && rejectInstruction.value.includes("No reject instructions")
        ? "text-blue-600"
        : "text-green-500";
});

const adjustColor_rejectiHc = computed(() => {
    // Check if rejectiHcRemarks is not null and is a string before calling includes
    return rejectiHcRemarks.value && rejectiHcRemarks.value.includes("iHc variance OK")
        ? "text-blue-600"
        : "text-red-500";
});

const adjustColor_iHcValue = computed(() => {
    if(getHighestSampleVariance.value <= 1500){
        return "text-blue-500";
    }else{
        return "text-red-500";
    }
});

const undoStampLabel = computed(() => {
    const labels = {
        prepared: 'Prepared By Stamp',
        checked: 'Checked By Stamp',
        approved: 'Approved By Stamp',
    }

    return labels[currentUndoType.value] || 'Stamp'
})

//UI Dynamic Color adjustments end

const massProd_qty = ref('');
const massProd_WT = ref('');
const massProd_boxNo = ref('');
const massProd_coating = ref('');
const massProd_rawMatCode = ref('');
const currentBoxIndex = ref(0);
const allBoxesData = ref([]);

const massProd_letter = ref(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K']);

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
        ///console.log('showGX:', showGX.value);
        //console.log('reportGX_iHcMinimum:', reportGX_iHcMinimum.value);
        //console.log('reportGX_iHcStandard:', reportGX_iHcStandard.value);

        if ((noteReasonForReject.value.includes('- N.G iHc') || specialModelsForGxFormat.value.includes(jhCurveActualModel.value)) && showGX.value === true &&
            reportGX_iHcMinimum.value !== null && reportGX_iHcStandard.value !== null) {
            if (reportGX_iHcMinimum.value < reportGX_iHcStandard.value) {
                    //console.log('GX iHc minimum is below standard — setting NG/REJECT');
                    reportSMPJudgement.value = 'REJECT';
            }else if(specialModelsForGxFormat.value.includes(jhCurveActualModel.value)){
                    //console.log('GX iHc minimum meets or exceeds standard — setting OK/HOLD');
                    reportSMPJudgement.value = 'PASSED';
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

const formatDateTime = (value) => {
    if (!value) return '-';

    return new Date(value).toLocaleString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    });
};

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

        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "generateReport",
            "Failed to generate report. Please try again."
        );
    }
};

const checkSpecialJudgement = async () => {
    console.log('Entered Special Judgement function');
    const responseGetVTData = await axios.get('/api/vt-models');
    const fetchAllVT = responseGetVTData.data;
    MODELS_SHOW_VT_DATA.value = fetchAllVT.map(item => item.model_name);
    //console.log('VT MODELS: ',MODELS_SHOW_VT_DATA.value);
    const responseGetCPKIHCData = await axios.get('/api/cpk-ihc-models');
    const fetchAllCPKIHC = responseGetCPKIHCData.data;
    MODELS_SHOW_CPK.value = fetchAllCPKIHC.map(item => item.model_name);
    //console.log('CPK MODELS: ',MODELS_SHOW_CPK.value);
    const responseGetGXData = await axios.get('/api/gx-models');
    const fetchAllGX = responseGetGXData.data;
    MODELS_SHOW_GX.value = fetchAllGX.map(item => item.model_name);
    console.log('GX MODELS: ',MODELS_SHOW_GX.value);
    const responseGetTTMNCData = await axios.get('/api/ttmnc-models');
    const fetchAllTTMNC = responseGetTTMNCData.data;
    MODELS_1X1X1_NO_CORNER.value = fetchAllTTMNC.map(item => item.model_name);
    //console.log('TTMNC MODELS: ',MODELS_1X1X1_NO_CORNER.value);
    const responseGetBHData = await axios.get('/api/bh-models');
    const fetchAllBH = responseGetBHData.data;
    MODELS_SHOW_BH.value = fetchAllBH.map(item => item.model_name);
    //console.log('BH MODELS: ',MODELS_SHOW_BH.value);
    const responseGetROBData = await axios.get('/api/rob-models');
    const fetchAllROB = responseGetROBData.data;
    MODELS_SHOW_ROB.value = fetchAllROB.map(item => item.model_name);
    //console.log('ROB MODELS: ',MODELS_SHOW_ROB.value);

    const model = jhCurveActualModel.value;

    const hasNGihc = noteReasonForReject.value.includes('- N.G iHc');
    isTTM_model.value = jhCurveActualModel.value.includes("TTM");
    if (!hasNGihc) return;

    // === Logic Blocks ===
    //console.log('--- Logic Evaluation Start ---');
    console.log('Model:', model);
    //console.log('VT Sample Qty:', reportVT_samplesQty.value);
    //console.log('BH Sample Qty:', reportBH_sampleQty.value);

    // VT Data
    if (MODELS_SHOW_VT_DATA.value.includes(model) && reportVT_samplesQty.value > 0) {
        showVTData.value = true;
        showVTData_default.value = false;
        console.log('[VT] Showing VT Data (sample qty > 0)');
    } else if (MODELS_SHOW_VT_DATA.value.includes(model)) {
        showVTData.value = false;
        showVTData_default.value = true;
        console.log('[VT] Showing default VT layout (sample qty = 0)');
    }

    // BH Data
    if (MODELS_SHOW_BH.value.includes(model) && reportBH_sampleQty.value > 0) {
        showBHData.value = true;
        showBHData_default.value = false;
        //console.log('[BH] Showing BH Data (sample qty > 0)');
    } else if (MODELS_SHOW_BH.value.includes(model)) {
        showBHData.value = false;
        showBHData_default.value = true;
        //console.log('[BH] Showing default BH layout (sample qty = 0)');
    }

    // 1x1x1 Data (TTM)
    if (model.includes("TTM")) {
        show1x1x1Data_withoutCorner.value = true;
        show1x1x1Data_Corner.value = true;
        //console.log('[TTM] Model includes TTM → Enabling 1x1x1 sections');

        if (MODELS_1X1X1_NO_CORNER.value.includes(model)) {
            show1x1x1Data_Corner.value = false;
            //console.log('[TTM] Corner data disabled for this model');
        }
    }

    // Flags
    if (MODELS_SHOW_CPK.value.includes(model)) {
        showCpkFrom_iHc.value = true;
        //console.log('[CPK] CPK enabled');
    }
    if (MODELS_SHOW_GX.value.includes(model) || specialModelsForGxFormat.value.includes(model)) {
        showGX.value = true;
        console.log('[GX] GX enabled');
    }
    if (MODELS_SHOW_ROB.value.includes(model)) {
        showROB.value = true;
        //console.log('[ROB] ROB enabled');
    }

    //console.log('--- Logic Evaluation End ---');

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

const getControlSheetData = async () => {
    try{
        const response = await axios.get(`/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}/layer-by-serial/${currentSerialSelected.value}`);
        const controlSheet = response.data.layer_data;
        reportTotalQuantity.value = controlSheet[12].data.A ?? controlSheet[12].data.B ?? 0;

        //console.log('reportTotalQuantity: ', reportTotalQuantity.value);

    }catch(error){
        console.log('Failed to get response Control Sheet Data: ', error);
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getControlSheetData",
            "Failed to get response Control Sheet Data"
        );
    }
}

const getUndoHistory = async () => {
    undoHistoryLoading.value = true;
    undoHistoryError.value = null;

    try {
        const serialNo = currentSerialSelected.value;

        if (!serialNo) {
            throw new Error('Serial number is missing.');
        }

        const response = await axios.get(
            `/api/stamp-undo-history/by-serial/${serialNo}`
        );

        undoHistory.value = response.data;

        console.log('Undo history fetched successfully.', response.data);

    } catch (error) {
        undoHistoryError.value = error.response?.data?.message || error.message;
        console.error('Failed to fetch undo history:', error);
    } finally {
        undoHistoryLoading.value = false;
    }
};

const fetchAllData = async () => {
    try {
        const serial = currentSerialSelected.value;
        if (!serial) throw new Error("No serial selected.");

        const responseTpm = await axios.get(`/api/tpmdata?serial=${serial}`);
        let TPM_Data = responseTpm.data.data[0];
        let TPM_Data2 = responseTpm.data.data;
        let tpmCat = TPM_Data.category;
        selectedMassProd.value = TPM_Data.mass_prod;
        selectedFurnace.value = TPM_Data.furnace;
        selectedLayer.value = TPM_Data.layer_no;
        propD_tpmData.value = TPM_Data2;
        propD_cycleNo.value = TPM_Data.sintering_furnace_no;
        propD_codeNo.value = TPM_Data.code_no;
        //console.log("Mass Prod number: ", selectedMassProd.value, " Layer no: ", selectedLayer.value);
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
        //const responseControlSheet = await axios.get(`/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}/layer-by-serial/${currentSerialSelected.value}`);

        getTpmModel.value = modelData;

        fetchMaterialCode.value = modelData[0].code_no || throwError("Missing material code.");
        jhCurveActualModel.value = tpmCat.actual_model;
        fetchActualModel.value = jhCurveActualModel.value;
        jhCurveLotNo.value = tpmCat.jhcurve_lotno;
        //propD_miasEmp.value = tomCat.
        await getControlSheetData();
        setJudgmentFlags(modelData);
        await resolveFurnaceAndLayer(modelData[0]);

        parseAggregates(rawData);

        const modelMatched = await matchInspectionModel(fetchActualModel.value);
        if (!modelMatched) return;

        // Extract individual values from tpmData for aggregate
        getAllIDValues.value = propD_tpmData.value.map(item => item.id);
        getAllBrValues.value = propD_tpmData.value.map(item => item.Br || null);
        getAllBrRemarks.value = propD_tpmData.value.map(item => item.remark.Br_remarks || null);
        getAlliHcValues.value = propD_tpmData.value.map(item => item.iHc || null);
        getAlliHcRemarks.value = propD_tpmData.value.map(item => item.remark.iHc_remarks || null);
        getAlliHkValues.value = propD_tpmData.value.map(item => item.iHk || null);
        getAlliHkRemarks.value = propD_tpmData.value.map(item => item.remark.iHk_remarks || null);
        getAllBHMaxValues.value = propD_tpmData.value.map(item => item.BHMax || null);
        getAllBHMaxRemarks.value = propD_tpmData.value.map(item => item.remark.BHMax_remarks || null);
        getAlliHr95Values.value = propD_tpmData.value.map(item => item.iHr95 || null);
        getAlliHr95Remarks.value = propD_tpmData.value.map(item => item.remark.iHr95_remarks || null);
        getAlliHr98Values.value = propD_tpmData.value.map(item => item.iHr98 || null);
        getAlliHr98Remarks.value = propD_tpmData.value.map(item => item.remark.iHr98_remarks || null);
        getAlliHciHkValues.value = propD_tpmData.value.map(item => item.iHkiHc || null);
        getAlliHciHkRemarks.value = propD_tpmData.value.map(item => item.remark.iHkiHc_remarks || null);
        getAllBr4paiValues.value = propD_tpmData.value.map(item => item.Br4pai || null);
        getAllBr4paiRemarks.value = propD_tpmData.value.map(item => item.remark.Br4pai_remarks || null);
        getAllbHcValues.value = propD_tpmData.value.map(item => item.bHc || null);
        getAllbHcRemarks.value = propD_tpmData.value.map(item => item.remark.bHc_remarks || null);
        getAllSquarenessValues.value = propD_tpmData.value.map(item => item.Squareness || null);
        getAllSquarenessRemarks.value = propD_tpmData.value.map(item => item.remark.Squareness_remarks || null);
        getAll4paildValues.value = propD_tpmData.value.map(item => item["4paiId"] || null);
        getAll4paildRemarks.value = propD_tpmData.value.map(item => item.remark["4paiId_remarks"] || null);
        getAll4pailsValues.value = propD_tpmData.value.map(item => item["4paiIs"] || null);
        getAll4pailsRemarks.value = propD_tpmData.value.map(item => item.remark["4paiIs_remarks"] || null);
        getAll4pailaValues.value = propD_tpmData.value.map(item => item["4paiIa"] || null);
        getAll4pailaRemarks.value = propD_tpmData.value.map(item => item.remark["4paiIa_remarks"] || null);

        //console.log('gettheIDs: ', getAllIDValues.value);
        //console.log('tpmRemarks: ', tpmRemarks.value);

        //get average function
        const calculateAverage = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return 0
            if (numbers.length === 0) return 0;

            // Calculate the sum and divide by the count
            const sum = numbers.reduce((total, value) => total + value, 0);
            const average = sum / numbers.length;

            // Check the maximum number of decimals present in the input
            const maxDecimals = Math.max(
                ...numbers.map(value => {
                    const parts = value.toString().split(".");
                    return parts[1] ? parts[1].length : 0; // Length of the decimal part
                })
            );

            // Round the average to match the maximum number of decimals in the input
            const factor = Math.pow(10, maxDecimals);
            return Math.round(average * factor) / factor;
        };
        //get maximum function
        const getMaxValue = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return null
            if (numbers.length === 0) return null;

            // Return the highest value using Math.max
            return Math.max(...numbers);
        };
        //get minimum function
        const getMinValue = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return null
            if (numbers.length === 0) return null;

            // Return the lowest value using Math.min
            return Math.min(...numbers);
        };
        //get Sample with Variance data
        const calculateVariance = (numericStringsArray, maxValue) => {
            // Convert the array of numeric strings to numbers
            const numbers = numericStringsArray
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Filter out invalid numbers

            // Subtract each value from maxValue and return the resulting array
            return numbers.map(num => maxValue - num);
        };

        // Function to sum up all the data in an array
        const calculateSum = (numericStringsArray) => {
            // Convert the array of numeric strings to numbers
            const numbers = numericStringsArray
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Filter out invalid numbers

            // Sum up all the valid numbers in the array
            return numbers.reduce((sum, num) => sum + num, 0);
        };


        //average values
        aveBr.value = calculateAverage(getAllBrValues.value);
        aveiHc.value = calculateAverage(getAlliHcValues.value);
        aveiHk.value = calculateAverage(getAlliHkValues.value);
        aveBHMax.value = calculateAverage(getAllBHMaxValues.value);
        aveiHr95.value = calculateAverage(getAlliHr95Values.value);
        aveiHr98.value = calculateAverage(getAlliHr98Values.value);
        aveiHciHk.value = calculateAverage(getAlliHciHkValues.value);
        aveBr4pai.value = calculateAverage(getAllBr4paiValues.value);
        avebHc.value = calculateAverage(getAllbHcValues.value);
        aveSquareness.value = calculateAverage(getAllSquarenessValues.value);
        ave4paild.value = calculateAverage(getAll4paildValues.value);
        ave4pails.value = calculateAverage(getAll4pailsValues.value);
        ave4paila.value = calculateAverage(getAll4pailaValues.value);

        //console.log("iHciHk average value: ", aveiHciHk.value);

        // Minimum values
        minBr.value = getMinValue(getAllBrValues.value);
        miniHc.value = getMinValue(getAlliHcValues.value);
        miniHk.value = getMinValue(getAlliHkValues.value);
        minBHMax.value = getMinValue(getAllBHMaxValues.value);
        miniHr95.value = getMinValue(getAlliHr95Values.value);
        miniHr98.value = getMinValue(getAlliHr98Values.value);
        miniHciHk.value = getMinValue(getAlliHciHkValues.value);
        minBr4pai.value = getMinValue(getAllBr4paiValues.value);
        minbHc.value = getMinValue(getAllbHcValues.value);
        minSquareness.value = getMinValue(getAllSquarenessValues.value);
        min4paild.value = getMinValue(getAll4paildValues.value);
        min4pails.value = getMinValue(getAll4pailsValues.value);
        min4paila.value = getMinValue(getAll4pailaValues.value);

        // Maximum values
        maxBr.value = getMaxValue(getAllBrValues.value);
        maxiHc.value = getMaxValue(getAlliHcValues.value);
        maxiHk.value = getMaxValue(getAlliHkValues.value);
        maxBHMax.value = getMaxValue(getAllBHMaxValues.value);
        maxiHr95.value = getMaxValue(getAlliHr95Values.value);
        maxiHr98.value = getMaxValue(getAlliHr98Values.value);
        maxiHciHk.value = getMaxValue(getAlliHciHkValues.value);
        maxBr4pai.value = getMaxValue(getAllBr4paiValues.value);
        maxbHc.value = getMaxValue(getAllbHcValues.value);
        maxSquareness.value = getMaxValue(getAllSquarenessValues.value);
        max4paild.value = getMaxValue(getAll4paildValues.value);
        max4pails.value = getMaxValue(getAll4pailsValues.value);
        max4paila.value = getMaxValue(getAll4pailaValues.value);

        //NG count values
        ngBr.value = calculateSum(getAllBrRemarks.value);
        ngiHc.value = calculateSum(getAlliHcRemarks.value);
        ngiHk.value = calculateSum(getAlliHkRemarks.value);
        ngBHMax.value = calculateSum(getAllBHMaxRemarks.value);
        ngiHr95.value = calculateSum(getAlliHr95Remarks.value);
        ngiHr98.value = calculateSum(getAlliHr98Remarks.value);
        ngiHciHk.value = calculateSum(getAlliHciHkRemarks.value);
        ngBr4pai.value = calculateSum(getAllBr4paiRemarks.value);
        ngbHc.value = calculateSum(getAllbHcRemarks.value);
        ngSquareness.value = calculateSum(getAllSquarenessRemarks.value);
        ng4paild.value = calculateSum(getAll4paildRemarks.value);
        ng4pails.value = calculateSum(getAll4pailsRemarks.value);
        ng4paila.value = calculateSum(getAll4pailaRemarks.value);

        sampleWithVariances.value = calculateVariance(getAlliHcValues.value, maxiHc.value);

        // Convert each item in sampleWithVariances to a string
        const sampleWithVariancesAsString = sampleWithVariances.value.map(String);
        //Getting the highest sample with variances (iHc Variance)
        getHighestSampleVariance.value = getMaxValue(sampleWithVariancesAsString);

        //Conditions for NG remarks

        const addRejectLotRemarks = (lotRemarks) => {
            if(!rejectLotRemarks.value.includes(lotRemarks + "  ")){
                rejectLotRemarks.value.push(lotRemarks + "  ");
            }
        }

        if(getHighestSampleVariance.value <= 1500){
            rejectiHcRemarks.value = iHcOK;
        }else{
            rejectiHcRemarks.value = iHcNG;
        }

        if(ngBr.value == 0 && ngiHc.value == 0 && ngiHk.value == 0 && ngBHMax.value == 0 && ngiHr95.value == 0 && ngiHr98.value == 0 && ngiHciHk.value == 0 && ngBr4pai.value == 0 && ngbHc.value == 0){
            rejectOKNG.value.push(ok);
            addRejectLotRemarks(lotPass);
            rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotReject + "  ");
            rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotHold + "  ");
            rejectInstruction.value = noRejIns;
        }else{

            if(ngBr.value > 0){
                rejectOKNG.value.push("Br " + within + "  ");
                addRejectLotRemarks(lotPass);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotReject + "  ");
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotHold + "  ");
            }
            if(ngiHc.value > 0) {
                rejectOKNG.value.push("iHc " + reject + "  ");
                addRejectLotRemarks(lotReject);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
                rejectInstruction.value = perform;
            }
            if(ngiHk.value > 0) {
                rejectOKNG.value.push("iHk " + reject + "  ");
                addRejectLotRemarks(lotReject);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
                rejectInstruction.value = perform;
            }
            if(ngBHMax.value > 0) {
                rejectOKNG.value.push("BHMax " + reject + "  ");
                addRejectLotRemarks(lotReject);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
            }
            if(ngiHr95.value > 0) {
                //noteReasonForReject.value.push('- N.G Hr95');
                rejectOKNG.value.push("Hr95 " + hold + "  ");
                addRejectLotRemarks(lotHold);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
            }
            if(ngiHr98.value > 0) {
                //noteReasonForReject.value.push('- N.G Hr98');
                rejectOKNG.value.push("Hr98 " + hold + "  ");
                addRejectLotRemarks(lotHold);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
            }
            if(ngiHciHk.value > 0) {
                rejectOKNG.value.push("iHciHk " + hold + "  ");
                addRejectLotRemarks(lotHold);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
            }
            if(ngBr4pai.value > 0) {
                rejectOKNG.value.push("Br4pai " + hold + "  ");
                addRejectLotRemarks(lotHold);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
            }
            if(ngbHc.value > 0) {
                rejectOKNG.value.push("bHc " + hold + "  ");
                addRejectLotRemarks(lotHold);
                rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
            }
        }

        await getSecAllSetsData();
        await fetchMiasFactor_category();

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
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "fetchAllData",
            "fetchAllData returned empty or invalid payload"
        );
        console.error("fetchAllData failed:", error);
        showNotification2("Data is incomplete for this serial. Please rerun the data.");
        exitReport();
        throw new Error("fetchAllData returned empty or invalid payload");
    }
};

const fetchMiasFactor_category = async () => {
    try {
        const responseTPM = await axios.get(`/api/tpmdata?serial=${currentSerialSelected.value}`);

        const tpmCat = responseTPM.data?.data?.[0]?.category;

        if (
            !tpmCat ||
            tpmCat.factor_emp == null ||
            tpmCat.mias_emp == null
            ) {
            await fetchMiasFactor();
            return;
        }

        propD_factorEmp.value = tpmCat.factor_emp;
        propD_miasEmp.value = tpmCat.mias_emp;

    } catch (error) {
        await userErrorLogging(
        {
            message: error.message,
            code: error.code ?? null,
            response: error.response?.data ?? null,
            payload: error.response?.data ?? null,
        },
        "fetchMiasFactor_category",
        "Failed to get Mias & Factor Employee"
        );
    }
};

const fetchMiasFactor = async () => {
    try{
        const response = await axios.get(`/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}`);
        const massProd = response.data;
        propD_factorEmp.value = massProd.factor_emp;
        propD_miasEmp.value = massProd.mias_emp;
    }catch(error){
        //console.log('Failed to get Mias & Factor Employee');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "fetchMiasFactor",
            "Failed to get Mias & Factor Employee"
        );
    }
}


/*const getSecHighestSetValue = async() => {
    try{
        const responseCheckNsa = await axios.get("/api/nsadata");
        //console.log('response check NSA: ',responseCheckNsa.data.data);
        const nsaData = responseCheckNsa.data.data["NSAData"] || [];
        //console.log('NSA data: ',nsaData);
        const nsafilteredData = nsaData.filter(item => item.serial_no == currentSerialSelected.value);
        //console.log('NSA data filtered by serial: ',nsafilteredData);

        if(nsafilteredData.length > 0) {
            const maxSetNo = Math.max(...nsafilteredData.map(item => item.set_no));
            current_setNo.value = maxSetNo;
            isSecAdditional.value = true;
            toast.info('SEC additional data is detected in this report');
        } else {
            isSecAdditional.value = false;
            toast.warning('No SEC additional data detected in this report');
            console.warn('No matching NSA data for this serial number.');
        }

        console.log('Highest set value: ', current_setNo.value);
        await nsa_showData();

    }catch(error){
        console.warn("404 No data detected or another error check here -> ",error)
    }
}*/

const secDataBySet = ref({}); // { set_no: [array of items] }
const availableSetNumbers = ref([]); // To know which sets exist for the serial

const getSecAllSetsData = async () => {
    try {
        // Fetch all sets available for the current serial
        const response = await axios.get("/api/nsadata?serial=" + currentSerialSelected.value);
        const allNsaData = response.data.data["NSAData"] || [];

        if (!allNsaData.length) {
            isSecAdditional.value = false;
            toast.warning("No SEC additional data detected for this serial number");
            secDataBySet.value = {};
            availableSetNumbers.value = [];
            return;
        }

        isSecAdditional.value = true;
        toast.info("SEC additional data is detected in this report");

        // Group by set_no to build available set selector
        const grouped = allNsaData.reduce((acc, item) => {
            if (!acc[item.set_no]) acc[item.set_no] = [];
            acc[item.set_no].push(item);
            return acc;
        }, {});

        secDataBySet.value = grouped;
        availableSetNumbers.value = Object.keys(grouped)
            .map(Number)
            .sort((a, b) => a - b);

        // Default to highest set
        current_setNo.value = Math.max(...availableSetNumbers.value);

        // Load data for that set via API
        await nsa_showData(current_setNo.value);

    } catch (error) {
        console.warn("Error fetching SEC data ->", error);
        secDataBySet.value = {};
        availableSetNumbers.value = [];
        isSecAdditional.value = false;
    }
};


const nsaData = ref([]);
const nsa_combinedData = ref([]);

const propsList = [
    'Br', 'iHc', 'iHk', 'BHMax', 'iHr95', 'iHr98', 'iHkiHc',
    'Br4pai', 'bHc', 'Squareness', '4paiId', '4paiIs', '4paiIa', 'Tracer'
];

const nsa_showData = async (setNo = current_setNo.value) => {
    try {
        // Fetch only the data for current serial and set
        const response = await axios.get(
            `/api/nsadata?serial=${currentSerialSelected.value}&set=${setNo}`
        );
        const setData = response.data.data["NSAData"] || [];

        if (!setData.length) {
            console.warn(`No data found for serial ${currentSerialSelected.value} and set ${setNo}`);
            nsaData.value = [];
            nsa_combinedData.value = [];
            return;
        }

        nsaData.value = setData;
        nsa_combinedData.value = setData;

        const nsaCat = setData[0].category;

        console.log('DEGUG setData: ', nsaCat);

        // Extract aggregate ID as before
        filteredAggregateID.value = setData.filter(
            item => item.nsa_serial == currentSerialSelected.value && item.nsa_set == setNo
        );
        nsaData_aggID.value = filteredAggregateID.value[0]?.id || null;

        // Fill the prop values for display
        secAdd_propD_jhCurveFurnaceName.value = setData[0]?.sintering_furnace_no ?? "No data found";
        secAdd_propD_jhCurveCodeNo.value = setData[0]?.code_no ?? "No data found";
        secAdd_propD_jhCurveRemarks.value = setData[0]?.set_name ?? "";

        // Map all the individual arrays for averages, max, min, etc.
        nsa_getAllIDValues.value = setData.map(item => item.id);
        nsa_getAllBrValues.value = setData.map(item => item.Br || null);
        nsa_getAllBrRemarks.value = setData.map(item => item.remark?.Br_remarks ?? null);
        nsa_getAlliHcValues.value = setData.map(item => item.iHc || null);
        nsa_getAlliHcRemarks.value = setData.map(item => item.remark?.iHc_remarks ?? null);
        nsa_getAlliHkValues.value = setData.map(item => item.iHk || null);
        nsa_getAlliHkRemarks.value = setData.map(item => item.remark?.iHk_remarks ?? null);
        nsa_getAllBHMaxValues.value = setData.map(item => item.BHMax || null);
        nsa_getAllBHMaxRemarks.value = setData.map(item => item.remark?.BHMax_remarks ?? null);
        nsa_getAlliHr95Values.value = setData.map(item => item.iHr95 || null);
        nsa_getAlliHr95Remarks.value = setData.map(item => item.remark?.iHr95_remarks ?? null);
        nsa_getAlliHr98Values.value = setData.map(item => item.iHr98 || null);
        nsa_getAlliHr98Remarks.value = setData.map(item => item.remark?.iHr98_remarks ?? null);
        nsa_getAlliHciHkValues.value = setData.map(item => item.iHkiHc || null);
        nsa_getAlliHciHkRemarks.value = setData.map(item => item.remark?.iHkiHc_remarks ?? null);
        nsa_getAllBr4paiValues.value = setData.map(item => item.Br4pai || null);
        nsa_getAllBr4paiRemarks.value = setData.map(item => item.remark?.Br4pai_remarks ?? null);
        nsa_getAllbHcValues.value = setData.map(item => item.bHc || null);
        nsa_getAllbHcRemarks.value = setData.map(item => item.remark?.bHc_remarks ?? null);
        nsa_getAllSquarenessValues.value = setData.map(item => item.Squareness ?? null);
        nsa_getAllSquarenessRemarks.value = setData.map(item => item.remark?.Squareness_remarks ?? null);
        nsa_getAll4paildValues.value = setData.map(item => item["4paiId"] || null);
        nsa_getAll4paildRemarks.value = setData.map(item => item.remark?.["4paiId_remarks"] ?? null);
        nsa_getAll4pailsValues.value = setData.map(item => item["4paiIs"] || null);
        nsa_getAll4pailsRemarks.value = setData.map(item => item.remark?.["4paiIs_remarks"] ?? null);
        nsa_getAll4pailaValues.value = setData.map(item => item["4paiIa"] || null);
        nsa_getAll4pailaRemarks.value = setData.map(item => item.remark?.["4paiIa_remarks"] ?? null);

        //get average function
        const calculateAverage = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return 0
            if (numbers.length === 0) return 0;

            // Calculate the sum and divide by the count
            const sum = numbers.reduce((total, value) => total + value, 0);
            const average = sum / numbers.length;

            // Check the maximum number of decimals present in the input
            const maxDecimals = Math.max(
                ...numbers.map(value => {
                    const parts = value.toString().split(".");
                    return parts[1] ? parts[1].length : 0; // Length of the decimal part
                })
            );

            // Round the average to match the maximum number of decimals in the input
            const factor = Math.pow(10, maxDecimals);
            return Math.round(average * factor) / factor;
        };
        //get maximum function
        const getMaxValue = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return null
            if (numbers.length === 0) return null;

            // Return the highest value using Math.max
            return Math.max(...numbers);
        };
        //get minimum function
        const getMinValue = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return null
            if (numbers.length === 0) return null;

            // Return the lowest value using Math.min
            return Math.min(...numbers);
        };
        //get Sample with Variance data
        const calculateVariance = (numericStringsArray, maxValue) => {
            // Convert the array of numeric strings to numbers
            const numbers = numericStringsArray
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Filter out invalid numbers

            // Subtract each value from maxValue and return the resulting array
            return numbers.map(num => maxValue - num);
        };

        // Function to sum up all the data in an array
        const calculateSum = (numericStringsArray) => {
            // Convert the array of numeric strings to numbers
            const numbers = numericStringsArray
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Filter out invalid numbers

            // Sum up all the valid numbers in the array
            return numbers.reduce((sum, num) => sum + num, 0);
        };


        //average values
        aveBr.value = calculateAverage(getAllBrValues.value);
        aveiHc.value = calculateAverage(getAlliHcValues.value);
        aveiHk.value = calculateAverage(getAlliHkValues.value);
        aveBHMax.value = calculateAverage(getAllBHMaxValues.value);
        aveiHr95.value = calculateAverage(getAlliHr95Values.value);
        aveiHr98.value = calculateAverage(getAlliHr98Values.value);
        aveiHciHk.value = calculateAverage(getAlliHciHkValues.value);
        aveBr4pai.value = calculateAverage(getAllBr4paiValues.value);
        avebHc.value = calculateAverage(getAllbHcValues.value);
        aveSquareness.value = calculateAverage(getAllSquarenessValues.value);
        ave4paild.value = calculateAverage(getAll4paildValues.value);
        ave4pails.value = calculateAverage(getAll4pailsValues.value);
        ave4paila.value = calculateAverage(getAll4pailaValues.value);

        //console.log("iHciHk average value: ", aveiHciHk.value);

        // Minimum values
        minBr.value = getMinValue(getAllBrValues.value);
        miniHc.value = getMinValue(getAlliHcValues.value);
        miniHk.value = getMinValue(getAlliHkValues.value);
        minBHMax.value = getMinValue(getAllBHMaxValues.value);
        miniHr95.value = getMinValue(getAlliHr95Values.value);
        miniHr98.value = getMinValue(getAlliHr98Values.value);
        miniHciHk.value = getMinValue(getAlliHciHkValues.value);
        minBr4pai.value = getMinValue(getAllBr4paiValues.value);
        minbHc.value = getMinValue(getAllbHcValues.value);
        minSquareness.value = getMinValue(getAllSquarenessValues.value);
        min4paild.value = getMinValue(getAll4paildValues.value);
        min4pails.value = getMinValue(getAll4pailsValues.value);
        min4paila.value = getMinValue(getAll4pailaValues.value);

        // Maximum values
        maxBr.value = getMaxValue(getAllBrValues.value);
        maxiHc.value = getMaxValue(getAlliHcValues.value);
        maxiHk.value = getMaxValue(getAlliHkValues.value);
        maxBHMax.value = getMaxValue(getAllBHMaxValues.value);
        maxiHr95.value = getMaxValue(getAlliHr95Values.value);
        maxiHr98.value = getMaxValue(getAlliHr98Values.value);
        maxiHciHk.value = getMaxValue(getAlliHciHkValues.value);
        maxBr4pai.value = getMaxValue(getAllBr4paiValues.value);
        maxbHc.value = getMaxValue(getAllbHcValues.value);
        maxSquareness.value = getMaxValue(getAllSquarenessValues.value);
        max4paild.value = getMaxValue(getAll4paildValues.value);
        max4pails.value = getMaxValue(getAll4pailsValues.value);
        max4paila.value = getMaxValue(getAll4pailaValues.value);

        //NG count values
        ngBr.value = calculateSum(getAllBrRemarks.value);
        ngiHc.value = calculateSum(getAlliHcRemarks.value);
        ngiHk.value = calculateSum(getAlliHkRemarks.value);
        ngBHMax.value = calculateSum(getAllBHMaxRemarks.value);
        ngiHr95.value = calculateSum(getAlliHr95Remarks.value);
        ngiHr98.value = calculateSum(getAlliHr98Remarks.value);
        ngiHciHk.value = calculateSum(getAlliHciHkRemarks.value);
        ngBr4pai.value = calculateSum(getAllBr4paiRemarks.value);
        ngbHc.value = calculateSum(getAllbHcRemarks.value);
        ngSquareness.value = calculateSum(getAllSquarenessRemarks.value);
        ng4paild.value = calculateSum(getAll4paildRemarks.value);
        ng4pails.value = calculateSum(getAll4pailsRemarks.value);
        ng4paila.value = calculateSum(getAll4pailaRemarks.value);

    } catch (error) {
        console.error("Error loading set data:", error);
    }
};

/*const test_nsa_showData = async() => {

    try {
        const responseNSARemarks = await axios.get("/api/nsadata?serial=" + currentSerialSelected.value + "&set=" + current_setNo.value); // Adjust this URL to your API endpoint
        console.log('responseNSARemarks - API Response showallData:', responseNSARemarks.data);
        //gg
        console.log('Serial No value = ', currentSerialSelected.value);

        // Extract arrays from the response
        nsaData.value = responseNSARemarks.data.data || [];
        console.log('showAllData nsa data: ',nsaData.value);
        const getAggregateID = responseNSARemarks.data[0];
        //console.log("Data for getting aggregate ID: ",getAggregateID);
        const filteredAggregateID_first = getAggregateID.filter(item => item.nsa_serial == currentSerialSelected.value);
        //console.log("Filtered aggregate with serial_no:", filteredAggregateID.value);
        filteredAggregateID.value = filteredAggregateID_first.filter(item => item.nsa_set == current_setNo.value);
        //console.log("Filtered aggregate with set_no:", filteredAggregateID.value);
        nsaData_aggID.value = filteredAggregateID.value[0].id;
        //console.log("aggregate ID to be patched: ",nsaData_aggID.value);
        //const nsaRemarks = response.data.data["remarks"] || [];
        //console.log('showAllData nsa remarks: ',nsaRemarks);

        // Combine the arrays
        nsa_combinedData.value = nsaData.value;
        //console.log('Combined Data: ', combinedData.value);

        secAdd_propD_jhCurveFurnaceName.value = nsaData.value[0].sintering_furnace_no || "No data found";
        secAdd_propD_jhCurveModel.value = nsaData.value[0].code_no || "No data found";

        // Extract individual values from nsaData for aggregate
        getAllIDValues.value = nsa_combinedData.value.map(item => item.id);
        getAllBrValues.value = nsa_combinedData.value.map(item => item.Br || null);
        getAllBrRemarks.value = nsa_combinedData.value.map(item => item.remark.Br_remarks || null);
        getAlliHcValues.value = nsa_combinedData.value.map(item => item.iHc || null);
        getAlliHcRemarks.value = nsa_combinedData.value.map(item => item.remark.iHc_remarks || null);
        getAlliHkValues.value = nsa_combinedData.value.map(item => item.iHk || null);
        getAlliHkRemarks.value = nsa_combinedData.value.map(item => item.remark.iHk_remarks || null);
        getAllBHMaxValues.value = nsa_combinedData.value.map(item => item.BHMax || null);
        getAllBHMaxRemarks.value = nsa_combinedData.value.map(item => item.remark.BHMax_remarks || null);
        getAlliHr95Values.value = nsa_combinedData.value.map(item => item.iHr95 || null);
        getAlliHr95Remarks.value = nsa_combinedData.value.map(item => item.remark.iHr95_remarks || null);
        getAlliHr98Values.value = nsa_combinedData.value.map(item => item.iHr98 || null);
        getAlliHr98Remarks.value = nsa_combinedData.value.map(item => item.remark.iHr98_remarks || null);
        getAlliHciHkValues.value = nsa_combinedData.value.map(item => item.iHkiHc || null);
        getAlliHciHkRemarks.value = nsa_combinedData.value.map(item => item.remark.iHkiHc_remarks || null);
        getAllBr4paiValues.value = nsa_combinedData.value.map(item => item.Br4pai || null);
        getAllBr4paiRemarks.value = nsa_combinedData.value.map(item => item.remark.Br4pai_remarks || null);
        getAllbHcValues.value = nsa_combinedData.value.map(item => item.bHc || null);
        getAllbHcRemarks.value = nsa_combinedData.value.map(item => item.remark.bHc_remarks || null);
        getAllSquarenessValues.value = nsa_combinedData.value.map(item => item.Squareness || null);
        getAllSquarenessRemarks.value = nsa_combinedData.value.map(item => item.remark.Squareness_remarks || null);
        getAll4paildValues.value = nsa_combinedData.value.map(item => item["4paiId"] || null);
        getAll4paildRemarks.value = nsa_combinedData.value.map(item => item.remark["4paiId_remarks"] || null);
        getAll4pailsValues.value = nsa_combinedData.value.map(item => item["4paiIs"] || null);
        getAll4pailsRemarks.value = nsa_combinedData.value.map(item => item.remark["4paiIs_remarks"] || null);
        getAll4pailaValues.value = nsa_combinedData.value.map(item => item["4paiIa"] || null);
        getAll4pailaRemarks.value = nsa_combinedData.value.map(item => item.remark["4paiIa_remarks"] || null);

        //console.log('gettheIDs: ', getAllIDValues.value);
        //console.log('tpmRemarks: ', tpmRemarks.value);

        //get average function
        const calculateAverage = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return 0
            if (numbers.length === 0) return 0;

            // Calculate the sum and divide by the count
            const sum = numbers.reduce((total, value) => total + value, 0);
            const average = sum / numbers.length;

            // Check the maximum number of decimals present in the input
            const maxDecimals = Math.max(
                ...numbers.map(value => {
                    const parts = value.toString().split(".");
                    return parts[1] ? parts[1].length : 0; // Length of the decimal part
                })
            );

            // Round the average to match the maximum number of decimals in the input
            const factor = Math.pow(10, maxDecimals);
            return Math.round(average * factor) / factor;
        };
        //get maximum function
        const getMaxValue = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return null
            if (numbers.length === 0) return null;

            // Return the highest value using Math.max
            return Math.max(...numbers);
        };
        //get minimum function
        const getMinValue = (array) => {
            // Convert numeric strings to numbers and filter out invalid values
            const numbers = array
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Exclude invalid numbers (NaN)

            // If no valid numbers, return null
            if (numbers.length === 0) return null;

            // Return the lowest value using Math.min
            return Math.min(...numbers);
        };
        //get Sample with Variance data
        const calculateVariance = (numericStringsArray, maxValue) => {
            // Convert the array of numeric strings to numbers
            const numbers = numericStringsArray
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Filter out invalid numbers

            // Subtract each value from maxValue and return the resulting array
            return numbers.map(num => maxValue - num);
        };

        // Function to sum up all the data in an array
        const calculateSum = (numericStringsArray) => {
            // Convert the array of numeric strings to numbers
            const numbers = numericStringsArray
                .map(value => (typeof value === 'number' ? value : parseFloat(value)))
                .filter(value => !isNaN(value)); // Filter out invalid numbers

            // Sum up all the valid numbers in the array
            return numbers.reduce((sum, num) => sum + num, 0);
        };


        //average values
        aveBr.value = calculateAverage(getAllBrValues.value);
        aveiHc.value = calculateAverage(getAlliHcValues.value);
        aveiHk.value = calculateAverage(getAlliHkValues.value);
        aveBHMax.value = calculateAverage(getAllBHMaxValues.value);
        aveiHr95.value = calculateAverage(getAlliHr95Values.value);
        aveiHr98.value = calculateAverage(getAlliHr98Values.value);
        aveiHciHk.value = calculateAverage(getAlliHciHkValues.value);
        aveBr4pai.value = calculateAverage(getAllBr4paiValues.value);
        avebHc.value = calculateAverage(getAllbHcValues.value);
        aveSquareness.value = calculateAverage(getAllSquarenessValues.value);
        ave4paild.value = calculateAverage(getAll4paildValues.value);
        ave4pails.value = calculateAverage(getAll4pailsValues.value);
        ave4paila.value = calculateAverage(getAll4pailaValues.value);

        //console.log("iHciHk average value: ", aveiHciHk.value);

        // Minimum values
        minBr.value = getMinValue(getAllBrValues.value);
        miniHc.value = getMinValue(getAlliHcValues.value);
        miniHk.value = getMinValue(getAlliHkValues.value);
        minBHMax.value = getMinValue(getAllBHMaxValues.value);
        miniHr95.value = getMinValue(getAlliHr95Values.value);
        miniHr98.value = getMinValue(getAlliHr98Values.value);
        miniHciHk.value = getMinValue(getAlliHciHkValues.value);
        minBr4pai.value = getMinValue(getAllBr4paiValues.value);
        minbHc.value = getMinValue(getAllbHcValues.value);
        minSquareness.value = getMinValue(getAllSquarenessValues.value);
        min4paild.value = getMinValue(getAll4paildValues.value);
        min4pails.value = getMinValue(getAll4pailsValues.value);
        min4paila.value = getMinValue(getAll4pailaValues.value);

        // Maximum values
        maxBr.value = getMaxValue(getAllBrValues.value);
        maxiHc.value = getMaxValue(getAlliHcValues.value);
        maxiHk.value = getMaxValue(getAlliHkValues.value);
        maxBHMax.value = getMaxValue(getAllBHMaxValues.value);
        maxiHr95.value = getMaxValue(getAlliHr95Values.value);
        maxiHr98.value = getMaxValue(getAlliHr98Values.value);
        maxiHciHk.value = getMaxValue(getAlliHciHkValues.value);
        maxBr4pai.value = getMaxValue(getAllBr4paiValues.value);
        maxbHc.value = getMaxValue(getAllbHcValues.value);
        maxSquareness.value = getMaxValue(getAllSquarenessValues.value);
        max4paild.value = getMaxValue(getAll4paildValues.value);
        max4pails.value = getMaxValue(getAll4pailsValues.value);
        max4paila.value = getMaxValue(getAll4pailaValues.value);

        //NG count values
        ngBr.value = calculateSum(getAllBrRemarks.value);
        ngiHc.value = calculateSum(getAlliHcRemarks.value);
        ngiHk.value = calculateSum(getAlliHkRemarks.value);
        ngBHMax.value = calculateSum(getAllBHMaxRemarks.value);
        ngiHr95.value = calculateSum(getAlliHr95Remarks.value);
        ngiHr98.value = calculateSum(getAlliHr98Remarks.value);
        ngiHciHk.value = calculateSum(getAlliHciHkRemarks.value);
        ngBr4pai.value = calculateSum(getAllBr4paiRemarks.value);
        ngbHc.value = calculateSum(getAllbHcRemarks.value);
        ngSquareness.value = calculateSum(getAllSquarenessRemarks.value);
        ng4paild.value = calculateSum(getAll4paildRemarks.value);
        ng4pails.value = calculateSum(getAll4pailsRemarks.value);
        ng4paila.value = calculateSum(getAll4pailaRemarks.value);

    } catch (error) {
        console.error('Error fetching data:', error);
        return;
    }
};*/



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
    const response = await axios.get(`/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}`);
    const massProd = response.data;
    currentFurnaceName.value = massProd.furnace; // K-40
    currentLayerName.value = "Layer " + selectedLayer.value; // Layer 1
    //console.log("Current Furnace: ", currentFurnaceName.value);
    //console.log("Current Layer: ", currentLayerName.value);
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

    //console.log("OVEN MACHINE NO: ", inspectionOvenMachineNo.value);

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
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "createReport",
            "Patch report data Error"
        );
    }
}

const showReportData = async () => {
    try {
        isReportDataReady.value = false;
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
        //reportTotalQuantity.value = filterBySerial[0].total_quantity;
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
        //console.log("Report is finalized: ", report_isFinalized.value);

        isAutomotive.value = filterBySerial[0].withCarmark == 1;
        //console.log("With carmark value: ",isAutomotive.value);
        coatingCompleted.value = filterBySerial[0].coating_completed == 1;
        //console.log("Coating completed value: ",coatingCompleted.value);
        heatTreatmentCompleted.value = filterBySerial[0].heat_treatment_completed == 1;
        //console.log("Heat treatment completed value: ",heatTreatmentCompleted.value);

        //Request to AUTO 'N/A' all oven fields if no oven - 5/31/2025

        reportOvenMachineNo.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].oven_machine_no;
        reportTimeLoading.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].time_loading;
        reportTimeUnloading.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].time_unloading;
        reportTemperature_TimeLoading.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].temp_time_loading;
        reportTemperature_TimeUnloading.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].temp_time_unloading;
        reportDate_OvenInfo.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].date_oven_info;
        reportShift_OvenInfo.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].shift_oven_info;
        reportOperator_OvenInfo.value = inspectionOvenMachineNo.value === 0 ? 'N/A' : filterBySerial[0].operator_oven_info;


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
        propD_rejectReasons.value = noteRejectReasons;
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

        console.log('Entering Evalation for Reject reasons...');
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
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "showReportData",
            "API get request showReportData Error"
        );
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

    await saveReportUpdate(saveReportData, currentSerialSelected.value);
}

const saveReportUpdate = async (saveData, serial) => {
    try{
        const responseSave = await axios.patch(`/api/reportdata/${serial}`, saveData);
        //console.log("Saved Report data: ", responseSave.data);
        checkApprovalStates();
        showNotification2("Saved Successfully");
    }catch (error){
        console.error("Patch report data Error:", error);
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "saveReportUpdate",
            "Patch report data Error"
        );
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

const openUndoModal = (type) => {
    currentUndoType.value = type
    stampUndoRemarks.value = ''
    showModalUndoStamp.value = true
}

const undoStamp = async () => {
    try {
        if (!currentUndoType.value) return
        if (!stampUndoRemarks.value){
            toast.warning('You must add a reason for undo.');
            return;
        }

        const fieldMap = {
            prepared: {
                firstname: 'prepared_by_firstname',
                surname: 'prepared_by_surname',
                date: 'prepared_by_date',
                label: 'Prepared By'
            },
            checked: {
                firstname: 'checked_by_firstname',
                surname: 'checked_by_surname',
                date: 'checked_by_date',
                label: 'Checked By'
            },
            approved: {
                firstname: 'approved_by_firstname',
                surname: 'approved_by_surname',
                date: 'approved_by_date',
                label: 'Approved By'
            }
        }

        const config = fieldMap[currentUndoType.value]

        const reportData = {
            [config.firstname]: "",
            [config.surname]: "",
            [config.date]: null,
        }

        // Clear stamp
        await axios.patch(
            `/api/reportdata/${currentSerialSelected.value}`,
            reportData
        )

        // Save undo history
        await axios.post('/api/stamp-undo-history', {
            serial_no: currentSerialSelected.value,
            mass_prod: selectedMassProd.value,
            furnace: selectedFurnace.value,
            model_name: jhCurveActualModel.value,
            lot_no: jhCurveLotNo.value,
            stamp_undone: config.label,
            undo_remarks: stampUndoRemarks.value,
            undo_by: state.user.firstName + state.user.surname,
        })

        await userReportLogging(`has successfully removed ${currentUndoType.value} by stamp | serial ${currentSerialSelected.value}`);

        showModalUndoStamp.value = false
        reportReset();
        await showReportData();
    } catch (error) {
        console.error('Failed to undo stamp', error)
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
  filterStatus_checked: String,
  ipAddress: String,
  fromApproval: [Boolean, String],
  fromViewList: [Boolean, String],
  fromApproval_checked: [Boolean, String],
  fromApproval_prepared: [Boolean, String],
});
// Update value after props are available
isFromApproval.value = props.fromApproval === true || props.fromApproval === 'true';
isFromApproval_checked.value = props.fromApproval_checked === true || props.fromApproval_checked === 'true';
isFromApproval_prepared.value = props.fromApproval_prepared === true || props.fromApproval_prepared === 'true';
isFromViewList.value = props.fromViewList === true || props.fromViewList === 'true';
//console.log('isFromApproval:', isFromApproval.value);
ipAddress.value = props.ipAddress;
filterStatus.value = props.filterStatus;
filterStatus_checked.value = props.filterStatus_checked;

if(ipAddress.value === '127.0.0.1'){
    onTestServer.value = true;
}
//console.log('Current IP address is:', props.ipAddress); // You can use this for debugging
//console.log('Serial Param in Reports.vue:', props.serialParam); // You can use this for debugging

const viewPropertyData = (serial, massprod, layer, furnace) => {
    //console.log('Navigating to manage with serial:', serial);
    Inertia.visit('/manage', {
        method: 'get',
        data: { manageSerialParam: serial, manageMassProd: massprod, manageLayer: layer, manageFurnace: furnace },   // Passing the serialParam here
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

        if((state.user.access_type) && (state.user.access_type === 'Preparation Approver' || state.user.access_type === 'Hybrid Approver' || state.user.access_type === 'Bypass Approver' || state.user.access_type === 'Automation')){
            preparedByButton.value = true;
            showPreparedByDefault.value = false;
        }

        if((state.user.access_type) && (prepared_by_firstname) && (state.user.access_type === 'Checking Approver' || state.user.access_type === 'Hybrid Approver' || state.user.access_type === 'Bypass Approver' || state.user.access_type === 'Automation')){
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

const finalizeReport = async (serial, isFinalized) => {
    console.log('isFInalized: ',isFinalized);
    if (!isFinalized) {
        await userFinalizedLogging(`has finalized report serial: ${serial}`);
    }
    window.open(`/reports/${encodeURIComponent(serial)}/pdf`, '_blank');
};

const sec_additional_redirect = (sec_serial, sec_massprod, sec_layer, sec_furnace) => {
    try {
        //console.log('Navigating to report with serial:', sec_serial);
        Inertia.visit('/sec_additional', {
            method: 'get',
            data: { sec_serialParam: sec_serial, sec_massProd: sec_massprod, sec_layer: sec_layer, sec_furnace: sec_furnace },
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

const backToApprovalFunction_checked = () => {
    //console.log('Navigating to report with serial:', serial);
    Inertia.visit('/approval_checked', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { filterStatus_checked: filterStatus_checked.value, fromReports: true },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};

const backToApprovalFunction_prepared = () => {
    //console.log('Navigating to report with serial:', serial);
    Inertia.visit('/approval_prepared', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { fromReports: true },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
};


// onMounted logic to call the function based on serialParam existence
onMounted(async () => {
    await checkAuthentication();
    await checkApprovalStates();
    //await test_nsa_showData();
    if (props.serialParam && props.fromApproval || props.serialParam && props.fromApproval_checked || props.serialParam && props.fromApproval_prepared || props.fromViewList) {
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

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

</style>
