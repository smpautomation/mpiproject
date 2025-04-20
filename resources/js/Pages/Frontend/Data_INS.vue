<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
            <div class="flex flex-col items-center justify-center mt-12 font-bold">This is the Data Upload page For Corner, Surface, and Core </div>
            <p class="flex flex-col items-center justify-center mt-10 mb-10 font-extrabold">Serial: 2503000001</p>
            <div v-show="showCornerUpload">
                <div class="flex flex-row items-center justify-center">
                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 mr-10 rounded-lg shadow-lg bg-cyan-100">
                        <!-- Upload Section Title -->
                        <p class="text-xl font-semibold text-gray-800">Upload Raw Data for</p> <span class="p-2 mb-5 text-2xl rounded-lg shadow-xl font-extra bg-cyan-300">CORNER:</span>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                            <!-- File Input Label -->
                            <label for="file-upload" class="text-lg font-medium text-gray-600">Select a file to upload:</label>

                            <!-- File Upload Input -->
                            <input
                                id="corner-file"
                                type="file"
                                accept=".tpm"
                                multiple
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @change="corner_storeFileList"
                            />

                            <div>

                            <button
                                class="px-4 py-2 mr-24 font-semibold text-white bg-red-700 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                @click="corner_clearFileUpload"
                            >
                                Clear
                            </button>

                            <!-- Submit Button -->
                            <input
                                type="submit"
                                id="submitRawdata"
                                class="px-4 py-2 font-semibold text-white transition duration-200 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                @click="corner_saveToDatabase"
                            />
                            <!-- Button to Redo Upload -->

                            </div>

                            <!-- Optional Instruction Text -->
                            <p class="mt-2 text-sm text-gray-500">Only .tpm files are allowed</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
                        <p>Files: </p>
                        <!--
                        <div v-for="(fileList, index) in fileLists" :key="index" class="p-2 m-2 text-white bg-blue-400 rounded-3xl">
                            {{ fileList }}
                        </div>
                        -->
                    </div>
                </div>

            </div>

            <div v-show="showSurfaceUpload">
                <div class="flex flex-row items-center justify-center">
                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 mr-10 rounded-lg shadow-lg bg-yellow-50">
                        <!-- Upload Section Title -->
                        <p class="text-xl font-semibold text-gray-800">Upload Raw Data for</p> <span class="p-2 mb-5 text-2xl bg-yellow-200 rounded-lg shadow-xl font-extra">SURFACE:</span>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                            <!-- File Input Label -->
                            <label for="file-upload" class="text-lg font-medium text-gray-600">Select a file to upload:</label>

                            <!-- File Upload Input -->
                            <input
                                id="surface-file"
                                type="file"
                                accept=".tpm"
                                multiple
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @change="surface_storeFileList"
                            />

                            <div>

                            <button
                                class="px-4 py-2 mr-24 font-semibold text-white bg-red-700 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                @click="surface_clearFileUpload"
                            >
                                Clear
                            </button>

                            <!-- Submit Button -->
                            <input
                                type="submit"
                                id="submitRawdata"
                                class="px-4 py-2 font-semibold text-white transition duration-200 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                @click="surface_saveToDatabase"
                            />
                            <!-- Button to Redo Upload -->

                            </div>

                            <!-- Optional Instruction Text -->
                            <p class="mt-2 text-sm text-gray-500">Only .tpm files are allowed</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
                        <p>Files: </p>
                        <!--
                        <div v-for="(fileList, index) in fileLists" :key="index" class="p-2 m-2 text-white bg-blue-400 rounded-3xl">
                            {{ fileList }}
                        </div>
                        -->
                    </div>
                </div>
            </div>

            <div v-show="showCoreUpload">
                <div class="flex flex-row items-center justify-center">
                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 mr-10 rounded-lg shadow-lg bg-violet-50">
                        <!-- Upload Section Title -->
                        <p class="text-xl font-semibold text-gray-800">Upload Raw Data for</p> <span class="p-2 mb-5 text-2xl rounded-lg shadow-xl bg-violet-300 font-extra">Core:</span>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                            <!-- File Input Label -->
                            <label for="file-upload" class="text-lg font-medium text-gray-600">Select a file to upload:</label>

                            <!-- File Upload Input -->
                            <input
                                id="core-file"
                                type="file"
                                accept=".tpm"
                                multiple
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @change="core_storeFileList"
                            />

                            <div>

                            <button
                                class="px-4 py-2 mr-24 font-semibold text-white bg-red-700 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                @click="core_clearFileUpload"
                            >
                                Clear
                            </button>

                            <!-- Submit Button -->
                            <input
                                type="submit"
                                id="submitRawdata"
                                class="px-4 py-2 font-semibold text-white transition duration-200 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                @click="core_saveToDatabase"
                            />
                            <!-- Button to Redo Upload -->

                            </div>

                            <!-- Optional Instruction Text -->
                            <p class="mt-2 text-sm text-gray-500">Only .tpm files are allowed</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
                        <p>Files: </p>
                        <!--
                        <div v-for="(fileList, index) in fileLists" :key="index" class="p-2 m-2 text-white bg-blue-400 rounded-3xl">
                            {{ fileList }}
                        </div>
                        -->
                    </div>
                </div>
            </div>

        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, nextTick } from 'vue';

