@extends('layouts.base')

@section('content')

<div class="container tg-bg-color tg-text-color">
    <div class="mt-1 mb-2">
        <livewire:show-categories />
    </div>
    <livewire:show-products />
</div>

@endsection

@push('scripts')
<script type="application/javascript">
    Livewire.on('showMainMenu', (msg) => {
        console.log('LiveWire:Updated' + msg);

        Telegram.WebApp.MainButton.setParams({
            text: msg,
            is_active: true,
            is_visible: true,
        });
    });
</script>
@endpush
