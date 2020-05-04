<?php

use Illuminate\Support\Facades\Request;

$json = [
    '@context'        => 'http://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [],
];

foreach ($breadcrumbs as $i => $breadcrumb) {
    if ($breadcrumbs->keys()->first() === $i) {
        if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.json-ld.showFirstItem', config('breadcrumbs.options.default.showFirstItem', true)) !== true) {
            continue;
        }
    }

    if ($breadcrumbs->keys()->last() === $i) {
        if ($breadcrumbs->count() > 1 && config('breadcrumbs.options.json-ld.showLastItem', config('breadcrumbs.options.default.showLastItem', true)) !== true) {
            continue;
        }
    }

    $json['itemListElement'][] = [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'item'     => [
            '@id'   => $breadcrumb->url ?: Request::fullUrl(),
            'name'  => $breadcrumb->title,
            'image' => $breadcrumb->image ?? null,
        ],
    ];
}
?>
<script type="application/ld+json"><?= json_encode($json) ?></script>
