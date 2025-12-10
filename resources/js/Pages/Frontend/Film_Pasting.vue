<template>
    <Frontend>
        <div class="flex flex-row items-start gap-10 p-5 m-0 text-green-600 bg-black">
            <div v-if="state.user && state.user.access_type == 'Automation'" class="space-x-2">
                <p>Dev Controls:</p>
                <button @click="bypassValidation = true" class="p-1 bg-gray-200 rounded-lg" :class="[bypassValidation ? 'bg-yellow-400' : '']">ByPass Validation</button>
                <button @click="bypassValidation = false" class="p-1 bg-gray-300 rounded-lg"> x </button>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center min-h-screen px-4 py-4 bg-gray-100">
            <div v-if="!filmPastingInfo.selected_mass_prod && !filmPastingInfo.selectedFurnace" class="mb-6">
                <div class="flex items-center p-4 space-x-3 border rounded-lg bg-gradient-to-r from-cyan-50 to-teal-50 border-cyan-200">
                <!-- Info Icon -->
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Message -->
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">
                        Please select a Mass Production Name to begin.
                    </p>
                    <p class="mt-1 text-xs text-gray-600">
                        Choose from the dropdown above to view layer status information.
                    </p>
                </div>
                </div>
            </div>
            <div v-else class="mb-6">
                <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">

                    <!-- Header -->
                    <div class="px-4 py-3 bg-gradient-to-r from-teal-600 to-cyan-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-sm font-semibold text-white">
                            Layer Status Preview ( {{ filmPastingInfo.selected_mass_prod }} Mass Production )
                        </h3>
                    </div>
                    </div>

                    <!-- Content with Layer Boxes -->
                    <div class="px-6 py-5">

                    <!-- Title and Legend -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-800">
                            Processing Layers (1 - 9.5)
                        </span>
                        <div class="flex items-center space-x-3 text-xs">
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-gray-600">Complete</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                <span class="text-gray-600">Pending</span>
                            </div>
                        </div>
                    </div>

                    <!-- Single Row of Layer Boxes -->
                    <div class="flex flex-wrap justify-center gap-2">
                        <div
                        v-for="layer in layers"
                        :key="layer"
                        class="relative group"
                        >
                        <!-- Layer Box -->
                        <div
                            class="flex flex-col items-center justify-center transition-all duration-200 border-2 rounded-lg h-14 w-14 hover:scale-105"
                            :class="completedLayers.includes(layer)
                            ? 'bg-emerald-50 border-emerald-400 shadow-sm'
                            : 'bg-gray-50 border-gray-300 hover:border-gray-400'"
                        >
                            <!-- Status Icon -->
                            <div class="mb-0.5">
                            <svg
                                v-if="completedLayers.includes(layer)"
                                class="w-3 h-3 text-emerald-600"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <div
                                v-else
                                class="w-3 h-3 border-2 border-gray-400 rounded-full"
                            ></div>
                            </div>

                            <!-- Layer Number -->
                            <span
                            class="text-xs font-semibold"
                            :class="completedLayers.includes(layer)
                                ? 'text-emerald-700'
                                : 'text-gray-500'"
                            >
                            {{ layer }}
                            </span>
                        </div>

                        <!-- Simple Hover Tooltip -->
                        <div class="absolute z-10 mb-2 transition-opacity duration-200 transform -translate-x-1/2 opacity-0 pointer-events-none bottom-full left-1/2 group-hover:opacity-100">
                            <div class="px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap">
                            Layer {{ layer }}
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Simple Status Text -->
                    <p class="mt-4 text-xs text-center text-gray-500">
                        Current layer status for selected mass production
                    </p>

                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-10 mt-10">
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">Film Pasting Information</h2>
                    <!-- Group: Mass Prod, Layer -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="relative">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">Furnace Name <span class="text-red-500">*</span></label>
                            <select
                                v-model="filmPastingInfo.selectedFurnace"
                                :disabled="isFilmPasteDataShown"
                                class="w-full text-xs font-semibold disabled:cursor-not-allowed text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50"
                            >
                                <option v-for="items in furnace_names" :key="items" :value="items">
                                    {{ items }}
                                </option>
                            </select>
                        </div>

                        <div class="relative">
                            <label class="block mb-1 text-xs font-semibold text-gray-800">Mass Prod. Name <span class="text-red-500">*</span></label>
                            <select
                                v-model="filmPastingInfo.selected_mass_prod"
                                :disabled="isFilmPasteDataShown"
                                class="w-full text-xs font-semibold disabled:cursor-not-allowed text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50"
                            >
                                <option v-for="items in massProdLists" :key="items" :value="items">
                                    {{ items }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Layer<span class="text-red-500"> *</span></label>
                            <select
                                v-model="filmPastingInfo.selected_layer"
                                :disabled="isFilmPasteDataShown"
                                class="w-full text-xs font-semibold disabled:cursor-not-allowed text-yellow-900 transition-all duration-150 border-2 border-yellow-500 rounded-lg shadow-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-600 bg-yellow-50"
                            >
                                <option v-for="items in available_layers" :key="items" :value="items">
                                    {{ items }}
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Lot Number -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Lot no<span class="text-red-500"> *</span>
                            </label>
                            <input
                                v-model="filmPasteLotNo"
                                type="text"
                                disabled
                                class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <!-- Model -->
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">
                                Model<span class="text-red-500"> *</span>
                            </label>
                            <input
                                v-model="filmPasteModel"
                                type="text"
                                disabled
                                class="w-full text-xs bg-gray-100 border-gray-300 rounded-lg shadow-sm cursor-not-allowed focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <!-- Button (wrapped for alignment) -->
                        <div class="flex items-end">
                            <button
                                v-if="!isFilmPasteDataShown"
                                @click="fetchFilmPastingDataSummary"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 rounded-lg shadow-md bg-cyan-600 hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                            >
                                Show Data
                            </button>
                            <button
                                v-else
                                @click="changeDataFilmPaste"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 rounded-lg shadow-md bg-yellow-600 hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                    <!-- Group: Film Pasting Date, Machine No, Total Magnet Weight -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Pasting Date<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.film_pasting_date" type="date" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Machine No<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.machine_no" @input="filmPastingInfo.machine_no = filmPastingInfo.machine_no.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Total Magnet Weight<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.total_magnet_weight" type="number" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Loader Operator, Unloader Operator, Checker Operator -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Loader Operator<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.loader_operator" @input="filmPastingInfo.loader_operator = filmPastingInfo.loader_operator.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Unloader Operator<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.unloader_operator" @input="filmPastingInfo.unloader_operator = filmPastingInfo.unloader_operator.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Checker Operator<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.checker_operator" @input="filmPastingInfo.checker_operator = filmPastingInfo.checker_operator.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <!-- Group: Film Coating Amount, Time Start, Time Finished -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Coating Amount<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.film_coating_amount" @input="filmPastingInfo.film_coating_amount = filmPastingInfo.film_coating_amount.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Start<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.time_start" type="time" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Time Finished<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.time_finished" type="time" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Remarks<span class="text-red-500"> *</span></label>
                        <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.remarks" @input="filmPastingInfo.remarks = filmPastingInfo.remarks.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>
                <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">-</h2>
                    <!-- Group: Film Pasting Date, Machine No, Total Magnet Weight -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Type (Tb or Dy)<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.film_type" @input="filmPastingInfo.film_type = filmPastingInfo.film_type.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film Class<span class="text-red-500"> *</span></label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.film_class" @input="filmPastingInfo.film_class = filmPastingInfo.film_class.toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-10 mt-5">
                <div class="flex flex-col gap-10 mt-5">
                    <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">H-Line Parameters</h2>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sprayer Water amount</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.h_line_parameters['sprayer_water_amount']" type="number" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film paste Lot no</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.h_line_parameters['film_paste_lot_no_1']" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.h_line_parameters['film_paste_lot_no_2']" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full mt-2 text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine Humidity %</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.h_line_parameters['film_machine_humidity']" type="number" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine temperature</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.h_line_parameters['film_machine_temperature']" type="number" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Dryer Temp setting</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.h_line_parameters['dryer_temp_setting']" @input="filmPastingInfo.h_line_parameters['dryer_temp_setting'] = filmPastingInfo.h_line_parameters['dryer_temp_setting'].toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-10 mt-5">
                    <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">T-Line Parameters</h2>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Sprayer Water amount</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.t_line_parameters['sprayer_water_amount']" type="number" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film paste Lot no</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.t_line_parameters['film_paste_lot_no_1']" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.t_line_parameters['film_paste_lot_no_2']" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full mt-2 text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine Humidity %</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.t_line_parameters['film_machine_humidity']" type="number" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Film machine temperature</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.t_line_parameters['film_machine_temperature']" type="number" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Dryer Temp setting</label>
                            <input :disabled="isFilmPasteDataShown" v-model="filmPastingInfo.t_line_parameters['dryer_temp_setting']" @input="filmPastingInfo.t_line_parameters['dryer_temp_setting'] = filmPastingInfo.t_line_parameters['dryer_temp_setting'].toUpperCase()" type="text" class="disabled:cursor-not-allowed disabled:bg-gray-100 w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-10 mt-5">
                    <div class="max-w-4xl px-2 mx-auto space-y-2 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                        <h2 class="pb-1 mb-10 font-bold text-gray-800 border-b text-md">
                            Setter Sand Application per Layer <span class="text-red-500">*</span>
                        </h2>

                        <div class="flex flex-col space-y-3">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <input
                                type="radio"
                                name="setter_sand"
                                :disabled="isFilmPasteDataShown"
                                value="1"
                                v-model="filmPastingInfo.is_setter_sand"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <span>With Setter Sand</span>
                            </label>

                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <input
                                type="radio"
                                name="setter_sand"
                                :disabled="isFilmPasteDataShown"
                                value="0"
                                v-model="filmPastingInfo.is_setter_sand"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <span>Without Setter Sand</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-20 mt-10">
                <div class="bg-gradient-to-br from-black via-gray-900 to-gray-800 border border-cyan-600 shadow-[0_10px_25px_rgba(99,102,241,0.4),0_4px_6px_rgba(0,0,0,0.4)] rounded-3xl px-6 py-6 flex items-center gap-16 ring-1 ring-teal-400 backdrop-blur-md whitespace-nowrap">
                    <!-- Glowing Spinning Cogwheel -->
                    <div class="relative flex-shrink-0 w-12 h-12">
                        <div class="absolute inset-0 rounded-full opacity-100 bg-cyan-400 blur-xl animate-pulse"></div>
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
                            @click="submit()"
                            :disabled="isExists"
                            :class="[
                                'flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl focus:outline-none focus:ring-4 focus:ring-opacity-50',
                                isExists
                                ? 'bg-red-600 cursor-not-allowed'
                                : 'bg-gradient-to-r from-cyan-500 to-teal-600 hover:from-cyan-600 hover:to-teal-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:ring-indigo-400'
                            ]"
                            >
                            {{ isExists ? 'DUPLICATE DETECTED' : 'SUBMIT' }}
                        </button>

                        <!-- Cancel -->
                        <button
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-red-400 focus:ring-opacity-50"
                        >
                            CANCEL
                        </button>

                        <!-- Clear All -->
                        <button
                            @click="clearAll()"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-gray-400 focus:ring-opacity-50"
                        >
                            CLEAR ALL
                        </button>
                        <!-- Coating -->
                        <button
                            @click="Inertia.visit('/coating')"
                            class="flex-1 px-4 py-3 text-lg font-bold text-white transition-all duration-300 transform shadow-md rounded-xl bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 hover:shadow-xl hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-orange-400 focus:ring-opacity-50"
                        >
                            COATING FORMAT
                        </button>
                    </div>
                </div>
            </div>
            <Modal :show="showModalSubmit" @close="showModalSubmit = false">
                <div class="relative">
                    <!-- Header with Danger Gradient -->
                    <div class="relative px-6 py-6 bg-gradient-to-r from-red-600 via-orange-600 to-red-700">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="hexagon" width="12" height="12" patternUnits="userSpaceOnUse">
                            <polygon points="6,1 11,4.5 11,9.5 6,13 1,9.5 1,4.5" fill="none" stroke="currentColor" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#hexagon)" />
                        </svg>
                    </div>
                    <!-- Header Content -->
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-25 border border-white rounded-xl backdrop-blur-sm border-opacity-40 animate-pulse">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l6.518 11.584c.75 1.334-.213 2.983-1.742 2.983H3.48c-1.53 0-2.492-1.65-1.742-2.983L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        </div>

                        <button @click="showModalSubmit = false"
                        class="p-2 text-white transition-all duration-200 rounded-lg hover:text-gray-200 hover:bg-white hover:bg-opacity-20 hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        </button>
                    </div>
                    </div>
                    <!-- Main Content -->
                    <div class="px-6 py-6 border border-red-200 rounded-lg shadow-inner bg-red-50">
                    <div class="mb-6 text-center">
                        <h3 class="flex items-center justify-center mb-2 space-x-2 text-xl font-bold text-red-700">
                        <svg class="w-5 h-5 text-red-600 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>⚠️ Confirm Submission Carefully</span>
                        </h3>
                        <p class="text-sm font-semibold text-red-700">
                        This action is <span class="underline">irreversible</span>! Please double-check all inputs.
                        </p>
                    </div>
                    <!-- Validation Checklist -->
                    <div class="p-4 mb-6 bg-red-100 border border-red-300 rounded-lg">
                        <h4 class="flex items-center mb-3 space-x-2 text-sm font-semibold text-red-800">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Checklist</span>
                        </h4>

                        <div class="space-y-2 text-xs text-red-800">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>All required fields completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>Data validated successfully</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                            <span>Submission is permanent. Proceed only if confident!</span>
                        </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button
                        @click="showModalSubmit = false"
                        class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition-all duration-200 transform hover:scale-[0.98] flex items-center justify-center space-x-2">
                        Cancel
                        </button>

                        <button
                        @click="saveToDatabase()"
                        class="group flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] relative overflow-hidden">
                        <div class="absolute inset-0 transition-transform transform -translate-x-full -skew-x-12 opacity-0 bg-gradient-to-r from-transparent via-white to-transparent group-hover:opacity-20 group-hover:translate-x-full duration-600"></div>
                        <span class="relative flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4 transition-all duration-300 group-hover:rotate-12 group-hover:scale-110 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>⚠️ Submit Now</span>
                        </span>
                        </button>
                    </div>
                    </div>
                    <!-- Bottom accent line -->
                    <div class="h-1 bg-gradient-to-r from-red-500 via-orange-400 to-red-500"></div>
                </div>
            </Modal>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import { useAuth } from '@/Composables/useAuth.js';
