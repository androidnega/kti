<?php

/**
 * Keeps large binary image payloads out of TEXT columns — use uploads + paths in the DB instead.
 * Provides safe HTML for program detail pages (rich text from admin editor).
 */
class ContentSanitizer {
    /** @var string[] */
    private static $programDetailAllowed = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'del',
        'ul', 'ol', 'li', 'h2', 'h3', 'h4', 'blockquote', 'a', 'div', 'sub', 'sup',
    ];

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

    /**
     * Heuristic: stored string is probably rich HTML (vs plain text / legacy entity-only).
     */
    public static function isLikelyRichProgramHtml($s) {
        $s = trim((string) $s);
        if ($s === '') {
            return false;
        }
        return (bool) preg_match('/<[a-z][a-z0-9]*\b[\s>]/i', $s);
    }

    /**
     * Build safe HTML for the public program detail body (rich or legacy plain).
     */
    public static function programDetailBodyHtml($stored) {
        $stored = trim((string) $stored);
        if ($stored === '') {
            return '';
        }
        $decoded = htmlspecialchars_decode($stored, ENT_QUOTES | ENT_HTML5);
        if (self::isLikelyRichProgramHtml($decoded)) {
            return self::sanitizeProgramDetailHtml($decoded);
        }
        return self::legacyPlainDetailToHtml($decoded);
    }

    /**
     * Legacy plain / line-broken text → simple safe paragraphs.
     */
    public static function legacyPlainDetailToHtml($text) {
        $text = trim((string) $text);
        if ($text === '') {
            return '';
        }
        $parts = preg_split('/\n[\s]*\n/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if ($parts === false || count($parts) === 0) {
            $parts = [$text];
        }
        $out = '';
        foreach ($parts as $part) {
            $t = trim($part);
            if ($t === '') {
                continue;
            }
            $out .= '<p>' . nl2br(htmlspecialchars($t, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) . '</p>';
        }
        return $out;
    }

    /**
     * Whitelist-based HTML for program detail (after stripDataImageUris on input).
     */
    public static function sanitizeProgramDetailHtml($html) {
        $html = trim((string) $html);
        $html = self::stripDataImageUris($html);
        if ($html === '') {
            return '';
        }
        if (!class_exists('DOMDocument')) {
            return self::legacyPlainDetailToHtml(strip_tags($html));
        }
        $uid = 'kti-sr-' . bin2hex(random_bytes(4));
        $wrapped = '<?xml encoding="UTF-8"><div id="' . $uid . '">' . $html . '</div>';
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $loaded = @$dom->loadHTML($wrapped, LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);
        libxml_clear_errors();
        if (!$loaded) {
            return self::legacyPlainDetailToHtml(strip_tags($html));
        }
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//*[@id="' . $uid . '"]');
        if (!$nodes || $nodes->length < 1) {
            return self::legacyPlainDetailToHtml(strip_tags($html));
        }
        /** @var DOMElement $root */
        $root = $nodes->item(0);
        self::sanitizeProgramDetailDomElement($root);
        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $dom->saveHTML($child);
        }
        return $out;
    }

    /**
     * @param DOMElement $el
     */
    private static function sanitizeProgramDetailDomElement($el) {
        foreach (iterator_to_array($el->childNodes) as $c) {
            if ($c instanceof DOMComment) {
                $el->removeChild($c);
                continue;
            }
            if ($c instanceof DOMElement) {
                self::sanitizeProgramDetailDomElement($c);
            }
        }
        $tag = strtolower($el->tagName);
        if ($tag === 'script' || $tag === 'style' || $tag === 'iframe' || $tag === 'object' || $tag === 'embed' || $tag === 'form') {
            if ($el->parentNode) {
                $el->parentNode->removeChild($el);
            }
            return;
        }
        if ($tag === 'h1') {
            $doc = $el->ownerDocument;
            if ($doc) {
                $h2 = $doc->createElement('h2');
                while ($el->firstChild) {
                    $h2->appendChild($el->firstChild);
                }
                $el->parentNode->replaceChild($h2, $el);
                self::sanitizeProgramDetailDomElement($h2);
            }
            return;
        }
        if (!in_array($tag, self::$programDetailAllowed, true)) {
            self::removeDomElementKeepChildren($el);
            return;
        }
        $removeAttrs = [];
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            if ($tag === 'a' && ($name === 'href' || $name === 'title')) {
                if ($name === 'href' && !self::safeProgramDetailHref($attr->value)) {
                    $removeAttrs[] = $attr->name;
                }
                continue;
            }
            if ($tag === 'a' && $name === 'target' && strtolower($attr->value) === '_blank') {
                continue;
            }
            if ($tag === 'a' && $name === 'rel') {
                continue;
            }
            $removeAttrs[] = $attr->name;
        }
        foreach ($removeAttrs as $n) {
            $el->removeAttribute($n);
        }
        if ($tag === 'a') {
            $href = $el->getAttribute('href');
            if ($href === '') {
                self::removeDomElementKeepChildren($el);
                return;
            }
            if (preg_match('#^(https?:)?//#i', $href)) {
                $el->setAttribute('rel', 'noopener noreferrer');
                $el->setAttribute('target', '_blank');
            }
        }
    }

    /**
     * @param DOMElement $el
     */
    private static function removeDomElementKeepChildren($el) {
        $p = $el->parentNode;
        if (!$p) {
            return;
        }
        while ($el->firstChild) {
            $p->insertBefore($el->firstChild, $el);
        }
        $p->removeChild($el);
    }

    private static function safeProgramDetailHref($href) {
        $href = trim((string) $href);
        if ($href === '' || $href === '#') {
            return true;
        }
        $lower = strtolower($href);
        if (strpos($lower, 'javascript:') === 0 || strpos($lower, 'data:') === 0 || strpos($lower, 'vbscript:') === 0) {
            return false;
        }
        if (preg_match('#^https?://#i', $href) || preg_match('#^//#', $href)) {
            return true;
        }
        if (preg_match('#^mailto:#i', $href)) {
            return true;
        }
        if ($href[0] === '#' && strlen($href) < 200) {
            return true;
        }
        if ($href[0] === '/' && strlen($href) < 500 && strpos($href, '//') !== 0) {
            return true;
        }
        if (preg_match('#^[./][^.\s]*#', $href) && strlen($href) < 500 && strpos($lower, 'script') === false) {
            return true;
        }
        return false;
    }
}
