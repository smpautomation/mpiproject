<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center min-h-screen px-8 py-12 mx-auto bg-gray-100 space-y-4">
            <!-- Loading indicator -->
            <div v-if="tpmData.length === 0" class="flex items-center justify-center min-h-screen">
                <span class="text-lg font-semibold text-gray-500">Loading data...</span>
            </div>

            <!-- DataTable -->
            <div v-else class="overflow-x-auto w-full">
                <table id="tpmDataTable" class="table-fixed border-collapse w-full rounded-lg shadow-lg">
                    <thead>
                        <tr class="text-lg text-white bg-gradient-to-r from-green-400 via-black to-blue-400">
                            <th class="w-1/12 px-4 py-4 border-b">Serial No</th>
                            <th class="w-2/12 px-4 py-4 border-b">Furnace ID</th>
                            <th class="w-1/12 px-4 py-4 border-b">Layer ID</th>
                            <th class="w-2/12 px-4 py-4 border-b">Model Name</th>
                            <th class="w-2/12 px-4 py-4 border-b">Lot No</th>
                            <th class="w-1/12 px-4 py-4 border-b">Furnace No</th>
                            <th class="w-1/12 px-4 py-4 border-b">Coating No</th>
                            <th class="w-1/12 px-4 py-4 border-b">Tracer No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-4 text-center text-md"></td>
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

        // Access the tpmData from response (adjust based on your API structure)
        const data = response.data.data?.tpmData || [];

        // Ensure unique serial numbers
        const uniqueData = data.filter((value, index, self) => {
            return index === self.findIndex((t) => t.serial_no === value.serial_no);
        });

        tpmData.value = uniqueData;
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
