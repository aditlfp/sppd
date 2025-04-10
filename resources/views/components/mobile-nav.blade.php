<div class="dock z-index-[100] sm:hidden items-start">
  <button
    x-data
    @click.prevent="document.getElementById('logout-form').submit()"
    class="flex items-center space-x-2"
  >
    <i class="ri-logout-box-line text-2xl text-center font-semibold text-red-500/80"></i>
    <span class="h-[0.8em]"></span>
  </button>
    
  <a href="{{ route('dashboard') }}" @class(['dock-active' => Route::is('dashboard')])>
    <i class="ri-home-4-line text-2xl text-center font-semibold text-blue-500/80"></i>
    <span class="h-[0.8em]"></span>
  </a>
  
  <a href="{{ route('main_sppds.index') }}" @class(['dock-active' => Route::is('main_sppds.*')])>
    <i class="ri-flight-takeoff-line text-2xl text-center font-semibold text-green-500/80"></i>
    <span class="h-[0.8em]"></span>
  </a>
</div>
  <span class="hidden">
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
      @csrf
    </form>
  </span>