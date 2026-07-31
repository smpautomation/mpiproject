<template>
  <Frontend>
    <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">

        <div v-if="showSelectionPanel" class="flex flex-col items-center justify-start min-h-screen pt-[70px] mx-auto space-y-6 bg-gray-100">

            <!-- Header -->
            <p class="px-6 py-3 mb-8 text-2xl font-bold tracking-wide text-blue-800 uppercase bg-blue-100 border border-blue-200 shadow-sm sm:text-3xl rounded-xl">
                SPECIAL INSTRUCTIONS / JUDGEMENTS
            </p>

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
                        class="w-full p-6 font-semibold text-blue-800 transition-all border border-blue-200 shadow-sm bg-blue-50 rounded-xl hover:bg-blue-100 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                        {{ item.name }}
                    </button>

                    <!-- Preview Trigger -->
                    <button
                        @click.stop="openImage(item.image)"
                        class="absolute z-20 flex items-center justify-center w-9 h-9 transition-all bg-white border rounded-full shadow-md top-2 right-2 border-slate-200 text-slate-500 hover:bg-cyan-500 hover:text-white group/preview"
                    >
                        <!-- Pulse -->
                        <span class="absolute inline-flex w-full h-full rounded-full bg-cyan-400 opacity-20 animate-ping group-hover/preview:hidden"></span>

                        <!-- Icon -->
                        <svg class="relative w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8v12H4z" />
                        </svg>

                        <!-- Preview Bubble -->
                        <div class="absolute z-30 hidden group-hover:block left-full ml-3 top-1/2 -translate-y-1/2">
                            <div class="bg-white p-2 rounded-lg shadow-xl border w-48">
                                <img :src="item.image" class="w-full h-auto object-contain rounded" />
                            </div>

                            <div class="absolute top-1/2 -right-1 w-2 h-2 bg-white rotate-45 border-r border-b -translate-y-1/2"></div>
                        </div>
                    </button>

                </div>
            </div>
            </div>

            <!-- IMAGE MODAL -->
            <div
            v-if="showImageModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
            @click="closeImage"
            >
            <div class="relative max-w-4xl p-4 bg-white rounded-lg shadow-2xl" @click.stop>

                <!-- Close Button -->
                <button
                    @click="closeImage"
                    class="absolute text-gray-500 top-2 right-2 hover:text-gray-800"
                >
                    ✕
                </button>

                <!-- Image -->
                <img :src="activeImage" class="max-h-[80vh] w-auto rounded" />
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

      <!-- 1x1x1 MODELS (With corner) Table Section -->
      <div
        v-if="showTTMWCPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">1x1x1 MODELS (With corner)</h2>
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
                v-for="ttmwc in ttmwcModels"
                :key="ttmwc.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(ttmwc.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ ttmwc.model_name }}</td>
                <td class="px-4 py-3">{{ ttmwc.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="ttmwc_startEditing(ttmwc)"
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
              v-model="ttmwc_newRecord.model_name"
              type="text"
              @input="ttmwc_newRecord.model_name = ttmwc_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="ttmwc_newRecord.encoded_by"
              type="text"
              @input="ttmwc_newRecord.encoded_by = ttmwc_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="ttmwc_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="ttmwc_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="ttmwc_editingRecord.model_name"
              type="text"
              @input="ttmwc_editingRecord.model_name = ttmwc_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="ttmwc_editingRecord.encoded_by"
              type="text"
              @input="ttmwc_editingRecord.encoded_by = ttmwc_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="ttmwc_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="ttmwc_editingRecord = null"
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
            class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-xl shadow-md border border-gray-100"
        >
            <!-- Table Header & Counter -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">ROB BH TRACER MODELS</h2>
                    <p class="text-xs text-gray-500">Standard Specifications & Baseline Rules</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200/60 rounded-full">
                    {{ robModels?.length || 0 }} Models Recorded
                </span>
            </div>

            <!-- Table Container -->
            <div class="overflow-hidden border border-gray-200/80 rounded-xl shadow-sm bg-white">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs font-semibold text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Model Name</th>
                            <th scope="col" class="px-6 py-3.5">Date Created</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="rob in robModels"
                            :key="rob.id"
                            class="transition-colors hover:bg-slate-50/80 group"
                        >
                            <!-- Model Name -->
                            <td class="px-6 py-4 font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ rob.model_name }}
                            </td>

                            <!-- Date Created -->
                            <td class="px-6 py-4 text-gray-500 text-xs font-medium">
                                {{ new Date(rob.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-2">
                                <!-- View Details Button -->
                                <button
                                    @click="openDynamicDetailsModal(rob, 'ROB Model')"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </button>

                                <!-- Delete Button -->
                                <button
                                    @click="rob_confirmDelete(rob)"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Corrected Empty State Variable -->
                        <tr v-if="!robModels || robModels.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-sm">
                                No ROB models recorded yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add New Model Card Section -->
            <div class="pt-6 mt-8 border-t border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                    <h2 class="text-xl font-bold text-gray-900">Add New Model</h2>
                    <p class="text-sm text-gray-500">Configure new model parameters and baseline specification limits.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Section 1: Basic Information -->
                    <div>
                        <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Basic Info</h3>
                        <div class="grid grid-cols-1">
                            <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700">Model Name</label>
                            <input
                                v-model="rob_newRecord.model_name"
                                type="text"
                                @input="rob_newRecord.model_name = rob_newRecord.model_name.toUpperCase()"
                                placeholder="e.g. TIC0755G"
                                class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg shadow-sm bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            />
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Specifications -->
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80">
                    <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Specifications Baseline</h3>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">Br RT Max</label>
                        <input
                            v-model.number="rob_newRecord.br_rt_standardmax"
                            type="number"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        </div>
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">Br RT Min</label>
                        <input
                            v-model.number="rob_newRecord.br_rt_standardmin"
                            type="number"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        </div>
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">Br VT Standard</label>
                        <input
                            v-model.number="rob_newRecord.br_vt_standard"
                            type="number"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        </div>
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">HD5 Standard</label>
                        <input
                            v-model.number="rob_newRecord.hd5_standard"
                            type="number"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        </div>
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-600">JD5 Standard</label>
                        <input
                            v-model.number="rob_newRecord.jd5_standard"
                            type="number"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        </div>
                    </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end">
                    <button
                        @click="rob_addRecord"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition duration-150"
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
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
    >
        <!-- Table Section -->
        <div class="space-y-4">
            <!-- Header with total count badge -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">ROB BH TRACER MODELS</h2>
                    <p class="text-xs text-gray-500">All Judgements Specifications & Baseline Rules</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200/60 rounded-full">
                    {{ robAjModels?.length || 0 }} Models Recorded
                </span>
            </div>

            <!-- Table Container -->
            <div class="overflow-hidden border border-gray-200/80 rounded-xl shadow-sm bg-white">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs font-semibold text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Model Name</th>
                            <th scope="col" class="px-6 py-3.5">Date Created</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="rob in robAjModels"
                            :key="rob.id"
                            class="transition-colors hover:bg-slate-50/80 group"
                        >
                            <!-- Model Name -->
                            <td class="px-6 py-4 font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ rob.model_name }}
                            </td>

                            <!-- Date Created -->
                            <td class="px-6 py-4 text-gray-500 text-xs font-medium">
                                {{ new Date(rob.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-2">
                                <!-- View Details Button -->
                                <button
                                    @click="openDynamicDetailsModal(rob, 'ROB AJ Model')"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </button>

                                <!-- Delete Button -->
                                <button
                                    @click="robaj_confirmDelete(rob)"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-if="!robAjModels || robAjModels.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-sm">
                                No ROB models recorded yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add New Model Card Section -->
        <div class="pt-6 mt-8 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                <h2 class="text-xl font-bold text-gray-900">Add New Model</h2>
                <p class="text-sm text-gray-500">Configure new model parameters and baseline specification limits.</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Section 1: Basic Information -->
                <div>
                    <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Basic Info</h3>
                    <div class="grid grid-cols-1">
                        <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Model Name</label>
                        <input
                            v-model="robaj_newRecord.model_name"
                            type="text"
                            @input="robaj_newRecord.model_name = robaj_newRecord.model_name.toUpperCase()"
                            placeholder="e.g. TIC0755G"
                            class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg shadow-sm bg-gray-50/50 text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Specifications -->
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Specifications Baseline</h3>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    <div>
                    <label class="block mb-1 text-xs font-medium text-gray-600">Br RT Max</label>
                    <input
                        v-model.number="robaj_newRecord.br_rt_standardmax"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    />
                    </div>
                    <div>
                    <label class="block mb-1 text-xs font-medium text-gray-600">Br RT Min</label>
                    <input
                        v-model.number="robaj_newRecord.br_rt_standardmin"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    />
                    </div>
                    <div>
                    <label class="block mb-1 text-xs font-medium text-gray-600">Br VT Standard</label>
                    <input
                        v-model.number="robaj_newRecord.br_vt_standard"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    />
                    </div>
                    <div>
                    <label class="block mb-1 text-xs font-medium text-gray-600">HD5 Standard</label>
                    <input
                        v-model.number="robaj_newRecord.hd5_standard"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    />
                    </div>
                    <div>
                    <label class="block mb-1 text-xs font-medium text-gray-600">JD5 Standard</label>
                    <input
                        v-model.number="robaj_newRecord.jd5_standard"
                        type="number"
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    />
                    </div>
                </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end">
                <button
                    @click="robaj_addRecord"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition duration-150"
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

      <!-- CPK BR MODELS Table Section -->
      <div
        v-if="showCPKBRPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">CPK BR MODELS</h2>
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
                v-for="cpkbr in cpkbrModels"
                :key="cpkbr.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(cpkbr.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ cpkbr.model_name }}</td>
                <td class="px-4 py-3">{{ cpkbr.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="cpkbr_startEditing(cpkbr)"
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
              v-model="cpkbr_newRecord.model_name"
              type="text"
              @input="cpkbr_newRecord.model_name = cpkbr_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="cpkbr_newRecord.encoded_by"
              type="text"
              @input="cpkbr_newRecord.encoded_by = cpkbr_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="cpkbr_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="cpkbr_editingRecord" class="pt-6 border-t">
          <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
          <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
              v-model="cpkbr_editingRecord.model_name"
              type="text"
              @input="cpkbr_editingRecord.model_name = cpkbr_editingRecord.model_name.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
              v-model="cpkbr_editingRecord.encoded_by"
              type="text"
              @input="cpkbr_editingRecord.encoded_by = cpkbr_editingRecord.encoded_by.toUpperCase()"
              class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="cpkbr_updateRecord"
              class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
            >
              Update
            </button>
            <button
              @click="cpkbr_editingRecord = null"
              class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

      <!-- HIS MODELS Table Section -->
      <div
        v-if="showHISPanel"
        class="w-full max-w-5xl p-6 mx-auto mt-10 space-y-8 bg-white rounded-lg shadow-md"
      >
        <!-- Table -->
        <div>
          <h2 class="mb-4 text-2xl font-semibold text-gray-800">HIS GS MODELS</h2>
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
                v-for="his in hisModels"
                :key="his.id"
                class="transition border-b hover:bg-gray-50 last:border-b-0"
              >
                <td class="px-4 py-3">{{ new Date(his.created_at).toISOString().slice(0, 10) }}</td>
                <td class="px-4 py-3">{{ his.model_name }}</td>
                <td class="px-4 py-3">{{ his.encoded_by }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="his_startEditing(his)"
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
              v-model="his_newRecord.model_name"
              type="text"
              @input="his_newRecord.model_name = his_newRecord.model_name.toUpperCase()"
              placeholder="Model Name"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
            <input
              v-model="his_newRecord.encoded_by"
              type="text"
              @input="his_newRecord.encoded_by = his_newRecord.encoded_by.toUpperCase()"
              placeholder="Encoded By"
              class="px-3 py-2 border rounded focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="his_addRecord"
            class="px-5 py-2 text-sm font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="his_editingRecord" class="pt-6 border-t">
            <h2 class="mb-2 text-lg font-semibold text-gray-800">Edit Employee details</h2>
            <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
            <input
                v-model="his_editingRecord.model_name"
                type="text"
                @input="his_editingRecord.model_name = his_editingRecord.model_name.toUpperCase()"
                class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            <input
                v-model="his_editingRecord.encoded_by"
                type="text"
                @input="his_editingRecord.encoded_by = his_editingRecord.encoded_by.toUpperCase()"
                class="px-3 py-2 border rounded focus:ring focus:ring-green-200"
            />
            </div>
            <div class="space-x-3">
                <button
                    @click="his_updateRecord"
                    class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded hover:bg-green-700"
                >
                    Update
                </button>
                <button
                    @click="his_editingRecord = null"
                    class="px-5 py-2 text-sm font-medium text-white transition bg-gray-400 rounded hover:bg-gray-500"
                >
                    Cancel
                </button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showRobAjDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Confirm Deletion</h3>

                <p class="mt-2 text-sm text-gray-600">
                    Are you sure you want to delete <strong class="text-gray-800">{{ recordToDelete?.model_name }}</strong>? This action cannot be undone.
                </p>

                <div class="flex justify-end space-x-3 mt-6">
                    <button
                        @click="showRobAjDeleteModal = false; recordToDelete = null;"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>
                    <button
                        @click="robaj_executeDelete"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showRobDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Confirm Deletion</h3>

                <p class="mt-2 text-sm text-gray-600">
                    Are you sure you want to delete <strong class="text-gray-800">{{ recordToDelete?.model_name }}</strong>? This action cannot be undone.
                </p>

                <div class="flex justify-end space-x-3 mt-6">
                    <button
                        @click="showRobDeleteModal = false; recordToDelete = null;"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition"
                    >
                        Cancel
                    </button>
                    <button
                        @click="rob_executeDelete"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition"
                    >
                        Delete
                    </button>
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

    </div>

  </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { router } from '@inertiajs/vue3'
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

    // activate selected panel
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

const vt_editingRecord = ref(null);
const cpkihc_editingRecord = ref(null);
const gx_editingRecord = ref(null);
const ttmwc_editingRecord = ref(null);
const ttmnc_editingRecord = ref(null);
const bh_editingRecord = ref(null);
const cpkbr_editingRecord = ref(null);
const his_editingRecord = ref(null);

const showRobDeleteModal = ref(false);
const showRobAjDeleteModal = ref(false);
const showAddConfirmModal = ref(false);
const showAddValidationModal = ref(false);
const showDetailsModal = ref(false);
const selectedModelDetails = ref(null);
const recordToDelete = ref(null);


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

const vt_startEditing = (record) => {
  vt_editingRecord.value = { ...record };
};

const cpkihc_startEditing = (record) => {
  cpkihc_editingRecord.value = { ...record };
};

const gx_startEditing = (record) => {
  gx_editingRecord.value = { ...record };
};

const ttmwc_startEditing = (record) => {
  ttmwc_editingRecord.value = { ...record };
};

const ttmnc_startEditing = (record) => {
  ttmnc_editingRecord.value = { ...record };
};

const bh_startEditing = (record) => {
  bh_editingRecord.value = { ...record };
};

// Opens the modal and stores the selected record
const rob_confirmDelete = (record) => {
    recordToDelete.value = record;
    showRobDeleteModal.value = true;
};

// Opens the modal and stores the selected record
const robaj_confirmDelete = (record) => {
    recordToDelete.value = record;
    showRobAjDeleteModal.value = true;
};

// Handles the actual API deletion call
const rob_executeDelete = async () => {
    if (!recordToDelete.value) return;

    try {
        await axios.delete(`/api/rob-models/${recordToDelete.value.id}`);
        await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of ROB Models`);
        await loadData();
    } catch (error) {
        console.error("Failed to delete record:", error);
    } finally {
        // Reset state & close modal
        showRobDeleteModal.value = false;
        recordToDelete.value = null;
    }
};

// Handles the actual API deletion call
const robaj_executeDelete = async () => {
    if (!recordToDelete.value) return;

    try {
        await axios.delete(`/api/rob-model-ajs/${recordToDelete.value.id}`);
        await userInstructionsLogging(`has successfully deleted ${recordToDelete.value.model_name} from the data list instructions of ROB AJ Models`);
        await loadData();
    } catch (error) {
        console.error("Failed to delete record:", error);
    } finally {
        // Reset state & close modal
        showRobAjDeleteModal.value = false;
        recordToDelete.value = null;
    }
};

const cpkbr_startEditing = (record) => {
    cpkbr_editingRecord.value = { ...record };
}

const his_startEditing = (record) => {
    his_editingRecord.value = { ...record };
}

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
  await userInstructionsLogging(`has successfully added ${gx_newRecord.value.model_name} to the data list instructions of GX Models`);
  await loadData();
  gx_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const ttmwc_addRecord = async () => {
  if (!ttmwc_newRecord.value.model_name || !ttmwc_newRecord.value.encoded_by) return;

  await axios.post('/api/ttmwc-models', ttmwc_newRecord.value);
  await userInstructionsLogging(`has successfully added ${ttmwc_newRecord.value.model_name} to the data list instructions of 1x1x1 with corner Models`);
  await loadData();
  ttmwc_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const ttmnc_addRecord = async () => {
  if (!ttmnc_newRecord.value.model_name || !ttmnc_newRecord.value.encoded_by) return;

  await axios.post('/api/ttmnc-models', ttmnc_newRecord.value);
  await userInstructionsLogging(`has successfully added ${ttmnc_newRecord.value.model_name} to the data list instructions of 1x1x1 without corner Models`);
  await loadData();
  ttmnc_newRecord.value = { model_name: '', encoded_by: ''};
};

// Add record
const bh_addRecord = async () => {
  if (!bh_newRecord.value.model_name || !bh_newRecord.value.encoded_by) return;

  await axios.post('/api/bh-models', bh_newRecord.value);
  await userInstructionsLogging(`has successfully added ${bh_newRecord.value.model_name} to the data list instructions of BH Models`);
  await loadData();
  bh_newRecord.value = { model_name: '', encoded_by: ''};
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

const cpkbr_addRecord = async () => {
  if (!cpkbr_newRecord.value.model_name || !cpkbr_newRecord.value.encoded_by) return;

  await axios.post('/api/cpk-br-models', cpkbr_newRecord.value);
  await userInstructionsLogging(`has successfully added ${cpkbr_newRecord.value.model_name} to the data list instructions of CPK BR Models`);
  await loadData();
  cpkbr_newRecord.value = { model_name: '', encoded_by: ''};
};

const his_addRecord = async () => {
  if (!his_newRecord.value.model_name || !his_newRecord.value.encoded_by) return;

  await axios.post('/api/his-models', his_newRecord.value);
  await userInstructionsLogging(`has successfully added ${his_newRecord.value.model_name} to the data list instructions of HIS Models`);
  await loadData();
  his_newRecord.value = { model_name: '', encoded_by: ''};
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
  await userInstructionsLogging(`has successfully edited ${gx_editingRecord.value.model_name} to the data list instructions of GX Models`);
  gx_editingRecord.value = null;
  await loadData();
};

// Update record
const ttmwc_updateRecord = async () => {
  await axios.put(`/api/ttmwc-models/${ttmwc_editingRecord.value.id}`, ttmwc_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${ttmwc_editingRecord.value.model_name} to the data list instructions of 1x1x1 with corner Models`);
  ttmwc_editingRecord.value = null;
  await loadData();
};

// Update record
const ttmnc_updateRecord = async () => {
  await axios.put(`/api/ttmnc-models/${ttmnc_editingRecord.value.id}`, ttmnc_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${ttmnc_editingRecord.value.model_name} to the data list instructions of 1x1x1 without corner Models`);
  ttmnc_editingRecord.value = null;
  await loadData();
};

// Update record
const bh_updateRecord = async () => {
  await axios.put(`/api/bh-models/${bh_editingRecord.value.id}`, bh_editingRecord.value);
  await userInstructionsLogging(`has successfully edited ${bh_editingRecord.value.model_name} to the data list instructions of BH Models`);
  bh_editingRecord.value = null;
  await loadData();
};

const cpkbr_updateRecord = async () => {
    await axios.put(`/api/cpk-br-models/${cpkbr_editingRecord.value.id}`, cpkbr_editingRecord.value);
    await userInstructionsLogging(`has successfully edited ${cpkbr_editingRecord.value.model_name} to the data list instructions of CPK BR Models`);
    cpkbr_editingRecord.value = null;
    await loadData();
};

const his_updateRecord = async () => {
    await axios.put(`/api/his-models/${his_editingRecord.value.id}`, his_editingRecord.value);
    await userInstructionsLogging(`has successfully edited ${his_editingRecord.value.model_name} to the data list instructions of HIS Models`);
    his_editingRecord.value = null;
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

onMounted(async () => {
  await loadData();
  await checkAuthentication();
});
</script>
