<x-admin-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        
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
                    Admin Moderation
                </span>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">จัดการคลังผลงานความรู้สั้น</h1>
                <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-semibold max-w-3xl">
                    หน้าควบคุมและคัดกรองเนื้อหาบทเรียนความรู้สั้นสไตล์ TikTok (วิดีโอ/สไลด์รูปภาพ) ทั้งหมดที่ถูกเผยแพร่โดยคุณครูในระบบ
                </p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-650" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    รายการผลงานทั้งหมดที่เผยแพร่
                </h2>
            </div>

            <!-- Table of Short Videos -->
            <div class="relative overflow-x-auto border border-gray-150 dark:border-gray-700 rounded-2xl shadow-sm bg-slate-50/50 dark:bg-slate-900/30">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-[10px] text-gray-700 uppercase bg-gray-100 dark:bg-gray-700/60 dark:text-gray-300 font-black tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4">ประเภท/สื่อ</th>
                            <th scope="col" class="px-6 py-4">หัวข้อบทเรียน</th>
                            <th scope="col" class="px-6 py-4">ผู้เขียน / ผู้สอน</th>
                            <th scope="col" class="px-6 py-4">หลักสูตรที่เชื่อมโยง</th>
                            <th scope="col" class="px-6 py-4 text-center">สถิติการรับชม</th>
                            <th scope="col" class="px-6 py-4">วันที่เผยแพร่</th>
                            <th scope="col" class="px-6 py-4 text-right">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($shorts as $short)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="w-12 h-20 bg-black rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative flex items-center justify-center flex-shrink-0">
                                        @if($short->type === 'images')
                                            @php
                                                $firstImg = is_array($short->images) && count($short->images) > 0 ? $short->images[0] : null;
                                            @endphp
                                            @if($firstImg)
                                                <img src="{{ asset('storage/' . $firstImg) }}" class="w-full h-full object-cover opacity-80">
                                            @else
                                                <span class="text-white text-[8px] font-black">ไม่มีรูป</span>
                                            @endif
                                            <span class="absolute top-1 right-1 bg-black/60 text-[8px] text-white px-1.5 py-0.5 rounded font-black z-10">
                                                🖼️{{ count($short->images) }}
                                            </span>
                                        @else
                                            <video class="w-full h-full object-cover opacity-75" preload="metadata" muted>
                                                <source src="{{ asset('storage/' . $short->video_path) }}#t=0.5" type="video/mp4">
                                            </video>
                                            <span class="absolute inset-0 flex items-center justify-center bg-black/30 pointer-events-none">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    <div class="max-w-xs">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="text-[8px] px-1.5 py-0.5 rounded font-black {{ $short->type === 'images' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $short->type === 'images' ? 'SLIDE' : 'VIDEO' }}
                                            </span>
                                        </div>
                                        <p class="truncate leading-relaxed" title="{{ $short->title }}">{{ $short->title }}</p>
                                        @if($short->description)
                                            <span class="text-[10px] text-gray-400 font-semibold block mt-1 truncate" title="{{ $short->description }}">{{ $short->description }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-750 dark:text-gray-300">
                                    {{ $short->teacher->name }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($short->course)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-300 text-[10px] font-bold border border-purple-100 dark:border-purple-800/40">
                                            {{ $short->course->title }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">ความรู้ทั่วไป</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1 text-[10px] font-bold text-gray-600 dark:text-gray-400">
                                        <span>👁️ {{ $short->views_count }} วิว</span>
                                        <span>❤️ {{ $short->likes_count }} ถูกใจ</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-450 dark:text-gray-500">
                                    {{ $short->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-3">
                                        <a href="{{ route('shorts.index') }}?id={{ $short->id }}" target="_blank" class="text-purple-650 hover:underline font-bold text-[10px] flex items-center gap-1">
                                            ดูหน้าเว็บ
                                        </a>

                                        <form action="{{ route('admin.shorts.destroy', $short->id) }}" method="POST" onsubmit="return confirm('ยืนยันความต้องการที่จะลบโพสต์นี้ถาวรโดยผู้ดูแลระบบ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-650 hover:underline font-bold text-[10px] flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                ลบ
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-gray-450 dark:text-gray-500 font-bold">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-700 animate-pulse" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"></path>
                                        </svg>
                                        <span>ไม่มีโพสต์การเรียนรู้สั้นในระบบ</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $shorts->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
