# 🛒 PHP E-Commerce Web Application

A fully functional E-Commerce web application built using **PHP (PDO), MySQL, Bootstrap, and Stripe Integration**.

This project includes complete user-side and admin-side functionality with secure authentication, cart management, order processing, reviews, and payment integration.

---

## 🔥 Features

### 👤 User Side
- User Registration & Login (Password Hashing)
- Product Listing with Pagination
- Category & Subcategory Filtering
- Product Detail Page
- Add to Cart (Database-based cart)
- Quantity Update / Remove from Cart
- Cash on Delivery Checkout
- Stripe Payment Integration
- Order Confirmation
- Star Rating & Review System
- Related Products Section

---

### 🛠 Admin Panel
- Admin Authentication
- Dashboard Overview
- Manage Categories (CRUD)
- Manage Subcategories (CRUD)
- Manage Products (CRUD with Pagination)
- Manage Users (Block / View)
- Manage Orders (Update Status)
- Order Details View
- Manage Reviews (Delete / View)

---

## 🧠 Tech Stack

- PHP (Core PHP with PDO)
- MySQL
- Bootstrap 5
- Stripe Payment Gateway
- HTML5 / CSS3
- Git & GitHub

---

## 🔐 Security Features

- PDO Prepared Statements (SQL Injection Protection)
- Password Hashing using `password_hash()`
- Session-based Authentication
- Secret keys excluded using `.gitignore`

---

## 📁 Project Structure


---

## ⚙ Installation Guide (Local Setup)

1. Clone the repository
2. Place project inside:



C:\xampp\htdocs\
3. Import database in phpMyAdmin
4. Configure database in:

config\db.php
5. Start Apache & MySQL from XAMPP
6. Open in browser:

http://localhost/ecommerce/

---

## 💳 Payment Integration

Stripe test mode is implemented.

To use Stripe:
- Add your test API keys in `config/stripe.php`
- Use Stripe test card numbers

---

## 📌 Future Enhancements

- Order email notifications
- Review approval system
- Admin analytics dashboard
- Product search functionality
- Deployment to live server

---

## 👩‍💻 Developed By

Anjana Modi  
MSc Computer Science  
Full Stack PHP Developer (Learning & Building Real Projects)

---

## ⭐ If You Like This Project

Give it a ⭐ on GitHub!
