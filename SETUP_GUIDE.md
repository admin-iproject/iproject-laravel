# iProject Laravel - Complete Setup Guide

## 📦 What You Have

This is a **complete, ready-to-run Laravel 11 application** that modernizes your legacy iProject PHP system.

### Included:
✅ Full Laravel 11 project structure  
✅ All 47 database migrations  
✅ Core models (User, Company, Project, Task, etc.)  
✅ Controllers with CRUD operations  
✅ Modern UI with Tailwind CSS  
✅ Authentication system ready  
✅ Role-based permissions (Spatie)  
✅ Responsive dashboard  
✅ API routes for future mobile app  

---

## 🚀 Quick Start (5 Minutes)

### 1. Prerequisites

Make sure you have:
- ✅ PHP 8.2+ installed
- ✅ MySQL 8.0+ installed and running
- ✅ Composer installed
- ✅ Node.js & NPM installed

### 2. Extract Files

```bash
unzip iproject-laravel.zip
cd iproject-laravel
```

### 3. Run Setup Script

**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

**Windows:**
```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file:
```env
DB_DATABASE=iproject_laravel
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Create Database

```bash
mysql -u root -p
CREATE DATABASE iproject_laravel;
exit;
```

### 6. Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed
```

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Compile Assets

**Development (with hot reload):**
```bash
npm run dev
```
Leave this running in a separate terminal.

**Production:**
```bash
npm run build
```

### 9. Start Server

```bash
php artisan serve
```

### 10. Login

Visit: http://localhost:8000

**Admin Account:**
- Email: `admin@iproject.local`
- Password: `password`

**Other Test Accounts:**
- Manager: `manager@iproject.local` / `password`
- Employee: `employee@iproject.local` / `password`

---

## 📂 What's in the Package

```
iproject-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php    # Main dashboard
│   │   ├── ProjectController.php      # Project CRUD
│   │   └── ...
│   └── Models/
│       ├── User.php                    # User model
│       ├── Company.php                 # Company model
│       ├── Project.php                 # Project model
│       ├── Task.php                    # Task model
│       └── ...
├── database/
│   ├── migrations/                     # All 47 table migrations
│   └── seeders/
│       ├── RoleSeeder.php              # Creates roles & permissions
│       └── AdminUserSeeder.php         # Creates admin user
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php           # Main layout
│   │   │   └── navigation.blade.php    # Navigation bar
│   │   └── dashboard.blade.php         # Dashboard view
│   ├── css/
│   │   └── app.css                     # Tailwind CSS
│   └── js/
│       └── app.js                      # Alpine.js
├── routes/
│   ├── web.php                         # Web routes
│   └── api.php                         # API routes
├── composer.json                       # PHP dependencies
├── package.json                        # JS dependencies
├── tailwind.config.js                  # Tailwind configuration
├── .env.example                        # Environment template
├── README.md                           # Full documentation
└── setup.sh                            # Quick setup script
```

---

## 🎨 UI Preview

### Dashboard
- Stats cards (Projects, Tasks, Tickets)
- Recent projects list
- My tasks list
- Assigned tickets table

### Navigation
- Dashboard
- Projects
- Tasks
- Tickets
- Companies
- Profile dropdown

### Design
- Modern Tailwind CSS design
- Responsive (mobile-friendly)
- Clean card-based layouts
- Color-coded priority badges
- Progress bars

---

## 🔧 Configuration

### Mail Setup (Optional)

Edit `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Queue Setup (Optional)

For background jobs:
```bash
# Change in .env
QUEUE_CONNECTION=database

# Run queue worker
php artisan queue:work
```

---

## 🗄️ Database

### Tables Created (47 total)

**Core:**
- users, companies, departments
- projects, tasks, contacts
- roles, permissions (Spatie)

**Projects:**
- project_team, project_resources, project_evm_issues

**Tasks:**
- task_log, task_checklist, task_dependencies, tasks_additional

**Tickets:**
- tickets, ticket_attachments, ticket_comments, ticket_auto_assignment

**Files:**
- files, files_index, files_approval, files_log

**More:**
- events, forums, approvals, etc.

### Sample Data

The seeders create:
- 1 demo company
- 3 users (admin, manager, employee)
- 4 roles with permissions

---

## 🎯 Next Steps

### 1. Customize Branding

**Logo:**
- Add your logo to `public/images/logo.png`
- Update navigation in `resources/views/layouts/navigation.blade.php`

**Colors:**
- Edit `tailwind.config.js` to change primary color
- Rebuild: `npm run build`

**Company Name:**
- Edit `.env`: `APP_NAME="Your Company"`

### 2. Import Your Data

**Option A: Manual Entry**
- Use the web interface to create companies, projects, etc.

