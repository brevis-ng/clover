<div class="tg-bg-color tg-text-color my-2">
    <!-- Shipping information -->
    <div>
        <h3 class="text-lg font-bold uppercase">{{ __('admin.shipping_info') }}</h3>
        <div class="grid grid-cols-1 gap-3 my-2">
            <label class="block">
                <span class="text-gray-700 inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    {{ __('admin.name') }}
                </span>
                <input type="text" class="pt-1 mt-0 block w-full px-0.5 text-indigo-500 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-indigo-500" placeholder="">
            </label>
            <label class="block">
                <span class="text-gray-700 inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                    {{ __('admin.phone') }}
                </span>
                <input type="text" class="pt-1 mt-0 block w-full px-0.5 text-indigo-500 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-indigo-500" placeholder="">
            </label>
            <label class="block">
                <span class="text-gray-700 inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    {{ __('admin.address') }}
                </span>
                <input type="text" class="pt-1 mt-0 block w-full px-0.5 text-indigo-500 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-indigo-500" placeholder="">
            </label>
            <label class="block">
                <span class="text-gray-700 inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                    </svg>
                    {{ __('admin.payment') }}
                </span>
                <div class="flex items-center mb-1 mx-4">
                    <input id="payment_cod" type="radio" name="payment" value="cod" class="block" checked>
                    <label for="payment_cod" class="ml-2">{{ __('admin.cod') }}</label>
                </div>
                <div class="flex items-center mb-1 mx-4">
                    <input id="payment_bank" type="radio" name="payment" value="bank" class="block">
                    <label for="payment_bank" class="ml-2">{{ __('admin.cod') }}</label>
                </div>
            </label>
        </div>
    </div>
    <!-- Bill -->
    <div class="items-center px-2">
        <h3 class="text-lg font-bold uppercase">{{ __('admin.bill') }}</h3>
    </div>
    <div class="my-3 divide-y divide-dashed divide-[--tg-theme-hint-color]">
        @foreach ($cart as $item)
        <div class="flex justify-between my-1">
            <img src="{{ '/storage/' . $item->product->image }}" class="aspect-video object-contain w-1/6 flex-none" />
            <h3 class="font-normal grow">{{ $item->product->name }} <span class="text-orange-500 ml-2">x{{
                    $item->quantity }}</span></h3>
            <div class="font-oswald">{{ config('clover.currency') . $item->amount }}</div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between items-center">
        <h3>{{ __('admin.ship_fee') }}</h3>
        <p>{{ 0 }}</p>
    </div>
    <div class="flex justify-between items-center">
        <h3>{{ __('admin.total') }}</h3>
        <p>{{ config('clover.currency') . round($this->getSubtotal()) }}</p>
    </div>
</div>

@push('scripts')
@endpush
