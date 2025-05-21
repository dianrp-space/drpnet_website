<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Services\WhatsAppService;

class PasswordResetLinkController extends Controller
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'send_method' => ['required', Rule::in(['email', 'whatsapp'])],
            'email' => [Rule::requiredIf($request->input('send_method') === 'email'), 'nullable', 'email'],
            'whatsapp_number' => [
                Rule::requiredIf($request->input('send_method') === 'whatsapp'), 
                'nullable', 
                'string', 
                'regex:/^[+]?[1-9]\d{1,14}$/'
            ],
        ]);

        $sendMethod = $request->input('send_method');
        $email = $request->input('email');
        $whatsappNumber = $request->input('whatsapp_number');

        Log::info('Password reset request received', ['method' => $sendMethod, 'email' => $email, 'whatsapp_number' => $whatsappNumber]);

        if ($sendMethod === 'email') {
            // We will send the password reset link to this user.
            $status = Password::sendResetLink($request->only('email'));

            if ($status == Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent successfully via email', ['email' => $email, 'status' => $status]);
                return back()->with('status', __($status));
            } else {
                Log::error('Failed to send password reset link via email', ['email' => $email, 'status' => $status]);
                return back()->withInput($request->all())->withErrors(['email' => __($status)]);
            }
        } elseif ($sendMethod === 'whatsapp') {
            // Hapus awalan + jika ada untuk konsistensi pencarian
            $normalizedWhatsAppNumber = ltrim($whatsappNumber, '+');

            // Coba cari pengguna berdasarkan nomor WhatsApp. Asumsikan ada kolom 'phone' di tabel User.
            // Sesuaikan nama kolom jika berbeda (misal: 'whatsapp_number')
            $user = User::where('phone', $normalizedWhatsAppNumber)->first();

            if ($user) {
                Log::info('User found for WhatsApp password reset', ['user_id' => $user->id, 'whatsapp_number' => $normalizedWhatsAppNumber]);

                // Buat token reset password
                $token = Password::broker()->createToken($user);

                // Buat URL reset password
                // Penting: pastikan $user->email ada dan valid
                $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

                // Siapkan pesan WhatsApp
                $message = __('Hello :name, you requested a password reset. Click this link to reset your password: :resetUrl This link is valid for :count minutes.', [
                    'name' => $user->name,
                    'resetUrl' => $resetUrl,
                    'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
                ]);
                $footer = __('Password Reset - :appName', ['appName' => config('app.name')]);

                // Kirim pesan WhatsApp
                $sent = $this->whatsAppService->sendMessage($normalizedWhatsAppNumber, $message, $footer);

                if ($sent) {
                    Log::info('Password reset link sent successfully via WhatsApp', ['user_id' => $user->id, 'whatsapp_number' => $normalizedWhatsAppNumber]);
                    return back()->with('status', __('We have sent your password reset link to your WhatsApp number!'));
                } else {
                    Log::error('Failed to send password reset link via WhatsApp', ['user_id' => $user->id, 'whatsapp_number' => $normalizedWhatsAppNumber]);
                    return back()->withInput($request->all())->withErrors(['whatsapp_number' => __('Failed to send password reset link via WhatsApp. Please try again later or use email.')]);
                }
            } else {
                Log::warning('User not found for WhatsApp password reset', ['whatsapp_number' => $normalizedWhatsAppNumber]);
                return back()->withInput($request->all())
                            ->withErrors(['whatsapp_number' => __('We could not find a user with that WhatsApp number. Please ensure the number is registered and correct.')]);
            }
        }

        return back(); // Fallback, seharusnya tidak tercapai
    }
}
