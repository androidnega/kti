<?php
ob_start();
$alumni = $alumni ?? [];
$count = count($alumni);
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
