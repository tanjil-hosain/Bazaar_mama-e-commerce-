# 🛒 BazaarMama — Comprehensive E-Commerce & Inventory Management Platform

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green.style=for-the-badge)](LICENSE)

**BazaarMama** is a full-featured, lightweight web application designed to optimize inventory management, dynamic product cataloging, and administrative workflows for e-commerce stores. Built using standard PHP (PDO) and relational MySQL databases, it provides an end-to-end admin ecosystem for handling multi-level product categorizations, asset storage, and operational business metrics.

---

## 📌 Executive Summary

Modern e-commerce applications require precise catalog synchronization, robust access security, and fast asset handling. **BazaarMama** addresses these needs by featuring:
- A clean, modular administrative control interface.
- Scalable relational database relationships across multi-tiered product topologies.
- Server-side validation and file system cleanup protocols to eliminate orphaned storage assets.

---

## 🌟 Core System Architecture & Features

### 1. 🔐 Authentication & Access Security
- **Role-Based Access Control (RBAC):** Restricts route execution based on session parameters (`$_SESSION['user_role'] === 'admin'`).
- **Session Management:** Built-in redirection protocols for unauthorized access attempts.
- **SQL Injection Defense:** Prepared statements executed exclusively through PHP Data Objects (PDO) with parameterized queries.

### 2. 📦 Product Inventory Management (Full CRUD)
- **Product Creation:** Form-driven insertion supporting dynamic selection of catalog tiers and file attachments.
- **Data Mutation & Edits:** Seamless updating of product records with automatic server-side image synchronization.
- **Automated Asset Cleanup:** Integrated `unlink()` triggers to remove obsolete image files from local disk space when products or thumbnails are deleted or overwritten.
- **Formatted Presentation:** Currency formatting (`৳`), localized unit rendering, and real-time category badges.

### 3. 🏷️ Multi-Tier Catalog Configuration
- **Product Types:** Define overarching product delivery styles or structures.
- **Categories & Sub-categories:** Hierarchical categorization for clear front-facing search navigation.
- **Product Units:** Standardized measurement metrics (e.g., *kg*, *ltr*, *pcs*) mapped directly to item listings via relational joins.

### 4. 🎨 Responsive UI/UX Interface
- **Dashboard Layout:** Custom CSS combined with Bootstrap 5 grid systems for split-screen sidebar and workspace navigation.
- **Data Tables:** Highly responsive tabular views with direct action triggers for editing and deletion.

---

## 🛠️ Technology Stack

| Domain | Technology / Tool | Usage Description |
| :--- | :--- | :--- |
| **Backend** | PHP 8.x (PDO) | Core application logic, routing, session control, and database interactions |
| **Database** | MySQL | Relational data persistence with Foreign Key constraints |
| **Frontend** | HTML5, CSS3, JavaScript | UI markup, custom styles, and interactive components |
| **Styling Framework** | Bootstrap 5.3 | Responsive grid layouts, tables, modals, and utilities |
| **Icons** | FontAwesome 6 | Vector iconography for sidebar, actions, and status feedback |
| **Server Architecture** | Apache (XAMPP / WAMP) | Local web server host and request parser |

---

## 📁 Repository Directory Structure

```text
bazaarmama/
│
├── config/
│   └── db.php                  # PDO database connection configuration & credentials
│
├── admin/
│   ├── dashboard.php           # Business overview & administrative metrics dashboard
│   ├── add_product.php         # Product creation interface with image upload handler
│   ├── manage_products.php     # Tabular product inventory with deletion triggers
│   ├── edit_product.php       # Product mutation interface with image overwrite logic
│   ├── add_category.php       # Main product category management
│   ├── add_subcategory.php    # Nested sub-category configuration interface
│   ├── add_product_type.php   # System product type configuration
│   └── add_unit.php           # Product unit of measurement (UOM) configuration
│
├── uploads/                    # Physical target directory for stored product images
├── login.php                   # Authentication portal for system users
└── README.md                   # Comprehensive project documentation
