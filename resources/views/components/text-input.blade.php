@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input input-sm rounded-sm']) }}>
