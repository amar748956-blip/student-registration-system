# Project Explanation Document

## Student Registration Management System

### Overview
This project is a Student Registration Management System that lets an
administrator register students, view them in a searchable table, edit their
details, and delete records. It is built using PHP (Object-Oriented), MySQL,
HTML, CSS, and JavaScript.

---

### How the Requirements Are Met

#### HTML
- All pages use **semantic elements** (`<header>`, `<main>`, `<footer>`,
  `<section>`, `<nav>`, `<table>`).
- Every form field has an associated `<label>`.
- The form is logically structured with a responsive grid; the student list
  uses a proper `<table>` with `<thead>` / `<tbody>`.

#### CSS (`assets/css/style.css`)
- Forms, tables, and buttons are fully styled with consistent spacing.
- **Hover effects** on buttons, table rows, and nav links.
- **Responsive layout** — the form collapses from two columns to one on small
  screens via media queries.

#### JavaScript (`assets/js/validation.js`)
Validates before submission and shows inline messages:
- Full Name not empty → "Please enter your full name"
- Valid email format → "Please enter a valid email address"
- Phone exactly 10 digits → "Phone number must contain 10 digits"
- Country must be selected → "Please select your country"
Errors clear automatically as the user fixes each field.

#### PHP (OOP)
The backend is organized into single-responsibility classes:

| Class          | Responsibility                                            |
|----------------|-----------------------------------------------------------|
| `Database`     | PDO connection using the **Singleton** pattern.           |
| `Student`      | All CRUD + search queries (prepared statements).          |
| `Validator`    | Server-side validation (defense in depth).                |
| `FileUploader` | Secure profile-image upload handling.                     |

Key practices:
- **Data received via POST** and processed in a single `process.php`
  controller that handles **both Create and Update** — no duplicated logic.
- **Server-side validation** runs even if JavaScript is bypassed.
- **Secure file upload**: real MIME type check (whitelist), 2 MB size limit,
  randomized filenames, and a `.htaccess` rule that blocks script execution
  inside `uploads/`.
- **SQL-injection safe** through PDO prepared statements everywhere.
- **XSS-safe output** via a central `e()` escaping helper.
- **Email uniqueness** is enforced both in the DB schema and in code.

#### Database (`database.sql`)
- A single `students` table with proper data types, a unique email constraint,
  an `ENUM` for gender, and automatic `created_at` / `updated_at` timestamps.
- Includes sample rows for quick testing.

---

### CRUD Flow
1. **Create** — `index.php` → `process.php` → insert → redirect to list.
2. **Read** — `list.php` shows all students; the search bar filters by name,
   email, phone, or country.
3. **Update** — `edit.php?id=N` → `process.php` → update.
4. **Delete** — `delete.php?id=N` removes the record and its image file
   (with a JavaScript confirmation prompt).

---

### Why This Is Clean Code
- No duplicated form markup (shared `includes/form.php`).
- No duplicated create/update logic (shared `process.php`).
- Shared header/footer partials.
- Clear separation of concerns: config, classes (logic), includes (view/helpers),
  and page controllers.
- Consistent naming, type declarations (`declare(strict_types=1)`), and
  documented methods.
