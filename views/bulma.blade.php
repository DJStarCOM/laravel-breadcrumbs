@if (count($breadcrumbs))

    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($loop->first)
                    @if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.bulma.showFirstItem', config('breadcrumbs.options.default.showFirstItem', true)) !== true)
                        @continue;
                    @endif
                @endif

                @if ($loop->last)
                    @if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.bulma.showLastItem', config('breadcrumbs.options.default.showLastItem', true)) !== true)
                        @continue;
                    @endif

                    @if ($breadcrumb->url)
                        <li class="is-active"><a href="{{ $breadcrumb->url }}" aria-current="page">{{ $breadcrumb->title }}</a></li>
                    @else
                        <li class="is-active"><a aria-current="page">{{ $breadcrumb->title }}</a></li>
                    @endif
                @else
                    @if ($breadcrumb->url)
                        <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                    @else
                        <li class="is-active"><a>{{ $breadcrumb->title }}</a></li>
                    @endif
                @endif

            @endforeach
        </ul>
    </nav>

@endif
