# BazaarMama 🛒 - E-Commerce & Inventory Management System

**BazaarMama** is a dynamic web application built to streamline product management, inventory tracking, and e-commerce configurations. It provides a robust, responsive Administrative Dashboard that empowers admins to seamlessly manage catalog structures (Types, Categories, Sub-categories, Units) and product listings.

---

## 🌟 Key Features

- **🔐 Secure Authentication & Access Control:**
  - Role-Based Access Control (RBAC) ensuring only administrative users can access sensitive dashboard routes.
  - PHP session-based protection across all admin pages.

- **📦 Comprehensive Inventory & Product Management:**
  - Full CRUD operations (Create, Read, Update, Delete) for products.
  - Image handling with automatic file deletion (`unlink()`) from the server during updates or removal.
  - Categorization hierarchy: Product Types, Categories, Sub-categories, and Units.

- **📊 Modern Admin Dashboard:**
  - Responsive layout built with Bootstrap 5 and FontAwesome.
  - Clean data tables with live image previews, status badges, dynamic price formatting, and quick action shortcuts.

- **🛡️ Secure Database Operations:**
  - Built using PHP Data Objects (PDO) with prepared statements to prevent SQL Injection attacks.

---

## 🛠️ Tech Stack & Technologies

- **Backend:** PHP 
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5, FontAwesome 6
- **Architecture/Tools:** Apache Server (XAMPP/WAMP), Git & GitHub

---

## 📁 Project Structure

```text
bazaarmama/
│
├── config/
│   └── db.php                # Database connection configuration (PDO)
│
├── admin/
│   ├── dashboard.php         # Business Overview & Analytics
│   ├── add_product.php       # Form to add new products
│   ├── manage_products.php   # Available products list with Delete action
│   ├── edit_product.php     # Product edit and image update handler
│   ├── add_category.php     # Category management
│   ├── add_subcategory.php  # Sub-category management
│   ├── add_product_type.php # Product type configuration
│   └── add_unit.php         # Product unit configuration
│
├── uploads/                  # Product image uploads folder
├── login.php                 # User authentication page
└── README.md                 # Project documentation
