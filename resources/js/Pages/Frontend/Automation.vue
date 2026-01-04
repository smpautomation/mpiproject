<template>
    <div class="flex min-h-screen bg-cyan-50">
        <!-- Sidebar -->
        <aside class="flex flex-col w-64 text-white bg-teal-600">
            <!-- Logo -->
            <div class="flex items-center px-6 py-4 space-x-3 cursor-pointer group">
                <!-- Magnetic Logo Container -->
                <div class="relative flex-shrink-0 w-16 h-16">
                    <!-- Outer glow -->
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-cyan-400 to-teal-400 rounded-xl blur-md group-hover:opacity-80"></div>

                    <!-- Main magnet box -->
                    <div class="relative w-full h-full p-2 transition-all duration-300 shadow-lg bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-2xl">
                        <svg viewBox="0 0 100 100" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <!-- Magnetic field lines (brighter) -->
                            <g class="animate-pulse">
                                <path d="M20 50 Q30 30, 50 30 Q70 30, 80 50" fill="none" stroke="rgba(0,255,255,0.6)" stroke-width="2"/>
                                <path d="M20 50 Q30 70, 50 70 Q70 70, 80 50" fill="none" stroke="rgba(0,255,255,0.6)" stroke-width="2"/>
                            </g>

                            <!-- Magnet poles (stronger colors) -->
                            <g class="transition-transform group-hover:scale-105">
                                <rect x="15" y="35" width="20" height="30" rx="3" fill="#ffffff" opacity="1"/>
                                <text x="25" y="55" font-size="16" font-weight="bold" fill="#00ffff" text-anchor="middle">N</text>

                                <rect x="65" y="35" width="20" height="30" rx="3" fill="#ffffff" opacity="1"/>
                                <text x="75" y="55" font-size="16" font-weight="bold" fill="#00e5b4" text-anchor="middle">S</text>
                            </g>

                            <!-- Center attraction particles -->
                            <circle cx="50" cy="50" r="3" fill="cyan" opacity="0.9" class="animate-ping"/>
                            <circle cx="50" cy="50" r="2" fill="cyan"/>
                        </svg>
                    </div>
                </div>

                <!-- Text -->
                <div class="flex flex-col justify-center">
                    <span class="text-xl font-extrabold leading-none text-transparent whitespace-nowrap bg-clip-text bg-gradient-to-r from-cyan-400 to-teal-300 group-hover:from-cyan-300 group-hover:to-teal-200 drop-shadow-md">
                        GBDP MPI
                    </span>
                    <span class="text-sm font-semibold text-gray-100 whitespace-nowrap group-hover:text-white">
                        Online System
                    </span>
                </div>
            </div>


            <!-- Navigation -->
            <nav class="flex-1 px-4 space-y-2">
                <button @click="activeSection = 'home'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Database</button>
                <button @click="activeSection = 'errors'" class="w-full px-3 py-2 text-left transition rounded hover:bg-teal-500">Error Logs</button>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
        <!-- Topbar -->
        <div class="flex items-center justify-between p-4 mb-6 text-white rounded-lg shadow bg-cyan-500">
            <h1 class="text-lg font-semibold">{{ sectionTitle }}</h1>
            <div class="flex space-x-4">
                <button @click="Inertia.visit('/')" class="px-3 py-1 rounded bg-cyan-400 hover:bg-cyan-300">Return Home</button>
            </div>
        </div>

        <!-- Section Content -->
        <div v-if="activeSection === 'home'">

            <!-- Charts & Tables (placeholders) -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="mb-4 text-lg font-semibold text-teal-700">Mass Production Table</h2>
                <div class="flex items-center justify-center h-64 rounded-lg bg-cyan-100">[Data here]</div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="mb-4 text-lg font-semibold text-teal-700">Recent Activity</h2>
                <div class="flex items-center justify-center h-64 rounded-lg bg-cyan-100">[Table here]</div>
            </div>
            </div>
        </div>

        <div v-if="activeSection === 'errors'">
            <div class="p-6 overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full text-left border border-gray-300">
                    <thead class="bg-cyan-100">
                    <tr>
                        <th class="px-4 py-2 border">Timestamp</th>
                        <th class="px-4 py-2 border">User</th>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Details</th>
                        <th class="px-4 py-2 border">Trigger Function</th>
                        <th class="px-4 py-2 border">Section</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Empty State -->
                    <tr v-if="!errorLogs.length">
                        <td class="px-4 py-2 text-center text-gray-400 border" colspan="6">
                        No error logs found.
                        </td>
                    </tr>

                    <!-- Error Log Rows -->
                    <tr v-for="(log, index) in errorLogs" :key="index" class="hover:bg-cyan-50" v-else>
                        <td class="px-4 py-2 border">{{ log.timestamp }}</td>
                        <td class="px-4 py-2 border">{{ log.user }}</td>
                        <td class="px-4 py-2 border">{{ log.title }}</td>
<td class="px-4 py-2 align-top border">
  <div>
    <!-- Expanded -->
    <div v-if="expandedRows.has(index)">
      <pre class="text-sm break-words whitespace-pre-wrap">
{{ typeof log.details === 'string' ? log.details.trim() : JSON.stringify(log.details, null, 2) }}
      </pre>
      <button
        class="mt-1 text-teal-600 hover:underline"
        @click="toggleRow(index)">
        See less
      </button>
    </div>

    <!-- Collapsed -->
    <div v-else>
      <pre class="text-sm break-words whitespace-pre-wrap">
{{
    typeof log.details === 'string'
    ? log.details.trim().slice(0, 120)
    : JSON.stringify(log.details).slice(0, 200)
}}…
      </pre>
      <button
        class="mt-1 text-teal-600 hover:underline"
        @click="toggleRow(index)">
        See more
      </button>
    </div>
  </div>
</td>
                        <td class="px-4 py-2 border">{{ log.trigger_function }}</td>
                        <td class="px-4 py-2 border">{{ log.section }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Add more sections as needed -->
        </main>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';

const activeSection = ref('home');
const errorLogs = ref([]);
const expandedRows = ref(new Set());

const sectionTitle = computed(() => {
    switch (activeSection.value) {
        case 'home': return 'Automation Master Control';
        case 'errors': return 'Error Logs';
        default: return '';
    }
});

const toggleRow = (index) => {
    if (expandedRows.value.has(index)) {
        expandedRows.value.delete(index);
    } else {
        expandedRows.value.add(index);
    }
};

// Fetch logs from API
const fetchErrorLogs = async () => {
    try {
        const response = await axios.get('/api/error-logs');
        console.log('API response:', response.data);

        errorLogs.value = response.data.map(log => ({
        timestamp: formatTimestampUTC(log.created_at),
        user: log.user || '',
        title: log.title || '',
        details: log.details || 'N/A',
        trigger_function: log.trigger_function || 'N/A',
        section: log.section || 'N/A'
        }));

        console.log('Mapped logs:', errorLogs.value);
    } catch (err) {
        console.error('Failed to fetch error logs:', err);
    }
};

const formatTimestampUTC = (isoString) => {
    if (!isoString) return '';
    const date = new Date(isoString);
    return date.toISOString().replace('T', ' ').split('.')[0]; // "2025-11-18 17:36:53"
};

const getMassProductionData = async () => {
    try{
        const response = axios.get('/api/mass-production');
        console.log("Mass Production data", response.data)
    }catch(error){
        console.error('Failed to fetch Mass production data', error);
    }
}

// Fetch on mount
onMounted( async () => {
    await fetchErrorLogs();
});

</script>
