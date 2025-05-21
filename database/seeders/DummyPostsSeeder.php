<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class DummyPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for posts
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::first();
        }
        
        // Get or create sample categories
        $category1 = Category::firstOrCreate(
            ['slug' => 'web-development'],
            ['name' => 'Web Development']
        );
        
        $category2 = Category::firstOrCreate(
            ['slug' => 'design'],
            ['name' => 'Design']
        );
        
        $category3 = Category::firstOrCreate(
            ['slug' => 'photography'],
            ['name' => 'Photography']
        );
        
        // Get or create sample tags
        $tag1 = Tag::firstOrCreate(
            ['slug' => 'laravel'],
            ['name' => 'Laravel']
        );
        
        $tag2 = Tag::firstOrCreate(
            ['slug' => 'php'],
            ['name' => 'PHP']
        );
        
        $tag3 = Tag::firstOrCreate(
            ['slug' => 'tailwind'],
            ['name' => 'Tailwind CSS']
        );
        
        $tag4 = Tag::firstOrCreate(
            ['slug' => 'javascript'],
            ['name' => 'JavaScript']
        );
        
        // Create dummy posts
        $posts = [
            [
                'title' => 'Getting Started with Laravel',
                'slug' => 'getting-started-with-laravel',
                'content' => "# Getting Started with Laravel\n\nLaravel is a powerful PHP framework that makes building web applications easier and faster. This post will guide you through setting up your first Laravel project.\n\n## Installation\n\nFirst, you need to have Composer installed. Then, you can create a new Laravel project using the following command:\n\n```bash\ncomposer create-project laravel/laravel example-app\n```\n\n## Basic Routing\n\nLaravel makes routing simple. Here's a basic example:\n\n```php\nRoute::get('/hello', function () {\n    return 'Hello, World!';\n});\n```\n\n## Conclusion\n\nLaravel provides a great foundation for building robust web applications with clean, expressive code.",
                'user_id' => $admin->id,
                'category_id' => $category1->id,
                'status' => 'publish',
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'Responsive Design with Tailwind CSS',
                'slug' => 'responsive-design-with-tailwind-css',
                'content' => "# Responsive Design with Tailwind CSS\n\nTailwind CSS is a utility-first CSS framework that makes it easy to create responsive designs without writing custom CSS.\n\n## Installation\n\nYou can add Tailwind CSS to your project using npm:\n\n```bash\nnpm install tailwindcss\n```\n\n## Responsive Utilities\n\nTailwind makes responsive design simple with breakpoint prefixes:\n\n```html\n<div class=\"w-full md:w-1/2 lg:w-1/3\">\n  <!-- This div will be full width on mobile, half width on medium screens, and one-third width on large screens -->\n</div>\n```\n\n## Dark Mode\n\nImplementing dark mode is also straightforward with Tailwind's dark mode utilities:\n\n```html\n<div class=\"bg-white dark:bg-gray-800 text-black dark:text-white\">\n  <!-- Content that automatically adjusts for dark mode -->\n</div>\n```",
                'user_id' => $admin->id,
                'category_id' => $category2->id,
                'status' => 'publish',
                'published_at' => now()->subDays(3)
            ],
            [
                'title' => 'Modern JavaScript Techniques',
                'slug' => 'modern-javascript-techniques',
                'content' => "# Modern JavaScript Techniques\n\nJavaScript has evolved significantly over the years. This post explores some modern techniques that can improve your code.\n\n## Destructuring\n\nDestructuring allows you to extract values from objects and arrays easily:\n\n```javascript\nconst { name, age } = person;\nconst [first, second] = array;\n```\n\n## Arrow Functions\n\nArrow functions provide a more concise syntax and lexical `this` binding:\n\n```javascript\nconst double = (x) => x * 2;\nconst items = [1, 2, 3].map(x => x * 2);\n```\n\n## Async/Await\n\nAsync/await makes asynchronous code more readable:\n\n```javascript\nasync function fetchData() {\n  try {\n    const response = await fetch('/api/data');\n    const data = await response.json();\n    return data;\n  } catch (error) {\n    console.error('Error fetching data:', error);\n  }\n}\n```",
                'user_id' => $admin->id,
                'category_id' => $category1->id,
                'status' => 'publish',
                'published_at' => now()->subDays(1)
            ],
            [
                'title' => 'Composition in Photography',
                'slug' => 'composition-in-photography',
                'content' => "# Composition in Photography\n\nGood composition is essential for creating compelling photographs. This post covers key composition techniques.\n\n## Rule of Thirds\n\nThe rule of thirds involves dividing your frame into nine equal segments and placing key elements along these lines or at their intersections.\n\n## Leading Lines\n\nLeading lines draw the viewer's eye toward the main subject or into the picture. Roads, paths, or natural features can all serve as leading lines.\n\n## Framing\n\nUsing elements within the scene to create a natural frame around your subject can add depth and focus attention on your main subject.\n\n## Perspective\n\nChanging your perspective (shooting from above, below, or at eye level) can dramatically alter the mood and impact of your photograph.",
                'user_id' => $admin->id,
                'category_id' => $category3->id,
                'status' => 'publish',
                'published_at' => now()->subDays(2)
            ],
            [
                'title' => 'Database Migrations in Laravel',
                'slug' => 'database-migrations-in-laravel',
                'content' => "# Database Migrations in Laravel\n\nMigrations provide a way to version your database schema and make it easy to share with your team.\n\n## Creating Migrations\n\n```bash\nphp artisan make:migration create_users_table\n```\n\n## Defining Tables\n\n```php\npublic function up()\n{\n    Schema::create('users', function (Blueprint $table) {\n        $table->id();\n        $table->string('name');\n        $table->string('email')->unique();\n        $table->timestamp('email_verified_at')->nullable();\n        $table->string('password');\n        $table->rememberToken();\n        $table->timestamps();\n    });\n}\n```\n\n## Running Migrations\n\n```bash\nphp artisan migrate\n```\n\n## Rolling Back\n\n```bash\nphp artisan migrate:rollback\n```",
                'user_id' => $admin->id,
                'category_id' => $category1->id,
                'status' => 'draft',
                'published_at' => null
            ],
        ];
        
        // Create posts and attach tags
        foreach ($posts as $postData) {
            $post = Post::create($postData);
            
            // Attach appropriate tags
            if (Str::contains($post->title, 'Laravel') || Str::contains($post->content, 'Laravel')) {
                $post->tags()->attach($tag1);
            }
            
            if (Str::contains($post->title, 'PHP') || Str::contains($post->content, 'PHP')) {
                $post->tags()->attach($tag2);
            }
            
            if (Str::contains($post->title, 'Tailwind') || Str::contains($post->content, 'Tailwind')) {
                $post->tags()->attach($tag3);
            }
            
            if (Str::contains($post->title, 'JavaScript') || Str::contains($post->content, 'JavaScript')) {
                $post->tags()->attach($tag4);
            }
        }
    }
} 