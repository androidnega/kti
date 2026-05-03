<?php

require_once APP_PATH . '/models/Page.php';
require_once APP_PATH . '/models/Staff.php';
require_once APP_PATH . '/models/Program.php';
require_once APP_PATH . '/models/ProgramMedia.php';
require_once APP_PATH . '/helpers/ImageProcessor.php';

class AdminController extends BaseController {
    private $pageModel;
    private $staffModel;
    private $programModel;
    private $programMediaModel;

    public function __construct() {
        $this->pageModel = new Page();
        $this->staffModel = new Staff();
        $this->programModel = new Program();
        $this->programMediaModel = new ProgramMedia();
    }

    public function dashboard() {
        $stats = [
            'pages' => count($this->pageModel->all()),
            'staff' => count($this->staffModel->all()),
            'programs' => count($this->programModel->all()),
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
            'content' => $this->sanitize($_POST['content']),
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
        $this->view('admin/programs/index', ['programs' => $programs]);
    }

    public function programForm($id = null) {
        $program = $id ? $this->programModel->find($id) : null;
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
        $description = $this->sanitize($_POST['description'] ?? '');
        $detailRaw = trim((string) ($_POST['detail_content'] ?? ''));
        $detailContent = htmlspecialchars($detailRaw, ENT_QUOTES, 'UTF-8');
        $coverImage = trim((string) ($_POST['cover_image'] ?? ''));
        $coverImage = $coverImage !== '' ? htmlspecialchars(strip_tags($coverImage), ENT_QUOTES, 'UTF-8') : '';

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
        } else {
            $data['created_at'] = $now;
            $this->programModel->create($data);
        }

        $this->redirect(ADMIN_URL . '?action=programs');
    }

    public function programDelete($id) {
        $this->programModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=programs');
    }

    public function programMediaUpload() {
        $programId = (int) ($_POST['program_id'] ?? 0);
        if ($programId < 1 || !$this->programModel->find($programId)) {
            $this->json(['ok' => false, 'error' => 'Invalid program'], 400);
        }
        if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->json(['ok' => false, 'error' => 'No file uploaded'], 400);
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

    public function programVideoUpload() {
        $programId = (int) ($_POST['program_id'] ?? 0);
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
        $programId = (int) ($_POST['program_id'] ?? 0);
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

        $this->json(['ok' => true, 'id' => $mid]);
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
