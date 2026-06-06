<?php
ob_start();
$events = $events ?? [];

function kti_event_date_parts($iso) {
    if (!$iso) {
        return null;
    }
    $ts = strtotime($iso);
    if (!$ts) {
        return null;
    }
    return [
        'day' => date('j', $ts),
        'month' => strtoupper(date('M', $ts)),
        'year' => date('Y', $ts),
        'full' => date('l, F j, Y', $ts),
        'time' => date('G', $ts) === '0' && date('i', $ts) === '00' ? '' : date('g:i a', $ts),
        'ts' => $ts,
    ];
}

$now = time();
$upcoming = [];
$past = [];
foreach ($events as $e) {
    $parts = kti_event_date_parts($e['event_date'] ?? null);
    $e['_parts'] = $parts;
    if (!$parts || $parts['ts'] >= ($now - 86400)) {
        $upcoming[] = $e;
    } else {
        $past[] = $e;
    }
}
?>

<section class="relative overflow-hidden bg-primary-900 py-20 text-white sm:py-24">
    <div class="absolute inset-0 z-0 bg-black/60"></div>
    <div class="absolute inset-0 z-0 bg-cover bg-center mix-blend-overlay" style="background-image: url('<?= APP_URL ?>/assets/images/vocational.jpg');"></div>
    <div class="relative z-10 mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 text-center">
        <p class="mb-3 text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-accent-400">Campus life</p>
        <h1 class="mb-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl text-balance">Events at Kikam Technical Institute</h1>
        <p class="mx-auto max-w-3xl text-base sm:text-lg leading-relaxed text-primary-100">
            Open days, speech and prize giving, workshops, sports, alumni gatherings — see what's on at Kikam.
        </p>
    </div>
</section>

<section class="border-b border-slate-200 bg-gray-50 py-12 sm:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <?php if (!empty($upcoming)): ?>
        <div class="mb-10">
            <h2 class="mb-5 text-xl font-bold text-primary-900 sm:text-2xl">Upcoming &amp; recent</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($upcoming as $e):
                    $cover = !empty($e['cover_image']) ? rtrim(APP_URL, '/') . '/' . ltrim($e['cover_image'], '/') : '';
                    $href = !empty($e['slug']) ? APP_URL . '?url=event/' . rawurlencode($e['slug']) : '#';
                    $p = $e['_parts'] ?? null;
                ?>
                <a href="<?= htmlspecialchars($href) ?>" class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-primary-300 hover:shadow-md">
                    <div class="relative aspect-[16/10] w-full bg-gradient-to-br from-primary-100 to-accent-100">
                        <?php if ($cover !== ''): ?>
                            <img src="<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($e['title']) ?>" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" decoding="async">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/0 to-transparent"></div>
                        <?php endif; ?>
                        <?php if ($p): ?>
                            <div class="absolute left-3 top-3 flex h-14 w-14 flex-col items-center justify-center rounded-xl bg-white/95 text-center shadow ring-1 ring-black/5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-accent-700"><?= htmlspecialchars($p['month']) ?></span>
                                <span class="-mt-0.5 text-xl font-bold leading-none text-primary-900"><?= htmlspecialchars($p['day']) ?></span>
                                <span class="text-[10px] text-slate-500"><?= htmlspecialchars($p['year']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-1 flex-col gap-2 p-5">
                        <h3 class="text-lg font-bold text-primary-900 group-hover:text-primary-700"><?= htmlspecialchars($e['title']) ?></h3>
                        <?php if ($p): ?>
                            <p class="text-xs font-medium text-slate-500"><?= htmlspecialchars($p['full']) ?><?= $p['time'] !== '' ? ' · ' . htmlspecialchars($p['time']) : '' ?></p>
                        <?php endif; ?>
                        <?php if (!empty($e['location'])): ?>
                            <p class="text-xs text-slate-500">📍 <?= htmlspecialchars($e['location']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($e['summary'])): ?>
                            <p class="mt-1 line-clamp-3 text-sm leading-relaxed text-slate-600"><?= htmlspecialchars($e['summary']) ?></p>
                        <?php endif; ?>
                        <span class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-accent-700">
                            View details
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($past)): ?>
        <div class="<?= !empty($upcoming) ? 'mt-12 border-t border-slate-200 pt-10' : '' ?>">
            <h2 class="mb-5 text-xl font-bold text-primary-900 sm:text-2xl">Past events</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($past as $e):
                    $cover = !empty($e['cover_image']) ? rtrim(APP_URL, '/') . '/' . ltrim($e['cover_image'], '/') : '';
                    $href = !empty($e['slug']) ? APP_URL . '?url=event/' . rawurlencode($e['slug']) : '#';
                    $p = $e['_parts'] ?? null;
                ?>
                <a href="<?= htmlspecialchars($href) ?>" class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-primary-300 hover:shadow-md">
                    <div class="relative aspect-[16/10] w-full bg-slate-100">
                        <?php if ($cover !== ''): ?>
                            <img src="<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($e['title']) ?>" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" decoding="async">
                        <?php endif; ?>
                        <?php if ($p): ?>
                            <span class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-black/70 px-2.5 py-0.5 text-[11px] font-semibold text-white backdrop-blur">
                                <?= htmlspecialchars($p['month']) ?> <?= htmlspecialchars($p['day']) ?>, <?= htmlspecialchars($p['year']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-1 flex-col gap-2 p-5">
                        <h3 class="text-base font-bold text-primary-900 group-hover:text-primary-700"><?= htmlspecialchars($e['title']) ?></h3>
                        <?php if (!empty($e['location'])): ?>
                            <p class="text-xs text-slate-500">📍 <?= htmlspecialchars($e['location']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($e['summary'])): ?>
                            <p class="mt-1 line-clamp-3 text-sm leading-relaxed text-slate-600"><?= htmlspecialchars($e['summary']) ?></p>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($upcoming) && empty($past)): ?>
        <div class="mx-auto max-w-2xl rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
            <h2 class="text-2xl font-bold text-primary-900">No events posted yet</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">
                Stay tuned — open days, speech and prize giving, sports days and workshops will be listed here.
            </p>
            <a href="<?= APP_URL ?>?url=contact" class="btn btn-accent mt-6 inline-flex px-6 py-3">Contact us</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = 'Events - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