// UI Control Variables

const showCornerUpload = ref(true);
const showSurfaceUpload = ref(false);
const showCoreUpload = ref(false);

// UI Control Variables end

// Upload file related variables

const corner_fileLists = ref([]);
const corner_fileData = ref([]); // This will hold the selected file data
const corner_rowCell = ref([]);

const surface_fileLists = ref([]);
const surface_fileData = ref([]); // This will hold the selected file data
const surface_rowCell = ref([]);

const core_fileLists = ref([]);
const core_fileData = ref([]); // This will hold the selected file data
const core_rowCell = ref([]);

// Upload file related variables end

// Store the file list when the input changes
const corner_storeFileList = (event) => {
    corner_fileData.value = Array.from(event.target.files);
    corner_fileLists.value = corner_fileData.value.map(file => file.name); // Extract and store file names
    console.log('Corner Files stored:', corner_fileData.value);
};

const surface_storeFileList = (event) => {
    surface_fileData.value = Array.from(event.target.files);
    surface_fileLists.value = surface_fileData.value.map(file => file.name); // Extract and store file names
    console.log('Surface Files stored:', surface_fileData.value);
};

const core_storeFileList = (event) => {
    core_fileData.value = Array.from(event.target.files);
    core_fileLists.value = core_fileData.value.map(file => file.name); // Extract and store file names
    console.log('Core Files stored:', core_fileData.value);
};

// Method to clear the file upload
const corner_clearFileUpload = () => {
    corner_fileData.value = null;
    corner_fileLists.value = null;
    const fileInput = document.getElementById('corner-file');
    if (fileInput) fileInput.value = '';
    console.log('Corner File upload cleared');
};

const surface_clearFileUpload = () => {
    surface_fileData.value = null;
    surface_fileLists.value = null;
    const fileInput = document.getElementById('surface-file');
    if (fileInput) fileInput.value = '';
    console.log('Surface File upload cleared');
};

const core_clearFileUpload = () => {
    core_fileData.value = null;
    core_fileLists.value = null;
    const fileInput = document.getElementById('core-file');
    if (fileInput) fileInput.value = '';
    console.log('Core File upload cleared');
};

const corner_saveToDatabase = () => {
    if (corner_fileData.value.length === 0) {
        console.error("No file selected! fileData is empty.");
        return; // Exit the function if fileData is empty
    }

    // Sort the files alphabetically by their name
    corner_fileData.value.sort((a, b) => a.name.localeCompare(b.name)); // Sort by file name alphabetically
    console.log('Corner Sorted Files:', corner_fileData.value);

    //layerTableRowLoading.value = true;
    corner_fileData.value.forEach((file) => {

        const reader = new FileReader();

        reader.onload = () => {
            const content = reader.result; // Read file content
            const parsedData = parseFileContent(content); // Parse content

            console.log('Corner Parsed Data:', parsedData);


            // Dynamically handle rows based on the length of the file content
            const lines = content.split("\n"); // Split content into lines (assuming newline delimiter)
            const rows = lines.map(line => line.split("\t")); // Split each line by tabs (if TSV)

            const numberOfRows = rows.length; // This gives the total number of rows in the file
            //console.log(`The file contains ${numberOfRows} rows.`);

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

            corner_rowCell.value = newRowValues;

            const rawDate = corner_rowCell.value[0]; // The raw date from corner_rowCell.value[0]

            // Check if the date is in a valid format and reformat it to YYYY-MM-DD
            //const formattedDate = new Date(rawDate).toISOString().split('T')[0];  // Converts to 'YYYY-MM-DD' format

            const layerData = {
                //"serial_no": serialNo.value,
                //"code_no": corner_rowCell.value[1],
                //"order_no": corner_rowCell.value[2],
                "Br": Math.round(corner_rowCell.value[11]),
                "iHc": Math.round(corner_rowCell.value[13]),
            };
            console.log("Corner Layer Data:", layerData);

            corner_sendLayerData(layerData); // Send the parsed data to the server
        };

        reader.onerror = () => {
            console.error('Error reading file:', file.name);
        };

        reader.readAsText(file); // Read the file as text
    });
};

