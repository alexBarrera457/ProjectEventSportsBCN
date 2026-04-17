# EventSportsBCN - Sports Events Management System

A PHP-based web application for managing and organizing sports events in Barcelona. Users can browse events by sport type, register for events, and managers can create and manage events.

## 📋 Table of Contents

- [Project Overview](#project-overview)
- [Architecture](#architecture)
- [Database Structure](#database-structure)
- [User Roles](#user-roles)
- [Features](#features)

---

## 🎯 Project Overview

EventSportsBCN is a sports event management platform that allows:
- **Regular Users**: Browse sports events, register for events, manage their profile, and track signed/saved events
- **Managers**: Create and manage sports events, track registrations, and manage their organization profile

---

## 🏗️ Architecture

### Class Diagram

```mermaid
classDiagram
    class UserController {
        -conn: mysqli
        +__construct()
        +login()
        +logout()
        +register()
        +__destruct()
    }

    class Usuario {
        id: int
        nombre: varchar
        apellidos: varchar
        nombre_usuario: varchar
        email: varchar
        password_hash: varchar
        rol: enum(usuario, manager)
        entidad: varchar
        telefono: varchar
        foto_perfil: varchar
        created_at: timestamp
    }

    class Evento {
        id: int
        titulo: varchar
        descripcion: text
        deporte: varchar
        fecha: date
        hora: time
        ubicacion: varchar
        plazas_totales: int
        plazas_disponibles: int
        id_manager: int
        created_at: timestamp
    }

    class Inscripcion {
        id: int
        id_usuario: int
        id_evento: int
        fecha_inscripcion: timestamp
    }

    UserController --> Usuario: manages
    Usuario --> Evento: creates (manager)
    Usuario --> Inscripcion: registers
    Evento --> Inscripcion: has many
```

---

## 💾 Database Structure

### Entity Relationship Diagram

```mermaid
erDiagram
    USUARIOS ||--o{ EVENTOS : creates
    USUARIOS ||--o{ INSCRIPCIONES : makes
    EVENTOS ||--o{ INSCRIPCIONES : has

    USUARIOS {
        int id PK
        string nombre
        string apellidos
        string nombre_usuario UK
        string email UK
        string password_hash
        enum rol
        string entidad
        string telefono
        string foto_perfil
        timestamp created_at
    }

    EVENTOS {
        int id PK
        string titulo
        text descripcion
        string deporte
        date fecha
        time hora
        string ubicacion
        int plazas_totales
        int plazas_disponibles
        int id_manager FK
        timestamp created_at
    }

    INSCRIPCIONES {
        int id PK
        int id_usuario FK
        int id_evento FK
        timestamp fecha_inscripcion
    }
```

---

## 🔄 User Workflows

### Authentication & Registration Flow

```mermaid
sequenceDiagram
    participant User as User/Browser
    participant View as View Layer
    participant Controller as UserController
    participant DB as Database

    Note over User,DB: User Registration Process
    User->>View: Fill registration form
    View->>Controller: POST register request
    Controller->>Controller: Validate inputs
    alt Password mismatch
        Controller->>User: Redirect to signup with error
    else All valid
        Controller->>DB: INSERT new user
        DB-->>Controller: User created with ID
        Controller->>Controller: Set session data
        Controller->>User: Redirect to home/dashboard
    end

    Note over User,DB: User Login Process
    User->>View: Enter credentials
    View->>Controller: POST login request
    Controller->>Controller: Validate fields not empty
    Controller->>DB: SELECT user by username
    DB-->>Controller: User data
    alt User not found
        Controller->>User: Error: User doesn't exist
    else Password mismatch
        Controller->>User: Error: Wrong password
    else Credentials valid
        Controller->>Controller: Regenerate session ID
        Controller->>Controller: Store user session
        Controller->>User: Redirect to profile
    end

    Note over User,DB: User Logout Process
    User->>View: Click logout
    View->>Controller: POST logout request
    Controller->>Controller: Unset session data
    Controller->>Controller: Destroy session
    Controller->>User: Redirect to login
```

### Event Registration Flow

```mermaid
sequenceDiagram
    participant User as Authenticated User
    participant View as Event Page
    participant Controller as EventController
    participant DB as Database

    User->>View: Browse events by sport
    View->>Controller: GET event list
    Controller->>DB: SELECT eventos WHERE deporte = ?
    DB-->>Controller: Event list
    Controller-->>View: Display events

    User->>View: Click "Join Event"
    View->>Controller: POST register inscription
    alt User not logged in
        Controller-->>User: Redirect to login
    else Spots available
        Controller->>DB: INSERT inscription
        Controller->>DB: UPDATE plazas_disponibles
        DB-->>Controller: Success
        Controller-->>View: Show confirmation
    else No spots available
        Controller-->>View: Show error message
    end

    User->>View: View "My Events"
    View->>Controller: GET user inscriptions
    Controller->>DB: SELECT from INSCRIPCIONES WHERE id_usuario = ?
    Controller->>DB: JOIN with EVENTOS table
    DB-->>Controller: User's events
    Controller-->>View: Display registered events
```

---

## 👥 User Roles

### Regular User
- ✅ Browse sports events (Basketball, Football, Golf, Tennis, Paddle)
- ✅ Register/Sign up for events
- ✅ View profile and followed sports
- ✅ Manage saved events
- ✅ View signed events
- ✅ Update profile information

### Manager
- ✅ All features of regular users
- ✅ Create new events
- ✅ Edit events
- ✅ View event registrations
- ✅ Manage organization profile
- ✅ Upload profile image

---

## 🎮 Features

### Supported Sports
- 🏀 Basketball
- ⚽ Football
- ⛳ Golf
- 🎾 Tennis
- 🏸 Paddle

### Core Functionality

#### User Management
- User registration (regular users and managers)
- User authentication with session management
- Password validation
- Role-based access control

#### Event Management
- Create events (managers only)
- Edit events (managers only)
- Browse events by sport category
- View event details
- Event capacity management

#### Event Registration
- Register for events
- View registered events
- View saved events
- Track event capacity

#### Profile Management
- View/Edit user profile
- Upload profile image (managers)
- Manage organization information (managers)

---

## 📁 Project Structure

```
EventSportsBCN/
├── Controller/
│   └── userControler.php       # User authentication and registration
├── Model/
│   └── Model.sql               # Database schema
├── View/
│   ├── Assets/
│   │   └── ProfileImages/      # User profile images
│   ├── CSS/
│   │   ├── Global/
│   │   │   └── global.css
│   │   └── Styles/             # Page-specific styles
│   └── HTML/
│       └── Pages/              # All application pages
└── README.md
```

---

## 🔧 Technical Stack

- **Backend**: PHP 7.x+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Paradigm**: MVC (Model-View-Controller)

---

## 🚀 Getting Started

### Prerequisites
- XAMPP or similar PHP/MySQL environment
- PHP 7.0+
- MySQL 5.7+

### Installation

1. Clone/Download the project to your XAMPP htdocs folder:
   ```
   c:\xampp\htdocs\HTML\
   ```

2. Import the database schema:
   ```bash
   mysql -u root < Model/Model.sql
   ```

3. Start XAMPP services (Apache & MySQL)

4. Access the application:
   ```
   http://localhost/HTML/View/HTML/Pages/Login.php
   ```

---

## 📝 Database Configuration

Default configuration in `userControler.php`:
- **Host**: localhost
- **User**: root
- **Password**: (empty)
- **Database**: eventsportsbcn
- **Charset**: utf8mb4

Modify these values in the `UserController::__construct()` method if your setup differs.

---

## 🔐 Security Notes

- Passwords are stored as plain text in the current implementation. **Use PHP's `password_hash()` and `password_verify()` functions in production**
- Implement prepared statements for all database queries
- Add input validation on the server side
- Use HTTPS in production
- Implement CSRF protection tokens
- Add rate limiting for login attempts

---

## 📞 Support

For issues or questions, please refer to the code documentation within each PHP file.

---
**Created**: 2026  
**Last Updated**: April 2026
