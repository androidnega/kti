<?php ob_start(); ?>

<article class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            <?= htmlspecialchars($page['title']) ?>
        </h1>
        
        <?php if (!empty($page['content'])): ?>
        <div class="prose prose-lg max-w-none mb-12">
            <?= nl2br(htmlspecialchars($page['content'])) ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($sections)): ?>
        <div class="space-y-12">
            <?php foreach ($sections as $section): ?>
            <section class="card">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <?= htmlspecialchars($section['section_title']) ?>
                </h2>
                <div class="prose max-w-none text-gray-600">
                    <?= nl2br(htmlspecialchars($section['section_content'])) ?>
                </div>
            </section>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</article>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($page['title']) . ' - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
