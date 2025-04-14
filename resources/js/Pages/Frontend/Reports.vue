<template>
    <Frontend>
      <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">

        <div v-show="showSelectionPanel">
            <div> <!-- Selection Panel -->
            <div class="flex flex-row items-center justify-center mt-10 align-baseline">
                <p>Select Serial No: </p>
                <select
                v-model="currentSerialSelected"
                class="py-2 m-4 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm px-auto focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option v-for="serial in serialList" :key="serial" :value="serial">{{ serial }}</option>
                </select>
            </div>
            <div class="flex flex-row justify-center">
                <button @click="generateReport" class="px-4 py-2 m-10 text-xl font-extrabold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    View Report
                </button>
            </div>
            <div v-show="showNotif2" class="flex flex-row items-center justify-center py-2 mx-auto my-10 text-white bg-orange-700 rounded-md shadow-lg px-28">
                <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
            </div>
        </div>
        </div>

        <div v-show="showReportContent">
            <div class="flex flex-row items-center justify-center mt-10">
                <button v-show="showExitButton" @click="exitReport" class="px-8 py-2 mt-4 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
                    Exit
                </button>
            </div>
             <!-- Report Content -->
             <div class="flex flex-col justify-center py-10 mx-20 mt-10 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="flex flex-row mb-4 justify-evenly">
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Model:</label>&nbsp;
                        <span>{{ reportModel }}</span>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Material Code:</label>&nbsp;
                        <input v-model="reportMaterialCode" type="text" name="date" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Partial No.:</label>&nbsp;
                        <input v-model="reportPartialNo" type="text" name="shift" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Total Quantity:</label>&nbsp;
                        <input v-model="reportTotalQuantity" type="number" name="operator" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                </div>
                <div class="flex flex-row mb-4 justify-evenly">
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Pulse Tracer Machine No:</label>&nbsp;
                        <span>{{ reportPulseTracerMachineNo }}</span>
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Date:</label>&nbsp;
                        <input v-model="reportDate" type="date" name="date" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Shift:</label>&nbsp;
                        <input v-model="reportShift" type="text" name="shift" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Operator:</label>&nbsp;
                        <input v-model="reportOperator" type="text" name="operator" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                </div>

                <div class="flex flex-row items-center justify-center align-middle">
                    <table class="mt-[1.1rem] ml-[2rem] border-collapse table-auto">
                        <thead>
                            <tr class="bg-blue-300">
                                <th colspan="2" class="px-2 py-2 text-lg font-semibold text-center">STANDARD&nbsp;SAMPLE&nbsp;DIMENSION&nbsp;and&nbsp;Material&nbsp;Grade<br>標準サンプル寸法および材料グレード</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-1 border">LENGTH&nbsp;(mm)</td>
                                <td class="px-4 py-1 border">{{ reportLength }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">WIDTH&nbsp;(mm)</td>
                                <td class="px-4 py-1 border">{{ reportWidth }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">THICKNESS&nbsp;(mm)</td>
                                <td class="px-4 py-1 border ">{{ reportThickness }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">MATERIAL&nbsp;GRADE</td>
                                <td class="px-4 py-1 border">{{ reportMaterialGrade }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">MPI&nbsp;SAMPLE&nbsp;QTY.</td>
                                <td class="px-4 py-1 border">{{ reportMPISampleQty }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="ml-2 mt-[1rem] mr-[2rem] border-collapse table-auto">
                        <thead>
                            <tr class="bg-blue-300">
                                <th colspan="6" class="py-4 text-lg font-semibold text-center">MAGNETIC&nbsp;PROPERTY&nbsp;DATA(磁気特性データ)</th>
                            </tr>
                            <tr class="bg-blue-200">
                                <th rowspan="2" class="px-4 border">ITEMS</th>
                                <th rowspan="2" class="px-4 border">STANDARD</th>
                                <th colspan="4" class="px-4 text-center border">ACTUAL DATA</th>
                            </tr>
                            <tr class="bg-blue-200">
                                <th class="px-4 border">AVERAGE</th>
                                <th class="px-4">MAXIMUM</th>
                                <th class="px-4">MINIMUM</th>
                                <th class="px-4">VARIANCE<br>(max-min)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td class="px-4 py-2 border">Br (G)</td>
                                <td class="px-4 py-2 border">{{ reportBrStandard }}</td>
                                <td class="px-4 py-2 border">{{ reportBrAverage }}</td>
                                <td class="px-4 py-2 border">{{ reportBrMaximum }}</td>
                                <td class="px-4 py-2 border">{{ reportBrMinimum }}</td>
                                <td class="px-4 py-2 border">{{ reportBrVariance }}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="px-4 py-2 border">iHc (Oe)</td>
                                <td class="px-4 py-2 border">{{ reportihcStandard }}</td>
                                <td class="px-4 py-2 border">{{ reportihcAverage }}</td>
                                <td class="px-4 py-2 border">{{ reportihcMaximum }}</td>
                                <td class="px-4 py-2 border">{{ reportihcMinimum }}</td>
                                <td class="px-4 py-2 border">{{ reportiHcVariance }}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="px-4 py-2 border">iHk (Oe)</td>
                                <td class="px-4 py-2 border">{{ reportihkStandard }}</td>
                                <td class="px-4 py-2 border">{{ reportihkAverage }}</td>
                                <td class="px-4 py-2 border">{{ reportihkMaximum }}</td>
                                <td class="px-4 py-2 border">{{ reportihkMinimum }}</td>
                                <td class="px-4 py-2 border">{{ reportiHkVariance }}</td>
                            </tr>
                            <!--
                                <tr class="bg-blue-200">
                                    <th rowspan="2" class="px-4 border">Br Cpk</th>
                                    <th class="px-4 border">STD DEV</th>
                                    <th class="px-4 border">Cpu</th>
                                    <th class="px-4 border">Cpl</th>
                                    <th class="px-4 border">Cpk</th>
                                    <th class="px-4 border">Remarks</th>
                                </tr>
                                <tr class="text-center">
                                    <td class="px-4 py-2 border">NA</td>
                                    <td class="px-4 py-2 border">NA</td>
                                    <td class="px-4 py-2 border">NA</td>
                                    <td class="px-4 py-2 border">NA</td>
                                    <td class="px-4 py-2 border">NA</td>
                                </tr>
                            -->
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-row items-center justify-center mx-5 mt-5 align-middle">
                    <p class="m-5">Remarks:</p>
                    <input v-model="reportRemarks" type="text" class="w-full px-2 py-1 text-sm border rounded-md" />
                    <p class="mx-20 text-3xl font-extrabold" :class="{'text-red-500': reportRemarksDisplay === 'E', 'text-green-500': reportRemarksDisplay !== 'E', 'text-red-500': reportRemarksDisplay === 'HOLD'}">
                        {{ reportRemarksDisplay }}
                    </p>
                </div>

                <!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE --><!-- SMP JUDGEMENT STARTS HERE -->

                <div class="flex flex-row mt-10 mb-10 mx-5 p-10 justify-center items-center border-blue-800 border-2 rounded-lg">
                    <div class="flex flex-col mr-8">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">SMP Judgement</p>
                        <p class="p-2 text-center border border-black">
                            <span
                                class="inline-block w-40 h-40 bg-center bg-no-repeat"
                                :style="{
                                    backgroundImage: reportRemarksDisplay === 'E'
                                        ? 'url(\'/photo/reject_stamp.png\')'
                                        : reportRemarksDisplay === 'HOLD'
                                        ? 'url(\'/photo/hold_stamp.png\')'
                                        : 'url(\'/photo/pass_stamp.png\')',
                                    backgroundSize: '101%'
                                }">
                            </span>
                        </p>
                    </div>

                    <!-- Additional Columns -->
                    <div class="flex flex-col">
                    <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">Prepared By:</p>
                    <div class="p-2 text-center border border-black">
                        <button @click="preparedByStamp" v-show="preparedByButton" class="px-6 py-3 m-10 font-semibold text-white transition duration-300 ease-in-out bg-blue-500 rounded-lg shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Click to Stamp
                        </button>
                        <!-- Confirmation Buttons -->
                        <div class="flex flex-col justify-center gap-4" v-show="preparedByStampConfirmation">
                            <button
                                @click="confirmPreparedByStamp"
                                class="px-5 py-2 font-medium text-white transition bg-green-500 rounded-md hover:bg-green-600">
                                    Confirm Stamp
                            </button>
                            <button
                                @click="cancelPreparedByStamp"
                                class="px-5 py-2 font-medium text-white transition bg-red-500 rounded-md hover:bg-red-600">
                                    Cancel
                            </button>
                        </div>
                        <span
                            v-show="preparedByStampPhoto"
                            class="w-40 h-40 bg-center bg-no-repeat flex items-center justify-center text-2xl font-extrabold text-red-600 text-center"
                            :style="{
                                backgroundImage: 'url(\'/photo/Prepared_by_stamp.png\')',
                                backgroundSize: '101%'
                            }">
                            {{ reportPreparedByDate }}
                        </span>
                    </div>
                </div>
                    <div class="flex flex-col">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">Checked By:</p>
                        <div class="p-2 text-center border border-black">
                            <button @click="checkedByStamp" v-show="checkedByButton" class="px-6 py-3 m-10 font-semibold text-white transition duration-300 ease-in-out bg-blue-500 rounded-lg shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Click to Stamp
                            </button>
                            <!-- Confirmation Buttons -->
                            <div class="flex flex-col justify-center gap-4" v-show="checkedByStampConfirmation">
                                <button
                                    @click="confirmCheckedByStamp"
                                    class="px-5 py-2 font-medium text-white transition bg-green-500 rounded-md hover:bg-green-600">
                                        Confirm Stamp
                                </button>
                                <button
                                    @click="cancelCheckedByStamp"
                                    class="px-5 py-2 font-medium text-white transition bg-red-500 rounded-md hover:bg-red-600">
                                        Cancel
                                </button>
                            </div>
                            <span v-show="checkedByStampPhoto"
                                class="w-40 h-40 bg-center bg-no-repeat flex items-center justify-center text-2xl font-extrabold text-red-600 text-center"
                                :style="{
                                    backgroundImage: 'url(\'/photo/Checked_by_stamp.png\')',
                                    backgroundSize: '101%'
                                }">
                                {{ reportCheckedByDate }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">Approved By:</p>
                        <div class="p-2 text-center border border-black">
                            <span v-show="approvedByStampPhoto"
                                class="w-40 h-40 bg-center bg-no-repeat flex items-center justify-center text-2xl font-extrabold text-red-600 text-center"
                                :style="{
                                    backgroundImage: 'url(\'/photo/Approved_by_stamp.png\')',
                                    backgroundSize: '101%'
                                }">
                                {{ reportApprovedByDate }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here --><!-- SAVE AND VIEW PROPERTY DATA START here -->

                <div v-show="showNotif3" class="flex flex-row items-center justify-center max-w-xs px-4 py-2 mx-auto mt-10 text-white bg-green-700 rounded-md shadow-lg">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
                <div class="flex flex-row items-center justify-center">
                    <button v-show="showReportSaveButton" @click="saveReport" class="px-6 py-4 mt-4 font-extrabold text-white transition duration-300 ease-in-out transform bg-green-500 shadow-xl rounded-xl hover:bg-green-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-600 active:scale-95">
                        {{ reportExistingSMPJudgement !== null ? 'OVERWRITE' : 'SAVE' }}
                    </button>
                    <button @click="viewPropertyData(currentSerialSelected)" class="px-6 py-4 mt-4 ml-5 font-extrabold text-blue-700 transition duration-300 ease-in-out transform border border-blue-700 shadow-xl hover:text-white rounded-xl hover:bg-blue-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-600 active:scale-95">
                        View Property Data
                    </button>
                </div>
                <div v-show="showNotif" class="flex flex-row items-center justify-center max-w-xs px-4 py-2 mx-auto mt-10 text-white bg-green-700 rounded-md shadow-lg">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
            </div>
            
        </div>
      </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, toRaw  } from 'vue';
import { Inertia } from '@inertiajs/inertia';

//UI Control start

const isOn = ref(false);

function toggleSwitch() {
    isOn.value = !isOn.value;
}

const showNotif = ref(false);
const showNotif2 = ref(false);
const showNotif3 = ref(false);
const showReportContent = ref(false);
const showSelectionPanel = ref(true);
const showReportSaveButton = ref(true);
const showExitButton = ref(true);

const preparedByStampConfirmation = ref(false);
const checkedByStampConfirmation = ref(false);

const preparedByButton = ref(true);
const checkedByButton = ref(true);
const approvedByButton = ref(true);
const preparedByStampPhoto = ref(false);
const checkedByStampPhoto = ref(false);
const approvedByStampPhoto = ref(false);

const exitReport = () => {
    showReportContent.value = false;
    showSelectionPanel.value = true;
    fetchSerial();
}

//UI Control end

//general variables start

const tpmData = ref([]);
const getTpmModel = ref('');
const inspectionDataList = ref([]);
const serialList = ref([]); // Stores all fetched furnaces
const currentSerialSelected = ref('');
const reportRemarksDisplay = ref('');
const ipAddress = ref('');
const preparedByPerson = ref('');
const checkedByPerson = ref('');
const approvedByPerson = ref('');

const reportNotificationMessage = ref('');

//general variables end


/// to be put in the form
const reportModel = ref('NA');
const reportPulseTracerMachineNo = ref('NA');
const reportMaterialCode = ref('NA');
const reportDate = ref('');
const reportPartialNo = ref('NA');
const reportShift = ref('NA');
const reportTotalQuantity = ref('0');
const reportOperator = ref('NA');
const reportRemarks = ref('NA');

const reportLength = ref('NA');
const reportWidth = ref('NA');
const reportThickness = ref('NA');
const reportMaterialGrade = ref('NA');
const reportMPISampleQty = ref('NA');

const reportBrStandard = ref('NA');
const reportBrAverage = ref('NA');
const reportBrMaximum = ref('NA');
const reportBrMinimum = ref('NA');
const reportihcStandard = ref('NA');
const reportihcAverage = ref('NA');
const reportihcMaximum = ref('NA');
const reportihcMinimum = ref('NA');
const reportihkStandard = ref('NA');
const reportihkAverage = ref('NA');
const reportihkMaximum = ref('NA');
const reportihkMinimum = ref('NA');

const reportBrVariance = ref('NA');
const reportiHcVariance = ref('NA');
const reportiHkVariance = ref('NA');

const reportSMPJudgement = ref('');
const reportExistingSMPJudgement = ref(null);
const reportPreparedByDate = ref(null);
const reportCheckedByDate = ref(null);
const reportApprovedByDate = ref(null);

/// to be put in the form end

const inspectionLength = ref('');
const inspectionWidth = ref('');
const inspectionThickness = ref('');
const inspectionMaterialGrade = ref('');
const inspectionMpiSampleQty = ref('');
const inspectionModel = ref('');
const inspectionBrStandard = ref('');
const inspectioniHcStandard = ref('');
const inspectioniHkStandard = ref('');

const tpmData_brAve = ref('');
const tpmData_brMax = ref('');
const tpmData_brMin = ref('');
const tpmData_ihcAve = ref('');
const tpmData_ihcMax = ref('');
const tpmData_ihcMin = ref('');
const tpmData_ihkAve = ref('');
const tpmData_ihkMax = ref('');
const tpmData_ihkMin = ref('');
const tpmData_tracerNo = ref('');

const showNotification = (message) => {
    // Show notification and set the message
    showNotif.value = true;
    reportNotificationMessage.value = message;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        showNotif.value = false;
    }, 3000);  // 3000ms = 3 seconds
}

