<template>
    <Frontend>
        <div class="flex flex-col justify-center items-center px-24 py-[100px] container-fluid bg-gray-100">
            <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
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
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                @change="handleFileUpload"
            />

            <!-- Submit Button -->
            <input
                type="submit"
                id="submitRawdata"
                class="px-4 py-2 font-semibold text-white transition duration-200 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                @click="fetchDataFromApi"
            />

            <!-- Optional Instruction Text -->
            <p class="mt-2 text-sm text-gray-500">Only .tpm files are allowed</p>
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
                                <tr class="border-b hover:bg-gray-50">
                                    <td v-for="tableLayerDataCell in tableLayerDataCells" :key="tableLayerDataCell" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">{{ tableLayerDataCell }}</td>
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
    </Frontend>
  </template>

  <script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { ref, watch } from 'vue';
    import { Chart, registerables } from 'chart.js'; // Import all required components

    //table dynamic variables
    const tableDate = ref('');
    const tableCodeNo = ref('');
    const tableOrderNo = ref('');
    const tableType = ref('');
    const tableLotNo = ref('');
    const tableFurnaceNo = ref('');
    const tableCoatingNo = ref('');
    const tableZone = ref('');
    const tablePassNo = ref('');
    const tableBr = ref('');
    const tableBrRemarks = ref('');
    const tableiHc = ref('');
    const tableiHcRemarks = ref('');
    const tableiHk = ref('');
    const tableiHkRemarks = ref('');
    const tableBHMax = ref('');
    const tableBHMaxRemarks = ref('');
    const tableHr95 = ref('');
    const tableHr95Remarks = ref('');
    const tableHr98 = ref('');
    const tableHr98Remarks = ref('');
    const tableiHciHk = ref('');
    const tableiHciHkRemarks = ref('');
    const tableBr4pai = ref('');
    const tableBr4paiRemarks = ref('');
    const tablebHc = ref('');
    const tablebHcRemarks = ref('');
    const tableSquareness = ref('');
    const tableSquarenessRemarks = ref('');
    const table4paild = ref('');
    const table4paildRemarks = ref('');
    const table4pails = ref('');
    const table4pailsRemarks = ref('');
    const table4paila = ref('');
    const table4pailaRemarks = ref('');
    const tableTracer = ref('');
    const tableSampleWithVariance = ref('');
    //table dynamic variables end...

    //table main layer header dynamic
    const tableLayerColumnHeaders = ref([
        {name: 'Date', colspan: 1},
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

    //table cell data dynamic
    const tableLayerDataCells = ref([
        tableDate,tableCodeNo,tableOrderNo,tableType,tableLotNo,tableFurnaceNo,tableCoatingNo,tableZone,tablePassNo,tableBr,
        tableBrRemarks,tableiHc,tableiHcRemarks,tableiHk,tableiHkRemarks,tableBHMax,tableBHMaxRemarks,tableHr95,tableHr95Remarks,
        tableHr98,tableHr98Remarks,tableiHciHk,tableiHciHkRemarks,tableBr4pai,tableBr4paiRemarks,tablebHc,tablebHcRemarks,tableSquareness,
        tableSquarenessRemarks,table4paild,table4paildRemarks,table4pails,table4pailsRemarks,table4paila,table4pailaRemarks,tableTracer,
        tableSampleWithVariance
    ]);
    //table cell data dynamic end

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

    const fileData = ref(null); // This will hold the selected file data
    const xAxis = ref([]);
    const yAxis = ref([]);
    const xJsonOutput = ref(""); // JSON for X-axis
    const yJsonOutput = ref(""); // JSON for Y-axis
    const rowCell = ref([]);

    // Method to handle file upload
    const handleFileUpload = (event) => {
        const file = event.target.files[0]; // Get the first file selected
        if (file) {
            // Store the file in the fileData variable
            fileData.value = file;
            console.log('Selected file:', file);

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
                        parseInt(key.replace('data', ''), 10) >= 100
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
                /* temporary for display */
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
    };

    // Function to send raw data via API
    const sendLayerData = async (layerData) => {
        try {
            const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
            console.log('API Response:', response.data);
        } catch (error) {
            console.error('Error sending data to API:', error);
        }
    };

    // State for storing fetched data and error
    const tpmData = ref(null);
    const error = ref(null);

    const fetchDataFromApi = async () => {
    try {
        const response = await axios.get('/api/tpmdata/43');
        const createdAt = response.data.data.x; // Extract the created_at column
        console.log('Created At:', createdAt);
    } catch (error) {
        console.error('Error fetching data:', error);
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




  </script>
