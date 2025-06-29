<template>
  <Frontend>
    <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">

    <div v-if="showSelectionPanel" class="flex flex-col items-center justify-start min-h-screen pt-[70px] mx-auto space-y-6 bg-gray-100">
        <p class="px-6 py-3 mb-8 text-2xl font-bold tracking-wide text-blue-800 uppercase bg-blue-100 border border-blue-200 shadow-sm sm:text-3xl rounded-xl">
            SPECIAL INSTRUCTIONS / JUDGEMENTS
        </p>
        <div class="grid w-full max-w-6xl grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <button @click="showVTPanel = true, showSelectionPanel = false" class="p-6 font-semibold text-blue-800 transition-all border border-blue-200 shadow-sm bg-blue-50 rounded-xl hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300">
                VT MODELS
            </button>

            <button @click="showCPKIHCPanel = true, showSelectionPanel = false" class="p-6 font-semibold text-blue-800 transition-all border border-blue-200 shadow-sm bg-blue-50 rounded-xl hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300">
                CPK IHC MODELS
            </button>

            <button @click="showGXPanel = true, showSelectionPanel = false" class="p-6 font-semibold text-blue-800 transition-all border border-blue-200 shadow-sm bg-blue-50 rounded-xl hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300">
                GX MODELS
            </button>

            <button @click="showTTMNCPanel = true, showSelectionPanel = false" class="p-6 font-semibold text-blue-800 transition-all border border-blue-200 shadow-sm bg-blue-50 rounded-xl hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300">
                1X1X1 MODELS (NO CORNER)
            </button>

            <button @click="showBHPanel = true, showSelectionPanel = false" class="p-6 font-semibold text-blue-800 transition-all border border-blue-200 shadow-sm bg-blue-50 rounded-xl hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300">
                BH MODELS
            </button>

            <button @click="showROBPanel = true, showSelectionPanel = false" class="p-6 font-semibold text-blue-800 transition-all border border-blue-200 shadow-sm bg-blue-50 rounded-xl hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300">
                ROB BH TRACER MODELS
            </button>
        </div>
    </div>

    <button
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
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">VT MODELS</h2>
          <table class="w-full overflow-hidden text-sm border border-gray-200 rounded-md table-auto">
            <thead class="text-xs tracking-wider text-gray-700 uppercase bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left border-b">Date</th>
                <th class="px-4 py-3 text-left border-b">Model Name</th>
                <th class="px-4 py-3 text-left border-b">Encoded By</th>
                <th class="px-4 py-3 text-left border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="vt in vtModels"
                :key="vt.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(vt.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ vt.model_name }}</td>
                <td class="px-4 py-3">{{ vt.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="vt_startEditing(vt)"
                    class="text-sm font-medium text-blue-600 hover:underline"
                  >
                    Edit Model
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add New -->
        <div class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Add New Model</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="vt_newRecord.model_name"
              type="text"
              @input="vt_newRecord.model_name = vt_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="vt_newRecord.encoded_by"
              type="text"
              @input="vt_newRecord.encoded_by = vt_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="vt_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="vt_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="vt_editingRecord.model_name"
              type="text"
              @input="vt_editingRecord.model_name = vt_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="vt_editingRecord.encoded_by"
              type="text"
              @input="vt_editingRecord.encoded_by = vt_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="vt_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="vt_editingRecord = null"
              class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

    <!-- CPK IHC MODELS Table Section -->
      <div
        v-if="showCPKIHCPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">CPK IHC MODELS</h2>
          <table class="w-full overflow-hidden text-sm border border-gray-200 rounded-md table-auto">
            <thead class="text-xs tracking-wider text-gray-700 uppercase bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left border-b">Date</th>
                <th class="px-4 py-3 text-left border-b">Model Name</th>
                <th class="px-4 py-3 text-left border-b">Encoded By</th>
                <th class="px-4 py-3 text-left border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="cpkihc in cpkihcModels"
                :key="cpkihc.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(cpkihc.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ cpkihc.model_name }}</td>
                <td class="px-4 py-3">{{ cpkihc.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="cpkihc_startEditing(cpkihc)"
                    class="text-sm font-medium text-blue-600 hover:underline"
                  >
                    Edit Model
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add New -->
        <div class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Add New Model</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="cpkihc_newRecord.model_name"
              type="text"
              @input="cpkihc_newRecord.model_name = cpkihc_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="cpkihc_newRecord.encoded_by"
              type="text"
              @input="cpkihc_newRecord.encoded_by = cpkihc_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="cpkihc_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="cpkihc_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="cpkihc_editingRecord.model_name"
              type="text"
              @input="cpkihc_editingRecord.model_name = cpkihc_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="cpkihc_editingRecord.encoded_by"
              type="text"
              @input="cpkihc_editingRecord.encoded_by = cpkihc_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="cpkihc_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="cpkihc_editingRecord = null"
              class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

    <!-- GX MODELS Table Section -->
      <div
        v-if="showGXPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">GX MODELS</h2>
          <table class="w-full overflow-hidden text-sm border border-gray-200 rounded-md table-auto">
            <thead class="text-xs tracking-wider text-gray-700 uppercase bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left border-b">Date</th>
                <th class="px-4 py-3 text-left border-b">Model Name</th>
                <th class="px-4 py-3 text-left border-b">Encoded By</th>
                <th class="px-4 py-3 text-left border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="gx in gxModels"
                :key="gx.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(gx.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ gx.model_name }}</td>
                <td class="px-4 py-3">{{ gx.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="gx_startEditing(gx)"
                    class="text-sm font-medium text-blue-600 hover:underline"
                  >
                    Edit Model
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add New -->
        <div class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Add New Model</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="gx_newRecord.model_name"
              type="text"
              @input="gx_newRecord.model_name = gx_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="gx_newRecord.encoded_by"
              type="text"
              @input="gx_newRecord.encoded_by = gx_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="gx_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="gx_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="gx_editingRecord.model_name"
              type="text"
              @input="gx_editingRecord.model_name = gx_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="gx_editingRecord.encoded_by"
              type="text"
              @input="gx_editingRecord.encoded_by = gx_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="gx_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="gx_editingRecord = null"
              class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

      <!-- 1x1x1 MODELS (No corner) Table Section -->
      <div
        v-if="showTTMNCPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">1x1x1 MODELS (No corner)</h2>
          <table class="w-full overflow-hidden text-sm border border-gray-200 rounded-md table-auto">
            <thead class="text-xs tracking-wider text-gray-700 uppercase bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left border-b">Date</th>
                <th class="px-4 py-3 text-left border-b">Model Name</th>
                <th class="px-4 py-3 text-left border-b">Encoded By</th>
                <th class="px-4 py-3 text-left border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="ttmnc in ttmncModels"
                :key="ttmnc.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(ttmnc.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ ttmnc.model_name }}</td>
                <td class="px-4 py-3">{{ ttmnc.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="ttmnc_startEditing(ttmnc)"
                    class="text-sm font-medium text-blue-600 hover:underline"
                  >
                    Edit Model
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add New -->
        <div class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Add New Model</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="ttmnc_newRecord.model_name"
              type="text"
              @input="ttmnc_newRecord.model_name = ttmnc_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="ttmnc_newRecord.encoded_by"
              type="text"
              @input="ttmnc_newRecord.encoded_by = ttmnc_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="ttmnc_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="ttmnc_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="ttmnc_editingRecord.model_name"
              type="text"
              @input="ttmnc_editingRecord.model_name = ttmnc_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="ttmnc_editingRecord.encoded_by"
              type="text"
              @input="ttmnc_editingRecord.encoded_by = ttmnc_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="ttmnc_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="ttmnc_editingRecord = null"
              class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

      <!-- BH MODELS Table Section -->
      <div
        v-if="showBHPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">BH MODELS</h2>
          <table class="w-full overflow-hidden text-sm border border-gray-200 rounded-md table-auto">
            <thead class="text-xs tracking-wider text-gray-700 uppercase bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left border-b">Date</th>
                <th class="px-4 py-3 text-left border-b">Model Name</th>
                <th class="px-4 py-3 text-left border-b">Encoded By</th>
                <th class="px-4 py-3 text-left border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="bh in bhModels"
                :key="bh.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(bh.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ bh.model_name }}</td>
                <td class="px-4 py-3">{{ bh.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="bh_startEditing(bh)"
                    class="text-sm font-medium text-blue-600 hover:underline"
                  >
                    Edit Model
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add New -->
        <div class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Add New Model</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="bh_newRecord.model_name"
              type="text"
              @input="bh_newRecord.model_name = bh_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="bh_newRecord.encoded_by"
              type="text"
              @input="bh_newRecord.encoded_by = bh_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="bh_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="bh_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="bh_editingRecord.model_name"
              type="text"
              @input="bh_editingRecord.model_name = bh_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="bh_editingRecord.encoded_by"
              type="text"
              @input="bh_editingRecord.encoded_by = bh_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="bh_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="bh_editingRecord = null"
              class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

    <!-- ROB MODELS Table Section -->
      <div
        v-if="showROBPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">ROB BH TRACER MODELS</h2>
          <table class="w-full overflow-hidden text-sm border border-gray-200 rounded-md table-auto">
            <thead class="text-xs tracking-wider text-gray-700 uppercase bg-gray-100">
              <tr>
                <th class="px-4 py-3 text-left border-b">Date</th>
                <th class="px-4 py-3 text-left border-b">Model Name</th>
                <th class="px-4 py-3 text-left border-b">Encoded By</th>
                <th class="px-4 py-3 text-left border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="rob in robModels"
                :key="rob.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(rob.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ rob.model_name }}</td>
                <td class="px-4 py-3">{{ rob.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="rob_startEditing(rob)"
                    class="text-sm font-medium text-blue-600 hover:underline"
                  >
                    Edit Model
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add New -->
        <div class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Add New Model</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="rob_newRecord.model_name"
              type="text"
              @input="rob_newRecord.model_name = rob_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="rob_newRecord.encoded_by"
              type="text"
              @input="rob_newRecord.encoded_by = rob_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="rob_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="rob_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="rob_editingRecord.model_name"
              type="text"
              @input="rob_editingRecord.model_name = rob_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="rob_editingRecord.encoded_by"
              type="text"
              @input="rob_editingRecord.encoded_by = rob_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="rob_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="rob_editingRecord = null"
              class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>


    </div>

  </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted } from 'vue';
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
const showTTMNCPanel = ref(false);
const showBHPanel = ref(false);
const showROBPanel = ref(false);

const vtModels = ref([]); // all fetched records
const cpkihcModels = ref([]);
const gxModels = ref([]);
const ttmncModels = ref([]);
const bhModels = ref([]);
const robModels = ref([]);

const vt_newRecord = ref({ model_name: '', encoded_by: ''});
const cpkihc_newRecord = ref({ model_name: '', encoded_by: ''});
const gx_newRecord = ref({ model_name: '', encoded_by: ''});
const ttmnc_newRecord = ref({ model_name: '', encoded_by: ''});
const bh_newRecord = ref({ model_name: '', encoded_by: ''});
const rob_newRecord = ref({ model_name: '', encoded_by: ''});

const vt_editingRecord = ref(null);
const cpkihc_editingRecord = ref(null);
const gx_editingRecord = ref(null);
const ttmnc_editingRecord = ref(null);
const bh_editingRecord = ref(null);
const rob_editingRecord = ref(null);

const backButton = () => {
    showSelectionPanel.value = true;
    showVTPanel.value = false;
    showBHPanel.value = false;
    showROBPanel.value = false;
    showCPKIHCPanel.value = false;
    showGXPanel.value = false;
    showTTMNCPanel.value = false;
}

const vt_startEditing = (record) => {
  vt_editingRecord.value = { ...record };
};

const cpkihc_startEditing = (record) => {
  cpkihc_editingRecord.value = { ...record };
};

const gx_startEditing = (record) => {
  gx_editingRecord.value = { ...record };
};

const ttmnc_startEditing = (record) => {
  ttmnc_editingRecord.value = { ...record };
};

const bh_startEditing = (record) => {
  bh_editingRecord.value = { ...record };
};

const rob_startEditing = (record) => {
  rob_editingRecord.value = { ...record };
};

// Add record
const vt_addRecord = async () => {
  if (!vt_newRecord.value.model_name || !vt_newRecord.value.encoded_by) return;

  await axios.post('/api/vt-models', vt_newRecord.value);
  await userInstructionsLogging(`has successfully added ${vt_newRecord.value.model_name} to the data list instructions of VT Models`);
  await loadData();
  vt_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const cpkihc_addRecord = async () => {
  if (!cpkihc_newRecord.value.model_name || !cpkihc_newRecord.value.encoded_by) return;

  await axios.post('/api/cpk-ihc-models', cpkihc_newRecord.value);
  await userInstructionsLogging(`has successfully added ${cpkihc_newRecord.value.model_name} to the data list instructions of CPK IHC Models`);
  await loadData();
  cpkihc_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const gx_addRecord = async () => {
  if (!gx_newRecord.value.model_name || !gx_newRecord.value.encoded_by) return;

  await axios.post('/api/gx-models', gx_newRecord.value);
  await userInstructionsLogging(`has successfully added ${gx_newRecord.value.model_name} to the data list instructions of CPK IHC Models`);
  await loadData();
  gx_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const ttmnc_addRecord = async () => {
  if (!ttmnc_newRecord.value.model_name || !ttmnc_newRecord.value.encoded_by) return;

  await axios.post('/api/ttmnc-models', ttmnc_newRecord.value);
  await userInstructionsLogging(`has successfully added ${ttmnc_newRecord.value.model_name} to the data list instructions of CPK IHC Models`);
  await loadData();
  ttmnc_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const bh_addRecord = async () => {
  if (!bh_newRecord.value.model_name || !bh_newRecord.value.encoded_by) return;

  await axios.post('/api/bh-models', bh_newRecord.value);
  await userInstructionsLogging(`has successfully added ${bh_newRecord.value.model_name} to the data list instructions of CPK IHC Models`);
  await loadData();
  bh_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const rob_addRecord = async () => {
  if (!rob_newRecord.value.model_name || !rob_newRecord.value.encoded_by) return;

  await axios.post('/api/rob-models', rob_newRecord.value);
  await userInstructionsLogging(`has successfully added ${rob_newRecord.value.model_name} to the data list instructions of VT Models`);
  await loadData();
  rob_newRecord.value = { model_name: '', encoded_by: ''};
};

// Update record
const vt_updateRecord = async () => {
  await axios.put(`/api/vt-models/${vt_editingRecord.value.id}`, vt_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${vt_editingRecord.value.model_name} to the data list instructions of VT Models`);
  vt_editingRecord.value = null;
  await loadData();
};

const cpkihc_updateRecord = async () => {
  await axios.put(`/api/cpk-ihc-models/${cpkihc_editingRecord.value.id}`, cpkihc_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${cpkihc_editingRecord.value.model_name} to the data list instructions of CPK IHC Models`);
  cpkihc_editingRecord.value = null;
  await loadData();
};

const gx_updateRecord = async () => {
  await axios.put(`/api/gx-models/${gx_editingRecord.value.id}`, gx_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${gx_editingRecord.value.model_name} to the data list instructions of CPK IHC Models`);
  gx_editingRecord.value = null;
  await loadData();
};

// Update record
const ttmnc_updateRecord = async () => {
  await axios.put(`/api/ttmnc-models/${ttmnc_editingRecord.value.id}`, ttmnc_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${ttmnc_editingRecord.value.model_name} to the data list instructions of VT Models`);
  ttmnc_editingRecord.value = null;
  await loadData();
};

// Update record
const bh_updateRecord = async () => {
  await axios.put(`/api/bh-models/${bh_editingRecord.value.id}`, bh_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${bh_editingRecord.value.model_name} to the data list instructions of VT Models`);
  bh_editingRecord.value = null;
  await loadData();
};

// Update record
const rob_updateRecord = async () => {
  await axios.put(`/api/rob-models/${rob_editingRecord.value.id}`, rob_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${rob_editingRecord.value.model_name} to the data list instructions of VT Models`);
  rob_editingRecord.value = null;
  await loadData();
};

const loadData = async () => {
  try {
    const responseGetVTData = await axios.get('/api/vt-models');
    vtModels.value = responseGetVTData.data;
    const responseGetCPKIHCData = await axios.get('/api/cpk-ihc-models');
    cpkihcModels.value = responseGetCPKIHCData.data;
    const responseGetGXData = await axios.get('/api/gx-models');
    gxModels.value = responseGetGXData.data;
    const responseGetTTMNCData = await axios.get('/api/ttmnc-models');
    ttmncModels.value = responseGetTTMNCData.data;
    const responseGetBHData = await axios.get('/api/bh-models');
    bhModels.value = responseGetBHData.data;
    const responseGetROBData = await axios.get('/api/rob-models');
    robModels.value = responseGetROBData.data;
  } catch (e) {
    console.error('Failed to load responseGetVTData: ', e);
  }
};

onMounted(async () => {
  await loadData();
  await checkAuthentication();
});
</script>
