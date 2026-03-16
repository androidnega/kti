# Kikam Technical Institute Website

Modern responsive website and admin panel for Kikam Technical Institute, built with PHP, Tailwind CSS, and SQLite.

## Features

- **Clean & Fast**: Optimized layout and assets for quick load times
- **Admin Panel**: Manage pages, staff profiles, and programs
- **Responsive Design**: Works smoothly on mobile, tablet, and desktop
- **SQLite Database**: Lightweight, file-based storage

## Requirements

- PHP 8.2 or higher with PDO SQLite
- Apache (with `mod_rewrite`) or compatible web server

## Basic Setup

1. Upload the project to your server, keeping the folder structure.
2. Point your domain’s **document root** to the `public/` directory.
3. Ensure the `storage/` directory is writable by the web server.
4. Update `config/config.php` with your production URLs (for example `https://kikamtech.org`).

The SQLite database and default admin user are created automatically from `config/database.sql` when the app runs for the first time.

## Project Structure

```text
KTI/
├── public/              # Public web root (index.php, assets)
├── app/                 # Controllers, models, views, helpers
├── admin/               # Admin panel entry points
├── storage/             # Database and uploads
├── config/              # App and database configuration
└── tailwind.config.js   # Tailwind CSS configuration
```

## Security Notes

- Change any default admin account created from the database seed after deployment.
- Keep file permissions restrictive on `config/` and `storage/`.
- Use HTTPS in production and keep PHP and dependencies up to date.
