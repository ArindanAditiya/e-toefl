<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SendTransactionNotification extends Notification
{
    use Queueable;

    protected $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
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
                    "[e-TOEFL Ma'had Aly Tebuireng] Transaksi Baru #" . $this->transaction->code,
                    "<h3>Transaksi Paket Ujian Baru</h3><p>Terdapat transaksi baru:</p><ul><li><b>No Invoice:</b> {$this->transaction->code}</li><li><b>Nama:</b> {$this->transaction->user->name}</li><li><b>Paket:</b> {$this->transaction->exam->title}</li><li><b>Total:</b> Rp. " . number_format($this->transaction->total_purchases, 0, ',', '.') . "</li></ul><p><a href='" . url('/admin/transactions') . "'>Buka Menu Manajemen Transaksi</a></p>"
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("SendTransactionNotification Brevo error: " . $e->getMessage());
            }
            return [];
        }

        if ($driver === 'log' || ($driver === 'smtp' && !empty($smtpUser) && !empty($smtpPass))) {
            return ['mail'];
        }

        \Illuminate\Support\Facades\Log::info("Admin Notification: New Transaction #{$this->transaction->code}");
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
            ->subject("[e-TOEFL Ma'had Aly Tebuireng] Transaksi Baru #" . $this->transaction->code)
            ->greeting("Assalamu'alaikum Wr. Wb. Admin e-TOEFL,")
            ->line("Terdapat transaksi/pesanan paket ujian baru:")
            ->line("No. Invoice: " . $this->transaction->code)
            ->line("Waktu Transaksi: " . $this->transaction->created_at)
            ->line("Nama Peserta: " . $this->transaction->user->name)
            ->line("Email Peserta: " . $this->transaction->user->email)
            ->line("Paket Ujian: " . $this->transaction->exam->title)
            ->line("Total Nominal: Rp. " . number_format($this->transaction->total_purchases, 0, ',', '.'))
            ->action('Buka Menu Manajemen Transaksi', url('/admin/transactions'))
            ->line("Pesan otomatis dari Sistem e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng.");
    }

    public function toTelegram($notifiable)
    {
        $content = 'Transaksi Terbaru'.PHP_EOL;
        $content .= 'No Invoice: '.$this->transaction->code.PHP_EOL;
        $content .= 'Tanggal Invoice : '.$this->transaction->created_at.PHP_EOL;
        $content .= 'Nama User: '.$this->transaction->user->name.PHP_EOL;
        $content .= 'Email User: '.$this->transaction->user->email.PHP_EOL;
        $content .= 'Judul Ujian: '.$this->transaction->exam->title.PHP_EOL;
        $content .= 'Total Pembayaran: '.number_format($this->transaction->total_purchases).PHP_EOL;

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
