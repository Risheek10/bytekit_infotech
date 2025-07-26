# Admin Guide - bytekit_infotech Web Application

**Date:** July 26, 2025
**Version:** 1.0 (Initial Draft)

This guide provides instructions for administrators managing the `bytekit_infotech` web application. It covers essential tasks such as user management, content updates, and monitoring.

---

## 1. Accessing the Admin Panel

The administrator panel is the central hub for managing the application.

* **URL:** Navigate to `http://jayakumj6.myweb.cs.uwindsor.ca/bytekit_infotech/admin/login.php` (or your specific admin login URL).
* **Login:** Enter your designated admin username and password.
    * **Note:** If you are unsure of your admin credentials, please contact the system developer or refer to initial setup documentation. Passwords are case-sensitive.

---

## 2. Admin Dashboard Overview

Upon successful login, you will be directed to the admin dashboard. This page provides a quick summary of key information and access points to various management sections.

* **Key Sections/Widgets:**
    * [Describe any overview widgets, e.g., "Total Users," "Recent Orders," "System Status Summary"].
* **Main Navigation:**
    * The primary navigation (usually in a sidebar or top menu) allows you to access:
        * Dashboard
        * User Management
        * Category Management
        * Product Management
        * Order Management
        * System Settings
        * Monitoring (once implemented)
        * Logout

---

## 3. User Management

This section allows you to manage all user accounts registered on the application.

### 3.1. Viewing Users

1.  From the admin navigation, click on "User Management" (or "Users").
2.  You will see a table listing all registered users with details like Username, Email, Role, etc.
3.  [Describe any search/filter options if available].

### 3.2. Adding a New User (If applicable)

1.  On the "User Management" page, click "Add New User" button.
2.  Fill in the required fields:
    * **Username:** [Specify rules, e.g., "Unique, alphanumeric"].
    * **Email:** [Specify rules, e.g., "Valid email format, unique"].
    * **Password:** [Specify password requirements, e.g., "Minimum 8 characters, mix of cases and numbers"].
    * **Confirm Password:** Re-enter the password.
    * **Full Name:** [Optional/Required, describe format].
    * **Role:** Select `user` or `admin`. (Be cautious when granting `admin` role).
3.  Click "Create User" (or "Save").

### 3.3. Editing User Profiles

1.  On the "User Management" page, locate the user you wish to edit.
2.  Click the "Edit" button/icon next to their entry.
3.  Modify the desired fields (Username, Email, Full Name, Role).
4.  **Changing Password:** To change a user's password, locate the password fields and enter a new password twice. This usually does not require the old password.
5.  Click "Save Changes" (or "Update User").

### 3.4. Deactivating/Deleting Users

1.  On the "User Management" page, locate the user.
2.  Click the "Delete" button/icon.
3.  **Confirm the deletion** when prompted. (Be aware that deleting a user may affect associated data like orders.)
    * [If there's a "Deactivate" option, describe its function vs. full deletion].

---

## 4. Content Management

This section details how to manage the primary content of the website.

### 4.1. Category Management

* **Viewing Categories:** Navigate to "Category Management." A list of existing product/service categories will be displayed.
* **Adding a New Category:**
    1.  Click "Add New Category."
    2.  Enter the **Category Name** (e.g., "Electronics", "Books").
    3.  Provide a **Description** (optional).
    4.  Click "Save Category."
* **Editing a Category:**
    1.  Click "Edit" next to the category you wish to modify.
    2.  Update the Name or Description.
    3.  Click "Save Changes."
* **Deleting a Category:**
    1.  Click "Delete" next to the category.
    2.  **Warning:** Deleting a category may affect products linked to it. [Specify your system's behavior: e.g., "Products in this category may become unassigned or be deleted."]. Confirm deletion.

### 4.2. Product Management

* **Viewing Products:** Navigate to "Product Management." You will see a list of all products.
* **Adding a New Product:**
    1.  Click "Add New Product."
    2.  Fill in details:
        * **Product Name:** [Required].
        * **Description:** [Detailed description].
        * **Price:** [Numerical value, e.g., "10.99"].
        * **Category:** Select from the dropdown list of existing categories.
        * **Brand:** [Optional].
        * **Model Number:** [Optional].
        * **Image URL:** https://answers.laserfiche.com/questions/194857/Forms--Make-field-required-if-file-is-uploaded.
        * **Stock Quantity:** [Initial stock level].
    3.  Click "Save Product."
* **Editing a Product:**
    1.  Click "Edit" next to the product.
    2.  Modify any fields.
    3.  Click "Save Changes."
* **Managing Product Images:** [Describe process if upload is implemented, or if just URL field].
* **Deleting a Product:** Click "Delete" and confirm.

---

## 5. Order Management

This section is for reviewing and processing customer orders.

* **Viewing Orders:** Navigate to "Order Management." A list of recent orders will appear.
    * [Describe filtering/search options, e.g., "Filter by status: Pending, Shipped, Cancelled"].
* **Viewing Order Details:** Click on an "Order ID" or "View Details" button for a specific order. This will show:
    * Customer Information (Name, Address, Contact)
    * Order Items (Product, Quantity, Price)
    * Total Amount
    * Current Status
* **Updating Order Status:**
    1.  From the order details view (or the main list, if available), locate the "Status" field.
    2.  Select the new status (e.g., "Processing", "Shipped", "Delivered", "Cancelled").
    3.  Click "Update Status" (or "Save").

---

## 6. Backend Monitoring Page (admin/monitor.php)

* **Access:** Navigate to `http://jayakumj6.myweb.cs.uwindsor.ca/bytekit_infotech/admin/monitor.php`.
* **Purpose:** This page provides real-time insights into the health and activity of the application.
* **Information Displayed:**
    * [Describe what information is shown here, e.g., "Server Uptime," "Database Connection Status," "Recent Error Logs," "Memory Usage," "Number of Active Sessions," "Recent User Logins"].
* **Interpreting Data:** [Briefly explain what key metrics mean and what to look for].

---

## 7. Troubleshooting / Common Issues

* **Login Issues:**
    * Double-check username and password for typos.
    * Ensure Caps Lock is not on.
    * If password is forgotten, contact the system administrator for a reset.
* **Data Not Appearing:**
    * Verify database connection is active.
    * Check for relevant error messages in your web server's error logs (usually accessible via DirectAdmin).
* **Performance Slowdown:**
    * Check the monitoring page (`admin/monitor.php`) for any unusual activity.
    * Contact hosting provider if persistent.

---
