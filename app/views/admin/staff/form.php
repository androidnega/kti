<?php ob_start(); ?>

<div class="mb-8">
    <a href="<?= ADMIN_URL ?>?action=staff" class="text-primary-600 hover:text-primary-800">← Back to Staff</a>
    <h1 class="text-3xl font-bold text-gray-900 mt-4"><?= isset($member) ? 'Edit Staff Member' : 'Add Staff Member' ?></h1>
</div>

<div class="card max-w-3xl">
    <form method="POST" action="<?= ADMIN_URL ?>?action=staff_save" class="space-y-6">
        <?php if (isset($member)): ?>
        <input type="hidden" name="id" value="<?= $member['id'] ?>">
        <?php endif; ?>

        <div>
            <label class="label">Full Name</label>
            <input type="text" name="name" required class="input" value="<?= htmlspecialchars($member['name'] ?? '') ?>" placeholder="John Doe">
        </div>

        <div>
            <label class="label">Department</label>
            <input type="text" name="department" class="input" value="<?= htmlspecialchars($member['department'] ?? '') ?>" placeholder="Computer Science">
        </div>

        <div>
            <label class="label">Role</label>
            <input type="text" name="role" class="input" value="<?= htmlspecialchars($member['role'] ?? '') ?>" placeholder="Lecturer">
        </div>

        <div>
            <label class="label">Rank</label>
            <input type="text" name="rank" class="input" value="<?= htmlspecialchars($member['rank'] ?? '') ?>" placeholder="Senior">
        </div>

        <div class="flex gap-4">
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
