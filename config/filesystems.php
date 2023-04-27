<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [
        
        'files_actuaciones' => [
            'driver' => 'local',
            'root' => storage_path('app/files_actuaciones'),
            'url' => 'files_actuaciones',
        ],

        
        'log_files' => [
            'driver' => 'local',
            'root' => storage_path('app/log_files'),
            'url' => 'app/log_files',
        ],

        'conc_status_files' => [
            'driver' => 'local',
            'root' => storage_path('app/con_estados_files'),
            'url' => 'app/con_estados_files',
        ],

        'conciliacion_files' => [
            'driver' => 'local',
            'root' => storage_path('app/conciliacion_files'),
            'url' => 'app/conciliacion_files',
        ],

        'reportes_files' => [
            'driver' => 'local',
            'root' => public_path('app/conciliacion_files'),
            'url' => 'app/conciliacion_files',
        ],

        'solicitud_files' => [
            'driver' => 'local', 
            'root' => storage_path('app/solicitud_files'),
            'url' => 'app/solicitud_files',
        ],

        'files_bibliotecas' => [
            'driver' => 'local',
            'root' => storage_path('app/files_bibliotecas'),
            'url' => 'files_bibliotecas',
        ],

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],
        'profile_files' => [
            'driver' => 'local',
            'root' => public_path('thumbnails'),
            'url' => 'thumbnails',
        ],
        'pdf_reporte_files' => [
            'driver' => 'local',
            'root' => storage_path('app/public/files_reportes'),
            'url' => 'files_reportes',
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

    ],

];
