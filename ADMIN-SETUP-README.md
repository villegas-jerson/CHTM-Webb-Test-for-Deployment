# CHTM Admin Panel — Setup Guide

This adds a real PHP + MySQL backend so you can log in and edit **Announcements**
and **Events** (text and images) from the browser, with changes saved to a database.

## Requirements
- A web host or local server with PHP 8+ and MySQL/MariaDB (e.g. XAMPP, WAMP, or
  your school's hosting).
- The `uploads/` folder must be writable by the web server.

## Setup steps

1. **Create the database.**
   Import `schema.sql` into MySQL. Locally with XAMPP/phpMyAdmin, use the "Import"
   tab and select the file, or from a terminal:
   ```
   mysql -u root -p < schema.sql
   ```
   This creates the `chtm_admin` database, its tables, and seeds:
   - One admin login: **username `admin`, password `chtm2026`**
   - The two existing announcement cards and two existing event blocks, so the
     pages aren't empty on first load.

2. **Edit `config.php`** with your actual database host/username/password if
   they differ from the defaults (`localhost` / `root` / no password — typical
   for XAMPP).

3. **Upload everything** (all files in this folder, keeping the folder
   structure — including `includes/` and `uploads/`) to your web server.

4. **Log in** at `Admin.html` with `admin` / `chtm2026`, then change your
   password by editing the `admin_users` row (or ask me to add a
   "change password" screen).

## What changed on the site

- `Admin.html` — the login form now submits to `login.php`, which checks the
  username/password against the database (hashed, not stored in plain text).
- `login.php` / `logout.php` — handle sessions.
- `admin_dashboard.php` — the page you land on after logging in, with links to
  manage Announcements and Events.
- `admin_announcements.php` — add, edit, and delete announcement cards
  (title, body text, image, display order).
- `admin_events.php` — add, edit, and delete events, and manage each event's
  photo slideshow (upload multiple photos, delete individual photos).
- `Annoucement.php` (replaces `Annoucement.html`) and `Events.php` (replaces
  `Events.html`) — now pull their content live from the database instead of
  being hardcoded, so your edits show up immediately for every visitor.
- Navigation links across `Home.html`, `About.html`, `YoungLeaders.html`, and
  `Admin.html` were updated to point to the new `.php` pages.
- `uploads/announcements/` and `uploads/events/` — where uploaded images are
  stored (a `.htaccess` blocks any script files from ever running there, as a
  safety measure).

## Notes

- Uploaded images are validated to be JPG/PNG/WEBP/GIF and under 5MB.
- Deleting an announcement or event also deletes its uploaded image file(s)
  from disk.
- The `admin` password can be changed by generating a new hash:
  ```
  php -r "echo password_hash('your-new-password', PASSWORD_DEFAULT);"
  ```
  then updating the `password_hash` column for that user in `admin_users`.
- This was tested locally end-to-end (login, add/edit/delete with image
  upload for both Announcements and Events, invalid-file rejection, and
  session protection on admin pages) before being handed to you.
