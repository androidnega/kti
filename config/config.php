<?php

// Application Configuration
define('APP_NAME', 'Kikam Technical Institute');
define('APP_URL', 'https://kikamtech.org');
define('ADMIN_URL', 'https://kikamtech.org/admin');

// YouTube (@KikamTechnicalInstitute) — RSS shows latest uploads; set API key for full channel list via Data API v3
define('YOUTUBE_CHANNEL_ID', 'UCA4fazj3TsAZelogMi8MS7w');
define('YOUTUBE_CHANNEL_URL', 'https://www.youtube.com/@KikamTechnicalInstitute');
define('YOUTUBE_API_KEY', ''); // optional: paste key from Google Cloud Console to load all uploads

// Curated watch?v= IDs for the Videos page (shown when non-empty; titles load via oEmbed). Leave empty to use RSS/API only.
define('YOUTUBE_CURATED_VIDEO_IDS', [
    'hEGC-NGcZYU',
    's8UmDYb6hvU',
    'H-RnUAn6b7Q',
    'GVkQrtr5fQM',
    '5cxyVBU4R0E',
    'xenGD-Vxf10',
    '_fgBVzVGSFU',
]);

// Database Configuration
define('DB_PATH', __DIR__ . '/../storage/database.sqlite');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', STORAGE_PATH . '/uploads');
/** Web-accessible program images/videos (under public/) */
define('PROGRAM_UPLOAD_PATH', PUBLIC_PATH . '/uploads/programs');
define('PROGRAM_VIDEO_PATH', PUBLIC_PATH . '/uploads/videos');
/** Max JPEG output size for program gallery images (bytes) */
define('PROGRAM_IMAGE_MAX_BYTES', 256000);

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// Environment
define('ENVIRONMENT', 'production'); // development or production

// Error Reporting
if (ENVIRONMENT === 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('UTC');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
