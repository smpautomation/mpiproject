<template>
    <div class="flex flex-col min-h-screen">
    <Head title="MPIOnlineSystem" />
      <Navbar />
      <main class="flex-grow">
        <slot />
      </main>
      <Footer />
    </div>
  </template>

  <script setup>
  import Navbar from '@/Components/Navbar.vue';
  import Footer from '@/Components/Footer.vue';
  </script>
