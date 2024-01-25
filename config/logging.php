<?php

use App\Logging\CustomizeFormatter;
use App\Logging\Processor\HTTPMethodProcessor;
use App\Logging\Processor\ParamsProcessor;
use App\Logging\Processor\RouteActionProcessor;
use App\Logging\Processor\UserIdProcessor;
use Hedii\LaravelGelfLogger\GelfLoggerFactory;
use Hedii\LaravelGelfLogger\Processors\NullStringProcessor;
use Hedii\LaravelGelfLogger\Processors\RenameIdFieldProcessor;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['custom-daily', 'gelf'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'custom-daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'tap' => [CustomizeFormatter::class],
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        'gelf' => [
            'driver' => 'custom',
            'via' => GelfLoggerFactory::class,

            // This optional option determines the processors that should be
            // pushed to the handler. This option is useful to modify a field
            // in the log context (see NullStringProcessor), or to add extra
            // data. Each processor must be a callable or an object with an
            // __invoke method: see monolog documentation about processors.
            // Default is an empty array.
            'processors' => [
                NullStringProcessor::class,
                RenameIdFieldProcessor::class,
                RouteActionProcessor::class,
                UserIdProcessor::class,
                ParamsProcessor::class,
                HTTPMethodProcessor::class,
            ],
            // This optional option determines the minimum "level" a message
            // must be in order to be logged by the channel. Default is 'debug'
            'level' => 'debug',

            // This optional option determines the channel name sent with the
            // message in the 'facility' field. Default is equal to app.env
            // configuration value
            'name' => 'my-custom-name',

            // This optional option determines the system name sent with the
            // message in the 'source' field. When forgotten or set to null,
            // the current hostname is used.
            'system_name' => null,

            // This optional option determines if you want the UDP, TCP or HTTP
            // transport for the gelf log messages. Default is UDP
            'transport' => env('GELF_TRANSPORT', 'upd'),

            // This optional option determines the host that will receive the
            // gelf log messages. Default is 127.0.0.1
            'host' => env('GELF_HOST', '127.0.0.1'),

            // This optional option determines the port on which the gelf
            // receiver host is listening. Default is 12201
            'port' => env('GELF_PORT', 12201),

            // This optional option determines the chunk size used when
            // transferring message via UDP transport. Default is 1420.
            'chunk_size' => 1420,

            // This optional option determines the path used for the HTTP
            // transport. When forgotten or set to null, default path '/gelf'
            // is used.
            'path' => null,

            // This optional option enable or disable ssl on TCP or HTTP
            // transports. Default is false.
            'ssl' => false,

            // If ssl is enabled, the following configuration is used.
            'ssl_options' => [
                // Enable or disable the peer certificate check. Default is
                // true.
                'verify_peer' => true,

                // Path to a custom CA file (eg: "/path/to/ca.pem"). Default
                // is null.
                'ca_file' => null,

                // List of ciphers the SSL layer may use, formatted as
                // specified in ciphers(1). Default is null.
                'ciphers' => null,

                // Whether self-signed certificates are allowed. Default is
                // false.
                'allow_self_signed' => false,
            ],

            // This optional option determines the maximum length per message
            // field. When forgotten or set to null, the default value of
            // \Monolog\Formatter\GelfMessageFormatter::DEFAULT_MAX_LENGTH is
            // used (currently this value is 32766)
            'max_length' => null,

            // This optional option determines the prefix for 'context' fields
            // from the Monolog record. Default is null (no context prefix)
            'context_prefix' => null,

            // This optional option determines the prefix for 'extra' fields
            // from the Monolog record. Default is null (no extra prefix)
            'extra_prefix' => null,

            // This optional option determines whether errors thrown during
            // logging should be ignored or not. Default is true.
            'ignore_error' => true,
        ],
    ],
];
