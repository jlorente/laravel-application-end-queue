<?php

/**
 * Part of the Laravel Application End Queue package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Laravel Application End Queue
 * @version    1.0.2
 * @author     Jose Lorente
 * @license    BSD License (3-clause)
 * @copyright  (c) 2018, Jose Lorente
 */

namespace Jlorente\Laravel\Queue\ApplicationEnd;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\QueueManager;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class ApplicationEndWorker
 *
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class ApplicationEndWorker
{

    /**
     * The queue manager instance.
     *
     * @var \Illuminate\Queue\QueueManager
     */
    protected $manager;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * The exception handler instance.
     *
     * @var \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected $exceptions;

    /**
     * Create a new queue worker.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @param  \Illuminate\Contracts\Debug\ExceptionHandler  $exceptions
     * @return void
     */
    public function __construct(QueueManager $manager,
            Dispatcher $events,
            ExceptionHandler $exceptions)
    {
        $this->events = $events;
        $this->manager = $manager;
        $this->exceptions = $exceptions;
    }

    /**
     * Runs the worker.
     */
    public function daemon()
    {
        $connection = $this->manager->connection(ApplicationEndConnector::CONNECTION_NAME);

        while (!is_null($job = $connection->pop())) {
            $this->process($job);
        }
    }

    /**
     * Process a job.
     * 
     * @param \Illuminate\Contracts\Queue\Job $job
     * @return void
     */
    protected function process($job)
    {
        try {
            $this->raiseBeforeJobEvent($job);

            $job->fire();

            $this->raiseAfterJobEvent($job);
        } catch (Exception $e) {
            $this->handleException($job, $e);
        } catch (Throwable $e) {
            $this->handleException($job, new FatalThrowableError($e));
        }
    }

    /**
     * Raise the before queue job event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @return void
     */
    protected function raiseBeforeJobEvent($job)
    {
        $this->events->dispatch(new JobProcessing(
                        ApplicationEndConnector::CONNECTION_NAME, $job
        ));
    }

    /**
     * Raise the after queue job event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @return void
     */
    protected function raiseAfterJobEvent($job)
    {
        $this->events->dispatch(new JobProcessed(
                        ApplicationEndConnector::CONNECTION_NAME, $job
        ));
    }

    /**
     * Handle an exception that occurred while processing a job.
     *
     * @param  \Illuminate\Queue\Jobs\Job  $queueJob
     * @param  \Exception  $e
     * @return void
     *
     * @throws \Exception
     */
    protected function handleException($queueJob, $e)
    {
        $this->raiseExceptionOccurredJobEvent($queueJob, $e);

        $queueJob->fail($e);

        throw $e;
    }

    /**
     * Raise the exception occurred queue job event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  \Exception  $e
     * @return void
     */
    protected function raiseExceptionOccurredJobEvent(Job $job, $e)
    {
        $this->events->dispatch(new JobExceptionOccurred(
                        ApplicationEndConnector::CONNECTION_NAME, $job, $e
        ));
    }

}
