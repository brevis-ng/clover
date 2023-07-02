<div class="tg-bg-color tg-text-color my-2">
    <div class="items-center px-2">
        <h3 class="text-lg font-bold uppercase">{{ __('admin.bill') }}</h3>
    </div>
    <div class="my-3 divide-y divide-dashed divide-[--tg-theme-hint-color]">
        @foreach ($cart as $item)
        <div class="flex justify-between my-1">
            <img src="{{ '/storage/' . $item->product->image }}" class="aspect-video object-contain w-1/6 flex-none" />
            <h3 class="font-normal">{{ $item->product->name }} <span class="text-orange-500 ml-2">x{{ $item->quantity }}</span></h3>
            <div class="font-oswald">{{ config('clover.currency') . $item->amount }}</div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between items-center">
        <h3>{{ __('admin.ship_fee') }}</h3>
        <p>{{ $ship_fee }}</p>
    </div>
    <div class="flex justify-between items-center">
        <h3>{{ __('admin.total') }}</h3>
        <p>{{ config('clover.currency') . round($ship_fee + $this->getSubtotal()) }}</p>
    </div>
</div>

@push('scripts')
@endpush
