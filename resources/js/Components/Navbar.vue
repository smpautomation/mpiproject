<template>
  <div>
    <nav class="top-0 left-0 right-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-700">
      <div class="flex items-center justify-between max-w-screen-xl px-4 py-3 mx-auto md:px-6">

        <!-- Logo + Title -->
        <div class="flex items-center space-x-4 md:mr-10">
          <img src="photo/smp_logo.png" class="h-9" alt="SMP Logo" />
          <span class="text-xl font-bold text-gray-800 dark:text-white whitespace-nowrap">
            MPI Online System
          </span>
        </div>

        <!-- Navigation -->
        <div class="hidden md:flex md:items-center md:w-auto" id="navbar-default">
          <ul
            v-if="state.isAuthenticated"
            class="flex flex-col items-center gap-4 p-4 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:gap-6 md:p-0 md:mt-0 md:border-0 md:bg-transparent dark:bg-gray-800 md:dark:bg-transparent dark:border-gray-700"
          >
            <!-- Nav Links -->
            <li v-for="(item, index) in filteredNavItems" :key="index">
              <Link
                :href="route(item.route)"
                class="block px-4 py-2 transition-all duration-150 rounded-md whitespace-nowrap"
                :class="{
                  'bg-blue-600 text-white md:bg-transparent md:text-blue-600 md:font-bold': route().current(item.route),
                  'text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400': !route().current(item.route)
                }"
              >
                {{ item.label }}
              </Link>
            </li>

            <!-- User Info + Logout -->
            <li class="flex items-center px-4 py-2 space-x-4 bg-gray-100 rounded-full shadow-inner dark:bg-gray-700">
              <!-- Avatar -->
              <div class="flex items-center justify-center font-semibold text-white bg-blue-500 rounded-full w-9 h-9 whitespace-nowrap">
                {{ state.user.firstName.charAt(0).toUpperCase() }}
              </div>
              <!-- Name -->
              <span class="text-sm font-medium text-gray-800 dark:text-gray-100 whitespace-nowrap">
                {{ state.user.firstName }}
              </span>
              <!-- Logout -->
              <button
                @click="handleLogout"
                class="flex items-center gap-2 text-sm font-semibold text-red-500 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
              >
                <!-- Power Icon -->
                <span class="relative flex items-center justify-center w-5 h-5">
                  <div class="relative w-5 h-5 border-2 border-current rounded-full">
                    <div class="absolute w-2 h-2 bg-white dark:bg-gray-900 rounded-full top-[-4px] left-1/2 -translate-x-1/2 z-10"></div>
                  </div>
                  <div class="absolute w-0.5 h-2 bg-current top-[-2px] z-20"></div>
                </span>
                <span>Logout</span>
              </button>
            </li>
          </ul>
        </div>

        <!-- Mobile Menu Toggle -->
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

const handleLogout = async () => {
    logUserLogout()
    .then(() => {
        logout();
        Inertia.visit('/');
    });
};

const logUserLogout = async () => {
    try{
        const responseUserLogin = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: 'has logged out',
            section: 'System',
        });

        console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('responseUserLogin post request failed: ',error);
    }
}

// Define the navigation lists here
const navItems = [
  { label: 'Home', route: 'homePage', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Automation', 'Final Approver', 'Proxy Approver'] },
  { label: 'Manage', route: 'manage', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Proxy Approver', 'Automation'] },
  { label: 'Report', route: 'reports', access: ['Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Proxy Approver', 'Automation'] },
  { label: 'Inspection', route: 'inspection', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Proxy Approver', 'Automation'] },
  { label: 'View List', route: 'viewList', access: ['Basic User', 'Preparation Approver', 'Checking Approver', 'Hybrid Approver', 'Bypass Approver', 'Proxy Approver', 'Automation'] },
  { label: 'Approval', route: 'approval', access: ['Final Approver', 'Proxy Approver', 'Bypass Approver', 'Automation'] },
  { label: 'Admin', route: 'admin', access: ['Final Approver', 'Proxy Approver', 'Automation'] }
];

// Filter nav items based on user access
const filteredNavItems = computed(() => {
  if (!state.user) return [];
  return navItems.filter(item => item.access.includes(state.user.access_type));
});
</script>
