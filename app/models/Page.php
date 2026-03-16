<?php

require_once __DIR__ . '/BaseModel.php';

class Page extends BaseModel {
    protected $table = 'pages';

    public function findBySlug($slug) {
        return $this->db->fetchOne(
            "SELECT * FROM pages WHERE slug = ?",
            [$slug]
        );
    }

    public function getSections($pageId) {
        return $this->db->fetchAll(
            "SELECT * FROM sections WHERE page_id = ? ORDER BY position ASC",
            [$pageId]
        );
    }

    public function updatePage($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }
}
