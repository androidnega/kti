<?php ob_start(); ?>

<div class="mb-6">
    <a href="<?= ADMIN_URL ?>?action=pages" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Pages
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-3"><?= isset($page) ? 'Edit Page' : 'Create Page' ?></h1>
    <p class="mt-1 text-sm text-gray-500">Define content and URL for a static website page.</p>
</div>

<div class="card max-w-4xl">
    <form method="POST" action="<?= ADMIN_URL ?>?action=page_save" class="space-y-6">
        <?php if (isset($page)): ?>
        <input type="hidden" name="id" value="<?= $page['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="label">Page Title</label>
                <input type="text" name="title" required class="input" value="<?= htmlspecialchars($page['title'] ?? '') ?>" placeholder="About Us">
            </div>

            <div>
                <label class="label">Slug (URL)</label>
                <input type="text" name="slug" required class="input" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" placeholder="about-us" pattern="[a-z0-9\-]+">
                <p class="text-xs text-gray-500 mt-1">Lowercase, numbers, and hyphens only (e.g. <code>about-us</code>).</p>
            </div>
        </div>

        <div>
            <label class="label">Content</label>
            <textarea name="content" rows="12" class="input font-mono text-xs leading-relaxed" placeholder="Enter page content..."><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
            <p class="mt-1 text-[11px] text-gray-500">You can paste rich text; this field stores HTML/plain content as is.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn btn-primary">
                <?= isset($page) ? 'Update Page' : 'Create Page' ?>
            </button>
            <a href="<?= ADMIN_URL ?>?action=pages" class="btn btn-secondary">Cancel</a>
            <?php if (isset($page)): ?>
                <a href="<?= APP_URL ?>?url=<?= urlencode($page['slug']) ?>" target="_blank" class="btn btn-secondary">
                    Preview Page
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$title = isset($page) ? 'Edit Page' : 'Create Page';
require __DIR__ . '/../layout.php';
?>
