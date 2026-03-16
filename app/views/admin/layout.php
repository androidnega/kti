<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - <?= APP_NAME ?></title>
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
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary-900 text-white flex-shrink-0">
            <div class="p-6 flex items-center gap-3">
                <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Logo" class="h-8 w-auto">
                <div>
                    <h1 class="text-xl font-bold">KTI Admin</h1>
                    <p class="text-primary-200 text-xs"><?= Auth::user()['name'] ?></p>
                </div>
            </div>
            <nav class="mt-6">
                <a href="<?= ADMIN_URL ?>?action=dashboard" class="block px-6 py-3 hover:bg-secondary-700 transition-colors <?= ($_GET['action'] ?? 'dashboard') === 'dashboard' ? 'bg-secondary-700 border-l-4 border-primary-500' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </span>
                </a>
                <a href="<?= ADMIN_URL ?>?action=pages" class="block px-6 py-3 hover:bg-secondary-700 transition-colors <?= in_array($_GET['action'] ?? '', ['pages', 'page_create', 'page_edit']) ? 'bg-secondary-700 border-l-4 border-primary-500' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Pages
                    </span>
                </a>
                <a href="<?= ADMIN_URL ?>?action=staff" class="block px-6 py-3 hover:bg-secondary-700 transition-colors <?= in_array($_GET['action'] ?? '', ['staff', 'staff_create', 'staff_edit']) ? 'bg-secondary-700 border-l-4 border-primary-500' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Staff
                    </span>
                </a>
                <a href="<?= ADMIN_URL ?>?action=programs" class="block px-6 py-3 hover:bg-secondary-700 transition-colors <?= in_array($_GET['action'] ?? '', ['programs', 'program_create', 'program_edit']) ? 'bg-secondary-700 border-l-4 border-primary-500' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Programs
                    </span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-64 p-6 border-t border-secondary-700">
                <a href="<?= APP_URL ?>" target="_blank" class="block text-secondary-400 hover:text-white mb-3 text-sm">
                    View Website →
                </a>
                <a href="<?= ADMIN_URL ?>?action=logout" class="block text-red-400 hover:text-red-300 text-sm">
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
</body>
</html>
