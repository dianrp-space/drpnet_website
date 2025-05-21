<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display general settings form
     */
    public function index()
    {
        return view('admin.settings.index', [
            'settings' => Setting::all()->keyBy('key'),
            'slides' => Slide::orderBy('order')->get(),
        ]);
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        // Handle logo upload
        if ($request->hasFile('site_logo') && $request->file('site_logo')->isValid()) {
            $logoPath = $request->file('site_logo')->store('images', 'public');
            Setting::set('site_logo', $logoPath, 'appearance');
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon') && $request->file('site_favicon')->isValid()) {
            $faviconPath = $request->file('site_favicon')->store('images', 'public');
            Setting::set('site_favicon', $faviconPath, 'appearance');
        }

        // Update other settings if present
        if ($request->has('app_name')) {
            Setting::set('app_name', $request->app_name, 'general');
        }
        
        // Update Tripay Settings
        if ($request->has('tripay_merchant_code')) {
            Setting::set('tripay_merchant_code', $request->tripay_merchant_code, 'payment');
        }
        
        if ($request->has('tripay_api_key')) {
            Setting::set('tripay_api_key', $request->tripay_api_key, 'payment');
        }
        
        if ($request->has('tripay_private_key')) {
            Setting::set('tripay_private_key', $request->tripay_private_key, 'payment');
        }
        
        // Checkbox handling for sandbox mode
        Setting::set('tripay_sandbox', $request->has('tripay_sandbox') ? "1" : "0", 'payment');
        
        // Update WhatsApp Settings
        if ($request->has('whatsapp_api_url')) {
            Setting::set('whatsapp_api_url', $request->whatsapp_api_url, 'notification');
        }
        
        if ($request->has('whatsapp_api_token')) {
            Setting::set('whatsapp_api_token', $request->whatsapp_api_token, 'notification');
        }
        
        if ($request->has('whatsapp_sender')) {
            Setting::set('whatsapp_sender', $request->whatsapp_sender, 'notification');
        }
        
        // Checkbox handling for WhatsApp enabled
        Setting::set('whatsapp_enabled', $request->has('whatsapp_enabled') ? "1" : "0", 'notification');

        // Update environment variables for Tripay
        $this->updateEnvFile([
            'TRIPAY_MERCHANT_CODE' => $request->tripay_merchant_code,
            'TRIPAY_API_KEY' => $request->tripay_api_key,
            'TRIPAY_PRIVATE_KEY' => $request->tripay_private_key,
            'TRIPAY_SANDBOX' => $request->has('tripay_sandbox') ? 'true' : 'false',
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully');
    }
    
    /**
     * Update the .env file with new values
     * 
     * @param array $values
     * @return bool
     */
    private function updateEnvFile($values)
    {
        $envFilePath = base_path('.env');
        
        if (!file_exists($envFilePath)) {
            return false;
        }
        
        $envContent = file_get_contents($envFilePath);
        
        foreach ($values as $key => $value) {
            // If value contains spaces, wrap it in quotes
            if (strpos($value, ' ') !== false) {
                $value = '"' . $value . '"';
            }
            
            // Check if key exists
            if (strpos($envContent, "{$key}=") !== false) {
                // Replace existing value
                $envContent = preg_replace("/{$key}=(.*)/", "{$key}={$value}", $envContent);
            } else {
                // Add new key=value pair
                $envContent .= PHP_EOL . "{$key}={$value}";
            }
        }
        
        file_put_contents($envFilePath, $envContent);
        
        return true;
    }

    /**
     * Manage slides for welcome page slider
     */
    public function slides()
    {
        return view('admin.settings.slides', [
            'slides' => Slide::orderBy('order')->get(),
        ]);
    }

    /**
     * Store a new slide
     */
    public function storeSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'active' => 'boolean',
        ]);

        $imagePath = $request->file('image')->store('slides', 'public');
        
        $slide = new Slide();
        $slide->title = $validated['title'];
        $slide->description = $validated['description'] ?? null;
        $slide->image_path = $imagePath;
        $slide->active = $validated['active'] ?? true;
        $slide->order = Slide::max('order') + 1;
        $slide->save();

        return redirect()->route('admin.settings.slides')->with('success', 'Slide added successfully');
    }

    /**
     * Update a slide
     */
    public function updateSlide(Request $request, Slide $slide)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean',
            'order' => 'integer|min:1',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image
            if (Storage::disk('public')->exists($slide->image_path)) {
                Storage::disk('public')->delete($slide->image_path);
            }
            $imagePath = $request->file('image')->store('slides', 'public');
            $slide->image_path = $imagePath;
        }

        $slide->title = $validated['title'] ?? $slide->title;
        $slide->description = $validated['description'] ?? $slide->description;
        $slide->active = $validated['active'] ?? $slide->active;
        $slide->order = $validated['order'] ?? $slide->order;
        $slide->save();

        return redirect()->route('admin.settings.slides')->with('success', 'Slide updated successfully');
    }

    /**
     * Delete a slide
     */
    public function deleteSlide(Slide $slide)
    {
        // Delete the image
        if (Storage::disk('public')->exists($slide->image_path)) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $slide->delete();

        return redirect()->route('admin.settings.slides')->with('success', 'Slide deleted successfully');
    }

    /**
     * Show WhatsApp test form
     */
    public function whatsappTest()
    {
        return view('admin.settings.whatsapp-test', [
            'apiUrl' => Setting::get('whatsapp_api_url', ''),
            'apiToken' => Setting::get('whatsapp_api_token', ''),
            'sender' => Setting::get('whatsapp_sender', ''),
            'enabled' => Setting::get('whatsapp_enabled', '0')
        ]);
    }
    
    /**
     * Send a test WhatsApp message
     */
    public function sendWhatsappTest(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|string',
            'message' => 'required|string',
            'footer' => 'nullable|string',
        ]);
        
        // Create WhatsApp service
        $whatsAppService = app(\App\Services\WhatsAppService::class);
        
        // Send the message
        $success = $whatsAppService->sendMessage(
            $validated['recipient'],
            $validated['message'],
            $validated['footer'] ?? 'Test message from DRP Net'
        );
        
        if ($success) {
            return redirect()->route('admin.settings.whatsapp-test')
                ->with('success', 'WhatsApp test message sent successfully!');
        } else {
            return redirect()->route('admin.settings.whatsapp-test')
                ->with('error', 'Failed to send WhatsApp test message. Check logs for details.');
        }
    }
}
