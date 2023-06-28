@extends('layouts.base')

@section('content')

<div class="min-h-screen container tg-bg-color tg-text-color">
    <div class="mt-1 mb-2">
        <livewire:show-categories />
    </div>
    <livewire:show-products />
</div>

@endsection

@push('scripts')
<script type="application/javascript">
    console.log("{{ $count }}")
</script>
@endpush
