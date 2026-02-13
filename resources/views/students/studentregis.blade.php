<x-app-layout>
    <x-slot name="header">
        <h6 class="font-bold text-slate-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            {{ __('ประวัติการลงทะเบียนเรียน') }}
        </h6>
    </x-slot>

    <div x-data="{ 
        search: '', 
        typeFilter: 'all',
        match(name, code, type) {
            const matchesSearch = name.toLowerCase().includes(this.search.toLowerCase()) || 
                                 code.toLowerCase().includes(this.search.toLowerCase());
            const matchesType = this.typeFilter === 'all' || type == this.typeFilter;
            return matchesSearch && matchesType;
        }
    }" class="p-4 sm:p-6">

        <div class="mb-6 bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input x-model="search" type="text" placeholder="ค้นหาชื่อวิชา หรือรหัสวิชา..." 
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm">
            </div>
            <div class="flex gap-2">
                <select x-model="typeFilter" class="border border-slate-200 rounded-xl py-2 px-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="all">ทุกประเภท</option>
                    <option value="1">วิชาบังคับ</option>
                    <option value="2">วิชาเลือก</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- 1. เรียนแล้ว (Success Section) --}}
            <div class="flex flex-col h-[calc(100vh-280px)] min-h-[500px]">
                <div class="bg-emerald-500 p-4 rounded-t-[2rem] flex justify-between items-center shadow-lg shadow-emerald-100 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full"></div>
                    <h3 class="text-white font-black tracking-wide flex items-center gap-2 relative z-10">
                        <span class="bg-white/20 p-1.5 rounded-lg text-xs">✓</span>
                        เรียนแล้ว
                    </h3>
                    <span class="bg-white text-emerald-600 px-4 py-1 rounded-full text-xs font-black shadow-sm relative z-10">
                        {{$sumcredit}} หน่วยกิต
                    </span>
                </div>
                
                <div class="bg-white border-x border-b border-slate-100 rounded-b-[2rem] flex-1 overflow-y-auto p-4 space-y-3 shadow-sm">
                    @foreach($learned as $l)
                    <div x-show="match('{{$l['sub_name']}}', '{{$l['sub_code']}}', '{{$l['sub_type']}}')" 
                         x-transition.opacity
                         class="group border border-slate-50 bg-slate-50/50 p-4 rounded-2xl hover:border-emerald-200 hover:bg-white transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{$l['sub_code']}}</span>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ $l['sub_type']==1 ? 'bg-indigo-100 text-indigo-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $l['sub_type']==1 ? 'วิชาบังคับ' : 'วิชาเลือก' }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700 leading-snug mb-3">{{$l['sub_name']}}</h4>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400">{{$l['sub_credit']}} นก.</span>
                            <span class="text-sm font-black text-emerald-600">เกรด {{$l['grade']}}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. กำลังเรียน (Warning Section) --}}
            <div class="flex flex-col h-[calc(100vh-280px)] min-h-[500px]">
                <div class="bg-amber-400 p-4 rounded-t-[2rem] flex justify-between items-center shadow-lg shadow-amber-100 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full"></div>
                    <h3 class="text-slate-800 font-black tracking-wide flex items-center gap-2 relative z-10">
                        <span class="bg-white/40 p-1.5 rounded-lg text-xs">⏳</span>
                        กำลังเรียน
                    </h3>
                    <span class="bg-slate-800 text-white px-4 py-1 rounded-full text-[10px] font-bold relative z-10">
                        เทอม {{$semestry}}
                    </span>
                </div>
                
                <div class="bg-white border-x border-b border-slate-100 rounded-b-[2rem] flex-1 overflow-y-auto p-4 space-y-3 shadow-sm">
                    @foreach($current_regis as $cr)
                    <div x-show="match('{{$cr->SUB_NAME}}', '{{$cr->SUB_CODE}}', '{{$cr->SUB_TYPE}}')" 
                         x-transition.opacity
                         class="group border border-slate-50 bg-slate-50/50 p-4 rounded-2xl hover:border-amber-200 hover:bg-white transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{$cr->SUB_CODE}}</span>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ $cr->SUB_TYPE==1 ? 'bg-indigo-100 text-indigo-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $cr->SUB_TYPE==1 ? 'วิชาบังคับ' : 'วิชาเลือก' }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700 leading-snug mb-3">{{$cr->SUB_NAME}}</h4>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400">{{$cr->SUB_CREDIT}} นก.</span>
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                                <span class="text-[10px] font-bold text-amber-600 uppercase">In Progress</span>
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. ยังไม่เรียน/ไม่ผ่าน (Pending Section) --}}
            <div class="flex flex-col h-[calc(100vh-280px)] min-h-[500px]">
                <div class="bg-slate-700 p-4 rounded-t-[2rem] flex justify-between items-center shadow-lg shadow-slate-200 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full"></div>
                    <h3 class="text-white font-black tracking-wide flex items-center gap-2 relative z-10">
                        <span class="bg-white/20 p-1.5 rounded-lg text-xs">!</span>
                        ยังไม่เรียน/ไม่ผ่าน
                    </h3>
                </div>
                
                <div class="bg-white border-x border-b border-slate-100 rounded-b-[2rem] flex-1 overflow-y-auto p-4 space-y-3 shadow-sm">
                    @foreach($notlearned as $l)
                    <div x-show="match('{{$l['sub_name']}}', '{{$l['sub_code']}}', '{{$l['sub_type']}}')" 
                         x-transition.opacity
                         class="group border border-slate-50 bg-slate-50/50 p-4 rounded-2xl hover:border-slate-300 hover:bg-white transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{$l['sub_code']}}</span>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold {{ $l['sub_type']==1 ? 'bg-indigo-100 text-indigo-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $l['sub_type']==1 ? 'วิชาบังคับ' : 'วิชาเลือก' }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-600 leading-snug mb-3">{{$l['sub_name']}}</h4>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-100 text-slate-400">
                            <span class="text-[10px] font-bold">{{$l['sub_credit']}} นก.</span>
                            <span class="text-[10px] font-bold italic">รอลงทะเบียน</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>