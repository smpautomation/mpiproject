<template>
    <Frontend>
      <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto bg-gray-100">
        <div v-if="!isFromApproval">
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
                            <input v-model="reportMaterialCode" @input="reportMaterialCode = reportMaterialCode.toUpperCase()" type="text" name="materialCode" class="w-[12rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out">
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

                                <template v-if="showROB && (noteReasonForReject.includes('- N.G iHc'))">
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
                                        <td class="px-4 py-2 text-blue-600 border-4 border-white">{{ reportROB_BrVTstardard }} kg</td>
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
                                <tr v-show="showBHData" class="text-center">
                                    <th colspan="2" class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">BH Data @ <input type="number" v-model="reportBH_temp" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> °C</th>
                                    <td colspan="3" class="px-1 py-[2px] font-extrabold text-white border-4 border-white bg-blue-300"><input type="text" v-model="reportBH_sample" @input="reportBH_sample = reportBH_sample.toUpperCase()" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"></td>
                                        <th class="px-1 py-[2px] font-extrabold text-white bg-blue-300 border-4 border-white">Result</th>
                                </tr>
                                <tr v-show="showBHData" class="text-center">
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
                                    <td colspan="3" class="px-1 py-[2px] text-blue-600 border-4 border-white"><input type="number" v-model="reportBH_result" name="stdDev" class="w-[6.5rem] h-[1.5rem] py-[14px] mt-1 text-sm border border-gray-300 rounded-md bg-white text-gray-800
                                        hover:border-blue-400 hover:ring-1 hover:ring-blue-300
                                        focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-white
                                        transition duration-200 ease-in-out"> kOe</td>
                                    <td class="px-1 py-[2px] text-blue-600 border-4 border-white">{{ reportBH_remarks }}</td>
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
                    <p class="p-2 text-center border-b-4 border-l-4 border-r-4 border-white">
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
                    </div>

                    <!-- Prepared By -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white whitespace-nowrap">Prepared By:</p>
                    <div class="items-center p-1 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <div v-show="showPreparedByDefault" class="w-[153px] h-[153px]">
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
                        class="flex flex-col items-center justify-center w-40 h-40 text-2xl font-extrabold text-center text-red-600 bg-center bg-no-repeat"
                        :style="{
                            backgroundImage: 'url(\'/photo/template.png\')',
                            backgroundSize: '101%'
                        }">
                        <span :class="getFontSize(preparedByPerson_firstName)">{{ preparedByPerson_firstName }}</span>
                        <span class="my-[6.5px]">{{ reportPreparedByDate }}</span>
                        <span :class="getFontSize(preparedByPerson_lastName)">{{ preparedByPerson_lastName }}</span>
                        </span>
                    </div>
                    </div>

                    <!-- Checked By -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center text-white bg-blue-400 border-4 border-white whitespace-nowrap">Checked By:</p>
                    <div class="items-center p-1 text-center border-b-4 border-l-4 border-r-4 border-white">
                        <div v-show="showCheckedByDefault" class="w-[153px] h-[153px]">
                        <span class="font-extrabold text-blue-700 opacity-100 animate-pulse whitespace-nowrap">
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
                        <span class="font-extrabold text-blue-700 opacity-100 animate-pulse whitespace-nowrap">
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
                                v-for="(note, index) in sortedNotes"
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
                    <button v-if="(currentUserIP == ipAddress) && (approvedByPerson == '' || approvedByPerson == null) && !isFromApproval" @click="saveReport" class="px-6 py-4 mt-4 font-extrabold text-white transition duration-300 ease-in-out transform bg-green-500 shadow-xl rounded-xl hover:bg-green-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-600 active:scale-95">
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
                        Apply Additional
                    </button>
                    <button
                        v-if="checkedByPerson"
                        @click="finalizeReport(currentSerialSelected)"
                        class="px-6 py-4 mt-4 ml-5 font-extrabold text-yellow-600 transition duration-300 ease-in-out transform border border-yellow-400 shadow-xl rounded-xl hover:text-white hover:bg-yellow-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-600 active:scale-95"
                    >
                        Finalize Report
                    </button>
                    <!-- Finalize Report BYPASS Button

                    <button @click="finalizeReport(currentSerialSelected)" class="p-2 bg-white rounded-lg">
                        Finalize Report BYPASS
                    </button>

                    -->
                    <button v-if="showExitButton && !isFromApproval" @click="exitReport()" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK
                    </button>

                    <button v-if="isFromApproval" @click="$inertia.visit('/approval')" class="px-6 py-4 mt-4 ml-5 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                        BACK TO APPROVAL
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
import { ref, onMounted, nextTick, watch, computed, watchEffect } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/vue3'
import DotsLoader from '@/Components/DotsLoader.vue';

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
const showROB = ref(false);

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
const reportBH_sample = ref(0);
const reportBH_temp = ref(0);
const reportBH_result = ref(0);

