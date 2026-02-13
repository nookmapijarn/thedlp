<x-teachers-layout>
    <div class="p-4 ">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14 bg-white shadow-sm">
            <div class="text-2xl font-bold w-full text-center text-slate-800 mb-6">📊 รายงานผลการเรียน</div>
            
            <form method="GET" action="{{ route('tgrade') }}" class="text-sm max-w-6xl mx-auto mb-8 bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1 font-medium text-gray-700">ภาคเรียน</label>
                        <select required name="semestry" class="bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">เลือกภาคเรียน</option>
                            @foreach($all_semestry as $sem)
                                <option value="{{ $sem->SEMESTRY }}" @if($semestry === $sem->SEMESTRY) selected @endif>
                                    {{ $sem->SEMESTRY }}
                                </option>
                            @endforeach    
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-gray-700">ศกร.ตำบล</label>
                        <select required name="tumbon" class="bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">เลือกตำบล</option>
                            @foreach($all_tumbon as $tm)
                                <option value="{{ $tm->GRP_CODE }}" @if($tumbon == $tm->GRP_CODE) selected @endif>
                                    {{ $tm->GRP_CODE }} {{ $tm->GRP_NAME }}
                                </option>
                            @endforeach    
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-gray-700">ระดับชั้น</label>
                        <select required name="lavel" class="bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">เลือกทุกระดับ</option>
                            <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                            <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                            <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg transition-all shadow-md shadow-indigo-100">
                    🔍 ดูรายงานผลการเรียน
                </button> 
            </form>

            @if($data)
                <div class="mb-4 p-4 rounded-xl shadow-sm border border-indigo-100 @if($lavel==1) bg-pink-100 @elseif($lavel==2) bg-green-100 @else bg-yellow-100 @endif">
                    <div class="flex flex-col md:flex-row justify-center items-center gap-4 text-slate-800">
                        <div class="text-lg font-bold">ตำบล: <span class="font-normal">{{request()->get('tumbon')}}</span></div>
                        <div class="text-lg font-bold">ระดับ: <span class="font-normal">
                            @if($lavel==1) ประถมศึกษา @elseif($lavel==2) มัธยมต้น @else มัธยมปลาย @endif
                        </span></div>
                    </div>
                    <div class="text-center text-xs mt-2 text-slate-600 italic">ความหมาย: รหัสวิชา (เกรด/คะแนนรวม)</div>
                </div>

                <div class="w-full overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table id="gradeTable" class="w-full text-sm text-left border-collapse">
                            <thead class="bg-slate-800 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-center border-r border-slate-700 w-12">ลำดับ</th>
                                    <th class="px-4 py-3 border-r border-slate-700">รหัสนักศึกษา</th>
                                    <th class="px-4 py-3 border-r border-slate-700">ชื่อ-นามสกุล</th>
                                    <th class="px-4 py-3 no-sort">ผลการเรียนรายวิชา</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $s)
                                <tr class="border-b transition-colors @if($lavel==1) bg-pink-50/50 hover:bg-pink-100 @elseif($lavel==2) bg-green-50/50 hover:bg-green-100 @else bg-yellow-50/50 hover:bg-yellow-100 @endif">
                                    <td class="px-4 py-2 text-center border-r">{{$loop->iteration}}</td>
                                    <td class="px-4 py-2 font-mono text-gray-700 border-r">{{$s['ID']}}</td>
                                    <td class="px-4 py-2 font-bold border-r whitespace-nowrap">{{$s['NAME']}} {{$s['SURNAME']}}</td>
                                    <td class="px-4 py-2 flex flex-wrap gap-1">
                                        @foreach($s['ALL_GRADE'] as $g)
                                            <span class="inline-block px-2 py-1 rounded text-[11px] border whitespace-nowrap
                                                @if($g->GRADE == 0) bg-red-50 text-red-700 border-red-200
                                                @elseif(!is_numeric($g->GRADE) || $g->GRADE == '') bg-yellow-50 text-yellow-700 border-yellow-200
                                                @else bg-white text-emerald-700 border-emerald-100 @endif">
                                                @if($g->TYP_CODE == 7) <span class="font-bold text-blue-600">[ซ่อม]</span> @endif
                                                {{$g->SUB_CODE}} ({{$g->GRADE}}/{{$g->TOTAL}})
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(!$data && request()->get('tumbon'))
                <div class="mt-6 p-4 text-center bg-orange-50 border border-orange-200 rounded-xl">
                    <p class="text-orange-800 font-bold">❌ ไม่พบข้อมูลการเรียน</p>
                    <p class="text-sm text-orange-600">กรุณาตรวจสอบภาคเรียน ตำบล หรือระดับชั้นอีกครั้ง</p>
                </div>
            @endif
        </div>
    </div>
</x-teachers-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

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
        "dom": '<"flex flex-col md:flex-row justify-between p-4 gap-4"f>rtip', // ปรับตำแหน่ง Search box
    });
});
</script>

<style>
    /* ปรับแต่ง DataTables Search ให้สวยขึ้นเข้ากับ Tailwind */
    .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        outline: none;
        width: 300px;
    }
    .dataTables_filter input:focus {
        ring: 2px;
        ring-color: #6366f1;
        border-color: #6366f1;
    }
</style>