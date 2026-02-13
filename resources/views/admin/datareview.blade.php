<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">

<x-admin-layout>
    <div class="p-4 sm:ml-64 bg-gray-50 min-h-screen font-sans">
        <div class="mt-16 container mx-auto max-w-7xl">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">ตรวจสอบข้อมูล (Data Review)</h1>
                    <p class="text-sm text-gray-500">เลือกตารางข้อมูลที่ต้องการตรวจสอบและจัดการ</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
                <form method="POST" action="{{ route('datareview') }}" class="flex flex-col md:flex-row items-end gap-4">
                    @csrf
                    <div class="w-full md:w-1/3">
                        <label for="table" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fa-solid fa-database mr-1"></i> เลือกฐานข้อมูล
                        </label>
                        <select id="table" name="table" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 transition-all dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                            <option selected disabled>-- กรุณาเลือกตาราง --</option>
                            <optgroup label="ผู้ใช้งาน">
                                <option value="users" {{ request('table') == 'users' ? 'selected' : '' }}>👤 ผู้ใช้งานระบบ (Users)</option>
                            </optgroup>
                            <optgroup label="ข้อมูลนักเรียน">
                                <option value="student1" {{ request('table') == 'student1' ? 'selected' : '' }}>🎓 ประถมศึกษา</option>
                                <option value="student2" {{ request('table') == 'student2' ? 'selected' : '' }}>🎓 มัธยมศึกษาตอนต้น</option>
                                <option value="student3" {{ request('table') == 'student3' ? 'selected' : '' }}>🎓 มัธยมศึกษาตอนปลาย</option>
                            </optgroup>
                            <optgroup label="ข้อมูลการเรียน">
                                <option value="grade1" {{ request('table') == 'grade1' ? 'selected' : '' }}>📊 เกรด (ประถม)</option>
                                <option value="grade2" {{ request('table') == 'grade2' ? 'selected' : '' }}>📊 เกรด (ม.ต้น)</option>
                                <option value="grade3" {{ request('table') == 'grade3' ? 'selected' : '' }}>📊 เกรด (ม.ปลาย)</option>
                            </optgroup>
                            <optgroup label="กิจกรรม (กพช.)">
                                <option value="activity1" {{ request('table') == 'activity1' ? 'selected' : '' }}>🏆 กพช. 1</option>
                                <option value="activity2" {{ request('table') == 'activity2' ? 'selected' : '' }}>🏆 กพช. 2</option>
                                <option value="activity3" {{ request('table') == 'activity3' ? 'selected' : '' }}>🏆 กพช. 3</option>
                            </optgroup>
                            <optgroup label="อื่นๆ">
                                <option value="schedule1" {{ request('table') == 'schedule1' ? 'selected' : '' }}>📅 ตารางสอบ 1</option>
                                <option value="schedule2" {{ request('table') == 'schedule2' ? 'selected' : '' }}>📅 ตารางสอบ 2</option>
                                <option value="schedule3" {{ request('table') == 'schedule3' ? 'selected' : '' }}>📅 ตารางสอบ 3</option>
                                <option value="subject1" {{ request('table') == 'subject1' ? 'selected' : '' }}>📚 รายวิชา 1</option>
                                <option value="subject2" {{ request('table') == 'subject2' ? 'selected' : '' }}>📚 รายวิชา 2</option>
                                <option value="subject3" {{ request('table') == 'subject3' ? 'selected' : '' }}>📚 รายวิชา 3</option>
                                <option value="group" {{ request('table') == 'group' ? 'selected' : '' }}>👥 กลุ่มเรียน</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-6 py-2.5 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            ค้นหาข้อมูล
                        </button>
                    </div>
                </form>
            </div>

            @if(isset($data) && count($data) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-table text-purple-500"></i> ผลลัพธ์ข้อมูล: <span class="text-purple-600">{{ request('table') }}</span>
                    </h2>
                    <span class="text-xs text-gray-500 bg-white border border-gray-200 px-2 py-1 rounded-md">Total Rows: {{ count($data) }}</span>
                </div>
                
                <div class="p-5 overflow-x-auto">
                    <table id="ReviewTable" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                @foreach($columns as $col)
                                <th scope="col" class="px-6 py-3 whitespace-nowrap bg-gray-100 border-b border-gray-200">{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($data as $list)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @foreach($columns as $col)
                                    <td class="px-6 py-3 whitespace-nowrap text-gray-900 dark:text-gray-300">
                                        {{ $list->$col }}
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
                @if(request('table'))
                <div class="flex flex-col items-center justify-center p-10 bg-white rounded-xl shadow-sm border border-gray-100 text-center">
                    <div class="text-gray-300 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">ไม่พบข้อมูลในตารางนี้</h3>
                    <p class="text-gray-500 text-sm mt-1">ตาราง {{ request('table') }} ไม่มีข้อมูล หรือยังไม่ได้ถูกสร้าง</p>
                </div>
                @endif
            @endif

        </div>
    </div>
</x-admin-layout>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.tailwindcss.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable only if table exists
        if ($('#ReviewTable').length) {
            $('#ReviewTable').DataTable({
                responsive: true,
                scrollX: true, // Enable horizontal scrolling for wide tables
                pageLength: 25,
                language: {
                    search: "🔍 ค้นหา:",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    paginate: {
                        first: "หน้าแรก",
                        last: "หน้าสุดท้าย",
                        next: "ถัดไป",
                        previous: "ก่อนหน้า"
                    },
                    zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
                    emptyTable: "ไม่มีข้อมูลในตาราง"
                },
                // Optional: Highlight header on hover
                headerCallback: function(thead, data, start, end, display) {
                    $(thead).find('th').addClass('text-gray-700 font-bold');
                }
            });
        }
    });
</script>