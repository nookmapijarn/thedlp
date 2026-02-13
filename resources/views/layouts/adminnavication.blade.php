<nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 dark:bg-gray-900/80 dark:border-gray-800 transition-all duration-300">
  <div class="px-4 py-2.5 lg:px-6">
    <div class="flex items-center justify-between">
      
      <div class="flex items-center justify-start">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-gray-500 rounded-xl sm:hidden hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 dark:focus:ring-gray-700 transition-colors">
          <span class="sr-only">Open sidebar</span>
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"></path>
          </svg>
        </button>
        
        <a href="{{ url('/adminuser') }}" class="flex ms-2 md:me-24 items-center group">
          <div class="p-1.5 rounded-lg bg-purple-50 dark:bg-purple-900/30 group-hover:scale-105 transition-transform">
             <x-application-logo class="w-7 h-7 fill-current text-purple-600 dark:text-purple-400" />
          </div>
          <span class="ms-3 self-center text-lg font-semibold tracking-tight text-gray-800 sm:text-xl whitespace-nowrap dark:text-white">
            {{ config('app.name') }} (Admin)
          </span>
        </a>
      </div>

      <div class="flex items-center gap-4">
        <div class="flex items-center ms-3">
          <button type="button" class="flex items-center gap-2 p-1 pr-3 text-sm bg-gray-50 dark:bg-gray-800 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-gray-100 dark:border-gray-700 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-800" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown-user">
            <span class="sr-only">Open user menu</span>
            <img class="w-8 h-8 rounded-full object-cover shadow-sm ring-2 ring-white dark:ring-gray-900" src="https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg" alt="user photo">
            <span class="hidden md:block text-xs font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <div class="z-50 hidden my-4 text-base list-none bg-white border border-gray-100 divide-y divide-gray-50 rounded-2xl shadow-xl dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-700 transition-all" id="dropdown-user">
            <div class="px-5 py-4" role="none">
              <p class="text-sm font-semibold text-gray-900 dark:text-white" role="none">
                {{ Auth::user()->name }}
              </p>
              <p class="text-xs font-medium text-gray-400 truncate mt-0.5" role="none">
                {{ Auth::user()->email }}
              </p>
            </div>
            
            <ul class="py-1" role="none">
              <li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                  @csrf
                </form>
                
                <button type="button" 
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                  class="flex items-center w-full px-5 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors" 
                  role="menuitem">
                  <svg class="w-4 h-4 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                  {{ __('ออกจากระบบ') }}
                </button>
              </li>
            </ul>
          </div>
          </div>
      </div>

    </div>
  </div>
</nav>