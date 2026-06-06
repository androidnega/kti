<?php
ob_start();
$alumni = $alumni ?? [];
$count = count($alumni);

$memoriesDir = PUBLIC_PATH . '/assets/images/alumni-memories';
$alumniMemories = [];
if (is_dir($memoriesDir)) {
    $files = scandir($memoriesDir);
    natsort($files);
    foreach ($files as $f) {
        if ($f[0] === '.') continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) continue;
        $alumniMemories[] = 'assets/images/alumni-memories/' . $f;
    }
}
?>

<section class="relative overflow-hidden bg-primary-900 py-20 text-white sm:py-24">
    <div class="absolute inset-0 z-0 bg-black/60"></div>
    <div class="absolute inset-0 z-0 bg-cover bg-center mix-blend-overlay" style="background-image: url('<?= APP_URL ?>/assets/images/vocational.jpg');"></div>
    <div class="relative z-10 mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 text-center">
        <p class="mb-3 text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-accent-400">Old students</p>
        <h1 class="mb-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl text-balance">Kikam alumni making us proud</h1>
        <p class="mx-auto max-w-3xl text-base sm:text-lg leading-relaxed text-primary-100">
            From workshops in Kikam to careers across Ghana and beyond — meet some of the students who started here and built lives in their craft.
        </p>
    </div>
</section>

