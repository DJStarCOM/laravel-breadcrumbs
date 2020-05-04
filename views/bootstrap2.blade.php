@if (count($breadcrumbs))

    <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->first)
                @if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.bootstrap2.showFirstItem', config('breadcrumbs.options.default.showFirstItem', true)) !== true)
                    @continue;
                @endif
            @endif

            @if ($loop->last)
                @if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.bootstrap2.showLastItem', config('breadcrumbs.options.default.showLastItem', true)) !== true)
                    @continue;
                @endif

                <li class="active">
                    {{ $breadcrumb->title }}
                </li>

            @elseif ($breadcrumb->url)

                <li>
                    <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                    <span class="divider">/</span>
                </li>

            @else

                {{-- Using .active to give it the right colour (grey by default) --}}
                <li class="active">
                    {{ $breadcrumb->title }}
                    <span class="divider">/</span>
                </li>

            @endif
        @endforeach
    </ul>

@endif
