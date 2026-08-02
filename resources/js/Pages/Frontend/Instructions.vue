<template>
  <Frontend>
    <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">

        <!-- MINIATURE LOGS PANEL (Compact & Streamlined) -->
        <div v-if="showMiniLogsPanel" class="w-full max-w-md p-4 mx-auto mt-4 space-y-3 bg-white rounded-xl shadow-md shadow-cyan-900/5 border border-cyan-100/60">
            <div class="flex items-center justify-between pb-2 border-b border-cyan-50">
                <div>
                    <h3 class="text-xs font-black tracking-wider text-gray-900 uppercase">Recent Activity</h3>
                    <p class="text-[10px] font-medium text-cyan-700/80">Latest Additions & Deletions</p>
                </div>
                <button
                    @click="openFullLogsModal"
                    class="text-[11px] font-bold text-cyan-600 hover:text-cyan-800 transition"
                >
                    View All →
                </button>
            </div>

            <!-- Mini Feed List -->
            <div class="space-y-2">
                <div
                    v-for="log in miniLogs"
                    :key="log.id"
                    class="flex items-center justify-between px-3 py-2 rounded-lg bg-cyan-50/30 border border-cyan-100/50 text-xs transition hover:bg-cyan-50/60"
                >
                    <div class="flex items-center gap-2 overflow-hidden pr-2">
                        <!-- Action Badge (Abbreviated for compactness) -->
                        <span
                            :class="[
                                'px-1.5 py-0.5 text-[9px] font-black uppercase rounded border shrink-0',
                                isAdded(log.event) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'
                            ]"
                        >
                            {{ isAdded(log.event) ? 'Add' : 'Del' }}
                        </span>

                        <!-- User & Trimmed Event Single Line Truncated -->
                        <div class="truncate text-[11px]">
                            <span class="font-bold text-gray-900">{{ log.user }}</span>
                            <span class="text-gray-600 ml-1 font-normal">{{ trimEvent(log.event) }}</span>
                        </div>
                    </div>

                    <span class="text-[10px] font-semibold text-gray-400 whitespace-nowrap shrink-0 pl-2">
                        {{ formatShortDate(log.created_at) }}
                    </span>
                </div>
            </div>
        </div>


        <!-- FULL PAGINATED LOGS PANEL / MODAL -->
        <div v-if="showFullLogsPanel" class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-6 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60">

            <!-- Header & Metrics -->
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-cyan-50 gap-4">
                <div>
                    <h2 class="text-xl font-extrabold tracking-tight text-gray-900">SYSTEM AUDIT LOGS</h2>
                    <p class="text-xs font-medium text-cyan-700/80 mt-0.5">User action event history</p>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-cyan-50/40 p-4 rounded-xl border border-cyan-100">
                <div>
                    <label class="block mb-1 text-xs font-semibold text-cyan-900/80">Search User or Keyword</label>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Filter records..."
                        class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-lg shadow-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 transition"
                    />
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-cyan-900/80">From Date</label>
                    <input v-model="startDate" type="date" class="w-full px-3 py-2 text-sm bg-white border border-cyan-200 rounded-lg shadow-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 transition" />
                </div>
                <div>
                    <label class="block mb-1 text-xs font-semibold text-cyan-900/80">To Date</label>
                    <div class="flex gap-2">
                        <input v-model="endDate" type="date" class="w-full px-3 py-2 text-sm bg-white border border-cyan-200 rounded-lg shadow-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 transition" />
                        <button @click="resetFilters" class="px-3 py-2 text-xs font-bold text-cyan-700 bg-white border border-cyan-200 rounded-lg hover:bg-cyan-50 transition shadow-sm">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                        <tr>
                            <th class="px-6 py-4">Timestamp</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Action Type</th>
                            <th class="px-6 py-4">Event Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyan-50">
                        <tr v-for="log in paginatedLogs" :key="log.id" class="transition-colors hover:bg-cyan-50/40 text-xs">
                            <td class="px-6 py-4 text-gray-500 font-semibold whitespace-nowrap">{{ formatDateTime(log.created_at) }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">{{ log.user }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="['inline-flex items-center px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-full border shadow-sm', isAdded(log.event) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200']">
                                    {{ isAdded(log.event) ? '➕ Added' : '🗑️ Deleted' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ log.event }}</td>
                        </tr>
                        <tr v-if="paginatedLogs.length === 0">
                            <td colspan="4" class="px-6 py-12 text-center text-cyan-900/40 text-sm font-medium">No logs matched your criteria.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Laravel Controller-Driven Pagination Controls Bar -->
            <div class="flex items-center justify-between pt-2">
                <span class="text-xs font-semibold text-cyan-900/70">
                    Showing page {{ paginationMeta.current_page }} of {{ paginationMeta.last_page || 1 }} (Total: {{ paginationMeta.total || 0 }} entries)
                </span>

                <div class="flex items-center gap-1.5">
                    <button
                        @click="fetchLogs(paginationMeta.current_page - 1)"
                        :disabled="!paginationMeta.prev_page_url"
                        class="px-3 py-1.5 text-xs font-bold text-cyan-800 bg-white border border-cyan-200 rounded-lg hover:bg-cyan-50 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition"
                    >
                        Previous
                    </button>
                    <button
                        @click="fetchLogs(paginationMeta.current_page + 1)"
                        :disabled="!paginationMeta.next_page_url"
                        class="px-3 py-1.5 text-xs font-bold text-cyan-800 bg-white border border-cyan-200 rounded-lg hover:bg-cyan-50 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showSelectionPanel" class="flex flex-col items-center justify-start min-h-screen pt-12 pb-16 px-6 mx-auto space-y-10 bg-gradient-to-b from-cyan-50/50 via-teal-50/20 to-gray-100/50">

            <!-- Header -->
            <div class="text-center space-y-2">
                <h1 class="inline-flex items-center px-8 py-3 text-2xl font-black tracking-wider text-cyan-950 uppercase bg-gradient-to-r from-cyan-100/80 to-teal-100/80 border border-cyan-200 shadow-sm sm:text-3xl rounded-2xl backdrop-blur-sm">
                    SPECIAL INSTRUCTIONS / JUDGEMENTS
                </h1>
                <p class="text-xs font-medium text-cyan-800/70">Select a model panel to manage or inspect configuration details</p>
            </div>

            <!-- Grid -->
            <div class="grid w-full max-w-6xl grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <!-- CARD -->
                <div
                    v-for="(item, index) in models"
                    :key="index"
                    class="relative group"
                >

                    <!-- Main Button -->
                    <button
                        @click="openPanel(item.panel)"
                        class="w-full p-7 font-bold text-left text-cyan-950 transition-all duration-300 border border-cyan-100/80 shadow-md shadow-cyan-900/5 bg-white rounded-2xl hover:bg-gradient-to-br hover:from-cyan-50/60 hover:to-teal-50/60 hover:border-cyan-300 hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/40 flex items-center justify-between"
                    >
                        <span>{{ item.name }}</span>
                        <svg class="w-5 h-5 text-cyan-400 group-hover:text-cyan-600 transition-colors transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Preview Trigger -->
                    <button
                        @click.stop="openImage(item.image)"
                        class="absolute z-20 flex items-center justify-center w-9 h-9 transition-all bg-white/90 backdrop-blur-md border rounded-full shadow-md top-3.5 right-3.5 border-cyan-100 text-cyan-700 hover:bg-cyan-600 hover:text-white group/preview hover:scale-110"
                    >
                        <!-- Pulse -->
                        <span class="absolute inline-flex w-full h-full rounded-full bg-cyan-400 opacity-30 animate-ping group-hover/preview:hidden"></span>

                        <!-- Icon -->
                        <svg class="relative w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8v12H4z" />
                        </svg>

                        <!-- Preview Bubble (Smart positioned to prevent clipping off screen) -->
                        <div class="absolute z-30 hidden group-hover:block left-full ml-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <div class="bg-white p-2.5 rounded-xl shadow-2xl border border-cyan-100 w-52 backdrop-blur-md bg-white/95">
                                <img :src="item.image" class="w-full h-auto object-contain rounded-lg shadow-sm" />
                            </div>
                            <div class="absolute top-1/2 -left-1 w-2 h-2 bg-white rotate-45 border-l border-b border-cyan-100 -translate-y-1/2"></div>
                        </div>
                    </button>

                </div>
            </div>
        </div>

<!-- IMAGE MODAL -->
<div
v-if="showImageModal"
class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md transition-opacity"
@click="closeImage"
>
<div class="relative max-w-5xl p-5 bg-white rounded-2xl shadow-2xl border border-cyan-100 transform transition-all" @click.stop>

    <!-- Close Button -->
    <button
        @click="closeImage"
        class="absolute z-10 flex items-center justify-center w-8 h-8 text-gray-500 bg-gray-100 rounded-full top-3 right-3 hover:bg-rose-100 hover:text-rose-600 transition-colors"
    >
        ✕
    </button>

    <!-- Image -->
    <img :src="activeImage" class="max-h-[82vh] w-auto rounded-xl shadow-inner object-contain mx-auto block" />
</div>
</div>

    <button
        v-if="!showSelectionPanel"
        @click="backButton"
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-800 transition-all border border-blue-200 rounded-lg shadow-sm bg-blue-50 hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300"
    >
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
        Back
    </button>

    <!-- VT MODELS Table Section -->
    <div
        v-if="showVTPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">VT MODELS</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage standard VT tracing specifications & inventory</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ vtModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="vt in vtModels"
                        :key="vt.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ vt.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ vt.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(vt.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="vt_confirmDelete(vt)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!vtModels || vtModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No VT models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section (Only Model Name input needed; Encoded By is auto-injected in script) -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New VT Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="vt_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="vt_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save VT Model
            </button>
        </div>
    </div>

    <!-- CPK IHC MODELS Table Section -->
    <div
        v-if="showCPKIHCPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">CPK IHC MODELS</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage standard CPK IHC specifications & records</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ cpkihcModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="cpkihc in cpkihcModels"
                        :key="cpkihc.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ cpkihc.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ cpkihc.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(cpkihc.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="cpkihc_confirmDelete(cpkihc)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!cpkihcModels || cpkihcModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No CPK IHC models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New CPK IHC Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="cpkihc_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="cpkihc_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save CPK IHC Model
            </button>
        </div>
    </div>



    <!-- GX MODELS Table Section -->
    <div
        v-if="showGXPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">GX MODELS</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage standard GX model specifications & records</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ gxModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="gx in gxModels"
                        :key="gx.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ gx.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ gx.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(gx.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="gx_confirmDelete(gx)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!gxModels || gxModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No GX models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section (Manual input for Encoded By removed) -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New GX Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="gx_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="gx_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save GX Model
            </button>
        </div>
    </div>

    <!-- 1x1x1 MODELS (With corner) Table Section -->
    <div
        v-if="showTTMWCPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">1x1x1 MODELS (With corner)</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage 1x1x1 corner model specifications & records</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ ttmwcModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="ttmwc in ttmwcModels"
                        :key="ttmwc.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ ttmwc.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ ttmwc.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(ttmwc.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="ttmwc_confirmDelete(ttmwc)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!ttmwcModels || ttmwcModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No 1x1x1 corner models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="ttmwc_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="ttmwc_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save Model
            </button>
        </div>
    </div>

    <!-- 1x1x1 MODELS (No corner) Table Section -->
    <div
        v-if="showTTMNCPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">1x1x1 MODELS (No corner)</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage 1x1x1 no-corner model specifications & records</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ ttmncModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="ttmnc in ttmncModels"
                        :key="ttmnc.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ ttmnc.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ ttmnc.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(ttmnc.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="ttmnc_confirmDelete(ttmnc)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!ttmncModels || ttmncModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No 1x1x1 no-corner models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="ttmnc_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="ttmnc_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save Model
            </button>
        </div>
    </div>

    <!-- BH MODELS Table Section -->
    <div
        v-if="showBHPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">BH MODELS</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage standard BH model specifications & records</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ bhModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="bh in bhModels"
                        :key="bh.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ bh.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ bh.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(bh.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="bh_confirmDelete(bh)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!bhModels || bhModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No BH models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="bh_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="bh_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save Model
            </button>
        </div>
    </div>

    <!-- CPK BR MODELS Table Section -->
    <div
        v-if="showCPKBRPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">CPK BR MODELS</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage standard CPK BR model specifications & records</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ cpkbrModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="cpkbr in cpkbrModels"
                        :key="cpkbr.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ cpkbr.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ cpkbr.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(cpkbr.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="cpkbr_confirmDelete(cpkbr)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!cpkbrModels || cpkbrModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No CPK BR models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="cpkbr_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="cpkbr_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save Model
            </button>
        </div>
    </div>

    <!-- HIS MODELS Table Section -->
    <div
        v-if="showHISPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Header & Counter Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">HIS GS MODELS</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Manage standard HIS GS model specifications & records</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ hisModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Encoded By</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="his in hisModels"
                        :key="his.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ his.model_name }}
                        </td>

                        <!-- Encoded By -->
                        <td class="px-6 py-4 text-gray-700 font-medium text-xs">
                            {{ his.encoded_by }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(his.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="his_confirmDelete(his)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!hisModels || hisModels.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No HIS GS models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Section -->
        <div class="pt-6 border-t border-cyan-50 space-y-4">
            <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New Model</h3>

            <div class="grid grid-cols-1 max-w-md">
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                    <select
                        v-model="his_newRecord.model_name"
                        class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                    >
                        <!-- Placeholder Option -->
                        <option value="" disabled selected>Select model...</option>

                        <!-- Dynamic Options -->
                        <option v-for="item in model_names" :key="item" :value="item">
                            {{ item }}
                        </option>
                    </select>
                </div>
            </div>

            <button
                @click="his_addRecord"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Save Model
            </button>
        </div>
    </div>


    <!-- ROB MODELS Table Section -->
    <div
        v-if="showROBPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Table Header & Counter -->
        <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">ROB BH TRACER MODELS</h2>
                <p class="text-xs font-medium text-cyan-700/80 mt-0.5">Standard Specifications & Baseline Rules</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                {{ robModels?.length || 0 }} Models Recorded
            </span>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Model Name</th>
                        <th scope="col" class="px-6 py-4">Date Created</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <tr
                        v-for="rob in robModels"
                        :key="rob.id"
                        class="transition-colors hover:bg-cyan-50/40 group"
                    >
                        <!-- Model Name -->
                        <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                            {{ rob.model_name }}
                        </td>

                        <!-- Date Created -->
                        <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                            {{ new Date(rob.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right space-x-2">
                            <!-- View Details Button -->
                            <button
                                @click="openDynamicDetailsModal(rob, 'ROB Model')"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-cyan-700 bg-cyan-50 border border-cyan-200 rounded-lg shadow-sm hover:bg-cyan-100 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition"
                            >
                                <svg class="w-3.5 h-3.5 mr-1.5 text-cyan-500 group-hover:text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Details
                            </button>

                            <!-- Delete Button -->
                            <button
                                @click="rob_confirmDelete(rob)"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                            >
                                <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="!robModels || robModels.length === 0">
                        <td colspan="3" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                            No ROB models recorded yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Model Card Section -->
        <div class="pt-6 mt-8 border-t border-cyan-50">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New Model</h3>
                    <p class="text-xs text-cyan-700/80 mt-0.5">Configure new model parameters and baseline specification limits.</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Section 1: Basic Information -->
                <div>
                    <h4 class="mb-2 text-xs font-semibold uppercase tracking-wider text-cyan-900/60">Basic Info</h4>
                    <div class="grid grid-cols-1 max-w-md">
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                            <select
                                v-model="rob_newRecord.model_name"
                                class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                            >
                                <!-- Placeholder Option -->
                                <option value="" disabled selected>Select model...</option>

                                <!-- Dynamic Options -->
                                <option v-for="item in model_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Specifications -->
                <div class="p-5 rounded-2xl bg-cyan-50/40 border border-cyan-100">
                    <h4 class="mb-3 text-xs font-semibold uppercase tracking-wider text-cyan-900/70">Specifications Baseline</h4>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Br RT Max</label>
                            <input
                                v-model.number="rob_newRecord.br_rt_standardmax"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Br RT Min</label>
                            <input
                                v-model.number="rob_newRecord.br_rt_standardmin"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Br VT Standard</label>
                            <input
                                v-model.number="rob_newRecord.br_vt_standard"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">HD5 Standard</label>
                            <input
                                v-model.number="rob_newRecord.hd5_standard"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">JD5 Standard</label>
                            <input
                                v-model.number="rob_newRecord.jd5_standard"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end pt-2">
                    <button
                        @click="rob_addRecord"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
                    >
                        <svg class="w-4 h-4 mr-2 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Save Model Record
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ROB MODELS ALL JUDGEMENTS Table Section -->
    <div
        v-if="showROBAJPanel"
        class="w-full max-w-5xl p-8 mx-auto mt-10 space-y-8 bg-white rounded-2xl shadow-xl shadow-cyan-900/5 border border-cyan-100/60"
    >
        <!-- Table Section -->
        <div class="space-y-4">
            <!-- Header with total count badge -->
            <div class="flex items-center justify-between pb-4 border-b border-cyan-50">
                <div>
                    <h2 class="text-xl font-extrabold tracking-tight text-gray-900">ROB BH TRACER MODELS</h2>
                    <p class="text-xs font-medium text-cyan-700/80 mt-0.5">All Judgements Specifications & Baseline Rules</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200/60 rounded-full shadow-sm">
                    {{ robAjModels?.length || 0 }} Models Recorded
                </span>
            </div>

            <!-- Table Container -->
            <div class="overflow-hidden border border-cyan-100 rounded-xl shadow-sm bg-white">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs font-bold text-cyan-900 uppercase bg-gradient-to-r from-cyan-50/80 to-teal-50/80 border-b border-cyan-100">
                        <tr>
                            <th scope="col" class="px-6 py-4">Model Name</th>
                            <th scope="col" class="px-6 py-4">Date Created</th>
                            <th scope="col" class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyan-50">
                        <tr
                            v-for="rob in robAjModels"
                            :key="rob.id"
                            class="transition-colors hover:bg-cyan-50/40 group"
                        >
                            <!-- Model Name -->
                            <td class="px-6 py-4 font-bold text-gray-900 group-hover:text-cyan-800 transition-colors">
                                {{ rob.model_name }}
                            </td>

                            <!-- Date Created -->
                            <td class="px-6 py-4 text-gray-500 text-xs font-semibold">
                                {{ new Date(rob.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-2">
                                <!-- View Details Button -->
                                <button
                                    @click="openDynamicDetailsModal(rob, 'ROB AJ Model')"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-cyan-700 bg-cyan-50 border border-cyan-200 rounded-lg shadow-sm hover:bg-cyan-100 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-cyan-500 group-hover:text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </button>

                                <!-- Delete Button -->
                                <button
                                    @click="robaj_confirmDelete(rob)"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition shadow-sm"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-if="!robAjModels || robAjModels.length === 0">
                            <td colspan="3" class="px-6 py-10 text-center text-cyan-900/40 text-sm font-medium">
                                No ROB models recorded yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add New Model Card Section -->
        <div class="pt-6 mt-8 border-t border-cyan-50">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold tracking-wider uppercase text-cyan-900">Add New Model</h3>
                    <p class="text-xs text-cyan-700/80 mt-0.5">Configure new model parameters and baseline specification limits.</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Section 1: Basic Information -->
                <div>
                    <h4 class="mb-2 text-xs font-semibold uppercase tracking-wider text-cyan-900/60">Basic Info</h4>
                    <div class="grid grid-cols-1 max-w-md">
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Model Name</label>
                            <select
                                v-model="robaj_newRecord.model_name"
                                class="w-full px-3.5 py-2.5 text-sm border border-cyan-200 rounded-xl bg-cyan-50/30 text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition shadow-sm"
                            >
                                <!-- Placeholder Option -->
                                <option value="" disabled selected>Select model...</option>

                                <!-- Dynamic Options -->
                                <option v-for="item in model_names" :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Specifications -->
                <div class="p-5 rounded-2xl bg-cyan-50/40 border border-cyan-100">
                    <h4 class="mb-3 text-xs font-semibold uppercase tracking-wider text-cyan-900/70">Specifications Baseline</h4>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Br RT Max</label>
                            <input
                                v-model.number="robaj_newRecord.br_rt_standardmax"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Br RT Min</label>
                            <input
                                v-model.number="robaj_newRecord.br_rt_standardmin"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">Br VT Standard</label>
                            <input
                                v-model.number="robaj_newRecord.br_vt_standard"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">HD5 Standard</label>
                            <input
                                v-model.number="robaj_newRecord.hd5_standard"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-cyan-900/80">JD5 Standard</label>
                            <input
                                v-model.number="robaj_newRecord.jd5_standard"
                                type="number"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 text-sm bg-white border border-cyan-200 rounded-xl shadow-sm text-gray-900 placeholder-cyan-900/30 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500 transition"
                            />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end pt-2">
                    <button
                        @click="robaj_addRecord"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-600 to-teal-600 rounded-xl hover:from-cyan-700 hover:to-teal-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition shadow-md shadow-cyan-600/20"
                    >
                        <svg class="w-4 h-4 mr-2 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Save Model Record
                    </button>
                </div>
            </div>
        </div>
    </div>


        <div
            v-if="showAddValidationModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-xl border border-gray-100">
                <div class="flex items-center space-x-3 text-amber-600 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-base font-bold text-gray-900">Incomplete Form</h3>
                </div>

                <p class="text-sm text-gray-600">
                Please fill in all model specification baseline fields before submitting.
                </p>

                <div class="mt-5 flex justify-end">
                <button
                    @click="showAddValidationModal = false"
                    class="px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition"
                >
                    Got it
                </button>
                </div>
            </div>
        </div>

        <div
            v-if="showAddConfirmModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-xl border border-gray-100 space-y-4">
                <div>
                <h3 class="text-lg font-bold text-gray-900">Confirm {{ activeModalType }} Creation</h3>
                <p class="text-xs text-gray-500">Please review the details below before saving to the database.</p>
                </div>

                <!-- Details Box -->
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200/80 space-y-2 text-sm">
                <div class="flex justify-between border-b pb-2 border-gray-200">
                    <span class="text-gray-500 font-medium">Model Name:</span>
                    <span class="font-semibold text-gray-900">{{ activePendingRecord?.model_name }}</span>
                </div>

                <div class="flex justify-between border-b pb-2 border-gray-200">
                    <span class="text-gray-500 font-medium">Encoded By:</span>
                    <span class="font-medium text-gray-800">{{ activePendingRecord?.encoded_by }}</span>
                </div>

                <!-- Extra AJ Fields (Renders automatically if present in object) -->
                <div v-if="activePendingRecord?.br_rt_standardmax !== undefined" class="grid grid-cols-2 gap-2 pt-2 text-xs">
                    <div>
                    <span class="text-gray-500 block">Br RT Max:</span>
                    <span class="font-semibold text-gray-800">{{ activePendingRecord.br_rt_standardmax }}</span>
                    </div>
                    <div>
                    <span class="text-gray-500 block">Br RT Min:</span>
                    <span class="font-semibold text-gray-800">{{ activePendingRecord.br_rt_standardmin }}</span>
                    </div>
                    <div>
                    <span class="text-gray-500 block">Br VT Standard:</span>
                    <span class="font-semibold text-gray-800">{{ activePendingRecord.br_vt_standard }}</span>
                    </div>
                    <div>
                    <span class="text-gray-500 block">HD5 Standard:</span>
                    <span class="font-semibold text-gray-800">{{ activePendingRecord.hd5_standard }}</span>
                    </div>
                    <div class="col-span-2">
                    <span class="text-gray-500 block">JD5 Standard:</span>
                    <span class="font-semibold text-gray-800">{{ activePendingRecord.jd5_standard }}</span>
                    </div>
                </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-2">
                <button
                    @click="showAddConfirmModal = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition"
                >
                    Cancel
                </button>
                <button
                    @click="activeSaveCallback && activeSaveCallback()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"
                >
                    Confirm & Save
                </button>
                </div>
            </div>
        </div>

        <!-- View Details Modal -->
        <div
            v-if="showDetailsModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div class="w-full max-w-lg p-6 bg-white rounded-xl shadow-xl border border-gray-100 space-y-5">
                <!-- Header -->
                <div class="flex items-center justify-between border-b pb-3 border-gray-100">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-blue-600">{{ activeModalType }} Specification</span>
                        <h3 class="text-xl font-bold text-gray-900">{{ activeDetailsRecord?.model_name }}</h3>
                    </div>
                    <button
                        @click="showDetailsModal = false; activeDetailsRecord = null;"
                        class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Details Grid -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 rounded-lg text-xs">
                        <div>
                            <span class="text-gray-400 block font-medium">Encoded By</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ activeDetailsRecord?.encoded_by || 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block font-medium">Date Created</span>
                            <span class="font-semibold text-gray-800 text-sm">
                                {{ activeDetailsRecord?.created_at ? new Date(activeDetailsRecord.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Standard Specifications List (Only renders if AJ spec fields exist) -->
                    <div v-if="activeDetailsRecord?.br_rt_standardmax !== undefined">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Technical Baseline Limits</h4>
                        <div class="divide-y divide-gray-100 border rounded-lg border-gray-200/80 bg-white">
                            <div class="flex justify-between items-center px-4 py-2.5 text-sm">
                                <span class="text-gray-600 font-medium">Br RT Standard Max</span>
                                <span class="font-bold text-gray-900">{{ activeDetailsRecord?.br_rt_standardmax ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2.5 text-sm">
                                <span class="text-gray-600 font-medium">Br RT Standard Min</span>
                                <span class="font-bold text-gray-900">{{ activeDetailsRecord?.br_rt_standardmin ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2.5 text-sm">
                                <span class="text-gray-600 font-medium">Br VT Standard</span>
                                <span class="font-bold text-gray-900">{{ activeDetailsRecord?.br_vt_standard ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2.5 text-sm">
                                <span class="text-gray-600 font-medium">HD5 Standard</span>
                                <span class="font-bold text-gray-900">{{ activeDetailsRecord?.hd5_standard ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2.5 text-sm">
                                <span class="text-gray-600 font-medium">JD5 Standard</span>
                                <span class="font-bold text-gray-900">{{ activeDetailsRecord?.jd5_standard ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end pt-2">
                    <button
                        @click="showDetailsModal = false; activeDetailsRecord = null;"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Unified Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Confirm Deletion</h3>

                <p class="mt-2 text-sm text-gray-600">
                    Are you sure you want to delete <strong class="text-gray-800">{{ recordToDelete?.model_name }}</strong>? This action cannot be undone.
                </p>

                <div class="flex justify-end space-x-3 mt-6">
                    <button
                        @click="showDeleteModal = false; recordToDelete = null; deleteType = ''"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>
                    <button
                        @click="executeDelete"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>


    </div>

  </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { router } from '@inertiajs/vue3'
import { ref, onMounted, computed, watch } from 'vue';
import DotsLoader from '@/Components/DotsLoader.vue';
import Papa from 'papaparse';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js'

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {

        const start = Date.now();
        const timeout = 500;

        while (!state.user) {
            if (Date.now() - start > timeout) {
                console.error('Auth timeout: user data failed to load.');
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

const userInstructionsLogging = async (logEvent) => {
    try{
        const responseInstructionsLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Instructions',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('userMiasFactorLogging post request failed: ',error);
    }
}

const showSelectionPanel = ref(true);
const showVTPanel = ref(false);
const showCPKIHCPanel = ref(false);
const showGXPanel = ref(false);
const showTTMWCPanel = ref(false);
const showTTMNCPanel = ref(false);
const showBHPanel = ref(false);
const showROBPanel = ref(false);
const showROBAJPanel = ref(false);
const showCPKBRPanel = ref(false);
const showHISPanel = ref(false);

// IMAGE MODAL STATE
const showImageModal = ref(false);
const activeImage = ref(null);

// DATA SOURCE (controls everything)
const models = [
    { name: 'VT MODELS', panel: 'showVTPanel', image: '/photo/vt_models.png' },
    { name: 'CPK IHC MODELS', panel: 'showCPKIHCPanel', image: '/photo/cpk_ihc_models.png' },
    { name: 'GX MODELS', panel: 'showGXPanel', image: '/photo/gx_models.png' },
    { name: '1X1X1 MODELS (WITH CORNER)', panel: 'showTTMWCPanel' },
    { name: '1X1X1 MODELS (NO CORNER)', panel: 'showTTMNCPanel', image: '/photo/no_corner_models.png' },
    { name: 'BH MODELS', panel: 'showBHPanel', image: '/photo/bh_models.png' },
    { name: 'ROB BH TRACER MODELS', panel: 'showROBPanel', image: '/photo/rob_tracer_models.png' },
    { name: 'ROB BH TRACER MODELS (All Judgements)', panel: 'showROBAJPanel', image: '/photo/rob_tracer_models.png' },
    { name: 'CPK BR MODELS', panel: 'showCPKBRPanel', image: '/photo/cpk_br_models.png' },
    { name: 'HIS MODELS', panel: 'showHISPanel', image: '/photo/gs_models.png' },
];

// PANEL NAVIGATION
const openPanel = (panelName) => {
    showSelectionPanel.value = false;
    showMiniLogsPanel.value = true;
    showFullLogsPanel.value = false;

    // reset all panels
    showVTPanel.value = false;
    showCPKIHCPanel.value = false;
    showGXPanel.value = false;
    showTTMWCPanel.value = false;
    showTTMNCPanel.value = false;
    showBHPanel.value = false;
    showROBPanel.value = false;
    showROBAJPanel.value = false;
    showCPKBRPanel.value = false;
    showHISPanel.value = false;

    // activate selected panelf
    if (panelName === 'showVTPanel') showVTPanel.value = true;
    if (panelName === 'showCPKIHCPanel') showCPKIHCPanel.value = true;
    if (panelName === 'showGXPanel') showGXPanel.value = true;
    if (panelName === 'showTTMWCPanel') showTTMWCPanel.value = true;
    if (panelName === 'showTTMNCPanel') showTTMNCPanel.value = true;
    if (panelName === 'showBHPanel') showBHPanel.value = true;
    if (panelName === 'showROBPanel') showROBPanel.value = true;
    if (panelName === 'showROBAJPanel') showROBAJPanel.value = true;
    if (panelName === 'showCPKBRPanel') showCPKBRPanel.value = true;
    if (panelName === 'showHISPanel') showHISPanel.value = true;
}

// IMAGE HANDLING
const openImage = (image) => {
    activeImage.value = image;
    showImageModal.value = true;
}

const closeImage = () => {
    showImageModal.value = false;
    activeImage.value = null;
}

const systemLogs = ref([]);
const showMiniLogsPanel = ref(true);
const showFullLogsPanel = ref(false);
const paginationMeta = ref({})
// Filter states
const searchQuery = ref('');
const startDate = ref('');
const endDate = ref('');

// Fast Backend Controller Pagination & Filter Fetcher
const fetchLogs = async (page = 1, perPage = 10) => {
    try {
        // Build query parameters dynamically
        const params = new URLSearchParams({
            page: page,
            per_page: perPage,
        });

        if (searchQuery.value) params.append('search', searchQuery.value);
        if (startDate.value) params.append('start_date', startDate.value);
        if (endDate.value) params.append('end_date', endDate.value);

        const response = await axios.get(`/api/userlogs/instructions?${params.toString()}`);

        systemLogs.value = Array.isArray(response.data.data) ? response.data.data : [];

        paginationMeta.value = {
            current_page: response.data.current_page || 1,
            last_page: response.data.last_page || 1,
            prev_page_url: response.data.prev_page_url || null,
            next_page_url: response.data.next_page_url || null,
            total: response.data.total || 0
        };
    } catch (error) {
        systemLogs.value = [];
        console.error('Failed to load paginated system logs:', error);
    }
}

// Watch filters so they automatically trigger backend queries in real time with pagination reset to page 1
watch([searchQuery, startDate, endDate], () => {
    fetchLogs(1, 10);
});

// Miniature top 5 subset feed
const miniLogs = computed(() => {
    return Array.isArray(systemLogs.value) ? systemLogs.value.slice(0, 5) : [];
});

// Since the backend already handles filtering, paginatedLogs just directly outputs systemLogs safely
const paginatedLogs = computed(() => {
    return Array.isArray(systemLogs.value) ? systemLogs.value : [];
});

const isAdded = (eventText) => {
    return eventText && eventText.toLowerCase().includes('added');
}

const formatDateTime = (timestamp) => {
    if (!timestamp) return '-';
    return new Date(timestamp).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

const formatShortDate = (timestamp) => {
    if (!timestamp) return '-';
    return new Date(timestamp).toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

const resetFilters = () => {
    searchQuery.value = '';
    startDate.value = '';
    endDate.value = '';
    // Watcher will automatically fire fetchLogs(1, 10)
}

const openFullLogsModal = () => {
    showFullLogsPanel.value = true;
    showMiniLogsPanel.value = false;
    fetchLogs(1, 10);
}

// Heavily trims the event string to remove boilerplate text
const trimEvent = (eventText) => {
    if (!eventText) return '';
    return eventText
        .replace(/has successfully /gi, '')
        .replace(/to the data list instructions of/gi, '')
        .replace(/from the data list instructions of/gi, '')
        .trim();
};

const model_names = ref([]);
const vtModels = ref([]); // all fetched records
const cpkihcModels = ref([]);
const gxModels = ref([]);
const ttmwcModels = ref([]);
const ttmncModels = ref([]);
const bhModels = ref([]);
const robModels = ref([]);
const robAjModels = ref([]);
const cpkbrModels = ref([]);
const hisModels = ref([]);

const vt_newRecord = ref({ model_name: '', encoded_by: ''});
const cpkihc_newRecord = ref({ model_name: '', encoded_by: ''});
const gx_newRecord = ref({ model_name: '', encoded_by: ''});
const ttmwc_newRecord = ref({ model_name: '', encoded_by: ''});
const ttmnc_newRecord = ref({ model_name: '', encoded_by: ''});
const bh_newRecord = ref({ model_name: '', encoded_by: ''});
const rob_newRecord = ref({ model_name: '', encoded_by: '', br_rt_standardmax: 0, br_rt_standardmin: 0, br_vt_standard: 0, hd5_standard: 0, jd5_standard: 0});
const robaj_newRecord = ref({ model_name: '', encoded_by: '', br_rt_standardmax: 0, br_rt_standardmin: 0, br_vt_standard: 0, hd5_standard: 0, jd5_standard: 0});
const cpkbr_newRecord = ref({ model_name: '', encoded_by: ''});
const his_newRecord = ref({ model_name: '', encoded_by: '' });

const ttmwc_editingRecord = ref(null);
const ttmnc_editingRecord = ref(null);
const bh_editingRecord = ref(null);
const cpkbr_editingRecord = ref(null);
const his_editingRecord = ref(null);

const showDeleteModal = ref(false);
const showAddConfirmModal = ref(false);
const showAddValidationModal = ref(false);
const showDetailsModal = ref(false);
const selectedModelDetails = ref(null);
const recordToDelete = ref(null);
const deleteType = ref('');


const activeModalType = ref(''); // Stores title e.g. 'ROB Model' or 'ROB AJ Model'
const activeDetailsRecord = ref(null);
const activePendingRecord = ref(null);
const activeSaveCallback = ref(null);

// Open Details Modal Dynamically
const openDynamicDetailsModal = (record, modelType) => {
    activeDetailsRecord.value = record;
    activeModalType.value = modelType;
    showDetailsModal.value = true;
};

const backButton = () => {
    showSelectionPanel.value = true;
    showVTPanel.value = false;
    showBHPanel.value = false;
    showROBPanel.value = false;
    showROBAJPanel.value = false;
    showCPKIHCPanel.value = false;
    showGXPanel.value = false;
    showTTMWCPanel.value = false;
    showTTMNCPanel.value = false;
    showCPKBRPanel.value = false;
    showHISPanel.value = false;
}

const vt_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'vt';
    showDeleteModal.value = true;
};

const cpkihc_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'cpkihc';
    showDeleteModal.value = true;
}

const gx_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'gx';
    showDeleteModal.value = true;
};


const ttmwc_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'ttmwc';
    showDeleteModal.value = true;
};

const ttmnc_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'ttmnc';
    showDeleteModal.value = true;
};

const bh_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'bh';
    showDeleteModal.value = true;
};

const cpkbr_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'cpkbr';
    showDeleteModal.value = true;
}

const his_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'his';
    showDeleteModal.value = true;
}

const rob_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'rob';
    showDeleteModal.value = true;
};

// Opens the modal for ROB AJ delete
const robaj_confirmDelete = (record) => {
    recordToDelete.value = record;
    deleteType.value = 'robaj';
    showDeleteModal.value = true;
};

// Handles the actual API deletion call dynamically based on deleteType
const executeDelete = async () => {
    if (!recordToDelete.value) return;

    try {
        if (deleteType.value === 'rob') {
            await axios.delete(`/api/rob-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of ROB Models`);
        } else if (deleteType.value === 'robaj') {
            await axios.delete(`/api/rob-model-ajs/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of ROB AJ Models`);
        } else if (deleteType.value === 'vt') {
            await axios.delete(`/api/vt-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of VT Models`);
        } else if (deleteType.value === 'gx') {
            await axios.delete(`/api/gx-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of GX Models`);
        } else if (deleteType.value === 'ttmwc') {
            await axios.delete(`/api/ttmwc-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of TTMWC Models`);
        } else if (deleteType.value === 'ttmnc') {
            await axios.delete(`/api/ttmnc-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of TTMNC Models`);
        } else if (deleteType.value === 'bh') {
            await axios.delete(`/api/bh-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of BH Models`);
        } else if (deleteType.value === 'cpkbr') {
            await axios.delete(`/api/cpk-br-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of CPK BR Models`);
        } else if (deleteType.value === 'his') {
            await axios.delete(`/api/his-models/${recordToDelete.value.id}`);
            await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of HIS Models`);
        }

        await loadData();
    } catch (error) {
        console.error("Failed to delete record:", error);
    } finally {
        // Reset state & close modal
        showDeleteModal.value = false;
        recordToDelete.value = null;
        deleteType.value = '';
    }
};

// Add record
const vt_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    vt_newRecord.value.encoded_by = userName.toUpperCase();

    if (!vt_newRecord.value.model_name || !vt_newRecord.value.encoded_by) return;

    await axios.post('/api/vt-models', vt_newRecord.value);
    await userInstructionsLogging(`has successfully added ${vt_newRecord.value.model_name} to the data list instructions of VT Models`);
    await loadData();
    vt_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const cpkihc_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    cpkihc_newRecord.value.encoded_by = userName.toUpperCase();
    if (!cpkihc_newRecord.value.model_name || !cpkihc_newRecord.value.encoded_by) return;

    await axios.post('/api/cpk-ihc-models', cpkihc_newRecord.value);
    await userInstructionsLogging(`has successfully added ${cpkihc_newRecord.value.model_name} to the data list instructions of CPK IHC Models`);
    await loadData();
    cpkihc_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const gx_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    gx_newRecord.value.encoded_by = userName.toUpperCase();
    if (!gx_newRecord.value.model_name || !gx_newRecord.value.encoded_by) return;

    await axios.post('/api/gx-models', gx_newRecord.value);
    await userInstructionsLogging(`has successfully added ${gx_newRecord.value.model_name} to the data list instructions of GX Models`);
    await loadData();
    gx_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const ttmwc_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    ttmwc_newRecord.value.encoded_by = userName.toUpperCase();
    if (!ttmwc_newRecord.value.model_name || !ttmwc_newRecord.value.encoded_by) return;

    await axios.post('/api/ttmwc-models', ttmwc_newRecord.value);
    await userInstructionsLogging(`has successfully added ${ttmwc_newRecord.value.model_name} to the data list instructions of 1x1x1 with corner Models`);
    await loadData();
    ttmwc_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const ttmnc_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    ttmnc_newRecord.value.encoded_by = userName.toUpperCase();
    if (!ttmnc_newRecord.value.model_name || !ttmnc_newRecord.value.encoded_by) return;

    await axios.post('/api/ttmnc-models', ttmnc_newRecord.value);
    await userInstructionsLogging(`has successfully added ${ttmnc_newRecord.value.model_name} to the data list instructions of 1x1x1 without corner Models`);
    await loadData();
    ttmnc_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const bh_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    bh_newRecord.value.encoded_by = userName.toUpperCase();
    if (!bh_newRecord.value.model_name || !bh_newRecord.value.encoded_by) return;

    await axios.post('/api/bh-models', bh_newRecord.value);
    await userInstructionsLogging(`has successfully added ${bh_newRecord.value.model_name} to the data list instructions of BH Models`);
    await loadData();
    bh_newRecord.value = { model_name: '', encoded_by: ''};
};

const cpkbr_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    cpkbr_newRecord.value.encoded_by = userName.toUpperCase();
    if (!cpkbr_newRecord.value.model_name || !cpkbr_newRecord.value.encoded_by) return;

    await axios.post('/api/cpk-br-models', cpkbr_newRecord.value);
    await userInstructionsLogging(`has successfully added ${cpkbr_newRecord.value.model_name} to the data list instructions of CPK BR Models`);
    await loadData();
    cpkbr_newRecord.value = { model_name: '', encoded_by: ''};
};

const his_addRecord = async () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    his_newRecord.value.encoded_by = userName.toUpperCase();
    if (!his_newRecord.value.model_name || !his_newRecord.value.encoded_by) return;

    await axios.post('/api/his-models', his_newRecord.value);
    await userInstructionsLogging(`has successfully added ${his_newRecord.value.model_name} to the data list instructions of HIS Models`);
    await loadData();
    his_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const rob_addRecord = () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    rob_newRecord.value.encoded_by = userName.toUpperCase();

    const rec = rob_newRecord.value;

    // Validation Check
    if (
        !rec.model_name || !rec.encoded_by ||
        rec.br_rt_standardmax === null || rec.br_rt_standardmax === '' ||
        rec.br_rt_standardmin === null || rec.br_rt_standardmin === '' ||
        rec.br_vt_standard === null || rec.br_vt_standard === '' ||
        rec.hd5_standard === null || rec.hd5_standard === '' ||
        rec.jd5_standard === null || rec.jd5_standard === ''
    ) {
        showAddValidationModal.value = true;
        return;
    }

    // Set dynamic target parameters and show confirmation
    activePendingRecord.value = rec;
    activeModalType.value = 'ROB Model';
    activeSaveCallback.value = rob_executeAdd;
    showAddConfirmModal.value = true;
};

const rob_executeAdd = async () => {
    const rec = rob_newRecord.value;
    try {
        await axios.post('/api/rob-models', rec);
        await userInstructionsLogging(`has successfully added ${rec.model_name} to ROB Models`);
        await loadData();

        rob_newRecord.value = { model_name: '', encoded_by: '' };
    } catch (error) {
        console.error("Failed to save ROB model:", error);
    } finally {
        showAddConfirmModal.value = false;
    }
};

// --- ROB AJ Add Logic (Plugs into the same modals) ---
const robaj_addRecord = () => {
    const userName = `${state.user?.firstName || ''} ${state.user?.surname || ''}`.trim();
    robaj_newRecord.value.encoded_by = userName.toUpperCase();

    const rec = robaj_newRecord.value;

    if (
        !rec.model_name || !rec.encoded_by ||
        rec.br_rt_standardmax === null || rec.br_rt_standardmax === '' ||
        rec.br_rt_standardmin === null || rec.br_rt_standardmin === '' ||
        rec.br_vt_standard === null || rec.br_vt_standard === '' ||
        rec.hd5_standard === null || rec.hd5_standard === '' ||
        rec.jd5_standard === null || rec.jd5_standard === ''
    ) {
        showAddValidationModal.value = true;
        return;
    }

    activePendingRecord.value = rec;
    activeModalType.value = 'ROB AJ Model';
    activeSaveCallback.value = robaj_executeAdd;
    showAddConfirmModal.value = true;
};

    // 2. Triggered when user confirms in the modal
const robaj_executeAdd = async () => {
    const rec = robaj_newRecord.value;

    try {
        await axios.post('/api/rob-model-ajs', rec);
        await userInstructionsLogging(`has successfully added ${rec.model_name} to the data list instructions of ROB AJ Models`);

        await loadData();

        // Reset form state
        robaj_newRecord.value = {
        model_name: '',
        encoded_by: '',
        br_rt_standardmax: null,
        br_rt_standardmin: null,
        br_vt_standard: null,
        hd5_standard: null,
        jd5_standard: null,
        };
    } catch (error) {
        console.error("Failed to save new ROB model:", error);
    } finally {
        showAddConfirmModal.value = false;
    }
};

const loadData = async () => {
  try {

    await getAllSpecialInstructionLogs();
    await fetchLogs(1, 10);

    const responseGetVTData = await axios.get('/api/vt-models');
    vtModels.value = responseGetVTData.data;
    const responseGetCPKIHCData = await axios.get('/api/cpk-ihc-models');
    cpkihcModels.value = responseGetCPKIHCData.data;
    const responseGetGXData = await axios.get('/api/gx-models');
    gxModels.value = responseGetGXData.data;
    const responseGetTTMWCData = await axios.get('/api/ttmwc-models');
    ttmwcModels.value = responseGetTTMWCData.data;
    const responseGetTTMNCData = await axios.get('/api/ttmnc-models');
    ttmncModels.value = responseGetTTMNCData.data;
    const responseGetBHData = await axios.get('/api/bh-models');
    bhModels.value = responseGetBHData.data;
    const responseGetROBData = await axios.get('/api/rob-models');
    robModels.value = responseGetROBData.data;
    const responseGetROBAJData = await axios.get('/api/rob-model-ajs');
    robAjModels.value = responseGetROBAJData.data;
    const responseGetCPKBRData = await axios.get('/api/cpk-br-models');
    cpkbrModels.value = responseGetCPKBRData.data;
    const responseGetHISData = await axios.get('/api/his-models');
    hisModels.value = responseGetHISData.data;
  } catch (e) {
    console.error('Failed to load responseGetVTData: ', e);
  }
};

const getModelLists = async () => {
    try{
        const response = await axios.get('/api/inspectiondata/');
        const inspectionDataList = response.data.data;
        model_names.value = inspectionDataList.map(item => item.model);
        //console.log('Model lists: ',model_names.value);
    }catch(error){
        console.error('Error fetching model names', error);
        toast.error('Failed to get the model names.');
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getModelLists",
            "Failed to get the model names"
        );
    }
}

const getAllSpecialInstructionLogs = async () => {
    try {
        // Updated to hit your new custom endpoint
        const response = await axios.get("/api/userlogs/instructions");
        systemLogs.value = response.data;
        console.log('Instruction Logs: ', systemLogs.value);
    } catch (error) {
        console.error('Failed to fetch system logs: ', error);
    }
}


onMounted(async () => {
  await loadData();
  await checkAuthentication();
  await getModelLists();
  await getAllSpecialInstructionLogs();
  await fetchLogs(1, 10);
});
</script>
