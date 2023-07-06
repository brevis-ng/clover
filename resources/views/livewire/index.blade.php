<div>
    <div class="mb-3">
        <ul class="flex -mb-px text-center flex-nowrap scroll-smooth scrollbar-hidden overflow-x-scroll">
            <li class="flex-auto text-center mx-2">
                <div>
                    <button wire:click="filter_products"
                        class="w-full inline-block pb-3 pt-1 border-b-2 rounded-t-lg whitespace-nowrap border-transparent focus:border-blue-600 focus:tg-link-color"
                    >
                        All
                    </button>
                </div>
            </li>
            @foreach ($categories as $category)
            <li class="flex-auto text-center mx-2">
                <div>
                    <button wire:click="filter_products({{ $category->id }})"
                        class="w-full inline-block pb-3 pt-1 border-b-2 rounded-t-lg whitespace-nowrap border-transparent focus:border-blue-600 focus:tg-link-color"
                    >
                        {{ $category->name }}
                    </button>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 tg-bg-color p-2">
        @foreach ($products as $product)
        <div class="tg-secondary-bg-color" x-data>
            <div class="relative">
                <div class="bg-red-500 text-white absolute px-2 py-1 uppercase">{{ $product->code }}</div>
                @if(file_exists(public_path('storage/' .$product->image )) && $product->image != null)
                    <img class="aspect-[4/3] object-cover" src="{{ '/storage/' . $product->image }}" alt="{{ $product->name }}">
                @else
                    <img class="aspect-[4/3] object-cover" src="{{ '/storage/default.jpg' }}" alt="{{ $product->name }}">
                @endif
            </div>
            <div class="text-center justify-center my-1">
                <h3 class="font-bold tracking-wide tg-text-color">{{ $product->name }}</h3>
                <p class="text-xs line-clamp-2 tg-hint-color">{{ $product->description }}</p>
                <p class="font-semibold tracking-wide text-base text-orange-600 font-oswald">{{ config('clover.currency') . $product->price }}/{{ $product->unit }}</p>
            </div>
            @if ($this->getQuantity($product) == 0)
            <button @click="Telegram.WebApp.HapticFeedback.impactOccurred('soft'); $wire.increment({{ $product }})"
                class="subpixel-antialiased tracking-tighter uppercase w-full tg-btn-color tg-btn-text-color py-2 flex justify-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                add to cart
            </button>
            @else
            <div class="flex justify-between items-center">
                <button class="py-2 px-5 bg-rose-400 text-white" @click="Telegram.WebApp.HapticFeedback.impactOccurred('soft'); $wire.decrement({{ $product }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                    </svg>
                </button>
                <p>{{ $this->getQuantity($product) }}</p>
                <button class="py-2 px-5 tg-btn-color tg-btn-text-color" @click="Telegram.WebApp.HapticFeedback.impactOccurred('soft'); $wire.increment({{ $product->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        Telegram.WebApp.BackButton.isVisible = false;

        if (parseInt("{{ App\Helpers\CartManager::count() }}") > 0) {
            showMainButton();
        }

        Livewire.on("cart-updated", (count) => {
            if (count > 0) {
                showMainButton();
            } else {
                Telegram.WebApp.MainButton.hide();
            }
        });

        function showMainButton() {
            Telegram.WebApp.MainButton.setParams({
                text: "{{ Str::upper(__('admin.view_carts')) }}",
                color: "#525FE1",
                is_active: true,
                is_visible: true,
            }).onClick(() => {
                window.location.href = "{{ route('frontend.carts') }}";
            });
        };
    });
</script>
@endpush
