<?php

namespace DJStarCOM\Breadcrumbs\Tests;

use Breadcrumbs;
use DJStarCOM\Breadcrumbs\BreadcrumbsManager;
use DJStarCOM\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Support\DeferrableProvider;
use LogicException;

class DeferredLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        // If the service provider is loaded before the test starts, this file
        // will throw an exception.
        $app->config->set('breadcrumbs.files', [__DIR__ . '/routes/should-not-be-loaded.php']);
    }

    protected function resolveApplicationConsoleKernel($app)
    {
        // Disable the console kernel because it calls loadDeferredProviders()
        // which defeats the purpose of this test.
        //
        // Раньше здесь подменялся Orchestra\Testbench\Console\Kernel наследником
        // с пустым bootstrap(). С testbench 9 этот класс объявлен final, и
        // наследование валит весь файл фатальной ошибкой на этапе автозагрузки.
        // Контракт Illuminate\Contracts\Console\Kernel реализуется заглушкой:
        // в этом тесте ядро нужно только для того, чтобы ничего не делать.
        $app->singleton(Kernel::class, DisabledConsoleKernel::class);
    }

    /**
     * Laravel decides whether a provider is deferred by looking for the DeferrableProvider
     * interface (Illuminate\Foundation\ProviderRepository::compileManifest). The legacy
     * isDeferred() method this package used to rely on has been ignored since Laravel 5.8,
     * so without this check testDeferredLoading() below passes either way - the console
     * kernel it disables is the only thing that would have loaded the provider anyway.
     */
    public function testTheServiceProviderIsMarkedAsDeferred()
    {
        $provider = new BreadcrumbsServiceProvider($this->app);

        $this->assertInstanceOf(DeferrableProvider::class, $provider);
        $this->assertSame([BreadcrumbsManager::class], $provider->provides());
    }

    public function testDeferredLoading()
    {
        $this->expectException(LogicException::class);

        // This triggers the service provider boot, which loads the breadcrumbs,
        // which throws an exception, which is caught by PHPUnit.
        Breadcrumbs::clearCurrentRoute();
    }
}

class DisabledConsoleKernel implements Kernel
{
    public function bootstrap()
    {
    }

    public function handle($input, $output = null)
    {
        return 0;
    }

    public function call($command, array $parameters = [], $outputBuffer = null)
    {
        return 0;
    }

    public function queue($command, array $parameters = [])
    {
    }

    public function all()
    {
        return [];
    }

    public function output()
    {
        return '';
    }

    public function terminate($input, $status)
    {
    }

    // Laravel 12 Foundation\Configuration\ApplicationBuilder зовёт эти методы
    // на разрешённом ядре (afterResolving), минуя контракт Kernel.
    public function addCommands(array $commands)
    {
    }

    public function addCommandPaths(array $paths)
    {
    }

    public function addCommandRoutePaths(array $paths)
    {
    }
}