import { useToast } from 'vue-toast-notification';
const toast = useToast();

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {
        const start = Date.now();
        const maxWait = 5000; // 5 seconds

        while (!state.user) {
            if (Date.now() - start > maxWait) {
                console.error('User data failed to load in time. Redirecting...');
                Inertia.visit('/'); // Redirect if user never loads
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50));
        }

        if (!state.isAuthenticated) {
            console.warn('User is not authenticated. Redirecting...');
            Inertia.visit('/');
            return false;
        }

        console.log("USER AUTHENTICATED!", `${state.user.firstName} ${state.user.surname}`);
        return true;

    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/');
        return false;
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
// General Variables --------- General Variables

// Toggle variable ---------- Toggle Variable

const bypassValidation = ref();
const showModalSubmit = ref();
const isFilmPasteDataShown = ref();

// Toggle variable ---------- Toggle Variable END


const layers = ref(['1','2','3','4','5','6','7','8','9','9.5']);
const isExists = ref(false);
const existingLayers = ref([]);
const available_layers = ref([]);
const completedLayers = ref([]);
const massProdLists = ref([]);
const furnace_names = ref([]);

const filmPasteModel = ref();
const filmPasteLotNo = ref();

const filmPastingInfo = reactive({
    selectedFurnace: '',
    selected_mass_prod: '',
    selected_layer: null,
    film_pasting_date: '',
    machine_no: '',
    total_magnet_weight: 0,
    loader_operator: '',
    unloader_operator: '',
    checker_operator: '',
    film_coating_amount: '',
    time_start: '',
    time_finished: '',
    remarks: '',
    film_type: '',
    film_class: '',
    h_line_parameters: {
        sprayer_water_amount: 0,
        film_paste_lot_no_1: 0,
        film_paste_lot_no_2: 0,
        film_machine_humidity: 0,
        film_machine_temperature: 0,
        dryer_temp_setting: ''
    },
    t_line_parameters: {
        sprayer_water_amount: 0,
        film_paste_lot_no_1: 0,
        film_paste_lot_no_2: 0,
        film_machine_humidity: 0,
        film_machine_temperature: 0,
        dryer_temp_setting: ''
    },
    is_setter_sand: false,
});

