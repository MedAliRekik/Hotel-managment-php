# Project Title
Hotel Management Web Application (PHP MVC)

# Project Description
This is an academic hotel management web application built with **pure PHP**, **MySQL**, **PDO**, **sessions**, **MVC architecture**, and **OOP**.

The project now supports **two authentication contexts**:
- **Admin session** for hotel back-office management.
- **Client session** for self-service registration, login, room browsing, and reservation.

# Objectives
- Practice MVC using a simple front controller.
- Apply OOP with entities and managers.
- Use PDO prepared statements for database access.
- Manage separate sessions for admin and client users.
- Implement CRUD operations for clients, rooms, and reservations.
- Add client self-service registration/login and reservation flow.
- Keep the codebase beginner-friendly for academic learning.

# Main Features
## Admin Features
- Admin login/logout.
- Admin dashboard.
- Client CRUD management.
- Room CRUD management (with image upload).
- Reservation CRUD management.
- Reservation search/filter by client, room, dates, and status.

## Client Features
- Client registration with password.
- Client login/logout.
- Dedicated client dashboard.
- Client can view only available rooms.
- Client can reserve a room using their logged-in identity.
- Client can view their own reservations.

# Client Functionality
The client flow is:
1. Open registration page and create account with password.
2. Login with email/password.
3. Access dedicated client dashboard.
4. Open available rooms page (only rooms with `status = available`).
5. Reserve a selected room.

Important access rule:
- Client sessions (`$_SESSION['client']`) cannot access admin dashboard/CRUD pages, because admin controllers require `$_SESSION['user']`.

# Project Architecture
The project keeps the same lightweight MVC architecture.

- **Front Controller**: `public/index.php`
  - Starts session
  - Resolves `controller` + `action` from query string
  - Loads matching controller class and action
- **Controllers (`app/controllers`)**
  - Read request data, validate input, invoke managers, render views.
- **Models (`app/models`)**
  - `Database.php`: singleton PDO connection.
  - `entities/`: domain classes (`User`, `Client`, `Room`, `Reservation`).
  - `managers/`: SQL CRUD/search and booking checks.
  - `Uploader.php`: room image validation/upload.
- **Views (`app/views`)**
  - PHP templates for admin and client screens.

# Project Structure
```text
Hotel-managment-php/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ClientAuthController.php
│   │   ├── ClientController.php
│   │   ├── ClientPortalController.php
│   │   ├── ReservationController.php
│   │   └── RoomController.php
│   ├── helpers/
│   │   └── Csrf.php
│   ├── models/
│   │   ├── Database.php
│   │   ├── Uploader.php
│   │   ├── entities/
│   │   │   ├── Client.php
│   │   │   ├── Reservation.php
│   │   │   ├── Room.php
│   │   │   └── User.php
│   │   └── managers/
│   │       ├── ClientManager.php
│   │       ├── ReservationManager.php
│   │       ├── RoomManager.php
│   │       └── UserManager.php
│   └── views/
│       ├── auth/
│       │   ├── login.php
│       │   ├── client_login.php
│       │   └── client_register.php
│       ├── client_portal/
│       │   └── dashboard.php
│       ├── clients/
│       ├── dashboard/
│       ├── layouts/
│       ├── reservations/
│       └── rooms/
├── config/
│   └── config.php
├── database/
│   ├── hotel_management.sql
│   └── seed.sql
├── public/
│   ├── assets/
│   │   ├── css/style.css
│   │   └── js/script.js
│   └── index.php
├── uploads/
│   └── rooms/
└── README.md
```

# Database Design
Schema file: `database/hotel_management.sql`.

## Main Tables
- `users`
  - Admin accounts (`fullname`, `email`, `password`).
- `clients`
  - Client identity (`first_name`, `last_name`, `phone`, `email`, `password`).
- `rooms`
  - Room catalog (`room_number`, `type`, `price`, `status`, `image`).
- `reservations`
  - Reservation relation (`client_id`, `room_id`, `check_in`, `check_out`, `status`).

