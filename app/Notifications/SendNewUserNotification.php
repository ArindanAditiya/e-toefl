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
        return ['mail'];
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
