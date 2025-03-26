<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
            <div v-show="showMainUI" class="flex flex-col items-center justify-center">
                <div>
                    <button @click="insertBtn" class="relative flex px-6 py-3 m-10 overflow-hidden font-semibold text-white transition duration-300 ease-in-out bg-green-600 shadow-md rounded-xl hover:brightness-110 hover:shadow-lg active:scale-95">
                        Insert New Record
                    </button>
                </div>
                <div class="flex items-center justify-center mb-10 bg-gray-100">
                    <table class="w-11/12 mx-auto border border-collapse border-gray-300 table-auto md:w-3/4">
                        <thead class="text-white bg-gradient-to-l from-blue-600 to-green-600 ">
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

            <div v-show="showInputData" class="flex flex-col w-full p-8 m-10 mx-auto bg-white shadow-lg max-w-7xl rounded-2xl">
                <div class="flex flex-row items-start justify-start">
                    <button @click="registerBackBtn" class="px-3 py-1 font-semibold text-gray-700 transition duration-300 ease-in-out bg-gray-300 rounded-lg shadow-md hover:bg-gray-400 hover:shadow-lg active:scale-95">
                        Back
                    </button>
                </div>

                <div class="flex-col items-center justify-center">
                    <!-- Title -->
                    <p class="mb-6 text-3xl font-bold text-center text-gray-800">Register New Data</p>

                    <!-- Model Input -->
                    <div class="w-full mb-6">
                        <label class="block mb-1 font-medium text-gray-600">Model:</label>
                        <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                    </div>

                    <!-- Grid for Inputs -->
                    <div class="grid w-full grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Length:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Width:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Thickness:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Material&nbsp;Grade:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Br: (ex: 1234 ~ 5678 )</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">iHc:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>

                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">iHk:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Oven&nbsp;Machine&nbsp;No:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Time&nbsp;Loading:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Temperature(TL):</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Date:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Time&nbsp;Unloading:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">Temperature(TU):</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Shift:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Operator:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium text-gray-600">MPI&nbsp;Sample:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">iHc&#8209;iHk:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block font-medium text-gray-600">Br&#8209;4πla:</label>
                                <input type="text" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button class="flex flex-row items-center px-6 py-3 m-10 overflow-hidden text-xl font-semibold text-white transition duration-300 ease-in-out bg-green-600 shadow-md rounded-xl hover:brightness-110 hover:shadow-lg active:scale-95">
                        Register
                    </button>
                </div>

            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted } from 'vue';

//UI Visibility Variables

const showMainUI = ref(true);
const showInputData = ref(false);

//UI Visibility Variables end

const insertBtn = () => {
    showMainUI.value = false;
    showInputData.value = true;
}

const registerBackBtn = () => {
    showMainUI.value = true;
    showInputData.value = false;
}

</script>
