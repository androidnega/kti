<?php ob_start(); ?>

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <p class="text-xs font-semibold tracking-widest uppercase text-primary-600">Community</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Old Students</h1>
        <p class="mt-1 text-sm text-gray-500">Add Kikam alumni profiles. Upload a photo and a short story for each one.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= ADMIN_URL ?>?action=alumni_create" class="inline-flex items-center gap-2 rounded-lg bg-primary-900 px-4 py-2 text-sm font-medium text-white hover:bg-black">
            <i class="fa-solid fa-plus text-xs"></i>
            Add alumnus
        </a>
        <a href="<?= APP_URL ?>?url=alumni" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
            <i class="fa-solid fa-up-right-from-square text-[11px]"></i>
            View page
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 bg-gray-50">
        <p class="text-sm font-medium text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-user-group text-primary-600"></i>
            <span>All alumni</span>
        </p>
        <span class="rounded-full bg-white px-3 py-0.5 text-[11px] font-medium text-gray-700 border border-gray-200"><?= count($alumni ?? []) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Person</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Program</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Year</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Featured</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($alumni)): ?>
                <tr>
                    <td colspan="5" class="py-10 text-center text-sm text-gray-500">No alumni yet. Add the first one to get started.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($alumni as $a):
                        $photo = !empty($a['photo_path']) ? rtrim(APP_URL, '/') . '/' . ltrim($a['photo_path'], '/') : '';
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-primary-50 text-xs font-semibold text-primary-700">
                                    <?php if ($photo): ?>
                                        <img src="<?= htmlspecialchars($photo) ?>" alt="" class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <?= strtoupper(mb_substr($a['name'] ?? '?', 0, 2)) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($a['name'] ?? '') ?></span>
                                    <?php if (!empty($a['current_role'])): ?>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($a['current_role']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs"><?= htmlspecialchars($a['program'] ?? '') ?></td>
                        <td class="py-3 px-4 text-gray-600 text-xs"><?= htmlspecialchars($a['graduation_year'] ?? '') ?></td>
                        <td class="py-3 px-4 text-xs">
                            <?php if (!empty($a['is_featured'])): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-accent-100 px-2 py-0.5 text-[11px] font-semibold text-accent-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-accent-500"></span>
                                    Featured
                                </span>
                            <?php else: ?>
                                <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="<?= ADMIN_URL ?>?action=alumni_edit&id=<?= (int) $a['id'] ?>" class="text-xs font-medium text-primary-600 hover:text-primary-800">Edit</a>
                                <button type="button" onclick="if(confirm('Delete this alumni record?')) { window.location='<?= ADMIN_URL ?>?action=alumni_delete&id=<?= (int) $a['id'] ?>'; }" class="text-xs font-medium text-red-600 hover:text-red-800">Delete</button>
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
$title = 'Old Students';
require __DIR__ . '/../layout.php';
?>
