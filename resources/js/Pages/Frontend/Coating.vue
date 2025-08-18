<template>
    <Frontend>
        <div class="flex flex-col items-center min-h-screen px-4 py-12 bg-gray-100">
            <div class="flex flex-row gap-10">
                <div class="max-w-8xl px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Data (Unit: µ/mm²)<span class="text-red-500"> *</span></h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">
                        <div class="flex flex-row">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 border-r border-gray-300 text-left">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr v-for="i in 10" :key="i" class="hover:bg-gray-50">
                                            <td class="px-3 py-1 border-r border-b border-gray-200">{{ i }}</td>
                                            <td class="px-3 py-1 border-b border-gray-200">
                                                <input
                                                    type="number"
                                                    class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 border-r border-gray-300 text-left">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr v-for="i in 10" :key="i" class="hover:bg-gray-50">
                                            <td class="px-3 py-1 border-r border-b border-gray-200">{{ i + 10 }}</td>
                                            <td class="px-3 py-1 border-b border-gray-200">
                                                <input
                                                    type="number"
                                                    class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 border-r border-gray-300 text-left">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr v-for="i in 10" :key="i" class="hover:bg-gray-50">
                                            <td class="px-3 py-1 border-r border-b border-gray-200">{{ i + 20 }}</td>
                                            <td class="px-3 py-1 border-b border-gray-200">
                                                <input
                                                    type="number"
                                                    class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1 border-r border-gray-300 text-left">No.</th>
                                            <th class="px-2 py-1 text-left">Coating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr v-for="i in 5" :key="i" class="hover:bg-gray-50">
                                            <td class="px-3 py-1 border-r border-b border-gray-200">{{ i + 30 }}</td>
                                            <td class="px-3 py-1 border-b border-gray-200">
                                                <input
                                                    type="number"
                                                    class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                                <thead class="bg-gray-100 text-center">
                                    <tr>
                                        <th colspan="8" class="text-center border-b border-gray-300">Concentration Amount</th>
                                    </tr>
                                    <tr>
                                        <th class="px-2 py-1 border-r border-gray-300"></th>
                                        <th class="px-2 py-1 border-r border-gray-300">M-01</th>
                                        <th class="px-2 py-1 border-r border-gray-300">M-02</th>
                                        <th class="px-2 py-1 border-r border-gray-300">M-03</th>
                                        <th class="px-2 py-1 border-r border-gray-300">M-04</th>
                                        <th class="px-2 py-1 border-r border-gray-300">M-05</th>
                                        <th class="px-2 py-1 border-r border-gray-300">M-06</th>
                                        <th class="px-2 py-1 border-gray-300">M-06</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">1-5</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">6-10</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">11-15</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">16-20</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">21-25</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">26-30</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-b border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-r border-gray-200">31-35</td>
                                        <td class="px-2 py-1 border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-r border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                        <td class="px-2 py-1 border-gray-200">
                                            <input
                                                type="number"
                                                class="w-[4rem] py-[0.1rem] text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DIV BELOW (BOTTOM PART) -->
            <div class="flex flex-row mt-10 gap-20">
                <div class="px-4 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl py-4 md:px-8">
                    <!-- Stats: Average / Max / Min -->
                    <div class="flex flex-row gap-20">
                        <div class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-center shadow-inner">
                            <div class="text-xs font-medium text-gray-500">Average</div>
                            <div class="text-sm font-semibold text-gray-800">(value)</div>
                        </div>
                        <div class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-center shadow-inner">
                            <div class="text-xs font-medium text-gray-500">Maximum</div>
                            <div class="text-sm font-semibold text-gray-800">(value)</div>
                        </div>
                        <div class="flex-1 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-center shadow-inner">
                            <div class="text-xs font-medium text-gray-500">Minimum</div>
                            <div class="text-sm font-semibold text-gray-800">(value)</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DIV BELOW (BOTTOM PART) -->
            <div class="flex flex-row gap-10 mt-10">
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Information</h2>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="relative">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">Mass Prod. Name <span class="text-red-500">*</span></label>
                            <select
                                class="w-full text-xs font-semibold text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50"
                            >
                                <option>
                                    452ND
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Layer<span class="text-red-500"> *</span></label>
                            <select class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option>
                                    1
                                </option>
                            </select>
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Date<span class="text-red-500"> *</span></label>
                            <input type="date" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Coating Machine No<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Slurry Lot No<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">MIN TB CONTENT<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sample Quantity<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Loader Operator<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Unloader Operator<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Checker Operator<span class="text-red-500"> *</span></label>
                            <input type="text" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Start<span class="text-red-500"> *</span></label>
                            <input type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Finished<span class="text-red-500"> *</span></label>
                            <input type="time" class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Selection -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                        <!-- Remarks Input -->
                        <div class="flex flex-col">
                            <label class="block mb-2 text-xs font-semibold text-gray-700">
                            Remarks <span class="text-red-500">*</span>
                            </label>
                            <input
                            type="text"
                            class="w-[36rem] text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                            placeholder="Enter remarks here..."
                            />
                        </div>
                    </div>
                </div>
                <div class="px-4 space-y-4 bg-white border border-gray-300 shadow-lg rounded-2xl py-4 md:px-8">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Additional Slurry<span class="text-red-500"> *</span></h2>
                    <div class="flex flex-row gap-5 whitespace-nowrap">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm border border-gray-200 rounded-lg text-center">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-2 py-1 border-r border-gray-300">MODULE</th>
                                        <th class="px-2 py-1 border-r border-gray-300">NEW</th>
                                        <th class="px-2 py-1 border-r border-gray-300">HOMO</th>
                                        <th class="px-2 py-1 border-r border-gray-300">TIME</th>
                                        <th class="px-2 py-1 border-r border-gray-300">LITERS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">M-01</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200">

                                        </td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-gray-200"></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">M-02</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-gray-200"></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">M-03</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-gray-200"></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">M-04</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-gray-200"></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">M-05</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-gray-200"></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1 border-b border-r border-gray-200">M-06</td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                        <td class="px-2 py-1 border-b border-gray-200"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- LAST BOTTOM DIV -->
            <div class="flex flex-row mt-10 gap-20">
                <div class="bg-gradient-to-br from-black via-gray-900 to-gray-800 border border-blue-600 shadow-[0_10px_25px_rgba(99,102,241,0.4),0_4px_6px_rgba(0,0,0,0.4)] rounded-3xl px-6 py-6 flex items-center gap-16 ring-1 ring-indigo-400 backdrop-blur-md whitespace-nowrap">
                    <!-- Glowing Spinning Cogwheel -->
                    <div class="relative w-12 h-12 flex-shrink-0">
                        <div class="absolute inset-0 bg-blue-400 rounded-full opacity-100 blur-xl animate-pulse"></div>
                        <img
                        src="photo/cogwheel.png"
                        alt="Settings"
                        class="relative z-10 object-contain w-full h-full animate-spin"
                        style="animation-duration: 3s;"
                        />
                    </div>

                    <!-- Buttons Row -->
                    <div class="flex w-full gap-10">
                        <!-- Finalize -->
                        <button
                        @click="finalize"
                        class="flex-1 py-3 px-4 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:ring-opacity-50"
                        >
                        FINALIZE
                        </button>

                        <!-- Cancel -->
                        <button
                        class="flex-1 py-3 px-4 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50"
                        >
                        CANCEL
                        </button>

                        <!-- Clear All -->
                        <button
                        @click="clearAll()"
                        class="flex-1 py-3 px-4 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
                        >
                        CLEAR ALL
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import Modal from '@/Components/Modal.vue'
import { useAuth } from '@/Composables/useAuth.js'
import { useToast } from 'vue-toast-notification';
const toast = useToast();

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

