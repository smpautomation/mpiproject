<template>
    <Frontend>
        <div class="relative flex flex-col items-center justify-center min-h-screen px-8 py-12 mx-auto bg-center bg-no-repeat bg-cover"
        :style="{ backgroundImage: 'url(/photo/manage_background.jpg)',
                backgroundPosition: 'center -120px'
        }">
        <!-- Overlay -->
        <div class="absolute inset-0 z-0 bg-black bg-opacity-30"></div>
            <div v-if="toggleManageForm" class="z-10 flex flex-col items-center justify-center shadow-xl rounded-xl w-[1000px] h-[450px] border-4 bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-lg mt-20">
                <div class="flex flex-row">
                    <p v-show="showSerialNo" class="flex flex-row mb-10 ml-10 font-extrabold text-white">Serial: {{ currentSerialSelected }}</p>
                    <p v-show="showSerialNo" class="flex flex-row mb-10 ml-10 font-extrabold text-white">Furnace: {{ currentFurnaceSelected }}</p>
                    <p v-show="showSerialNo" class="flex flex-row mb-10 ml-10 font-extrabold text-white">Mass Prod: {{ currentMassProdSelected }}</p>
                    <p v-show="showSerialNo" class="flex flex-row mb-10 ml-10 font-extrabold text-white">Layer: {{ currentLayerSelected }}</p>
                </div>

                <div v-if="showGraphAndTables" class="mt-[100px]">

                </div>
                <div v-show="showUploadData" class="flex flex-row items-center justify-center">
                    <div v-show="showUploadTPMFiles" class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 mr-10 rounded-lg shadow-lg bg-gray-50">
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
                                    @click="saveToDatabase_showConfirm"
                                />
                            </div>

                            <!-- Optional Instruction Text -->
                            <p class="mt-2 text-sm text-gray-500">Only .tpm files are allowed</p>
                        </div>
                    </div>

                    <div
                        v-show="showNoFileSelectedError"
                        class="flex items-center px-4 py-2 mt-4 text-red-700 bg-red-100 border border-red-300 rounded-md"
                        >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                        <span>No file selected. Please upload a file.</span>
                    </div>

                    <div v-show="showUploadTPMFiles" class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
                        <div v-if="fileLists.length > 0" class="w-[410px] h-[230px] overflow-auto">
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

                    <div
                        v-show="showSaveToDatabase_confirmation"
                        class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mt-10 bg-white border border-gray-200 shadow-xl rounded-xl"
                        >
                        <div class="mb-6">
                            <p class="text-lg font-semibold text-center text-gray-800">Are you sure you want to upload all these files?</p>
                        </div>
                        <div class="flex space-x-6">
                            <button
                            @click="saveToDatabase"
                            class="px-6 py-2 font-bold text-white transition duration-200 bg-blue-500 rounded-lg shadow-md hover:bg-blue-600"
                            >
                            YES
                            </button>
                            <button
                            @click="saveToDatabase_cancel"
                            class="px-6 py-2 font-bold text-gray-700 transition duration-200 bg-gray-200 rounded-lg shadow-md hover:bg-gray-300"
                            >
                            NO
                            </button>
                        </div>
                    </div>

                </div>
                <div>
                    <div v-if="showProceed1" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold text-white animate-pulse">TPM DATA UPLOADED SUCCESSFULLY!</p>
                        <p class="mt-5 text-white">Please type the remarks description for this additional: </p>
                        <input
                            type="text"
                            v-model="additional_remarks"
                            @input="additional_remarks = additional_remarks.toUpperCase()"
                            class="w-[700px] px-4 py-2 mt-4 mb-10 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="E.G. 2ND LAYER AT OTHER CORNER OF N.G BOX"
                        />
                        <button
                            class="px-4 py-2 mt-4 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="proceedToCsvUpload_showConfirm"
                            >
                                Proceed to Upload CSV
                        </button>
                    </div>
                    <div
                        v-show="showNoRemarksError"
                        class="flex items-center px-4 py-2 mt-4 text-red-700 bg-red-100 border border-red-300 rounded-md"
                        >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                        <span>This field is required</span>
                    </div>
                    <div
                        v-show="showProceedToCsvUpload_confirmation"
                        class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mt-10 bg-white border border-gray-200 shadow-xl rounded-xl"
                        >
                        <div class="mb-6">
                            <p class="text-lg font-semibold text-center text-gray-800">Are you sure this description is correct?</p>
                        </div>
                        <div class="flex space-x-6">
                            <button
                            @click="proceedToCsvUpload"
                            class="px-6 py-2 font-bold text-white transition duration-200 bg-blue-500 rounded-lg shadow-md hover:bg-blue-600"
                            >
                            YES
                            </button>
                            <button
                            @click="proceedToCsvUpload_cancel"
                            class="px-6 py-2 font-bold text-gray-700 transition duration-200 bg-gray-200 rounded-lg shadow-md hover:bg-gray-300"
                            >
                            NO
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <DotsLoader v-show="showLoadingForProceed1"/>
                </div>
                <div>
                    <div v-show="csvUpload">
                        <!-- Upload Section Title -->
                        <p class="mb-4 text-xl font-semibold text-white">Upload file for Temperature and Data Status:</p>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                        <!-- File Input Label -->
                        <label for="csv-file-upload" class="text-lg font-medium text-white">Select a file to upload:</label>

                        <!-- File Upload Input -->
                        <input
                            id="csv-file-upload"
                            type="file"
                            accept=".csv"
                            class="block w-full px-4 py-3 text-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                            @click="csvUpload_showConfirm"
                            >
                            Submit
                            </button>
                        </div>
                        <p class="mt-2 text-sm text-white">Only .csv files are allowed</p>
                        </div>
                    </div>
                    <div
                        v-show="showNoCsvFileSelectedError"
                        class="flex items-center px-4 py-2 mt-4 text-red-700 bg-red-100 border border-red-300 rounded-md"
                        >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                        <span>No CSV file selected.</span>
                    </div>
                    <div
                        v-show="showCsvUpload_confirmation"
                        class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mt-10 bg-white border border-gray-200 shadow-xl rounded-xl"
                        >
                        <div class="mb-6">
                            <p class="text-lg font-semibold text-center text-gray-800">Are you sure this CSV file is correct?</p>
                        </div>
                        <div class="flex space-x-6">
                            <button
                            @click="csv_submitFile"
                            class="px-6 py-2 font-bold text-white transition duration-200 bg-blue-500 rounded-lg shadow-md hover:bg-blue-600"
                            >
                            YES
                            </button>
                            <button
                            @click="csvUpload_cancel"
                            class="px-6 py-2 font-bold text-gray-700 transition duration-200 bg-gray-200 rounded-lg shadow-md hover:bg-gray-300"
                            >
                            NO
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Loading Indicator -->
                <DotsLoader v-if="showCsvLoading" />
                <p v-if="mias_factorCsvError" class="font-extrabold text-red-500 font-lg">Error: Data in the uploaded Property Data csv file does not exist in the Mias Factor Data list.</p>
                <div>
                    <div v-show="showProceed3" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold text-white animate-pulse">ALL DATA HAS BEEN PROCESSED SUCCESSFULLY!</p>
                        <button
                            class="px-4 py-2 mt-4 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="showAllData"
                            >
                                Proceed ( Show Data )
                        </button>
                    </div>
                </div>
               <!-- Loading Indicator -->
                <DotsLoader v-if="layerTableRowLoading" />
            </div>
            <DotsLoader v-show="showLoadingForGraphAndTables" class="z-10"/>
            <div v-show="showGraphAndTables" class="z-10 flex flex-col">
                <div class="flex flex-row items-center justify-center">
                    <p class="px-4 py-2 text-xl text-white border-2 shadow-lg animate-pulse rounded-xl bg-gradient-to-br from-blue-600/80 to-blue-600/40 backdrop-blur-lg">Serial: {{ currentSerialSelected }} </p>
                    <p class="px-4 py-2 ml-10 text-xl text-white border-2 shadow-lg animate-pulse rounded-xl bg-gradient-to-br from-blue-600/80 to-blue-600/40 backdrop-blur-lg">Set #: {{ highest_setNo }} </p>
                </div>
                <div class="flex flex-row justify-center mt-5 space-x-4">
                    <div class="w-[600px] h-[520px] bg-blue-100 rounded-xl flex items-center pr-5 border-2 border-blue-900 justify-center">
                        <canvas ref="myChartCanvas" width="800" height="600" style="transform: scale(1); transform-origin: top left;"></canvas>
                    </div>
                    <!-- Side Content -->
                    <div class="w-[350px] h-[420px] bg-blue-200 rounded-xl border-2 border-blue-900 flex justify-center items-start p-4">
                        <div class="flex flex-col items-start space-y-2">
                            <p>
                                SMP Lot (
                                <span>{{ currentMassProdSelected }}</span>
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
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex flex-row items-start justify-center">
                            <div class="flex flex-row shadow-2xl">
                                <div class="min-w-[700px]">
                                    <table class="border border-gray-300 table-auto rounded-xl">
                                        <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                                            <tr>
                                                <th v-for="tableLayerColumnHeader in tableLayerColumnHeaders"
                                                    :key="tableLayerColumnHeader.name"
                                                    :colspan="tableLayerColumnHeader.colspan"
                                                    class="px-[4.8px] py-[3px] text-sm font-medium text-center border border-white">
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
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center mt-10">
                    <button @click="Inertia.reload()" class="flex items-center gap-2 px-4 py-2 font-semibold text-white transition duration-300 bg-blue-600 rounded shadow-md hover:bg-blue-700">
                        <span>UPLOAD AGAIN</span>
                        <span class="w-[16px] h-[15px] inline-block bg-no-repeat bg-center"
                            :style="{
                                backgroundImage: 'url(\'/photo/repeat_upload.png\')',
                                backgroundSize: 'contain'
                            }"
                        ></span>
                    </button>
                    <button @click="$inertia.visit('/reports')" class="flex items-center gap-2 px-4 py-2 ml-10 font-semibold text-white transition duration-300 rounded shadow-md bg-cyan-600 hover:bg-cyan-700">
                        <span>GO TO REPORT</span>
                        <span class="w-[16px] h-[15px] inline-block bg-no-repeat bg-center"
                            :style="{
                                backgroundImage: 'url(\'/photo/gotoreport.png\')',
                                backgroundSize: 'contain'
                            }"
                        ></span>
                    </button>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, nextTick } from 'vue';