const reportROB_brMax = ref('');
const reportROB_brMin = ref('');
const reportROB_iHcMax = ref('');
const reportROB_iHcMin = ref('');
const reportROB_BrRTstandard = ref(13.0);
const reportROB_BrVTstandard = ref(10.5);
const reportROB_HD5standard = ref(10.053);
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
const currentUserData = ref([]);
const currentUserName = ref('');
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

// Method to dynamically adjust font size based on string length
const getFontSize = (name) => {
  const length = name.length;

  if (length <= 4) return 'text-[32px]';         // Very short names
  if (length === 5) return 'text-[30px]';
  if (length === 6) return 'text-[26px]';
  if (length === 7) return 'text-[24px]';
  if (length === 8) return 'text-[22px]';         // Mid point
  if (length === 9) return 'text-[20px]';
  if (length === 10) return 'text-[18px]';
  if (length === 11) return 'text-[16px]';
  return 'text-[14px]';                          // 12 or more characters
};

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


// special judgement conditions logic

//FOR ROB - models ROB-0A70G
watchEffect(() => {
    if (noteReasonForReject.value.includes('- N.G iHc') && showROB.value === true) {
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
            reportSMPJudgement.value = 'REJECT';
        } else {
            reportROB_remarks.value = 'OK';
            reportSMPJudgement.value = 'HOLD';
        }
    }
});

//FOR VT - models DNS-0A54G, MIE-0751G, MIS-0766G
watch(
  [reportVT_iHcResults, reportVT_iHc, reportVT_remarks, reportSMPJudgement],
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
  [reportSurface_cpk, reportCore_cpk, reportSurface_remarks, reportCore_remarks, reportSMPJudgement],
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
  [reportCpkFrom_iHc_Cpk, reportCpkFrom_iHc_remarks, reportSMPJudgement],
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
  [reportGX_iHcMinimum, reportGX_iHcStandard, reportSMPJudgement],
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

//FOR BH - models
watch(
  [reportBH_result, reportBH_dataStandard, reportSMPJudgement, reportBH_remarks],
  () => {
    if (
      noteReasonForReject.value.includes('- N.G iHc') &&
      showBHData.value === true &&
      reportBH_result.value !== null &&
      reportBH_dataStandard.value !== null
    ) {
      if (reportBH_result.value < reportBH_dataStandard.value) {
        reportBH_remarks.value = 'NG';
        reportSMPJudgement.value = 'REJECT';
      } else {
        reportBH_remarks.value = 'OK';
        reportSMPJudgement.value = 'HOLD';
      }
    } else {
      //console.log('BH display not active — skipping BH check');
    }
  },
  { immediate: true }
);

//For TTM Models
watch(
  [reportCpk, reportCpkRemarks, reportSurface_cpk, reportCore_cpk, reportCorner_cpk, reportSMPJudgement, reportSurface_remarks, reportCore_remarks, reportCorner_remarks],
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

const showNotification2 = (message) => {
    // Show notification and set the message
    showNotif2.value = true;
    reportNotificationMessage.value = message;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        showNotif2.value = false;
    }, 2000);  // 2000ms = 2 seconds
}

