@if (count($breadcrumbs))

    <nav>
        <div class="nav-wrapper">
            <div class="col s12">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($loop->first)
                        @if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.materialize.showFirstItem', config('breadcrumbs.options.default.showFirstItem', true)) !== true)
                            @continue;
                        @endif
                    @endif

                    @if ($loop->last)
                        @if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.materialize.showLastItem', config('breadcrumbs.options.default.showLastItem', true)) !== true)
                            @continue;
                        @endif
                    @endif

                    @if ($breadcrumb->url && !$loop->last)
                        <a href="{{ $breadcrumb->url }}" class="breadcrumb">{{ $breadcrumb->title }}</a>
                    @else
                        <span class="breadcrumb">{{ $breadcrumb->title }}</span>
                    @endif

                @endforeach
            </div>
        </div>
    </nav>

@endif
