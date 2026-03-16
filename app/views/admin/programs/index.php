<?php ob_start(); ?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Programs</h1>
    <a href="<?= ADMIN_URL ?>?action=program_create" class="btn btn-primary">
        + Add Program
    </a>
</div>

<div class="card">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Department</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Description</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($programs)): ?>
            <tr>
                <td colspan="4" class="text-center py-8 text-gray-500">No programs found.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($programs as $program): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium"><?= htmlspecialchars($program['name']) ?></td>
                    <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($program['department']) ?></td>
                    <td class="py-3 px-4 text-gray-600 text-sm"><?= htmlspecialchars(substr($program['description'], 0, 60)) ?>...</td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="<?= ADMIN_URL ?>?action=program_edit&id=<?= $program['id'] ?>" class="text-primary-600 hover:text-primary-800 text-sm">Edit</a>
                        <a href="<?= ADMIN_URL ?>?action=program_delete&id=<?= $program['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 text-sm">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
$title = 'Programs';
require __DIR__ . '/../layout.php';
?>
