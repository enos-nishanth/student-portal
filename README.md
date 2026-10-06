# Student Portal

A Laravel-based Student Portal and E-commerce application designed to provide separate experiences for students and administrators.

The platform allows students to manage their profiles, browse products, add products to a cart, manage delivery addresses, and eventually complete purchases through an online payment system. Administrators can manage students and products through a dedicated admin interface.

---

## 🚀 Project Overview

The Student Portal is being developed using Laravel and Blade with a focus on clean architecture, role-based access, and a simple user experience.

The application currently includes:

- Student authentication and registration
- Admin authentication
- Student profile management
- Admin dashboard
- Student management
- Product management
- Product browsing
- Product details
- Shopping cart
- Add to Cart and Buy Now flows
- Delivery address management
- Google Maps integration planning
- REST APIs using Laravel Sanctum

Future versions will include checkout, order management, and Razorpay payment integration.

---

## 🛠️ Tech Stack

| Technology | Version / Usage |
|------------|-----------------|
| Laravel | 12.69.2 |
| PHP | 8.2.12 |
| Database | MySQL |
| Frontend | Blade |
| UI Framework | Bootstrap 5 |
| Authentication | Laravel Sanctum |
| Build Tool | Vite |
| Payment Gateway | Razorpay (Planned) |
| Maps | Google Maps (Planned) |
| Version Control | Git & GitHub |

---

## ✨ Features

### 👨‍🎓 Student Features

- Student registration
- Student login
- Profile management
- Browse available products
- View product details
- Add products to cart
- Update cart quantity
- Remove products from cart
- Buy Now functionality
- Manage delivery addresses
- Select delivery location using map coordinates

### 👨‍💼 Admin Features

- Admin authentication
- Admin dashboard
- View students
- View student details
- Edit student information
- Product management
- Create products
- Edit products
- View products
- Manage product status

### 🛒 Shopping Cart

The cart system supports:

- Adding products to cart
- Increasing/decreasing quantity
- Removing products
- Calculating item totals
- Calculating cart total

Product prices are retrieved from the `products` table rather than being duplicated inside `cart_items`.

### ⚡ Add to Cart vs Buy Now

The application provides two separate purchasing flows.

**Add to Cart**

```text
Product
   ↓
Cart
   ↓
Checkout