## Relationships
- `reservations.client_id` → `clients.id` (FK).
- `reservations.room_id` → `rooms.id` (FK).

## Authentication-related client fields
- Client authentication uses `clients.email` + `clients.password`.
- Password is stored as a hash using PHP `password_hash()`.

# Authentication Flows
## Admin Authentication Flow
1. Go to `auth/login`.
2. Submit email/password.
3. Password verified with `password_verify()` against `users.password` hash.
4. On success, `$_SESSION['user']` is created and session id is regenerated.
5. Admin-only controllers require `$_SESSION['user']`.

## Client Authentication Flow
1. Go to `clientauth/register` to create account, then `clientauth/login`.
2. Password verified with `password_verify()` against `clients.password` hash.
3. On success, `$_SESSION['client']` is created and session id is regenerated.
4. Client portal controller requires `$_SESSION['client']`.

# Client Registration Flow
1. Open: `index.php?controller=clientauth&action=register`
2. Required fields:
   - `first_name`
   - `last_name`
   - `email`
   - `password` (minimum 6 chars)
   - `phone` is optional
3. Server validates required fields, email format, minimum password length, and unique email.
4. Password is hashed with `password_hash(PASSWORD_DEFAULT)` before insert.
5. Client then logs in on `index.php?controller=clientauth&action=login`.
6. After successful login, client is redirected to the client dashboard.

# Client Dashboard
After client login, dashboard page:
- greets the client,
- shows quick counts (available rooms and personal reservations),
- provides links to:
  - available rooms,
  - my reservations.

# How to Run the Project
## Prerequisites
- PHP 8+
- Apache (XAMPP recommended)
- MySQL/MariaDB
- PDO MySQL extension enabled

## XAMPP Steps
1. Put project in `htdocs`, for example:
   - `C:\xampp\htdocs\Hotel-managment-php`
2. Start Apache and MySQL from XAMPP Control Panel.
3. Import SQL files in this order:
   - `database/hotel_management.sql`
   - `database/seed.sql`
4. Verify DB settings in `config/config.php`.
5. Ensure `uploads/rooms/` is writable.
6. Open:
   - `http://localhost/Hotel-managment-php/public/index.php`

# Example URLs
- Admin login:
  - `index.php?controller=auth&action=login`
- Client registration:
  - `index.php?controller=clientauth&action=register`
- Client login:
  - `index.php?controller=clientauth&action=login`
- Client dashboard:
  - `index.php?controller=clientportal&action=dashboard`
- Available rooms (client):
  - `index.php?controller=clientportal&action=rooms`
- Room reservation (client):
  - `index.php?controller=clientportal&action=reserve&room_id=1`

# Security Review
This section reflects the current code state.

## Vulnerabilities / Weak Points Found
1. **Missing CSRF protection on forms** (high risk).
2. **Unsafe delete via GET links** (high risk).
3. **No overlap check for room bookings** (integrity risk).
4. **Some validation was basic only** (data quality risk).
5. **Client/admin session separation needed explicit documentation and checks**.

## Fixes Applied
1. Added CSRF helper (`app/helpers/Csrf.php`) and CSRF token validation in key POST actions (login, registration, reservation create/edit, delete, logout).
2. Switched delete actions from GET links to POST forms with CSRF token.
3. Added reservation overlap check (`ReservationManager::hasRoomConflict`) and applied in admin create/edit and client reservation flow.
4. Added stricter date validation and check-out > check-in rule.
5. Enforced separate login contexts through existing `$_SESSION['user']` and `$_SESSION['client']` gates.

## Remaining Limitations
- No brute-force/rate-limiting on login endpoints.
- No centralized authorization middleware or roles beyond admin/client session checks.
- File uploads are still stored in a web-accessible directory.
- No audit logging yet.

# Perspectives / Future Improvements
- Add role/permission matrix for multi-admin scenarios.
- Add login throttling and lockout policy.
- Add security headers and stronger session cookie configuration.
- Add pagination and sorting for large tables.
- Add unit/integration tests.
- Add reservation cancellation rules and status transition policy.
- Add email confirmation / password reset for clients.

