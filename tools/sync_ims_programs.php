#!/usr/bin/env php
<?php
/**
 * Upserts IMS-linked department programs and copies/optimizes images into the web tree:
 *   public/uploads/programs/{slug}/*.jpg
 * Images are matched by IMS folder name (exact match preferred, then fuzzy match to department).
 *
 * Usage (from project root):
 *   php tools/sync_ims_programs.php
 *   php tools/sync_ims_programs.php --prune-legacy   # removes programs whose slug is not one of the six
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once APP_PATH . '/helpers/Database.php';
require_once APP_PATH . '/helpers/ImageProcessor.php';
require_once APP_PATH . '/models/Program.php';
require_once APP_PATH . '/models/ProgramMedia.php';

/**
 * @param string $folderName e.g. "Electrical Engineering Technology Department"
 */
function ims_folder_normalize($folderName) {
    $s = strtolower(trim($folderName));
    $s = preg_replace('/\bdepartment\b/u', '', $s);
    $s = preg_replace('/[^a-z0-9]+/u', '', $s);
    return $s;
}

/**
 * Map actual directory names under ims/ to department definitions.
 *
 * @param string $imsRoot
 * @param array<string,array> $definitions canonical folder name => def
 * @return array<string,array> disk folder basename => def
 */
function resolve_ims_folder_map($imsRoot, array $definitions) {
    $map = [];
    foreach ($definitions as $expectedFolder => $def) {
        $path = $imsRoot . '/' . $expectedFolder;
        if (is_dir($path)) {
            $map[$expectedFolder] = $def;
        }
    }

    $dirs = glob($imsRoot . '/*', GLOB_ONLYDIR) ?: [];
    foreach ($dirs as $full) {
        $disk = basename($full);
        if (isset($map[$disk])) {
            continue;
        }
        $normDisk = ims_folder_normalize($disk);
        $best = null;
        $bestPct = 0.0;
        foreach ($definitions as $expectedFolder => $def) {
            $normExp = ims_folder_normalize($expectedFolder);
            if ($normDisk === $normExp) {
                $best = $def;
                $bestPct = 100.0;
                break;
            }
            similar_text($normDisk, $normExp, $pct);
            if ($pct > $bestPct) {
                $bestPct = $pct;
                $best = $def;
            }
        }
        if ($best !== null && $bestPct >= 42.0) {
            $map[$disk] = $best;
            fwrite(STDERR, "Matched IMS folder \"{$disk}\" → slug {$best['slug']} (" . round($bestPct, 1) . "% name similarity)\n");
        } else {
            fwrite(STDERR, "Unmatched IMS folder (add or rename): {$disk}\n");
        }
    }

    return $map;
}

$prune = in_array('--prune-legacy', $argv, true);

$imsRoot = ROOT_PATH . '/ims';

$definitions = [
    'Electrical Engineering Technology Department' => [
        'slug' => 'electrical-engineering-technology',
        'name' => 'Electrical Engineering Technology',
        'faculty' => 'Engineering',
        'department' => 'Electrical Engineering Technology',
        'description' => 'Hands-on training in electrical installations, motor control, power distribution, and workshop practice aligned to industry standards.',
    ],
    'Electroonics Engineering Department' => [
        'slug' => 'electronics-engineering',
        'name' => 'Electronics Engineering',
        'faculty' => 'Technology',
        'department' => 'Electronics Engineering',
        'description' => 'Circuit design, electronic systems, troubleshooting, and maintenance of modern electronic equipment used in industry and everyday technology.',
    ],
    'Fashion Department' => [
        'slug' => 'fashion',
        'name' => 'Fashion',
        'faculty' => 'General',
        'department' => 'Fashion',
        'description' => 'Garment construction, pattern making, textiles, and fashion entrepreneurship for the creative and apparel sector.',
    ],
    'Mechanical Engineering Department' => [
        'slug' => 'mechanical-engineering',
        'name' => 'Mechanical Engineering',
        'faculty' => 'Engineering',
        'department' => 'Mechanical Engineering',
        'description' => 'Mechanical systems, machining, thermodynamics, and manufacturing using modern workshop facilities.',
    ],
    'Plumbing and Gas Fitting Department' => [
        'slug' => 'plumbing-and-gas-fitting',
        'name' => 'Plumbing and Gas Fitting',
        'faculty' => 'Construction',
        'department' => 'Plumbing and Gas Fitting',
        'description' => 'Safe installation and maintenance of plumbing, sanitation, and gas systems for residential and commercial buildings.',
    ],
    'Solar Department' => [
        'slug' => 'solar-technology',
        'name' => 'Solar Technology',
        'faculty' => 'Technology',
        'department' => 'Solar Technology',
        'description' => 'Photovoltaic systems, renewable energy basics, site assessment, and practical solar installation skills.',
    ],
];

