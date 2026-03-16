<?php ob_start(); ?>

<div class="mb-6">
    <a href="<?= ADMIN_URL ?>?action=programs" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Programs
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-3"><?= isset($program) ? 'Edit Program' : 'Add Program' ?></h1>
    <p class="mt-1 text-sm text-gray-500">Describe each academic or technical program clearly for prospective students.</p>
</div>

<div class="card max-w-4xl">
    <form method="POST" action="<?= ADMIN_URL ?>?action=program_save" class="space-y-6">
        <?php if (isset($program)): ?>
        <input type="hidden" name="id" value="<?= $program['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Program Name</label>
                <input type="text" name="name" required class="input" value="<?= htmlspecialchars($program['name'] ?? '') ?>" placeholder="Mechanical Engineering">
            </div>

            <div>
                <label class="label">Department</label>
                <input type="text" name="department" class="input" value="<?= htmlspecialchars($program['department'] ?? '') ?>" placeholder="Engineering, Construction, Technology">
            </div>
        </div>

        <div>
            <label class="label">Description</label>
            <textarea name="description" rows="8" class="input text-sm leading-relaxed" placeholder="Enter program description..."><?= htmlspecialchars($program['description'] ?? '') ?></textarea>
        </div>

        <div class="flex flex-wrap gap-3">
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