// General Variables --------- General Variables END


const submit = async () => {
    if(!bypassValidation.value){
        if (
            !filmPastingInfo.film_pasting_date ||
            !filmPastingInfo.machine_no ||
            !filmPastingInfo.total_magnet_weight ||
            !filmPastingInfo.loader_operator ||
            !filmPastingInfo.unloader_operator ||
            !filmPastingInfo.checker_operator ||
            !filmPastingInfo.film_coating_amount ||
            !filmPastingInfo.time_start ||
            !filmPastingInfo.time_finished ||
            !filmPastingInfo.remarks ||
            !filmPastingInfo.film_type ||
            !filmPastingInfo.film_class
        ) {
            //console.log('Coating Table: ', coatingValues.value);
            toast.error("Please fill in all required Film Pasting Info fields.");
            return;
        }else if(
            !filmPastingInfo.is_setter_sand
        ){
            toast.error("Please choose Setter sand application per layer.");
            return;
        }
    }

    showModalSubmit.value = true;
}

const saveToDatabase = async () => {
    try{
        const payload = {
            furnace: filmPastingInfo.selectedFurnace,
            mass_prod: filmPastingInfo.selected_mass_prod,
            layer: Number(filmPastingInfo.selected_layer),
            date: filmPastingInfo.film_pasting_date,
            machine_no: filmPastingInfo.machine_no,
            total_magnet_weight: filmPastingInfo.total_magnet_weight,
            loader_operator: filmPastingInfo.loader_operator,
            unloader_operator: filmPastingInfo.unloader_operator,
            checker_operator: filmPastingInfo.checker_operator,
            film_coating_amount: filmPastingInfo.film_coating_amount,
            time_start: filmPastingInfo.time_start.slice(0,5),
            time_finished: filmPastingInfo.time_finished.slice(0,5),
            remarks: filmPastingInfo.remarks,
            film_type: filmPastingInfo.film_type,
            film_class: filmPastingInfo.film_class,
            h_line_parameters: filmPastingInfo.h_line_parameters,
            t_line_parameters: filmPastingInfo.t_line_parameters,
            setter_sand: filmPastingInfo.is_setter_sand === '1' ? true : false,
        };

        const response = await axios.post('/api/film-pasting-data', payload);
        console.log(response.data);
        toast.success('Data successfully saved.')

    }catch(error){
        toast.error('Failed to save film pasting data')
    }finally{
        showModalSubmit.value = false;
        isFilmPasteDataShown.value = false;
        await getCompletedLayers();
        await updateFormatType();
        await fetchAvailableLayers();
        await fetchExistingLayers();
        clearAll();
    }
};

