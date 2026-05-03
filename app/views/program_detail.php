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

$detailHtml = ContentSanitizer::programDetailBodyHtml($program['detail_content'] ?? '');
$hasDetail = trim(strip_tags(str_replace(["\xc2\xa0", '&nbsp;'], ' ', $detailHtml))) !== '';

$gallerySlides = [];
foreach ($images as $img) {
    $src = !empty($img['file_path']) ? APP_URL . '/' . ltrim($img['file_path'], '/') : '';
    if ($src === '') {
        continue;
    }
    $cap = trim((string) ($img['caption'] ?? ''));
    $alt = $cap !== '' ? $cap : ($program['name'] ?? 'Gallery');
    $gallerySlides[] = ['src' => $src, 'caption' => $cap, 'alt' => $alt];
}
$galleryCount = count($gallerySlides);
$hasGallery = $galleryCount > 0;
$bifold = $hasDetail && $hasGallery;

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

<?php if ($hasDetail || $hasGallery): ?>
<section class="border-b border-slate-200 bg-white py-10 sm:py-12 lg:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 <?= $bifold ? 'lg:grid-cols-12 lg:gap-12 xl:gap-14' : '' ?>">
            <?php if ($hasDetail): ?>
            <div class="<?= $bifold ? 'lg:col-span-7' : '' ?> min-w-0">
                    <div class="rounded-2xl border border-slate-200/90 bg-gradient-to-b from-slate-50/90 to-white p-5 shadow-sm sm:p-7 lg:p-8">
                        <h2 class="text-lg font-bold tracking-tight text-primary-900 sm:text-xl">About this department</h2>
                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">Key information for students and visitors.</p>

                        <div class="relative mt-6">
                            <div
                                id="program-detail-inner"
                                class="overflow-hidden transition-[max-height] duration-500 ease-in-out"
                                style="max-height: 22rem;"
                            >
                                <div class="program-detail-prose max-w-none text-left text-[15px] leading-relaxed text-slate-700 sm:text-base sm:leading-relaxed [&_p]:mb-4 [&_p:last-child]:mb-0 [&_ul]:my-4 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:marker:text-primary-600 [&_ol]:my-4 [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:marker:font-semibold [&_ol]:marker:text-primary-700 [&_li]:mb-2 [&_li]:pl-0.5 [&_li]:marker:text-inherit [&_h2]:mb-3 [&_h2]:mt-10 [&_h2]:border-b [&_h2]:border-slate-200 [&_h2]:pb-2 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:tracking-tight [&_h2]:text-primary-900 [&_h2:first-child]:mt-0 [&_h3]:mb-2 [&_h3]:mt-8 [&_h3]:text-lg [&_h3]:font-bold [&_h3]:text-primary-900 [&_h4]:mb-2 [&_h4]:mt-6 [&_h4]:text-base [&_h4]:font-semibold [&_h4]:text-primary-800 [&_blockquote]:my-5 [&_blockquote]:rounded-r-xl [&_blockquote]:border-l-4 [&_blockquote]:border-accent-400 [&_blockquote]:bg-white/90 [&_blockquote]:py-3 [&_blockquote]:pl-4 [&_blockquote]:pr-3 [&_blockquote]:text-slate-600 [&_blockquote]:shadow-sm [&_a]:font-medium [&_a]:text-primary-700 [&_a]:underline [&_a]:decoration-primary-300 [&_a]:decoration-2 [&_a]:underline-offset-2 [&_a]:transition-colors [&_a:hover]:text-accent-700 [&_strong]:font-semibold [&_strong]:text-slate-900 [&_em]:italic [&_u]:decoration-slate-400 [&_div]:mb-0">
                                    <?= $detailHtml ?>
                                </div>
                            </div>
                            <div
                                id="program-detail-fade"
                                class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-white via-white/95 to-transparent transition-opacity duration-300 sm:h-24"
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
            </div>
            <?php endif; ?>

            <?php if ($hasGallery): ?>
                <aside class="min-w-0 <?= $bifold ? 'lg:col-span-5' : '' ?>">
                    <div class="lg:sticky lg:top-24">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                            <h2 class="text-base font-bold text-primary-900 sm:text-lg">Photo gallery</h2>
                            <p class="mt-0.5 text-xs text-slate-500 sm:text-sm"><?= (int) $galleryCount ?> photo<?= $galleryCount === 1 ? '' : 's' ?> · tap to enlarge</p>
                            <div class="mt-4 grid max-h-[min(70vh,36rem)] grid-cols-2 gap-2 overflow-y-auto overscroll-contain pr-0.5 sm:gap-3 md:max-h-[min(75vh,40rem)]">
                                <?php foreach ($gallerySlides as $gi => $slide): ?>
                                    <button
                                        type="button"
                                        class="gallery-thumb group relative block w-full overflow-hidden rounded-xl bg-slate-100 text-left ring-1 ring-slate-200/80 transition duration-200 hover:ring-2 hover:ring-accent-400 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2 active:scale-[0.98]"
                                        data-gallery-index="<?= (int) $gi ?>"
                                        aria-label="Open image <?= (int) ($gi + 1) ?> of <?= (int) $galleryCount ?> in gallery"
                                    >
                                        <div class="aspect-square w-full sm:aspect-[4/3]">
                                            <img
                                                src="<?= htmlspecialchars($slide['src']) ?>"
                                                alt="<?= htmlspecialchars($slide['alt']) ?>"
                                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                loading="lazy"
                                                decoding="async"
                                                width="400"
                                                height="400"
                                            >
                                        </div>
                                        <?php if ($slide['caption'] !== ''): ?>
                                            <span class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 to-transparent px-2 pb-2 pt-8 text-[10px] font-medium leading-tight text-white opacity-0 transition group-hover:opacity-100 sm:text-xs"><?= htmlspecialchars($slide['caption']) ?></span>
                                        <?php endif; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($hasGallery): ?>
