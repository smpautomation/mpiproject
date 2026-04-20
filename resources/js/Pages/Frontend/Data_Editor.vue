<template>
    <Frontend>
        <div class="w-full space-y-6">
            <div class="flex flex-row">


                <div class="mt-10 ml-10 w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

                    <!-- Title -->
                    <h2 class="mb-6 text-sm font-semibold tracking-wide text-gray-800 uppercase">
                        Select Mass Production Data
                    </h2>

                    <!-- Furnace -->
                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-medium text-gray-600">
                            Furnace
                        </label>
                        <select
                            v-model="selectedFurnace"
                            :disabled="isValidationSuccess"
                            class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
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
                            @input="selectedMassProd = selectedMassProd.toUpperCase()"
                            :disabled="isValidationSuccess"
                            class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
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
                            :disabled="isValidationSuccess"
                            class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled: cursor-not-allowed"
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

                    <div v-if="!isCorrectiveActionCompleted">
                        <!-- Action -->
                        <button
                            v-if="isValidationSuccess"
                            @click="cancelValidate"
                            class="w-full px-4 py-2 text-sm font-semibold text-white transition bg-red-500 rounded-md hover:bg-red-600 active:scale-[0.99]"
                        >
                            Cancel
                        </button>

                        <!-- Action -->
                        <button
                            v-else
                            @click="checkValidate"
                            class="w-full px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.99]"
                        >
                            Check/Validate
                        </button>
                    </div>


                    <div v-if="isValidationSuccess" class="mt-10">
                        <p class="text-xs text-gray-500">Selected data: </p> Layer <strong>{{ selectedLayer }}</strong> of <strong>{{ selectedFurnace }} {{ selectedMassProd }}</strong>
                    </div>

                </div>

                <div v-if="isValidationSuccess" class="w-full max-w-lg p-6 bg-white border border-gray-200 ml-10 mt-10 rounded-lg shadow-sm">
                    <!-- Header -->
                    <h2 class="mb-4 text-sm font-semibold tracking-wide text-gray-800 uppercase ">
                        Fill in the fields
                    </h2>

                    <!-- Reason -->
                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-medium text-gray-600">
                            Reason for data modification:
                        </label>

                        <textarea
                            v-model="userReason"
                            rows="3"
                            :disabled="isCorrectiveActionCompleted"
                            class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            placeholder="Explain why the data needs to be modified..."
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-medium text-gray-600">
                            Corrective Action (steps taken to ensure this issue does not happen again):
                        </label>

                        <textarea
                            v-model="userCorrectiveAction"
                            rows="4"
                            :disabled="isCorrectiveActionCompleted"
                            class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            placeholder="Describe preventive steps taken..."
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-medium text-gray-600">
                            Verified by (Superior / PIC / Shift Leader):
                        </label>
                        <input
                            v-model="userVerifiedBy"
                            type="text"
                            :disabled="isCorrectiveActionCompleted"
                            class="w-full px-3 py-2 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-md disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            placeholder="Enter verifier name..."
                        />
                    </div>

                    <label class="flex items-start text-xs text-gray-600 mb-4">
                        <input :disabled="isCorrectiveActionCompleted" type="checkbox" v-model="userVerified" class="mr-2 mt-0.5 disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed">
                        <span v-if="state.user && state.user.access_type">
                            I, <strong>{{ state.user.firstName }} {{ state.user.surname }}</strong>, confirm that this modification has been reviewed and approved by the responsible supervisor/PIC.
                            I understand that all changes are logged and subject to audit review.
                        </span>
                    </label>
                    <div class="flex float-right">
                        <!-- Action -->
                        <button
                            v-if="isCorrectiveActionCompleted"
                            @click="cancelSubmitForm"
                            class="w-full px-4 py-2 text-sm font-semibold text-white transition bg-red-500 rounded-md hover:bg-red-600 active:scale-[0.99]"
                        >
                            Cancel
                        </button>
                        <button
                            v-else
                            class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                            @click="submitForm"
                        >
                            Next
                        </button>
                    </div>

                </div>

                <div v-if="isValidationSuccess && isCorrectiveActionCompleted" class="w-full max-w-lg p-6 bg-white border border-gray-200 ml-10 mt-10 rounded-lg shadow-sm">

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
                                    This action will permanently reset all mass production data for the selected layer,
                                    including coating and film pasting (if present). All linked additional lots within this layer group will also be removed.
                                    Initial process data will remain untouched.
                                </span>

                                <br>

                                <span class="text-xs text-red-600 font-medium">
                                    Warning: This affects all connected lots under the same layer grouping.
                                    It is strongly recommended to review the Control Sheet page before proceeding after deletion.
                                </span>
                            </p>

                            <button
                                class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                                @click="proceedButton"
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
                        <span class="font-semibold">{{ selectedFurnace }} {{ selectedMassProd }}</span>
                        at Layer
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
                            @click="deleteLayerData"
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
import { onMounted, ref, computed, watch } from "vue";
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

