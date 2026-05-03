<?php

require_once APP_PATH . '/models/Page.php';
require_once APP_PATH . '/models/Program.php';
require_once APP_PATH . '/models/Staff.php';
require_once APP_PATH . '/helpers/YoutubeFeed.php';

class HomeController extends BaseController {
    private $pageModel;
    private $programModel;
    private $staffModel;

    public function __construct() {
        $this->pageModel = new Page();
        $this->programModel = new Program();
        $this->staffModel = new Staff();
    }

    public function index() {
        $programs = $this->programModel->allOrdered();
        foreach ($programs as &$p) {
            $this->programModel->enrichProgramCover($p);
        }
        unset($p);
        $this->view('home', [
            'programs' => array_slice($programs, 0, 3),
        ]);
    }

    public function programs() {
        $facultyRows = $this->programModel->getAllFaculties();
        $programs = $this->programModel->allOrdered();
        foreach ($programs as &$p) {
            $this->programModel->enrichProgramCover($p);
        }
        unset($p);
        $programsByFaculty = [];

        foreach ($programs as $program) {
            $faculty = !empty($program['faculty']) ? $program['faculty'] : ($program['department'] ?? 'General');
            if (!isset($programsByFaculty[$faculty])) {
                $programsByFaculty[$faculty] = [];
            }
            $programsByFaculty[$faculty][] = $program;
        }

        $this->view('programs', [
            'faculties' => $facultyRows,
            'programsByFaculty' => $programsByFaculty,
        ]);
    }

    public function programDetail($slug) {
        $slug = trim((string) $slug);
        $program = $this->programModel->findBySlug($slug);
        if (!$program) {
            http_response_code(404);
            echo '<h1>Program not found</h1><p><a href="' . htmlspecialchars(APP_URL) . '?url=programs">Back to programs</a></p>';
            return;
        }

        require_once APP_PATH . '/helpers/ContentSanitizer.php';
        require_once APP_PATH . '/models/ProgramMedia.php';
        $mediaModel = new ProgramMedia();
        $media = $mediaModel->forProgram((int) $program['id']);
        $this->programModel->enrichProgramCover($program);

        $this->view('program_detail', [
            'program' => $program,
            'media' => $media,
        ]);
    }

    public function staff() {
        $departments = $this->staffModel->getAllDepartments();
        $staffByDept = [];
        
        foreach ($departments as $dept) {
            $deptName = $dept['department'];
            $staffByDept[$deptName] = $this->staffModel->getByDepartment($deptName);
        }
        
        $this->view('staff', [
            'departments' => $departments,
            'staffByDept' => $staffByDept
        ]);
    }

    public function history() {
        $this->view('history');
    }

    public function videos() {
        $curated = YoutubeFeed::buildCuratedVideos();
        if (count($curated) > 0) {
            $this->view('videos', [
                'videos' => $curated,
                'feedError' => null,
                'videoSource' => 'curated',
            ]);
            return;
        }

        list($videos, $feedError) = YoutubeFeed::fetchChannelVideos();
        $this->view('videos', [
            'videos' => $videos,
            'feedError' => $feedError,
            'videoSource' => 'feed',
        ]);
    }

    public function contact() {
        $this->view('contact');
    }

    public function page($slug) {
        $page = $this->pageModel->findBySlug($slug);
        
        if (!$page) {
            http_response_code(404);
            echo "<h1>Page Not Found</h1>";
            return;
        }

        $sections = $this->pageModel->getSections($page['id']);
        
        $this->view('page', [
            'page' => $page,
            'sections' => $sections
        ]);
    }
}