// Utility: Save and load to sessionStorage
function useSessionStorage(key, state) {
    // Load existing session value
    const saved = sessionStorage.getItem(key)
    if (saved !== null) {
        try {
            const parsed = JSON.parse(saved)
            if (typeof state === 'object' && 'value' in state) {
                state.value = parsed
            } else {
                Object.assign(state, parsed)
            }
        } catch {
        /* ignore parse errors */
        }
    }

    // Watch and persist changes
    watch(
        state,
        (val) => {
        sessionStorage.setItem(key, JSON.stringify(val))
        },
        { deep: true }
    )
}

//Dev Controls ----------------- Allow Commands
const bypassValidation = ref(false);
//Dev Controls ----------------- Allow Commands

// General Variables --------- General Variables

const massProd_names = ref([]);
const model_names = ref([]);
const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);

// General Variables --------- General Variables

// Coating Information Variables --------- Coating Information Variables

const coatingInfo = reactive({
    selectedMassProd: '',
    selectedModel: '',
    selectedLayer: '1',
    coatingDate: '',
    coatingMachineNo: '',
    slurryLotNo: '',
    minTbContent: '',
    sampleQuantity: '',
    totalMagnetWeight: '',
    loaderOperator: '',
    unloaderOperator: '',
    checkerOperator: '',
    timeStart: '',
    timeFinished: '',
    remarks: ''
});

// Coating Information Variables --------- Coating Information Variables

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
});

</script>

<style scoped>

    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
