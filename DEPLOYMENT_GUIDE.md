# WPD Puspamukti - Production Deployment Guide

**Target:** Shared Hosting (Jagoan Hosting / cPanel)  
**Stack:** Laravel 11, MySQL, PHP 8.3+

---

## 📋 Phase 1: Domain & Hosting Setup

### 1.1 Buy Domain
- Recommended: `.id` or `.go.id` (government credibility)
- Registrar: Niagahoster, Dewaweb, IDCloudHost, or directly via PANDI

### 1.2 Point Domain to Jagoan Hosting
```
Nameservers:
ns1.jagoanhosting.com
ns2.jagoanhosting.com
ns3.jagoanhosting.com
```

### 1.3 Verify DNS Propagation
```bash
dig yourdomain.id A
# Or check: https://whatsmydns.net
```

### 1.4 Create MySQL Database (cPanel)
1. **MySQL Databases** → Create Database: `username_wpd_puspamukti`
2. **MySQL Users** → Create User: `username_wpd_user` + **Strong Password**
3. **Add User to Database** → Grant **ALL PRIVILEGES**
4. **Save credentials** securely

### 1.5 Enable SSL (Let's Encrypt)
**cPanel → SSL/TLS → Let's Encrypt SSL** → Issue certificate for domain + www

---

## 📋 Phase 2: Local Environment Preparation

### 2.1 Create Production Environment File
```bash
cp .env.example .env.production
```

### 2.2 Configure `.env.production` (CRITICAL VALUES)

```env
# APPLICATION
APP_NAME="WPD Puspamukti"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.id
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  # Generate below

# DATABASE (from cPanel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_wpd_puspamukti
DB_USERNAME=username_wpd_user
DB_PASSWORD=your_strong_database_password

# SESSION SECURITY (HTTPS REQUIRED)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_PATH=/
SESSION_DOMAIN=null

# CACHE & QUEUE
CACHE_STORE=database
QUEUE_CONNECTION=database

# MAIL (configure with your email provider)
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.id
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.id
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@yourdomain.id"
MAIL_FROM_NAME="${APP_NAME}"

# LOGGING
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null

# OTHER
BCRYPT_ROUNDS=12
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta
VITE_APP_NAME="${APP_NAME}"
```

### 2.3 Generate APP_KEY
```bash
php artisan key:generate --show
# Copy output to APP_KEY in .env.production
```

### 2.4 Build Assets & Optimize
```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Build frontend
npm ci && npm run build

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 2.5 Create Deployment Package
```bash
zip -r deploy.zip . \
  -x "node_modules/*" \
  -x ".git/*" \
  -x "tests/*" \
  -x "storage/logs/*" \
  -x ".env*" \
  -x "*.log" \
  -x "scratch/*" \
  -x "database/database.sqlite" \
  -x "DEPLOYMENT_GUIDE.md" \
  -x "README.md"
```

---

## 📋 Phase 3: Upload & Extract (cPanel File Manager)

1. **Login cPanel** → **File Manager**
2. Navigate to `public_html/`
3. **Upload** `deploy.zip`
4. **Extract** → Files appear in `public_html/`
5. **Move contents** if extracted in subfolder

### 3.1 Set Document Root (Critical!)
**cPanel → Domains → Manage** → Edit:
- **Document Root:** `/home/username/public_html/public`
- Save

### 3.2 Create Storage Symlink
**Option A: cPanel Terminal**
```bash
cd ~/public_html
ln -s ../storage/app/public public/storage
```

**Option B: PHP Script (create `symlink.php` in public_html)**
```php
<?php symlink('/home/username/storage/app/public', '/home/username/public_html/storage'); ?>
```
Then access `https://yourdomain.id/symlink.php` and delete file.

### 3.3 Configure PHP Version & Extensions
**cPanel → Select PHP Version** → Set to **8.3+**
Enable extensions:
- `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `iconv`
- `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `zip`, `intl`

---

