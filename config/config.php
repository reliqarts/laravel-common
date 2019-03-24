<?php

return [
    // debug mode?
    'debug' => false,

    // storage
    'files' => [
        // Version number file.
        'version' => env('COMMON_VERSION_FILE', 'version.number'),

        // Build number file.
        'build' => env('COMMON_BUILD_FILE', 'build.number'),
    ],
];
