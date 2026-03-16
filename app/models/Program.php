<?php

require_once __DIR__ . '/BaseModel.php';

class Program extends BaseModel {
    protected $table = 'programs';

    public function getByDepartment($department) {
        return $this->db->fetchAll(
            "SELECT * FROM programs WHERE department = ? ORDER BY name ASC",
            [$department]
        );
    }

    public function getAllDepartments() {
        return $this->db->fetchAll(
            "SELECT DISTINCT department FROM programs WHERE department IS NOT NULL ORDER BY department ASC"
        );
    }
}
