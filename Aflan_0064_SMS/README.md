# Society Management System (SMS)

A role-based PHP & MySQL system for managing society members, finances, announcements, and communications.

## Roles & Access

| Role | Dashboard | Capabilities |
|------|-----------|--------------|
| **Admin** | `admin_dashboard.php` | Full control: manage members & users, post announcements, manage secretary/treasurer messages, handle financial records |
| **Secretary** | `secretary_dashboard.php` | Post secretary messages, view announcements |
| **Treasurer** | `treasurer_dashboard.php` | Manage financial accounts & balances, post treasurer messages |
| **Member** | `member_dashboard.php` | View announcements, account balance (read-only), secretary & treasurer messages |

## Pages Overview

### For Everyone
- **Login** (`login.html`) - Authenticates and redirects to your role dashboard
- **Register** (`Register.html`) - Self-registration (defaults to Member role)
- **Logout** (`logout.php`) - Destroys session

### Admin Pages
- **Admin Dashboard** (`admin_dashboard.php`) - Stats overview, quick actions, recent announcements
- **Manage Users** (`manage_users.php`) - Create system users with any role, reset passwords, delete users
- **Add Member** (`add_member.php`) - Register new society members
- **Manage Members** (`manage_members.php`) - View/edit/delete member records
- **View Member** (`view_member.php`) - Full member profile details
- **Announcements** (`announcements.php`) - Post and manage society announcements
- **Secretary Messages** (`secretary_messages.php`) - Post and manage secretary communications
- **Treasurer Messages** (`treasurer_messages.php`) - Post and manage treasurer communications
- **Financial Records** (`financial_records.php`) - Create member accounts and update balances

### Secretary Pages
- **Secretary Dashboard** - Message stats, quick actions, recent messages
- **Secretary Messages** - Post and view secretary-related messages
- **Announcements** - View society announcements

### Treasurer Pages
- **Treasurer Dashboard** - Financial stats overview
- **Financial Records** - Create accounts, update member balances
- **Treasurer Messages** - Post financial/treasury messages

### Member Pages
- **Member Dashboard** - Account balance, latest announcements, secretary & treasurer messages
- **Announcements** - View all announcements
- **Secretary Messages** - View secretary messages
- **Treasurer Messages** - View treasurer messages

## Database Tables

- **`userd`** - System users (login credentials + role assignment)
- **`members`** - Society member records (personal info, membership type, status)
- **`messages`** - All announcements, secretary & treasurer messages (typed)
- **`accounts`** - Member financial accounts (linked by member_id, stores balance)

## Setup Instructions

1. Import `database.sql` into MySQL (creates `userlogin` database and all tables)
2. Update database credentials in `config/database.php` if needed
3. Place the project folder in your web server root (e.g. `htdocs`)
4. Access via `http://localhost/sms-aflan part/`
5. Register a new account (defaults to Member) OR manually set a user as Admin in the database:
   ```sql
   UPDATE userd SET role = 'admin' WHERE id = 1;
   ```

## Security

- PHP sessions for authentication
- Role-based access control (`includes/auth_check.php`) on every page
- Unauthorized URL access redirects to the user's proper dashboard
- Passwords hashed with `password_hash(PASSWORD_DEFAULT)`
- SQL prepared statements prevent injection
- Input sanitized with `htmlspecialchars()`

## File Structure

```
├── config/database.php          Database connection
├── includes/
│   ├── auth_check.php           Session & role validation
│   ├── header.php               HTML head + Bootstrap CDN
│   ├── navbar.php               Top navigation bar (role-aware)
│   ├── sidebar.php              Sidebar menu (role-aware)
│   └── footer.php               Scripts + footer
├── assets/
│   ├── css/style.css            Custom styles
│   └── js/script.js             DataTables, modals, interactions
├── *.html                       Public pages (login, register)
├── *.php                        All protected pages
├── uploads/                     Member profile photos
└── database.sql                 Database schema
```

Developed By Mohamed Aflan
