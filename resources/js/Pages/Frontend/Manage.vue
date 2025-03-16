<template>
    <Frontend>
        <div class="flex flex-col justify-center items-center px-64 py-24 container bg-gray-100 mx-auto">
            <div v-if="!toggleManageForm" class="flex flex-col items-center justify-center py-4 space-y-4">
                <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" @click="showManageForm">
                    Add New
                </button>
            </div>
            <div v-if="toggleManageForm" class="flex flex-col items-center justify-center w-full">
                <p class="flex flex-col mb-10 font-extrabold">Serial: {{ serialNo }}</p>
                <div v-show="showUploadData" class="flex flex-row items-center justify-center">
                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 mr-10 rounded-lg shadow-lg bg-gray-50">
                        <!-- Upload Section Title -->
                        <p class="mb-4 text-xl font-semibold text-gray-800">Upload Raw Data:</p>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                            <!-- File Input Label -->
                            <label for="file-upload" class="text-lg font-medium text-gray-600">Select a file to upload:</label>

                            <!-- File Upload Input -->
                            <input
                                id="file-upload"
                                type="file"
                                accept=".tpm"
                                multiple
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @change="storeFileList"
                            />

                            <div>

                            <button
                                class="px-4 py-2 mr-24 font-semibold text-white bg-red-700 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                @click="clearFileUpload"
                            >
                                Clear
                            </button>

                            <!-- Submit Button -->
                            <input
                                type="submit"
                                id="submitRawdata"
                                class="px-4 py-2 font-semibold text-white transition duration-200 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                @click="saveToDatabase"
                            />
                            <!-- Button to Redo Upload -->

                            </div>

                            <!-- Optional Instruction Text -->
                            <p class="mt-2 text-sm text-gray-500">Only .tpm files are allowed</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
                        <p>Files: </p>
                        <div v-for="(fileList, index) in fileLists" :key="index" class="p-2 m-2 text-white bg-blue-400 rounded-3xl">
                            {{ fileList }}
                        </div>
                    </div>
                </div>
                <div>
                    <div v-show="showProceed" class="flex flex-col items-center justify-center">
                        <p>UPLOAD SUCCESSFULLY COMPLETED!</p>
                        <button
                            class="px-4 py-2 mt-4 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="showAllData"
                            >
                                Proceed
                        </button>
                    </div>
                </div>

               <!-- Loading Indicator -->
                <div
                v-if="layerTableRowLoading"
                class="flex items-center justify-center"
                >
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 border-t-4 border-blue-500 rounded-full animate-spin"></div>
                    <span class="mt-4 text-lg font-medium text-white">Loading...</span>
                </div>
                </div>

            <div v-show="showGraphAndTables">
                <!-- Chart Container -->
                <div class="w-full max-w-[1000px] h-[550px] bg-blue-100 rounded-xl mx-auto">
                    <canvas id="myChart"></canvas>
                </div>
                <div class="w-full p-6 rounded-lg shadow-lg mt-14 bg-gray-50">
                    <label class="block w-full p-2 mb-4 text-2xl font-semibold text-gray-800 rounded-md shadow-xl bg-gradient-to-r from-yellow-400 to-yellow-100 max-w-40">
                        FIRST LAYER
                    </label>

                        <div class="flex flex-col items-center justify-center w-full">
                            <div class="flex flex-row items-center justify-center w-full">
                                <div class="w-full mb-20 overflow-x-auto shadow-2xl"> <!-- Added overflow-x-auto for scrollable table -->
                                    <!-- Table with Full Width -->
                                    <table class="w-full overflow-hidden border border-gray-300 table-auto rounded-xl">
                                        <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                            <tr>
                                                <th v-for="tableLayerColumnHeader in tableLayerColumnHeaders"
                                                    :key="tableLayerColumnHeader.name"
                                                    :colspan="tableLayerColumnHeader.colspan"
                                                    class="px-3 py-2 text-sm font-medium text-center border border-white sm:px-4 sm:py-3 sm:text-base md:px-5 md:py-4 md:text-lg">
                                                    {{ tableLayerColumnHeader.name }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr
                                                v-for="item in combinedData"
                                                :key="item.id"
                                                class="border-b hover:bg-gray-50"
                                            >
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.date }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.serial_no }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.code_no }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.order_no }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.type }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.press_1 }} {{ item.press_2 }} {{ item.machine_no }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.sintering_furnace_no }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.furnace_no }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.zone }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.pass_no }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.Br }}
                                                </td>
                                                <td
                                                    v-if="item.remark.Br_remarks == '1'"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.Br_remarks == '0' ? '' : item.remark.Br_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.iHc }}
                                                </td>
                                                <td
                                                    v-if="item.remark.iHc_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.iHc_remarks == 0 ? '' : item.remark.iHc_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.iHk }}
                                                </td>
                                                <td
                                                    v-if="item.remark.iHk_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.iHk_remarks == 0 ? '' : item.remark.iHk_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.BHMax }}
                                                </td>
                                                <td
                                                    v-if="item.remark.BHMax_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.BHMax_remarks == 0 ? '' : item.remark.BHMax_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.iHr95 }}
                                                </td>
                                                <td
                                                    v-if="item.remark.iHr95_remarks == '1'"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.iHr95_remarks == '0' ? '' : item.remark.iHr95_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.iHr98 }}
                                                </td>
                                                <td
                                                    v-if="item.remark.iHr98_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.iHr98_remarks == 0 ? '' : item.remark.iHr98_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.iHkiHc }}
                                                </td>
                                                <td
                                                    v-if="item.remark.iHkiHc_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.iHkiHc_remarks == 0 ? '' : item.remark.iHkiHc_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.Br4pai }}
                                                </td>
                                                <td
                                                    v-if="item.remark.Br4pai_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.Br4pai_remarks == 0 ? '' : item.remark.Br4pai_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.bHc }}
                                                </td>
                                                <td
                                                    v-if="item.remark.bHc_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.bHc_remarks == 0 ? '' : item.remark.bHc_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.Squareness }}
                                                </td>
                                                <td
                                                    v-if="item.remark.Squareness_remarks == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark.Squareness_remarks == 0 ? '' : item.remark.Squareness_remarks }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item['4paiId'] }}
                                                </td>
                                                <td
                                                    v-if="item.remark['4paiId_remarks'] == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark['4paiId_remarks'] == 0 ? '' : item.remark['4paiId_remarks'] }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item['4paiIs'] }}
                                                </td>
                                                <td
                                                    v-if="item.remark['4paiIs_remarks'] == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark['4paiIs_remarks'] == 0 ? '' : item.remark['4paiIs_remarks'] }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item['4paiIa'] }}
                                                </td>
                                                <td
                                                    v-if="item.remark['4paiIa_remarks'] == 1"
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 bg-red-500 text-white'">
                                                    E
                                                </td>
                                                <td
                                                    v-else
                                                    :class="'px-3 py-2 text-sm text-center border border-blue-500 text-gray-700'">
                                                    {{ item.remark['4paiIa_remarks'] == 0 ? '' : item.remark['4paiIa_remarks'] }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ item.Tracer }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="ml-5 overflow-hidden border border-gray-300 rounded-lg shadow-2xl table-auto">
                                        <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                            <tr>
                                                <th class="px-3 py-6 text-xl font-extrabold text-center border border-white">Sample&nbsp;with&nbsp;Variance</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr v-for="(variance, index) in sampleWithVariances" :key="index">
                                                <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                                    {{ variance }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex flex-row">
                                <div class="mb-16">
                                    <table class="overflow-hidden border border-gray-300 rounded-lg shadow-2xl table-auto">
                                        <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                            <tr>
                                                <th v-for="secondTableLayerColumnHeader in secondTableLayerColumnHeaders" :key="secondTableLayerColumnHeader.name" class="px-3 py-2 text-2xl font-medium text-center">{{ secondTableLayerColumnHeader.name }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-3 py-2 text-2xl font-extrabold text-center text-white bg-blue-700">AVERAGE</td>
                                                <td v-for="(aggAveValue, index) in aggAveValues" :key="index" class="px-3 py-2 text-xl text-center text-gray-700 border border-blue-500">
                                                    {{ aggAveValue.value }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2 text-2xl font-extrabold text-center text-white bg-blue-700">MAXIMUM</td>
                                                <td v-for="(aggMaxValue, index) in aggMaxValues" :key="index" class="px-3 py-2 text-xl text-center text-gray-700 border border-blue-500">
                                                    {{ aggMaxValue.value }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2 text-2xl font-extrabold text-center text-white bg-blue-700">MINIMUM</td>
                                                <td v-for="(aggMinValue, index) in aggMinValues" :key="index" class="px-3 py-2 text-xl text-center text-gray-700 border border-blue-500">
                                                    {{ aggMinValue.value }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 py-2 text-2xl font-extrabold text-center text-white bg-red-800">NG COUNTER</td>
                                                <td v-for="(ngCount, index) in aggNGCounts" :key="index" class="px-3 py-2 text-xl text-center text-gray-700 border border-blue-500">
                                                    {{ ngCount }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex flex-col items-center justify-center p-5 mb-10 ml-40 bg-blue-200 shadow-lg rounded-xl">
                                    <div class="flex flex-row justify-center">
                                        <p class="px-12 mb-4 text-3xl font-extrabold text-white bg-blue-900 shadow-xl rounded-xl">Data Results:</p>
                                    </div>
                                    <div class="flex flex-row items-center px-16 py-6 bg-white rounded-3xl">
                                        <p class="mr-2 text-4xl font-extrabold">
                                            <span :class="adjustColor_rejectOKNG(rejectOKNGlist)" v-for="(rejectOKNGlist, index) in rejectOKNG" :key="index"> {{ rejectOKNGlist }} </span>
                                        </p>
                                        <p class="ml-2 text-2xl">
                                            <span :class="adjustColor_rejectInstructions"> {{ rejectInstruction }} </span>
                                        </p>
                                    </div>
                                    <div class="flex flex-row items-center mt-4 mb-0 align-baseline">
                                        <div class="flex flex-col items-center px-4 py-4 mr-2 bg-white rounded-3xl">
                                            <p class="text-2xl font-semibold">
                                                <span :class="adjustColor_rejectiHc"> {{ rejectiHcRemarks }} </span>
                                            </p>
                                            <p class="text-4xl font-extrabold">
                                                <span :class="adjustColor_iHcValue"> {{ getHighestSampleVariance }} </span>
                                            </p>
                                        </div>
                                        <div class="flex px-4 py-8 ml-2 bg-white rounded-3xl">
                                            <p class="text-4xl font-extrabold">
                                                <span :class="adjustColor_rejectLotRemarks(rejectLotRemarksList)" v-for="(rejectLotRemarksList, index) in rejectLotRemarks" :key="index"> {{ rejectLotRemarksList }} </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
  </template>

  <script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { ref, computed } from 'vue';
    import { Chart, registerables } from 'chart.js'; // Import all required components
    // Register all Chart.js components using registerables
    Chart.register(...registerables);

    //UI VISIBILITY variables...
    const showGraphAndTables = ref(false);
    const showUploadData = ref(true);
    const showProceed = ref(false);

    const toggleManageForm = ref(false);
    const showManageForm = () => {
        toggleManageForm.value = !toggleManageForm.value;
        generateSerialNumber();
    }

    //UI VISIBILITY variables end ...

    //UI Dynamic Color adjustments

    const adjustColor_rejectOKNG = (arrayString_OKNG) => {
        console.log(arrayString_OKNG); // Log the value to see what it is
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

    //UI Dynamic Color adjustments end

    //Serial Generation

    const serialNo = ref(null);  // Reactive variable to hold the generated serial number
    const generateSerialNumber = async () => {
        try {
            // Step 1: Fetch the data from the API
            const response = await axios.get("/api/tpmdata"); // Adjust this URL to your API endpoint
            const tpmData = response.data.data.tpmData || [];  // Fallback to an empty array if undefined

            if (tpmData.length > 0) {
                // Step 2: Loop through tpmData to find serial_no values and find the maximum serial_no
                const serialNumbers = tpmData.map(item => item.serial_no).filter(serial => serial);  // Get all serial_no values
                // Step 3: Find the highest serial number
                const highestSerial = serialNumbers.reduce((max, current) => {
                    return parseInt(current.slice(4), 10) > parseInt(max.slice(4), 10) ? current : max;
                });

                const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
                const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)

                // Extract numeric part of the serial number and increment by 1
                const numericPart = parseInt(highestSerial.slice(4), 10);
                const newSerialNumber = numericPart + 1;

                // Generate the new serial number with zero-padding
                const paddedNumber = newSerialNumber.toString().padStart(6, "0");

                // Combine year, month, and the new incremented serial number
                serialNo.value = `${year}${month}${paddedNumber}`;

                console.log('Generated Serial Number:', serialNo.value);
                //alert(`Generated Serial Number: ${//serialNo.value}`);
            } else {
                // Step 4: If no serial number exists, generate the first one
                const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
                const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)

                // Start with 1 if there's no serial number in the database
                const firstSerialNumber = '000001';

                // Generate the first serial number
                serialNo.value = `${year}${month}${firstSerialNumber}`;

                console.log('Generated First Serial Number:', serialNo.value);
                alert(`Generated First Serial Number: ${serialNo.value}`);
            }
        } catch (error) {
            console.error('Error generating serial number:', error);
        }
    };


    //Serial Generation end

    //table main layer header dynamic
    const tableLayerColumnHeaders = ref([
        {name: 'Date', colspan: 1},
        {name: 'Serial\u00A0No', colspan: 1},
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
        //table main layer header dynamic end

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
    //2nd table cell data dynamic end

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

    //Variables for ave max min ng counter end

    // Reject Remarks variables

    /*rejectOKNG.value = "No data available";
    rejectInstruction.value = "No data available";
    rejectiHcRemarks.value = "No data available";
    rejectLotRemarks.value = "No data available";*/

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

    // Reject remarks variables end

    const sampleWithVariances = ref([]);
    const getHighestSampleVariance = ref(null);

    const fileLists = ref([]);
    const fileData = ref([]); // This will hold the selected file data
    const xAxis = ref([]);
    const yAxis = ref([]);
    const xJsonOutput = ref(null);
    const yJsonOutput = ref(null);
    const rowCell = ref([]);

    // Store the file list when the input changes
    const storeFileList = (event) => {
        fileData.value = Array.from(event.target.files);
        fileLists.value = fileData.value.map(file => file.name); // Extract and store file names
        console.log('Files stored:', fileData.value);
    };

    const saveToDatabase = () => {
        if (fileData.value.length === 0) {
            console.error("No file selected! fileData is empty.");
            return; // Exit the function if fileData is empty
        }
        layerTableRowLoading.value = true;
        fileData.value.forEach((file) => {

            const reader = new FileReader();

            reader.onload = () => {
                const content = reader.result; // Read file content
                const parsedData = parseFileContent(content); // Parse content

                console.log('Parsed Data:', parsedData);

                // Map specific keys to extract relevant values
                const dataKeysValue = [
                    2, 4, 6, 8, 10, 12, 14, 16, 18, 20,
                    22, 24, 27, 30, 33, 36, 39, 42, 45,
                    48, 51, 54, 57, 60, 63, 66, 68, 71,
                    74, 76, 78, 81, 83, 86, 88, 91, 93,
                    96
                ];

                const newRowValues = dataKeysValue
                    .map((i) => parsedData[`data${i}`])
                    .filter(Boolean);

                //-------------------------------------GRAPH
                const xValues = [];
                const yValues = [];
                for (const key in parsedData) {
                    if (
                        parsedData.hasOwnProperty(key) &&
                        key.startsWith('data') &&
                        parseInt(key.replace('data', ''), 10) >= 1700 &&
                        parseInt(key.replace('data', ''), 10) <= 2050
                    ) {
                        const [x, y] = parsedData[key].split(',').map(Number);
                        xValues.push(x);
                        yValues.push(y);
                    }
                }

                xAxis.value = xValues;
                yAxis.value = yValues;

                xJsonOutput.value = JSON.stringify(xValues, null, 2);
                yJsonOutput.value = JSON.stringify(yValues, null, 2);

                //console.log('X Axis JSON:', xJsonOutput.value);
                //console.log('Y Axis JSON:', yJsonOutput.value);

                //-------------------------------------GRAPH END

                rowCell.value = newRowValues;

                const layerData = {
                    "date": rowCell.value[0],
                    "serial_no": serialNo.value,
                    "code_no": rowCell.value[1],
                    "order_no": rowCell.value[2],
                    "type": rowCell.value[3],
                    "press_1": rowCell.value[4],
                    "press_2": rowCell.value[5],
                    "machine_no": rowCell.value[6],
                    "sintering_furnace_no": rowCell.value[7],
                    "furnace_no": rowCell.value[8],
                    "zone": rowCell.value[9],
                    "pass_no": rowCell.value[10],
                    "Br": Math.round(rowCell.value[11]),
                    "4paiId": Math.round(rowCell.value[12]),
                    "iHc": Math.round(rowCell.value[13]),
                    "bHc": Math.round(rowCell.value[14]),
                    "BHMax": parseFloat(parseFloat(rowCell.value[15]).toFixed(2)) || 0,
                    "Squareness": parseFloat(parseFloat(rowCell.value[16]).toFixed(3)) || 0,
                    "4paiIs": Math.round(rowCell.value[17]),
                    "iHk": Math.round(rowCell.value[18]),
                    "4paiIa": Math.round(rowCell.value[19]),
                    "Density": rowCell.value[20],
                    "iHkiHc": Math.round(rowCell.value[21]),
                    "Br4pai": Math.round(rowCell.value[22]),
                    "iHr95": Math.round(rowCell.value[23]),
                    "iHr98": Math.round(rowCell.value[24]),
                    "Tracer": rowCell.value[25],
                    "HRX": rowCell.value[26],
                    "MRX": rowCell.value[27],
                    "HRY": rowCell.value[28],
                    "MRY": rowCell.value[29],
                    "IHKA": rowCell.value[30],
                    "MRA": rowCell.value[31],
                    "IHKB": rowCell.value[32],
                    "MRB": rowCell.value[33],
                    "IHKC": rowCell.value[34],
                    "MRC": rowCell.value[35],
                    "HR": rowCell.value[36],
                    "HRO": rowCell.value[37],
                    "x": xJsonOutput.value,
                    "y": yJsonOutput.value,
                    "Br_remarks": parsedData.data25,
                    "4paiId_remarks": parsedData.data28,
                    "iHc_remarks": parsedData.data31,
                    "bHc_remarks": parsedData.data34,
                    "BHMax_remarks": parsedData.data37,
                    "Squareness_remarks": parsedData.data40,
                    "4paiIs_remarks": parsedData.data43,
                    "iHk_remarks": parsedData.data46,
                    "4paiIa_remarks": parsedData.data49,
                    "Density_remarks": parsedData.data52,
                    "iHkiHc_remarks": parsedData.data55,
                    "Br4pai_remarks": parsedData.data58,
                    "iHr95_remarks": parsedData.data61,
                    "iHr98_remarks": parsedData.data64,
                };
                //console.log("Layer Data:", layerData);

                sendLayerData(layerData); // Send the parsed data to the server
            };

            reader.onerror = () => {
                console.error('Error reading file:', file.name);
            };

            reader.readAsText(file); // Read the file as text
        });
    };

    // Function to parse the file content
    const parseFileContent = (content) => {
    const lines = content.split('\n'); // Split the content by new lines
        let variables = {};

        // Loop through each line and assign them to variables
        for (let i = 0; i < lines.length; i++) {
            // Trim the line to remove leading/trailing spaces and remove colons
            const line = lines[i].trim().replace(/:/g, '').trim(); // Remove colons and extra spaces

            if (line) {
                // Dynamically create variable names like data1, data2, etc.
                variables[`data${i + 1}`] = line;
            }
        }
        return variables;
    };


    // Method to clear the file upload
    const clearFileUpload = () => {
    fileData.value = null; // Reset the file data
    fileLists.value = null;
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) fileInput.value = ''; // Clear the input field
    console.log('File upload cleared');
    };

    // Function to send raw data via API
    const sendLayerData = async (layerData) => {
        try {
            const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
            console.log('API Response sendlayerdata:', response.data);
        } catch (error) {
            console.error('Error sending data to API:', error.response?.data || error.message);
        } finally {
            layerTableRowLoading.value = false;
            showProceed.value = true;
            showUploadData.value = false;
        }
    };

    // Function to send raw data via PUT API
    const saveAggregates = async (saveData) => {
        try {
            const response = await axios.put('/api/tpmdata', saveData); // Replace '/api/endpoint' with your API endpoint
            console.log('API Response:', response.data);
        } catch (error) {
            console.error('Error sending data to API:', error.response?.data || error.message);
        }
    };

    //const selectedFileID = ref(null); // Store the latest item
    // Reactive variables
    const layerTableRowLoading = ref(false);
    const items = ref([]); // Holds the fetched data
    const tpmData = ref([]); // Holds the tpmData array
    const tpmRemarks = ref([]); // Holds the remarks array
    const tpmAggregateAve = ref([]); // Holds the aggregateFunctions array
    const tpmAggregateMax = ref([]); // Holds the aggregateFunctions array
    const tpmAggregateMin = ref([]); // Holds the aggregateFunctions array
    const combinedData = ref([]); // Holds the combined array

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

    // Variables for aggregate end

    // Function to fetch data from the API
    const showAllData = async () => {
        layerTableRowLoading.value = true;
        try {
            showProceed.value = false;
            showGraphAndTables.value = true;
            const response = await axios.get("/api/tpmdata?serial=" + serialNo.value);
            console.log('API Response showallData:', response.data);
            console.log('Serial No value = ', serialNo.value);
            items.value = response.data;

            // Extract arrays from the response
            tpmData.value = response.data.data || []; // Fallback to an empty array if undefined
            tpmRemarks.value = response.data.remark || [];

            // Combine the arrays
            combinedData.value = tpmData.value;
            console.log('tpmData: ', tpmData.value);
            console.log('tpmRemarks: ', tpmRemarks.value);
            console.log('Combined Data: ', combinedData.value);

            // Extract individual values from tpmData for aggregate
            getAllBrValues.value = combinedData.value.map(item => item.Br || null);
            getAllBrRemarks.value = combinedData.value.map(item => item.remark.Br_remarks || null);
            getAlliHcValues.value = combinedData.value.map(item => item.iHc || null);
            getAlliHcRemarks.value = combinedData.value.map(item => item.remark.iHc_remarks || null);
            getAlliHkValues.value = combinedData.value.map(item => item.iHk || null);
            getAlliHkRemarks.value = combinedData.value.map(item => item.remark.iHk_remarks || null);
            getAllBHMaxValues.value = combinedData.value.map(item => item.BHMax || null);
            getAllBHMaxRemarks.value = combinedData.value.map(item => item.remark.BHMax_remarks || null);
            getAlliHr95Values.value = combinedData.value.map(item => item.iHr95 || null);
            getAlliHr95Remarks.value = combinedData.value.map(item => item.remark.iHr95_remarks || null);
            getAlliHr98Values.value = combinedData.value.map(item => item.iHr98 || null);
            getAlliHr98Remarks.value = combinedData.value.map(item => item.remark.iHr98_remarks || null);
            getAlliHciHkValues.value = combinedData.value.map(item => item.iHkiHc || null);
            getAlliHciHkRemarks.value = combinedData.value.map(item => item.remark.iHkiHc_remarks || null);
            getAllBr4paiValues.value = combinedData.value.map(item => item.Br4pai || null);
            getAllBr4paiRemarks.value = combinedData.value.map(item => item.remark.Br4pai_remarks || null);
            getAllbHcValues.value = combinedData.value.map(item => item.bHc || null);
            getAllbHcRemarks.value = combinedData.value.map(item => item.remark.bHc_remarks || null);
            getAllSquarenessValues.value = combinedData.value.map(item => item.Squareness || null);
            getAllSquarenessRemarks.value = combinedData.value.map(item => item.remark.Squareness_remarks || null);
            getAll4paildValues.value = combinedData.value.map(item => item["4paiId"] || null);
            getAll4paildRemarks.value = combinedData.value.map(item => item.remark["4paiId_remarks"] || null);
            getAll4pailsValues.value = combinedData.value.map(item => item["4paiIs"] || null);
            getAll4pailsRemarks.value = combinedData.value.map(item => item.remark["4paiIs_remarks"] || null);
            getAll4pailaValues.value = combinedData.value.map(item => item["4paiIa"] || null);
            getAll4pailaRemarks.value = combinedData.value.map(item => item.remark["4paiIa_remarks"] || null);

            //console.log('tpmData: ', tpmData.value);
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
            //console.log('Sample with Variance:', sampleWithVariances.value);

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
                    rejectOKNG.value.push("Hr95 " + hold + "  ");
                    addRejectLotRemarks(lotHold);
                    rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
                }
                if(ngiHr98.value > 0) {
                    rejectOKNG.value.push("Hr98 " + hold + "  ");
                    addRejectLotRemarks(lotHold);
                    rejectLotRemarks.value = rejectLotRemarks.value.filter(rejectLot => rejectLot !== lotPass + "  ");
                }
                if(ngiHciHk.value > 0) {
                    rejectOKNG.value.push("HciHk " + hold + "  ");
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

            //ng conditions for NG remarks end...


            //console.log("Average Values:", aggAveValues.value.map(refObj => refObj.value));
            //console.log("ng iHr95 test: ", calculateSum(getAlliHr95Remarks.value));

        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            layerTableRowLoading.value = false;
            showProceed.value = false;
            layerTableRowLoading.value = false;
        }

        fetchDataCreateGraph();
    };

// State for storing fetched data and error
const error = ref(null);
const datasets = ref([]); // Array to hold multiple datasets

const fetchDataCreateGraph = async () => {
    try {
        const response = await axios.get("/api/tpmdata?serial=" + serialNo.value);

        // Log the response structure to check
        console.log("API Response:", response.data);

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

        // Render the chart with updated data
        renderChart();
    } catch (err) {
        error.value = err;
        console.error("Error fetching data:", err);
    }
};

// Generate a unique color for each dataset
const generateColor = (index) => {
    const colors = ["green", "blue", "red", "orange", "purple", "cyan", "magenta", "yellow", "teal", "pink", "lime"];
    return colors[index % colors.length]; // Cycle through predefined colors
};

const renderChart = () => {
    const ctx = document.getElementById("myChart").getContext("2d");

    const x_offset = 4000; // The amount to offset subsequent datasets
    const y_offset = 6000; // The amount to offset subsequent datasets

    const chartDatasets = datasets.value.map((dataset, index) => {
        return {
            label: `Dataset ${index + 1}`,
            data: dataset.xAxis.map((x, i) => ({
                x: x + index * x_offset, // Apply offset dynamically
                y: (dataset.yAxis[i] || 0) - index * y_offset, // Apply offset dynamically
            })),
            borderColor: dataset.color,
            borderWidth: 2,
            fill: false,
            pointBackgroundColor: dataset.color,
            pointBorderColor: dataset.color,
        };
    });

    new Chart(ctx, {
        type: "line",
        data: {
            datasets: chartDatasets,
        },
        options: {
            responsive: true,
            animation: {
                duration: 1000,
                easing: "easeOutQuart",
            },
            plugins: {
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
                        text: "X-Axis",
                        color: "#333",
                    },
                    ticks: {
                        display: false, // Hides the values
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
                        text: "Y-Axis",
                        color: "#333",
                    },
                    ticks: {
                        display: false, // Hides the values
                    },
                },
            },
        },
    });
};




  </script>
