<template>
<nav class="bg-white border-b border-gray-200 shadow dark:bg-gray-900 dark:border-gray-700">
  <div class="max-w-screen-xl mx-auto px-4 py-3 flex items-center justify-between">

    <!-- Logo -->
    <div v-if="state.isAuthenticated && state.user" class="flex items-center space-x-4 group cursor-pointer relative">
    <!-- Logo Image with subtle hover effect -->
    <img
        src="photo/smp_logo.png"
        class="h-10 transition-transform duration-300 ease-in-out group-hover:scale-105 group-hover:rotate-2"
        alt="SMP Logo"
    />

    <!-- Text with gradient and subtle shadow on hover -->
    <span class="relative z-10 text-xl font-extrabold text-gray-900 dark:text-white transition-all duration-300 group-hover:drop-shadow-lg">
        MPI
        <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-400">Online</span>
        <span class="text-gray-400"> System</span>
    </span>
    </div>




    <!-- Desktop Menu -->
    <ul class="hidden md:flex items-center gap-6 font-medium text-gray-700 dark:text-gray-300">
      <li v-for="item in filteredNavItems" :key="item.label" class="relative group">
        <template v-if="item.children && item.children.length">
          <span class="flex items-center cursor-pointer transition-colors hover:text-blue-600">
            {{ item.label }}
            <svg class="ml-1 w-3 h-3 transition-transform duration-300 group-hover:rotate-180"
              fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </span>
          <ul class="absolute left-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <li v-for="child in item.children" :key="child.label">
                <Link :href="route(child.route)" class="block px-4 py-2 hover:bg-gray-100">
                    {{ child.label }}
                </Link>
            </li>
          </ul>
        </template>
        <template v-else>
          <Link :href="route(item.route)" class="hover:text-blue-600 transition">{{ item.label }}</Link>
        </template>
      </li>


        <!-- User Info + Logout -->
        <li v-if="state.isAuthenticated && state.user" class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 rounded-full px-3 py-1 shadow-sm transition-all duration-200 hover:shadow-md">

        <!-- Avatar -->
        <div class="flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-semibold text-sm shadow-inner">
            {{ state.user.firstName.charAt(0).toUpperCase() }}
        </div>

        <!-- Name -->
        <span class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate max-w-[6rem]">
            {{ state.user.firstName }}
        </span>

        <!-- Logout -->
        <button
            @click="handleLogout"
            class="px-2 py-1 text-sm font-semibold text-red-500 bg-red-50 rounded-full hover:bg-red-100 hover:text-red-700 dark:bg-red-700 dark:text-red-200 dark:hover:bg-red-600 dark:hover:text-red-50 transition-colors duration-200"
        >
            Logout
        </button>
        </li>

    </ul>

    <!-- Mobile Toggle -->
    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <ul v-if="mobileOpen" class="md:hidden bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 space-y-2">
    <li v-for="item in filteredNavItems" :key="item.label">
      <template v-if="item.children && item.children.length">
        <span class="block py-2 font-medium">{{ item.label }}</span>
        <ul class="pl-4 space-y-1">
          <li v-for="child in item.children" :key="child.label">
            <Link :href="route(child.route)" class="block py-1">{{ child.label }}</Link>
          </li>
        </ul>
      </template>
      <template v-else>
        <Link :href="route(item.route)" class="block py-2">{{ item.label }}</Link>
      </template>
    </li>
  </ul>
</nav>
</template>


<script setup>
import { Link } from '@inertiajs/vue3';
import { useAuth } from '@/Composables/useAuth.js';
import { computed, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const { state, logout } = useAuth();
const mobileOpen = ref(false);

const handleLogout = async () => {
    logUserLogout().then(() => {
        logout();
        Inertia.visit('/');
    });
};

const logUserLogout = async () => {
    if (!state.user) return;
    try{
        await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: 'has logged out',
            section: 'System',
        });
    } catch(err) { console.error(err); }
};

// Nav items with children
const navItems = [
  { label: 'Home', route: 'homePage', access: ['Basic User','Automation','Final Approver','Proxy Approver','Coating','Heat Treatment'] },
  { label: 'Mass Production', route: 'massprod', access: ['Automation'] },
  { label: 'Input Forms', access: ['Automation','Basic User','Preparation Approver','Checking Approver','Hybrid Approver','Bypass Approver','Proxy Approver'], children: [
      { label: 'Heat Treatment', route: 'heat_treatment' },
      { label: 'Coating', route: 'coating' },
      { label: 'MPI', route: 'manage' }
    ]
  },
  { label: 'Others', access: ['Automation','Basic User','Preparation Approver','Checking Approver','Hybrid Approver','Bypass Approver','Proxy Approver'], children: [
      { label: 'Inspection', route: 'inspection' },
      { label: 'View List', route: 'viewList' },
      { label: 'Approval', route: 'approval' }
    ]
  },
  { label: 'Admin', route: 'admin', access: ['Final Approver','Proxy Approver','Automation','Bypass Approver'] }
];

// Safe filtering
const filteredNavItems = computed(() => {
    if (!state.user) return [];
    return navItems
      .filter(item => item.access.includes(state.user.access_type))
      .map(item => ({
          ...item,
          children: item.children ? [...item.children] : undefined
      }));
});
</script>
