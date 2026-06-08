# Student Registration Management System

A basic **Student Registration Management System** built with **PHP (OOP), MySQL,
HTML5, CSS3, and JavaScript**. It supports full CRUD operations, secure image
uploads, client- and server-side validation, and a searchable student list.

---

## 1. Tech Stack

| Layer      | Technology                        |
|------------|-----------------------------------|
| Frontend   | HTML5, CSS3, Vanilla JavaScript   |
| Backend    | PHP 8+ (OOP, PDO)                 |
| Database   | MySQL                             |

---

## 2. Folder Structure

```
student-registration/
├── assets/
│   ├── css/style.css         # All styling (responsive, hover effects)
│   └── js/validation.js      # Client-side validation
├── classes/
│   ├── Student.php           # Model: CRUD + search (PDO prepared statements)
│   ├── Validator.php         # Server-side validation logic
│   └── FileUploader.php      # Secure image upload handling
├── config/
│   └── Database.php          # PDO connection (Singleton pattern)
├── includes/
│   ├── functions.php         # Bootstrap + helpers (escape, flash, redirect)
│   ├── header.php            # Shared page header
│   ├── footer.php            # Shared page footer
│   └── form.php              # Reusable form partial (Create + Edit)
├── uploads/                  # Uploaded profile images (scripts blocked)
├── index.php                 # Registration form (Create)
├── edit.php                  # Edit form (Update)
├── list.php                  # Student list + search (Read)
├── delete.php                # Delete a record (Delete)
├── process.php               # Single processor for Create & Update
├── database.sql              # MySQL schema + sample data
└── docs/EXPLANATION.md       # Short explanation document
```

---

## 3. Setup Instructions

1. **Install a PHP + MySQL environment** (e.g. XAMPP, WAMP, MAMP, or LAMP).
2. **Copy the project** into your web root:
   - XAMPP → `htdocs/student-registration`
3. **Import the database**:
   - Open **phpMyAdmin** → Import → select `database.sql`, **or** run:
     ```bash
     mysql -u root -p < database.sql
     ```
4. **Configure DB credentials** (if different) in `config/Database.php`:
   ```php
   private string $host     = 'localhost';
   private string $dbName   = 'student_registration';
   private string $username = 'root';
   private string $password = '';
   ```
5. **Set permissions** on the `uploads/` folder so PHP can write to it.
6. **Run** the app:
   - Visit `http://localhost/student-registration/index.php`

---

## 4. Features

- **Semantic HTML** with labels for every field.
- **Responsive CSS** with hover effects and clean alignment.
- **JavaScript validation** (name, email format, 10-digit phone, country) with
  inline error messages.
- **PHP / OOP** architecture: `Database`, `Student`, `Validator`, `FileUploader`.
- **Secure image upload**: MIME whitelist, size limit, randomized filenames,
  script execution blocked in `uploads/`.
- **PDO prepared statements** → SQL-injection safe.
- **Full CRUD** on student records.
- **Search filter** by name, email, phone, or country.

---

## 5. Validation Rules

| Field    | Rule                                  |
|----------|---------------------------------------|
| Name     | Required                              |
| Email    | Required + valid email + unique       |
| Phone    | Required + exactly 10 digits          |
| Gender   | Required                              |
| DOB      | Required                              |
| Country  | Required (must be selected)           |
