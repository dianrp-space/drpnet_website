<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * API URL for WhatsApp service
     */
    protected $apiUrl;

    /**
     * API token for WhatsApp service
     */
    protected $apiToken;

    /**
     * WhatsApp sender number
     */
    protected $sender;

    /**
     * Whether WhatsApp notifications are enabled
     */
    protected $enabled;

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        // Load settings from database
        $this->apiUrl = Setting::get('whatsapp_api_url', 'https://wa.drpnet.my.id/send-message');
        $this->apiToken = Setting::get('whatsapp_api_token', '');
        $this->sender = Setting::get('whatsapp_sender', '');
        $this->enabled = Setting::get('whatsapp_enabled', '0') === '1';
    }

    /**
     * Send a deposit notification to a user
     *
     * @param User $user
     * @param float $amount
     * @param float $newBalance
     * @return bool
     */
    public function sendDepositNotification(User $user, float $amount, float $newBalance): bool
    {
        if (!$this->enabled || !$this->isConfigured() || empty($user->phone)) {
            return false;
        }

        $message = "Halo {$user->name},\n\n";
        $message .= "Deposit sebesar Rp " . number_format($amount, 0, ',', '.') . " telah berhasil.\n";
        $message .= "Saldo Anda saat ini: Rp " . number_format($newBalance, 0, ',', '.') . "\n\n";
        $message .= "Terima kasih telah menggunakan layanan kami.";
        
        $footer = "DRP Net - Deposit Notification";

        return $this->sendMessage($user->phone, $message, $footer);
    }

    /**
     * Send transfer notifications to both sender and recipient
     *
     * @param User $sender
     * @param User $recipient
     * @param float $amount
     * @param float $senderBalance
     * @param float $recipientBalance
     * @return array
     */
    public function sendTransferNotifications(
        User $sender, 
        User $recipient, 
        float $amount, 
        float $senderBalance,
        float $recipientBalance
    ): array {
        $results = [
            'sender' => false,
            'recipient' => false
        ];

        if (!$this->enabled || !$this->isConfigured()) {
            return $results;
        }

        // Send notification to sender
        if (!empty($sender->phone)) {
            $message = "Halo {$sender->name},\n\n";
            $message .= "Transfer sebesar Rp " . number_format($amount, 0, ',', '.') . " ke {$recipient->name} berhasil.\n";
            $message .= "Saldo Anda saat ini: Rp " . number_format($senderBalance, 0, ',', '.') . "\n\n";
            $message .= "Terima kasih telah menggunakan layanan kami.";

            $results['sender'] = $this->sendMessage($sender->phone, $message, "DRP Net - Transfer Confirmation");
        }

        // Send notification to recipient
        if (!empty($recipient->phone)) {
            $message = "Halo {$recipient->name},\n\n";
            $message .= "Anda menerima transfer sebesar Rp " . number_format($amount, 0, ',', '.') . " dari {$sender->name}.\n";
            $message .= "Saldo Anda saat ini: Rp " . number_format($recipientBalance, 0, ',', '.') . "\n\n";
            $message .= "Terima kasih telah menggunakan layanan kami.";

            $results['recipient'] = $this->sendMessage($recipient->phone, $message, "DRP Net - Transfer Received");
        }

        return $results;
    }
    
    /**
     * Send purchase notification to a user
     *
     * @param User $user
     * @param string $productName
     * @param float $amount
     * @param float $newBalance
     * @return bool
     */
    public function sendPurchaseNotification(User $user, string $productName, float $amount, float $newBalance): bool
    {
        if (!$this->enabled || !$this->isConfigured() || empty($user->phone)) {
            return false;
        }

        $message = "Halo {$user->name},\n\n";
        $message .= "Pembelian produk \"{$productName}\" sebesar Rp " . number_format($amount, 0, ',', '.') . " telah berhasil.\n";
        $message .= "Saldo Anda saat ini: Rp " . number_format($newBalance, 0, ',', '.') . "\n\n";
        $message .= "Terima kasih telah berbelanja di toko kami.";
        
        $footer = "DRP Net - Purchase Confirmation";

        return $this->sendMessage($user->phone, $message, $footer);
    }

    /**
     * Send welcome notification to a new user
     *
     * @param User $user
     * @return bool
     */
    public function sendWelcomeNotification(User $user): bool
    {
        if (!$this->enabled || !$this->isConfigured() || empty($user->phone)) {
            return false;
        }

        $message = "Halo {$user->name},\n\n";
        $message .= "Selamat datang di DRP Net!\n\n";
        $message .= "Akun Anda telah berhasil dibuat. Anda sekarang dapat menjelajahi semua fitur yang kami tawarkan.\n\n";
        $message .= "Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.\n\n";
        $message .= "Terima kasih telah bergabung dengan kami!";
        
        $footer = "DRP Net - Welcome";

        return $this->sendMessage($user->phone, $message, $footer);
    }

    /**
     * Send a generic WhatsApp message
     *
     * @param string $phoneNumber
     * @param string $message
     * @param string $footer
     * @return bool
     */
    public function sendMessage(string $phoneNumber, string $message, string $footer = ''): bool
    {
        if (!$this->enabled || !$this->isConfigured()) {
            return false;
        }

        // Format phone number (remove + if exists)
        $phoneNumber = ltrim($phoneNumber, '+');

        try {
            // Use the exact API parameters as specified in the documentation
            $response = Http::post($this->apiUrl, [
                'api_key' => $this->apiToken,
                'sender' => $this->sender,
                'number' => $phoneNumber,
                'message' => $message,
                'footer' => $footer ?: 'Powered by DRP Net'
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'phone' => $phoneNumber,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'phone' => $phoneNumber,
                    'error' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception when sending WhatsApp message', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if WhatsApp service is properly configured
     *
     * @return bool
     */
    private function isConfigured(): bool
    {
        return !empty($this->apiUrl) && !empty($this->apiToken) && !empty($this->sender);
    }
} 