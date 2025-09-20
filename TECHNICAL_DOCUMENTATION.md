# Incident Tracking System - Technical Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Architecture](#architecture)
3. [Database Design](#database-design)
4. [Module Documentation](#module-documentation)
5. [Business Logic & Rules](#business-logic--rules)
6. [API Documentation](#api-documentation)
7. [Security Implementation](#security-implementation)
8. [User Roles & Permissions](#user-roles--permissions)
9. [Workflow Diagrams](#workflow-diagrams)
10. [Deployment Guide](#deployment-guide)

---

## System Overview

The Incident Tracking System is a web-based platform built with Laravel 11 for reporting and managing security incidents. It provides role-based access control, incident lifecycle management, and collaborative commenting features.

### Key Features
- **User Management**: Registration, authentication, and role-based access
- **Incident Management**: Create, read, update, and track security incidents
- **Assignment System**: Admin can assign incidents to specific users
- **Comment System**: Collaborative discussion on incidents
- **Filtering & Search**: Advanced filtering and search capabilities
- **Dashboard Analytics**: Role-specific dashboards with statistics

---

## Architecture

### Technology Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL/PostgreSQL/SQLite
- **Frontend**: Blade Templates + Tailwind CSS
- **Authentication**: Laravel's built-in authentication
- **Icons**: Font Awesome 6

### System Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Blade +      │◄──►│   (Laravel)     │◄──►│   (MySQL)       │
│   Tailwind)     │    │                 │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Directory Structure
```
Heroic/
├── app/
│   ├── Console/Commands/          # Artisan commands
│   ├── Http/Controllers/          # Request handlers
│   ├── Http/Middleware/           # Request middleware
│   └── Models/                    # Eloquent models
├── database/
│   ├── migrations/                # Database schema
│   └── seeders/                   # Sample data
├── resources/views/               # Blade templates
├── routes/                        # Route definitions
└── public/                        # Web assets
```

---

## Database Design

### Entity Relationship Diagram
```
Users (1) ──────── (M) Incidents
  │                    │
  │                    │
  │                    │
  │                    │
  └──────── (M) IncidentComments
```

### Tables

#### Users Table
```sql
users:
├── id (Primary Key)
├── name (VARCHAR 255)
├── email (VARCHAR 191, UNIQUE)
├── password (VARCHAR 255, HASHED)
├── role (ENUM: 'user', 'admin')
├── email_verified_at (TIMESTAMP, NULLABLE)
├── remember_token (VARCHAR 100, NULLABLE)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

#### Incidents Table
```sql
incidents:
├── id (Primary Key)
├── user_id (Foreign Key → users.id)
├── title (VARCHAR 255)
├── description (TEXT)
├── severity (ENUM: 'low', 'medium', 'high', 'critical')
├── status (ENUM: 'open', 'in-progress', 'resolved')
├── assigned_to (Foreign Key → users.id, NULLABLE)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

#### Incident Comments Table
```sql
incident_comments:
├── id (Primary Key)
├── incident_id (Foreign Key → incidents.id)
├── user_id (Foreign Key → users.id)
├── comment (TEXT)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

### Indexes
- `users.email` (UNIQUE)
- `incidents.user_id` (INDEX)
- `incidents.assigned_to` (INDEX)
- `incidents.severity, status` (COMPOSITE INDEX)
- `incident_comments.incident_id` (INDEX)

---

## Module Documentation

### 1. Authentication Module

#### AuthController
**Purpose**: Handles user registration, login, and logout

**Methods**:
- `showLogin()`: Display login form
- `showRegister()`: Display registration form
- `login(Request $request)`: Process login
- `register(Request $request)`: Process registration
- `logout(Request $request)`: Process logout

**Security Features**:
- Password hashing using Laravel's Hash facade
- CSRF protection on all forms
- Session regeneration on login
- Input validation and sanitization

#### User Model
**Purpose**: Represents user entities and relationships

**Key Methods**:
- `isAdmin()`: Check if user has admin role
- `incidents()`: Get incidents created by user
- `assignedIncidents()`: Get incidents assigned to user
- `comments()`: Get comments made by user

### 2. Incident Management Module

#### IncidentController
**Purpose**: Handles CRUD operations for incidents

**Methods**:
- `index(Request $request)`: List incidents with filtering
- `create()`: Show incident creation form
- `store(Request $request)`: Create new incident
- `show(Incident $incident)`: Display incident details
- `edit(Incident $incident)`: Show incident edit form
- `update(Request $request, Incident $incident)`: Update incident
- `assign(Request $request, Incident $incident)`: Assign incident (Admin only)
- `updateStatus(Request $request, Incident $incident)`: Update status (Admin only)
- `addComment(Request $request, Incident $incident)`: Add comment

**Authorization Rules**:
- Normal users can only see incidents they created or are assigned to
- Only incident creators can edit incidents
- Only admins can assign incidents and update status
- Users can comment on incidents they created or are assigned to

#### Incident Model
**Purpose**: Represents incident entities and business logic

**Key Methods**:
- `user()`: Get incident creator
- `assignedTo()`: Get assigned user
- `comments()`: Get incident comments
- `isAssigned()`: Check if incident is assigned
- `canBeUpdatedBy(User $user)`: Check edit permissions
- `canBeCommentedBy(User $user)`: Check comment permissions

### 3. Dashboard Module

#### DashboardController
**Purpose**: Provides role-specific dashboards

**Methods**:
- `index()`: Display appropriate dashboard based on user role

**Admin Dashboard Features**:
- Total incidents count
- Open incidents count
- In-progress incidents count
- Resolved incidents count
- Recent incidents list
- Quick action buttons

**User Dashboard Features**:
- Personal incidents count
- Open incidents count
- Resolved incidents count
- Recent incidents (created + assigned)
- Quick action buttons

### 4. Comment System Module

#### IncidentComment Model
**Purpose**: Represents comment entities

**Relationships**:
- `incident()`: Belongs to incident
- `user()`: Belongs to user

**Features**:
- Real-time comment display
- User attribution
- Timestamp tracking
- Authorization-based visibility

---

## Business Logic & Rules

### Access Control Rules

#### **Rule 1: Incident Visibility**
```
IF user.role == 'admin' THEN
    user can see ALL incidents
ELSE
    user can see incidents WHERE:
        - incident.user_id == user.id (incidents they created)
        OR
        - incident.assigned_to == user.id (incidents assigned to them)
```

#### **Rule 2: Incident Editing**
```
IF user.role == 'admin' THEN
    user can edit ANY incident
ELSE
    user can edit incidents WHERE:
        - incident.user_id == user.id (only incidents they created)
```

#### **Rule 3: Comment Permissions**
```
IF user.role == 'admin' THEN
    user can comment on ANY incident
ELSE
    user can comment on incidents WHERE:
        - incident.user_id == user.id (incidents they created)
        OR
        - incident.assigned_to == user.id (incidents assigned to them)
```

#### **Rule 4: Assignment & Status Updates**
```
IF user.role == 'admin' THEN
    user can:
        - Assign incidents to any user
        - Update incident status
        - Update incident severity
ELSE
    user CANNOT:
        - Assign incidents
        - Update incident status
        - Update incident severity
```

### User Roles

#### Normal User
**What They CAN Do**:
- Create new incidents
- View incidents they created
- View incidents assigned to them
- Edit incidents they created
- Comment on incidents they created
- Comment on incidents assigned to them
- Filter and search their visible incidents

**What They CANNOT Do**:
- See incidents created by other users (unless assigned to them)
- Edit incidents created by others
- Assign incidents to anyone
- Update incident status
- Update incident severity
- Comment on unrelated incidents
- Access admin dashboard

#### Admin User
**What They CAN Do**:
- All normal user capabilities
- View ALL incidents in the system
- Edit ANY incident
- Assign incidents to any user
- Update incident status
- Update incident severity
- Comment on any incident
- Access admin dashboard with system-wide statistics

**What They CANNOT Do**:
- No restrictions (full system access)

### Incident Lifecycle

#### States
1. **Open**: Newly created incident
2. **In-Progress**: Incident is being worked on
3. **Resolved**: Incident has been resolved

#### Transitions
- **Open → In-Progress**: Admin action
- **In-Progress → Resolved**: Admin action
- **Resolved → Open**: Admin action (if needed)

### Severity Levels
- **Low**: Minor issues with minimal impact
- **Medium**: Moderate issues with some impact
- **High**: Serious issues with significant impact
- **Critical**: Severe issues requiring immediate attention

### Assignment Rules
- Only admins can assign incidents
- Incidents can be assigned to any user
- Assigned users can comment on incidents
- Assignment is optional (incidents can be unassigned)

---

## API Documentation

### Authentication Endpoints

#### POST /login
**Purpose**: Authenticate user
**Parameters**:
- `email` (required): User email
- `password` (required): User password

**Response**: Redirect to dashboard on success

#### POST /register
**Purpose**: Register new user
**Parameters**:
- `name` (required): User full name
- `email` (required): User email
- `password` (required): User password
- `password_confirmation` (required): Password confirmation

**Response**: Redirect to dashboard on success

#### POST /logout
**Purpose**: Logout user
**Response**: Redirect to login page

### Incident Endpoints

#### GET /incidents
**Purpose**: List incidents with filtering
**Query Parameters**:
- `search`: Search term
- `severity`: Filter by severity
- `status`: Filter by status
- `sort_by`: Sort column
- `sort_order`: Sort direction (asc/desc)

**Response**: Paginated incident list

#### POST /incidents
**Purpose**: Create new incident
**Parameters**:
- `title` (required): Incident title
- `description` (required): Incident description
- `severity` (required): Severity level

**Response**: Redirect to incident list

#### GET /incidents/{id}
**Purpose**: Show incident details
**Response**: Incident detail page

#### PUT /incidents/{id}
**Purpose**: Update incident
**Parameters**: Same as create
**Authorization**: Only incident creator

#### POST /incidents/{id}/assign
**Purpose**: Assign incident to user
**Parameters**:
- `assigned_to` (required): User ID
**Authorization**: Admin only

#### POST /incidents/{id}/status
**Purpose**: Update incident status
**Parameters**:
- `status` (required): New status
**Authorization**: Admin only

#### POST /incidents/{id}/comments
**Purpose**: Add comment to incident
**Parameters**:
- `comment` (required): Comment text
**Authorization**: Incident creator or assigned user

---

## Security Implementation

### Authentication Security
- **Password Hashing**: Bcrypt with salt
- **Session Management**: Laravel's built-in session handling
- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Comprehensive validation rules

### Authorization Security
- **Role-Based Access**: Middleware-based role checking
- **Resource Ownership**: Users can only access their own resources
- **Admin Override**: Admins have full system access
- **Assignment-Based Access**: Users can access assigned incidents

### Data Security
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **XSS Prevention**: Blade template escaping
- **Input Sanitization**: Laravel's validation and sanitization
- **Database Constraints**: Foreign key relationships and constraints

### Middleware Implementation
```php
// Admin Middleware
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        return $next($request);
    }
}
```

---

## User Roles & Permissions

### Permission Matrix

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

### Access Control Implementation

#### **Code Implementation of Business Rules**

##### **Rule 1: Incident Visibility (IncidentController@index)**
```php
// Filter incidents based on user role
if (!Auth::user()->isAdmin()) {
    // Normal users can only see incidents they created or are assigned to
    $query->where(function ($q) {
        $q->where('user_id', Auth::id())      // Incidents they created
          ->orWhere('assigned_to', Auth::id()); // Incidents assigned to them
    });
}
// Admins see all incidents (no filtering applied)
```

##### **Rule 2: Incident Editing (IncidentController@update)**
```php
// Check if user can update this incident
if (!$incident->canBeUpdatedBy(Auth::user())) {
    abort(403, 'You can only edit incidents you created.');
}

// Model method: canBeUpdatedBy()
public function canBeUpdatedBy(User $user): bool
{
    return $user->role === 'admin' || $user->id === $this->user_id;
}
```

##### **Rule 3: Comment Permissions (IncidentController@addComment)**
```php
// Check if user can comment on this incident
if (!$incident->canBeCommentedBy(Auth::user())) {
    abort(403, 'You can only comment on incidents you created or are assigned to.');
}

// Model method: canBeCommentedBy()
public function canBeCommentedBy(User $user): bool
{
    return $user->role === 'admin' ||
           $user->id === $this->user_id ||
           $user->id === $this->assigned_to;
}
```

##### **Rule 4: Assignment & Status Updates (Admin Middleware)**
```php
// Admin-only routes in web.php
Route::middleware('admin')->group(function () {
    Route::post('/incidents/{incident}/assign', [IncidentController::class, 'assign']);
    Route::post('/incidents/{incident}/status', [IncidentController::class, 'updateStatus']);
});

// AdminMiddleware
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403, 'Access denied. Admin privileges required.');
    }
    return $next($request);
}
```

#### **Frontend Access Control**

##### **Conditional UI Elements**
```php
// Show edit button only if user can edit
@if($incident->canBeUpdatedBy(Auth::user()))
    <a href="{{ route('incidents.edit', $incident) }}">Edit</a>
@endif

// Show comment form only if user can comment
@if($incident->canBeCommentedBy(Auth::user()))
    <form method="POST" action="{{ route('incidents.comments', $incident) }}">
        <!-- Comment form -->
    </form>
@else
    <p>You can only comment on incidents you created or are assigned to.</p>
@endif

// Show admin actions only to admins
@if(Auth::user()->isAdmin())
    <button onclick="assignIncident()">Assign</button>
    <button onclick="updateStatus()">Update Status</button>
@endif
```

#### **Dashboard Access Control**

##### **Role-Based Dashboard Data**
```php
// Admin Dashboard - Shows all incidents
$incidents = Incident::with(['user', 'assignedTo'])->latest()->take(10)->get();
$totalIncidents = Incident::count();

// User Dashboard - Shows only user's incidents
$incidents = Incident::where(function ($query) use ($user) {
    $query->where('user_id', $user->id)
          ->orWhere('assigned_to', $user->id);
})->with(['user', 'assignedTo'])->latest()->take(10)->get();
```

---

## Business Logic Flow Examples

### **Scenario 1: Normal User Login**
```
1. User logs in as "john@example.com" (normal user)
2. System checks: user.role = 'user'
3. Dashboard shows:
   - Only incidents created by john@example.com
   - Only incidents assigned to john@example.com
4. User can:
   - Create new incidents
   - Edit incidents they created
   - Comment on incidents they created
   - Comment on incidents assigned to them
5. User cannot:
   - See incidents created by other users
   - Edit incidents created by others
   - Assign incidents to anyone
   - Update incident status
```

### **Scenario 2: Admin User Login**
```
1. User logs in as "admin@example.com" (admin user)
2. System checks: user.role = 'admin'
3. Dashboard shows:
   - ALL incidents in the system
   - System-wide statistics
4. Admin can:
   - See all incidents
   - Edit any incident
   - Comment on any incident
   - Assign incidents to any user
   - Update incident status
   - Access admin dashboard
```

### **Scenario 3: Incident Assignment Flow**
```
1. Admin assigns incident #5 to "jane@example.com"
2. System updates: incident.assigned_to = jane's user ID
3. Jane can now:
   - See incident #5 in her incident list
   - Comment on incident #5
   - View incident #5 details
4. Jane cannot:
   - Edit incident #5 (unless she created it)
   - Assign incident #5 to others
   - Update incident #5 status
```

### **Scenario 4: Comment Authorization**
```
Incident #10 created by "bob@example.com"
Incident #10 assigned to "alice@example.com"

Who can comment?
✅ bob@example.com (creator)
✅ alice@example.com (assigned user)
✅ admin@example.com (admin)
❌ john@example.com (unrelated user)

Code check:
if (user.role === 'admin' || 
    user.id === incident.user_id || 
    user.id === incident.assigned_to) {
    // Allow comment
}
```

### **Scenario 5: Edit Authorization**
```
Incident #15 created by "sarah@example.com"
Incident #15 assigned to "mike@example.com"

Who can edit?
✅ sarah@example.com (creator)
✅ admin@example.com (admin)
❌ mike@example.com (assigned user only)
❌ john@example.com (unrelated user)

Code check:
if (user.role === 'admin' || user.id === incident.user_id) {
    // Allow edit
}
```

---

## Workflow Diagrams

### Incident Creation Workflow
```
User Login → Dashboard → Report Incident → Fill Form → Submit → 
Incident Created → Redirect to List → Admin Notification
```

### Incident Assignment Workflow
```
Admin Login → View Incidents → Select Incident → Assign to User → 
User Notification → User Can Comment → Status Updates
```

### Comment Workflow
```
User Views Incident → Check Authorization → Show Comment Form → 
Add Comment → Save to Database → Display in Comments List
```

---

## Deployment Guide

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/PostgreSQL/SQLite
- Web server (Apache/Nginx)

### Installation Steps
1. Clone repository
2. Install dependencies: `composer install`
3. Configure environment: Copy `.env.example` to `.env`
4. Generate application key: `php artisan key:generate`
5. Configure database in `.env`
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Set up web server to point to `public/` directory

### Environment Configuration
```env
APP_NAME="Incident Tracking System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=incident_tracker
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Production Considerations
- Set `APP_DEBUG=false`
- Use HTTPS in production
- Configure proper file permissions
- Set up database backups
- Configure log rotation
- Use queue workers for heavy tasks
- Implement monitoring and alerting

---

## Maintenance & Support

### Regular Tasks
- Database backups
- Log file rotation
- Security updates
- Performance monitoring
- User access reviews

### Troubleshooting
- Check Laravel logs in `storage/logs/`
- Verify database connections
- Check file permissions
- Monitor server resources
- Review error logs

### Support Contacts
- Development Team: [Contact Information]
- System Administrator: [Contact Information]
- Security Team: [Contact Information]

---

**Document Version**: 1.0  
**Last Updated**: September 2025  
**Maintained By**: Development Team
