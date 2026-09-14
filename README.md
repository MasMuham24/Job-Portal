# Job Portal

A full-stack job portal application built with **Laravel 12**, **Blade**, **Tailwind CSS**, and **MySQL**.

Job Portal menyediakan platform untuk mempertemukan pencari kerja dan perusahaan dalam satu sistem. Pencari kerja dapat membuat profil dan melamar lowongan, sementara recruiter dapat mengelola perusahaan, membuat lowongan, dan memproses lamaran. Admin bertugas melakukan moderasi terhadap lowongan yang dipublikasikan.

## ✨ Features

### 🔐 Authentication & Authorization

* User registration and login
* Login role selection
* Role-based access control
* Supported roles:

  * `Admin`
  * `Employer`
  * `Job Seeker`
* Protected routes with middleware
* Secure logout and session handling

### 👤 Job Seeker

* Complete personal profile
* Profile completion percentage
* Personal information management
* Education and skills
* Work experience
* Bio
* Browse available jobs
* View job details
* Apply for jobs
* Cover letter submission
* Application history
* Track application status

Application statuses:

* Pending
* Reviewed
* Accepted
* Rejected

Job applications are only available when the job seeker has completed their profile.

### 🏢 Employer

* Create and manage company profile
* Company logo support
* Company description
* Create job postings
* Edit job postings
* Delete job postings
* Manage job posting status
* View incoming applications
* Filter applications
* Review candidate profiles
* Update application status
* Employer dashboard with application statistics

When an active job posting is edited, its status automatically returns to `pending` so the job can be reviewed again by an administrator.

### 🛡️ Admin

* Admin dashboard
* User management
* View registered users
* Create users
* Edit users
* Delete users
* Job posting moderation
* View job posting details
* Approve / manage job posting status
* Delete inappropriate job postings

### 🔎 Public Job Catalog

* Public job listing
* Job search and filtering
* Filter by location
* Filter by employment type
* Pagination
* Job detail page
* Real-time job status polling

### ⚡ Performance & Security

* Database indexes for frequently queried columns
* Pagination on listing pages
* AJAX-based job status polling
* Polling automatically stops when the browser tab becomes inactive
* CSRF protection
* Mass assignment protection
* Role-based authorization
* Ownership validation
* IDOR prevention
* Unique constraint for company ownership
* Unique constraint preventing duplicate applications
* Re-moderation of edited active jobs
* Custom `403`, `404`, and `500` error pages

## 🧰 Tech Stack

### Backend

* PHP 8.2+
* Laravel 12
* MySQL
* Laravel Blade

### Frontend

* Blade Templates
* Tailwind CSS
* JavaScript
* Vite

### Development Tools

* Composer
* NPM
* PHPUnit / Laravel Testing

## 📁 Project Structure

```text
Job-Portal/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── ...
├── resources/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── company/
│       ├── employer/
│       ├── jobs/
│       ├── applications/
│       ├── profile/
│       └── layouts/
├── routes/
│   └── web.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── public/
├── storage/
├── vite.config.js
├── package.json
└── composer.json
```

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/your-username/Job-Portal.git
cd Job-Portal
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Create Environment File

```bash
cp .env.example .env
```

For Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Edit the `.env` file:

```env
APP_NAME="Job Portal"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:

```sql
CREATE DATABASE job_portal;
```

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 10. Start Laravel Server

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## 🧪 Testing

The project includes automated feature and unit tests covering authentication, authorization, job postings, applications, profiles, moderation, and security-related functionality.

Run the complete test suite:

```bash
php artisan test
```

Current test result:

```text
144 passed
488 assertions
0 failures
0 errors
0 skipped
```

## 🔒 Security

The application implements several security measures:

* CSRF protection on forms
* Role-based middleware
* Authorization and ownership checks
* Mass assignment protection
* Database constraints
* Duplicate application prevention
* Profile completion validation
* Job moderation workflow
* Re-moderation after active job edits
* Protected employer application access
* Protected job seeker application history
* Custom production error pages

Production environments should use:

```env
APP_ENV=production
APP_DEBUG=false
```

Production secrets such as `APP_KEY`, database credentials, and other environment variables must never be committed to Git.

## 📊 Database Overview

Main entities:

```text
Users
  │
  ├── Companies
  │      │
  │      └── Job Postings
  │               │
  │               └── Applications
  │
  └── Applications
```

### Main Tables

* `users`
* `companies`
* `job_postings`
* `applications`

The application uses foreign keys and unique constraints to maintain relational data integrity.

## 🔄 Application Workflow

```text
Job Seeker
    │
    ├── Register
    │
    ├── Complete Profile
    │
    ├── Browse Jobs
    │
    └── Apply
          │
          ▼
      Pending
          │
          ▼
      Employer Review
          │
       ┌──┴──┐
       ▼     ▼
   Accepted Rejected
```

Job posting workflow:

```text
Employer Creates Job
        │
        ▼
     Pending
        │
        ▼
   Admin Review
        │
    ┌───┴────┐
    ▼        ▼
 Active    Closed
```

Editing an active job automatically returns it to:

```text
Pending → Admin Review → Active
```

## 🧑‍💻 Development

This project was developed as a portfolio full-stack web application with a focus on:

* Laravel MVC architecture
* Relational database design
* Authentication and authorization
* CRUD operations
* Role-based workflows
* Form validation
* Security
* Database integrity
* Automated testing
* Production readiness

## 📌 Production Checklist

Before deploying to production:

```bash
composer install --optimize-autoloader --no-dev

npm install

npm run build

php artisan migrate --force

php artisan storage:link

php artisan optimize
```

Production `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

Make sure:

* `APP_KEY` is generated
* Database credentials are configured
* `.env` is not committed
* HTTPS is enabled
* Storage permissions are configured
* Production assets are built

## 📈 Future Improvements

Potential future development:

* Email notifications
* Saved jobs
* Advanced job search
* Resume/CV upload
* Employer analytics
* Job recommendation system
* Admin analytics
* Email verification
* Password reset improvements
* Production asset optimization

## 📄 License

This project is intended as a portfolio and learning project.

---

**Job Portal** — Laravel-based recruitment platform for job seekers, employers, and administrators.
