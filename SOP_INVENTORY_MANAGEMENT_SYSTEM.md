# Standard Operating Procedure (SOP)
## Inventory Management System

| Document Info | Details |
|---|---|
| **Document Title** | SOP — Inventory Management System |
| **Version** | 1.0 |
| **Date** | February 19, 2026 |
| **Platform** | Web-Based (Laravel 11.x) |
| **URL** | *(Deployed application URL)* |
| **Prepared By** | System Administrator |
| **Approved By** | *(Management)* |

---

## Table of Contents

1. [Purpose](#1-purpose)
2. [Scope](#2-scope)
3. [System Overview](#3-system-overview)
4. [Technology Stack](#4-technology-stack)
5. [User Roles & Access Levels](#5-user-roles--access-levels)
6. [System Installation & Setup](#6-system-installation--setup)
7. [Login & Authentication](#7-login--authentication)
8. [Dashboard](#8-dashboard)
9. [Master Data Management](#9-master-data-management)
    - 9.1 [Product Types](#91-product-types)
    - 9.2 [Products](#92-products)
    - 9.3 [Stores / Locations](#93-stores--locations)
    - 9.4 [Asset Statuses](#94-asset-statuses)
    - 9.5 [Suppliers](#95-suppliers)
    - 9.6 [Departments](#96-departments)
10. [Employee Management](#10-employee-management)
11. [Purchase Management](#11-purchase-management)
12. [Inventory / Stock Management](#12-inventory--stock-management)
13. [Distribution / Transaction Management](#13-distribution--transaction-management)
14. [Requisition Management](#14-requisition-management)
15. [QR Code & Barcode Management](#15-qr-code--barcode-management)
16. [Reports](#16-reports)
17. [User Management](#17-user-management)
18. [Role & Permission Management](#18-role--permission-management)
19. [Employee Management (Management Module)](#19-employee-management-management-module)
20. [Onboarding Module](#20-onboarding-module)
21. [Data Import](#21-data-import)
22. [Settings](#22-settings)
23. [Audit / User Activity Logs](#23-audit--user-activity-logs)
24. [Key Business Workflows](#24-key-business-workflows)
25. [Troubleshooting & FAQ](#25-troubleshooting--faq)
26. [Appendix — Permission Reference](#26-appendix--permission-reference)

---

## 1. Purpose

This document provides a complete Standard Operating Procedure (SOP) for the **Inventory Management System**. It serves as the authoritative guide for all users — administrators, managers, and employees — to understand and operate every feature of the system, from initial setup through day-to-day operations, reporting, and auditing.

---

## 2. Scope

This SOP covers:

- System installation, configuration, and initial setup
- User authentication and role-based access control (RBAC)
- All master data modules (product types, products, stores, statuses, suppliers, departments)
- Employee lifecycle management
- Purchase order creation and processing
- Inventory/stock management and asset tagging
- Asset distribution (issue/return) to employees
- QR code and barcode generation and printing
- Requisition management
- Reporting and analytics
- Data import/export
- Onboarding/offboarding workflows
- System settings and user activity logs
- Troubleshooting and common operations

---

## 3. System Overview

The Inventory Management System is a web-based application designed to manage the complete lifecycle of organizational assets — from procurement to distribution to retirement. It provides:

- **Centralized asset tracking** with unique asset tags, serial numbers, and service tags
- **Purchase management** with multi-product purchase orders, supplier tracking, and GRN generation
- **Automated inventory creation** from approved purchases
- **Employee assignment tracking** with acknowledgement receipts
- **QR code and barcode** label generation for physical asset identification
- **Comprehensive reporting** across employees, stocks, transactions, and inventory
- **Role-based access control** with 52 granular permissions
- **Complete audit trail** via user activity logs

---

## 4. Technology Stack

| Component | Technology |
|---|---|
| **Backend Framework** | Laravel 11.x (PHP 8.2+) |
| **Frontend** | Blade Templates, Bootstrap (Laravel UI), Vite |
| **Database** | MySQL / MariaDB |
| **DataTables** | Yajra Laravel DataTables (server-side) |
| **PDF Generation** | Barryvdh DomPDF |
| **Excel Import** | Maatwebsite Excel v3.1 |
| **Barcode** | Milon Barcode (Code128) |
| **QR Code** | SimpleSoftwareIO Simple QR Code v4.2 |
| **Image Processing** | Intervention Image v3.7 (GD driver) |
| **RBAC** | Spatie Laravel Permission v6.9 |
| **Activity Logging** | Spatie Laravel Activity Log v4.8 |
| **Notifications** | Brian2694 Laravel Toastr |
| **Web Server** | Apache (WAMP) / Nginx |

---

## 5. User Roles & Access Levels

The system ships with three predefined roles. Additional custom roles can be created.

### 5.1 Super Admin

| Attribute | Value |
|---|---|
| **Default Email** | `superadmin@gmail.com` |
| **Default Password** | `123456` |
| **Username** | `superadmin` |
| **Access** | Full access to all 52 permissions |
| **Special Privileges** | Can manage all users including other admins; cannot be deleted or modified by non-super-admins |

### 5.2 Admin

| Attribute | Value |
|---|---|
| **Default Email** | `admin@gmail.com` |
| **Default Password** | `123456` |
| **Username** | `admin` |
| **Access** | Full access to all 52 permissions |
| **Restrictions** | Cannot assign/modify the super-admin role; cannot see super-admin user in user lists |

### 5.3 Employee (Self-Service)

| Attribute | Value |
|---|---|
| **Access** | Limited to 3 self-service permissions |
| **Permissions** | `self-view-profile`, `self-view-assets`, `self-view-transactions` |

### 5.4 Custom Roles

Administrators can create custom roles with any combination of the 52 available permissions (see [Appendix](#26-appendix--permission-reference)).

> **IMPORTANT:** Change default passwords immediately after first login.

---

## 6. System Installation & Setup

### 6.1 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL / MariaDB database
- Apache or Nginx web server
- PHP Extensions: GD, mbstring, xml, zip, curl, bcmath

### 6.2 Installation Steps

```bash
# 1. Clone the repository
git clone <repository-url> inventory-management
cd inventory-management

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Environment configuration
cp .env.example .env
php artisan key:generate
```

### 6.3 Database Configuration

Edit the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6.4 Database Migration & Seeding

```bash
# Run all migrations
php artisan migrate

# Seed initial data (permissions, roles, admin users)
php artisan db:seed
```

This will execute:
1. **PermissionTableSeeder** — Creates 52 permissions
2. **CreateAdminUserSeeder** — Creates Super Admin, Admin, and Employee role

### 6.5 Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 6.6 Storage & Permissions

```bash
php artisan storage:link
```

Ensure the following directories are writable:
- `storage/`
- `bootstrap/cache/`
- `public/images/employee/`
- `storage/qrcodes/`

---

## 7. Login & Authentication

### 7.1 Accessing the System

1. Open a web browser and navigate to the application URL
2. The login page is displayed at the root URL (`/`) or `/login`

### 7.2 Login Procedure

1. Enter your **Email Address**
2. Enter your **Password** (minimum 6 characters)
3. Click **Login**
4. On successful authentication, you are redirected to the **Dashboard**
5. On failure, an error message is displayed: *"The provided credentials do not match our records."*

### 7.3 First Login — Forced Password Change

- If the `must_change_password` flag is set on your account, you will be **automatically redirected** to the password change page
- You **cannot access any other page** until the password is changed
- After changing the password, the flag is cleared and you can navigate normally

### 7.4 Logout

1. Click the **Logout** button/link
2. Your session is invalidated and CSRF token is regenerated
3. You are redirected to the login page

> **Audit:** All login and logout actions are recorded in the User Activity Log.

---

## 8. Dashboard

**Route:** `/dashboard`  
**Permission:** Authenticated users only

The dashboard provides a real-time overview of the inventory system:

### 8.1 Key Metrics Displayed

| Metric | Description |
|---|---|
| **Total Employees** | Count of active employees (status = 1) |
| **Purchases This Month** | Number of purchases in the current month |
| **Total Stock Items** | Total number of stock records |
| **Total Laptops** | Stock items of type "Laptop" |
| **Total Mobiles** | Stock items of type "Mobile" |
| **Assigned Laptops** | Laptops currently assigned to employees |
| **Assigned Mobiles** | Mobiles currently assigned to employees |
| **Total Assigned** | All stock items currently assigned |
| **Total Available** | All stock items available for distribution |
| **Utilization Rate** | Percentage of stock currently assigned |
| **Warranty Expiring (30 days)** | Items with warranty expiring within 30 days |
| **Warranty Expired** | Items with expired warranty |
| **Low Stock Categories** | Product types with < 5 available items |
| **Out of Stock** | Product types with 0 available items |

### 8.2 Additional Dashboard Elements

- **Top 5 Product Categories** — by total stock count
- **Monthly Purchase Trend** — last 6 months chart
- **Recent Purchases** — last 5 purchases with supplier info

---

## 9. Master Data Management

Master data forms the foundation of the inventory system. These must be configured before processing purchases or distributions.

> **Recommended setup order:** Departments → Product Types → Products → Stores → Asset Statuses → Suppliers

---

### 9.1 Product Types

**Route:** `/product-types`  
**Permissions Required:** `product-type-list`, `product-type-create`, `product-type-edit`, `product-type-delete`

Product types are categories of assets (e.g., Laptop, Monitor, Mouse, Software License).

#### 9.1.1 View Product Types
1. Navigate to **Product Types** from the sidebar
2. All product types are listed in reverse chronological order

#### 9.1.2 Create Product Type
1. Click the **Add** button
2. Fill in:
   - **Name** *(required, unique, max 255 characters)*
3. The system auto-generates a URL slug from the name
4. Click **Save**
5. Success notification is displayed

#### 9.1.3 Edit Product Type
1. Click the **Edit** icon on the desired row
2. Modify the name
3. Click **Update**

#### 9.1.4 Delete Product Type
1. Click the **Delete** icon on the desired row
2. Confirm the deletion
3. The product type is permanently removed

> **Note:** Deletion will fail if any products are associated with the product type.

---

### 9.2 Products

**Route:** `/products`  
**Permissions Required:** `product-list`, `product-create`, `product-edit`, `product-delete`

Products are specific items within product types (e.g., "Dell Latitude 5540" under "Laptop").

#### 9.2.1 View Products
1. Navigate to **Products** from the sidebar
2. Products are listed with their type, brand, model, and flags

#### 9.2.2 Create Product
1. Click the **Add** button
2. Fill in:
   - **Product Type** *(required, dropdown)*
   - **Brand** *(required)*
   - **Model** *(required)*
   - **Unit** *(required, e.g., pcs, license)*
   - **Requires Serial Number** *(checkbox)* — If checked, serial numbers must be entered during purchase
   - **Requires License/Warranty** *(checkbox)* — If checked, warranty info is mandatory during purchase
   - **Is Taggable** *(checkbox)* — If checked, asset tags are auto-generated when added to inventory
   - **Is Consumable** *(checkbox)* — Marks the product as a consumable item
   - **Description** *(optional)*
3. The product title is automatically composed as: `Brand + Model + Type Name`
4. Click **Save**

#### 9.2.3 Edit Product
1. Click the **Edit** icon
2. Modify fields as needed
3. Check/uncheck the **Is Consumable** flag as needed
4. Click **Update**

#### 9.2.4 Delete Product
1. Click the **Delete** icon
2. Confirm the deletion
3. **Restriction:** A product cannot be deleted if it has any associated purchase products

---

### 9.3 Stores / Locations

**Route:** `/stores`  
**Permissions Required:** `store-list`, `store-create`, `store-edit`, `store-delete`

Stores represent physical storage locations for inventory items.

#### 9.3.1 Create Store
1. Click **Add**
2. Enter:
   - **Name** *(required)*
   - **Address** *(optional)*
3. Click **Save**

#### 9.3.2 Edit / Delete Store
- Edit: Click **Edit** icon, update name/address, click **Update**
- Delete: Click **Delete** icon. **Restriction:** Cannot delete if stock items reference this store.

---

### 9.4 Asset Statuses

**Route:** `/statuses`  
**Permissions Required:** `status-list`, `status-create`, `status-edit`, `status-delete`

Asset statuses categorize the condition or lifecycle stage of stock items (e.g., Active, In Repair, Disposed).

#### 9.4.1 Create Status
1. Click **Add**
2. Enter **Name** *(required)*
3. Click **Save** — slug is auto-generated

#### 9.4.2 Edit / Delete Status
- Edit: Click **Edit** icon, update name, click **Update**
- Delete: Click **Delete** icon. **Restriction:** Cannot delete if stock items reference this status.

---

### 9.5 Suppliers

**Route:** `/suppliers`  
**Permissions Required:** `suppliers-list`, `suppliers-create`, `suppliers-edit`, `suppliers-delete`

Suppliers are organizations providing products for purchase.

#### 9.5.1 Create Supplier
1. Click **Add**
2. Fill in:
   - **Company Name** *(required)*
   - **Contact Person Name** *(required)*
   - **Phone** *(optional)*
   - **Email** *(optional)*
   - **Address** *(optional)*
   - **Description** *(optional)*
3. Click **Save**

#### 9.5.2 Edit / Delete Supplier
- Edit: Click **Edit** icon, update fields, click **Update**
- Delete: Click **Delete** icon. **Restriction:** Cannot delete if purchases reference this supplier.

---

### 9.6 Departments

**Route:** `/departments`  
**Permissions Required:** `department-list`, `department-create`, `department-edit`, `department-delete`

Departments are organizational units that employees belong to.

#### 9.6.1 Create Department
1. Click **Add**
2. Enter:
   - **Name** *(required)*
   - **Short Name** *(optional)*
3. Click **Save** — slug is auto-generated

#### 9.6.2 Edit / Delete Department
- Edit: Click **Edit** icon, update name/short name, click **Update**
- Delete: Click **Delete** icon. **Restriction:** Cannot delete if employees belong to this department.

---

## 10. Employee Management

**Route:** `/employees`  
**Permissions Required:** `employee-list`, `employee-create`, `employee-edit`, `employee-delete`

### 10.1 View Employees

1. Navigate to **Employees** from the sidebar
2. The page displays:
   - **Summary Statistics:** Total employees, Active, Inactive, With Assignments, Departments, Active Distributions
3. The DataTable supports filtering by:
   - **Department** (dropdown)
   - **Status** (Active / Inactive)
   - **Assignment Status** (With Assets / Without Assets)
4. Each row shows: Employee info (photo, ID, name, designation), Contact info, Status badge, Assignment count

### 10.2 Create Employee

1. Click **Create Employee**
2. Fill in:
   - **Department** *(required, dropdown)*
   - **Employee Name** *(required)*
   - **Designation** *(required)*
   - **Employee ID** *(required, unique numeric identifier)*
   - **Phone** *(optional)*
   - **Email** *(optional)*
   - **Date of Joining** *(optional)*
   - **Profile Photo** *(optional, image file)*
3. The photo is automatically resized to **400×400 pixels**
4. Default image is used if no photo is uploaded
5. Employee status is set to **Active** by default
6. Click **Save**

### 10.3 View Employee Details

1. Click the **View** icon on an employee row
2. View complete employee profile

### 10.4 Edit Employee

1. Click the **Edit** icon
2. Update fields as needed
3. Upload a new photo to replace the existing one (old photo is deleted)
4. Click **Update**

### 10.5 Toggle Employee Status

1. Click the **Status Toggle** button on an employee row
2. The status alternates between **Active** (1) and **Inactive** (2)

> **Audit:** All employee create/edit/status operations are logged.

---

## 11. Purchase Management

**Route:** `/purchases`  
**Permissions Required:** `purchase-list`, `purchase-create`, `purchase-edit`, `purchase-delete`, `purchase-addinventory`

The purchase module handles procurement of assets from suppliers.

### 11.1 View Purchases

1. Navigate to **Purchases** from the sidebar
2. The page displays:
   - **Summary Statistics:** Total purchases, Approved, Pending, Total Value, This Month count/value
3. The DataTable supports filtering by:
   - **Supplier** (dropdown)
   - **Approval Status** (Approved / Pending)
   - **Date Range** (from–to date pickers)
   - **Price Range** (minimum–maximum)
4. Each row shows: Status badge, Approval progress bar, Date, Price, Actions

### 11.2 Create Purchase

1. Click **Create Purchase**
2. Fill in the **Purchase Header:**
   - **Supplier** *(required, dropdown)*
   - **Invoice Number** *(auto-generated in format `DDMMYYYY-NNN`, editable)*
   - **Reference Invoice** *(optional)*
   - **Challan Number** *(optional)*
   - **Purchase Date** *(required)*
   - **Received Date** *(optional)*
3. **Add Product Lines** (one or more):
   - **Product Type** *(required, dropdown)* — Filters available products
   - **Product** *(required, dropdown)* — Filtered by selected type
   - **Quantity** *(required, numeric)*
   - **Unit Price** *(required, numeric)*
   - **Total** *(auto-calculated: quantity × unit price)*
   - **Serial Numbers** *(required if product has `is_serial` flag)* — Enter each serial on a new line
   - **Warranty Period** *(required if product has `is_license` flag)* — In months; expiry date is auto-calculated
4. Click **Add More** to add additional product lines
5. Click **Save Purchase**

**Validation Rules:**
- If a product requires serial numbers, the count of serials must **exactly match** the quantity
- If a product requires warranty, the warranty field is mandatory
- At least one product line is required

**After saving:** The purchase is created with status **Pending** (`is_stocked = 2`).

### 11.3 Edit Purchase

1. Click **Edit** on a purchase row
2. Modify purchase header or product lines
3. Add new product lines, update existing ones, or remove lines
4. Click **Update**
5. The purchase is reset to **Pending** status

### 11.4 View Purchase Details

1. Click **View** on a purchase row
2. See complete purchase information, product lines, and stock status

### 11.5 Generate GRN (Goods Received Note)

1. From the purchase list or purchase view, click **GRN**
2. A **landscape A4 PDF** is generated containing:
   - Supplier information
   - All product lines with quantities, serials, and prices
   - Total amount
3. The PDF can be printed or downloaded

### 11.6 Add to Inventory (Approve Purchase)

**Permission Required:** `purchase-addinventory`

This is the critical step that converts purchase products into stock entries.

1. From the purchase view, click **Add to Inventory** for a product line
2. The system creates stock records based on the product type:

   | Product Type | Stock Creation Logic |
   |---|---|
   | **Laptop** | Creates **1 stock record per unit**. Service tags assigned from serial numbers. |
   | **Software** | Creates **1 stock record** with the total quantity. |
   | **Other Products** | Creates **1 stock record per unit**. |

3. **If the product is taggable (`is_taggable = true`):**
   - Asset tags are **auto-generated** in the format: `{TypeInitial}{5-digit-number}`
   - Example: `L00001` for Laptop, `M00001` for Monitor
   - The system finds the highest existing tag for that prefix and increments
4. After processing:
   - The PurchaseProduct is marked as stocked (`is_stocked = 1`)
   - When all products in a purchase are stocked, the purchase itself is marked as stocked

> **Important:** This action is irreversible. Verify product details before adding to inventory.

### 11.7 View Purchased Products

**Route:** `/purchased-products`

1. Navigate to **Purchased Products**
2. View all individual purchase product records across all purchases
3. Click on a product to see detailed information

---

## 12. Inventory / Stock Management

**Route:** `/inventories`  
**Permissions Required:** `inventory-list`, `inventory-create`, `inventory-edit`, `inventory-delete`, `inventory-update-tag`

### 12.1 View Inventory

1. Navigate to **Inventory** from the sidebar
2. The page displays:
   - **Summary Statistics:** Total items, Assigned, Available, Pending Tags, Warranty Expiring (30 days), Damaged Items
3. The DataTable supports filtering by:
   - **Product Type** (dropdown)
   - **Condition** (dropdown)
   - **Store / Location** (dropdown)
   - **Supplier** (dropdown)
   - **Department** (dropdown)
   - **Assignment Status** (Assigned / Available)
4. Each row shows: Asset tag, Serial/Service tag, Product info, Store, Condition, Assignment status (employee name or store), Actions
5. **QR + Barcode combo print button** is available for selected items

### 12.2 View Stock Item Details

1. Click **View** on a stock row
2. View complete stock information including:
   - Product details (type, brand, model)
   - Purchase information (date, supplier, invoice)
   - Asset identifiers (tag, serial, service tag, MAC)
   - Current assignment status
   - Warranty information
   - Transaction history

### 12.3 Edit Stock Item

1. Click **Edit** on a stock row (or from the detail view)
2. Editable fields:
   - **Store / Location** *(dropdown)*
   - **Asset Condition** *(dropdown)*
   - **Serial Number / Service Tag**
   - **Asset Tag**
3. **Employee Reassignment:**
   - If you change the assigned **Employee ID**:
     - The current assignment is **automatically closed** (return date set)
     - A **new transaction** is created for the new employee
   - If the stock is unassigned and you assign an employee:
     - A new transaction is created
     - Stock status changes to **Assigned**
4. Click **Update**

### 12.4 Pending Asset Tags

**Route:** `/pending-tag-updates`  
**Permission Required:** `inventory-update-tag`

1. Navigate to **Pending Tag Updates**
2. View all stock items that do **not** have an asset tag
3. Filter by: Product type, Status, Store, Assignment status
4. For each item, enter the asset tag manually
5. Click **Update Tag** — the tag is saved via AJAX

### 12.5 Bulk Upload Asset Tags

1. From the Pending Tags page, click **Upload Bulk**
2. Upload an **Excel/CSV file** with asset tag mappings
3. The system processes the file using the `StockImport` class
4. Tags are assigned to matching stock records

---

## 13. Distribution / Transaction Management

**Route:** `/transections`  
**Permissions Required:** `distribution-list`, `distribution-create`, `distribution-edit`, `distribution-delete`, `distribution-return`

This module manages the assignment (issue) and return of assets to/from employees.

### 13.1 View Distributions

1. Navigate to **Distributions** from the sidebar
2. The page displays:
   - **Summary Statistics:** Total transactions, Active assignments, Returned items, Overdue (>30 days), Unique employees, Total items out
3. The DataTable supports filtering by:
   - **Employee** (dropdown)
   - **Department** (dropdown)
   - **Product Type** (dropdown)
   - **Status** (Active / Returned / Overdue)
   - **Date Range** (from–to)
4. Each row shows: Employee info, Product info, Issue date, Return date, Status (Active/Returned/Overdue)

### 13.2 Create Distribution (Issue Asset)

1. Click **Create Distribution**
2. Fill in:
   - **Product Type** *(required, dropdown)* — Filters available stock
   - **Available Item** *(required, dropdown)* — Shows only unassigned/available stock
   - **Employee** *(required, dropdown)*
   - **Quantity** *(required, for software items)*
   - **Date of Issue** *(required)*
   - **Comment** *(optional)*
   - **Print Acknowledgement** *(checkbox)* — If checked, ACK PDF opens after save
3. Click **Save**

**Business Logic:**
- **Software/License Items:** The system checks available license count (`quantity - assigned`). If sufficient, increments the `assigned` count.
- **Physical Items:** The stock item is directly marked as **Assigned** (`is_assigned = 1`).
- A `Transection` record is created linking the stock to the employee.

### 13.3 Process Return

There are two methods to process a return:

#### Method A — From the Edit/Update
1. Click **Edit** on a distribution row
2. Set the **Return Date**
3. Click **Update**

#### Method B — Quick Return (AJAX)
1. Click **Mark Returned** on a distribution row
2. Set the return date in the inline field
3. Confirm the return

**Return Business Logic:**
- **Software:** Decrements the `assigned` count. If no copies remain assigned, resets `is_assigned = 2`.
- **Physical Items:** Sets `is_assigned = 2` (Available).
- The `return_date` is set on the `Transection` record.

### 13.4 Print Acknowledgement (ACK)

1. **Single ACK:** Click **ACK** on a distribution row → A4 PDF is generated
2. **Multi-Item ACK:** Select multiple distributions, enter employee ID and issue date, click **Print ACK** → Combined A4 PDF for all selected items

### 13.5 Print Return Receipt

1. Click **Return Receipt** on a returned distribution row
2. A PDF return form is generated

---

## 14. Requisition Management

**Route:** `/requisitions`  
**Permissions Required:** *(Currently no explicit permission middleware)*

Requisitions allow departments to formally request assets.

### 14.1 View Requisitions

1. Navigate to **Requisitions** from the sidebar
2. All requisitions are listed

### 14.2 Create Requisition

1. Click **Create Requisition**
2. Fill in:
   - **Product Type** *(required, dropdown)*
   - **Product** *(optional, dropdown — filtered by type)*
   - **Department** *(required, dropdown)*
   - **Quantity** *(required)*
   - **Description** *(optional)*
   - **Justification** *(optional)*
   - **Remarks** *(optional)*
3. Click **Save**
4. Status is set to **Pending** automatically

---

## 15. QR Code & Barcode Management

**Permissions Required:** `qrcode-generate`, `qrcode-print`, `barcode-generate`, `barcode-print`

The system supports three types of labels: QR codes, barcodes, and combo (QR + Barcode) labels.

### 15.1 QR Code Generation

QR codes contain structured data about the stock item:
- Organization name
- Serial/Service tag number
- Product type and model
- Asset tag
- Purchase date
- URL link to the stock detail page

### 15.2 Individual Stock Labels

From any stock item view or the inventory list:

| Action | Label Size | Content |
|---|---|---|
| **Print QR Code** | 1.4" × 1.4" | QR code with stock data |
| **Print Barcode** | 3.5" × 1.4" | Code128 barcode with serial |
| **Print Combo Label** | 1.4" × 2.5" | QR code + Code128 barcode |

### 15.3 Bulk Label Printing

1. In the Inventory list, select multiple stock items using checkboxes
2. Choose the print action:
   - **Print Multiple QR Codes** — A4 page with multiple 1.4" QR labels
   - **Print Multiple Barcodes** — A4 page with multiple barcode labels
   - **Print Multiple Combo Labels** — A4 page with 1.4" × 2.5" combo labels

### 15.4 Purchase-Level Label Printing

From a purchase view:

| Action | Description |
|---|---|
| **Print QR Codes** | All stock items from the purchase on one PDF |
| **Print QR Labels** | Individual 1.4" × 1.4" QR labels per stock |
| **Print Combo Labels** | Individual 1.4" × 2.5" QR + Barcode labels per stock |

### 15.5 Custom QR Code Generation

**Route:** `/qr-codes`

1. Navigate to **QR Code Generator**
2. Options:
   - **Custom Data** — Enter up to 1,000 characters of text
   - **URL** — Enter a URL to generate a QR code
3. Customize: Color, margin, error correction level
4. Click **Generate**

---

## 16. Reports

**Route:** `/reports/*`  
**Permissions Required:** `report-list`, `report-view`, `report-employee`, `report-product`, `report-distribution`, `report-purchase`, `report-inventory`, `users-log`, `user-log-view`

### 16.1 Employee Report

**Route:** `/reports/employees`

1. Navigate to **Reports → Employees**
2. Filter by **Department** and **Sort Order**
3. View employee listing with asset assignment summary
4. Click on an employee to see detailed asset report

### 16.2 Stock Summary Report

**Route:** `/reports/stocks`

1. Navigate to **Reports → Stocks**
2. View stock summary **grouped by product type**:
   - Total items per type
   - Assigned count
   - Available count
   - Total value (from purchase data)
3. Click on a type to drill down to detailed stock list

### 16.3 Transaction Report

**Route:** `/reports/transections`

1. Navigate to **Reports → Transactions**
2. Filter by **Employee** and sort
3. View all transaction history with issue/return dates

### 16.4 Detailed Inventory Report

**Route:** `/reports/detailed-inventory`

1. Navigate to **Reports → Detailed Inventory**
2. Apply filters:
   - **Product Type**
   - **Product Model**
   - **Store / Location**
   - **Supplier**
   - **Department**
   - **Condition** (Good / Obsolete / Damaged)
   - **Asset Status**
   - **Assignment Status** (Assigned / Available)
   - **Warranty Status** (Active / Expiring / Expired)
   - **Date Range** (purchase date)
3. Click **Search** — Results displayed via DataTable
4. Each row shows complete stock details with current assignment

### 16.5 User Activity Logs

**Route:** `/reports/user-logs`

1. Navigate to **Reports → User Logs**
2. Filter by:
   - **User** (dropdown)
   - **Action Type** (dropdown)
   - **Date Range**
3. View all logged user actions with timestamps and IP addresses

---

## 17. User Management

**Route:** `/users`  
**Permissions Required:** `user-list`, `user-create`, `user-edit`, `user-delete`

### 17.1 View Users

1. Navigate to **Users** from the sidebar
2. **Super Admin** sees all users
3. **Other admins** see only non-admin users

### 17.2 Create User

1. Click **Create User**
2. Fill in:
   - **Name** *(required)*
   - **Username** *(required, unique)*
   - **Email** *(required, unique)*
   - **Employee ID** *(required, unique — links to Employee record)*
   - **Role** *(required, dropdown)*
   - **Password** *(required, min 6 characters)*
   - **Confirm Password** *(required, must match)*
3. Click **Save**

**Business Rules:**
- Non-super-admins **cannot** see or assign the `super-admin` role
- If a user is assigned the `super-admin` role, their `is_admin` flag is set to `1`

### 17.3 Edit User

1. Click **Edit** on a user row
2. Update fields (password is optional — leave blank to keep current)
3. Change role assignment if needed
4. Click **Update**

### 17.4 Delete User

1. Click **Delete** on a user row
2. Confirm the deletion
3. All roles are revoked before the user is deleted

**Restriction:** Non-super-admins **cannot** delete users with the super-admin role.

---

## 18. Role & Permission Management

**Route:** `/roles`  
**Permissions Required:** `role-list`, `role-create`, `role-edit`, `role-delete`

### 18.1 View Roles

1. Navigate to **Roles** from the sidebar
2. All roles are listed **except** the super-admin role (ID 1)

### 18.2 Create Role

1. Click **Create Role**
2. Enter **Role Name** *(required, unique)*
3. Select **Permissions** — Check individual permissions to grant
4. Click **Save**

**Restriction:** The name `super-admin` is blocked — it cannot be used for new roles.

### 18.3 Edit Role

1. Click **Edit** on a role row
2. Update name and/or permissions
3. Click **Update**

**Restriction:** The super-admin role cannot be edited.

### 18.4 Delete Role

1. Click **Delete** on a role row
2. Confirm the deletion
3. All permissions are revoked from the role before deletion

**Restriction:** The super-admin role cannot be deleted.

---

## 19. Employee Management (Management Module)

**Route:** `/management/employees`  
**Permission Required:** `management-all`

This module handles employee offboarding/resignation and product status management.

### 19.1 Employee Offboarding

1. Navigate to **Management → Employees**
2. View list of active employees
3. Click **Edit** on an employee
4. Enter **Date of Resignation** *(required)*
5. Click **Submit**

**Automated Offboarding Process:**
The system automatically:
1. **Closes all active transactions** — Sets `return_date` on every active assignment
2. **Makes all stock available** — Sets `is_assigned = 2` on all previously assigned items
3. **Updates employee status** — Sets status to **Inactive** (2)
4. **Records resign date** — Saves the date of resignation

> **Warning:** This action processes all active transactions for the employee. Ensure all assets have been physically collected before proceeding.

### 19.2 Product Status Management

**Route:** `/management/products`

1. Navigate to **Management → Products**
2. View all stock items
3. Update the product status for any item:
   - **Active** (1)
   - **Poor** (2)
   - **Damaged** (3)

---

## 20. Onboarding Module

**Route:** `/onboarding`  
**Permissions Required:** `onboarding-list`, `onboarding-create`

### 20.1 View Onboarding

1. Navigate to **Onboarding**
2. View all employees available for onboarding

### 20.2 Generate Onboarding Form

1. Select an employee
2. Check the equipment to include:
   - ☐ Laptop
   - ☐ Mobile
   - ☐ Pen Drive
   - ☐ Mouse
   - ☐ Camera
   - ☐ Laptop Bag
   - ☐ SD Card
   - ☐ Manual
3. Click **Print**
4. An **Acknowledgement PDF** is generated

### 20.3 Generate Return Form

1. Select an employee
2. Check the equipment
3. Enable the **Return** option
4. Click **Print**
5. A **Return Form PDF** is generated

---

## 21. Data Import

**Route:** `/imports`  
**Permissions Required:** `imports-list`, `import-create`

### 21.1 Import Data

1. Navigate to **Imports** from the sidebar
2. Select the **Import Type:**

   | Import Type | Description |
   |---|---|
   | **Products** | Import product records |
   | **Purchase Products** | Import purchase product records |
   | **Stock (All)** | Import complete stock records |
   | **Transactions** | Import transaction records |
   | **Employees** | Import employee records |

3. Upload a **CSV file** *(CSV format only)*
4. Click **Import**
5. The system processes the file and imports records

> **Audit:** All imports are logged with the import type and user.

### 21.2 CSV File Requirements

Each import type expects specific column headers matching the model's fillable fields. Refer to the corresponding Import class for exact column mappings.

---

## 22. Settings

### 22.1 Profile Settings

**Route:** `/settings/profile`

1. Navigate to **Settings → Profile**
2. Update:
   - **Name**
   - **Profile Photo** *(image file, resized to 400×400)*
3. Click **Update Profile**
4. Both the User record and linked Employee record are updated

### 22.2 Password Change

**Route:** `/settings/password`

1. Navigate to **Settings → Password**
2. Enter:
   - **Current Password** *(required)*
   - **New Password** *(required, min 6 characters)*
   - **Confirm New Password** *(required, must match)*
3. Click **Change Password**
4. The `must_change_password` flag is cleared upon success

### 22.3 Policy Print

**Route:** `/policy-print/{id}`  
**Permission Required:** `system-settings`

1. Navigate to a transaction's policy view
2. View the policy document associated with the transaction

---

## 23. Audit / User Activity Logs

The system maintains a comprehensive audit trail of all user actions.

### 23.1 What is Logged

| Category | Actions Logged |
|---|---|
| **Authentication** | Login, Logout |
| **Master Data** | Create, Update, Delete of product types, products, stores, statuses, suppliers, departments |
| **Employees** | Create, Update, Status change |
| **Purchases** | Create, Update, Add to inventory |
| **Inventory** | Update, Tag update, Bulk upload |
| **Distribution** | Create (issue), Return, Multi-ACK |
| **Management** | Employee offboarding, Product status update |
| **Imports** | Data import operations |
| **Settings** | Profile update, Password change |

### 23.2 Log Data Captured

Each log entry includes:
- **User** — Who performed the action
- **Action** — Type of action (e.g., `login`, `create`, `update`, `delete`)
- **Details** — Descriptive text of what was done
- **IP Address** — Client IP
- **User Agent** — Browser/device information
- **Timestamp** — When the action occurred

### 23.3 Viewing Logs

See [Section 16.5 — User Activity Logs](#165-user-activity-logs)

---

## 24. Key Business Workflows

### 24.1 Complete Asset Lifecycle

```
┌────────────┐     ┌──────────────┐     ┌────────────────┐     ┌──────────────┐
│  PURCHASE   │────▶│ ADD TO       │────▶│  DISTRIBUTE    │────▶│   RETURN     │
│  (Create    │     │ INVENTORY    │     │  (Issue to     │     │   (Collect   │
│   PO)       │     │ (Stock       │     │   Employee)    │     │    back)     │
│             │     │  Created)    │     │                │     │              │
└────────────┘     └──────────────┘     └────────────────┘     └──────────────┘
       │                   │                    │                      │
       ▼                   ▼                    ▼                      ▼
  GRN PDF            Asset Tags           ACK PDF             Return PDF
  Invoice           QR/Barcode Labels     Policy Print
```

### 24.2 Purchase to Inventory Workflow

1. **Create Purchase** → Select supplier, add product lines, enter serials/warranty
2. **Purchase Pending** → Review purchase details, generate GRN
3. **Add to Inventory** → For each product line, stocks are created
4. **Asset Tagging** → Auto-generated (if taggable) or manually assigned
5. **Label Generation** → Print QR codes, barcodes, or combo labels
6. **Stock Ready** → Items appear in inventory as "Available"

### 24.3 Distribution Workflow

1. **Create Distribution** → Select product type > available item > employee
2. **Issue Recorded** → Stock marked as "Assigned", transaction created
3. **Print ACK** → Acknowledgement document generated for signature
4. **Asset in Use** → Stock status reflects current assignment
5. **Return** → Employee returns item, return date recorded
6. **Stock Available** → Item returns to available pool

### 24.4 Employee Offboarding Workflow

1. **Initiate Offboarding** → Management module > select employee > enter resign date
2. **Auto-Return** → ALL active transactions are closed automatically
3. **Stock Released** → All assigned items become "Available"
4. **Employee Deactivated** → Status set to "Inactive"

### 24.5 Software License Distribution Workflow

1. **Purchase Software** → Enter total quantity (e.g., 50 licenses)
2. **Add to Inventory** → Single stock record with quantity=50
3. **Distribute** → Each distribution reduces available count
4. **Return** → Available count is restored
5. **Tracking** → System tracks: Total, Assigned, Available at all times

---

## 25. Troubleshooting & FAQ

### 25.1 Common Issues

| Issue | Solution |
|---|---|
| Cannot login | Verify email and password. Check if account exists. Minimum password: 6 characters. |
| Redirected to password change | Your account has `must_change_password` flag. Change your password to proceed. |
| Cannot delete a product type | Remove all associated products first. |
| Cannot delete a product | Remove all purchase products referencing it first. |
| Cannot delete a supplier | Remove all purchases for this supplier first. |
| Cannot delete a department | Reassign or remove all employees in the department. |
| Cannot delete a store | Reassign all stock items to a different store first. |
| Cannot delete an asset status | Reassign all stock items to a different status first. |
| Serial count mismatch | When creating a purchase, the number of serial numbers must match the quantity exactly. |
| Asset tag not generated | Ensure the product has `is_taggable` checked. Tags are generated during "Add to Inventory". |
| QR code not rendering | System falls back from PNG to SVG to HTML. Check PHP Imagick extension if PNG fails. |
| Barcode too long / scaling issues | The system uses adaptive scaling for Code128B based on serial length. |
| Import fails | Ensure the file is CSV format and column headers match the expected format. |
| Permission denied | Contact your administrator to verify your role has the required permissions. |

### 25.2 Invoice Number Format

The auto-generated invoice follows the format: `DDMMYYYY-NNN`
- DD = Day (2 digits)
- MM = Month (2 digits)
- YYYY = Year (4 digits)
- NNN = Sequential number (3 digits)

### 25.3 Asset Tag Format

Auto-generated asset tags follow the format: `{TypePrefix}{5-digit-number}`
- TypePrefix = First letter of the product type name (uppercase)
- Example: `L00001` (Laptop), `M00001` (Monitor), `S00001` (Software)
- The system finds the highest existing number for each prefix and increments

### 25.4 Stock Assignment Status Codes

| Code | Status | Meaning |
|---|---|---|
| 1 | Assigned | Currently assigned to an employee |
| 2 | Available | Available for distribution |

### 25.5 Employee Status Codes

| Code | Status |
|---|---|
| 1 | Active |
| 2 | Inactive / Resigned |

### 25.6 Product Condition Codes

| Code | Condition |
|---|---|
| 1 | Active (Good) |
| 2 | Poor |
| 3 | Damaged |

---

## 26. Appendix — Permission Reference

### Complete Permission List (52 Permissions)

| # | Permission | Module | Description |
|---|---|---|---|
| 1 | `role-list` | Roles | View roles list |
| 2 | `role-create` | Roles | Create new roles |
| 3 | `role-edit` | Roles | Edit existing roles |
| 4 | `role-delete` | Roles | Delete roles |
| 5 | `user-list` | Users | View users list |
| 6 | `user-create` | Users | Create new users |
| 7 | `user-edit` | Users | Edit existing users |
| 8 | `user-delete` | Users | Delete users |
| 9 | `product-type-list` | Product Types | View product types |
| 10 | `product-type-create` | Product Types | Create product types |
| 11 | `product-type-edit` | Product Types | Edit product types |
| 12 | `product-type-delete` | Product Types | Delete product types |
| 13 | `product-list` | Products | View products |
| 14 | `product-create` | Products | Create products |
| 15 | `product-edit` | Products | Edit products |
| 16 | `product-delete` | Products | Delete products |
| 17 | `store-list` | Stores | View stores/locations |
| 18 | `store-create` | Stores | Create stores |
| 19 | `store-edit` | Stores | Edit stores |
| 20 | `store-delete` | Stores | Delete stores |
| 21 | `status-list` | Asset Statuses | View statuses |
| 22 | `status-create` | Asset Statuses | Create statuses |
| 23 | `status-edit` | Asset Statuses | Edit statuses |
| 24 | `status-delete` | Asset Statuses | Delete statuses |
| 25 | `suppliers-list` | Suppliers | View suppliers |
| 26 | `suppliers-create` | Suppliers | Create suppliers |
| 27 | `suppliers-edit` | Suppliers | Edit suppliers |
| 28 | `suppliers-delete` | Suppliers | Delete suppliers |
| 29 | `purchase-list` | Purchases | View purchases |
| 30 | `purchase-create` | Purchases | Create purchases |
| 31 | `purchase-edit` | Purchases | Edit purchases |
| 32 | `purchase-delete` | Purchases | Delete purchases |
| 33 | `purchase-addinventory` | Purchases | Add to inventory (approve) |
| 34 | `purchase-approve` | Purchases | Approve purchases |
| 35 | `department-list` | Departments | View departments |
| 36 | `department-create` | Departments | Create departments |
| 37 | `department-edit` | Departments | Edit departments |
| 38 | `department-delete` | Departments | Delete departments |
| 39 | `employee-list` | Employees | View employees |
| 40 | `employee-create` | Employees | Create employees |
| 41 | `employee-edit` | Employees | Edit employees |
| 42 | `employee-delete` | Employees | Delete employees |
| 43 | `inventory-list` | Inventory | View inventory |
| 44 | `inventory-create` | Inventory | Create inventory |
| 45 | `inventory-edit` | Inventory | Edit inventory |
| 46 | `inventory-delete` | Inventory | Delete inventory |
| 47 | `inventory-update-tag` | Inventory | Update asset tags |
| 48 | `distribution-list` | Distributions | View distributions |
| 49 | `distribution-create` | Distributions | Create distributions |
| 50 | `distribution-edit` | Distributions | Edit distributions |
| 51 | `distribution-delete` | Distributions | Delete distributions |
| 52 | `distribution-return` | Distributions | Process returns |
| 53 | `report-list` | Reports | View reports list |
| 54 | `report-view` | Reports | View report details |
| 55 | `report-employee` | Reports | Employee reports |
| 56 | `report-product` | Reports | Product reports |
| 57 | `report-distribution` | Reports | Distribution reports |
| 58 | `report-purchase` | Reports | Purchase reports |
| 59 | `report-inventory` | Reports | Inventory reports |
| 60 | `qrcode-generate` | QR/Barcode | Generate QR codes |
| 61 | `qrcode-print` | QR/Barcode | Print QR codes |
| 62 | `barcode-generate` | QR/Barcode | Generate barcodes |
| 63 | `barcode-print` | QR/Barcode | Print barcodes |
| 64 | `imports-list` | Imports | View imports page |
| 65 | `import-create` | Imports | Execute imports |
| 66 | `users-log` | System | View user logs |
| 67 | `user-log-view` | System | View log details |
| 68 | `system-settings` | System | System settings access |
| 69 | `management-all` | Management | Full management access |
| 70 | `onboarding-list` | Onboarding | View onboarding |
| 71 | `onboarding-create` | Onboarding | Create onboarding |
| 72 | `self-view-profile` | Self-Service | View own profile |
| 73 | `self-view-assets` | Self-Service | View own assets |
| 74 | `self-view-transactions` | Self-Service | View own transactions |

---

### Role — Permission Mapping (Default)

| Role | Permissions |
|---|---|
| **Super Admin** | All 52+ permissions |
| **Admin** | All 52+ permissions |
| **Employee** | `self-view-profile`, `self-view-assets`, `self-view-transactions` |

---

*End of Document*

*This SOP should be reviewed and updated whenever significant system changes are made.*
