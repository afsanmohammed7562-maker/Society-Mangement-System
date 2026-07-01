# Society Management System

A PHP/MySQL web application for managing a housing society or community. Features role-based admin panel for President, Secretary, and Treasurer, plus a member-facing portal.

## Tech Stack

- **Backend:** PHP (PDO)
- **Database:** MySQL
- **Frontend:** Bootstrap 5, Font Awesome 6
- **Server:** XAMPP (Apache + MySQL + PHP)

## Setup Instructions

1. Copy the project folder to your XAMPP `htdocs` directory.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open phpMyAdmin and import `SQL_query.sql` (creates the `society_management` database).
4. Access the application at `http://localhost/society-management64/`.

## Admin Login Credentials

All admins share the password: **`admin123`**

| Username    | Role        |
|-------------|-------------|
| `president` | President   |
| `secretary` | Secretary   |
| `treasurer` | Treasurer   |

## User Login Credentials

| Username | Password | Role  |
|----------|----------|-------|
| `john`   | `user123`| Member|

> Run `http://localhost/society-management64/setup_passwords.php` to reset all passwords to defaults.

## Features

- **President:** Dashboard, member management, view all modules
- **Secretary:** Post notices, upload monthly secretary reports
- **Treasurer:** Generate monthly bills, record payments, upload treasurer reports
- **Members:** View notices, gallery, contact admin, check payment status, download reports
- **Gallery:** Upload and manage community event photos
- **Messages:** Contact form with admin reply
- **Remember Me:** Persistent login via cookies

## Database Config

`includes/db.php` — Host: `localhost`, DB: `society_management`, User: `root`, Password: *(empty)*
