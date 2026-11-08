<?php

namespace DJStarCOM\Breadcrumbs\Tests;

use DJStarCOM\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Support\ServiceProvider;

/**
 * `php artisan vendor:publish --tag=breadcrumbs-config` is the documented way to
 * get a config file, and nothing else in this suite notices if the publish group
 * stops being registered: every test reads config through mergeConfigFrom(), which
 * keeps working. The command would simply report nothing to publish.
 */
class ServiceProviderPublishingTest extends TestCase
{
    public function testTheConfigFileIsRegisteredUnderTheDocumentedTag()
    {
        $paths = ServiceProvider::pathsToPublish(BreadcrumbsServiceProvider::class, 'breadcrumbs-config');

        $this->assertNotEmpty(
            $paths,
            'Nothing is registered under the breadcrumbs-config tag, so vendor:publish --tag=breadcrumbs-config publishes nothing.'
        );

        $source = (string) array_key_first($paths);

        $this->assertFileExists($source, "The publish source does not exist: {$source}");
        $this->assertSame(
            realpath(__DIR__ . '/../config/breadcrumbs.php'),
            realpath($source),
            'The publish group points at a different file than the package config.'
        );
        $this->assertSame(config_path('breadcrumbs.php'), $paths[$source]);
    }
}
