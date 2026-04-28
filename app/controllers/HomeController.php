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
        $programs = $this->programModel->all();
        $this->view('home', [
            'programs' => array_slice($programs, 0, 3) // Show top 3 programs
        ]);
    }

    public function programs() {
        $departments = $this->programModel->getAllDepartments();
        $programsByDept = [];
        
        foreach ($departments as $dept) {
            $deptName = $dept['department'];
            $programsByDept[$deptName] = $this->programModel->getByDepartment($deptName);
        }
        
        $this->view('programs', [
            'departments' => $departments,
            'programsByDept' => $programsByDept
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
