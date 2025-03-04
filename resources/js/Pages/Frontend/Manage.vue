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
                <label class="block mb-4 text-2xl font-semibold text-gray-800 shadow-xl bg-gradient-to-r from-yellow-400 to-yellow-100 max-w-36">FIRST LAYER</label>
                <!-- Table with Full Width -->
                <table class="overflow-hidden border border-gray-300 rounded-lg shadow-2xl table-auto">
                    <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                        <tr>
                            <th class="px-3 py-2 text-sm font-medium text-center">Date</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Code&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Order&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Type</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Lot&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Furnace&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Coating&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Zone</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Pass&nbsp;No</th>
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
                            <th class="px-3 py-2 text-sm font-medium text-center">Tracer</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Sample with&nbsp;Variance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td colspan="24" class="px-3 py-3 text-sm text-center bg-gradient-to-r from-blue-700 to-blue-400"></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">AVG</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">MAX</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">MIN</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                        </tr>
                    </tbody>
                </table>
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
                    2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 30, 45, 36, 60, 63,
                    54, 57, 33, 39, 27, 42, 48, 66
                ];

                const graphCoordinates = Array.from({ length: 3543 - 100 + 1 }, (_, i) => i + 100);

                // Update graphAxis with the new values
                graphAxis.value = graphCoordinates
                    .map((i) => parsedData[`data${i}`]) // Extract corresponding parsed data
                    .filter(Boolean); // Remove undefined or falsy values
                // Convert graphAxis to JSON
                convertToJSON();
                //console.log('Graph Axis:', graphAxis); //GET GRAPH AXIS VALUE



                // Concatenate data10, data12, and data14
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

                // Add the concatenated value in place of data10, data12, and data14
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
                layerBr: rowCell.value[9],
                layeriHc: rowCell.value[10],
                layeriHk: rowCell.value[11],
                layerBHMax: rowCell.value[12],
                layerHr95: rowCell.value[13],
                layerHr98: rowCell.value[14],
                layeriHciHk: rowCell.value[15],
                layerBr4pai: rowCell.value[16],
                layerbHc: rowCell.value[17],
                layerSquareness: rowCell.value[18],
                layer4paild: rowCell.value[19],
                layer4pails: rowCell.value[20],
                layer4paila: rowCell.value[21],
                layerTracer: rowCell.value[22],
                graphAxis: graphAxis.value, // Include graphAxis JSON data here
            };

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

    // Function to send data via API
    const sendLayerData = async (layerData) => {
        try {
            const response = await axios.post('/api/user', layerData); // Replace '/api/layer-data' with your API endpoint
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
