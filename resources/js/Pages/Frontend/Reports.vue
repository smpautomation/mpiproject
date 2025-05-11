<template>
    <Frontend>
      <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">

        <div v-if="serialList.length == 0 || isFromApproval == false"> <!-- default div -->
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

        <div v-else v-show="showSelectionPanel">
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
                        class="px-6 py-3 mt-10 mb-10 text-xl font-extrabold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        Generate Report
                    </button>
                </div>
                <div v-show="showNotif2" class="flex flex-row items-center justify-center px-4 py-2 my-10 text-white bg-yellow-500 shadow-lg rounded-2xl">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
            </div>
        </div>


        <div v-show="showReportContent">
            <div v-show="showReportProceedButtons">
                <div v-if="isLoading">Generating Report...</div>
                <div v-else>
                    <div v-if="!isFromApproval">
                        <!-- Checkbox + Label -->
                        <label v-if="isAutomotiveInitiallyMarked == false" class="flex items-center mb-5 space-x-3 text-lg">
                            <!--
                            <input
                            v-model="isTTM_model"
                            type="checkbox"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                                <span>(Tick this box if the TTM model applies.)</span>
                            -->
                            <input
                            v-model="isAutomotive"
                            type="checkbox"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                                <span>(Tick this box if the model is for Automotive)</span>
                        </label>
                    </div>
                    <div v-if="isFromApproval">
                        <p class="mb-10 text-xl font-extrabold animate-pulse">Report Data is ready for viewing...</p>
                    </div>
                    <div class="flex flex-row justify-center">
                        <button @click="showReportButton" class="px-3 py-2 text-white bg-blue-500 shadow-xl rounded-xl">{{ isFromApproval ? 'Click to Show' : 'Show Report' }}</button>
                    </div>

                </div>
            </div>
            <!-- Report Content -->
            <div v-show="showReportMain" class="flex flex-col justify-center py-10 mx-20 mt-10 mb-20 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="flex flex-row w-full max-w-4xl px-4 mx-auto mb-10">
                    <div v-if="currentUserName != 'ITADANI KAZUYA' && ipAddress == currentUserIP" class="flex flex-col bg-blue-200 mr-10 w-[250px] h-[135px] rounded-xl shadow-xl justify-center items-start px-5 py-4 text-white space-y-1">
                        <p class="text-sm font-medium">Hello, Welcome!</p>
                        <p class="text-xl font-bold uppercase">Ma'am {{ currentUserName.split(' ')[0] }}</p>
                    </div>
                    <div v-else-if="currentUserName == 'ITADANI KAZUYA'" class="flex flex-col bg-blue-200 mr-10 w-[250px] h-[135px] rounded-xl shadow-xl justify-center items-start px-5 py-4 text-white space-y-1">
                        <p class="text-sm font-medium">Hello, Welcome!</p>
                        <p class="text-xl font-bold uppercase">{{ currentUserName.split(' ')[0] }} SAN</p>
                    </div>
                    <div v-else class="flex flex-col bg-blue-200 mr-10 w-[250px] h-[135px] rounded-xl shadow-xl justify-center items-start px-5 py-4 text-white space-y-1">
                        <p class="text-sm font-medium">Hello, Welcome!</p>
                        <p class="text-xl font-bold">You are not yet registered</p>
                    </div>
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
                <div class="p-5 mx-10 mb-10 border-2 border-white rounded-lg shadow-xl">
                    <!-- Oven Heating Information Section -->
                    <div class="flex flex-col mb-4 space-y-4">
                        <p class="mb-2 text-xl font-extrabold">Oven Heating Information</p>

                        <!-- Row 1 -->
                        <div class="flex flex-row justify-evenly">
                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Oven Machine No:</label>&nbsp;
                                <input
                                    v-model="reportOvenMachineNo"
                                    type="text"
                                    name="ovenMachineNo"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>

                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Time Loading:</label>&nbsp;
                                <input
                                    v-model="reportTimeLoading"
                                    type="text"
                                    @input="reportTimeLoading = reportTimeLoading.toUpperCase()"
                                    name="timeLoading"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>

                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Temperature (Time Loading):</label>&nbsp;
                                <input
                                    v-model="reportTemperature_TimeLoading"
                                    type="text"

                                    name="temperature_timeLoading"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="flex flex-row justify-evenly">
                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Date:</label>&nbsp;
                                <input
                                    v-model="reportDate_OvenInfo"
                                    type="date"
                                    name="ovenInfo_date"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>

                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Time Unloading:</label>&nbsp;
                                <input
                                    v-model="reportTimeUnloading"
                                    type="text"
                                    @input="reportTimeUnloading = reportTimeUnloading.toUpperCase()"
                                    name="timeUnloading"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>

                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Temperature (Time Unloading):</label>&nbsp;
                                <input
                                    v-model="reportTemperature_TimeUnloading"
                                    type="text"
                                    name="temperature_TimeUnloading"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="flex flex-row justify-evenly">
                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Shift:</label>&nbsp;
                                <input
                                    v-model="reportShift_OvenInfo"
                                    type="text"
                                    @input="reportShift_OvenInfo = reportShift_OvenInfo.toUpperCase()"
                                    name="ovenInfo_shift"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>

                            <div class="flex flex-row items-baseline">
                                <label class="text-lg font-extrabold">Operator:</label>&nbsp;
                                <input
                                    v-model="reportOperator_OvenInfo"
                                    type="text"
                                    @input="reportOperator_OvenInfo = reportOperator_OvenInfo.toUpperCase()"
                                    name="ovenInfo_operator"
                                    class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"
                                />
                            </div>
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
                                {{ jhCurveActualModel }}
                            </span>
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Material Code:</label>&nbsp;
                            <span
                                class="px-4 py-1 text-gray-800 transition duration-200 ease-in-out bg-white rounded-md cursor-default hover:ring-1 hover:ring-blue-500 hover:shadow-md"
                                >
                                {{ reportMaterialCode }}
                            </span>
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Partial No.:</label>&nbsp;
                            <input v-model="reportPartialNo" @input="reportPartialNo = reportPartialNo.toUpperCase()" type="text" name="shift" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
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
                            <input v-model="reportShift" type="text" @input="reportShift = reportShift.toUpperCase()" name="shift" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out">
                        </div>
                        <div class="flex flex-row items-baseline">
                            <label class="text-lg font-semibold">Operator:</label>&nbsp;
                            <input v-model="reportOperator" type="text" @input="reportOperator = reportOperator.toUpperCase()" name="operator" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
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
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">Br (G)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ inspectionBrStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportBrVariance }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">iHc (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ inspectioniHcStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihcMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHcVariance }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">iHk (Oe)</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ inspectioniHkStandard }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkAverage }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMaximum }}</td>
                                    <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportihkMinimum }}</td>
                                    <td colspan="2" class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportiHkVariance }}</td>
                                </tr>
                                <tr v-show="isTTM_model" class="bg-blue-300">
                                    <th rowspan="2" colspan="2" class="px-4 py-2 text-xl text-white border-4 border-white whitespace-nowrap">Computation of Cpk from Br</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">STD DEV</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">Cp</th>
                                    <th class="px-4 py-2 text-white border-4 border-white whitespace-nowrap">Cpk &#8805; 1.00</th> <!-- &#8805; is greater than equal to symbol -->
                                    <th colspan="2" class="px-4 py-2 text-white border-4 border-white">Remarks</th>
                                </tr>
                                <tr v-show="isTTM_model" class="text-center">
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
                                    <td colspan="2" class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="text" v-model="reportCpkRemarks" name="stdDev" class="w-[8.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                </tr>
                                <tr v-show="show1x1x1Data_withoutCorner" class="text-center bg-blue-300">
                                    <td colspan="2" class="px-4 py-2 font-extrabold text-white border-4 border-white">Data of 1x1x1 mm samples</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">AVERAGE</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">MAXIMUM</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">MINIMUM</td>
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white whitespace-nowrap">Cpk &#8805; 1.33</td> <!-- &#8805; is greater than equal to symbol -->
                                    <td class="px-4 py-2 font-extrabold text-white border-4 border-white">Remarks</td>
                                </tr>
                                <tr v-show="show1x1x1Data_Corner" class="text-center">
                                    <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Corner</th>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCorner" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
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
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="text" v-model="reportCorner_remarks" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                </tr>
                                <tr v-show="show1x1x1Data_withoutCorner" class="text-center">
                                    <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Surface</th>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportSurface" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
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
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="text" v-model="reportSurface_remarks" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                </tr>
                                <tr v-show="show1x1x1Data_withoutCorner" class="text-center">
                                    <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Core</th>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportCore" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
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
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="text" v-model="reportCore_remarks" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                </tr>
                                <tr v-show="showVTData" class="text-center">
                                    <th colspan="2" class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">VT Data</th>
                                    <td colspan="3" class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300"><input type="text" v-model="reportVT_sample" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> (N.G iHc)</td>
                                        <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Result</th>
                                </tr>
                                <tr v-show="showVTData" class="text-center">
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
                                    <td colspan="3" class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportVT_iHcResult" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> kOe</td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportVT_remarks }}</td>
                                </tr>
                                <tr v-show="showCpkFrom_iHc" class="bg-blue-300">
                                    <th rowspan="2" colspan="2" class="px-4 py-2 text-xl text-white border-4 border-white whitespace-nowrap">Computation of Cpk from iHc</th>
                                    <th class="px-4 py-2 text-white border-4 border-white">STD DEV</th>
                                    <th colspan="2" class="px-4 py-2 text-white border-4 border-white whitespace-nowrap">Cpk &#8805; 1.33</th> <!-- &#8805; is greater than equal to symbol -->
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
                                <tr v-show="showVTData2" class="text-center">
                                    <th colspan="2" class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">VT Data</th>
                                    <td colspan="2" class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300"><input type="text" v-model="reportVT2_minSample" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> (min iHc)</td>
                                    <td class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300 whitespace-nowrap"><input type="text" v-model="reportVT2_maxSample" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> (max iHc)</td>
                                    <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Result</th>
                                </tr>
                                <tr v-show="showVTData2" class="text-center">
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">
                                        <div class="flex items-center">
                                            <input type="number" v-model="reportVT2_temp" name="stdDev" class="w-[4.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                                hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                                focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                                transition duration-200 ease-in-out">
                                            <span class="ml-1 align-baseline">°C</span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white whitespace-nowrap">iHc (kOe) &#8805; <input type="number" v-model="reportVT2_iHc" name="stdDev" class="w-[5.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> (kOe)</td>
                                    <td colspan="2" class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportVT_iHcResult" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> kOe</td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportVT_iHcResult" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> kOe</td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportVT_remarks }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-row items-center justify-center mx-5 mt-5 align-middle">
                        <p class="m-5 text-lg font-extrabold">Remarks:</p>
                        <input
                            v-model="reportRemarks"
                            type="text"
                            @input="reportRemarks = reportRemarks.toUpperCase()"
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
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white">
                        SMP Judgement
                    </p>
                    <p class="p-2 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <span
                        class="inline-block w-40 h-40 bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: reportSMPJudgement === 'REJECT'
                            ? 'url(\'/photo/reject_stamp.png\')'
                            : reportSMPJudgement === 'HOLD'
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

                    <!-- Note: -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white">Note: Reason of REJECT/HOLD</p>
                    <div class="p-2 border-4 border-white w-[320px] h-[190px] box-border">
                        <div class="flex flex-col items-start w-full h-full overflow-auto bg-blue-100">
                            <span
                                v-for="(note, index) in noteReasonForReject"
                                :key="index"
                                class="font-extrabold text-red-700 opacity-100"
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
                <div class="flex flex-row items-center justify-center">
                    <button v-if="(currentUserIP == ipAddress) && (approvedByPerson == '' || approvedByPerson == null)" @click="saveReport" class="px-6 py-4 mt-4 font-extrabold text-white transition duration-300 ease-in-out transform bg-green-500 shadow-xl rounded-xl hover:bg-green-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-600 active:scale-95">
                        {{ reportExistingSMPJudgement !== null ? 'OVERWRITE' : 'SAVE' }}
                    </button>
                    <button @click="viewPropertyData(currentSerialSelected)" class="px-6 py-4 mt-4 ml-5 font-extrabold text-blue-700 transition duration-300 ease-in-out transform border border-blue-700 shadow-xl hover:text-white rounded-xl hover:bg-blue-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-600 active:scale-95">
                        View Property Data
                    </button>
                    <!-- Apply Data 1x1x1 Button (New Styled Button) -->
                    <button
                        v-if="reportRemarksDisplay === 'E' || reportRemarksDisplay === 'HOLD'"
                        @click="sec_additional_redirect(currentSerialSelected)"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-red-700 transition duration-300 ease-in-out transform border border-red-700 shadow-xl rounded-xl hover:text-white hover:bg-red-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-600 active:scale-95"
                        >
                        Apply SEC Additionals
                    </button>
                    <button
                        @click="finalizeReport(currentSerialSelected)"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-yellow-600 transition duration-300 ease-in-out transform border border-yellow-400 shadow-xl rounded-xl hover:text-white hover:bg-yellow-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-600 active:scale-95"
                    >
                        Finalize Report
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
import { ref, onMounted, nextTick } from 'vue';
import { Inertia } from '@inertiajs/inertia';

