# Database Schema - bytekit_infotech

This document outlines the structure of the `bytekit_infotech` database.

---

## Table: `users`

**Purpose:** Stores information about registered users of the application.

| Column Name   | Data Type         | Constraints                                | Default Value | Description                                  |
| :------------ | :---------------- | :----------------------------------------- | :------------ | :------------------------------------------- |
| `user_id`     | `INT(11)`         | `PRIMARY KEY`, `NOT NULL`, `AUTO_INCREMENT`| -             | Unique identifier for the user.              |
| `username`    | `VARCHAR(50)`     | `NOT NULL`, `UNIQUE`                       | -             | Unique username for login.                   |
| `email`       | `VARCHAR(255)`    | `NOT NULL`, `UNIQUE`                       | -             | User's email address.                        |
| `password_hash`| `VARCHAR(255)`    | `NOT NULL`                                 | -             | Hashed password for security.                |
| `full_name`   | `VARCHAR(255)`    | `NULL`                                     | -             | User's full name.                            |
| `role`        | `ENUM('user', 'admin')`| `NOT NULL`                              | `'user'`      | User's role (e.g., user, admin).             |
| `created_at`  | `TIMESTAMP`       | `NOT NULL`                                 | `CURRENT_TIMESTAMP` | Timestamp when the user account was created. |
| `updated_at`  | `TIMESTAMP`       | `NOT NULL`                                 | `CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` | Timestamp of the last update to the user record. |

**Relationships:**
* N/A

**Indexes:**
* `username` (Unique Index)
* `email` (Unique Index)

---

## Table: `categories`

**Purpose:** Stores different categories for products or other items.

| Column Name   | Data Type         | Constraints                                | Default Value                   | Description                                          |
| :------------ | :---------------- | :----------------------------------------- | :------------------------------ | :--------------------------------------------------- |
| `category_id` | `INT(11)`         | `PRIMARY KEY`, `NOT NULL`, `AUTO_INCREMENT`| -                               | Unique identifier for the category.                  |
| `name`        | `VARCHAR(255)`    | `NOT NULL`                                 | -                               | Name of the category (e.g., 'Electronics', 'Books'). |
| `description` | `TEXT`            | `NULL`                                     | -                               | Detailed description of the category.                |
| `created_at`  | `TIMESTAMP`       | `NOT NULL`                                 | `CURRENT_TIMESTAMP`             | Timestamp when the category was created.             |
| `updated_at`  | `TIMESTAMP`       | `NOT NULL`                                 | `CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` | Timestamp of the last update to the category record. |

**Relationships:**
* N/A

**Indexes:**
* `category_id` (Primary Key Index)
* *(Optional: `name` (Unique Index), if category names must be unique.)*

---

## Table: `products`

**Purpose:** Stores information about the products available.

| Column Name   | Data Type         | Constraints                                | Default Value | Description                                  |
| :------------ | :---------------- | :----------------------------------------- | :------------ | :------------------------------------------- |
| `product_id`  | `INT(11)`         | `PRIMARY KEY`, `NOT NULL`, `AUTO_INCREMENT`| -             | Unique identifier for the product.           |
| `name`        | `VARCHAR(255)`    | `NOT NULL`                                 | -             | Name of the product.                         |
| `description` | `TEXT`            | `NULL`                                     | -             | Detailed description of the product.         |
| `price`       | `DECIMAL(10, 2)`  | `NOT NULL`                                 | -             | Price of the product.                        |
| `category_id` | `INT(11)`         | `NOT NULL`                                 | -             | Foreign key linking to the categories table. |
| `brand`       | `VARCHAR(100)`    | `NULL`                                     | -             | Brand of the product.                        |
| `model_number`| `VARCHAR(100)`    | `NULL`                                     | -             | Model number of the product.                 |
| `image_url`   | `VARCHAR(255)`    | `NULL`                                     | -             | URL for the product image.                   |
| `stock_quantity`| `INT(11)`         | `NOT NULL`                                 | `0`           | Current stock level.                         |
| `created_at`  | `TIMESTAMP`       | `NOT NULL`                                 | `CURRENT_TIMESTAMP` | Timestamp when the product was added.        |
| `updated_at`  | `TIMESTAMP`       | `NOT NULL`                                 | `CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` | Timestamp of the last update.                |

**Relationships:**
* `category_id` (FK) -> `categories.category_id`

**Indexes:**
* `category_id` (Index for foreign key)

---

## Table: `orders`

**Purpose:** Stores information about customer orders.

| Column Name      | Data Type         | Constraints                                | Default Value                   | Description                                  |
| :--------------- | :---------------- | :----------------------------------------- | :------------------------------ | :------------------------------------------- |
| `order_id`       | `INT(11)`         | `PRIMARY KEY`, `NOT NULL`, `AUTO_INCREMENT`| -                               | Unique identifier for the order.             |
| `user_id`        | `INT(11)`         | `NOT NULL`                                 | -                               | Foreign key linking to the users table.      |
| `order_date`     | `TIMESTAMP`       | `NOT NULL`                                 | `CURRENT_TIMESTAMP`             | Date and time the order was placed.          |
| `total_amount`   | `DECIMAL(10, 2)`  | `NOT NULL`                                 | -                               | Total amount of the order.                   |
| `shipping_address`| `TEXT`            | `NOT NULL`                                 | -                               | Full shipping address for the order.         |
| `city`           | `VARCHAR(100)`    | `NOT NULL`                                 | -                               | City for shipping.                           |
| `province`       | `VARCHAR(100)`    | `NOT NULL`                                 | -                               | Province/State for shipping.                 |
| `postal_code`    | `VARCHAR(20)`     | `NOT NULL`                                 | -                               | Postal/ZIP code for shipping.                |
| `status`         | `ENUM(...)`       | `NOT NULL`                                 | `'pending'`                     | Current status of the order.                 |

**Relationships:**
* `user_id` (FK) -> `users.user_id`

**Indexes:**
* `user_id` (Index for foreign key)

---

## Table: `order_items`

**Purpose:** Stores individual items within each order, linking products to orders.

| Column Name      | Data Type         | Constraints                                | Default Value | Description                                  |
| :--------------- | :---------------- | :----------------------------------------- | :------------ | :------------------------------------------- |
| `item_id`        | `INT(11)`         | `PRIMARY KEY`, `NOT NULL`, `AUTO_INCREMENT`| -             | Unique identifier for the order item.        |
| `order_id`       | `INT(11)`         | `NOT NULL`                                 | -             | Foreign key linking to the orders table.     |
| `product_id`     | `INT(11)`         | `NOT NULL`                                 | -             | Foreign key linking to the products table.   |
| `quantity`       | `INT(11)`         | `NOT NULL`                                 | -             | Quantity of the product in this order item.  |
| `price_at_purchase`| `DECIMAL(10, 2)`  | `NOT NULL`                                 | -             | Price of the product at the time of purchase. |

**Relationships:**
* `order_id` (FK) -> `orders.order_id`
* `product_id` (FK) -> `products.product_id`

**Indexes:**
* `order_id` (Index for foreign key)
* `product_id` (Index for foreign key)

---