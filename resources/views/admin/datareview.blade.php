<x-admin-layout>
    <div class="p-4 sm:ml-64 mt-6">

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <form class="p-5 max-w-sm mx-auto w-60px" method="POST" class="p-4" action="{{ route('datareview') }}">
                @csrf
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เลือกตาราง</label>
                <select id="table" name="table" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>เลือก</option>
                    <option value="ีusers">ผู้ใช้งาน</option>
                    <option value="student1">ผู้เรียน1 ประถม</option>
                    <option value="student2">ผู้เรียน2 ม.ต้น</option>
                    <option value="student3">ผู้เรียน3 ม.ปลาย</option>
                    <option value="grade1">เกรด1</option>
                    <option value="grade2">เกรด2</option>
                    <option value="grade3">เกรด3</option>
                    <option value="activity1">กพช1</option>
                    <option value="activity2">กพช2</option>
                    <option value="activity3">กพช3</option>
                    <option value="schedule1">ตารางสอบ1</option>
                    <option value="schedule2">ตารางสอบ2</option>
                    <option value="schedule3">ตารางสอบ3</option>
                    <option value="subject1">รายวิชา1</option>
                    <option value="subject2">รายวิชา2</option>
                    <option value="subject3">รายวิชา3</option>
                    <option value="group">กลุ่ม</option>
                </select>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">ตกลง</button>
            </form>
        </div>

        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-2">
            <div class="relative overflow-x-auto w-full max-h-[500px]">
                <h1 class="text-2xl mb-2">ตารางผู้ใช้งาน</h1>
                <table id="myTable" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            @foreach($columns as $col)
                            <th scope="col" class="px-6 py-3 text-center">{{$col}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $list)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            @foreach($columns as $col)
                                <td class="text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $list->$col }}
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>   
        </div>
    </div>
</x-admin-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />

<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            "paging": true,          // Enable pagination
            "searching": true,       // Enable search
            "ordering": true,       // Enable sorting
            "pageLength": 50   
        });
    });
 </script>