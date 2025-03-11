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
                @click="submitFile"
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
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Date</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Code&nbsp;No</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Order&nbsp;No</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Type</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Lot&nbsp;No</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Furnace&nbsp;No</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Coating&nbsp;No</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Zone</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Pass&nbsp;No</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">Br</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">iHc</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">iHk</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">BHMax</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">Hr95</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">Hr98</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">iHciHk</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">Br4pai</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">bHc</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">Squareness</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">4paild</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">4pails</th>
                                    <th colspan="2" class="px-3 py-2 text-sm font-medium text-center border border-white">4paila</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Tracer</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center border border-white">Sample with&nbsp;Variance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="border-b hover:bg-gray-50">
                                    <td id="tableDate" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableCodeNo" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableOrderNo" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableType" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableLotNo" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableFurnaceNo" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableCoatingNo" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableZone" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tablePassNo" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableBr" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableBrRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableiHc" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableiHcRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableiHk" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableiHkRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableBHMax" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableBHMaxRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableHr95" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableHr95Remarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableHr98" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableHr98Remarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableiHciHk" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableiHciHkRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableBr4pai" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableBr4paiRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tablebHc" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tablebHcRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableSquareness" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableSquarenessRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="table4paild" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="table4paildRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="table4pails" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="table4pailsRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="table4paila" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="table4pailaRemarks" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableTracer" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                    <td id="tableSampleWithVariance" class="px-3 py-2 text-sm text-center text-gray-700 border border-blue-500">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-16">
                        <table class="overflow-hidden border border-gray-300 rounded-lg shadow-2xl table-auto">
                            <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                <tr>
                                    <th class="px-3 py-2 text-sm font-medium text-center"></th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">Br</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">iHc</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">iHk</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">BHMax</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">Hr95</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">Hr98</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">iHciHk</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">Br4pai</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">bHc</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">Squareness</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">4paild</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">4pails</th>
                                    <th class="px-3 py-2 text-sm font-medium text-center">4paila</th>
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
    import { ref, onMounted } from 'vue';
    import { Chart, registerables } from 'chart.js'; // Import all required components

    const fileData = ref(null); // This will hold the selected file data
    const rowCell = ref([]);  // Declare the rowHeader array
    const graphAxis = ref([]);
    const graphAxisJSON = ref(''); // JSON representation of graphAxis

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

                console.log('Parsed Data:', parsedData);

                // Use a loop to add the data dynamically
                const dataKeysValue = [
                    2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 25, 30, 31, 45, 46, 36, 37, 60, 61, 63, 64,
                    54, 55, 57, 58, 33, 34, 39, 40, 27, 28, 42, 43, 48, 49, 66
                ];

                const graphCoordinates = Array.from({ length: 3543 - 100 + 1 }, (_, i) => i + 100);

                // Update graphAxis with the new values
                graphAxis.value = graphCoordinates
                    .map((i) => parsedData[`data${i}`]) // Extract corresponding parsed data
                    .filter(Boolean); // Remove undefined or falsy values
                // Convert graphAxis to JSON
                convertToJSON();
                //console.log('Graph Axis:', graphAxis); //GET GRAPH AXIS VALUE



                // Concatenate data10, data12, and data14 (this is lot number)
                const concatenatedValue = [
                    parsedData[`data10`],
                    parsedData[`data12`],
                    parsedData[`data14`]
                ].filter(Boolean).join(' '); // Concatenate and remove any falsy values (e.g., undefined)

                // Now, let's create the rowValues, and add the concatenated value to the right position
                const newRowValues = dataKeysValue
                    .map(i => {
                        if (i === 10 || i === 12 || i === 14) {
                            return null; // Skip these keys, we'll add the concatenated value instead
                        }
                        return parsedData[`data${i}`]; // Return the rest of the data
                    })
                    .filter(Boolean); // Filter out undefined or falsy values

                // Add the concatenated value in place of data10, data12, and data14 (this is lot number)
                newRowValues.splice(4, 0, concatenatedValue); // Insert concatenated value at the appropriate index (index 4 corresponds to data10)

                // Update rowCell with the new values
                rowCell.value = newRowValues;

                // Get All THIS variables
                const layerData = {
                    layerDate: rowCell.value[0],
                    layerCodeNo: rowCell.value[1],
                    layerOrderNo: rowCell.value[2],
                    layerType: rowCell.value[3],
                    layerLotNo: rowCell.value[4],
                    layerFurnaceNo: rowCell.value[5],
                    layerCoatingNo: rowCell.value[6],
                    layerZone: rowCell.value[7],
                    layerPassNo: rowCell.value[8],
                    layerBr: Math.round(rowCell.value[9]),
                    layerBrRemarks: rowCell.value[10] == 1 ? 'E' : rowCell.value[10] == 0 ? '' : rowCell.value[10],
                    layeriHc: Math.round(rowCell.value[11]),
                    layeriHcRemarks: rowCell.value[12] == 1 ? 'E' : rowCell.value[12] == 0 ? '' : rowCell.value[12],
                    layeriHk: Math.round(rowCell.value[13]),
                    layeriHkRemarks: rowCell.value[14] == 1 ? 'E' : rowCell.value[14] == 0 ? '' : rowCell.value[14],
                    layerBHMax: parseFloat(parseFloat(rowCell.value[15]).toFixed(2)) || 0,
                    layerBHMaxRemarks: rowCell.value[16] == 1 ? 'E' : rowCell.value[16] == 0 ? '' : rowCell.value[16],
                    layerHr95: Math.round(rowCell.value[17]),
                    layerHr95Remarks: rowCell.value[18] == 1 ? 'E' : rowCell.value[18] == 0 ? '' : rowCell.value[18],
                    layerHr98: Math.round(rowCell.value[19]),
                    layerHr98Remarks: rowCell.value[20] == 1 ? 'E' : rowCell.value[20] == 0 ? '' : rowCell.value[20],
                    layeriHciHk: Math.round(rowCell.value[21]),
                    layeriHciHkRemarks: rowCell.value[22] == 1 ? 'E' : rowCell.value[22] == 0 ? '' : rowCell.value[22],
                    layerBr4pai: Math.round(rowCell.value[23]),
                    layerBr4paiRemarks: rowCell.value[24] == 1 ? 'E' : rowCell.value[24] == 0 ? '' : rowCell.value[24],
                    layerbHc: Math.round(rowCell.value[25]),
                    layerbHcRemarks: rowCell.value[26] == 1 ? 'E' : rowCell.value[26] == 0 ? '' : rowCell.value[26],
                    layerSquareness: parseFloat(parseFloat(rowCell.value[27]).toFixed(3)) || 0,
                    layerSquarenessRemarks: rowCell.value[28] == 1 ? 'E' : rowCell.value[28] == 0 ? '' : rowCell.value[28],
                    layer4paild: Math.round(rowCell.value[29]),
                    layer4paildRemarks: rowCell.value[30] == 1 ? 'E' : rowCell.value[30] == 0 ? '' : rowCell.value[30],
                    layer4pails: Math.round(rowCell.value[31]),
                    layer4pailsRemarks: rowCell.value[32] == 1 ? 'E' : rowCell.value[32] == 0 ? '' : rowCell.value[32],
                    layer4paila: Math.round(rowCell.value[33]),
                    layer4pailaRemarks: rowCell.value[34] == 1 ? 'E' : rowCell.value[34] == 0 ? '' : rowCell.value[34],
                    layerTracer: rowCell.value[35],
                    graphAxis: graphAxis.value, // Include graphAxis JSON data here
                };

                /* temporary for display */
                document.getElementById("tableDate").textContent = layerData.layerDate;
                document.getElementById("tableCodeNo").textContent = layerData.layerCodeNo;
                document.getElementById("tableOrderNo").textContent = layerData.layerOrderNo;
                document.getElementById("tableType").textContent = layerData.layerType;
                document.getElementById("tableLotNo").textContent = layerData.layerLotNo;
                document.getElementById("tableFurnaceNo").textContent = layerData.layerFurnaceNo;
                document.getElementById("tableCoatingNo").textContent = layerData.layerCoatingNo;
                document.getElementById("tableZone").textContent = layerData.layerZone;
                document.getElementById("tablePassNo").textContent = layerData.layerPassNo;
                document.getElementById("tableBr").textContent = layerData.layerBr;
                document.getElementById("tableBrRemarks").textContent = layerData.layerBrRemarks;
                document.getElementById("tableiHc").textContent = layerData.layeriHc;
                document.getElementById("tableiHcRemarks").textContent = layerData.layeriHcRemarks;
                document.getElementById("tableiHk").textContent = layerData.layeriHk;
                document.getElementById("tableiHkRemarks").textContent = layerData.layeriHkRemarks;
                document.getElementById("tableBHMax").textContent = layerData.layerBHMax;
                document.getElementById("tableBHMaxRemarks").textContent = layerData.layerBHMaxRemarks;
                document.getElementById("tableHr95").textContent = layerData.layerHr95;
                document.getElementById("tableHr95Remarks").textContent = layerData.layerHr95Remarks;
                document.getElementById("tableHr98").textContent = layerData.layerHr98;
                document.getElementById("tableHr98Remarks").textContent = layerData.layerHr98Remarks;
                document.getElementById("tableiHciHk").textContent = layerData.layeriHciHk;
                document.getElementById("tableiHciHkRemarks").textContent = layerData.layeriHciHkRemarks;
                document.getElementById("tableBr4pai").textContent = layerData.layerBr4pai;
                document.getElementById("tableBr4paiRemarks").textContent = layerData.layerBr4paiRemarks;
                document.getElementById("tablebHc").textContent = layerData.layerbHc;
                document.getElementById("tablebHcRemarks").textContent = layerData.layerbHcRemarks;
                document.getElementById("tableSquareness").textContent = layerData.layerSquareness;
                document.getElementById("tableSquarenessRemarks").textContent = layerData.layerSquarenessRemarks;
                document.getElementById("table4paild").textContent = layerData.layer4paild;
                document.getElementById("table4paildRemarks").textContent = layerData.layer4paildRemarks;
                document.getElementById("table4pails").textContent = layerData.layer4pails;
                document.getElementById("table4pailsRemarks").textContent = layerData.layer4pailsRemarks;
                document.getElementById("table4paila").textContent = layerData.layer4paila;
                document.getElementById("table4pailaRemarks").textContent = layerData.layer4pailaRemarks;
                document.getElementById("tableTracer").textContent = layerData.layerTracer;

            console.log("Layer Data:", layerData);

            // Send data to API
            sendLayerData(layerData);
                //Get All THIS variables
                /*var layerDate = rowCell.value[0];
                var layerCodeNo = rowCell.value[1];
                var layerOrderNo = rowCell.value[2];
                var layerType = rowCell.value[3];
                var layerLotNo = rowCell.value[4];
                var layerFurnaceNo = rowCell.value[5];
                var layerCoatingNo = rowCell.value[6];
                var layerZone = rowCell.value[7];
                var layerPassNo = rowCell.value[8];
                var layerBr = rowCell.value[9];
                var layeriHc = rowCell.value[10];
                var layeriHk = rowCell.value[11];
                var layerBHMax = rowCell.value[12];
                var layerHr95 = rowCell.value[13];
                var layerHr98 = rowCell.value[14];
                var layeriHciHk = rowCell.value[15];
                var layerBr4pai = rowCell.value[16];
                var layerbHc = rowCell.value[17];
                var layerSquareness = rowCell.value[18];
                var layer4paild = rowCell.value[19];
                var layer4pails = rowCell.value[20];
                var layer4paila = rowCell.value[21];
                var layerTracer = rowCell.value[22];*/

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
            const response = await axios.post('/api/endpoint', layerData); // Replace '/api/endpoint' with your API endpoint
            console.log('API Response:', response.data);
        } catch (error) {
            console.error('Error sending data to API:', error);
        }
    };

    // Function to convert graphAxis to JSON
    const convertToJSON = () => {
        if (graphAxis.value.length > 0) {
            graphAxisJSON.value = JSON.stringify(graphAxis.value);
            console.log('Graph Axis as JSON:', graphAxisJSON.value);
        } else {
            console.error('Graph Axis is empty!');
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
    const submitFile = () => {
    if (fileData.value) {
        // Here, you can process the fileData, send it to a server, or read its content
        console.log('File ready for submission:', fileData.value);
    } else {
        alert('Please select a file first.');
    }
    };

    // Register all components from Chart.js
    Chart.register(...registerables); // Register the necessary components (e.g., linear scale, etc.)

    onMounted(() => {
    const ctx = document.getElementById('myChart').getContext('2d');


    // Initialize Chart.js with static data
    new Chart(ctx, {
        type: 'line', // Line chart type
        data: {
        datasets: [
            {
            label: 'Dataset 1',
            data: [
                { x: 4783.843, y: -0.234 },
                { x: 4784.391, y: 12747.743 },
                { x: 4894.063, y: 13747.097 },
                { x: 5094.673, y: 14747.050 },
                { x: 5294.509, y: 14847.401 },
                { x: 20100.481, y: 14947.628 },
                { x: 23400.450, y: 15147.370 },
                { x: 23421.419, y: 15147.449 },
            ],
            borderColor: 'blue',
            fill: false,
            xAxisID: 'x1', // Link to the first x-axis
            },
            {
            label: 'Dataset 2',
            data: [
                { x: 0, y: -2500 },
                { x: 0, y: 14500 },
                { x: 1500, y: 15600 },
                { x: 3000, y: 16500 },
                { x: 19000, y: 17000 }
            ],
            borderColor: 'green',
            fill: false,
            xAxisID: 'x2', // Link to the second x-axis
            },
            {
            label: 'Dataset 3',
            data: [
                { x: -5000, y: 2500 },
                { x: -5000, y: 17500 },
                { x: 6500, y: 19600 },
                { x: 19000, y: 19600 },
                { x: 19000, y: 19600 }
            ],
            borderColor: 'yellow',
            fill: false,
            xAxisID: 'x3', // Link to the second x-axis
            }
        ]
        },
        options: {
        responsive: true,
        scales: {
            x1: {
            type: 'linear', // Linear scale for the first x-axis
            position: 'bottom', // Position it at the bottom
            min: -50000,   // Set the minimum value for the x-axis
            max: 50000,  // Set the maximum value for the x-axis
            },
            x2: {
            type: 'linear', // Linear scale for the second x-axis
            position: 'top', // Position it at the top
            min: -50000,   // Set the minimum value for the x-axis
            max: 50000,  // Set the maximum value for the x-axis
            ticks: {
                display: false, // Hide the x-axis ticks (labels)
            },
            },
            x3: {
            type: 'linear', // Linear scale for the second x-axis
            //position: 'top', // Position it at the top
            min: -50000,   // Set the minimum value for the x-axis
            max: 50000,  // Set the maximum value for the x-axis
            ticks: {
                display: false, // Hide the x-axis ticks (labels)
            },
            },
            y: {
            type: 'linear', // Use a linear scale for the y-axis
            position: 'left', // Position it on the left
            min: -20000,   // Set the minimum value for the x-axis
            max: 20000,  // Set the maximum value for the x-axis
            },
        },
        },
    });


    });
  </script>
