<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Details

This project is built using Laravel version 11 and focuses on RESTful API design. It implements best practices and emphasizes separating requests and responses while adhering to various design principles such as DRY (Don't Repeat Yourself), SOLID, and Separation of Concerns.

### Features Implemented

- Two complete CRUD operations for models: `User` and `Product`
- Usage of:
  - Traits
  - Services
  - Enums
  - Custom Middleware
  - JWT Token Authentication
  - Object-Oriented Programming (OOP) principles
  - DRY (Don't Repeat Yourself) approach
  - Error handling
  - Request handling
  - Resource management

### Setting Up The Project

1. **Clone the Repository**: 
   - Open your terminal or command prompt.
   - Navigate to the directory where you want to clone the project.
   - Run the following command:
     ```bash
     git clone https://github.com/adamhmid/Laravel-11-Auth-JWT-RESTful-API.git
     ```

2. **Install Dependencies**:
   - Navigate into the cloned project directory:
     ```bash
     cd Laravel-11-Auth-JWT-RESTful-API
     ```
   - Install Composer dependencies:
     ```bash
     composer install
     ```

3. **Create Environment File**:
   - Make a copy of the `.env.example` file and rename it to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Generate an application key:
     ```bash
     php artisan key:generate
     ```

4. **Database & JWT Configuration**:
   - Open the `.env` file and configure your settings.
   - Set the database name, username, password, and other relevant settings.
   - Set `JWT_SECRET` for secret key, using random character.
   - Set `JWT_ALGO` for the encryption algorithm you want to use, such as <b>HS256</b> and others.

5. **Run Migrations and Seeders**:
   - Run database migrations to create tables:
     ```bash
     php artisan migrate
     ```
   - Run the seeders to populate the database with sample data:
     ```bash
     php artisan db:seed
     ```

6. **Serve the Application**:
   - Start the Laravel development server:
     ```bash
     php artisan serve
     ```

7. **Access the Application**:
   - Open your web browser and go to `http://localhost:8000` or the URL provided by the `php artisan serve` command.

8. **Test APIs**:
   - Utilize the provided CRUD operations for testing the RESTful APIs as per the project's documentation or instructions.

9. **Explore Additional Features**:
   - Explore and test additional features such as Traits, Services, Enums, Custom Middleware, JWT Token Authentication, Object-Oriented Programming (OOP) principles, DRY approach, Error handling, Request handling, and Resource management as provided in the project.

10. **Start Developing**:
    - With the project set up, you can now start developing your application or explore further customization as per your requirements.
