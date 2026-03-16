<?php

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/helpers/Database.php';
require_once APP_PATH . '/helpers/Auth.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (Auth::login($username, $password)) {
        // Redirect to unified dashboard route after successful login
        header('Location: http://localhost/KTI/dashboard');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}

// Redirect if already logged in
if (Auth::check()) {
    header('Location: http://localhost/KTI/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= APP_NAME ?></title>
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
    <style type="text/tailwindcss">
        @layer components {
            .btn { @apply px-4 py-2 rounded-lg font-medium transition-all duration-200; }
            .btn-primary { @apply bg-primary-600 text-white hover:bg-primary-700 shadow-sm hover:shadow-md; }
            .card { @apply bg-white rounded-xl shadow-sm border border-gray-200; }
            .input { @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-sm; }
            .label { @apply block text-xs font-medium text-gray-700 mb-1; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="card max-w-sm w-full p-6">
        <div class="text-center mb-6">
            <img src="<?= APP_URL ?>/assets/images/logo.png" alt="KTI Logo" class="h-12 w-auto mx-auto mb-3">
            <h1 class="text-xl font-bold text-gray-900 mb-1">Admin Sign In</h1>
            <p class="text-gray-500 text-xs tracking-wide uppercase font-semibold">Kikam Technical Institute</p>
        </div>

        <?php if (isset($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg mb-4 text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-3">
            <div>
                <label class="label">Username</label>
                <input type="text" name="username" required class="input" placeholder="Enter your username">
            </div>
            <div>
                <label class="label">Password</label>
                <input type="password" name="password" required class="input" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary w-full py-2.5 text-sm">
                Sign In
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="<?= APP_URL ?>" class="text-xs text-gray-500 hover:text-primary-600">
                ← Back to Website
            </a>
        </div>
    </div>
</body>
</html>
