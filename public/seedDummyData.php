<?php

// This script should be deleted after use - it's for development purposes only!
// Access this via: http://your-domain.com/seedDummyData.php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

use Database\Seeders\DummyPostsSeeder;
use Database\Seeders\DummyGalleriesSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Tag;

try {
    // Create a kernel instance and handle the request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);

    echo "<h1>Dummy Data Seeder</h1>";
    echo "<p>Running seeders to generate dummy data for blog posts and galleries...</p>";
    
    // Create directory structure
    $placeholderDir = storage_path('app/public/placeholders');
    if (!File::exists($placeholderDir)) {
        File::makeDirectory($placeholderDir, 0755, true);
        echo "<p>Created placeholders directory at: {$placeholderDir}</p>";
    } else {
        echo "<p>Placeholders directory already exists at: {$placeholderDir}</p>";
    }
    
    // Run seeders
    try {
        // Create test categories and tags if they don't exist
        echo "<h2>Creating Categories and Tags</h2>";
        Category::firstOrCreate(['slug' => 'web-development'], ['name' => 'Web Development']);
        Category::firstOrCreate(['slug' => 'design'], ['name' => 'Design']);
        Category::firstOrCreate(['slug' => 'photography'], ['name' => 'Photography']);
        Tag::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel']);
        Tag::firstOrCreate(['slug' => 'php'], ['name' => 'PHP']);
        Tag::firstOrCreate(['slug' => 'tailwind'], ['name' => 'Tailwind CSS']);
        Tag::firstOrCreate(['slug' => 'javascript'], ['name' => 'JavaScript']);
        echo "<p>Categories and tags created successfully</p>";
        
        echo "<h2>Running Post Seeder</h2>";
        $postSeeder = new DummyPostsSeeder();
        $postSeeder->run();
        echo "<p>Post seeder completed successfully</p>";
    } catch (Exception $e) {
        echo "<p>Error running post seeder: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    try {
        echo "<h2>Running Gallery Seeder</h2>";
        $gallerySeeder = new DummyGalleriesSeeder();
        $gallerySeeder->run();
        echo "<p>Gallery seeder completed successfully</p>";
    } catch (Exception $e) {
        echo "<p>Error running gallery seeder: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    // Create storage link if it doesn't exist
    if (!file_exists(public_path('storage'))) {
        echo "<p>Creating storage link...</p>";
        try {
            // Check if Windows or Unix-like OS
            if (PHP_OS_FAMILY === 'Windows') {
                // Use built-in symlink function for Windows
                $target = storage_path('app/public');
                $link = public_path('storage');
                symlink($target, $link);
            } else {
                symlink('../storage/app/public', './storage');
            }
            echo "<p>Storage link created</p>";
        } catch (Exception $e) {
            echo "<p>Error creating storage link: " . $e->getMessage() . "</p>";
            echo "<p>Please run <code>php artisan storage:link</code> manually from the command line.</p>";
        }
    } else {
        echo "<p>Storage link already exists</p>";
    }
    
    echo "<h2>All Done!</h2>";
    echo "<p>Dummy data has been generated. You can now view posts and galleries.</p>";
    echo "<p><strong>IMPORTANT:</strong> Please delete this file (public/seedDummyData.php) for security reasons.</p>";
    
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} 