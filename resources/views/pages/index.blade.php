@extends('layouts.base')

@section('content')

<div class="min-h-screen container tg-bg-color tg-text-color">
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        <div class="tg-secondary-bg-color" x-data="{ count: 0 }">
            <div class="relative">
                <div class="bg-red-500 text-white absolute px-2 py-1 uppercase">ad1</div>
                <img class="spect-square object-contain" src="/storage/0o4qWNRCNw7txQJsoxWQZo8DLAitIt-metaYmFrZWQtY2hpY2tlbi13aW5ncy1hc2lhbi1zdHlsZS10b21hdG9lcy1zYXVjZS1wbGF0ZS5qcGc=-.jpg" alt="">
            </div>
            <div class="text-center justify-center">
                <h3 class="text-lg font-bold tracking-wide tg-text-color my-2">product name</h3>
                <p class="text-xs line-clamp-2 tg-hint-color">Let all your things have their places; let each part of your business have its time.</p>
                <p class="font-bold tracking-wide text-lg text-orange-600 font-oswald my-2">₱300</p>
            </div>
            <button x-on:click="count++" x-show="count == 0"
                class="subpixel-antialiased tracking-tighter uppercase w-full tg-btn-color tg-btn-text-color py-2 flex justify-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                add to cart
            </button>
            <div class="flex justify-between items-center"
                x-show="count > 0"
            >
                <button class="py-2 px-6 bg-rose-400 text-white" x-on:click="count--">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                    </svg>
                </button>
                <p x-text="count"></p>
                <button class="py-2 px-6 tg-btn-color tg-btn-text-color" x-on:click="count++, console.log(count)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
