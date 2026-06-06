<?php

require_once APP_PATH . '/models/Page.php';
require_once APP_PATH . '/models/Staff.php';
require_once APP_PATH . '/models/Program.php';
require_once APP_PATH . '/models/ProgramMedia.php';
require_once APP_PATH . '/models/Alumni.php';
require_once APP_PATH . '/models/EventItem.php';
require_once APP_PATH . '/models/HeroSlide.php';
require_once APP_PATH . '/helpers/ImageProcessor.php';
require_once APP_PATH . '/helpers/ContentSanitizer.php';

class AdminController extends BaseController {
    private $pageModel;
    private $staffModel;
    private $programModel;
    private $programMediaModel;
    private $alumniModel;
    private $eventModel;
    private $heroModel;

    public function __construct() {
        $this->pageModel = new Page();
        $this->staffModel = new Staff();
        $this->programModel = new Program();
        $this->programMediaModel = new ProgramMedia();
        $this->alumniModel = new Alumni();
        $this->eventModel = new EventItem();
        $this->heroModel = new HeroSlide();
    }

    public function dashboard() {
        $stats = [
            'pages' => count($this->pageModel->all()),
            'staff' => count($this->staffModel->all()),
            'programs' => count($this->programModel->all()),
            'alumni' => count($this->alumniModel->all()),
            'events' => count($this->eventModel->all()),
        ];
        $this->view('admin/dashboard', ['stats' => $stats]);
    }

    // Pages Management
    public function pages() {
        $pages = $this->pageModel->all();
        $this->view('admin/pages/index', ['pages' => $pages]);
    }

    public function pageForm($id = null) {
        $page = $id ? $this->pageModel->find($id) : null;
        $this->view('admin/pages/form', ['page' => $page]);
    }

    public function pageSave() {
        $data = [
            'slug' => $this->sanitize($_POST['slug']),
            'title' => $this->sanitize($_POST['title']),
            'content' => $this->sanitize(ContentSanitizer::stripDataImageUris((string) ($_POST['content'] ?? ''))),
        ];

        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->pageModel->updatePage($id, $data);
        } else {
            $this->pageModel->create($data);
        }