//UI Control start

const isOn = ref(false);

function toggleSwitch() {
    isOn.value = !isOn.value;
}

const isFromApproval = ref(false);

const showNotif = ref(false);
const showNotif2 = ref(false);
const showNotif3 = ref(false);
const showReportContent = ref(false);
const showReportMain = ref(false);
const showReportProceedButtons = ref(true);
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

const noteReasonForReject = ref([]);

const exitReport = () => {
    showReportContent.value = false;
    showSelectionPanel.value = true;
    showReportProceedButtons.value = true;
    showReportMain.value = false;
    fetchSerial();
    reportReset();
}

const showReportButton = async () => {
    showReportMain.value = true;
    showReportProceedButtons.value = false;
    if(isAutomotiveInitiallyMarked.value == false){
        checkCarmark();
    }
    await showReportData();
}

// models with special instructions

const isTTM_model = ref(false);
const isAutomotive = ref(false);
const isAutomotiveInitiallyMarked = ref(false);
const show1x1x1Data_withoutCorner = ref(false);
const show1x1x1Data_Corner = ref(false);
const showVTData = ref(false);
const showVTData2 = ref(false);
const showCpkFrom_iHc = ref(false);



const isLoading = ref(true);

//UI Control end

// variables for models with special instructions

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
const reportVT_sample = ref(0);
const reportVT_temp = ref(0);
const reportVT_iHc = ref(0);
const reportVT_iHcResult = ref(0);
const reportVT_remarks = ref('NA');
const reportCpkFrom_iHc_StdDev = ref(0);
const reportCpkFrom_iHc_Cpk = ref(0);
const reportCpkFrom_iHc_remarks = ref('NA');

