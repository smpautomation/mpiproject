<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
            <div>
                <div>
                    <p><span>{{ redirectedMassPro }}</span> Mass Production</p>
                </div>
                <table id="controlSheetTable" class="table-auto border border-gray-300 border-collapse ">
                    <thead>
                        <tr :class="[headerFontSize]">
                          <th colspan="2" rowspan="2" :class="[borderColor, headerPaddings]">
                            #
                          </th>
                          <th colspan="11" :class="[borderColor, headerPaddings]">
                            <p>Heat Treatment Production Control Sheet</p>
                          </th>
                          <th rowspan="2" :class="[borderColor, headerPaddings]">
                            TOTAL WT(KG)
                          </th>
                          <th rowspan="2" :class="[borderColor, headerPaddings]">
                            TOTAL QTY(PCS)
                          </th>
                          <th rowspan="2" :class="[borderColor, headerPaddings]">
                            MPI Sample Qty.
                          </th>
                        </tr>
                        <tr :class="[headerFontSize]">
                          <th
                          v-for="(letter, index) in controlSheet_headerLetters"
                          :key="index"
                          :class="[borderColor, headerPaddings]"
                          >
                            {{ letter }}
                          </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                          v-for="(layer, layerIndex) in controlSheet_layers"
                          :key="layerIndex"
                        >
                          <tr
                            v-for="(row, rowIndex) in controlSheet_dataMatrix[layer]"
                            :key="`${layer}-${rowIndex}`"
                            :class="[cellFontSize]"
                          >
                            <!-- Left Layer Cell (rowspan once per layer) -->
                            <td
                              v-if="rowIndex === 0"
                              :rowspan="controlSheet_rowTitles.length"
                              :class="[borderColor, cellPaddings]"
                            >
                              {{ layer }}
                            </td>

                            <!-- Row Title -->
                            <td :class="[borderColor, cellPaddings]">
                              {{ row.rowTitle }}
                            </td>

                            <!-- Data Cells -->
                            <td
                              v-for="letter in controlSheet_headerLetters"
                              :key="letter"
                              :class="[borderColor, cellPaddings]"
                              class="text-center"
                            >
                              {{ row.data[letter] }}
                            </td>
                            <!-- Final 3 total cells (only show once per layer, at last row) -->
                            <template v-if="rowIndex === 0">
                              <td
                                :rowspan="controlSheet_dataMatrix[layer].length"
                                :class="[borderColor, cellPaddings]"
                              >
                                {{  }}
                              </td>
                              <td
                                :rowspan="controlSheet_dataMatrix[layer].length"
                                :class="[borderColor, cellPaddings]"
                              >
                                {{ }}
                              </td>
                              <td
                                :rowspan="controlSheet_dataMatrix[layer].length"
                                :class="[borderColor, cellPaddings]"
                              >
                                {{ }}
                              </td>
                            </template>
                          </tr>
                        </template>
                        <tr :class="[headerFontSize]">
                          <td v-for="n in 11" :key="n"></td>
                          <th colspan="2" :class="[borderColor]">GRAND TOTAL</th>
                          <th :class="[borderColor]">

                          </th>
                          <th :class="[borderColor]">

                          </th>
                          <th :class="[borderColor]">

                          </th>
                        </tr>
                    </tbody>
                </table>
                <div class="p-2 border border-gray-400">
                  <p class="font-bold">Heat Treatment Information</p>
                  <div class="flex flex-row">
                    <div class="flex flex-col text-end gap-[1px] pt-[1px]">
                        <label :class="[ht_info_fontsize]">BATCH CYCLE No:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">MACHINE No:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">CYCLE No:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">PATTERN No:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">Cycle Pattern:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">Current Pattern:&nbsp;</label>
                    </div>
                    <div class="flex flex-col pr-4">
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.batchCycleNo ?? 'NA' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.machineNo ?? 'NA' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.cycleNo ?? 'NA' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.patternNo ?? 'NA' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline, controlSheet_ht_info_values.cyclePattern === 'ABNORMAL' ? 'text-red-500 font-extrabold' : '']">{{ controlSheet_ht_info_values.cyclePattern ?? 'NA' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline, controlSheet_ht_info_values.currentPattern === 'ABNORMAL' ? 'text-red-500 font-extrabold' : '']">{{ controlSheet_ht_info_values.currentPattern ?? 'NA' }}</span>
                    </div>
                    <div class="flex flex-col text-end gap-[1px] pt-[1px]">
                        <label :class="[ht_info_fontsize]">DATE START:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">TIME START:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">LOADER:&nbsp;</label>

                    </div>
                    <div class="flex flex-col pr-4">
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.dateStart ?? null }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.timeStart ?? null }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.loader ?? 'NA' }}</span>
                    </div>
                    <div class="flex flex-col text-end gap-[1px] pt-[1px]">
                            <label :class="[ht_info_fontsize]">DATE FINISHED:&nbsp;</label>
                            <label :class="[ht_info_fontsize]">TIME FINISHED:&nbsp;</label>
                            <label :class="[ht_info_fontsize]">UNLOADER:&nbsp;</label>
                    </div>
                    <div class="flex flex-col pr-4">
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.dateFinished ?? null }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.timeFinished ?? null }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.unloader ?? 'NA' }}</span>
                    </div>
                    <div class="flex flex-col text-end gap-[1px] pt-[1px]">
                            <label :class="[ht_info_fontsize]">BOX CONDITION:&nbsp;</label>
                            <label :class="[ht_info_fontsize]">BOX COVER:&nbsp;</label>
                            <label :class="[ht_info_fontsize]">BOX ARRANGEMENT:&nbsp;</label>
                            <label :class="[ht_info_fontsize]">ENCODED BY:&nbsp;</label>
                    </div>
                    <div class="flex flex-col pr-4">
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.boxCondition ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.boxCover ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.boxArrangement ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.encodedBy ?? '' }}</span>
                    </div>
                    <div class="flex flex-col text-end gap-[1px] pt-[1px]">
                        <label :class="[ht_info_fontsize]" class="font-bold">REMARKS:&nbsp;</label>
                    </div>
                    <div class="flex flex-col pr-4">
                        <span :class="[ht_info_fontsize, ht_info_underline2]">{{ controlSheet_ht_info_values.remarks1 ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline2]">{{ controlSheet_ht_info_values.remarks2 ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline2]">{{ controlSheet_ht_info_values.remarks3 ?? '' }}</span>
                    </div>
                  </div>
                </div>
                <div class="flex flex-row justify-end mt-3">
                    <button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-200">
                        Download Excel
                    </button>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js'

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

const massProd_list = ref([]);
const selectedMassPro = ref();
const redirectedMassPro = ref();
const controlSheet_props = defineProps({
    massProd: String
});
redirectedMassPro.value = controlSheet_props.massProd;
console.log(redirectedMassPro.value);

const tableProperties = {
    borderColor: 'border border-gray-400',
    headerPaddings: 'px-3 py-1',
    cellPaddings: 'px-1 py-1',
    headerFontSize: 'text-[14px]',
    cellFontSize: 'text-[9px]',
}

const heatTreatmentInformationProperties = {
   fontSize: 'text-[10px]',
   inputBoxSize: 'px-0 py-0',
   underlineEffect: 'inline-block border-b border-black w-24 text-start',
   underlineEffect2: 'inline-block border-b border-black w-80 text-start'
}

const borderColor = ref(tableProperties.borderColor);
const headerPaddings = ref(tableProperties.headerPaddings);
const headerFontSize = ref(tableProperties.headerFontSize);
const cellFontSize = ref(tableProperties.cellFontSize);
const cellPaddings = ref(tableProperties.cellPaddings);

const ht_info_fontsize = ref(heatTreatmentInformationProperties.fontSize);
const ht_info_inputBoxSize = ref(heatTreatmentInformationProperties.inputBoxSize);
const ht_info_underline = ref(heatTreatmentInformationProperties.underlineEffect);
const ht_info_underline2 = ref(heatTreatmentInformationProperties.underlineEffect2);

const controlSheet_headerLetters = ref(['A','B','C','D','E','F','G','H','J','K','L']);
const controlSheet_layers = ref(['9.5','9','8','7','6','5','4','3','2','1']);
const controlSheet_rowTitles = ref([
  'MODEL:',
  'COATING M/C No.:',
  'LT. No.:',
  'QTY (PCS):',
  'HT (PCS):',
  'LT (PCS):',
  'COATING:',
  'WT (KG):',
  'BOX No.:',
  'Magnet prepared by:',
  'Box prepared by:',
]);

const controlSheet_ht_info_values = ref({
    batchCycleNo: 'NA',
    machineNo: 'NA',
    cycleNo: 'NA',
    patternNo: 'NA',
    cyclePattern: 'NA',
    currentPattern: 'NA',
    dateStart: 'NA',
    timeStart: 'NA',
    loader: 'NA',
    dateFinished: 'NA',
    timeFinished: 'NA',
    unloader: 'NA',
    boxCondition: 'NA',
    boxCover: 'NA',
    boxArrangement: 'NA',
    encodedBy: 'NA',
    remarks1: 'NA',
    remarks2: 'NA',
    remarks3: 'NA'
});

const controlSheet_dataMatrix = ref({});

for (const layer of controlSheet_layers.value) {
  controlSheet_dataMatrix.value[layer] = controlSheet_rowTitles.value.map(rowTitle => {
    const data = {};
    for (const letter of controlSheet_headerLetters.value) {
      data[letter] = '';
    }
    return { rowTitle, data };
  });
}
//console.log(controlSheet_dataMatrix.value);

function setDataMatrixValue(layer, rowTitle, letter, value) {
  const layerRows = controlSheet_dataMatrix.value[layer];
  if (!layerRows) return;

  const row = layerRows.find(r => r.rowTitle === rowTitle);
  if (row) {
    row.data[letter] = value;
  }
}

const getMassProdData = async (massprod) => {
    const response = await axios.get('/api/mass-production');
    massProd_list.value = response.data;

    // Filter based on mass_prod column
    selectedMassPro.value = massProd_list.value.filter(item => item.mass_prod === massprod);
    console.log('Filtered Data: ', selectedMassPro.value);

    const mp = selectedMassPro.value[0];
    const htVal = controlSheet_ht_info_values.value;

    //heat treatment values
    htVal.batchCycleNo = mp.batch_cycle_no ?? 'NA';
    htVal.machineNo = mp.machine_no ?? 'NA';
    htVal.cycleNo = mp.cycle_no ?? 'NA';
    htVal.patternNo = mp.pattern_no ?? 'NA';
    htVal.cyclePattern = mp.cycle_pattern ?? 'NA';
    htVal.currentPattern = mp.current_pattern ?? 'NA';
    htVal.dateStart = mp.date_start ?? 'NA';
    htVal.timeStart = mp.time_start ?? 'NA';
    htVal.loader = mp.loader ?? 'NA';
    htVal.dateFinished = mp.date_finished ?? 'NA';
    htVal.timeFinished = mp.time_finished ?? 'NA';
    htVal.unloader = mp.unloader ?? 'NA';
    htVal.boxCondition = mp.box_condition ?? 'NA';
    htVal.boxCover = mp.box_cover ?? 'NA';
    htVal.boxArrangement = mp.box_arrangement ?? 'NA';
    htVal.encodedBy = mp.encoded_by ?? 'NA';
    htVal.remarks1 = mp.remarks1 ?? 'NA';
    htVal.remarks2 = mp.remarks2 ?? 'NA';
    htVal.remarks3 = mp.remarks3 ?? 'NA';

    for (const layer of controlSheet_layers.value) {
        const fieldName = `layer_${layer.replace('.', '_')}`;
        const rawLayer = mp[fieldName];
        if (!rawLayer) continue;

        const parsedLayer = JSON.parse(rawLayer);

        for (let rowIndex = 0; rowIndex < controlSheet_rowTitles.value.length; rowIndex++) {
            const rowTitle = controlSheet_rowTitles.value[rowIndex];
            const rowData = parsedLayer[rowIndex]?.data ?? {};

            for (const letter of controlSheet_headerLetters.value) {
                const value = rowData[letter] ?? '';
                setDataMatrixValue(layer, rowTitle, letter, value);
            }
        }
    }
}



// GETTING TOTALS

const totalQty = computed(() => getTotalQty());
const totalWt = computed(() => getTotalWt());

const getTotalQty = () => {
  let total = 0;
  for (const layer of controlSheet_layers.value) {
    const rows = controlSheet_dataMatrix.value[layer];
    if (!rows) continue;

    const qtyRow = rows.find(r => r.rowTitle === 'QTY (PCS):');
    if (!qtyRow) continue;

    for (const val of Object.values(qtyRow.data)) {
      const num = parseFloat(val);
      if (!isNaN(num)) {
        total += num;
      }
    }
  }
  return total;
};

const getTotalWt = () => {
  let total = 0;
  for (const layer of controlSheet_layers.value) {
    const rows = controlSheet_dataMatrix.value[layer];
    if (!rows) continue;

    const wtRow = rows.find(r => r.rowTitle === 'WT (KG):');
    if (!wtRow) continue;

    for (const val of Object.values(wtRow.data)) {
      const num = parseFloat(val);
      if (!isNaN(num)) {
        total += num;
      }
    }
  }
  return total;
};

const getTotalMpiQty = (qty) => {

}

// GETTING TOTALS END

onMounted(async()=>{
    const massPro = redirectedMassPro.value;
    getMassProdData(massPro);
});

</script>
