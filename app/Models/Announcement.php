<?php
class Announcement {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all announcements ordered by creation date
     */
    public function getAll($limit = null) {
        $sql = "
            SELECT a.*, u.name as created_by_name
            FROM announcements a
            LEFT JOIN users u ON a.created_by = u.id
            ORDER BY a.created_at DESC
        ";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Get announcement by ID
     */
    public function getById($id) {
        $this->db->query("
            SELECT a.*, u.name as created_by_name
            FROM announcements a
            LEFT JOIN users u ON a.created_by = u.id
            WHERE a.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Create a new announcement
     */
    public function create($data) {
        $this->db->query("
            INSERT INTO announcements (title, content, created_by)
            VALUES (:title, :content, :created_by)
        ");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':created_by', $data['created_by']);
        return $this->db->execute();
    }

    /**
     * Get the ID of the last inserted announcement
     */
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    /**
     * Update an announcement
     */
    public function update($id, $data) {
        $this->db->query("
            UPDATE announcements 
            SET title = :title, content = :content
            WHERE id = :id
        ");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Delete an announcement
     */
    public function delete($id) {
        $this->db->query("DELETE FROM announcements WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Get recent announcements for dashboard
     */
    public function getRecent($limit = 5) {
        return $this->getAll($limit);
    }
}
