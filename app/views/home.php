<?php
ob_start();
$heroSlides = isset($heroSlides) && is_array($heroSlides) ? $heroSlides : [];
if (empty($heroSlides)) {
    $heroSlides = [
        [
            'image_path' => 'assets/images/hero-workshop.png',
            'alt_text' => 'Students learning lathe operations at Kikam Technical Institute workshop',
            'caption' => '',
        ],
        [
            'image_path' => 'assets/images/hero-electrical.jpg',
            'alt_text' => 'Electrical engineering students wiring a contactor in the Kikam workshop',
            'caption' => '',
        ],
    ];
}
$slideCount = count($heroSlides);
?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-accent-50 text-primary-900">
    <div class="pointer-events-none absolute -top-32 -left-24 h-72 w-72 rounded-full bg-accent-200/50 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -right-24 h-80 w-80 rounded-full bg-primary-200/40 blur-3xl"></div>

    <div class="relative mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-4 py-14 sm:px-6 sm:py-16 md:gap-12 md:py-20 lg:grid-cols-12 lg:gap-12 lg:px-8 lg:py-24">
        <div class="order-1 lg:col-span-5">
            <div class="relative mx-auto w-full max-w-sm sm:max-w-md lg:max-w-none">
                <div class="absolute -inset-3 hidden rounded-3xl bg-gradient-to-br from-accent-300/40 to-primary-300/30 blur-xl lg:block" aria-hidden="true"></div>
                <style>
                    #hero-slider .hero-slide {
                        opacity: 0;
                        transition: opacity 900ms cubic-bezier(0.4, 0, 0.2, 1);
                        pointer-events: none;
                    }
                    #hero-slider .hero-slide.is-active {
                        opacity: 1;
                        pointer-events: auto;
                    }
                    #hero-slider .hero-slide__img {
                        transform: scale(1.08);
                        transition: transform 6500ms ease-out;
                        will-change: transform;
                    }
                    #hero-slider .hero-slide.is-active .hero-slide__img {
                        transform: scale(1);
                    }
                    @media (prefers-reduced-motion: reduce) {
                        #hero-slider .hero-slide,
                        #hero-slider .hero-slide__img { transition: none !important; }
                        #hero-slider .hero-slide__img { transform: none !important; }
                    }
                </style>

                <div
                    id="hero-slider"
                    class="relative aspect-[4/3] overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-black/5"
                    data-auto="<?= $slideCount > 1 ? '1' : '0' ?>"
                    data-interval="5500"
                    role="region"
                    aria-roledescription="carousel"
                    aria-label="Kikam Technical Institute highlights"
                >
                    <?php foreach ($heroSlides as $idx => $slide):
                        $isFirst = $idx === 0;
                        $src = !empty($slide['image_path'])
                            ? rtrim(APP_URL, '/') . '/' . ltrim($slide['image_path'], '/')
                            : rtrim(APP_URL, '/') . '/assets/images/hero-workshop.png';
                        $alt = !empty($slide['alt_text']) ? $slide['alt_text'] : ($slide['caption'] ?? 'Kikam Technical Institute');
                    ?>
                        <figure
                            class="hero-slide absolute inset-0 <?= $isFirst ? 'is-active' : '' ?>"
                            data-index="<?= (int) $idx ?>"
                            aria-hidden="<?= $isFirst ? 'false' : 'true' ?>"
                        >
                            <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($alt) ?>" class="hero-slide__img h-full w-full object-cover" <?= $isFirst ? 'loading="eager" fetchpriority="high"' : 'loading="lazy" decoding="async"' ?>>
                            <?php if (!empty($slide['caption'])): ?>
                                <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/55 via-black/15 to-transparent p-4 sm:p-5">
                                    <span class="text-sm font-semibold text-white sm:text-base"><?= htmlspecialchars($slide['caption']) ?></span>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endforeach; ?>

                    <?php if ($slideCount > 1): ?>
                        <div class="absolute bottom-3 left-1/2 z-10 flex -translate-x-1/2 items-center gap-1.5">
                            <?php foreach ($heroSlides as $idx => $_): ?>
                                <button
                                    type="button"
                                    class="hero-dot h-1.5 rounded-full bg-white/60 ring-1 ring-black/10 transition-all duration-500 hover:bg-white <?= $idx === 0 ? 'w-7 bg-white' : 'w-1.5' ?>"
                                    data-index="<?= (int) $idx ?>"
                                    aria-label="Show slide <?= (int) $idx + 1 ?>"
                                ></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($slideCount > 1): ?>
                <script>
                (function () {
                    var slider = document.getElementById('hero-slider');
                    if (!slider) return;
                    var slides = Array.prototype.slice.call(slider.querySelectorAll('.hero-slide'));
                    var dots = Array.prototype.slice.call(slider.querySelectorAll('.hero-dot'));
                    if (slides.length < 2) return;
                    var current = 0;
                    var interval = parseInt(slider.getAttribute('data-interval'), 10) || 5500;
                    var auto = slider.getAttribute('data-auto') === '1';
                    var timer = null;
                    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                    function show(next) {
                        next = ((next % slides.length) + slides.length) % slides.length;
                        if (next === current) return;
                        slides[current].classList.remove('is-active');
                        slides[current].setAttribute('aria-hidden', 'true');
                        slides[next].classList.add('is-active');
                        slides[next].setAttribute('aria-hidden', 'false');
                        if (dots.length) {
                            dots[current].classList.remove('w-7', 'bg-white');
                            dots[current].classList.add('w-1.5', 'bg-white/60');
                            dots[next].classList.add('w-7', 'bg-white');
                            dots[next].classList.remove('w-1.5', 'bg-white/60');
                        }
                        current = next;
                    }

                    function tick() { show(current + 1); }

                    function start() {
                        if (!auto || prefersReducedMotion) return;
                        stop();
                        timer = setInterval(tick, interval);
                    }
                    function stop() {
                        if (timer) { clearInterval(timer); timer = null; }
                    }

                    dots.forEach(function (dot) {
                        dot.addEventListener('click', function () {
                            var i = parseInt(dot.getAttribute('data-index'), 10) || 0;
                            show(i);
                            start();
                        });
                    });

                    slider.addEventListener('mouseenter', stop);
                    slider.addEventListener('mouseleave', start);
                    slider.addEventListener('focusin', stop);
                    slider.addEventListener('focusout', start);
                    document.addEventListener('visibilitychange', function () {
                        if (document.hidden) stop(); else start();
                    });

                    start();
                })();
                </script>
                <?php endif; ?>
            </div>
        </div>

        <div class="order-2 lg:col-span-7">
            <span class="inline-flex items-center gap-2 rounded-full border border-primary-900/10 bg-white/70 px-4 py-1.5 text-[11px] font-semibold uppercase tracking-[0.2em] text-primary-700 backdrop-blur sm:text-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-500"></span>
                Established 1963
            </span>

            <h1 class="mt-6 text-balance text-3xl font-bold leading-[1.1] tracking-tight text-primary-900 sm:text-4xl md:text-5xl lg:text-6xl">
                Train today. Skill tomorrow. <span class="text-accent-600">Succeed always.</span>
            </h1>

            <p class="mt-5 max-w-xl text-base leading-relaxed text-primary-700 sm:text-lg">
                Hands-on technical and vocational training in the Western Region of Ghana — preparing students with the skills industry actually needs.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:gap-4">
                <a href="<?= APP_URL ?>?url=departments" class="inline-flex items-center justify-center rounded-full bg-primary-900 px-7 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-black focus:outline-none focus:ring-2 focus:ring-primary-700 focus:ring-offset-2 focus:ring-offset-accent-50">
                    Explore departments
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="<?= APP_URL ?>?url=contact" class="inline-flex items-center justify-center rounded-full border border-primary-900/20 bg-white/70 px-7 py-3 text-base font-semibold text-primary-900 backdrop-blur transition hover:border-primary-900/40 hover:bg-white focus:outline-none focus:ring-2 focus:ring-primary-700 focus:ring-offset-2 focus:ring-offset-accent-50">
                    Contact us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold mb-6 text-primary-600">About Our Institute</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Kikam Technical Institute is a premier technical institution committed to equipping students with practical skills and knowledge for the modern industrial world. Founded in 1963, we have a long history of excellence in technical capability and vocational training.
                </p>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Located in the Western Region of Ghana, we offer a serene academic environment conducive for learning. Our programs are designed to meet industry standards and bridge the gap between education and employment.
                </p>
                <a href="<?= APP_URL ?>?url=history" class="text-primary-600 font-semibold hover:text-primary-800 inline-flex items-center">
                    Read More About Us
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
            <div class="relative w-full">
                <div class="absolute inset-0 bg-primary-600 rounded-2xl transform rotate-3 scale-105 opacity-10 pointer-events-none hidden md:block"></div>
                <div
                    id="home-video"
                    class="group relative aspect-video cursor-pointer overflow-hidden rounded-2xl bg-black shadow-xl"
                    data-video-id="_fgBVzVGSFU"
                    data-video-title="Kikam Technical Institute"
                    role="button"
                    tabindex="0"
                    aria-label="Play video about Kikam Technical Institute"
                >
                    <img
                        src="https://i.ytimg.com/vi/_fgBVzVGSFU/hqdefault.jpg"
                        alt=""
                        class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300 group-hover:opacity-90"
                        loading="lazy"
                        decoding="async"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/95 text-primary-900 shadow-lg ring-1 ring-black/10 transition-transform duration-200 group-hover:scale-105 sm:h-20 sm:w-20" aria-hidden="true">
                            <svg class="ml-1 h-7 w-7 sm:h-8 sm:w-8" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                    </div>
                </div>
                <script>
                (function () {
                    var wrap = document.getElementById('home-video');
                    if (!wrap) return;
                    var loaded = false;

                    function load(autoplay) {
                        if (loaded) return;
                        loaded = true;
                        var id = wrap.getAttribute('data-video-id');
                        var title = wrap.getAttribute('data-video-title') || 'Video';
                        var params = ['rel=0', 'modestbranding=1', 'playsinline=1'];
                        if (autoplay) {
                            params.push('autoplay=1');
                            params.push('mute=1');
                        }
                        var src = 'https://www.youtube.com/embed/' + encodeURIComponent(id) + '?' + params.join('&');
                        var iframe = document.createElement('iframe');
                        iframe.setAttribute('src', src);
                        iframe.setAttribute('title', title);
                        iframe.setAttribute('class', 'absolute inset-0 h-full w-full border-0');
                        iframe.setAttribute('allow', 'autoplay; accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
                        iframe.setAttribute('allowfullscreen', '');
                        wrap.innerHTML = '';
                        wrap.style.cursor = 'default';
                        wrap.appendChild(iframe);
                    }

                    wrap.addEventListener('click', function () { load(true); });
                    wrap.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            load(true);
                        }
                    });

                    if ('IntersectionObserver' in window) {
                        var io = new IntersectionObserver(function (entries) {
                            entries.forEach(function (entry) {
                                if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
                                    load(true);
                                    io.disconnect();
                                }
                            });
                        }, { threshold: [0.5] });
                        io.observe(wrap);
                    }
                })();
                </script>
            </div>
        </div>
    </div>