const updateFormatType = async () => { // Update format type of Mass Productions Table
    const layerKey = filmPastingInfo.selected_layer === '9.5' ? 'layer_9_5_format_type' : `layer_${filmPastingInfo.selected_layer}_format_type`;

    const dataPayload = {
        furance: filmPastingInfo.selectedFurnace,
        mass_prod: filmPastingInfo.selected_mass_prod,
        [layerKey]: 'Film Pasting',
    }

    try{
        const responseUpdate = await axios.patch(`/api/mass-production/${filmPastingInfo.selectedFurnace}/${filmPastingInfo.selected_mass_prod}`, dataPayload);
        console.log('Response Update: ', responseUpdate.data);
    }catch(error){
        console.log('Failed to update format type');
    }
}

const clearAll = () => {
    Object.assign(filmPastingInfo,{
        film_pasting_date: '',
        machine_no: '',
        total_magnet_weight: 0,
        loader_operator: '',
        unloader_operator: '',
        checker_operator: '',
        film_coating_amount: '',
        time_start: '',
        time_finished: '',
        remarks: '',
        film_type: '',
        film_class: '',
        h_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        t_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        is_setter_sand: false,
    });
};


// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE

const getMassProdLists = async () => {
    try{
        const response = await axios.get('/api/mass-production');
        const massProdList = response.data;
        massProdLists.value = massProdList.map(item => item.mass_prod);
        //console.log("List of mass prods: ",massProd_names.value);
    }catch(error){
        console.error('Error fetching mass prod lists',error);
        toast.error('Failed to get the mass prod lists api error');
    }
}

