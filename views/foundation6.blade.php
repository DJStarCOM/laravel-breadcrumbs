@if (count($breadcrumbs))

    <nav aria-label="You are here:" role="navigation">
        <ul class="breadcrumbs">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($loop->first)
                    @if (config('breadcrumbs.options.foundation6.showFirstItem', config('breadcrumbs.options.default.showFirstItem', true)) !== true)
                        @continue;
                    @endif
                @endif

                @if ($loop->last)
                    @if (config('breadcrumbs.options.foundation6.showLastItem', config('breadcrumbs.options.default.showLastItem', true)) !== true)
                        @continue;
                    @endif

                    <li class="current"><span class="show-for-sr">Current:</span> {{ $breadcrumb->title }}</li>
                @elseif ($breadcrumb->url)
                    <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                @else
                    <li class="disabled">{{ $breadcrumb->title }}</li>
                @endif

            @endforeach
        </ul>
    </nav>

@endif
