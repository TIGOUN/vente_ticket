@php
$targets = is_array($wireTarget) ? $wireTarget : explode(',', $wireTarget);
@endphp

<div
    wire:loading.delay.longest
    @foreach($targets as $target)
    wire:target="{{ trim($target) }}"
    @endforeach
    class="top-0 position-fixed d-flex align-items-center justify-content-center bg-dark bg-opacity-50 w-100 h-100 start-0"
    style="z-index: 1050; pointer-events: all;">
    <div class="text-white text-center">
        <div class="mb-4 spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="h5 fw-semibold">Chargement...</p>
    </div>
</div>