<style>
.gallery-lightbox {
    visibility: hidden;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.35s cubic-bezier(0.22, 1, 0.36, 1), visibility 0.35s step-end;
}
.gallery-lightbox.gallery-lightbox--open {
    visibility: visible;
    opacity: 1;
    pointer-events: auto;
    transition: opacity 0.35s cubic-bezier(0.22, 1, 0.36, 1), visibility 0s step-start;
}
.gallery-lightbox__scrim {
    opacity: 0;
    transition: opacity 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}
.gallery-lightbox--open .gallery-lightbox__scrim {
    opacity: 1;
}
.gallery-lightbox__figure {
    transform: scale(0.9) translateY(12px);
    opacity: 0;
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.35s ease;
}
.gallery-lightbox--open .gallery-lightbox__figure {
    transform: scale(1) translateY(0);
    opacity: 1;
}
.gallery-lightbox__img {
    transition: opacity 0.2s ease, transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
}
.gallery-lightbox__img.gallery-lightbox__img--swap {
    opacity: 0;
    transform: scale(0.98);
}
</style>
<div
    id="gallery-lightbox"
    class="gallery-lightbox fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-8"
    role="dialog"
    aria-modal="true"
    aria-label="Gallery"
    aria-hidden="true"
>
    <button type="button" class="gallery-lightbox__scrim absolute inset-0 z-[10] cursor-default border-0 bg-slate-950/90 p-0 backdrop-blur-md" data-lightbox-close aria-label="Close gallery"></button>

    <button type="button" class="gallery-lightbox__close absolute right-3 top-3 z-30 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-accent-400 sm:right-5 sm:top-5" data-lightbox-close aria-label="Close gallery">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    <?php if ($galleryCount > 1): ?>
    <button type="button" id="gallery-lightbox-prev" class="gallery-lightbox__prev absolute left-1 top-1/2 z-30 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-accent-400 sm:left-4 sm:h-12 sm:w-12" aria-label="Previous image">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
    </button>
    <button type="button" id="gallery-lightbox-next" class="gallery-lightbox__next absolute right-1 top-1/2 z-30 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-accent-400 sm:right-4 sm:h-12 sm:w-12" aria-label="Next image">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
    </button>
    <?php endif; ?>

    <figure class="gallery-lightbox__figure pointer-events-auto relative z-20 mx-auto w-full max-w-5xl px-1">
        <img id="gallery-lightbox-img" src="" alt="" class="gallery-lightbox__img mx-auto max-h-[min(82vh,880px)] w-auto max-w-full rounded-xl object-contain shadow-2xl ring-1 ring-white/15" width="1200" height="900" decoding="async">
        <figcaption id="gallery-lightbox-caption" class="mt-4 max-h-24 overflow-y-auto text-center text-sm leading-snug text-white/90 sm:text-base"></figcaption>
    </figure>
</div>
<?php endif; ?>

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

<?php if ($hasGallery): ?>
<script>
(function () {
    var slides = <?= json_encode($gallerySlides, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
    if (!slides || !slides.length) return;

    var root = document.getElementById('gallery-lightbox');
    var imgEl = document.getElementById('gallery-lightbox-img');
    var capEl = document.getElementById('gallery-lightbox-caption');
    var prevBtn = document.getElementById('gallery-lightbox-prev');
    var nextBtn = document.getElementById('gallery-lightbox-next');
    if (!root || !imgEl) return;

    var idx = 0;
    var lastFocus = null;

    function showIndex(i, animate) {
        idx = (i + slides.length) % slides.length;
        var s = slides[idx];
        function apply() {
            imgEl.src = s.src;
            imgEl.alt = s.alt || '';
            capEl.textContent = s.caption || '';
            capEl.style.display = s.caption ? 'block' : 'none';
        }
        if (animate && imgEl.classList) {
            imgEl.classList.add('gallery-lightbox__img--swap');
            setTimeout(function () {
                apply();
                requestAnimationFrame(function () {
                    imgEl.classList.remove('gallery-lightbox__img--swap');
                });
            }, 160);
        } else {
            apply();
        }
    }

    function open(at) {
        lastFocus = document.activeElement;
        idx = typeof at === 'number' ? at : 0;
        showIndex(idx, false);
        root.classList.add('gallery-lightbox--open');
        root.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        var closeBtn = root.querySelector('.gallery-lightbox__close');
        if (closeBtn) closeBtn.focus();
    }

    function close() {
        root.classList.remove('gallery-lightbox--open');
        root.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastFocus && typeof lastFocus.focus === 'function') {
            try { lastFocus.focus(); } catch (e) {}
        }
    }

    document.querySelectorAll('.gallery-thumb').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var i = parseInt(btn.getAttribute('data-gallery-index'), 10);
            if (!isNaN(i)) open(i);
        });
    });

    root.querySelectorAll('[data-lightbox-close]').forEach(function (el) {
        el.addEventListener('click', function (ev) {
            ev.preventDefault();
            close();
        });
    });

    if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            showIndex(idx - 1, true);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            showIndex(idx + 1, true);
        });
    }

    document.addEventListener('keydown', function (e) {
        if (!root.classList.contains('gallery-lightbox--open')) return;
        if (e.key === 'Escape') {
            e.preventDefault();
            close();
        } else if (e.key === 'ArrowLeft' && slides.length > 1) {
            e.preventDefault();
            showIndex(idx - 1, true);
        } else if (e.key === 'ArrowRight' && slides.length > 1) {
            e.preventDefault();
            showIndex(idx + 1, true);
        }
    });
})();
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($program['name']) . ' - ' . APP_NAME;
require __DIR__ . '/layout.php';
