# SulamProject

SulamProject is a comprehensive **Community Management System** designed for **Masjid Desa Ilmu**. It replaces legacy Excel sheets and manual processes with a secure, role-based web application to efficiently manage community data and mosque operations.

## 🚀 Overview

The system is built to ensure no needy resident is overlooked and provides transparency in mosque management. It covers key functional areas including resident registry, financial aid (Zakat), donations, funeral management, and event scheduling.

### Key Features

*   **👥 Resident Management**: Digital registry of community members and household details.
*   **💰 Financial & Zakat**: Complete workflow for Zakat applications, assessments, and disbursements.
*   **🎁 Donations**: Tracking donors, issuing receipts, and managing funds.
*   **⚰️ Death & Funeral (Khairat Kematian)**: Managing death notifications and funeral assistance.
*   **📅 Events**: Scheduling and announcing mosque events.
*   **🛡️ Security**: Role-based access control, audit logs, and secure data handling.

## 🛠️ Technical Stack

The project utilizes a simplified, robust stack tailored for easy deployment and maintenance:

*   **Backend**: Vanilla PHP (No heavy frameworks)
*   **Database**: MySQL / MariaDB
*   **Frontend**: HTML5, CSS3, Vanilla JavaScript
*   **Server Environment**: Apache (Developed using Laragon on Windows)

## 📂 Project Architecture

This project follows a **Feature-Based Architecture**. Code is organized by business domain/features rather than technical layers (MVC folders).

```text
sulamproject/
├── features/                  # CORE LOGIC (Residents, Financial, etc.)
│   ├── [feature-name]/
│   │   ├── shared/            # Shared logic (Models, Libraries)
│   │   ├── admin/             # Admin controllers & views
│   │   └── user/              # User controllers & views
├── database/                  # Migrations & Seeds
├── public/                    # (Root) Entry points & Assets
├── context-docs/              # Detailed internal documentation
└── db.php                     # Database connection & auto-provisioning
```

## ⚙️ Installation & Setup

1.  **Prerequisites**:
    *   Install **Laragon** (recommended for Windows) or any LAMP/WAMP stack.
    *   Ensure PHP 7.4+ and MySQL 5.7+ are running.

2.  **Clone the Repository**:
    ```bash
    git clone https://github.com/your-repo/sulamproject.git
    cd sulamproject
    ```

3.  **Database Setup (Manual)**:
    Since automated provisioning is disabled for live production safety:
    1.  **Create Database**: Create a database named `masjidkamek` (or your preferred name) in your MySQL server (e.g., via phpMyAdmin).
    2.  **Import Schema**: Import the file `database/manual_install.sql` into that database. This file contains the base schema and all necessary migrations.

4.  **Configuration**:
    *   Edit the connection file: `features/shared/lib/database/mysqli-db.php`.
    *   Update the `$DB_HOST`, `$DB_USER`, `$DB_PASS`, and `$DB_NAME` variables to match your environment.
    *   *Alternatively, you can set these as environment variables.*

5.  **Access**:
    *   Point your web server (Apache/Nginx) to the project root.
    *   Visit: `http://localhost/sulamproject` (or your domain).

## 📚 Documentation

For more detailed technical documentation, architectural decisions, and feature specs, please refer to the `context-docs/` directory:

*   [Architecture Overview](context-docs/Architecture.md)
*   [Feature Based Structure](context-docs/Feature-Based-Structure.md)
*   [Requirements](context-docs/Requirements_Summary.md)
