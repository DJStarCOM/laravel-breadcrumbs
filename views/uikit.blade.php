@if (count($breadcrumbs))
    <ul class="uk-breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->first)
                @if (config('breadcrumbs.options.uikit.showFirstItem', config('breadcrumbs.options.default.showFirstItem', true)) !== true)
                    @continue;
                @endif
            @endif

            @if ($loop->last)
                @if (config('breadcrumbs.options.uikit.showLastItem', config('breadcrumbs.options.default.showLastItem', true)) !== true)
                    @continue;
                @endif

                <li><span>{{ $breadcrumb->title }}</span></li>
            @elseif ($breadcrumb->url)
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="uk-disabled"><a>{{ $breadcrumb->title }}</a></li>
            @endif
        @endforeach
    </ul>
@endif
