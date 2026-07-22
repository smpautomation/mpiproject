<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 space-y-6 bg-gray-100">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between pb-6 border-b border-gray-100">
                <!-- Title Section -->
                <div class="flex-1">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Heat Treatment 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-teal-500 to-cyan-500">
                            Graph Patterns
                        </span>
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed max-w-xl">
                        Manage, review, and encode industrial furnace configurations and target model rules.
                    </p>
                </div>

                <!-- Actions Container (Contextual Create Buttons) -->
                <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center w-full lg:w-auto shrink-0">
                    
                    <!-- Primary Action: Create New Pattern -->
                    <button
                        type="button"
                        @click="showModalCreatePattern = true"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-white transition-all duration-200 bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 rounded-xl shadow-md shadow-teal-500/10 hover:shadow-teal-500/15 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 active:scale-[0.98] whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create New Pattern
                    </button>

                    <!-- Elegant Border Action: Create New Rule -->
                    <button
                        type="button"
                        @click="showModalCreateRule = true"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-teal-800 transition-all duration-200 bg-white border border-teal-100 hover:bg-teal-50/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 active:scale-[0.98] whitespace-nowrap"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 text-teal-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create New Rule
                    </button>
                </div>
            </div>

            <!-- Empty State Panel -->
            <div v-if="graphFileLists.length === 0"
                class="flex flex-col items-center justify-center p-8 border border-gray-200 rounded-lg bg-gray-50 animate-fadeIn">

                <!-- Pulsing Icon -->
                <svg class="w-12 h-12 mb-4 text-gray-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6a2 2 0 114 0v6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h5l5 5v7a2 2 0 01-2 2z"/>
                </svg>

                <!-- Message -->
                <p class="text-sm text-center text-gray-500 delay-200 animate-fadeIn">
                    No graphs found. Start by creating a new pattern.
                </p>
            </div>


            <!-- Patterns container -->
            <div v-else class="w-full mt-12 overflow-x-auto bg-white border border-gray-100 shadow-sm rounded-xl">
                
                <div class="space-y-4 w-full">
                    <!-- Browser-style Tab Bar Nav -->
                    <div class="flex items-center border-b border-gray-250 bg-slate-50/50 p-1.5 rounded-t-2xl gap-1">
                        <!-- Tab A: Patterns List -->
                        <button
                            type="button"
                            @click="activeTab = 'patterns'"
                            class="flex items-center gap-2 px-4 py-2 text-xs font-bold transition-all duration-200 rounded-xl focus:outline-none"
                            :class="activeTab === 'patterns' 
                                ? 'bg-white text-teal-700 shadow-sm border border-gray-200/80' 
                                : 'text-gray-500 hover:text-gray-800 hover:bg-slate-100'"
                        >
                            <!-- Pattern Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h3a1 1 0 011 1v6a1 1 0 01-1 1h-3a1 1 0 01-1-1v-6z" />
                            </svg>
                            Pattern Registers
                        </button>

                        <!-- Tab B: Model Rules -->
                        <button
                            type="button"
                            @click="activeTab = 'rules'"
                            class="flex items-center gap-2 px-4 py-2 text-xs font-bold transition-all duration-200 rounded-xl focus:outline-none"
                            :class="activeTab === 'rules' 
                                ? 'bg-white text-teal-700 shadow-sm border border-gray-200/80' 
                                : 'text-gray-500 hover:text-gray-800 hover:bg-slate-100'"
                        >
                            <!-- Rules/Layers Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Model Configurations
                        </button>
                    </div>

                    <!-- Active Tab Content Area -->
                    <div class="transition-all duration-250">
                        
                        <!-- ==================== TABLE 1: PATTERNS ==================== -->
                        <div v-show="activeTab === 'patterns'" class="space-y-3.5">
                            
                            <!-- Table Action Header: Holds your filters specifically for Patterns -->
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between p-4 bg-white border border-gray-200 rounded-2xl shadow-sm">
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-sm font-bold text-slate-900">Pattern Registry Directory</h3>
                                    <p class="text-xs text-slate-500">Search and filter registered furnace configurations</p>
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <!-- Modern Minimalist Search Input -->
                                    <div class="relative flex-1 sm:w-64 lg:w-72">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.603 10.603Z" />
                                            </svg>
                                        </span>
                                        <input
                                            type="number"
                                            v-model="searchQuery"
                                            placeholder="Search by pattern number..."
                                            class="w-full pl-10 pr-4 py-2 text-sm text-gray-900 placeholder-gray-400 bg-white border border-gray-200 rounded-xl shadow-sm transition-all focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                        />
                                    </div>

                                    <!-- New Furnace Filter Dropdown -->
                                    <div class="w-full sm:w-48">
                                        <select
                                            v-model="selectedFurnaceFilter"
                                            class="w-full px-3.5 py-2 text-sm text-gray-900 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                                        >
                                            <option value="">All Furnaces</option>
                                            <option v-for="item in furnaceNo" :key="item" :value="item">
                                                {{ item }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- The actual Table Container -->
                            <div class="w-full overflow-hidden border border-gray-200 shadow-sm rounded-2xl bg-white">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse table-auto">
                                        <!-- ... Your original <thead> and <tbody> for graphFileLists ... -->
                                        <thead>
                                            <tr class="border-b border-gray-100 bg-gray-50/90">
                                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-teal-800 uppercase">Pattern</th>
                                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-teal-800 uppercase">Furnace</th>
                                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-teal-800 uppercase">Encoded By</th>
                                                <th class="px-6 py-4 text-right text-xs font-bold tracking-wider text-teal-800 uppercase">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            <tr
                                                v-for="(pattern, index) in graphFileLists"
                                                :key="pattern.id || index"
                                                class="relative transition-colors cursor-pointer group hover:bg-cyan-50/30"
                                                @click="selectedGraph = pattern.url; selectedPattern = pattern; showModalGraph = true"
                                            >
                                                <td class="px-6 py-4.5 whitespace-nowrap border-l-2 border-transparent group-hover:border-cyan-500 transition-colors">
                                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-cyan-700 transition-colors">
                                                        {{ pattern.pattern_no }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-5 whitespace-nowrap">
                                                    <span class="text-sm text-gray-600">
                                                        {{ pattern.furnace_no }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-5 max-w-[200px] truncate">
                                                    <span class="text-sm text-gray-500">
                                                        {{ pattern.encoded_by }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-5 text-right whitespace-nowrap" @click.stop>
                                                    <button
                                                        @click="editPattern(pattern)"
                                                        class="px-3.5 py-1.5 text-xs font-semibold tracking-wide text-teal-700 bg-teal-50 rounded-lg hover:bg-teal-600 hover:text-white transition-all duration-200"
                                                    >
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Table 1 Pagination Footer -->
                                <div 
                                    v-if="paginationMeta && graphFileLists.length > 0" 
                                    class="flex items-center justify-between px-6 py-3 bg-white border-t border-gray-150 rounded-b-xl"
                                >
                                    <!-- Left Section: Meta Count -->
                                    <div>
                                        <p class="text-xs text-gray-700">
                                            Showing 
                                            <span class="font-bold text-slate-900">{{ paginationMeta.from }}</span> 
                                            to 
                                            <span class="font-bold text-slate-900">{{ paginationMeta.to }}</span> 
                                            of 
                                            <span class="font-bold text-slate-900">{{ paginationMeta.total }}</span> 
                                            patterns
                                        </p>
                                    </div>

                                    <!-- Right Section: Compact Navigation Controls -->
                                    <div>
                                        <nav class="relative z-0 inline-flex items-center rounded-lg shadow-sm bg-white border border-cyan-100 p-0.5 gap-0.5" aria-label="Pagination">
                                            <!-- Prev Button -->
                                            <button
                                                type="button"
                                                @click="currentPage > 1 ? currentPage-- : null"
                                                :disabled="currentPage === 1"
                                                class="relative inline-flex items-center justify-center p-1 rounded-md text-cyan-600 hover:text-cyan-700 hover:bg-cyan-50/50 transition focus:outline-none disabled:opacity-30 disabled:hover:bg-transparent disabled:cursor-not-allowed shrink-0"
                                                aria-label="Previous page"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>

                                            <!-- Compact Page Tracker badge -->
                                            <span class="relative inline-flex items-center px-2.5 py-1 rounded-md bg-cyan-50/40 text-[10px] font-extrabold uppercase tracking-wider text-teal-800 select-none whitespace-nowrap">
                                                {{ currentPage }} / {{ paginationMeta.last_page }}
                                            </span>

                                            <!-- Next Button -->
                                            <button
                                                type="button"
                                                @click="currentPage < paginationMeta.last_page ? currentPage++ : null"
                                                :disabled="currentPage === paginationMeta.last_page"
                                                class="relative inline-flex items-center justify-center p-1 rounded-md text-cyan-600 hover:text-cyan-700 hover:bg-cyan-50/50 transition focus:outline-none disabled:opacity-30 disabled:hover:bg-transparent disabled:cursor-not-allowed shrink-0"
                                                aria-label="Next page"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TABLE 2: MODEL RULES ==================== -->
                        <div v-show="activeTab === 'rules'" class="space-y-3.5">
                            
                            <!-- Table Action Header: Search specifically for Model Rules -->
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between p-4 bg-white border border-gray-200 rounded-2xl shadow-sm">
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-sm font-bold text-slate-900">Model Configuration Rules</h3>
                                    <p class="text-xs text-slate-500">Search rules by model</p>
                                </div>

                                <!-- Search input triggering fetchModelRulesTable -->
                                <div class="relative w-full sm:w-72 lg:w-80">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.603 10.603Z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="text"
                                        v-model="searchQueryRules"
                                        @input="fetchModelRulesTable(1)"
                                        placeholder="Search by model"
                                        class="w-full pl-10 pr-4 py-2 text-sm text-gray-900 placeholder-gray-400 bg-white border border-gray-200 rounded-xl shadow-sm transition-all focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                                    />
                                </div>
                            </div>

                            <!-- Table Container -->
                            <div class="w-full overflow-hidden border border-gray-200 shadow-sm rounded-2xl bg-white">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[700px] border-collapse text-left text-sm text-gray-500">
                                        <!-- Table Header -->
                                        <thead class="bg-gray-50 border-b border-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Model</th>
                                                <th scope="col" class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Allowed Patterns</th>
                                                <th scope="col" class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Allowed Layers</th>
                                                <th scope="col" class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Encoded By</th>
                                            </tr>
                                        </thead>

                                        <!-- Table Body -->
                                        <tbody class="divide-y divide-gray-100 bg-white">
                                            <!-- Data Rows -->
                                            <tr 
                                                v-for="rule in modelRules" 
                                                :key="rule.model"
                                                class="hover:bg-slate-50/50 transition-colors duration-150"
                                            >
                                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ rule.model }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap gap-1.5 max-w-[280px]">
                                                        <span 
                                                            v-for="pattern in rule.pattern_list" 
                                                            :key="pattern"
                                                            class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-teal-700 bg-teal-50 border border-teal-100/60 rounded-md"
                                                        >
                                                            P-{{ pattern }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap gap-1.5 max-w-[280px]">
                                                        <span 
                                                            v-for="layer in rule.layer_list" 
                                                            :key="layer"
                                                            class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200/60 rounded-md"
                                                        >
                                                            L-{{ layer }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-600">{{ rule.rule_encoded_by }}</td>
                                            </tr>

                                            <!-- Empty State (No records matching filters) -->
                                            <tr v-if="modelRules.length === 0">
                                                <td colspan="4" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center justify-center gap-2">
                                                        <div class="p-3 bg-slate-50 text-slate-400 rounded-full">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-3.586-3.586a2 2 0 00-2.828 0L16 11m-10 2l3.586-3.586a2 2 0 012.828 0L13 11" />
                                                            </svg>
                                                        </div>
                                                        <span class="text-sm font-bold text-slate-800">No model configurations found</span>
                                                        <span class="text-xs text-slate-500 max-w-xs">There are currently no active rules mapped matching your query or search criteria.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Table 2 Pagination Footer -->
                                <div v-if="rulePaginationMeta && modelRules.length > 0" class="flex items-center justify-between px-6 py-4 bg-white border-t border-gray-150">
                                    <!-- Mobile Pagination -->
                                    <div class="flex-1 flex justify-between sm:hidden">
                                        <button
                                            @click="fetchModelRulesTable(currentRulesPage - 1)"
                                            :disabled="currentRulesPage === 1"
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-250 text-xs font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition disabled:opacity-40 disabled:cursor-not-allowed"
                                        >
                                            Previous
                                        </button>
                                        <button
                                            @click="fetchModelRulesTable(currentRulesPage + 1)"
                                            :disabled="currentRulesPage === rulePaginationMeta.last_page"
                                            class="relative inline-flex items-center ml-3 px-4 py-2 border border-gray-250 text-xs font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition disabled:opacity-40 disabled:cursor-not-allowed"
                                        >
                                            Next
                                        </button>
                                    </div>
                                    
                                    <!-- Desktop Pagination -->
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-xs text-gray-700">
                                                Showing 
                                                <span class="font-bold text-slate-900">{{ rulePaginationMeta.from }}</span> 
                                                to 
                                                <span class="font-bold text-slate-900">{{ rulePaginationMeta.to }}</span> 
                                                of 
                                                <span class="font-bold text-slate-900">{{ rulePaginationMeta.total }}</span> 
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex items-center rounded-lg shadow-sm bg-white border border-cyan-100 p-0.5 gap-0.5" aria-label="Pagination">
                                                <!-- Prev Button -->
                                                <button
                                                    type="button"
                                                    @click="fetchModelRulesTable(currentRulesPage - 1)"
                                                    :disabled="currentRulesPage === 1"
                                                    class="relative inline-flex items-center justify-center p-1 rounded-md text-cyan-600 hover:text-cyan-700 hover:bg-cyan-50/50 transition focus:outline-none disabled:opacity-30 disabled:hover:bg-transparent disabled:cursor-not-allowed shrink-0"
                                                    aria-label="Previous page"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                </button>

                                                <!-- Compact Page Tracker badge -->
                                                <span class="relative inline-flex items-center px-2.5 py-1 rounded-md bg-cyan-50/40 text-[10px] font-extrabold uppercase tracking-wider text-teal-800 select-none whitespace-nowrap">
                                                    {{ currentRulesPage }} / {{ rulePaginationMeta.last_page }}
                                                </span>

                                                <!-- Next Button -->
                                                <button
                                                    type="button"
                                                    @click="fetchModelRulesTable(currentRulesPage + 1)"
                                                    :disabled="currentRulesPage === rulePaginationMeta.last_page"
                                                    class="relative inline-flex items-center justify-center p-1 rounded-md text-cyan-600 hover:text-cyan-700 hover:bg-cyan-50/50 transition focus:outline-none disabled:opacity-30 disabled:hover:bg-transparent disabled:cursor-not-allowed shrink-0"
                                                    aria-label="Next page"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Modal for full-size image -->
            <Modal :show="showModalGraph" @close="showModalGraph = false">
                <div class="relative flex flex-col items-center w-full max-w-3xl p-6 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">
                    <!-- Close button -->
                    <button
                        @click="showModalGraph = false"
                        class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-50"
                        aria-label="Close"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Header & Pattern Info -->
                    <div class="w-full text-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">
                            Pattern {{ selectedPattern?.pattern_no }}
                        </h3>
                        
                        <!-- Metadata Badges -->
                        <div class="flex flex-wrap items-center justify-center gap-2 text-xs font-medium text-gray-600">
                            <span class="px-3 py-1 bg-gray-50 border border-gray-100 rounded-full">
                                <span class="text-gray-400 font-normal">Furnace:</span> {{ selectedPattern?.furnace_no }}
                            </span>
                            <span class="px-3 py-1 bg-gray-50 border border-gray-100 rounded-full">
                                <span class="text-gray-400 font-normal">Encoded by:</span> {{ selectedPattern?.encoded_by }}
                            </span>
                            <span class="px-3 py-1 bg-teal-50/50 border border-teal-100/50 text-teal-800 rounded-full">
                                <span class="text-teal-600/70 font-normal">Duration:</span> {{ selectedPattern?.pattern_no_hours }} hrs
                            </span>
                        </div>
                    </div>

                    <!-- Image Display Container (Strict Fixed Dimension Wrapper) -->
                    <div class="w-full h-[450px] overflow-hidden border border-gray-200 rounded-xl bg-gray-50/50 shadow-inner p-2 flex items-center justify-center">
                        <img
                            :src="selectedGraph"
                            alt="Graph pattern"
                            class="w-full h-full object-contain rounded-lg mix-blend-multiply"
                        />
                    </div>
                </div>
            </Modal>

            <Modal :show="showModalEdit" @close="showModalEdit = false">
                <div class="relative flex flex-col w-full max-w-3xl p-8 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">
                    <!-- Close Button -->
                    <button 
                        @click="showModalEdit = false" 
                        class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-50"
                        aria-label="Close modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h3 class="mb-6 text-lg font-bold text-center text-gray-900">Edit Pattern</h3>

                    <!-- Form Fields Row 1 -->
                    <div class="grid grid-cols-1 gap-5 mb-5 md:grid-cols-2">
                        <!-- Pattern No -->
                        <div class="flex flex-col">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Pattern No.</label>
                            <input 
                                type="number" 
                                v-model="patternNo" 
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>

                        <!-- Furnace No -->
                        <div class="flex flex-col">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Furnace No</label>
                            <select 
                                v-model="selectedFurnace" 
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            >
                                <option v-for="item in furnaceNo" :key="item" :value="item">{{ item }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Form Fields Row 2 -->
                    <div class="flex flex-col w-full gap-5 mb-6 md:flex-row">
                        <!-- Replace Graph -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Replace Graph (optional)</label>
                            <input 
                                type="file"
                                ref="editFile"
                                accept=".jpeg,.jpg,.png"
                                @change="handleEditGraph"
                                class="w-full px-3 py-1 text-sm text-gray-900 placeholder-gray-400 bg-white border border-gray-200 rounded-xl shadow-sm cursor-pointer file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-teal-50 file:text-teal-700 file:font-semibold hover:file:bg-teal-100 file:transition-colors file:cursor-pointer focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            />
                            <p class="mt-1.5 text-xs text-gray-400">Supported formats: JPEG, JPG, or PNG only.</p>
                        </div>

                        <!-- Time (Hours) -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Time (Hours)</label>
                            <input
                                type="number"
                                v-model="patternNoHours"
                                placeholder="Input Hours..."
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 transition bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div v-if="uploadedGraphEdited" class="w-full mb-6">
                        <p class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Preview:</p>
                        <div class="text-center border border-gray-200 rounded-xl bg-gray-50/50 shadow-inner p-2">
                            <img :src="uploadedGraphEdited" alt="Edited Graph Preview" class="inline-block object-contain max-h-48"/>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-center w-full">
                        <button 
                            @click="updatePattern"
                            class="px-8 py-2.5 text-sm font-semibold text-white transition-all duration-200 rounded-xl shadow-sm bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-500/20"
                        >
                            Save Changes
                        </button>
                    </div>
                </div>
            </Modal>


            <Modal :show="showModalCreatePattern" @close="showModalCreatePattern = false">
                <div class="relative flex flex-col items-center justify-center w-full max-w-3xl p-8 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100">
                    <!-- Close button -->
                    <button
                        @click="showModalCreatePattern = false"
                        class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-50"
                        aria-label="Close modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Header -->
                    <h3 class="mb-6 text-lg font-bold text-center text-gray-900">Please fill out all the fields</h3>

                    <!-- Form fields row 1 -->
                    <div class="flex flex-col w-full gap-5 mb-5 md:flex-row">
                        <!-- Pattern No -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Pattern No.</label>
                            <input
                                type="number"
                                v-model="patternNo"
                                placeholder="Input Pattern Number..."
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 uppercase transition bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>

                        <!-- Furnace No -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Furnace No</label>
                            <select
                                v-model="selectedFurnace"
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            >
                                <option v-for="item in furnaceNo" :key="item" :value="item">{{ item }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Form fields row 2 -->
                    <div class="flex flex-col w-full gap-5 mb-6 md:flex-row">
                        <!-- Upload Graph -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Upload Graph</label>
                            <input
                                type="file"
                                id="cycleGraph"
                                accept=".jpeg,.jpg,.png"
                                @change="uploadGraph"
                                class="w-full px-3 py-1 text-sm text-gray-900 placeholder-gray-400 bg-white border border-gray-200 rounded-xl shadow-sm cursor-pointer file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-teal-50 file:text-teal-700 file:font-semibold hover:file:bg-teal-100 file:transition-colors file:cursor-pointer focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            />
                            <p class="mt-1.5 text-xs text-gray-400">Supported formats: JPEG, JPG, or PNG only.</p>
                        </div>

                        <!-- Time (Hours) -->
                        <div class="flex flex-col flex-1">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Time (Hours)</label>
                            <input
                                type="number"
                                v-model="patternNoHours"
                                placeholder="Input Hours..."
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 transition bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            />
                        </div>
                    </div>

                    <!-- File preview -->
                    <div v-if="uploadedGraph" class="w-full mt-2 mb-6">
                        <p class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Preview:</p>
                        <img
                            :src="uploadedGraph"
                            alt="Graph Preview"
                            class="object-contain w-full border border-gray-200 rounded-xl bg-gray-50/50 shadow-inner max-h-48 p-2"
                        />
                    </div>

                    <!-- Submit / Confirmation -->
                    <div v-if="!showConfirmation" class="flex justify-center w-full">
                        <button
                            @click="submitFormPattern"
                            class="px-8 py-2.5 text-sm font-semibold text-white transition-all duration-200 rounded-xl shadow-sm bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-500/20"
                        >
                            Submit
                        </button>
                    </div>

                    <div v-else class="flex flex-col items-center justify-center gap-4 w-full bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                        <p class="text-sm font-medium text-center text-gray-700">
                            Are you <span class="font-bold text-rose-600">sure</span> that input is correct?
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="showConfirmation = false"
                                class="px-6 py-2 text-sm font-semibold text-gray-700 transition-colors duration-200 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 hover:text-gray-900"
                            >
                                No
                            </button>
                            <button
                                @click="saveToDatabase"
                                class="px-6 py-2 text-sm font-semibold text-white transition-colors duration-200 bg-teal-600 rounded-xl shadow-sm hover:bg-teal-700"
                            >
                                Yes
                            </button>
                        </div>
                    </div>
                </div>
            </Modal>

            <Modal :show="showModalCreateRule" @close="showModalCreateRule = false">
                <div class="relative flex flex-col w-full max-w-3xl p-6 mx-auto bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    
                    <!-- Close Button -->
                    <button
                        @click="showModalCreateRule = false, overwriteMode = false, msgRuleAlreadyExists = false, showConfirmationRule = false"
                        class="absolute p-2 text-gray-400 transition-colors duration-150 rounded-full top-4 right-4 hover:text-gray-600 hover:bg-gray-100/80 focus:outline-none"
                        aria-label="Close modal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                                Create New Rule
                            </h3>
                            
                            <!-- Premium Overwrite Mode Indicator Badge -->
                            <span 
                                v-if="overwriteMode" 
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 border border-rose-150 text-[10px] font-extrabold uppercase tracking-wider text-rose-700 select-none shadow-sm shadow-rose-500/5 animate-pulse"
                            >
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Overwrite ON
                            </span>
                        </div>
                        <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Configure your target model specifications, active pattern parameters, and mechanical layer alignments.
                        </p>
                    </div>

                    <!-- Form Body -->
                    <div class="space-y-6">
                        
                        <!-- Row 1: Model Selection (Spans Full Width for clean entry) -->
                        <div class="flex flex-col">
                            <label class="mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-500">Target Model</label>
                            <select
                                v-model="selectedModel"
                                :disabled="showConfirmationRule"
                                class="w-full px-3.5 py-2.5 text-sm font-medium text-gray-700 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                            >
                                <option value="" disabled>Select Model</option>
                                <option v-for="item in modelLists" :key="item" :value="item">{{ item }}</option>
                            </select>
                        </div>

                        <!-- Row 2: Two-Column Array Builders Grid -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            
                            <!-- Column A: Patterns Card -->
                            <div class="flex flex-col p-4 border border-slate-100 bg-slate-50/70 rounded-2xl">
                                <label class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Pattern Configurations</label>
                                
                                <div class="space-y-3">
                                    <!-- Dropdown + Add button input group -->
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <select
                                                v-model="currentSelectedPattern"
                                                class="w-full px-3.5 py-2 text-sm font-medium text-gray-700 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                                            >
                                                <option value="" disabled>Select Pattern</option>
                                                <option v-for="item in patternLists" :key="item" :value="item">{{ item }}</option>
                                            </select>
                                        </div>
                                        <button
                                            type="button"
                                            @click="addPatternToArray"
                                            :disabled="!currentSelectedPattern || showConfirmationRule"
                                            class="px-4 py-2 text-sm font-semibold text-white transition-all duration-200 rounded-xl shadow-sm bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-40 disabled:cursor-not-allowed disabled:pointer-events-none active:scale-[0.98]"
                                        >
                                            Add
                                        </button>
                                    </div>

                                    <!-- Error Message -->
                                    <div v-show="msgPatternAlreadyAdded" class="flex items-center gap-1.5 text-rose-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs font-semibold">Pattern already added!</span>
                                    </div>

                                    <!-- Active Preview Container -->
                                    <div v-if="selectedPatterns.length > 0" class="p-3 bg-white border border-slate-100 rounded-xl shadow-inner-sm">
                                        <span class="block mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Selected Patterns ({{ selectedPatterns.length }})</span>
                                        <div class="flex flex-wrap gap-1.5 max-h-[120px] overflow-y-auto">
                                            <span
                                                v-for="(pattern, index) in selectedPatterns"
                                                :key="pattern"
                                                class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-1 text-xs font-bold text-teal-700 bg-teal-50/80 border border-teal-100/50 rounded-lg"
                                            >
                                                P-{{ pattern }}
                                                <button
                                                    type="button"
                                                    @click="removePattern(index)"
                                                    :disabled="showConfirmationRule"
                                                    class="p-0.5 text-teal-400 hover:text-teal-700 hover:bg-teal-100/50 transition-colors rounded-md focus:outline-none"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Column B: Layers Card -->
                            <div class="flex flex-col p-4 border border-slate-100 bg-slate-50/70 rounded-2xl">
                                <label class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Layer Alignments</label>
                                
                                <div class="space-y-3">
                                    <!-- Dropdown + Add button input group -->
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <select
                                                v-model="currentlySelectedLayer"
                                                class="w-full px-3.5 py-2 text-sm font-medium text-gray-700 bg-white transition border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10"
                                            >
                                                <option value="" disabled>Select Layer</option>
                                                <option v-for="item in layerLists" :key="item" :value="item">{{ item }}</option>
                                            </select>
                                        </div>
                                        <button
                                            type="button"
                                            @click="addLayerToArray"
                                            :disabled="!currentlySelectedLayer || showConfirmationRule"
                                            class="px-4 py-2 text-sm font-semibold text-white transition-all duration-200 rounded-xl shadow-sm bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-40 disabled:cursor-not-allowed disabled:pointer-events-none active:scale-[0.98]"
                                        >
                                            Add
                                        </button>
                                    </div>

                                    <!-- Preset Quick Actions (Styled elegantly as low-profile tabs instead of big buttons) -->
                                    <div v-if="selectedLayers.length < 1" class="flex flex-wrap gap-2 pt-1">
                                        <button
                                            type="button"
                                            @click="addAllLayerToArray"
                                            :disabled="showConfirmationRule"
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-teal-700 bg-white border border-teal-200/55 rounded-lg hover:bg-teal-50/40 transition-colors"
                                        >
                                            All Layers
                                        </button>
                                        <button
                                            type="button"
                                            @click="addAllLayerExceptNinePointFiveToArray"
                                            :disabled="showConfirmationRule"
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors"
                                        >
                                            Layers 1 to 9 Only
                                        </button>
                                    </div>

                                    <!-- Error Message -->
                                    <div v-show="msgLayerAlreadyAdded" class="flex items-center gap-1.5 text-rose-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs font-semibold">Layer already added!</span>
                                    </div>

                                    <!-- Active Preview Container -->
                                    <div v-if="selectedLayers.length > 0" class="p-3 bg-white border border-slate-100 rounded-xl shadow-inner-sm">
                                        <span class="block mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Selected Layers ({{ selectedLayers.length }})</span>
                                        <div class="flex flex-wrap gap-1.5 max-h-[120px] overflow-y-auto">
                                            <span
                                                v-for="(layer, index) in selectedLayers"
                                                :key="layer"
                                                class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-1 text-xs font-bold text-teal-700 bg-teal-50/80 border border-teal-100/50 rounded-lg"
                                            >
                                                L-{{ layer }}
                                                <button
                                                    type="button"
                                                    @click="removeLayer(index)"
                                                    :disabled="showConfirmationRule"
                                                    class="p-0.5 text-teal-400 hover:text-teal-700 hover:bg-teal-100/50 transition-colors rounded-md focus:outline-none"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Submit & Confirmation Section -->
                    <div class="mt-8 pt-5 border-t border-slate-100">
                        <!-- Normal Submit View -->

                        <div v-if="!showConfirmationRule" class="flex flex-col gap-3.5 w-full mt-4">
    
                            <!-- Premium Alert Callout for Existing Rules -->
                            <div 
                                v-if="msgRuleAlreadyExists" 
                                class="flex items-start gap-3 px-4 py-3 bg-red-50/50 border border-red-100 rounded-xl shadow-sm transition-all duration-200"
                            >
                                <!-- Info Warning Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-red-600 shrink-0 mt-0.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12v-.008Z" />
                                </svg>
                                
                                <div class="flex flex-col flex-1">
                                    <span class="text-xs font-bold text-red-950">Existing Configuration Found</span>
                                    <span class="text-[11px] text-red-800/90 leading-relaxed mt-0.5">
                                        The selected model already has active rules mapped. Submitting right now will overwrite the current configuration logs.
                                    </span>
                                    
                                    <!-- Interactive Enable Overwrite Switcher Link -->
                                    <div class="mt-2 flex items-center">
                                        <button
                                            type="button"
                                            @click="overwriteMode = true; msgRuleAlreadyExists = false"
                                            class="group inline-flex items-center gap-1 text-[11px] font-extrabold tracking-wide uppercase text-red-700 hover:text-red-900 focus:outline-none transition-colors duration-150"
                                        >
                                            <span class="underline decoration-red-300/80 group-hover:decoration-red-600 underline-offset-4 transition-colors duration-150">
                                                Click here to unlock overwrite mode
                                            </span>
                                            <!-- Clean Micro-interaction arrow chevron -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-red-600 group-hover:translate-x-0.5 transition-transform duration-150">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Right-Aligned Action Button Wrapper -->
                            <div class="flex justify-end w-full">
                                <button
                                    type="button"
                                    @click="submitFormRule"
                                    class="w-full md:w-auto px-6 py-2 text-xs font-bold text-white transition-all duration-200 rounded-xl shadow-sm bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-teal-500/10 active:scale-[0.98] whitespace-nowrap"
                                >
                                    Submit Configuration
                                </button>
                            </div>
                        </div>

                        <!-- Confirmation Gate -->
                        <div v-else class="flex flex-col gap-4 p-4 border border-rose-200 bg-rose-50/50 rounded-2xl sm:flex-row sm:items-start sm:justify-between">
                            
                            <!-- Left Hand: Icon & Alert Message Context -->
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <!-- Structural Alert Icon -->
                                <div class="p-2 text-rose-600 bg-rose-100 rounded-xl shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                
                                <!-- Text & Summary Group -->
                                <div class="space-y-4 flex-1 min-w-0">
                                    <div class="space-y-2">
                                        <!-- Header + Dynamic Alert Badge Row -->
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="text-sm font-bold text-slate-900">Double check specifications</h4>
                                            
                                            <!-- Shows specifically when overwrite mode is enabled -->
                                            <span 
                                                v-if="overwriteMode" 
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 text-[10px] font-extrabold uppercase tracking-wider text-rose-700 border border-rose-100/80 animate-pulse"
                                            >
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Overwrite Mode ON
                                            </span>
                                        </div>
                                        
                                        <!-- Contextual Descriptions changing based on state -->
                                        <p class="text-xs leading-relaxed text-slate-500">
                                            <span v-if="overwriteMode" class="font-medium text-rose-700 block mb-0.5">
                                                Warning: This submission will overwrite existing data records mapped to this model.
                                            </span>
                                            Are you absolutely sure all active inputs and layers are correct?
                                        </p>
                                    </div>

                                    <!-- Structured Review Metadata Block (Border shifts colors based on warning state) -->
                                    <div 
                                        class="space-y-2 pt-3 border-t max-w-md transition-colors duration-200"
                                        :class="overwriteMode ? 'border-rose-200' : 'border-slate-100'"
                                    >
                                        <div class="flex text-xs items-baseline">
                                            <span class="w-20 font-bold tracking-wide uppercase text-[10px] text-slate-400 shrink-0">Model:</span>
                                            <span class="font-bold text-slate-900 break-all">{{ selectedModel }}</span>
                                        </div>
                                        <div class="flex text-xs items-baseline">
                                            <span class="w-20 font-bold tracking-wide uppercase text-[10px] text-slate-400 shrink-0">Patterns:</span>
                                            <span class="font-bold text-slate-900 break-words">
                                                {{ selectedPatterns.join(', ') || 'None selected' }}
                                            </span>
                                        </div>
                                        <div class="flex text-xs items-baseline">
                                            <span class="w-20 font-bold tracking-wide uppercase text-[10px] text-slate-400 shrink-0">Layers:</span>
                                            <span class="font-bold text-slate-900 break-words">
                                                {{ selectedLayers.join(', ') || 'None selected' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Hand: Action Buttons Alignment -->
                            <div class="flex items-center gap-2 w-full shrink-0 sm:w-auto sm:self-center sm:justify-end">
                                <button
                                    type="button"
                                    @click="showConfirmationRule = false, msgRuleAlreadyExists = false, overwriteMode = false"
                                    class="flex-1 sm:flex-none px-4 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl shadow-sm hover:bg-slate-50 hover:text-slate-900 transition focus:outline-none whitespace-nowrap"
                                >
                                    No, Cancel
                                </button>
                                <button
                                    type="button"
                                    @click="saveRuleToDatabase"
                                    class="flex-1 sm:flex-none px-5 py-2.5 text-xs font-semibold text-white bg-teal-600 rounded-xl shadow-sm hover:bg-teal-700 transition focus:outline-none active:scale-[0.98] whitespace-nowrap"
                                >
                                    Yes, Commit Changes
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </Modal>

        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
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
                router.visit('/'); // Redirect if not authenticated
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50)); // small delay
        }

        if (!state.isAuthenticated) {
            router.visit('/'); // Redirect if not authenticated
            return false; // Indicate not authenticated
        }

        console.warn("USER AUTHENTICATED!");
        console.warn("Name: ", state.user.firstName + " " + state.user.surname);
        console.warn("Access: ", state.user.access_type);

        return true; // Indicate authenticated
    } catch (error) {
        console.error('Error checking authentication:', error);
        router.visit('/'); // Redirect on error
        return false; // Indicate not authenticated
    }
};

const userManageLogging = async (logEvent) => {
    try{
        const responseUserLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'HT Graph',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userManageLogging post request failed: ',error);
    }
}


// General Variables ---------------------------------- General Variables

//Toggles
const showModalCreatePattern = ref(false);
const showModalCreateRule = ref(false);
const showConfirmation = ref(false);
const showConfirmationRule = ref(false);
const showModalGraph = ref(false);
const showModalEdit = ref(false);
const msgPatternAlreadyAdded = ref(false);
const msgLayerAlreadyAdded = ref(false);
const msgRuleAlreadyExists = ref(false);
const overwriteMode = ref(false);

//Toggles

const activeTab = ref('patterns');

const patternNo = ref(0);
const patternNoHours = ref(0);
const furnaceNo = ref([]);
const modelLists = ref([]);
const layerLists = ref(["1","2","3","4","5","6","7","8","9","9.5"]);
const layerListsExceptNinePointFive = ref(["1","2","3","4","5","6","7","8","9"]);
const selectedFurnace = ref();
const selectedModel = ref("");
const currentlySelectedLayer = ref("");
const selectedLayers = ref([]);
const selectedPatterns = ref([]);
const uploadedGraph = ref();
const uploadedGraphEdited = ref();
const encodedBy = ref();
const graphFileLists = ref([]);
const selectedGraph = ref();
const selectedPattern = ref();
const editId = ref(null);
const editFile = ref(null);
const editedGraphFile = ref(null);



const selectedFurnaceFilter = ref('');

// 1. Helper state for the dropdown selection
const currentSelectedPattern = ref("");

const patternLists = computed(() => {
    if (!graphFileLists.value || graphFileLists.value.length === 0) return [];
    
    // 1. Extract only the 'pattern_no' from each item
    const rawPatterns = graphFileLists.value.map(item => item.pattern_no);
    
    // 2. Filter out null/undefined and keep unique values only
    const uniquePatterns = [...new Set(rawPatterns)].filter(val => val !== null && val !== undefined);
    
    // 3. Optional: Sort them numerically/alphabetically
    return uniquePatterns.sort((a, b) => a - b);
});

// Utility to sort string numbers in true ascending order
const sortLayersAscending = (array) => {
    return array.sort((a, b) => parseFloat(a) - parseFloat(b));
};

const addPatternToArray = () => {
    const value = currentSelectedPattern.value;
    if (value && !selectedPatterns.value.includes(value)) {
        msgPatternAlreadyAdded.value = false;
        selectedPatterns.value.push(value);
        currentSelectedPattern.value = "";
    }else{
        msgPatternAlreadyAdded.value = true;
    }
};

// 3. Action to push dropdown value to the array (avoiding duplicates)
const addLayerToArray = () => {
    const value = currentlySelectedLayer.value;
    if (value && !selectedLayers.value.includes(value)) {
        msgLayerAlreadyAdded.value = false;
        selectedLayers.value.push(value);
        
        // Real-time sort after insertion
        sortLayersAscending(selectedLayers.value);
        
        currentlySelectedLayer.value = "";
    } else {
        msgLayerAlreadyAdded.value = true;
    }
};

const addAllLayerToArray = () => {
    if (!layerLists.value || layerLists.value.length === 0) return;

    const newLayers = layerLists.value.filter(
        (layer) => !selectedLayers.value.includes(layer)
    );

    selectedLayers.value.push(...newLayers);
    
    // Real-time sort after bulk insertion
    sortLayersAscending(selectedLayers.value);
};

const addAllLayerExceptNinePointFiveToArray = () => {
    if (!layerListsExceptNinePointFive.value || layerListsExceptNinePointFive.value.length === 0) return;

    const newLayers = layerListsExceptNinePointFive.value.filter(
        (layer) => !selectedLayers.value.includes(layer)
    );

    selectedLayers.value.push(...newLayers);
    
    // Real-time sort after bulk insertion
    sortLayersAscending(selectedLayers.value);
};

const removePattern = (index) => {
    selectedPatterns.value.splice(index, 1);
    msgPatternAlreadyAdded.value = false;
};

const removeLayer = (index) => {
    selectedLayers.value.splice(index, 1);
    msgLayerAlreadyAdded.value = false;
};

// General Variables ---------------------------------- General Variables

const editPattern = (pattern) => {
    editId.value = pattern.id;
    patternNo.value = pattern.pattern_no;
    patternNoHours.value = pattern.pattern_no_hours;
    selectedFurnace.value = pattern.furnace_no;
    uploadedGraph.value = pattern.url;
    showModalEdit.value = true;
    console.log('Editing pattern id:', editId.value);
}

const updatePattern = async () => {
    if (!editId.value) return;

    const user = state.user.firstName + " " + state.user.surname;

    const formData = new FormData();
    formData.append('pattern_no', patternNo.value || '');
    formData.append('pattern_no_hours', patternNoHours.value || '');
    formData.append('furnace_no', selectedFurnace.value || '');
    formData.append('encoded_by', user || '');

    if (editedGraphFile.value) {
        formData.append('graph', editedGraphFile.value);
    }

    // Spoof method for Laravel
    formData.append('_method', 'PATCH');

    try {
        const updateResponse = await axios.post(`/api/patterns/${editId.value}/update`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        toast.success('Pattern updated successfully');
        showModalEdit.value = false;
        console.log("Success: ", updateResponse.data);
        await userManageLogging('edited Graph Pattern no: '+ patternNo.value +' Encoded by: ' + encodedBy.value + ' successfully.');
    } catch (error) {
        toast.error('Failed to update pattern');
        console.error(error.response?.data || error);
    } finally {
        await getAllPatterns();
        editedGraphFile.value = null;
        uploadedGraphEdited.value = null;
    }
};

const handleEditGraph = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type.');
        event.target.value = '';
        uploadedGraphEdited.value = null;
        editedGraphFile.value = null;
        return;
    }

    editedGraphFile.value = file; // store file properly
    uploadedGraphEdited.value = URL.createObjectURL(file); // preview
}

const submitFormPattern = () => {
    if(selectedFurnace.value == null || patternNo.value == null || patternNo.value == 0 || uploadedGraph.value == null){
        toast.warning('Please fill up all the fields.')
        uploadedGraph.value = null;
        return;
    }
    showConfirmation.value = true;
}

const submitFormRule = () => {
    if(!selectedModel.value || selectedLayers.value.length == 0 || selectedPatterns.value.length == 0){
        toast.warning('Please fill up all the fields.')
        console.log("Model: ", selectedModel.value);
        console.log("Layers: ", selectedLayers.value);
        console.log("Patterns: ", selectedPatterns.value);
        return;
    }
    showConfirmationRule.value = true;
    msgRuleAlreadyExists.value = false;
}

let graph = null;
const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
const uploadGraph = (event) => {
    const file = event.target.files[0];
    if (!file){
        return;
    }
    if(!allowedTypes.includes(file.type)) {
        toast.error('Invalid file type. Please upload a PNG, JPG or JPEG image.');
        event.target.value = ''; // Clear the input if the file type is not allowed
        uploadedGraph.value = null; // Reset preview if invalid file
        return;
    }
    uploadedGraph.value = URL.createObjectURL(file); // Create a preview URL
    graph = file;
    console.log('Pattern Graph File selected:', graph.name);
}

const uploadGraphFinal = async() => {
    // Prepare form data
    const formData = new FormData();
    formData.append('graph', graph);
    formData.append('pattern_no', patternNo.value);
    formData.append('furnace_no', selectedFurnace.value);

    try {
        const response = await axios.post('/api/ht-graph-patterns/upload-graph', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        });
        toast.success(response.data.message);
        console.log('Upload response:', response.data);
    } catch (error) {
        toast.error('Upload failed.');
        console.error(error.response?.data || error);
    }
}

const saveToDatabase = async () => {
    try {

        const user = state.user.firstName + " " + state.user.surname;

        const response = await axios.post('/api/ht-graph-patterns', {
            pattern_no: patternNo.value,
            pattern_no_hours: patternNoHours.value,
            furnace_no: selectedFurnace.value,
            encoded_by: user
        });

        await uploadGraphFinal();

        toast.success('Graph Pattern created successfully!');
        await userManageLogging('created Graph Pattern no: '+ patternNo.value +' Encoded by: ' + encodedBy.value + ' successfully.');
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors || {};

            if (errors.pattern_no || errors.furnace_no) {
                toast.error('This Pattern No + Furnace No combination already exists.');
            } else {
                toast.error('Validation failed. Check input values.');
            }

            console.log('Validation errors:', errors);
        } else if (error.response?.status === 404) {
            toast.error('Pattern + Furnace combination not found.');
        } else {
            toast.error('Failed to create Graph Pattern.');
            console.error(error);
        }
    } finally {
        await getAllPatterns();
        showModalCreatePattern.value = false;
        selectedFurnace.value = '';
        encodedBy.value = '';
        patternNo.value = 0;
        patternNoHours.value = 0;
        uploadedGraph.value = null;
        showConfirmation.value = false;
    }
};

const saveRuleToDatabase = async () => {
    try{
        if(!overwriteMode.value){
            const firstResponse = await axios.get('/api/inspection/check-existing', {
                params: {
                    model: selectedModel.value
                }
            });

            if(firstResponse.data.rule_existing){
                msgRuleAlreadyExists.value = true;
                showConfirmationRule.value = false;
                return;
            }else{
                msgRuleAlreadyExists.value = false;
            }
        }

        const user = state.user.firstName + " " + state.user.surname;

        console.log([
            "Model: ", selectedModel.value,
            "Pattern List: ", selectedPatterns.value,
            "Layer List: ", selectedLayers.value,
            "Encoded by: ", user,
        ]);
        
        const response = await axios.patch('/api/inspection/model-rules',{
            model: selectedModel.value,
            pattern_list: selectedPatterns.value,
            layer_list: selectedLayers.value,
            rule_encoded_by: user,
        });

        if(response.data.success){
            console.log("save rule response: ", response.data);
            toast.success('Model Rule created successfully!');

            if(overwriteMode.value){
                await userManageLogging('overwritten a new rule with model: '+ selectedModel.value +' Encoded by: ' +  user + ' successfully.');
            }else{
                await userManageLogging('created a new rule with model: '+ selectedModel.value +' Encoded by: ' +  user + ' successfully.');
            }

            showModalCreatePattern.value = false;
            selectedFurnace.value = '';
            encodedBy.value = '';
            patternNo.value = 0;
            patternNoHours.value = 0;
            uploadedGraph.value = null;
            showConfirmation.value = false;
            selectedModel.value = "";
            selectedPatterns.value = [];
            selectedLayers.value = [];
            showModalCreateRule.value = false;
            showConfirmationRule.value = false;
            overwriteMode.value = false;

            await getAllPatterns();
            await fetchModelRulesTable();
        }else{
            toast.error("Patch failed", response.data.message);
            showModalCreatePattern.value = false;
            selectedFurnace.value = '';
            encodedBy.value = '';
            patternNo.value = 0;
            patternNoHours.value = 0;
            uploadedGraph.value = null;
            showConfirmation.value = false;
            selectedModel.value = "";
            selectedPatterns.value = [];
            selectedLayers.value = [];
            showModalCreateRule.value = false;
            showConfirmationRule.value = false;
            overwriteMode.value = false;

            await getAllPatterns();
            await fetchModelRulesTable();
        }

        
    }catch(error){
        console.error('Failed to save rule to database', error);
    }
}

const searchQuery = ref('');
const currentPage = ref(1);

const paginationMeta = ref({
    total: 0,
    per_page: 10,
    last_page: 1,
    from: 0,
    to: 0
});

const modelRules = ref([]);
const searchQueryRules = ref('');
const currentRulesPage = ref(1);
const rulePaginationMeta = ref({});

const filteredPatterns = computed(() => {
  const q = String(searchQuery.value || '').toLowerCase(); // <-- coerce safely
  if (!q) return graphFileLists.value;

  return graphFileLists.value.filter(
    p =>
      String(p.pattern_no || '').includes(q) ||
      String(p.encoded_by || '').toLowerCase().includes(q)
  );
});

const getAllPatterns = async (page = 1) => {
    try {
        const response = await axios.get('/api/htgraph-patterns/list', {
            params: {
                page: page,
                search: searchQuery.value,
                furnace: selectedFurnaceFilter.value
            }
        });

        // Laravel pagination results nest data arrays inside response.data.data
        const backendResponse = response.data;
        
        graphFileLists.value = Array.isArray(backendResponse.data) 
            ? backendResponse.data 
            : Object.values(backendResponse.data || {});

        // Store pagination metrics safely
        currentPage.value = backendResponse.current_page;
        paginationMeta.value = {
            total: backendResponse.total || 0,
            per_page: backendResponse.per_page || 15,
            last_page: backendResponse.last_page || 1,
            from: backendResponse.from || 0,
            to: backendResponse.to || 0
        };

        console.log('Normalized Page Data:', graphFileLists.value);
    } catch (error) {
        console.error('Failed to fetch graphs', error);
    }
};

const fetchModelRulesTable = async (page = 1) => {
    try {
        const response = await axios.get('/api/inspection/rule-table-list', {
            params: {
                page: page,
                search: searchQueryRules.value
            }
        });

        if (response.data && response.data.success) {
            // Arrays inside Laravel JSON casting arrive fully formatted as native arrays
            modelRules.value = response.data.data; 
            
            // Map the unified structural object metrics
            currentRulesPage.value = response.data.pagination.current_page;
            rulePaginationMeta.value = response.data.pagination;
        }
    } catch (error) {
        console.error("Failed to load rules dataset into table", error);
    }
};

const getModelLists = async () => {
    try {
        const response = await axios.get("/api/inspection/all-model-names");

        modelLists.value = response.data;
        console.log('Model lists: ',modelLists.value);
    } catch (error) {
        console.error("Error fetching model names", error);
        toast.error("Failed to get the model names.");
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getModelLists",
            "Failed to get the model names",
        );
    }
};

// Auto-fetch data if user switches pages
watch(currentPage, (newPage) => {
    getAllPatterns(newPage);
});

// Auto-reset to page 1 and fetch data when user searches
watch([searchQuery, selectedFurnaceFilter], () => {
    currentPage.value = 1;
    getAllPatterns(1);
});

const getFurnaceLists = async() => {
    try{
        const response = await axios.get('/api/furnace-data/');
        const furnaceData = response.data;
        furnaceNo.value = furnaceData.map(item => item.furnace_name);
        console.log("Furnace Lists: ", furnaceNo.value);
    }catch(error){
        console.error('Failed to fetch furnace data lists: ',error);
        toast.error('Furnace Data List error.');
    }
}

onMounted(async() => {
    await checkAuthentication();
    await getAllPatterns();
    await getFurnaceLists();
    await getModelLists();
    await fetchModelRulesTable();
});


</script>
