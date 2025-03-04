<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center mt-12 font-bold">
            <label>Lists of reports are located here</label>
            <div class="w-[1000px] h-[550px] bg-blue-100 rounded-xl">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, onMounted } from 'vue';
import { Chart, LineController, LinearScale, PointElement, LineElement } from 'chart.js';

// Register required Chart.js components
Chart.register(LineController, LinearScale, PointElement, LineElement);

onMounted(() => {
  try {
    const ctx = document.getElementById('myChart').getContext('2d');

    // Initialize Chart.js
    new Chart(ctx, {
      type: 'line',
      data: {
        datasets: [
          {
            label: 'Dataset 1',
            data: [
              { x: 4783.843, y: -0.234 },
              { x: 4784.391, y: 12747.743 },
              // Additional points...
            ],
            borderColor: 'blue',
            fill: false,
          },
          {
            label: 'Dataset 2',
            data: [
              { x: 0, y: -2500 },
              { x: 0, y: 14500 },
              // Additional points...
            ],
            borderColor: 'green',
            fill: false,
          },
          {
            label: 'Dataset 3',
            data: [
              { x: -5000, y: 2500 },
              { x: -5000, y: 17500 },
              // Additional points...
            ],
            borderColor: 'yellow',
            fill: false,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          x: {
            type: 'linear',
            position: 'bottom',
            min: -50000,
            max: 50000,
          },
          y: {
            type: 'linear',
            position: 'left',
            min: -20000,
            max: 20000,
          },
        },
      },
    });
  } catch (error) {
    console.error('Error initializing the chart:', error);
  }
});
</script>
