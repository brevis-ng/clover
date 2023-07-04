<div class="tg-bg-color tg-text-color mt-2">
    <div class="flex justify-between items-center px-2">
        <h3 class="text-base font-bold uppercase">{{ __('admin.your_order') }}</h3>
        <a href="{{ route('frontend.index') }}" class="tg-link-color">{{ __('admin.edit') }}</a>
    </div>
    <div class="my-3">
        @foreach ($cart as $item)
        <div class="flex justify-between my-2">
            <img src="{{ '/storage/' . $item->product->image }}" class="aspect-video object-contain w-1/5 flex-none" />
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
            window.location.href = "{{ route('frontend.orderplaced') }}";
        });
    });
</script>
@endpush