const showNotification2 = (message) => {
    // Show notification and set the message
    showNotif2.value = true;
    reportNotificationMessage.value = message;

    // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(() => {
        showNotif2.value = false;
    }, 3000);  // 3000ms = 3 seconds
}

const generateReport = async () => {
    showReportContent.value = true;
    showSelectionPanel.value = false;
    await fetchAllData();
    await showReportData();
}

const fetchAllData = async () => {
    try {
        const responseTpm = await axios.get("/api/tpmdata?serial=" + currentSerialSelected.value);
        //console.log("Show All tpm data API response: ", responseTpm.data);

        // Filter the data to include only rows with serial_no like '0002'
        tpmData.value = responseTpm.data[0] || [];
        //console.log("Filtered tpm aggregate data list: ", tpmData.value);
        getTpmModel.value = responseTpm.data.data || [];
        //console.log("GetModelValue: ", getTpmModel.value[0].code_no);

        //Remarks checking start
        const getAllBrNG = getTpmModel.value.map(item => item.remark.Br_remarks || null);
        const getAlliHcNG = getTpmModel.value.map(item => item.remark.iHc_remarks || null);
        const getAlliHkNG = getTpmModel.value.map(item => item.remark.iHk_remarks || null);
        const getAll4paildNG = getTpmModel.value.map(item => item.remark["4paild_remarks"] || null);
        const getAll4pailsNG = getTpmModel.value.map(item => item.remark["4pails_remarks"] || null);
        const getAll4pailaNG = getTpmModel.value.map(item => item.remark["4paila_remarks"] || null);
        const getAllbHcNG = getTpmModel.value.map(item => item.remark.bHc_remarks || null);
        const getAllBHMaxNG = getTpmModel.value.map(item => item.remark.BHMax_remarks || null);
        const getAllSquarenessNG = getTpmModel.value.map(item => item.remark.Squareness_remarks || null);
        const getAllDensityNG = getTpmModel.value.map(item => item.remark.Density_remarks || null);
        const getAlliHkiHcNG = getTpmModel.value.map(item => item.remark.iHkiHc_remarks || null);
        const getAllBr4paiNG = getTpmModel.value.map(item => item.remark.Br4pai_remarks || null);
        const getAlliHr95NG = getTpmModel.value.map(item => item.remark.iHr95_remarks || null);
        const getAlliHr98NG = getTpmModel.value.map(item => item.remark.iHr98_remarks || null);
        console.log("check br remarks: ", getAllBrNG);
        // Check if "1" exists in getAllBrNG
        if (getAllBrNG.includes("1") || getAlliHcNG.includes("1") || getAlliHkNG.includes("1") || getAll4paildNG.includes("1") || getAll4pailsNG.includes("1") || getAll4pailaNG.includes("1") || getAllbHcNG.includes("1") || getAllBHMaxNG.includes("1") || getAllSquarenessNG.includes("1") || getAllDensityNG.includes("1") || getAlliHkiHcNG.includes("1") || getAllBr4paiNG.includes("1") || getAlliHr95NG.includes("1") || getAlliHr98NG.includes("1")) {
            // Perform your action here (leave it blank for now)
            reportRemarksDisplay.value = "E";
            reportSMPJudgement.value = "REJECT";
            if(getAlliHr95NG.includes("1") || getAlliHr98NG.includes("1")){
                reportRemarksDisplay.value = "HOLD";
                reportSMPJudgement.value = reportRemarksDisplay.value;
            }
        }else{
            reportRemarksDisplay.value = "OK";
            reportSMPJudgement.value = "PASSED";
        }
        //Remarks checking end


        const tpm_current_model = getTpmModel.value[0].code_no;
        tpmData_tracerNo.value = getTpmModel.value[0].Tracer;

        //console.log("currently selected serial: ",currentSerialSelected.value);
        //console.log("Aggregate Averages: ",tpmData.value[0].average);
        // Access the 'average' property (which is a stringified JSON)
        const averageJsonString = tpmData.value[0].average;
        const maximumJsonString = tpmData.value[0].maximum;
        const minimumJsonString = tpmData.value[0].minimum;

        // Parse the stringified JSON into an actual JavaScript object
        const averageData = JSON.parse(averageJsonString);
        const maximumData = JSON.parse(maximumJsonString);
        const minimumData = JSON.parse(minimumJsonString);

        // Now you can access the variables inside the parsed object
        //console.log("Parsed Aggregate Averages: ", averageData);
        tpmData_brAve.value = averageData.Br;
        tpmData_brMax.value = maximumData.Br;
        tpmData_brMin.value = minimumData.Br;
        tpmData_ihcAve.value = averageData.iHc;
        tpmData_ihcMax.value = maximumData.iHc;
        tpmData_ihcMin.value = minimumData.iHc;
        tpmData_ihkAve.value = averageData.iHk;
        tpmData_ihkMax.value = maximumData.iHk;
        tpmData_ihkMin.value = minimumData.iHk;

        const responseInsp = await axios.get("/api/inspectiondata");
        console.log("Show All inspection data API response: ", responseInsp.data);
        inspectionDataList.value = responseInsp.data.data || [];
        console.log("Show All inspection data list : ", inspectionDataList.value);

        const getAllInspModels = inspectionDataList.value.map(item => item.model);
        console.log("List of models in inspection: ", getAllInspModels);
        console.log("Current model in tpm: ",tpm_current_model);

        // Check if tpm_current_model exists in getAllInspModels
        if (getAllInspModels.includes(tpm_current_model)) {
            const filteredInspectionData = inspectionDataList.value.filter(item => item.model == tpm_current_model);
            console.log("Filtered inspection data for the selected model: ", filteredInspectionData);
            // Access the `br` value for each item in filteredInspectionData
            filteredInspectionData.forEach(item => {
                inspectionBrStandard.value = item.br;
                inspectioniHcStandard.value = item.ihc;
                inspectioniHkStandard.value = item.ihk;
                inspectionLength.value = item.length;
                inspectionWidth.value = item.width;
                inspectionThickness.value = item.thickness;
                inspectionMaterialGrade.value = item.material_grade;
                inspectionMpiSampleQty.value = item.mpi_sample;
            });

        } else {
            showNotification2("The model does not exist in the inspection data!")
            showReportContent.value = false;
            showSelectionPanel.value = true;
            return;
        }

        console.log("Getting br value: ", inspectionBrStandard.value);  // Assuming each item has a `br` property


        const repData = {
            "date": reportDate.value,
            "length": inspectionLength.value,
            "magnetic_property_data": JSON.stringify({
                "brStandard": inspectionBrStandard.value,
                "brAverage": tpmData_brAve.value,
                "brMaximum": tpmData_brMax.value,
                "brMinimum": tpmData_brMin.value,
                "ihcStandard": inspectioniHcStandard.value,
                "ihcAverage": tpmData_ihcAve.value,
                "ihcMaximum": tpmData_ihcMax.value,
                "ihcMinimum": tpmData_ihcMin.value,
                "ihkStandard": inspectioniHkStandard.value,
                "ihkAverage": tpmData_ihkAve.value,
                "ihkMaximum": tpmData_ihkMax.value,
                "ihkMinimum": tpmData_ihkMin.value,
            }),
            "material_grade": inspectionMaterialGrade.value,
            "model": tpm_current_model,
            "mpi_sample_quantity": inspectionMpiSampleQty.value,
            "pulse_tracer_machine_number": tpmData_tracerNo.value,
            "thickness": inspectionThickness.value,
            "width": inspectionWidth.value
        }

        console.log("Rep Data: ",repData);
        createReport(repData, currentSerialSelected.value);

    } catch (error) {
        console.error("API get request showTpmData Error:", error);
    }
};

