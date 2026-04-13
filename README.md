# Shubharambh Infra Advisors — Website

PHP Core + MySQL rebuild of https://shubharambhinfraadvisors.com/ re-skinned
around the new navy + gold brand identity.

## Stack

- **PHP 8+** (core, no framework)
- **MySQL 8 / MariaDB** via PDO prepared statements
- **Plain HTML / CSS / vanilla JS** — no build step
- **Apache** via XAMPP (local) or shared hosting

## Directory layout

```
shubharambh/
├── config/          # DB credentials, site URL, reCAPTCHA keys  (deny web access)
├── includes/        # db, helpers, settings, recaptcha, rate limit, header, footer
├── sql/             # schema.sql + seed.sql
└── public/          # Apache DocumentRoot
    ├── index.php        # Home
    ├── about.php
    ├── projects.php     # All projects
    ├── project.php      # ?slug=… single project
    ├── contact.php
    ├── privacy-policy.php
    ├── terms.php
    ├── 404.php
    ├── api/             # Form + search endpoints
    ├── assets/css|js|img
    └── uploads/         # project, partner, team images
```

## Local setup (XAMPP on macOS)

1. **Start XAMPP** — open the XAMPP control panel, start Apache + MySQL.

2. **Create the database**
   - Open `http://localhost/phpmyadmin/`
   - New → database name `shubharambh_db`, collation `utf8mb4_unicode_ci`
   - Import `sql/schema.sql`
   - Import `sql/seed.sql`

3. **Make the site accessible via Apache**

   Symlink the `public` folder into XAMPP's htdocs so `http://localhost/shubharambh/`
   serves the site:

   ```bash
   ln -s /Users/deepanshu/Desktop/shubharambh/public /Applications/XAMPP/htdocs/shubharambh
   ```

   (If XAMPP lives elsewhere on your machine, adjust the target path.)

   You may need to enable symlinks in the XAMPP Apache config. Edit
   `/Applications/XAMPP/etc/httpd.conf` and ensure the `<Directory "...htdocs">`
   block contains `Options Indexes FollowSymLinks Includes ExecCGI` and
   `AllowOverride All`. Then restart Apache.

4. **Configure credentials** — edit `config/config.php`:

   ```php
   define('DB_HOST', '127.0.0.1');
   define('DB_NAME', 'shubharambh_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');           // XAMPP default
   define('SITE_URL', 'http://localhost/shubharambh');
   define('RECAPTCHA_SITE_KEY', ''); // leave blank in local dev
   define('RECAPTCHA_SECRET',   ''); // leave blank in local dev
   ```

5. **Load the site** — visit `http://localhost/shubharambh/`.

## reCAPTCHA v3 (optional but recommended in production)

1. Register a new site at https://www.google.com/recaptcha/admin (type v3).
2. Paste the two keys into `config/config.php`.
3. If left blank, the form still works; server-side verification is simply skipped.

## Managing content (no admin panel)

All dynamic content lives in MySQL. Use phpMyAdmin (or any SQL client) to
update:

| Table           | What's in it                                |
| --------------- | ------------------------------------------- |
| `site_settings` | Contact details, socials, hero copy, about  |
| `projects`      | Featured properties                         |
| `testimonials`  | Client reviews                              |
| `partners`      | Developer logos                             |
| `team_members`  | Team bios                                   |
| `inquiries`     | Form submissions (read-only)                |

To feature/hide a project on the home page, toggle `is_featured = 1/0`.
To change the order, set `sort_order` (lower = shown first).

**Inquiries**: every form submission lands in the `inquiries` table. Check
for new leads by running:

```sql
SELECT id, full_name, phone, email, project_name, message, created_at
FROM inquiries
WHERE status = 'new'
ORDER BY created_at DESC;
```

Mark them as read:

```sql
UPDATE inquiries SET status='read' WHERE id = 42;
```

## Deploying to production (cPanel / shared hosting)

1. Create a MySQL database in cPanel and import `sql/schema.sql` + `sql/seed.sql`.
2. Upload the project files:
   - Contents of `public/*` → `public_html/`
   - `config/`, `includes/`, `sql/` → one level **above** `public_html/`
     (e.g. `/home/youruser/shubharambh_private/`).
3. Update `require_once` paths if you moved `config/` and `includes/` out of
   the project root, OR keep the whole tree under `public_html/` and rely on
   the `.htaccess` files inside `config/`, `includes/`, `sql/` to block
   direct access (simpler, less secure but fine for v1).
4. Edit `config/config.php`: production DB credentials, real site URL,
   reCAPTCHA keys, `APP_ENV='production'`.
5. Force HTTPS in `public/.htaccess` (add a `RewriteRule` redirect).
6. Verify:
   - `/includes/db.php` returns 403
   - `/config/config.php` returns 403
   - `/uploads/anything.php` returns 403 or raw text (never executes)

## Security features

- PDO prepared statements on every query
- HTML escaping via `e()` helper on every echo of user data
- Session cookies: HttpOnly, Secure, SameSite=Lax
- CSRF token validated on every form submission
- Honeypot field + reCAPTCHA v3 + IP rate limit on contact forms
- `.htaccess` denies direct access to config, includes, SQL, and PHP execution in uploads
- Security headers via `public/.htaccess`

## Customising the brand

Colors live in `public/assets/css/style.css` at the top under `:root`:

```css
--c-navy:  #0B1D33;   /* primary background */
--c-gold:  #B59355;   /* accent */
--c-white: #FFFFFF;
```

Fonts are Playfair Display (serif headings) + Inter (body), loaded from
Google Fonts in `includes/header.php`.

## License

© Shubharambh Infra Advisors. All rights reserved.