const surface_saveToDatabase = () => {
    if (corner_fileData.value.length === 0) {
        console.error("Surface No file selected! fileData is empty.");
        return; // Exit the function if fileData is empty
    }

    // Sort the files alphabetically by their name
    corner_fileData.value.sort((a, b) => a.name.localeCompare(b.name)); // Sort by file name alphabetically
    console.log('Surface Sorted Files:', corner_fileData.value);

    //layerTableRowLoading.value = true;
    corner_fileData.value.forEach((file) => {

        const reader = new FileReader();

        reader.onload = () => {
            const content = reader.result; // Read file content
            const parsedData = parseFileContent(content); // Parse content

            console.log('Surface Parsed Data:', parsedData);


            // Dynamically handle rows based on the length of the file content
            const lines = content.split("\n"); // Split content into lines (assuming newline delimiter)
            const rows = lines.map(line => line.split("\t")); // Split each line by tabs (if TSV)

            const numberOfRows = rows.length; // This gives the total number of rows in the file
            //console.log(`The file contains ${numberOfRows} rows.`);

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

            surface_rowCell.value = newRowValues;

            const rawDate = surface_rowCell.value[0]; // The raw date from surface_rowCell.value[0]

            // Check if the date is in a valid format and reformat it to YYYY-MM-DD
            //const formattedDate = new Date(rawDate).toISOString().split('T')[0];  // Converts to 'YYYY-MM-DD' format

            const layerData = {
                //"serial_no": serialNo.value,
                //"code_no": surface_rowCell.value[1],
                //"order_no": surface_rowCell.value[2],
                "Br": Math.round(surface_rowCell.value[11]),
                "iHc": Math.round(surface_rowCell.value[13]),
            };
            console.log("Surface Layer Data:", layerData);

            surface_sendLayerData(layerData); // Send the parsed data to the server
        };

        reader.onerror = () => {
            console.error('Error reading file:', file.name);
        };

        reader.readAsText(file); // Read the file as text
    });
};

const core_saveToDatabase = () => {
    if (core_fileData.value.length === 0) {
        console.error("Core No file selected! fileData is empty.");
        return; // Exit the function if fileData is empty
    }

    // Sort the files alphabetically by their name
    core_fileData.value.sort((a, b) => a.name.localeCompare(b.name)); // Sort by file name alphabetically
    console.log('Core Sorted Files:', core_fileData.value);

    //layerTableRowLoading.value = true;
    core_fileData.value.forEach((file) => {

        const reader = new FileReader();

        reader.onload = () => {
            const content = reader.result; // Read file content
            const parsedData = parseFileContent(content); // Parse content

            console.log('Core Parsed Data:', parsedData);


            // Dynamically handle rows based on the length of the file content
            const lines = content.split("\n"); // Split content into lines (assuming newline delimiter)
            const rows = lines.map(line => line.split("\t")); // Split each line by tabs (if TSV)

            const numberOfRows = rows.length; // This gives the total number of rows in the file
            //console.log(`The file contains ${numberOfRows} rows.`);

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

            core_rowCell.value = newRowValues;

            const rawDate = core_rowCell.value[0]; // The raw date from core_rowCell.value[0]

            // Check if the date is in a valid format and reformat it to YYYY-MM-DD
            //const formattedDate = new Date(rawDate).toISOString().split('T')[0];  // Converts to 'YYYY-MM-DD' format

            const layerData = {
                //"serial_no": serialNo.value,
                //"code_no": core_rowCell.value[1],
                //"order_no": core_rowCell.value[2],
                "Br": Math.round(core_rowCell.value[11]),
                "iHc": Math.round(core_rowCell.value[13]),
            };
            console.log("Core Layer Data:", layerData);

            core_sendLayerData(layerData); // Send the parsed data to the server
        };

        reader.onerror = () => {
            console.error('Error reading file:', file.name);
        };

        reader.readAsText(file); // Read the file as text
    });
};


// Function to send raw data via API
const corner_sendLayerData = async (layerData) => {
    try {
        console.log("Sending CORNER TO API: ",layerData);
        //const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
        //console.log('API Response sendlayerdata:', response.data);
    } catch (error) {
        console.error('Error sending data to API:', error.response?.data || error.message);
    } finally {
        showCornerUpload.value = false;
        showSurfaceUpload.value = true;
    }
};

const surface_sendLayerData = async (layerData) => {
    try {
        console.log("Sending SURFACE TO API: ",layerData);
        //const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
        //console.log('API Response sendlayerdata:', response.data);
    } catch (error) {
        console.error('Error sending data to API:', error.response?.data || error.message);
    } finally {
        showSurfaceUpload.value = false;
        showCoreUpload.value = true;
    }
};

const core_sendLayerData = async (layerData) => {
    try {
        console.log("Sending CORE TO API: ",layerData);
        //const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
        //console.log('API Response sendlayerdata:', response.data);
    } catch (error) {
        console.error('Error sending data to API:', error.response?.data || error.message);
    } finally {
        showCoreUpload.value = false;
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

</script>
