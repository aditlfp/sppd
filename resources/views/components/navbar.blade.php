<nav class="min-h-14 bg-blue-500 drop-shadow-md fixed z-[1005] w-full top-0">
    <div class="flex items-center justify-between h-14 mx-5">
        <p class="capitalize text-white font-bold fixed left-5 z-[1001]">{{ Auth::user()->name }}</p>
        {{-- Mobile --}}
        <p id="labelMenu"
            class=" opacity-0 text-center capitalize text-white font-bold fixed inset-x-[25%] z-[1002] h-fit transition duration-50000 ease-in-out">
            Menu - Menu</p>
        <button id="hamburger-button" class="block lg:hidden focus:outline-none fixed right-5 z-[1005]">
            <div class="hamburger-line transition duration-300 ease-in-out transform"></div>
            <div class="hamburger-line transition duration-300 ease-in-out transform"></div>
            <div class="hamburger-line transition duration-300 ease-in-out transform"></div>
        </button>
    </div>
</nav>
<div id="fullscreen-menu"
    class="fixed top-0 left-0 inset-0 bg-blue-500 transform translate-x-full transition-transform duration-500 ease-in-out z-[1001]">
    <div id="divIsi"
        class="flex flex-col gap-2 items-center justify-center h-full transition-all transform -translate-x-10 duration-1000 ease-in-out opacity-0">
        <a href="{{ route('dashboard') }}" class="text-white font-bold text-xl">Dashboard</a>
        <a href="{{ route('main_sppds.index') }}" class="text-white font-bold text-xl">SPPD</a>

        @if (auth()->user()->role->name == 'admin')
            <a href="{{ route('eslons.index') }}" class="text-white font-bold text-xl">Eslon</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="text-white font-bold text-xl">Logout</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#hamburger-button').click(function() {
            $(this).toggleClass('open');
            $('#fullscreen-menu').toggleClass('translate-x-0 translate-x-full');
            $('#divIsi').toggleClass('opacity-100 opacity-0 -translate-x-10');
            $('#labelMenu').toggleClass('opacity-0 opacity-100');
        });
    });
</script>
<style>
    .hamburger-line {
        width: 25px;
        height: 3px;
        background-color: white;
        margin: 4px 0;
    }

    #hamburger-button.open .hamburger-line:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    #hamburger-button.open .hamburger-line:nth-child(2) {
        opacity: 0;
    }

    #hamburger-button.open .hamburger-line:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }
</style>
