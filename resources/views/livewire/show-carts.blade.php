@extends('layouts.base')

@section('content')

<div class="tg-bg-color tg-text-color mt-2">
    <div class="flex justify-between items-center px-2">
        <h3 class="text-lg font-bold uppercase">{{ __('admin.your_order') }}</h3>
        <a href="{{ route('frontend.index') }}" class="tg-link-color">{{ __('admin.edit') }}</a>
    </div>
    <div class="my-3">
        @foreach ($carts as $item)
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

@endsection

@push('scripts')
<script type="application/javascript">
    // Show BackButton
    const backBtn = Telegram.WebApp.BackButton;
    backBtn.isVisible = true;
    backBtn.onClick(() => {
        window.location.href = "{{ route('frontend.index') }}";
    });
    // Show MainButton
    const mainButton = Telegram.WebApp.MainButton;
    if (mainButton.isVisible) {
        mainButton.hide();
    } else {
        mainButton.setParams({
            text: "{{ Str::upper(__('admin.order')) }}",
            is_active: true,
            is_visible: true,
        });
    }
</script>
@endpush