const getSelectedMassProdData = async() => {
    try{
        const response = await axios.get(`/api/mass-production/${filmPastingInfo.selectedFurnace}/${filmPastingInfo.selected_mass_prod}/layer/${filmPastingInfo.selected_layer}/layer-no`);
        const massProdLayerData = response.data.layer_data;
        console.log('Mass Prod layer data: ', massProdLayerData);
        filmPasteModel.value = massProdLayerData[0].data['A'];
        filmPasteLotNo.value = massProdLayerData[2].data['A'];

        console.log('success response getSelectedMassProdData: ', massProdLayerData);
    }catch(error){
        console.error('Error fetching mass prod data:', error);
        toast.error('Failed to get the mass prod data api error');
    }
}

const getFurnaceLists = async () => {
    try{
        const response = await axios.get('/api/furnace-data');
        const furnaceList = response.data;
        furnace_names.value = furnaceList.map(item => item.furnace_name);
        //console.log("List of mass prods: ",furnace_names.value);
    }catch(error){
        console.error('Error fetching mass prod lists',error);
        toast.error('Failed to get the mass prod lists api error');
    }
}

const getCompletedLayers = async () => {
    try {
        const response = await axios.get(`/api/film-pasting-data/${filmPastingInfo.selectedFurnace}/${filmPastingInfo.selected_mass_prod}/layers`);
        completedLayers.value = response.data.layers.map(String);
        console.log("Completed Layers: ",completedLayers.value);
    } catch (error) {
        console.error(error);
        toast.error('Failed to fetch layers');
    }
};

