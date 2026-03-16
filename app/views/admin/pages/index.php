<?php ob_start(); ?>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <p class="text-xs font-semibold tracking-widest text-primary-600 uppercase">Content Management</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Pages</h1>
        <p class="mt-1 text-sm text-gray-500">Manage all static pages displayed on the public website.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= ADMIN_URL ?>?action=page_create" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
            <i class="fa-solid fa-plus text-xs"></i>
            New Page
        </a>
        <a href="<?= APP_URL ?>" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
            <i class="fa-solid fa-up-right-from-square text-[11px]"></i>
            View site
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 bg-gray-50">
        <p class="text-sm font-medium text-gray-800 flex items-center gap-2">
            <i class="fa-regular fa-file-lines text-primary-600"></i>
            <span>All Pages</span>
        </p>
        <span class="rounded-full bg-white px-3 py-0.5 text-[11px] font-medium text-gray-700 border border-gray-200"><?= count($pages ?? []) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Title</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Slug</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Last Updated</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($pages)): ?>
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-500 text-sm">No pages found. Create your first page to get started.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($pages as $page): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded bg-primary-50 text-primary-600 text-xs font-semibold uppercase">
                                    <?= strtoupper(substr($page['title'], 0, 2)) ?>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($page['title']) ?></span>
                                    <?php if (in_array($page['slug'], ['home', 'about', 'contact'])): ?>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 mt-1">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Core page
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 font-mono text-[11px] text-gray-700">
                                /<?= htmlspecialchars($page['slug']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs">
                            <?= date('M d, Y', strtotime($page['updated_at'])) ?>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="<?= APP_URL ?>?url=<?= urlencode($page['slug']) ?>" target="_blank" class="text-xs font-medium text-gray-500 hover:text-gray-700">Preview</a>
                                <span class="text-gray-300">|</span>
                                <a href="<?= ADMIN_URL ?>?action=page_edit&id=<?= $page['id'] ?>" class="text-xs font-medium text-primary-600 hover:text-primary-800">Edit</a>
                                <button type="button" onclick="if(confirm('Are you sure you want to delete this page?')) { window.location='<?= ADMIN_URL ?>?action=page_delete&id=<?= $page['id'] ?>'; }" class="text-xs font-medium text-red-600 hover:text-red-800">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Pages';
require __DIR__ . '/../layout.php';
?>