// variables for models with special instructions end

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
const currentUserData = ref([]);
const currentUserName = ref('');
const currentUserApproverStage = ref('');
const currentUserIP = ref('');
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
const reportihr95Minimum = ref('');
const reportihr98Minimum = ref('');

const reportBrVariance = ref('NA');
const reportiHcVariance = ref('NA');
const reportiHkVariance = ref('NA');

const reportSMPJudgement = ref('');
const reportExistingSMPJudgement = ref(null);
const reportPreparedByDate = ref(null);
const reportCheckedByDate = ref(null);
const reportApprovedByDate = ref(null);
const reportRemarksDisplayNG_ihcihk = ref(false);
const reportRemarksDisplayNG_br4pia = ref(false);
const reportRemarksDisplayNG_bhMax = ref(false);
const reportRemarksDisplayNG_bhc = ref(false);

const reportOvenMachineNo = ref('NA');
const reportTimeLoading = ref('NA');
const reportTemperature_TimeLoading = ref('NA');
const reportDate_OvenInfo = ref('');
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
const inspectionOvenMachineNo = ref('');
const inspectionTimeLoading = ref('');
const inspectionTemperature_TimeLoading = ref('');
const inspectionDate_OvenInfo = ref('');
const inspectionTimeUnloading = ref('');
const inspectionTemperature_TimeUnloading = ref('');
const inspectionShift_OvenInfo = ref('');
const inspectionOperator_OvenInfo = ref('');

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

