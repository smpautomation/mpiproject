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
            <div v-show="showStartManageDiv" class="z-10">
                <div v-show="showCreateExistingFurnaceBtn" class="animate-fade-in-down flex flex-col items-center justify-start p-5 h-[450px] w-[1000px] rounded-xl shadow-lg bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-lg">
                    <div class="flex flex-row items-center justify-between w-full mb-10">
                        <div class="flex flex-row items-center space-x-4">
                            <span
                                class="w-16 h-16 transition duration-300 bg-center bg-no-repeat bg-cover"
                                style="
                                    background-image: url('/photo/manage_logo.png');
                                    background-size: 80%;
                                "
                            ></span>
                            <p class="text-2xl font-bold text-blue-900">MANAGE</p>
                        </div>

                        <button @click="$inertia.visit('/email_form')" class="group relative cursor-pointer flex flex-col justify-center items-center shadow-xl text-white font-extrabold bg-blue-800/10 backdrop-blur-lg border border-white/10 w-[180px] h-[50px] rounded-xl transform transition duration-300 ease-in-out hover:scale-105 active:scale-95 active:shadow-inner">
                            Send Email to SEC
                        </button>
                    </div>
                    <div class="flex flex-row">
                        <!-- Quick Add Furnace -->
                        <div
                            @click="addNewFurnaceBtn"
                            :class="{ 'opacity-50 pointer-events-none': isLoadingForAddFurnaces }"
                            class="group relative cursor-pointer flex flex-col mr-20 justify-center items-center shadow-xl bg-white/10 backdrop-blur-lg border border-white/10 w-[400px] h-[230px] rounded-xl transform transition duration-300 ease-in-out hover:scale-105 active:scale-95 active:shadow-inner"
                        >
                            <span
                            class="w-40 h-40 transition duration-300 bg-center bg-no-repeat bg-cover"
                            :style="{
                                backgroundImage: 'url(\'/photo/furnace_logo.png\')',
                                backgroundSize: '100%'
                            }"
                            ></span>
                            <p class="mt-2 text-lg font-bold text-blue-900 transition group-hover:text-blue-700">
                            {{ isLoadingForAddFurnaces ? "On going..." : "Quick Add Furnace" }}
                            </p>
                        </div>

                        <!-- Existing Furnaces -->
                        <div
                            @click="existingFurnaceBtn"
                            :class="{
                                'opacity-50 pointer-events-none': isLoadingForAddFurnaces,
                                'cursor-pointer hover:scale-105 active:scale-95 active:shadow-inner': !isLoadingForAddFurnaces
                            }"
                            class="group relative flex flex-col justify-center items-center shadow-xl bg-white/20 backdrop-blur-lg border border-white/30 w-[400px] h-[230px] rounded-xl transform transition duration-300 ease-in-out"
                        >
                            <span
                                class="w-[200px] h-[170px] bg-center bg-no-repeat bg-cover transition duration-300"
                                :style="{
                                    backgroundImage: 'url(\'/photo/existing_furnaces_logo.png\')',
                                    backgroundSize: '100%'
                                }"
                            ></span>
                            <p class="text-lg font-bold text-blue-900 transition group-hover:text-blue-700">
                                Existing Furnaces
                            </p>
                        </div>
                    </div>
                    <p v-if="isLoadingForAddFurnaces" class="mt-10 text-xl text-center text-white animate-pulse">Creating... Please wait.</p>
                    <p v-show="showFurnaceCreatedNotif" class="mt-10 text-lg text-center text-white animate-pulse">A new furnace with a name <span class="px-2 py-1 text-xl font-extrabold text-orange-100 bg-orange-600 rounded-lg">{{ showFurnaceName }}</span> has been added successfully</p>
                    <p v-show="showNoFurnaceDetectedNotif" class="mt-10 text-lg text-center text-white animate-pulse">No furnace detected in the system. Create one by clicking <span class="px-2 py-1 text-xl font-extrabold text-orange-100 bg-orange-600 rounded-lg">Quick Add Furnace</span> first.</p>
                </div>
                <div v-show="showSelectFurnace" class="flex flex-col w-[1000px] h-[450px] items-center p-5 justify-start align-middle shadow-xl rounded-xl bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-lg">
                    <div class="flex flex-row items-center self-start justify-start mb-10 space-x-4">
                        <span
                            class="w-16 h-16 transition duration-300 bg-center bg-no-repeat bg-cover"
                            :style="{
                            backgroundImage: 'url(\'/photo/manage_logo.png\')',
                            backgroundSize: '80%'
                            }"
                        ></span>
                        <p class="text-2xl font-bold text-blue-900">MANAGE</p>
                    </div>
                    <div class="flex flex-row items-center justify-center align-baseline">
                        <p class="text-xl font-extrabold text-white">Select a furnace:</p>
                        <select
                        v-model="currentFurnaceName"
                        class="py-2 m-4 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm px-auto focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <option v-for="furnace in furnaceList" :key="furnace.id" :value="furnace.furnace_name">
                            {{ furnace.furnace_name }}
                        </option>
                        </select>
                    </div>
                    <div class="flex flex-row items-center justify-center m-2 space-x-16">
                        <button @click="existingBackBtn" class="px-4 py-2 font-bold text-white bg-gray-500 rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Back
                        </button>
                        <button
                        @click="existingProceedBtn"
                        class="flex items-baseline gap-2 px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                        >
                        Proceed
                            <span
                                class="w-[16px] h-[15px] inline-block bg-no-repeat bg-center bg-contain relative top-[2px]"
                                :style="{
                                backgroundImage: 'url(/photo/gotoreport.png)'
                                }"
                            ></span>
                        </button>
                    </div>
                </div>
                <div v-show="showAddNewLayer" class="flex flex-col items-center justify-start p-5 space-y-4 w-[1000px] h-[450px] shadow-xl rounded-xl bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-lg">
                    <div class="flex flex-row items-center self-start justify-start mb-10 space-x-4">
                        <span
                            class="w-16 h-16 transition duration-300 bg-center bg-no-repeat bg-cover"
                            :style="{
                            backgroundImage: 'url(\'/photo/manage_logo.png\')',
                            backgroundSize: '80%'
                            }"
                        ></span>
                        <p class="text-2xl font-bold text-blue-900">MANAGE</p>
                    </div>
                    <p class="text-lg font-extrabold text-white ">You have selected <span class="text-xl animate-pulse">{{ currentFurnaceName }}</span></p>
                    <div class="flex flex-row items-center justify-center text-white align-baseline">
                        <p>Select a layer:</p>
                        <select
                        v-model="currentLayerName"
                        class="py-2 m-4 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm px-auto focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option v-for="layer in layerList" :key="layer.id" :value="layer.layer_name">
                                {{ layer.layer_name }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-row items-center justify-center m-2 space-x-16">
                        <button @click="proceedLayerBackBtn" class="px-4 py-2 font-bold text-white bg-gray-500 rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Back
                        </button>
                        <button @click="proceedLayerBtn" class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                            Proceed
                            <span
                                class="w-[16px] h-[15px] inline-block bg-no-repeat bg-center bg-contain relative top-[2px]"
                                :style="{
                                backgroundImage: 'url(/photo/gotoreport.png)'
                                }"
                            ></span>
                        </button>
                    </div>
                </div>
            </div>
            <div v-show="showAddNewDataLayer" v-if="!toggleManageForm" class="z-10 flex flex-col items-center justify-center py-4 space-y-4  w-[1000px] h-[450px] shadow-xl rounded-xl bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-lg">
                <div class="flex flex-col items-center justify-center space-x-4 text-lg font-extrabold text-white align-middle">
                    <p>Currently selected: </p>
                    <p>{{ currentFurnaceName }} on {{ currentLayerName }}</p>
                </div>
                <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" @click="showManageForm">
                    Begin Process
                </button>
                <button class="px-4 py-2 font-bold text-white bg-red-600 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2" @click="showAddNewDataLayer_cancel">
                    Cancel
                </button>
            </div>
            <div v-if="toggleManageForm" class="z-10 flex flex-col items-center justify-center shadow-xl rounded-xl w-[1000px] h-[450px] bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-lg mt-20">
                <p v-show="showSerialNo" class="flex flex-col mb-10 font-extrabold text-white">Serial: {{ serialNo }}</p>
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
                            <!-- Button to Redo Upload -->

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
                    <div v-show="showProceed1" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold text-white animate-pulse">TPM DATA UPLOADED SUCCESSFULLY!</p>
                        <p class="mt-5 text-white">Please Type the Model Name before proceeding: </p>
                        <input
                            type="text"
                            v-model="jhCurveActualModel"
                            @input="jhCurveActualModel = jhCurveActualModel.toUpperCase()"
                            class="px-4 py-2 mt-4 mb-10 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="E.G. TIC-0755G"
                        />
                        <button
                            class="px-4 py-2 mt-4 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="proceedToCsvUpload_showConfirm"
                            >
                                Proceed to Upload CSV
                        </button>
                    </div>
                    <div
                        v-show="showNoModelError"
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
                        <span>Please type the model name first.</span>
                    </div>
                    <div
                        v-show="showProceedToCsvUpload_confirmation"
                        class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mt-10 bg-white border border-gray-200 shadow-xl rounded-xl"
                        >
                        <div class="mb-6">
                            <p class="text-lg font-semibold text-center text-gray-800">Are you sure this model name is correct?</p>
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
                <DotsLoader v-show="showCsvLoading" />
                <div>
                    <div v-show="showProceed2" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold text-white animate-pulse">UPLOADING CSV DATA SUCCESSFULLY COMPLETED!</p>
                        <p class="mt-5 text-white">Please fill in the details before proceeding</p>
                        <div class="flex flex-row">
                            <div class="flex flex-col items-start justify-start">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-white">Mass Production Name:</label>
                                <input
                                    type="text"
                                    v-model="jhCurveMassProdName"
                                    @input="jhCurveMassProdName = jhCurveMassProdName.toUpperCase()"
                                    placeholder="e.g. K40 541st"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start ml-5">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-white">Mias. Employee:</label>
                                <input
                                    type="text"
                                    v-model="propData_factorEmp"
                                    @input="propData_factorEmp = propData_factorEmp.toUpperCase()"
                                    placeholder="e.g. Ella"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start ml-5">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-white">Factor Employee:</label>
                                <input
                                    type="text"
                                    v-model="propData_miasEmp"
                                    @input="propData_miasEmp = propData_miasEmp.toUpperCase()"
                                    placeholder="e.g. Kathryn"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start ml-5">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-white">Lot No.:</label>
                                <input
                                    type="text"
                                    v-model="jhCurveLotNo"
                                    @input="jhCurveLotNo = jhCurveLotNo.toUpperCase()"
                                    placeholder="e.g. 100/101-1"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        <button
                            class="px-4 py-2 mt-10 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="proceedToFinal_showConfirm"
                            >
                                Submit
                        </button>
                    </div>

                    <div
                    v-show="showProceed2_confirmation"
                    class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mt-10 bg-white border border-gray-200 shadow-xl rounded-xl"
                    >
                        <div class="mb-6">
                            <p class="text-lg font-semibold text-center text-gray-800">Are you sure all information is correct?</p>
                        </div>
                        <div class="flex space-x-6">
                            <button
                            @click="proceedToFinal"
                            class="px-6 py-2 font-bold text-white transition duration-200 bg-blue-500 rounded-lg shadow-md hover:bg-blue-600"
                            >
                            YES
                            </button>
                            <button
                            @click="proceedToFinal_cancel"
                            class="px-6 py-2 font-bold text-gray-700 transition duration-200 bg-gray-200 rounded-lg shadow-md hover:bg-gray-300"
                            >
                            NO
                            </button>
                        </div>
                    </div>

                    <div
                        v-show="showIncompleteInformationError"
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
                        <span>All fields are required.</span>
                    </div>
                </div>
                <div>
                    <div v-show="showProceed2_massprod" class="flex flex-col items-center justify-center">
                        <p class="text-lg font-extrabold text-white animate-pulse">UPLOADING CSV DATA SUCCESSFULLY COMPLETED!</p>
                        <p class="mt-5 text-white">Please fill in the details for mass production control sheet before proceeding</p>
                        <div class="flex flex-row">
                            <span class="my-4">BOX {{ massProd_letter }}</span>
                            <div class="flex flex-col items-start justify-start">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-white">Quantity:</label>
                                <input
                                    type="number"
                                    v-model="massProd_qty"
                                    placeholder="e.g. K40 541st"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-white">Box no.:</label>
                                <input
                                    type="text"
                                    v-model="massProd_boxNo"
                                    @input="massProd_boxNo = massProd_boxNo.toUpperCase()"
                                    placeholder="e.g. UBP8 5071"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="flex flex-col items-start justify-start ml-5">
                                <label class="mt-5 mb-1 text-sm font-extrabold text-white">WT:</label>
                                <input
                                    type="number"
                                    v-model="massProd_WT"
                                    @input="massProd_WT = massProd_WT.toUpperCase()"
                                    placeholder="e.g. 15.23"
                                    class="px-4 py-2 mt-1 text-base font-semibold text-gray-700 placeholder-gray-400 uppercase bg-white border border-gray-300 rounded-lg shadow-sm placeholder-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        <button
                            class="px-4 py-2 mt-10 text-base font-semibold text-white transition-all duration-300 ease-in-out bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 active:scale-95"
                            @click="proceedToFinal_showConfirm"
                            >
                                Submit
                        </button>
                    </div>

                    <div
                    v-show="showProceed2_confirmation"
                    class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mt-10 bg-white border border-gray-200 shadow-xl rounded-xl"
                    >
                        <div class="mb-6">
                            <p class="text-lg font-semibold text-center text-gray-800">Are you sure all information is correct?</p>
                        </div>
                        <div class="flex space-x-6">
                            <button
                            @click="proceedToFinal"
                            class="px-6 py-2 font-bold text-white transition duration-200 bg-blue-500 rounded-lg shadow-md hover:bg-blue-600"
                            >
                            YES
                            </button>
                            <button
                            @click="proceedToFinal_cancel"
                            class="px-6 py-2 font-bold text-gray-700 transition duration-200 bg-gray-200 rounded-lg shadow-md hover:bg-gray-300"
                            >
                            NO
                            </button>
                        </div>
                    </div>

                    <div
                        v-show="showIncompleteInformationError"
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
                        <span>All fields are required.</span>
                    </div>
                </div>
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
                <div class="flex flex-col items-center justify-center">
                    <p class="px-4 py-2 text-xl text-white border-2 shadow-lg animate-pulse rounded-xl bg-gradient-to-br from-cyan-900/80 to-green-100/40 backdrop-blur-lg">Note: You may now proceed to the <span class="text-2xl font-extrabold text-blue-400">Report</span> section and select {{ serialNo }} </p>
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
    // Register all Chart.js components using registerables
    Chart.register(...registerables);

    //UI VISIBILITY variables...
    const showFurnaceCreatedNotif = ref(false);
    const showNoFurnaceDetectedNotif = ref(false);
    const showGraphAndTables = ref(false);
    const showLoadingForGraphAndTables = ref(false);
    const showUploadData = ref(true)
    const showUploadTPMFiles = ref(true);
    const showLoadingForProceed1 = ref(false);
    const showProceed1 = ref(false);
    const showProceed2 = ref(false);
    const showProceed3 = ref(false);
    const showProceed2_massprod = ref(false);
    const csvUpload = ref(false);
    const showAddNewDataLayer = ref(false);
    const showAddNewLayer = ref(false);
    const showStartManageDiv = ref(true);
    const showAddNewFurnace = ref(true);
    const showSelectFurnace = ref(false);
    const showCreateExistingFurnaceBtn = ref(true);
    const currentFurnaceName = ref('');
    const currentFurnaceNo = ref(null);
    const toggleManageForm = ref(false);
    const currentLayerName = ref('');
    const currentLayerNo = ref(null);
    const showFurnaceName = ref('');
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

    const showManageForm = () => {
        toggleManageForm.value = !toggleManageForm.value;
        generateSerialNumber();
    }

    const showAddNewDataLayer_cancel = () => {
        showStartManageDiv.value = true;
        showAddNewDataLayer.value = false;
    }

    const proceedToCsvUpload_showConfirm = () => {
        if(jhCurveActualModel.value == null || jhCurveActualModel.value == ''){
            //alert('Please type the Model Name first.');
            showNoModelError.value = true;
            showSerialNo.value = false;
            showProceed1.value = false;

            setTimeout(() => {
                showNoModelError.value = false;
                showSerialNo.value = true;
                showProceed1.value = true;
            }, 2000);
            return;
        }else{
            showProceedToCsvUpload_confirmation.value = true;
            showSerialNo.value = false;
            showProceed1.value = false;
        }
    }

    const proceedToCsvUpload_cancel = () => {
        showProceedToCsvUpload_confirmation.value = false;
        showProceed1.value = true;
    }

    const proceedToCsvUpload = async () => {
        showProceedToCsvUpload_confirmation.value = false;
        showLoadingForProceed1.value = true;
        setTimeout(() => {
            csvUpload.value = true;
            showProceed1.value = false;
            showLoadingForProceed1.value = false;
        }, 500); // Delay just enough to let Vue catch up

    }

    const proceedToFinal_showConfirm = () => {
        if(jhCurveMassProdName.value == null || jhCurveMassProdName.value == "" ||
            propData_factorEmp.value == null || propData_factorEmp.value == "" ||
            propData_miasEmp.value == null || propData_miasEmp.value == "" ||
            jhCurveLotNo.value == null || jhCurveLotNo.value == ""){

            showIncompleteInformationError.value = true;
            showProceed2.value = false;

            setTimeout(() => {
                showIncompleteInformationError.value = false;
                showProceed2.value = true;
            }, 2000);
            return;
        }else{
            showProceed2_confirmation.value = true;
            showProceed2.value = false;
        }
    }

    const proceedToFinal = () => {
        showProceed3.value = true;
        showProceed2.value = false;
        showProceed2_confirmation.value = false;
        saveToTpmCategory();
    }

    const proceedToFinal_cancel = () => {
        showProceed2_confirmation.value = false;
        showProceed2.value = true;
    }

    const saveToTpmCategory = async () => {
        try{
            const responsePatchCategory = await axios.patch(`/api/updatecategory/${serialNo.value}`,{
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

    const saveToTpmBoxes = async () => {
        try{

        }catch(error){
            console.error("Error fetching API Response SaveToTpmCategory:", error);
        }
    }

    const isLoadingForAddFurnaces = ref(false); // Initialize loading state

    const addNewFurnaceBtn = async () => {
        isLoadingForAddFurnaces.value = true; // Start loading

        try {
            // Fetch existing furnace data
            const response = await axios.get('/api/furnacedata');
            //console.log("API Response:", response.data.data["Furnace Data"]); // Debugging: Check the response structure

            // Extract the correct array from the API response
            const existingFurnaces = response.data.data["Furnace Data"] || []; // Adjust this if needed
            //console.log("Extracted Furnaces:", existingFurnaces); // Debugging: Check if it's an array

            // Ensure existingFurnaces is an array
            if (!Array.isArray(existingFurnaces)) {
                console.error("Error: Fetched data is not an array, received:", existingFurnaces);
                return; // Stop execution if data is not an array
            }

            // Process existing furnace names and extract numbers
            const furnaceNumbers = existingFurnaces
                .map(item => item.furnace_name)
                .filter(name => name && /^New Furnace \d+$/.test(name)) // Ensure it matches "New Furnace X"
                .map(name => {
                    const match = name.match(/\d+$/);
                    return match ? parseInt(match[0], 10) : 1;
                });

            // Determine the next furnace number
            const nextFurnaceNumber = furnaceNumbers.length > 0
                ? Math.max(...furnaceNumbers) + 1
                : 1;

            const newFurnaceName = `New Furnace ${nextFurnaceNumber}`;

            // Prepare the new furnace data
            const newFurnaceData = {
                furnace_name: newFurnaceName,
                description: "Enter description here"
            };

            //console.log("Saving Furnace:", newFurnaceData); // Debugging log before sending

            await saveNewFurnace(newFurnaceData);

            //alert(`${newFurnaceName} has been added`);
            showFurnaceName.value = newFurnaceName;
        } catch (error) {
            console.error("Error fetching furnace data:", error);
        } finally {
            isLoadingForAddFurnaces.value = false; // Stop loading
            console.log("Loading state reset.");

            showFurnaceCreatedNotif.value = true;

            // Hide the notification after 5 seconds
            setTimeout(() => {
                showFurnaceCreatedNotif.value = false;
            }, 5000); // 5000 milliseconds = 5 seconds
        }
    };

    const csv_selectedFile = ref(null)
    const csv_parsedData = ref([])
    const csv_tempWithDataStat = ref([]);

    const csv_handleFileSelect = (event) => {
        csv_selectedFile.value = event.target.files[0]
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

    const csv_submitFile = async () => {
        csvUpload.value = false;
        showCsvUpload_confirmation.value = false;
        showCsvLoading.value = true;

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
                console.error('Error parsing CSV:', err);
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
                console.log(`Patched ID ${id} with row ${i}:`, response.data);
                return response.data; // optionally return result
            }).catch(error => {
                console.error(`Error patching ID ${id}:`, error.response?.data || error.message);
                return null; // or rethrow if you want to break the flow
            });
            });

            // 👇 Run them all at once
            const patchResults = await Promise.all(patchPromises);
            console.log('All patch results:', patchResults);

        } catch (error) {
            console.error("Something went wrong in mergeTempToTPM:", error);
        } finally{
            showCsvLoading.value = false;
            showProceed2.value = true;
        }
    };


    const furnaceList = ref([]); // Stores all fetched furnaces

    // Function to fetch furnace data
    const fetchFurnaces = async () => {
    try {
        const response = await axios.get("/api/furnacedata");
        //console.log("API Response:", response.data.data["Furnace Data"]);

        // Extract furnace data dynamically
        const extractedFurnaces = response.data.data["Furnace Data"] || response.data.furnaces || response.data.data || [];

        // Ensure data is an array
        if (!Array.isArray(extractedFurnaces)) {
        console.error("Error: Fetched data is not an array.", extractedFurnaces);
        return;
        }

        // Update the reactive variable
        furnaceList.value = extractedFurnaces;
        //console.log("Furnace list: ", furnaceList.value);
        // Set default selection to first furnace, if available
        if (furnaceList.value.length > 0) {
            currentFurnaceName.value = furnaceList.value[0].furnace_name;
        }

    } catch (error) {
        console.error("Error fetching furnace data:", error);
    }
    };

    const layerList = ref([]); // Stores fetched layers\
    const currentFurnaceNoForLayer = ref(null);

    // Function to fetch layer data
    const fetchLayers = async () => {
        try {
            console.log("Fetching layer data from /api/layerdata...");

            const response = await axios.get("/api/layerdata");
            //console.log("API Response:", response.data.data["Layer Data"]);

            // Extract layer data safely
            const extractedLayers = response.data.data["Layer Data"] || response.data?.layers || response.data?.data || [];

            //console.log("Extracted Layers:", extractedLayers);

            // Ensure extractedLayers is an array
            if (!Array.isArray(extractedLayers)) {
                console.error("Error: Fetched data is not an array.", extractedLayers);
                return;
            }

            currentFurnaceNoForLayer.value = await getFurnaceNoByName(currentFurnaceName.value);
            currentFurnaceNo.value = currentFurnaceNoForLayer.value;
            //console.log("Current furnace no for layer : ", currentFurnaceNoForLayer.value);
            // 🔍 **Filter the data
            const filteredLayers = extractedLayers.filter(layer => layer.furnace_id === currentFurnaceNoForLayer.value);

            //console.log("Filtered Layers:", filteredLayers);

            // Update the reactive layer list
            layerList.value = filteredLayers;

            // Set default selection to first layer, if available
            if (layerList.value.length > 0) {
                currentLayerName.value = layerList.value[0].layer_name;
            } else {
                console.warn("No matching layers found.");
            }
        } catch (error) {
            console.error("Error fetching layer data:", error);

            if (error.response) {
                console.error("Server responded with:", error.response.status, error.response.data);
            } else if (error.request) {
                console.error("No response received from API. Request details:", error.request);
            } else {
                console.error("Error setting up request:", error.message);
            }
        }
    };

    const getFurnaceNoByName = async (furnaceName) => {
        try {
            console.log("Fetching furnace_id for furnace:", furnaceName);

            // Fetch all furnaces from the API
            const response = await axios.get('/api/furnacedata');
            const furnaces = response.data.data["Furnace Data"] || [];

            // Find the furnace with the matching name
            const foundFurnace = furnaces.find(f => f.furnace_name === furnaceName);

            if (foundFurnace) {
                //console.log("Furnace found:", foundFurnace);
                return foundFurnace.furnace_id; // Return the furnace_no
            } else {
                console.warn("Furnace not found:", furnaceName);
                return null;
            }
        } catch (error) {
            console.error("Error fetching furnace_no:", error);
            return null;
        }
    };

    const getLayerNoByName = async (layerName) => {
        try {
            console.log("Fetching layer_no for layer:", layerName);

            // Fetch all layers from the API
            const response = await axios.get('/api/layerdata');
            const layers = response.data.data["Layer Data"] || [];

            // Find the layer with the matching name
            const foundLayer = layers.find(f => f.layer_name === layerName);

            if (foundLayer) {
                //console.log("Layer found:", foundLayer.layer_no);
                return foundLayer.layer_no; // Return the layer_no
            } else {
                console.warn("Layer not found:", layerName);
                return null;
            }
        } catch (error) {
            console.error("Error fetching layer_no:", error);
            return null;
        }
    };

    const proceedLayerBtn = () => {
        if(currentLayerName.value === ''){
            alert('Please select a layer');
        }else{
            showStartManageDiv.value = false;
            showAddNewDataLayer.value = true;
            saveNewLayer();
        }
    }

    const existingFurnaceBtn = async () => {
        try{
            const response = await axios.get('/api/furnacedata');
            const existingFurnaces = response.data.data["Furnace Data"] || [];
            console.log("Existing furnaces: ", existingFurnaces.length);
            if(existingFurnaces.length === 0){
                showNoFurnaceDetectedNotif.value = true;
                setTimeout(() => {
                    showNoFurnaceDetectedNotif.value = false;
                }, 5000);
            }else{
                showSelectFurnace.value = true;
                showCreateExistingFurnaceBtn.value = false;
            }
        }catch(error){
            console.error('Error fetching furnaces:', error);
        }
    }

    const existingProceedBtn = () => {
        if(currentFurnaceName.value === ''){
            alert('Please select a furnace');
        }else{
            showAddNewLayer.value = true;
            showSelectFurnace.value = false;
            fetchLayers();
        }
    }

    const existingBackBtn = () => {
        showCreateExistingFurnaceBtn.value = true;
        showSelectFurnace.value = false;
    }

    const proceedLayerBackBtn = () => {
        showAddNewLayer.value = false;
        showSelectFurnace.value = true;
    }

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

