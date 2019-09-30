<?php

namespace Jlorente\Laravel\Queue\ApplicationEnd;

use Illuminate\Queue\Connectors\ConnectorInterface;

/**
 * Class QueueServiceProvider
 *
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class ApplicationEndConnector implements ConnectorInterface
{

    const CONNECTION_NAME = 'application-end';

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new ApplicationEndQueue;
    }

}
