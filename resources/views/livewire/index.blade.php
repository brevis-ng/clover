<div x-data="{
        active: 0,
        isActive: function (val) {
            return val == this.active
        },
        setActive: function (val) {
            this.active = val
            $wire.set('category_id', val)
        },
        handleIncrement: function (el) {
            Telegram.WebApp.HapticFeedback.impactOccurred('soft');
            $wire.increment(el.currentTarget.dataset.product);
        },
        handleDecrement: function (data) {
            Telegram.WebApp.HapticFeedback.impactOccurred('soft');
            $wire.decrement(data);
        },
    }">
    <div class="mb-3">
        <ul class="flex -mb-px text-center flex-nowrap scroll-smooth scrollbar-hidden overflow-x-scroll">
            <li class="flex-auto text-center mx-2">
                <div>
                    <button @click="setActive(0)"
                        class="w-full inline-block pb-3 pt-1 border-b-2 whitespace-nowrap"
                        :class="isActive(0) ? 'border-indigo-500 tg-link-color' : 'border-transparent tg-text-color'"
                    >
                        All
                    </button>
                </div>
            </li>
            @foreach ($categories as $category)
            <li class="flex-auto text-center mx-2">
                <div>
                    <button @click="setActive({{ $category->id }})"
                        class="w-full inline-block pb-3 pt-1 border-b-2 whitespace-nowrap"
                        :class="isActive({{ $category->id }}) ? 'border-indigo-500 tg-link-color' : 'border-transparent tg-text-color'"
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
        <div class="tg-secondary-bg-color flex flex-col">
            <div class="relative">
                <div class="bg-red-500 text-white absolute p-1 uppercase">{{ $product->code }}</div>
                <div class="bg-blue-500 text-white absolute px-1 bottom-0 right-0">{{ $product?->remarks }}</div>
                @if($product->image && Illuminate\Support\Facades\Storage::disk("products")->exists($product->image))
                    <img class="aspect-[4/3] object-cover" src="{{ Illuminate\Support\Facades\Storage::disk('products')->url($product->image) }}" alt="{{ $product->name }}">
                @else
                    <img class="aspect-[4/3] object-cover" src="{{ '/storage/default.jpg' }}" alt="{{ $product->name }}">
                @endif
            </div>
            <div class="text-center justify-center my-1 grow">
                <h3 class="font-bold tracking-wide tg-text-color">{{ $product->name }}</h3>
                <p class="text-xs line-clamp-2 tg-hint-color">{!! $product->description !!}</p>
                <p class="font-semibold tracking-wide text-base text-orange-600 font-oswald">{{ money($product->price, convert: true) }}/{{ App\Enums\Units::getTranslation($product->unit) }}</p>
            </div>
            @if ($this->getQuantity($product->id) == 0)
            <button @click="handleIncrement" data-product="{{ $product }}"
                class="subpixel-antialiased tracking-tighter uppercase w-full tg-btn-color tg-btn-text-color py-2 flex justify-center bottom-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                add to cart
            </button>
            @else
            <div class="flex justify-between items-center">
                <button class="py-2 px-5 bg-rose-400 text-white" @click="handleDecrement({{ $product->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                    </svg>
                </button>
                <p>{{ $this->getQuantity($product->id) }}</p>
                <button class="py-2 px-5 tg-btn-color tg-btn-text-color" @click="handleIncrement" data-product="{{ $product }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    {{ $products->links() }}
</div>

@push('scripts')
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        Telegram.WebApp.BackButton.isVisible = false;

        if (parseInt("{{ App\Helpers\CartManager::count() }}") > 0) {
            showMainButton();
        }

        Livewire.on("cart-updated", (count) => {
            count > 0 ? showMainButton() : Telegram.WebApp.MainButton.hide();
        });

        function showMainButton() {
            Telegram.WebApp.MainButton.setParams({
                text: "{{ Str::upper(__('frontend.cart_view')) }}",
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