        $this->redirect(ADMIN_URL . '?action=pages');
    }

    public function pageDelete($id) {
        $this->pageModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=pages');
    }

    // Staff Management
    public function staff() {
        $staff = $this->staffModel->all();
        $this->view('admin/staff/index', ['staff' => $staff]);
    }

    public function staffForm($id = null) {
        $member = $id ? $this->staffModel->find($id) : null;
        $this->view('admin/staff/form', ['member' => $member]);
    }

    public function staffSave() {
        $data = [
            'name' => $this->sanitize($_POST['name']),
            'department' => $this->sanitize($_POST['department']),
            'role' => $this->sanitize($_POST['role']),
            'rank' => $this->sanitize($_POST['rank']),
        ];

        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->staffModel->update($id, $data);
        } else {
            $this->staffModel->create($data);
        }

        $this->redirect(ADMIN_URL . '?action=staff');
    }

    public function staffDelete($id) {
        $this->staffModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=staff');
    }

    // Programs Management
    public function programs() {
        $programs = $this->programModel->allOrdered();
        foreach ($programs as &$row) {
            $this->programModel->enrichProgramCover($row);
        }
        unset($row);
        $this->view('admin/programs/index', ['programs' => $programs]);
    }

    public function programForm($id = null) {
        $program = $id ? $this->programModel->find($id) : null;
        if ($program) {
            $this->programModel->enrichProgramCover($program);
        }
        $media = [];
        if ($program) {
            $media = $this->programMediaModel->forProgram((int) $program['id']);
        }
        $this->view('admin/programs/form', ['program' => $program, 'media' => $media]);
    }

    public function programSave() {
        $name = $this->sanitize($_POST['name'] ?? '');
        $department = $this->sanitize($_POST['department'] ?? '');
        $faculty = $this->sanitize($_POST['faculty'] ?? '');
        $description = $this->sanitize(ContentSanitizer::stripDataImageUris((string) ($_POST['description'] ?? '')));
        $detailRaw = trim(ContentSanitizer::stripDataImageUris((string) ($_POST['detail_content'] ?? '')));
        $detailContent = ContentSanitizer::sanitizeProgramDetailHtml($detailRaw);
        $coverRaw = trim(ContentSanitizer::stripDataImageUris((string) ($_POST['cover_image'] ?? '')));
        $coverImage = $coverRaw !== '' ? htmlspecialchars(strip_tags($coverRaw), ENT_QUOTES, 'UTF-8') : '';

        $slugInput = trim((string) ($_POST['slug'] ?? ''));
        $baseSlug = $slugInput !== '' ? Program::slugify($slugInput) : Program::slugify($name);

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $slug = $this->uniqueProgramSlug($baseSlug, $id > 0 ? $id : null);

        $now = gmdate('Y-m-d H:i:s');

        $data = [
            'name' => $name,
            'department' => $department,
            'faculty' => $faculty,
            'description' => $description,
            'detail_content' => $detailContent,
            'slug' => $slug,
            'cover_image' => $coverImage,
            'updated_at' => $now,
        ];

        if ($id > 0) {
            $this->programModel->update($id, $data);
            $savedId = $id;
        } else {
            $data['created_at'] = $now;
            $savedId = (int) $this->programModel->create($data);
        }

        $this->redirect(ADMIN_URL . '?action=program_edit&id=' . max(1, $savedId));
    }

    public function programDelete($id) {
        $this->programModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=programs');
    }

    public function programMediaUpload() {
        $programId = (int) ($_POST['program_id'] ?? $_GET['program_id'] ?? 0);
        if ($programId < 1 || !$this->programModel->find($programId)) {
            $this->json(['ok' => false, 'error' => 'Invalid program'], 400);
        }
        if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->json(['ok' => false, 'error' => 'No file uploaded'], 400);
        }

        $maxGalleryImages = 9;
        $cntRow = Database::getInstance()->fetchOne(
            'SELECT COUNT(*) AS c FROM program_media WHERE program_id = ? AND media_type = ?',
            [$programId, 'image']
        );
        $imageCount = $cntRow && isset($cntRow['c']) ? (int) $cntRow['c'] : 0;
        if ($imageCount >= $maxGalleryImages) {
            $this->json([
                'ok' => false,
                'error' => 'This gallery already has the maximum of ' . $maxGalleryImages . ' photos. Remove one to add another.',
            ], 400);
        }

        $tmp = $_FILES['file']['tmp_name'];
        $info = @getimagesize($tmp);
        if ($info === false) {
            $this->json(['ok' => false, 'error' => 'Not a valid image'], 400);
        }

        if (!is_dir(PROGRAM_UPLOAD_PATH)) {
            mkdir(PROGRAM_UPLOAD_PATH, 0755, true);
        }

        $basename = 'p' . $programId . '_' . bin2hex(random_bytes(6)) . '.jpg';
        $absDest = rtrim(PROGRAM_UPLOAD_PATH, '/') . '/' . $basename;
        if (!ImageProcessor::toJpegMaxBytes($tmp, $absDest)) {
            $this->json(['ok' => false, 'error' => 'Could not process image'], 500);
        }

        $webPath = 'uploads/programs/' . $basename;
        $maxRow = Database::getInstance()->fetchOne(
            'SELECT MAX(sort_order) AS m FROM program_media WHERE program_id = ?',
            [$programId]
        );
        $sort = isset($maxRow['m']) && $maxRow['m'] !== null ? (int) $maxRow['m'] + 1 : 0;

        $mid = $this->programMediaModel->create([
            'program_id' => $programId,
            'media_type' => 'image',
            'file_path' => $webPath,
            'external_url' => null,
            'caption' => '',
            'sort_order' => $sort,
        ]);

        $this->json([
            'ok' => true,
            'id' => $mid,
            'file_path' => $webPath,
            'url' => rtrim(APP_URL, '/') . '/' . $webPath,
        ]);
    }

    public function programCoverUpload() {
        $programId = (int) ($_POST['program_id'] ?? $_GET['program_id'] ?? 0);
        if ($programId < 1 || !$this->programModel->find($programId)) {
            $this->json(['ok' => false, 'error' => 'Invalid program'], 400);
        }
        if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->json(['ok' => false, 'error' => 'No file uploaded'], 400);
        }

        $tmp = $_FILES['file']['tmp_name'];
        if (@getimagesize($tmp) === false) {
            $this->json(['ok' => false, 'error' => 'Not a valid image'], 400);
        }

        if (!is_dir(PROGRAM_UPLOAD_PATH)) {
            mkdir(PROGRAM_UPLOAD_PATH, 0755, true);
        }

        $basename = 'p' . $programId . '_cover_' . bin2hex(random_bytes(5)) . '.jpg';
        $absDest = rtrim(PROGRAM_UPLOAD_PATH, '/') . '/' . $basename;
        if (!ImageProcessor::toJpegMaxBytes($tmp, $absDest)) {
            $this->json(['ok' => false, 'error' => 'Could not process image'], 500);
        }

        $webPath = 'uploads/programs/' . $basename;
        $now = gmdate('Y-m-d H:i:s');
        $this->programModel->update($programId, [
            'cover_image' => $webPath,
            'updated_at' => $now,
        ]);

        $this->json([
            'ok' => true,
            'file_path' => $webPath,
            'url' => rtrim(APP_URL, '/') . '/' . $webPath,
        ]);
    }

    public function programVideoUpload() {
        $programId = (int) ($_POST['program_id'] ?? $_GET['program_id'] ?? 0);
        if ($programId < 1 || !$this->programModel->find($programId)) {
            $this->json(['ok' => false, 'error' => 'Invalid program'], 400);
        }
        if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->json(['ok' => false, 'error' => 'No file uploaded'], 400);
        }

        $name = $_FILES['file']['name'] ?? '';
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($ext !== 'mp4') {
            $this->json(['ok' => false, 'error' => 'Only MP4 videos are allowed'], 400);
        }

        if (!is_dir(PROGRAM_VIDEO_PATH)) {
            mkdir(PROGRAM_VIDEO_PATH, 0755, true);
        }

        $basename = 'p' . $programId . '_' . bin2hex(random_bytes(6)) . '.mp4';
        $absDest = rtrim(PROGRAM_VIDEO_PATH, '/') . '/' . $basename;
        if (!@move_uploaded_file($_FILES['file']['tmp_name'], $absDest)) {
            $this->json(['ok' => false, 'error' => 'Could not save video'], 500);
        }

        $webPath = 'uploads/videos/' . $basename;
        $maxRow = Database::getInstance()->fetchOne(
            'SELECT MAX(sort_order) AS m FROM program_media WHERE program_id = ?',
            [$programId]
        );
        $sort = isset($maxRow['m']) && $maxRow['m'] !== null ? (int) $maxRow['m'] + 1 : 0;

        $mid = $this->programMediaModel->create([
            'program_id' => $programId,
            'media_type' => 'video',
            'file_path' => $webPath,
            'external_url' => null,
            'caption' => '',
            'sort_order' => $sort,
        ]);

        $this->json([
            'ok' => true,
            'id' => $mid,
            'file_path' => $webPath,
            'url' => rtrim(APP_URL, '/') . '/' . $webPath,
        ]);
    }

    public function programVideoUrlSave() {
        $programId = (int) ($_POST['program_id'] ?? $_GET['program_id'] ?? 0);
        $url = trim((string) ($_POST['external_url'] ?? ''));
        if ($programId < 1 || !$this->programModel->find($programId)) {
            $this->json(['ok' => false, 'error' => 'Invalid program'], 400);
        }
        if ($url === '') {
            $this->json(['ok' => false, 'error' => 'URL required'], 400);
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->json(['ok' => false, 'error' => 'Invalid URL'], 400);
        }

        $maxRow = Database::getInstance()->fetchOne(
            'SELECT MAX(sort_order) AS m FROM program_media WHERE program_id = ?',
            [$programId]
        );
        $sort = isset($maxRow['m']) && $maxRow['m'] !== null ? (int) $maxRow['m'] + 1 : 0;

        $mid = $this->programMediaModel->create([
            'program_id' => $programId,
            'media_type' => 'video',
            'file_path' => null,
            'external_url' => $url,
            'caption' => '',
            'sort_order' => $sort,
        ]);

        $this->json(['ok' => true, 'id' => $mid, 'external_url' => $url]);
    }

    public function programMediaReorder() {
        $raw = file_get_contents('php://input');
        $body = json_decode($raw, true);
        if (!is_array($body) || !isset($body['ids']) || !is_array($body['ids'])) {
            $this->json(['ok' => false, 'error' => 'Expected JSON {ids:[]}' ], 400);
        }

        $ids = array_values(array_filter(array_map('intval', $body['ids']), function ($i) {
            return $i > 0;
        }));
        if (count($ids) === 0) {
            $this->json(['ok' => true]);
        }

        $first = $this->programMediaModel->find($ids[0]);
        if (!$first) {
            $this->json(['ok' => false, 'error' => 'Invalid media'], 400);
        }
        $programId = (int) $first['program_id'];
        foreach ($ids as $mid) {
            $row = $this->programMediaModel->find($mid);
            if (!$row || (int) $row['program_id'] !== $programId) {
                $this->json(['ok' => false, 'error' => 'Mixed program media'], 400);
            }
        }

        $this->programMediaModel->updateOrder($ids);
        $this->json(['ok' => true]);
    }

    public function programMediaCaptionSave() {
        $id = (int) ($_POST['id'] ?? 0);
        $caption = htmlspecialchars(trim((string) ($_POST['caption'] ?? '')), ENT_QUOTES, 'UTF-8');
        if ($id < 1) {
            $this->json(['ok' => false, 'error' => 'Invalid id'], 400);
        }
        $row = $this->programMediaModel->find($id);
        if (!$row) {
            $this->json(['ok' => false, 'error' => 'Not found'], 404);
        }
        $this->programMediaModel->update($id, ['caption' => $caption]);
        $this->json(['ok' => true]);
    }

    public function programMediaDelete($id) {
        $id = (int) $id;
        if ($id < 1) {
            $this->redirect(ADMIN_URL . '?action=programs');
        }
        $row = $this->programMediaModel->find($id);
        $programId = $row ? (int) $row['program_id'] : 0;
        if ($row && !empty($row['file_path'])) {
            $rel = ltrim($row['file_path'], '/');
            $abs = rtrim(PUBLIC_PATH, '/') . '/' . $rel;
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
        if ($row) {
            $this->programMediaModel->delete($id);
        }
        $this->redirect($programId > 0 ? ADMIN_URL . '?action=program_edit&id=' . $programId : ADMIN_URL . '?action=programs');
    }

    public function programMediaSetCover($mediaId) {
        $mediaId = (int) $mediaId;
        if ($mediaId < 1) {
            $this->redirect(ADMIN_URL . '?action=programs');
        }
        $row = $this->programMediaModel->find($mediaId);
        if (!$row) {
            $this->redirect(ADMIN_URL . '?action=programs');
            return;
        }
        $programId = (int) $row['program_id'];
        if (($row['media_type'] ?? '') !== 'image' || empty($row['file_path'])) {
            $this->redirect(ADMIN_URL . '?action=program_edit&id=' . $programId);
            return;
        }
        $this->programModel->update($programId, [
            'cover_image' => $row['file_path'],
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);
        $this->redirect(ADMIN_URL . '?action=program_edit&id=' . $programId);
    }

    // ===== Hero slides =====

    public function heroSlides() {
        $slides = $this->heroModel->allOrdered();
        $this->view('admin/hero/index', ['slides' => $slides]);
    }

    /**
     * Multipart upload endpoint. Accepts one image per request via field `file`
     * (used by the drag-and-drop uploader on the index page). Returns JSON.
     */
    public function heroSlideUpload() {
        if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->json(['ok' => false, 'error' => 'No file uploaded'], 400);
        }
        $tmp = $_FILES['file']['tmp_name'];
        if (@getimagesize($tmp) === false) {
            $this->json(['ok' => false, 'error' => 'Not a valid image'], 400);
        }
        if (!is_dir(HERO_UPLOAD_PATH)) {
            @mkdir(HERO_UPLOAD_PATH, 0755, true);
        }
        $basename = 'hero_' . bin2hex(random_bytes(6)) . '.jpg';
        $absDest = rtrim(HERO_UPLOAD_PATH, '/') . '/' . $basename;
        if (!ImageProcessor::toJpegMaxBytes($tmp, $absDest)) {
            $this->json(['ok' => false, 'error' => 'Could not process image'], 500);
        }
        $webPath = 'uploads/hero/' . $basename;
        $sort = $this->heroModel->nextSortOrder();
        $id = (int) $this->heroModel->create([
            'image_path' => $webPath,
            'caption' => '',
            'alt_text' => '',
            'sort_order' => $sort,
            'is_active' => 1,
            'created_at' => gmdate('Y-m-d H:i:s'),
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);
        $this->json([
            'ok' => true,
            'id' => $id,
            'image_path' => $webPath,
            'url' => rtrim(APP_URL, '/') . '/' . $webPath,
            'sort_order' => $sort,
        ]);
    }

    public function heroSlideUpdate() {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id < 1) {
            $this->json(['ok' => false, 'error' => 'Invalid id'], 400);
        }
        $row = $this->heroModel->find($id);
        if (!$row) {
            $this->json(['ok' => false, 'error' => 'Not found'], 404);
        }
        $data = ['updated_at' => gmdate('Y-m-d H:i:s')];
        if (array_key_exists('caption', $_POST)) {
            $data['caption'] = htmlspecialchars(trim((string) $_POST['caption']), ENT_QUOTES, 'UTF-8');
        }
        if (array_key_exists('alt_text', $_POST)) {
            $data['alt_text'] = htmlspecialchars(trim((string) $_POST['alt_text']), ENT_QUOTES, 'UTF-8');
        }
        if (array_key_exists('is_active', $_POST)) {
            $data['is_active'] = empty($_POST['is_active']) ? 0 : 1;
        }
        $this->heroModel->update($id, $data);
        $this->json(['ok' => true]);
    }

    public function heroSlideReorder() {
        $raw = file_get_contents('php://input');
        $body = json_decode($raw, true);
        if (!is_array($body) || !isset($body['ids']) || !is_array($body['ids'])) {
            $this->json(['ok' => false, 'error' => 'Expected JSON {ids:[]}'], 400);
        }
        $ids = array_values(array_filter(array_map('intval', $body['ids']), function ($i) {
            return $i > 0;
        }));
        $this->heroModel->updateOrder($ids);
        $this->json(['ok' => true]);
    }

    public function heroSlideDelete($id) {
        $id = (int) $id;
        if ($id < 1) {
            $this->redirect(ADMIN_URL . '?action=hero_slides');
            return;
        }
        $row = $this->heroModel->find($id);
        if ($row && !empty($row['image_path'])) {
            $abs = PUBLIC_PATH . '/' . ltrim($row['image_path'], '/');
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
        $this->heroModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=hero_slides');
    }

    // ===== Alumni / Old Students =====

    public function alumni() {
        $rows = $this->alumniModel->allOrdered();
        $this->view('admin/alumni/index', ['alumni' => $rows]);
    }

    public function alumniForm($id = null) {
        $member = $id ? $this->alumniModel->find($id) : null;
        $this->view('admin/alumni/form', ['member' => $member]);
    }

    public function alumniSave() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            $this->redirect(ADMIN_URL . '?action=alumni');
            return;
        }

        $now = gmdate('Y-m-d H:i:s');
        $data = [
            'name' => $this->sanitize($name),
            'program' => $this->sanitize($_POST['program'] ?? ''),
            'graduation_year' => $this->sanitize($_POST['graduation_year'] ?? ''),
            'current_role' => $this->sanitize($_POST['current_role'] ?? ''),
            'location' => $this->sanitize($_POST['location'] ?? ''),
            'quote' => $this->sanitize($_POST['quote'] ?? ''),
            'bio' => htmlspecialchars(trim((string) ($_POST['bio'] ?? '')), ENT_QUOTES, 'UTF-8'),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_featured' => empty($_POST['is_featured']) ? 0 : 1,
            'updated_at' => $now,
        ];

        $photoPath = $this->handleAlumniPhotoUpload();
        if ($photoPath !== null) {
            $data['photo_path'] = $photoPath;
        }

        if ($id > 0) {
            $existing = $this->alumniModel->find($id);
            if ($existing && $photoPath === null && empty($_POST['_keep_photo'])) {
                // No new upload, keep existing path silently
            }
            $this->alumniModel->update($id, $data);
        } else {
            $data['created_at'] = $now;
            $id = (int) $this->alumniModel->create($data);
        }

        $this->redirect(ADMIN_URL . '?action=alumni_edit&id=' . max(1, $id));
    }

    public function alumniDelete($id) {
        $id = (int) $id;
        if ($id < 1) {
            $this->redirect(ADMIN_URL . '?action=alumni');
            return;
        }
        $row = $this->alumniModel->find($id);
        if ($row && !empty($row['photo_path'])) {
            $abs = PUBLIC_PATH . '/' . ltrim($row['photo_path'], '/');
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
        $this->alumniModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=alumni');
    }

    /**
     * Read the uploaded `photo` from the alumni form, write a normalized JPEG
     * into ALUMNI_UPLOAD_PATH, and return the web-relative path (or null when
     * no usable file was sent).
     */
    private function handleAlumniPhotoUpload() {
        if (empty($_FILES['photo']) || !is_array($_FILES['photo'])) {
            return null;
        }
        if (($_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }
        if (!is_uploaded_file($_FILES['photo']['tmp_name'])) {
            return null;
        }
        $tmp = $_FILES['photo']['tmp_name'];
        if (@getimagesize($tmp) === false) {
            return null;
        }
        if (!is_dir(ALUMNI_UPLOAD_PATH)) {
            @mkdir(ALUMNI_UPLOAD_PATH, 0755, true);
        }
        $basename = 'a_' . bin2hex(random_bytes(6)) . '.jpg';
        $absDest = rtrim(ALUMNI_UPLOAD_PATH, '/') . '/' . $basename;
        if (!ImageProcessor::toJpegMaxBytes($tmp, $absDest)) {
            return null;
        }
        return 'uploads/alumni/' . $basename;
    }

    // ===== Events =====

    public function events() {
        $rows = $this->eventModel->adminList();
        $this->view('admin/events/index', ['events' => $rows]);
    }

    public function eventForm($id = null) {
        $event = $id ? $this->eventModel->find($id) : null;
        $this->view('admin/events/form', ['event' => $event]);
    }

    public function eventSave() {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $title = trim((string) ($_POST['title'] ?? ''));
        if ($title === '') {
            $this->redirect(ADMIN_URL . '?action=events');
            return;
        }

        $slugInput = trim((string) ($_POST['slug'] ?? ''));
        $baseSlug = $slugInput !== '' ? EventItem::slugify($slugInput) : EventItem::slugify($title);
        $slug = $this->uniqueEventSlug($baseSlug, $id > 0 ? $id : null);

        $contentRaw = trim(ContentSanitizer::stripDataImageUris((string) ($_POST['content'] ?? '')));
        $contentSafe = ContentSanitizer::sanitizeProgramDetailHtml($contentRaw);

        $eventDate = trim((string) ($_POST['event_date'] ?? ''));
        $endDate = trim((string) ($_POST['end_date'] ?? ''));
        $eventDateNorm = $eventDate !== '' ? str_replace('T', ' ', $eventDate) : null;
        $endDateNorm = $endDate !== '' ? str_replace('T', ' ', $endDate) : null;

        $now = gmdate('Y-m-d H:i:s');
        $data = [
            'title' => $this->sanitize($title),
            'slug' => $slug,
            'summary' => $this->sanitize($_POST['summary'] ?? ''),
            'content' => $contentSafe,
            'event_date' => $eventDateNorm,
            'end_date' => $endDateNorm,
            'location' => $this->sanitize($_POST['location'] ?? ''),
            'is_published' => empty($_POST['is_published']) ? 0 : 1,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'updated_at' => $now,
        ];

        $coverPath = $this->handleEventCoverUpload();
        if ($coverPath !== null) {
            $data['cover_image'] = $coverPath;
        }

        if ($id > 0) {
            $this->eventModel->update($id, $data);
        } else {
            $data['created_at'] = $now;
            $id = (int) $this->eventModel->create($data);
        }

        $this->redirect(ADMIN_URL . '?action=event_edit&id=' . max(1, $id));
    }

    public function eventDelete($id) {
        $id = (int) $id;
        if ($id < 1) {
            $this->redirect(ADMIN_URL . '?action=events');
            return;
        }
        $row = $this->eventModel->find($id);
        if ($row && !empty($row['cover_image'])) {
            $abs = PUBLIC_PATH . '/' . ltrim($row['cover_image'], '/');
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
        $this->eventModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=events');
    }

    private function handleEventCoverUpload() {
        if (empty($_FILES['cover']) || !is_array($_FILES['cover'])) {
            return null;
        }
        if (($_FILES['cover']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }
        if (!is_uploaded_file($_FILES['cover']['tmp_name'])) {
            return null;
        }
        $tmp = $_FILES['cover']['tmp_name'];
        if (@getimagesize($tmp) === false) {
            return null;
        }
        if (!is_dir(EVENT_UPLOAD_PATH)) {
            @mkdir(EVENT_UPLOAD_PATH, 0755, true);
        }
        $basename = 'e_' . bin2hex(random_bytes(6)) . '.jpg';
        $absDest = rtrim(EVENT_UPLOAD_PATH, '/') . '/' . $basename;
        if (!ImageProcessor::toJpegMaxBytes($tmp, $absDest)) {
            return null;
        }
        return 'uploads/events/' . $basename;
    }

    private function uniqueEventSlug($base, $excludeId = null) {
        $slug = $base;
        $n = 2;
        $db = Database::getInstance();
        while (true) {
            $row = $db->fetchOne('SELECT id FROM events WHERE slug = ?', [$slug]);
            if (!$row) {
                return $slug;
            }
            if ($excludeId !== null && (int) $row['id'] === (int) $excludeId) {
                return $slug;
            }
            $slug = $base . '-' . $n++;
        }
    }

    /**
     * @param int|null $excludeProgramId When updating, exclude this program id from uniqueness check
     */
    private function uniqueProgramSlug($base, $excludeProgramId = null) {
        $slug = $base;
        $n = 2;
        $db = Database::getInstance();
        while (true) {
            $row = $db->fetchOne('SELECT id FROM programs WHERE slug = ?', [$slug]);
            if (!$row) {
                return $slug;
            }
            if ($excludeProgramId !== null && (int) $row['id'] === (int) $excludeProgramId) {
                return $slug;
            }
            $slug = $base . '-' . $n++;
        }
    }
}