const reportReset = () => {
    reportRemarksDisplayNG_ihcihk.value = false;
    reportRemarksDisplayNG_br4pia.value = false;
    reportRemarksDisplayNG_bhMax.value = false;
    reportRemarksDisplayNG_bhc.value = false;
    isTTM_model.value = false;
    isAutomotive.value = false;
    isAutomotiveInitiallyMarked.value = false;
    show1x1x1Data.value = false;
    noteReasonForReject.value = [];
}

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
    }, 5000);  // 5000ms = 5 seconds
}

const sec_additional_button = () => {
    //redirect here
}

const generateReport = async () => {
    showReportContent.value = true;
    showSelectionPanel.value = false;
    initialCarmarkChecking();
    await fetchAllData();
}

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


const fetchAllData = async () => {
    try {
        const responseTpm = await axios.get("/api/tpmdata?serial=" + currentSerialSelected.value);
        //console.log("Show All tpm data API response: ", responseTpm.data);

        // Filter the data to include only rows with serial_no like '0002'
        tpmData.value = responseTpm.data[0] || [];
        //console.log("Filtered tpm aggregate data list: ", tpmData.value);
        getTpmModel.value = responseTpm.data.data || [];
        //console.log("GetModelValue: ", getTpmModel.value[0].code_no);
        fetchMaterialCode.value = getTpmModel.value[0].code_no;
        const tpm_category_actualmodel = getTpmModel.value.map(item => item.category?.actual_model ?? null);
        jhCurveActualModel.value = tpm_category_actualmodel[0];

        //Remarks checking start
        getAllBrNG.value = getTpmModel.value.map(item => item.remark.Br_remarks || null);
        getAlliHcNG.value = getTpmModel.value.map(item => item.remark.iHc_remarks || null);
        getAlliHkNG.value = getTpmModel.value.map(item => item.remark.iHk_remarks || null);
        getAll4paildNG.value = getTpmModel.value.map(item => item.remark["4paild_remarks"] || null);
        getAll4pailsNG.value = getTpmModel.value.map(item => item.remark["4pails_remarks"] || null);
        getAll4pailaNG.value = getTpmModel.value.map(item => item.remark["4paila_remarks"] || null);
        getAllbHcNG.value = getTpmModel.value.map(item => item.remark.bHc_remarks || null);
        getAllBHMaxNG.value = getTpmModel.value.map(item => item.remark.BHMax_remarks || null);
        getAllSquarenessNG.value = getTpmModel.value.map(item => item.remark.Squareness_remarks || null);
        getAllDensityNG.value = getTpmModel.value.map(item => item.remark.Density_remarks || null);
        getAlliHkiHcNG.value = getTpmModel.value.map(item => item.remark.iHkiHc_remarks || null);
        getAllBr4paiNG.value = getTpmModel.value.map(item => item.remark.Br4pai_remarks || null);
        getAlliHr95NG.value = getTpmModel.value.map(item => item.remark.iHr95_remarks || null);
        getAlliHr98NG.value = getTpmModel.value.map(item => item.remark.iHr98_remarks || null);
        //console.log("check br remarks: ", getAllBrNG);
        // Check if "1" exists in getAllBrNG
        //REJECT CONDITIONS
        /*

const reportRemarksDisplayNG_ihcihk = ref(false);
const reportRemarksDisplayNG_br4pia = ref(false);
const reportRemarksDisplayNG_bhMax = ref(false);
const reportRemarksDisplayNG_bhc = ref(false);

        */


        if (getAllBrNG.value.includes("1") || getAlliHr95NG.value.includes("1") || getAlliHr98NG.value.includes("1") || getAllSquarenessNG.value.includes("1") || getAllDensityNG.value.includes("1") || getAlliHkiHcNG.value.includes("1") || getAllBr4paiNG.value.includes("1") || getAll4paildNG.value.includes("1") || getAll4pailsNG.value.includes("1") || getAll4pailaNG.value.includes("1") || getAllbHcNG.value.includes("1")) {

            reportRemarksDisplay.value = "E";
            reportSMPJudgement.value = "HOLD";

            if(getAlliHcNG.value.includes("1") || getAlliHkNG.value.includes("1") || getAllBHMaxNG.value.includes("1")){
                // Perform your action here (leave it blank for now)
                reportSMPJudgement.value = "REJECT";
            }
        }else{
            reportRemarksDisplay.value = "OK";
            reportSMPJudgement.value = "PASSED";
        }
        //Remarks checking end

        console.log("SMP JUDGEMENT IS: ",reportSMPJudgement.value);
        console.log("REPORT REMARKS IS: ",reportRemarksDisplay.value);


        fetchActualModel.value = jhCurveActualModel.value;
        const tpm_current_model = jhCurveActualModel.value;
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
        if (filteredFurnaceName) {
            currentFurnaceName.value = filteredFurnaceName.furnace_name;
        } else {
            console.error("Furnace not found for ID:", thisfurnaceId);
            currentFurnaceName.value = "Unknown Furnace"; // or whatever default value makes sense
        }

        const responseLayerAPI = await axios.get(`/api/layerdata/`);
        //console.log("Show All layer id API response: ", responseLayerAPI.data);
        const layerNames = responseLayerAPI.data.data["Layer Data"];
        //console.log("Layer arrays: ",layerNames);
        const filteredLayerName = layerNames.find(f =>
            f.layer_no == thisLayerId && f.furnace_id == thisfurnaceId
        );
        //console.log("filtered Layer Names: ",filteredLayerName);
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
        tpmData_ihr95Min.value = minimumData.Hr95;
        tpmData_ihr98Min.value = minimumData.Hr98;

        const responseInsp = await axios.get("/api/inspectiondata");
        //console.log("Show All inspection data API response: ", responseInsp.data);
        inspectionDataList.value = responseInsp.data.data || [];
        //console.log("Show All inspection data list : ", inspectionDataList.value);

        const getAllInspModels = inspectionDataList.value.map(item => item.model);
        console.log("List of models in inspection: ", getAllInspModels);
        console.log("Current model in tpm: ",tpm_current_model);

        // Check if tpm_current_model exists in getAllInspModels
        if (getAllInspModels.includes(fetchActualModel.value)) {
            const filteredInspectionData = inspectionDataList.value.filter(item => item.model == fetchActualModel.value);
            console.log("Filtered inspection data for the selected model: ", filteredInspectionData);
            console.log("Fetched model:", fetchActualModel.value);
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
                inspectionOvenMachineNo.value = item.oven_machine_no;
                inspectionTimeLoading.value = item.time_loading;
                inspectionTemperature_TimeLoading.value = item.temperature_1;
                inspectionDate_OvenInfo.value = item.date;
                inspectionTimeUnloading.value = item.time_unloading;
                inspectionTemperature_TimeUnloading.value = item.temperature_2;
                inspectionShift_OvenInfo.value = item.shift;
                inspectionOperator_OvenInfo.value = item.operator;
            });


        } else {
            showNotification2("The specified model does not exist in the inspection data. Please create the necessary inspection data first in the Inspection section of the website.");
            showReportContent.value = false;
            showSelectionPanel.value = true;
            return;
        }

        //console.log("Getting br value: ", inspectionBrStandard.value);  // Assuming each item has a `br` property

        if (inspectionBrStandard.value && inspectionBrStandard.value.includes('~')) {
            const [lower, higher] = inspectionBrStandard.value.split('~');
            inspectionBrStandard_lower.value = lower;
            inspectionBrStandard_higher.value = higher;
        } else {
            inspectionBrStandard_lower.value = '';
            inspectionBrStandard_higher.value = '';
        }
        console.log("brStandard LOWER: ",inspectionBrStandard_lower.value);
        console.log("brStandard HIGHER: ",inspectionBrStandard_higher.value);

        const repData = {
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
                "ihr95Minimum": tpmData_ihr95Min.value,
                "ihr98Minimum": tpmData_ihr98Min.value
            }),
            "material_grade": inspectionMaterialGrade.value,
            "material_code": fetchMaterialCode.value,
            "model": fetchActualModel.value,
            "mpi_sample_quantity": inspectionMpiSampleQty.value,
            "pulse_tracer_machine_number": tpmData_tracerNo.value,
            "thickness": inspectionThickness.value,
            "width": inspectionWidth.value
        }

        //console.log("Rep Data: ",repData);
        createReport(repData, currentSerialSelected.value);
        await nextTick();
        isLoading.value = false;
    } catch (error) {
        console.error("API get request showTpmData Error:", error);
        showNotification2("Data is incomplete for this serial. Please Rerun the data.");
        exitReport();
    }
};

