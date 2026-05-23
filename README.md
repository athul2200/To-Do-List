# ✅ Modern To-Do List

A sleek, full-stack task management web application built with **PHP**, **MySQL**, and a modern CSS UI. Features secure user authentication and complete CRUD operations for personal task management.

---

## ✨ Features

- **User Authentication** — Secure registration & login with password hashing
- **Add Tasks** — Quickly create new tasks from the dashboard
- **Edit Tasks** — Modify existing task descriptions
- **Delete Tasks** — Remove tasks you no longer need
- **Toggle Completion** — Mark tasks as complete/incomplete with a single click
- **Per-User Data** — Each user sees only their own tasks
- **Modern UI** — Clean design with gradients, glassmorphism, and the Inter font
- **Responsive Layout** — Works across desktop and mobile devices

---

## 🛠 Tech Stack

| Layer      | Technology             |
|------------|------------------------|
| Backend    | PHP 7.4+ (PDO)        |
| Database   | MySQL                  |
| Frontend   | HTML5, CSS3            |
| Server     | Apache (XAMPP / WAMP)  |
| Font       | Google Fonts — Inter   |

---

## 📁 Project Structure

```
ToDoList/
├── index.php            # Main dashboard — view & add tasks
├── login.php            # User login page
├── register.php         # User registration page
├── edit.php             # Edit an existing task
├── delete.php           # Handle task deletion
├── toggle_status.php    # Toggle task completion status
├── logout.php           # Destroy session & log out
├── db.php               # Database connection (PDO)
├── migrate.php          # Database migration helper
├── database.sql         # SQL schema (users & tasks tables)
├── style.css            # Application stylesheet
├── favicon.svg          # App favicon
├── .env                 # Environment config (DB credentials)
└── .gitignore           # Git ignore rules
```

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or WAMP / MAMP)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Installation

1. **Clone the repository** into your web server's document root:
   ```bash
   git clone https://github.com/athul2200/To-Do-List.git
   cd ToDoList
   ```
   Or copy the project folder to `C:\xampp\htdocs\ToDoList\`.

2. **Create the database:**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Create a new database named `todo_db`
   - Import `database.sql` or run the SQL manually:
     ```sql
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         username VARCHAR(50) UNIQUE NOT NULL,
         password VARCHAR(255) NOT NULL
     );

     CREATE TABLE tasks (
         id INT AUTO_INCREMENT PRIMARY KEY,
         user_id INT NOT NULL,
         task VARCHAR(255) NOT NULL,
         is_completed TINYINT(1) DEFAULT 0,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
     );
     ```

3. **Configure environment variables:**
   Update the `.env` file with your database credentials:
   ```ini
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=todo_db
   ```

4. **Start the server:**
   - Launch XAMPP and start **Apache** and **MySQL**
   - Navigate to `http://localhost/ToDoList/`

5. **Create an account** — Click "Sign up", register, and start managing your tasks!

---

## 🔒 Security

- Passwords are hashed using `password_hash()` and verified with `password_verify()`
- All database queries use **PDO prepared statements** to prevent SQL injection
- User sessions are managed with PHP's built-in session handling
- Task operations verify **user ownership** before executing

---

## 📄 License

This project is open-source and available for personal and educational use.
