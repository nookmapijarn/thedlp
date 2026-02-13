<x-app-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-gray-800 leading-tight">
            {{ __('กิจกรรม กพช.') }}
        </h6>
    </x-slot>

    {{-- Content Container --}}
    <div class="p-4">
        <div class="max-w-4xl mx-auto">
            
            {{-- Card Container --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-slate-700">ประวัติการเข้าร่วมกิจกรรม</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">ภาคเรียนที่</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">กิจกรรม</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">จำนวนชั่วโมง</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($activity as $act)
                                <tr class="hover:bg-violet-50 transition-colors duration-150">
                                    <td class="px-4 py-3 text-center text-sm text-gray-600 font-medium">
                                        {{$act->SEMESTRY}}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{$act->ACTIVITY}}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800">
                                            {{$act->HOUR}} ชม.
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-sm">
                                        ไม่พบข้อมูลกิจกรรม กพช.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        
                        {{-- ส่วนสรุปผลรวม (Footer ของตาราง) --}}
                        <tfoot class="bg-slate-50 font-bold">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right text-sm text-gray-700">รวมชั่วโมงทั้งหมด:</td>
                                <td class="px-4 py-3 text-center text-sm text-violet-700">
                                    {{ $activity->sum('HOUR') }} ชม.
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- หมายเหตุ หรือ คำแนะนำเพิ่มเติม --}}
            <p class="mt-4 text-[11px] text-gray-400 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                หมายเหตุ: ข้อมูลกิจกรรม กพช. จะอัปเดตหลังจากสิ้นสุดโครงการภายใน 15 วันทำการ
            </p>

        </div>
    </div>
</x-app-layout>