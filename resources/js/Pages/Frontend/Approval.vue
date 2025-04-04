<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
            <div class="flex flex-col items-center justify-center mt-12 font-bold">This is the Approval page</div>
            <div>
                <table class="border border-collapse border-black table-auto">
                    <thead class="text-center bg-gray-100">
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Date</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Serial No</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Magnet Model</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Material Code</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Partial No</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300">Total Quantity</th>
                        <th class="px-4 py-2 text-sm font-semibold text-white bg-yellow-600 border border-gray-300">SMP Judgement</th>
                        <th class="px-4 py-2 text-sm font-semibold text-white bg-yellow-600 border border-gray-300">Prepared By</th>
                        <th class="px-4 py-2 text-sm font-semibold text-white bg-yellow-600 border border-gray-300">Checked By</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 bg-green-200 border border-gray-300">Action</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-700 bg-yellow-200 border border-gray-300">Approval</th>
                    </thead>
                    <tbody class="text-center bg-white">
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm border border-gray-300">Null</td>
                        <td class="px-4 py-2 text-sm text-center border border-gray-300">
                            <button class="px-4 py-2 text-blue-500 transition duration-200 border border-blue-500 rounded-md cursor-pointer hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                                View Report
                            </button>
                        </td>
                        <td class="px-4 py-2 text-sm text-center border border-gray-300">
                            <input type="checkbox" class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded-md cursor-pointer focus:ring-blue-500 focus:ring-2" />
                        </td>
                    </tbody>
                </table>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
</script>
