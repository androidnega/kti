<?php

require_once __DIR__ . '/BaseModel.php';

class Staff extends BaseModel {
    protected $table = 'staff';

    public function getByDepartment($department) {
        return $this->db->fetchAll(
            "SELECT * FROM staff WHERE department = ? ORDER BY name ASC",
            [$department]
        );
    }

    public function getAllDepartments() {
        return $this->db->fetchAll(
            "SELECT DISTINCT department FROM staff WHERE department IS NOT NULL ORDER BY department ASC"
        );
    }
}
