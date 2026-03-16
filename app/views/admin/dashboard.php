<?php ob_start(); ?>

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <p class="text-xs font-semibold tracking-widest text-primary-600 uppercase">Admin Overview</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Welcome back, <span class="font-semibold text-gray-800"><?= Auth::user()['name'] ?></span>. Here’s what’s happening in your system.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= ADMIN_URL ?>?action=page_create" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
            <i class="fa-solid fa-plus text-xs"></i>
            New Page
        </a>
        <a href="<?= ADMIN_URL ?>?action=staff_create" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <i class="fa-solid fa-user-plus text-xs"></i>
            Add Staff
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="card bg-primary-600 text-white border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-primary-100 text-xs font-medium uppercase tracking-wide">Total Pages</p>
                <p class="text-4xl font-bold mt-3 leading-none"><?= $stats['pages'] ?></p>
                <p class="mt-2 text-xs text-primary-100">Manage static and dynamic content</p>
            </div>
            <div class="w-10 h-10 rounded bg-primary-700/60 flex items-center justify-center">
                <i class="fa-solid fa-file-lines text-lg"></i>
            </div>
        </div>
    </div>

    <div class="card bg-emerald-500 text-white border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-xs font-medium uppercase tracking-wide">Staff Members</p>
                <p class="text-4xl font-bold mt-3 leading-none"><?= $stats['staff'] ?></p>
                <p class="mt-2 text-xs text-emerald-100">Academic and non-teaching staff</p>
            </div>
            <div class="w-10 h-10 rounded bg-emerald-600/70 flex items-center justify-center">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
        </div>
    </div>

    <div class="card bg-indigo-500 text-white border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-xs font-medium uppercase tracking-wide">Programs</p>
                <p class="text-4xl font-bold mt-3 leading-none"><?= $stats['programs'] ?></p>
                <p class="mt-2 text-xs text-indigo-100">Active technical and vocational programs</p>
            </div>
            <div class="w-10 h-10 rounded bg-indigo-600/70 flex items-center justify-center">
                <i class="fa-solid fa-chalkboard-user text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <span>Quick Actions</span>
                <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-0.5 text-[11px] font-medium text-primary-700">Fast access</span>
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="<?= ADMIN_URL ?>?action=page_create" class="group rounded-xl border border-gray-200 bg-white p-4 hover:border-primary-200 hover:shadow-sm transition flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center justify-center rounded-lg bg-primary-50 text-primary-700 w-9 h-9 mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">Create New Page</h3>
                    <p class="mt-1 text-xs text-gray-500">Add or update website content quickly.</p>
                </div>
                <span class="mt-3 text-xs font-medium text-primary-600 group-hover:text-primary-700 inline-flex items-center">
                    Open builder
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </a>

            <a href="<?= ADMIN_URL ?>?action=staff_create" class="group rounded-xl border border-gray-200 bg-white p-4 hover:border-emerald-200 hover:shadow-sm transition flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 w-9 h-9 mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">Add Staff Member</h3>
                    <p class="mt-1 text-xs text-gray-500">Maintain an up-to-date staff directory.</p>
                </div>
                <span class="mt-3 text-xs font-medium text-emerald-600 group-hover:text-emerald-700 inline-flex items-center">
                    Manage staff
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </a>

            <a href="<?= ADMIN_URL ?>?action=program_create" class="group rounded-xl border border-gray-200 bg-white p-4 hover:border-indigo-200 hover:shadow-sm transition flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-700 w-9 h-9 mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">Add Program</h3>
                    <p class="mt-1 text-xs text-gray-500">Update academic and technical offerings.</p>
                </div>
                <span class="mt-3 text-xs font-medium text-indigo-600 group-hover:text-indigo-700 inline-flex items-center">
                    Configure programs
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <div class="card">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 flex items-center justify-between">
            <span>System Information</span>
            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600">Status</span>
        </h2>
        <dl class="space-y-3">
            <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-3 py-2.5">
                <dt class="text-xs font-medium text-gray-500">PHP Version</dt>
                <dd class="text-sm font-semibold text-gray-900"><?= phpversion() ?></dd>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-3 py-2.5">
                <dt class="text-xs font-medium text-gray-500">Database</dt>
                <dd class="text-sm font-semibold text-gray-900">SQLite</dd>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-3 py-2.5">
                <dt class="text-xs font-medium text-gray-500">Environment</dt>
                <dd class="inline-flex items-center gap-1 text-xs font-semibold <?= ENVIRONMENT === 'production' ? 'text-emerald-700' : 'text-amber-700' ?>">
                    <span class="inline-block h-2 w-2 rounded-full <?= ENVIRONMENT === 'production' ? 'bg-emerald-500' : 'bg-amber-400' ?>"></span>
                    <?= ucfirst(ENVIRONMENT) ?>
                </dd>
            </div>
        </dl>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/layout.php';
?>
