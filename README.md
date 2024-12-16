# Blockchain Attendance System (Prototype)

## Project Overview

This project is a prototype for the **Godamlah**. Blockchain-based attendance system. It provides a simple yet robust framework for managing staff and tracking their attendance using blockchain technology.

### Features

- **Staff Management**: Add, view, and manage staff members.
- **Attendance Tracking**: Securely mark attendance with blockchain validation.
- **Blockchain Validation**: Ensure the integrity of attendance records through blockchain technology.

### Technologies Used

- PHP
- MySQL
- Blockchain (SHA-256)
- DataTables for tabular data representation

### Project Structure

- `db.php`: Database connection script
- `blockchain.php`: Blockchain-related functions for hashing and validation
- `staff.php`: Handles staff management
- `attendance.php`: Handles attendance marking with blockchain validation

### Setup

1. **Download and Setup**:
   - Download the project files.
   - Move the project folder to the `htdocs` directory of your XAMPP installation (e.g., `C:\xampp\htdocs\Blockchain_Attendance_System`).

2. **Database Setup**:
   - Open phpMyAdmin and create a new database (e.g., `blockchain_db`).
   - Import the `schema.sql` file located in the project directory to set up the necessary tables.

3. **Configuration**:
   - Open `db.php` and update the database connection details (host, username, password) as per your setup.

4. **Run the Project**:
   - Start your XAMPP server.
   - Navigate to `http://localhost/Blockchain_Attendance_System` in your web browser.