const createReport = async (reportData, serial) => {
    try {
        console.log("Rep Data: ",reportData);
        const response = await axios.patch(`/api/reportdata/${serial}`, reportData);
        console.log("Create report function Patched report data: ", response.data);
    } catch (error) {
        console.error("Patch report data Error:", error);
    }
}

const showReportData = async () => {
    try {
        const response = await axios.get(`/api/reportdata/`);
        console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        console.log("Filtered data: ", filterBySerial);
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

        reportOvenMachineNo.value = filterBySerial[0].oven_machine_no;
        reportTimeLoading.value = filterBySerial[0].time_loading;
        reportTimeUnloading.value = filterBySerial[0].time_unloading;
        reportTemperature_TimeLoading.value = filterBySerial[0].temp_time_loading;
        reportTemperature_TimeUnloading.value = filterBySerial[0].temp_time_unloading;
        reportDate_OvenInfo.value = filterBySerial[0].date_oven_info;
        reportShift_OvenInfo.value = filterBySerial[0].shift_oven_info;
        reportOperator_OvenInfo.value = filterBySerial[0].operator_oven_info;

        isAutomotive.value = filterBySerial[0].withCarmark == 1;
        console.log("With carmark value: ",isAutomotive.value);

        const noteRejectReasons = JSON.parse(filterBySerial[0].note_reason_reject);
        console.log("Parsed noteRejectReasons from DB:", noteRejectReasons);

        if (noteRejectReasons && Array.isArray(noteRejectReasons)) {
            noteReasonForReject.value = []; // Clear existing values
            noteRejectReasons.forEach(reason => {
                noteReasonForReject.value.push(reason);
            });
        } else {
            console.warn("No valid noteRejectReasons found (null or not an array).");
        }

        console.log("Model value: ",reportModel.value);

        //console.log("Report Data Model", reportModel.value);

        const magneticProperty = JSON.parse(filterBySerial[0].magnetic_property_data);
        console.log("Magnetic Property: ", magneticProperty);
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

        evaluateAllRejectReasons();
        checkApprovalStates();
    } catch (error) {
        console.error("API get request showReportData Error:", error);
    }
}

