# Incident Tracking System

A comprehensive web-based platform for reporting and managing security incidents, built with Laravel.

## Features

### 🔐 Authentication & Security
- Manual registration and login system
- Password hashing for security
- Role-based access control (User/Admin)
- Protection against SQL injection, XSS, and CSRF attacks
- Admin middleware for secure access control



### Permission Matrix details

| Action | Normal User | Admin |
|--------|-------------|-------|
| Create Incident | ✅ | ✅ |
| View Own Incidents | ✅ | ✅ |
| View Assigned Incidents | ✅ | ✅ |
| View All Incidents | ❌ | ✅ |
| Edit Own Incidents | ✅ | ✅ |
| Edit Others' Incidents | ❌ | ✅ |
| Comment on Own Incidents | ✅ | ✅ |
| Comment on Assigned Incidents | ✅ | ✅ |
| Comment on Any Incident | ❌ | ✅ |
| Assign Incidents | ❌ | ✅ |
| Update Status | ❌ | ✅ |
| View Admin Dashboard | ❌ | ✅ |


### 📊 Database Design
- **Users Table**: id, name, email, password, role (user/admin), timestamps
- **Incidents Table**: id, user_id, title, description, severity, status, assigned_to, timestamps
- **Incident Comments Table**: id, incident_id, user_id, comment, timestamps
- Proper foreign key relationships and database migrations

### 🎯 User Roles & Permissions
- **Normal Users**: Can report incidents and add comments to their own incidents
- **Admins**: Can assign incidents to responders, update status, and comment on any incident

### 🎨 Frontend Features
- Clean, responsive UI with Tailwind CSS
- Incident list with filtering and sorting capabilities
- Incident detail pages with comments system
- Forms for reporting and editing incidents
- Admin dashboard with statistics and metrics
- Mobile-responsive design

### 🔍 Advanced Features
- Search functionality (by title/description)
- Pagination for incident lists
- Filtering by severity and status
- Sorting by multiple columns
- Real-time comment system
- Activity tracking through comments

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP** 8.2 or higher
- **Composer** (PHP dependency manager)
- **MySQL** or **PostgreSQL** database
- **Node.js** and **npm** (optional, for frontend assets)

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd Heroic
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
```bash
cp .env.example .env
```

Edit the `.env` file with your database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=incident_tracker
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 6. Configure String Length (MySQL)
Add this line to `app/Providers/AppServiceProvider.php` in the `boot()` method:

```php
public function boot(): void
{
    Schema::defaultStringLength(191);
}
```

## Creating Admin Users

The application comes with a default admin user, but you can create additional admin users using several methods:

### Method 1: Default Admin (Already Available)
After running the seeder, you'll have a default admin account:
- **Email**: `admin@example.com`
- **Password**: `password`

### Method 2: Using the Custom Artisan Command (Recommended)

The application includes a custom command to easily create admin users:

#### Interactive Mode:
```bash
php artisan admin:create
```
This will prompt you for:
- Admin name
- Admin email  
- Admin password

#### Command Line Options:
```bash
# Create admin with all details
php artisan admin:create --name="John Admin" --email="john@admin.com" --password="secret123"

# Create admin with name and email, prompt for password
php artisan admin:create --name="Jane Admin" --email="jane@admin.com"

# Create admin with email only, prompt for name and password
php artisan admin:create --email="admin2@example.com"
```



## Running the Application

### Start the Development Server
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### Access the Application

#### Default Login Credentials

**Admin Account:**
- Email: `admin@example.com`
- Password: `password`

**Regular User Account:**
- Email: `john@example.com`
- Password: `password`

#### Additional Test Accounts
- `jane@example.com` / `password`
- `bob@example.com` / `password`



## Usage Guide

### For Regular Users

1. **Register/Login**: Create an account or login with existing credentials
2. **Report Incident**: Click "Report New Incident" to create a new security incident
3. **View Incidents**: Access "Incidents" to see all incidents with filtering options
4. **Add Comments**: Add comments to incidents you've reported
5. **Edit Incidents**: Edit incidents you've created

### For Administrators

1. **Admin Dashboard**: View comprehensive statistics and recent incidents
2. **Assign Incidents**: Assign incidents to specific users for resolution
3. **Update Status**: Change incident status (Open → In Progress → Resolved)
4. **Manage All Incidents**: Full access to all incidents in the system
5. **Add Comments**: Comment on any incident in the system


### Authentication
- `GET /login` - Login page
- `POST /login` - Process login
- `GET /register` - Registration page
- `POST /register` - Process registration
- `POST /logout` - Logout user

### Incidents
- `GET /incidents` - List all incidents
- `GET /incidents/create` - Create incident form
- `POST /incidents` - Store new incident
- `GET /incidents/{id}` - Show incident details
- `GET /incidents/{id}/edit` - Edit incident form
- `PUT /incidents/{id}` - Update incident
- `POST /incidents/{id}/comments` - Add comment

### Admin Only
- `POST /incidents/{id}/assign` - Assign incident to user
- `POST /incidents/{id}/status` - Update incident status

## Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **XSS Prevention**: Proper output escaping in Blade templates
- **SQL Injection Prevention**: Uses Eloquent ORM with parameterized queries
- **Password Security**: Bcrypt hashing for all passwords
- **Role-based Access**: Middleware protection for admin functions
- **Input Validation**: Comprehensive validation rules for all inputs



### Common Issues

1. **Database Connection Error**
   - Verify database credentials in `.env`
   - Ensure database server is running
   - Check database exists

2. **Migration Errors**
   - Run `php artisan migrate:fresh` to reset database
   - Ensure all dependencies are installed

3. **Permission Errors**
   - Check file permissions on storage and bootstrap/cache directories
   - Run `php artisan storage:link` if needed

4. **String Length Error (MySQL)**
   - Ensure `Schema::defaultStringLength(191)` is in AppServiceProvider

### Debug Mode
Enable debug mode in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

