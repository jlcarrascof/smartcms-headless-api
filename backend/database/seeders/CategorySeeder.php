<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories table with sample data for development and testing.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Technology',
                'slug'        => 'technology',
                'description' => 'Articles about software development, AI, and tech trends.',
            ],
            [
                'name'        => 'Laravel',
                'slug'        => 'laravel',
                'description' => 'Tutorials, tips, and best practices for Laravel developers.',
            ],
            [
                'name'        => 'Vue.js',
                'slug'        => 'vuejs',
                'description' => 'Frontend development with Vue 3 and the Composition API.',
            ],
            [
                'name'        => 'DevOps',
                'slug'        => 'devops',
                'description' => 'Docker, CI/CD pipelines, cloud deployments, and infrastructure.',
            ],
            [
                'name'        => 'Career',
                'slug'        => 'career',
                'description' => 'Job hunting tips, portfolio advice, and developer growth.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
