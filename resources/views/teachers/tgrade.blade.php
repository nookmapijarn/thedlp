<x-teachers-layout>
    <div class="p-6 mt-16 max-w-7xl mx-auto space-y-6">
        
        <!-- Header Banner Block -->
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-[11px] font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    OLIS Student Analytics
                </span>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">รายงานผลการเรียน</h1>
                <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-bold max-w-3xl mt-1.5">
                    สืบค้นข้อมูลบันทึกเกรดผลสัมฤทธิ์ทางการเรียนของผู้เรียนในระดับต่าง ๆ แยกตามรายตำบลและรายวิชา
                </p>
            </div>
        </div>

        <!-- Single Unified Report Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] shadow-sm overflow-hidden">
            
            <!-- Top Control Header (Form selectors) -->
            <div class="p-4 sm:p-5 border-b border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                <form method="GET" action="{{ route('tgrade') }}" class="w-full flex flex-col md:flex-row md:items-end gap-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1 w-full">
                        <div class="w-full">
                            <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ภาคเรียน</label>
                            <select required name="semestry" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                                <option value="">เลือกภาคเรียน</option>
                                @foreach($all_semestry as $sem)
                                    <option value="{{ $sem->SEMESTRY }}" @if($semestry === $sem->SEMESTRY) selected @endif>
                                        {{ $sem->SEMESTRY }}
                                    </option>
                                @endforeach    
                            </select>
                        </div>
                        <div class="w-full">
                            <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ศกร.ตำบล</label>
                            <select required name="tumbon" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                                <option value="">เลือกตำบล</option>
                                @foreach($all_tumbon as $tm)
                                    <option value="{{ $tm->GRP_CODE }}" @if($tumbon == $tm->GRP_CODE) selected @endif>
                                        {{ $tm->GRP_CODE }} {{ $tm->GRP_NAME }}
                                    </option>
                                @endforeach    
                            </select>
                        </div>
                        <div class="w-full">
                            <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ระดับชั้น</label>
                            <select required name="lavel" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                                <option value="">เลือกทุกระดับ</option>
                                <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                                <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                                <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-xl text-sm font-black tracking-widest uppercase transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 h-10 w-full md:w-auto shrink-0">
                        🔍 ค้นหาผลการเรียน
                    </button>
                </form>
            </div>

            @if($data)
                <!-- Target Header details -->
                <div class="p-4 sm:p-5 border-b border-slate-150 dark:border-slate-800 bg-slate-55/10 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-black text-slate-700 dark:text-slate-300">ผลการเรียนของกลุ่ม:</span>
                        <span class="px-2.5 py-1 rounded-lg text-sm font-black bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300">ตำบล {{ $tumbon }}</span>
                        <span class="px-2.5 py-1 rounded-lg text-sm font-black @if($lavel==1) bg-pink-100 text-pink-700 @elseif($lavel==2) bg-emerald-100 text-emerald-700 @else bg-amber-100 text-amber-700 @endif">
                            @if($lavel==1) ประถมศึกษา @elseif($lavel==2) มัธยมต้น @else มัธยมปลาย @endif
                        </span>
                    </div>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-bold italic">ความหมาย: รหัสวิชา (เกรด/คะแนนรวม)</span>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto scrollbar-thin">
                    <table id="gradeTable" class="min-w-full text-base">
                        <thead>
                            <tr class="bg-slate-900 text-white">
                                <th class="p-4 text-sm font-black uppercase tracking-wider text-center w-12">ลำดับ</th>
                                <th class="p-4 text-sm font-black uppercase tracking-wider text-center">รหัสนักศึกษา ↕</th>
                                <th class="p-4 text-sm font-black uppercase tracking-wider text-left">ชื่อ-นามสกุล ↕</th>
                                <th class="p-4 text-sm font-black uppercase tracking-wider text-left no-sort">ผลการเรียนรายวิชา</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($data as $s)
                            <tr class="hover:bg-purple-50/20 dark:hover:bg-slate-800/40 transition-colors @if($lavel==1) bg-pink-50/10 @elseif($lavel==2) bg-emerald-50/10 @else bg-amber-50/10 @endif">
                                <td class="p-4 text-center">{{ $loop->iteration }}</td>
                                <td class="p-4 text-center font-mono font-bold text-slate-700 dark:text-slate-300">{{ $s['ID'] }}</td>
                                <td class="p-4 font-black text-slate-800 dark:text-white text-left whitespace-nowrap">{{ $s['NAME'] }} {{ $s['SURNAME'] }}</td>
                                <td class="p-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($s['ALL_GRADE'] as $g)
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-black border whitespace-nowrap
                                                @if($g->GRADE == 0) bg-red-50 text-red-700 border-red-200 dark:bg-red-950/20 dark:text-red-300 dark:border-red-900/40
                                                @elseif(!is_numeric($g->GRADE) || $g->GRADE == '') bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-950/20 dark:text-yellow-300 dark:border-yellow-900/40
                                                @else bg-white text-emerald-700 border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-300 dark:border-emerald-900/40 @endif">
                                                @if($g->TYP_CODE == 7) <span class="font-black text-blue-600 dark:text-blue-450 mr-1">[ซ่อม]</span> @endif
                                                {{ $g->SUB_CODE }} ({{ $g->GRADE }}/{{ $g->TOTAL }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if(!$data && request()->get('tumbon'))
                <div class="p-8 text-center bg-orange-50/50 dark:bg-orange-950/10 border-t border-orange-100 dark:border-orange-900/40">
                    <p class="text-orange-850 dark:text-orange-355 font-black text-base">❌ ไม่พบข้อมูลการเรียน</p>
                    <p class="text-sm text-slate-450 dark:text-slate-500 font-bold mt-1">กรุณาตรวจสอบภาคเรียน ตำบล หรือระดับชั้นอีกครั้ง</p>
                </div>
            @endif
        </div>
    </div>
</x-teachers-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link class="no-print" rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script class="no-print" type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#gradeTable').DataTable({
        "pageLength": 50,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Thai.json"
        },
        "columnDefs": [
            { "targets": 'no-sort', "orderable": false }
        ],
        "dom": '<"flex flex-col md:flex-row justify-between p-4 gap-4"f>rtip',
    });
});
</script>