**Option B: Data Migration**
- We'll create a custom migration script in Session 2
- It will import from your old database

### 3. Configure Features

**Email Notifications:**
- Set up SMTP in `.env`
- Customize email templates in `resources/views/emails/`

**File Uploads:**
- Configure storage disk in `config/filesystems.php`
- Set max upload size in `php.ini`

### 4. Add More Features

**What's Already Implemented:**
- ✅ Authentication
- ✅ Basic CRUD for all entities
- ✅ Dashboard with stats
- ✅ Role-based permissions

**Coming in Future Sessions:**
- ⏳ Complete task management (dependencies, Gantt)
- ⏳ Ticketing system (email integration)
- ⏳ File management (uploads, approval)
- ⏳ Calendar & events
- ⏳ Forums
- ⏳ Reports & charts
- ⏳ Advanced workflows

---

## 🐛 Troubleshooting

### "Composer not found"
```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### "NPM not found"
```bash
# Install Node.js (includes NPM)
# Visit: https://nodejs.org/
```

### "Class not found" errors
```bash
composer dump-autoload
php artisan optimize:clear
```

### "Permission denied" on Linux/Mac
```bash
chmod -R 775 storage bootstrap/cache
chmod +x setup.sh
```

### Assets not loading
```bash
# Make sure npm run dev is running
npm install
npm run dev
```

### Can't login
```bash
# Reseed database
php artisan migrate:fresh --seed
```

### Port 8000 already in use
```bash
# Use different port
php artisan serve --port=8001
```

---

## 📚 Learning Resources

### Laravel
- [Official Docs](https://laravel.com/docs/11.x)
- [Laracasts](https://laracasts.com/) - Video tutorials
- [Laravel News](https://laravel-news.com/)

### Tailwind CSS
- [Official Docs](https://tailwindcss.com/docs)
- [Tailwind UI](https://tailwindui.com/) - Component examples
- [Heroicons](https://heroicons.com/) - Icons

### Alpine.js
- [Official Docs](https://alpinejs.dev/)
- [Alpine Toolbox](https://www.alpinetoolbox.com/)

---

## 🎓 Understanding the Code

### How Routing Works

**routes/web.php:**
```php
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');
```
This means: "When someone visits /projects, run the index() method in ProjectController"

### How Controllers Work

**app/Http/Controllers/ProjectController.php:**
```php
public function index() {
    $projects = Project::all();
    return view('projects.index', compact('projects'));
}
```
This gets all projects and passes them to the view.

### How Views Work

**resources/views/projects/index.blade.php:**
```blade
@foreach($projects as $project)
    <div>{{ $project->name }}</div>
@endforeach
```
This loops through projects and displays them.

### How Models Work

**app/Models/Project.php:**
```php
public function company() {
    return $this->belongsTo(Company::class);
}
```
This defines relationships. Now you can do: `$project->company->name`

---

## 🚀 Deployment to Production

### 1. Environment

```bash
# .env
APP_ENV=production
APP_DEBUG=false
```

### 2. Optimize

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### 3. Security

- Set strong `APP_KEY`
- Use HTTPS (SSL certificate)
- Configure proper file permissions
- Enable firewall
- Regular backups

### 4. Server Requirements

- PHP 8.2 with required extensions
- MySQL 8.0
- Nginx or Apache
- Supervisor for queues
- Cron for scheduled tasks

---

## 📞 Getting Help

### In Session 2, We'll Cover:

1. **Complete User Management**
   - User profiles
   - Role assignment UI
   - Permission management

2. **Company Module**
   - Company settings
   - Department hierarchy
   - Multi-company support

3. **Testing**
   - How to test the application
   - Writing automated tests

4. **Questions & Issues**
   - Debug any problems
   - Add requested features

---

## ✅ Checklist

Before Session 2:

- [ ] Downloaded and extracted files
- [ ] Ran `composer install`
- [ ] Ran `npm install`
- [ ] Created `.env` file
- [ ] Created database
- [ ] Ran migrations
- [ ] Ran seeders
- [ ] Can login as admin
- [ ] Dashboard loads
- [ ] Tested creating a project
- [ ] Tested creating a task

If all checked, you're ready for Session 2! 🎉

---

## 🎉 Congratulations!

You now have:
- ✅ Modern Laravel 11 application
- ✅ Secure authentication
- ✅ Role-based permissions
- ✅ Beautiful UI with Tailwind
- ✅ Responsive design
- ✅ Complete database schema
- ✅ Foundation for all features

**Your legacy PHP app is now a modern Laravel application!**

For Session 2, just bring:
- This project folder
- Any questions or issues
- List of what you tested
- Requested features/changes

---

**Happy Coding! 🚀**

**Questions?** Document them for Session 2!
