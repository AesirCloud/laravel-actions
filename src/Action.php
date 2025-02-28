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
     * Primary logic for your action.
     *
     * If your child class needs typed parameters, just override it with them:
     *   public function handle(string $orderId, array $items): mixed { ... }
     *
     * Or keep it variadic:
     *   public function handle(...$params): mixed { ... }
     *
     * @param  mixed  ...$params
     * @return mixed
     */
    abstract public function handle(...$params): mixed;

    /**
     * Run the action synchronously (no queue).
     *
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function run(mixed ...$arguments): mixed
    {
        // Instantiate with constructor arguments
        $action = new static(...$arguments);

        // Pass those same arguments to handle(...) if you prefer:
        return $action->handle(...$arguments);

        // Or if your child Action uses constructor injection only:
        // return $action->handle();
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
        return new static(...$arguments)->queue();
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
