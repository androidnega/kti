<?php

require_once __DIR__ . '/BaseModel.php';

class ProgramMedia extends BaseModel {
    protected $table = 'program_media';

    public function forProgram($programId) {
        return $this->db->fetchAll(
            'SELECT * FROM program_media WHERE program_id = ? ORDER BY sort_order ASC, id ASC',
            [$programId]
        );
    }

    public function deleteForProgram($programId) {
        return $this->db->query('DELETE FROM program_media WHERE program_id = ?', [$programId]);
    }

    public function updateOrder(array $orderedIds) {
        $pos = 0;
        foreach ($orderedIds as $id) {
            $id = (int) $id;
            if ($id < 1) {
                continue;
            }
            $this->db->query(
                'UPDATE program_media SET sort_order = ? WHERE id = ?',
                [$pos++, $id]
            );
        }
        return true;
    }
}
