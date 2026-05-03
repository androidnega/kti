<?php

/**
 * Converts images to JPEG and targets a maximum file size while preserving aspect ratio (uniform scale only).
 */
class ImageProcessor {
    /**
     * Free GD image; PHP 8+ uses GdImage objects and imagedestroy is deprecated (PHP 8.5+).
     *
     * @param resource|\GdImage|false|null $im
     */
    private static function freeImage($im) {
        if ($im === false || $im === null) {
            return;
        }
        if (PHP_VERSION_ID < 80000 && is_resource($im) && get_resource_type($im) === 'gd') {
            imagedestroy($im);
        }
    }

    /**
     * @param string $srcPath Absolute path to source (jpeg, png, gif, webp when GD supports)
     * @param string $destPath Output .jpg path
     */
    public static function toJpegMaxBytes($srcPath, $destPath, $maxBytes = null) {
        if ($maxBytes === null) {
            $maxBytes = defined('PROGRAM_IMAGE_MAX_BYTES') ? (int) PROGRAM_IMAGE_MAX_BYTES : 250000;
        }
        if (!is_readable($srcPath)) {
            return false;
        }
        if (!extension_loaded('gd')) {
            return @copy($srcPath, $destPath);
        }

        $img = self::loadImage($srcPath);
        if ($img === false) {
            return false;
        }

        if (function_exists('imagepalettetotruecolor')) {
            @imagepalettetotruecolor($img);
        }

        $w = imagesx($img);
        $h = imagesy($img);
        if ($w < 1 || $h < 1) {
            self::freeImage($img);
            return false;
        }

        $gd = $img;
        $maxEdge = 2200;
        if ($w > $maxEdge || $h > $maxEdge) {
            $scale = min($maxEdge / $w, $maxEdge / $h);
            $nw = max(1, (int) round($w * $scale));
            $nh = max(1, (int) round($h * $scale));
            $resized = imagecreatetruecolor($nw, $nh);
            imagealphablending($resized, true);
            imagecopyresampled($resized, $gd, 0, 0, 0, 0, $nw, $nh, $w, $h);
            self::freeImage($gd);
            $gd = $resized;
            $w = $nw;
            $h = $nh;
        }

        $jpegBytes = self::buildJpegUnderMaxBytes($gd, $w, $h, $maxBytes);
        if ($gd !== null && $gd !== false) {
            self::freeImage($gd);
        }

        if ($jpegBytes === false || $jpegBytes === null || $jpegBytes === '') {
            return false;
        }
        $dir = dirname($destPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($destPath, $jpegBytes) !== false;
    }

    /**
     * Encode as JPEG under $maxBytes by lowering quality then uniformly scaling (same factor for width and height).
     * Frees $gd and sets it to null before returning bytes written to disk only (never stored in the database).
     *
     * @param resource|\GdImage|null|false $gd
     * @return string|false
     */
    private static function buildJpegUnderMaxBytes(&$gd, &$w, &$h, $maxBytes) {
        $chosen = false;
        for ($round = 0; $round < 56; $round++) {
            $best = null;
            $bestLen = PHP_INT_MAX;

            for ($q = 92; $q >= 38; $q -= 2) {
                ob_start();
                imagejpeg($gd, null, $q);
                $try = ob_get_clean();
                if ($try === false) {
                    continue;
                }
                $len = strlen($try);
                if ($len <= $maxBytes) {
                    self::freeImage($gd);
                    $gd = null;
                    return $try;
                }
                if ($len < $bestLen) {
                    $bestLen = $len;
                    $best = $try;
                }
            }

            $chosen = $best;

            if ($chosen !== null && strlen($chosen) <= $maxBytes) {
                self::freeImage($gd);
                $gd = null;
                return $chosen;
            }

            if ($w <= 20 && $h <= 20) {
                break;
            }

            $scale = 0.87;
            $nw = max(1, (int) round($w * $scale));
            $nh = max(1, (int) round($h * $scale));
            if ($nw >= $w && $nh >= $h) {
                break;
            }

            $scaled = imagecreatetruecolor($nw, $nh);
            imagealphablending($scaled, true);
            imagecopyresampled($scaled, $gd, 0, 0, 0, 0, $nw, $nh, $w, $h);
            self::freeImage($gd);
            $gd = $scaled;
            $w = $nw;
            $h = $nh;
        }

        if ($chosen !== false && $chosen !== null && $chosen !== '') {
            self::freeImage($gd);
            $gd = null;
            return $chosen;
        }

        ob_start();
        imagejpeg($gd, null, 38);
        $fallback = ob_get_clean();
        self::freeImage($gd);
        $gd = null;
        return ($fallback !== false && $fallback !== '') ? $fallback : false;
    }

    private static function loadImage($path) {
        $info = @getimagesize($path);
        if ($info === false) {
            return false;
        }
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                return @imagecreatefromjpeg($path);
            case IMAGETYPE_PNG:
                $im = @imagecreatefrompng($path);
                if ($im) {
                    imagealphablending($im, true);
                    imagesavealpha($im, true);
                }
                return $im;
            case IMAGETYPE_GIF:
                return @imagecreatefromgif($path);
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    return @imagecreatefromwebp($path);
                }
                return false;
            default:
                return false;
        }
    }
}
