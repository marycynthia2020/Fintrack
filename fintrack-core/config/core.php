<?php

return [

    'default_currency' => env('FINTRACK_CURRENCY', 'USD'),

    'default_timezone' => env('FINTRACK_TIMEZONE', 'UTC'),

    'storage_disk' => env('FINTRACK_STORAGE_DISK', 'local'),

    'attachments' => [
        'folder' => env('FINTRACK_ATTACHMENTS_FOLDER', 'attachments'),
        'max_size' => env('FINTRACK_ATTACHMENTS_MAX_SIZE', 10240), // KB
    ],

    'artifacts' => [
        'folder' => env('FINTRACK_ARTIFACTS_FOLDER', 'artifacts'),
    ],

];
