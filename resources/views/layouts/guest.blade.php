<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SPPD Online - PT. Surya Amanah Cendikia</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-cover bg-right-bottom bg-[url(/public/img/bgLogin.jpg)]">
            <div class="bg-white drop-shadow-md w-[90%] h-[80svh] rounded-sm flex flex-col md:flex-row justify-between pb-[20%] md:p-20">
                <div class="flex flex-col justify-between md:justify-start gap-y-8 ">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('img/sac.png') }}" alt="sac.png" width="50" srcset="{{ asset('img/sac.png') }}" class=" p-2">
                        <p class="text-xs font-semibold">PT. Surya Amanah Cendikia Ponorogo</p>
                    </div>
                    <div class="flex-col items-center justify-center gap-2 hidden md:flex">
                        <p class="text-3xl font-extrabold flex items-center justify-center gap-1"><span class="py-0.5 px-2.5 rounded-full bg-blue-500 text-white text-xl text-center">e</span>-SPPD</p>
                        <p class="hidden md:block text-xs max-w-[450px] text-center">Merupakan sistem berbasis digital yang digunakan untuk mengelola administrasi perjalanan dinas secara elektronik. Sistem ini memungkinkan proses pengajuan, persetujuan, dan pelaporan perjalanan dinas dilakukan secara efisien tanpa harus menggunakan dokumen fisik. <span class="font-semibold">e-SPPD</span> membantu meningkatkan transparansi, mengurangi birokrasi, serta mempermudah monitoring dan pelacakan anggaran perjalanan dinas.</p>
                    </div>
                </div>
                <div class="flex md:hidden flex-col items-center justify-center gap-2">
                    <p class="text-3xl font-extrabold flex items-center justify-center gap-1"><span class="py-0.5 px-2.5 rounded-full bg-blue-500 text-white text-xl text-center">e</span>-SPPD</p>
                    <p class="text-xs text-slate-600">E-Surat Perintah Perjalanan Dinas</p>
                </div>
                <div class="divider divider-horizontal hidden md:flex"></div>
                <div class="text-center">
                    <div class="w-full md:w-[30svw] mt-6 px-6 py-4 overflow-hidden sm:rounded-lg text-start">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