import { Chart, registerables } from 'chart.js'; // Import all required components
import { Inertia } from '@inertiajs/inertia';
import Papa from 'papaparse';
import axios from 'axios';
import DotsLoader from '@/Components/DotsLoader.vue';
import { useAuth } from '@/Composables/useAuth.js';
import { useToast } from 'vue-toast-notification';

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

        return true; // Indicate authenticated
    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/'); // Redirect on error
        return false; // Indicate not authenticated
    }
};
// Register all Chart.js components using registerables
Chart.register(...registerables);

    const userManageLogging = async (logEvent) => {
        try{
            const responseUserLogging = await axios.post('/api/userlogs', {
                user: state.user.firstName + " " + state.user.surname,
                event: logEvent,
                section: 'SEC Additional',
            });

            //console.log('responseUserLogin-data: ',responseUserLogin.data);
        }catch(error){
            console.error('userManageLogging post request failed: ',error);
        }
    }

    const userErrorLogging = async (details, triggerFunction, title) => {
        try{
            const response = await axios.post('/api/error-logs', {
                user: state.user.firstName + " " + state.user.surname,
                title: title,
                details: details,
                trigger_function: triggerFunction,
                section: 'SEC Additional',
            });

            //console.log('userErrorLogging-data: ',responseUserLogin.data);
        }catch(error){
            console.error('userErrorLogging post request failed: ',error);
        }
    }


