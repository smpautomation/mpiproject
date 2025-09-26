<template>
    <Frontend>
    <div class="relative flex flex-col items-center justify-center min-h-screen px-8 py-12 mx-auto bg-center bg-no-repeat bg-cover"
        :style="{
            backgroundImage: 'url(/photo/manage_background.jpg)',
            backgroundPosition: 'center center',
            backgroundRepeat: 'no-repeat',
            backgroundSize: 'cover'
        }">
        <!-- Overlay -->
        <div class="absolute inset-0 z-0 bg-black bg-opacity-50"></div>
            <div v-if="toggleManageForm" class="max-w-5xl p-8 mx-auto mb-8 bg-white/95 backdrop-blur-sm border border-teal-200/50 rounded-xl shadow-2xl">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-10">
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Mass Prod. Name <span class="text-red-500">*</span></label>
                        <select
                            v-model="selectedMassProd"
                            class="w-full px-4 py-3 text-sm font-medium text-gray-800 bg-white border border-teal-300 rounded-lg shadow-sm transition-all duration-200 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 hover:border-teal-400"
                        >
                            <option v-for="item in massProd_names" :key="item" :value="item">
                                {{ item }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Layer<span class="text-red-500"> *</span></label>
                        <select v-model="currentLayerNo" class="w-full px-4 py-3 text-sm font-medium text-gray-800 bg-white border border-teal-300 rounded-lg shadow-sm transition-all duration-200 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 hover:border-teal-400">
                            <option v-for="item in layers" :key="item" :value="item">
                                {{ item }}
                            </option>
                        </select>
                    </div>
                </div>
                <p v-if="showUploadTPMFiles" class="mb-6 text-xl font-semibold text-gray-800">Please select and upload TPM files:</p>
                <div v-show="showUploadData" class="flex flex-row items-center justify-center">
                    <div v-show="showUploadTPMFiles" class="max-w-lg mx-auto mb-12 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4">
                            <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Upload TPM Files</h3>
                                <p class="text-cyan-100 text-sm">Select your .tpm files for processing</p>
                            </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">

                            <!-- Drag & Drop Zone -->
                            <div class="relative">
                            <input
                                id="file-upload"
                                type="file"
                                accept=".tpm"
                                multiple
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                @change="storeFileList"
                            />

                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-teal-400 hover:bg-teal-50 transition-all duration-200 group">
                                <!-- Upload Icon -->
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                </div>

                                <!-- Upload Text -->
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                    Drop files here or click to browse
                                </h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    Select multiple .tpm files for upload
                                </p>

                                <!-- File Type Badge -->
                                <div class="inline-flex items-center px-3 py-1 rounded-full bg-teal-100 text-teal-800 text-xs font-medium">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15.586 13H14a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                    Only .TPM files
                                </div>
                            </div>
                            </div>

                            <!-- Selected Files Preview (if any files selected) -->
                            <!-- You can add this section to show selected files -->

                            <!-- Action Button -->
                            <div class="mt-6 flex justify-center">
                            <button
                                @click="clearFileUpload"
                                class="group px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-300"
                            >
                                <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 3a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                <span>Clear Files</span>
                                </span>
                            </button>
                            </div>

                            <!-- Help Text -->
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-start space-x-2">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                <p class="text-sm text-gray-700 font-medium mb-1">Upload Instructions:</p>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• Multiple file selection supported</li>
                                    <li>• Only .tpm file format accepted</li>
                                    <li>• Drag and drop or click to select</li>
                                </ul>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div v-show="showUploadTPMFiles" class="max-w-lg mx-auto mb-12 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden ml-10">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-cyan-600 to-teal-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd" />
                                </svg>
                                </div>
                                <div>
                                <h3 class="text-white font-semibold text-lg">Selected Files</h3>
                                <p class="text-cyan-100 text-sm">Preview of uploaded files</p>
                                </div>
                            </div>

                            <!-- File Count Badge -->
                            <div v-if="fileLists.length > 0" class="bg-white bg-opacity-20 px-3 py-1 rounded-full">
                                <span class="text-white text-sm font-semibold">{{ fileLists.length }} files</span>
                            </div>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="p-6">

                            <!-- Files List -->
                            <div v-if="fileLists.length > 0" class="h-48 overflow-auto">
                            <div class="space-y-3">
                                <div
                                v-for="(fileList, index) in fileLists"
                                :key="index"
                                class="group flex items-center justify-between p-3 bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 rounded-lg hover:from-teal-100 hover:to-cyan-100 transition-all duration-200"
                                >
                                <!-- File Icon and Name -->
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ fileList }}
                                    </p>
                                    <p class="text-xs text-gray-500">.tpm file</p>
                                    </div>
                                </div>

                                <!-- File Status -->
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-xs text-green-700 font-medium">Ready</span>
                                </div>
                                </div>
                            </div>
                            </div>

                            <!-- Empty State -->
                            <div v-else class="h-48 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v1.5h16V5a2 2 0 00-2-2H4zm14 4.5H2V14a2 2 0 002 2h12a2 2 0 002-2V7.5zM5 9a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <h4 class="text-lg font-semibold text-gray-900 mb-2">No Files Selected</h4>
                            <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                                Upload some .tpm files to see them listed here for processing.
                            </p>

                            <!-- Animated Indicator -->
                            <div class="mt-4 flex space-x-1">
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                            </div>
                            </div>

                            <!-- Summary Footer (when files exist) -->
                            <div v-if="fileLists.length > 0" class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Total files selected:</span>
                                <span class="font-semibold text-gray-900">{{ fileLists.length }} files</span>
                            </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div>
                    <div class="flex flex-col items-center justify-center max-w-lg p-8 mx-auto mb-12 bg-white border border-teal-200 rounded-xl shadow-xl">
                        <!-- Upload Section Title -->
                        <div class="flex items-center mb-6">
                            <div class="p-3 mr-3 bg-gradient-to-r from-teal-100 to-cyan-100 rounded-full">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Upload Property Data CSV
                            </h3>
                        </div>

                        <!-- File Input Section -->
                        <div class="flex flex-col items-center w-full space-y-4">
                            <!-- File Input Label -->
                            <label for="csv-file-upload" class="text-sm font-medium text-teal-700">Select a CSV file to upload:</label>

                            <!-- File Upload Input with Custom Styling -->
                            <div class="relative w-full">
                                <input
                                    id="csv-file-upload"
                                    type="file"
                                    accept=".csv"
                                    class="block w-full px-4 py-3 text-gray-700 bg-teal-50 border-2 border-teal-300 rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 hover:border-teal-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-teal-500 file:text-white file:cursor-pointer hover:file:bg-teal-600"
                                    @change="csv_handleFileSelect"
                                />
                            </div>

                            <!-- Clear Button -->
                            <div>
                                <button
                                    class="px-6 py-2 font-medium text-gray-600 bg-gray-100 border border-gray-300 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200"
                                    @click="csv_clearFile"
                                >
                                    Clear File
                                </button>
                            </div>

                            <!-- Help Text -->
                            <p class="mt-2 text-xs text-center text-gray-500">Only CSV files are supported</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-center">
                        <button @click="proceedToGraphConfirmation()" class="px-8 py-3 font-semibold text-white bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg shadow-md hover:from-teal-600 hover:to-cyan-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Generate Graph
                            </span>
                        </button>
                    </div>
                </div>
                <DotsLoader v-show="showCsvLoading" />
                <p v-if="mias_factorCsvError" class="font-extrabold text-red-500 font-lg">Error: Data in the uploaded Property Data csv file does not exist in the Mias Factor Data list.</p>
            </div>
            <DotsLoader v-show="showLoadingForGraphAndTables" class="z-10"/>
            <div v-show="showGraphAndTables" class="z-10 flex flex-col">
                <div class="flex flex-col items-center justify-center">
                    <p class="px-4 py-2 text-xl text-white border-2 shadow-lg animate-pulse rounded-xl bg-gradient-to-br from-cyan-900/80 to-green-100/40 backdrop-blur-lg">Note: You may now proceed to the <span class="text-2xl font-extrabold text-blue-400">Report</span> section and select <span class="text-2xl font-extrabold text-blue-400"> {{ serialNo }}</span></p>
                </div>
                <div class="flex flex-row justify-center mt-5 space-x-4">
                    <div class="w-[600px] h-[520px] bg-blue-100 rounded-xl flex items-center border-2 border-blue-900 justify-center">
                        <canvas ref="myChartCanvas" width="800" height="600" style="transform: scale(1); transform-origin: top left;"></canvas>
                    </div>
                    <!-- Side Content -->
                    <div class="w-[350px] h-[420px] bg-blue-200 rounded-xl border-2 border-blue-900 flex justify-center items-start p-4">
                        <div class="flex flex-col items-start space-y-2">
                            <p>
                                SMP Lot (
                                <span>{{ selectedMassProd }}</span>
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

                <div class="p-6 mt-4 mb-10 border-2 border-gray-500 rounded-lg shadow-lg bg-gray-50">
                    <div class="mb-4">
                        <p class="text-center">PROPERTY DATA</p>
                    </div>
                    <div class="flex flex-row justify-center">
                        <p class="mr-10">Mias. Employee: <span>{{ propData_miasEmp }}</span> </p>
                        <p>Factor Employee: <span>{{ propData_factorEmp }}</span></p>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex flex-row items-start justify-center">
                            <div class="flex flex-row flex-[3] mb-10 shadow-2xl">
                                <div class="min-w-[700px]">
                                    <table class="border border-gray-300 table-auto rounded-xl">
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
                                            <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.code_no }}</td>
                                            <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.order_no }}</td>
                                            <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.type }}</td>
                                            <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.press_1 }}</td>
                                            <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.press_2 }}</td>
                                            <td class="whitespace-nowrap px-[3px] py-[3px] text-[10px] text-center text-gray-700 border border-blue-500">{{ item.machine_no }}</td>
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

                        <div class="flex flex-row"> <!-- DIV 5 -->
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
                            <div class="flex flex-col items-center justify-center p-4 mb-4 ml-6 space-y-3 text-sm bg-blue-200 rounded-lg shadow-md"> <!-- DIV 6 -->

                                <!-- Header -->
                                <div>
                                    <p class="px-4 py-1 text-xs font-bold text-white bg-blue-900 rounded-md shadow">Data Results:</p>
                                </div>

                                <div class="w-[600px] h-[50px] px-4 py-2 bg-white rounded-2xl text-xs space-y-1">
                                    <p class="flex gap-1 font-bold whitespace-nowrap">
                                        <span
                                        v-for="(rejectOKNGlist, index) in rejectOKNG"
                                        :key="index"
                                        :class="adjustColor_rejectOKNG(rejectOKNGlist)"
                                        >
                                        {{ rejectOKNGlist }}
                                        </span>
                                    </p>
                                    <p class="whitespace-nowrap">
                                        <span :class="adjustColor_rejectInstructions">
                                        {{ rejectInstruction }}
                                        </span>
                                    </p>
                                </div>

                                <!-- Additional Info Row -->
                                <div class="flex items-stretch justify-center w-full space-x-2">

                                    <!-- iHc Remarks -->
                                    <div class="flex flex-col items-center justify-center w-1/2 px-4 py-2 text-center bg-white rounded-2xl">
                                    <p class="text-xs font-semibold">
                                        <span :class="adjustColor_rejectiHc">{{ rejectiHcRemarks }}</span>
                                    </p>
                                    <p class="text-sm font-bold">
                                        <span :class="adjustColor_iHcValue">{{ getHighestSampleVariance }}</span>
                                    </p>
                                    </div>

                                    <!-- Lot Remarks -->
                                    <div class="flex flex-wrap items-center justify-center w-1/2 px-4 py-2 text-xs font-bold bg-white rounded-2xl">
                                    <span
                                        v-for="(rejectLotRemarksList, index) in rejectLotRemarks"
                                        :key="index"
                                        :class="adjustColor_rejectLotRemarks(rejectLotRemarksList)"
                                    >
                                        {{ rejectLotRemarksList }}
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Modal :show="showGraphProceedConfirmation" @close="showGraphProceedConfirmation = false">
                <!-- Modal Content - goes directly in the slot -->
                <div class="relative">

                <!-- Header with Gradient -->
                <div class="relative px-6 py-6 bg-gradient-to-r from-cyan-600 via-teal-600 to-cyan-700">
                    <!-- Geometric Pattern Background -->
                    <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                        <pattern id="hexagon" width="12" height="12" patternUnits="userSpaceOnUse">
                            <polygon points="6,1 11,4.5 11,9.5 6,13 1,9.5 1,4.5" fill="none" stroke="currentColor" stroke-width="0.5"/>
                        </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#hexagon)" />
                    </svg>
                    </div>

                    <!-- Header Content -->
                    <div class="relative flex items-center justify-between">
                    <!-- Create Icon -->
                    <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-25 border border-white rounded-xl backdrop-blur-sm border-opacity-40">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Close Button -->
                    <button
                        @click="showGraphProceedConfirmation = false"
                        class="p-2 text-white transition-all duration-200 rounded-lg hover:text-gray-200 hover:bg-white hover:bg-opacity-20 hover:scale-110"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="px-6 py-6">

                    <!-- Title -->
                    <div class="mb-6 text-center">
                    <h3 class="flex items-center justify-center mb-2 space-x-2 text-xl font-bold text-gray-900">
                        <svg class="w-5 h-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Confirm Submission</span>
                    </h3>
                    <p class="text-sm leading-relaxed text-gray-600">
                        Are you sure? Please ensure all inputs are correct before proceeding.
                    </p>
                    </div>

                    <!-- Validation Checklist -->
                    <div class="p-4 mb-6 border rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-200">
                    <h4 class="flex items-center mb-3 space-x-2 text-sm font-semibold text-gray-800">
                        <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Validation Summary</span>
                    </h4>

                    <div class="space-y-2 text-xs text-gray-700">
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-cyan-500 rounded-full"></div>
                        <span>All required fields completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-teal-500 rounded-full"></div>
                        <span>Data validation passed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                        <div class="w-1.5 h-1.5 bg-cyan-500 rounded-full"></div>
                        <span>Ready for database insertion</span>
                        </div>
                    </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">

                    <!-- Cancel Button -->
                    <button
                        @click="showGraphProceedConfirmation = false"
                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 transform hover:scale-[0.98] active:scale-95 flex items-center justify-center space-x-2"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Cancel</span>
                    </button>

                    <!-- Confirm Button with Animation -->
                    <button
                        @click="finalizeGraph()"
                        class="group flex-1 px-4 py-3 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-700 hover:to-teal-700 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-cyan-300 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 relative overflow-hidden"
                    >
                        <!-- Shine effect -->
                        <div class="absolute inset-0 transition-transform transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-20 group-hover:translate-x-full duration-600"></div>

                        <span class="relative flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 transition-all duration-300 group-hover:rotate-90 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Submit Now</span>
                        </span>
                    </button>
                    </div>
                </div>

                <!-- Bottom accent line -->
                <div class="h-1 bg-gradient-to-r from-cyan-500 via-teal-400 to-teal-500"></div>
                </div>
            </Modal>
        </div>
    </Frontend>
