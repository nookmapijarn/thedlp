<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
        .swal2-confirm {background-color: #4f46e5 !important;}
        .swal2-cancel {background-color: red !important;}
</style>
<x-admin-layout>
    <div class="p-4 sm:ml-64 bg-gray-50 min-h-screen font-sans mb-20">
        <div class="mt-16 container mx-auto max-w-7xl">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">ระบบจัดการผู้ใช้งาน</h1>
                    <p class="text-sm text-gray-500">จัดการข้อมูล ครู ผู้บริหาร และผู้ดูแลระบบ</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                
                <div class="xl:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 sticky top-20">
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-t-xl">
                            <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-blue-500"></i> เพิ่มผู้ใช้งานใหม่
                            </h2>
                        </div>
                        
                        <form method="POST" action="{{ route('adminregister') }}" class="p-5 space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ระดับสิทธิ์</label>
                                <select name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-all">
                                    <option value="2">👨‍🏫 ครู (Teacher)</option>
                                    <option value="3">👔 ผู้บริหาร (Executive)</option>
                                    <option value="4">⚙️ ผู้ดูแลระบบ (Admin)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อ-นามสกุล</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="กรอกชื่อผู้ใช้งาน">
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">อีเมล</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="example@email.com">
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รหัสผ่าน</label>
                                    <input type="password" name="password" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ยืนยันรหัสผ่าน</label>
                                    <input type="password" name="password_confirmation" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="col-span-2 mt-1" />
                            </div>

                            <div class="pt-2">
                                <div class="bg-blue-50 p-3 rounded-lg text-xs text-blue-800 mb-2 h-24 overflow-y-auto border border-blue-100">
                                    <strong>PDPA Consent:</strong> ข้าพเจ้ายินยอมให้เก็บรวบรวมข้อมูลส่วนบุคคล... (ตามรายละเอียดพ.ร.บ.คุ้มครองข้อมูลส่วนบุคคล)
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="pdpa_check" id="pdpa" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="pdpa" class="ml-2 text-sm text-gray-600">ยอมรับเงื่อนไข PDPA</label>
                                </div>
                                <x-input-error :messages="$errors->get('pdpa_check')" class="mt-1" />
                            </div>

                            <button type="submit" class="w-full text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center shadow-md transition-all transform hover:scale-[1.02]">
                                <i class="fa-solid fa-circle-check mr-2"></i> ลงทะเบียนสมาชิก
                            </button>
                        </form>
                    </div>
                </div>

                <div class="xl:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                            <h2 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-users-gear text-indigo-500"></i> รายชื่อผู้ใช้งาน
                            </h2>
                            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded border border-indigo-200">Total: {{ count($users) }}</span>
                        </div>
                        
                        <div class="p-5">
                            @if(session('success'))
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({ icon: 'success', title: 'สำเร็จ', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
                                    });
                                </script>
                            @endif
                            @if(session('error'))
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: "{{ session('error') }}" });
                                    });
                                </script>
                            @endif

                            <div class="relative overflow-x-auto">
                                <table id="AdminTable" class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-4 py-3 rounded-l-lg">No.</th>
                                            <th class="px-4 py-3">Role</th>
                                            <th class="px-4 py-3">Name (Edit)</th>
                                            <th class="px-4 py-3">Email (Edit)</th>
                                            <th class="px-4 py-3 text-center rounded-r-lg">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($users as $user_ad)
                                        <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $user_ad->id }}">
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $badges = [
                                                        1 => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'ผู้เรียน'],
                                                        2 => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'ครู'],
                                                        3 => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'ผู้บริหาร'],
                                                        4 => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Admin'],
                                                    ];
                                                    $role = $badges[$user_ad->role] ?? $badges[1];
                                                @endphp
                                                <span class="{{ $role['bg'] }} {{ $role['text'] }} text-xs font-medium px-2.5 py-0.5 rounded border border-opacity-20">
                                                    {{ $role['label'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" name="name" value="{{ $user_ad->name }}" class="edit-input border-0 bg-transparent hover:bg-white hover:border hover:border-gray-300 rounded focus:ring-2 focus:ring-blue-500 w-full text-sm py-1 px-2 transition-all">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="email" name="email" value="{{ $user_ad->email }}" class="edit-input border-0 bg-transparent hover:bg-white hover:border hover:border-gray-300 rounded focus:ring-2 focus:ring-blue-500 w-full text-sm py-1 px-2 transition-all">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button" class="btn-update text-white bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 font-medium rounded-lg text-xs px-3 py-2 transition-all shadow-sm">
                                                        <i class="fa-solid fa-save"></i>
                                                    </button>
                                                    <button type="button" class="btn-remove text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2 transition-all shadow-sm">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.tailwindcss.js"></script>

<script>
    $(document).ready(function () {
        // 1. Initialize DataTables with Clean Config
        const table = $('#AdminTable').DataTable({
            responsive: true,
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
                zeroRecords: "ไม่พบข้อมูลที่ค้นหา"
            },
            columnDefs: [
                { orderable: false, targets: 4 } // ห้าม sort คอลัมน์ Action
            ]
        });

        // 2. SweetAlert2 + AJAX Update Logic
        $('#AdminTable').on('click', '.btn-update', function () {
            var row = $(this).closest('tr');
            var id = row.data('id');
            // ดึงค่าจาก input ที่อยู่ใน row นั้นๆ
            var name = row.find('input[name="name"]').val();
            var email = row.find('input[name="email"]').val();

            // แสดง Loading
            Swal.fire({
                title: 'กำลังบันทึก...',
                didOpen: () => { Swal.showLoading() }
            });

            $.ajax({
                url: '{{ route("adminuserupdate") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    name: name,
                    email: email
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ!',
                        text: 'ข้อมูลได้รับการแก้ไขเรียบร้อยแล้ว',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้: ' + xhr.responseText
                    });
                }
            });
        });

        // 3. SweetAlert2 + AJAX Remove Logic
        $('#AdminTable').on('click', '.btn-remove', function () {
            var row = $(this).closest('tr');
            // กรณีใช้ DataTables ต้องลบผ่าน API เพื่อให้หน้า table ไม่งง
            var dataTableRow = table.row(row); 
            var id = row.data('id');

            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#D72230',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    Swal.fire({ title: 'กำลังลบ...', didOpen: () => { Swal.showLoading() } });

                    $.ajax({
                        url: '{{ route("adminuserremove") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            // ลบแถวออกจาก DataTable และ DOM
                            dataTableRow.remove().draw();
                            
                            Swal.fire(
                                'ลบสำเร็จ!',
                                'ข้อมูลผู้ใช้งานถูกลบแล้ว',
                                'success'
                            );
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'เกิดข้อผิดพลาด!',
                                'ไม่สามารถลบข้อมูลได้',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>