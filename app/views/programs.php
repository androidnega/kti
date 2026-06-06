<?php
require_once APP_PATH . '/helpers/DepartmentIcons.php';
ob_start();

$totalPrograms = 0;
foreach (($programsByFaculty ?? []) as $list) {
    $totalPrograms += is_array($list) ? count($list) : 0;
}
$facultyCount = is_array($programsByFaculty ?? null) ? count($programsByFaculty) : 0;
?>

<section class="relative bg-primary-900 text-white py-20 sm:py-24 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-60 z-0"></div>
    <div class="absolute inset-0 bg-[url('<?= APP_URL ?>/assets/images/vocational.jpg')] bg-cover bg-center mix-blend-overlay z-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <p class="mb-3 text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] text-accent-400">Our departments</p>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 text-balance">Hands-on training across <?= (int) $totalPrograms ?> Kikam departments</h1>
        <p class="text-base sm:text-lg text-primary-100 max-w-3xl mx-auto font-light leading-relaxed">
            Pick a department to see what students learn, the workshop facilities they use, and photos of their day-to-day work.
        </p>
        <?php if ($totalPrograms > 0): ?>
        <div class="mt-7 inline-flex flex-wrap items-center justify-center gap-2 text-xs sm:text-sm text-primary-100">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-400"></span>
                <?= (int) $totalPrograms ?> departments
            </span>
            <?php if ($facultyCount > 0): ?>
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-400"></span>
                <?= (int) $facultyCount ?> faculties
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-12 sm:py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if (!empty($faculties)): ?>
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10 sm:mb-12">
            <button type="button" class="filter-btn active px-4 sm:px-6 py-2 rounded-full border border-primary-900 text-primary-900 hover:bg-primary-900 hover:text-white transition-all text-sm font-medium" data-filter="all">
                All faculties
            </button>
            <?php foreach ($faculties as $frow):
                $fname = $frow['faculty'] ?? '';
                if ($fname === '') {
                    continue;
                }
                $filterClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $fname));
                ?>
                <button type="button" class="filter-btn px-4 sm:px-6 py-2 rounded-full border border-gray-300 text-gray-600 hover:border-primary-900 hover:text-primary-900 transition-all text-sm font-medium" data-filter="<?= htmlspecialchars($filterClass) ?>">
                    <?= htmlspecialchars($fname) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($programsByFaculty)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" id="programs-grid">
                <?php foreach ($programsByFaculty as $facultyName => $programs):
                    $filterClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $facultyName));
                    foreach ($programs as $program):
                        $slug = $program['slug'] ?? '';
                        $href = $slug !== '' ? (APP_URL . '?url=program/' . rawurlencode($slug)) : '#';
                        $img = !empty($program['cover_image'])
                            ? APP_URL . '/' . ltrim($program['cover_image'], '/')
                            : APP_URL . '/assets/images/vocational.jpg';
                        $deptName = $program['department'] ?? $program['name'];
                        $icon = DepartmentIcons::for($deptName, $facultyName);
                        ?>
                        <a href="<?= htmlspecialchars($href) ?>"
                           class="program-card group relative flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-primary-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent-400 focus:ring-offset-2 <?= $slug === '' ? 'pointer-events-none opacity-60' : '' ?>"
                           data-category="<?= htmlspecialchars($filterClass) ?>">
                            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($program['name']) ?>" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.04]" loading="lazy" decoding="async">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/15 to-transparent"></div>
                                <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-primary-800 shadow-sm sm:text-xs">
                                    <span class="h-1.5 w-1.5 rounded-full bg-accent-400"></span>
                                    <?= htmlspecialchars($facultyName) ?>
                                </span>
                                <span class="absolute -bottom-6 right-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-accent-200 bg-white text-primary-700 shadow-md ring-1 ring-black/5 transition-colors group-hover:bg-accent-400 group-hover:text-primary-900 sm:h-16 sm:w-16" aria-hidden="true">
                                    <span class="h-7 w-7 sm:h-8 sm:w-8">
                                        <?= $icon ?>
                                    </span>
                                </span>
                            </div>
                            <div class="flex flex-1 flex-col p-5 sm:p-6 pt-7 sm:pt-8">
                                <h3 class="text-lg sm:text-xl font-semibold text-primary-700 group-hover:text-primary-900 leading-snug">
                                    <?= htmlspecialchars($program['name']) ?>
                                </h3>
                                <p class="mt-2 line-clamp-4 flex-1 text-sm leading-relaxed text-gray-600">
                                    <?= htmlspecialchars($program['description'] ?? '') ?>
                                </p>
                                <?php if ($slug !== ''): ?>
                                    <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-accent-600 group-hover:text-accent-700">
                                        View department
                                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const buttons = document.querySelectorAll('.filter-btn');
                    const cards = document.querySelectorAll('.program-card');
                    const activeClass = ['bg-primary-900', 'text-white', 'border-primary-900'];

                    function setActive(btn) {
                        btn.classList.remove('text-gray-600', 'text-primary-900', 'bg-white', 'border-gray-300');
                        activeClass.forEach(function (c) { btn.classList.add(c); });
                    }

                    function setInactive(btn) {
                        activeClass.forEach(function (c) { btn.classList.remove(c); });
                        btn.classList.add('bg-white', 'border-gray-300', 'text-gray-600', 'hover:text-primary-900', 'hover:border-primary-900');
                    }

                    buttons.forEach(function(btn) {
                        if (btn.classList.contains('active')) {
                            setActive(btn);
                        } else {
                            setInactive(btn);
                        }

                        btn.addEventListener('click', function() {
                            buttons.forEach(function(b) {
                                b.classList.remove('active');
                                setInactive(b);
                            });
                            btn.classList.add('active');
                            setActive(btn);

                            var filterValue = btn.getAttribute('data-filter');
                            cards.forEach(function(card) {
                                if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                                    card.style.display = '';
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                        });
                    });
                });
            </script>

        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-xl text-gray-600">No departments available at this time.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-16 bg-primary-800 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="<?= APP_URL ?>/assets/images/school-building.jpg" alt="" class="w-full h-full object-cover">
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl font-bold mb-4">Questions about our departments?</h2>
        <p class="text-base sm:text-lg text-primary-100 mb-8">Contact us to learn more about training at KTI and the courses we offer.</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn bg-accent-400 text-primary-900 hover:bg-accent-500 px-8 py-3 text-lg font-bold">
            Contact us today
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = 'Departments - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