// DATABASE FETCHING ZONE ------------------------------ DATABASE FETCHING ZONE END

// Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger

const fetchAvailableLayers = async () => {
    try {
        const response = await axios.get(
            `/api/mass-production/${filmPastingInfo.selectedFurnace}/${filmPastingInfo.selected_mass_prod}/completed-layers-film-paste`
        );
        available_layers.value = response.data.completed_layers;
        console.log("Available Layers: ", available_layers.value);
    } catch (error) {
        console.error(error);
        available_layers.value = [];
        toast.error('Failed to fetch available layers from Heat Treatment');
    }
};

const fetchExistingLayers = async () => {
    if (!filmPastingInfo.selected_mass_prod && !filmPastingInfo.selected_layer && !filmPastingInfo.selectedFurnace) {
        console.warn("Furnace, Mass Production or layer not selected yet.");
        return;
    }

    try {
        // 1st Coating
        const response1 = await axios.get(
            `/api/mass-production/${filmPastingInfo.selectedFurnace}/${filmPastingInfo.selected_mass_prod}/film-pasting-completed-layers`
        );
        existingLayers.value = response1.data.film_pasting_layers;
        console.log("Existing Layers for Coating:", existingLayers.value);

        // Initial check after fetching
        if (filmPastingInfo.selected_layer) {
            isExists.value = existingLayers.value.includes(filmPastingInfo.selected_layer);
        }

        if (isExists.value) {
            toast.warning('Selected layer already contains existing coating data.');
        }

    } catch (error) {
        isExists.value = false;
        console.error("Error fetching existing layers:", error);
        toast.error('Failed to fetch existing layers.');
    }
};

