<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
            <div>
                <div>
                    <p><span>123RD</span> Mass Production</p>
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
                                {{ getLayerTotalWt(layer) }}
                              </td>
                              <td
                                :rowspan="controlSheet_dataMatrix[layer].length"
                                :class="[borderColor, cellPaddings]"
                              >
                                {{ getLayerTotalQty(layer) }}
                              </td>
                              <td
                                :rowspan="controlSheet_dataMatrix[layer].length"
                                :class="[borderColor, cellPaddings]"
                              >
                                {{ getLayerMpiQty(layer) }}
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
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.batchCycleNo ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.machineNo ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.cycleNo ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.patternNo ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.cyclePattern ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.currentPattern ?? '' }}</span>
                    </div>
                    <div class="flex flex-col text-end gap-[1px] pt-[1px]">
                        <label :class="[ht_info_fontsize]">DATE START:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">TIME START:&nbsp;</label>
                        <label :class="[ht_info_fontsize]">LOADER:&nbsp;</label>

                    </div>
                    <div class="flex flex-col pr-4">
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.dateStart ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.timeStart ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.loader ?? '' }}</span>
                    </div>
                    <div class="flex flex-col text-end gap-[1px] pt-[1px]">
                            <label :class="[ht_info_fontsize]">DATE FINISHED:&nbsp;</label>
                            <label :class="[ht_info_fontsize]">TIME FINISHED:&nbsp;</label>
                            <label :class="[ht_info_fontsize]">UNLOADER:&nbsp;</label>
                    </div>
                    <div class="flex flex-col pr-4">
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.dateFinished ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.timeFinished ?? '' }}</span>
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.unloader ?? '' }}</span>
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
                        <span :class="[ht_info_fontsize, ht_info_underline]">{{ controlSheet_ht_info_values.remarks1 ?? '' }}</span>
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

const tableProperties = {
    borderColor: 'border border-gray-400',
    headerPaddings: 'px-4 py-2',
    cellPaddings: 'px-2 py-2',
    headerFontSize: 'text-[14px]',
    cellFontSize: 'text-[9px]',
}

const heatTreatmentInformationProperties = {
   fontSize: 'text-[10px]',
   inputBoxSize: 'px-0 py-0',
   underlineEffect: 'inline-block border-b border-black w-24 text-start',
   underlineEffect2: 'inline-block border-b border-black w-37 text-start'
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
  'WT (KG)',
  'BOX No.:',
  'Magnet prepared by:',
  'Box prepared by:',
]);

const controlSheet_ht_info_values = ref({
    batchCycleNo: 'test',
    machineNo: 'test',
    cycleNo: 'test',
    patternNo: 'test',
    cyclePattern: 'test',
    currentPattern: 'test',
    dateStart: 'test',
    timeStart: 'test',
    loader: 'test',
    dateFinished: 'test',
    timeFinished: 'test',
    unloader: 'test',
    boxCondition: 'test',
    boxCover: 'test',
    boxArrangement: 'test',
    encodedBy: 'test',
    remarks1: 'test',
    remarks2: 'test',
    remarks3: 'test'
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
console.log(controlSheet_dataMatrix.value);

function setDataMatrixValue(layer, rowTitle, letter, value) {
  const layerRows = controlSheet_dataMatrix.value[layer];
  if (!layerRows) return;

  const row = layerRows.find(r => r.rowTitle === rowTitle);
  if (row) {
    row.data[letter] = value;
  }
}

for (const layer of controlSheet_layers.value) {
  for (const rowTitle of controlSheet_rowTitles.value) {
    for (const col of controlSheet_headerLetters.value) {
      setDataMatrixValue(layer, rowTitle, col, 'test');
    }
  }
}



// GETTING TOTALS

function getLayerTotalWt(layer) {
  return '';
}

function getLayerTotalQty(layer) {
  return '';
}

function getLayerMpiQty(layer) {
  return '';
}

// GETTING TOTALS END


</script>
