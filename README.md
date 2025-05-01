# Hospital Intern Management System

A web-based system for managing medical internships at hospitals, built with Laravel 10 and TailwindCSS.

## Features

-   Multi-role authentication (Admin, Doctor, Student)
-   Student attendance tracking
-   Internship scheduling
-   Performance monitoring
-   Real-time notifications
-   Report generation

## Requirements

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   MySQL or PostgreSQL
-   Laravel CLI

## Project Setup

1. Clone the repository

```bash
git clone https://github.com/yourusername/hospital-intern.git
cd hospital-intern
```

2. Install PHP dependencies

```bash
composer install
```

3. Install NPM packages

```bash
npm install
```

4. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_intern
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations and seeders

```bash
php artisan migrate --seed
```

7. Start the development server

```bash
php artisan serve
npm run dev
```

## Project Structure

```
├── app
│   ├── Http
│   │   ├── Controllers        # Controllers
│   │   ├── Middleware        # Custom middleware
│   │   └── Requests         # Form requests
│   ├── Models               # Eloquent models
│   └── Services            # Business logic
├── database
│   ├── migrations          # Database migrations
│   └── seeders            # Database seeders
├── resources
│   ├── css                # Tailwind CSS
│   ├── js                 # Alpine.js and custom scripts
│   └── views
│       ├── components     # Reusable Blade components
│       ├── layouts        # Layout templates
│       └── pages          # Page views
└── routes
    └── web.php           # Web routes
```

## Key Components

### Authentication

-   Custom auth using Laravel's built-in features
-   Role-based access control
-   Session management

### Database Models

-   Users (Admin, Doctor, Student)
-   Internship Classes
-   Schedules
-   Attendance
-   Study Programs
-   Campus

### Frontend

-   TailwindCSS for styling
-   Alpine.js for interactivity
-   Blade components
-   Responsive design

## Development Guidelines

1. Follow PSR-12 coding standards
2. Use type hints and return types
3. Write meaningful commit messages
4. Document new features
5. Write tests for critical features

## Testing

Run the test suite:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