## 📋 Phase 4: Post-Deploy Commands (cPanel Terminal / SSH)

```bash
cd ~/public_html

# 1. Ensure .env exists with production values
cp .env.production .env

# 2. Generate key (if not set)
php artisan key:generate --force

# 3. Run migrations
php artisan migrate --force

# 4. Seed roles & permissions
php artisan db:seed --class=RolePermissionSeeder --force

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# 6. Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
chmod 644 .env
```

---

## 📋 Phase 5: Queue Worker Setup (Background Jobs)

### Option A: Cron Job (Recommended for Shared Hosting)
**cPanel → Cron Jobs** → Add:
```bash
* * * * * /usr/local/bin/php /home/username/public_html/artisan schedule:run >> /dev/null 2>&1
* * * * * /usr/local/bin/php /home/username/public_html/artisan queue:work --stop-when-empty --max-time=55 --sleep=3 --tries=3 >> /dev/null 2>&1
```

### Option B: Supervisor (Ask Jagoan Support)
If they support it, request Supervisor config for `queue:work`.

---

## 📋 Phase 6: Security Hardening

### 6.1 Security Headers (Add to `public/.htaccess`)
```apache
# Security Headers
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self'; connect-src 'self';"

# Hide Server Signature
ServerTokens Prod
ServerSignature Off
```

### 6.2 Disable Directory Listing
```apache
Options -Indexes
```

### 6.3 Protect Sensitive Files
```apache
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.lock">
    Order allow,deny
    Deny from all
</Files>
```

---

## 📋 Phase 7: Verification Checklist

| Test | Expected Result |
|------|-----------------|
| `https://yourdomain.id` | Loads RT landing page |
| `https://yourdomain.id/admin-gate` | Shows admin gate page |
| `https://yourdomain.id/login` | Shows login page |
| Admin login | Works with 2-step verification |
| Warga login (NIK + Nama) | Works per RT |
| Surat pengajuan | Create → Submit → PDF download |
| Pengaduan | Submit → Appears in admin dashboard |
| Chat | Warga ↔ Admin real-time |
| Notifications | Bell icon works |
| File uploads | Images/PDFs work |
| Email | Password reset / notifications send |

---

## 📋 Phase 8: Ongoing Maintenance

### Weekly
- Check `storage/logs/laravel.log` for errors
- Verify backup completion

### Monthly
```bash
# Update dependencies (test locally first!)
composer update
npm update
php artisan migrate
```

### Backup Strategy (cPanel)
1. **Backup Wizard** → Full Backup (weekly)
2. **Database Only** → Daily (via cron)
3. **Test Restore** quarterly

---

## 🆘 Troubleshooting Quick Reference

| Error | Fix |
|-------|-----|
| `500 Internal Server Error` | Check `storage/logs/laravel.log`, verify `.env`, check PHP version |
| `No application encryption key` | Run `php artisan key:generate --force` |
| `Database connection failed` | Verify DB credentials in `.env`, check DB host is `localhost` |
| `Permission denied` | `chmod -R 755 storage bootstrap/cache` |
| `Class not found` | `composer dump-autoload -o` |
| `Route not found` | `php artisan route:cache` |
| `Vite manifest not found` | `npm run build` |
| `Storage link broken` | `php artisan storage:link` |
| Queue not processing | Check cron job running, verify `QUEUE_CONNECTION=database` |

---

## 📞 Support Contacts

- **Jagoan Hosting Support:** Live chat / ticket via client area
- **Laravel Docs:** https://laravel.com/docs/11.x/deployment
- **This Project:** Check `scratch/` folder for test scripts

---

## 📝 Notes for Future Updates

1. **Always test locally** with `APP_ENV=production` before deploying
2. **Backup database** before running migrations
3. **Deploy during low traffic** hours
4. **Keep `.env.production`** in secure password manager
5. **Document any custom changes** to this guide

---

*Last Updated: 2026-08-21*  
*Project: WPD Puspamukti - Website Perangkat Desa*