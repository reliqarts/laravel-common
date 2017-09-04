<?php

return [
    // debug mode?
    'debug' => false,

    // storage
    'files' => [
        // Version number file.
        'version' => env('SIMPLE_COMMONS_VERSION_FILE', 'version.number'),

        // Build number file.
        'build' => env('SIMPLE_COMMONS_BUILD_FILE', 'build.number'),
    ],

];
