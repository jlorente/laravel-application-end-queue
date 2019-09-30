Laravel Application End Queue
=============================

A Laravel queue connector to process the enqueued jobs at the end of the application.

This connector is very similar to the "sync" connector with the difference that 
jobs are executed at the end of the application instead of instantly. 

It is useful for example when sending real time notifications to third party 
webhooks inside database transactions. With the "sync" connector, if the third 
party application webhook queries your API, as the transaction wouldn't have 
end, the third party application won't know the real state of the model. With 
this connector, the notification will be sent at the end of the application 
when all commits have been executed.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

With Composer installed, you can then install the extension using the following commands:

```bash
$ php composer.phar require jlorente/laravel-application-end-queue
```

or add 

```json
...
    "require": {
        "jlorente/laravel-application-end-queue": "*"
    }
```

to the ```require``` section of your `composer.json` file.

## Configuration

Register the ServiceProvider in your config/app.php service provider list.

config/app.php
```php
return [
    //other stuff
    'providers' => [
        //other stuff
        Jlorente\Laravel\Queue\ApplicationEndQueueServiceProvider,
    ];
];
```

Then add the driver to the application config queue file.

config\queue.php
```php
return [
    //other stuff
    'connections' => [
        //other stuff
        'application-end' => [
            'driver' => 'application-end',
        ],
    ],
];
```

## Usage

See the [Laravel documentation](https://laravel.com/docs/master/queues) to learn 
how to use jobs and queues.

Remember that [notifications](https://laravel.com/docs/master/notifications) can 
also be enqueued.

## License 

Copyright &copy; 2019 José Lorente Martín <jose.lorente.martin@gmail.com>.

Licensed under the BSD 3-Clause License. See LICENSE.txt for details.