const sec_additional_button = () => {
    //redirect here
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
    const hasNGihc = noteReasonForReject.value.includes('- N.G iHc');
    isTTM_model.value = jhCurveActualModel.value.includes("TTM");
    if (!hasNGihc) return;

    const model = jhCurveActualModel.value;

    // === Model Groups by Behavior ===
    const MODELS_SHOW_VT_DATA     = ["DNS0A54G", "MIS0766G", "MIE0751G","DNS0942G","MIE0599G","MIE0602G","MIE0603G","MIE0605G","MIE0606G","MIE0C51G","MIE0C63G","MIE0C72G","JTT0051G","JTT0740G","NIM0C31G"];
    const MODELS_1X1X1_NO_CORNER  = ["TTM0A58D", "TTM0C16D", "AAW0935G"];
    const MODELS_SHOW_CPK         = ["DNS0917G"];
    const MODELS_SHOW_GX          = ["MIE0983G", "AAW0969G","DNS0134G","MIE0860G"];
    const MODELS_SHOW_BH          = ["ZFS0982G"];
    const MODELS_SHOW_ROB         = ["ROB0A70G"]; //ROB0A70G

    // === Logic Blocks ===

    if (MODELS_SHOW_VT_DATA.includes(model) && reportVT_samplesQty.value > 0) {
        showVTData.value = true;
        showVTData_default.value = false;
    }else if(MODELS_SHOW_VT_DATA.includes(model)){
        showVTData.value = false;
        showVTData_default.value = true;
    }

    if (model.includes("TTM") || MODELS_1X1X1_NO_CORNER.includes(model)) {
        show1x1x1Data_withoutCorner.value = true;

        if (model.includes("TTM") && !["TTM0A58D", "TTM0C16D"].includes(model)) {
            show1x1x1Data_Corner.value = true;
        }
    }

    if (MODELS_SHOW_CPK.includes(model))  showCpkFrom_iHc.value = true;
    if (MODELS_SHOW_GX.includes(model))   showGX.value = true;
    if (MODELS_SHOW_BH.includes(model))   showBHData.value = true;
    if (MODELS_SHOW_ROB.includes(model))  showROB.value = true;
};

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

        //console.log("SMP JUDGEMENT IS: ",reportSMPJudgement.value);
        //console.log("REPORT REMARKS IS: ",reportRemarksDisplay.value);


        fetchActualModel.value = jhCurveActualModel.value;
        const tpm_current_model = jhCurveActualModel.value;
        tpmData_tracerNo.value = getTpmModel.value[0].Tracer;
        const thisLayerId = getTpmModel.value[0].layer_no;
        const thisfurnaceId = getTpmModel.value[0].furnace_id;
        //console.log("Finding layer no: ", thisLayerId);
        //console.log("Finding furnace id: ",thisfurnaceId);

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
        //console.log("List of models in inspection: ", getAllInspModels);
        //console.log("Current model in tpm: ",tpm_current_model);

        // Check if tpm_current_model exists in getAllInspModels
        if (getAllInspModels.includes(fetchActualModel.value)) {
            const filteredInspectionData = inspectionDataList.value.filter(item => item.model == fetchActualModel.value);
            //console.log("Filtered inspection data for the selected model: ", filteredInspectionData);
            //console.log("Fetched model:", fetchActualModel.value);
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
                inspectionAutomotive.value = item.is_automotive;
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
        //console.log("brStandard LOWER: ",inspectionBrStandard_lower.value);
        //console.log("brStandard HIGHER: ",inspectionBrStandard_higher.value);

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
            "width": inspectionWidth.value,
            "withCarmark": inspectionAutomotive.value,
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

        isAutomotive.value = filterBySerial[0].withCarmark == 1;
        //console.log("With carmark value: ",isAutomotive.value);

        //Request to AUTO 'N/A' all oven fields if no oven - 5/31/2025
        if(inspectionOvenMachineNo.value == 0){
            reportTimeLoading.value = 'N/A';
            reportTimeUnloading.value = 'N/A';
            reportTemperature_TimeLoading.value = 'N/A';
            reportTemperature_TimeUnloading.value = 'N/A';
            reportDate_OvenInfo.value = 'N/A';
            reportShift_OvenInfo.value = 'N/A';
            reportOperator_OvenInfo.value = 'N/A';
        }else{
            reportOvenMachineNo.value = filterBySerial[0].oven_machine_no;
            reportTimeLoading.value = filterBySerial[0].time_loading;
            reportTimeUnloading.value = filterBySerial[0].time_unloading;
            reportTemperature_TimeLoading.value = filterBySerial[0].temp_time_loading;
            reportTemperature_TimeUnloading.value = filterBySerial[0].temp_time_unloading;
            reportDate_OvenInfo.value = filterBySerial[0].date_oven_info;
            reportShift_OvenInfo.value = filterBySerial[0].shift_oven_info;
            reportOperator_OvenInfo.value = filterBySerial[0].operator_oven_info;
        }

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

        reportGX_iHcStandard.value = GX.iHcStandard || '';
        reportGX_iHcAverage.value = GX.iHcAverage || '';
        reportGX_iHcMaximum.value = GX.iHcMaximum || '';
        reportGX_iHcMinimum.value = GX.iHcMinimum || '';
        reportGX_iHcVariance.value = (GX.iHcMaximum - GX.iHcMinimum) || '';
        reportGX_iHkAverage.value = GX.iHkAverage || '';
        reportGX_iHkMaximum.value = GX.iHkMaximum || '';
        reportGX_iHkMinimum.value = GX.iHkMinimum || '';
        reportGX_iHkVariance.value = (GX.iHkMaximum - GX.iHkMinimum) || '';

        reportBH_data.value = bh.data || '';
        reportBH_dataStandard.value = bh.dataStandard || '';
        reportBH_remarks.value = bh.remarks || '';
        reportBH_sample.value = bh.sample || '';
        reportBH_temp.value = bh.temp || '';
        reportBH_result.value = bh.result || '';

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
        "smp_judgement":reportSMPJudgement.value,
        "remarks_display": reportRemarksDisplay.value,
        "note_reason_reject": noteReasonForReject.value,
        "std_dev": reportStdDev.value,
        "cp": reportCpk.value,
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
            "sample": reportBH_sample.value,
            "temp": reportBH_temp.value,
            "result": reportBH_result.value,
        }),
        "data_ROB_info": JSON.stringify({
            "brMin": reportROB_brMin.value,
            "brMax": reportROB_brMax.value,
            "iHcMin": reportROB_iHcMin.value,
            "iHcMax": reportROB_iHcMax.value,
            "brRTStandard": reportROB_BrRTstandard,
            "brVTStandard": reportROB_BrVTstandard,
            "hd5Standard": reportROB_HD5standard,
            "jd5Standard": reportROB_JD5standard,
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
    }
    //console.log("Save report data: ", saveReportData);
    saveReportUpdate(saveReportData, currentSerialSelected.value);
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
  ipAddress: String,
  fromApproval: [Boolean, String],
});
// Update value after props are available
isFromApproval.value = props.fromApproval === true || props.fromApproval === 'true';
//console.log('isFromApproval:', isFromApproval.value);
ipAddress.value = props.ipAddress;
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

    preparedByPerson.value = currentUserName.value;
    // Split the full name into first and last name
    preparedByStampPhoto.value = true;
    preparedByStampConfirmation.value = false;
    const dateNow = datenow();
    //console.log("Prepared By Has been stamped by -> ", preparedByPerson.value);
    const preparedByData = {
        prepared_by: preparedByPerson.value, // Set the approved_by field to "ITADANI KAZUYA"
        prepared_by_date: dateNow
    };

    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, preparedByData);
    //console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);

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
    checkedByPerson.value = currentUserName.value;
    checkedByStampPhoto.value = true;
    checkedByStampConfirmation.value = false;
    const dateNow = datenow();
    //console.log("Checked By Has been stamped by -> ", checkedByPerson.value);
    const checkedByData = {
        checked_by: checkedByPerson.value,
        checked_by_date: dateNow
    };

    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, checkedByData);
    //console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);

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

        const prepared_by = filterBySerial[0]?.prepared_by;
        const checked_by = filterBySerial[0]?.checked_by;
        const approved_by = filterBySerial[0]?.approved_by;

        if (prepared_by != "" && prepared_by != null) {
            showPreparedByDefault.value = false;
            preparedByButton.value = false;
            preparedByStampPhoto.value = true;

            const nameParts = prepared_by ? prepared_by.split(' ') : [''];
            preparedByPerson_firstName.value = nameParts[0];
            preparedByPerson_lastName.value = nameParts.length > 1 ? nameParts.slice(1).join(' ') : '';
            //console.error("Prepared By First Name: ", preparedByPerson_firstName.value);
            //console.error("Prepared By Last Name: ", preparedByPerson_lastName.value);
        } else if (currentUserApproverStage.value == "PREPARED BY") {
            showPreparedByDefault.value = false;
            preparedByButton.value = true;
            preparedByStampPhoto.value = false;
        } else {
            preparedByButton.value = false;
            showPreparedByDefault.value = true;
            preparedByStampPhoto.value = false;
        }

        if (checked_by != "" && checked_by != null) {
            showCheckedByDefault.value = false;
            checkedByButton.value = false;
            checkedByStampPhoto.value = true;

            const nameParts = checked_by ? checked_by.split(' ') : [''];
            checkedByPerson_firstName.value = nameParts[0];
            checkedByPerson_lastName.value = nameParts.length > 1 ? nameParts.slice(1).join(' ') : '';
        } else if (currentUserApproverStage.value == "CHECKED BY" && (prepared_by != "" && prepared_by != null)) {
            showCheckedByDefault.value = false;
            checkedByButton.value = true;
            checkedByStampPhoto.value = false;
        } else {
            checkedByButton.value = false;
            showCheckedByDefault.value = true;
            checkedByStampPhoto.value = false;
        }

        if (approved_by == "" || approved_by == null) {
            showApprovedByDefault.value = true;
            approvedByStampPhoto.value = false;

            const nameParts = checked_by ? checked_by.split(' ') : [''];
            approvedByPerson_firstName.value = nameParts[0];
            approvedByPerson_lastName.value = nameParts.length > 1 ? nameParts.slice(1).join(' ') : '';
            //console.error("Checked By First Name: ", approvedByPerson_firstName.value);
            //console.error("Checked By Last Name: ", approvedByPerson_lastName.value);
        } else {
            approvedByButton.value = false;
            showApprovedByDefault.value = false;
            approvedByStampPhoto.value = true;
            showReportSaveButton.value = false;
            //insert finalize button here the button for printing
        }

    } catch (error) {
        console.error("ERROR Getting report data API result: ", error);
    }
};

