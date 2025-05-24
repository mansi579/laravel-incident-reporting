# 🔐 Secure Incident Reporting System

A highly secure and scalable Incident Reporting System built with **Laravel**, **MySQL**, **Bootstrap**, and **Spatie Permissions**, featuring real-time notifications, analytics dashboards, and role-based access control.

---

## 🚀 Features

### 🔒 Authentication & Roles (via Spatie)
- **Users**: Submit and view their own incidents.
- **Admins**: Manage all incidents with filters, analytics, and bulk actions.
- **Super Admins**: Manage users and permissions, delete incidents permanently.

### 📝 Incident Management
- Submit incidents with title, description, category, priority, date, and evidence upload.
- Filter and sort by date, category, or status.
- Status updates (Open, In Progress, Resolved).

### 📊 Dashboards
- **User Dashboard**: View & filter personal incidents.
- **Admin Dashboard**:
  - Total Incidents
  - Open vs Resolved (Pie chart)
  - Common Categories (Bar chart)
  - Avg. Resolution Time
- **Super Admin Dashboard**:
  - User management (add/edit/block roles)

### 📣 Real-Time Notifications
- Users receive in-app notifications when status of their incident is updated by an admin.

### 📁 Audit Logs
- Actions like creation, update, or delete are logged with user ID, action type, timestamp, and IP address.

---

## 🧪 Demo Accounts

| Role         | Email                 | Password  |
|--------------|-----------------------|-----------|
| Super Admin  | superadmin@yopmail.com| 123456  |
| Admin        | admin@yopmail.com     | 123456  |
| User         | mansi@yopmail.com      | 123456  |

> 📌 You can create these accounts manually via DB seed. Ensure roles are assigned using Spatie.

---

## 🛠️ Installation

```bash
git clone https://github.com/mansi579/laravel-incident-reporting.git
cd incident-reporting-system

checkout into beta brnach

composer install
cp .env.example .env
php artisan key:generate

# Set up your database in .env

php artisan migrate
php artisan db:seed # if demo data added
php artisan serve
