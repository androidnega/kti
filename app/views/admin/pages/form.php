<?php ob_start(); ?>

<div class="mb-8">
    <a href="<?= ADMIN_URL ?>?action=pages" class="text-primary-600 hover:text-primary-800">← Back to Pages</a>
    <h1 class="text-3xl font-bold text-gray-900 mt-4"><?= isset($page) ? 'Edit Page' : 'Create Page' ?></h1>
</div>

<div class="card max-w-3xl">
    <form method="POST" action="<?= ADMIN_URL ?>?action=page_save" class="space-y-6">
        <?php if (isset($page)): ?>
        <input type="hidden" name="id" value="<?= $page['id'] ?>">
        <?php endif; ?>

        <div>
            <label class="label">Page Title</label>
            <input type="text" name="title" required class="input" value="<?= htmlspecialchars($page['title'] ?? '') ?>" placeholder="About Us">
        </div>

        <div>
            <label class="label">Slug (URL)</label>
            <input type="text" name="slug" required class="input" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" placeholder="about-us" pattern="[a-z0-9\-]+">
            <p class="text-sm text-gray-500 mt-1">Use lowercase letters, numbers, and hyphens only</p>
        </div>

        <div>
            <label class="label">Content</label>
            <textarea name="content" rows="10" class="input" placeholder="Enter page content..."><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn btn-primary">
                <?= isset($page) ? 'Update Page' : 'Create Page' ?>
            </button>
            <a href="<?= ADMIN_URL ?>?action=pages" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$title = isset($page) ? 'Edit Page' : 'Create Page';
require __DIR__ . '/../layout.php';
?>