// UI Control Variables
const showSerialNo = ref(true);
const showMiasFactorEmp = ref(false);
const showUploadTPMFiles = ref(true);
const toggleManageForm = ref(true);
const showUploadData = ref(true);
const showProceed1 = ref(false);
const showProceed2 = ref(false);
const showProceed3 = ref(false);
const showLoadingForProceed1 = ref(false);
const csvUpload = ref(false);
const layerTableRowLoading = ref(false);
const showGraphAndTables = ref(false);
const showNoFileSelectedError = ref(false);
const showSaveToDatabase_confirmation = ref(false);
const showNoModelError = ref(false);
const showProceedToCsvUpload_confirmation = ref(false);
const showNoCsvFileSelectedError = ref(false);
const showCsvUpload_confirmation = ref(false);
const showLoadingForGraphAndTables = ref(false);
const showNoRemarksError = ref(false);
const showCsvLoading = ref(false);
const showIncompleteInformationError = ref(false);
const finishProceed_showConfirm = ref(false);

// UI Control Variables end

const nsa_FactorEmp = ref("");
const nsa_MiasEmp = ref("");

const saveToDatabase_showConfirm = () => {
        if (!fileData.value || fileData.value.length === 0) {
            console.error("No file selected! fileData is empty or null.");

            showNoFileSelectedError.value = true;
            showUploadTPMFiles.value = false;
            showSerialNo.value = false;

            setTimeout(() => {
                showNoFileSelectedError.value = false;
                showUploadTPMFiles.value = true;
                showSerialNo.value = true;
            }, 2000);

            return; // Exit the function if fileData is null or empty
        } else {
            showSaveToDatabase_confirmation.value = true;
            showUploadTPMFiles.value = false;
            showSerialNo.value = false;
        }
    }

// Upload file related variables

const fileLists = ref([]);
const fileData = ref([]); // This will hold the selected file data
const rowCell = ref([]);

// Upload file related variables end

//General variables
const mias_factorCsvError = ref(false);
const currentSerialSelected = ref('');
const currentMassProdSelected = ref('');
const currentLayerSelected = ref('');
const currentFurnaceSelected = ref('');
const dataReady = ref(false); // Flag to track if data is ready
const myChartCanvas = ref(null); // Ref for the canvas

const mias_factorData = async (factor, mias) => {
        const response = await axios.get('/api/mias-factor');
        const miasFactorData = response.data.data;
        console.log("MIAS Factor Data:", miasFactorData);

        const findByEmpOrMiasNo = (searchValue) => {
            return (
                miasFactorData.find(item => item.employee_no === searchValue) ||
                miasFactorData.find(item => item.mias_no === searchValue)
            );
        };

        const factorMatch = findByEmpOrMiasNo(factor);
        if (factorMatch) {
            nsa_FactorEmp.value = factorMatch.employee_name;
        } else {
            mias_factorCsvError.value = true;
            throw new Error(`Factor ID "${factor}" not found in either employee_no or mias_no fields.`);
        }

        const miasMatch = findByEmpOrMiasNo(mias);
        if (miasMatch) {
            nsa_MiasEmp.value = miasMatch.employee_name;
        } else {
            mias_factorCsvError.value = true;
            throw new Error(`MIAS ID "${mias}" not found in either employee_no or mias_no fields.`);
        }
    };

// Define the prop that will receive the serialParam
const props = defineProps({
    sec_serialParam: String,  // Expecting the serialParam to be a string
    sec_massProd: String,
    sec_layer: String,
    sec_furnace: String
});

currentSerialSelected.value = props.sec_serialParam;
currentLayerSelected.value = props.sec_layer;
currentMassProdSelected.value = props.sec_massProd;
currentFurnaceSelected.value = props.sec_furnace;
//console.log('Serial Param check: ',currentSerialSelected.value);
//console.log('Mass Prod Param check: ',currentMassProdSelected.value);
//console.log('Layer Param check: ',currentLayerSelected.value);

