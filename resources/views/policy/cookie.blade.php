<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>นโยบายคุกกี้ - OLIS</title>
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
        <div class="max-w-3xl w-full glass-card rounded-[2.5rem] p-8 sm:p-12 space-y-8 my-8">
            <div class="space-y-3 border-b border-white/10 pb-6">
                <h1 class="text-3xl font-black text-white tracking-tight">นโยบายคุกกี้ (Cookie Policy)</h1>
                <p class="text-xs text-purple-300 font-bold uppercase tracking-widest">Distance Learning Portal</p>
            </div>
            
            <div class="space-y-6 text-sm text-slate-300 leading-relaxed font-semibold">
                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">1. คุกกี้คืออะไร</h3>
                    <p>คุกกี้ (Cookies) คือไฟล์ข้อความขนาดเล็กที่จัดเก็บไว้ในคอมพิวเตอร์หรืออุปกรณ์พกพาของท่านเมื่อท่านเข้าเยี่ยมชมเว็บไซต์ คุกกี้ช่วยให้เว็บไซต์จดจำข้อมูลและการตั้งค่าบางประการของท่านได้ เพื่อความสะดวกและเพิ่มความรวดเร็วในการใช้งานในครั้งถัดไป</p>
                </section>

                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">2. การใช้งานคุกกี้ภายในระบบ</h3>
                    <p>ระบบจัดการเรียนรู้ออนไลน์ OLIS ใช้งานเฉพาะ **"คุกกี้ที่จำเป็นอย่างยิ่ง (Strictly Necessary Cookies)"** เพื่อความมั่นคงปลอดภัยและการรักษาสถานภาพการใช้งานเท่านั้น:
                        <ul class="list-disc list-inside pl-4 mt-2 space-y-1.5 text-slate-400">
                            <li><strong class="text-white">laravel_session:</strong> คุกกี้ระบุเซสชันของผู้เรียนสำหรับใช้รักษาสถานะล็อกอินเมื่อเปลี่ยนหน้าบทเรียน</li>
                            <li><strong class="text-white">XSRF-TOKEN:</strong> คุกกี้ความปลอดภัยสำหรับการตรวจสอบคำขอยื่นฟอร์มเพื่อป้องกันการโจมตีทางไซเบอร์แบบ Cross-site Scripting</li>
                        </ul>
                    </p>
                </section>

                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">3. ไม่มีการใช้งานคุกกี้บุคคลภายนอก</h3>
                    <p>ระบบการศึกษาทางไกลนี้ไม่มีการติดตั้งตัวติดตาม คุกกี้วิเคราะห์พฤติกรรมผู้ใช้งาน หรือการนำส่งข้อมูลเพื่อการตลาดและโฆษณาเชิงพาณิชย์ของบุคคลภายนอก (เช่น Google Analytics หรือ Facebook Pixel) ใดๆ ทั้งสิ้น</p>
                </section>

                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">4. วิธีจัดการคุกกี้ของท่าน</h3>
                    <p>ท่านสามารถเปิด/ปิด หรือลบคุกกี้ทั้งหมดจากเบราว์เซอร์ของท่านได้ผ่านการตั้งค่าเบราว์เซอร์ของท่าน อย่างไรก็ตาม หากท่านปิดการใช้งานคุกกี้ที่จำเป็นของระบบ ท่านจะไม่สามารถลงชื่อเข้าเรียนออนไลน์ ทำแบบทดสอบ หรือเข้าถึงบริการบทเรียนออนไลน์ในระบบ OLIS ได้</p>
                </section>
            </div>

            <div class="pt-6 border-t border-white/10 flex justify-between items-center flex-wrap gap-4">
                <span class="text-[10px] text-slate-500 font-bold">ปรับปรุงล่าสุดเมื่อ: 9 กรกฎาคม 2569</span>
                <a href="/" class="px-6 py-2.5 bg-white text-slate-900 hover:bg-slate-100 font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95">
                    ย้อนกลับหน้าแรก
                </a>
            </div>
        </div>
    </body>
</html>