const saveReport = async () => {
    // Use default values (empty string) or check for null/undefined before calling toUpperCase
    reportPartialNo.value = (reportPartialNo.value || '').toUpperCase();
    reportShift.value = (reportShift.value || '').toUpperCase();
    reportOperator.value = (reportOperator.value || '').toUpperCase();

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
        "partial_number": reportPartialNo.value,
        "shift": reportShift.value,
        "total_quantity": reportTotalQuantity.value,
        "operator": reportOperator.value,
        "remarks": reportRemarks.value,
        "smp_judgement":reportSMPJudgement.value,
        "withCarmark": isAutomotive.value,
        "remarks_display": reportRemarksDisplay.value,
        "note_reason_reject": noteReasonForReject.value,
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
    console.log("API Response fetchSerial-data:", response.data);

    // Extract furnace data dynamically
    tpmData.value = response.data.data["tpmDataAll"] || [];
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
  fromApproval: Boolean,
});
isFromApproval.value = props.fromApproval;
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

    preparedByPerson.value = currentUserName.value;
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
    checkedByPerson.value = currentUserName.value;
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

        if(prepared_by != "" && prepared_by != null){ //Approver Conditions
            showPreparedByDefault.value = false;
            preparedByButton.value = false;  //Already approved condition
            preparedByStampPhoto.value = true;
        }else if(currentUserApproverStage.value == "PREPARED BY"){
            showPreparedByDefault.value = false;
            preparedByButton.value = true; //Not approved yet but ready for approval
            preparedByStampPhoto.value = false;
        }else{
            preparedByButton.value = false;
            showPreparedByDefault.value = true; //Not approver, not approved yet
            preparedByStampPhoto.value = false;
        }

        if(checked_by != "" && checked_by != null){ //Approver Conditions
            showCheckedByDefault.value = false;
            checkedByButton.value = false;          //Already approved condition
            checkedByStampPhoto.value = true;
        }else if(currentUserApproverStage.value == "CHECKED BY" && (prepared_by != "" && prepared_by != null)){
            showCheckedByDefault.value = false;
            checkedByButton.value = true;       //Not approved yet but ready for approval
            checkedByStampPhoto.value = false;
        }else{
            checkedByButton.value = false;
            showCheckedByDefault.value = true; //Not approver, not approved yet
            checkedByStampPhoto.value = false;
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
            //insert finalize button here the button for printing
        }
    }catch(error){
        console.error("ERROR Getting report data API result: ", error);
    }
}

