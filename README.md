# Hotel Management Web Application (PHP MVC)

## Project Description
This repository contains an academic **Hotel Management** web application developed with **PHP 8+, MySQL, PDO, and sessions**.

The application provides an admin interface to manage:
- clients,
- rooms (including room images),
- reservations,
- and reservation search with multiple criteria.

It follows a classic **MVC architecture** with separate controllers, models (entities + managers), and views rendered in PHP templates.

---

## Objectives
This project demonstrates the following educational and technical objectives:

- Build a dynamic PHP web application.
- Apply the **MVC design pattern**.
- Use **Object-Oriented Programming (OOP)** for domain modeling.
- Access MySQL securely via **PDO**.
- Handle authentication with **PHP sessions**.
- Implement CRUD operations for core entities.
- Implement multi-criteria reservation search.
- Handle room image upload via `$_FILES` and a dedicated uploader class.

---

## Main Features

### Implemented
- **Authentication**
  - Admin login (`auth/login`) with password verification.
  - Logout (`auth/logout`) with session destruction.
- **Dashboard**
  - Basic admin landing page after login.
- **Client Management**
  - Create, read, update, delete clients.
- **Room Management**
  - Create, read, update, delete rooms.
  - Upload room image (JPG/PNG/WEBP, max size from config).
- **Reservation Management**
  - Create, read, update, delete reservations.
  - Multi-criteria search by client name, room number, date range, status.

### Partially Implemented / Missing
- No role-based access control (single authenticated admin context).
- No availability conflict check to prevent overlapping reservations for the same room.
- No pagination for list pages.
- No CSRF token protection in forms.

---

## Project Architecture

The project uses a lightweight MVC approach driven by a front controller (`public/index.php`).

### MVC Responsibilities
- **Controllers (`app/controllers`)**
  - Read request data (`$_GET`, `$_POST`, `$_FILES`).
  - Validate basic input.
  - Call manager/model methods.
  - Load corresponding view templates.
- **Models (`app/models`)**
  - `Database`: singleton PDO connection factory.
  - `entities/*`: domain classes (`User`, `Client`, `Room`, `Reservation`).
  - `managers/*`: DAO/services for CRUD and search.
  - `Uploader`: room image upload validation + move logic.
- **Views (`app/views`)**
  - PHP/HTML templates for auth, dashboard, clients, rooms, reservations, and shared layout.

### Core Supporting Classes
- **Database class**
  - Loads DB config and creates PDO with exception mode.
- **Uploader class**
  - Validates upload error, file size, and MIME type.
  - Generates unique filename and stores in `uploads/rooms/`.
- **Manager classes**
  - Encapsulate SQL operations with prepared statements.
- **Entity classes**
  - Encapsulate entity attributes with getters/setters.

---

## Project Structure

```text
Hotel-managment-php/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ClientController.php
│   │   ├── ReservationController.php
│   │   └── RoomController.php
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
│       ├── auth/login.php
│       ├── dashboard/index.php
│       ├── clients/{index,create,edit}.php
│       ├── rooms/{index,create,edit}.php
│       ├── reservations/{index,create,edit}.php
│       └── layouts/{header,footer}.php
├── config/
│   └── config.php
├── database/
│   ├── hotel_management.sql
│   └── seed.sql
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/style.css
│       └── js/script.js
├── uploads/
│   └── rooms/
└── README.md
```

---

## Database Design

The SQL schema is located in `database/hotel_management.sql` and seeds in `database/seed.sql`.

### Main Tables
- `users`: admin account credentials.
- `clients`: hotel clients.
- `rooms`: room catalog with type, price, status, optional image.
- `reservations`: links `client_id` + `room_id` with date range and status.

### Relationships
- `reservations.client_id` → `clients.id` (`ON DELETE CASCADE`).
- `reservations.room_id` → `rooms.id` (`ON DELETE RESTRICT`).

### Notes
- Room numbers are unique.
- User emails are unique.
- Reservation and room statuses are constrained by SQL `ENUM`.
- Seed data includes one admin user and sample clients/rooms/reservations.

