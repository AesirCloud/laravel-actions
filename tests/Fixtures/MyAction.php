<?php

namespace Tests\Fixtures;

use AesirCloud\LaravelActions\Action;
use Illuminate\Http\Request;

class MyAction extends Action
{
    public function handle(...$params): string
    {
        return 'Hello from handle!';
    }

    public function asController(Request $request): string
    {
        return 'Hello from asController!';
    }
}
