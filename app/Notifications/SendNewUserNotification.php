<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SendNewUserNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $smtpUser = config('mail.mailers.smtp.username');
        $smtpPass = config('mail.mailers.smtp.password');
        $driver = config('mail.default');

        // Kirim via Brevo API jika API key tersedia
        $apiKey = config('services.brevo.api_key') ?: env('BREVO_API_KEY');
        if (!empty($apiKey) && !in_array($apiKey, ['[Masukkan API Key Brevo Anda di .env]', 'YOUR_BREVO_API_KEY'])) {
            try {
                \App\Services\Notification\BrevoEmailService::sendEmail(
                    $notifiable->email,
                    $notifiable->name,
                    "[e-TOEFL Ma'had Aly Tebuireng] Pendaftaran Pengguna Baru: " . $this->user->name,
                    "<h3>Pendaftaran Pengguna Baru</h3><p>Terdapat peserta baru yang mendaftar di platform e-TOEFL:</p><ul><li><b>Nama:</b> {$this->user->name}</li><li><b>Email:</b> {$this->user->email}</li><li><b>Waktu:</b> {$this->user->created_at}</li></ul><p><a href='" . url('/admin/users') . "'>Buka Menu Manajemen Pengguna</a></p>"
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("SendNewUserNotification Brevo error: " . $e->getMessage());
            }
            return [];
        }

        // Jika tidak via Brevo API, gunakan mail channel HANYA jika kredensial SMTP tersedia atau log
        if ($driver === 'log' || ($driver === 'smtp' && !empty($smtpUser) && !empty($smtpPass))) {
            return ['mail'];
        }

        \Illuminate\Support\Facades\Log::info("Admin Notification: New User Registered ({$this->user->name} - {$this->user->email})");
        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("[e-TOEFL Ma'had Aly Tebuireng] Pendaftaran Pengguna Baru: " . $this->user->name)
            ->greeting("Assalamu'alaikum Wr. Wb. Admin e-TOEFL,")
            ->line("Terdapat pengguna/peserta baru yang telah mendaftar di platform e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng:")
            ->line("Nama Peserta: " . $this->user->name)
            ->line("Email: " . $this->user->email)
            ->line("Waktu Pendaftaran: " . $this->user->created_at)
            ->action('Buka Menu Manajemen Pengguna', url('/admin/users'))
            ->line("Pesan otomatis dari Sistem e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng.");
    }

    public function toTelegram($notifiable)
    {
        $content = 'User Terbaru'.PHP_EOL;
        $content .= 'Tanggal Daftar : '.$this->user->created_at.PHP_EOL;
        $content .= 'Nama: '.$this->user->name.PHP_EOL;
        $content .= 'Email: '.$this->user->email.PHP_EOL;

        return TelegramMessage::create()
        ->to(config('services.telegram-bot-api.group_id') ?: env('TELEGRAM_BOT_GROUPID'))
            ->content($content);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
