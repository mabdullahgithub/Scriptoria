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
                'content' => 'Laravel is a powerful PHP framework that makes web development enjoyable and creative. In this article, we will explore the basics of Laravel and how to get started with your first project.',
                'status' => ArticleStatus::PUBLISHED,
            ],
            [
                'title' => 'Understanding PHP 8 Features',
                'content' => 'PHP 8 introduced many exciting features including named arguments, union types, and the match expression. Let\'s dive deep into these new capabilities.',
                'status' => ArticleStatus::PUBLISHED,
            ],
            [
                'title' => 'Building REST APIs with Laravel',
                'content' => 'RESTful APIs are essential for modern web applications. This comprehensive guide will show you how to build robust APIs using Laravel.',
                'status' => ArticleStatus::PENDING_REVIEW,
            ],
            [
                'title' => 'Database Optimization Techniques',
                'content' => 'Learn how to optimize your database queries and improve the performance of your Laravel applications.',
                'status' => ArticleStatus::PENDING_REVIEW,
            ],
            [
                'title' => 'Vue.js Integration with Laravel',
                'content' => 'Discover how to seamlessly integrate Vue.js with Laravel to create dynamic and interactive user interfaces.',
                'status' => ArticleStatus::DRAFT,
            ],
            [
                'title' => 'Testing in Laravel',
                'content' => 'Writing tests is crucial for maintaining code quality. Learn how to implement comprehensive testing in your Laravel projects.',
                'status' => ArticleStatus::REJECTED,
            ],
        ];

        foreach ($articles as $articleData) {
            Article::create([
                'user_id' => $writers->random()->id,
                'title' => $articleData['title'],
                'content' => $articleData['content'],
                'status' => $articleData['status'],
            ]);
        }
    }
}