const fetchLayerModelAndLotno = async () => {
    console.log(
        'Entering fetchLayerModelAndLotno function | MassProd: ',
        currentMassProdSelected.value,
        'Furnace: ',
        currentFurnaceSelected.value,
        ' Layer: ',
        currentLayerSelected.value
    );

    if (!currentMassProdSelected.value || !currentFurnaceSelected.value || !currentLayerSelected.value) {
        console.warn("MassProd or Layer not selected yet.");
        return;
    }

    try {
        const [responseModel, responseLotno] = await Promise.all([
            axios.get('/api/mass-production/get-layer-model', {
                params: {
                    furnace: currentFurnaceSelected.value,
                    massprod: currentMassProdSelected.value,
                    layer: currentLayerSelected.value,
                }
            }),
            axios.get('/api/mass-production/get-layer-lotno', {
                params: {
                    furnace: currentFurnaceSelected.value,
                    massprod: currentMassProdSelected.value,
                    layer: currentLayerSelected.value,
                }
            })
        ]);

        const model = responseModel.data.model;
        const lotno = responseLotno.data.lotno;

        jhCurveActualModel.value = model;
        jhCurveLotNo.value = lotno;

        return { model, lotno };

    } catch (error) {
        console.error("Error fetching layer model or lotno:", error);
        toast.error('Failed to fetch layer data.');

        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "fetchLayerModelAndLotno",
            "Error fetching layer model/lotno"
        );
    }
};

// Store the file list when the input changes
const storeFileList = (event) => {
    fileData.value = Array.from(event.target.files);
    fileLists.value = fileData.value.map(file => file.name); // Extract and store file names
    //console.log('Files stored:', fileData.value);
};

 // Method to clear the file upload
 const clearFileUpload = () => {
    fileData.value = null; // Reset the file data
    fileLists.value = [];
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) fileInput.value = ''; // Clear the input field
    //console.log('File upload cleared');
    };

    const saveToDatabase_cancel = () => {
        showSaveToDatabase_confirmation.value = false;
        showUploadTPMFiles.value = true;
        showSerialNo.value = true;
        clearFileUpload();
    }

    const proceedToCsvUpload_showConfirm = () => {
        if(additional_remarks.value == null || additional_remarks.value == ''){
            showNoRemarksError.value = true;
            showProceed1.value = false;
            setTimeout(() => {
                showNoRemarksError.value = false;
                showProceed1.value = true;
            }, 2000);
            return;
        }else{
            showProceedToCsvUpload_confirmation.value = true;
            showProceed1.value = false;
        }
    }

    const proceedToCsvUpload = () => {
        csvUpload.value = true;
        showProceed1.value = false;
        showProceedToCsvUpload_confirmation.value = false;
    }

    const proceedToCsvUpload_cancel = () => {
        showProceedToCsvUpload_confirmation.value = false;
        showProceed1.value = true;
    }

    const csvUpload_showConfirm = () => {
        if (!csv_selectedFile.value) {
            //alert('Please select a CSV file first.')
            showSerialNo.value = false;
            csvUpload.value = false;
            showNoCsvFileSelectedError.value = true;
            setTimeout(() => {
                //showCsv.value = false;
                showSerialNo.value = true;
                csvUpload.value = true;
                showNoCsvFileSelectedError.value = false;
            }, 2000);
            return;
        }else{
            showCsvUpload_confirmation.value = true;
            showSerialNo.value = false;
            csvUpload.value = false;
        }
    }

    const csvUpload_cancel = () => {
        showCsvUpload_confirmation.value = false;
        csvUpload.value = true;
        csv_clearFile();
    }



    const additional_remarks = ref("");

    //tpmdata category in database
    const jhCurveModel = ref("");
    const jhCurveMassProdName = ref("");
    const jhCurveActualModel = ref("");
    const jhCurveFurnaceName = ref("");
    const jhCurveLotNo = ref("");

    const propData_miasEmp = ref("");
    const propData_factorEmp = ref("");

    const csv_selectedFile = ref(null)
    const csv_parsedData = ref([])
    const csv_tempWithDataStat = ref([]);

    const csv_handleFileSelect = (event) => {
        csv_selectedFile.value = event.target.files[0]
    }

    const csv_submitFile = async () => {
        csvUpload.value = false;
        showCsvLoading.value = true;
        showCsvUpload_confirmation.value = false;

        try {
            const results = await new Promise((resolve, reject) => {
                Papa.parse(csv_selectedFile.value, {
                    header: true,
                    skipEmptyLines: true,
                    complete: resolve,
                    error: reject,
                });
            });

            csv_parsedData.value = results.data;
            const rows = results.data;

            csv_tempWithDataStat.value = rows.map(row => ({
                temp: row["Temperature"],
                status: row["Data class"],
                employee_no: row["Meas. Employee"],
                mias_no: row["Factor Employee"],
            }));

            for (const row of csv_tempWithDataStat.value) {
                // Defensive cleanup with leading zero strip
                const factor = row.employee_no
                    ? row.employee_no.toString().trim().replace(/^0+/, '') || '0'
                    : null;
                const mias = row.mias_no
                    ? row.mias_no.toString().trim().replace(/^0+/, '') || '0'
                    : null;

                // Skip rows that are completely empty
                if (!factor && !mias && !row.temp && !row.status) {
                    console.warn('Skipping completely empty row:', row);
                    continue;
                }

                console.log('Raw row data:', row);
                console.log('Cleaned factor:', factor, 'Cleaned mias:', mias);

                if (factor && mias) {
                    console.log('Valid row - Processing factor:', factor, 'and mias:', mias);
                    await mias_factorData(factor, mias);
                } else {
                    console.warn('Missing factor or mias in cleaned row:', row);
                    return;
                }
            }
            await mergeTempToTPM();

        } catch (err) {
            console.error('Error during CSV processing:', err);
        }
    };

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
        try { // tempDataClass contains the value to be updated
            const responseTPM = await axios.get("/api/nsadata");
            const nsaData = responseTPM.data.data["NSAData"] || [];

            const nsafilteredData = nsaData.filter(item => item.serial_no == currentSerialSelected.value);
            const nsafilteredData2 = nsafilteredData.filter(item => item.set_no == highest_setNo.value);
            const getAllID = nsafilteredData2.map(item => item.id);

            // Create array of PATCH promises
            const patchPromises = getAllID.map((id, i) => {
            return axios.patch(`/api/nsadataupdate/${id}`, {
                temperature: csv_tempWithDataStat.value[i]?.temp || null,
                data_status: csv_tempWithDataStat.value[i]?.status || null,
                set_name: additional_remarks.value || null
            }).catch(error => {
                console.error(`Error patching ID ${id}:`, error.response?.data || error.message);
                return null; // Continue with others even if one fails
            });
            });

            // Wait for all PATCH requests to finish
            const patchResults = await Promise.all(patchPromises);
            // Optionally log patchResults or process them if needed
           // console.log('All patch results:', patchResults);

        } catch (error) {
           // console.log("Merge failed: ", error);
        } finally {
            await fetchLayerModelAndLotno();
            await saveToNsaCategory();
            showCsvLoading.value = false;
            showProceed3.value = true;
        }
    }

    //New Furnace , New Layers end

    //table main layer header dynamic
    const tableLayerColumnHeaders = ref([
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
        //table main layer header dynamic end

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
   // console.log('Sorted Files:', fileData.value);

    layerTableRowLoading.value = true;
    showSaveToDatabase_confirmation.value = false;
    try{
        const responseCheckNsa = await axios.get("/api/nsadata");
        //console.log('response check NSA: ',responseCheckNsa.data.data);
        const nsaData = responseCheckNsa.data.data["NSAData"] || [];
        console.log('tpm data: ',nsaData);
        const nsafilteredData = nsaData.filter(item => item.serial_no == currentSerialSelected.value);
        console.log('tpm data filtered by serial: ',nsafilteredData);

        if(nsafilteredData.length > 0) {
            const maxSetNo = Math.max(...nsafilteredData.map(item => item.set_no));
            highest_setNo.value = maxSetNo + 1;
        } else {
            console.warn('No matching NSA data for this serial number.');
        }
    }catch(error){
        console.warn("404 No data detected or another error check here -> ",error)
    }


    //console.log('Current set_no value: ',highest_setNo.value);

        const filePromises = fileData.value.map((file) => {
            return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = async () => {
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
                //console.log("ADDITIONAL REMARKS: ",additional_remarks.value);

                const layerData = {
                    "date": formattedDate,
                    "serial_no": currentSerialSelected.value,
                    "furnace": currentFurnaceSelected.value,
                    "mass_prod": currentMassProdSelected.value,
                    "layer_no": currentLayerSelected.value,
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

                await sendLayerData(layerData);
                resolve(); // Finish promise
            };

            reader.onerror = () => {
                console.error('Error reading file:', file.name);
                reject(reader.error);
            };

            reader.readAsText(file); // Read the file as text
        });
    });

    try {
        await Promise.all(filePromises);
        showProceed1.value = true;
    } catch (e) {
        console.error("One or more files failed to upload." , e);
    } finally {
        showUploadData.value = false;
        layerTableRowLoading.value = false;
    }
};


