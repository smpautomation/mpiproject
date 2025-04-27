<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center h-screen px-8 py-12 mx-auto bg-gray-100">
            <div class="flex flex-col items-center justify-center mt-12 font-bold">SEC ADDITIONAL</div>
            <div class="flex flex-row items-center justify-center my-10 align-baseline">
                <p>Selected Serial: {{ currentSerialSelected }}</p>
            </div>
            <div v-if="toggleManageForm" class="flex flex-col items-center justify-center shadow-xl rounded-xl w-[1000px] h-[450px] border-4 mt-20">
                <div v-show="showUploadData" class="flex flex-row items-center justify-center">
                    <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 mr-10 rounded-lg shadow-lg bg-gray-50">
                        <!-- Upload Section Title -->
                        <p class="mb-4 text-xl font-semibold text-gray-800">Upload Raw Data:</p>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                            <!-- File Input Label -->
                            <label for="upload-file" class="text-lg font-medium text-gray-600">Select a file to upload:</label>

                            <!-- File Upload Input -->
                            <input
                                id="upload-file"
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
                        <div v-if="fileLists?.length > 0" class="w-[410px] h-[230px] overflow-auto">
                            <p><span class="text-lg font-extrabold">Files:</span> ( You have selected {{ fileLists.length }} files. ) </p>
                            <div
                                v-for="(fileList, index) in fileLists"
                                :key="index"
                                class="p-2 m-2 text-white bg-blue-400 rounded-3xl"
                            >
                                {{ fileList }}
                            </div>
                        </div>

                        <!-- This div shows when there are no files -->
                        <div v-else class="flex flex-row justify-center items-center w-[410px] h-[230px]">
                            <span class="text-2xl animate-pulse">No files selected</span>
                        </div>
                    </div>
                </div>

                <div>
                    <div v-show="showProceed1" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold animate-pulse">TPM DATA UPLOADED SUCCESSFULLY!</p>
                        <p class="mt-5">Please Type the Model Name before proceeding: </p>
                        <input
                            type="text"
                            v-model="jhCurveActualModel"
                            class="px-4 py-2 mt-4 mb-10 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Model Name"
                        />
                        <button
                            class="px-4 py-2 mt-4 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="proceedToCsvUpload"
                            >
                                Proceed to Upload CSV
                        </button>
                    </div>
                </div>

                <div>
                    <div v-show="showLoadingForProceed1" class="flex items-center justify-center mt-10 text-xl align-middle animate-pulse">
                        Loading...
                    </div>
                </div>

                <div>
                    <div v-show="csvUpload">
                        <!-- Upload Section Title -->
                        <p class="mb-4 text-xl font-semibold text-gray-800">Upload file for Temperature and Data Status:</p>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                        <!-- File Input Label -->
                        <label for="csv-file-upload" class="text-lg font-medium text-gray-600">Select a file to upload:</label>

                        <!-- File Upload Input -->
                        <input
                            id="csv-file-upload"
                            type="file"
                            accept=".csv"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            @change="csv_handleFileSelect"
                        />

                        <div>
                            <!-- Clear Button -->
                            <button
                            class="px-4 py-2 mr-4 font-semibold text-white bg-red-700 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                            @click="csv_clearFile"
                            >
                            Clear
                            </button>

                            <!-- Submit Button -->
                            <button
                            class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            @click="csv_submitFile"
                            >
                            Submit
                            </button>
                        </div>

                        <p class="mt-2 text-sm text-gray-500">Only .csv files are allowed</p>
                        </div>
                    </div>
                </div>

                <div>
                    <div v-show="showProceed2" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold animate-pulse">UPLOADING CSV DATA SUCCESSFULLY COMPLETED!</p>
                        <p class="mt-5">Please fill in the details before proceeding</p>
                        <div class="flex flex-row">
                            <div class="flex flex-col items-start justify-start">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-gray-700">Mass Production Name:</label>
                                <input
                                    type="text"
                                    v-model="jhCurveMassProdName"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start ml-5">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-gray-700">Mias. Employee:</label>
                                <input
                                    type="text"
                                    v-model="propData_factorEmp"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start ml-5">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-gray-700">Factor Employee:</label>
                                <input
                                    type="text"
                                    v-model="propData_miasEmp"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start ml-5">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-gray-700">Lot No.:</label>
                                <input
                                    type="text"
                                    v-model="jhCurveLotNo"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        <button
                            class="px-4 py-2 mt-10 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="proceedToFinal"
                            >
                                Submit
                        </button>
                    </div>
                </div>

                <div>
                    <div v-show="showProceed3" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold animate-pulse">ALL DATA HAS BEEN PROCESSED SUCCESSFULLY!</p>
                        <button
                            class="px-4 py-2 mt-4 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="showAllData"
                            >
                                Proceed ( Show Data )
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
                <div class="flex flex-row justify-center space-x-4">
                    <div class="w-[500px] h-[420px] bg-blue-100 rounded-xl flex items-center border-2 border-blue-900 justify-center">
                        <canvas ref="myChartCanvas" width="650" height="680" style="transform: scale(1); transform-origin: top left;"></canvas>
                    </div>
                    <!-- Side Content -->
                    <div class="w-[300px] h-[420px] bg-blue-200 rounded-xl border-2 border-blue-900 flex justify-center items-start p-4">
                        <div class="flex flex-col items-start space-y-2">
                            <p>
                                SMP Lot (
                                <span>{{ jhCurveMassProdName }}</span>
                                Mass Production )
                            </p>
                            <p>
                                Furnace Cycle No. : {{ jhCurveFurnaceName }}
                            </p>
                            <p>
                                <span>{{ jhCurveActualModel }}</span>
                                ( {{ jhCurveModel }} )
                            </p>
                            <p>
                                Lot # {{ jhCurveLotNo }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 mt-4 border-2 border-gray-500 rounded-lg shadow-lg bg-gray-50">
                        <div class="mb-4">
                            <p class="text-center">PROPERTY DATA</p>
                        </div>
                        <div class="flex flex-row justify-center">
                            <p class="mr-10">Mias. Employee: <span>{{ propData_miasEmp }}</span> </p>
                            <p>Factor Employee: <span>{{ propData_factorEmp }}</span></p>
                        </div>
                        <div class="flex flex-col items-center justify-center w-full ">
                            <div class="flex flex-row items-start justify-center w-full">
                                <div class="flex flex-row flex-[3] mb-10 shadow-2xl">
                                    <div class="min-w-[1000px]">
                                        <table class="w-full border border-gray-300 table-auto rounded-xl">
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
                                                v-for="item in combinedData"
                                                :key="item.id"
                                                class="border-b hover:bg-gray-50"
                                            >
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.date }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.serial_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.code_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.order_no }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.type }}</td>
                                                <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.press_1 }} {{ item.press_2 }} {{ item.machine_no }}</td>
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
                                                v-for="item in combinedData"
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

                            <div class="flex flex-row">
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
                                <div class="flex flex-col items-center justify-center p-2 mb-5 ml-10 bg-blue-200 shadow-lg rounded-xl">
                                    <div class="flex flex-row justify-center">
                                        <p class="px-5 mb-2 text-sm font-extrabold text-white bg-blue-900 shadow-xl rounded-xl">Data Results:</p>
                                    </div>
                                    <div class="flex flex-row items-center px-8 py-2 bg-white rounded-3xl">
                                        <p class="mr-2 text-sm font-extrabold">
                                            <span :class="adjustColor_rejectOKNG(rejectOKNGlist)" v-for="(rejectOKNGlist, index) in rejectOKNG" :key="index"> {{ rejectOKNGlist }} </span>
                                        </p>
                                        <p class="ml-2 text-sm">
                                            <span :class="adjustColor_rejectInstructions"> {{ rejectInstruction }} </span>
                                        </p>
                                    </div>
                                    <div class="flex flex-row items-center mt-4 mb-0 align-baseline">
                                        <div class="flex flex-col items-center px-4 py-2 mr-2 bg-white rounded-3xl">
                                            <p class="text-sm font-semibold">
                                                <span :class="adjustColor_rejectiHc"> {{ rejectiHcRemarks }} </span>
                                            </p>
                                            <p class="text-sm font-extrabold">
                                                <span :class="adjustColor_iHcValue"> {{ getHighestSampleVariance }} </span>
                                            </p>
                                        </div>
                                        <div class="flex px-4 py-2 ml-2 bg-white rounded-3xl">
                                            <p class="text-sm font-extrabold">
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
import { ref, computed, onMounted, nextTick } from 'vue';
import { Chart, registerables } from 'chart.js'; // Import all required components
import Papa from 'papaparse';
import axios from 'axios';
// Register all Chart.js components using registerables
Chart.register(...registerables);

