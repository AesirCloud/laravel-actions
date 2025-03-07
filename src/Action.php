<?php

namespace AesirCloud\LaravelActions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class Action implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Run the action synchronously (no queue).
     *
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function run(mixed ...$arguments): mixed
    {
        $action = new static(...$arguments);

        return call_user_func_array([$action, 'handle'], $arguments);
    }

    /**
     * Dispatch the action as a queued job.
     *
     * @param  mixed  ...$arguments
     * @return PendingDispatch
     */
    public static function dispatch(mixed ...$arguments): PendingDispatch
    {
        // Similarly, pass arguments into constructor
        return (new static(...$arguments))->queue();
    }

    /**
     * Actually dispatch the action (job) to the queue.
     *
     * @return PendingDispatch
     */
    public function queue(): PendingDispatch
    {
        return dispatch($this);
    }

    /**
     * Allow the class to act as an invokable controller.
     */
    public function __invoke(Request $request): mixed
    {
        return $this->asController($request);
    }

    /**
     * If you need request data, override asController to gather/validate that data,
     * then call handle(...) with specific params.
     */
    public function asController(Request $request): mixed
    {
        // e.g., $data = $request->validate(...);
        // return $this->handle($data);

        return $this->handle();
    }
}
