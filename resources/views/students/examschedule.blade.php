<x-app-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ __('ตารางสอบปลายภาค') }}
        </h6>
    </x-slot>

    <div x-data="{ search: '' }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ส่วนข้อมูลนักศึกษาและตารางสอบ --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                
                {{-- Header Card --}}
                <div class="p-6 bg-violet-800 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-500 rounded-lg text-violet-900">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">ตารางสอบ ปลายภาค</h3>
                                <p class="text-violet-200 text-sm">ภาคเรียนที่ {{$semestry}}</p>
                            </div>
                        </div>

                        @foreach($student as $s)
                        <div class="bg-white/10 p-3 rounded-xl backdrop-blur-sm border border-white/20 text-sm">
                            <p><strong>ชื่อ-สกุล:</strong> {{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}}</p>
                            <p><strong>รหัส:</strong> {{$s->ID}}</p>
                            <p><strong>สนามสอบ:</strong> ตามประกาศสถานศึกษา</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="p-4 bg-gray-50 border-b flex items-center gap-2">
                    <div class="relative flex-1 max-w-md">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input x-model="search" type="text" placeholder="ค้นหารหัสวิชา หรือ ชื่อวิชา..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500 text-sm">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        @if($schedule)
                        <thead class="bg-violet-100 text-violet-900 text-sm uppercase font-bold">
                            <tr>
                                <th class="px-4 py-3 text-center w-16">ลำดับ</th>
                                <th class="px-4 py-3 w-32">รหัสวิชา</th>
                                <th class="px-4 py-3">รายวิชา</th>
                                <th class="px-4 py-3">วันสอบ</th>
                                <th class="px-4 py-3">เวลา</th>
                                <th class="px-4 py-3 text-center">ห้องสอบ</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @foreach($schedule as $s)
                            <tr x-show="'{{$s['sub_code']}} {{$s['sub_name']}}'.toLowerCase().includes(search.toLowerCase())"
                                x-transition.opacity
                                class="border-b hover:bg-violet-50 transition-colors">
                                <td class="px-4 py-4 text-center text-gray-400 font-medium">{{$loop->iteration}}</td>
                                <td class="px-4 py-4 font-mono font-bold text-violet-700">{{$s['sub_code']}}</td>
                                <td class="px-4 py-4 font-medium">{{$s['sub_name']}}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-bold">
                                        {{$s['exam_day'] != 0 ? $s['exam_day'] : '-'}}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    {{$s['exam_start'] != 0 ? $s['exam_start'].'-'.$s['exam_end'].' น.' : '-'}}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-bold text-gray-700">{{$s['exam_room'] ?? '-'}}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p class="text-lg font-semibold">ยังไม่มีตารางสอบ</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>

            {{-- N-NET Section --}}
            @if($nnet === 'N-NET')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-indigo-200 mt-8">
                <div class="p-6 bg-indigo-800 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold italic text-yellow-400">N-NET / E-Exam</h3>
                        <p class="text-indigo-200">สิทธิการเข้าสอบระดับชาติ ภาคเรียนที่ {{$semestry}}</p>
                    </div>
                    <span class="px-4 py-1 bg-green-500 text-white rounded-full text-sm font-bold animate-pulse">มีสิทธิสอบ</span>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-center">
                        <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <p class="text-xs text-yellow-600 uppercase font-bold">ประกาศสนามสอบ</p>
                            <p class="font-semibold">ติดตามประกาศของสถานศึกษา</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Message Alerts for N-NET Status --}}
            @if(in_array($nnet, ['ผ่านแล้ว', 'E-Exam', null]) && $nnet !== 'N-NET')
            <div class="p-4 rounded-xl bg-gray-100 border border-gray-200 text-center">
                @if($nnet === 'ผ่านแล้ว')
                    <p class="text-green-600 font-bold">✅ คุณทดสอบ N-NET ผ่านเรียบร้อยแล้ว</p>
                @elseif($nnet === 'E-Exam')
                    <p class="text-amber-600 font-bold">🖥️ คุณมีรายการต้องสอบแบบ E-Exam</p>
                @else
                    <p class="text-gray-500 font-medium italic">"คุณยังไม่มีสิทธิสอบ N-NET ในภาคเรียนนี้"</p>
                @endif
            </div>
            @endif

        </div>
    </div>
</x-app-layout>