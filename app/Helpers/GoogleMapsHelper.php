<?php

namespace App\Helpers;

class GoogleMapsHelper
{
    /**
     * Generar el script de Google Maps con la API key
     */
    public static function getMapsScript()
    {
        $apiKey = config('google.maps_api_key');
        return "https://maps.googleapis.com/maps/api/js?key={$apiKey}&libraries=places&callback=initMap";
    }
    
    /**
     * Generar el script completo con la API key
     */
    public static function getMapsScriptTag()
    {
        $scriptUrl = self::getMapsScript();
        return "<script src=\"{$scriptUrl}\" async defer></script>";
    }
    
    /**
     * Verificar si la API key está configurada
     */
    public static function isApiKeyConfigured()
    {
        $apiKey = config('google.maps_api_key');
        return !empty($apiKey) && $apiKey !== 'TU_API_KEY_AQUI';
    }
    
    /**
     * Obtener la configuración por defecto del mapa
     */
    public static function getDefaultMapConfig()
    {
        return [
            'center' => config('google.default_center'),
            'zoom' => config('google.default_zoom'),
            'country_restriction' => config('google.country_restriction')
        ];
    }
}




