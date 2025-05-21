<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DummyGalleriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for galleries
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::first();
        }
        
        // Create dummy galleries
        $galleries = [
            [
                'title' => 'Nature Photography',
                'slug' => 'nature-photography',
                'description' => 'A collection of beautiful nature photographs showcasing landscapes, wildlife, and natural phenomena.',
                'user_id' => $admin->id,
                'photos' => [
                    [
                        'caption' => 'Mountain Sunset',
                        'description' => 'Beautiful sunset over mountain ranges'
                    ],
                    [
                        'caption' => 'Forest Stream',
                        'description' => 'Peaceful stream running through a dense forest'
                    ],
                    [
                        'caption' => 'Wildlife',
                        'description' => 'Deer in natural habitat'
                    ],
                    [
                        'caption' => 'Ocean Waves',
                        'description' => 'Powerful waves crashing on rocky shore'
                    ]
                ]
            ],
            [
                'title' => 'Urban Architecture',
                'slug' => 'urban-architecture',
                'description' => 'Modern and historic architectural designs from cities around the world.',
                'user_id' => $admin->id,
                'photos' => [
                    [
                        'caption' => 'Modern Skyscraper',
                        'description' => 'Contemporary glass and steel building reaching into the clouds'
                    ],
                    [
                        'caption' => 'Historic Cathedral',
                        'description' => 'Gothic architecture with intricate details'
                    ],
                    [
                        'caption' => 'City Bridge',
                        'description' => 'Iconic bridge at sunset'
                    ],
                    [
                        'caption' => 'Urban Park',
                        'description' => 'Green space integrated into city design'
                    ]
                ]
            ],
            [
                'title' => 'Abstract Art',
                'slug' => 'abstract-art',
                'description' => 'A collection of abstract digital art pieces exploring color, form, and composition.',
                'user_id' => $admin->id,
                'photos' => [
                    [
                        'caption' => 'Color Explosion',
                        'description' => 'Vibrant colors in motion'
                    ],
                    [
                        'caption' => 'Geometric Patterns',
                        'description' => 'Interlocking geometric shapes creating depth'
                    ],
                    [
                        'caption' => 'Digital Waves',
                        'description' => 'Flowing digital design resembling ocean waves'
                    ],
                    [
                        'caption' => 'Light Study',
                        'description' => 'Study of how light interacts with abstract forms'
                    ]
                ]
            ]
        ];
        
        // Skip image generation and just use static data
        // Create galleries and associated photos
        foreach ($galleries as $galleryData) {
            $photoData = $galleryData['photos'];
            unset($galleryData['photos']);
            
            // Add placeholder image path (we'll use a simple string path without actually creating files)
            $galleryData['image_path'] = 'placeholders/gallery-' . $galleryData['slug'] . '.jpg';
            
            // Create gallery
            $gallery = Gallery::create($galleryData);
            
            // Add photos to gallery
            foreach ($photoData as $index => $photo) {
                $photo['gallery_id'] = $gallery->id;
                $photo['image_path'] = 'placeholders/photo-' . $gallery->slug . '-' . ($index + 1) . '.jpg';
                Photo::create($photo);
            }
        }
    }
} 