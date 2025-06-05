# 🍳 The OverCooked System – SEF Project (Trimester 2410)

The OverCooked System is a web-based recipe platform developed as part of the **Software Engineering Fundamentals (SEF)** course at **Multimedia University (MMU)**. This system supports multiple user roles—Guest, Registered User, Chef, and Administrator—each with unique permissions and functionality, simulating a real-world software engineering solution with complete lifecycle documentation.

## 🌟 Key Features

- User authentication (login, registration)
- Role-based access (Guest, Registered User, Chef, Administrator)
- Recipe creation, saving, commenting, and browsing
- Admin moderation tools for users, recipes, comments, and backups
- UI designs implemented using HTML/CSS/JS with PHP and MySQL backend

## 🛠️ Tech Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL (via XAMPP)
- IDE: Visual Studio Code
- Server Environment: XAMPP

## 📂 Project Setup

1. Install [XAMPP](https://www.apachefriends.org/index.html).
2. Copy the project folder into `C:/xampp/htdocs/`.
3. Launch XAMPP and start **Apache** and **MySQL**.
4. Open your browser and go to `localhost/phpmyadmin`.
5. Create a database called `the_overcooked_db`.
6. Import the provided SQL file (`the_overcooked_db.sql`) from the project folder.
7. Run the system at `localhost/project/signin_uppage.html`.

## 👥 User Roles & Capabilities

| Role           | Capabilities |
|----------------|--------------|
| **Guest**      | View recipes, search recipes, register |
| **Registered** | Save recipes, comment, update profile |
| **Chef**       | Publish/edit/delete recipes, view/save/comment |
| **Admin**      | Moderate users, manage backups, publish/approve recipes |

## 🧱 System Architecture

- MVC-aligned modular subsystems
- Component-based breakdown of each role's interactions
- Deployment architecture supports local usage and testing

## 🧪 Testing & QA

- Unit-tested by each team member
- Integration followed incremental build strategy
- Acceptance testing verified by respective actors (Guest, Chef, Admin)

## 👨‍💻 Team Group 7 – Section TT2L

| Name                               | Role            |
|------------------------------------|-----------------|
| Wan Amirul Amir bin Wan Romzi      | Administrator   |
| Mohamad Syamel bin Mohamad Karid  | Guest           |
| Ammar Hakimi bin Adnan            | Registered User |
| Aleef Nufayl bin Mohd Zainal Badri | Chef            |

## 📄 Documentation

All diagrams (UML, ERD, Component, Activity), screen designs, and pseudocode are included in the full System Documentation PDF (not uploaded).

## 📜 License

This project is submitted for academic purposes and is not intended for commercial use.
