<template>
    <Frontend>
        <div class="w-full space-y-6">
            <div class="mt-10 ml-10 w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

                <!-- Title -->
                <h2 class="mb-6 text-sm font-semibold tracking-wide text-gray-800 uppercase">
                    Data Editor
                </h2>

                <!-- Furnace -->
                <div class="mb-4">
                    <label class="block mb-1 text-xs font-medium text-gray-600">
                        Furnace
                    </label>
                    <select
                        v-model="selectedFurnace"
                        class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black"
                    >
                        <option
                            v-for="item in furnace_names"
                            :key="item"
                            :value="item"
                        >
                            {{ item }}
                        </option>
                    </select>
                </div>

                <!-- Mass Production -->
                <div class="mb-4">
                    <label class="block mb-1 text-xs font-medium text-gray-600">
                        Mass Production Name
                    </label>
                    <input
                        v-model="selectedMassProd"
                        type="text"
                        class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black"
                        placeholder="Enter name..."
                    />
                </div>

                <!-- Layer -->
                <div class="mb-6">
                    <label class="block mb-1 text-xs font-medium text-gray-600">
                        Layer
                    </label>
                    <select
                        v-model="selectedLayer"
                        class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black"
                    >
                        <option
                            v-for="item in layers"
                            :key="item"
                            :value="item"
                        >
                            {{ item }}
                        </option>
                    </select>
                </div>

                <!-- Action -->
                <button
                    @click="proceedButton"
                    class="w-full px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.99]"
                >
                    Proceed
                </button>

            </div>

            <div class="w-full max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

                <!-- Header -->
                <h2 class="mb-4 text-sm font-semibold tracking-wide text-gray-800 uppercase">
                    Choose an Action
                </h2>

                <div class="space-y-4">

                    <!-- DANGEROUS ACTION -->
                    <div class="p-4 border border-gray-300 rounded-md bg-gray-50">

                        <p class="mb-3 text-sm text-gray-800">
                            <span class="font-semibold text-black">Delete LAYER data (FULL RESET)</span><br>
                            <span class="text-xs text-gray-600">
                                This will reset all mass production data for the selected layer, including coating and film pasting (if they exist). Initial process data will remain untouched.
                            </span>
                        </p>

                        <button
                            class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                            @click="proceedFullReset"
                        >
                            Proceed
                        </button>

                    </div>

                    <!-- SAFE ACTION -->
                    <div class="p-4 border border-gray-300 rounded-md bg-white">

                        <p class="mb-3 text-sm text-gray-800">
                            <span class="font-semibold">Modify Cycle No Only</span><br>
                            <span class="text-xs text-gray-500">
                                Adjust cycle no without affecting production data.
                            </span>
                        </p>

                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-800 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 active:scale-[0.98]"
                            @click="openCycleEditor"
                        >
                            Proceed
                        </button>

                    </div>
                </div>
            </div>



            <div
                v-if="showProceedConfirmPanel"
                class="fixed inset-0 z-50 flex items-center justify-center"
            >
                <!-- Backdrop -->
                <div
                    class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                    @click="showProceedConfirmPanel = false"
                ></div>

                <!-- Panel -->
                <div class="relative z-10 w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-xl">

                    <!-- Title -->
                    <h2 class="mb-4 text-sm font-semibold text-gray-800 uppercase">
                        Confirm Action
                    </h2>

                    <!-- Message -->
                    <p class="mb-6 text-sm leading-relaxed text-gray-700">
                        You are about to delete ALL data related to
                        <span class="font-semibold">model {{ fetchModel }}</span>
                        and
                        <span class="font-semibold">lot no {{ fetchedLotNo }}</span>
                        at
                        <span class="font-semibold">{{ selectedFurnace }} {{ selectedMassProd }}</span>
                        Layer
                        <span class="font-semibold">{{ selectedLayer }}</span>.
                        <br><br>
                        This action cannot be undone. Proceed?
                    </p>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3">

                        <button
                            @click="showProceedConfirmPanel = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
                        >
                            Cancel
                        </button>

                        <button
                            @click="confirmProceedDelete"
                            class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                        >
                            Yes, Delete
                        </button>

                    </div>

                </div>
            </div>


        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { onMounted, ref, computed } from "vue";
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js'
import { useToast } from "vue-toast-notification";
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
                router.visit('/'); // Redirect if user never loads
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50));
        }

        if (!state.isAuthenticated) {
            console.warn('User is not authenticated. Redirecting...');
            router.visit('/');
            return false;
        }

        console.log("USER AUTHENTICATED!", `${state.user.firstName} ${state.user.surname}`);
        return true;

    } catch (error) {
        console.error('Error checking authentication:', error);
        router.visit('/');
        return false;
    }
};

const userAdminLogging = async (logEvent) => {
    try{
        const responseAdminLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Data_Editor',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('responseAdminLogging post request failed: ',error);
    }
}

const showProceedConfirmPanel = ref(false);

const selectedFurnace = ref();
const selectedMassProd = ref();
const selectedLayer = ref();

const fetchedModel = ref();
const fetchedLotNo = ref();

const furnace_names = ref([]);
const layers = ref(["1", "2", "3", "4", "5", "6", "7", "8", "9", "9.5"]);


const getFurnaceLists = async () => {
    try {
        const response = await axios.get("/api/furnace-data");
        const furnaceList = response.data;
        furnace_names.value = furnaceList.map((item) => item.furnace_name);
        //console.log("List of mass prods: ",furnace_names.value);
    } catch (error) {
        //console.error('Error fetching mass prod lists',error);
        toast.error("Failed to get the furnace lists api error");
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "getFurnaceLists",
            "Failed to get the furnace lists api error",
        );
    }
};


const proceedButton = async() =>{

    if(!selectedFurnace.value && !selectedLayer.value && !selectedMassProd.value){
        toast.warning('Fill all fields before proceeding.');
        return;
    }

    showProceedConfirmPanel.value = true;
}

onMounted(async() => {
    await getFurnaceLists();
});

</script>
