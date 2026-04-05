<?php ob_start(); ?>

<section class="relative bg-primary-900 text-white py-20 md:py-24 overflow-hidden">
    <div class="absolute inset-0 bg-black/50 z-0"></div>
    <div class="absolute inset-0 bg-[url('<?= APP_URL ?>/assets/images/vocational.jpg')] bg-cover bg-center mix-blend-overlay opacity-40 z-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <p class="text-accent-400 text-sm font-semibold tracking-widest uppercase mb-3">Official channel</p>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">KTI on YouTube</h1>
        <p class="text-lg text-primary-200 max-w-2xl mx-auto mb-8">
            Watch the latest from Kikam Technical Institute—events, departments, student life, and more.
        </p>
        <a href="<?= htmlspecialchars(YOUTUBE_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg bg-accent-400 text-primary-900 font-bold px-6 py-3 hover:bg-accent-500 transition-colors">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            Open YouTube channel
        </a>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($feedError) && empty($videos)): ?>
            <div class="rounded-xl border border-amber-200 bg-amber-50 text-amber-900 px-6 py-4 text-center">
                <p class="font-medium"><?= htmlspecialchars($feedError) ?></p>
                <p class="mt-2 text-sm text-amber-800">
                    You can still browse all videos on
                    <a href="<?= htmlspecialchars(YOUTUBE_CHANNEL_URL) ?>" class="underline font-semibold" target="_blank" rel="noopener noreferrer">our YouTube channel</a>.
                </p>
            </div>
        <?php elseif (empty($videos)): ?>
            <p class="text-center text-gray-600 text-lg">No videos are listed yet. Check back soon or visit our channel on YouTube.</p>
        <?php else: ?>
            <?php
            $source = $videoSource ?? 'feed';
            ?>
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
                <div class="max-w-xl">
                    <?php if ($source === 'curated'): ?>
                        <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                            Featured clips from our channel. Use the search box to find a video by title. For the full library, visit
                            <a href="<?= htmlspecialchars(YOUTUBE_CHANNEL_URL) ?>/videos" class="text-primary-900 font-semibold hover:underline" target="_blank" rel="noopener noreferrer">YouTube</a>.
                        </p>
                    <?php elseif (defined('YOUTUBE_API_KEY') && YOUTUBE_API_KEY !== ''): ?>
                        <p class="text-gray-600 text-sm sm:text-base">Uploads from our channel (YouTube Data API). Search filters the list below.</p>
                    <?php else: ?>
                        <p class="text-gray-600 text-sm sm:text-base">
                            Latest videos from our channel feed.
                            <a href="<?= htmlspecialchars(YOUTUBE_CHANNEL_URL) ?>/videos" class="text-primary-900 font-semibold hover:underline" target="_blank" rel="noopener noreferrer">Full archive on YouTube</a>.
                        </p>
                    <?php endif; ?>
                </div>
                <div class="w-full lg:max-w-md shrink-0">
                    <label for="videoSearch" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Search videos</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="search" id="videoSearch" autocomplete="off" placeholder="Type to filter by title…" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder:text-gray-400 shadow-sm focus:ring-2 focus:ring-accent-400 focus:border-accent-400 outline-none transition-shadow">
                    </div>
                    <p class="mt-2 text-sm text-gray-500" id="videoSearchCount" aria-live="polite"></p>
                </div>
            </div>

            <p class="hidden rounded-xl border border-gray-200 bg-white px-6 py-12 text-center text-gray-600 mb-8" id="videoNoResults" role="status">No videos match your search. Try another word or clear the box.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="videoGrid">
                <?php foreach ($videos as $v): ?>
                    <?php
                    $searchBlob = strtolower($v['title'] . ' ' . $v['video_id']);
                    ?>
                    <article class="video-card group bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow" data-video-search="<?= htmlspecialchars($searchBlob, ENT_QUOTES, 'UTF-8') ?>">
                        <a href="<?= htmlspecialchars($v['url']) ?>" target="_blank" rel="noopener noreferrer" class="block relative aspect-video bg-black">
                            <img src="<?= htmlspecialchars($v['thumbnail']) ?>" alt="" class="w-full h-full object-cover opacity-95 group-hover:opacity-100 transition-opacity" loading="lazy" width="480" height="360">
                            <span class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-colors">
                                <span class="w-14 h-14 rounded-full bg-accent-400 text-primary-900 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                                    <svg class="w-7 h-7 ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                            </span>
                        </a>
                        <div class="p-4">
                            <h2 class="font-semibold text-primary-900 leading-snug line-clamp-2 mb-2">
                                <a href="<?= htmlspecialchars($v['url']) ?>" target="_blank" rel="noopener noreferrer" class="hover:text-accent-700 hover:underline">
                                    <?= htmlspecialchars($v['title']) ?>
                                </a>
                            </h2>
                            <?php if (!empty($v['published'])): ?>
                                <time class="text-xs text-gray-500" datetime="<?= htmlspecialchars($v['published']) ?>">
                                    <?= htmlspecialchars(date('M j, Y', strtotime($v['published']))) ?>
                                </time>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <script>
            (function () {
                var input = document.getElementById('videoSearch');
                var cards = document.querySelectorAll('.video-card');
                var countEl = document.getElementById('videoSearchCount');
                var emptyEl = document.getElementById('videoNoResults');
                var grid = document.getElementById('videoGrid');
                function norm(s) { return (s || '').toLowerCase().trim(); }
                function update() {
                    var q = norm(input && input.value);
                    var n = 0;
                    for (var i = 0; i < cards.length; i++) {
                        var hay = cards[i].getAttribute('data-video-search') || '';
                        var show = !q || hay.indexOf(q) !== -1;
                        cards[i].classList.toggle('hidden', !show);
                        if (show) n++;
                    }
                    if (countEl) {
                        countEl.textContent = n === cards.length
                            ? String(cards.length) + ' video' + (cards.length === 1 ? '' : 's')
                            : String(n) + ' of ' + String(cards.length) + ' videos';
                    }
                    if (emptyEl && grid) {
                        var none = n === 0 && cards.length > 0;
                        emptyEl.classList.toggle('hidden', !none);
                        grid.classList.toggle('hidden', none);
                    }
                }
                if (input) {
                    input.addEventListener('input', update);
                    input.addEventListener('search', update);
                    update();
                }
            })();
            </script>
        <?php endif; ?>
    </div>
</section>

<section class="py-14 bg-primary-900 text-white">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-2xl font-bold mb-3">Subscribe for updates</h2>
        <p class="text-primary-200 mb-6">Never miss new uploads—follow Kikam Technical Institute on YouTube.</p>
        <a href="<?= htmlspecialchars(YOUTUBE_CHANNEL_URL) ?>?sub_confirmation=1" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border-2 border-accent-400 text-accent-400 font-semibold px-6 py-3 hover:bg-accent-400 hover:text-primary-900 transition-colors">
            Subscribe on YouTube
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = 'Videos - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
