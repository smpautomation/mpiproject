<template>
    <Frontend>
        <div class="flex flex-col items-center min-h-screen px-4 py-12 bg-gray-100">
            <div class="flex flex-row gap-10">
                <div class="max-w-4xl px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
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
                </div>

                <div class="max-w-4xl px-2 mx-auto space-y-4 bg-white border border-gray-200 shadow-xl rounded-2xl py-7 md:px-12">
                    <h2 class="pb-1 font-bold text-gray-800 border-b text-md">Coating Data (Unit: µ/mm²)</h2>
                    <p>Concentration Amount:</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
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
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-gray-200"></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-1 border-b border-r border-gray-200">6-10</td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-gray-200"></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-1 border-b border-r border-gray-200">11-15</td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-gray-200"></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-1 border-b border-r border-gray-200">16-20</td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-gray-200"></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-1 border-b border-r border-gray-200">21-25</td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-gray-200"></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-1 border-b border-r border-gray-200">26-30</td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-b border-gray-200"></td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-1 border-r border-gray-200">31-35</td>
                                    <td class="px-2 py-1 border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-r border-gray-200"></td>
                                    <td class="px-2 py-1 border-gray-200"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <p>Coating Amount</p>
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
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



onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }
});

</script>

<style scoped>

</style>
