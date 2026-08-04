# Deployment Guide — Jakarta CSIRT Portal

> How to deploy the Laravel project to production.
> Currently configured for SQLite (development). These steps cover production setup.

---

## Prerequisites

- PHP 8.2+
- Composer
- Node.js (for npm, though Vite is not actively used)
- A hosting platform (recommended: **Render.com** for free tier prototype)

---

## Option A: Render.com (Recommended for Prototype)

Render.com has a generous free tier. Render **does not have a native PHP runtime** —
the available runtimes are Docker, Elixir, Go, Node, Python 3, Ruby, Rust — so the
app deploys via the **Docker runtime** using the `Dockerfile` committed at the repo
root. Render's Docker runtime is confirmed working: the "New Web Service" form
auto-detects the `Dockerfile` and auto-fills the repo, branch, and fields.

> **Blocked (as of 2026-08-05):** Render requires card verification even for the
> free plan, and the bank declines the debit card. This is a **bank-side decline,
> not a Render UI bug** — the Stripe card step silently re-prompts with no error
> text after entry (classic bank-decline signature). The card works for streaming
> (e.g., Crunchyroll) but is blocked for the cloud-hosting/stripe.com merchant
> category, even with international payments enabled. Fix options: enable
> per-category toggles in the bank app ("digital goods / online subscriptions"),
> enable 3D-Secure/OTP, whitelist `stripe.com`, or try a virtual card from another
> bank (Jenius/Jago). Cardless fallbacks: **Hetzner + PayPal** (same Dockerfile,
> ~€4/mo), **Zeabur + Wonder Mesh** (zero code change), **Cloudflare Tunnel** (free).

### Dockerfile overview

- Base image `php:8.2-cli` with `pdo_sqlite`, `mbstring`, `zip`, `bcmath` extensions
- `composer install --no-dev --optimize-autoloader` at build
- **`cp .env.example .env`** at build — required, because `php artisan key:generate`
  fails at boot if no `.env` exists (`.env` is git-ignored, so `COPY . .` omits it)
- No npm/Vite — the layout loads assets from `public/` directly
- On container start: `key:generate`, `migrate --force --seed`, `storage:link`,
  then `php artisan serve --host=0.0.0.0 --port=$PORT`

### Steps

1. **Push to GitHub**
   ```bash
   git add Dockerfile .dockerignore
   git commit -m "deploy: add Dockerfile for Render Docker runtime"
   git push origin main
   ```

2. **Create a Render Web Service**
   - Go to [render.com](https://render.com) → New → Web Service
   - Connect your GitHub repo (auto-detected)
   - Settings:
     - **Name:** `csirt-jakarta` (yields `https://csirt-jakarta.onrender.com`)
     - **Language/Runtime:** Docker (auto-detected)
     - **Branch:** `main`
     - **Region:** Singapore (Southeast Asia)
     - **Instance Type:** Free
     - **Docker Command:** leave empty (the Dockerfile `CMD` handles startup)

3. **Environment Variables** (set in Render dashboard):
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://csirt-jakarta.onrender.com
   DB_CONNECTION=sqlite
   ```
   `APP_KEY` is not needed — the Dockerfile CMD regenerates it each boot.
   Optional but recommended — a stable key generated once locally:
   ```
   APP_KEY=base64:...          # Generate with: php artisan key:generate --show
   ```

4. **Verify** — homepage loads, then `/admin/login` with the seeded
   credentials `admin@gmail.com` / `12345678`. Change the admin password after
   deploy (it is public in the repo).

### Limitations (Free Tier)
- Spins down after ~15 minutes of inactivity (first request takes ~30s)
- SQLite file storage resets on redeploy/restart (fine for prototype)
- Uploaded incident proof images (`storage/app/public`) are ephemeral
- No background queue worker (jobs run inline via sync)

---

## Option B: Railway

Similar to Render but with persistent storage.

### Environment Variables
Same as Render, plus:
```
SESSION_DRIVER=database
```

### Advantages over Render
- No spin-down on inactivity
- Persistent SQLite file (doesn't reset on redeploy)

---

## Option C: Traditional VPS (DigitalOcean, Linode, etc.)

### Server Setup

```bash
# Install PHP 8.2 + extensions
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-sqlite3 php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js (optional, for npm build)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs
```

### Deploy

```bash
# Clone repo
git clone your-repo-url
cd new-csirt

# Install dependencies
composer install --optimize-autoloader --no-dev

# Setup
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Nginx Config

```nginx
server {
    listen 80;
    server_name csirt.jakarta.go.id;
    root /var/www/new-csirt/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realroot$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Supervisor (Queue Worker)

If you add queued jobs later:

```ini
[program:csirt-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/new-csirt/artisan queue:listen
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/csirt-queue.log
```

---

## Environment Variables Reference

| Variable | Development | Production |
|---|---|---|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_URL` | `http://localhost:8000` | `https://csirt.jakarta.go.id` |
| `DB_CONNECTION` | `sqlite` | `sqlite` (or `mysql`) |
| `SESSION_DRIVER` | `file` | `file` (or `database`) |
| `QUEUE_CONNECTION` | `sync` | `sync` (or `database`) |

---

## Post-Deployment Checklist

- [ ] `APP_DEBUG=false` — never show stack traces in production
- [ ] `APP_KEY` is set — `php artisan key:generate`
- [ ] `php artisan storage:link` — file uploads work
- [ ] Database is migrated and seeded — `php artisan migrate --seed`
- [ ] Admin login works — `admin@gmail.com` / `12345678`
- [ ] Change admin password before going live
- [ ] HTTPS is enabled (Render/Railway handle this automatically)
- [ ] `APP_URL` matches your actual domain

---

## Switching to MySQL (Optional)

If you need MySQL for production:

1. Update `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=csirt
   DB_USERNAME=root
   DB_PASSWORD=secret
   ```

2. Install MySQL driver:
   ```bash
   composer require symfony/mailer
   ```

3. **Fix the `event` table** — it's a MySQL reserved word. Update the migration to quote it:
   ```php
   Schema::create('event', function (Blueprint $table) {
       // Laravel automatically quotes table names in most cases,
       // but raw queries may need manual quoting: `event`
   });
   ```

4. Run `php artisan migrate:fresh --seed`
