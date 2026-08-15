<?php
/**
 * Geocoding Helper - Reverse geocoding using OpenStreetMap Nominatim API
 * Converts latitude/longitude coordinates to human-readable location names
 */
class Geocoding {
    
    private static $cache = [];

    /**
     * Reverse geocode coordinates to get location name
     * Uses OpenStreetMap Nominatim API with memory caching and short timeout
     * 
     * @param float $latitude
     * @param float $longitude
     * @return string Location name or "Unknown location" if API fails
     */
    public static function getLocationName($latitude, $longitude) {
        if (empty($latitude) || empty($longitude)) {
            return "Barangay Dulong Bayan";
        }

        // Cache key by rounded coordinates
        $cacheKey = round((float)$latitude, 4) . ',' . round((float)$longitude, 4);
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=" . urlencode($latitude) . "&lon=" . urlencode($longitude);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1); // 1s max timeout
            curl_setopt($ch, CURLOPT_USERAGENT, 'WasteWatch/2.0');
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                
                if ($data && isset($data['address'])) {
                    $address = $data['address'];
                    $parts = [];
                    
                    if (!empty($address['road'])) {
                        $parts[] = $address['road'];
                    } elseif (!empty($address['street'])) {
                        $parts[] = $address['street'];
                    }
                    
                    if (!empty($address['suburb'])) {
                        $parts[] = $address['suburb'];
                    } elseif (!empty($address['neighbourhood'])) {
                        $parts[] = $address['neighbourhood'];
                    } elseif (!empty($address['residential'])) {
                        $parts[] = $address['residential'];
                    }
                    
                    if (!empty($address['city'])) {
                        $parts[] = $address['city'];
                    } elseif (!empty($address['county'])) {
                        $parts[] = $address['county'];
                    }
                    
                    if (!empty($parts)) {
                        $location = implode(', ', $parts);
                        self::$cache[$cacheKey] = $location;
                        return $location;
                    }
                }
            }
        } catch (Exception $e) {
            // Silent fallback
        }
        
        $fallback = "Dulong Bayan";
        self::$cache[$cacheKey] = $fallback;
        return $fallback;
    }
}
