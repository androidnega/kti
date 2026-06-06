<?php
ob_start();
$event = $event ?? [];
$cover = !empty($event['cover_image']) ? rtrim(APP_URL, '/') . '/' . ltrim($event['cover_image'], '/') : APP_URL . '/assets/images/vocational.jpg';
$ts = !empty($event['event_date']) ? strtotime($event['event_date']) : null;
$endTs = !empty($event['end_date']) ? strtotime($event['end_date']) : null;

$contentHtml = '';
$raw = trim((string) ($event['content'] ?? ''));
if ($raw !== '') {
    require_once APP_PATH . '/helpers/ContentSanitizer.php';
    $contentHtml = ContentSanitizer::programDetailBodyHtml($raw);
}
?>

<section class="relative overflow-hidden bg-primary-900 py-16 text-white sm:py-20 md:py-24">
    <div class="absolute inset-0 z-0 bg-black/55"></div>
    <div class="absolute inset-0 z-0 bg-cover bg-center mix-blend-overlay" style="background-image: url('<?= htmlspecialchars($cover) ?>');"></div>
    <div class="relative z-10 mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <nav class="mb-4 text-sm text-primary-100/90">
            <a href="<?= APP_URL ?>?url=events" class="font-medium underline-offset-2 transition hover:text-white hover:underline">Events</a>
            <span class="mx-2 opacity-60" aria-hidden="true">/</span>
            <span class="font-medium text-white"><?= htmlspecialchars($event['title']) ?></span>
        </nav>
        <?php if ($ts): ?>
        <p class="mb-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-accent-300 backdrop-blur">
            <?= htmlspecialchars(date('l, F j, Y', $ts)) ?>
            <?php if (date('G', $ts) !== '0' || date('i', $ts) !== '00'): ?>
                · <?= htmlspecialchars(date('g:i a', $ts)) ?>
            <?php endif; ?>
        </p>
        <?php endif; ?>
        <h1 class="mb-4 text-3xl font-bold leading-tight tracking-tight sm:text-4xl md:text-5xl text-balance"><?= htmlspecialchars($event['title']) ?></h1>
        <?php if (!empty($event['location'])): ?>
        <p class="text-base text-primary-100 sm:text-lg">📍 <?= htmlspecialchars($event['location']) ?></p>
        <?php endif; ?>
        <?php if (!empty($event['summary'])): ?>
        <p class="mt-4 max-w-3xl text-base leading-relaxed text-primary-50 sm:text-lg">
            <?= nl2br(htmlspecialchars($event['summary'])) ?>
        </p>
        <?php endif; ?>
    </div>
</section>

<section class="bg-white py-12 sm:py-14">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <?php if ($contentHtml !== ''): ?>
            <div class="program-detail-prose max-w-none text-left text-[15px] leading-relaxed text-slate-700 sm:text-base sm:leading-relaxed [&_p]:mb-4 [&_p:last-child]:mb-0 [&_ul]:my-4 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:my-4 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:mb-2 [&_h2]:mb-3 [&_h2]:mt-10 [&_h2]:border-b [&_h2]:border-slate-200 [&_h2]:pb-2 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-primary-900 [&_h2:first-child]:mt-0 [&_h3]:mb-2 [&_h3]:mt-8 [&_h3]:text-lg [&_h3]:font-bold [&_h3]:text-primary-900 [&_blockquote]:my-5 [&_blockquote]:rounded-r-xl [&_blockquote]:border-l-4 [&_blockquote]:border-accent-400 [&_blockquote]:bg-slate-50 [&_blockquote]:py-3 [&_blockquote]:px-4 [&_blockquote]:text-slate-600 [&_a]:font-medium [&_a]:text-primary-700 [&_a]:underline [&_strong]:font-semibold [&_strong]:text-slate-900">
                <?= $contentHtml ?>
            </div>
        <?php else: ?>
            <p class="text-center text-slate-500">More details will be shared soon.</p>
        <?php endif; ?>

        <div class="mt-10 flex flex-col items-center gap-3 border-t border-slate-200 pt-8 sm:flex-row sm:justify-between">
            <a href="<?= APP_URL ?>?url=events" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 hover:text-primary-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                All events
            </a>
            <a href="<?= APP_URL ?>?url=contact" class="btn btn-accent inline-flex items-center justify-center px-6 py-2.5 text-sm">Ask about this event</a>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = htmlspecialchars($event['title']) . ' - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
