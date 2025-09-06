# Scriptoria - Modern Content Management System

**Deadline: 24 Hours**

Scriptoria is a sophisticated content management system built with Laravel 11, featuring a modern glassmorphism design and comprehensive article management capabilities. The system provides role-based access control with separate interfaces for writers and administrators.

## Live Demo

**URL:** [scriptorianavico.ranktriz.com](https://scriptorianavico.ranktriz.com)

**Test Credentials:**
- **Writer:** writer@navicosoft.com | password: password
- **Admin:** admin@navicosoft.com | password: password

## Live Database Access

**Database Management Interface:** [https://auth-db1941.hstgr.io/index.php?db=u408040054_scriptoria](https://auth-db1941.hstgr.io/index.php?db=u408040054_scriptoria)

**Database Credentials:**
- **Database Name:** u408040054_scriptoria
- **Username:** u408040054_scriptoria
- **Password:** kyzmo8-cohwig-Gacvab

This provides direct access to the live production database for inspection and verification of the application's data structure and content.

## Technical Architecture

### Backend Implementation

#### Enumerations
- **ArticleStatus** - Manages article lifecycle states (Draft, Pending Review, Published, Rejected)
- **HttpStatus** - Standardizes HTTP response codes across the application

#### Traits Implementation
- **ApiExceptionHandler** - Centralized exception handling for API responses
- **ApiResponses** - Standardized JSON response formatting
- **DatabaseTransaction** - Ensures data integrity with automatic rollback capabilities

#### Event-Driven Architecture
- **ArticleSubmitted** - Triggered when articles are submitted for review
- **Event Listeners** - LogArticleSubmission, LogLoginActivity for comprehensive audit trails
- **Login Activity Tracking** - Automatic logging of all user authentication events
  - Records user IP addresses, login timestamps, and session details
  - Stored in dedicated `login_activities` database table
  - Enables security monitoring and user activity analysis
  - Provides comprehensive audit trail for admin oversight

#### Public API Endpoints
The system exposes a robust public API with the following endpoints:

- `GET /api/articles` - Retrieve paginated article listings
- `GET /api/articles/{id}` - Fetch individual article details

All API responses follow consistent JSON formatting with proper HTTP status codes and error handling.

### Frontend Architecture

#### Blade Template System
- **Modular Components** - Reusable breadcrumb, navigation, and form components
- **Layout Inheritance** - Clean separation of concerns with master layouts
- **Conditional Rendering** - Role-based UI elements and dynamic content display

#### Styling Architecture
- **External CSS Files** - Separated CSS for maintainability and performance
- **Glassmorphism Design** - Modern UI with backdrop filters and transparent elements
- **Component-Based Styling** - Individual CSS files for specific components
- **Responsive Design** - Mobile-first approach with progressive enhancement

#### JavaScript Implementation
- **External JS Files** - Modular JavaScript for specific functionalities
- **Interactive Elements** - Glow cursor effects, form validation, and dynamic interactions
- **Performance Optimized** - Minimal JavaScript footprint with efficient event handling

### Design Techniques

#### Visual Design
- **Color Scheme** - Primary orange (#fe7f2d) with dark gradient backgrounds
- **Typography** - System fonts with carefully crafted hierarchy
- **Glassmorphism Effects** - Backdrop blur, transparency, and subtle borders
- **Micro-Interactions** - Hover effects, transitions, and animated elements

#### User Experience
- **Intuitive Navigation** - Clear breadcrumbs and contextual menus
- **Status Indicators** - Visual article status badges and progress indicators
- **Responsive Layout** - Seamless experience across all device sizes
- **Accessibility** - Semantic HTML and keyboard navigation support

## Features

### Writer Dashboard
- Article creation with dual-action workflow (Save as Draft / Submit for Review)
- Personal article management and editing capabilities
- Real-time statistics and progress tracking
- Modern glassmorphism interface with responsive design

### Admin Dashboard
- Comprehensive article review and management system
- User activity monitoring and audit trails
- **Login Activity Tracking** - Complete database logging of user authentication events
- System statistics and performance metrics
- Advanced filtering and search capabilities
- Real-time security monitoring with IP address tracking

### Public Interface
- Clean article browsing with search functionality
- Individual article pages with reading time estimates
- SEO-optimized URLs and meta tags
- Mobile-responsive design

### Security Features
- **Comprehensive Login Activity Logging** - Every user authentication is recorded in database
- **IP Address Tracking** - Monitor user locations and detect suspicious activity
- **Session Management** - Secure session handling with automatic timeout
- **Event-Driven Security Auditing** - Real-time logging of critical system events
- **Role-Based Access Control** - Strict permission system for writers and administrators

## Installation Guide

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and NPM
- SQLite (default) or MySQL/PostgreSQL

### Step 1: Clone Repository
```bash
git clone https://github.com/mabdullahgithub/Scriptoria.git
cd Scriptoria
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
```bash
# Run migrations and seeders
php artisan migrate --seed

# This will create:
# - Database tables
# - Admin user (admin@navicosoft.com)
# - Writer users (writer@navicosoft.com)
# - Sample articles
```

### Step 5: Asset Compilation
```bash
# Compile frontend assets
npm run build

# For development
npm run dev
```

### Step 6: Start Development Server
```bash
php artisan serve
```

Access the application at `http://localhost:8000`

### Step 7: Storage Configuration
```bash
# Create storage symlink
php artisan storage:link
```
## Development Standards

### Code Organization
- PSR-4 autoloading standards
- Laravel naming conventions
- Comprehensive error handling
- Database transaction management

### Security Implementation
- CSRF protection on all forms
- SQL injection prevention through Eloquent ORM
- Role-based access control

### Performance Optimization
- Efficient database queries with proper indexing
- Asset minification and compression
- Lazy loading for improved page speeds
- Caching strategies for frequently accessed data

## Technology Stack

- **Backend:** Laravel 11, PHP 8.3
- **Database:** MySQL
- **Frontend:** Blade Templates, Modern CSS
- **Design:** Glassmorphism UI, Responsive Design
- **Tools:** Composer, NPM

## Contributing

This project follows Laravel best practices and maintains high code quality standards. All contributions should include proper documentation and testing.

## License

This project is developed as part of a technical assessment and follows standard software development practices.
