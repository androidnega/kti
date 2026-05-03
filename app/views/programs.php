<?php ob_start(); ?>

<!-- Programs Header -->
<section class="relative bg-primary-900 text-white py-24 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-60 z-0"></div>
    <div class="absolute inset-0 bg-[url('<?= APP_URL ?>/assets/images/vocational.jpg')] bg-cover bg-center mix-blend-overlay z-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Programs</h1>
        <p class="text-xl text-primary-100 max-w-3xl mx-auto font-light">
            Explore our technical and vocational departments. Each card links to photos, videos, and more detail.
        </p>
    </div>
</section>

<!-- Programs Grid & Filters -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if (!empty($faculties)): ?>
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button type="button" class="filter-btn active px-6 py-2 rounded-full border border-primary-900 text-primary-900 hover:bg-primary-900 hover:text-white transition-all font-medium" data-filter="all">
                All faculties
            </button>
            <?php foreach ($faculties as $frow):
                $fname = $frow['faculty'] ?? '';
                if ($fname === '') {
                    continue;
                }
                $filterClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $fname));
                ?>
                <button type="button" class="filter-btn px-6 py-2 rounded-full border border-gray-300 text-gray-600 hover:border-primary-900 hover:text-primary-900 transition-all font-medium" data-filter="<?= htmlspecialchars($filterClass) ?>">
                    <?= htmlspecialchars($fname) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($programsByFaculty)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="programs-grid">
                <?php foreach ($programsByFaculty as $facultyName => $programs):
                    $filterClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $facultyName));
                    foreach ($programs as $program):
                        $slug = $program['slug'] ?? '';
                        $href = $slug !== '' ? (APP_URL . '?url=program/' . rawurlencode($slug)) : '#';
                        $img = !empty($program['cover_image'])
                            ? APP_URL . '/' . ltrim($program['cover_image'], '/')
                            : APP_URL . '/assets/images/vocational.jpg';
                        ?>
                        <a href="<?= htmlspecialchars($href) ?>" class="program-card card shadow-sm hover:shadow-md border border-gray-200 flex flex-col h-full bg-white transition-all duration-200 overflow-hidden group <?= $slug === '' ? 'pointer-events-none opacity-60' : '' ?>"
                           data-category="<?= htmlspecialchars($filterClass) ?>">
                            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                                <img src="<?= htmlspecialchars($img) ?>" alt="" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-80"></div>
                                <span class="absolute top-3 left-3 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/90 text-primary-800">
                                    <?= htmlspecialchars($facultyName) ?>
                                </span>
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-semibold text-primary-600 mb-2 group-hover:text-primary-800">
                                    <?= htmlspecialchars($program['name']) ?>
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed flex-grow line-clamp-4">
                                    <?= htmlspecialchars($program['description'] ?? '') ?>
                                </p>
                                <?php if ($slug !== ''): ?>
                                    <span class="mt-4 inline-flex items-center text-sm font-semibold text-accent-600 group-hover:text-accent-700">
                                        View department
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                <p class="text-xl text-gray-600">No programs available at this time.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-800 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="<?= APP_URL ?>/assets/images/school-building.jpg" alt="" class="w-full h-full object-cover">
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl font-bold mb-4">Questions about our programs?</h2>
        <p class="text-xl text-primary-100 mb-8">Contact us to learn more about training at KTI and what we offer.</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn bg-accent-400 text-primary-900 hover:bg-accent-500 px-8 py-3 text-lg font-bold">
            Contact Us Today
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = 'Programs - ' . APP_NAME;
require __DIR__ . '/layout.php';
?>
