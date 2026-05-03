#!/usr/bin/env php
<?php
/**
 * Upserts the six IMS department programs and imports / optimizes images from ims/<Department Folder>/.
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

foreach ($definitions as $folder => $def) {
    $dir = $imsRoot . '/' . $folder;
    if (!is_dir($dir)) {
        fwrite(STDERR, "Skip missing folder: {$folder}\n");
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
        fwrite(STDERR, "Updated program #{$programId} {$slug}\n");
    } else {
        $payload['created_at'] = gmdate('Y-m-d H:i:s');
        $programId = (int) $programModel->create($payload);
        fwrite(STDERR, "Created program #{$programId} {$slug}\n");
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
    $firstRel = null;
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
        if ($firstRel === null) {
            $firstRel = $rel;
        }

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

    $programRow = $programModel->find($programId);
    if ($programRow && empty($programRow['cover_image']) && $firstRel) {
        $programModel->update($programId, ['cover_image' => $firstRel]);
    }
}

fwrite(STDERR, "Done.\n");
