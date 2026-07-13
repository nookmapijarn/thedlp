<x-admin-layout>
    <div class="p-4 sm:ml-64 max-w-7xl mx-auto space-y-6">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-950/30 dark:text-green-300 border border-green-200 dark:border-green-800/40 flex items-center gap-3 animate-in fade-in" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header Block -->
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[160px]">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-xs font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    Admin Petitions Management
                </span>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">ระบบจัดการคำร้องออนไลน์ของนักศึกษา</h1>
                <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-semibold max-w-3xl">
                    หน้าควบคุมสำหรับแอดมินหรือเจ้าหน้าที่ตรวจสอบ คำอนุมัติคำร้อง เอกสารใบรับรอง และรายงานขอพักการเรียนของนักเรียน/นักศึกษาทั้งหมดในสถาบัน
                </p>
            </div>
        </div>

        <!-- Main Content List -->
        <div class="space-y-4" x-data="{ activeFilter: 'all' }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-650" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    รายการยื่นคำร้องทั้งหมด
                </h2>

                <!-- Category Filters -->
                <div class="flex gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                    <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-purple-650 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-gray-800 dark:text-slate-400'" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all">ทั้งหมด</button>
                    <button @click="activeFilter = 'pending'" :class="activeFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-gray-800 dark:text-slate-400'" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all">รอดำเนินการ</button>
                    <button @click="activeFilter = 'in_progress'" :class="activeFilter === 'in_progress' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-gray-800 dark:text-slate-400'" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all">กำลังดำเนินการ</button>
                    <button @click="activeFilter = 'approved'" :class="activeFilter === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-gray-800 dark:text-slate-400'" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all">อนุมัติแล้ว</button>
                    <button @click="activeFilter = 'rejected'" :class="activeFilter === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 dark:bg-gray-800 dark:text-slate-400'" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all">ปฏิเสธ</button>
                </div>
            </div>

            <!-- Petitions table list -->
            <div class="border border-slate-150 dark:border-gray-700/80 rounded-2xl shadow-sm overflow-hidden bg-white dark:bg-gray-800">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-[10px] text-gray-700 uppercase bg-gray-150 dark:bg-gray-700/60 dark:text-gray-300 font-black tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-4">ประเภท</th>
                                <th scope="col" class="px-6 py-4">หัวข้อคำร้อง</th>
                                <th scope="col" class="px-6 py-4">ผู้ยื่นคำร้อง (รหัสนักศึกษา)</th>
                                <th scope="col" class="px-6 py-4">วันที่ยื่น</th>
                                <th scope="col" class="px-6 py-4">สถานะ</th>
                                <th scope="col" class="px-6 py-4 text-right">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150 dark:divide-gray-750">
                            @forelse($petitions as $petition)
                                <tr x-show="activeFilter === 'all' || activeFilter === '{{ $petition->status }}'" 
                                    x-data="{ isExpanded: false }"
                                    class="hover:bg-slate-50/50 dark:hover:bg-gray-750/30 transition-colors">
                                    
                                    <td class="px-6 py-4" @click="isExpanded = !isExpanded">
                                        <span class="inline-flex px-2.5 py-1 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-black text-[10px] rounded-lg">
                                            {{ $petition->type }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 font-black text-slate-800 dark:text-white cursor-pointer" @click="isExpanded = !isExpanded">
                                        {{ $petition->title }}
                                        <span class="block text-[10px] text-slate-400 font-normal mt-0.5 max-w-sm truncate">
                                            {{ $petition->description }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300" @click="isExpanded = !isExpanded">
                                        {{ $petition->user->name ?? 'ไม่พบชื่อ' }}
                                        <span class="block text-[10px] font-mono text-slate-400 mt-0.5">ID: {{ $petition->student_id }}</span>
                                    </td>

                                    <td class="px-6 py-4 text-slate-400 font-bold" @click="isExpanded = !isExpanded">
                                        {{ $petition->created_at->addYears(543)->locale('th')->isoFormat('D MMM YY, HH:mm') }} น.
                                    </td>

                                    <td class="px-6 py-4" @click="isExpanded = !isExpanded">
                                        @if($petition->status == 'pending')
                                            <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 text-[10px] font-black rounded-lg">รอดำเนินการ</span>
                                        @elseif($petition->status == 'in_progress')
                                            <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-lg">กำลังดำเนินการ</span>
                                        @elseif($petition->status == 'approved')
                                            <span class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black rounded-lg">อนุมัติแล้ว</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 text-[10px] font-black rounded-lg">ปฏิเสธ</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <button @click="isExpanded = !isExpanded" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-200 rounded-xl font-black transition-all">
                                            <span x-text="isExpanded ? 'ซ่อน' : 'พิจารณา'"></span>
                                        </button>
                                    </td>

                                    <!-- Expanded details inside table row -->
                                    <template x-if="isExpanded">
                                        <tr class="bg-slate-50/50 dark:bg-gray-900/40">
                                            <td colspan="6" class="px-8 py-6 border-t border-slate-100 dark:border-slate-800">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <!-- Left Side: Petition Details -->
                                                    <div class="space-y-4">
                                                        <h4 class="text-xs font-black text-purple-700 dark:text-purple-400 uppercase tracking-wider">รายละเอียดข้อความคำร้อง</h4>
                                                        <div class="bg-white dark:bg-gray-850 p-4.5 rounded-xl border border-slate-100 dark:border-gray-800 text-slate-700 dark:text-slate-300 font-semibold leading-relaxed">
                                                            {{ $petition->description }}
                                                        </div>

                                                        @if($petition->file_path)
                                                            <div class="flex items-center space-x-2">
                                                                <svg class="w-4 h-4 text-purple-650" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                                                </svg>
                                                                <a href="{{ asset('storage/' . $petition->file_path) }}" target="_blank" class="text-xs font-black text-purple-650 hover:underline">
                                                                    ดาวน์โหลดเอกสารแนบผู้ยื่นคำร้อง
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Right Side: Status Update and Comment Form -->
                                                    <form action="{{ route('admin.petitions.update', $petition->id) }}" method="POST" class="space-y-4 bg-white dark:bg-gray-850 p-5 rounded-2xl border border-slate-100 dark:border-gray-800 shadow-sm">
                                                        @csrf
                                                        @method('PUT')

                                                        <h4 class="text-xs font-black text-slate-800 dark:text-white">ประเมินและอัปเดตคำร้อง</h4>
                                                        
                                                        <!-- Status drop selector -->
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">สถานะคำร้อง</label>
                                                            <select name="status" class="w-full text-xs font-bold bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl px-3 py-2">
                                                                <option value="pending" {{ $petition->status == 'pending' ? 'selected' : '' }}>รอดำเนินการ (Pending)</option>
                                                                <option value="in_progress" {{ $petition->status == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ (In Progress)</option>
                                                                <option value="approved" {{ $petition->status == 'approved' ? 'selected' : '' }}>อนุมัติคำร้อง (Approved)</option>
                                                                <option value="rejected" {{ $petition->status == 'rejected' ? 'selected' : '' }}>ปฏิเสธคำร้อง (Rejected)</option>
                                                            </select>
                                                        </div>

                                                        <!-- Admin comment input -->
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">บันทึกความคิดเห็นหรือเหตุผลการปฏิเสธ</label>
                                                            <textarea name="admin_comment" rows="3" placeholder="ระบุเหตุผล ข้อคิดเห็น หรือหมายเหตุให้ผู้ยื่นคำร้องทราบ..." class="w-full text-xs font-bold bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl px-3 py-2">{{ $petition->admin_comment }}</textarea>
                                                        </div>

                                                        <!-- Submit comment/status -->
                                                        <button type="submit" class="w-full py-2 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-xl text-xs font-black transition-colors shadow-sm">
                                                            บันทึกการพิจารณาคำร้อง
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-bold bg-white dark:bg-gray-800">
                                        <span class="text-3xl block mb-2">✉️</span>
                                        ไม่พบประวัติการยื่นคำร้องออนไลน์ตามสถานะนี้ในระบบ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
