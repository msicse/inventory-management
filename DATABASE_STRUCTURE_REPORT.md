# Database Structure Report

## Inventory Management System — Laravel Project

**Generated:** March 3, 2026

---

## Table of Contents

1. [Table Structures](#part-1-table-structures)
2. [Eloquent Model Relationships](#part-2-eloquent-model-relationships)
3. [Entity Relationship Diagram](#part-3-entity-relationship-diagram)
4. [Migration Alteration History](#part-4-migration-alteration-history)

---

## Part 1: Table Structures

### 1. `users`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| name | string | NO | — | — |
| username | string | NO | — | UNIQUE |
| employee_id | string | NO | — | UNIQUE |
| is_admin | string | NO | `'2'` | — |
| email | string | NO | — | UNIQUE |
| email_verified_at | timestamp | YES | NULL | — |
| password | string | NO | — | — |
| must_change_password | boolean | NO | `false` | — |
| remember_token | string(100) | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

> **Note:** `employee_id` is a string that links logically to `employees.emply_id` (no formal FK constraint).

---

### 2. `password_reset_tokens`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| email | string | NO | — | PRIMARY KEY |
| token | string | NO | — | — |
| created_at | timestamp | YES | NULL | — |

---

### 3. `sessions`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | string | NO | — | PRIMARY KEY |
| user_id | bigint unsigned | YES | NULL | INDEX, FK → `users.id` |
| ip_address | string(45) | YES | NULL | — |
| user_agent | text | YES | NULL | — |
| payload | longText | NO | — | — |
| last_activity | integer | NO | — | INDEX |

---

### 4. `cache`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| key | string | NO | — | PRIMARY KEY |
| value | mediumText | NO | — | — |
| expiration | integer | NO | — | — |

---

### 5. `cache_locks`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| key | string | NO | — | PRIMARY KEY |
| owner | string | NO | — | — |
| expiration | integer | NO | — | — |

---

### 6. `jobs`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| queue | string | NO | — | INDEX |
| payload | longText | NO | — | — |
| attempts | unsignedTinyInteger | NO | — | — |
| reserved_at | unsignedInteger | YES | NULL | — |
| available_at | unsignedInteger | NO | — | — |
| created_at | unsignedInteger | NO | — | — |

---

### 7. `job_batches`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | string | NO | — | PRIMARY KEY |
| name | string | NO | — | — |
| total_jobs | integer | NO | — | — |
| pending_jobs | integer | NO | — | — |
| failed_jobs | integer | NO | — | — |
| failed_job_ids | longText | NO | — | — |
| options | mediumText | YES | NULL | — |
| cancelled_at | integer | YES | NULL | — |
| created_at | integer | NO | — | — |
| finished_at | integer | YES | NULL | — |

---

### 8. `failed_jobs`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| uuid | string | NO | — | UNIQUE |
| connection | text | NO | — | — |
| queue | text | NO | — | — |
| payload | longText | NO | — | — |
| exception | longText | NO | — | — |
| failed_at | timestamp | NO | `CURRENT_TIMESTAMP` | — |

---

### 9. `producttypes`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| name | string | NO | — | — |
| slug | string | NO | — | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 10. `products`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| producttype_id | bigint unsigned | NO | — | FK → `producttypes.id` |
| title | string | NO | — | — |
| brand | string | NO | — | — |
| slug | string | NO | — | — |
| model | string | YES | NULL | — |
| unit | string | YES | NULL | — |
| is_serial | tinyInteger | NO | `2` | — |
| is_taggable | tinyInteger | NO | `2` | 1=Yes, 2=No |
| is_consumable | tinyInteger | NO | `2` | — |
| is_license | tinyInteger | NO | `2` | — |
| description | text | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `producttype_id` → `producttypes.id`

---

### 11. `suppliers`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| company | string | NO | — | — |
| name | string | NO | — | — |
| phone | string | NO | — | — |
| email | string | YES | NULL | — |
| address | text | NO | — | — |
| description | text | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 12. `departments`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| name | string | NO | — | — |
| short_name | string | NO | — | — |
| slug | string | NO | — | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 13. `employees`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| department_id | bigint unsigned | NO | — | FK → `departments.id` |
| emply_id | string | NO | — | — |
| name | string | NO | — | — |
| designation | string | NO | — | — |
| phone | string | NO | — | — |
| email | string | NO | — | — |
| blood | string | YES | NULL | — |
| gender | string | NO | — | — |
| location | string | NO | — | — |
| image | string | YES | NULL | — |
| date_of_join | string | YES | NULL | — |
| resign_date | string | YES | NULL | — |
| status | tinyInteger | NO | — | — |
| type | string | NO | `'employee'` | — |
| about | text | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `department_id` → `departments.id`

---

### 14. `purchases`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| supplier_id | bigint unsigned | NO | — | FK → `suppliers.id` |
| total_price | double | YES | NULL | — |
| invoice_no | string | YES | NULL | — |
| reference_invoice | string | YES | NULL | — |
| challan_no | string | YES | NULL | — |
| purchase_date | string | NO | — | — |
| received_date | string | YES | NULL | — |
| is_stocked | tinyInteger | NO | — | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `supplier_id` → `suppliers.id`

---

### 15. `purchase_products`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| product_id | bigint unsigned | NO | — | FK → `products.id` |
| supplier_id | bigint unsigned | NO | — | FK → `suppliers.id` |
| purchase_id | bigint unsigned | NO | — | FK → `purchases.id` |
| quantity | integer | NO | — | — |
| unit_price | double | NO | — | — |
| total_price | double | NO | — | — |
| serials | text | YES | NULL | — |
| warranty | text | YES | NULL | — |
| purchase_date | string | NO | — | — |
| received_date | string | YES | NULL | — |
| expired_date | string | YES | NULL | — |
| is_stocked | string | NO | — | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `product_id` → `products.id`
- `supplier_id` → `suppliers.id`
- `purchase_id` → `purchases.id`

---

### 16. `stocks`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| product_id | bigint unsigned | NO | — | FK → `products.id` |
| store_id | bigint unsigned | NO | — | FK → `stores.id` |
| status_id | bigint unsigned | NO | — | FK → `asset_statuses.id` |
| purchase_id | bigint unsigned | NO | — | FK → `purchases.id` |
| producttype_id | bigint unsigned | NO | — | FK → `producttypes.id` |
| pproduct_id | bigint unsigned | NO | — | FK → `purchase_products.id` |
| asset_tag | string | YES | NULL | — |
| serial_no | integer | YES | NULL | — |
| service_tag | string | YES | NULL | — |
| mac | string | YES | NULL | — |
| warranty | string | YES | NULL | — |
| purchase_date | string | YES | NULL | — |
| expired_date | string | YES | NULL | — |
| quantity | string | YES | NULL | — |
| assigned | tinyInteger | YES | NULL | — |
| is_assigned | tinyInteger | NO | — | — |
| asset_condition | string | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `product_id` → `products.id`
- `purchase_id` → `purchases.id`
- `producttype_id` → `producttypes.id`
- `pproduct_id` → `purchase_products.id`
- `store_id` → `stores.id`
- `status_id` → `asset_statuses.id`

---

### 17. `transections`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| stock_id | bigint unsigned | NO | — | FK → `stocks.id` |
| employee_id | bigint unsigned | NO | — | FK → `employees.id` |
| issued_date | string | YES | NULL | — |
| return_date | string | YES | NULL | — |
| quantity | integer | YES | NULL | — |
| comment | text | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `stock_id` → `stocks.id`
- `employee_id` → `employees.id`

---

### 18. `permissions` (Spatie)

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| name | string | NO | — | UNIQUE(name, guard_name) |
| guard_name | string | NO | — | UNIQUE(name, guard_name) |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 19. `roles` (Spatie)

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| name | string | NO | — | UNIQUE(name, guard_name) |
| guard_name | string | NO | — | UNIQUE(name, guard_name) |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 20. `model_has_permissions` (Spatie Pivot)

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| permission_id | bigint unsigned | NO | — | FK → `permissions.id` (CASCADE) |
| model_type | string | NO | — | — |
| model_id | bigint unsigned | NO | — | INDEX |

**Primary Key:** composite (`permission_id`, `model_id`, `model_type`)

---

### 21. `model_has_roles` (Spatie Pivot)

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| role_id | bigint unsigned | NO | — | FK → `roles.id` (CASCADE) |
| model_type | string | NO | — | — |
| model_id | bigint unsigned | NO | — | INDEX |

**Primary Key:** composite (`role_id`, `model_id`, `model_type`)

---

### 22. `role_has_permissions` (Spatie Pivot)

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| permission_id | bigint unsigned | NO | — | FK → `permissions.id` (CASCADE) |
| role_id | bigint unsigned | NO | — | FK → `roles.id` (CASCADE) |

**Primary Key:** composite (`permission_id`, `role_id`)

---

### 23. `activity_log` (Spatie Activity Log)

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| log_name | string | YES | NULL | INDEX |
| description | text | NO | — | — |
| subject_type | string | YES | NULL | INDEX (morphs) |
| subject_id | bigint unsigned | YES | NULL | INDEX (morphs) |
| event | string | YES | NULL | — |
| causer_type | string | YES | NULL | INDEX (morphs) |
| causer_id | bigint unsigned | YES | NULL | INDEX (morphs) |
| properties | json | YES | NULL | — |
| batch_uuid | uuid | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 24. `stores`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| name | string | NO | — | — |
| slug | string | NO | — | — |
| address | string | NO | — | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 25. `asset_statuses`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| name | string | NO | — | — |
| slug | string | NO | — | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

---

### 26. `requisitions`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| producttype_id | bigint unsigned | NO | — | FK → `producttypes.id` |
| product_id | bigint unsigned | YES | NULL | FK → `products.id` |
| department_id | bigint unsigned | NO | — | FK → `departments.id` |
| quantity | string | NO | — | — |
| status | string | NO | — | — |
| description | text | YES | NULL | — |
| justification | text | YES | NULL | — |
| remarks | text | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `producttype_id` → `producttypes.id`
- `product_id` → `products.id`
- `department_id` → `departments.id`

---

### 27. `user_logs`

| Column | Type | Nullable | Default | Constraints |
|--------|------|----------|---------|-------------|
| id | bigint (auto-increment) | NO | — | PRIMARY KEY |
| user_id | bigint unsigned | NO | — | FK → `users.id` (CASCADE) |
| action | string | NO | — | — |
| ip_address | string | YES | NULL | — |
| user_agent | string | YES | NULL | — |
| details | text | YES | NULL | — |
| created_at | timestamp | YES | NULL | — |
| updated_at | timestamp | YES | NULL | — |

**Foreign Keys:**
- `user_id` → `users.id` (ON DELETE CASCADE)

---

## Part 2: Eloquent Model Relationships

### AssetStatus → `asset_statuses`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| hasMany | `stocks()` | Stock | `status_id` |

---

### Department → `departments`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| hasMany | `employees()` | Employee | `department_id` |

---

### Employee → `employees`

| Relationship | Method | Related Model | Foreign Key | Local Key |
|-------------|--------|---------------|-------------|-----------|
| belongsTo | `department()` | Department | `department_id` | `id` |
| hasMany | `transections()` | Transection | `employee_id` | `id` |
| hasOne | `user()` | User | `employee_id` | `emply_id` |

---

### Product → `products`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| belongsTo | `type()` | Producttype | `producttype_id` |

**Casts:** `is_serial` → int, `is_license` → int, `is_taggable` → int, `is_consumable` → int

---

### Producttype → `producttypes`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| hasMany | `products()` | Product | `producttype_id` |
| hasMany | `stocks()` | Stock | `producttype_id` |

---

### Purchase → `purchases`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| hasMany | `products()` | PurchaseProduct | `purchase_id` |
| hasMany | `purchaseProducts()` | PurchaseProduct | `purchase_id` |
| belongsTo | `supplier()` | Supplier | `supplier_id` |

**Casts:** `purchase_date` → datetime, `received_date` → datetime

---

### PurchaseProduct → `purchase_products`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| belongsTo | `product()` | Product | `product_id` |
| belongsTo | `purchase()` | Purchase | `purchase_id` |
| belongsTo | `supplier()` | Supplier | `supplier_id` |

---

### Requisition → `requisitions`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| belongsTo | `type()` | Producttype | `producttype_id` |
| belongsTo | `department()` | Department | `department_id` |
| belongsTo | `product()` | Product | `product_id` |

---

### Role → `roles` (Spatie)

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| hasMany | `users()` | User | *(implicit)* |

**Traits:** `HasFactory`, `LogsActivity`

---

### Stock → `stocks`

| Relationship | Method | Related Model | Foreign Key | Notes |
|-------------|--------|---------------|-------------|-------|
| belongsTo | `product()` | Product | `product_id` | |
| belongsTo | `purchase()` | Purchase | `purchase_id` | |
| belongsTo | `producttype()` | Producttype | `producttype_id` | |
| belongsTo | `store()` | Store | `store_id` | |
| belongsTo | `status()` | AssetStatus | `status_id` | |
| belongsTo | `supplier()` | Supplier | `supplier_id` | Via purchase context |
| hasMany | `transections()` | Transection | `stock_id` | |
| hasMany | `currentUser()` | Transection | `stock_id` | Filtered: `whereNull('return_date')` |

**Casts:** `purchase_date` → datetime, `expired_date` → datetime

---

### Store → `stores`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| hasMany | `stocks()` | Stock | `store_id` |

---

### Supplier → `suppliers`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| hasMany | `purchases()` | Purchase | `supplier_id` |

---

### Transection → `transections`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| belongsTo | `employee()` | Employee | `employee_id` |
| belongsTo | `stock()` | Stock | `stock_id` |

---

### User → `users`

| Relationship | Method | Related Model | Foreign Key | Local Key |
|-------------|--------|---------------|-------------|-----------|
| hasOne | `profile()` | Employee | `emply_id` | `employee_id` |
| hasMany | `userLogs()` | UserLog | `user_id` | `id` |
| morphToMany | *(Spatie)* | Role | via `model_has_roles` | — |
| morphToMany | *(Spatie)* | Permission | via `model_has_permissions` | — |

**Traits:** `HasFactory`, `Notifiable`, `HasRoles` (Spatie), `LogsActivity` (Spatie)  
**Casts:** `email_verified_at` → datetime, `password` → hashed, `must_change_password` → boolean

---

### UserLog → `user_logs`

| Relationship | Method | Related Model | Foreign Key |
|-------------|--------|---------------|-------------|
| belongsTo | `user()` | User | `user_id` |

---

## Part 3: Entity Relationship Diagram

```
┌──────────────────┐         ┌──────────────────┐
│   Producttype    │────────<│     Product       │
│                  │         │                   │
│  id              │         │  id               │
│  name            │         │  producttype_id   │──> Producttype
│  slug            │         │  title, brand     │
└──────┬───────────┘         │  slug, model      │
       │                     │  is_serial         │
       │                     │  is_taggable       │
       │                     │  is_consumable     │
       │                     │  is_license        │
       │                     └────────┬───────────┘
       │                              │
       │    ┌─────────────────────────┤
       │    │                         │
       ▼    ▼                         ▼
┌──────────────────┐         ┌──────────────────┐
│     Stock        │         │ PurchaseProduct   │
│                  │         │                   │
│  id              │         │  id               │
│  product_id      │──> Product  product_id     │──> Product
│  producttype_id  │──> Producttype              │
│  purchase_id     │──> Purchase  purchase_id   │──> Purchase
│  pproduct_id     │──> PurchaseProduct          │
│  store_id        │──> Store    supplier_id    │──> Supplier
│  status_id       │──> AssetStatus              │
│  asset_tag       │         │  quantity          │
│  serial_no       │         │  unit_price        │
│  service_tag     │         │  total_price       │
│  mac             │         │  serials           │
│  warranty        │         │  warranty          │
│  quantity        │         │  purchase_date     │
│  is_assigned     │         │  received_date     │
│  asset_condition │         │  expired_date      │
└──────┬───────────┘         └──────────────────┘
       │                              ▲
       │                              │
       ▼                     ┌────────┴─────────┐
┌──────────────────┐         │    Purchase       │
│   Transection    │         │                   │
│                  │         │  id               │
│  id              │         │  supplier_id      │──> Supplier
│  stock_id        │──> Stock│  total_price      │
│  employee_id     │──> Employee  invoice_no    │
│  issued_date     │         │  purchase_date    │
│  return_date     │         │  received_date    │
│  quantity        │         │  is_stocked       │
│  comment         │         └──────────────────┘
└──────────────────┘

┌──────────────────┐         ┌──────────────────┐
│   Department     │────────<│    Employee       │
│                  │         │                   │
│  id              │         │  id               │
│  name            │         │  department_id    │──> Department
│  short_name      │         │  emply_id         │
│  slug            │         │  name             │
└──────┬───────────┘         │  designation      │
       │                     │  type             │
       │                     │  status           │
       │                     └────────┬──────────┘
       │                              │
       ▼                              │ hasOne (emply_id ↔ employee_id)
┌──────────────────┐                  ▼
│  Requisition     │         ┌──────────────────┐
│                  │         │      User         │
│  id              │         │                   │
│  producttype_id  │──> Producttype  id          │
│  product_id      │──> Product  employee_id    │──> Employee.emply_id
│  department_id   │──> Department  username     │
│  quantity        │         │  email            │
│  status          │         │  must_change_pwd  │
│  description     │         └────────┬──────────┘
│  justification   │                  │
│  remarks         │                  │ hasMany
└──────────────────┘                  ▼
                             ┌──────────────────┐
┌──────────────────┐         │    UserLog        │
│    Supplier      │         │                   │
│                  │         │  id               │
│  id              │         │  user_id          │──> User
│  company         │         │  action           │
│  name            │         │  ip_address       │
│  phone           │         │  user_agent       │
│  email           │         │  details          │
│  address         │         └──────────────────┘
└──────────────────┘

┌──────────────────┐         ┌──────────────────┐
│     Store        │         │   AssetStatus     │
│                  │         │                   │
│  id              │         │  id               │
│  name            │         │  name             │
│  slug            │         │  slug             │
│  address         │         └──────────────────┘
└──────────────────┘

          ┌──────────────────────────────────────────┐
          │       Spatie Permission System            │
          ├──────────────────────────────────────────┤
          │                                          │
          │  ┌──────┐  role_has_permissions  ┌────┐  │
          │  │ Role │◄──────────────────────►│Perm│  │
          │  └──┬───┘                        └──┬─┘  │
          │     │ model_has_roles               │    │
          │     ▼              model_has_perms   │    │
          │  ┌──────┐◄──────────────────────────┘    │
          │  │ User │                                │
          │  └──────┘                                │
          └──────────────────────────────────────────┘
```

### Relationship Summary (Compact)

```
Producttype  ──<  Product              (one-to-many)
Producttype  ──<  Stock                (one-to-many)
Producttype  ──<  Requisition          (one-to-many)

Product      ──<  PurchaseProduct      (one-to-many)
Product      ──<  Requisition          (one-to-many)

Supplier     ──<  Purchase             (one-to-many)
Supplier     ──<  PurchaseProduct      (one-to-many)

Purchase     ──<  PurchaseProduct      (one-to-many)
Purchase     ──<  Stock                (one-to-many)

PurchaseProduct ──< Stock              (one-to-many, via pproduct_id)

Department   ──<  Employee             (one-to-many)
Department   ──<  Requisition          (one-to-many)

Employee     ──<  Transection          (one-to-many)
Employee     ──1  User                 (one-to-one, emply_id ↔ employee_id)

Stock        ──<  Transection          (one-to-many)
Stock        >──  Store                (belongs-to)
Stock        >──  AssetStatus          (belongs-to)

Store        ──<  Stock                (one-to-many)
AssetStatus  ──<  Stock                (one-to-many)

User         ──<  UserLog              (one-to-many)
User         ──1  Employee             (one-to-one, profile)
User         ──<>──  Role              (many-to-many, via model_has_roles)
User         ──<>──  Permission        (many-to-many, via model_has_permissions)

Role         ──<>──  Permission        (many-to-many, via role_has_permissions)
```

**Legend:** `──<` one-to-many | `──1` one-to-one | `>──` belongs-to | `──<>──` many-to-many

---

## Part 4: Migration Alteration History

| Migration | Date | Table(s) Affected | Change Description |
|-----------|------|-------------------|-------------------|
| `add_store_id_status_id_and_remove_stats_from_stocks_table` | 2024-10-17 | `stocks` | Dropped `product_status`; Added `status_id` FK → `asset_statuses`, `store_id` FK → `stores` |
| `add_condition_to_stocks_table` | 2025-01-23 | `stocks` | Added `asset_condition` (string, nullable) |
| `add_type_to_employees_table` | 2025-06-17 | `employees` | Added `type` (string, default `'employee'`) after `status` |
| `add_is_taggable_to_products_table` | 2025-09-08 | `products` | Added `is_taggable` (tinyInteger, default 2) after `is_serial` |
| `add_is_consumable_to_products_table` | 2026-01-29 | `products` | Added `is_consumable` (tinyInteger, default 2) after `is_taggable` |
| `add_received_date_to_purchases_and_purchase_products` | 2026-01-29 | `purchases`, `purchase_products` | Added `received_date` (string, nullable) after `purchase_date` |
| `add_must_change_password_to_users_table` | 2026-02-08 | `users` | Added `must_change_password` (boolean, default false) after `password` |

---

## Summary Statistics

| Metric | Count |
|--------|-------|
| **Total Tables** | 27 |
| **Application Tables** | 16 |
| **Spatie Permission Tables** | 4 |
| **Spatie Activity Log Tables** | 1 |
| **Laravel Framework Tables** | 6 (sessions, cache, cache_locks, jobs, job_batches, failed_jobs) |
| **Eloquent Models** | 15 |
| **Foreign Key Constraints** | 22 |
| **Many-to-Many Relationships** | 3 (User↔Role, User↔Permission, Role↔Permission) |
| **One-to-One Relationships** | 1 (User↔Employee) |