// Function to send raw data via API
const sendLayerData = async (layerData) => {
    try {
        //console.log("Sending layer TO API: ",layerData);
        const response = await axios.post('/api/nsadata', layerData); // Replace '/api/endpoint' with your API endpoint
        console.log('API Response sendlayerdata:', response.data);

    } catch (error) {
        console.error('Error sending data to API:', error.response?.data || error.message);
        return;
    } finally {
        showUploadData.value = false;
        showSaveToDatabase_confirmation.value = false;
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
const nsaData = ref([]); // Holds the nsaData array
const nsaData_aggID = ref(null);
const filteredAggregateID = ref([]);
const filteredNSAData = ref([]);
const tpmRemarks = ref([]); // Holds the remarks array
const tpmAggregateAve = ref([]); // Holds the aggregateFunctions array
const tpmAggregateMax = ref([]); // Holds the aggregateFunctions array
const tpmAggregateMin = ref([]); // Holds the aggregateFunctions array
const combinedData = ref([]); // Holds the combined array
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

// Variables for aggregate end
const tpmCatData = ref([]);
const checkTPMCategory = async () => {
    try{
        const responseTPMCAT = await axios.get("/api/tpmdata?serial=" + currentSerialSelected.value); // Adjust this URL to your API endpoint
        //console.log('checkTPMCategory - API Response showallData:', responseTPMCAT.data);
        tpmCatData.value = responseTPMCAT.data.data;

        const tpm_category_actualmodel = tpmCatData.value.map(item => item.category?.actual_model ?? null);
        //console.log('tpm_category_actualmodel:', tpm_category_actualmodel);
        //jhCurveActualModel.value = tpm_category_actualmodel[0];
        //console.log('jhCurveActualModel:', jhCurveActualModel.value);

        const tpm_category_factorEmp = tpmCatData.value.map(item => item.category?.factor_emp ?? null);
        //console.log('tpm_category_factorEmp:', tpm_category_factorEmp);
        propData_factorEmp.value = tpm_category_factorEmp[0];
        //console.log('propData_factorEmp:', propData_factorEmp.value);

        const tpm_category_miasEmp = tpmCatData.value.map(item => item.category?.mias_emp ?? null);
        //console.log('tpm_category_miasEmp:', tpm_category_miasEmp);
        propData_miasEmp.value = tpm_category_miasEmp[0];
        //console.log('propData_miasEmp:', propData_miasEmp.value);

        const tpm_category_jhCurveLotno = tpmCatData.value.map(item => item.category?.jhcurve_lotno ?? null);
        //console.log('tpm_category_jhCurveLotno:', tpm_category_jhCurveLotno);
        //jhCurveLotNo.value = tpm_category_jhCurveLotno[0];
        //console.log('jhCurveLotNo:', jhCurveLotNo.value);

        const tpm_category_massProdName = tpmCatData.value.map(item => item.category?.massprod_name ?? null);
        //console.log('tpm_category_massProdName:', tpm_category_massProdName);
        //jhCurveMassProdName.value = tpm_category_massProdName[0];
        //console.log('jhCurveMassProdName:', jhCurveMassProdName.value);

        const responsePatchCategory = await axios.patch(`/api/nsaupdatecategory/${currentSerialSelected.value}`,{
            actual_model: jhCurveActualModel.value,
            jhcurve_lotno: jhCurveLotNo.value,
            massprod_name: currentMassProdSelected.value,
        });

        //console.log("API PATCHED category: ",responsePatchCategory);
    }catch(error){
        console.error("Error fetching for checkTPMCategory-data: ",error);
    }
}

const saveToNsaCategory = async () => {
    try{

        const responseTPMCAT = await axios.get("/api/tpmdata?serial=" + currentSerialSelected.value); // Adjust this URL to your API endpoint
        console.log('saveToNsaCategory - API Response responseTPMCAT:', responseTPMCAT.data);
        tpmCatData.value = responseTPMCAT.data.data;

        const tpm_category_actualmodel = tpmCatData.value.map(item => item.category?.actual_model ?? null);
        //console.log('tpm_category_actualmodel:', tpm_category_actualmodel);
        //jhCurveActualModel.value = tpm_category_actualmodel[0];
        //console.log('jhCurveActualModel:', jhCurveActualModel.value);

        const tpm_category_jhCurveLotno = tpmCatData.value.map(item => item.category?.jhcurve_lotno ?? null);
        //console.log('tpm_category_jhCurveLotno:', tpm_category_jhCurveLotno);
        //jhCurveLotNo.value = tpm_category_jhCurveLotno[0];
        //console.log('jhCurveLotNo:', jhCurveLotNo.value);

        const tpm_category_massProdName = tpmCatData.value.map(item => item.category?.massprod_name ?? null);
        //console.log('tpm_category_massProdName:', tpm_category_massProdName);
        //jhCurveMassProdName.value = tpm_category_massProdName[0];
        //console.log('jhCurveMassProdName:', jhCurveMassProdName.value);

        const responsePatchCategory = await axios.patch(`/api/nsaupdatecategory/${currentSerialSelected.value}`,{
                mias_emp: nsa_MiasEmp.value,
                factor_emp: nsa_FactorEmp.value,
                actual_model: jhCurveActualModel.value,
                jhcurve_lotno: jhCurveLotNo.value,
                massprod_name: currentMassProdSelected.value,
            });
        console.log("API PATCHED category: ",responsePatchCategory);

        const responsePatchNSAData = await axios.patch(`/api/nsadataupdatemiasfactor/`, {
            serial_no: currentSerialSelected.value,
            set_no: highest_setNo.value,
            factor_emp: nsa_FactorEmp.value,
            mias_emp: nsa_MiasEmp.value,
        });
        console.log("API PATCHED NSA Data: ",responsePatchNSAData);

    }catch(error){
        console.error("Error fetching API Response saveToNsaCategory:", error);
    }
}

    // Function to fetch data from the API
    const showAllData = async () => {
        showLoadingForGraphAndTables.value = true;
        layerTableRowLoading.value = true;
        showProceed3.value = false;
        toggleManageForm.value = false;

        try {
            showProceed3.value = false;
            toggleManageForm.value = false;

            // Fetch data from API
            const responseNSARemarks = await axios.get(
                "/api/nsadata?serial=" + currentSerialSelected.value + "&set=" + highest_setNo.value
            );

            // Ensure the structure matches the controller
            const responseData = responseNSARemarks.data.data || {};

            // Assign NSAData safely
            nsaData.value = responseData.NSAData || [];

            // Assign aggregateFunctions safely
            const aggregateFunctionsArray = responseData.aggregateFunctions || [];

            // Filter aggregate functions for current serial and set
            const filteredAggregateID_first = aggregateFunctionsArray.filter(
                item => item.nsa_serial === Number(currentSerialSelected.value)
            );
            filteredAggregateID.value = filteredAggregateID_first.filter(
                item => item.nsa_set === highest_setNo.value
            );

            console.log(typeof aggregateFunctionsArray[0].nsa_serial, typeof currentSerialSelected.value);
            console.log(typeof aggregateFunctionsArray[0].nsa_set, typeof highest_setNo.value);

            // Safe access to aggregate ID
            nsaData_aggID.value = filteredAggregateID.value[0]?.id || null;

            await checkTPMCategory();

            // Combine the arrays
            combinedData.value = nsaData.value;
            //console.log('Combined Data: ', combinedData.value);

            jhCurveFurnaceName.value = nsaData.value[0].sintering_furnace_no || "No data found";
            jhCurveModel.value = nsaData.value[0].code_no || "No data found";

            // Extract individual values from nsaData for aggregate
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
            console.log('Aggregate ID for patch:', nsaData_aggID.value)
            await sendAggData(aggregateData, nsaData_aggID.value);

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
            toggleManageForm.value = false;
        }
        await fetchDataCreateGraph();
    };

    // Function to send raw data via API
    const sendAggData = async (aggData, id) => {
        try {
            const response = await axios.patch(`/api/nsaaggregateupdate/${id}`, aggData); // Proper string interpolation for URL
            //console.log('API Response sendAggData:', response.data);
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
        const response = await axios.get("/api/nsadata");

        // Log the response structure to check
        //console.log("API Response:", response.data);
        const nsaData = response.data.data["NSAData"] || [];
        //console.log('tpm data: ',nsaData);
        const nsafilteredData = nsaData.filter(item => item.serial_no == currentSerialSelected.value);
        //console.log('tpm data filtered by serial: ',nsafilteredData);
        const tableRows = nsafilteredData.filter(item => item.set_no == highest_setNo.value);
        //console.log('tpm data filtered by set_no and serial: ',tableRows);

        const allData = tableRows;

        const compareZone = (a, b) => { //Sorting ensures the plotted lines in graph is in order (proper sequence based on zone column)
            const parse = (zone) => {
                const match = zone.match(/^(\d+)([A-Z]+)(\d+)$/i);
                if (!match) return { n1: 0, s: zone, n2: 0 };

                return {
                    n1: parseInt(match[1], 10),   // first number
                    s: match[2],                  // letters
                    n2: parseInt(match[3], 10),   // last number
                };
            };

            const A = parse(a.zone);
            const B = parse(b.zone);

            // 1) compare first number
            if (A.n1 !== B.n1) return A.n1 - B.n1;

            // 2) compare letters
            if (A.s !== B.s) return A.s.localeCompare(B.s);

            // 3) compare last number
            return A.n2 - B.n2;
        };

        if (Array.isArray(allData) && allData.length > 0) {
            // Step 1: Find the highest set_no
            const maxSetNo = Math.max(...allData.map(item => item.set_no));
            // Step 2: Filter the data to only those with the highest set_no
            filteredNSADataForGraph.value = allData
                .filter(item => item.set_no === maxSetNo)
                .sort(compareZone);
            //console.log('Filtered data with highest set_no:', filteredNSADataForGraph.value);
        } else {
            //console.log('No data found or data is not an array.');
        }

        // Check if tableRows is not undefined or null before proceeding
        if (!filteredNSADataForGraph.value) {
            throw new Error("No data found in nsaData");
        }

        // Parse each row and dynamically generate datasets
        datasets.value = filteredNSADataForGraph.value.map((row, index) => ({
            xAxis: JSON.parse(row.x || "[]"), // Parse x values
            yAxis: JSON.parse(row.y || "[]"), // Parse y values
            color: generateColor(index), // Assign a unique color
        }));

        // Set dataReady to true once the data is ready
        dataReady.value = true;
        //console.log("dataReady ", dataReady.value);
        // Ensure DOM updates before rendering
        nextTick(() => {
            //console.log("myChartCanvas reference:", myChartCanvas.value);
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
    } finally{
        showGraphAndTables.value = true;  // Set this to true after data is loaded
        showLoadingForGraphAndTables.value = false;
        await autoDeleteNullData();
    }
};

// Generate a unique color for each dataset
const generateColor = (index) => {
    const colors = [
        "#0d9ae0", // deep blue
        "#a543de", // violet
        "#82bd62", // green
        "#564094", // purple
        "#5cd1ac", // green
        "#bf49d1", // brown
        "#2da5eb", // blue
        "#b469f5", // purple
        "#5cd1ac", // green
        "#946ff7", // purple
        "#393B79", // navy
        "#637939", // olive
        "#8C6D31", // ochre
        "#843C39", // brick
        "#7B4173", // plum
        "#3182BD", // steel blue
        "#E6550D", // burnt orange
        "#31A354", // vivid green
        "#756BB1", // violet
        "#636363"  // dark gray
    ];
    return colors[index % colors.length];
};


const renderChart = () => {
    if (!myChartCanvas.value) {
        console.error("Canvas element is not available.");
        return;
    }

    if (!dataReady.value) {
        console.error("Chart cannot be rendered: Data not ready.");
        return;
    }

    const ctx = myChartCanvas.value.getContext("2d");
    if (!ctx) {
        console.error("Failed to get 2D context for canvas.");
        return;
    }

    const x_offset = 2000;
    const y_offset = 1700;

    const chartDatasets = datasets.value.map((dataset, index) => ({
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
    }));

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
                        display: false,
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
                        grace: "5%",
                        grid: { color: "rgba(0, 0, 0, 0.1)" },
                        title: {
                            display: true,
                            text: "←  kOe  →",
                            color: "#333",
                            font: { size: 14, weight: "bold" },
                        },
                        ticks: { stepSize: 1000, display: false },
                    },
                    y: {
                        type: "linear",
                        position: "left",
                        grace: "5%",
                        grid: { color: "rgba(0, 0, 0, 0.1)" },
                        title: {
                            display: true,
                            text: "←  kG  →",
                            color: "#333",
                            font: { size: 14, weight: "bold" },
                        },
                        ticks: { stepSize: 1750, display: false },
                    },
                },
            },
        });

        setTimeout(() => {
            const canvas = myChartCanvas.value;
            const imageData = canvas.toDataURL("image/png");

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            fetch("/upload-chart-sec", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken || "",
                },
                body: JSON.stringify({
                    image: imageData,
                    filename: `chart_${currentSerialSelected.value}_set${highest_setNo.value}.png`
                }),
            })
            .then(response => response.json())
            .then(data => {
                // File saved successfully
            })
            .catch(err => console.error("Chart upload failed:", err));
        }, 1000); // Wait for chart to render fully
    } catch (error) {
        console.error("Error initializing Chart.js:", error);
        userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "fetchDataCreateGraph",
            "Error fetching graph data"
        );
    }
};