const createReport = async (reportData, serial) => {
    try {
        const response = await axios.patch(`/api/reportdata/${serial}`, reportData);
        console.log("Patched report data: ", response.data);
    } catch (error) {
        console.error("Patch report data Error:", error);
    }
}

const showReportData = async () => {
    try {
        const response = await axios.get(`/api/reportdata/`);
        console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        console.log("Filtered data: ", filterBySerial);
        reportModel.value = filterBySerial[0].model;
        reportPulseTracerMachineNo.value = filterBySerial[0].pulse_tracer_machine_number;
        reportMaterialCode.value = filterBySerial[0].material_code;
        reportDate.value = filterBySerial[0].date;
        reportPartialNo.value = filterBySerial[0].partial_number;
        reportShift.value = filterBySerial[0]["shift"];
        reportTotalQuantity.value = filterBySerial[0].total_quantity;
        reportOperator.value = filterBySerial[0].operator;
        reportLength.value = filterBySerial[0].length;
        reportWidth.value = filterBySerial[0].width;
        reportThickness.value = filterBySerial[0].thickness;
        reportMaterialGrade.value = filterBySerial[0].material_grade;
        reportMPISampleQty.value = filterBySerial[0].mpi_sample_quantity;
        reportRemarks.value = filterBySerial[0].remarks;
        reportExistingSMPJudgement.value = filterBySerial[0].smp_judgement;
        reportPreparedByDate.value = filterBySerial[0].prepared_by_date
        ? filterBySerial[0].prepared_by_date.split(' ')[0]
        : '';
        reportCheckedByDate.value = filterBySerial[0].checked_by_date
        ? filterBySerial[0].checked_by_date.split(' ')[0]
        : '';
        reportApprovedByDate.value = filterBySerial[0].approved_by_date
        ? filterBySerial[0].approved_by_date.split(' ')[0]
        : '';



        console.log("Model value: ",reportModel.value);

        //console.log("Report Data Model", reportModel.value);

        const magneticProperty = JSON.parse(filterBySerial[0].magnetic_property_data);

         // Extracting BR, IHC, and IHK properties

        // BR values
        reportBrStandard.value = magneticProperty.brStandard;
        reportBrAverage.value = magneticProperty.brAverage;
        reportBrMaximum.value = magneticProperty.brMaximum;
        reportBrMinimum.value = magneticProperty.brMinimum;

        // IHC values
        reportihcStandard.value = magneticProperty.ihcStandard;
        reportihcAverage.value = magneticProperty.ihcAverage;
        reportihcMaximum.value = magneticProperty.ihcMaximum;
        reportihcMinimum.value = magneticProperty.ihcMinimum;

        // IHK values
        reportihkStandard.value = magneticProperty.ihkStandard;
        reportihkAverage.value = magneticProperty.ihkAverage;
        reportihkMaximum.value = magneticProperty.ihkMaximum;
        reportihkMinimum.value = magneticProperty.ihkMinimum;

        // Convert strings to numbers before performing subtraction
        reportBrVariance.value = parseFloat(reportBrMaximum.value) - parseFloat(reportBrMinimum.value);
        reportiHcVariance.value = parseFloat(reportihcMaximum.value) - parseFloat(reportihcMinimum.value);
        reportiHkVariance.value = parseFloat(reportihkMaximum.value) - parseFloat(reportihkMinimum.value);

        checkApprovalStates();
    } catch (error) {
        console.error("API get request showReportData Error:", error);
    }
}

