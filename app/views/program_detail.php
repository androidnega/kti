<?php
$cover = !empty($program['cover_image']) ? APP_URL . '/' . ltrim($program['cover_image'], '/') : APP_URL . '/assets/images/vocational.jpg';
$faculty = !empty($program['faculty']) ? $program['faculty'] : ($program['department'] ?? '');
ob_start();
?>

<section class="relative bg-primary-900 text-white py-16 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-55 z-0"></div>
    <div class="absolute inset-0 bg-cover bg-center mix-blend-overlay z-0" style="background-image: url('<?= htmlspecialchars($cover) ?>');"></div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <nav class="text-sm text-primary-100 mb-4">
            <a href="<?= APP_URL ?>?url=programs" class="hover:text-white underline-offset-2 hover:underline">Programs</a>
            <span class="mx-2 opacity-70">/</span>
            <span class="text-white font-medium"><?= htmlspecialchars($program['name']) ?></span>
        </nav>
        <?php if ($faculty !== ''): ?>
            <p class="text-sm font-semibold tracking-wide text-accent-400 uppercase mb-2"><?= htmlspecialchars($faculty) ?></p>
        <?php endif; ?>
        <h1 class="text-3xl md:text-4xl font-bold mb-4"><?= htmlspecialchars($program['name']) ?></h1>
        <p class="text-lg text-primary-100 max-w-3xl leading-relaxed">
            <?= nl2br(htmlspecialchars($program['description'] ?? '')) ?>
        </p>
    </div>
</section>

<section class="py-14 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-neutral max-w-none">
        <?php if (!empty($program['detail_content'])): ?>
            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                <?= nl2br(htmlspecialchars($program['detail_content'])) ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
$images = [];
$videos = [];
foreach ($media as $m) {
    if (($m['media_type'] ?? '') === 'video') {
        $videos[] = $m;
    } else {
        $images[] = $m;
    }
}
?>

<?php if (!empty($images)): ?>
<section class="py-14 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-primary-900 mb-8">Gallery</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($images as $img): ?>
                <?php
                $src = !empty($img['file_path']) ? APP_URL . '/' . ltrim($img['file_path'], '/') : '';
                if ($src === '') {
                    continue;
                }
                ?>
                <a href="<?= htmlspecialchars($src) ?>" target="_blank" rel="noopener" class="group block rounded-xl overflow-hidden border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                        <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($img['caption'] ?: $program['name']) ?>" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300" loading="lazy">
                    </div>
                    <?php if (!empty($img['caption'])): ?>
                        <p class="p-3 text-sm text-gray-600"><?= htmlspecialchars($img['caption']) ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($videos)): ?>
<section class="py-14 bg-white border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-primary-900 mb-8">Videos</h2>
        <div class="space-y-10">
            <?php foreach ($videos as $vid): ?>
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-black">
                    <?php if (!empty($vid['external_url'])): ?>
                        <?php
                        $eu = $vid['external_url'];
                        $embed = null;
                        if (preg_match('~youtube\\.com/watch\\?v=([a-zA-Z0-9_-]+)~', $eu, $m) || preg_match('~youtu\\.be/([a-zA-Z0-9_-]+)~', $eu, $m)) {
                            $embed = 'https://www.youtube.com/embed/' . $m[1];
                        }
                        ?>
                        <?php if ($embed): ?>
                            <div class="aspect-video">
                                <iframe class="w-full h-full" src="<?= htmlspecialchars($embed) ?>" title="Video" allowfullscreen loading="lazy"></iframe>
                            </div>
                        <?php else: ?>
                            <div class="p-6 text-center text-white">
                                <a href="<?= htmlspecialchars($eu) ?>" class="text-accent-400 underline" target="_blank" rel="noopener">Open video link</a>
                            </div>
                        <?php endif; ?>
                    <?php elseif (!empty($vid['file_path'])): ?>
                        <?php $vsrc = APP_URL . '/' . ltrim($vid['file_path'], '/'); ?>
                        <video class="w-full max-h-[480px]" controls preload="metadata" playsinline>
                            <source src="<?= htmlspecialchars($vsrc) ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                    <?php if (!empty($vid['caption'])): ?>
                        <p class="p-3 text-sm text-gray-200 bg-primary-900"><?= htmlspecialchars($vid['caption']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-12 bg-primary-800 text-white">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <p class="text-primary-100 mb-4">Want to know more about this department?</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn bg-accent-400 text-primary-900 hover:bg-accent-500 px-8 py-3 font-bold">Contact us</a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($program['name']) . ' - ' . APP_NAME;
require __DIR__ . '/layout.php';
