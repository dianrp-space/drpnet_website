<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GenerateDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy data for blog posts and galleries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating dummy data for blog posts and galleries...');
        
        // Run only our specific seeders
        $this->call('db:seed', [
            '--class' => 'Database\Seeders\DummyPostsSeeder'
        ]);
        
        $this->call('db:seed', [
            '--class' => 'Database\Seeders\DummyGalleriesSeeder'
        ]);
        
        // Create storage link if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            $this->info('Creating storage link...');
            $this->call('storage:link');
        }
        
        $this->info('Dummy data generation completed!');
        $this->info('You can now view the dummy blog posts and galleries.');
        
        return Command::SUCCESS;
    }
} 