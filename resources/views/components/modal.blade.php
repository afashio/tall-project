@props(['trigger'])
<div
    class="flex fixed top-0 bg-gray-900 bg-opacity-60 items-center w-full h-full"
    x-show="{{ $trigger }}"
    @click.self="{{ $trigger }} = false"
    @keydown.escape.window="{{ $trigger }} = false"
>
    <div {{ $attributes->merge(['class' => 'm-auto bg-gray-500 shadow-2xl rounded-xl p-8']) }} >
       {{ $slot }}
    </div>
</div>
