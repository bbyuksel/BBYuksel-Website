<<<<<<< HEAD
# Academic Website with Admin Panel

## Directory Structure

```
WebSite/
├── admin/
│   ├── includes/
│   │   └── admin-header.php
│   ├── dashboard.php
│   ├── index.php
│   ├── logout.php
│   ├── news-manage.php
│   ├── profile-edit.php
│   ├── projects-manage.php
│   ├── publications-manage.php
│   └── users-manage.php
├── css/
│   ├── admin.css
│   └── style.css
├── includes/
│   ├── config.php
│   ├── db.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
├── uploads/
├── database.sql
├── index.php
├── projects.php
├── publications.php
└── README.md
```

## Setup Instructions

1. **Database Setup**
   - Create a new MySQL database
   - Import the `database.sql` file into your database
   - Update the database credentials in `includes/config.php`

2. **Configuration**
   - Open `includes/config.php`
   - Update the following constants:
     - DB_HOST
     - DB_USER
     - DB_PASS
     - DB_NAME
     - SITE_URL (your website URL)
     - SITE_TITLE (your website title)

3. **File Permissions**
   - Make sure the `uploads` directory is writable by the web server
   ```bash
   chmod 755 uploads
   ```

4. **Admin Access**
   - Default admin credentials:
     - Username: admin
     - Password: admin123
   - Change these credentials after first login

5. **Initial Setup**
   - Log in to the admin panel
   - Update your profile information
   - Add your publications, projects, and news items

## Features

- Responsive design using Bootstrap 4
- Secure admin panel with user management
- Profile management
- Publications management
- Projects management
- News management
- Image upload functionality

## Security Notes

1. Change the default admin password immediately after installation
2. Keep your PHP version updated
3. Use strong passwords
4. Regularly backup your database
5. Monitor login attempts

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- mod_rewrite enabled (for clean URLs)
- GD Library (for image processing)

## Support

For issues and questions, please create an issue in the repository.

## License

This project is licensed under the MIT License.
=======
# BBYuksel-Website
>>>>>>> 36b8b4b346936f653d46ccc057f5aab1726c4752
