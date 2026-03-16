<?php ob_start(); ?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Staff</h1>
    <a href="<?= ADMIN_URL ?>?action=staff_create" class="btn btn-primary">
        + Add Staff Member
    </a>
</div>

<div class="card">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Department</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Role</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Rank</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($staff)): ?>
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-500">No staff members found.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($staff as $member): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium"><?= htmlspecialchars($member['name']) ?></td>
                    <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($member['department']) ?></td>
                    <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($member['role']) ?></td>
                    <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($member['rank']) ?></td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="<?= ADMIN_URL ?>?action=staff_edit&id=<?= $member['id'] ?>" class="text-primary-600 hover:text-primary-800 text-sm">Edit</a>
                        <a href="<?= ADMIN_URL ?>?action=staff_delete&id=<?= $member['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-800 text-sm">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
$title = 'Staff';
require __DIR__ . '/../layout.php';
?>
