<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-4 bg-gray-100">
            <!-- Loading indicator -->
            <div v-if="tpmData.length === 0" class="flex items-center justify-center min-h-screen">
                <span class="text-lg font-semibold text-gray-500">No data available. Please go to manage section first.</span>
            </div>

            <!-- DataTable -->
            <div v-else class="w-full overflow-x-auto">
                <table id="tpmDataTable" class="w-full border-collapse rounded-lg shadow-lg table-fixed">
                    <thead>
                        <tr class="text-lg text-white bg-gradient-to-r from-green-400 via-black to-blue-400">
                            <th class="w-1/12 px-1 py-2 border-b whitespace-nowrap">Serial No</th>
                            <th class="w-2/12 px-1 py-2 border-b whitespace-nowrap">Model Name</th>
                            <th class="w-2/12 px-1 py-2 border-b whitespace-nowrap">Lot No</th>
                            <th class="w-1/12 px-1 py-2 border-b whitespace-nowrap">Furnace No</th>
                            <th class="w-1/12 px-1 py-2 border-b whitespace-nowrap">Tracer No</th>
                            <th class="w-2/12 px-1 py-2 border-b whitespace-nowrap">SMP Judgement</th>
                            <th class="w-1/12 px-1 py-2 border-b whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in tpmData" :key="index">
                            <td class="p-2 text-center text-md whitespace-nowrap">{{ item.category[0].tpm_data_serial }}</td>
                            <td class="p-2 text-center text-md whitespace-nowrap">{{ item.category[0].actual_model }}</td>
                            <td class="p-2 text-center text-md whitespace-nowrap">{{ item.category[0].jhcurve_lotno }}</td>
                            <td class="p-2 text-center text-md whitespace-nowrap">{{ item.tpm[0].sintering_furnace_no }}</td>
                            <td class="p-2 text-center text-md whitespace-nowrap">{{ item.tpm[0].Tracer }}</td>
                            <td class="p-2 text-center text-md whitespace-nowrap">{{ item.report[0].smp_judgement || "NO DATA" }}</td>
                            <td v-if="item.report[0].approved_by" class="p-2 text-center text-md">COMPLETED</td>
                            <td v-else class="p-2 text-center text-md">PENDING</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import $ from 'jquery'; // jQuery is a dependency of DataTables
import 'datatables.net'; // DataTables script

const tpmData = ref([]);

// Fetch the TPM data
const viewAllSerialedLayers = async () => {
    try {
        const response = await axios.get("/api/tpmdata");
        console.log('API Response viewlist:', response.data);

        const rawData = response.data.data?.tpmData || {};
        tpmData.value = Object.values(rawData); // convert object to array

        //tpmData.value = dataArray;
        console.log("Unique tpm data array: ", tpmData.value);
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};

// Initialize DataTable
onMounted(() => {
    viewAllSerialedLayers().then(() => {
        $('#tpmDataTable').DataTable({
            pageLength: 10, // Default entries per page
            searching: true, // Enable search
            paging: true, // Enable pagination
            ordering: true, // Enable sorting
            language: {
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No records available",
                infoFiltered: "(filtered from _MAX_ total entries)",
            },
            dom: "<'flex justify-between items-center m-4'f>" +
                "<'overflow-x-auto'tr>" +
                "<'flex justify-between items-center m-4'i<'pagination p-2'>>",
            initComplete: function() {
                $('.dataTables_filter input').addClass('input input-bordered'); // Tailwind-friendly search box
                $('.dataTables_paginate').addClass('flex items-center'); // Tailwind pagination
            }
        });
    }).catch((error) => {
        console.error('Error loading data:', error);
    });
});
</script>
