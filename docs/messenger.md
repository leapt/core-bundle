# Messenger Worker Status

!!! info

    This feature was introduced in 6.1, and requires the `symfony/messenger` package. It is only registered
    if that package is installed in your application.

The bundle can keep track of whether a `messenger:consume` worker is currently running for a given transport,
regardless of the transport type. This is useful, for example, to display a worker status indicator in an
admin dashboard.

## How it works

A `Leapt\CoreBundle\Listener\MessengerWorkerHeartbeatListener` listens to the worker lifecycle events
(`WorkerStartedEvent`, `WorkerRunningEvent`, `WorkerStoppedEvent`) and stores a short-lived heartbeat (30 seconds)
per transport in a dedicated cache pool (`leapt_core.messenger.cache`, based on `cache.app`). As long as a worker
keeps running, the heartbeat gets refreshed; it naturally expires shortly after the worker stops or dies
unexpectedly.

## Usage

Inject `Leapt\CoreBundle\Messenger\MessengerHelper` into your service or controller:

```php
use Leapt\CoreBundle\Messenger\MessengerHelper;

class DashboardController
{
    public function __construct(private MessengerHelper $messengerHelper) {}

    public function index(): Response
    {
        $isRunning = $this->messengerHelper->isWorkerRunning('async');

        // ...
    }
}
```

`MessengerHelper` also provides a `stopWorkers()` method, which is a shortcut for running the
`messenger:stop-workers` command programmatically (e.g. from an admin action).

## Twig usage

### `is_messenger_worker_running` function

!!! example "Usage"

    ```twig
    {% if is_messenger_worker_running('async') %}
        Worker is running
    {% else %}
        Worker is not running
    {% endif %}
    ```