---

## OOP Design

OOP is used in these layers:

- **Entities** (`User`, `Client`, `Room`, `Reservation`):
  - private attributes,
  - constructor initialization,
  - getters/setters.
- **Managers** (`UserManager`, `ClientManager`, `RoomManager`, `ReservationManager`):
  - each manager owns persistence operations for one entity.
- **Infrastructure classes**:
  - `Database` for shared PDO lifecycle,
  - `Uploader` for upload-specific behavior.

### Design Observation
Managers currently return arrays for many read operations instead of hydrated entity objects; this is practical but not fully consistent with a pure domain-driven OOP approach.

---

## Authentication Flow

1. User opens login page (`controller=auth&action=login`).
2. Submitted credentials are validated via `UserManager::findByEmail()`.
3. `password_verify()` checks plaintext password against hashed DB value.
4. On success, `$_SESSION['user']` is created and user is redirected to dashboard.
5. Protected controllers (`ClientController`, `RoomController`, `ReservationController`) call `ensureAuthenticated()` in constructor.
6. Logout clears and destroys session then redirects to login.

---

## Routing / Navigation

Routing is handled by `public/index.php`:
- Reads `controller` and `action` from query string.
- Maps controller key to file/class.
- Instantiates controller and invokes action method if it exists.
- Special-cases `dashboard` route directly in front controller.

### Entry Point
- Main entry: `public/index.php`.

---

## Installation

### Prerequisites
- PHP 8.0+ (with PDO MySQL extension)
- MySQL / MariaDB
- Apache (XAMPP/LAMP/WAMP) or PHP built-in server

### Steps (XAMPP-style)

```bash
# 1) Clone
git clone <your-repo-url>

# 2) Place in htdocs
# Example path:
# C:\xampp\htdocs\Hotel-managment-php
```

1. Start **Apache** and **MySQL**.
2. Create/import database:
   - Import `database/hotel_management.sql`.
   - Import `database/seed.sql`.
3. Check DB settings in `config/config.php`:
   - host, port, dbname, username, password.
4. Ensure upload directory is writable:
   - `uploads/rooms/`.

---

## How to Run the Project

### Option A: Apache (recommended for this project)
Open in browser:

```text
http://localhost/Hotel-managment-php/public/index.php
```

### Option B: PHP built-in server
From project root:

```bash
php -S localhost:8000 -t public
```

Then open:

```text
http://localhost:8000/index.php
```

### Demo Credentials
- **Email:** `admin@hotel.com`
- **Password:** `admin123`

---

## Example URLs

- Login:
  - `index.php?controller=auth&action=login`
- Dashboard:
  - `index.php?controller=dashboard&action=index`
- Clients:
  - `index.php?controller=client&action=index`
  - `index.php?controller=client&action=create`
  - `index.php?controller=client&action=edit&id=1`
- Rooms:
  - `index.php?controller=room&action=index`
  - `index.php?controller=room&action=create`
- Reservations:
  - `index.php?controller=reservation&action=index`
  - `index.php?controller=reservation&action=create`
  - Search example:
    - `index.php?controller=reservation&action=index&client_name=John&status=confirmed`

---

## Screens / Functional Modules

- **Auth screen**: admin login form + error feedback.
- **Dashboard**: simple welcome panel and navigation hub.
- **Clients module**: tabular listing + create/edit/delete forms.
- **Rooms module**: listing, create/edit/delete, image display/upload.
- **Reservations module**: listing, create/edit/delete, filtering/search form.

---

## Security Review

Below is a concise audit based on the current code.

### Positive Points
- Uses PDO prepared statements in CRUD and search queries.
- Passwords are hashed and verified with `password_verify()`.
- Output escaping (`htmlspecialchars`) is applied on most dynamic view data.
- Upload MIME type and file size checks exist.

### Identified Risks & Fixes

1. **Missing CSRF protection (High)**
   - **Where:** all state-changing forms (create/edit/delete/logout).
   - **Risk:** attacker can force authenticated admin actions via cross-site requests.
   - **Fix:** add CSRF token generation in session + hidden form token + server-side validation.

