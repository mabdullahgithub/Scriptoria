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

        // Sample articles in different statuses
        $articles = [
            [
                'title' => 'Getting Started with Laravel',
                'content' => 'Laravel is a powerful PHP framework that makes web development enjoyable and creative. In this article, we will explore the basics of Laravel and how to get started with your first project. We\'ll cover installation, routing, controllers, views, and basic database operations.',
                'excerpt' => 'Laravel is a powerful PHP framework that makes web development enjoyable and creative. Learn the basics and get started with your first project.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Understanding PHP 8 Features',
                'content' => 'PHP 8 introduced many exciting features including named arguments, union types, and the match expression. Let\'s dive deep into these new capabilities and see how they can improve your code quality and development experience.',
                'excerpt' => 'PHP 8 introduced many exciting features including named arguments, union types, and the match expression. Explore these new capabilities.',
                'status' => ArticleStatus::PUBLISHED,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Building REST APIs with Laravel',
                'content' => 'RESTful APIs are essential for modern web applications. This comprehensive guide will show you how to build robust APIs using Laravel, including authentication, validation, and proper error handling.',
                'excerpt' => 'RESTful APIs are essential for modern web applications. This comprehensive guide shows you how to build robust APIs using Laravel.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Database Optimization Techniques',
                'content' => 'Learn how to optimize your database queries and improve the performance of your Laravel applications. We\'ll cover indexing, query optimization, and caching strategies.',
                'excerpt' => 'Learn how to optimize your database queries and improve the performance of your Laravel applications.',
                'status' => ArticleStatus::PENDING_REVIEW,
                'published_at' => null,
            ],
            [
                'title' => 'Vue.js Integration with Laravel',
                'content' => 'Discover how to seamlessly integrate Vue.js with Laravel to create dynamic and interactive user interfaces. This guide covers setup, component creation, and API integration.',
                'excerpt' => 'Discover how to seamlessly integrate Vue.js with Laravel to create dynamic and interactive user interfaces.',
                'status' => ArticleStatus::DRAFT,
                'published_at' => null,
            ],
            [
                'title' => 'Testing in Laravel',
                'content' => 'Writing tests is crucial for maintaining code quality. Learn how to implement comprehensive testing in your Laravel projects using PHPUnit and Laravel\'s testing tools.',
                'excerpt' => 'Writing tests is crucial for maintaining code quality. Learn comprehensive testing in Laravel projects.',
                'status' => ArticleStatus::REJECTED,
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