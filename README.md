# Bytekit Infotech Web Application

A web application for managing categories, products, users, and orders. This project demonstrates a full-stack PHP application with a MySQL database.
Files are accessible from the master branch.

## Features

* **User Management:** Register, login, manage user profiles.
* **Admin Panel:** Dedicated interface for administrators to manage users, categories, products, and orders.
* **Product Catalog:** Browse products by category, view product details.
* **Order Management:** Create and track customer orders.
* **Database Integration:** MySQL database for data storage.
* **Backend Monitoring:** Admin page to check system health and application metrics.

## Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP (with PDO for database interaction)
* **Database:** MySQL

## Installation Guide

Follow these steps to set up and run the Bytekit Infotech application on your local machine or web server.

### 1. System Requirements

* Web Server (Apache or Nginx recommended)
* PHP 7.4 or higher,
* MySQL 5.7+ or MariaDB 10.x+

### 2. Get the Code

You can download the project in two ways:

* **Download ZIP:**
    Download the latest stable version of the project as a ZIP file from the [Releases page](https://github.com/Risheek10/bytekit_infotech.git) (or direct link to your uploaded ZIP if not using releases).
    * [Link to your ZIP file here, e.g., `https://github.com/your-username/your-repo-name/raw/main/bytekit_infotech.zip`]

* **Clone Repository (Requires Git):**
    ```bash
    git clone [https://github.com/Risheek10/bytekit_infotech.git](https://github.com/your-username/your-repo-name.git)
    ```

Once you have the code, place the `bytekit_infotech` folder in your web server's document root (e.g., `htdocs` for XAMPP/WAMP, or `public_html` for shared hosting).

### 3. Database Setup

1.  **Create a MySQL Database:**
    * Using phpMyAdmin, cPanel, DirectAdmin, or your preferred database tool, create a new MySQL database.
    * **Recommended Database Name:** `bytekit_infotech` (for local) or `yourusername_bytekit_infotech` (for shared hosting).

2.  **Create a MySQL User:**
    * Create a new MySQL user and assign it **all privileges** to the database you just created.
    * **Record the:** Database Host (usually `localhost`), Database Name, Database Username, and Database Password.

3.  **Import Database Schema and Data:**
    * Locate the `bytekit_infotech_database.sql` file in the `sql/` (or `docs/`) directory of the downloaded project.
    * In phpMyAdmin, select your newly created database.
    * Go to the "Import" tab, choose the `bytekit_infotech_database.sql` file, and click "Go" to import the schema and initial data.

### 4. Application Configuration

1.  **Configure Database Connection:**
    * Navigate to the `includes/` directory within your `bytekit_infotech` project folder.
    * Open the `db_connect.php.template` (or `db_connect.php` if you used the template above) file in a text editor.
    * Update the `$host`, `$db`, `$user`, and `$pass` variables with the database credentials you recorded in Step 3.2.
    * Save the changes.

### 5. Access the Application

* **Main Application:** Open your web browser and navigate to:
    * **Local:** `http://localhost/bytekit_infotech/`
    * **Web Server:** `http://yourdomain.com/bytekit_infotech/` (replace with your actual domain/path)

* **Admin Panel:** The administrator panel can be accessed at:
    * **Local:** `http://localhost/bytekit_infotech/admin/login.php`
    * **Web Server:** `http://yourdomain.com/bytekit_infotech/admin/login.php`

    * **Initial Admin User:**
        * **Username:** `adminuser`
        * **Password:** `admin_secure_pass` (or the password you set during database population)
        * **IMPORTANT:** Change this password immediately after your first login for security!

## Documentation

* **Database Schema:** Detailed database structure in `docs/database_schema.md`
* **Admin Guide:** Instructions for managing the application in `docs/admin_guide.md`
* **Help Wiki:** User-facing help pages in the `help/` directory.

---
