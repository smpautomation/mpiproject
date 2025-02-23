<template>
    <Frontend>
      <div class="px-[420px] py-[100px] container-fluid bg-gray-100">
        <p class="font-bold">Upload Raw Data:</p>
        <input type="file" accept=".txt" /><br><br><br><br><br>

        <!-- Chart Container -->
        <div class="w-2/3 bg-blue-100 rounded-xl">
          <apexchart
            type="line"
            :series="lineChartSeries"
            :options="lineChartOptions"
          />
        </div>
      </div>
    </Frontend>
  </template>

  <script setup>
    import Frontend from '@/Layouts/FrontendLayout.vue';
    import { ref } from 'vue';

    // Chart data for the Missing / Null Values chart
    const lineChartSeries = ref([
      {
        name: 'Sample1',
        data: [null, null, null, 15, 20, 20, 20, 20, 20, 20, 20, 20, 20, null, null, null, null, null, null, null, null, null, null],
      },
      {
        name: 'Sample2',
        data: [null, null, null, null, 12, 18, 18, 18, 18, 18, 18, 18, null, null, null, null, null, null, null, null, null, null, null],
      },
      {
        name: 'Sample3',
        data: [null, null, null, null, null, 9, 15, 15, 15, 15, 15, 15, null, null, null, null, null, null, null, null, null, null, null],
      },
    ]);

    // Chart options including customizations like labels, stroke, and xaxis
    const lineChartOptions = ref({
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: false,
        },
        animations: {
          enabled: false,
        },
      },
      stroke: {
        width: [5, 5, 4],
        curve: 'smooth',
      },
      labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
      title: {
        text: 'JH CURVE',
        align: 'center',
        style: {
          fontSize: '18px',
          fontWeight: 'bold',
          color: '#333',
        },
      },
      xaxis: {
        // You can adjust any other specific x-axis properties here if necessary
      },
    });
  </script>
