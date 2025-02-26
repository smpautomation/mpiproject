<template>
    <Frontend>
        <div class="flex flex-col justify-center items-center px-24 py-[100px] container-fluid bg-gray-100">
            <div class="flex flex-col items-center justify-center max-w-md p-8 mx-auto mb-12 rounded-lg shadow-lg bg-gray-50">
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
                        accept=".txt"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />

                    <!-- Optional Instruction Text -->
                    <p class="mt-2 text-sm text-gray-500">Only .txt files are allowed</p>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="w-2/3 bg-blue-100 rounded-xl">
                <apexchart
                    type="line"
                    :series="lineChartSeries"
                    :options="lineChartOptions"
                />
            </div>
            <div class="p-6 rounded-lg shadow-lg mt-14 bg-gray-50">
                <label class="block mb-4 text-2xl font-semibold text-gray-800 shadow-xl bg-gradient-to-r from-yellow-400 to-yellow-100 max-w-36">FIRST LAYER</label>
                <!-- Table with Full Width -->
                <table class="overflow-hidden border border-gray-300 rounded-lg shadow-2xl table-auto">
                    <thead class="text-white bg-gradient-to-r from-blue-700 to-blue-400">
                        <tr>
                            <th class="px-3 py-2 text-sm font-medium text-center">Date</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Code&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Order&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Type</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Lot&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Furnace&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Coating&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Zone</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Pass&nbsp;No</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Br</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">iHc</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">iHk</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">BHMax</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Hr95</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Hr98</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">iHciHk</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Br4pai</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">bHc</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Squareness</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">4paild</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">4pails</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">4paila</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Tracer</th>
                            <th class="px-3 py-2 text-sm font-medium text-center">Sample with&nbsp;Variance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td colspan="24" class="px-3 py-3 text-sm text-center bg-gradient-to-r from-blue-700 to-blue-400"></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">AVG</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">MAX</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">MIN</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700">0</td>
                            <td class="px-3 py-2 text-sm text-center text-gray-700"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Frontend>
  </template>

  <script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { ref } from 'vue';

    // Multiple groups of data (3 data sets)
    const lineChartSeries = ref([
    {
        name: 'Group 1',        // Name of the first line
        data: [10, 20, 30, 40, 50, 100, 14000], // Y values for Group 1
    },
    {
        name: 'Group 2',        // Name of the second line
        data: [15, 25, 35, 45, 55, 65, 75], // Y values for Group 2
    },
    {
        name: 'Group 3',        // Name of the third line
        data: [5, 15, 25, 35, 45, 55, 65], // Y values for Group 3
    }
    ]);

    const lineChartOptions = ref({
    chart: {
        height: 350,
        type: 'line',
        zoom: {
        enabled: false,
        },
    },
    stroke: {
        width: [4, 4, 4],  // Stroke width for each line (adjust as needed)
        curve: 'smooth',   // Smooth curves for each line
    },
    xaxis: {
        categories: [1000, 3000, 5000, 7000, 10000, 13000, 15000],  // X-axis categories
        title: {
        text: 'X Axis Label',  // Label for X-axis
        }
    },
    yaxis: {
        title: {
        text: 'Y Axis Label',  // Label for Y-axis
        }
    },
    title: {
        text: 'Multi-Line Chart',
        align: 'center',
        style: {
        fontSize: '18px',
        fontWeight: 'bold',
        color: '#333',
        },
    },
    });
  </script>
