<nav x-data="{ open: false }"">
    <div class="min-h-14 bg-blue-500 drop-shadow-md fixed z-[1005] w-screen top-0">
        <div class="flex items-center justify-between h-14 mx-5">
            <p id="userName" class="capitalize text-white font-bold fixed left-5 z-[1001] transition-all duration-300 ease-in-out opacity-100">{{ Auth::user()->name }}</p>
            {{-- Mobile --}}
            <p id="labelMenu"
                class="opacity-0 text-center capitalize text-white font-bold fixed inset-x-[25%] z-[1002] h-fit transition duration-200 ease-in-out">
                Menu SPPD</p>
            <button @click="open = !open" id="hamburger-button" class="block lg:hidden focus:outline-none fixed right-5 z-[1005]">
                <div class="hamburger-line transition duration-300 ease-in-out transform"></div>
                <div class="hamburger-line transition duration-300 ease-in-out transform"></div>
                <div class="hamburger-line transition duration-300 ease-in-out transform"></div>
            </button>
        </div>
    </div>
    <div id="fullscreen-menu"
        class=" fixed top-0 left-0 inset-0 bg-blue-500 transform translate-x-full transition-transform duration-500 ease-in-out z-[1001]">
        <div id="divIsi"
            class="hidden flex-col gap-3 items-start mt-[40%] mx-[7%] h-full transition-all transform -translate-x-10 duration-1000 ease-in-out opacity-0">
            <a href="{{ route('dashboard') }}" class="text-white font-bold text-xl flex items-center justify-center gap-2 btn bg-sky-500 w-full rounded-sm p-2 px-[25svw] text-center"><svg
                    xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M3 10C3 10.5523 3.44772 11 4 11L12 11C12.5523 11 13 10.5523 13 10V4C13 3.44772 12.5523 3 12 3H4C3.44772 3 3 3.44772 3 4V10ZM11 20C11 20.5523 11.4477 21 12 21H20C20.5523 21 21 20.5523 21 20V14C21 13.4477 20.5523 13 20 13H12C11.4477 13 11 13.4477 11 14V20ZM13 15H19V19H13V15ZM3 20C3 20.5523 3.44772 21 4 21H8C8.55229 21 9 20.5523 9 20V14C9 13.4477 8.55229 13 8 13H4C3.44772 13 3 13.4477 3 14V20ZM5 19V15H7V19H5ZM5 9V5L11 5L11 9L5 9ZM20 11C20.5523 11 21 10.5523 21 10V4C21 3.44772 20.5523 3 20 3H16C15.4477 3 15 3.44772 15 4V10C15 10.5523 15.4477 11 16 11H20ZM19 9H17V5H19V9Z">
                    </path>
                </svg> Dashboard</a>
            <a href="{{ route('main_sppds.index') }}" class="text-white font-bold text-xl flex items-center justify-center gap-2 btn bg-sky-500 w-full rounded-sm p-2 px-[25svw] text-center"><svg
                    xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M7 5V2C7 1.44772 7.44772 1 8 1H16C16.5523 1 17 1.44772 17 2V5H21C21.5523 5 22 5.44772 22 6V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V6C2 5.44772 2.44772 5 3 5H7ZM9 13H4V19H20V13H15V16H9V13ZM20 7H4V11H9V9H15V11H20V7ZM11 11V14H13V11H11ZM9 3V5H15V3H9Z">
                    </path>
                </svg> SPPD</a>
            @if (auth()->user()->role_id == 2)
                <a href="{{ route('eslons.index') }}" class="text-white font-bold text-xl flex items-center justify-center gap-2 btn bg-sky-500 w-full rounded-sm p-2 px-[25svw] text-center"><svg
                        xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M17 6C16.4477 6 16 5.55228 16 5C16 4.44772 16.4477 4 17 4C17.5523 4 18 4.44772 18 5C18 5.55228 17.5523 6 17 6ZM17 8C18.6569 8 20 6.65685 20 5C20 3.34315 18.6569 2 17 2C15.3431 2 14 3.34315 14 5C14 6.65685 15.3431 8 17 8ZM7 3C4.79086 3 3 4.79086 3 7V9H5V7C5 5.89543 5.89543 5 7 5H10V3H7ZM17 21C19.2091 21 21 19.2091 21 17V15H19V17C19 18.1046 18.1046 19 17 19H14V21H17ZM8 13C8 12.4477 7.55228 12 7 12C6.44772 12 6 12.4477 6 13C6 13.5523 6.44772 14 7 14C7.55228 14 8 13.5523 8 13ZM10 13C10 14.6569 8.65685 16 7 16C5.34315 16 4 14.6569 4 13C4 11.3431 5.34315 10 7 10C8.65685 10 10 11.3431 10 13ZM17 11C15.8954 11 15 11.8954 15 13H13C13 10.7909 14.7909 9 17 9C19.2091 9 21 10.7909 21 13H19C19 11.8954 18.1046 11 17 11ZM5 21C5 19.8954 5.89543 19 7 19C8.10457 19 9 19.8954 9 21H11C11 18.7909 9.20914 17 7 17C4.79086 17 3 18.7909 3 21H5Z">
                        </path>
                    </svg> Eslon</a>
                <a href="{{ route('pocket_moneys.index') }}"
                    class="text-white font-bold text-xl flex items-center justify-center gap-2 btn bg-sky-500  w-full rounded-sm p-2 px-[25svw] text-center"><svg xmlns="http://www.w3.org/2000/svg"
                        width="20" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M14.0049 2.00281C18.4232 2.00281 22.0049 5.58453 22.0049 10.0028C22.0049 13.2474 20.0733 16.0409 17.2973 17.296C16.0422 20.0718 13.249 22.0028 10.0049 22.0028C5.5866 22.0028 2.00488 18.4211 2.00488 14.0028C2.00488 10.7587 3.9359 7.96554 6.71122 6.71012C7.96681 3.93438 10.7603 2.00281 14.0049 2.00281ZM10.0049 8.00281C6.69117 8.00281 4.00488 10.6891 4.00488 14.0028C4.00488 17.3165 6.69117 20.0028 10.0049 20.0028C13.3186 20.0028 16.0049 17.3165 16.0049 14.0028C16.0049 10.6891 13.3186 8.00281 10.0049 8.00281ZM11.0049 9.00281V10.0028H13.0049V12.0028H9.00488C8.72874 12.0028 8.50488 12.2267 8.50488 12.5028C8.50488 12.7483 8.68176 12.9524 8.91501 12.9948L9.00488 13.0028H11.0049C12.3856 13.0028 13.5049 14.1221 13.5049 15.5028C13.5049 16.8835 12.3856 18.0028 11.0049 18.0028V19.0028H9.00488V18.0028H7.00488V16.0028H11.0049C11.281 16.0028 11.5049 15.7789 11.5049 15.5028C11.5049 15.2573 11.328 15.0532 11.0948 15.0109L11.0049 15.0028H9.00488C7.62417 15.0028 6.50488 13.8835 6.50488 12.5028C6.50488 11.1221 7.62417 10.0028 9.00488 10.0028V9.00281H11.0049ZM14.0049 4.00281C12.2214 4.00281 10.6196 4.78097 9.52064 6.01629C9.68133 6.00764 9.84254 6.00281 10.0049 6.00281C14.4232 6.00281 18.0049 9.58453 18.0049 14.0028C18.0049 14.1655 18 14.327 17.9905 14.4873C19.2265 13.3885 20.0049 11.7866 20.0049 10.0028C20.0049 6.6891 17.3186 4.00281 14.0049 4.00281Z">
                        </path>
                    </svg> Eslon</a>
                <a href="{{ route('transportations.index') }}"
                    class="text-white font-bold text-xl flex items-center justify-center gap-2 btn bg-sky-500 w-full rounded-sm p-2 px-[25svw] text-center"><svg xmlns="http://www.w3.org/2000/svg"
                        width="20" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M19 20H5V21C5 21.5523 4.55228 22 4 22H3C2.44772 22 2 21.5523 2 21V11L4.4805 5.21216C4.79566 4.47679 5.51874 4 6.31879 4H17.6812C18.4813 4 19.2043 4.47679 19.5195 5.21216L22 11V21C22 21.5523 21.5523 22 21 22H20C19.4477 22 19 21.5523 19 21V20ZM20 13H4V18H20V13ZM4.17594 11H19.8241L17.6812 6H6.31879L4.17594 11ZM6.5 17C5.67157 17 5 16.3284 5 15.5C5 14.6716 5.67157 14 6.5 14C7.32843 14 8 14.6716 8 15.5C8 16.3284 7.32843 17 6.5 17ZM17.5 17C16.6716 17 16 16.3284 16 15.5C16 14.6716 16.6716 14 17.5 14C18.3284 14 19 14.6716 19 15.5C19 16.3284 18.3284 17 17.5 17Z">
                        </path>
                    </svg> Transportasi</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="w-full mt-[20%]">
                @csrf
                <button type="submit" class="text-white font-bold text-xl flex items-center justify-center gap-2  w-full rounded-sm p-2 px-[25svw] text-center btn bg-red-500"><svg
                        xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M4 18H6V20H18V4H6V6H4V3C4 2.44772 4.44772 2 5 2H19C19.5523 2 20 2.44772 20 3V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V18ZM6 11H13V13H6V16L1 12L6 8V11Z">
                        </path>
                    </svg> Logout</button>
            </form>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function() {
        $('#hamburger-button').click(function() {
            $(this).toggleClass('open');
            $('#labelMenu').toggleClass('opacity-0', !$('#labelMenu').hasClass('opacity-0'))
               .toggleClass('opacity-100', !$('#labelMenu').hasClass('opacity-100'));
            $('#fullscreen-menu').fadeIn(100).toggleClass('translate-x-0 translate-x-full');
            $('#divIsi').toggleClass('opacity-100 opacity-0 -translate-x-10');
            $('#divIsi').removeClass('hidden').addClass('flex');
            $('#userName').toggleClass('opacity-0 opacity-100');
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
