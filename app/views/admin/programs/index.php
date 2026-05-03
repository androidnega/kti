<?php ob_start(); ?>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <p class="text-xs font-semibold tracking-widest text-primary-600 uppercase">Academics</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Programs</h1>
        <p class="mt-1 text-sm text-gray-500">Configure and maintain all academic and technical programs.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= ADMIN_URL ?>?action=program_create" class="inline-flex items-center gap-2 rounded-lg bg-primary-900 px-4 py-2 text-sm font-medium text-white hover:bg-black">
            <i class="fa-solid fa-plus text-xs"></i>
            Add Program
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 bg-gray-50">
        <p class="text-sm font-medium text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-primary-600"></i>
            <span>All Programs</span>
        </p>
        <span class="rounded-full bg-white px-3 py-0.5 text-[11px] font-medium text-gray-700 border border-gray-200"><?= count($programs ?? []) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600 w-14"></th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Name</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Slug</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Faculty</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Department</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Description</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($programs)): ?>
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500 text-sm">No programs found. Add your first program.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($programs as $program): ?>
                    <?php
                    $slug = trim((string) ($program['slug'] ?? ''));
                    $coverPath = trim((string) ($program['cover_image'] ?? ''));
                    $coverThumb = $coverPath !== '' ? rtrim(APP_URL, '/') . '/' . ltrim($coverPath, '/') : '';
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 align-middle">
                            <?php if ($coverThumb !== ''): ?>
                            <img src="<?= htmlspecialchars($coverThumb) ?>" alt="" class="h-10 w-10 rounded-lg border border-gray-200 object-cover" width="40" height="40" loading="lazy">
                            <?php else: ?>
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-100 bg-primary-50 text-primary-700 text-[10px] font-bold uppercase" title="No cover yet"><?= strtoupper(substr((string) ($program['name'] ?? '?'), 0, 2)) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
            <div class="flex flex-col">
                    <span class="font-medium text-gray-900"><?= htmlspecialchars($program['name']) ?></span>
                </div>
        </td>
                        <td class="py-3 px-4 text-gray-600 font-mono text-xs"><?= $slug !== '' ? htmlspecialchars($slug) : '—' ?></td>
                        <td class="py-3 px-4 text-gray-600">
                            <?php if (!empty($program['faculty'])): ?>
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700">
                                <?= htmlspecialchars($program['faculty']) ?>
                            </span>
                            <?php else: ?>
                            <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-gray-600">
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700">
                                <i class="fa-solid fa-layer-group text-[10px] text-primary-500"></i>
                                <?= htmlspecialchars($program['department'] ?? '') ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs"><?= htmlspecialchars(strlen($program['description'] ?? '') > 80 ? substr($program['description'], 0, 77) . '...' : ($program['description'] ?? '')) ?></td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex flex-wrap items-center justify-end gap-2">
                                <?php if ($slug !== ''): ?>
                                <a href="<?= htmlspecialchars(APP_URL) ?>?url=program/<?= rawurlencode($slug) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-secondary-600 hover:text-secondary-800">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    View on site
                                </a>
                                <?php endif; ?>
                                <a href="<?= ADMIN_URL ?>?action=program_edit&id=<?= $program['id'] ?>" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-800">
                                    <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                    Edit
                                </a>
                                <button type="button" onclick="if(confirm('Are you sure you want to delete this program?')) { window.location='<?= ADMIN_URL ?>?action=program_delete&id=<?= $program['id'] ?>'; }" class="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:text-red-800">
                                    <i class="fa-solid fa-trash-can text-[11px]"></i>
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
$title = 'Programs';
require __DIR__ . '/../layout.php';
?>