const checkCurrentUser = async () => {
    try {
        const responseFindApprovers = await axios.get("/api/approver");
        //console.log('API GET requestFrom-responseFindPreparedBy: ', responseFindApprovers.data);

        const approvers = responseFindApprovers.data.data?.Approvers || [];

        currentUserData.value = approvers.filter(column => column.ip_address === ipAddress.value);
        //console.log('Current approver data array: ', currentUserData.value);

        if (currentUserData.value.length > 0) {
            const userData = currentUserData.value[0];
            currentUserName.value = userData.approver_name;
            currentUserApproverStage.value = userData.approval_stage;
            currentUserIP.value = userData.ip_address;
            //console.log('current user name: ', currentUserName.value);
            //console.log('current approver stage: ', currentUserApproverStage.value);
            //console.log('current user IP: ',currentUserIP.value);
        } else {
            console.warn("No approver found for the current IP address.");
            currentUserName.value = '';
            currentUserApproverStage.value = '';
        }

    } catch (error) {
        console.error("Error on checkCurrentUser API GET request", error);
    }
}

const evaluateAllRejectReasons = async () => {
    console.log('Have already entered Evalation for Reject reasons...');

    // force some async delay for testing
    await new Promise(res => setTimeout(res, 100));

    if (!noteReasonForReject.value || noteReasonForReject.value.length === 0) {
    noteReasonForReject.value = []; // Reset before evaluation

    //console.log('Evaluating rejection reasons part1...');

    if (getAlliHkiHcNG.value.includes("1")) {
      //console.log('Condition met: getAlliHkiHcNG includes "1"');
      noteReasonForReject.value.push('- N.G iHc-iHk');
    }

    if (getAllBr4paiNG.value.includes("1")) {
      //console.log('Condition met: getAllBr4paiNG includes "1"');
      noteReasonForReject.value.push('- N.G Br-4PIa');
    }

    if (getAllBHMaxNG.value.includes("1")) {
      //console.log('Condition met: getAllBHMaxNG includes "1"');
      noteReasonForReject.value.push('- N.G BH(max)');
    }

    if (getAllbHcNG.value.includes("1")) {
      //console.log('Condition met: getAllbHcNG includes "1"');
      noteReasonForReject.value.push('- N.G bHc');
    }

    //console.log('Final Rejection Reasons part 1:', noteReasonForReject.value);

    //console.log('Evaluating rejection reasons part2...');

    if (reportBrMinimum.value < inspectionBrStandard_lower.value) {
      //console.log(`LOW BR: ${reportBrMinimum.value} < ${inspectionBrStandard_lower.value}`);
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
      //console.log(`iHc Below Target+500 Oe: ${reportihcMinimum.value} < ${Number(inspectioniHcStandard.value) + 500}`);
      noteReasonForReject.value.push('- iHc Below Target+500 Oe');
    }

    if (reportihkMinimum.value < inspectioniHkStandard.value) {
      console.log(`N.G iHk: ${reportihkMinimum.value} < ${inspectioniHkStandard.value}`);
      noteReasonForReject.value.push('- N.G iHk');
    }

    if ((reportihr95Minimum.value < Number(inspectioniHcStandard.value) - 750) && (getAlliHr95NG.value.includes("1"))) {
      //console.log(`N.G Hr95: ${reportihr95Minimum.value} < ${Number(inspectioniHcStandard.value) - 750}`);
      noteReasonForReject.value.push('- N.G Hr95');
    }

    if ((reportihr98Minimum.value < Number(inspectioniHcStandard.value) - 1250) && (getAlliHr98NG.value.includes("1"))) {
      //console.log(`N.G Hr98: ${reportihr98Minimum.value} < ${Number(inspectioniHcStandard.value) - 1250}`);
      noteReasonForReject.value.push('- N.G Hr98');
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

// onMounted logic to call the function based on serialParam existence
onMounted(async () => {
    checkCurrentUser();

    if (props.serialParam && props.fromApproval) {
        currentSerialSelected.value = props.serialParam;

        // This ensures reactivity is flushed before running the report logic
        await Promise.resolve();

        generateReport();
    } else {
        fetchSerial();
    }
});
</script>
