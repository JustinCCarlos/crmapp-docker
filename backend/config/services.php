<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | ElasticSearch
    | 
    | 
    |
    */

    'elasticsearch' => [
        'host' => env('ELASTICSEARCH_HOST', 'searcher'),
        'port' => env('ELASTICSEARCH_PORT', 9200),
    ],
];
