<template>
    <Frontend>
        <div class="w-full space-y-6">
            <div class="flex flex-col">
                <div class="flex float-left mt-5 ml-5">
                    <!-- your other content here -->
                    <div
                        v-if="state.user && state.user.is_editing_allowed == 1"
                        class="px-3 py-1 text-xs font-medium text-green-700 border border-green-200 rounded-md shadow-sm bg-green-50"
                    >
                        <span
                            class="inline-block w-2 h-2 mr-2 bg-green-500 rounded-full"
                        ></span>
                        User Admin Detected
                    </div>
                    <div
                        v-else
                        class="px-3 py-1 text-xs font-medium text-yellow-700 border border-yellow-200 rounded-md shadow-sm bg-yellow-50"
                    >
                        <span
                            class="inline-block w-2 h-2 mr-2 bg-yellow-500 rounded-full"
                        ></span>
                        User Requestor only
                    </div>
                </div>

                <div
                    v-if="
                        !isValidationSuccess &&
                        state.user &&
                        state.user.is_editing_allowed == 1
                    "
                    class="flex flex-row"
                >
                    <button
                        v-if="openDataEditorPanel"
                        class="ml-5 mt-5 px-4 py-2 text-sm font-semibold text-black transition bg-gray-200 rounded-md hover:bg-gray-300 active:scale-[0.98]"
                        @click="closeDataEditor"
                    >
                        Close Editor
                    </button>
                    <button
                        v-else
                        class="ml-5 mt-5 px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                        @click="proceedToDataEditor"
                    >
                        Modify Data
                    </button>
                </div>

                <div
                    v-if="
                        !req_isValidationSuccess &&
                        state.user &&
                        state.user.is_editing_allowed == 0
                    "
                    class="flex flex-row"
                >
                    <button
                        v-if="req_openDataEditorPanel"
                        class="ml-5 mt-5 px-4 py-2 text-sm font-semibold text-black transition bg-gray-200 rounded-md hover:bg-gray-300 active:scale-[0.98]"
                        @click="req_closeDataEditor"
                    >
                        Show Pending List
                    </button>
                    <button
                        v-else
                        class="ml-5 mt-5 px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                        @click="req_proceedToDataEditor"
                    >
                        Show Request Form
                    </button>
                </div>

                <div v-if="openDataEditorPanel" class="flex flex-row">
                    <div
                        class="w-full max-w-md p-6 mt-10 ml-10 bg-white border border-gray-200 rounded-lg shadow-sm"
                    >
                        <!-- Title -->
                        <h2
                            class="mb-6 text-sm font-semibold tracking-wide text-gray-800 uppercase"
                        >
                            Select Mass Production Data
                        </h2>

                        <!-- Furnace -->
                        <div class="mb-4">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Furnace
                            </label>
                            <select
                                v-model="selectedFurnace"
                                :disabled="isValidationSuccess"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
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
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Mass Production Name
                            </label>
                            <input
                                v-model="selectedMassProd"
                                type="text"
                                @input="
                                    selectedMassProd =
                                        selectedMassProd.toUpperCase()
                                "
                                :disabled="isValidationSuccess"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                                placeholder="Enter name..."
                            />
                        </div>

                        <!-- Layer -->
                        <div v-if="!htInfoEditingOnly" class="mb-6">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Layer (layer){{ selectedLayer }}
                            </label>

                            <select
                                v-model="selectedLayer"
                                :disabled="isValidationSuccess"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            >
                                <option value="" disabled>Select layer</option>

                                <option
                                    v-for="item in availableLayers"
                                    :key="item"
                                    :value="item"
                                >
                                    {{ item }}
                                </option>
                            </select>
                        </div>

                        <label
                            v-if="!isValidationSuccess"
                            class="flex float-right mb-6 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                v-model="htInfoEditingOnly"
                                class="mr-2 mt-0.5 disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            />
                            <span class="text-xs text-gray-500"
                                >Heat Treatment Info editing only</span
                            >
                        </label>

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
                            <div v-if="htInfoEditingOnly">
                                <p class="text-xs text-gray-500">
                                    Selected data:
                                </p>
                                <strong
                                    >{{ selectedFurnace }}
                                    {{ selectedMassProd }}</strong
                                >
                            </div>

                            <div v-else>
                                <p class="text-xs text-gray-500">
                                    Selected data:
                                </p>
                                Layer <strong>{{ selectedLayer }}</strong> of
                                <strong
                                    >{{ selectedFurnace }}
                                    {{ selectedMassProd }}</strong
                                >
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="isValidationSuccess"
                        class="w-full max-w-lg p-6 mt-10 ml-10 bg-white border border-gray-200 rounded-lg shadow-sm"
                    >
                        <!-- Header -->
                        <h2
                            class="mb-4 text-sm font-semibold tracking-wide text-gray-800 uppercase"
                        >
                            Fill in the fields
                        </h2>

                        <!-- Reason -->
                        <div class="mb-4">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Reason for data modification:
                            </label>

                            <textarea
                                v-model="userReason"
                                rows="3"
                                :disabled="isCorrectiveActionCompleted"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                                placeholder="Explain why the data needs to be modified..."
                            ></textarea>
                        </div>

                        <div class="mb-4">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Corrective Action (steps taken to ensure this
                                issue does not happen again):
                            </label>

                            <textarea
                                v-model="userCorrectiveAction"
                                rows="4"
                                :disabled="isCorrectiveActionCompleted"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                                placeholder="Describe preventive steps taken..."
                            ></textarea>
                        </div>

                        <label
                            class="flex items-start mb-4 text-xs text-gray-600"
                        >
                            <input
                                :disabled="isCorrectiveActionCompleted"
                                type="checkbox"
                                v-model="userVerified"
                                class="mr-2 mt-0.5 disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            />
                            <span v-if="state.user && state.user.access_type">
                                I,
                                <strong
                                    >{{ state.user.firstName }}
                                    {{ state.user.surname }}</strong
                                >, confirm that this modification has been
                                reviewed and approved by the responsible
                                supervisor/PIC. I understand that all changes
                                are logged and subject to audit review.
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

                    <div
                        v-if="
                            isValidationSuccess && isCorrectiveActionCompleted
                        "
                        class="w-full max-w-lg p-6 mt-10 ml-10 bg-white border border-gray-200 rounded-lg shadow-sm"
                    >
                        <!-- Header -->
                        <h2
                            class="mb-4 text-sm font-semibold tracking-wide text-gray-800 uppercase"
                        >
                            Choose an Action
                        </h2>

                        <div class="space-y-4">
                            <!-- DANGEROUS ACTION -->
                            <div
                                v-if="!htInfoEditingOnly"
                                class="p-4 border border-gray-300 rounded-md bg-gray-50"
                            >
                                <p class="mb-3 text-sm text-gray-800">
                                    <span class="font-semibold text-black"
                                        >Delete LAYER data (FULL RESET)</span
                                    ><br />

                                    <span class="text-xs text-gray-600">
                                        This action will permanently reset all
                                        mass production data for the selected
                                        layer, including coating and film
                                        pasting (if present). All linked
                                        additional lots within this layer group
                                        will also be removed. Initial process
                                        data will remain untouched.
                                    </span>

                                    <br />

                                    <span
                                        class="text-xs font-medium text-red-600"
                                    >
                                        Warning: This affects all connected lots
                                        under the same layer grouping. It is
                                        strongly recommended to review the
                                        Control Sheet page before proceeding
                                        after deletion.
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
                            <div
                                v-if="htInfoEditingOnly"
                                class="p-5 space-y-4 bg-white border border-gray-200 rounded-md"
                            >
                                <!-- Header -->
                                <div>
                                    <p
                                        class="text-sm font-semibold text-gray-800"
                                    >
                                        Modify Cycle No Only
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        This will update cycle number without
                                        affecting production data.
                                    </p>
                                </div>

                                <!-- Input -->
                                <div>
                                    <label
                                        class="block mb-1 text-xs font-medium text-gray-600"
                                    >
                                        New Cycle No
                                    </label>

                                    <input
                                        type="text"
                                        v-model="newCycleNo"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black"
                                    />
                                </div>

                                <!-- Action -->
                                <div class="flex justify-end">
                                    <button
                                        @click="proceedButtonCycleNo"
                                        class="px-4 py-2 text-sm font-medium text-gray-800 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 active:scale-[0.98]"
                                    >
                                        Proceed
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="showProceedDeleteLayerData"
                    class="fixed inset-0 z-50 flex items-center justify-center"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                        @click="showProceedDeleteLayerData = false"
                    ></div>

                    <!-- Panel -->
                    <div
                        class="relative z-10 w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-xl"
                    >
                        <!-- Title -->
                        <h2
                            class="mb-4 text-sm font-semibold text-gray-800 uppercase"
                        >
                            Confirm Action
                        </h2>

                        <!-- Message -->
                        <p class="mb-6 text-sm leading-relaxed text-gray-700">
                            You are about to delete ALL data related to
                            <span class="font-semibold"
                                >{{ selectedFurnace }}
                                {{ selectedMassProd }}</span
                            >
                            at Layer
                            <span class="font-semibold">{{
                                selectedLayer
                            }}</span
                            >. <br /><br />
                            This action cannot be undone. Proceed?
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3">
                            <button
                                @click="showProceedDeleteLayerData = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
                            >
                                Cancel
                            </button>

                            <button
                                @click="deleteLayerDataFinal"
                                class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                            >
                                Yes, Delete
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-if="showProceedUpdateCycleNo"
                    class="fixed inset-0 z-50 flex items-center justify-center"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                        @click="showProceedUpdateCycleNo = false"
                    ></div>

                    <!-- Panel -->
                    <div
                        class="relative z-10 w-full max-w-md p-6 space-y-6 bg-white border border-gray-200 rounded-lg shadow-xl"
                    >
                        <!-- WARNING HEADER -->
                        <div
                            class="p-3 border border-red-200 rounded-md bg-red-50"
                        >
                            <p class="text-sm font-bold text-red-700 uppercase">
                                ⚠ Critical Action - Review Carefully
                            </p>
                            <p class="mt-1 text-xs text-red-600">
                                You are about to modify production data. This
                                change is permanent and cannot be undone.
                            </p>
                        </div>

                        <!-- CURRENT VALUE -->
                        <div
                            class="p-3 border border-gray-200 rounded-md bg-gray-50"
                        >
                            <p class="text-xs text-gray-500">
                                CURRENT VALUE (will be replaced)
                            </p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ cycleNoToEdit }}
                            </p>
                        </div>

                        <!-- TARGET CONTEXT -->
                        <div class="text-sm text-gray-700">
                            <p class="text-xs text-gray-500">
                                Mass Production Target
                            </p>
                            <p class="font-semibold text-gray-900">
                                {{ selectedFurnace }} {{ selectedMassProd }}
                            </p>
                        </div>

                        <!-- NEW VALUE (HIGHLIGHTED STRONGLY) -->
                        <div
                            class="p-4 bg-white border-2 border-black rounded-md"
                        >
                            <p class="text-xs text-gray-500 uppercase">
                                NEW VALUE (will overwrite current)
                            </p>

                            <p class="mt-1 text-2xl font-extrabold text-black">
                                {{ newCycleNo }}
                            </p>

                            <p class="mt-2 text-xs font-medium text-red-600">
                                ▲ This value will replace the CURRENT cycle
                                number above
                            </p>
                        </div>

                        <!-- FINAL WARNING -->
                        <p class="text-xs font-medium text-red-500">
                            Proceed only if you have verified the values above.
                        </p>

                        <!-- ACTIONS -->
                        <div class="flex justify-end gap-3 pt-2">
                            <button
                                @click="showProceedUpdateCycleNo = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
                            >
                                Cancel
                            </button>

                            <button
                                @click="editCycleNo"
                                class="px-4 py-2 text-sm font-bold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                            >
                                CONFIRM UPDATE
                            </button>
                        </div>
                    </div>
                </div>

                <!--REQUESTOR SECTION -------- REQUESTOR SECTION -------- REQUESTOR SECTION -------- REQUESTOR SECTION -------- REQUESTOR SECTION --------  REQUESTOR SECTION -------- REQUESTOR SECTION ------- -->

                <div
                    v-if="
                        state.user &&
                        state.user.is_editing_allowed == 0 &&
                        req_openDataEditorPanel
                    "
                    class="flex flex-row"
                >
                    <div
                        class="w-full max-w-md p-6 mt-10 ml-10 bg-white border border-gray-200 rounded-lg shadow-sm"
                    >
                        <!-- Title -->
                        <h2
                            class="mb-6 text-sm font-semibold tracking-wide text-gray-800 uppercase"
                        >
                            Select Mass Production Data
                        </h2>

                        <!-- Furnace -->
                        <div class="mb-4">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Furnace
                            </label>
                            <select
                                v-model="selectedFurnace"
                                :disabled="req_isValidationSuccess"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
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
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Mass Production Name
                            </label>
                            <input
                                v-model="selectedMassProd"
                                type="text"
                                @input="
                                    selectedMassProd =
                                        selectedMassProd.toUpperCase()
                                "
                                :disabled="req_isValidationSuccess"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                                placeholder="Enter name..."
                            />
                        </div>

                        <!-- Layer -->
                        <div v-if="!req_htInfoEditingOnly" class="mb-6">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Layer
                            </label>

                            <select
                                v-model="selectedLayer"
                                :disabled="req_isValidationSuccess"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            >
                                <option value="" disabled>Select layer</option>

                                <option
                                    v-for="item in availableLayers"
                                    :key="item"
                                    :value="item"
                                >
                                    {{ item }}
                                </option>
                            </select>
                        </div>

                        <label
                            v-if="!req_isValidationSuccess"
                            class="flex float-right mb-6 cursor-pointer"
                        >
                            <input
                                type="checkbox"
                                v-model="req_htInfoEditingOnly"
                                class="mr-2 mt-0.5 disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            />
                            <span class="text-xs text-gray-500"
                                >Heat Treatment Info editing only</span
                            >
                        </label>

                        <div v-if="!req_isCorrectiveActionCompleted">
                            <!-- Action -->
                            <button
                                v-if="req_isValidationSuccess"
                                @click="req_cancelValidate"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition bg-red-500 rounded-md hover:bg-red-600 active:scale-[0.99]"
                            >
                                Cancel
                            </button>

                            <!-- Action -->
                            <button
                                v-else
                                @click="req_checkValidate"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.99]"
                            >
                                Check/Validate
                            </button>
                        </div>

                        <div v-if="req_isValidationSuccess" class="mt-10">
                            <div v-if="req_htInfoEditingOnly">
                                <p class="text-xs text-gray-500">
                                    Selected data:
                                </p>
                                <strong
                                    >{{ selectedFurnace }}
                                    {{ selectedMassProd }}</strong
                                >
                            </div>

                            <div v-else>
                                <p class="text-xs text-gray-500">
                                    Selected data:
                                </p>
                                Layer <strong>{{ selectedLayer }}</strong> of
                                <strong
                                    >{{ selectedFurnace }}
                                    {{ selectedMassProd }}</strong
                                >
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="req_isValidationSuccess"
                        class="w-full max-w-lg p-6 mt-10 ml-10 bg-white border border-gray-200 rounded-lg shadow-sm"
                    >
                        <!-- Header -->
                        <h2
                            class="mb-4 text-sm font-semibold tracking-wide text-gray-800 uppercase"
                        >
                            Fill in the fields
                        </h2>

                        <!-- Reason -->
                        <div class="mb-4">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Reason for data modification:
                            </label>

                            <textarea
                                v-model="userReason"
                                rows="3"
                                :disabled="req_isCorrectiveActionCompleted"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                                placeholder="Explain why the data needs to be modified..."
                            ></textarea>
                        </div>

                        <div class="mb-4">
                            <label
                                class="block mb-1 text-xs font-medium text-gray-600"
                            >
                                Corrective Action (steps taken to ensure this
                                issue does not happen again):
                            </label>

                            <textarea
                                v-model="userCorrectiveAction"
                                rows="4"
                                :disabled="req_isCorrectiveActionCompleted"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-1 focus:ring-black disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                                placeholder="Describe preventive steps taken..."
                            ></textarea>
                        </div>

                        <label
                            class="flex items-start mb-4 text-xs text-gray-600"
                        >
                            <input
                                :disabled="req_isCorrectiveActionCompleted"
                                type="checkbox"
                                v-model="userVerified"
                                class="mr-2 mt-0.5 disabled:opacity-100 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed"
                            />
                            <span v-if="state.user && state.user.access_type">
                                I,
                                <strong
                                    >{{ state.user.firstName }}
                                    {{ state.user.surname }}</strong
                                >, hereby submit this modification for review by
                                the responsible supervisor/PIC. I understand
                                that this request will be logged and subject to
                                audit and approval before any changes are
                                applied.
                            </span>
                        </label>
                        <div class="flex float-right">
                            <!-- Action -->
                            <button
                                v-if="req_isCorrectiveActionCompleted"
                                @click="req_cancelSubmitForm"
                                class="w-full px-4 py-2 text-sm font-semibold text-white transition bg-red-500 rounded-md hover:bg-red-600 active:scale-[0.99]"
                            >
                                Cancel
                            </button>
                            <button
                                v-else
                                class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                                @click="req_submitForm"
                            >
                                Next
                            </button>
                        </div>
                    </div>

                    <div
                        v-if="
                            req_isValidationSuccess &&
                            req_isCorrectiveActionCompleted
                        "
                        class="w-full max-w-lg p-6 mt-10 ml-10 bg-white border border-gray-200 rounded-lg shadow-sm"
                    >
                        <!-- Header -->
                        <h2
                            class="mb-4 text-sm font-semibold tracking-wide text-gray-800 uppercase"
                        >
                            Choose an Action
                        </h2>

                        <div class="space-y-4">
                            <!-- DANGEROUS ACTION -->
                            <div
                                v-if="!req_htInfoEditingOnly"
                                class="p-4 border border-gray-300 rounded-md bg-gray-50"
                            >
                                <p class="mb-3 text-sm text-gray-800">
                                    <span class="font-semibold text-black"
                                        >Delete LAYER data (FULL RESET)</span
                                    ><br />

                                    <span class="text-xs text-gray-600">
                                        This action will permanently reset all
                                        mass production data for the selected
                                        layer, including coating and film
                                        pasting (if present). All linked
                                        additional lots within this layer group
                                        will also be removed. Initial process
                                        data will remain untouched.
                                    </span>

                                    <br />

                                    <span
                                        class="text-xs font-medium text-red-600"
                                    >
                                        Warning: This affects all connected lots
                                        under the same layer grouping. It is
                                        strongly recommended to review the
                                        Control Sheet page before proceeding
                                        after deletion.
                                    </span>
                                </p>

                                <button
                                    class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                                    @click="req_proceedButton"
                                >
                                    Proceed
                                </button>
                            </div>

                            <!-- SAFE ACTION -->
                            <div
                                v-if="req_htInfoEditingOnly"
                                class="p-5 space-y-4 bg-white border border-gray-200 rounded-md"
                            >
                                <!-- Header -->
                                <div>
                                    <p
                                        class="text-sm font-semibold text-gray-800"
                                    >
                                        Modify Cycle No Only
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        This will update cycle number without
                                        affecting production data.
                                    </p>
                                </div>

                                <!-- Input -->
                                <div>
                                    <label
                                        class="block mb-1 text-xs font-medium text-gray-600"
                                    >
                                        New Cycle No
                                    </label>

                                    <input
                                        type="text"
                                        v-model="newCycleNo"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black"
                                    />
                                </div>

                                <!-- Action -->
                                <div class="flex justify-end">
                                    <button
                                        @click="req_proceedButtonCycleNo"
                                        class="px-4 py-2 text-sm font-medium text-gray-800 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 active:scale-[0.98]"
                                    >
                                        Proceed
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="req_showProceedDeleteLayerData"
                    class="fixed inset-0 z-50 flex items-center justify-center"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                        @click="req_showProceedDeleteLayerData = false"
                    ></div>

                    <!-- Panel -->
                    <div
                        class="relative z-10 w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-xl"
                    >
                        <!-- Title -->
                        <h2
                            class="mb-4 text-sm font-semibold text-gray-800 uppercase"
                        >
                            Submit Deletion Request
                        </h2>

                        <!-- Message -->
                        <p class="mb-6 text-sm leading-relaxed text-gray-700">
                            You are about to submit a request to remove all data
                            related to
                            <span class="font-semibold"
                                >{{ selectedFurnace }}
                                {{ selectedMassProd }}</span
                            >
                            at Layer
                            <span class="font-semibold">{{
                                selectedLayer
                            }}</span
                            >. <br /><br />
                            This request will be reviewed by the responsible
                            supervisor/PIC. No data will be deleted unless the
                            request is approved.
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3">
                            <button
                                @click="req_showProceedDeleteLayerData = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
                            >
                                Cancel
                            </button>

                            <button
                                @click="req_deleteLayerDataFinal"
                                class="px-4 py-2 text-sm font-semibold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                            >
                                Submit Request
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-if="req_showProceedUpdateCycleNo"
                    class="fixed inset-0 z-50 flex items-center justify-center"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                        @click="req_showProceedUpdateCycleNo = false"
                    ></div>

                    <!-- Panel -->
                    <div
                        class="relative z-10 w-full max-w-md p-6 space-y-6 bg-white border border-gray-200 rounded-lg shadow-xl"
                    >
                        <!-- HEADER -->
                        <div
                            class="p-3 border rounded-md border-amber-200 bg-amber-50"
                        >
                            <p
                                class="text-sm font-bold uppercase text-amber-700"
                            >
                                Submit Update Request
                            </p>
                            <p class="mt-1 text-xs text-amber-700">
                                This will send a request to modify the cycle
                                number. No changes will be applied until
                                approved.
                            </p>
                        </div>

                        <!-- CURRENT VALUE -->
                        <div
                            class="p-3 border border-gray-200 rounded-md bg-gray-50"
                        >
                            <p class="text-xs text-gray-500">
                                Current Cycle No
                            </p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ cycleNoToEdit }}
                            </p>
                        </div>

                        <!-- TARGET CONTEXT -->
                        <div class="text-sm text-gray-700">
                            <p class="text-xs text-gray-500">Mass Production</p>
                            <p class="font-semibold text-gray-900">
                                {{ selectedFurnace }} {{ selectedMassProd }}
                            </p>
                        </div>

                        <!-- REQUESTED VALUE -->
                        <div
                            class="p-4 bg-white border-2 border-black rounded-md"
                        >
                            <p class="text-xs text-gray-500 uppercase">
                                Requested New Cycle No
                            </p>

                            <p class="mt-1 text-2xl font-extrabold text-black">
                                {{ newCycleNo }}
                            </p>

                            <p class="mt-2 text-xs font-medium text-gray-600">
                                This value will replace the current cycle number
                                upon approval.
                            </p>
                        </div>

                        <!-- FINAL NOTE -->
                        <p class="text-xs text-gray-600">
                            Please review the details carefully before
                            submitting the request.
                        </p>

                        <!-- ACTIONS -->
                        <div class="flex justify-end gap-3 pt-2">
                            <button
                                @click="req_showProceedUpdateCycleNo = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200"
                            >
                                Cancel
                            </button>

                            <button
                                @click="req_editCycleNo()"
                                class="px-4 py-2 text-sm font-bold text-white transition bg-black rounded-md hover:bg-gray-800 active:scale-[0.98]"
                            >
                                Submit Request
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-if="!openDataEditorPanel && !req_openDataEditorPanel"
                    class="flex flex-row gap-4 mt-10"
                >
                    <!-- DELETE REQUESTS -->
                    <div class="w-1/2 ml-5">
                        <h2
                            class="mb-1 text-[11px] font-semibold text-gray-700"
                        >
                            Pending List (Delete Request)
                        </h2>

                        <div
                            class="overflow-hidden border border-gray-300 rounded-md"
                        >
                            <table
                                class="w-full text-[11px] text-left text-gray-700"
                            >
                                <thead
                                    class="bg-gray-100 border-b text-[10px] uppercase tracking-wide"
                                >
                                    <tr>
                                        <th class="px-2 py-1">Furnace</th>
                                        <th class="px-2 py-1">MP</th>
                                        <th class="px-2 py-1">Reason</th>
                                        <th class="px-2 py-1">Action</th>
                                        <th class="px-2 py-1">Request By</th>
                                        <th class="px-2 py-1">Status</th>
                                        <th class="px-2 py-1">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="item in paginatedDeleteRequests"
                                        :key="item.id"
                                        class="border-b hover:bg-gray-50"
                                    >
                                        <td class="px-2 py-1">
                                            {{ item.furnace }}
                                        </td>
                                        <td class="px-2 py-1">
                                            {{ item.mass_prod }}
                                        </td>
                                        <td
                                            class="px-2 py-1 truncate max-w-[120px]"
                                        >
                                            {{ item.reason }}
                                        </td>
                                        <td
                                            class="px-2 py-1 truncate max-w-[120px]"
                                        >
                                            {{ item.corrective_action }}
                                        </td>
                                        <td class="px-2 py-1">
                                            {{ item.request_by }}
                                        </td>
                                        <td class="px-2 py-1 font-medium">
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px]"
                                                :class="{
                                                    'bg-amber-100 text-amber-800':
                                                        item.status?.trim() ===
                                                        'Pending',

                                                    'bg-green-100 text-green-800':
                                                        item.status?.trim() ===
                                                        'Approved',

                                                    'bg-red-100 text-red-800':
                                                        item.status?.trim() ===
                                                        'Rejected',
                                                }"
                                            >
                                                {{ item.status }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-1 space-x-2">
                                            <button
                                                @click="
                                                    openRequestModal(
                                                        item,
                                                        'delete',
                                                    )
                                                "
                                                class="text-[10px] px-2 py-1 bg-black text-white rounded hover:bg-gray-800"
                                            >
                                                View
                                            </button>
                                            <button
                                                v-if="canApprove(item)"
                                                @click="
                                                    openApproveModal(
                                                        item,
                                                        'delete',
                                                    )
                                                "
                                                class="text-[10px] px-2 py-1 bg-white text-black rounded hover:bg-gray-200 border border-gray-400"
                                            >
                                                Approved
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="!deleteRequestLists.length">
                                        <td
                                            colspan="6"
                                            class="px-2 py-3 text-center text-gray-400"
                                        >
                                            No pending requests
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="flex items-center justify-between px-2 py-2 text-[10px] text-gray-600"
                        >
                            <button
                                @click="deleteCurrentPage--"
                                :disabled="deleteCurrentPage === 1"
                                class="px-2 py-1 border rounded disabled:opacity-30"
                            >
                                Prev
                            </button>

                            <span>
                                Page {{ deleteCurrentPage }} /
                                {{ deleteTotalPages }}
                            </span>

                            <button
                                @click="deleteCurrentPage++"
                                :disabled="
                                    deleteCurrentPage === deleteTotalPages
                                "
                                class="px-2 py-1 border rounded disabled:opacity-30"
                            >
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- EDIT REQUESTS -->
                    <div class="w-1/2 mr-5">
                        <h2
                            class="mb-1 text-[11px] font-semibold text-gray-700"
                        >
                            Pending List (Edit Request)
                        </h2>

                        <div
                            class="overflow-hidden border border-gray-300 rounded-md"
                        >
                            <table
                                class="w-full text-[11px] text-left text-gray-700"
                            >
                                <thead
                                    class="bg-gray-100 border-b text-[10px] uppercase tracking-wide"
                                >
                                    <tr>
                                        <th class="px-2 py-1">Furnace</th>
                                        <th class="px-2 py-1">MP</th>
                                        <th class="px-2 py-1">Reason</th>
                                        <th class="px-2 py-1">Action</th>
                                        <th class="px-2 py-1">Current</th>
                                        <th class="px-2 py-1">New</th>
                                        <th class="px-2 py-1">Request By</th>
                                        <th class="px-2 py-1">Status</th>
                                        <th class="px-2 py-1">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="item in paginatedEditRequests"
                                        :key="item.id"
                                        class="border-b hover:bg-gray-50"
                                    >
                                        <td class="px-2 py-1">
                                            {{ item.furnace }}
                                        </td>
                                        <td class="px-2 py-1">
                                            {{ item.mass_prod }}
                                        </td>
                                        <td
                                            class="px-2 py-1 truncate max-w-[120px]"
                                        >
                                            {{ item.reason }}
                                        </td>
                                        <td
                                            class="px-2 py-1 truncate max-w-[120px]"
                                        >
                                            {{ item.corrective_action }}
                                        </td>
                                        <td class="px-2 py-1">
                                            {{ item.current_data }}
                                        </td>
                                        <td class="px-2 py-1">
                                            {{ item.new_data }}
                                        </td>
                                        <td class="px-2 py-1">
                                            {{ item.request_by }}
                                        </td>
                                        <td class="px-2 py-1 font-medium">
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px]"
                                                :class="{
                                                    'bg-amber-100 text-amber-800':
                                                        item.status?.trim() ===
                                                        'Pending',

                                                    'bg-green-100 text-green-800':
                                                        item.status?.trim() ===
                                                        'Approved',

                                                    'bg-red-100 text-red-800':
                                                        item.status?.trim() ===
                                                        'Rejected',
                                                }"
                                            >
                                                {{ item.status }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-1 space-x-2">
                                            <button
                                                @click="
                                                    openRequestModal(
                                                        item,
                                                        'edit',
                                                    )
                                                "
                                                class="text-[10px] px-2 py-1 bg-black text-white rounded hover:bg-gray-800"
                                            >
                                                View
                                            </button>
                                            <button
                                                v-if="canApprove(item)"
                                                @click="
                                                    openApproveModal(
                                                        item,
                                                        'edit',
                                                    )
                                                "
                                                class="text-[10px] px-2 py-1 bg-white text-black rounded hover:bg-gray-200 border border-gray-400"
                                            >
                                                Approve
                                            </button>
                                            <button
                                                v-if="canApprove(item)"
                                                @click="
                                                    openRejectModal(
                                                        item,
                                                        'edit',
                                                    )
                                                "
                                                class="text-[10px] px-2 py-1 bg-white text-red-600 rounded hover:bg-gray-200 border border-red-300"
                                            >
                                                Reject
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="!editRequestLists.length">
                                        <td
                                            colspan="8"
                                            class="px-2 py-3 text-center text-gray-400"
                                        >
                                            No pending requests
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="flex items-center justify-between px-2 py-2 text-[10px] text-gray-600"
                        >
                            <button
                                @click="editCurrentPage--"
                                :disabled="editCurrentPage === 1"
                                class="px-2 py-1 border rounded disabled:opacity-30"
                            >
                                Prev
                            </button>

                            <span>
                                Page {{ editCurrentPage }} /
                                {{ editTotalPages }}
                            </span>

                            <button
                                @click="editCurrentPage++"
                                :disabled="editCurrentPage === editTotalPages"
                                class="px-2 py-1 border rounded disabled:opacity-30"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-if="showRequestModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                >
                    <div
                        class="w-[500px] bg-white rounded-md shadow-lg border border-gray-300"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-4 py-2 border-b"
                        >
                            <h3 class="text-sm font-semibold text-gray-800">
                                Request Details
                            </h3>
                            <button
                                @click="closeRequestModal"
                                class="text-sm text-gray-500 hover:text-black"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="p-4 space-y-2 text-xs text-gray-700">
                            <div>
                                <span class="font-semibold">Furnace:</span>
                                {{ selectedRequest.furnace }}
                            </div>
                            <div>
                                <span class="font-semibold"
                                    >Mass Production:</span
                                >
                                {{ selectedRequest.mass_prod }}
                            </div>

                            <div v-if="selectedRequest.current_data">
                                <span class="font-semibold">Current Data:</span>
                                {{ selectedRequest.current_data }}
                            </div>

                            <div v-if="selectedRequest.layer">
                                <span class="font-semibold">Target Layer:</span>
                                {{ selectedRequest.layer }}
                            </div>

                            <div v-if="selectedRequest.new_data">
                                <span class="font-semibold">New Value:</span>
                                {{ selectedRequest.new_data }}
                            </div>

                            <div>
                                <span class="font-semibold">Reason:</span>
                                <div
                                    class="mt-1 p-2 bg-gray-50 border rounded text-[11px]"
                                >
                                    {{ selectedRequest.reason }}
                                </div>
                            </div>

                            <div>
                                <span class="font-semibold"
                                    >Corrective Action:</span
                                >
                                <div
                                    class="mt-1 p-2 bg-gray-50 border rounded text-[11px]"
                                >
                                    {{ selectedRequest.corrective_action }}
                                </div>
                            </div>

                            <div
                                class="flex justify-between pt-2 text-[11px] text-gray-600"
                            >
                                <span
                                    ><strong>Requested By:</strong>
                                    {{ selectedRequest.request_by }}</span
                                >
                                <span
                                    ><strong>Status:</strong>
                                    {{ selectedRequest.status }}</span
                                >
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div class="flex justify-end px-4 py-2 border-t">
                            <button
                                @click="closeRequestModal"
                                class="px-3 py-1 text-xs text-white bg-black rounded hover:bg-gray-800"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-if="showApproveModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                >
                    <div
                        class="w-[380px] bg-white rounded-md border border-gray-300 shadow-lg"
                    >
                        <!-- HEADER -->
                        <div class="px-4 py-2 border-b">
                            <h3 class="text-sm font-semibold text-gray-800">
                                Confirm Approval
                            </h3>
                        </div>

                        <!-- BODY -->
                        <div class="p-4 space-y-2 text-xs text-gray-700">
                            <p>
                                You are about to approve this
                                <span
                                    v-if="selectedApproveItem?.layer"
                                    class="text-red-600 semi-bold"
                                    >delete</span
                                >
                                <span v-else class="text-red-600 semi-bold"
                                    >edit</span
                                >
                                request.
                            </p>

                            <div
                                class="p-2 bg-gray-50 border rounded text-[11px] space-y-1"
                            >
                                <div>
                                    <strong>Furnace:</strong>
                                    {{ selectedApproveItem?.furnace }}
                                </div>
                                <div>
                                    <strong>MP:</strong>
                                    {{ selectedApproveItem?.mass_prod }}
                                </div>
                                <div v-if="selectedApproveItem?.layer">
                                    <strong>Target Layer:</strong>
                                    {{ selectedApproveItem.layer }}
                                </div>
                                <div v-if="selectedApproveItem?.current_data">
                                    <strong>Current:</strong>
                                    {{ selectedApproveItem.current_data }}
                                </div>
                                <div v-if="selectedApproveItem?.new_data">
                                    <strong>New:</strong>
                                    {{ selectedApproveItem.new_data }}
                                </div>
                            </div>

                            <p class="text-[11px] text-gray-500">
                                This action cannot be undone.
                            </p>
                        </div>

                        <!-- FOOTER -->
                        <div class="flex justify-end gap-2 px-4 py-2 border-t">
                            <button
                                @click="closeApproveModal"
                                class="px-3 py-1 text-xs border border-gray-400 rounded hover:bg-gray-100"
                            >
                                Cancel
                            </button>

                            <button
                                @click="confirmApprove"
                                class="px-3 py-1 text-xs text-white bg-black rounded hover:bg-gray-800"
                            >
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-if="showRejectModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                >
                    <div
                        class="w-[380px] bg-white rounded-md border border-gray-300 shadow-lg"
                    >
                        <!-- HEADER -->
                        <div class="px-4 py-2 border-b">
                            <h3 class="text-sm font-semibold text-gray-800">
                                Confirm Rejection
                            </h3>
                        </div>

                        <!-- BODY -->
                        <div class="p-4 space-y-2 text-xs text-gray-700">
                            <p>
                                You are about to reject this
                                <span
                                    v-if="selectedRejectItem?.layer"
                                    class="text-red-600 semi-bold"
                                    >delete</span
                                >
                                <span v-else class="text-red-600 semi-bold"
                                    >edit</span
                                >
                                request.
                            </p>

                            <div
                                class="p-2 bg-gray-50 border rounded text-[11px] space-y-1"
                            >
                                <div>
                                    <strong>Furnace:</strong>
                                    {{ selectedRejectItem?.furnace }}
                                </div>
                                <div>
                                    <strong>MP:</strong>
                                    {{ selectedRejectItem?.mass_prod }}
                                </div>
                                <div v-if="selectedRejectItem?.layer">
                                    <strong>Target Layer:</strong>
                                    {{ selectedRejectItem.layer }}
                                </div>
                                <div v-if="selectedRejectItem?.current_data">
                                    <strong>Current:</strong>
                                    {{ selectedRejectItem.current_data }}
                                </div>
                                <div v-if="selectedRejectItem?.new_data">
                                    <strong>New:</strong>
                                    {{ selectedRejectItem.new_data }}
                                </div>
                            </div>

                            <p class="text-[11px] text-gray-500">
                                This action cannot be undone.
                            </p>
                        </div>

                        <!-- FOOTER -->
                        <div class="flex justify-end gap-2 px-4 py-2 border-t">
                            <button
                                @click="closeRejectModal"
                                class="px-3 py-1 text-xs border border-gray-400 rounded hover:bg-gray-100"
                            >
                                Cancel
                            </button>

                            <button
                                @click="confirmReject"
                                class="px-3 py-1 text-xs text-white bg-black rounded hover:bg-gray-800"
                            >
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from "@/Layouts/FrontendLayout.vue";
import { onMounted, ref, computed, watch, nextTick, useSSRContext } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import { useAuth } from "@/Composables/useAuth.js";
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
                console.error(
                    "User data failed to load in time. Redirecting...",
                );
                router.visit("/"); // Redirect if user never loads
                return false;
            }
            await new Promise((resolve) => setTimeout(resolve, 50));
        }

        if (!state.isAuthenticated) {
            console.warn("User is not authenticated. Redirecting...");
            router.visit("/");
            return false;
        }

        console.log(
            "USER AUTHENTICATED!",
            `${state.user.firstName} ${state.user.surname}`,
        );
        return true;
    } catch (error) {
        console.error("Error checking authentication:", error);
        router.visit("/");
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

const showProceedDeleteLayerData = ref(false);
const showProceedUpdateCycleNo = ref(false);
const isValidationSuccess = ref(false);
const isCorrectiveActionCompleted = ref(false);
const htInfoEditingOnly = ref(false);
const openDataEditorPanel = ref(false);
const req_openDataEditorPanel = ref(false);

const req_showProceedDeleteLayerData = ref(false);
const req_showProceedUpdateCycleNo = ref(false);
const req_isValidationSuccess = ref(false);
const req_isCorrectiveActionCompleted = ref(false);
const req_htInfoEditingOnly = ref(false);

const userReason = ref();
const userCorrectiveAction = ref();
const userVerified = ref(false);

const selectedFurnace = ref();
const selectedMassProd = ref();
const selectedLayer = ref(null);
const availableLayers = ref([]);
const cycleNoToEdit = ref();
const newCycleNo = ref();

const deleteRequestLists = ref([]);
const editRequestLists = ref([]);

const fetchedModel = ref();
const fetchedLotNo = ref();

const furnace_names = ref([]);
const layers = ref(["1", "2", "3", "4", "5", "6", "7", "8", "9", "9.5"]);

const selectedRequest = ref(null);
const showRequestModal = ref(false);

const deleteCurrentPage = ref(1);
const deletePerPage = 10;
const editCurrentPage = ref(1);
const editPerPage = 10;

const sortedEditRequests = computed(() => {
    return [...editRequestLists.value].sort((a, b) => {
        if (a.status === "Pending" && b.status !== "Pending") return -1;
        if (a.status !== "Pending" && b.status === "Pending") return 1;

        return new Date(b.created_at) - new Date(a.created_at);
    });
});

const sortedDeleteRequests = computed(() => {
    return [...deleteRequestLists.value].sort((a, b) => {
        if (a.status === "Pending" && b.status !== "Pending") return -1;
        if (a.status !== "Pending" && b.status === "Pending") return 1;

        return new Date(b.created_at) - new Date(a.created_at);
    });
});

const paginatedEditRequests = computed(() => {
    const start = (editCurrentPage.value - 1) * editPerPage;
    return sortedEditRequests.value.slice(start, start + editPerPage);
});

const paginatedDeleteRequests = computed(() => {
    const start = (deleteCurrentPage.value - 1) * deletePerPage;
    return sortedDeleteRequests.value.slice(start, start + deletePerPage);
});

const editTotalPages = computed(() => {
    return Math.ceil(sortedEditRequests.value.length / editPerPage);
});

const deleteTotalPages = computed(() => {
    return Math.ceil(sortedDeleteRequests.value.length / deletePerPage);
});

const openRequestModal = (item) => {
    selectedRequest.value = item;
    showRequestModal.value = true;
};

const closeRequestModal = () => {
    showRequestModal.value = false;
    selectedRequest.value = null;
};

const showApproveModal = ref(false);
const selectedApproveItem = ref(null);
const approveType = ref(null);

const showRejectModal = ref(false);
const selectedRejectItem = ref(null);
const rejectType = ref(null);

const openApproveModal = (item, type) => {
    selectedApproveItem.value = item;
    approveType.value = type;
    showApproveModal.value = true;
};

const closeApproveModal = () => {
    showApproveModal.value = false;
    selectedApproveItem.value = null;
    approveType.value = null;
};

const openRejectModal = (item, type) => {
    selectedRejectItem.value = item;
    rejectType.value = type;
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    selectedRejectItem.value = null;
    rejectType.value = null;
};

const confirmApprove = async () => {
    if (!selectedApproveItem.value) return;

    try {
        if (approveType.value === "delete") {
            selectedMassProd.value = selectedApproveItem.value.mass_prod;
            selectedFurnace.value = selectedApproveItem.value.furnace;
            selectedLayer.value = selectedApproveItem.value.layer;

            await deleteLayerDataFinal(selectedApproveItem.value.id);
        }

        if (approveType.value === "edit") {
            selectedMassProd.value = selectedApproveItem.value.mass_prod;
            selectedFurnace.value = selectedApproveItem.value.furnace;
            newCycleNo.value = selectedApproveItem.value.new_data;

            await editCycleNo(selectedApproveItem.value.id);
        }

        closeApproveModal();
    } catch (err) {
        console.error("Approval process failed:", err);
    }
};

const confirmReject = async () => {
    if (!selectedRejectItem.value) return;

    try {
        if (rejectType.value === "delete") {
            await updateDeletePendingList(
                selectedRejectItem.value.id,
                "Rejected",
            );
            resetPage();
            await getDeleteRequests();
            await getEditRequests();
        }

        if (rejectType.value === "edit") {
            await updateEditPendingList(
                selectedRejectItem.value.id,
                "Rejected",
            );
            resetPage();
            await getDeleteRequests();
            await getEditRequests();
        }

        closeRejectModal();
    } catch (err) {
        console.error("Approval process failed:", err);
    }
};

const canApprove = (item) => {
    if (!state.user) return false;

    const isAllowed = Number(state.user.is_editing_allowed) === 1;
    const isPending = item.status === "Pending";

    return isAllowed && isPending;
};

//Watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers

watch([selectedFurnace, selectedMassProd], ([furnace, massProd]) => {
    if (furnace && massProd) {
        nextTick(() => {
            checkAvailableLayers();
        });
    }
});

watch(sortedDeleteRequests, () => {
    if (deleteCurrentPage.value > deleteTotalPages.value) {
        deleteCurrentPage.value = deleteTotalPages.value || 1;
    }
});

//Watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers -------------- watchers

const checkAvailableLayers = async () => {
    try {
        const response = await axios.get("/api/check-validate-data-editor", {
            params: {
                mass_prod: selectedMassProd.value,
                furnace: selectedFurnace.value,
            },
        });

        availableLayers.value = response.data.available_layers ?? [];
        console.log("Available layers: ", availableLayers.value);
    } catch (error) {
        console.error("Failed to check avaialable layers", error);
    }
};

const checkValidate = async () => {
    try {
        if (
            !selectedFurnace.value ||
            !selectedMassProd.value ||
            (!htInfoEditingOnly.value && !selectedLayer.value)
        ) {
            toast.warning("Please make sure all fields are selected");
            return;
        }

        const response = await axios.get("/api/check-validate-data-editor", {
            params: {
                mass_prod: selectedMassProd.value,
                furnace: selectedFurnace.value,
                ...(!htInfoEditingOnly.value && {
                    layer: String(selectedLayer.value),
                }), //Include layer only if htInfoEditingOnly is true
            },
        });

        console.log("Mass Prod: ", selectedMassProd.value);
        console.log("Furnace: ", selectedFurnace.value);
        console.log("Selected Layer: ", selectedLayer.value);
        console.log("htInfoEditingOnly: ", htInfoEditingOnly.value);
        console.log("Response: ", response.data);

        isValidationSuccess.value = response.data.mass_prod_exists;

        if (isValidationSuccess.value) {
            toast.success("Validation Success");
        } else {
            toast.error("The selected data does not exists in the database");
        }
    } catch (error) {
        console.error(
            "Validation check failed:",
            error.response?.data || error,
        );
    }
};

const req_checkValidate = async () => {
    try {
        if (
            !selectedFurnace.value ||
            !selectedMassProd.value ||
            (!req_htInfoEditingOnly.value && !selectedLayer.value)
        ) {
            toast.warning("Please make sure all fields are selected");
            return;
        }

        const response = await axios.get("/api/check-validate-data-editor", {
            params: {
                mass_prod: selectedMassProd.value,
                furnace: selectedFurnace.value,
                ...(!req_htInfoEditingOnly.value && {
                    layer: String(selectedLayer.value),
                }), //Include layer only if htInfoEditingOnly is true
            },
        });

        console.log("Mass Prod: ", selectedMassProd.value);
        console.log("Furnace: ", selectedFurnace.value);
        console.log("Selected Layer: ", selectedLayer.value);
        console.log("htInfoEditingOnly: ", htInfoEditingOnly.value);
        console.log("Response: ", response.data);

        req_isValidationSuccess.value = response.data.mass_prod_exists;

        if (req_isValidationSuccess.value) {
            toast.success("Validation Success");
        } else {
            toast.error("The selected data does not exists in the database");
        }
    } catch (error) {
        console.error(
            "Validation check failed:",
            error.response?.data || error,
        );
    }
};

const cancelValidate = async () => {
    isValidationSuccess.value = false;
};

const req_cancelValidate = async () => {
    req_isValidationSuccess.value = false;
};

const proceedToDataEditor = () => {
    openDataEditorPanel.value = true;
};

const req_proceedToDataEditor = () => {
    req_openDataEditorPanel.value = true;
};

const closeDataEditor = () => {
    openDataEditorPanel.value = false;
};

const req_closeDataEditor = () => {
    req_openDataEditorPanel.value = false;
};

const cancelSubmitForm = async () => {
    isCorrectiveActionCompleted.value = false;
};

const req_cancelSubmitForm = async () => {
    req_isCorrectiveActionCompleted.value = false;
};

const proceedButton = async () => {
    if (
        !selectedFurnace.value &&
        !selectedLayer.value &&
        !selectedMassProd.value
    ) {
        toast.warning("Fill all fields before proceeding.");
        return;
    }

    showProceedDeleteLayerData.value = true;
};

const req_proceedButton = async () => {
    if (
        !selectedFurnace.value &&
        !selectedLayer.value &&
        !selectedMassProd.value
    ) {
        toast.warning("Fill all fields before proceeding.");
        return;
    }

    req_showProceedDeleteLayerData.value = true;
};

const proceedButtonCycleNo = async () => {
    try {
        const response = await axios.get(
            `/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}`,
        );
        //console.log('Mass Production data result: ', response.data);
        const mp = response.data;
        cycleNoToEdit.value = mp.cycle_no;
        showProceedUpdateCycleNo.value = true;
    } catch (error) {
        console.error(
            "Failed to do initial checking for heat treatment information cycle no due to ",
            error,
        );
    }
};

const req_proceedButtonCycleNo = async () => {
    try {
        const response = await axios.get(
            `/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}`,
        );
        //console.log('Mass Production data result: ', response.data);
        const mp = response.data;
        cycleNoToEdit.value = mp.cycle_no;
        req_showProceedUpdateCycleNo.value = true;
    } catch (error) {
        console.error(
            "Failed to do initial checking for heat treatment information cycle no due to ",
            error,
        );
    }
};

const submitForm = async () => {
    if (
        !userReason.value ||
        !userCorrectiveAction.value ||
        !userVerified.value
    ) {
        toast.warning("Fill all fields before proceeding.");
        return;
    }

    isCorrectiveActionCompleted.value = true;
};

const req_submitForm = async () => {
    if (
        !userReason.value ||
        !userCorrectiveAction.value ||
        !userVerified.value
    ) {
        toast.warning("Fill all fields before proceeding.");
        return;
    }

    req_isCorrectiveActionCompleted.value = true;
};

const deleteLayerDataFinal = async (requestId) => {
    try {
        const response = await axios.post(
            "/api/mass-production/delete-layer-full",
            {
                massprod: selectedMassProd.value,
                furnace: selectedFurnace.value,
                layer: selectedLayer.value,
            },
        );

        if (response.data.success) {
            const total = response.data.total_deleted ?? 0;

            await saveLog(
                `has fully removed Layer ${selectedLayer.value} data from ${selectedMassProd.value} ${selectedFurnace.value}. Total deleted: ${total}`,
            );

            toast.success(
                response.data.message || "Layer deleted successfully.",
            );

            await updateDeletePendingList(requestId, "Approved");

            resetPage();
            await getDeleteRequests();
            await getEditRequests();
        } else {
            toast.warning(response.data.message || "No matching data found.");
        }
    } catch (error) {
        console.error("Failed to delete layer data", error);

        toast.error("Failed to delete layer data.");

        await userErrorLogging(
            {
                message: error.message,
                code: error.code ?? null,
                response: error.response?.data ?? null,
            },
            "deleteLayerFull",
            "Failed to delete layer data.",
        );
    }
};

const req_deleteLayerDataFinal = async () => {
    try {
        const response = await axios.post("/api/layer-delete-request", {
            mass_prod: selectedMassProd.value,
            furnace: selectedFurnace.value,
            layer: selectedLayer.value,
            reason: userReason.value,
            corrective_action: userCorrectiveAction.value,
            request_by: state.user.firstName + " " + state.user.surname,
            status: "Pending",
        });
        toast.success("Delete layer request sent successfully");
        console.log("Request sent successfully", response.data);
        req_showProceedDeleteLayerData.value = false;
        resetPage();
        await getDeleteRequests();
        await getEditRequests();
    } catch (error) {
        console.error(
            "Failed to request modification for cycle no due to:",
            error,
        );
        toast.error("Modify Request failed");
    }
};

const editCycleNo = async (requestId) => {
    try {
        const response = await axios.patch(
            `/api/mass-production/${selectedFurnace.value}/${selectedMassProd.value}`,
            {
                cycle_no: newCycleNo.value,
            },
        );

        toast.success("Cycle No updated successfully");
        console.log("Cycle No edited successfully: ", response.data);
        await updateEditPendingList(requestId, "Approved");
        await getDeleteRequests();
        await getEditRequests();
    } catch (error) {
        console.error("Failed to modify/update cycle no due to:", error);
        toast.error("Update failed");
    }
};

const req_editCycleNo = async () => {
    try {
        const response = await axios.post("/api/field-edit-request", {
            mass_prod: selectedMassProd.value,
            furnace: selectedFurnace.value,
            current_data: cycleNoToEdit.value,
            new_data: newCycleNo.value,
            target_column: "cycle_no",
            reason: userReason.value,
            corrective_action: userCorrectiveAction.value,
            request_by: state.user.firstName + " " + state.user.surname,
            status: "Pending",
        });

        toast.success("Cycle No modification request sent successfully");
        console.log("Request sent successfully", response.data);
        req_showProceedUpdateCycleNo.value = false;
        resetPage();
        await getDeleteRequests();
        await getEditRequests();
    } catch (error) {
        console.error(
            "Failed to request modification for cycle no due to:",
            error,
        );
        toast.error("Modify Request failed");
    }
};

const updateEditPendingList = async (id, statusResult) => {
    try {
        await axios.patch(`/api/field-edit-request/${id}`, {
            status: statusResult,
        });

        toast.success("Request status updated");
    } catch (error) {
        console.error("Failed to update pending list status due to", error);
        toast.error("Failed to update request status");
    }
};

const updateDeletePendingList = async (id, statusResult) => {
    try {
        await axios.patch(`/api/layer-delete-request/${id}`, {
            status: statusResult,
        });

        toast.success("Request status updated");
    } catch (error) {
        console.error("Failed to update pending list status due to", error);
        toast.error("Failed to update request status");
    }
};

const saveLog = async (log) => {
    try {
        const response = await axios.post("/api/data_editor_logs", {
            mass_prod: selectedMassProd.value,
            furnace: selectedFurnace.value,
            user_reason: userReason.value,
            user_corrective_action: userCorrectiveAction.value,
            user_confirmation: userVerified.value,
            log_remarks: log,
        });

        //toast.success('Data logged successfully');
        console.log("Data saved successfully", response.data);
    } catch (error) {
        console.error("Failed to save data editor logs", error);
        toast.error("Failed to save editor logs");
    }
};

const resetPage = () => {
    showProceedDeleteLayerData.value = false;
    isValidationSuccess.value = false;
    isCorrectiveActionCompleted.value = false;
    userReason.value = null;
    userCorrectiveAction.value = null;
    userVerified.value = null;
    req_showProceedDeleteLayerData.value = false;
    req_isValidationSuccess.value = false;
    req_isCorrectiveActionCompleted.value = false;
    req_showProceedUpdateCycleNo.value = false;
};

const getDeleteRequests = async () => {
    try {
        const response = await axios.get("/api/layer-delete-request");
        console.log("Delete Request lists: ", response.data);
        deleteRequestLists.value = response.data || [];
    } catch (error) {
        console.error("Failed to fetch delete requests data due to ", error);
        toast.error("Failed to get Delete Request Lists");
    }
};

const getEditRequests = async () => {
    try {
        const response = await axios.get("/api/field-edit-request");
        console.log("Edit Request lists: ", response.data);
        editRequestLists.value = response.data || [];
    } catch (error) {
        console.error("Failed to fetch edit requests data due to ", error);
        toast.error("Failed to get Edit Request Lists");
    }
};

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

//SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- SESSION SECTION ---- ]

useSessionStorage("selectedFurnace", selectedFurnace);
useSessionStorage("selectedMassProd", selectedMassProd);
useSessionStorage("userReason", userReason);
useSessionStorage("userCorrectiveAction", userCorrectiveAction);
useSessionStorage("userVerified", userVerified);

onMounted(async () => {
    const isAuthenticated = await checkAuthentication();
    if (!isAuthenticated) {
        return; // Stop execution if not authenticated
    }

    await getFurnaceLists();
    await getDeleteRequests();
    await getEditRequests();
});
</script>