if (!is_dir(PROGRAM_UPLOAD_PATH)) {
    mkdir(PROGRAM_UPLOAD_PATH, 0755, true);
}
if (!is_dir(PROGRAM_VIDEO_PATH)) {
    mkdir(PROGRAM_VIDEO_PATH, 0755, true);
}

$db = Database::getInstance()->getConnection();
$programModel = new Program();
$mediaModel = new ProgramMedia();

$canonicalSlugs = array_column($definitions, 'slug');

if ($prune) {
    $placeholders = implode(',', array_fill(0, count($canonicalSlugs), '?'));
    $db->prepare("DELETE FROM program_media WHERE program_id NOT IN (SELECT id FROM programs WHERE slug IN ($placeholders))")->execute($canonicalSlugs);
    $db->prepare("DELETE FROM programs WHERE slug IS NULL OR slug NOT IN ($placeholders)")->execute($canonicalSlugs);
    fwrite(STDERR, "Pruned programs not in canonical IMS list.\n");
}

if (!is_dir($imsRoot)) {
    fwrite(STDERR, "Missing ims/ root: {$imsRoot}\n");
    exit(1);
}

$folderMap = resolve_ims_folder_map($imsRoot, $definitions);

foreach ($folderMap as $diskFolder => $def) {
    $dir = $imsRoot . '/' . $diskFolder;
    if (!is_dir($dir)) {
        continue;
    }

    $slug = $def['slug'];
    $row = $programModel->findBySlug($slug);
    $payload = [
        'name' => $def['name'],
        'department' => $def['department'],
        'faculty' => $def['faculty'],
        'slug' => $slug,
        'description' => $def['description'],
        'updated_at' => gmdate('Y-m-d H:i:s'),
    ];

    if ($row) {
        $programModel->update($row['id'], $payload);
        $programId = (int) $row['id'];
        fwrite(STDERR, "Updated program #{$programId} {$slug} (from folder: {$diskFolder})\n");
    } else {
        $payload['created_at'] = gmdate('Y-m-d H:i:s');
        $programId = (int) $programModel->create($payload);
        fwrite(STDERR, "Created program #{$programId} {$slug} (from folder: {$diskFolder})\n");
    }

    $outDir = PROGRAM_UPLOAD_PATH . '/' . $slug;
    if (!is_dir($outDir)) {
        mkdir($outDir, 0755, true);
    }

    $existing = $mediaModel->forProgram($programId);
    $existingFiles = [];
    foreach ($existing as $m) {
        if (($m['media_type'] ?? '') === 'image' && !empty($m['file_path'])) {
            $existingFiles[$m['file_path']] = true;
        }
    }

    $files = [];
    foreach (glob($dir . '/*') ?: [] as $f) {
        if (!is_file($f)) {
            continue;
        }
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            continue;
        }
        $files[] = $f;
    }
    sort($files);

    $sort = 0;
    foreach ($files as $src) {
        $base = preg_replace('/[^a-z0-9]+/i', '-', strtolower(pathinfo($src, PATHINFO_FILENAME)));
        $base = trim($base, '-') ?: 'photo';
        $destName = $base . '.jpg';
        $destPath = $outDir . '/' . $destName;
        $n = 1;
        while (file_exists($destPath)) {
            $destName = $base . '-' . $n . '.jpg';
            $destPath = $outDir . '/' . $destName;
            $n++;
        }

        if (!ImageProcessor::toJpegMaxBytes($src, $destPath)) {
            fwrite(STDERR, "Failed to process: {$src}\n");
            continue;
        }

        $rel = 'uploads/programs/' . $slug . '/' . $destName;

        if (isset($existingFiles[$rel])) {
            continue;
        }

        $mediaModel->create([
            'program_id' => $programId,
            'media_type' => 'image',
            'file_path' => $rel,
            'external_url' => null,
            'caption' => '',
            'sort_order' => $sort++,
        ]);
    }

    $st = $db->prepare(
        "SELECT file_path FROM program_media WHERE program_id = ? AND media_type = 'image' AND file_path IS NOT NULL AND trim(file_path) != '' ORDER BY sort_order ASC, id ASC LIMIT 1"
    );
    $st->execute([$programId]);
    $coverRow = $st->fetch(PDO::FETCH_ASSOC);
    if ($coverRow && !empty($coverRow['file_path'])) {
        $programModel->update($programId, [
            'cover_image' => $coverRow['file_path'],
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);
    }
}

fwrite(STDERR, "Done. Web images live under: " . PROGRAM_UPLOAD_PATH . " (URL path uploads/programs/{slug}/)\n");
