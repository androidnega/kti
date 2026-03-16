<?php ob_start(); ?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600 mt-2">Welcome back, <?= Auth::user()['name'] ?>!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Pages</p>
                <p class="text-4xl font-bold mt-2"><?= $stats['pages'] ?></p>
            </div>
            <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Staff Members</p>
                <p class="text-4xl font-bold mt-2"><?= $stats['staff'] ?></p>
            </div>
            <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Programs</p>
                <p class="text-4xl font-bold mt-2"><?= $stats['programs'] ?></p>
            </div>
            <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="space-y-3">
            <a href="<?= ADMIN_URL ?>?action=page_create" class="btn btn-primary w-full justify-center flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Page
            </a>
            <a href="<?= ADMIN_URL ?>?action=staff_create" class="btn btn-secondary w-full justify-center flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Staff Member
            </a>
            <a href="<?= ADMIN_URL ?>?action=program_create" class="btn btn-secondary w-full justify-center flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Program
            </a>
        </div>
    </div>

    <div class="card">
        <h2 class="text-xl font-semibold mb-4">System Information</h2>
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-gray-600">PHP Version</dt>
                <dd class="font-medium"><?= phpversion() ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-600">Database</dt>
                <dd class="font-medium">SQLite</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-600">Environment</dt>
                <dd class="font-medium"><?= ENVIRONMENT ?></dd>
            </div>
        </dl>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/layout.php';
?>
