<template>
    <Frontend>
        <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 space-y-6 bg-gray-100">
            <h1 class="text-2xl font-semibold text-gray-800"><span>{{ redirectedFurnace }} {{ redirectedMassPro }}</span> Mass Production Heat Treatment Graphs</h1>
            <div class="flex flex-col w-full max-w-6xl gap-6">
                <div class="w-full p-4 bg-white border border-gray-300 rounded shadow-sm">
                    <h2 class="mb-4 text-lg font-medium text-gray-700">Cycle Graph</h2>
                    <div
                        class="w-full h-[400px] bg-gray-50 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-4"
                    >
                        <img
                            v-if="cycleGraphUrl"
                            :src="cycleGraphUrl"
                            class="object-contain w-full h-full"
                            alt="Cycle Graph"
                            @error="cycleGraphMissing = true"
                            v-show="!cycleGraphMissing"
                        />
                        <p v-if="cycleGraphMissing">No image uploaded</p>
                    </div>
                    <button
                        class="p-2 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700"
                        @click="downloadImage('cycle')"
                    >
                        Download Image
                    </button>
                </div>

                <!-- Two Panels Side-by-Side -->
                <div class="flex w-full gap-6">
                    <!-- Standard Graph Panel -->
                    <div class="flex-1 p-4 bg-white border border-gray-300 rounded shadow-sm">
                        <h2 class="mb-4 text-lg font-medium text-gray-700">Standard Graph</h2>
                        <div
                            class="w-full h-[400px] bg-gray-50 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-4"
                        >
                            <img
                                v-if="standardGraphUrl"
                                :src="standardGraphUrl"
                                class="object-contain w-full h-full"
                                alt="Standard Graph"
                                @error="standardGraphMissing = true"
                                v-show="!standardGraphMissing"
                            />
                            <p v-if="standardGraphMissing">No image uploaded</p>
                        </div>
                        <button
                            class="p-2 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700"
                            @click="downloadImage('standard')"
                        >
                            Download Image
                        </button>
                    </div>

                    <!-- Actual Graph Panel -->
                    <div class="flex-1 p-4 bg-white border border-gray-300 rounded shadow-sm">
                        <h2 class="mb-4 text-lg font-medium text-gray-700">Actual Graph</h2>
                        <div
                            class="w-full h-[400px] bg-gray-50 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 mb-4"
                        >
                            <img
                                v-if="actualGraphUrl"
                                :src="actualGraphUrl"
                                class="object-contain w-full h-full"
                                alt="Actual Graph"
                                @error="actualGraphMissing = true"
                                v-show="!actualGraphMissing"
                            />
                            <p v-if="actualGraphMissing">No image uploaded</p>
                        </div>
                        <button
                            class="p-2 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700"
                            @click="downloadImage('actual')"
                        >
                            Download Image
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-start mt-8">
                <button
                    @click="Inertia.visit('/massprod')"
                    class="flex items-center gap-2 px-4 py-2 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-100 hover:text-black"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Mass Production Lists
                </button>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js'

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
const redirectedMassPro = ref();
const redirectedFurnace = ref();
const ht_props = defineProps({
    massProd: String,
    furnace: String
});
redirectedMassPro.value = ht_props.massProd;
redirectedFurnace.value = ht_props.furnace;

const standardGraphMissing = ref(false);
const actualGraphMissing = ref(false);
const cycleGraphMissing = ref(false);
const basePath = `/htgraphs/` + redirectedFurnace.value + ` ` + redirectedMassPro.value;
const standardGraphUrl = computed(() =>
  `${basePath}/standard/graph.png?ts=${Date.now()}`  //Note: The addition of ?ts=${Date.now()} is to prevent browser cache so that it will always show the current image.
);

const actualGraphUrl = computed(() =>
  `${basePath}/actual/graph.png?ts=${Date.now()}`
);

const cycleGraphUrl = computed(() =>
  `${basePath}/cycle/graph.png?ts=${Date.now()}`
);

const downloadImage = (type) => {
    let url;

    switch (type) {
        case 'standard':
            url = standardGraphUrl.value;
            break;
        case 'actual':
            url = actualGraphUrl.value;
            break;
        case 'cycle':
            url = cycleGraphUrl.value;
            break;
        default:
            alert('Invalid image type.');
            return;
    }

    if (!url) {
        alert('No image to download.');
        return;
    }

    const link = document.createElement('a');
    link.href = url;
    link.download = `${redirectedFurnace.value}_${redirectedMassPro.value}_${type}_graph.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

</script>