// UI Control Variables

const showUploadSection = ref(true);
const toggleManageForm = ref(true);
const showUploadData = ref(true);
const showProceed1 = ref(false);
const showProceed2 = ref(false);
const showProceed3 = ref(false);
const showLoadingForProceed1 = ref(false);
const csvUpload = ref(false);
const layerTableRowLoading = ref(false);
const showGraphAndTables = ref(false);

// UI Control Variables end

// Upload file related variables

const fileLists = ref([]);
const fileData = ref([]); // This will hold the selected file data
const rowCell = ref([]);

// Upload file related variables end

//General variables
const currentSerialSelected = ref('');
const dataReady = ref(false); // Flag to track if data is ready
const myChartCanvas = ref(null); // Ref for the canvas

// Define the prop that will receive the serialParam
const props = defineProps({
    sec_serialParam: String,  // Expecting the serialParam to be a string
});

currentSerialSelected.value = props.sec_serialParam;
console.log('Serial Param check: ',props.sec_serialParam);

// Store the file list when the input changes
const storeFileList = (event) => {
    fileData.value = Array.from(event.target.files);
    fileLists.value = fileData.value.map(file => file.name); // Extract and store file names
    console.log('Files stored:', fileData.value);
};

// Method to clear the file upload
const clearFileUpload = () => {
    fileData.value = null;
    fileLists.value = null;
    const fileInput = document.getElementById('upload-file');
    if (fileInput) fileInput.value = '';
    console.log('File upload cleared');
};

