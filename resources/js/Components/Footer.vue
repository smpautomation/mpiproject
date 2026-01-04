<template>
    <footer class="w-full py-4 mt-auto bg-black border-t border-gray-800">
        <div class="px-4">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <!-- Logo & Company Section -->
                <div class="flex items-center gap-4 group">
                    <img
                        src="photo/AE.png"
                        @click="Inertia.visit('/automation')"
                        class="w-20 h-20 transition-all duration-300 ease-in-out group-hover:scale-110 filter brightness-100 group-hover:brightness-110"
                        alt="AE Logo"
                    />
                    <div class="text-left">
                        <p class="text-base font-bold tracking-wide text-white">Shin-Etsu Magnetics Philippines, Inc.</p>
                        <p class="mt-1 text-xs font-medium text-teal-400">Automation Engineering Department</p>
                    </div>
                </div>

                <!-- Divider (hidden on mobile) -->
                <div class="hidden w-px h-12 md:block bg-gradient-to-b from-transparent via-teal-500/30 to-transparent"></div>

                <!-- Contact Section -->
                <div class="space-y-1 text-center md:text-right">
                    <p class="text-sm text-gray-300">
                        <span class="text-gray-500">Contact:</span> <span class="font-medium text-white">Local 619 / 206</span>
                    </p>
                    <p class="text-sm text-gray-300">
                        <span class="text-gray-500">Email:</span> <a href="mailto:ae@smp.com.ph" class="font-medium transition-colors text-cyan-400 hover:text-cyan-300">ae@smp.com.ph</a>
                    </p>
                    <p class="mt-2 text-xs text-gray-500">&copy; 2025 All rights reserved</p>
                </div>
            </div>

            <!-- Bottom Accent Line -->
            <div class="mt-6 h-0.5 w-full bg-gradient-to-r from-transparent via-cyan-500/60 to-transparent"></div>
        </div>
    </footer>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia';
</script>
