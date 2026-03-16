<?php

require_once __DIR__ . '/BaseModel.php';

class Section extends BaseModel {
    protected $table = 'sections';

    public function getByPage($pageId) {
        return $this->db->fetchAll(
            "SELECT * FROM sections WHERE page_id = ? ORDER BY position ASC",
            [$pageId]
        );
    }

    public function deleteByPage($pageId) {
        return $this->db->query(
            "DELETE FROM sections WHERE page_id = ?",
            [$pageId]
        );
    }
}
