<?php ob_start(); ?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Pages</h1>
    <a href="<?= ADMIN_URL ?>?action=page_create" class="btn btn-primary">
        + Create Page
    </a>
</div>

<div class="card">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Title</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Slug</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Updated</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pages)): ?>
            <tr>
                <td colspan="4" class="text-center py-8 text-gray-500">No pages found. Create your first page!</td>
            </tr>
            <?php else: ?>
                <?php foreach ($pages as $page): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4"><?= htmlspecialchars($page['title']) ?></td>
                    <td class="py-3 px-4 text-gray-600">/<?= htmlspecialchars($page['slug']) ?></td>
                    <td class="py-3 px-4 text-gray-600 text-sm"><?= date('M d, Y', strtotime($page['updated_at'])) ?></td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="<?= APP_URL ?>?url=<?= urlencode($page['slug']) ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                        <a href="<?= ADMIN_URL ?>?action=page_edit&id=<?= $page['id'] ?>" class="text-primary-600 hover:text-primary-800 text-sm">Edit</a>
                        <a href="<?= ADMIN_URL ?>?action=page_delete&id=<?= $page['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 text-sm">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
$title = 'Pages';
require __DIR__ . '/../layout.php';
?>
