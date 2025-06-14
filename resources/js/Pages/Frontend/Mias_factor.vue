<template>
  <Frontend>
    <div class="flex flex-col items-center justify-start min-h-screen px-8 py-12 mx-auto space-y-6 bg-gray-100">
      <!-- Upload Section (Hidden if data exists) -->
      <div
        v-if="miasFactors.length === 0"
        class="flex flex-col items-center justify-center bg-white shadow-lg rounded-lg p-8 max-w-md mx-auto mt-10 space-y-6 border border-gray-200"
      >
        <h2 class="text-2xl font-bold text-gray-800">MIAS Factor Upload</h2>

        <input
          type="file"
          @change="handleFileUpload"
          accept=".csv"
          class="file:mr-4 file:py-2 file:px-4
                 file:rounded-full file:border-0
                 file:text-sm file:font-semibold
                 file:bg-blue-600 file:text-white
                 hover:file:bg-blue-700
                 cursor-pointer text-gray-600"
        />

        <button
          @click="uploadFile"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition"
        >
          Upload
        </button>

        <DotsLoader v-if="loading" />

        <p
          v-if="uploadStatus"
          :class="[ 'text-sm text-center', uploadStatusType === 'success' ? 'text-green-600' : uploadStatusType === 'failed' ? 'text-red-600' : 'text-gray-600']"
        >
          {{ uploadStatus }}
        </p>
      </div>

      <!-- Table Section -->
      <div
        v-if="miasFactors.length > 0"
        class="bg-white shadow-md rounded-lg w-full max-w-5xl mx-auto mt-10 p-6 space-y-8"
      >
        <!-- Table -->
        <div>
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">Uploaded MIAS Factor Records</h2>
          <table class="w-full table-auto text-sm border border-gray-200 rounded-md overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
              <tr>
                <th class="px-4 py-3 text-left border-b">Employee Name</th>
                <th class="px-4 py-3 text-left border-b">Employee No</th>
                <th class="px-4 py-3 text-left border-b">MIAS No</th>
                <th class="px-4 py-3 text-left border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="factor in miasFactors"
                :key="factor.id"
                class="hover:bg-gray-50 border-b last:border-b-0 transition"
              >
                <td class="px-4 py-3">{{ factor.employee_name }}</td>
                <td class="px-4 py-3">{{ factor.employee_no }}</td>
                <td class="px-4 py-3">{{ factor.mias_no }}</td>
                <td class="px-4 py-3">
                  <button
                    @click="startEditing(factor)"
                    class="text-blue-600 hover:underline text-sm font-medium"
                  >
                    Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add New -->
        <div class="border-t pt-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-2">Add New MIAS Factor</h2>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <input
              v-model="newRecord.employee_name"
              type="text"
              @input="newRecord.employee_name = newRecord.employee_name.toUpperCase()"
              placeholder="Employee Name"
              class="border rounded px-3 py-2 focus:ring focus:ring-blue-200"
            />
            <input
              v-model="newRecord.employee_no"
              type="text"
              placeholder="Employee No"
              class="border rounded px-3 py-2 focus:ring focus:ring-blue-200"
            />
            <input
              v-model="newRecord.mias_no"
              type="text"
              placeholder="MIAS No"
              class="border rounded px-3 py-2 focus:ring focus:ring-blue-200"
            />
          </div>
          <button
            @click="addRecord"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded text-sm font-medium transition"
          >
            Add
          </button>
        </div>

        <!-- Edit Record -->
        <div v-if="editingRecord" class="border-t pt-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-2">Edit MIAS Factor</h2>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <input
              v-model="editingRecord.employee_name"
              @input="editingRecord.employee_name = editingRecord.employee_name.toUpperCase()"
              placeholder="Employee Name"
              class="border rounded px-3 py-2 focus:ring focus:ring-green-200"
            />
            <input
              v-model="editingRecord.employee_no"
              type="text"
              placeholder="Employee No"
              class="border rounded px-3 py-2 focus:ring focus:ring-green-200"
            />
            <input
              v-model="editingRecord.mias_no"
              type="text"
              placeholder="MIAS No"
              class="border rounded px-3 py-2 focus:ring focus:ring-green-200"
            />
          </div>
          <div class="space-x-3">
            <button
              @click="updateRecord"
              class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded text-sm font-medium transition"
            >
              Update
            </button>
            <button
              @click="editingRecord = null"
              class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded text-sm font-medium transition"
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

const file = ref(null);
const loading = ref(false);
const uploadStatus = ref('');
const uploadStatusType = ref('');
const miasFactors = ref([]); // all fetched records
const newRecord = ref({ employee_name: '', employee_no: '', mias_no: '' });
const editingRecord = ref(null);

const startEditing = (record) => {
  editingRecord.value = { ...record }; // shallow clone to prevent binding issues
};

// Add record
const addRecord = async () => {
  if (!newRecord.value.employee_name || !newRecord.value.employee_no || !newRecord.value.mias_no) return;

  await axios.post('/api/mias-factor', newRecord.value);
  await loadData();
  newRecord.value = { employee_name: '', employee_no: '', mias_no: '' };
};

// Update record
const updateRecord = async () => {
  await axios.put(`/api/mias-factor/${editingRecord.value.id}`, editingRecord.value);
  editingRecord.value = null;
  await loadData();
};

const handleFileUpload = (event) => {
  file.value = event.target.files[0];
};

const uploadFile = () => {
  if (!file.value) {
    uploadStatus.value = 'Please select a CSV file first.';
    uploadStatusType.value = 'info';
    resetStatusAfterDelay();
    return;
  }

  loading.value = true;

  Papa.parse(file.value, {
    header: true,
    skipEmptyLines: true,
    complete: async (results) => {
      try {
        const response = await axios.post('/api/mias-factor/bulk-upload', {
          data: results.data,
        });

        uploadStatus.value = `${response.data.message} (${response.data.inserted} records inserted)`;
        uploadStatusType.value = 'success';
        await loadData(); // refresh table data after upload
      } catch (err) {
        uploadStatus.value = 'Upload failed: ' + (err.response?.data?.message || err.message);
        uploadStatusType.value = 'failed';
      } finally {
        loading.value = false;
        resetStatusAfterDelay();
      }
    },
    error: (err) => {
      uploadStatus.value = 'CSV parse error: ' + err.message;
      uploadStatusType.value = 'failed';
      loading.value = false;
      resetStatusAfterDelay();
    },
  });
};

const resetStatusAfterDelay = () => {
  setTimeout(() => {
    uploadStatus.value = '';
  }, 3000);
};

const loadData = async () => {
  try {
    const res = await axios.get('/api/mias-factor');
    miasFactors.value = res.data.data; // assuming your API returns { data: [...] }
    console.log('MIAS factors loaded:', miasFactors.value);
  } catch (e) {
    console.error('Failed to load MIAS factors:', e);
  }
};

const formatDate = (date) => new Date(date).toLocaleDateString();

onMounted(async () => {
  await loadData();
});
</script>
