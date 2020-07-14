<div
    class="box {{ isset($tabs) ?  ($outline ?? config('boilerplate.theme.card.outline', false)) ? 'box-outline-tabs' : 'box-tabs' : ''}} {{ ($outline ?? config('boilerplate.theme.card.outline', false)) ? 'box-outline' : '' }} box-{{ $color ?? config('boilerplate.theme.card.default_color', 'info') }}">
    @if($title ?? $header ?? false)
    <div
        class="box-header {{ isset($tabs) ? ($outline ?? config('boilerplate.theme.card.outline', false)) ? 'p-0' : 'p-0 pt-1' : '' }} border-bottom-0">
        @isset($header)
        {{ $header }}
        @else
        <h3 class="box-title">{{ $title }}</h3>
        @isset($tools)
        <div class="box-tools">
            {{ $tools }}
        </div>
        @endisset
        @endisset
    </div>
    @endif
    <div
        class="box-body {{ $title ?? false ? ($outline ?? config('boilerplate.theme.card.outline', false)) ? 'pt-0' : '' : '' }}">
        {{ $slot }}
    </div>
    @isset($footer)
    <div class="box-footer">
        {{ $footer }}
    </div>
    @endif
</div>
