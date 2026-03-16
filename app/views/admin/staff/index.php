<?php ob_start(); ?>

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <p class="text-xs font-semibold tracking-widest text-primary-600 uppercase">People</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Staff</h1>
        <p class="mt-1 text-sm text-gray-500">Manage teaching and non-teaching staff profiles.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= ADMIN_URL ?>?action=staff_create" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Staff Member
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
        <p class="text-sm font-medium text-gray-700">All Staff</p>
        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600"><?= count($staff ?? []) ?> total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Name</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Department</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Role</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Rank</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($staff)): ?>
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500 text-sm">No staff members found. Add your first staff profile.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($staff as $member): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900"><?= htmlspecialchars($member['name']) ?></td>
                        <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($member['department']) ?></td>
                        <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($member['role']) ?></td>
                        <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($member['rank']) ?></td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="<?= ADMIN_URL ?>?action=staff_edit&id=<?= $member['id'] ?>" class="text-xs font-medium text-primary-600 hover:text-primary-800">Edit</a>
                                <button type="button" onclick="if(confirm('Are you sure you want to remove this staff member?')) { window.location='<?= ADMIN_URL ?>?action=staff_delete&id=<?= $member['id'] ?>'; }" class="text-xs font-medium text-red-600 hover:text-red-800">
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
$title = 'Staff';
require __DIR__ . '/../layout.php';
?>
