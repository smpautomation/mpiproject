<template>
    <footer class="w-full bg-black border-t border-gray-800 py-4 mt-auto">
        <div class="px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Logo & Company Section -->
                <div class="flex items-center gap-4 group">
                    <img
                        src="photo/AE.png"
                        class="h-20 w-20 transition-all duration-300 ease-in-out group-hover:scale-110 filter brightness-100 group-hover:brightness-110"
                        alt="AE Logo"
                    />
                    <div class="text-left">
                        <p class="text-base font-bold text-white tracking-wide">Shin-Etsu Magnetics Philippines, Inc.</p>
                        <p class="text-xs text-teal-400 font-medium mt-1">Automation Engineering Department</p>
                    </div>
                </div>

                <!-- Divider (hidden on mobile) -->
                <div class="hidden md:block h-12 w-px bg-gradient-to-b from-transparent via-teal-500/30 to-transparent"></div>

                <!-- Contact Section -->
                <div class="text-center md:text-right space-y-1">
                    <p class="text-sm text-gray-300">
                        <span class="text-gray-500">Contact:</span> <span class="font-medium text-white">Local 619 / 206</span>
                    </p>
                    <p class="text-sm text-gray-300">
                        <span class="text-gray-500">Email:</span> <a href="mailto:ae@smp.com.ph" class="font-medium text-cyan-400 hover:text-cyan-300 transition-colors">ae@smp.com.ph</a>
                    </p>
                    <p class="text-xs text-gray-500 mt-2">&copy; 2025 All rights reserved</p>
                </div>
            </div>

            <!-- Bottom Accent Line -->
            <div class="mt-6 h-0.5 w-full bg-gradient-to-r from-transparent via-cyan-500/60 to-transparent"></div>
        </div>
    </footer>
</template>
