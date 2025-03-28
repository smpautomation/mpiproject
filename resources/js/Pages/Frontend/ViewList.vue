<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center mt-12 font-bold">
            <br>
            <table class="table-auto border-collapse shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-gradient-to-r from-green-400 via-black to-blue-400 text-white text-lg">
                        <th class="px-4 py-4 border-b">Serial No</th>
                        <th class="px-4 py-4 border-b">Furnace</th>
                        <th class="px-4 py-4 border-b">Layer</th>
                        <th class="px-4 py-4 border-b">Model Name</th>
                        <th class="px-4 py-4 border-b">Lot No</th>
                        <th class="px-4 py-4 border-b">Furnace No</th>
                        <th class="px-4 py-4 border-b">Coating No</th>
                        <th class="px-4 py-4 border-b">Tracer No</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                        <td class="text-center text-md p-4">
                            null
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js'; // Import all required components

</script>

