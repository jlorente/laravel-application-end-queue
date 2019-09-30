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

use Illuminate\Queue\Connectors\ConnectorInterface;

/**
 * Class ApplicationEndConnector
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