const proceedToCsvUpload = async () => {
        if(jhCurveActualModel.value == null || jhCurveActualModel.value == ''){
            alert('Please type the Model Name first.');
            return;
        }else{
            showLoadingForProceed1.value = true;
            setTimeout(() => {
                csvUpload.value = true;
                showProceed1.value = false;
                showLoadingForProceed1.value = false;
            }, 1000); // Delay just enough to let Vue catch up
        }
    }

    const proceedToFinal = () => {
        if(jhCurveMassProdName.value == null || jhCurveMassProdName.value == "" ||
            propData_factorEmp.value == null || propData_factorEmp.value == "" ||
            propData_miasEmp.value == null || propData_miasEmp.value == "" ||
            jhCurveLotNo.value == null || jhCurveLotNo.value == ""){
                alert('Please fill in all the details first before proceeding');
                return;
        }else{
            showProceed3.value = true;
            showProceed2.value = false;
            saveToTpmCategory();
        }
    }

 //tpmdata category in database
 const jhCurveModel = ref("");
    const jhCurveMassProdName = ref("");
    const jhCurveActualModel = ref("");
    const jhCurveFurnaceName = ref("");
    const jhCurveLotNo = ref("");

    const propData_miasEmp = ref("");
    const propData_factorEmp = ref("");

    const saveToTpmCategory = async () => {
        try{
            const responsePatchCategory = await axios.patch(`/api/nsaupdatecategory/${currentSerialSelected.value}`,{
                    actual_model: jhCurveActualModel.value,
                    factor_emp: propData_factorEmp.value,
                    jhcurve_lotno: jhCurveLotNo.value,
                    mias_emp: propData_miasEmp.value,
                    massprod_name: jhCurveMassProdName.value,
                });
                console.log("API PATCHED category: ",responsePatchCategory);
        }catch(error){
            console.error("Error fetching API Response SaveToTpmCategory:", error);
        }
    }

