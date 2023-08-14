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
        username: function () {
            var full_name = '';
            if (Telegram.WebApp.initDataUnsafe.user) {
                full_name = Telegram.WebApp.initDataUnsafe.user.first_name + ' ' + Telegram.WebApp.initDataUnsafe.user.last_name;
            }
            return full_name ? full_name : 'My Darling';
        },
        setLang: function (val) {
            $wire.setLanguage(val)
        }
    }">
    <div class="my-4 mx-2 flex justify-between items-center">
        <h3 class="text-lg text-gray-800 dark:text-white line-clamp-1 text-ellipsis overflow-hidden">Hi, <span x-text="username()" class="font-bold"></span></h3>
        <div class="inline-flex items-center gap-1">
            <label for="languages" class="text-gray-900 dark:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                </svg>
            </label>
            <select id="languages" x-on:change="setLang($event.target.value)"
                class="text-xs bg-white border border-gray-300 text-gray-900 rounded-lg dark:bg-[#04293A] dark:border-slate-800 dark:placeholder-gray-300 dark:text-white">
                <option @if(app()->getLocale() == 'vi') selected @endif value="vi">Tiếng Việt</option>
                <option @if(app()->getLocale() == 'zh') selected @endif value="zh">中文</option>
            </select>
        </div>
    </div>
    <div class="my-3 scrollbar-hidden px-2">
        <ul class="flex flex-nowrap gap-2 items-center scroll-smooth snap-x snap-mandatory overflow-x-auto no-scrollbar">
            <li class="flex-none snap-always snap-center">
                <div @click="setActive(0)"
                    class="overflow-hidden inline-flex items-center rounded-s-md rounded-e-md p-1 border"
                    :class="isActive(0) ? 'bg-[#F0A500] text-[#EEEEEE] dark:bg-[#03506F] dark:text-white dark:border-[#2D4263]' : 'bg-white dark:bg-[#04293A] text-gray-700 dark:text-[#BBBBBB] dark:border-[#04293A]'"
                >
                    <img class="w-10 h-10 object-cover rounded-md" src="{{ '/storage/default.jpg' }}">
                    <div class="mx-1">All</div>
                </div>
            </li>
            @foreach ($categories as $category)
            <li class="flex-none snap-always snap-center cursor-pointer">
                <div @click="setActive({{ $category->id }})"
                    class="overflow-hidden inline-flex items-center rounded-s-md rounded-e-md p-1 border"
                    :class="isActive({{ $category->id }}) ? 'bg-[#F0A500] text-[#EEEEEE] dark:bg-[#03506F] dark:text-white dark:border-[#2D4263]' : 'bg-white dark:bg-[#04293A] text-gray-700 dark:text-[#BBBBBB] dark:border-[#04293A]'"
                >
                    @if($category->image && Illuminate\Support\Facades\Storage::disk("categories")->exists($category->image))
                        <img class="w-10 h-10 object-cover rounded-md" src="{{ Illuminate\Support\Facades\Storage::disk('categories')->url($category->image) }}">
                    @else
                        <img class="w-10 h-10 object-cover rounded-md" src="{{ '/storage/default.jpg' }}">
                    @endif
                    <div class="mx-1">{{ $category->name }}</div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="grid grid-cols-2 gap-x-3 gap-y-5 p-2">
        @foreach ($products as $product)
        <div class="flex flex-col bg-white dark:bg-[#064663] shadow-lg shadow-black/30 overflow-visible">
            <div class="relative">
                <div class="bg-[#E45826] absolute px-1 uppercase top-1 -left-1 before:content-[''] before:inline-block before:absolute before:brightness-75 before:left-0 before:-bottom-1 before:border-[#E45826] before:border-t-4 before:border-l-4 before:border-solid before:border-l-transparent">
                    <span class="text-[#FDF6EC]">{{ $product->code }}</span>
                </div>
                <div class="bg-gray-800/40 text-slate-200 absolute px-1 bottom-0 right-0 rounded">{{ $product?->remarks }}</div>
                @if($product->image && Illuminate\Support\Facades\Storage::disk("products")->exists($product->image))
                    <img class="aspect-[4/3] object-cover" src="{{ Illuminate\Support\Facades\Storage::disk('products')->url($product->image) }}" alt="{{ $product->name }}">
                @else
                    <img class="aspect-[4/3] object-cover" src="{{ '/storage/default.jpg' }}" alt="{{ $product->name }}">
                @endif
            </div>
            <div class="text-center justify-center my-1 grow">
                <h3 class="font-bold text-[#1B1A17] dark:text-white">{{ $product->name }}</h3>
                <p class="text-sm line-clamp-1 text-[#00092C] dark:text-[#DDDDDD]">{!! $product->description !!}</p>
                <div class="inline-flex gap-x-2 justify-center items-baseline">
                    @if($product->old_price && $product->old_price != 0)
                    <p class="tracking-wide text-xs line-through text-gray-700 dark:text-gray-200 font-oswald">
                        {{ money($product->old_price, convert: true) }}
                    </p>
                    @endif
                    <p class="font-semibold tracking-wide text-base text-[#E45826] font-oswald">{{ money($product->price, convert: true) }}{{ $product->unit?->getTrans() }}</p>
                </div>
            </div>
            @if ($this->getQuantity($product->id) == 0)
            <button @click="handleIncrement" data-product="{{ $product }}"
                class="subpixel-antialiased tracking-tighter uppercase w-full bg-[#F0A500] text-[#EEEEEE] py-1.5 flex justify-center bottom-0 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                add to cart
            </button>
            @else
            <div class="flex justify-between items-center">
                <button class="py-1.5 px-5 bg-[#E6D5B8] text-white dark:bg-[#04293A] cursor-pointer" @click="handleDecrement({{ $product->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                    </svg>
                </button>
                <p class="text-[#1B1A17] dark:text-white font-bold">{{ $this->getQuantity($product->id) }}</p>
                <button class="py-1.5 px-5 bg-[#F0A500] text-[#EEEEEE] cursor-pointer" @click="handleIncrement" data-product="{{ $product }}">
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

        function showMainButton() {
            Telegram.WebApp.MainButton.setParams({
                text: "{{ Str::upper(__('frontend.cart_view')) }}",
                text_color: "#EEEEEE",
                color: "#F0A500",
                is_active: true,
                is_visible: true,
            }).onClick(() => {
                window.location.href = "/webapp/carts";
            });
        };

        if (parseInt("{{ App\Helpers\CartManager::count() }}") > 0) {
            showMainButton();
        }

        Livewire.on("cart-updated", (count) => {
            count > 0 ? showMainButton() : Telegram.WebApp.MainButton.hide();
        });
    });
</script>
@endpush