const checkCurrentUser = async () => {
    try {
        const responseFindApprovers = await axios.get("/api/approver");
        console.log('API GET requestFrom-responseFindPreparedBy: ', responseFindApprovers.data);

        const approvers = responseFindApprovers.data.data?.Approvers || [];

        currentUserData.value = approvers.filter(column => column.ip_address === ipAddress.value);
        console.log('Current approver data array: ', currentUserData.value);

        if (currentUserData.value.length > 0) {
            const userData = currentUserData.value[0];
            currentUserName.value = userData.approver_name;
            currentUserApproverStage.value = userData.approval_stage;
            currentUserIP.value = userData.ip_address;
            console.log('current user name: ', currentUserName.value);
            console.log('current approver stage: ', currentUserApproverStage.value);
            console.log('current user IP: ',currentUserIP.value);
        } else {
            console.warn("No approver found for the current IP address.");
            currentUserName.value = '';
            currentUserApproverStage.value = '';
        }

    } catch (error) {
        console.error("Error on checkCurrentUser API GET request", error);
    }
}

const evaluateAllRejectReasons = () => {
    if (!noteReasonForReject.value || noteReasonForReject.value.length === 0) {
    noteReasonForReject.value = []; // Reset before evaluation

    console.log('Evaluating rejection reasons part1...');

    if (getAlliHkiHcNG.value.includes("1")) {
      console.log('Condition met: getAlliHkiHcNG includes "1"');
      noteReasonForReject.value.push('- N.G iHc-iHk');
    }

    if (getAllBr4paiNG.value.includes("1")) {
      console.log('Condition met: getAllBr4paiNG includes "1"');
      noteReasonForReject.value.push('- N.G Br-4PIa');
    }

    if (getAllBHMaxNG.value.includes("1")) {
      console.log('Condition met: getAllBHMaxNG includes "1"');
      noteReasonForReject.value.push('- N.G BH(max)');
    }

    if (getAllbHcNG.value.includes("1")) {
      console.log('Condition met: getAllbHcNG includes "1"');
      noteReasonForReject.value.push('- N.G bHc');
    }

    console.log('Final Rejection Reasons part 1:', noteReasonForReject.value);

    console.log('Evaluating rejection reasons part2...');

    if (reportBrMinimum.value < inspectionBrStandard_lower.value) {
      console.log(`LOW BR: ${reportBrMinimum.value} < ${inspectionBrStandard_lower.value}`);
      noteReasonForReject.value.push('- LOW BR');
    }

    if (reportBrMaximum.value > inspectionBrStandard_higher.value) {
      console.log(`HIGH BR: ${reportBrMaximum.value} > ${inspectionBrStandard_higher.value}`);
      noteReasonForReject.value.push('- HIGH BR');
    }

    if (reportihcMinimum.value < inspectioniHcStandard.value) {
      console.log(`N.G iHc: ${reportihcMinimum.value} < ${inspectioniHcStandard.value}`);
      noteReasonForReject.value.push('- N.G iHc');
    } else if (reportihcMinimum.value < Number(inspectioniHcStandard.value) + 500) {
      console.log(`iHc Below Target+500 Oe: ${reportihcMinimum.value} < ${Number(inspectioniHcStandard.value) + 500}`);
      noteReasonForReject.value.push('- iHc Below Target+500 Oe');
    }

    if (reportihkMinimum.value < inspectioniHkStandard.value) {
      console.log(`N.G iHk: ${reportihkMinimum.value} < ${inspectioniHkStandard.value}`);
      noteReasonForReject.value.push('- N.G iHk');
    }

    if (reportihr95Minimum.value < Number(inspectioniHcStandard.value) - 750) {
      console.log(`N.G Hr95: ${reportihr95Minimum.value} < ${Number(inspectioniHcStandard.value) - 750}`);
      noteReasonForReject.value.push('- N.G Hr95');
    }

    if (reportihr98Minimum.value < Number(inspectioniHcStandard.value) - 1250) {
      console.log(`N.G Hr98: ${reportihr98Minimum.value} < ${Number(inspectioniHcStandard.value) - 1250}`);
      noteReasonForReject.value.push('- N.G Hr98');
    }

    console.log('Final Rejection Reasons part 2:', noteReasonForReject.value);
  } else {
    console.log('Skipping rejection evaluation: Reasons already exist.');
  }
}

const checkCarmark = async () => {
    try{
        const responseCarMark = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, {
            "withCarmark": isAutomotive.value,
        });
        console.log("Saved carmark data: ", responseCarMark.data);
        isAutomotive.value = true;
    }catch(error){
        console.log("ERROR API RESPONSE PATCH REQUEST: ",error);
    }
}

const initialCarmarkChecking = async () => {
    try{
        const response = await axios.get(`/api/reportdata/`);
        //console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        isAutomotiveInitiallyMarked.value = filterBySerial[0].withCarmark == 1;
        console.warn("Is carmarked initially marked? = ",isAutomotiveInitiallyMarked.value);
    }catch(error){
        console.error("ERROR GET REQUEST FOR CARMARK: ", error);
    }
}

const finalizeReport = (serial) => {
    Inertia.visit('/create_pdf', {
        method: 'get',   // You can keep 'get' since we are not modifying any data
        data: { serialParam: serial },   // Passing the serialParam here
        preserveState: true,
        preserveScroll: true,
    });
}

const sec_additional_redirect = (sec_serial) => {
    try {
        console.log('Navigating to report with serial:', sec_serial);
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

// onMounted logic to call the function based on serialParam existence
onMounted(() => {
    checkCurrentUser();
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
