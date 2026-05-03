<?php

require_once __DIR__ . '/BaseModel.php';

class Program extends BaseModel {
    protected $table = 'programs';

    public static function slugify($text) {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'program';
    }

    public function findBySlug($slug) {
        return $this->db->fetchOne(
            'SELECT * FROM programs WHERE slug = ?',
            [$slug]
        );
    }

    public function getByDepartment($department) {
        return $this->db->fetchAll(
            "SELECT * FROM programs WHERE department = ? ORDER BY name ASC",
            [$department]
        );
    }

    public function getByFaculty($faculty) {
        return $this->db->fetchAll(
            "SELECT * FROM programs WHERE faculty = ? ORDER BY name ASC",
            [$faculty]
        );
    }

    public function getAllDepartments() {
        return $this->db->fetchAll(
            "SELECT DISTINCT department FROM programs WHERE department IS NOT NULL AND trim(department) != '' ORDER BY department ASC"
        );
    }

    /**
     * Faculty labels used for program listing filters (matches site “faculties”).
     */
    public function getAllFaculties() {
        $rows = $this->db->fetchAll(
            "SELECT DISTINCT faculty AS faculty FROM programs WHERE faculty IS NOT NULL AND trim(faculty) != '' ORDER BY faculty ASC"
        );
        if (!empty($rows)) {
            return $rows;
        }
        $out = [];
        foreach ($this->getAllDepartments() as $r) {
            $out[] = ['faculty' => $r['department']];
        }
        return $out;
    }

    public function allOrdered() {
        return $this->db->fetchAll(
            'SELECT * FROM programs ORDER BY faculty ASC, name ASC'
        );
    }
}
