<template>
    <Frontend>
        <div class="flex flex-col justify-center items-center px-24 py-[100px] container-fluid bg-gray-100">
            <div v-if="!toggleManageForm" class="flex flex-col items-center justify-center py-4 space-y-4">
                <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" @click="showManageForm">
                    Add New
                </button>
            </div>
            <div v-if="toggleManageForm" class="flex flex-col items-center justify-center">
                <p class="flex flex-col mb-10 font-extrabold">Serial: {{ serialNo }}</p>
                <div class="flex flex-row items-center justify-center">
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
                                @change="handleFileUpload"
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
                                @click="fetchItems"
                            />
                            <!-- Button to Redo Upload -->

                            </div>

                            <!-- Optional Instruction Text -->
                            <p class="mt-2 text-sm text-gray-500">Only .tpm files are allowed</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
                        <p>Files: </p>
                        <div class="p-2 text-white bg-blue-400 rounded-3xl">test file name here</div>
                    </div>
                </div>


                <!-- Chart Container -->
                <div class="w-[1000px] h-[550px] bg-blue-100 rounded-xl">
                    <canvas id="myChart"></canvas>
                </div>
                <div class="p-6 rounded-lg shadow-lg mt-14 bg-gray-50">
                    <label class="block p-2 mb-4 text-2xl font-semibold text-gray-800 rounded-md shadow-xl bg-gradient-to-r from-yellow-400 to-yellow-100 max-w-40">FIRST LAYER</label>
                    <div class="flex flex-col items-center justify-center">
                        <div class="mb-20">
                            <!-- Table with Full Width -->
                            <table class="overflow-hidden border border-gray-300 rounded-lg shadow-2xl table-auto">
                                <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                    <tr>
                                        <th v-for="tableLayerColumnHeader in tableLayerColumnHeaders" :key="tableLayerColumnHeader.name" :colspan="tableLayerColumnHeader.colspan" class="px-3 py-2 text-sm font-medium text-center border border-white">{{ tableLayerColumnHeader.name }}</th>
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
                                            {{ item.serialNo }}
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
                                            {{ item.lotNo }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                            {{ item.order_no }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">
                                            {{ item.order_no }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-16">
                            <table class="overflow-hidden border border-gray-300 rounded-lg shadow-2xl table-auto">
                                <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                    <tr>
                                        <th v-for="secondTableLayerColumnHeader in secondTableLayerColumnHeaders" :key="secondTableLayerColumnHeader.name" class="px-3 py-2 text-sm font-medium text-center">{{ secondTableLayerColumnHeader.name }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-3 py-2 font-extrabold text-center text-white bg-blue-700 text-md">AVERAGE</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-extrabold text-center text-white bg-blue-700 text-md">MAXIMUM</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-extrabold text-center text-white bg-blue-700 text-md">MINIMUM</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-extrabold text-center text-white bg-red-800 text-md">NG COUNTER</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-10 mb-10 bg-yellow-100 shadow-lg rounded-xl">
                            <label class="text-lg font-bold">Dynamic Notification Div</label><br>
                            <label>OK = </label><span id="okRemarksList">Br, iHc, BHMax</span><br>
                            <span id="rejectRemarks">Reject, N.G BH(Max)</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </Frontend>
  </template>

  <script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { ref, watch, onMounted } from 'vue';
    import { Chart, registerables } from 'chart.js'; // Import all required components
    // Register all Chart.js components using registerables
    Chart.register(...registerables);

    const toggleManageForm = ref(false);
    const showManageForm = () => {
        toggleManageForm.value = !toggleManageForm.value;
        generateSerialNumber();
    }

    const serialNo = ref(null);
    const generateSerialNumber = () => {
        const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
        const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)
        const newNumber = 1; // Static number for demonstration
        const paddedNumber = newNumber.toString().padStart(6, "0"); // Pad to 6 digits

        serialNo.value = `${year}${month}${paddedNumber}`;

        // Display the serial numbers in an alert
        alert(`Successfully created a new serial: ${serialNo.value} (preview sample only)`);
    };

    //table main layer header dynamic
    const tableLayerColumnHeaders = ref([
        {name: 'Date', colspan: 1},
        {name: 'Serial No', colspan: 1},
        {name: 'Code No', colspan: 1},
        {name: 'Order No', colspan: 1},
        {name: 'Type', colspan: 1},
        {name: 'Lot No', colspan: 1},
        {name: 'Furnace No', colspan: 1},
        {name: 'Coating No', colspan: 1},
        {name: 'Zone', colspan: 1},
        {name: 'Pass No', colspan: 1},
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
        {name: 'Sample with Variance', colspan: 1}
    ]);
        //table main layer header dynamic end

        //table main layer cell data dynamic
    const tableLayerColumnCell = ref([
        {name: 'Date', colspan: 1},
        {name: 'Serial No', colspan: 1},
        {name: 'Code No', colspan: 1},
        {name: 'Order No', colspan: 1},
        {name: 'Type', colspan: 1},
        {name: 'Lot No', colspan: 1},
        {name: 'Furnace No', colspan: 1},
        {name: 'Coating No', colspan: 1},
        {name: 'Zone', colspan: 1},
        {name: 'Pass No', colspan: 1},
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
        {name: 'Sample with Variance', colspan: 1}
    ]);
        //table main layer cell data dynamic end

    //2nd table cell data dynamic
    const secondTableLayerColumnHeaders = ref([
        {name: '', colspan: 1},
        {name: 'Br', colspan: 1},
        {name: 'iHc', colspan: 1},
        {name: 'iHk', colspan: 1},
        {name: 'BHMax', colspan: 1},
        {name: 'Hr95', colspan: 1},
        {name: 'Hr98', colspan: 1},
        {name: 'iHciHk', colspan: 1},
        {name: 'Br4pai', colspan: 1},
        {name: 'bHc', colspan: 1},
        {name: 'Squareness', colspan: 1},
        {name: '4paild', colspan: 1},
        {name: '4pails', colspan: 1},
        {name: '4paila', colspan: 1},
    ]);
    //2nd table cell data dynamic end

    const fileData = ref([]); // This will hold the selected file data
    const xAxis = ref([]);
    const yAxis = ref([]);
    const xJsonOutput = ref(""); // JSON for X-axis
    const yJsonOutput = ref(""); // JSON for Y-axis
    const rowCell = ref([]);

    // Method to handle file upload
    /*const handleFileUpload = (event) => {
        const files = event.target.files; // Get the first file selected
        if (files.length) {
            // Store the file in the fileData variable
            fileData.value = Array.from(files);
            console.log('Selected files:', fileData.value);

            const reader = new FileReader();
            reader.onload = () => {
                // The file content is now in reader.result
                const content = reader.result;

                // Parse the content into variables
                const parsedData = parseFileContent(content);
                //debug
                console.log('Parsed Data:', parsedData);

                // Use a loop to add the data dynamically
                const dataKeysValue = [
                    2, 4, 6, 8, 10, 12, 14, 16, 18, 20,
                    22, 24, 27, 30, 33, 36, 39, 42, 45,
                    48, 51, 54, 57, 60, 63, 66, 68, 71,
                    74, 76, 78, 81, 83, 86, 88, 91, 93,
                    96
                ];

                // Now, let's create the rowValues, and add the concatenated value to the right position
                const newRowValues = dataKeysValue
                    .map(i => {
                        return parsedData[`data${i}`]; // Return the rest of the data
                    })
                    .filter(Boolean); // Filter out undefined or falsy values

                    //-------------------------------------GRAPH
                // Process coordinates starting from data100
                const xValues = [];
                const yValues = [];
                for (const key in parsedData) {
                    if (
                        parsedData.hasOwnProperty(key) &&
                        key.startsWith('data') &&
                        parseInt(key.replace('data', ''), 10) >= 1700 && // Greater than or equal to 1700
                        parseInt(key.replace('data', ''), 10) <= 2050    // Less than or equal to 2030
                    ) {
                        const [x, y] = parsedData[key].split(',').map(Number); // Split and parse
                        xValues.push(x);
                        yValues.push(y);
                    }
                }

                // Update reactive variables
                xAxis.value = xValues;
                yAxis.value = yValues;

                // Convert x and y into separate JSON format
                xJsonOutput.value = JSON.stringify(xValues, null, 2);
                yJsonOutput.value = JSON.stringify(yValues, null, 2);

                console.log('X Axis JSON:', xJsonOutput.value);
                console.log('Y Axis JSON:', yJsonOutput.value);

                //-------------------------------------GRAPH END

                rowCell.value = newRowValues;

                const layerData = {
                    "date": rowCell.value[0],
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
                    "Br_remarks": parsedData.data25 == 1 ? 'E' : parsedData.data25 == 0 ? '' : parsedData.data25,
                    "4paiId_remarks": parsedData.data28 == 1 ? 'E' : parsedData.data28 == 0 ? '' : parsedData.data28,
                    "iHc_remarks": parsedData.data31 == 1 ? 'E' : parsedData.data31 == 0 ? '' : parsedData.data31,
                    "bHc_remarks": parsedData.data34 == 1 ? 'E' : parsedData.data34 == 0 ? '' : parsedData.data34,
                    "BHMax_remarks": parsedData.data37 == 1 ? 'E' : parsedData.data37 == 0 ? '' : parsedData.data37,
                    "Squareness_remarks": parsedData.data40 == 1 ? 'E' : parsedData.data40 == 0 ? '' : parsedData.data40,
                    "4paiIs_remarks": parsedData.data43 == 1 ? 'E' : parsedData.data43 == 0 ? '' : parsedData.data43,
                    "iHk_remarks": parsedData.data46 == 1 ? 'E' : parsedData.data46 == 0 ? '' : parsedData.data46,
                    "4paiIa_remarks": parsedData.data49 == 1 ? 'E' : parsedData.data49 == 0 ? '' : parsedData.data49,
                    "Density_remarks": parsedData.data52 == 1 ? 'E' : parsedData.data52 == 0 ? '' : parsedData.data52,
                    "iHkiHc_remarks": parsedData.data55 == 1 ? 'E' : parsedData.data55 == 0 ? '' : parsedData.data55,
                    "Br4pai_remarks": parsedData.data58 == 1 ? 'E' : parsedData.data58 == 0 ? '' : parsedData.data58,
                    "iHr95_remarks": parsedData.data61 == 1 ? 'E' : parsedData.data61 == 0 ? '' : parsedData.data61,
                    "iHr98_remarks": parsedData.data64 == 1 ? 'E' : parsedData.data64 == 0 ? '' : parsedData.data64,
                };

                const lotNo = layerData.press_1 + layerData.press_2 + layerData.machine_no;//concatenate to form lot number
                /* temporary for display
                tableDate.value = layerData.date;
                tableCodeNo.value = layerData.code_no;
                tableOrderNo.value = layerData.order_no;
                tableType.value = layerData.type;
                tableLotNo.value = lotNo;
                tableFurnaceNo.value = layerData.sintering_furnace_no;
                tableCoatingNo.value = layerData.furnace_no;
                tableZone.value = layerData.zone;
                tablePassNo.value = layerData.pass_no;
                tableBr.value = layerData.Br;
                tableBrRemarks.value = layerData.Br_remarks;
                table4paild.value = layerData["4paiId"];
                table4paildRemarks.value = layerData["4paiId_remarks"];
                tableiHc.value = layerData.iHc;
                tableiHcRemarks.value = layerData.iHc_remarks;
                tablebHc.value = layerData.bHc;
                tablebHcRemarks.value = layerData.bHc_remarks;
                tableBHMax.value = layerData.BHMax;
                tableBHMaxRemarks.value = layerData.BHMax_remarks;
                tableSquareness.value = layerData.Squareness;
                tableSquarenessRemarks.value = layerData.Squareness_remarks;
                table4pails.value = layerData["4paiIs"];
                table4pailsRemarks.value = layerData["4paiIs_remarks"];
                tableiHk.value = layerData.iHk;
                tableiHkRemarks.value = layerData.iHk_remarks;
                table4paila.value = layerData["4paiIa"];
                table4pailaRemarks.value = layerData["4paiIa_remarks"];
                tableiHciHk.value = layerData.iHkiHc;
                tableiHciHkRemarks.value = layerData.iHkiHc_remarks;
                tableBr4pai.value = layerData.Br4pai;
                tableBr4paiRemarks.value = layerData.Br4pai_remarks;
                tableHr95.value = layerData.iHr95;
                tableHr95Remarks.value = layerData.iHr95_remarks;
                tableHr98.value = layerData.iHr98;
                tableHr98Remarks.value = layerData.iHr98_remarks;
                tableTracer.value = layerData.Tracer;

                console.log("Layer Data:", layerData);

                sendLayerData(layerData);

            };

            reader.onerror = () => {
                console.error('Error reading file');
            };

            // Read the file as text
            reader.readAsText(file);
        }
    };*/

    const handleFileUpload = (event) => {
        const files = event.target.files; // Get all selected files
        if (files.length) {
            fileData.value = Array.from(files); // Store files in the reactive variable
            console.log('Selected files:', fileData.value);

            Array.from(files).forEach((file) => {

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

                    console.log('X Axis JSON:', xJsonOutput.value);
                    console.log('Y Axis JSON:', yJsonOutput.value);

                    //-------------------------------------GRAPH END

                    rowCell.value = newRowValues;

                    const layerData = {
                        "date": rowCell.value[0],
                        "serial_no": "2021312",
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
                        "Br_remarks": parsedData.data25 == 1 ? 'E' : parsedData.data25 == 0 ? '' : parsedData.data25,
                        "4paiId_remarks": parsedData.data28 == 1 ? 'E' : parsedData.data28 == 0 ? '' : parsedData.data28,
                        "iHc_remarks": parsedData.data31 == 1 ? 'E' : parsedData.data31 == 0 ? '' : parsedData.data31,
                        "bHc_remarks": parsedData.data34 == 1 ? 'E' : parsedData.data34 == 0 ? '' : parsedData.data34,
                        "BHMax_remarks": parsedData.data37 == 1 ? 'E' : parsedData.data37 == 0 ? '' : parsedData.data37,
                        "Squareness_remarks": parsedData.data40 == 1 ? 'E' : parsedData.data40 == 0 ? '' : parsedData.data40,
                        "4paiIs_remarks": parsedData.data43 == 1 ? 'E' : parsedData.data43 == 0 ? '' : parsedData.data43,
                        "iHk_remarks": parsedData.data46 == 1 ? 'E' : parsedData.data46 == 0 ? '' : parsedData.data46,
                        "4paiIa_remarks": parsedData.data49 == 1 ? 'E' : parsedData.data49 == 0 ? '' : parsedData.data49,
                        "Density_remarks": parsedData.data52 == 1 ? 'E' : parsedData.data52 == 0 ? '' : parsedData.data52,
                        "iHkiHc_remarks": parsedData.data55 == 1 ? 'E' : parsedData.data55 == 0 ? '' : parsedData.data55,
                        "Br4pai_remarks": parsedData.data58 == 1 ? 'E' : parsedData.data58 == 0 ? '' : parsedData.data58,
                        "iHr95_remarks": parsedData.data61 == 1 ? 'E' : parsedData.data61 == 0 ? '' : parsedData.data61,
                        "iHr98_remarks": parsedData.data64 == 1 ? 'E' : parsedData.data64 == 0 ? '' : parsedData.data64,
                        "average": {
                            "Br": "value",
                            "bHc": "value",
                            "iHc": "value",
                            "iHk": "test",
                            "Hr95": "test",
                            "Hr98": "test",
                            "BHMax": "value",
                            "4paila": "test",
                            "4paild": "test",
                            "4pails": "test",
                            "Br4pai": "value",
                            "iHciHk": "test",
                            "Squareness": "fuck"
                        },
                        "maximum": {
                            "Br": "value",
                            "bHc": "value",
                            "iHc": "value",
                            "iHk": "test",
                            "Hr95": "test",
                            "Hr98": "test",
                            "BHMax": "value",
                            "4paila": "test",
                            "4paild": "test",
                            "4pails": "test",
                            "Br4pai": "value",
                            "iHciHk": "test",
                            "Squareness": "fuck"
                        },
                        "minimum": {
                            "Br": "value",
                            "bHc": "value",
                            "iHc": "value",
                            "iHk": "test",
                            "Hr95": "test",
                            "Hr98": "test",
                            "BHMax": "value",
                            "4paila": "test",
                            "4paild": "test",
                            "4pails": "test",
                            "Br4pai": "value",
                            "iHciHk": "test",
                            "Squareness": "fuck"
                        },
                        "ng_counter": {
                            "Br": "value",
                            "bHc": "value",
                            "iHc": "value",
                            "iHk": "test",
                            "Hr95": "test",
                            "Hr98": "test",
                            "BHMax": "value",
                            "4paila": "test",
                            "4paild": "test",
                            "4pails": "test",
                            "Br4pai": "value",
                            "iHciHk": "test",
                            "Squareness": "fuck"
                        }

                    };
                    console.log("Layer Data:", layerData);

                    sendLayerData(layerData); // Send the parsed data to the server
                };

                reader.onerror = () => {
                    console.error('Error reading file:', file.name);
                };

                reader.readAsText(file); // Read the file as text
            });
        }
    };


    // Method to clear the file upload
    const clearFileUpload = () => {
    fileData.value = null; // Reset the file data
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) fileInput.value = ''; // Clear the input field
    console.log('File upload cleared');
    };

    // Function to send raw data via API
    const sendLayerData = async (layerData) => {
        try {
            const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
            console.log('API Response:', response.data);
        } catch (error) {
            console.error('Error sending data to API:', error.response?.data || error.message);
        }
    };

    //const selectedFileID = ref(null); // Store the latest item
    // Reactive variables
    const items = ref([]); // Holds the fetched data
    const tpmData = ref([]); // Holds the tpmData array
    const tpmRemarks = ref([]); // Holds the remarks array
    const tpmAggregate = ref([]); // Holds the aggregateFunctions array
    const combinedData = ref([]); // Holds the combined array

    // Function to fetch data from the API
    const fetchItems = async () => {
        try {
            const response = await axios.get('/api/tpmdata');
            console.log('API Response:', response.data);
            items.value = response.data;

            // Extract arrays from the response
            tpmData.value = response.data.data.tpmData || []; // Fallback to an empty array if undefined
            tpmRemarks.value = response.data.data.remarks || [];
            tpmAggregate.value = response.data.data.aggregateFunctions || [];

            console.log('tpmData: ', tpmData.value);
            console.log('tpmRemarks: ', tpmRemarks.value);
            console.log('tpmAggregate: ', tpmAggregate.value);

            // Combine the arrays
            combinedData.value = tpmData.value.map((item, index) => ({
                ...item,
                remarks: tpmRemarks.value[index] || null,
                aggregate: tpmAggregate.value[index] || null,
            }));

            console.log('Combined Data: ', combinedData.value);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };

// Call fetchItems to fetch and process data when needed
//fetchItems();

    // State for storing fetched data and error
    const error = ref(null);
    const graph_xAxis = ref(null);
    const graph_yAxis = ref(null);

    const fetchDataFromApi = async () => {
    try {
        // Wait for fetchItems to complete
        await fetchItems();

        // Check if selectedFileID is valid before making the API call
        if (selectedFileID.value) {
        const response = await axios.get(`/api/tpmdata/${selectedFileID.value}`);

        // Parse the "longtext" JSON string into actual arrays
        graph_xAxis.value = JSON.parse(response.data.data.x || "[]");
        graph_yAxis.value = JSON.parse(response.data.data.y || "[]");

        // Render the chart with updated data
        renderChart();
        } else {
        console.error("Error: No valid file ID found for fetching data.");
        }
    } catch (err) {
        error.value = err;
        console.error("Error fetching data:", err);
    }
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

    // Method to submit the file (you can add additional logic to send the file to a server)
    /*const submitFile = () => {
    if (fileData.value) {
        // Here, you can process the fileData, send it to a server, or read its content
        console.log('File ready for submission:', fileData.value);
    } else {
        alert('Please select a file first.');
    }
    };*/

    const renderChart = () => {
    const ctx = document.getElementById("myChart").getContext("2d");

    const chartData = [];
    for (let i = 0; i < graph_xAxis.value.length; i++) {
    chartData.push({
        x: graph_xAxis.value[i],
        y: graph_yAxis.value[i] || 0, // Default to 0 if no corresponding y-value
    });
    }

    console.log("CHART DATA: ", chartData)

    new Chart(ctx, {
        type: "line",
        data: {
        datasets: [
            {
            label: "Dynamic Dataset",
            data: chartData,
            borderColor: "green",
            borderWidth: 2,
            fill: false,
            pointBackgroundColor: "green",
            pointBorderColor: "green",
            },
        ],
        },
        options: {
        responsive: true,
        scales: {
            x: {
            type: "linear",
            position: "bottom",
            title: {
                display: true,
                text: "X-Axis",
            },
            },
            y: {
            type: "linear",
            position: "left",
            title: {
                display: true,
                text: "Y-Axis",
            },
            },
        },
        },
    });
    };

  </script>
