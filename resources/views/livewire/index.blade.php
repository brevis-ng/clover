@extends('layouts.base')

@section('content')

<div class="tg-bg-color tg-text-color">
    <div class="mt-1 mb-2">
        <livewire:show-categories />
    </div>
    <livewire:show-products />
</div>

@endsection

@push('scripts')
<script type="application/javascript">
    Livewire.on('show-carts', (msg) => {
        Telegram.WebApp.MainButton.setParams({
            text: msg,
            is_active: true,
            is_visible: true,
        }).onClick(() => {
            window.location.href = "{{ route('frontend.carts') }}";
        });
    });
    Livewire.on('hide-main-button', () => {
        Telegram.WebApp.MainButton.hide();
    });
</script>
@endpush
