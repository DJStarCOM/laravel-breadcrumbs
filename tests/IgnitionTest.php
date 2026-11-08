<?php

namespace DJStarCOM\Breadcrumbs\Tests;

use Breadcrumbs;
use Config;
use DJStarCOM\Breadcrumbs\Exceptions\DuplicateBreadcrumbException;
use DJStarCOM\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
use DJStarCOM\Breadcrumbs\Exceptions\UnnamedRouteException;
use DJStarCOM\Breadcrumbs\Exceptions\ViewNotSetException;
use ErrorException;
use Spatie\ErrorSolutions\Contracts\ProvidesSolution;
use Spatie\ErrorSolutions\Contracts\Solution;
use Route;

class IgnitionTest extends TestCase
{
    private function assertSolutionMatchesSnapshot(ProvidesSolution $exception)
    {
        $solution = $exception->getSolution();

        // Using snapshots to avoid duplicating all the solutions here
        // (I tested them in the browser already, and the code coverage checker ensures I caught all combinations)
        $this->assertMatchesSnapshot($solution->getSolutionTitle());
        $this->assertMatchesSnapshot($solution->getSolutionDescription());
        $this->assertMatchesSnapshot($solution->getDocumentationLinks());
    }

    public static function dataOneOrManyConfigFiles()
    {
        return [
            'Single config file' => [['routes/breadcrumbs.php']],
            'Multiple config files' => [['breadcrumbs/file1.php', 'breadcrumbs/file2.php']],
        ];
    }

    /** @dataProvider dataOneOrManyConfigFiles */
    public function testDuplicateBreadcrumbSolution(array $files)
    {
        Config::set('breadcrumbs.files', $files);

        Breadcrumbs::for('duplicate', function () {
        });

        try {
            Breadcrumbs::for('duplicate', function () {
            });
            $this->fail('No exception thrown');
        } catch (DuplicateBreadcrumbException $e) {
            $this->assertSolutionMatchesSnapshot($e);
        }
    }

    /** @dataProvider dataOneOrManyConfigFiles */
    public function testInvalidBreadcrumbSolution(array $files)
    {
        Config::set('breadcrumbs.files', $files);

        try {
            Breadcrumbs::render('invalid');
            $this->fail('No exception thrown');
        } catch (InvalidBreadcrumbException $e) {
            $this->assertSolutionMatchesSnapshot($e);
        }
    }

    /** @dataProvider dataOneOrManyConfigFiles */
    public function testMissingRouteBoundBreadcrumbSolution(array $files)
    {
        Config::set('breadcrumbs.files', $files);

        Route::name('home')->get('/', function () {
            return Breadcrumbs::render();
        });

        try {
            $this->get('/');
            $this->fail('No exception thrown');
        } catch (InvalidBreadcrumbException $e) {
            $this->assertSolutionMatchesSnapshot($e);
        }
    }

    /**
     * The default config ships absolute paths (base_path('routes/breadcrumbs.php')),
     * so in a real application every file name reaching the solution text is absolute
     * and has to be made relative before it is shown. The snapshot cases above cannot
     * see this: they configure relative paths, which leaves nothing to strip.
     */
    public function testDuplicateSolutionStripsTheBasePathFromASingleFile()
    {
        Config::set('breadcrumbs.files', [base_path('routes/breadcrumbs.php')]);

        $description = $this->duplicateSolution()->getSolutionDescription();

        $this->assertStringContainsString('`routes/breadcrumbs.php`', $description);
        $this->assertStringNotContainsString(base_path(), $description);
    }

    public function testDuplicateSolutionStripsTheBasePathFromEveryFile()
    {
        Config::set('breadcrumbs.files', [
            base_path('breadcrumbs/file1.php'),
            base_path('breadcrumbs/file2.php'),
        ]);

        $description = $this->duplicateSolution()->getSolutionDescription();

        $this->assertStringContainsString('`breadcrumbs/file1.php`', $description);
        $this->assertStringContainsString('`breadcrumbs/file2.php`', $description);
        $this->assertStringNotContainsString(base_path(), $description);
    }

    public function testInvalidBreadcrumbSolutionStripsTheBasePath()
    {
        Config::set('breadcrumbs.files', [base_path('routes/breadcrumbs.php')]);

        try {
            Breadcrumbs::render('invalid');
            $this->fail('No exception thrown');
        } catch (InvalidBreadcrumbException $e) {
            $title = $e->getSolution()->getSolutionTitle();

            $this->assertSame('Add this to routes/breadcrumbs.php', $title);
            $this->assertStringNotContainsString(base_path(), $title);
        }
    }

    private function duplicateSolution(): Solution
    {
        Breadcrumbs::for('duplicate-absolute', function () {
        });

        try {
            Breadcrumbs::for('duplicate-absolute', function () {
            });
            $this->fail('No exception thrown');
        } catch (DuplicateBreadcrumbException $e) {
            return $e->getSolution();
        }
    }

    public function testViewNotSetSolution()
    {
        Config::set('breadcrumbs.view', '');

        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', url('/'));
        });

        try {
            Breadcrumbs::render('home');
            $this->fail('No exception thrown');
        } catch (ViewNotSetException $e) {
            $this->assertSolutionMatchesSnapshot($e);
        }
    }

    public function testUnnamedClosureRouteSolution()
    {
        Route::get('/blog', function () {
            return Breadcrumbs::render();
        });

        try {
            $this->get('/blog');
            $this->fail('No exception thrown');
        } catch (UnnamedRouteException $e) {
            $this->assertSolutionMatchesSnapshot($e);
        }
    }

    public function testUnnamedControllerRouteSolution()
    {
        Route::get('/posts/{post}', 'App\Http\Controllers\PostController@edit');

        try {
            $this->get('/posts/1');
            $this->fail('No exception thrown');
        } catch (UnnamedRouteException $e) {
            $this->assertSolutionMatchesSnapshot($e);
        }
    }

    public function testUnnamedViewRouteSolution()
    {
        Route::view('/blog', 'page');

        try {
            $this->get('/blog');
            $this->fail('No exception thrown');
        } catch (ErrorException $e) {
            $this->assertSolutionMatchesSnapshot($e->getPrevious());
        }
    }
}
