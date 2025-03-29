<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center mt-12 font-bold">
            <br>
            <table id="tpmDataTable" class="border-collapse rounded-lg shadow-lg table-auto">
                <thead>
                    <tr class="text-lg text-white bg-gradient-to-r from-green-400 via-black to-blue-400">
                        <th class="px-4 py-4 border-b">Serial No</th>
                        <th class="px-4 py-4 border-b">Furnace ID</th>
                        <th class="px-4 py-4 border-b">Layer ID</th>
                        <th class="px-4 py-4 border-b">Model Name</th>
                        <th class="px-4 py-4 border-b">Lot No</th>
                        <th class="px-4 py-4 border-b">Furnace No</th>
                        <th class="px-4 py-4 border-b">Coating No</th>
                        <th class="px-4 py-4 border-b">Tracer No</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in tpmData" :key="index">
                        <td class="p-4 text-center text-md">{{ item.serial_no }}</td>
                        <td class="p-4 text-center text-md">{{ item.furnace_id }}</td>
                        <td class="p-4 text-center text-md">{{ item.layer_no }}</td>
                        <td class="p-4 text-center text-md">{{ item.code_no }}</td>
                        <td class="p-4 text-center text-md">{{ item.press_1 + item.press_2 + item.machine_no }}</td>
                        <td class="p-4 text-center text-md">{{ item.sintering_furnace_no }}</td>
                        <td class="p-4 text-center text-md">{{ item.furnace_no }}</td>
                        <td class="p-4 text-center text-md">{{ item.Tracer }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted } from 'vue';
import $ from 'jquery'; // jQuery is a dependency of DataTables
import 'datatables.net'; // DataTables script
import { Chart, registerables } from 'chart.js'; // Import all required components

const tpmData = ref([]);

const viewAllSerialedLayers = async () => {
    try {
        const response = await axios.get("/api/tpmdata");
        console.log('API Response viewlist:', response.data);

        // Access the tpmData from response (adjust based on your API structure)
        const data = response.data.data["tpmData"] || [];

        // Ensure unique serial numbers
        const uniqueData = data.filter((value, index, self) => {
            return index === self.findIndex((t) => t.serial_no === value.serial_no);
        });

        tpmData.value = uniqueData;
        console.log("Unique tpm data array: ", tpmData.value);

    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

onMounted(() => {
    // Fetch the data first
    viewAllSerialedLayers().then(() => {
        // Once the data is loaded, initialize the DataTable
        $('#tpmDataTable').DataTable({
            pageLength: 10, // Set the default number of entries per page
            searching: true, // Enable search functionality
            paging: true, // Enable pagination
            ordering: true, // Enable sorting on columns
            language: {
                search: "Search:", // Customize search box placeholder text
                info: "Showing _START_ to _END_ of _TOTAL_ entries", // Customize the info text
                infoEmpty: "No records available", // Customize the text when no records are available
                infoFiltered: "(filtered from _MAX_ total entries)", // Customize the filtered info text
            },
            dom: "<'row'<'col-sm-12'f>>" +  // Keep the search bar
                "<'row'<'col-sm-12'tr>>" +  // Table body
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",  // Pagination and info display
            initComplete: function() {
                // Add Tailwind CSS margin using inline styles
                $('.dataTables_filter').addClass('m-8 p-2'); // This will add a margin-bottom of 1rem (16px)
            }
        });
    }).catch((error) => {
        console.error('Error loading data:', error);
    });
});
</script>
