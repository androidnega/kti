<?php ob_start(); ?>

<!-- Hero Section -->
<section class="hero relative py-32 bg-cover bg-center" style="background-image: url('<?= APP_URL ?>/assets/images/droneshotcampus.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-70"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 text-balance">
            Welcome to Kikam Technical Institute
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto">
            Providing demand-driven technical education since 1963 - Judge Us By Our Deeds
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= APP_URL ?>?url=programs" class="btn bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-md text-lg transition-colors border-2 border-primary-600">
                Explore Programs
            </a>
            <a href="<?= APP_URL ?>?url=contact" class="btn bg-white hover:bg-gray-100 text-primary-900 px-8 py-3 rounded-md text-lg transition-colors border-2 border-white">
                Contact Us
            </a>
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
            <div class="relative">
                <div class="absolute inset-0 bg-primary-600 rounded-2xl transform rotate-3 scale-105 opacity-10"></div>
                <img src="<?= APP_URL ?>/assets/images/students-auto.jpg" alt="Students in workshop" class="relative rounded-2xl shadow-xl w-full h-[400px] object-cover">
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
            <div class="card hover:shadow-lg transition-shadow border border-gray-200">
                <h3 class="text-xl font-semibold text-primary-600 mb-3"><?= htmlspecialchars($program['name']) ?></h3>
                <p class="text-sm text-primary-500 mb-3 font-medium"><?= htmlspecialchars($program['department']) ?></p>
                <p class="text-gray-600 line-clamp-3"><?= htmlspecialchars($program['description']) ?></p>
            </div>
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
        <h2 class="text-4xl font-bold mb-4">Ready to Start Your Journey?</h2>
        <p class="text-xl text-primary-100 mb-8">Join thousands of students who have transformed their careers with KTI</p>
        <a href="<?= APP_URL ?>?url=contact" class="btn bg-accent-400 text-primary-900 hover:bg-accent-500 px-8 py-3 text-lg font-bold">
            Get Started Today
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
$title = APP_NAME . ' - Home';
require __DIR__ . '/layout.php';
?>
