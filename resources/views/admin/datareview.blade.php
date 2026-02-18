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
                <form method="GET" action="{{ route('datareview') }}" class="flex flex-col md:flex-row items-end gap-4">
                    <div class="w-full md:w-1/3">
                        <label for="table" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fa-solid fa-database mr-1"></i> เลือกฐานข้อมูล
                        </label>
                        <select id="table" name="table" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 transition-all dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                            <option value="users" {{ request('table') == 'users' || !request('table') ? 'selected' : '' }}>👤 ผู้ใช้งานระบบ (Users)</option>
                            <optgroup label="ข้อมูลนักเรียน">
                                <option value="student1" {{ request('table') == 'student1' ? 'selected' : '' }}>🎓 ประถมศึกษา</option>
                                <option value="student2" {{ request('table') == 'student2' ? 'selected' : '' }}>🎓 มัธยมศึกษาตอนต้น</option>
                                <option value="student3" {{ request('table') == 'student3' ? 'selected' : '' }}>🎓 มัธยมศึกษาตอนปลาย</option>
                            </optgroup>
                            </select>
                    </div>
                    <div class="w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-6 py-2.5 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fa-solid fa-magnifying-glass"></i> ค้นหาตาราง
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-table text-purple-500"></i> ผลลัพธ์ข้อมูล: <span class="text-purple-600">{{ request('table', 'users') }}</span>
                    </h2>
                </div>
                
                <div class="p-5">
                    <table id="ReviewTable" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                @foreach($columns as $col)
                                <th scope="col" class="px-6 py-3 whitespace-nowrap bg-gray-100 border-b border-gray-200">{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.tailwindcss.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
$(document).ready(function () {
    // ฟังก์ชันสำหรับสั่งวาดตาราง
    function initializeDataTable() {
        // ถ้ามีตารางเดิมอยู่แล้ว ให้ทำลายทิ้งก่อน
        if ($.fn.DataTable.isDataTable('#ReviewTable')) {
            $('#ReviewTable').DataTable().destroy();
            $('#ReviewTable').empty(); // ล้างเนื้อหาในตารางทิ้งด้วย (รวมถึง <thead>)
        }

        // สร้าง <thead> ใหม่ตามคอลัมน์ที่ได้รับมาจาก PHP (เพื่อความแม่นยำ)
        let headerRow = '<tr>';
        const columnsConfig = [];

        @foreach($columns as $col)
            headerRow += '<th class="px-6 py-3 whitespace-nowrap bg-gray-100 border-b border-gray-200">{{ ucfirst(str_replace("_", " ", $col)) }}</th>';
            columnsConfig.push({ data: '{{ $col }}', name: '{{ $col }}', defaultContent: "-" });
        @endforeach
        
        headerRow += '</tr>';
        $('#ReviewTable').append('<thead class="text-xs text-gray-700 uppercase bg-gray-50">' + headerRow + '</thead>');
        $('#ReviewTable').append('<tbody class="divide-y divide-gray-100"></tbody>');

        // เริ่มต้น DataTables
        $('#ReviewTable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true, // บังคับสร้างใหม่
            responsive: false, // ปิดไว้ก่อนถ้าคอลัมน์เยอะเกินไปจะทำให้พังง่าย
            scrollX: true,
            ajax: {
                url: "{{ route('datareview') }}",
                data: { table: "{{ $tableName }}" }
            },
            columns: columnsConfig,
            language: {
                processing: "กำลังประมวลผล...",
                search: "ค้นหา:",
                // ... ภาษาไทยอื่นๆ ที่คุณใส่ไว้ ...
            }
        });
    }

    // เรียกทำงานทันทีที่โหลดหน้า
    initializeDataTable();
});
</script>