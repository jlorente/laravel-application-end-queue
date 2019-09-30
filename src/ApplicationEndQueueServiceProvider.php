<?php

namespace Jlorente\Laravel\Queue\ApplicationEnd;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class ApplicationEndQueueServiceProvider
 *
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class ApplicationEndQueueServiceProvider extends ServiceProvider
{

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->registerApplicationEndConnector($this->app['queue']);
        $this->registerApplicationEndEvent();
    }

    /**
     * Registers the application end event.
     */
    protected function registerApplicationEndEvent()
    {
        $this->app->terminating(function() {
            $this->runWorker();
        });
    }

    /**
     * Registers the request end connector.
     */
    protected function registerApplicationEndConnector(QueueManager $manager)
    {
        $manager->addConnector(ApplicationEndConnector::CONNECTION_NAME, function() {
            return new ApplicationEndConnector;
        });
    }

    /**
     * Runs the application-end queue until it is empty.
     */
    protected function runWorker()
    {
        $this->createWorker()->daemon();
    }

    /**
     * Creates the request end worker.
     * 
     * @return ApplicationEndWorker
     */
    protected function createWorker(): ApplicationEndWorker
    {
        return new ApplicationEndWorker(
                $this->app['queue'], $this->app['events'], $this->app[ExceptionHandler::class]
        );
    }

}
