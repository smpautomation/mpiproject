<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Maintenance</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen text-slate-800 font-sans p-4">

    <div class="max-w-lg w-full bg-white border border-slate-200/80 rounded-2xl p-8 shadow-sm text-center">
        
        <!-- Logos Header -->
        <div class="flex items-center justify-center gap-6 mb-8">
            <img src="/photo/shinetsu.png" alt="Shin-Etsu Logo" class="h-12 w-auto object-contain">
            <div class="h-8 w-[1px] bg-slate-200"></div>
            <img src="/photo/AE.png" alt="AE Department Logo" class="h-24 w-auto object-contain">
        </div>
        
        <h1 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">
            Scheduled System Maintenance
        </h1>
        
        <p class="text-slate-600 mb-6 leading-relaxed">
            The system is currently undergoing a scheduled backup process to ensure data integrity and security. 
        </p>

        <!-- Time Window Card -->
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-6 text-left">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Maintenance Window</div>
            <div class="flex items-start gap-3">
                <span class="text-xl">📅</span>
                <div>
                    <p class="font-semibold text-slate-900">Sunday, July 19, 2026</p>
                    <p class="text-sm text-slate-500">4:00 PM – 6:00 PM</p>
                </div>
            </div>
        </div>
        
        <p class="text-sm text-slate-500 mb-2">
            The system will be completely unavailable during this period.
        </p>
        <p class="text-sm font-medium text-slate-700">
            Thank you for your patience and understanding.
        </p>
    </div>

</body>
</html>