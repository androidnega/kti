<?php
$cover = !empty($program['cover_image']) ? APP_URL . '/' . ltrim($program['cover_image'], '/') : APP_URL . '/assets/images/vocational.jpg';
$faculty = !empty($program['faculty']) ? $program['faculty'] : ($program['department'] ?? '');

$images = [];
$videos = [];
foreach ($media ?? [] as $m) {
    if (($m['media_type'] ?? '') === 'video') {
        $videos[] = $m;
    } else {
        $images[] = $m;
    }
}

$detailRaw = trim((string) ($program['detail_content'] ?? ''));
$detailParagraphs = [];
if ($detailRaw !== '') {
    $parts = preg_split('/\n[\s]*\n/u', $detailRaw, -1, PREG_SPLIT_NO_EMPTY);
    foreach ($parts as $part) {
        $t = trim($part);
        if ($t !== '') {
            $detailParagraphs[] = $t;
        }
    }
    if (count($detailParagraphs) === 0) {
        $detailParagraphs[] = $detailRaw;
    }
}

$galleryCount = 0;
foreach ($images as $img) {
    if (!empty($img['file_path'])) {
        $galleryCount++;
    }
}
$hasGallery = $galleryCount > 0;
$hasDetail = count($detailParagraphs) > 0;
$mainColClass = $hasGallery ? 'lg:col-span-7' : 'lg:col-span-12';

ob_start();
?>

<section class="relative overflow-hidden bg-primary-900 py-14 text-white sm:py-16 md:py-20">
    <div class="absolute inset-0 z-0 bg-black/60"></div>
    <div class="absolute inset-0 z-0 bg-cover bg-center mix-blend-overlay" style="background-image: url('<?= htmlspecialchars($cover) ?>');"></div>
    <div class="relative z-10 mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <nav class="mb-4 text-sm text-primary-100/90">
            <a href="<?= APP_URL ?>?url=programs" class="font-medium underline-offset-2 transition hover:text-white hover:underline">Programs</a>
            <span class="mx-2 opacity-60" aria-hidden="true">/</span>
            <span class="font-medium text-white"><?= htmlspecialchars($program['name']) ?></span>
        </nav>
        <?php if ($faculty !== ''): ?>
            <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-accent-400 sm:text-sm"><?= htmlspecialchars($faculty) ?></p>
        <?php endif; ?>
        <h1 class="mb-4 max-w-3xl text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl"><?= htmlspecialchars($program['name']) ?></h1>
        <?php if (!empty($program['description'])): ?>
            <p class="max-w-2xl text-base leading-relaxed text-primary-50 sm:text-lg">
                <?= nl2br(htmlspecialchars($program['description'])) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="border-b border-slate-200 bg-white py-10 sm:py-12 lg:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-12 xl:gap-14">
            <div class="<?= htmlspecialchars($mainColClass) ?> min-w-0">
                <?php if ($hasDetail): ?>
                    <div class="rounded-2xl border border-slate-200/90 bg-slate-50/60 p-5 sm:p-7 lg:p-8">
                        <h2 class="text-lg font-bold tracking-tight text-primary-900 sm:text-xl">About this department</h2>
                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">Key information for students and visitors.</p>

                        <div class="relative mt-6">
                            <div
                                id="program-detail-inner"
                                class="overflow-hidden transition-[max-height] duration-500 ease-in-out"
                                style="max-height: 22rem;"
                                data-collapsed-height="22rem"
                            >
                                <div class="space-y-4 text-left text-[15px] leading-relaxed text-slate-700 sm:text-base sm:leading-relaxed">
                                    <?php foreach ($detailParagraphs as $para): ?>
                                        <p class="whitespace-pre-line break-words"><?= nl2br(htmlspecialchars($para)) ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div
                                id="program-detail-fade"
                                class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-slate-50 via-slate-50/90 to-transparent transition-opacity duration-300 sm:h-24"
                                aria-hidden="true"
                            ></div>
                        </div>
                        <button
                            type="button"
                            id="program-detail-toggle"
                            class="mt-4 inline-flex items-center gap-2 rounded-xl border border-primary-200 bg-white px-4 py-2.5 text-sm font-semibold text-primary-900 shadow-sm transition hover:border-accent-400 hover:bg-accent-50 focus:outline-none focus:ring-2 focus:ring-accent-400 focus:ring-offset-2"
                            aria-expanded="false"
                            aria-controls="program-detail-inner"
                        >
                            <span id="program-detail-toggle-label">Read more</span>
                            <svg id="program-detail-toggle-icon" class="h-4 w-4 shrink-0 text-primary-600 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                <?php else: ?>
                    <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 px-5 py-8 text-center text-sm text-slate-500">More information for this department will appear here when added in the admin panel.</p>
                <?php endif; ?>
            </div>

            <?php if ($hasGallery): ?>
                <aside class="min-w-0 lg:col-span-5">
                    <div class="lg:sticky lg:top-24">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                            <h2 class="text-base font-bold text-primary-900 sm:text-lg">Photo gallery</h2>
                            <p class="mt-0.5 text-xs text-slate-500 sm:text-sm"><?= (int) $galleryCount ?> photo<?= $galleryCount === 1 ? '' : 's' ?></p>
                            <div class="mt-4 grid max-h-[min(70vh,36rem)] grid-cols-2 gap-2 overflow-y-auto overscroll-contain pr-0.5 sm:gap-3 md:max-h-[min(75vh,40rem)]">
                                <?php foreach ($images as $img): ?>
                                    <?php
                                    $src = !empty($img['file_path']) ? APP_URL . '/' . ltrim($img['file_path'], '/') : '';
                                    if ($src === '') {
                                        continue;
                                    }
                                    $cap = trim((string) ($img['caption'] ?? ''));
                                    ?>
                                    <a
                                        href="<?= htmlspecialchars($src) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="group relative block overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200/80 transition hover:ring-2 hover:ring-accent-400 focus:outline-none focus:ring-2 focus:ring-accent-500"
                                    >
                                        <div class="aspect-square w-full sm:aspect-[4/3]">
                                            <img
                                                src="<?= htmlspecialchars($src) ?>"
                                                alt="<?= htmlspecialchars($cap !== '' ? $cap : $program['name']) ?>"
                                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                loading="lazy"
                                                decoding="async"
                                                width="400"
                                                height="400"
                                            >
                                        </div>
                                        <?php if ($cap !== ''): ?>
                                            <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent px-2 pb-2 pt-8 text-[10px] font-medium leading-tight text-white opacity-0 transition group-hover:opacity-100 sm:text-xs"><?= htmlspecialchars($cap) ?></span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($videos)): ?>
