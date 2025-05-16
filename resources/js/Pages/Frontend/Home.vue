<template>
    <Frontend>
      <div
        class="relative flex flex-col items-center justify-center min-h-screen px-8 py-12 mx-auto bg-cover bg-center"
        :style="{ backgroundImage: 'url(/photo/home_background.jpg)' }"
      >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

        <!-- Headline -->
        <div
          class="stamp-text relative z-10 flex flex-col items-center justify-center font-bold text-white text-5xl drop-shadow
                 opacity-0 -translate-y-3 animate-fade-in-down"
        >
          Welcome to MPI Website
        </div>

        <!-- Button -->
        <button
            @click="$inertia.visit('/manage')"
            class="mt-8 z-10 px-8 py-3 text-white font-semibold bg-gradient-to-r from-blue-600 to-cyan-500 rounded-full
                    shadow-lg hover:from-blue-500 hover:to-cyan-400 hover:scale-105 hover:shadow-xl
                    active:scale-95 active:shadow-md focus:outline-none focus:ring-4 focus:ring-cyan-300
                    transition-all duration-300 ease-out
                    opacity-0 animate-fade-in"
            >
            Begin
        </button>
      </div>
    </Frontend>
  </template>

  <script setup>
  import Frontend from '@/Layouts/FrontendLayout.vue';
  </script>

  <style scoped>
    .stamp-text {
        font-family: 'MyFont', sans-serif;
    }

    @keyframes fade-in-down {
    0% {
        opacity: 0;
        transform: translateY(-1.75rem);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
    }

    .animate-fade-in-down {
    animation: fade-in-down 1s ease-out forwards;
    }

    /* Add delay directly here (1.5s delay to match text animation) */
    @keyframes fade-in {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
    }

    .animate-fade-in {
        animation: fade-in 1s ease-out 1s forwards; /* 1.5s delay */
    }
  </style>
