<template>
  <div>
    <nav class="bg-white border-gray-200 dark:bg-gray-900 top-0 left-0 right-0 z-50">
      <div class="flex flex-wrap items-center justify-between max-w-screen-xl p-4 mx-auto">

        <!-- Logo and Title -->
        <a class="flex items-center space-x-3 rtl:space-x-reverse">
          <img src="photo/smp_logo.png" class="h-8" alt="SMP Logo" />
          <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">
            MPI Online System
          </span>
        </a>

        <!-- Mobile Toggle -->
        <button
          data-collapse-toggle="navbar-default"
          type="button"
          class="inline-flex items-center justify-center w-10 h-10 p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="navbar-default"
          aria-expanded="false"
        >
          <span class="sr-only">Open main menu</span>
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M1 1h15M1 7h15M1 13h15" />
          </svg>
        </button>

        <!-- Navigation Items -->
        <div class="hidden w-full md:flex md:items-center md:w-auto" id="navbar-default">
          <ul
            v-if="state.isAuthenticated"
            class="flex flex-col md:flex-row items-center gap-4 md:gap-8 p-4 md:p-0 mt-4 md:mt-0 font-medium border border-gray-100 rounded-lg md:border-0 md:bg-white bg-gray-50 dark:bg-gray-800 md:dark:bg-transparent dark:border-gray-700"
          >
            <li v-for="(item, index) in filteredNavItems" :key="index">
              <Link
                :href="route(item.route)"
                :class="{
                  'text-white bg-blue-700 rounded-sm md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500': route().current(item.route)
                }"
              >
                {{ item.label }}
              </Link>
            </li>

            <!-- User Info & Logout -->
            <li class="flex items-center">
              <div class="flex items-center gap-3 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full shadow-sm">
                <!-- User Initial Avatar -->
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 font-bold">
                  {{ state.user.firstName.charAt(0).toUpperCase() }}
                </div>
                <!-- Username -->
                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                  {{ state.user.firstName }}
                </span>
                <!-- Logout Button -->
                <button
                @click="handleLogout"
                class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-semibold transition-colors"
                >
                <!-- Power Icon -->
                <span class="relative flex items-center justify-center w-5 h-5">
                    <div class="w-5 h-5 border-2 border-current rounded-full relative">
                    <div class="absolute top-[-4px] left-1/2 -translate-x-1/2 w-2 h-2 bg-white dark:bg-gray-900 z-10 rounded-full"></div>
                    </div>
                    <div class="absolute w-0.5 h-2 bg-current top-[-2px] z-20"></div>
                </span>
                <span>Logout</span>
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useAuth } from '@/Composables/useAuth.js';
import { computed, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const { state, logout } = useAuth();

const handleLogout = () => {
  logout();
  Inertia.visit('/');
};

// Define the navigation lists here
const navItems = [
  { label: 'Home', route: 'homePage', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Automation', 'Final Approver', 'Proxy Approver'] },
  { label: 'Manage', route: 'manage', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Automation'] },
  { label: 'Report', route: 'reports', access: ['Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Automation'] },
  { label: 'Inspection', route: 'inspection', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Automation'] },
  { label: 'View List', route: 'viewList', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Automation'] },
  { label: 'Approval', route: 'approval', access: ['Final Approver', 'Proxy Approver', 'Bypass Approver', 'Automation'] },
  { label: 'Admin', route: 'admin', access: ['Final Approver', 'Proxy Approver', 'Automation'] }
];

// Filter nav items based on user access
const filteredNavItems = computed(() => {
  if (!state.user) return [];
  return navItems.filter(item => item.access.includes(state.user.access_type));
});
</script>
