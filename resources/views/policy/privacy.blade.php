<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>นโยบายความเป็นส่วนตัว - OLIS</title>
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
                <h1 class="text-3xl font-black text-white tracking-tight">นโยบายความเป็นส่วนตัว (Privacy Policy)</h1>
                <p class="text-xs text-purple-300 font-bold uppercase tracking-widest">Distance Learning Portal</p>
            </div>
            
            <div class="space-y-6 text-sm text-slate-300 leading-relaxed font-semibold">
                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">1. ข้อมูลส่วนบุคคลที่เราจัดเก็บ</h3>
                    <p>ระบบจัดการเรียนรู้ออนไลน์ (OLIS) จัดเก็บข้อมูลส่วนบุคคลของท่านที่จำเป็นต่อการจัดการศึกษา ได้แก่:
                        <ul class="list-disc list-inside pl-4 mt-2 space-y-1 text-slate-400">
                            <li>รหัสประจำตัวนักศึกษา และข้อมูลการเรียนที่ลงทะเบียน</li>
                            <li>ชื่อ-นามสกุล และข้อมูลสิทธิ์การเข้าใช้งานระบบ</li>
                            <li>ประวัติการทำแบบทดสอบออนไลน์ คะแนนสอบ และบันทึกกิจกรรม กพช.</li>
                            <li>บันทึกการรายงานคำร้องขอความช่วยเหลือจากศูนย์รับแจ้งปัญหา</li>
                        </ul>
                    </p>
                </section>

                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">2. วัตถุประสงค์ในการเก็บข้อมูล</h3>
                    <p>เราจัดเก็บรวบรวมข้อมูลส่วนบุคคลของท่านภายใต้ข้อตกลงและเงื่อนไขการให้บริการของสถาบัน เพื่อวัตถุประสงค์ในการดำเนินกิจการการเรียนการสอนทางไกล การประเมินผลการเรียนรู้ของวิชา การตรวจสอบความโปร่งใสความคืบหน้าของคำร้องความช่วยเหลือ และความปลอดภัยทางคอมพิวเตอร์ตามสิทธิการเรียนการสอน</p>
                </section>

                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">3. การเก็บรักษาและระยะเวลา</h3>
                    <p>ข้อมูลส่วนบุคคลทั้งหมด of ท่านจะถูกเก็บรักษาไว้ในระบบฐานข้อมูลอย่างปลอดภัยภายใต้มาตรการทางเทคนิคที่เข้มงวด และจะถูกลบหรือทำลายอย่างเป็นระบบเมื่อบัญชีการเรียนรู้ของท่านสิ้นสุดสภาพหรือเมื่อสถาบันประเมินว่าหมดความจำเป็นตามกรอบการศึกษา</p>
                </section>

                <section class="space-y-2">
                    <h3 class="text-base font-bold text-white">4. สิทธิของท่าน</h3>
                    <p>ตามกฎหมาย PDPA ท่านมีสิทธิ์ในการขอเข้าถึง ขอสำเนา ขอแก้ไขข้อมูลส่วนบุคคลให้ถูกต้อง หรือคัดค้านการเก็บรวบรวมข้อมูลใดๆ ในระบบ โดยสามารถประสานงานตรวจสอบติดต่อได้ผ่านหน้าศูนย์รับแจ้งปัญหาของระบบหรือเจ้าหน้าที่เทคโนโลยีสารสนเทศของสถาบันการศึกษาโดยตรง</p>
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
