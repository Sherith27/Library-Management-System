# MakeWay Library Management System

## Overview

The MakeWay Library Management System is a web-based application designed to simplify library operations by allowing users to browse, search, reserve, and manage books online. The system provides an efficient platform for both library members and administrators to manage library resources and borrowing activities.

The application was developed using PHP, MySQL, HTML, CSS, and JavaScript, providing a user-friendly interface and secure session-based authentication.

---

## Objectives

- Provide a digital platform for managing library resources.
- Allow users to search and browse available books.
- Enable book reservation and borrowing functionality.
- Track borrowed books and their collection details.
- Improve the efficiency of library management processes.

---

## Features

### User Authentication
- User registration.
- Secure login system.
- Session-based access control.
- Logout functionality.

### Book Management
- Display all available books.
- Categorize books by genre or category.
- Search books by:
  - Title
  - Author
  - ISBN
- Filter books by:
  - Free Books
  - Rental Books

### Book Borrowing
- Reserve available books.
- Select collection date.
- Select collection time.
- Track borrowing status.

### Borrowed Books
- View personal borrowed books.
- Monitor reservation details.
- Track collection dates and times.

### User Interface
- Responsive design.
- Modern and intuitive layout.
- Dynamic search functionality.
- Category and type filtering.

---

## Technologies Used

### Front-End
- HTML5
- CSS3
- JavaScript

### Back-End
- PHP

### Database
- MySQL

### Development Tools
- XAMPP
- phpMyAdmin
- Visual Studio Code

---

## System Architecture

### Front-End Components
- library.html
- loginpage.html
- books.php
- my_borrowed.php

### Back-End Components
- db_connect.php
- borrow.php
- search_books.php
- logout.php

### Database
The system stores information in MySQL tables including:

- Users
- Books
- Borrowed Books
- Reservations

---

## Data Structures Used

### MySQL Tables
Data is organized using relational database tables:

- Books Table
- Users Table
- Borrow Records Table

### PHP Associative Arrays
Database records retrieved using `fetch_assoc()` are stored as associative arrays for processing and display.

### Collections and Lists
Book categories and search results are managed as collections of records retrieved from the database.

---

## Algorithms Implemented

### Searching
Users can search for books using:
- Book Title
- Author Name
- ISBN Number

### Filtering
Books can be filtered by:
- Category
- Book Type (Free or Rent)

### Sorting
Records are sorted using SQL queries with `ORDER BY` statements.

---

## Security Features

- Session-based authentication.
- Restricted page access.
- User validation.
- Secure logout process.
- Prevention of unauthorized access to protected pages.

---

## Installation

### Requirements

- XAMPP
- PHP 8+
- MySQL
- Web Browser

### Setup Instructions

1. Install XAMPP.
2. Place the project folder inside the `htdocs` directory.
3. Start Apache and MySQL.
4. Open phpMyAdmin.
5. Create the project database.
6. Import the provided SQL file.
7. Update database credentials in `db_connect.php`.
8. Open:
   
   http://localhost/MakeWayLibrary/library.html

9. Register or log in to access the system.

---

## Future Improvements

- Administrator dashboard.
- Fine management.
- Email notifications.
- Online payment integration for rental books.
- QR code-based book checkout.
- Report generation and analytics.
- Book recommendation system.

---

## Conclusion

The MakeWay Library Management System provides a practical solution for managing library operations digitally. The system enhances accessibility, improves record management, reduces manual processes, and offers users a convenient way to search, reserve, and manage library books online.


