# iProject - Modern Laravel Project Management Suite

A complete modernization of the legacy iProject PHP application, rebuilt with Laravel 11, Tailwind CSS, and Alpine.js.

## 🚀 Features

- **Project Management** - Create, manage, and track projects with teams, budgets, and timelines
- **Task Management** - Comprehensive task tracking with dependencies, checklists, and time logging
- **Unified Ticketing System** - Consolidated ticketing system (combining tracker, OTRS, and ticketsmith)
- **Company & Department Management** - Multi-company support with hierarchical departments
- **Contact Management** - Organize and manage business contacts
- **User Management** - Role-based permissions and user profiles
- **Modern UI** - Responsive design with Tailwind CSS
- **Secure** - Built-in CSRF protection, SQL injection prevention, bcrypt password hashing

## 📋 Requirements

- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer
- Node.js & NPM
- Git

## 🛠️ Installation

### 1. Download and Extract

Extract the project files to your desired location:
```bash
cd /path/to/your/projects
unzip iproject-laravel.zip
cd iproject-laravel
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file:
```bash
cp .env.example .env
```

Edit `.env` and configure your database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iproject_laravel
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Database

Create a new MySQL database:
```bash
mysql -u root -p
CREATE DATABASE iproject_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Install Spatie Permission

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 9. Seed Database (Optional)

Create roles and an admin user:
```bash
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=AdminUserSeeder
```

Default admin credentials:
- Email: `admin@iproject.local`
- Password: `password`

### 10. Create Storage Link

```bash
php artisan storage:link
```

### 11. Compile Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 12. Start Development Server

```bash
php artisan serve
```

Visit: http://localhost:8000

## 📁 Project Structure

```
iproject-laravel/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/                # Eloquent models
│   ├── Policies/              # Authorization policies
│   └── Services/              # Business logic services
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── css/                   # Tailwind CSS
│   ├── js/                    # Alpine.js & JavaScript
│   └── views/                 # Blade templates
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
└── public/                    # Public assets
```

## 🎨 Frontend Stack

- **Tailwind CSS 3.x** - Utility-first CSS framework
- **Alpine.js 3.x** - Lightweight JavaScript framework
- **Vite** - Fast build tool and dev server
- **Blade Templates** - Laravel's templating engine

## 🔐 Security Features

- CSRF Protection (built-in)
- SQL Injection Prevention (Eloquent ORM)
- XSS Protection (Blade escaping)
- Bcrypt Password Hashing
- Input Validation (Form Requests)
- Rate Limiting
- Sanctum API Authentication

## 📊 Database

The application includes migrations for 47 tables:

**Core Tables:**
- users, companies, departments
- projects, tasks, contacts
- roles, permissions

**Supporting Tables:**
- project_team, project_resources
- task_log, task_checklist, task_dependencies
- tickets, ticket_attachments, ticket_comments
- files, files_approval, files_log
- events, forums, forum_messages
- And more...

## 🧪 Testing

Run tests with:
```bash
php artisan test
```

## 📝 Common Commands

```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Create a new controller
php artisan make:controller NameController

# Create a new model
php artisan make:model Name

# View routes
php artisan route:list

# Interactive shell
php artisan tinker
```

## 🔄 Migrating from Old iProject

### Data Migration

1. Export your old database
2. Run the data migration script:
```bash
php artisan migrate:old-data path/to/old-database.sql
```

3. The script will:
   - Import companies, departments, users
   - Convert projects and tasks
   - Migrate files and documents
   - Transform MD5 passwords to bcrypt
   - Consolidate the 3 ticket systems

### Manual Steps

1. Copy uploaded files from old system to `storage/app/public/`
2. Update file paths in database if needed
3. Review and update company settings
4. Test user logins
5. Verify data integrity

## 🎯 Key Improvements from Legacy System

### Security
- ✅ No SQL injection vulnerabilities
- ✅ Secure password hashing (bcrypt vs MD5)
- ✅ CSRF protection
- ✅ Input validation
- ✅ XSS protection

### Code Quality
- ✅ MVC architecture
- ✅ Eloquent ORM instead of raw SQL
- ✅ Service layer for business logic
- ✅ Policy-based authorization
- ✅ Type declarations
- ✅ Namespaces

### Frontend
- ✅ Modern responsive design
- ✅ Mobile-first approach
- ✅ No Flash charts (using Chart.js)
- ✅ Proper CSS with Tailwind
- ✅ Component-based UI

### Database
- ✅ Migration system
- ✅ Proper relationships
- ✅ Indexes for performance
- ✅ Soft deletes

## 🐛 Troubleshooting

### "Class not found" errors
```bash
composer dump-autoload
php artisan optimize:clear
```

### Migration errors
```bash
php artisan migrate:fresh
```

### Assets not loading
```bash
npm install
npm run dev
# Keep this running in a separate terminal
```

### Permission errors
```bash
chmod -R 775 storage bootstrap/cache
```

## 📚 Documentation

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/start-here)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)

## 🤝 Contributing

1. Create a feature branch
2. Make your changes
3. Write/update tests
4. Submit a pull request

## 📄 License

MIT License

## 🆘 Support

For issues or questions:
1. Check the documentation
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check browser console for JS errors
4. Enable debug mode in `.env`: `APP_DEBUG=true`

## 🎉 What's Next?

After successful installation:

1. **Customize** - Update logo, colors, company name
2. **Configure** - Set up email, queues, backups
3. **Import Data** - Migrate from old system
4. **Train Users** - Create user documentation
5. **Deploy** - Set up production environment

## 🚀 Deployment

For production deployment:

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `npm run build`
4. Set up queue workers
5. Configure cron for scheduled tasks
6. Set up database backups
7. Configure SSL certificate

## 📈 Performance Tips

- Enable caching: `php artisan config:cache`
- Optimize routes: `php artisan route:cache`
- Use queue workers for emails
- Enable OPcache in PHP
- Use Redis for sessions/cache
- Set up CDN for static assets

---

**Built with ❤️ using Laravel 11, Tailwind CSS, and Alpine.js**

**Version:** 1.0.0  
**Last Updated:** January 2024
