<template>
<nav class="bg-white border-b border-gray-200 shadow dark:bg-gray-900 dark:border-gray-700">
  <div class="flex items-center justify-between max-w-screen-xl px-4 py-3 mx-auto">

    <!-- Enhanced MPI Logo with Magnetic Theme -->
    <div v-if="state.isAuthenticated && state.user" class="relative flex items-center space-x-4 cursor-pointer group">

        <!-- Animated Magnetic Logo SVG -->
        <div class="relative w-12 h-12">
            <!-- Outer glow effect -->
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-xl blur-md group-hover:opacity-60"></div>

            <!-- Main logo container -->
            <div class="relative w-12 h-12 p-2 transition-all duration-300 shadow-lg bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-2xl">

            <!-- Magnetic Field Icon -->
            <svg viewBox="0 0 100 100" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <!-- Magnetic field lines (animated) -->
                <g class="animate-pulse">
                <path d="M20 50 Q30 30, 50 30 Q70 30, 80 50" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                <path d="M20 50 Q30 70, 50 70 Q70 70, 80 50" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                </g>

                <!-- Magnet poles -->
                <g class="transition-transform group-hover:scale-105">
                <!-- North pole (left) -->
                <rect x="15" y="35" width="20" height="30" rx="3" fill="white" opacity="0.9"/>
                <text x="25" y="55" font-size="16" font-weight="bold" fill="#0891b2" text-anchor="middle">N</text>

                <!-- South pole (right) -->
                <rect x="65" y="35" width="20" height="30" rx="3" fill="white" opacity="0.9"/>
                <text x="75" y="55" font-size="16" font-weight="bold" fill="#14b8a6" text-anchor="middle">S</text>
                </g>

                <!-- Center attraction particles -->
                <circle cx="50" cy="50" r="3" fill="white" opacity="0.8" class="animate-ping"/>
                <circle cx="50" cy="50" r="2" fill="white"/>
            </svg>
            </div>
        </div>

        <!-- Enhanced Text with Gradient -->
        <div class="relative">
            <span class="block text-xl font-extrabold leading-none">
            <span class="text-transparent transition-all duration-300 bg-clip-text bg-gradient-to-r from-cyan-600 to-teal-600 group-hover:from-cyan-500 group-hover:to-teal-500 drop-shadow-sm">
                GBDP MPI
            </span>
            </span>
            <span class="block text-sm font-semibold text-gray-600 transition-colors duration-300 dark:text-gray-100 group-hover:text-gray-700 dark:group-hover:text-gray-300">
            Online System
            </span>
        </div>

        <!-- Hover indicator line -->
        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-500 to-teal-500 group-hover:w-full transition-all duration-300 rounded-full"></div>
    </div>




        <!-- Desktop Menu -->
    <ul class="items-center hidden gap-6 font-medium text-gray-700 md:flex dark:text-gray-300">
        <li v-for="item in filteredNavItems" :key="item.label" class="relative group">
            <template v-if="item.children && item.children.length">
                <span class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-100 transition-all duration-200 rounded-lg cursor-pointer hover:text-blue-600 hover:bg-blue-50">
                    {{ item.label }}
                    <svg class="w-4 h-4 ml-1.5 transition-transform duration-300 group-hover:rotate-180 text-gray-500 dark:text-gray-300 group-hover:text-blue-600"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
                <ul class="absolute left-0 z-50 invisible mt-1 transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 w-52 group-hover:opacity-100 group-hover:visible group-hover:translate-y-1">
                    <li v-for="child in item.children" :key="child.label" class="overflow-hidden first:rounded-t-lg last:rounded-b-lg">
                        <Link :href="route(child.route)"
                            class="block px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors duration-150 hover:bg-blue-50 hover:text-blue-600 hover:pl-5">
                            {{ child.label }}
                        </Link>
                    </li>
                </ul>
            </template>
            <template v-else>
                <Link :href="route(item.route)"
                    class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-100 transition-all duration-200 rounded-lg hover:text-blue-600 hover:bg-blue-50">
                    {{ item.label }}
                </Link>
            </template>
        </li>


        <li v-if="state.isAuthenticated && state.user"
            class="flex items-center px-4 py-2 space-x-3 transition-all duration-200 bg-white border border-blue-100 rounded-full shadow-md hover:shadow-lg hover:border-blue-200">

            <!-- Avatar -->
            <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full shadow-md bg-gradient-to-br from-blue-400 via-blue-500 to-cyan-500">
                {{ state.user.firstName.charAt(0).toUpperCase() }}
            </div>

            <!-- Name -->
            <span class="text-sm font-semibold text-gray-700 truncate max-w-[6rem]">
                <span class="flex flex-col text-sm font-semibold leading-tight text-gray-700">
                    <span class="truncate max-w-[8rem]">{{ state.user.firstName }}</span>
                    <span class="text-xs font-medium text-blue-600 whitespace-nowrap">({{ state.user.access_type }})</span>
                </span>
            </span>

            <!-- Logout -->
            <button
                @click="handleLogout"
                class="px-3 py-1.5 text-xs font-semibold text-white transition-all duration-200 rounded-full
                    bg-gradient-to-r from-blue-500 to-cyan-500
                    hover:from-blue-600 hover:to-cyan-600 hover:shadow-md
                    active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1"
            >
                Logout
            </button>
        </li>

    </ul>

    <!-- Mobile Toggle -->
    <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

    <!-- Mobile Menu -->
    <ul v-if="mobileOpen" class="p-4 space-y-2 border-t border-gray-200 md:hidden bg-gray-50 dark:bg-gray-100 dark:border-gray-100">
    <li v-for="item in filteredNavItems" :key="item.label">
        <template v-if="item.children && item.children.length">
        <span class="block py-2 font-medium">{{ item.label }}</span>
        <ul class="pl-4 space-y-1">
            <li v-for="child in item.children" :key="child.label">
            <Link :href="route(child.route)" class="block py-1">{{ child.label }}</Link>
            </li>
        </ul>
        </template>
        <template v-else-if="item.route">
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
    { label: 'Home', route: 'homePage', access: ['Basic User','Automation','Final Approver','Proxy Approver','Coating','Heat Treatment','Checking Approver', 'Preparation Approver'] },
    { label: 'Mass Production', route: 'massprod', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver','Final Approver','Coating','Heat Treatment'] },
    {
        label: 'Input Forms',
        access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver','Final Approver','Coating','Heat Treatment'],
        children: [
            { label: 'Heat Treatment', route: 'heat_treatment', access: ['Heat Treatment','Automation','Coating'] },
            { label: 'Coating', route: 'coating', access: ['Heat Treatment','Automation','Coating'] },
            { label: 'Film Pasting', route: 'film_pasting', access: ['Heat Treatment','Automation','Coating'] },
            { label: 'JH Curve', route: 'manage', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver'] },
            { label: 'MPI Report', route: 'reports', access: ['Automation','Final Approver','Proxy Approver','Coating','Heat Treatment','Checking Approver', 'Preparation Approver'] },
            { label: 'Initial Process', route: 'initial_process', access: ['Heat Treatment','Automation','Coating'] },
        ]
    },
    {
        label: 'Options',
        access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver','Final Approver','Coating','Heat Treatment'],
        children: [
            { label: 'Inspection', route: 'inspection', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver','Coating','Heat Treatment'] },
            { label: 'View List', route: 'viewList', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver','Final Approver'] },
            { label: 'Approval', route: 'approval', access: ['Automation','Final Approver','Proxy Approver'] },
            { label: 'Approval', route: 'approval_prepared', access: ['Preparation Approver'] },
            { label: 'Approval (Prep)', route: 'approval_prepared', access: ['Automation','Proxy Approver'] },
            { label: 'Approval', route: 'approval_checked', access: ['Checking Approver'] },
            { label: 'Approval (Checking)', route: 'approval_checked', access: ['Automation','Proxy Approver'] },
            { label: 'Furnace', route: 'furnace', access: ['Heat Treatment','Automation','Coating'] },
            { label: '1st & 2nd GBDP (Models)', route: 'second_gbdp_models', access: ['Heat Treatment','Automation','Coating'] },
            { label: 'View 1st & 2nd GBDP', route: 'view_second_gbdp', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver','Final Approver','Coating','Heat Treatment'] },
            { label: 'Special Instructions', route: 'instructions', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver'] },
            { label: 'Send Email', route: 'email', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver'] },
            { label: 'Send Email (Manual)', route: 'email_form_manual', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver'] },
            { label: 'Mias Factor Emp', route: 'mias_factor', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver'] },
            { label: 'HT Graph Patterns', route: 'htgraph_patterns', access: ['Heat Treatment','Automation','Coating'] },
            { label: 'Mass Prod Status', route: 'mass_prod_monitoring', access: ['Automation','Preparation Approver','Checking Approver','Proxy Approver','Final Approver','Coating','Heat Treatment'] }
        ]
    },
    { label: 'Admin', route: 'admin', access: ['Final Approver','Proxy Approver','Automation'] }
];

// Safe filtering
const filteredNavItems = computed(() => {
    if (!state.user) return [];

    return navItems
        .filter(item => item.access.includes(state.user.access_type))
        .map(item => {
            const filteredChildren = item.children
                ? item.children.filter(child => child.access.includes(state.user.access_type))
                : undefined;

            return {
                ...item,
                children: filteredChildren && filteredChildren.length ? filteredChildren : undefined
            };
        })
        .filter(item => item.children !== undefined || item.route); // hide parent if no accessible children
});
</script>

<style scoped>
@keyframes ping {
  75%, 100% {
    transform: scale(2);
    opacity: 0;
  }
}

.animate-ping {
  animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
