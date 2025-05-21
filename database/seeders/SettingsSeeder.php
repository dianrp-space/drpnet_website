<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General settings
        if (!Setting::where('key', 'app_name')->exists()) {
            Setting::create([
                'key' => 'app_name',
                'value' => 'DRP Net',
                'group' => 'general',
                'is_public' => true
            ]);
        }
        
        // Appearance settings
        if (!Setting::where('key', 'site_logo')->exists()) {
            Setting::create([
                'key' => 'site_logo',
                'value' => 'images/logo.png',
                'group' => 'appearance',
                'is_public' => true
            ]);
        }
        
        if (!Setting::where('key', 'site_favicon')->exists()) {
            Setting::create([
                'key' => 'site_favicon',
                'value' => 'images/favicon.ico',
                'group' => 'appearance',
                'is_public' => true
            ]);
        }
        
        // Payment settings (Tripay)
        if (!Setting::where('key', 'tripay_merchant_code')->exists()) {
            Setting::create([
                'key' => 'tripay_merchant_code',
                'value' => '',
                'group' => 'payment',
                'is_public' => false
            ]);
        }
        
        if (!Setting::where('key', 'tripay_api_key')->exists()) {
            Setting::create([
                'key' => 'tripay_api_key',
                'value' => '',
                'group' => 'payment',
                'is_public' => false
            ]);
        }
        
        if (!Setting::where('key', 'tripay_private_key')->exists()) {
            Setting::create([
                'key' => 'tripay_private_key',
                'value' => '',
                'group' => 'payment',
                'is_public' => false
            ]);
        }
        
        if (!Setting::where('key', 'tripay_sandbox')->exists()) {
            Setting::create([
                'key' => 'tripay_sandbox',
                'value' => '1',
                'group' => 'payment',
                'is_public' => false
            ]);
        }
        
        // WhatsApp notification settings
        if (!Setting::where('key', 'whatsapp_api_url')->exists()) {
            Setting::create([
                'key' => 'whatsapp_api_url',
                'value' => '',
                'group' => 'notification',
                'is_public' => false
            ]);
        }
        
        if (!Setting::where('key', 'whatsapp_api_token')->exists()) {
            Setting::create([
                'key' => 'whatsapp_api_token',
                'value' => '',
                'group' => 'notification',
                'is_public' => false
            ]);
        }
        
        if (!Setting::where('key', 'whatsapp_sender')->exists()) {
            Setting::create([
                'key' => 'whatsapp_sender',
                'value' => '',
                'group' => 'notification',
                'is_public' => false
            ]);
        }
        
        if (!Setting::where('key', 'whatsapp_enabled')->exists()) {
            Setting::create([
                'key' => 'whatsapp_enabled',
                'value' => '0',
                'group' => 'notification',
                'is_public' => false
            ]);
        }
        
        $this->command->info('Settings table created and seeded successfully!');
    }
} 