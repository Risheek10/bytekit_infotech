# Installation Guide - bytekit_infotech Web Application

**Date:** July 26, 2025
**Version:** 1.0

This guide provides step-by-step instructions for installing and setting up the `bytekit_infotech` web application on a new server or local development environment.

---

## 1. System Requirements

Ensure your server or local machine meets the following minimum requirements:

* **Operating System:** Linux (Ubuntu/Debian recommended), Windows (with XAMPP/WAMP/Laragon), macOS (with MAMP/Herd).
* **Web Server:** Apache 2.4+ or Nginx 1.x+
* **PHP Version:** PHP 7.4 or higher
* **PHP Extensions:**
    * `php_pdo_mysql` (for database connectivity)
    * `php_mbstring` (for multi-byte string functions)
* **Database:** MySQL 5.7+ 

---

## 2. Installation Steps

Follow these steps to get `bytekit_infotech` up and running.

### 2.1. Get the Application Code

1.  **Download the project files:**
    * [**Option A: If using Git (Recommended)**] Clone the repository:
        ```bash
        git clone https://github.com/Risheek10/bytekit_infotech.git
        ```
    * [**Option B: If downloading a ZIP**] Download the `bytekit_infotech.zip` archive from my github (will update that soon).
        * Unzip the archive to your desired location.

2.  **Place the files on your web server:**
    * Upload the entire `bytekit_infotech` folder (the one containing `admin/`, `css/`, `index.php`, etc.) to your web server's document root.
    * **For shared hosting (e.g., DirectAdmin):** Upload the `bytekit_infotech` folder to your `public_html` directory (e.g., `/home/yourusername/domains/yourdomain.com/public_html/bytekit_infotech/`).
    * **For local setup (e.g., XAMPP):** Place the folder inside your `htdocs` directory (e.g., `C:\xampp\htdocs\bytekit_infotech`).

### 2.2. Database Setup

1.  **Create a New MySQL Database:**
    * Access your database management tool (e.g., phpMyAdmin, DirectAdmin's MySQL Management, MySQL Workbench, or command line).
    * Create a new database. Recommended name: `yourusername_bytekit_infotech` (replace `yourusername` with your hosting account username if on shared hosting). For local, simply `bytekit_infotech`.

2.  **Create a New MySQL User & Grant Privileges:**
    * Create a dedicated MySQL user for this database.
    * **Crucially, grant this user ALL PRIVILEGES** to the `yourusername_bytekit_infotech` database.
    * **Record the:**
        * **Database Host:** (usually `localhost`)
        * **Database Name:** (the one you just created)
        * **Database Username:** (the one you just created)
        * **Database Password:** (the password you set for the user)

3.  **Import Database Schema and Data:**
    * **Option A (Recommended: using a SQL dump file):**
        * Download the provided `database_dump.sql` file from [Link to SQL dump if you create one].
        * In phpMyAdmin, select your newly created database.
        * Go to the "Import" tab.
        * Click "Choose File" and select the `database_dump.sql` file.
        * Click "Go" to import.
    * **Option B (Manually import schema and data):**
        * Execute the `CREATE TABLE` statements found in `database_schema.md` (or your separate schema file) in your database.
        * Then, execute the `INSERT` statements to populate initial data. (You can copy the `INSERT` statements from the guidance provided to you previously).

### 2.3. Application Configuration

1.  **Configure Database Connection:**
    * Navigate to the `includes/` directory within your `bytekit_infotech` project folder.
    * Open the `db_connect.php` file in a text editor.
    * Update the following lines with your database credentials from Step 2.2:
        ```php
        $host = 'localhost'; // Or your specific database host
        $db = 'YOUR_DATABASE_NAME';
        $user = 'YOUR_DATABASE_USERNAME';
        $pass = 'YOUR_DATABASE_PASSWORD';
        ```
    * Save the changes to `db_connect.php`.

### 2.4. Web Server Configuration (If needed)

* Ensure your web server (Apache or Nginx) is configured to serve PHP files and has `mod_rewrite` enabled if your application uses clean URLs (e.g., `.htaccess` rules). For most shared hosting, this is usually handled automatically.

---

## 3. Post-Installation Steps

1.  **Access the Application:**
    * Open your web browser and navigate to the root URL of your application:
        * **For shared hosting:** `http://yourdomain.com/bytekit_infotech/`
        * **For local setup:** `http://localhost/bytekit_infotech/` (or `http://localhost:port/bytekit_infotech/`)

2.  **Access Admin Panel:**
    * The admin panel can be accessed at: `http://yourdomain.com/bytekit_infotech/admin/login.php`
    * **Initial Admin User:**
        * [**If you inserted an admin user during database population**] Log in with the credentials of the `adminuser` you created (username: `adminuser`, password: `admin_secure_pass` (or whatever you set)). **Remember to change this password immediately after first login!**
        * [**If your app has an admin registration process**] Follow the instructions to register a new user and assign them the 'admin' role.

3.  **Security Review:**
    * Change all default passwords immediately.
    * Ensure file permissions are set correctly (e.g., 644 for files, 755 for directories).
    * Do not leave any sensitive configuration files in publicly accessible directories.

---