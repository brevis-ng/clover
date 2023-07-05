<div class="p-2 tg-bg-color">
    <div class="flex justify-between items-center">
        <h3 class="text-base font-bold uppercase">{{ __('admin.your_order') }}</h3>
        <a href="{{ route('frontend.index') }}" class="tg-link-color">{{ __('admin.edit') }}</a>
    </div>
    <div class="my-3">
        @foreach ($cart as $item)
        <div class="flex justify-between my-2">
            @if(file_exists(public_path('storage/' .$item->product->image )) && $item->product->image != null)
                <img class="aspect-[4/3] object-cover w-1/5 flex-none" src="{{ '/storage/' . $item->product->image }}" alt="{{ $item->product->name }}">
            @else
                <img class="aspect-[4/3] object-cover w-1/5 flex-none" src="{{ '/storage/default.jpg' }}" alt="{{ $item->product->name }}">
            @endif
            <div class="flex flex-col pl-1 grow">
                <h3 class="font-bold">{{ $item->product->name }} <span class="text-orange-500 ml-2">x{{ $item->quantity }}</span></h3>
                <p class="tg-hint-color text-xs line-clamp-1 overflow-hidden text-ellipsis">{{ $item->product->description }}</p>
            </div>
            <div class="font-oswald">{{ config('clover.currency') . $item->amount }}</div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        // Show backbutton
        Telegram.WebApp.BackButton.isVisible = true;
        Telegram.WebApp.onEvent('backButtonClicked', () => {
            window.location.href = "{{ route('frontend.index') }}";
        });

        // Show mainbutton
        Telegram.WebApp.MainButton.setParams({
            text: "{{ Str::upper(__('admin.order')) . ' ' . config('clover.currency') . $this->getSubtotal() }}",
            color: "#525FE1",
            is_active: true,
            is_visible: true,
        }).onClick(() => {
            Livewire.emit('tg:initData', Telegram.WebApp.initData);
            window.location.href = "{{ route('frontend.orderplaced') }}";
        });
    });
</script>
@endpush
