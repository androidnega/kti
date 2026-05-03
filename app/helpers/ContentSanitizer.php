<?php

/**
 * Keeps large binary image payloads out of TEXT columns — use uploads + paths in the DB instead.
 */
class ContentSanitizer {
    /**
     * Strip inline data:image/...;base64,... segments from stored text (pages, program copy, etc.).
     */
    public static function stripDataImageUris($text) {
        if ($text === null || $text === '') {
            return '';
        }
        $out = preg_replace('#data:image/[\w+.-]+;base64,[A-Za-z0-9+/=\s]+#i', '', (string) $text);
        return $out === null ? (string) $text : $out;
    }
}