<section class="border-b border-slate-200 bg-gray-50 py-12 sm:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <?php if ($count > 0): ?>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($alumni as $a):
                    $photo = !empty($a['photo_path']) ? rtrim(APP_URL, '/') . '/' . ltrim($a['photo_path'], '/') : '';
                    $initials = strtoupper(mb_substr(trim((string) ($a['name'] ?? '?')), 0, 2));
                ?>
                <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-primary-300 hover:shadow-md">
                    <div class="relative aspect-[4/3] w-full bg-gradient-to-br from-primary-50 to-accent-50">
                        <?php if ($photo !== ''): ?>
                            <img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($a['name']) ?>" class="h-full w-full object-cover" loading="lazy" decoding="async">
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center text-3xl font-bold tracking-wider text-primary-700/70 sm:text-4xl">
                                <?= htmlspecialchars($initials) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($a['is_featured'])): ?>
                            <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-accent-400 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-primary-900 shadow">
                                Featured
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($a['graduation_year'])): ?>
                            <span class="absolute left-3 bottom-3 inline-flex items-center gap-1.5 rounded-full bg-black/70 px-2.5 py-0.5 text-[11px] font-semibold text-white backdrop-blur">
                                Class of <?= htmlspecialchars($a['graduation_year']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-1 flex-col gap-2 p-5">
                        <h3 class="text-lg font-bold text-primary-900"><?= htmlspecialchars($a['name']) ?></h3>
                        <?php if (!empty($a['current_role'])): ?>
                            <p class="text-sm font-semibold text-accent-700"><?= htmlspecialchars($a['current_role']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($a['program']) || !empty($a['location'])): ?>
                            <p class="text-xs text-slate-500">
                                <?php
                                $bits = array_filter([
                                    !empty($a['program']) ? $a['program'] : '',
                                    !empty($a['location']) ? $a['location'] : '',
                                ], 'strlen');
                                echo htmlspecialchars(implode(' · ', $bits));
                                ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($a['quote'])): ?>
                            <blockquote class="mt-2 rounded-xl border-l-4 border-accent-300 bg-slate-50 px-3 py-2 text-sm italic text-slate-700">
                                “<?= htmlspecialchars($a['quote']) ?>”
                            </blockquote>
                        <?php endif; ?>
                        <?php if (!empty($a['bio'])): ?>
                            <p class="mt-1 line-clamp-4 text-sm leading-relaxed text-slate-600">
                                <?= nl2br(htmlspecialchars($a['bio'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="mx-auto max-w-2xl rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                <h2 class="text-2xl font-bold text-primary-900">We're collecting alumni stories</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Are you a former student of Kikam Technical Institute? We’d love to feature your story here — your name, year, what you do today, and a photo if you can share one.
                </p>
                <a href="<?= APP_URL ?>?url=contact" class="btn btn-accent mt-6 inline-flex px-6 py-3">Reach out to share your story</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($alumniMemories)): ?>
<section class="bg-white py-14 sm:py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex items-center gap-2 rounded-full bg-accent-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-accent-700 sm:text-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-500"></span>
                Through the years
            </span>
            <h2 class="mt-4 text-3xl font-bold tracking-tight text-primary-900 sm:text-4xl">Memories from our alumni community</h2>
            <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                Every cohort that has passed through Kikam Technical Institute has left a mark — on the workshops, on the school, and on each other. From speech and prize giving days, sports gatherings and graduation ceremonies to reunions and informal class meet-ups, these photos celebrate the friendships, mentors and milestones that have shaped generations of Kikam old students.
            </p>
            <p class="mt-3 text-base leading-relaxed text-slate-600 sm:text-lg">
                We owe an enormous debt to the men and women who walked these corridors before us. Many of them are now in industry, public service and entrepreneurship across Ghana and beyond — and they continue to support the next generation of trainees back home in Kikam.
            </p>
        </div>

        <div id="alumni-memories" class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4">
            <?php foreach ($alumniMemories as $idx => $rel):
                $url = rtrim(APP_URL, '/') . '/' . $rel;
            ?>
            <button type="button" class="memory-thumb group relative aspect-square overflow-hidden rounded-xl bg-slate-100 ring-1 ring-black/5 transition hover:ring-primary-300 focus:outline-none focus:ring-2 focus:ring-accent-400" data-index="<?= (int) $idx ?>" aria-label="Open photo <?= (int) $idx + 1 ?>">
                <img src="<?= htmlspecialchars($url) ?>" alt="Kikam Technical Institute alumni memory photo" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                <span class="absolute inset-0 bg-black/0 transition group-hover:bg-black/10"></span>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    #memory-lightbox { transition: opacity 250ms ease-in-out, visibility 250ms ease-in-out; }
    #memory-lightbox.is-open { opacity: 1; visibility: visible; }
    #memory-lightbox-img { transform: scale(0.96); opacity: 0; transition: opacity 250ms ease-out, transform 250ms ease-out; }
    #memory-lightbox.is-open #memory-lightbox-img { opacity: 1; transform: scale(1); }
</style>

<div id="memory-lightbox" class="invisible fixed inset-0 z-[80] flex items-center justify-center bg-black/90 p-4 opacity-0" aria-hidden="true" role="dialog">
    <button type="button" id="memory-lightbox-close" class="absolute right-4 top-4 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Close">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <button type="button" id="memory-lightbox-prev" class="absolute left-2 sm:left-4 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Previous">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" id="memory-lightbox-next" class="absolute right-2 sm:right-4 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Next">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    <figure class="relative max-h-[90vh] max-w-5xl">
        <img id="memory-lightbox-img" src="" alt="" class="max-h-[88vh] w-auto max-w-full rounded-lg object-contain shadow-2xl">
        <figcaption id="memory-lightbox-counter" class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-white/70"></figcaption>
    </figure>
</div>

<script>
(function () {
    var images = <?= json_encode(array_map(function ($p) {
        return rtrim(APP_URL, '/') . '/' . $p;
    }, $alumniMemories), JSON_UNESCAPED_SLASHES) ?>;
    if (!images.length) return;

    var lightbox = document.getElementById('memory-lightbox');
    var imgEl = document.getElementById('memory-lightbox-img');
    var counter = document.getElementById('memory-lightbox-counter');
    var closeBtn = document.getElementById('memory-lightbox-close');
    var prevBtn = document.getElementById('memory-lightbox-prev');
    var nextBtn = document.getElementById('memory-lightbox-next');
    var thumbs = document.querySelectorAll('#alumni-memories .memory-thumb');
    var current = 0;

    function open(i) {
        current = i;
        imgEl.src = images[current];
        counter.textContent = (current + 1) + ' / ' + images.length;
        lightbox.classList.add('is-open');
        lightbox.classList.remove('invisible');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function close() {
        lightbox.classList.remove('is-open');
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        setTimeout(function () { lightbox.classList.add('invisible'); }, 260);
    }
    function go(delta) {
        var next = (current + delta + images.length) % images.length;
        imgEl.style.opacity = '0';
        imgEl.style.transform = 'scale(0.97)';
        setTimeout(function () {
            current = next;
            imgEl.src = images[current];
            counter.textContent = (current + 1) + ' / ' + images.length;
            imgEl.style.opacity = '1';
            imgEl.style.transform = 'scale(1)';
        }, 150);
    }

    thumbs.forEach(function (t) {
        t.addEventListener('click', function () {
            open(parseInt(t.getAttribute('data-index'), 10) || 0);
        });
    });
    closeBtn.addEventListener('click', close);
    prevBtn.addEventListener('click', function () { go(-1); });
    nextBtn.addEventListener('click', function () { go(1); });
    lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox) close();
    });
    document.addEventListener('keydown', function (e) {
        if (lightbox.classList.contains('invisible')) return;
        if (e.key === 'Escape') close();
        else if (e.key === 'ArrowLeft') go(-1);
        else if (e.key === 'ArrowRight') go(1);
    });
})();
</script>
<?php endif; ?>

<section class="bg-primary-900 py-14 text-white">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6">
        <h2 class="text-2xl font-bold sm:text-3xl">Are you a Kikam old student?</h2>
        <p class="mx-auto mt-3 max-w-xl text-primary-100">Tell us where you are today. Your story can inspire the next generation of trainees.</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn btn-accent mt-6 inline-flex px-8 py-3">Get in touch</a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = 'Old Students - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
