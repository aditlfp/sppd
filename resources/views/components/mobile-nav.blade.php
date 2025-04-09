<div class="dock z-index-[100] sm:hidden items-start">
    <button>
      <i class="ri-logout-box-line text-2xl text-center font-semibold text-red-500/80"></i>
      <span class="h-[0.8em]"></span>
    </button>
    
    <button @class(['dock-active' => Route::is('dashboard')])>
      <i class="ri-home-4-line text-2xl text-center font-semibold text-blue-500/80"></i>
      <span class="h-[0.8em]"></span>
    </button>
    
    <button @class(['dock-active' => Route::is('main_sppds.*')])>
      <i class="ri-flight-takeoff-line text-2xl text-center font-semibold text-green-500/80"></i>
      <span class="h-[0.8em]"></span>
    </button>
  </div>