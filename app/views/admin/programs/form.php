<?php ob_start(); ?>

<div class="mb-8">
    <a href="<?= ADMIN_URL ?>?action=programs" class="text-primary-600 hover:text-primary-800">← Back to Programs</a>
    <h1 class="text-3xl font-bold text-gray-900 mt-4"><?= isset($program) ? 'Edit Program' : 'Add Program' ?></h1>
</div>

<div class="card max-w-3xl">
    <form method="POST" action="<?= ADMIN_URL ?>?action=program_save" class="space-y-6">
        <?php if (isset($program)): ?>
        <input type="hidden" name="id" value="<?= $program['id'] ?>">
        <?php endif; ?>

        <div>
            <label class="label">Program Name</label>
            <input type="text" name="name" required class="input" value="<?= htmlspecialchars($program['name'] ?? '') ?>" placeholder="Computer Science">
        </div>

        <div>
            <label class="label">Department</label>
            <input type="text" name="department" class="input" value="<?= htmlspecialchars($program['department'] ?? '') ?>" placeholder="Technology">
        </div>

        <div>
            <label class="label">Description</label>
            <textarea name="description" rows="6" class="input" placeholder="Enter program description..."><?= htmlspecialchars($program['description'] ?? '') ?></textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn btn-primary">
                <?= isset($program) ? 'Update Program' : 'Add Program' ?>
            </button>
            <a href="<?= ADMIN_URL ?>?action=programs" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$title = isset($program) ? 'Edit Program' : 'Add Program';
require __DIR__ . '/../layout.php';
?>
