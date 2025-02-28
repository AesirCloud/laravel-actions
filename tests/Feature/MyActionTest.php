<?php

use Illuminate\Support\Facades\Bus;
use Tests\Fixtures\MyAction;
use function Pest\Laravel\post;

it('can run MyAction synchronously', function () {
    $result = MyAction::run();
    expect($result)->toBe('Hello from handle!');
});

it('can dispatch MyAction as a queued job', function () {
    Bus::fake();

    MyAction::dispatch();

    Bus::assertDispatched(MyAction::class);
});

it('can call MyAction via HTTP (as a controller)', function () {
    $response = post('test-action');

    $response
        ->assertStatus(200)
        ->assertSee('Hello from asController!');
});
