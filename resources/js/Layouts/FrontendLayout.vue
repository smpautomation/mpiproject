<template>
    <div class="flex flex-col min-h-screen">
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
    import { onMounted } from 'vue'
    import { useAuth } from '@/Composables/useAuth'

    const { fetchUser } = useAuth()

    onMounted(() => {
        fetchUser()
    })
  </script>
