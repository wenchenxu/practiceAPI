# Developer & Technical Documentation

This document is for new developers joining the Vehicle & Driver Management Dashboard project. It covers architecture, code structure, environment setup, and deployment.

---

## 1. Architecture Overview
- **Framework:** Laravel 12 (PHP 8.4)
- **Frontend:** Blade templates, Tailwind CSS, JavaScript
- **Database:** PostgreSQL (via Docker)
- **API:** RESTful endpoints for vehicles, drivers, and car key integration
- **Deployment:** Docker containers on Alibaba ECS (Ubuntu)

---

## 2. Project Structure
- `app/Http/Controllers/` — Application controllers
- `app/Models/` — Eloquent models (Vehicle, Driver, User)
- `app/Services/` — Business logic (CarKeyService)
- `resources/views/` — Blade templates
- `routes/web.php` — Route definitions
- `database/migrations/` — Schema migrations
- `config/` — Configuration files
- `docker-config/` — Nginx, PHP-FPM, Supervisor configs
- `Dockerfile`, `docker-compose.yml`, `docker-compose.prod.yml` — Container setup

---

## 3. Environment Setup
### Local Development
- Install Docker, Composer, Node.js
- Copy `.env.example` to `.env` and configure
- Run `composer install` and `npm install`
- Run migrations: `php artisan migrate`
- Start local server: `php artisan serve`

### Docker
- Build and run: `docker compose up -d`
- For production, use `docker-compose.prod.yml` and `.env.prod`

---

## 4. Database
- **PostgreSQL**
- Migrations in `database/migrations/`
- Seeders in `database/seeders/`

---

## 5. Key Code Locations
- **Vehicle CRUD:** `VehicleController.php`, `Vehicle.php`, Blade views in `resources/views/vehicles/`
- **Driver Management:** `Driver.php`, related controllers and views
- **Car Key Integration:** `CarKeyController.php`, `CarKeyService.php`

---

## 6. Deployment (Alibaba ECS)
- Build Docker image and push to registry
- Configure ECS to use your image
- Use environment variables for secrets
- Monitor containers and logs via ECS console

---

## 7. Testing
- PHPUnit tests in `tests/`
- Run tests: `vendor/bin/phpunit`

---

## 8. Contribution Guidelines
- Follow PSR-12 coding standards
- Use feature branches for new work
- Submit pull requests with clear descriptions

---

## 9. Troubleshooting
- Check container logs for errors
- Ensure database migrations are up to date
- Verify `.env` configuration

---

## 10. Useful Commands
```bash
# Start containers
docker compose up -d

# Run migrations
php artisan migrate

# Run tests
vendor/bin/phpunit
```

---

For questions, contact the project maintainer or check the README for more info.
