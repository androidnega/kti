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

        <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 sm:gap-10">
            <?php
            $leaders = [
                [
                    'name' => 'Mr. Emmanuel A. Anomah',
                    'role' => 'Principal',
                    'image' => 'assets/images/principal.jpg',
                    'alt' => 'Portrait of Mr. Emmanuel A. Anomah, Principal',
                ],
                [
                    'name' => 'Vice Principal, Academics',
                    'role' => 'Academics',
                    'image' => 'assets/images/vp-academics.jpg',
                    'alt' => 'Portrait of the Vice Principal, Academics',
                ],
            ];
            foreach ($leaders as $leader):
                $src = rtrim(APP_URL, '/') . '/' . ltrim($leader['image'], '/');
            ?>
            <article class="group mx-auto w-full max-w-sm text-center sm:max-w-none">
                <div class="relative aspect-[3/4] overflow-hidden rounded-2xl bg-slate-100 shadow-xl ring-1 ring-black/5">
                    <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($leader['alt']) ?>" class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy" decoding="async">
                </div>
                <div class="mt-5">
                    <h3 class="text-xl font-bold text-primary-900 sm:text-2xl"><?= htmlspecialchars($leader['name']) ?></h3>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.15em] text-accent-700 sm:text-sm">
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
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($staffByDept)): ?>
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
        <?php else: ?>
            <div class="text-center py-20">
                <div class="bg-white rounded-2xl p-12 max-w-md mx-auto shadow-sm border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-gray-900">Staff info coming soon</p>
                    <p class="text-gray-500 mt-2">We are currently updating our faculty directory.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-800 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="<?= APP_URL ?>/assets/images/girls domitory.jpg" alt="" class="w-full h-full object-cover">
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl font-bold mb-4">Join Our Team</h2>
        <p class="text-xl text-primary-100 mb-8">We're always looking for passionate educators and professionals</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn bg-accent-400 text-primary-900 hover:bg-accent-500 px-8 py-3 text-lg font-bold">
            Get In Touch
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = 'Staff - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
