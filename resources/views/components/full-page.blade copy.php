@php
$wire_target ??='';
$is_livewire_loading ??=true;
@endphp

<button @if(!$is_livewire_loading) id="fullPageLoaderNotLivewire" @endif
    class="btn btn-primary full_page_loader {{ $is_livewire_loading ? 'flex d-none' : 'd-flex'}}" type="button" disabled
    @if($is_livewire_loading) wire:loading.flex wire:loading.class.remove="d-none" wire:target="{{$wire_target}}"
    @endif>
    <span class="me-1 spinner-border spinner-border-sm fs-1" role="status" aria-hidden="true"></span>
    Chargement...
</button>

<style>
    .full_page_loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(74, 88, 243, 0.108);
        backdrop-filter: blur(5px);
        /* Fond semi-transparent */
        align-items: center;
        justify-content: center;
        z-index: 100000;
        /* Assure que le loader est au-dessus de tout le contenu */
    }
</style>