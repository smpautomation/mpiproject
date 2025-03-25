<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
            <div class="flex flex-col items-center justify-center">
                <div>
                    <button class="flex m-10 px-6 py-3 bg-orange-600 text-white font-semibold rounded-xl shadow-md hover:bg-orange-700 transition duration-200 ease-in-out">
                        Insert New Record
                    </button>
                </div>
                <div class="flex items-center justify-center mb-10 bg-gray-100">
                    <table class="w-11/12 mx-auto border border-collapse border-gray-300 table-auto md:w-3/4">
                        <thead class="text-white bg-gradient-to-l from-orange-300 to-orange-500">
                            <tr>
                                <th class="px-4 py-2 text-center border">Model</th>
                                <th class="px-4 py-2 text-center border">L</th>
                                <th class="px-4 py-2 text-center border">W</th>
                                <th class="px-4 py-2 text-center border">T</th>
                                <th class="px-4 py-2 text-center border">Material&nbsp;Grade</th>
                                <th class="px-4 py-2 text-center border">Br</th>
                                <th class="px-4 py-2 text-center border">iHc</th>
                                <th class="px-4 py-2 text-center border">iHk</th>
                                <th class="px-4 py-2 text-center border">Oven&nbsp;Machine&nbsp;No</th>
                                <th class="px-4 py-2 text-center border">Time&nbsp;Loading</th>
                                <th class="px-4 py-2 text-center border">Temperature</th>
                                <th class="px-4 py-2 text-center border">Date</th>
                                <th class="px-4 py-2 text-center border">Time&nbsp;Unloading</th>
                                <th class="px-4 py-2 text-center border">Temperature</th>
                                <th class="px-4 py-2 text-center border">Shift</th>
                                <th class="px-4 py-2 text-center border">Operator</th>
                                <th class="px-4 py-2 text-center border">MPI&nbsp;Sample</th>
                                <th class="px-4 py-2 text-center border">iHc&#8209;iHk</th>
                                <th class="px-4 py-2 text-center border">Br&#8209;4πla</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                                <td class="px-4 py-2 text-center border">null</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted } from 'vue';



</script>
