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
                    Generate Report
                </button>
            </div>
        </div>
        </div>

        <div v-show="showReportContent">
            <div class="flex flex-row items-center justify-center mt-10">
                <button @click="exitReport" class="px-8 py-2 mt-4 font-extrabold text-white bg-gray-500 rounded-lg shadow-md text-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900">
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
                <div class="flex flex-row items-center justify-center">
                    <button @click="saveReport" class="px-6 py-4 mt-4 font-extrabold text-white transition duration-300 ease-in-out transform bg-green-500 shadow-xl rounded-xl hover:bg-green-400 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-600 active:scale-95">
                        {{ reportExistingSMPJudgement !== null ? 'OVERWRITE' : 'SAVE' }}
                    </button>
                </div>
                <div v-show="showNotif" class="flex flex-row items-center justify-center max-w-xs px-4 py-2 mx-auto mt-10 text-white bg-green-700 rounded-md shadow-lg">
                    <p class="text-lg font-extrabold text-center">{{ reportNotificationMessage }}</p>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center px-8 py-8 my-10 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="flex flex-row">
                    <div class="flex flex-col mr-8">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">SMP Judgement</p>
                        <p class="p-2 text-center border border-black">
                            <span
                                class="inline-block w-40 bg-center h-44"
                                :style="{
                                    backgroundImage: reportRemarksDisplay === 'E'
                                        ? 'url(\'/photo/reject_stamp.png\')'
                                        : reportRemarksDisplay === 'HOLD'
                                        ? 'url(\'/photo/hold_stamp.png\')'
                                        : 'url(\'/photo/pass_stamp.png\')',
                                    backgroundSize: '150%'
                                }">
                            </span>
                        </p>
                    </div>

                    <!-- Additional Columns -->
                    <div class="flex flex-col">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">Prepared By:</p>
                        <p class="p-10 text-center border border-black"><span>(Insert Stamp here)</span></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">Check By:</p>
                        <p class="p-10 text-center border border-black"><span>(Insert Stamp here)</span></p>
                    </div>
                    <div class="flex flex-col">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">Approve By:</p>
                        <p class="p-10 text-center border border-black"><span>(Insert Stamp here)</span></p>
                    </div>
                </div>
            </div>

        </div>
      </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, toRaw  } from 'vue';

//UI Control start

const isOn = ref(false);

function toggleSwitch() {
    isOn.value = !isOn.value;
}

const showNotif = ref(false);
const showReportContent = ref(false);
const showSelectionPanel = ref(true);

const exitReport = () => {
    showReportContent.value = false;
    showSelectionPanel.value = true;
}

//UI Control end

const tpmData = ref([]);
const getTpmModel = ref('');
const inspectionDataList = ref([]);
const serialList = ref([]); // Stores all fetched furnaces
const currentSerialSelected = ref('');
const reportRemarksDisplay = ref('');

const reportNotificationMessage = ref('');

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

const generateReport = async () => {
    showReportContent.value = true;
    showSelectionPanel.value = false;
    fetchAllData();
    showReportData();
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
            console.log("The model does not exist in the inspection data!");
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
    } catch (error) {
        console.error("API get request showReportData Error:", error);
    }
}

const saveReport = async () => {
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

//Makes sure furnace lists is loaded on start.
onMounted(fetchSerial);

</script>
