<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Panel') ?> - <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= APP_URL ?>/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fafafa', 100: '#f4f4f5', 200: '#e4e4e7', 300: '#d4d4d8',
                            400: '#a1a1aa', 500: '#71717a', 600: '#3f3f46', 700: '#27272a',
                            800: '#18181b', 900: '#09090b',
                        },
                        accent: {
                            50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d',
                            400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309',
                            800: '#92400e', 900: '#78350f',
                        },
                        secondary: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1',
                            400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155',
                            800: '#1e293b', 900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .input {
                @apply w-full border border-slate-300 bg-white text-slate-900 outline-none transition;
                @apply focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20;
            }
            .label {
                @apply block text-sm font-medium text-slate-700 mb-1.5;
            }
            .btn {
                @apply inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold transition;
            }
            .btn-primary {
                @apply bg-primary-900 text-white hover:bg-black;
            }
            .btn-secondary {
                @apply bg-slate-200 text-slate-800 hover:bg-slate-300;
            }
            .card {
                @apply rounded-xl border border-slate-200 bg-white p-6;
            }
        }
    </style>
</head>
<body class="flex h-[100dvh] max-h-[100dvh] flex-col overflow-hidden bg-slate-50 text-slate-900 antialiased">
    <!-- Mobile top bar -->
    <header class="z-40 flex shrink-0 items-center justify-between gap-3 border-b border-slate-800 bg-primary-900 px-4 py-3 text-white lg:hidden">
        <button type="button" id="admin-sidebar-open" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/15 bg-white/5 text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-accent-400" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open menu">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <span class="truncate text-sm font-semibold tracking-tight">KTI Admin</span>
        <a href="<?= ADMIN_URL ?>?action=logout" class="shrink-0 rounded-lg px-3 py-2 text-xs font-medium text-red-200 hover:bg-white/10 hover:text-white">Logout</a>
    </header>

    <!-- Backdrop when mobile menu open -->
    <div id="admin-sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-black/40 lg:hidden" aria-hidden="true"></div>

    <div class="flex min-h-0 flex-1 overflow-hidden lg:flex-row">
        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 flex w-[min(100vw,18rem)] -translate-x-full flex-col border-r border-slate-800 bg-primary-900 text-white transition-transform duration-200 ease-out lg:static lg:z-0 lg:h-full lg:w-64 lg:min-h-0 lg:translate-x-0 lg:flex-shrink-0" aria-label="Admin navigation">
            <div class="flex shrink-0 items-center gap-3 border-b border-white/10 p-5">
                <img src="<?= APP_URL ?>/assets/images/logo.png" alt="" class="h-9 w-auto object-contain" width="36" height="36">
                <div class="min-w-0">
                    <p class="truncate text-lg font-bold leading-tight">KTI Admin</p>
                    <p class="truncate text-xs text-primary-200"><?= htmlspecialchars(Auth::user()['name'] ?? '') ?></p>
                </div>
                <button type="button" id="admin-sidebar-close" class="ml-auto inline-flex h-9 w-9 items-center justify-center rounded-lg text-white/80 hover:bg-white/10 lg:hidden" aria-label="Close menu">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <nav class="min-h-0 flex-1 space-y-0.5 overflow-y-auto overscroll-contain px-3 py-4">
                <?php
                $a = $_GET['action'] ?? 'dashboard';
                $nav = function ($href, $label, $icon, $active) {
                    $cls = $active
                        ? 'border-accent-400 bg-white/10 text-white'
                        : 'border-transparent text-primary-100 hover:border-white/10 hover:bg-white/5 hover:text-white';
                    echo '<a href="' . htmlspecialchars($href) . '" class="flex items-center gap-3 rounded-xl border-l-4 px-3 py-3 text-sm font-medium transition-colors ' . $cls . '">';
                    echo '<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/10 text-accent-300">' . $icon . '</span>';
                    echo '<span>' . htmlspecialchars($label) . '</span></a>';
                };
                $nav(ADMIN_URL . '?action=dashboard', 'Dashboard', '<i class="fa-solid fa-chart-line text-sm"></i>', $a === 'dashboard');
                $nav(ADMIN_URL . '?action=pages', 'Pages', '<i class="fa-regular fa-file-lines text-sm"></i>', in_array($a, ['pages', 'page_create', 'page_edit'], true));
                $nav(ADMIN_URL . '?action=staff', 'Staff', '<i class="fa-solid fa-users text-sm"></i>', in_array($a, ['staff', 'staff_create', 'staff_edit'], true));
                $nav(ADMIN_URL . '?action=programs', 'Programs', '<i class="fa-solid fa-graduation-cap text-sm"></i>', in_array($a, ['programs', 'program_create', 'program_edit', 'program_media_upload', 'program_video_upload', 'program_video_url_save', 'program_media_reorder', 'program_media_delete', 'program_media_set_cover', 'program_media_caption_save'], true));
                ?>
            </nav>
            <div class="shrink-0 border-t border-white/10 p-4">
                <a href="<?= APP_URL ?>" target="_blank" rel="noopener" class="mb-2 flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm text-primary-200 hover:bg-white/5 hover:text-white">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-70"></i>
                    View website
                </a>
                <a href="<?= ADMIN_URL ?>?action=logout" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm text-red-300 hover:bg-red-950/40 hover:text-red-100">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    Logout
                </a>
            </div>
        </aside>

        <main class="min-h-0 min-w-0 flex-1 overflow-y-auto overscroll-y-contain bg-slate-50">
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <script>
    (function () {
        var sidebar = document.getElementById('admin-sidebar');
        var openBtn = document.getElementById('admin-sidebar-open');
        var closeBtn = document.getElementById('admin-sidebar-close');
        var backdrop = document.getElementById('admin-sidebar-backdrop');
        function openMenu() {
            if (!sidebar) return;
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.documentElement.classList.add('overflow-hidden');
            if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
        }
        function closeMenu() {
            if (!sidebar) return;
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.documentElement.classList.remove('overflow-hidden');
            if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
        }
        if (openBtn) openBtn.addEventListener('click', openMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);
        if (backdrop) backdrop.addEventListener('click', closeMenu);
        document.querySelectorAll('#admin-sidebar a[href*="action="]').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.matchMedia('(max-width: 1023px)').matches) closeMenu();
            });
        });
    })();
    </script>
</body>
</html>