const csv_selectedFile = ref(null)
    const csv_parsedData = ref([])
    const csv_tempWithDataStat = ref([]);

    const csv_handleFileSelect = (event) => {
        csv_selectedFile.value = event.target.files[0]
    }

    const csv_submitFile = async () => {
    if (!csv_selectedFile.value) {
        alert('Please select a CSV file first.')
        return
    }else{
        csvUpload.value = false;
        showProceed2.value = true;
    }

    Papa.parse(csv_selectedFile.value, {
        header: true,
        skipEmptyLines: true,
        complete: (results) => {
            csv_parsedData.value = results.data
            const rows = results.data
            // Combine them into one array for the table
            csv_tempWithDataStat.value = rows.map(row => ({
                temp: row["Temperature"],
                status: row["Data class"]
            }));

            console.log('CSV Parsed Data:', csv_parsedData.value);
            console.log('Temp with Data class:', csv_tempWithDataStat.value);

        },
        error: (err) => {
        console.error('Error parsing CSV:', err)
        }
    });
    mergeTempToTPM();
    }

    const csv_clearFile = () => {
    csv_selectedFile.value = null
    csv_parsedData.value = []

    // Reset the file input manually
    const csv_fileInput = document.getElementById('csv-file-upload')
        if (csv_fileInput) {
            csv_fileInput.value = ''
        }
    }

    const mergeTempToTPM = async () => {
        try{ //tempDataClass contains the value to be updated
            const responseTPM = await axios.get("/api/nsadata?serial=" + currentSerialSelected.value); // This is used to find the proper id to update
            console.log('API Response MergeTempToTPMDATA:', responseTPM.data);

            const tpmData = responseTPM.data.data || [];
            console.log('tpm data: ',tpmData);
            const getAllID = tpmData.map(item => item.id);
            console.log('getting all the ids:',getAllID);

            for (let i = 0; i < getAllID.length; i++) {
                try {
                    const responseTempWithData = await axios.patch(`/api/nsadataupdate/${getAllID[i]}`, {
                        temperature: csv_tempWithDataStat.value[i]?.temp || null,
                        data_status: csv_tempWithDataStat.value[i]?.status || null
                    });

                    console.log(`Patched ID ${getAllID[i]} with row ${i}:`, responseTempWithData.data);
                } catch (error) {
                    console.error(`Error patching ID ${getAllID[i]}:`, error.response?.data || error.message);
                }
            }
            // Uncomment this to see the response
            console.log('API Response Patch merging Temp and Dataclass:', responseTempWithData.data);
        }catch(error){

        }
    }

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

    //UI Dynamic Color adjustments end

    //New Furnace , New Layers end

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
const xAxis = ref([]);
const yAxis = ref([]);
const xJsonOutput = ref(null)
const yJsonOutput = ref(null);

// set of new variables for the 2nd save ( the aggregates and remarks )

const saveBrRemarks = ref('');
const save4paiIdRemarks = ref('');
const saveIHcRemarks = ref('');
const saveBHcRemarks = ref('');
const saveBHMaxRemarks = ref('');
const saveSquarenessRemarks = ref('');
const save4paiIsRemarks = ref('');
const saveIHkRemarks = ref('');
const save4paiIaRemarks = ref('');
const saveDensityRemarks = ref('');
const saveIHkiHcRemarks = ref('');
const saveBr4paiRemarks = ref('');
const saveIHr95Remarks = ref('');
const saveIHr98Remarks = ref('');

