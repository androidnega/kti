<?php ob_start(); ?>

<div class="mb-6">
    <a href="<?= ADMIN_URL ?>?action=staff" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Staff
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-3"><?= isset($member) ? 'Edit Staff Member' : 'Add Staff Member' ?></h1>
    <p class="mt-1 text-sm text-gray-500">Maintain accurate details for academic and administrative staff.</p>
</div>

<div class="card max-w-4xl">
    <form method="POST" action="<?= ADMIN_URL ?>?action=staff_save" class="space-y-6">
        <?php if (isset($member)): ?>
        <input type="hidden" name="id" value="<?= $member['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Full Name</label>
                <input type="text" name="name" required class="input" value="<?= htmlspecialchars($member['name'] ?? '') ?>" placeholder="John Doe">
            </div>

            <div>
                <label class="label">Department</label>
                <input type="text" name="department" class="input" value="<?= htmlspecialchars($member['department'] ?? '') ?>" placeholder="Mechanical Engineering">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Role</label>
                <input type="text" name="role" class="input" value="<?= htmlspecialchars($member['role'] ?? '') ?>" placeholder="Lecturer, Tutor, Administrator">
            </div>

            <div>
                <label class="label">Rank</label>
                <input type="text" name="rank" class="input" value="<?= htmlspecialchars($member['rank'] ?? '') ?>" placeholder="Senior, Principal, Assistant">
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn btn-primary">
                <?= isset($member) ? 'Update Staff Member' : 'Add Staff Member' ?>
            </button>
            <a href="<?= ADMIN_URL ?>?action=staff" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$title = isset($member) ? 'Edit Staff Member' : 'Add Staff Member';
require __DIR__ . '/../layout.php';
?>
