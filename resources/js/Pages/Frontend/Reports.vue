<template>
    <Frontend>
      <div class="flex flex-col items-center justify-center align-middle bg-gray-100 container-fluid">
        <div>This is the Reports page</div>

        <div>
            <p>Select Serial No: </p>
        </div>

        <!-- Report Content -->
            <div class="flex flex-col justify-center py-10 mx-20 my-20 align-middle bg-blue-100 shadow-2xl rounded-3xl">
                <div class="flex flex-row mb-4 justify-evenly">
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Model:</label>&nbsp;
                        <input type="text" name="pulseTracerMachineNo" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Material Code:</label>&nbsp;
                        <input type="text" name="date" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Partial No.:</label>&nbsp;
                        <input type="text" name="shift" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Total Quantity:</label>&nbsp;
                        <input type="number" name="operator" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                </div>
                <div class="flex flex-row mb-4 justify-evenly">
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Pulse Tracer Machine No:</label>&nbsp;
                        <input type="text" name="pulseTracerMachineNo" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Date:</label>&nbsp;
                        <input type="date" name="date" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Shift:</label>&nbsp;
                        <input type="text" name="shift" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                    <div class="flex flex-row items-baseline">
                        <label class="text-sm font-semibold">Operator:</label>&nbsp;
                        <input type="text" name="operator" class="w-[12rem] h-[1.5rem] p-2 mt-1 text-sm border rounded-md">
                    </div>
                </div>

                <div class="flex flex-row items-center justify-center align-middle">
                    <table class="mt-[1.1rem] ml-[2rem] border-collapse table-auto">
                        <thead>
                            <tr class="bg-blue-300">
                                <th colspan="2" class="px-2 py-2 text-lg font-semibold text-center">STANDARD&nbsp;SAMPLE&nbsp;DIMENSION&nbsp;and&nbsp;Material&nbsp;Grade<br>標準サンプル寸法および材料グレード</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-1 border">LENGTH&nbsp;(mm)</td>
                                <td class="px-4 py-1 border"><input type="text" class="w-[10rem] py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">WIDTH&nbsp;(mm)</td>
                                <td class="px-4 py-1 border"><input type="text" class="w-[10rem] py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">THICKNESS&nbsp;(mm)</td>
                                <td class="px-4 py-1 border "><input type="text" class="w-[10rem] py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">MATERIAL&nbsp;GRADE</td>
                                <td class="px-4 py-1 border"><input type="text" class="w-[10rem] py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-1 border">MPI&nbsp;SAMPLE&nbsp;QTY.</td>
                                <td class="px-4 py-1 border"><input type="text" class="w-[10rem] py-1 text-sm border rounded-md"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="ml-2 mt-[1rem] mr-[2rem] border-collapse table-auto">
                        <thead>
                            <tr class="bg-blue-300">
                                <th colspan="6" class="py-4 text-lg font-semibold text-center">MAGNETIC&nbsp;PROPERTY&nbsp;DATA(磁気特性データ)</th>
                            </tr>
                            <tr class="bg-blue-200">
                                <th rowspan="2" class="px-4 border">ITEMS</th>
                                <th rowspan="2" class="px-4 border">STANDARD</th>
                                <th colspan="4" class="px-4 text-center border">ACTUAL DATA</th>
                            </tr>
                            <tr class="bg-blue-200">
                                <th class="px-4 border">AVERAGE</th>
                                <th class="px-4">MAXIMUM</th>
                                <th class="px-4">MINIMUM</th>
                                <th class="px-4">VARIANCE<br>(max-min)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 border">Br (G)</td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border">iHc (Oe)</td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border">iHk (Oe)</td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                            </tr>
                            <tr class="bg-blue-200">
                                <th rowspan="2" class="px-4 border">Br Cpk</th>
                                <th class="px-4 border">STD DEV</th>
                                <th class="px-4 border">Cpu</th>
                                <th class="px-4 border">Cpl</th>
                                <th class="px-4 border">Cpk</th>
                                <th class="px-4 border">Remarks</th>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
                                <td class="px-4 py-2 border"><input type="text" class="w-full px-2 py-1 text-sm border rounded-md"></td>
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
</script>