const autoDeleteNullData = async () => {
    //console.log('[autoDeleteNullData] Starting...');
    try {
        const responseGetNSA = await axios.get(`/api/nsadata`);
        const nsadata = responseGetNSA.data.data["NSAData"] || [];
        //console.log('[autoDeleteNullData] Raw API data:', nsadata);
        //console.log('[autoDeleteNullData] Total rows fetched:', nsadata.length);
        const nsaOverallData = responseGetNSA.data;
        //console.log('[autoDeleteNullData] Overall data fetched:', nsaOverallData);

        const nsaForDelete_IDs = [];

        nsadata.forEach((row, idx) => {
            const { id, layer_no, ...otherFields } = row;
            const nullFields = Object.keys(otherFields).filter(key => otherFields[key] === null);

            if (nullFields.length > 0) {
                nsaForDelete_IDs.push(id);
                console.log(`[Row ${idx}] ID ${id} marked for deletion. Null fields:`, nullFields);
            }
        });

        //console.log('[autoDeleteNullData] IDs marked for deletion:', nsaForDelete_IDs);

        for (const id of nsaForDelete_IDs) {
            try {
                const res = await axios.delete(`/api/nsadata/${id}`);
                console.log(`[DELETE] ID ${id} successful:`, res.data);
            } catch (err) {
                console.error(`[DELETE] ID ${id} failed:`, err.response?.data || err.message);
            }
        }

        await deleteNullOnNsaAggregate();
        await deleteNullOnNsaCategory();

    } catch (error) {
        console.error('[autoDeleteNullData] Error deleting null data:', error.response?.data || error.message);
    }
};

