<?php ob_start(); ?>

<!-- Programs Header -->
<section class="relative bg-primary-900 text-white py-24 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-60 z-0"></div>
    <div class="absolute inset-0 bg-[url('<?= APP_URL ?>/assets/images/vocational.jpg')] bg-cover bg-center mix-blend-overlay z-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Programs</h1>
        <p class="text-xl text-primary-100 max-w-3xl mx-auto font-light">
            Explore our comprehensive range of technical and vocational programs designed to meet Ghana's skilled manpower needs
        </p>
    </div>
</section>

<!-- Programs Grid & Filters -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Buttons -->
        <?php if (!empty($departments)): ?>
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button class="filter-btn active px-6 py-2 rounded-full border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white transition-all font-medium" data-filter="all">
                All Departments
            </button>
            <?php foreach ($departments as $dept): ?>
                <button class="filter-btn px-6 py-2 rounded-full border border-gray-300 text-gray-600 hover:border-primary-600 hover:text-primary-600 transition-all font-medium" data-filter="<?= htmlspecialchars(strtolower(str_replace(' ', '-', $dept['department']))) ?>">
                    <?= htmlspecialchars($dept['department']) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Programs Grid -->
        <?php if (!empty($programsByDept)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="programs-grid">
                <?php foreach ($programsByDept as $department => $programs): 
                    $filterClass = strtolower(str_replace(' ', '-', $department));
                ?>
                    <?php foreach ($programs as $program): ?>
                        <div class="program-card card shadow-none hover:shadow-none border border-gray-200 flex flex-col h-full bg-white transition-all duration-200" data-category="<?= htmlspecialchars($filterClass) ?>">
                            <div class="mb-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700">
                                    <?= htmlspecialchars($department) ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-primary-600 mb-3">
                                <?= htmlspecialchars($program['name']) ?>
                            </h3>
                            <p class="text-gray-600 leading-relaxed flex-grow">
                                <?= htmlspecialchars($program['description']) ?>
                            </p>
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <a href="<?= APP_URL ?>?url=contact" class="text-primary-600 font-medium hover:text-primary-800 inline-flex items-center group">
                                    Apply Now
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- JavaScript for Filtering -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const buttons = document.querySelectorAll('.filter-btn');
                    const cards = document.querySelectorAll('.program-card');
                    
                    // Simple active class toggle style
                    const activeClass = ['bg-primary-600', 'text-white', 'border-primary-600'];
                    const inactiveClass = ['bg-white', 'text-gray-600', 'border-gray-300', 'text-primary-600'];

                    function setActive(btn) {
                        btn.classList.remove('text-gray-600', 'text-primary-600', 'bg-white', 'border-gray-300');
                        btn.classList.add(...activeClass);
                    }

                    function setInactive(btn) {
                         btn.classList.remove(...activeClass);
                         btn.classList.add('bg-white', 'border-gray-300');
                         btn.classList.add('text-gray-600');
                         btn.classList.add('hover:text-primary-600', 'hover:border-primary-600');
                    }

                    // Initialize
                    buttons.forEach(btn => {
                        if(btn.classList.contains('active')) {
                            setActive(btn);
                        } else {
                            setInactive(btn);
                        }

                        btn.addEventListener('click', () => {
                            buttons.forEach(b => {
                                b.classList.remove('active');
                                setInactive(b);
                            });
                            
                            btn.classList.add('active');
                            setActive(btn);
                            
                            const filterValue = btn.getAttribute('data-filter');
                            
                            cards.forEach(card => {
                                if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                                card.style.display = 'flex';
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
        <h2 class="text-4xl font-bold mb-4">Ready to Apply?</h2>
        <p class="text-xl text-primary-100 mb-8">Join us and start your journey towards a successful technical career</p>
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
