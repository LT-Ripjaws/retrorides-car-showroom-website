<h1 align="center"> 🚘 RetroRides - Vintage Automobile Management System </h1>

<p align="center">
   <img src="https://img.shields.io/badge/Contributors-1-brightgreen" alt="Contributors">
   <img src="https://img.shields.io/badge/Technologies-HTML%20%7C%20CSS%20%7C%20PHP | JavaScript-blue" alt="Technologies">
   <img src="https://img.shields.io/badge/Status-Ongoing-success" alt="Status">
</p>

<p align="center">
  <img src="screenshots/header.jpg" alt="Intro" width="900" />
</p>

## 📖  Overview  
A comprehensive **web application** for managing vintage car collections, sales, and restoration services while offering customers a seamless way to explore, book, and connect.  
The Academic project is built with **PHP, MySQL, HTML, CSS, and JavaScript** using a custom **PHP MVC architecture** that's similar to Laravel.
Overall, it was a great learning experience.

---

## 🚗 Features

### 🌐 Public Features
-  **Landing Page** – Showcase featured classic cars and services.  
-  **Car Collection Browser** – Browse available vintage automobiles.  
-  **About Us** – Learn about the dealership’s history and team.  
-  **Contact Page** – Get in touch with the team.  

### 👥 User Management
-  **User Registration & Authentication**  
-  **Role-Based Access**: Customer, Sales, Admin  
-  **“Remember Me” Sessions** – Secure token-based authentication  
-  **Profile Management**  

### 🛡️ Admin Panel
-  **Dashboard** – Analytics & stats  
-  **User Management** – Activate/deactivate, roles  
-  **Team Management** – Full CRUD for employees  
-  **Car Inventory** – Full CRUD for cars  
-  **Booking Management**  
-  **Sales Tracking**  

### 🧑‍💼 Salesman
-  **TO-DO**  

### 🧑 Customer
-  **TO-DO**  

### 🔒 Security Features
-  **CSRF Protection**  
-  **Password hashing (bcrypt)**  
-  **Secure sessions** (HttpOnly, SameSite cookies)  
-  **Input validation & sanitization**  
-  **SQL injection prevention** with prepared statements  
-  **Role-based authorization**  

---


## ✨ Demo & Screenshots

### 🌐 Public Pages
| Landing Page |
|--------------|
![Landing](screenshots/landing-page.gif) 

| Car Collection | About Us |
|--------------|----------------|
| ![Cars](screenshots/collections.gif) | ![About](screenshots/about.gif) |

| Contact Us | Authentication |
|------------|----------------|
| ![Contact](screenshots/contact.gif) | ![Authentication](screenshots/authentication.gif) |

### 🛡️ Admin Panel
| Admin Pages |
|--------------|
![Dashboard](screenshots/admin-panel.gif)



## 🛠️ Technology Stack
-  **Backend:** PHP  
-  **Database:** MySQL  
-  **Frontend:** HTML5, CSS3, Vanilla JS  
-  **Architecture:** Custom MVC + Dependency Injection  
-  **Security:** CSRF tokens, bcrypt, prepared statements  

---

## 📋 Requirements
-  PHP  
-  MySQL  
-  Apache/Nginx with `mod_rewrite` enabled  
-  Composer  
-  XAMPP 

## 🚀 Installation

```
# 1. Clone repo:
https://github.com/LT-Ripjaws/retrorides-car-showroom-website.git
cd retrorides

# 2. Install dependencies:
composer install

# 3. Configure database:
# Import retrorides_db in your xampp's MySQL server. (will be provided later)

# 4. Make sure xampp is configured:
#   In xammp/apache/conf/httpd.conf make sure <Directory "C:/xampp/htdocs"> has
#   AllowOverride All
#   Require all granted
```
---

### 🗄️ Database Schema
- 👥 **users** – Customer accounts  
- 🧑‍🤝‍🧑 **employees** – Staff members  
- 🚗 **cars** – Car inventory  
- 📅 **bookings** – Reservations  
- ❓ **inquiries** – Customer's inquiries  
- 🏷️ **offers** – Car offers and discounts  
- 🔑 **remember_me** – Persistent login tokens

### 🔮 Future Enhancements:
-  **ML-based price predictions**  
-  **Predictive analytics dashboard**  
-  **Salesman Module**  
-  **Customer Module**  

### 📝 License
This project is licensed under the MIT License

### 📊 Project Status
🚧 Active development – Version 1.0.0

## 👤 Author
*Chinmoy Guha*  

- GitHub: [@LT-Ripjaws](https://github.com/LT-Ripjaws)  
- Email: chinmoyguha676z@gmail.com
<p align="center">
  <img src="https://user-images.githubusercontent.com/74038190/242390524-0c7eb6ed-663b-4ce4-bfbd-18239a38ba1b.gif" alt="Profile Banner" width="70%" height = "50%" />
</p>
<p align="center"> <img src="https://readme-typing-svg.herokuapp.com?font=Fira+Code&size=22&duration=3000&pause=1000&color=FF6B6B&center=true&vCenter=true&width=700&lines=Thanks+for+visiting!+👾;Drop+a+star+⭐+if+you+like+it;Let's+build+something+awesome+together!+🚀"> </p>
<p align="center">
<img src="https://octodex.github.com/images/daftpunktocat-thomas.gif" width="30%">
</p>
