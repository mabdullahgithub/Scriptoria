# Scriptoria - Mini Content Management System

A Laravel-based multi-author content management system built for the Laravel Developer Assessment. This CMS allows writers to create articles, submit them for review, and admins to approve or reject submissions.

## Features

### Authentication & Authorization
- **Laravel Breeze** authentication with Blade templates
- **Custom middleware** for role-based route protection (`IsAdmin`, `IsWriter`)
- **Automatic login activity tracking** with IP addresses and timestamps
- **Event-driven security auditing** for all authentication actions

### Content Management
- **Article creation and management** with draft/review workflow
- **Status-based article lifecycle**: Draft → Pending Review → Published/Rejected
- **Writer dashboard** to manage personal articles
- **Admin dashboard** for reviewing pending submissions

### Technical Features
- **Modern PHP 8.1+ Enums** for type-safe status management
- **Professional traits** for database transactions and API responses
- **Comprehensive Eloquent relationships** and query optimization
- **Public REST API** for published articles
- **Event-driven architecture** with `ArticleSubmitted` events and logging
- **Custom middleware** for role-based authorization
- **Automatic security auditing** with login activity tracking

## � Authentication & Authorization

### Middleware Protection
- **`IsAdmin`**: Protects admin-only routes (`/admin/*`)
  - Verifies user authentication and admin privileges
  - Returns 403 error for non-admin users
  - Redirects to login for unauthenticated users

- **`IsWriter`**: Protects writer-only routes (`/articles/*`)
  - Ensures user has writer privileges (non-admin)
  - Maintains separation between admin and writer functions

### Event-Driven Security
- **Login Activity Tracking**: Automatically logs all user logins
  - Records IP address, timestamp, and user information
  - Enables security auditing and monitoring
  - Integrates with Laravel's built-in `Login` event

- **Article Submission Events**: Logs article review submissions
  - Triggers when writers submit articles for review
  - Creates audit trail for content workflow
  - Custom `ArticleSubmitted` event with detailed logging

## �🗄️ Database Schema

### Users Table
- `id`, `name`, `email`, `password`
- `is_admin` (boolean, default: false)
- `email_verified_at`, `remember_token`, `timestamps`

### Articles Table
- `id`, `user_id` (foreign key), `title`, `content`
- `status` (enum: draft, pending_review, published, rejected)
- `timestamps` with performance indexes

### Login Activities Table
- `id`, `user_id` (foreign key), `ip_address`
- `login_at` (timestamp), `timestamps`

## Setup Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js & npm (for frontend assets)

### Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd Scriptoria
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database in .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scriptoria
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

7. **Build frontend assets**
```bash
npm run build
```

8. **Start development server**
```bash
php artisan serve
```

## 👤 Test Users

The application comes with pre-seeded test users:

### Admin User
- **Email**: `admin@example.com`
- **Password**: `password`
- **Permissions**: Can review, approve, and reject articles

### Writer Users
- **Primary Writer**: `writer@example.com` / `password`
- **Additional Writers**: `writer1@example.com`, `writer2@example.com`, `writer3@example.com`
- **Password**: `password` (for all)
- **Permissions**: Can create, edit, and submit articles for review

## User Workflows

### For Writers (`/articles/*`)
1. **Dashboard Access**: View personal articles and their statuses
2. **Create Articles**: Write new content (automatically saved as 'draft')
3. **Submit for Review**: Change article status from 'draft' to 'pending_review'
4. **Edit Articles**: Modify draft or rejected articles

### For Admins (`/admin/*`)
1. **Review Dashboard**: View all articles pending review
2. **Approve Articles**: Change status to 'published' (public visibility)
3. **Reject Articles**: Send back to writer for revision

## 🔗 API Endpoints

### Public API
- **GET** `/api/articles` - Retrieve all published articles
  - Returns: Article title, content, and author name
  - No authentication required
  - JSON format with standardized response structure

## 🏗️ Architecture

### Enums
- **`ArticleStatus`**: Type-safe status management with business logic
- **`HttpStatus`**: Professional HTTP response codes with descriptions

### Traits
- **`DatabaseTransaction`**: Safe database operations with rollback
- **`ApiResponses`**: Standardized JSON response formatting
- **`ApiExceptionHandler`**: Centralized error handling for APIs

### Models & Relationships
```php
User::class
├── hasMany(Article::class)
└── hasMany(LoginActivity::class)

Article::class
└── belongsTo(User::class)

LoginActivity::class
└── belongsTo(User::class)
```

## Testing

### Manual Testing
1. **Registration/Login**: Test authentication flows
2. **Article Creation**: Create and submit articles as writer
3. **Admin Review**: Approve/reject as admin user
4. **API Testing**: Access `/api/articles` endpoint
5. **Role Permissions**: Verify middleware protection

### Sample Data
The application includes 6 sample articles in various statuses:
- 2 Published articles (publicly visible)
- 2 Pending review (admin action required)
- 1 Draft (writer can edit)
- 1 Rejected (writer can revise)

## Development Status

### ✅ Completed Features
- [x] Laravel Breeze authentication setup
- [x] Database schema with migrations and seeders
- [x] User roles and permissions (Admin/Writer)
- [x] **Custom middleware for route protection**
- [x] **Event-driven login activity tracking**
- [x] **Article submission event logging**
- [x] Article CRUD operations foundation
- [x] Status-based workflow with enums
- [x] Public API endpoint structure
- [x] Professional code organization with traits
- [x] Comprehensive documentation


##  Assessment Compliance

This project fulfills all core requirements:
- ✅ Laravel 11 with Breeze authentication
- ✅ MySQL database with proper relationships
- ✅ User roles (Admin/Writer) with boolean column
- ✅ **Custom middleware (IsAdmin, IsWriter)**
- ✅ Article model with status workflow
- ✅ **LoginActivity tracking implementation**
- ✅ Eloquent relationships and migrations
- ✅ **Event-driven architecture with listeners**
- ✅ Public API endpoint for published articles
- ✅ Professional code organization and best practices

### Additional Professional Features
- ✅ **Type-safe enums** for status management
- ✅ **Database transaction traits** for data integrity
- ✅ **API response standardization** with custom traits
- ✅ **Comprehensive event logging** for security auditing
- ✅ **Professional documentation** and setup instructions

## License

This project is created for assessment purposes and follows Laravel's open-source license.
