<template>
  <section class="dots-wrapper">
    <section class="dots-container">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
    </section>
    <p class="loading-text">{{ loadingMessage }}</p>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const messages = [
  "Aligning magnetic domains...",
  "Polarizing the process...",
  "Charging the flux capacitor...",
  "Calibrating magnetic field...",
  "Attracting data bits...",
  "Magnetizing the interface...",
  "Inducing current flow...",
  "Tuning magnetic resonance...",
  "Spinning up the magnetic moment...",
  "Forging the perfect field...",

  // New Additions
  "Cooling neodymium cores...",
  "Optimizing coercivity levels...",
  "Measuring hysteresis loop...",
  "Coating magnets with precision...",
  "Synchronizing field vectors...",
  "Running gaussmeter diagnostics...",
  "Stacking magnet arrays...",
  "Stabilizing magnetic flux...",
  "Crushing eddy currents...",
  "Amplifying magnetic strength...",
  "Mapping dipole alignments...",
  "Prepping sintering furnace...",
  "Balancing north and south...",
  "Filtering magnetic anomalies...",
  "Resisting demagnetization...",
  "Finalizing isotropic orientation...",
  "Warming up domain walls...",
  "Constructing magnetic lattice...",
];

// bossing easter egg
if (Math.random() < 0.033333333) {
    messages.push("板谷（いただに）さんの磁力には絶対に逆らえません。");
}

//SHIN-ETSU supremacy easter egg
if (Math.random() < 0.2) {
    messages.push("シンエツは絶対に最高です！");
}

const loadingMessage = ref('');

onMounted(() => {
  const randomIndex = Math.floor(Math.random() * messages.length);
  loadingMessage.value = messages[randomIndex];
});
</script>

<style scoped>
.dots-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  width: 100%;
}

.dots-container {
  display: flex;
  align-items: center;
  justify-content: center;
}
/*
.loading-text {
  margin-top: 2rem;
  font-size: 1.4rem;
  color: #769ffd;
  font-style: italic;
  font-weight: bolder;
  text-align: center;
  text-shadow: 0 0 10px #769ffd, 0 0 20px #a3bfff;
  animation: flicker 0.7s infinite alternate;
}*/

/* dot animation styles (as in your original code) */
.dot {
  height: 20px;
  width: 20px;
  margin-right: 10px;
  border-radius: 10px;
  background-color: #b3d4fc;
  animation: pulse-dots 1.5s infinite ease-in-out;
}

.dot:last-child {
  margin-right: 0;
}

.dot:nth-child(1) {
  animation-delay: -0.3s;
}

.dot:nth-child(2) {
  animation-delay: -0.1s;
}

.dot:nth-child(3) {
  animation-delay: 0.1s;
}
</style>

<style>
.loading-text {
  margin-top: 2rem;
  font-size: 1.4rem;
  color: #769ffd;
  font-style: italic;
  font-weight: bolder;
  text-align: center;
  text-shadow: 0 0 10px #769ffd, 0 0 20px #a3bfff;
  animation: flicker 0.7s infinite alternate;
}


@keyframes pulse-dots {
  0% {
    transform: scale(0.8);
    background-color: #b3d4fc;
    box-shadow: 0 0 0 0 rgba(178, 212, 252, 0.7);
  }

  50% {
    transform: scale(1.2);
    background-color: #6793fb;
    box-shadow: 0 0 0 10px rgba(178, 212, 252, 0);
  }

  100% {
    transform: scale(0.8);
    background-color: #b3d4fc;
    box-shadow: 0 0 0 0 rgba(178, 212, 252, 0.7);
  }
}

@keyframes flicker {
  0% {
    opacity: 1;
    text-shadow: 0 0 10px #769ffd, 0 0 20px #b2c7f3;
  }
  100% {
    opacity: 0.4;
    text-shadow: 0 0 5px #5f8dfa, 0 0 20px #5386fd;
  }
}

@media (max-width: 480px) {
  .dot {
    width: 14px;
    height: 14px;
    margin-right: 6px;
  }

  .loading-text {
    font-size: 1.1rem;
  }
}
</style>
