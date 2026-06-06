<?php

require_once __DIR__ . '/BaseModel.php';

class HeroSlide extends BaseModel {
    protected $table = 'hero_slides';

    public function allOrdered() {
        return $this->db->fetchAll(
            'SELECT * FROM hero_slides ORDER BY sort_order ASC, id ASC'
        );
    }

    public function publicList() {
        return $this->db->fetchAll(
            'SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public function nextSortOrder() {
        $row = $this->db->fetchOne('SELECT MAX(sort_order) AS m FROM hero_slides');
        return $row && isset($row['m']) && $row['m'] !== null ? ((int) $row['m']) + 1 : 0;
    }

    /**
     * @param int[] $ids Apply 0..n sort_order in this exact sequence.
     */
    public function updateOrder(array $ids) {
        foreach ($ids as $i => $id) {
            $this->db->query(
                'UPDATE hero_slides SET sort_order = ? WHERE id = ?',
                [(int) $i, (int) $id]
            );
        }
    }
}
