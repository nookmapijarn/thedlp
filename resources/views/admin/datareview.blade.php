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
                
                <div class="p-5 overflow-x-auto">
                    <table id="ReviewTable" class="w-full text-sm text-left text-gray-500 border-collapse">
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
    function initializeDataTable() {
        // 1. ตรวจสอบและทำลายตารางเก่า (ถ้ามี)
        if ($.fn.DataTable.isDataTable('#ReviewTable')) {
            $('#ReviewTable').DataTable().destroy();
        }
        
        // 2. ล้าง HTML ทั้งหมดในตารางทิ้ง (สะอาดที่สุด)
        $('#ReviewTable').empty(); 

        // 3. เตรียมโครงสร้าง Header และการตั้งค่า Column
        let theadHtml = '<thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400"><tr>';
        const columnsConfig = [];

        // วนลูปสร้าง Header จาก PHP Variable ($columns)
        @foreach($columns as $col)
            theadHtml += '<th scope="col" class="px-6 py-3 whitespace-nowrap border-b border-gray-200 dark:border-gray-600">{{ ucfirst(str_replace("_", " ", $col)) }}</th>';
            columnsConfig.push({ 
                data: '{{ $col }}', 
                name: '{{ $col }}', 
                defaultContent: "-",
                render: function(data, type, row) {
                    if (data === null) return '-';
                    // ป้องกันข้อความยาวเกินไปในแต่ละช่อง
                    return data.length > 50 ? '<span title="'+data+'">'+data.substr(0, 50)+'...</span>' : data;
                }
            });
        @endforeach
        
        theadHtml += '</tr></thead>';
        
        // 4. ใส่ thead กลับเข้าไปในตาราง
        $('#ReviewTable').append(theadHtml);
        $('#ReviewTable').append('<tbody class="divide-y divide-gray-100 dark:divide-gray-700"></tbody>');

        // 5. เริ่มต้น DataTables แบบ Server-side ด้วย POST Method
        $('#ReviewTable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            scrollX: true, // รองรับ 117 คอลัมน์
            pageLength: 10,
            ajax: {
                url: "{{ route('datareview') }}",
                type: "POST", // แก้ปัญหา ERR_CONNECTION_CLOSED เพราะ URL ยาวเกินไป
                data: function(d) {
                    d._token = "{{ csrf_token() }}"; // จำเป็นสำหรับ POST ใน Laravel
                    d.table = "{{ request('table', 'users') }}";
                },
                error: function(xhr, error, thrown) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("ไม่สามารถโหลดข้อมูลได้ (อาจเกิดจากข้อมูลมีขนาดใหญ่เกินไป หรือ Session หมดอายุ)");
                }
            },
            columns: columnsConfig,
            language: {
                processing: '<div class="flex items-center justify-center"><i class="fa-solid fa-circle-notch fa-spin text-purple-600 text-2xl"></i> <span class="ml-2">กำลังประมวลผล...</span></div>',
                search: "ค้นหาเจาะจง:",
                lengthMenu: "แสดง _MENU_ แถว",
                info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ แถว",
                infoEmpty: "ไม่มีข้อมูล",
                infoFiltered: "(กรองจากทั้งหมด _MAX_ แถว)",
                paginate: {
                    first: "หน้าแรก",
                    last: "หน้าสุดท้าย",
                    next: "ถัดไป",
                    previous: "ก่อนหน้า"
                },
                emptyTable: "ไม่พบข้อมูลในตาราง"
            }
        });
    }

    // เรียกทำงาน
    initializeDataTable();
});
</script>

<style>
    /* ปรับแต่ง DataTables Tailwind เพิ่มเติม */
    .dt-container .dt-scroll-body {
        border-color: #f3f4f6;
    }
    .dark .dt-container .dt-scroll-body {
        border-color: #374151;
    }
    /* บังคับให้หัวตารางตรงกับข้อมูล */
    th { text-align: left !important; }
</style>