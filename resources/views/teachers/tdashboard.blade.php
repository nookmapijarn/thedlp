<x-teachers-layout>
    {{-- Main Container --}}
    <div class="p-4  transition-all duration-300">
        
        {{-- ส่วนของกราฟ --}}
        <div class="p-6 bg-white border border-slate-100 rounded-2xl shadow-sm mt-20">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-700">สถิติจำนวนผู้เรียน</h3>
                <span class="px-3 py-1 bg-purple-50 text-purple-600 text-xs font-bold rounded-full">อัปเดตเรียลไทม์</span>
            </div>
            <div class="relative w-full" style="height: 350px;">
                <canvas id="myChart"></canvas>
            </div>
        </div>

        {{-- ตารางที่ 1: จำนวนผู้เรียนแยกตามประเภท --}}
        <div class="mt-6 overflow-hidden bg-white border border-slate-100 rounded-2xl shadow-sm">
            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">จำนวนผู้เรียน (คน)</h3>
                <p class="text-sm text-slate-500">หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">ภาคเรียน</th>
                            @foreach($labels as $sem)
                                <th class="px-6 py-4 text-center">{{ $sem }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-amber-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-amber-600 italic">● ผู้เรียนใหม่</td>
                            @foreach($data_student['data_new_student'] as $ns)
                                <td class="px-6 py-4 text-center font-medium">{{ $ns }}</td>
                            @endforeach
                        </tr>
                        <tr class="hover:bg-purple-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-purple-600 italic">● ผู้เรียนเก่า</td>
                            @foreach($data_student['data_old_student'] as $os)
                                <td class="px-6 py-4 text-center font-medium">{{ $os }}</td>
                            @endforeach
                        </tr>
                        <tr class="bg-indigo-50/30 font-black">
                            <td class="px-6 py-4 text-indigo-700 underline decoration-indigo-200 decoration-2">ผู้เรียนทั้งหมด</td>
                            @foreach($data_student['data_student'] as $total)
                                <td class="px-6 py-4 text-center text-indigo-700 text-base">{{ $total }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ตารางที่ 2: รายตำบล --}}
        @if($student_tumbon !== null)
        <div class="mt-6 overflow-hidden bg-white border border-slate-100 rounded-2xl shadow-sm mb-10">
            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800 text-purple-700">จำนวนผู้เรียน (รายตำบล) ภาคเรียนที่ {{$current_semestry}}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-800 text-white text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-4 text-center">ลำดับ</th>
                            <th class="px-4 py-4">ศกร.ระดับตำบล</th>
                            <th class="px-4 py-4">ครูผู้สอน</th>
                            <th class="px-4 py-4 text-center bg-pink-500/10 text-pink-600">ประถม</th>
                            <th class="px-4 py-4 text-center bg-green-500/10 text-green-600">มัธยมต้น</th>
                            <th class="px-4 py-4 text-center bg-amber-500/10 text-amber-600">มัธยมปลาย</th>
                            <th class="px-4 py-4 text-center bg-indigo-600 text-white">รวม</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php $totalST1 = 0; $totalST2 = 0; $totalST3 = 0; @endphp
                        @foreach($student_tumbon as $index => $sttm)
                            @php
                                $totalST1 += $sttm['STUDENT']['ST1'];
                                $totalST2 += $sttm['STUDENT']['ST2'];
                                $totalST3 += $sttm['STUDENT']['ST3'];
                                $rowTotal = $sttm['STUDENT']['ST1'] + $sttm['STUDENT']['ST2'] + $sttm['STUDENT']['ST3'];
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-4 py-4 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 font-bold text-slate-700">{{ $sttm['GRP']->GRP_NAME }}</td>
                                <td class="px-4 py-4 text-slate-500">{{ $sttm['GRP']->GRP_ADVIS }}</td>
                                <td class="px-4 py-4 text-center font-bold text-pink-600 bg-pink-50/30">{{ $sttm['STUDENT']['ST1'] }}</td>
                                <td class="px-4 py-4 text-center font-bold text-green-600 bg-green-50/30">{{ $sttm['STUDENT']['ST2'] }}</td>
                                <td class="px-4 py-4 text-center font-bold text-amber-600 bg-amber-50/30">{{ $sttm['STUDENT']['ST3'] }}</td>
                                <td class="px-4 py-4 text-center font-black text-indigo-700 bg-indigo-50 group-hover:bg-indigo-100">{{ $rowTotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-indigo-700 text-white font-bold">
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-right uppercase tracking-widest">Grand Total</td>
                            <td class="px-4 py-4 text-center border-l border-indigo-600">{{ $totalST1 }}</td>
                            <td class="px-4 py-4 text-center border-l border-indigo-600">{{ $totalST2 }}</td>
                            <td class="px-4 py-4 text-center border-l border-indigo-600">{{ $totalST3 }}</td>
                            <td class="px-4 py-4 text-center bg-amber-400 text-slate-900 text-lg">{{ $totalST1 + $totalST2 + $totalST3 }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-teachers-layout>

@include('layouts.footer')

{{-- Script ปรับจูนสี Chart ให้สวยขึ้น --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    const labels = {{ Js::from($labels) }};
    const data_student = {{ Js::from($data_student['data_student']) }};
    const data_new_student = {{ Js::from($data_student['data_new_student']) }}; 
    const data_old_student = {{ Js::from($data_student['data_old_student']) }};

    const ctx = document.getElementById('myChart').getContext('2d');
    
    // สร้าง Gradient สำหรับสีแท่งกราฟ
    const purpleGrad = ctx.createLinearGradient(0, 0, 0, 400);
    purpleGrad.addColorStop(0, '#8b5cf6');
    purpleGrad.addColorStop(1, '#6366f1');

    const amberGrad = ctx.createLinearGradient(0, 0, 0, 400);
    amberGrad.addColorStop(0, '#fbbf24');
    amberGrad.addColorStop(1, '#f59e0b');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'ผู้เรียนใหม่',
                    backgroundColor: amberGrad,
                    borderRadius: 6,
                    data: data_new_student,
                },
                {
                    label: 'ผู้เรียนเก่า',
                    backgroundColor: purpleGrad,
                    borderRadius: 6,
                    data: data_old_student,
                },
                {
                    label: 'ผู้เรียนทั้งหมด',
                    backgroundColor: '#e2e8f0',
                    borderRadius: 6,
                    data: data_student,
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, font: { family: 'IBM Plex Sans Thai', weight: '600' } } }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });
</script>