const saveReport = async () => {
    // Use default values (empty string) or check for null/undefined before calling toUpperCase
    reportMaterialCode.value = (reportMaterialCode.value || '').toUpperCase();
    reportPartialNo.value = (reportPartialNo.value || '').toUpperCase();
    reportShift.value = (reportShift.value || '').toUpperCase();
    reportOperator.value = (reportOperator.value || '').toUpperCase();

    const saveReportData = {
        "material_code": reportMaterialCode.value,
        "date": reportDate.value,
        "partial_number": reportPartialNo.value,
        "shift": reportShift.value,
        "total_quantity": reportTotalQuantity.value,
        "operator": reportOperator.value,
        "remarks": reportRemarks.value,
        "smp_judgement":reportSMPJudgement.value,
    }

    console.log("Save report data: ", saveReportData);

    saveReportUpdate(saveReportData, currentSerialSelected.value);

    setTimeout(() => {
        showReportContent.value = false;
        showSelectionPanel.value = true;
    },1000);
}

const saveReportUpdate = async (saveData, serial) => {
    try{
        const responseSave = await axios.patch(`/api/reportdata/${serial}`, saveData);
        console.log("Saved Report data: ", responseSave.data);
        checkApprovalStates();
        showNotification("Saved Successfully");
    }catch (error){
        console.error("Patch report data Error:", error);
    }
}

