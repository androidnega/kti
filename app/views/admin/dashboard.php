<?php ob_start(); ?>

<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-600">Signed in as <span class="font-medium text-slate-800"><?= htmlspecialchars(Auth::user()['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span></p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="<?= ADMIN_URL ?>?action=page_create" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-800 hover:border-slate-400 hover:bg-slate-50">
            <i class="fa-solid fa-plus text-xs text-slate-500"></i>
            New page
        </a>
        <a href="<?= ADMIN_URL ?>?action=staff_create" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-800 hover:border-slate-400 hover:bg-slate-50">
            <i class="fa-solid fa-user-plus text-xs text-slate-500"></i>
            Add staff
        </a>
    </div>
</div>

<div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white px-5 py-4">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Pages</p>
        <p class="mt-2 text-3xl font-semibold tabular-nums text-slate-900"><?= (int) $stats['pages'] ?></p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white px-5 py-4">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Staff</p>
        <p class="mt-2 text-3xl font-semibold tabular-nums text-slate-900"><?= (int) $stats['staff'] ?></p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white px-5 py-4">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Programs</p>
        <p class="mt-2 text-3xl font-semibold tabular-nums text-slate-900"><?= (int) $stats['programs'] ?></p>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white p-5 lg:col-span-2">
        <h2 class="text-sm font-semibold text-slate-900">Quick links</h2>
        <ul class="mt-4 divide-y divide-slate-100 border-t border-slate-100">
            <li>
                <a href="<?= ADMIN_URL ?>?action=page_create" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-700 hover:text-slate-900">
                    <span>Create page</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_URL ?>?action=staff_create" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-700 hover:text-slate-900">
                    <span>Add staff member</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_URL ?>?action=program_create" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-700 hover:text-slate-900">
                    <span>Add program</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_URL ?>?action=programs" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-700 hover:text-slate-900">
                    <span>Manage programs</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5">
        <h2 class="text-sm font-semibold text-slate-900">System</h2>
        <dl class="mt-4 space-y-3 text-sm">
            <div class="flex justify-between gap-2 border-b border-slate-100 pb-3">
                <dt class="text-slate-500">PHP</dt>
                <dd class="font-medium text-slate-900"><?= htmlspecialchars(phpversion(), ENT_QUOTES, 'UTF-8') ?></dd>
            </div>
            <div class="flex justify-between gap-2 border-b border-slate-100 pb-3">
                <dt class="text-slate-500">Database</dt>
                <dd class="font-medium text-slate-900">SQLite</dd>
            </div>
            <div class="flex justify-between gap-2">
                <dt class="text-slate-500">Environment</dt>
                <dd class="font-medium text-slate-900"><?= htmlspecialchars(ucfirst(ENVIRONMENT), ENT_QUOTES, 'UTF-8') ?></dd>
            </div>
        </dl>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/layout.php';
?>
