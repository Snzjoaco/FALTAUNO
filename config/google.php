<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Maps API Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar tu API key de Google Maps.
    | Obtén tu API key desde: https://console.cloud.google.com/
    |
    */

    'maps_api_key' => env('GOOGLE_MAPS_API_KEY', 'TU_API_KEY_AQUI'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Map Settings
    |--------------------------------------------------------------------------
    */
    
    'default_center' => [
        'lat' => -34.6037, // Buenos Aires
        'lng' => -58.3816
    ],
    
    'default_zoom' => 13,
    
    'country_restriction' => 'ar', // Argentina
    
    'map_types' => [
        'roadmap' => 'ROADMAP',
        'satellite' => 'SATELLITE',
        'hybrid' => 'HYBRID',
        'terrain' => 'TERRAIN'
    ]
];




