<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ออฟไลน์ - OLIS</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/olislogo.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { 
                font-family: 'IBM Plex Sans Thai', sans-serif; 
                background-color: #0f172a;
                color: #e2e8f0;
            }
            .premium-bg {
                background: radial-gradient(circle at top right, #3e0089 0%, transparent 40%),
                            radial-gradient(circle at bottom left, #7e22ce 0%, transparent 40%),
                            linear-gradient(to bottom, #0f172a, #1e1b4b);
                min-height: 100vh;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            }
        </style>
    </head>
    <body class="premium-bg antialiased flex items-center justify-center p-4 sm:p-8">
        <div class="max-w-md w-full glass-card rounded-[2.5rem] p-8 sm:p-12 text-center space-y-6">
            <!-- Animated Disconnected Cloud Icon -->
            <div class="w-20 h-20 mx-auto rounded-3xl bg-indigo-500/10 flex items-center justify-center relative">
                <span class="absolute inline-flex h-3 w-3 rounded-full bg-rose-500 opacity-75 animate-ping -top-1 -right-1"></span>
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5" />
                </svg>
            </div>
            
            <div class="space-y-2">
                <h1 class="text-2xl font-black text-white tracking-tight">ขาดการเชื่อมต่ออินเทอร์เน็ต</h1>
                <p class="text-xs text-purple-300 font-bold uppercase tracking-widest">Connection Offline</p>
            </div>

            <p class="text-sm text-slate-400 font-semibold leading-relaxed">
                ขออภัยด้วยครับ ดูเหมือนว่าขณะนี้อุปกรณ์ของคุณไม่ได้เชื่อมต่ออินเทอร์เน็ต โปรดตรวจสอบการเชื่อมต่อของคุณแล้วลองใหม่อีกครั้ง
            </p>

            <div class="pt-4">
                <button onclick="window.location.reload()" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95">
                    ลองใหม่อีกครั้ง
                </button>
            </div>
        </div>
    </body>
</html>
