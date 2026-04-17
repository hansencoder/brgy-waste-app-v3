<?php
/**
 * Geocoding Helper - Reverse geocoding using OpenStreetMap Nominatim API
 * Converts latitude/longitude coordinates to human-readable location names
 */
class Geocoding {
    
    /**
     * Reverse geocode coordinates to get location name
     * Uses OpenStreetMap Nominatim API (free, no API key required)
     * 
     * @param float $latitude
     * @param float $longitude
     * @return string Location name or "Unknown location" if API fails
     */
    public static function getLocationName($latitude, $longitude) {
        try {
            // Format: latitude, longitude (space-separated)
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=" . urlencode($latitude) . "&lon=" . urlencode($longitude);
            
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'CivicLens/1.0');
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                
                if ($data && isset($data['address'])) {
                    $address = $data['address'];
                    
                    // Build location string with priority: street > neighborhood > suburb > city
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
                        return implode(', ', $parts);
                    }
                }
            }
        } catch (Exception $e) {
            // Silent fail - return default text
        }
        
        return "Unknown location";
    }
}