const highest_setNo = ref(1);

const saveToDatabase = async () => {
    if (fileData.value.length === 0) {
        console.error("No file selected! fileData is empty.");
        return; // Exit the function if fileData is empty
    }

    // Sort the files alphabetically by their name
    fileData.value.sort((a, b) => a.name.localeCompare(b.name)); // Sort by file name alphabetically
    console.log('Sorted Files:', fileData.value);

    layerTableRowLoading.value = true;

    const responseCheckNsa = await axios.get("/api/nsadata");
    console.log('response check NSA: ',responseCheckNsa.data);
    const nsaData = responseCheckNsa.data.data["NSAData"];
    console.log('nsa Data array fectch: ',nsaData);

    if (Object.keys(nsaData).length > 0) {
        const nsaArray = Object.values(nsaData);
        const maxSetNo = Math.max(...nsaArray.map(item => item.set_no));
        highest_setNo.value = maxSetNo + 1;
    }else{
        console.log('NOT ARRAY!!');
    }

    console.log('Current set_no value: ',highest_setNo.value);

    fileData.value.forEach((file) => {

        const reader = new FileReader();

        reader.onload = () => {
            const content = reader.result; // Read file content
            const parsedData = parseFileContent(content); // Parse content

            //console.log('Parsed Data:', parsedData);


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

        //-------------------------------------GRAPH
            const xValues = [];
            const yValues = [];
            for (const key in parsedData) {
                if (
                    parsedData.hasOwnProperty(key) &&
                    key.startsWith('data') &&
                    parseInt(key.replace('data', ''), 10) >= 150 && //Adjust
                    parseInt(key.replace('data', ''), 10) <= numberOfRows
                ) {
                    const [x, y] = parsedData[key].split(',').map(Number);

                        // Custom condition: x must be less than 100 and y must be greater than -1000
                    if (x >= 100 || y <= -1000) { // If x >= 100 or y <= -1000, skip the data
                        //console.log(`Skipping data: x = ${x}, y = ${y} due to condition.`);
                        continue; // Skip this iteration if condition is not met
                    }

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

            saveBrRemarks.value = parsedData.data25;
            save4paiIdRemarks.value = parsedData.data28;
            saveIHcRemarks.value = parsedData.data31;
            saveBHcRemarks.value = parsedData.data34;
            saveBHMaxRemarks.value = parsedData.data37;
            saveSquarenessRemarks.value = parsedData.data40;
            save4paiIsRemarks.value = parsedData.data43;
            saveIHkRemarks.value = parsedData.data46;
            save4paiIaRemarks.value = parsedData.data49;
            saveDensityRemarks.value = parsedData.data52;
            saveIHkiHcRemarks.value = parsedData.data55;
            saveBr4paiRemarks.value = parsedData.data58;
            saveIHr95Remarks.value = parsedData.data61;
            saveIHr98Remarks.value = parsedData.data64;

            const rawDate = rowCell.value[0]; // The raw date from rowCell.value[0]

            // Check if the date is in a valid format and reformat it to YYYY-MM-DD
            const formattedDate = new Date(rawDate).toISOString().split('T')[0];  // Converts to 'YYYY-MM-DD' format

            const layerData = {
                "date": formattedDate,
                "serial_no": currentSerialSelected.value,
                "set_no": highest_setNo.value,
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
                "Br_remarks": saveBrRemarks.value,
                "4paiId_remarks": save4paiIdRemarks.value,
                "iHc_remarks": saveIHcRemarks.value,
                "bHc_remarks": saveBHcRemarks.value,
                "BHMax_remarks": saveBHMaxRemarks.value,
                "Squareness_remarks": saveSquarenessRemarks.value,
                "4paiIs_remarks": save4paiIsRemarks.value,
                "iHk_remarks": saveIHkRemarks.value,
                "4paiIa_remarks": save4paiIaRemarks.value,
                "Density_remarks": saveDensityRemarks.value,
                "iHkiHc_remarks": saveIHkiHcRemarks.value,
                "Br4pai_remarks": saveBr4paiRemarks.value,
                "iHr95_remarks": saveIHr95Remarks.value,
                "iHr98_remarks": saveIHr98Remarks.value,
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

// Function to send raw data via API
const sendLayerData = async (layerData) => {
    try {
        console.log("Sending layer TO API: ",layerData);
        const response = await axios.post('/api/nsadata', layerData); // Replace '/api/endpoint' with your API endpoint
        console.log('API Response sendlayerdata:', response.data);

    } catch (error) {
        console.error('Error sending data to API:', error.response?.data || error.message);
        return;
    } finally {
        showProceed1.value = true;
        showUploadData.value = false;
        layerTableRowLoading.value = false;
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

const items = ref([]); // Holds the fetched data
const tpmData = ref([]); // Holds the tpmData array
const filteredNSAData = ref([]);
const tpmRemarks = ref([]); // Holds the remarks array
const tpmAggregateAve = ref([]); // Holds the aggregateFunctions array
const tpmAggregateMax = ref([]); // Holds the aggregateFunctions array
const tpmAggregateMin = ref([]); // Holds the aggregateFunctions array
const combinedData = ref([]); // Holds the combined array
const getAllIDValues = ref([]);

// Variables for aggregate
const getAggregateID = ref([]);

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
        showProceed3.value = false;
        try {
            showProceed3.value = false;
            const response = await axios.get("/api/nsadata?serial=" + currentSerialSelected.value); // Adjust this URL to your API endpoint
            console.log('API Response showallData:', response.data);

            console.log('Serial No value = ', currentSerialSelected.value);

            items.value = response.data;

            // Extract arrays from the response
            tpmData.value = response.data.data || []; // Fallback to an empty array if undefined
            console.log('TPM DATA VALUE IN SHOW ALL DATA: ',tpmData.value);

            const allData = tpmData.value;

            if (Array.isArray(allData) && allData.length > 0) {
                // Step 1: Find the highest set_no
                const maxSetNo = Math.max(...allData.map(item => item.set_no));
                // Step 2: Filter the data to only those with the highest set_no
                filteredNSAData.value = allData.filter(item => item.set_no === maxSetNo);
                console.log('Filtered data with highest set_no:', filteredNSAData.value);
            } else {
                console.log('No data found or data is not an array.');
            }

            tpmRemarks.value = response.data.remark || [];
            getAggregateID.value = response.data[0][0].id || [];
            console.log("Aggregate ID: ", getAggregateID.value);

            const tpm_category_actualmodel = filteredNSAData.value.map(item => item.category?.actual_model ?? null);
            jhCurveActualModel.value = tpm_category_actualmodel[0];
            const tpm_category_factorEmp = filteredNSAData.value.map(item => item.category?.factor_emp ?? null);
            propData_factorEmp.value = tpm_category_factorEmp[0];
            const tpm_category_miasEmp = filteredNSAData.value.map(item => item.category?.mias_emp ?? null);
            propData_miasEmp.value = tpm_category_miasEmp[0];
            const tpm_category_jhCurveLotno = filteredNSAData.value.map(item => item.category?.jhcurve_lotno ?? null);
            jhCurveLotNo.value = tpm_category_jhCurveLotno[0];
            const tpm_category_massProdName = filteredNSAData.value.map(item => item.category?.massprod_name ?? null);
            jhCurveMassProdName.value = tpm_category_massProdName[0];

            // Combine the arrays
            combinedData.value = filteredNSAData.value;
            console.log('Combined Data: ', combinedData.value);

            jhCurveFurnaceName.value = filteredNSAData.value[0].sintering_furnace_no || "No data found";
            jhCurveModel.value = filteredNSAData.value[0].code_no || "No data found";

            // Extract individual values from tpmData for aggregate
            getAllIDValues.value = combinedData.value.map(item => item.id);
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

            const aggregateData = {
                "average": JSON.stringify({
                    "Br": aveBr.value,
                    "bHc": avebHc.value,
                    "iHc": aveiHc.value,
                    "iHk": aveiHk.value,
                    "Hr95": aveiHr95.value,
                    "Hr98": aveiHr98.value,
                    "BHMax": aveBHMax.value,
                    "4paila": ave4paila.value,
                    "4paild": ave4paild.value,
                    "4pails": ave4pails.value,
                    "Br4pai": aveBr4pai.value,
                    "iHciHk": aveiHciHk.value,
                    "Squareness": aveSquareness.value
                }),
                "maximum": JSON.stringify({
                    "Br": maxBr.value,
                    "bHc": maxbHc.value,
                    "iHc": maxiHc.value,
                    "iHk": maxiHk.value,
                    "Hr95": maxiHr95.value,
                    "Hr98": maxiHr98.value,
                    "BHMax": maxBHMax.value,
                    "4paila": max4paila.value,
                    "4paild": max4paild.value,
                    "4pails": max4pails.value,
                    "Br4pai": maxBr4pai.value,
                    "iHciHk": maxiHciHk.value,
                    "Squareness": maxSquareness.value
                }),
                "minimum": JSON.stringify({
                    "Br": minBr.value,
                    "bHc": minbHc.value,
                    "iHc": miniHc.value,
                    "iHk": miniHk.value,
                    "Hr95": miniHr95.value,
                    "Hr98": miniHr98.value,
                    "BHMax": minBHMax.value,
                    "4paila": min4paila.value,
                    "4paild": min4paild.value,
                    "4pails": min4pails.value,
                    "Br4pai": minBr4pai.value,
                    "iHciHk": miniHciHk.value,
                    "Squareness": minSquareness.value
                }),
                "ng_counter": JSON.stringify({
                    "Br": ngBr.value,
                    "bHc": ngbHc.value,
                    "iHc": ngiHc.value,
                    "iHk": ngiHk.value,
                    "Hr95": ngiHr95.value,
                    "Hr98": ngiHr98.value,
                    "BHMax": ngBHMax.value,
                    "4paila": ng4paila.value,
                    "4paild": ng4paild.value,
                    "4pails": ng4pails.value,
                    "Br4pai": ngBr4pai.value,
                    "iHciHk": ngiHciHk.value,
                    "Squareness": ngSquareness.value
                })
            };

            console.log('Aggregate Data:', aggregateData);
            sendAggData(aggregateData, currentSerialSelected.value);

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
            showProceed3.value = false;
            //ng conditions for NG remarks end...

            //console.log("Average Values:", aggAveValues.value.map(refObj => refObj.value));
            //console.log("ng iHr95 test: ", calculateSum(getAlliHr95Remarks.value));

        } catch (error) {
            console.error('Error fetching data:', error);
            return;
        } finally {
            showProceed3.value = false;
            showGraphAndTables.value = true;
        }
        await fetchDataCreateGraph();
        console.log('END OF SHOWALLDATA EYYYY!!');
    };

    // Function to send raw data via API
    const sendAggData = async (aggData, id) => {  // Make sure to pass 'id' as a parameter
        try {
            // Correct string interpolation for the URL
            const response = await axios.patch(`/api/nsaaggregateupdate/${id}`, aggData); // Proper string interpolation for URL
            // Uncomment this to see the response
            console.log('API Response sendAggData:', response.data);
        } catch (error) {
            console.error('Error sending aggregate data to API:', error.response?.data || error.message);
        }
    };

    // State for storing fetched data and error
const error = ref(null);
const datasets = ref([]); // Array to hold multiple datasets
const filteredNSADataForGraph = ref([]);

const fetchDataCreateGraph = async () => {
    try {
        const response = await axios.get("/api/nsadata?serial=" + currentSerialSelected.value);

        // Log the response structure to check
        //console.log("API Response:", response.data);

        const tableRows = response.data.data; // Assuming API returns an array of rows

        const allData = tableRows;

        if (Array.isArray(allData) && allData.length > 0) {
            // Step 1: Find the highest set_no
            const maxSetNo = Math.max(...allData.map(item => item.set_no));
            // Step 2: Filter the data to only those with the highest set_no
            filteredNSADataForGraph.value = allData.filter(item => item.set_no === maxSetNo);
            console.log('Filtered data with highest set_no:', filteredNSAData.value);
        } else {
            console.log('No data found or data is not an array.');
        }

        // Check if tableRows is not undefined or null before proceeding
        if (!filteredNSADataForGraph.value) {
            throw new Error("No data found in tpmData");
        }

        // Parse each row and dynamically generate datasets
        datasets.value = filteredNSADataForGraph.value.map((row, index) => ({
            xAxis: JSON.parse(row.x || "[]"), // Parse x values
            yAxis: JSON.parse(row.y || "[]"), // Parse y values
            color: generateColor(index), // Assign a unique color
        }));
        showGraphAndTables.value = true;  // Set this to true after data is loaded

        // Set dataReady to true once the data is ready
        dataReady.value = true;
        //console.log("dataReady ", dataReady.value);
        // Ensure DOM updates before rendering
        nextTick(() => {
            //console.log("myChartCanvas reference:", myChartCanvas.value); // Debug the canvas ref
            if (myChartCanvas.value) {
                renderChart();  // Proceed to render the chart if the canvas is available
            } else {
                console.error("Canvas element is not available.");
            }
        });
        layerTableRowLoading.value = false;
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
    // Ensure myChartCanvas is not null
    if (!myChartCanvas.value) {
        console.error("Canvas element is not available.");
        return;
    }
    // Ensure the canvas is accessible
    //console.log("Rendering chart with canvas:", myChartCanvas.value);

    // Ensure datasets are available before creating the chart
    //console.log("Datasets available for chart:", datasets.value);

    if (dataReady.value && myChartCanvas.value) {
        const ctx = myChartCanvas.value.getContext("2d");

        // Check if the context is obtained
        if (!ctx) {
            console.error("Failed to get 2D context for canvas.");
            return;
        }

        //console.log("2D Context obtained, proceeding to render the chart.");

        const x_offset = 2000;
        const y_offset = 4000;

        const chartDatasets = datasets.value.map((dataset, index) => {
            return {
                label: `Dataset ${index + 1}`,
                data: dataset.xAxis.map((x, i) => ({
                    x: x + index * x_offset,
                    y: (dataset.yAxis[i] || 0) - index * y_offset,
                })),
                borderColor: dataset.color,
                borderWidth: 2,
                fill: false,
                pointBackgroundColor: dataset.color,
                pointBorderColor: dataset.color,
            };
        });

        //console.log("Final Chart Datasets:", chartDatasets);

        try {
            new Chart(ctx, {
                type: "line",
                data: {
                    datasets: chartDatasets.map(dataset => ({
                        ...dataset,
                        pointRadius: 0,
                        tension: 0.6,
                    })),
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: "easeOutQuart",
                    },
                    plugins: {
                        legend: {
                            display: false, // 👈 hides the legend completely
                        },
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
                                text: "←  kOe  →",
                                color: "#333",
                                font: {
                                    size: 14,  // Increase font size
                                    weight: "bold", // Make it bold
                                },
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
                                text: "←  kG  →",
                                color: "#333",
                                font: {
                                    size: 14,  // Increase font size
                                    weight: "bold", // Make it bold
                                },
                            },
                        },
                    },
                },
            });
        } catch (error) {
            console.error("Error initializing Chart.js:", error);
        }
    } else {
        console.error("Chart cannot be rendered: Missing data or canvas context.");
    }
};

</script>
