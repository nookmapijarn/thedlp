<!-- Mobile bottom tab menu -->
@php
$currentPath = Request::path();
@endphp
<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 sm:hidden">
  <div class="flex justify-around text-sm text-gray-700 dark:text-white">

    <!-- ประวัติการเรียน -->
    <a href="{{ url('ประวัติการเรียน') }}" class="flex flex-col items-center p-2 {{ $currentPath == 'teachers' ? 'text-blue-500' : '' }}">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 14l9-5-9-5-9 5 9 5z" />
        <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
      </svg>
      <span class="text-xs">ประวัติการเรียน</span>
    </a>

    <!-- ลงทะเบียน -->
    <a href="{{ url('ประวัติการลงทะเบียน') }}" class="flex flex-col items-center p-2 {{ $currentPath == 'teachers/tstudentprofile' ? 'text-blue-500' : '' }}">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 11c0-1.105.895-2 2-2s2 .895 2 2-.895 2-2 2-2-.895-2-2z" />
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
        <path d="M12 7V4m0 0H9m3 0h3" />
      </svg>
      <span class="text-xs">ลงทะเบียน</span>
    </a>

    <!-- ตารางสอบ -->
    <a href="{{ url('ตารางสอบ') }}" class="flex flex-col items-center p-2 {{ $currentPath == 'teachers/treport' ? 'text-blue-500' : '' }}">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <span class="text-xs">ตารางสอบ</span>
    </a>

    <!-- กิจกรรม -->
    <a href="{{ url('กพช') }}" class="flex flex-col items-center p-2 {{ $currentPath == 'teachers/tgrade' ? 'text-blue-500' : '' }}">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9.75 17L3 9.75 4.5 8.25 9.75 14.25 19.5 4.5 21 6z" />
      </svg>
      <span class="text-xs">กิจกรรม</span>
    </a>

    <!-- ตั้งค่าระบบ -->
    <a href="{{ url('profile') }}" class="flex flex-col items-center p-2 {{ $currentPath == 'teachers/tscore' ? 'text-blue-500' : '' }}">
      <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 6V4m0 16v-2m8-8h2M4 12H2m15.364-6.364l1.414-1.414M6.343 17.657l-1.414 1.414M17.657 17.657l1.414 1.414M6.343 6.343L4.93 4.93" />
        <circle cx="12" cy="12" r="3" />
      </svg>
      <span class="text-xs">ตั้งค่าระบบ</span>
    </a>

  </div>
</nav>
