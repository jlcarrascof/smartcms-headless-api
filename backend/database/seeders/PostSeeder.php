<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Seed the posts table with sample data for development and testing.
     */
    public function run(): void
    {
        // Retrieve admin user seeded previously to assign as post author
        $admin = User::where('email', 'admin@smartcms.com')->first();

        $posts = [
            [
                'title'        => 'Getting Started with Laravel 11',
                'slug'         => 'getting-started-laravel-11',
                'content'      => 'Laravel 11 introduces a streamlined application structure, new Artisan commands, and improved performance. In this post we walk through setting up a fresh project and exploring the new directory layout.',
                'excerpt'      => 'A practical introduction to Laravel 11 and its new features.',
                'status'       => 'published',
                'category_id'  => 2, // Laravel
                'user_id'      => $admin->id,
                'published_at' => now()->subDays(10),
            ],
            [
                'title'        => 'Building REST APIs with JWT Authentication',
                'slug'         => 'rest-api-jwt-authentication',
                'content'      => 'Securing your Laravel API with JWT tokens is a professional-grade approach. We cover installing tymon/jwt-auth, configuring guards, and protecting your routes with stateless authentication.',
                'excerpt'      => 'Step-by-step guide to add JWT authentication to your Laravel REST API.',
                'status'       => 'published',
                'category_id'  => 2, // Laravel
                'user_id'      => $admin->id,
                'published_at' => now()->subDays(8),
            ],
            [
                'title'        => 'Vue 3 Composition API: A Complete Guide',
                'slug'         => 'vue-3-composition-api-guide',
                'content'      => 'The Composition API changes how we organize Vue logic. Using script setup, ref, computed, and composables, we can build clean, reusable, and testable components for any scale.',
                'excerpt'      => 'Master the Vue 3 Composition API with practical examples.',
                'status'       => 'published',
                'category_id'  => 3, // Vue.js
                'user_id'      => $admin->id,
                'published_at' => now()->subDays(6),
            ],
            [
                'title'        => 'Docker for PHP Developers: A Practical Setup',
                'slug'         => 'docker-php-developers-setup',
                'content'      => 'Docker removes the "works on my machine" problem forever. We configure a multi-container setup with PHP 8.3, Nginx, and MySQL using Docker Compose, ready for both local development and production.',
                'excerpt'      => 'Set up a professional Docker environment for your PHP projects.',
                'status'       => 'published',
                'category_id'  => 4, // DevOps
                'user_id'      => $admin->id,
                'published_at' => now()->subDays(4),
            ],
            [
                'title'        => 'How to Build a CMS Portfolio Project that Impresses Recruiters',
                'slug'         => 'cms-portfolio-project-recruiters',
                'content'      => 'A headless CMS project combines Laravel, Vue 3, JWT, Cloudinary, and proper Git workflow in one portfolio piece. Here is how to approach it strategically to maximize interview impact.',
                'excerpt'      => 'Build a portfolio CMS project that stands out to senior developers.',
                'status'       => 'published',
                'category_id'  => 5, // Career
                'user_id'      => $admin->id,
                'published_at' => now()->subDays(2),
            ],
            [
                'title'        => 'Pinia vs Vuex: State Management in Vue 3',
                'slug'         => 'pinia-vs-vuex-vue-3',
                'content'      => 'Pinia is now the official state manager for Vue 3. Compared to Vuex, it offers a simpler API, full TypeScript support, and native DevTools integration. We compare both with real examples.',
                'excerpt'      => 'Should you use Pinia or Vuex in your next Vue 3 project?',
                'status'       => 'published',
                'category_id'  => 3, // Vue.js
                'user_id'      => $admin->id,
                'published_at' => now()->subDay(),
            ],
            [
                'title'        => 'Cloudinary Integration with Laravel: Upload and Transform Images',
                'slug'         => 'cloudinary-laravel-image-upload',
                'content'      => 'Cloudinary handles image storage, resizing, format conversion, and CDN delivery. In this tutorial we install the Laravel SDK, configure our credentials, and build an upload endpoint with automatic transformations.',
                'excerpt'      => 'Add professional image management to your Laravel app with Cloudinary.',
                'status'       => 'draft',
                'category_id'  => 2, // Laravel
                'user_id'      => $admin->id,
                'published_at' => null,
            ],
            [
                'title'        => 'Tailwind CSS v4: What Changed and How to Migrate',
                'slug'         => 'tailwind-css-v4-changes-migration',
                'content'      => 'Tailwind CSS v4 is a complete rebuild with a new engine, zero-config setup, and CSS-native variables. We review the key changes and walk through migrating a Vue 3 project from v3.',
                'excerpt'      => 'Everything you need to know about Tailwind CSS version 4.',
                'status'       => 'draft',
                'category_id'  => 1, // Technology
                'user_id'      => $admin->id,
                'published_at' => null,
            ],
            [
                'title'        => 'CI/CD Pipeline with GitHub Actions for Laravel',
                'slug'         => 'cicd-github-actions-laravel',
                'content'      => 'Automating your tests, linting, and deployment with GitHub Actions turns your repository into a professional workflow. We build a pipeline that runs Pest tests on every pull request and deploys to Railway on merge.',
                'excerpt'      => 'Automate your Laravel deployment pipeline with GitHub Actions.',
                'status'       => 'draft',
                'category_id'  => 4, // DevOps
                'user_id'      => $admin->id,
                'published_at' => null,
            ],
            [
                'title'        => 'Understanding OpenAPI and Swagger in Laravel',
                'slug'         => 'openapi-swagger-laravel',
                'content'      => 'L5-Swagger generates interactive API documentation from PHP attributes directly in your controllers. We cover the annotation syntax, how to secure endpoints with bearerAuth, and how to publish and share your docs.',
                'excerpt'      => 'Auto-generate professional API documentation with L5-Swagger.',
                'status'       => 'published',
                'category_id'  => 2, // Laravel
                'user_id'      => $admin->id,
                'published_at' => now(),
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
