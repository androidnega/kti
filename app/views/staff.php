<?php ob_start(); ?>

<!-- Staff Header -->
<section class="relative bg-primary-900 text-white py-20 sm:py-24 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-60 z-0"></div>
    <div class="absolute inset-0 bg-[url('<?= APP_URL ?>/assets/images/entrance.jpg')] bg-cover bg-center mix-blend-overlay z-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <p class="mb-3 text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-accent-400">Our team</p>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 text-balance">The people behind Kikam</h1>
        <p class="text-base sm:text-lg text-primary-100 max-w-3xl mx-auto font-light leading-relaxed">
            Meet our dedicated team of experienced educators and professionals committed to your success.
        </p>
    </div>
</section>

<!-- Leadership Feature -->
<section class="border-b border-slate-200 bg-white py-12 sm:py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-flex items-center gap-2 rounded-full bg-accent-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-accent-700 sm:text-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-500"></span>
                Leadership
            </span>
            <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-primary-900 sm:text-4xl">Meet our leadership</h2>
            <p class="mx-auto mt-3 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Guided by the values of <span class="font-semibold text-primary-900">Leadership, Integrity and Excellence</span>, our leadership team keeps Kikam focused on delivering practical training and shaping confident, work-ready students.
            </p>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-3 sm:gap-6 md:gap-8">
            <?php
            $leaders = [
                [
                    'name' => 'Mr. Emmanuel A. Anomah',
                    'role' => 'Principal',
                    'image' => 'assets/images/principal.jpg',
                    'alt' => 'Portrait of Mr. Emmanuel A. Anomah, Principal',
                    'focus' => '50% 50%',
                ],
                [
                    'name' => 'Mr. David K. Jekpo',
                    'role' => 'Vice Principal, Academics',
                    'image' => 'assets/images/vp-academics.jpg',
                    'alt' => 'Portrait of Mr. David K. Jekpo, Vice Principal, Academics',
                    'focus' => '50% 22%',
                ],
                [
                    'name' => 'Mr. Charles L. Saalidong',
                    'role' => 'Vice Principal, Administration',
                    'image' => 'assets/images/vp-administration.jpg',
                    'alt' => 'Portrait of Mr. Charles L. Saalidong, Vice Principal, Administration',
                    'focus' => '50% 25%',
                ],
            ];
            foreach ($leaders as $leader):
                $src = rtrim(APP_URL, '/') . '/' . ltrim($leader['image'], '/');
                $focus = $leader['focus'] ?? '50% 50%';
            ?>
            <article class="group mx-auto w-full max-w-[14rem] text-center sm:max-w-[16rem]">
                <div class="relative aspect-[3/4] overflow-hidden rounded-xl bg-slate-100 shadow-md ring-1 ring-black/5">
                    <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($leader['alt']) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" style="object-position: <?= htmlspecialchars($focus) ?>;" loading="lazy" decoding="async">
                </div>
                <div class="mt-4">
                    <h3 class="text-base font-bold text-primary-900 sm:text-lg"><?= htmlspecialchars($leader['name']) ?></h3>
                    <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-accent-700 sm:text-xs">
                        <?= htmlspecialchars($leader['role']) ?>
                    </p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="mt-10 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
            <a href="<?= APP_URL ?>?url=history" class="inline-flex items-center justify-center rounded-full bg-primary-900 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-black">
                Our history
            </a>
            <a href="<?= APP_URL ?>?url=contact" class="inline-flex items-center justify-center rounded-full border border-primary-900/20 bg-white px-6 py-2.5 text-sm font-semibold text-primary-900 transition hover:border-primary-900/40 hover:bg-slate-50">
                Contact the office
            </a>
        </div>
    </div>
</section>

<!-- Staff by Department -->
<?php if (!empty($staffByDept)): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php foreach ($staffByDept as $department => $staffMembers): ?>
                <div class="mb-20 last:mb-0">
                    <h2 class="text-3xl font-bold text-gray-900 mb-10 border-l-4 border-primary-600 pl-4">
                        <?= htmlspecialchars($department) ?> Department
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($staffMembers as $member): ?>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-md transition-shadow">
                                <div class="w-24 h-24 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-6 text-primary-600 ring-4 ring-primary-50/50">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    <?= htmlspecialchars($member['name']) ?>
                                </h3>
                                <?php if (!empty($member['role'])): ?>
                                    <p class="text-primary-600 font-semibold mb-1 uppercase text-xs tracking-wider">
                                        <?= htmlspecialchars($member['role']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($member['rank'])): ?>
                                    <p class="text-gray-500 text-sm italic">
                                        <?= htmlspecialchars($member['rank']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = 'Staff - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
