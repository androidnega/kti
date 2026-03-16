# Kikam Technical Institute Website

Official website for Kikam Technical Institute built with PHP, Tailwind CSS, and SQLite.

## Features

- **Clean & Fast**: Minimal design with optimized performance
- **Admin Panel**: Full content management system
- **Responsive Design**: Works on all devices
- **SQLite Database**: Lightweight, file-based database
- **Tailwind CSS**: Local build, no CDN dependencies

## Requirements

- PHP 8.2 or higher
- PDO SQLite extension enabled
- Node.js (for Tailwind CSS build)
- Apache with mod_rewrite or Nginx

## Installation

1. **Clone or download** this project to your web server directory:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/KTI
   ```

2. **Install Tailwind CSS dependencies**:
   ```bash
   npm install
   ```

3. **Build Tailwind CSS**:
   ```bash
   npm run build
   ```

4. **Set permissions** for the storage directory:
   ```bash
   chmod -R 755 storage
   ```

5. **Configure Apache**: Ensure `.htaccess` files are enabled and `mod_rewrite` is active.

6. **Access the website**:
   - Public site: `http://localhost/KTI/public`
   - Admin panel: `http://localhost/KTI/admin`

## Default Admin Credentials

- **Email**: admin@kti.edu
- **Password**: admin123

**⚠️ Change these credentials immediately after first login!**

## Project Structure

```
KTI/
├── public/              # Public web root
│   ├── index.php       # Main entry point
│   └── assets/         # CSS, JS, images
├── app/
│   ├── controllers/    # Application controllers
│   ├── models/         # Database models
│   ├── views/          # View templates
│   └── helpers/        # Helper classes
├── admin/              # Admin panel
│   ├── index.php       # Admin entry point
│   └── login.php       # Admin login
├── storage/            # Database and uploads
│   └── database.sqlite # SQLite database
├── config/             # Configuration files
│   ├── config.php      # App configuration
│   └── database.sql    # Database schema
└── tailwind.config.js  # Tailwind CSS config
```

## Development

### Watch Tailwind CSS for changes:
```bash
npm run watch
```

### Build for production:
```bash
npm run build
```

## Admin Panel Features

- **Pages Management**: Create and manage website pages
- **Staff Management**: Add and organize faculty/staff
- **Programs Management**: Manage academic programs
- **Dashboard**: Overview of site statistics

## Database Schema

- **users**: Admin users and authentication
- **pages**: Website pages with custom content
- **sections**: Page sections for structured content
- **staff**: Faculty and staff information
- **programs**: Academic programs and courses

## Security Notes

1. Change default admin credentials
2. Set proper file permissions
3. Keep PHP and dependencies updated
4. Use HTTPS in production
5. Regular database backups

## Customization

### Update Site Name
Edit `config/config.php`:
```php
define('APP_NAME', 'Your Institute Name');
```

### Modify Colors
Edit `tailwind.config.js` to customize the color scheme.

### Add Custom Pages
Use the admin panel to create new pages with custom slugs.

## Troubleshooting

### Database not initializing
- Check storage directory permissions
- Ensure PDO SQLite extension is enabled

### CSS not loading
- Run `npm run build`
- Check file paths in templates

### Rewrite rules not working
- Enable `mod_rewrite` in Apache
- Check `.htaccess` files are being read

## Support

For issues or questions, contact the development team.

## License

© 2026 Kikam Technical Institute. All rights reserved.
