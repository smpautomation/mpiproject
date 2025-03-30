<template>
    <Frontend>
      <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
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
                <button @click="generateReport" class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    Generate Report
                </button>
            </div>
        </div>

        <!-- Report Content -->
            <div class="flex flex-col justify-center py-10 mx-20 mt-10 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="flex flex-row mb-4 justify-evenly">
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Model:</label>&nbsp;
                        <input type="text" name="pulseTracerMachineNo" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Material Code:</label>&nbsp;
                        <input type="text" name="date" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Partial No.:</label>&nbsp;
                        <input type="text" name="shift" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Total Quantity:</label>&nbsp;
                        <input type="number" name="operator" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                </div>
                <div class="flex flex-row mb-4 justify-evenly">
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Pulse Tracer Machine No:</label>&nbsp;
                        <input type="text" name="pulseTracerMachineNo" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Date:</label>&nbsp;
                        <input type="date" name="date" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Shift:</label>&nbsp;
                        <input type="text" name="shift" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Operator:</label>&nbsp;
                        <input type="text" name="operator" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
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
                                <td class="px-4 py-1 border">NA</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">WIDTH&nbsp;(mm)</td>
                                <td class="px-4 py-1 border">NA</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">THICKNESS&nbsp;(mm)</td>
                                <td class="px-4 py-1 border ">NA</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">MATERIAL&nbsp;GRADE</td>
                                <td class="px-4 py-1 border">NA</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">MPI&nbsp;SAMPLE&nbsp;QTY.</td>
                                <td class="px-4 py-1 border">NA</td>
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
                                <td class="px-4 py-2 border">NA</td>
                                <td class="px-4 py-2 border">NA</td>
                                <td class="px-4 py-2 border">NA</td>
                                <td class="px-4 py-2 border">NA</td>
                                <td class="px-4 py-2 border">NA</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border">iHc (Oe)</td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border">iHk (Oe)</td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr class="bg-blue-200">
                                <th rowspan="2" class="px-4 border">Br Cpk</th>
                                <th class="px-4 border">STD DEV</th>
                                <th class="px-4 border">Cpu</th>
                                <th class="px-4 border">Cpl</th>
                                <th class="px-4 border">Cpk</th>
                                <th class="px-4 border">Remarks</th>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-row items-center justify-center mx-5 mt-5 align-middle">
                    <p class="m-5">Remarks:</p>
                    <input type="text" class="w-full px-2 py-1 text-sm border rounded-md" />
                    <p class="mx-20 text-3xl text-blue-500">OK</p>
                </div>

            </div>

            <div class="flex flex-col justify-center px-8 py-8 my-10 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="flex flex-row">
                    <div class="flex flex-col mr-8">
                        <p class="p-2 text-xl font-extrabold text-center bg-blue-300 border border-black">SMP Judgement</p>
                        <p class="p-10 text-center border border-black"><span>(Insert Stamp here)</span></p>
                    </div>
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
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted } from 'vue';


const tpmData = ref([]);
const getTpmModel = ref('');
const inspectionDataList = ref([]);
const serialList = ref([]); // Stores all fetched furnaces
const currentSerialSelected = ref('');

/// to be put in the form
const reportModel = ref('');
const reportPulseTracerMachineNo = ref('');
const reportMaterialCode = ref('');
const reportDate = ref('');
const reportPartialNo = ref('');
const reportShift = ref('');
const reportTotalQuantity = ref('');
const reportOperator = ref('');

const reportLength = ref('');
const reportWidth = ref('');
const reportThickness = ref('');
const reportMaterialGrade = ref('');
const reportMPISampleQty = ref('');

const reportBrStandard = ref('');
const reportBrAverage = ref('');
const reportBrMaximum = ref('');
const reportBrMinimum = ref('');
const reportihcStandard = ref('');
const reportihcAverage = ref('');
const reportihcMaximum = ref('');
const reportihcMinimum = ref('');
const reportihkStandard = ref('');
const reportihkAverage = ref('');
const reportihkMaximum = ref('');
const reportihkMinimum = ref('');

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

const generateReport = async () => {
    fetchAllData();
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

        const tpm_current_model = getTpmModel.value[0].code_no;

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
        console.log("Current model in tpm: ",tpm_current_model)

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
            "approved_by": null,
            "br_crpk_remarks": null,
            "checked_by": null,
            "cpk": null,
            "cpl": null,
            "cpu": null,
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
            "material_code": null,
            "material_grade": inspectionMaterialGrade.value,
            "model": tpm_current_model,
            "mpi_sample_quantity": inspectionMpiSampleQty.value,
            "operator": null,
            "partial_number": null,
            "prepared_by": null,
            "pulse_tracer_machine_number": reportPulseTracerMachineNo.value,
            "remarks": null,
            "shift": null,
            "smp_judgement": null,
            "std_dev": null,
            "thickness": inspectionThickness.value,
            "total_quantity": null,
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
        console.log("Patched report data: ",response.data);
    } catch (error) {
        console.error("Patch report data Error:", error);
    }
}


// Fetching the serial start

    // Function to fetch serial data
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
