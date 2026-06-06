<?php

require_once __DIR__ . '/BaseModel.php';

class Alumni extends BaseModel {
    protected $table = 'alumni';

    public function allOrdered() {
        return $this->db->fetchAll(
            'SELECT * FROM alumni ORDER BY is_featured DESC, sort_order ASC, id DESC'
        );
    }

    public function publicList() {
        return $this->allOrdered();
    }
}
