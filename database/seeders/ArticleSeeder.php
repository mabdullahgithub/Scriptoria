<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use App\Enums\ArticleStatus;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $writers = User::where('is_admin', false)->get();

        if ($writers->count() === 0) {
            return;
        }

        // 20 Sample articles
        $articles = [
            [
                'title' => 'Getting Started with Laravel',
                'content' => "Laravel is a powerful PHP framework that simplifies backend development. It provides expressive syntax, built-in tools for routing, authentication, caching, and a vast ecosystem of packages. Beginners can quickly scaffold projects and focus on business logic instead of boilerplate code.\n\nIn this article, we’ll walk through Laravel installation, environment setup, and building your first route, controller, and view. You’ll also learn how to set up migrations to manage databases effectively.",
                'excerpt' => 'Learn the basics of Laravel including installation, routing, controllers, and migrations.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Understanding PHP 8 Features',
                'content' => "PHP 8 introduced groundbreaking improvements such as named arguments, union types, attributes, match expressions, and Just-In-Time (JIT) compilation. These features bring PHP closer to strongly typed languages, enhancing performance and developer experience.\n\nFor example, named arguments allow you to pass parameters by name rather than position, improving code readability. The match expression provides a cleaner alternative to switch-case statements. This article breaks down these features with practical examples.",
                'excerpt' => 'Explore PHP 8 features like named arguments, union types, attributes, and JIT.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Building REST APIs with Laravel',
                'content' => "APIs are the backbone of modern applications. Laravel makes it seamless to develop RESTful APIs with features like resource routing, request validation, and built-in authentication.\n\nIn this guide, we’ll create a simple task management API. We’ll cover route definitions, controller methods, Eloquent models, and JSON responses. Additionally, we’ll look at securing endpoints with Laravel Sanctum and handling errors gracefully.",
                'excerpt' => 'Step-by-step guide to building and securing RESTful APIs with Laravel.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Database Optimization Techniques',
                'content' => "Databases are often the bottleneck in large-scale applications. Optimizing queries and schemas can drastically improve performance.\n\nThis article explains indexing strategies, query optimization tips, caching techniques using Redis, and database normalization. We’ll also discuss Laravel’s query builder performance tricks such as eager loading relationships to prevent the N+1 query problem.",
                'excerpt' => 'Optimize your Laravel database with indexing, caching, and query improvements.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Vue.js Integration with Laravel',
                'content' => "Laravel and Vue.js make a perfect pair for building interactive full-stack applications. Laravel handles backend logic while Vue.js takes care of the frontend reactivity.\n\nWe’ll start with Laravel Mix for compiling Vue components, then build a sample SPA that consumes a Laravel API. You’ll also learn about event broadcasting with Laravel Echo and real-time updates using WebSockets.",
                'excerpt' => 'Learn how to integrate Vue.js with Laravel for dynamic applications.',
                'status' => ArticleStatus::DRAFT,
                'published_at' => null,
            ],
            [
                'title' => 'Testing in Laravel',
                'content' => "Testing ensures code quality and prevents regressions. Laravel provides PHPUnit and a dedicated testing suite out of the box.\n\nWe’ll cover writing unit tests for models, feature tests for routes, and integration tests for APIs. Additionally, you’ll see how Laravel’s database testing helpers make it easy to refresh migrations and seed test data.",
                'excerpt' => 'A guide to writing unit, feature, and integration tests in Laravel.',
                'status' => ArticleStatus::REJECTED,
                'published_at' => null,
            ],
            [
                'title' => 'Event Broadcasting in Laravel',
                'content' => "Real-time applications like chat apps and notifications depend on event broadcasting. Laravel simplifies this with built-in drivers like Pusher, Ably, and Redis.\n\nWe’ll implement an example where users send messages in real-time. You’ll learn about events, listeners, broadcasting channels, and frontend integration using Laravel Echo.",
                'excerpt' => 'Build real-time features with Laravel broadcasting and Echo.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Mastering Eloquent Relationships',
                'content' => "Laravel’s Eloquent ORM provides powerful relationship management: one-to-one, one-to-many, many-to-many, and polymorphic associations.\n\nThis article explores how to set up and query relationships efficiently. You’ll also learn about eager loading, pivot tables, and advanced filtering within relationships.",
                'excerpt' => 'Master Laravel Eloquent relationships with practical use cases.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Laravel Queues and Jobs',
                'content' => "Background jobs improve performance by offloading heavy tasks like sending emails, processing uploads, or running reports. Laravel’s queue system supports multiple drivers like database, Redis, and Amazon SQS.\n\nWe’ll create a queue job to process image uploads asynchronously and explore Laravel Horizon for monitoring.",
                'excerpt' => 'Learn to implement background jobs and queues in Laravel.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Authentication with Laravel Breeze and Jetstream',
                'content' => "Laravel offers authentication scaffolding via Breeze and Jetstream. Breeze provides a simple starter kit, while Jetstream offers advanced features like teams and profile management.\n\nWe’ll set up both, compare their differences, and customize them for real-world projects.",
                'excerpt' => 'Implement authentication using Laravel Breeze and Jetstream.',
                'status' => ArticleStatus::DRAFT,
                'published_at' => null,
            ],
            [
                'title' => 'Caching Strategies in Laravel',
                'content' => "Caching is critical for performance optimization. Laravel supports multiple caching backends like Redis, Memcached, and file cache.\n\nThis article explains query caching, route caching, and full-page caching. You’ll also see practical examples of caching API responses.",
                'excerpt' => 'Speed up your Laravel apps with effective caching strategies.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Deploying Laravel Applications on AWS',
                'content' => "AWS provides scalable infrastructure for hosting Laravel applications. We’ll walk through setting up an EC2 instance, configuring Nginx, PHP-FPM, and MySQL, and deploying your Laravel code.\n\nWe’ll also discuss using S3 for storage, RDS for databases, and CloudFront for CDN integration.",
                'excerpt' => 'Step-by-step deployment guide for Laravel apps on AWS.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Laravel Middleware Explained',
                'content' => "Middleware filters HTTP requests entering your application. They are essential for authentication, logging, throttling, and more.\n\nIn this article, we’ll create custom middleware for role-based access control and logging request data.",
                'excerpt' => 'Learn to build and apply custom middleware in Laravel.',
                'status' => ArticleStatus::REJECTED,
                'published_at' => null,
            ],
            [
                'title' => 'Debugging Laravel Applications',
                'content' => "Debugging saves hours of development time. Laravel integrates with tools like Laravel Telescope, Ray, and Debugbar for deep insights.\n\nWe’ll demonstrate using these tools to debug queries, requests, and exceptions effectively.",
                'excerpt' => 'Use Telescope, Ray, and Debugbar to debug Laravel applications.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'File Uploads in Laravel',
                'content' => "Handling file uploads is a common requirement. Laravel provides simple APIs for validating, storing, and retrieving files.\n\nWe’ll build a feature where users upload profile pictures, validate file types, and store them on local or cloud disks.",
                'excerpt' => 'Learn file validation and storage techniques in Laravel.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Building a Blog with Laravel',
                'content' => "A blog is a great starter project. Laravel provides everything needed: authentication, CRUD, and database migrations.\n\nWe’ll create a simple blog system where users can publish articles, manage categories, and leave comments.",
                'excerpt' => 'Step-by-step tutorial on building a blog in Laravel.',
                'status' => ArticleStatus::DRAFT,
                'published_at' => null,
            ],
            [
                'title' => 'Laravel and React Integration',
                'content' => "React offers powerful UI rendering capabilities. When combined with Laravel APIs, you get a robust full-stack framework.\n\nWe’ll build a sample project where Laravel provides JSON APIs and React handles frontend rendering with hooks and context.",
                'excerpt' => 'Integrate React with Laravel APIs for full-stack development.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'Securing Laravel Applications',
                'content' => "Security is critical in web applications. Laravel comes with CSRF protection, hashed passwords, and input sanitization.\n\nWe’ll enhance these by adding role-based authorization, encrypting sensitive data, and securing API endpoints.",
                'excerpt' => 'Implement best practices for securing Laravel applications.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Working with Laravel Collections',
                'content' => "Laravel collections provide a fluent, convenient wrapper for arrays of data. They allow method chaining and powerful transformations.\n\nWe’ll cover methods like filter, map, reduce, and groupBy with real-world examples.",
                'excerpt' => 'Master Laravel collections with powerful methods and chaining.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(40),
            ],
            [
                'title' => 'Task Scheduling in Laravel',
                'content' => "Laravel’s task scheduler lets you automate repetitive jobs using cron. Instead of writing raw cron syntax, you can define schedules fluently in code.\n\nWe’ll create scheduled tasks for sending reports, clearing logs, and database backups.",
                'excerpt' => 'Automate recurring tasks using Laravel’s scheduler.',
                'status' => ArticleStatus::DRAFT,
                'published_at' => null,
            ],
        ];

        foreach ($articles as $articleData) {
            Article::create([
                'user_id' => $writers->random()->id,
                'title' => $articleData['title'],
                'content' => $articleData['content'],
                'excerpt' => $articleData['excerpt'],
                'status' => $articleData['status'],
                'published_at' => $articleData['published_at'],
            ]);
        }
    }
}