2. **State-changing actions via GET (High)**
   - **Where:** delete actions are triggered by links with query params (`action=delete&id=...`).
   - **Risk:** accidental deletion, CSRF amplification, unsafe semantics.
   - **Fix:** switch delete routes to POST (or DELETE), require CSRF token, and confirm intent server-side.

3. **Session fixation hardening missing (Medium)**
   - **Where:** login flow does not call `session_regenerate_id(true)` after authentication.
   - **Risk:** attacker can reuse pre-auth session ID.
   - **Fix:** regenerate session ID on successful login and set stricter cookie flags.

4. **Weak authorization granularity (Medium)**
   - **Where:** only checks “session exists”; no role/permission model.
   - **Risk:** no separation of privileges if multiple user types are introduced.
   - **Fix:** add role column + authorization middleware/checks per action.

5. **Upload hardening can be improved (Medium)**
   - **Where:** `Uploader` accepts extension from original filename and stores under web-accessible path.
   - **Risk:** extension spoofing edge cases; direct public serving of uploads.
   - **Fix:** derive extension from trusted MIME mapping, optionally re-encode images, store outside web root and serve via controlled endpoint.

6. **Validation is minimal (Low/Medium)**
   - **Where:** controllers perform only basic non-empty/format checks.
   - **Risk:** inconsistent data quality (invalid phones/emails/status/date logic edge cases).
   - **Fix:** add centralized validation layer and stricter domain rules.

7. **Business rule missing: reservation overlap check (Functional + Integrity)**
   - **Where:** reservation create/update.
   - **Risk:** same room can be double-booked for overlapping dates.
   - **Fix:** enforce overlap query before insert/update and reject conflicting bookings.

### Potential Security Improvements
- Add security headers (CSP, X-Frame-Options, X-Content-Type-Options).
- Add rate limiting / brute-force protection on login.
- Log authentication and destructive actions.
- Add account lockout policy.

---

## Code Quality Review

### Strengths
- Clean separation of MVC folders.
- Controllers are relatively small and readable.
- SQL is mostly parameterized.
- View templates consistently reused via header/footer layout.

### Inconsistencies / Maintainability Issues
- Typo in repository name (`managment` instead of `management`).
- No autoloader (many manual `require_once`).
- Mixed approach: entities exist but managers often return associative arrays.
- Repeated helper methods (`ensureAuthenticated`, `redirect`) across controllers.
- No central error handler (raw 404 text in router).

### Refactoring Ideas
- Introduce base controller for shared auth/redirect behavior.
- Add lightweight router abstraction and middleware pipeline.
- Use Composer PSR-4 autoloading.
- Normalize manager return types (entity objects or DTOs).
- Introduce service layer for business rules (e.g., booking availability).

---

## Perspectives / Future Improvements

- Role-based access control (admin/receptionist/manager).
- Reservation availability conflict detection.
- Pagination + sorting on large tables.
- CSRF protection on all forms.
- Strong validation/sanitization service.
- Better file management (preview, replace, delete old images).
- Reporting/statistics dashboard.
- Calendar-based reservation visualization.
- REST API layer for mobile/front-end integration.
- Dockerized development environment.
- Unit/integration tests (PHPUnit).
- Centralized exception handling and user-friendly error pages.

---

## Possible Improvements for Academic Defense

For oral presentation/demo, highlight:

- How MVC is implemented from front controller to views.
- OOP usage with entities/managers and responsibility separation.
- Relational schema and foreign keys (`clients`, `rooms`, `reservations`).
- Authentication flow with sessions and password hashing.
- CRUD cycle for each core module.
- Upload flow with `$_FILES` and validation in `Uploader`.
- SQL security with prepared statements via PDO.
- Honest discussion of current security gaps and planned mitigations.

---

## Authors / Contributors

- **Author(s):** _To be completed by project team._
- **Contributors:** _To be completed by project team._

---

## License
No explicit license file is currently present in the repository. Add a `LICENSE` file if redistribution is intended.
