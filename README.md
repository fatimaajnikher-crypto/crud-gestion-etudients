# 📚 Student Management System - CRUD Application

A complete, clean, and beginner-friendly CRUD (Create, Read, Update, Delete) web application for managing student information. Built with PHP, MySQL, HTML, and CSS.

---

## ✨ Features

- ✅ **List Students** - View all registered students in a beautiful table
- ✅ **Add Student** - Register new students with validation
- ✅ **Edit Student** - Update student information
- ✅ **Delete Student** - Remove students with confirmation
- ✅ **Responsive Design** - Works on desktop and mobile devices
- ✅ **Clean Modern UI** - Portfolio-ready interface
- ✅ **Secure Queries** - Protected against SQL injection with prepared statements
- ✅ **Input Validation** - Both client-side and server-side validation
- ✅ **Email Uniqueness** - Prevents duplicate email registrations

---

## 🛠️ Technologies Used

- **Backend**: PHP (Procedural)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3
- **Server**: Apache (via XAMPP)

---

## 📋 Database Structure

### Table: `etudiants`

```sql
- id              INT (Primary Key, Auto Increment)
- nom             VARCHAR(100) - Student last name
- prenom          VARCHAR(100) - Student first name
- email           VARCHAR(100) - Student email (UNIQUE)
- filiere         VARCHAR(100) - Field of study
- date_inscription TIMESTAMP - Registration date/time
```

### Available Fields of Study:
- Computer Science
- Business
- Engineering
- Medicine
- Law
- Arts
- Other

---

## 🚀 Installation & Setup

### Prerequisites:
- XAMPP (or any Apache + MySQL setup)
- PHP 7.0 or higher
- MySQL 5.6 or higher

### Step-by-Step Installation:

#### 1. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL**

#### 2. **Create Database**
   
   **Option A: Using phpMyAdmin (Recommended for beginners)**
   
   - Open browser and go to `http://localhost/phpmyadmin`
   - Click "New" or "Create database"
   - Enter database name: `gestion_etudiants`
   - Click "Create"
   - Click on the new database
   - Go to "Import" tab
   - Choose the `database-setup.sql` file from the project folder
   - Click "Import"
   
   **Option B: Using MySQL Command Line**
   
   ```bash
   mysql -u root -p < database-setup.sql
   ```
   
   **Option C: Manual SQL Execution**
   
   - Copy all content from `database-setup.sql`
   - Paste into phpMyAdmin "SQL" tab
   - Click "Go"

#### 3. **Project Folder Setup**
   
   The project is already in the correct location:
   ```
   c:\xampp\htdocs\crud gestion etud\
   ```

#### 4. **Verify Database Connection**
   
   Edit `db.php` if needed:
   ```php
   $db_host = 'localhost';      // Usually localhost
   $db_user = 'root';           // Default XAMPP user
   $db_password = '';           // Empty by default
   $db_name = 'gestion_etudiants'; // Database name
   ```

#### 5. **Access the Application**
   
   Open your browser and go to:
   ```
   http://localhost/crud%20gestion%20etud/
   ```
   or
   ```
   http://localhost/crud gestion etud/
   ```

---

## 📁 Project File Structure

```
crud gestion etud/
├── index.php              # Main dashboard - list all students
├── add.php                # Add new student form
├── edit.php               # Edit student form
├── delete.php             # Delete confirmation page
├── db.php                 # Database connection
├── style.css              # Styling (modern gradient design)
├── database-setup.sql     # Database schema and sample data
└── README.md              # This file
```

---

## 📖 How to Use

### 1. **View All Students**
   - Open `http://localhost/crud gestion etud/`
   - See all registered students in a table
   - Student count is displayed at the top

### 2. **Add a New Student**
   - Click "➕ Add New Student" button
   - Fill in the form:
     - First Name *
     - Last Name *
     - Email *
     - Field of Study *
   - Click "✓ Add Student"
   - Success message will appear

### 3. **Edit Student Information**
   - Click "✏️ Edit" button next to any student
   - Modify the information
   - Click "✓ Save Changes"
   - Updated successfully!

### 4. **Delete a Student**
   - Click "🗑️ Delete" button next to any student
   - Review student details
   - Click "🗑️ Delete Permanently" to confirm or "← Cancel" to go back
   - Deletion confirmed

---

## 🔒 Security Features

### Implemented Security Measures:

1. **Prepared Statements**
   - All database queries use prepared statements
   - Protection against SQL injection attacks

2. **Input Validation**
   - Server-side validation on all forms
   - Email format validation
   - Empty field validation
   - Duplicate email prevention

3. **HTML Escaping**
   - `htmlspecialchars()` prevents XSS attacks
   - Safe output of user-submitted data

4. **Database Indexing**
   - Faster searches and lookups
   - Improved performance

---

## 🎨 UI/UX Features

- **Modern Gradient Design** - Professional purple gradient background
- **Responsive Layout** - Adapts to mobile and tablet screens
- **Clean Tables** - Easy-to-read student information
- **Clear Navigation** - Intuitive buttons and links
- **Success Messages** - User feedback on actions
- **Error Handling** - Clear error messages for validation
- **Empty States** - Helpful message when no students exist
- **Hover Effects** - Interactive buttons with animations

---

## 🔧 Customization

### Change Database Credentials
Edit `db.php`:
```php
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';
```

### Modify Fields of Study
Edit `add.php` and `edit.php`, find the `<select>` element and add/remove options:
```php
<option value="Your Field">Your Field</option>
```

### Change Color Scheme
Edit `style.css`:
```css
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Add More Database Fields
1. Modify `database-setup.sql` to add columns
2. Update forms in `add.php` and `edit.php`
3. Update table queries in `index.php`

---

## 🐛 Troubleshooting

### Issue: "Connection failed" error

**Solution:**
- Make sure MySQL is running in XAMPP
- Check database credentials in `db.php`
- Verify database name is `gestion_etudiants`

### Issue: Database doesn't exist

**Solution:**
- Create database using phpMyAdmin
- Import `database-setup.sql`
- OR run MySQL commands manually

### Issue: Table not found error

**Solution:**
- The `etudiants` table wasn't created
- Re-import the `database-setup.sql` file
- Check phpMyAdmin for the table

### Issue: "Email already registered" when adding new student

**Solution:**
- This is by design - prevents duplicate emails
- Use a different email address
- Or modify the existing student

### Issue: Forms don't submit

**Solution:**
- All fields marked with * are required
- Email must be valid format (example@email.com)
- Fill in all required fields and try again

### Issue: Can't see images or styling

**Solution:**
- Make sure `style.css` is in the same folder
- Clear browser cache (Ctrl + Shift + Delete)
- Check file permissions

---

## 💡 Learning Points

This project demonstrates:

- ✅ PHP procedural programming
- ✅ MySQL database operations (CRUD)
- ✅ HTML form handling with POST/GET
- ✅ Prepared statements for security
- ✅ Input validation and error handling
- ✅ Session/message passing with GET parameters
- ✅ Responsive CSS design
- ✅ PHP array handling
- ✅ String functions (htmlspecialchars, trim, etc.)
- ✅ Date/time formatting

---

## 📝 Sample Data

The database comes with 5 sample students:

1. **Ahmed Boucher** - Computer Science
2. **Sophie Martin** - Business
3. **Jean Durand** - Engineering
4. **Marie Dubois** - Medicine
5. **Pierre Moreau** - Law

Feel free to modify or delete these records!

---

## 🎯 Improvements & Future Enhancements

Potential improvements for the future:

- [ ] User authentication/login system
- [ ] Search and filter functionality
- [ ] Export to CSV/PDF
- [ ] Pagination for large datasets
- [ ] Student profile page with detailed info
- [ ] File upload (profile photo)
- [ ] Advanced filtering and sorting
- [ ] Dashboard with statistics
- [ ] Email notifications
- [ ] Admin dashboard

---

## 📄 License

This project is free to use and modify for personal and educational purposes.

---

## 👨‍💻 Code Quality

- **Well-commented** - Every section has explanatory comments
- **Beginner-friendly** - Easy to understand and modify
- **Best practices** - Follows PHP and web development standards
- **Portfolio-ready** - Professional code structure and design

---

## 🤝 Support

If you encounter any issues:

1. Check the **Troubleshooting** section above
2. Verify all files are in `c:\xampp\htdocs\crud gestion etud\`
3. Make sure Apache and MySQL are running
4. Check browser console for JavaScript errors
5. Review phpMyAdmin for database issues

---

## ⭐ Credits

Created as a complete, production-ready CRUD application for educational and portfolio purposes.

**Happy coding! 🚀**
