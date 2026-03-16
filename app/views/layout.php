<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <meta name="description" content="<?= $description ?? 'Kikam Technical Institute - Providing demand-driven technical education since 1963' ?>">
    <link rel="icon" type="image/x-icon" href="<?= APP_URL ?>/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#e6ebf5',
                            100: '#ccd8eb',
                            200: '#99b3d6',
                            300: '#668ec2',
                            400: '#3369ad',
                            500: '#004499',
                            600: '#00367a',
                            700: '#002366', // School Navy
                            800: '#001a4d',
                            900: '#001133',
                        },
                        accent: {
                            50: '#fffdf0',
                            100: '#fffbc2',
                            200: '#fff694',
                            300: '#ffe566',
                            400: '#ffd700', // School Gold
                            500: '#e6c200',
                            600: '#b39700',
                            700: '#806b00',
                            800: '#4d4000',
                            900: '#1a1500',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
          body {
            @apply bg-gray-50 text-gray-900;
          }
        }
        @layer components {
          .btn {
            @apply px-4 py-2 rounded-lg font-medium transition-all duration-200;
          }
          .btn-primary {
            @apply bg-primary-600 text-white hover:bg-primary-700 shadow-sm hover:shadow-md;
          }
          .btn-accent {
            @apply bg-accent-400 text-primary-900 hover:bg-accent-500 shadow-sm hover:shadow-md font-bold;
          }
          .btn-secondary {
            @apply bg-secondary-200 text-secondary-800 hover:bg-secondary-300;
          }
          .btn-danger {
            @apply bg-red-600 text-white hover:bg-red-700;
          }
          .card {
            @apply bg-white rounded-xl shadow-sm border border-gray-200 p-6;
          }
          .input {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all;
          }
          .label {
            @apply block text-sm font-medium text-gray-700 mb-2;
          }
          .hero {
            @apply bg-gradient-to-br from-primary-800 to-primary-900 text-white;
          }
          .section-title {
            @apply text-3xl font-bold text-gray-900 mb-4;
          }
          .nav-link {
            @apply text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200;
          }
        }
        @layer utilities {
          .glass {
            @apply bg-white/80 backdrop-blur-sm;
          }
          .text-balance {
            text-wrap: balance;
          }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <a href="<?= APP_URL ?>" class="flex-shrink-0 flex items-center gap-3">
                        <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Kikam Technical Institute" class="h-12 w-auto object-contain">
                        <div class="flex flex-col">
                            <span class="text-xl md:text-2xl font-bold text-primary-800 leading-none tracking-tight">KTI</span>
                            <span class="text-[0.65rem] font-bold text-accent-600 tracking-widest uppercase">Supreme Kimtech</span>
                        </div>
                    </a>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="<?= APP_URL ?>" class="nav-link">Home</a>
                    <a href="<?= APP_URL ?>?url=history" class="nav-link">History</a>
                    <a href="<?= APP_URL ?>?url=programs" class="nav-link">Programs</a>
                    <a href="<?= APP_URL ?>?url=staff" class="nav-link">Staff</a>
                    <a href="<?= APP_URL ?>?url=contact" class="nav-link">Contact</a>
                </div>
                <div>
                    <a href="<?= ADMIN_URL ?>" class="btn btn-primary text-sm bg-primary-800 hover:bg-primary-900">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-primary-900 text-white mt-auto border-t border-primary-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Column 1: Brand -->
                <div class="flex flex-col h-full md:border-r md:border-white/10 md:pr-8">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="<?= APP_URL ?>/assets/images/logo.png" alt="KTI Logo" class="h-10 w-auto">
                        <div>
                            <h3 class="text-lg font-bold leading-tight">Kikam Technical Institute</h3>
                            <p class="text-xs text-accent-400 font-medium tracking-wider uppercase">Supreme Kimtech</p>
                        </div>
                    </div>
                    <p class="text-primary-200 text-sm leading-relaxed mb-4">
                        Providing quality technical and vocational education to the youth of Ghana since 1963. We are committed to excellence in skills training and character development.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="flex flex-col h-full md:border-r md:border-white/10 md:px-8">
                    <h4 class="font-bold text-base mb-4 text-accent-400">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-primary-200">
                        <li><a href="<?= APP_URL ?>" class="hover:text-white hover:underline transition-colors">Home</a></li>
                        <li><a href="<?= APP_URL ?>?url=history" class="hover:text-white hover:underline transition-colors">History</a></li>
                        <li><a href="<?= APP_URL ?>?url=programs" class="hover:text-white hover:underline transition-colors">Programs</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div class="flex flex-col h-full md:pl-8">
                    <h4 class="font-bold text-base mb-4 text-accent-400">Contact Us</h4>
                    <ul class="space-y-3 text-sm text-primary-200">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-accent-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>P.O. Box 4, Kikam<br>Western Region, Ghana</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-accent-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:Kimtechmail@yahoo.com" class="hover:text-white transition-colors">Kimtechmail@yahoo.com</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-accent-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:+233546561424" class="hover:text-white transition-colors">+233 54 656 1424</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Copyright Row -->
            <div class="border-t border-primary-800 mt-8 pt-6 text-center">
                <p class="text-primary-300 text-xs text-balance">
                    &copy; <?= date('Y') ?> Kikam Technical Institute. All rights reserved.
                </p>
            </div>
            </div>
        </div>
    </footer>
</body>
</html>