// Fetching the serial start

// Function to fetch serial data
const fetchSerial = async () => {
  try {
    const response = await axios.get("/api/tpmdata");
    //console.log("API Response:", response.data);

    // Extract furnace data dynamically
    tpmData.value = response.data.data["tpmData"] || [];
    //console.log("TPM DATA response: ", tpmData.value);

    // Extract unique serial numbers by using a Set
    serialList.value = [...new Set(tpmData.value.map(item => item.serial_no))];

    // Sort the serialList in descending order (highest value first)
    serialList.value = serialList.value.sort((a, b) => {
      return Number(b) - Number(a); // Convert to number for proper sorting
    });

    //console.log("Unique Serial lists (Descending):", serialList.value);

    // Set default selection to first serial, if available
    if (serialList.value.length > 0) {
        currentSerialSelected.value = serialList.value[0];
    }

  } catch (error) {
    console.error("Error fetching serial data:", error);
  }
};
// Fetching the serial start end

// Define the prop that will receive the serialParam
const props = defineProps({
  serialParam: String,  // Expecting the serialParam to be a string
  ipAddress: String,
});

ipAddress.value = props.ipAddress;
console.log('Current IP address is:', props.ipAddress); // You can use this for debugging
console.log('Serial Param in Reports.vue:', props.serialParam); // You can use this for debugging

