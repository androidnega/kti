<?php

require_once __DIR__ . '/BaseModel.php';

/**
 * Stored in the `events` table. Class is named EventItem to avoid clashing with
 * the future PHP reserved word `Event`.
 */
class EventItem extends BaseModel {
    protected $table = 'events';

    public static function slugify($text) {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'event';
    }

    public function findBySlug($slug) {
        return $this->db->fetchOne(
            'SELECT * FROM events WHERE slug = ?',
            [$slug]
        );
    }

    /**
     * Upcoming / current first, then newest past.
     */
    public function publicList() {
        return $this->db->fetchAll(
            'SELECT * FROM events
             WHERE is_published = 1
             ORDER BY
                CASE
                    WHEN event_date IS NULL THEN 2
                    WHEN datetime(event_date) >= datetime("now", "-1 day") THEN 0
                    ELSE 1
                END,
                CASE
                    WHEN event_date IS NULL THEN created_at
                    WHEN datetime(event_date) >= datetime("now", "-1 day") THEN event_date
                    ELSE event_date
                END
                DESC,
                sort_order ASC,
                id DESC'
        );
    }

    public function adminList() {
        return $this->db->fetchAll(
            'SELECT * FROM events
             ORDER BY
                CASE WHEN event_date IS NULL THEN 1 ELSE 0 END,
                event_date DESC,
                sort_order ASC,
                id DESC'
        );
    }
}