const deleteNullOnNsaAggregate = async () => {
    try {
        const response = await axios.get(`/api/nsadata`);
        const nsaAggregateData = response.data.data["aggregateFunctions"] || [];
        //console.log('[deleteNullOnNsaAggregate] Fetched aggregate data:', nsaAggregateData);

        const idsToDelete = nsaAggregateData
            .filter(({ average, maximum, minimum, ng_counter }) =>
                average == null || maximum == null || minimum == null || ng_counter == null
            )
            .map(({ id }) => id);

        await Promise.all(
            idsToDelete.map(async id => {
                try {
                    await axios.delete(`/api/nsaaggregate/${id}`);
                    console.log(`[DELETE] Deleted aggregate with ID ${id}`);
                } catch (err) {
                    console.error(`[DELETE ERROR] ID ${id}:`, err.response?.data || err.message);
                }
            })
        );
    } catch (err) {
        console.error('[deleteNullOnNsaAggregate] Failed to fetch or delete:', err.response?.data || err.message);
    }
};

const deleteNullOnNsaCategory = async () => {
    try {
        const response = await axios.get(`/api/nsadata`);
        const nsaCategoryData = response.data.data["nsaCategory"] || [];
        //console.log('[deleteNullOnNsaCategory] Fetched category data:', nsaCategoryData);

        const requiredFields = [
            'actual_model',
            'factor_emp',
            'jhcurve_lotno',
            'massprod_name',
            'mias_emp',
            'nsa_serial',
        ];

        const idsToDelete = nsaCategoryData
            .filter(row => {
                return requiredFields.some(field => row[field] == null);
            })
            .map(({ id }) => id)
            .filter(id => id != null); // extra safety

        await Promise.all(
            idsToDelete.map(async id => {
                try {
                    await axios.delete(`/api/nsacategory/${id}`);
                    console.log(`[DELETE] Deleted category with ID ${id}`);
                } catch (err) {
                    console.error(`[DELETE ERROR] ID ${id}:`, err.response?.data || err.message);
                }
            })
        );
    } catch (err) {
        console.error('[deleteNullOnNsaCategory] Failed to fetch or delete:', err.response?.data || err.message);
    }
};

onMounted(async() => {
    //await testNSA();
    await autoDeleteNullData();
    await checkAuthentication();
});

</script>
