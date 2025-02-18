<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form SPPD') }}
        </h2>
    </x-slot>
    @if ($sppd_bellow)
        {!! $sppd_bellow !!}
    @endif
</x-app-layout>
