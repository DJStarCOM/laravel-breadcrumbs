@if (count($breadcrumbs))

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->first)
                @if (config('breadcrumbs.options.bootstrap4.showFirstItem', config('breadcrumbs.options.default.showFirstItem', true)) !== true)
                    @continue;
                @endif
            @endif

            @if ($loop->last)
                @if (config('breadcrumbs.options.bootstrap4.showLastItem', config('breadcrumbs.options.default.showLastItem', true)) !== true)
                    @continue;
                @endif
            @endif

            @if ($breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
            @endif

        @endforeach
    </ol>

@endif