</template>

<script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { ref, computed, onMounted, nextTick, watch } from 'vue';
    import { Chart, registerables } from 'chart.js'; // Import all required components
    import { Inertia } from '@inertiajs/inertia';
    import Papa from 'papaparse';
    import axios from 'axios';
    import Modal from '@/Components/Modal.vue';
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
                section: 'Manage',
            });

            //console.log('responseUserLogin-data: ',responseUserLogin.data);
        }catch(error){
            console.error('userManageLogging post request failed: ',error);
        }
    }

    //New Variables
    const selectedMassProd = ref();
    const massProd_names = ref([]);
    const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);
    const showModalSubmit = ref(false);
    const controlSheet_layerdata = ref();

    //UI VISIBILITY variables...
    const mias_factorCsvError = ref(false);
    const showGraphProceedConfirmation = ref(false);

    const showGraphAndTables = ref(false);
    const showLoadingForGraphAndTables = ref(false);
    const showUploadData = ref(true);
    const showUploadTPMFiles = ref(true);
    const showLoadingForProceed1 = ref(false);
    const showProceed1 = ref(false);
    const showProceed2 = ref(false);
    const showProceed3 = ref(false);
    const showProceed2_massprod = ref(false);
    const csvUpload = ref(false);
    const showAddNewDataLayer = ref(false);
    const currentFurnaceName = ref();
    const currentFurnaceNo = ref(null);
    const toggleManageForm = ref(true);
    const currentLayerNo = ref(null);
    const showProceedToCsvUpload_confirmation = ref(false);
    const showSaveToDatabase_confirmation = ref(false);
    const showNoFileSelectedError = ref(false);
    const showNoModelError = ref(false);
    const showSerialNo = ref(true);
    const showCsvUpload_confirmation = ref(false);
    const showNoCsvFileSelectedError = ref(false);
    const showProceed2_confirmation = ref(false);
    const showIncompleteInformationError = ref(false);
    const showCsvLoading = ref(false);
    const showProceed2_massprod_confirmation = ref(false);

    const dataReady = ref(false); // Flag to track if data is ready
    const myChartCanvas = ref(null); // Ref for the canvas

    //tpmdata category in database
    const jhCurveModel = ref("");
    const jhCurveMassProdName = ref("");
    const jhCurveActualModel = ref("");
    const jhCurveFurnaceName = ref("");
    const jhCurveLotNo = ref("");

    const propData_miasEmp = ref("");
    const propData_factorEmp = ref("");

    const proceedToGraphConfirmation = () => {
        if(!selectedMassProd.value){
            toast.error('Please select a Mass Production.');
            return;
        }else if(!currentLayerNo.value){
            toast.error('Please select layer');
        }else if(!fileData.value || fileData.value.length === 0){
            toast.error('No .tmp file(s) detected.');
            return;
        }else if(!csv_selectedFile.value){
            toast.error('No CSV file detected');
            return;
        }else{
            showGraphProceedConfirmation.value = true;
        }
    }

    // Data fetching zone ------ Data fetching zone

    const getMassProdLists = async () => {
        try {
            const response = await axios.get('/api/mass-production/');
            const massProdList = response.data;

            // Save the mass_prod names
            massProd_names.value = massProdList.map(item => item.mass_prod);

            // Dynamically get the correct layer column
            const columnKey = currentLayerNo.value === '9.5' ? 'layer_9_5' : `layer_${currentLayerNo.value}`;
            const layerData = massProdList.map(item => item[columnKey]);

            console.log(`Data for ${columnKey}:`, layerData);
        } catch (error) {
            console.error('Error fetching mass prod lists', error);
            toast.error('Failed to get the mass prod lists api error');
        }
    };

    const fetchControlSheetData = async () => {
        try {
            const response = await axios.get('/api/mass-production/');
            const massProdList = response.data;

            // Dynamically get the correct layer column
            const columnKey = currentLayerNo.value === '9.5' ? 'layer_9_5' : `layer_${currentLayerNo.value}`;
            controlSheet_layerdata.value = massProdList.map(item => item[columnKey]);

            console.log(`Data for ${columnKey}:`, controlSheet_layerdata.value);
        } catch (error) {
            console.error('Error fetching mass prod lists', error);
            toast.error('Failed to get the mass prod lists api error');
        }
    }

    // Watcher
    watch([selectedMassProd, currentLayerNo], async ([newProd, newLayer], [oldProd, oldLayer]) => {
        if (newProd && newLayer) {
            console.log(`Watcher triggered → MassProd: ${newProd}, Layer: ${newLayer}`);
            await fetchControlSheetData();
        }
    });


    // Data fetching zone ------ Data fetching zone END

    const mias_factorData = async (factor, mias) => {
        //console.log("🔄 Starting mias_factorData with:", { factor, mias });

        // Step 1: Fetch data
        const response = await axios.get('/api/mias-factor');
        const miasFactorData = response.data.data;
        //console.log("📥 API Response Data:", miasFactorData);

        // Helper function with logging
        const findByEmpOrMiasNo = (searchValue) => {
            //console.log(`🔍 Searching for: ${searchValue}`);
            const empMatch = miasFactorData.find(item => item.employee_no === searchValue);
            if (empMatch) {
                //console.log(`✅ Found match in employee_no:`, empMatch);
                return empMatch;
            }
            const miasMatch = miasFactorData.find(item => item.mias_no === searchValue);
            if (miasMatch) {
                //console.log(`✅ Found match in mias_no:`, miasMatch);
                return miasMatch;
            }
            //console.log(`❌ No match found for: ${searchValue}`);
            return null;
        };

        // Step 2: Factor check
        //console.log("⚡ Checking factor:", factor);
        const factorMatch = findByEmpOrMiasNo(factor);
        if (factorMatch) {
            propData_factorEmp.value = factorMatch.employee_name;
            //console.log("➡️ Assigned factor employee:", propData_factorEmp.value);
        } else {
            mias_factorCsvError.value = true;
            //console.error(`❌ Factor ID "${factor}" not found in either employee_no or mias_no fields.`);
            throw new Error(`Factor ID "${factor}" not found.`);
        }

        // Step 3: MIAS check
        console.log("⚡ Checking mias:", mias);
        const miasMatch = findByEmpOrMiasNo(mias);
        if (miasMatch) {
            propData_miasEmp.value = miasMatch.employee_name;
            //console.log("➡️ Assigned mias employee:", propData_miasEmp.value);
        } else {
            mias_factorCsvError.value = true;
            //console.error(`❌ MIAS ID "${mias}" not found in either employee_no or mias_no fields.`);
            throw new Error(`MIAS ID "${mias}" not found.`);
        }

        //console.log("✅ Completed mias_factorData successfully.");
    };

    const csv_selectedFile = ref(null);
    const csv_parsedData = ref([]);
    const csv_tempWithDataStat = ref([]);

    const csv_handleFileSelect = (event) => {
        csv_selectedFile.value = event.target.files[0];
    }

    //const finalizeDelay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    const finalizeGraph = async () => {
        toast.success('Generating graph...');

        // Step 1: Save to database
        await saveToDatabase();

        // Step 2: Submit file after DB save completes
        await csv_submitFile();

        // Step 3: Wait 1.5 seconds before showing all data
        //await finalizeDelay(1500);

        // Step 4: Finally show all data
        await showAllData();
    };

    const csv_submitFile = async () => {
        showCsvLoading.value = true;

        const parsedData = await new Promise((resolve, reject) => {
            Papa.parse(csv_selectedFile.value, {
                header: true,
                skipEmptyLines: true,
                complete: (results) => resolve(results.data),
                error: (err) => reject(err),
            });
        });

        csv_parsedData.value = parsedData;

        csv_tempWithDataStat.value = parsedData.map(row => ({
            temp: row["Temperature"],
            status: row["Data class"],
            employee_no: row["Meas. Employee"],
            mias_no: row["Factor Employee"],
        }));

        console.log('CSV Parsed Data:', csv_parsedData.value);
        console.log('Temp with Data class:', csv_tempWithDataStat.value);

        function cleanInteger(str) {
            if (typeof str !== 'string') return null;

            const trimmed = str.trim();

            if (!/^\d+$/.test(trimmed)) return null; // Only digits

            return parseInt(trimmed, 10).toString(); // Normalize: remove leading zeroes
        }

        for (const row of csv_tempWithDataStat.value) {
            const factor = cleanInteger(row.employee_no);
            const mias   = cleanInteger(row.mias_no);
            const temp   = row.temp;
            const status = row.status;

            const isEmptyRow = !factor && !mias && !temp && !status;
            if (isEmptyRow) {
                console.warn('Skipping completely empty row:', row);
                continue;
            }

            //console.log('Raw row data:', row);
            //console.log('Cleaned factor:', factor, 'Cleaned mias:', mias);

            if (factor && mias) {
                console.log('Valid row - Processing factor:', factor, 'and mias:', mias);
                await mias_factorData(factor, mias);
            } else {
                console.warn('Invalid or missing factor/mias:', row);
                continue; // Don't crash the loop, just skip
            }
        }

        await mergeTempToTPM();
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
        try {
            const responseTPM = await axios.get("/api/tpmdata?serial=" + serialNo.value);
            const tpmData = responseTPM.data.data || [];

            const getAllID = tpmData.map(item => item.id);

            // 👇 Create an array of promises
            const patchPromises = getAllID.map((id, i) => {
            return axios.patch(`/api/tpmdataupdate/${id}`, {
                temperature: csv_tempWithDataStat.value[i]?.temp || null,
                data_status: csv_tempWithDataStat.value[i]?.status || null
            }).then(response => {
                //console.log(`Patched ID ${id} with row ${i}:`, response.data);
                return response.data; // optionally return result
            }).catch(error => {
                console.error(`Error patching ID ${id}:`, error.response?.data || error.message);
                return null; // or rethrow if you want to break the flow
            });
            });

            // 👇 Run them all at once
            const patchResults = await Promise.all(patchPromises);
            //console.log('All patch results:', patchResults);

        } catch (error) {
            console.error("Something went wrong in mergeTempToTPM:", error);
        } finally{
            showCsvLoading.value = false;
            showGraphProceedConfirmation.value = false;
        }
    };

    const layerList = ref([]); // Stores fetched layers

    //UI VISIBILITY variables end ...

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

    //Serial Generation

    const serialNo = ref('');  // Reactive variable to hold the generated serial number
    const generateSerialNumber = async () => {
        try {
            // Step 1: Fetch the data from the API
            const response = await axios.get("/api/tpmdata"); // Adjust this URL to your API endpoint
            const tpmData = response.data.data.tpmDataAll || [];  // Fallback to an empty array if undefined
            //console.log("tpmData-data result = ",tpmData);

            if (tpmData.length > 0) {
                // Step 2: Loop through tpmData to find serial_no values and filter out invalid serial numbers
                const serialNumbers = tpmData
                    .map(item => item.serial_no)
                    .filter(serial => typeof serial === 'number');  // Ensure serial_no is a number

                if (serialNumbers.length > 0) {
                    // Step 3: Find the highest serial number (no need for slice, handle it as a full number)
                    const highestSerial = serialNumbers.reduce((max, current) => {
                        return current > max ? current : max;  // Compare the numbers directly
                    });

                    const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
                    const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)

                    // Extract the numeric part of the serial number (last part after year and month)
                    const numericPart = highestSerial.toString().slice(4);  // Take everything after the first 4 digits (YYYYMM)
                    const newNumericPart = parseInt(numericPart, 10) + 1; // Increment the numeric part

                    // Ensure the new numeric part is padded to 6 digits (e.g., 000001)
                    const paddedNumericPart = newNumericPart.toString().padStart(6, "0");

                    // Combine year, month, and the new incremented serial number
                    serialNo.value = `${year}${month}${paddedNumericPart}`;

                    //console.log('Generated Serial Number:', serialNo.value);
                } else {
                    // Step 4: If no valid serial number exists, generate the first one
                    const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
                    const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)

                    // Start with 1 if there's no serial number in the database
                    const firstSerialNumber = '000001';

                    // Generate the first serial number
                    serialNo.value = `${year}${month}${firstSerialNumber}`;

                    console.log('Generated First Serial Number:', serialNo.value);
                    alert(`Generated First Serial Number: ${serialNo.value}`);
                }
            } else {
                // Handle case where there's no data
                console.log('No data available in tpmData');
                const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
                const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)
                const firstSerialNumber = '000001';
                serialNo.value = `${year}${month}${firstSerialNumber}`;
                alert(`Generated First Serial Number: ${serialNo.value}`);
            }
        } catch (error) {
            console.error('Error generating serial number:', error);
        }
    };

    //Serial Generation end

    //New Furnace , New Layers end

    //table main layer header dynamic
    const tableLayerColumnHeaders = ref([
        {name: 'Date', colspan: 1},
        {name: 'Code\u00A0No', colspan: 1},
        {name: 'Order\u00A0No', colspan: 1},
        {name: 'Type', colspan: 1},
        {name: 'Lot\u00A0No', colspan: 1},
        {name: ' ', colspan: 1},
        {name: ' ', colspan: 1},
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

    // set of new variables for the 2nd save ( the aggregates and remarks )

    const saveBrRemarks = ref(null);
    const save4paiIdRemarks = ref(null);
    const saveIHcRemarks = ref(null);
    const saveBHcRemarks = ref(null);
    const saveBHMaxRemarks = ref(null);
    const saveSquarenessRemarks = ref(null);
    const save4paiIsRemarks = ref(null);
    const saveIHkRemarks = ref(null);
    const save4paiIaRemarks = ref(null);
    const saveDensityRemarks = ref(null);
    const saveIHkiHcRemarks = ref(null);
    const saveBr4paiRemarks = ref(null);
    const saveIHr95Remarks = ref(null);
    const saveIHr98Remarks = ref(null);


    // set of new variables for the 2nd save end

    // Store the file list when the input changes
    const storeFileList = (event) => {
        fileData.value = Array.from(event.target.files);
        fileLists.value = fileData.value.map(file => file.name); // Extract and store file names
        //console.log('Files stored:', fileData.value);
    };

    const saveToDatabase = async () => {
        await generateSerialNumber();
        //showUploadData.value = false;
        // Sort the files alphabetically by their name
        fileData.value.sort((a, b) => a.name.localeCompare(b.name)); // Sort by file name alphabetically
        //console.log('Sorted Files:', fileData.value);

        layerTableRowLoading.value = true;
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

                const layerData = {
                    "date": formattedDate,
                    "serial_no": serialNo.value,
                    "mass_prod": selectedMassProd.value,
                    "layer_no": currentLayerNo.value,
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
                console.log("Layer Data:", layerData);

                await sendLayerData(layerData); // Send the parsed data to the server
                resolve();
            };

            reader.onerror = () => {
                console.error('Error reading file:', file.name);
                reject(reader.error);
            };

            reader.readAsText(file); // Read the file as text

            });
        });

        try{
            await Promise.all(filePromises);
            showProceed1.value = true;
            showSerialNo.value = true;
        }catch(e){
            console.error("One or more files failed to upload." , e);
        }finally{
            layerTableRowLoading.value = false;
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


    // Method to clear the file upload
    const clearFileUpload = () => {
        fileData.value = null; // Reset the file data
        fileLists.value = [];
        const fileInput = document.querySelector('input[type="file"]');
        if (fileInput) fileInput.value = ''; // Clear the input field
        //console.log('File upload cleared');
    };

    // Function to send raw data via API
    const sendLayerData = async (layerData) => {
        try {
            const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
            console.log('API Response sendlayerdata:', response.data);
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
        showLoadingForGraphAndTables.value = true;
        layerTableRowLoading.value = true;
        showProceed3.value = false;
        toggleManageForm.value = false;
        showAddNewDataLayer.value = false;
        try {
            showProceed3.value = false;
            toggleManageForm.value = false;
            showAddNewDataLayer.value = false;
            const response = await axios.get("/api/tpmdata?serial=" + serialNo.value); // Adjust this URL to your API endpoint
            //console.log('API Response showallData:', response.data);

            //console.log('Serial No value = ', serialNo.value);

            items.value = response.data;

            // Extract arrays from the response
            tpmData.value = response.data.data || []; // Fallback to an empty array if undefined
            tpmRemarks.value = response.data.remark || [];
            getAggregateID.value = response.data[0][0].id || [];
            //console.log("Aggregate ID: ", getAggregateID.value);

            const tpm_category_actualmodel = tpmData.value.map(item => item.category?.actual_model ?? null);
            jhCurveActualModel.value = tpm_category_actualmodel[0];
            //const tpm_category_factorEmp = tpmData.value.map(item => item.category?.factor_emp ?? null);
            //propData_factorEmp.value = tpm_category_factorEmp[0];
            //const tpm_category_miasEmp = tpmData.value.map(item => item.category?.mias_emp ?? null);
            //propData_miasEmp.value = tpm_category_miasEmp[0];
            const tpm_category_jhCurveLotno = tpmData.value.map(item => item.category?.jhcurve_lotno ?? null);
            jhCurveLotNo.value = tpm_category_jhCurveLotno[0];
            const tpm_category_massProdName = tpmData.value.map(item => item.category?.massprod_name ?? null);
            jhCurveMassProdName.value = tpm_category_massProdName[0];

            // Combine the arrays
            combinedData.value = tpmData.value;
            //console.log('Combined Data: ', combinedData.value);

            jhCurveFurnaceName.value = tpmData.value[0].sintering_furnace_no || "No data found";
            jhCurveModel.value = tpmData.value[0].code_no || "No data found";

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

            //console.log('Aggregate Data:', aggregateData);
            await sendAggData(aggregateData, serialNo.value);

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
            //await autoRenameFurnace();
            await userManageLogging('created '+ serialNo.value +' data successfully | Model : ' + jhCurveActualModel.value);
        }
        await fetchDataCreateGraph();
    };

// Function to send raw data via API
const sendAggData = async (aggData, id) => {  // Make sure to pass 'id' as a parameter
    try {
        // Correct string interpolation for the URL
        const response = await axios.patch(`/api/tpmaggregateupdate/${id}`, aggData); // Proper string interpolation for URL
        // Uncomment this to see the response
        //console.log('API Response sendAggData:', response.data);
    } catch (error) {
        console.error('Error sending aggregate data to API:', error.response?.data || error.message);
    }
};

// State for storing fetched data and error
const error = ref(null);
const datasets = ref([]); // Array to hold multiple datasets

const fetchDataCreateGraph = async () => {
    try {
        const response = await axios.get("/api/tpmdata?serial=" + serialNo.value);

        // Log the response structure to check
        //console.log("API Response:", response.data);

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
        //showGraphAndTables.value = true;  // Set this to true after data is loaded

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
    } finally {
        showGraphAndTables.value = true;
        showLoadingForGraphAndTables.value = false;
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

        //new updated offset 5/5/2025
        const x_offset = 2000;
        const y_offset = 1700;

        //const x_offset = 2000;
        //const y_offset = 4000;

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
                            ticks: {
                                display: false // disables X-axis labels
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
                            ticks: {
                                display: false // disables X-axis labels
                            },
                        },
                    },
                },
            });
            setTimeout(() => {
                const canvas = myChartCanvas.value;
                const imageData = canvas.toDataURL("image/png");

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                fetch("/upload-chart", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken || "",
                    },
                    body: JSON.stringify({
                        image: imageData,
                        filename: `chart_${serialNo.value}.png`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Chart image saved at:", data.path);
                    // Optional: store this filename in a hidden input or use it to trigger PDF render
                })
                .catch(err => console.error("Chart upload failed:", err));
            }, 1000); // Give Chart.js time to fully render
        } catch (error) {
            console.error("Error initializing Chart.js:", error);
        }
    } else {
        console.error("Chart cannot be rendered: Missing data or canvas context.");
    }
};

   // Define props that Inertia will pass to the component
    const props = defineProps({
        manageSerialParam: String,  // Expecting the serialParam to be a string
    });

    //console.log('Serial Param in Manage.vue:', props.manageSerialParam); // You can use this for debugging

    // onMounted logic to call the function based on serialParam existence
    onMounted(async () => {
        // Log the value to check if it's being passed correctly
        //console.log('Serial Param in Manage.vue:', props.manageSerialParam);
        if (props.manageSerialParam) {
            showStartManageDiv.value = false;
            // If serialParam has a value, do not fetch serial
            serialNo.value = props.manageSerialParam;
            toggleManageForm.value = true;
            showGraphAndTables.value = true;
            showUploadData.value = false;
            //console.log("Showdiv graphs ",showGraphAndTables.value);
            showAllData();
            //console.log('serialParam is provided, skipping fetchSerial.');
        } else {
            // If serialParam does not have a value, proceed with fetchSerial
            //fetchFurnaces();
        }

        await checkAuthentication();
        await getMassProdLists();

    });


  </script>

<style scoped>

@keyframes fade-in-down {
    0% {
        opacity: 0;
        transform: translateY(-1.75rem);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
    }

.animate-fade-in-down {
    animation: fade-in-down 1s ease-out forwards;
}


</style>