<style>
    .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        outline: none;
        width: 100%;
        max-width: 300px;
    }
    .dataTables_filter input:focus {
        ring: 2px;
        ring-color: #7e22ce;
        border-color: #7e22ce;
    }

    @media (max-width: 640px) {
        /* Hide table header */
        #gradeTable thead {
            display: none;
        }
        
        /* Make table rows look like cards */
        #gradeTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        /* Make table cells act as block elements */
        #gradeTable tbody tr td {
            display: block;
            padding: 0.25rem 0 !important;
            border: none !important;
            text-align: left !important;
            width: 100% !important;
        }
        
        /* Custom styling for specific columns */
        #gradeTable tbody tr td:nth-child(1) {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 800;
            text-transform: uppercase;
            border-bottom: 1px dashed #e2e8f0 !important;
            padding-bottom: 0.5rem !important;
            margin-bottom: 0.5rem;
        }
        #gradeTable tbody tr td:nth-child(1)::before {
            content: "ลำดับที่: ";
        }
        
        #gradeTable tbody tr td:nth-child(2) {
            font-family: monospace;
            font-size: 0.75rem;
            color: #64748b;
        }
        #gradeTable tbody tr td:nth-child(2)::before {
            content: "รหัสนักศึกษา: ";
            font-weight: bold;
            color: #475569;
        }
        
        #gradeTable tbody tr td:nth-child(3) {
            font-weight: 950;
            font-size: 0.95rem;
            color: #1e293b;
            white-space: normal !important;
            margin-bottom: 0.5rem;
        }
        
        #gradeTable tbody tr td:nth-child(4) {
            padding-top: 0.5rem !important;
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
    }
</style>