</section>

<!-- Impact in Numbers (Moved from Footer/History) -->
<section class="py-16 bg-primary-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="p-6 border border-white/10 rounded-lg">
                <div class="text-4xl font-bold text-accent-400 mb-2">2,698</div>
                <div class="text-sm text-primary-200">Total Students</div>
            </div>
             <div class="p-6 border border-white/10 rounded-lg">
                <div class="text-4xl font-bold text-accent-400 mb-2">60</div>
                <div class="text-sm text-primary-200">Teaching Staff</div>
            </div>
             <div class="p-6 border border-white/10 rounded-lg">
                <div class="text-4xl font-bold text-accent-400 mb-2">32</div>
                <div class="text-sm text-primary-200">Non-Teaching Staff</div>
            </div>
             <div class="p-6 border border-white/10 rounded-lg">
                <div class="text-4xl font-bold text-accent-400 mb-2">12+</div>
                <div class="text-sm text-primary-200">Departments</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="section-title text-center text-primary-900">Why Choose KTI?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <div class="card text-center hover:shadow-lg transition-shadow border-none shadow-sm bg-gray-50">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Established 1963</h3>
                <p class="text-gray-600">Over 60 years of excellence in technical and vocational education</p>
            </div>
            <div class="card text-center hover:shadow-lg transition-shadow border-none shadow-sm bg-gray-50">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Modern Facilities</h3>
                <p class="text-gray-600">Equipped through Oil and Gas Capacity Building Project</p>
            </div>
            <div class="card text-center hover:shadow-lg transition-shadow border-none shadow-sm bg-gray-50">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">12+ Departments</h3>
                <p class="text-gray-600">Comprehensive TVET programs for Ghana's skilled manpower needs</p>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<?php if (!empty($programs)): ?>
