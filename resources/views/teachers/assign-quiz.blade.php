<x-teachers-layout>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwind.min.css">
    
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Custom Table Styling */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem;
            border-color: #e2e8f0;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
        }
        table.dataTable thead th { border-bottom: 1px solid #e2e8f0 !important; }
        .checkbox-indigo { @apply w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500; }
    </style>

    <div class="p-6 mt-20 animate-fade-in">
        <form id="assign-form" action="{{ route('ttest.assign.store', $quiz->id) }}" method="POST" class="max-w-6xl mx-auto space-y-6">
            @csrf
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-indigo-900 px-8 py-6 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-black">มอบหมาย: {{ $quiz->title }}</h2>
                            <div class="flex items-center gap-2 mt-1 text-indigo-200 text-sm">
                                <span class="bg-white/10 px-2 py-0.5 rounded">{{ $gradeLabel }}</span>
                                <span>ภาคเรียนล่าสุด: {{ $latestSemestry }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <label class="text-xs font-bold text-indigo-300 mb-1">กำหนดส่ง (Due Date)</label>
                            <input type="datetime-local" name="due_date" class="bg-indigo-800 border-none rounded-xl text-white text-sm focus:ring-2 focus:ring-indigo-400">
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1 uppercase">กรองตามกลุ่มเรียน</label>
                                <select id="filter_group" class="rounded-xl border-slate-200 text-sm font-bold text-slate-600 focus:ring-indigo-500">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($groups as $grp)
                                        <option value="{{ $grp->GRP_CODE }}">{{ $grp->GRP_CODE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pt-5">
                                <button type="button" id="select_visible" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg hover:bg-indigo-100 transition-colors">
                                    เลือกทั้งหมดที่แสดงอยู่
                                </button>
                            </div>
                        </div>
                        <div class="text-right">
                            <span id="selected-counter" class="text-sm font-black text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full border border-indigo-100">
                                เลือกแล้ว 0 คน
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="studentTable" class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-black tracking-wider">
                                <tr>
                                    <th class="px-4 py-4 text-center">
                                        <input type="checkbox" id="check_all" class="checkbox-indigo">
                                    </th>
                                    <th class="px-4 py-4">รหัสนักเรียน</th>
                                    <th class="px-4 py-4">ชื่อ-นามสกุล</th>
                                    <th class="px-4 py-4 text-center">กลุ่มเรียน (GRP_CODE)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($students as $student)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-4 text-center">
                                        <input type="checkbox" name="user_ids[]" value="{{ $student->user_id }}" 
                                            class="student-checkbox checkbox-indigo">
                                    </td>
                                    <td class="px-4 py-4 font-mono font-bold text-slate-600">
                                        {{ $student->student_code }} </td>
                                    <td class="px-4 py-4 font-bold text-slate-700">
                                        {{ $student->NAME }} {{ $student->SURNAME }} </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                                            {{ $student->GRP_CODE }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <a href="{{ route('ttest.index') }}" class="text-slate-400 font-bold hover:text-slate-600 transition-colors">ยกเลิก</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black shadow-lg shadow-indigo-200 transition-all hover:-translate-y-1 active:scale-95">
                    ยืนยันการมอบหมายงาน ( <span id="btn-counter">0</span> คน )
                </button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwind.min.js"></script>

    <script>
        $(document).ready(function() {
            // กำหนดค่า DataTable
            const table = $('#studentTable').DataTable({
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"f>rt<"flex justify-between items-center mt-4"ip>',
                pageLength: 50,
                language: {
                    search: "",
                    searchPlaceholder: "ค้นหาชื่อ หรือ รหัส...",
                    // ... (ภาษาไทยเดิม)
                },
                columnDefs: [{ orderable: false, targets: 0 }],
                order: [[1, 'asc']]
            });

            // ฟังก์ชันอัปเดตจำนวน (นับจาก Checkbox ที่ถูกติ๊กจริงๆ ทั่วทั้งตาราง)
            function updateCounter() {
                // ใช้ table.$ เพื่อเข้าถึง checkbox ในทุกหน้าของ Pagination
                const count = table.$('.student-checkbox:checked').length;
                $('#selected-counter').text(`เลือกแล้ว ${count} คน`);
                $('#btn-counter').text(count);
            }

            // Filter กลุ่ม
            $('#filter_group').on('change', function() {
                table.column(3).search(this.value).draw();
            });

            // ติ๊กทั้งหมด "เฉพาะที่กำลังแสดงผล" (Visible)
            $('#select_visible').on('click', function() {
                const rows = table.rows({ search: 'applied' }).nodes();
                $('input.student-checkbox', rows).prop('checked', true);
                updateCounter();
            });

            // ติ๊กหัวตาราง (ติ๊กทั้งตาราง)
            $('#check_all').on('change', function() {
                const isChecked = $(this).is(':checked');
                table.$('.student-checkbox').prop('checked', isChecked);
                updateCounter();
            });

            // เมื่อมีการเปลี่ยนหน้า หรือติ๊กรายคน
            $(document).on('change', '.student-checkbox', function() {
                updateCounter();
            });
        });
    </script>
</x-teachers-layout>