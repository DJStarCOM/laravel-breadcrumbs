<?php

namespace App\Http\Controllers;

use Breadcrumbs;
use DJStarCOM\Breadcrumbs\Tests\Models\Post;

class PostController
{
    public function edit(Post $post)
    {
        return Breadcrumbs::render();
    }
}