const userManageLogging = async (logEvent) => {
    try {
        const responseUserLogging = await axios.post("/api/userlogs", {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: "Data Editor",
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    } catch (error) {
        console.error("userManageLogging post request failed: ", error);
    }
};

const userErrorLogging = async (details, triggerFunction, title) => {
    try {
        const response = await axios.post("/api/error-logs", {
            user: state.user.firstName + " " + state.user.surname,
            title: title,
            details: details,
            trigger_function: triggerFunction,
            section: "Data Editor",
        });

        //console.log('userErrorLogging-data: ',responseUserLogin.data);
    } catch (error) {
        console.error("userErrorLogging post request failed: ", error);
    }
};

// Utility: Save and load to sessionStorage
function useSessionStorage(key, state) {
    // Load existing session value
    const saved = sessionStorage.getItem(key);
    if (saved !== null) {
        try {
            const parsed = JSON.parse(saved);
            if (typeof state === "object" && "value" in state) {
                state.value = parsed;
            } else {
                Object.assign(state, parsed);
            }
        } catch {
            /* ignore parse errors */
        }
    }

    // Watch and persist changes
    watch(
        state,
        (val) => {
            sessionStorage.setItem(key, JSON.stringify(val));
        },
        { deep: true },
    );
}

const showProceedConfirmPanel = ref(false);
const isValidationSuccess = ref(false);
const isCorrectiveActionCompleted = ref(false);

const userReason = ref();
const userCorrectiveAction = ref();
const userVerifiedBy = ref();
const userVerified = ref(false);

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

const checkValidate = async() => {
    try{
        if(!selectedFurnace.value || !selectedMassProd.value || !selectedLayer.value){
            toast.warning('Please make sure all fields are selected');
            return;
        }

        const response = await axios.get('/api/check-validate-data-editor',{
            params: {
                mass_prod: selectedMassProd.value,
                furnace: selectedFurnace.value,
                layer: String(selectedLayer.value),
            }
        });

        isValidationSuccess.value = response.data.mass_prod_exists;

        if(isValidationSuccess.value){
            toast.success('Validation Success');
        }else{
            toast.error('The selected data does not exists in the database');
        }

    }catch(error){
        console.error('Validation check failed:', error.response?.data || error);
    }
}

const cancelValidate = async() => {
    isValidationSuccess.value = false;
}

const cancelSubmitForm = async() => {
    isCorrectiveActionCompleted.value = false;
}



const proceedButton = async() =>{

    if(!selectedFurnace.value && !selectedLayer.value && !selectedMassProd.value){
        toast.warning('Fill all fields before proceeding.');
        return;
    }

    showProceedConfirmPanel.value = true;
}

const submitForm = async() => {
    if(!userReason.value || !userCorrectiveAction.value || !userVerifiedBy.value ||!userVerified.value){
        toast.warning('Fill all fields before proceeding.');
        return;
    }

    isCorrectiveActionCompleted.value = true;

}

const deleteLayerData = async () => {
    try {
        const response = await axios.post(
            "/api/mass-production/delete-layer-data",
            {
                massprod: selectedMassProd.value,
                furnace: selectedFurnace.value,
                layer: selectedLayer.value,
            },
        );

        if (response.data.success) {
            await saveLog(`has removed Layer ${selectedLayer.value} data from ${selectedMassProd.value} ${selectedFurnace.value}`);
            toast.success(
                `Layer ${selectedLayer.value} data deleted successfully.`,
            );
            await userManageLogging(
                `has removed Layer ${selectedLayer.value} data from ${selectedMassProd.value} | ${selectedFurnace.value} successfully.`,
            );
            resetPage();
        } else {
            toast.warning("No matching data found.");
        }
    } catch (error) {
        console.error("Failed to delete layer data", error);
        toast.error("Failed to delete layer data.");
        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
                payload: error.response?.data ?? null,
            },
            "deleteLayerData",
            "Failed to delete layer data.",
        );
    }
};

const saveLog = async(log) => {
    try{
        const response = await axios.post('/api/data_editor_logs', {
            mass_prod: selectedMassProd.value,
            furnace: selectedFurnace.value,
            layer: String(selectedLayer.value),
            user_reason: userReason.value,
            user_corrective_action: userCorrectiveAction.value,
            user_verified_by: userVerifiedBy.value,
            user_confirmation: userVerified.value,
            log_remarks: log,
        });

        toast.success('Data logged successfully');
        console.log('Data saved successfully', response.data);

    }catch(error){
        console.error('Failed to save data editor logs', error);
        toast.error('Failed to save editor logs');
    }
}

const resetPage = () => {
    showProceedConfirmPanel.value = false;
    isValidationSuccess.value = false;
    isCorrectiveActionCompleted.value = false;
    userReason.value = null;
    userCorrectiveAction.value = null;
    userVerifiedBy.value = null;
    userVerified.value = null;
}

//SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- ]

useSessionStorage("selectedFurnace", selectedFurnace);
useSessionStorage("selectedMassProd", selectedMassProd);
useSessionStorage("selectedLayer", selectedLayer);
useSessionStorage("userReason", userReason);
useSessionStorage("userCorrectiveAction", userCorrectiveAction);
useSessionStorage("userVerifiedBy", userVerifiedBy);
useSessionStorage("userVerified", userVerified);

onMounted(async() => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }

    await getFurnaceLists();

});

</script>
