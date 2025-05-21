<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load Tripay settings from database to override config values
        try {
            // Only run this code if the settings table exists
            if (Schema::hasTable('settings')) {
                $tripayMerchantCode = Setting::get('tripay_merchant_code');
                $tripayApiKey = Setting::get('tripay_api_key');
                $tripayPrivateKey = Setting::get('tripay_private_key');
                $tripaySandbox = Setting::get('tripay_sandbox');
                
                if (!empty($tripayMerchantCode)) {
                    config(['tripay.merchant_code' => $tripayMerchantCode]);
                }
                
                if (!empty($tripayApiKey)) {
                    config(['tripay.api_key' => $tripayApiKey]);
                }
                
                if (!empty($tripayPrivateKey)) {
                    config(['tripay.private_key' => $tripayPrivateKey]);
                }
                
                if ($tripaySandbox !== null) {
                    config(['tripay.sandbox' => $tripaySandbox === '1']);
                    
                    // Also update the API URL based on sandbox mode
                    $apiUrl = $tripaySandbox === '1' 
                        ? 'https://tripay.co.id/api-sandbox/'
                        : 'https://tripay.co.id/api/';
                    
                    config(['tripay.api_url' => $apiUrl]);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't crash the application
            \Log::error('Failed to load Tripay settings: ' . $e->getMessage());
        }
    }
}