const fetchFilmPastingDataSummary = async () => {
    try {
        const response = await axios.get(
            `/api/initial-film-pasting/${filmPasteLotNo.value}/${filmPasteModel.value}/fetch-film-paste-data`
        );

        console.log('Fetched Film Paste Data:', response.data);

        const filmPaste = response.data;
        if (!filmPaste) return;

        filmPastingInfo.film_pasting_date    = filmPaste.film_pasting_date ?? '';
        filmPastingInfo.machine_no           = filmPaste.machine_no ?? '';
        filmPastingInfo.total_magnet_weight  = filmPaste.total_magnet_weight ?? 0;

        filmPastingInfo.loader_operator      = filmPaste.loader_operator ?? '';
        filmPastingInfo.unloader_operator    = filmPaste.unloader_operator ?? '';
        filmPastingInfo.checker_operator     = filmPaste.checker_operator ?? '';

        filmPastingInfo.film_coating_amount  = filmPaste.film_coating_amount ?? '';
        filmPastingInfo.time_start           = filmPaste.time_start ?? '';
        filmPastingInfo.time_finished        = filmPaste.time_finished ?? '';

        filmPastingInfo.remarks              = filmPaste.remarks ?? '';

        filmPastingInfo.film_type            = filmPaste.film_type ?? '';
        filmPastingInfo.film_class           = filmPaste.film_class ?? '';

        filmPastingInfo.is_setter_sand       = filmPaste.setter_sand ? "1" : "0";

        // --- H Line ---
        if (filmPaste.h_line_parameters) {
            filmPastingInfo.h_line_parameters.sprayer_water_amount     = filmPaste.h_line_parameters.sprayer_water_amount ?? 0;
            filmPastingInfo.h_line_parameters.film_paste_lot_no_1      = filmPaste.h_line_parameters.film_paste_lot_no_1 ?? 0;
            filmPastingInfo.h_line_parameters.film_paste_lot_no_2      = filmPaste.h_line_parameters.film_paste_lot_no_2 ?? 0;
            filmPastingInfo.h_line_parameters.film_machine_humidity    = filmPaste.h_line_parameters.film_machine_humidity ?? 0;
            filmPastingInfo.h_line_parameters.film_machine_temperature = filmPaste.h_line_parameters.film_machine_temperature ?? 0;
            filmPastingInfo.h_line_parameters.dryer_temp_setting       = filmPaste.h_line_parameters.dryer_temp_setting ?? '';
        }

        // --- T Line ---
        if (filmPaste.t_line_parameters) {
            filmPastingInfo.t_line_parameters.sprayer_water_amount     = filmPaste.t_line_parameters.sprayer_water_amount ?? 0;
            filmPastingInfo.t_line_parameters.film_paste_lot_no_1      = filmPaste.t_line_parameters.film_paste_lot_no_1 ?? 0;
            filmPastingInfo.t_line_parameters.film_paste_lot_no_2      = filmPaste.t_line_parameters.film_paste_lot_no_2 ?? 0;
            filmPastingInfo.t_line_parameters.film_machine_humidity    = filmPaste.t_line_parameters.film_machine_humidity ?? 0;
            filmPastingInfo.t_line_parameters.film_machine_temperature = filmPaste.t_line_parameters.film_machine_temperature ?? 0;
            filmPastingInfo.t_line_parameters.dryer_temp_setting       = filmPaste.t_line_parameters.dryer_temp_setting ?? '';
        }

        isFilmPasteDataShown.value = true;

    }  catch (error) {

        if (error.response && error.response.status === 404) {
            toast.warning('No record exists for this model and lot no');
            isFilmPasteDataShown.value = false;
            return;
        }

        console.error('Film Pasting Data Summary:', error);
    }
};

const changeDataFilmPaste = () => {
    Object.assign(filmPastingInfo,{
        film_pasting_date: '',
        machine_no: '',
        total_magnet_weight: 0,
        loader_operator: '',
        unloader_operator: '',
        checker_operator: '',
        film_coating_amount: '',
        time_start: '',
        time_finished: '',
        remarks: '',
        film_type: '',
        film_class: '',
        h_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        t_line_parameters: {
            sprayer_water_amount: 0,
            film_paste_lot_no_1: 0,
            film_paste_lot_no_2: 0,
            film_machine_humidity: 0,
            film_machine_temperature: 0,
            dryer_temp_setting: ''
        },
        is_setter_sand: false,
    });


    isFilmPasteDataShown.value = false;
}

// Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger ------ Fetch on trigger END


// Watch zone -------- Watch Zone

watch(
    [
        () => filmPastingInfo.selectedFurnace,
        () => filmPastingInfo.selected_mass_prod,
        () => filmPastingInfo.selected_layer
    ],
    async ([newFurnace, newMassProd, newLayer]) => {
        if (newFurnace && newMassProd) {
            await getCompletedLayers();
            await fetchAvailableLayers();
            await fetchExistingLayers();
            await getSelectedMassProdData();
        } else {
            completedLayers.value = []; // reset if furnace or mass_prod is cleared
        }
    }
);

// Watch zone -------- Watch Zone END


// APPLYING Browser Session ----------------- APPLYING Browser Session

useSessionStorage("filmPastingInfo", filmPastingInfo);

// APPLYING Browser Session ----------------- APPLYING Browser Session

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return;
    }
    await getMassProdLists();
    await getFurnaceLists();
});

</script>

<style scoped>

    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>