<section class="border-t border-slate-100 bg-slate-50 py-10 sm:py-12 lg:py-14">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-6 text-xl font-bold text-primary-900 sm:text-2xl">Videos</h2>
        <div class="space-y-8">
            <?php foreach ($videos as $vid): ?>
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-black shadow-md">
                    <?php if (!empty($vid['external_url'])): ?>
                        <?php
                        $eu = $vid['external_url'];
                        $embed = null;
                        if (preg_match('~youtube\\.com/watch\\?v=([a-zA-Z0-9_-]+)~', $eu, $m) || preg_match('~youtu\\.be/([a-zA-Z0-9_-]+)~', $eu, $m)) {
                            $embed = 'https://www.youtube.com/embed/' . $m[1];
                        }
                        ?>
                        <?php if ($embed): ?>
                            <div class="aspect-video w-full">
                                <iframe class="h-full w-full" src="<?= htmlspecialchars($embed) ?>" title="Video" allowfullscreen loading="lazy"></iframe>
                            </div>
                        <?php else: ?>
                            <div class="p-6 text-center text-white">
                                <a href="<?= htmlspecialchars($eu) ?>" class="font-medium text-accent-400 underline hover:text-accent-300" target="_blank" rel="noopener noreferrer">Open video link</a>
                            </div>
                        <?php endif; ?>
                    <?php elseif (!empty($vid['file_path'])): ?>
                        <?php $vsrc = APP_URL . '/' . ltrim($vid['file_path'], '/'); ?>
                        <video class="max-h-[min(70vh,32rem)] w-full" controls preload="metadata" playsinline>
                            <source src="<?= htmlspecialchars($vsrc) ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                    <?php if (!empty($vid['caption'])): ?>
                        <p class="border-t border-white/10 bg-primary-950 px-4 py-3 text-sm text-slate-200"><?= htmlspecialchars($vid['caption']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="bg-primary-900 py-12 text-white">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6">
        <p class="mb-4 text-primary-100">Want to know more about this department?</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn btn-accent inline-flex items-center justify-center px-8 py-3">Contact us</a>
    </div>
</section>

<?php if ($hasDetail): ?>
<script>
(function () {
    var inner = document.getElementById('program-detail-inner');
    var btn = document.getElementById('program-detail-toggle');
    var fade = document.getElementById('program-detail-fade');
    var label = document.getElementById('program-detail-toggle-label');
    var icon = document.getElementById('program-detail-toggle-icon');
    if (!inner || !btn) return;

    var collapsedPx = Math.min(inner.offsetHeight || 352, 352);
    try {
        var h = window.getComputedStyle(inner).maxHeight;
        if (h && h !== 'none') {
            var n = parseFloat(h);
            if (!isNaN(n)) collapsedPx = n;
        }
    } catch (e) {}

    function measure() {
        var prev = inner.style.maxHeight;
        inner.style.maxHeight = 'none';
        var full = inner.scrollHeight;
        inner.style.maxHeight = prev;
        return full;
    }

    var fullH = measure();
    var threshold = collapsedPx + 24;

    if (fullH <= threshold) {
        inner.style.maxHeight = 'none';
        if (fade) fade.style.display = 'none';
        btn.style.display = 'none';
        return;
    }

    inner.style.maxHeight = collapsedPx + 'px';
    var expanded = false;

    btn.addEventListener('click', function () {
        expanded = !expanded;
        if (expanded) {
            inner.style.maxHeight = fullH + 48 + 'px';
            if (fade) fade.style.opacity = '0';
            if (label) label.textContent = 'Show less';
            if (icon) icon.classList.add('rotate-180');
        } else {
            inner.style.maxHeight = collapsedPx + 'px';
            if (fade) fade.style.opacity = '1';
            if (label) label.textContent = 'Read more';
            if (icon) icon.classList.remove('rotate-180');
        }
        btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    });

    window.addEventListener('resize', function () {
        if (expanded) {
            fullH = measure();
            inner.style.maxHeight = fullH + 48 + 'px';
        }
    });
})();
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($program['name']) . ' - ' . APP_NAME;
require __DIR__ . '/layout.php';
