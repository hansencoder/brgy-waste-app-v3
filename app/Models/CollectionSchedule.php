<?php

class CollectionSchedule {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($activeOnly = true) {
        $sql = "
            SELECT cs.*, 
                   GROUP_CONCAT(p.purok_name SEPARATOR ', ') as puroks
            FROM collection_schedules cs
            LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
            LEFT JOIN puroks p ON csp.purok_id = p.purok_id
        ";
        if ($activeOnly) {
            $sql .= " WHERE cs.status = 'active'";
        }
        $sql .= " GROUP BY cs.schedule_id ORDER BY FIELD(cs.collection_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("
            SELECT cs.*, 
                  GROUP_CONCAT(p.purok_name SEPARATOR ', ') as puroks
            FROM collection_schedules cs
            LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
            LEFT JOIN puroks p ON csp.purok_id = p.purok_id
            WHERE cs.schedule_id = :id
            GROUP BY cs.schedule_id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getTodaySchedule() {
        $day = date('l');
        $this->db->query("
            SELECT cs.*, 
                  GROUP_CONCAT(p.purok_name SEPARATOR ', ') as puroks
            FROM collection_schedules cs
            LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
            LEFT JOIN puroks p ON csp.purok_id = p.purok_id
            WHERE cs.status = 'active' AND cs.collection_day = :day
            GROUP BY cs.schedule_id
            ORDER BY cs.start_time
        ");
        $this->db->bind(':day', $day);
        return $this->db->resultSet();
    }
}