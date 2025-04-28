<template>
  <div class="print-container mx-auto mt-10">
    <!-- A4 Layout Box -->
    <div class="a4-page shadow-lg p-8 bg-white text-black">
      <h1 class="text-3xl font-bold mb-4 text-center">Final Report</h1>

      <div class="mb-2">
        <p><span class="font-semibold">Serial No:</span> {{ serialNo }}</p>
        <p><span class="font-semibold">Set No:</span> {{ setNo }}</p>
        <p><span class="font-semibold">Date:</span> {{ new Date().toLocaleDateString() }}</p>
      </div>

      <hr class="my-4" />

      <!-- Dummy content table -->
      <table class="w-full border border-collapse border-gray-400 text-sm">
        <thead>
          <tr class="bg-gray-200 text-left">
            <th class="border px-2 py-1">Property</th>
            <th class="border px-2 py-1">Value</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(value, key) in reportData" :key="key">
            <td class="border px-2 py-1">{{ key }}</td>
            <td class="border px-2 py-1">{{ value }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Optional manual print button -->
      <div class="mt-6 text-center">
        <button
          @click="window.print()"
          class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition"
        >
          Print This Report
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

// You could pass these via props or retrieve via an API
const serialNo = '12345'
const setNo = 3

const reportData = {
  'Br': 1.23,
  'iHc': 2.45,
  'Density': 7.65,
  'Temperature': '950°C',
  'Furnace No': 'F-03'
}

// Optional: auto print on page load
onMounted(() => {
  // window.print()
})
</script>

<style scoped>
/* A4 size container */
.a4-page {
  width: 210mm;
  min-height: 297mm;
  box-sizing: border-box;
  page-break-after: always;
  background: white;
}

/* Print-specific tweaks */
@media print {
  body {
    margin: 0;
    background: white;
  }

  .a4-page {
    box-shadow: none !important;
    margin: 0 auto;
  }

  .print-container {
    padding: 0;
  }

  button {
    display: none !important;
  }
}

@page {
  size: A4;
  margin: 0;
}

/* Optional: custom class for page breaks */
.break-after-page {
  page-break-after: always;
}
</style>
