<?php

use App\Lib\ConfigClosures;

return [
    /**
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => true,

    /**
     * Configure the cache adapters.
     *
     * These settings will be merged with the keys from app.php. That
     * means we don't have to redefine the [className => Memcached]
     * key, only where to connect.
     *
     * In a development Vagrant box, we want to connect to the simple,
     * unprotected daemon running on localhost, and we want caches to
     * expire quickly.
     */
    'Cache' => [
        'default' => [
            'compress' => false,
            'duration' => 120,
            'servers' => 'localhost',
            'username' => null,
            'password' => null,
        ],
        '_cake_core_' => [
            'duration' => 120,
            'servers' => 'localhost',
            'username' => null,
            'password' => null,
        ],
        '_cake_model_' => [
            'duration' => 120,
            'servers' => 'localhost',
            'username' => null,
            'password' => null,
        ],
    ],

    /**
     * Email configuration.
     *
     * The vagrant VM runs Mailcatcher internally, making email
     * available in a web interface on port 1080.
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            'host' => 'localhost',
            'port' => 1025,
            'timeout' => 30,
            'username' => null,
            'password' => null,
            'client' => null,
            'tls' => null,
        ],
    ],

    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => 'you@localhost',
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
        ],
    ],

    /**
     * Connection information used by the ORM to connect
     * to your application's datastores.
     * Drivers include Mysql Postgres Sqlite Sqlserver
     * See vendor\cakephp\cakephp\src\Database\Driver for complete list
     */
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            //'port' => 'nonstandard_port_number',
            'username' => 'vagrant',
            'password' => 'vagrant',
            'database' => 'vagrant',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,

            /*
            * Set identifier quoting to true if you are using reserved words or
            * special characters in your table or column names. Enabling this
            * setting will result in queries built using the Query Builder having
            * identifiers quoted when creating SQL. It should be noted that this
            * decreases performance because each query needs to be traversed and
            * manipulated before being executed.
            */
            'quoteIdentifiers' => false,

            /*
            * During development, if using MySQL < 5.6, uncommenting the
            * following line could boost the speed at which schema metadata is
            * fetched from the database. It can also be set directly with the
            * mysql configuration directive 'innodb_stats_on_metadata = 0'
            * which is the recommended value in production environments
            */
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
        ],

        /**
         * The test connection is used during the test suite.
         */
        'test' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            //'port' => 'nonstandard_port_number',
            'username' => 'vagrant',
            'password' => 'vagrant',
            'database' => 'vagrant_test',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
        ],
    ],
];
