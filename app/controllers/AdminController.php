<?php

require_once APP_PATH . '/models/Page.php';
require_once APP_PATH . '/models/Staff.php';
require_once APP_PATH . '/models/Program.php';

class AdminController extends BaseController {
    private $pageModel;
    private $staffModel;
    private $programModel;

    public function __construct() {
        $this->pageModel = new Page();
        $this->staffModel = new Staff();
        $this->programModel = new Program();
    }

    public function dashboard() {
        $stats = [
            'pages' => count($this->pageModel->all()),
            'staff' => count($this->staffModel->all()),
            'programs' => count($this->programModel->all())
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
            'content' => $this->sanitize($_POST['content'])
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
            'rank' => $this->sanitize($_POST['rank'])
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
        $programs = $this->programModel->all();
        $this->view('admin/programs/index', ['programs' => $programs]);
    }

    public function programForm($id = null) {
        $program = $id ? $this->programModel->find($id) : null;
        $this->view('admin/programs/form', ['program' => $program]);
    }

    public function programSave() {
        $data = [
            'name' => $this->sanitize($_POST['name']),
            'department' => $this->sanitize($_POST['department']),
            'description' => $this->sanitize($_POST['description'])
        ];

        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->programModel->update($id, $data);
        } else {
            $this->programModel->create($data);
        }

        $this->redirect(ADMIN_URL . '?action=programs');
    }

    public function programDelete($id) {
        $this->programModel->delete($id);
        $this->redirect(ADMIN_URL . '?action=programs');
    }
}
