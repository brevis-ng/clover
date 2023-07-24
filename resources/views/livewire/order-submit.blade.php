<div>
    <!-- Shipping information -->
    <div class="tg-bg-color p-2 mb-3">
        <h3 class="text-base font-bold uppercase">{{ __('frontend.order_information') }}</h3>
        <form wire:submit.prevent="submit">
            <div class="grid grid-cols-1 gap-3 my-2">
                <label class="block">
                    <span class="inline-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        {{ __('frontend.name') }}
                    </span>
                    <input type="text" wire:model.debounce.1s="name" autofocus class="py-0.5 mt-0 block w-full px-0.5 tg-link-color bg-transparent border-0 border-b-[1px] border-[--tg-theme-link-color] focus:ring-0 focus:border-[--tg-theme-link-color]" placeholder="">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="inline-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        {{ __('frontend.phone') }}
                    </span>
                    <input type="tel" wire:model.debounce.1s="phone" class="py-0.5 mt-0 block w-full px-0.5 tg-link-color bg-transparent border-0 border-b-[1px] border-[--tg-theme-link-color] focus:ring-0 focus:border-[--tg-theme-link-color]" placeholder="">
                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="inline-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        {{ __('frontend.address') }}
                    </span>
                    <input type="text" wire:model.debounce.1s="address" class="py-0.5 mt-0 block w-full px-0.5 tg-link-color bg-transparent border-0 border-b-[1px] border-[--tg-theme-link-color] focus:ring-0 focus:border-[--tg-theme-link-color]" placeholder="">
                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="inline-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                        </svg>
                        {{ __('frontend.payment_method') }}
                    </span>
                    <div class="flex items-center mb-1 mx-4">
                        <input id="payment_cod" type="radio" wire:model="payment" value="cod" class="block">
                        <label for="payment_cod" class="ml-2">{{ __('frontend.cod') }}</label>
                    </div>
                    <div class="flex items-center mb-1 mx-4">
                        <input id="payment_bank" type="radio" wire:model="payment" value="bank" class="block">
                        <label for="payment_bank" class="ml-2">{{ __('frontend.bank') }}</label>
                    </div>
                    @error('payment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                        {{ __('frontend.notes') }}
                    </span>
                    <input type="text" wire:model.debounce.1s="notes" class="py-0.5 mt-0 block w-full px-0.5 tg-link-color bg-transparent border-0 border-b-[1px] border-[--tg-theme-link-color] focus:ring-0 focus:border-[--tg-theme-link-color]" placeholder="">
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </label>
                <input type="submit" class="sr-only" id="submitBtn">
            </div>
        </form>
    </div>
    <!-- Bill -->
    <div class="tg-bg-color p-2">
        <h3 class="text-base font-bold uppercase">{{ __('frontend.bill') }}</h3>
        <div class="my-3 divide-y divide-dashed divide-[--tg-theme-hint-color]">
            @foreach ($cart as $item)
            <div class="flex justify-between items-center py-1 text-sm">
                @if($item->product['image'] != null && Illuminate\Support\Facades\Storage::disk("products")->exists($item->product['image']))
                    <img class="aspect-video object-cover w-1/6 flex-none" src="{{ Illuminate\Support\Facades\Storage::disk('products')->url($item->product['image']) }}" alt="{{ $item->product['name'] }}">
                @else
                    <img class="aspect-video object-cover w-1/6 flex-none" src="{{ '/storage/default.jpg' }}" alt="{{ $item->product['name'] }}">
                @endif
                <h3 class="ml-2 grow">{{ $item->product['name'] }} <span class="text-orange-500 ml-2">x{{
                        $item->quantity }}</span></h3>
                <div class="font-oswald">{{ money($item->amount, convert: true) }}</div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-between items-center">
            <h3>{{ __('frontend.shipping_amount') }}</h3>
            <p class="font-oswald">N/A</p>
        </div>
        <div class="flex justify-between items-center">
            <h3>{{ __('frontend.total_amount') }}</h3>
            <p class="font-oswald text-orange-500">{{ money($subtotal, convert: true) }}</p>
        </div>
    </div>
</div>

@push('scripts')
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        if (Telegram.WebApp.initData || Telegram.WebApp.initDataUnsafe) {
            Telegram.WebApp.BackButton.isVisible = true;
            Telegram.WebApp.onEvent('backButtonClicked', () => {
                Telegram.WebApp.HapticFeedback.impactOccurred('medium');
                window.location.href = "{{ route('frontend.carts') }}";
            });

            const mainButton = Telegram.WebApp.MainButton;
            mainButton.setParams({
                text: "{{ Str::upper(__('frontend.order_placed')) }}",
                color: "#525FE1",
                is_active: true,
                is_visible: true,
            });
            mainButton.onClick(() => {
                document.getElementById("submitBtn").click();
            });
            // Listen for events
            Livewire.on('tg:orderPlaced', msg => {
                Telegram.WebApp.showAlert(msg, () => {
                    Telegram.WebApp.HapticFeedback.notificationOccurred('success');
                    setTimeout(function() {
                        Telegram.WebApp.close();
                    }, 50);
                });
            })
        }
    });
</script>
@endpush
