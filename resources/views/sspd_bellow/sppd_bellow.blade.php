<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Konfirmasi {{ $datas == 'true' ? 'Kedatangan' : 'Ke Kantor/Rumah' }}
        </h2>
    </x-slot>
        {!! $page_html !!}
</x-app-layout>
