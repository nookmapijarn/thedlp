<x-admin-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <form action="{{ route('zip.upload') }}" method="POST" class="mx-auto mt-4 max-w-4xl sm:mt-6" enctype="multipart/form-data">   
                <h1 class=" text-2lg mb-2"> 
                    UPLOAD ไฟล์ BACKUP 
                <x-input-error :messages="$errors->get('zip_file')" class="mt-2" />
                </h1>
                @csrf
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="m-2 w-6 h-6 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>                          
                    </div>
                    <input type="file" name="zip_file" id="zip_file" required  autofocus class=" block w-full p-4 pl-12 text-sm text-gray-900 border border-gray-100 rounded-full bg-gray-100 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2">Upload</button>
                </div>
            </form>
        </div>

        <form id="yourForm" action="{{ route('clearTable') }}" method="POST" class="p-2">
            @csrf
            <button type="submit" class="mt-2 text-red-200 right-2.5 bottom-2.5 hover:text-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm">X ล้างข้อมูล</button>
        </form>

        {{-- Clear Table --}}
        <div class="pt-2 rounded-lg dark:border-gray-700 mt-2">
            <!-- แสดงข้อความ error -->
            @if(session('error'))
                <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- แสดงข้อความ success -->
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- แสดงข้อความแจ้งเตือน -->
            <div id="alert-container" class="hidden rounded-lg text-center"></div>

        </div>

        {{-- Table --}}
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-2">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                No.
                            </th>
                            <th scope="col" class="px-6 py-3">
                                ตาราง
                            </th>
                            <th scope="col" class="px-6 py-3">
                                วันที่แก้ไขไฟล์ (ล่าสุด)
                            </th>
                            <th scope="col" class="px-6 py-3">
                                วันที่อัพโหลด (ล่าสุด)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lastmodified as $lastmodi)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class=" text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loop->iteration}}
                            </th>
                            <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $lastmodi->FILE_NAME }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $lastmodi->LAST_MODIFIED }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $lastmodi->UPLOADED }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>   
        </div>
    </div>

<!-- Modal -->
<div id="modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <ul class="max-w-md space-y-2 text-gray-100 list-inside dark:text-gray-400">
        <li class="flex items-center">
            <div role="status">
                <svg aria-hidden="true" class="w-6 h-6 me-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                <span class="sr-only">Loading...</span>
            </div>
            กำลังดำเนินการ...
        </li>
    </ul>
</div>

</x-admin-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal');
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                modal.classList.remove('hidden');
            });
        });

        document.addEventListener('ajaxComplete', function () {
            modal.classList.add('hidden');
        });
    });

    // Function to trigger custom event after AJAX request completion
    function triggerAjaxComplete() {
        const event = new Event('ajaxComplete');
        document.dispatchEvent(event);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        const alertContainer = document.getElementById('alert-container');

        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const modal = document.getElementById('modal');
                modal.classList.remove('hidden');

                const formData = new FormData(form);
                const action = form.getAttribute('action');
                const method = form.getAttribute('method').toUpperCase();

                fetch(action, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    modal.classList.add('hidden');
                    alertContainer.classList.remove('hidden');
                    alertContainer.innerHTML = `<div class="bg-${data.success ? 'green' : 'red'}-200 text-gray-900 p-4 rounded-lg">${data.message}</div>`;

                    if (data.success) {
                        // Reload the page after a short delay
                        setTimeout(() => {
                            location.reload();
                        }, 2000); // Delay of 2 seconds before reload
                    }

                })
                .catch(err => {
                    modal.classList.add('hidden');
                    alertContainer.classList.remove('hidden');
                    alertContainer.innerHTML = `<div class="bg-${data.success ? 'green' : 'red'}-200 text-gray-900 p-4 rounded-lg">${err.message}</div>`;
                });
            });
        });
    });
</script>