const serialNo = ref(null);  // Reactive variable to hold the generated serial number
    const generateSerialNumber = async () => {
        try {
            // Step 1: Fetch the data from the API
            const response = await axios.get("/api/tpmdata"); // Adjust this URL to your API endpoint
            const tpmData = response.data.data.tpmDataAll || [];  // Fallback to an empty array if undefined
            console.log("tpmData-data result = ",tpmData);

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

                    console.log('Generated Serial Number:', serialNo.value);
                } else {
                    // Step 4: If no valid serial number exists, generate the first one
                    const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
                    const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)

                    // Start with 1 if there's no serial number in the database
                    const firstSerialNumber = '000001';

                    // Generate the first serial number
                    serialNo.value = `${year}${month}${firstSerialNumber}`;

                    console.log('Generated First Serial Number:', serialNo.value);
                    //alert(`Generated First Serial Number: ${serialNo.value}`);
                }
            } else {
                // Handle case where there's no data
                console.log('No data available in tpmData');
                const year = new Date().getFullYear().toString().slice(-2); // Get last 2 digits of the year
                const month = (new Date().getMonth() + 1).toString().padStart(2, "0"); // Get month (01-12)
                const firstSerialNumber = '000001';
                serialNo.value = `${year}${month}${firstSerialNumber}`;
                //alert(`Generated First Serial Number: ${serialNo.value}`);
            }
        } catch (error) {
            console.error('Error generating serial number:', error);
        }
    };

    //Serial Generation end

    //New Furnace , New Layers

    const saveNewFurnace = async (newFurnace) => {
        try {
            console.log("Attempting to save new furnace:", JSON.stringify(newFurnace, null, 2));

            // First, check if the furnace already exists
            const checkResponse = await axios.get('/api/furnacedata');
            const existingFurnaces = checkResponse.data.data["Furnace Data"] || [];

            // Check if the furnace already exists
            if (existingFurnaces.some(f => f.furnace_name === newFurnace.furnace_name)) {
                alert("Furnace name already exists! Try a different name.");
                return;
            }

            // Send the new furnace data
            const response = await axios.post('/api/furnacedata', newFurnace);
            console.log("Furnace created successfully:", response.data);

            // Extract furnace_id from response (assuming API returns the created furnace object)
            const newFurnaceNo = response.data.data.id; // Adjust based on API response
            console.log("Get furnace no: ", newFurnaceNo);
            // Now, create default layers under this furnace
            await createDefaultLayers(newFurnaceNo);

            // Refresh the furnace list
            fetchFurnaces();
            fetchLayers();

        } catch (error) {
            console.error("Error sending data to API:", error);
            if (error.response) {
                console.error("Server responded with:", error.response.status, error.response.data);
            } else if (error.request) {
                console.error("No response received from API. Request details:", error.request);
            } else {
                console.error("Error setting up request:", error.message);
            }
        }
    };

    const createDefaultLayers = async (furnaceNo) => {
        for (let i = 1; i <= 10; i++) {
            const layerDatabase = {
                layer_no: i,
                layer_name: `Layer ${i}`,
                furnace_id: furnaceNo,
                description: "Enter description here"
            };

            try {
                console.log(`Sending Layer ${i}:`, layerDatabase);
                const response = await axios.post('/api/layerdata', layerDatabase);
                console.log(`Layer ${i} created:`, response.data.data["Layer Name"]);
            } catch (error) {
                console.error(`Failed to create Layer ${i}:`, error);
            }
        }
    };

    const saveNewLayer = async () => {
        try {
            currentLayerNo.value = await getLayerNoByName(currentLayerName.value);
            console.log("Current layer no from save new layer: ", currentLayerNo.value);
            //alert('New Layer Added');
        } catch (error) {
            console.error('Error fetching layer number:', error);
        }
    };

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

    const saveToDatabase_cancel = () => {
        showSaveToDatabase_confirmation.value = false;
        showUploadTPMFiles.value = true;
        showSerialNo.value = true;
        clearFileUpload();
    }

    const saveToDatabase = async () => {
        showUploadData.value = false;
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
                    "furnace_id": currentFurnaceNo.value,
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
                //console.log("Layer Data:", layerData);

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
            showUploadData.value = false;
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
    console.log('File upload cleared');
    };

    // Function to send raw data via API
    const sendLayerData = async (layerData) => {
        try {
            const response = await axios.post('/api/tpmdata', layerData); // Replace '/api/endpoint' with your API endpoint
            //console.log('API Response sendlayerdata:', response.data);
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

    const massProd_boxNo = ref("");
    const massProd_WT = ref(0);
    const massProd_letter = ref("");
    const massProd_qty = ref(0);

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
            console.log('API Response showallData:', response.data);

            console.log('Serial No value = ', serialNo.value);

            items.value = response.data;

            // Extract arrays from the response
            tpmData.value = response.data.data || []; // Fallback to an empty array if undefined
            tpmRemarks.value = response.data.remark || [];
            getAggregateID.value = response.data[0][0].id || [];
            console.log("Aggregate ID: ", getAggregateID.value);

            const tpm_category_actualmodel = tpmData.value.map(item => item.category?.actual_model ?? null);
            jhCurveActualModel.value = tpm_category_actualmodel[0];
            const tpm_category_factorEmp = tpmData.value.map(item => item.category?.factor_emp ?? null);
            propData_factorEmp.value = tpm_category_factorEmp[0];
            const tpm_category_miasEmp = tpmData.value.map(item => item.category?.mias_emp ?? null);
            propData_miasEmp.value = tpm_category_miasEmp[0];
            const tpm_category_jhCurveLotno = tpmData.value.map(item => item.category?.jhcurve_lotno ?? null);
            jhCurveLotNo.value = tpm_category_jhCurveLotno[0];
            const tpm_category_massProdName = tpmData.value.map(item => item.category?.massprod_name ?? null);
            jhCurveMassProdName.value = tpm_category_massProdName[0];

            // Combine the arrays
            combinedData.value = tpmData.value;
            console.log('Combined Data: ', combinedData.value);

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

            console.log('Aggregate Data:', aggregateData);
            sendAggData(aggregateData, serialNo.value);

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
const sendAggData = async (aggData, id) => {  // Make sure to pass 'id' as a parameter
    try {
        // Correct string interpolation for the URL
        const response = await axios.patch(`/api/tpmaggregateupdate/${id}`, aggData); // Proper string interpolation for URL
        // Uncomment this to see the response
        console.log('API Response sendAggData:', response.data);
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

   // Define props that Inertia will pass to the component
    const props = defineProps({
        manageSerialParam: String,  // Expecting the serialParam to be a string
    });

    console.log('Serial Param in Manage.vue:', props.manageSerialParam); // You can use this for debugging

    // onMounted logic to call the function based on serialParam existence
    onMounted(() => {
    // Log the value to check if it's being passed correctly
    console.log('Serial Param in Manage.vue:', props.manageSerialParam);
    showLoadingForGraphAndTables
    if (props.manageSerialParam) {
        showStartManageDiv.value = false;
        // If serialParam has a value, do not fetch serial
        serialNo.value = props.manageSerialParam;
        toggleManageForm.value = true;
        showGraphAndTables.value = true;
        showUploadData.value = false;
        //console.log("Showdiv graphs ",showGraphAndTables.value);
        showAllData();
        console.log('serialParam is provided, skipping fetchSerial.');
    } else {
        // If serialParam does not have a value, proceed with fetchSerial
        fetchFurnaces();
    }
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
