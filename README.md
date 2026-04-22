# Hotel Management - PHP MVC Project

## Project Overview

This project is a small **Hotel Management web application** developed in **PHP** and **MySQL**, following the **MVC (Model - View - Controller)** architecture and **Object-Oriented Programming (OOP)** principles.

The application is designed for an academic project and must demonstrate:

- Dynamic web development using **HTML** and **PHP**
- Respect of the **MVC pattern**
- Use of **OOP**
- Database access with **PDO**
- Secure **authentication** using sessions
- File upload using a dedicated **Uploader** class
- CRUD operations on the main entities
- Multi-criteria search
- Proper use of PHP superglobals: `$_GET`, `$_POST`, `$_SESSION`, `$_FILES`

---

## Main Features

### Authentication
- Admin login
- Admin logout
- Session management
- Secure password verification with `password_hash()` and `password_verify()`

### Main Entities
The application manages the following entities:

- **User**
- **Client**
- **Room**
- **Reservation**

### CRUD Features
Full CRUD must be available for:

- Clients
- Rooms
- Reservations

### Search
A multi-criteria search must be implemented for reservations using:

- Client name
- Room number
- Check-in date
- Check-out date
- Reservation status

### File Upload
- Upload room images from the admin interface
- Store uploaded files in the `uploads/rooms/` directory
- Validate file type and size using a dedicated `Uploader` class

---

## Technical Requirements

### Architecture
The project must strictly follow the MVC pattern:

- **Models**: entities, managers, database logic
- **Views**: PHP/HTML pages
- **Controllers**: request handling and coordination between models and views

### OOP Requirements
The project must include:

- One **Entity class** per entity
- One **Manager / DAO class** per entity for CRUD operations
- One **Database** class using **PDO**
- One **Uploader** utility class

### Security
- Use **prepared statements** with PDO
- Use **sessions** properly
- Protect admin pages from unauthorized access
- Sanitize and validate form data

---

## Project Structure

```text
hotel-management/
│
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ClientController.php
│   │   ├── RoomController.php
│   │   └── ReservationController.php
│   │
│   ├── models/
│   │   ├── Database.php
│   │   ├── Uploader.php
│   │   │
│   │   ├── entities/
│   │   │   ├── User.php
│   │   │   ├── Client.php
│   │   │   ├── Room.php
│   │   │   └── Reservation.php
│   │   │
│   │   └── managers/
│   │       ├── UserManager.php
│   │       ├── ClientManager.php
│   │       ├── RoomManager.php
│   │       └── ReservationManager.php
│   │
│   └── views/
│       ├── layouts/
│       │   ├── header.php
│       │   └── footer.php
│       │
│       ├── auth/
│       │   └── login.php
│       │
│       ├── dashboard/
│       │   └── index.php
│       │
│       ├── clients/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       │
│       ├── rooms/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       │
│       └── reservations/
│           ├── index.php
│           ├── create.php
│           └── edit.php
│
├── config/
│   └── config.php
│
├── database/
│   ├── hotel_management.sql
│   └── seed.sql
│
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/
│       │   └── style.css
│       ├── js/
│       │   └── script.js
│       └── images/
│
├── uploads/
│   └── rooms/
│
├── README.md
└── .gitignore


Database Design

The database must contain at least these 4 related tables:

users

* id
* fullname
* email
* password
* created_at

clients

* id
* first_name
* last_name
* phone
* email
* created_at

rooms

* id
* room_number
* type
* price
* status
* image
* created_at

reservations

* id
* client_id
* room_id
* check_in
* check_out
* status
* created_at

Relationships

* One reservation belongs to one client
* One reservation belongs to one room
* One client can have many reservations
* One room can be linked to many reservations over time

⸻

Required Classes

Entities

* User
* Client
* Room
* Reservation

Each entity class must contain:

* private attributes
* constructor
* getters and setters

Managers

* UserManager
* ClientManager
* RoomManager
* ReservationManager

Each manager class must use PDO and implement the required CRUD logic.

Utility Classes

* Database for PDO connection using Singleton pattern
* Uploader for file uploads

⸻

Controllers

AuthController

Must handle:

* login
* logout

ClientController

Must handle:

* list clients
* create client
* edit client
* delete client
* search if needed

RoomController

Must handle:

* list rooms
* create room
* edit room
* delete room
* upload room image

ReservationController

Must handle:

* list reservations
* create reservation
* edit reservation
* delete reservation
* multi-criteria search

⸻

Views

The application must provide pages for:

* login
* dashboard
* clients list / create / edit
* rooms list / create / edit
* reservations list / create / edit

A simple admin layout with:

* header
* footer
* navigation menu

⸻

Routing

The entry point of the application must be:

* public/index.php

Routing can be based on query parameters such as:

* controller
* action

Example:

* index.php?controller=auth&action=login
* index.php?controller=client&action=index
* index.php?controller=room&action=create
* index.php?controller=reservation&action=index

⸻

Expected Behavior

Authentication Flow

* User opens login page
* User submits email and password
* Credentials are checked against database
* Session starts if authentication succeeds
* User is redirected to dashboard
* User can logout

Client Management

* Admin can create, view, edit and delete clients

Room Management

* Admin can create, view, edit and delete rooms
* Admin can upload a room image

Reservation Management

* Admin can create, view, edit and delete reservations
* Admin can search reservations using multiple filters

⸻

Coding Guidelines

The generated code must follow these rules:

* pure PHP, no framework
* beginner-friendly and readable code
* clear separation of responsibilities
* clean folder organization
* comments where useful
* use PDO prepared statements only
* avoid overly complex code
* keep naming consistent with this README

⸻

Generation Instructions for AI Code Assistant

The code generator must:

1. Read this README first
2. Respect the exact project structure
3. Generate all required folders and files
4. Implement the database schema in SQL
5. Implement all PHP classes
6. Implement all controllers
7. Implement all views
8. Implement authentication
9. Implement CRUD features
10. Implement multi-criteria search
11. Implement file upload
12. Keep code simple and maintainable

Important:

* Do not change the architecture
* Do not rename files unless absolutely necessary
* Generate a complete working academic project
* Keep the code consistent with MVC and OOP principles