<section class="py-16 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="section-title text-center text-primary-900">Featured Programs</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <?php foreach ($programs as $program): ?>
            <?php
            $slug = trim((string) ($program['slug'] ?? ''));
            $cover = !empty($program['cover_image'])
                ? rtrim(APP_URL, '/') . '/' . ltrim($program['cover_image'], '/')
                : rtrim(APP_URL, '/') . '/assets/images/vocational.jpg';
            $cardClass = 'card hover:shadow-lg transition-shadow border border-gray-200';
            ?>
            <?php if ($slug !== ''): ?>
            <a href="<?= htmlspecialchars(APP_URL . '?url=program/' . rawurlencode($slug)) ?>" class="block <?= $cardClass ?> text-left no-underline text-inherit focus:outline-none focus:ring-2 focus:ring-primary-500">
            <?php else: ?>
            <div class="<?= $cardClass ?>">
            <?php endif; ?>
                <div class="aspect-[16/10] w-full overflow-hidden rounded-lg bg-gray-100 mb-4 -mt-1">
                    <img src="<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($program['name']) ?>" class="h-full w-full object-cover" loading="lazy">
                </div>
                <h3 class="text-xl font-semibold text-primary-600 mb-3"><?= htmlspecialchars($program['name']) ?></h3>
                <p class="text-sm text-primary-500 mb-3 font-medium"><?= htmlspecialchars($program['department'] ?? '') ?></p>
                <p class="text-gray-600 line-clamp-3"><?= htmlspecialchars($program['description'] ?? '') ?></p>
            <?php if ($slug !== ''): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-12">
            <a href="<?= APP_URL ?>?url=programs" class="btn btn-primary">View All Programs</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-16 bg-primary-800 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="<?= APP_URL ?>/assets/images/entrance.jpg" alt="" class="w-full h-full object-cover">
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl font-bold mb-4">Want to know more?</h2>
        <p class="text-xl text-primary-100 mb-8">Reach out for information about our campus, programs, and institute.</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn bg-accent-400 text-primary-900 hover:bg-accent-500 px-8 py-3 text-lg font-bold">
            Contact Us
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = APP_NAME . ' - Home';
require __DIR__ . '/layout.php';
?>
