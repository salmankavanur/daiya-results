# Daiya Results Management System

A comprehensive web-based results management system built with Laravel, designed for managing and publishing examination results efficiently.

## 🚀 Quick Start

1. Install dependencies: `composer install` and `npm install`
2. Copy environment file: `cp .env.example .env`
3. Generate application key: `php artisan key:generate`
4. Run migrations and seed the database: `php artisan migrate --seed`
   - *Default Admin Login:* `test@example.com` / `password`
5. Compile assets: `npm run dev`
6. Start development server: `php artisan serve`

## 🌟 Features

- **Results Management**: Upload, update, and manage student examination results securely.
- **Student Dashboard**: Portal for students to view and download their results.
- **Admin Panel**: Comprehensive dashboard for administrators to manage courses, students, and marks.
- **Data Export/Import**: Export and import marks via Excel/CSV (e.g., *MARK LIST OF DAIYA EVEN SEMESTER EXAMINATION*).
- **Role-based Access Control**: Different access levels for Admins, Teachers, and Students.
- **Responsive Design**: Mobile-friendly user interface built with modern CSS frameworks (Tailwind CSS).

## 💻 Technology Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Blade Templates, Tailwind CSS, JavaScript
- **Database**: MySQL / MariaDB
- **Build Tool**: Vite

## ⚙️ Environment Configuration

For deployment and local setup, copy the environment variables below into your `.env` file:

```env
APP_NAME="Daiya Results Management System"
APP_ENV=production
APP_KEY=base64:your_generated_app_key_here
APP_DEBUG=false
APP_URL=https://your-production-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=daiya_results_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## 🌐 Production Level Deployment Guide

Deploying the Daiya Results Management System requires a robust server setup. Below is the guide for deploying to a standard Linux server (e.g., Ubuntu) using Nginx and PHP-FPM.

### 1. Pre-deployment Steps

1. SSH into your server.
2. Ensure PHP 8.x, Composer, Nginx, and MySQL are installed.
3. Clone the repository into your web directory (e.g., `/var/www/daiya-results`).

```bash
cd /var/www
git clone <repository-url> daiya-results
cd daiya-results
```

### 2. Application Setup

1. **Install Composer Dependencies**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

2. **Install NPM Dependencies & Build Assets**:
   ```bash
   npm install
   npm run build
   ```

3. **Environment Variables**:
   Create the `.env` file with your **Production** values.
   ```bash
   cp .env.example .env
   nano .env
   # Update DB credentials, APP_ENV=production, APP_DEBUG=false
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations & Seed**:
   ```bash
   php artisan migrate --force --seed
   ```
   > ⚠️ **Security Note:** The seeder creates a default admin account with email `test@example.com` and password `password`. **Change this immediately** after your first login!

6. **Optimize Configuration & Routes**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### 3. Directory Permissions

Ensure your application has the correct permissions to write cache, uploads, and logs. Create the log file and set required permissions:

```bash
# Navigate to the project root
cd /var/www/daiya-results

# Set ownership to the web server user (e.g., www-data for Nginx/Apache)
sudo chown -R www-data:www-data .

# Set proper directory permissions
sudo find . -type d -exec chmod 755 {} \;
sudo find . -type f -exec chmod 644 {} \;

# Set write permissions for storage and bootstrap/cache
sudo chmod -R ugo+rw storage
touch storage/logs/laravel.log
sudo chmod -R ugo+rw storage
sudo chmod -R ugo+rw bootstrap/cache
```

### 4. Reverse Proxy Setup (Nginx)

To serve your application on port 80/443, configure an Nginx server block:

```nginx
server {
    listen 80;
    server_name your-production-domain.com;
    root /var/www/daiya-results/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock; # adjust PHP version accordingly
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/daiya-results /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 📂 Project Structure

```
├── app/                    # Controllers, Models, Middleware
├── bootstrap/              # App bootstrap and cache
├── config/                 # Application configuration
├── database/               # Migrations, Seeders, Factories
├── public/                 # Web root, compiled assets (index.php)
├── resources/              # Blade views, raw CSS/JS assets
├── routes/                 # Web and API routes
├── storage/                # Logs, compiled views, file uploads
└── tests/                  # PHPUnit tests
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## Google Sheet Sync

You can configure and sync results directly from the admin dashboard.

### Manual sync

1. Open Dashboard as admin.
2. Add or update Google Sheet URL in Google Sheet Sync Settings.
3. Save sync settings.
3. Click Sync Google Sheet Now.

The sheet should be viewable by link (public or shared with "Anyone with the link can view").

### Automatic sync (dynamic interval)

Set these from the admin dashboard:

1. Enable Auto Sync.
2. Set Sync Interval (minutes), such as 10, 15, 30, etc.

Ensure Laravel scheduler is running on the server:

```bash
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

The app scheduler checks every minute and runs sync only when the configured interval is due.
