# E-Learning Portal - Starter

## What is included
- Minimal PHP + MySQL starter for the Capstone E-Learning Portal.
- Registration with OTP (OTP is emailed using PHP mail(); on many hosts you must configure SMTP or use PHPMailer).
- Simple AJAX search, Chart.js enrollments chart API.
- SQL schema at `sql/schema.sql`.

## Quick setup
1. Import `sql/schema.sql` into your MySQL server (creates `elearn` DB).
2. Update DB credentials in `src/config.php`.
3. Place contents of `public/` into your web root (e.g., `public_html`).
4. Ensure `src/` is accessible by the PHP files (relative include paths used).
5. For OTP emails, configure mail or replace `mail()` with PHPMailer + SMTP.

## Notes
- This is a starter kit. Add CSRF protection, input validation, prepared statements (PDO prepared statements are used), and HTTPS before production use.
- To create an admin: after registering, update `users` table `role` to 'admin' using SQL.
