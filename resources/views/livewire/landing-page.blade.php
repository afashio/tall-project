<div
    class="flex flex-col bg-indigo-900 w-full h-screen"
    x-data="{
            showSubscribe: @entangle('showSubscribe'),
            showSuccess: @entangle('showSuccess')
        }"
>
    <nav class="flex pt-5 justify-between container mx-auto text-indigo-200">
        <a class="text-4xl font-bold" href="{{ route('home') }}">
            <x-application-logo class="w-16 h-16 fill-current"></x-application-logo>
        </a>
        <div class="flex justify-end">
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </nav>
    <div class="flex container mx-auto items-center h-full">
        <div class="flex flex-col w-1/3 items-start">
            <h1 class="text-white font-bold text-5xl leading-tight mb-4">Simple generic landing page to
                subscribe</h1>
            <p class="text-indigo-200 text-xl mb-10">
                Just messing with <span class="font-bold underline">TALL</span> stack. Consider subscribing.</p>
            <x-button
                class="py-3 bg-red-500 hover:bg-red-700"
                @click="showSubscribe = true"
            >
                Subscribe
            </x-button>
        </div>
    </div>
    <x-modal class="bg-pink-500" trigger="showSubscribe">
        <p class="text-white text-5xl font-extrabold text-center">Let's do it!</p>
        <form
            class="flex flex-col items-center p-24"
            wire:submit.prevent="subscribe">
            <x-input
                class="px-5 py-3 w-80 border border-blue-500"
                type="email"
                name="email"
                placeholder="Email address"
                wire:model.defer="email">
            </x-input>
            <span class="text-gray-100 text-xs">
                @if($errors->has('email'))
                    {{ $errors->first('email') }}
                @else
                    We will send you a confirmation email
                @endif
            </span>
            <x-button class="px-5 py-3 mt-5 w-80 bg-blue-500 justify-center">
                <span class="animate-spin" wire:loading wire:terget="subscribe">&#9696;</span>
                <span wire:loading.remove wire:terget="subscribe">Get In</span>
            </x-button>
        </form>
    </x-modal>
    <x-modal class="bg-green-500" trigger="showSuccess">
        <p class="animate-pulse text-white text-9xl font-extrabold text-center">
            &check;
        </p>
        <p class="animate-pulse text-white text-5xl font-extrabold text-center mt-8">
            Great!
        </p>
        @if(request()->has('verified') && (int)request()->get('verified') === 1)
            <p class="text-white text-3xl text-center">
                Thx for being w/us!
            </p>
        @else
            <p class="text-white text-3xl text-center">
                See you in your inbox.
            </p>
        @endif
    </x-modal>
</div>
