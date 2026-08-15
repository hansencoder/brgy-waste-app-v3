<?php

class Barangay {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getInfo() {
        $this->db->query("SELECT * FROM barangays LIMIT 1");
        return $this->db->single();
    }

    public function updateInfo($data) {
        $this->db->query("
            UPDATE barangays SET 
                barangay_name = :barangay_name,
                municipality = :municipality,
                province = :province,
                region = :region,
                official_address = :official_address,
                contact_number = :contact_number,
                official_email = :official_email
            WHERE barangay_id = :id
        ");
        $this->db->bind(':barangay_name', $data['barangay_name']);
        $this->db->bind(':municipality', $data['municipality']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':region', $data['region']);
        $this->db->bind(':official_address', $data['official_address']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':official_email', $data['official_email']);
        $this->db->bind(':id', $data['barangay_id'] ?? 1);
        return $this->db->execute();
    }

    /**
     * Get official Barangay boundary with GeoJSON polygon and map center settings
     */
    public function getBoundary($barangayId = 1) {
        $this->db->query("
            SELECT boundary_id, barangay_id, 
                   ST_AsGeoJSON(polygon_geometry) AS polygon_geometry,
                   center_latitude, center_longitude, default_zoom,
                   updated_at
            FROM barangay_boundaries 
            WHERE barangay_id = :id 
            ORDER BY boundary_id DESC 
            LIMIT 1
        ");
        $this->db->bind(':id', $barangayId);
        $boundary = $this->db->single();

        if (!$boundary || empty($boundary['polygon_geometry'])) {
            // Default fallback coordinates (Barangay Dulong Bayan)
            $defaultGeo = json_encode([
                "type" => "Polygon",
                "coordinates" => [[
                    [120.8013517, 15.5699279], [120.8008898, 15.569572], [120.8008276, 15.5686578],
                    [120.8006126, 15.5685788], [120.8005542, 15.5678398], [120.8001844, 15.5672858],
                    [120.8000725, 15.5668847], [120.8001665, 15.566531], [120.7995785, 15.5663685],
                    [120.7989717, 15.5657033], [120.7987031, 15.5658025], [120.7984537, 15.5654243],
                    [120.7980956, 15.5652], [120.7977553, 15.5652043], [120.7975135, 15.5652862],
                    [120.7971285, 15.5652259], [120.7964691, 15.5648604], [120.7961709, 15.5643821],
                    [120.795562, 15.5643993], [120.7951681, 15.5637567], [120.7953561, 15.5632478],
                    [120.7952523, 15.562581], [120.7950598, 15.5617529], [120.7950416, 15.5611835],
                    [120.7945939, 15.5608471], [120.7946431, 15.5603295], [120.7943504, 15.5596467],
                    [120.7937415, 15.5597848], [120.7930393, 15.55916], [120.7928646, 15.5570187],
                    [120.7921781, 15.555107], [120.7912123, 15.554853], [120.7913399, 15.5543176],
                    [120.7915605, 15.5533236], [120.7918092, 15.5534046], [120.8001316, 15.5478115],
                    [120.8011058, 15.5481325], [120.8021398, 15.5484701], [120.8027807, 15.5485113],
                    [120.8032508, 15.5489723], [120.8030798, 15.5500426], [120.8038043, 15.5501365],
                    [120.8044282, 15.5502517], [120.8049495, 15.550614], [120.8058211, 15.5508445],
                    [120.8062911, 15.551569], [120.8071584, 15.5520964], [120.8076635, 15.5520903],
                    [120.8081181, 15.5524005], [120.8083454, 15.5523519], [120.8085979, 15.5525708],
                    [120.8088668, 15.5528807], [120.8118007, 15.5512389], [120.8126332, 15.550257],
                    [120.8153176, 15.5523838], [120.817434, 15.549628], [120.8219183, 15.5518119],
                    [120.8232918, 15.5522367], [120.8253946, 15.5516159], [120.8260956, 15.5512188],
                    [120.8281375, 15.5526533], [120.8298546, 15.5518644], [120.8310955, 15.5519514],
                    [120.8335885, 15.5541358], [120.8325752, 15.5557229], [120.8326161, 15.5574083],
                    [120.8332704, 15.5602447], [120.8283841, 15.5650646], [120.8236492, 15.5703491],
                    [120.82189, 15.5689622], [120.8219651, 15.5676998], [120.8203353, 15.5645562],
                    [120.8205697, 15.5594636], [120.8185042, 15.5617437], [120.8149287, 15.5609879],
                    [120.8126889, 15.5623097], [120.8092582, 15.5595308], [120.8032464, 15.5673914],
                    [120.8014669, 15.5699463], [120.8013517, 15.5699279]
                ]]
            ]);
            return [
                'boundary_id' => null,
                'barangay_id' => $barangayId,
                'polygon_geometry' => $defaultGeo,
                'center_latitude' => 15.55800000,
                'center_longitude' => 120.80300000,
                'default_zoom' => 15,
                'updated_at' => null
            ];
        }

        return $boundary;
    }

    /**
     * Save / Update official Barangay boundary
     */
    public function saveBoundary($barangayId, $geoJson, $centerLat = 15.558, $centerLng = 120.803, $zoom = 15, $userId = null) {
        $this->db->query("SELECT boundary_id FROM barangay_boundaries WHERE barangay_id = :id LIMIT 1");
        $this->db->bind(':id', $barangayId);
        $existing = $this->db->single();

        if ($existing) {
            $this->db->query("
                UPDATE barangay_boundaries 
                SET polygon_geometry = ST_GeomFromGeoJSON(:geojson),
                    center_latitude = :lat,
                    center_longitude = :lng,
                    default_zoom = :zoom,
                    updated_by = :user_id,
                    updated_at = NOW()
                WHERE barangay_id = :id
            ");
        } else {
            $this->db->query("
                INSERT INTO barangay_boundaries 
                (barangay_id, polygon_geometry, center_latitude, center_longitude, default_zoom, created_by, updated_by)
                VALUES 
                (:id, ST_GeomFromGeoJSON(:geojson), :lat, :lng, :zoom, :user_id, :user_id)
            ");
        }

        $this->db->bind(':geojson', $geoJson);
        $this->db->bind(':lat', (float)$centerLat);
        $this->db->bind(':lng', (float)$centerLng);
        $this->db->bind(':zoom', (int)$zoom);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':id', $barangayId);

        return $this->db->execute();
    }

    /**
     * Convenience method to get all map configurations (boundary, center, zoom, puroks)
     */
    public function getMapConfig($barangayId = 1) {
        $boundary = $this->getBoundary($barangayId);
        
        $this->db->query("
            SELECT p.purok_id, p.purok_name, ST_AsGeoJSON(pb.polygon_geometry) AS polygon_geometry 
            FROM puroks p
            LEFT JOIN purok_boundaries pb ON p.purok_id = pb.purok_id
            WHERE p.is_active = 1
            ORDER BY p.purok_name
        ");
        $puroks = $this->db->resultSet();

        return [
            'boundary' => $boundary,
            'boundary_geojson' => $boundary['polygon_geometry'] ?? null,
            'center' => [
                'lat' => (float)($boundary['center_latitude'] ?? 15.558),
                'lng' => (float)($boundary['center_longitude'] ?? 120.803),
                'zoom' => (int)($boundary['default_zoom'] ?? 15)
            ],
            'puroks' => $puroks
        ];
    }
}