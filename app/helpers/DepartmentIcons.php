<?php

/**
 * Inline SVG icons used on the public Departments page (and anywhere we want a
 * subject-related icon next to a department's name). The site doesn’t load
 * Font Awesome on the public layout, so we keep these as SVG strings.
 */
class DepartmentIcons {
    /**
     * Pick the best icon for a department / faculty pair.
     *
     * @param string|null $department
     * @param string|null $faculty
     * @return string SVG markup (currentColor)
     */
    public static function for($department = '', $faculty = '') {
        $deptKey = self::normalize($department);
        $facKey = self::normalize($faculty);

        $deptMap = [
            'electrical-engineering-technology' => self::bolt(),
            'electrical-engineering' => self::bolt(),
            'electrical' => self::bolt(),
            'electronics-engineering' => self::microchip(),
            'electronics' => self::microchip(),
            'mechanical-engineering' => self::cog(),
            'mechanical' => self::cog(),
            'plumbing-and-gas-fitting' => self::wrench(),
            'plumbing' => self::wrench(),
            'gas-fitting' => self::wrench(),
            'solar-technology' => self::sun(),
            'solar' => self::sun(),
            'fashion' => self::sparkle(),
            'tailoring' => self::sparkle(),
            'automotive' => self::car(),
            'auto' => self::car(),
            'welding' => self::flame(),
            'carpentry' => self::hammer(),
            'masonry' => self::trowel(),
            'building-construction' => self::building(),
            'construction' => self::building(),
            'catering' => self::utensils(),
            'hospitality' => self::utensils(),
            'information-technology' => self::desktop(),
            'it' => self::desktop(),
            'computer' => self::desktop(),
        ];
        if (isset($deptMap[$deptKey])) {
            return $deptMap[$deptKey];
        }

        $facMap = [
            'engineering' => self::cog(),
            'construction' => self::building(),
            'technology' => self::microchip(),
            'automotive' => self::car(),
            'general' => self::graduation(),
        ];
        if (isset($facMap[$facKey])) {
            return $facMap[$facKey];
        }

        return self::graduation();
    }

    private static function normalize($s) {
        $s = strtolower(trim((string) $s));
        if ($s === '') {
            return '';
        }
        $s = preg_replace('/[^a-z0-9]+/', '-', $s);
        return trim($s, '-');
    }

    public static function bolt() {
        return '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/></svg>';
    }

    public static function microchip() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 9h6v6H9z"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 6h1M5 9h1M5 12h1M5 15h1M5 18h1M18 6h1M18 9h1M18 12h1M18 15h1M18 18h1M6 5v1M9 5v1M12 5v1M15 5v1M18 5v1M6 18v1M9 18v1M12 18v1M15 18v1M18 18v1"/></svg>';
    }

    public static function cog() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1A1.7 1.7 0 0 0 4.6 9a1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></svg>';
    }

    public static function wrench() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L4 17v3h3l5.3-5.3a4 4 0 0 0 5.4-5.4l-2.2 2.2-2.6-2.6 2.2-2.2z"/></svg>';
    }

    public static function sun() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';
    }

    public static function sparkle() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.7 5.3L19 10l-5.3 1.7L12 17l-1.7-5.3L5 10l5.3-1.7L12 3z"/><path d="M19 14l.9 2.6L22 17l-2.1.4L19 20l-.9-2.6L16 17l2.1-.4L19 14z"/></svg>';
    }

    public static function car() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 17V11l2-5h10l2 5v6"/><path d="M5 17h14"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="16.5" cy="17.5" r="1.5"/></svg>';
    }

    public static function flame() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3s4 4 4 8a4 4 0 1 1-8 0c0-3 2-5 4-8z"/><path d="M12 21a5 5 0 0 0 5-5"/></svg>';
    }

    public static function hammer() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 12l-8 8-3-3 8-8"/><path d="M17.5 6.5L21 10l-3.5 3.5L14 10z"/><path d="M14 10l-2-2"/></svg>';
    }

    public static function trowel() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21l5-5"/><path d="M8 16l4-4 8 4-4 4z"/><path d="M12 12l3-3"/></svg>';
    }

    public static function building() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 9h2M13 9h2M9 13h2M13 13h2M9 17h2M13 17h2"/></svg>';
    }

    public static function utensils() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 3v8a2 2 0 0 0 2 2v8"/><path d="M9 3v6"/><path d="M5 3v6"/><path d="M17 14l3-3a4 4 0 0 0-3-7v18"/></svg>';
    }

    public static function desktop() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>';
    }

    public static function graduation() {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/></svg>';
    }
}