const viewPropertyData = (serial) => {
  console.log('Navigating to manage with serial:', serial);
  Inertia.visit('/manage', {
    method: 'get',   // You can keep 'get' since we are not modifying any data
    data: { manageSerialParam: serial },   // Passing the serialParam here
    preserveState: true,
    preserveScroll: true,
  });
};

const preparedByStamp = () => {
    preparedByButton.value = false;
    preparedByStampConfirmation.value = true;
}

const cancelPreparedByStamp = () => {
    preparedByButton.value = true;
    preparedByStampConfirmation.value = false;
}

const confirmPreparedByStamp = async () => {
    preparedByPerson.value = "IRISH MERCADO"; //Temporary Hardcode. Replace with Assigned IP address linked to a person
    preparedByStampPhoto.value = true;
    preparedByStampConfirmation.value = false;
    const dateNow = datenow();
    console.log("Prepared By Has been stamped by -> ", preparedByPerson.value);
    const preparedByData = {
        prepared_by: preparedByPerson.value, // Set the approved_by field to "ITADANI KAZUYA"
        prepared_by_date: dateNow
    };

    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, preparedByData);
    console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);
    showReportData();
}

const datenow = () => {
    const now = new Date();
    const pad = (n) => n.toString().padStart(2, '0');

    const year = now.getFullYear();
    const month = pad(now.getMonth() + 1); // Months are zero-based
    const day = pad(now.getDate());

    const hours = pad(now.getHours());
    const minutes = pad(now.getMinutes());
    const seconds = pad(now.getSeconds());

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const checkedByStamp = () => {
    checkedByButton.value = false;
    checkedByStampConfirmation.value = true;
}

const cancelCheckedByStamp = () => {
    checkedByButton.value = true;
    checkedByStampConfirmation.value = false;
}

const confirmCheckedByStamp = async () => {
    checkedByPerson.value = "CHECKED BY PERSON"; //Temporary Hardcode. Replace with Assigned IP address linked to a person
    checkedByStampPhoto.value = true;
    checkedByStampConfirmation.value = false;
    const dateNow = datenow();
    console.log("Checked By Has been stamped by -> ", checkedByPerson.value);
    const checkedByData = {
        checked_by: checkedByPerson.value,
        checked_by_date: dateNow
    };

    const response = await axios.patch(`/api/reportdata/${currentSerialSelected.value}`, checkedByData);
    console.log(`Successfully approved report with serial ${currentSerialSelected.value}:`, response.data);
    showReportData();
}

const checkApprovalStates = async () => {
    try{
        console.log("Checking approval states...");
        const response = await axios.get(`/api/reportdata/`);
        //console.log("Getting report data API result: ", response.data.data);
        const filterBySerial = response.data.data.filter(column => column.tpm_data_serial == currentSerialSelected.value); // filter by serial
        //console.log("Filtered data: ", filterBySerial);

        const prepared_by = filterBySerial[0].prepared_by;
        const checked_by = filterBySerial[0].checked_by;
        const approved_by = filterBySerial[0].approved_by;

        console.log("prepared by: ",prepared_by);
        console.log("checked by: ",checked_by);
        console.log("approved by: ",approved_by);

        if(prepared_by == "" || prepared_by == null){
            preparedByButton.value = true;
            preparedByStampPhoto.value = false;
        }else{
            preparedByButton.value = false;
            preparedByStampPhoto.value = true;
        }
        if(checked_by == "" || checked_by == null){
            checkedByButton.value = true;
            checkedByStampPhoto.value = false;
        }else{
            checkedByButton.value = false;
            checkedByStampPhoto.value = true;
        }
        if(approved_by == "" || approved_by == null){
            approvedByButton.value = true;
            approvedByStampPhoto.value = false;
        }else{
            approvedByButton.value = false;
            approvedByStampPhoto.value = true;
            showReportSaveButton.value = false;
        }
    }catch(error){

    }
}

// onMounted logic to call the function based on serialParam existence
onMounted(() => {

  if (props.serialParam) {
    // If serialParam has a value, do not fetch serial
    // Placeholder for additional actions when serialParam exists
    currentSerialSelected.value = props.serialParam;
    showReportContent.value = true;
    showSelectionPanel.value = false;
    showReportSaveButton.value = false;
    showExitButton.value = false;
    fetchAllData();
    showReportData();
    console.log('serialParam is provided, skipping fetchSerial.');
  } else {
    // If serialParam does not have a value, proceed with fetchSerial
    fetchSerial();
  }
});
</script>
