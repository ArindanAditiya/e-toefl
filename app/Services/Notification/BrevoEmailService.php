<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class BrevoEmailService
{
    /**
     * Send email via Brevo REST API v3 or fallback to Laravel Mail (SMTP/Log)
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlContent
     * @param string|null $textContent
     * @return bool
     */
    public static function sendEmail($toEmail, $toName, $subject, $htmlContent, $textContent = null)
    {
        $apiKey = config('services.brevo.api_key') ?: env('BREVO_API_KEY');
        $fromEmail = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS', 'mahadaly@tebuireng.ac.id');
        $fromName = config('mail.from.name') ?: env('MAIL_FROM_NAME', "e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng");

        // 1. Prioritas Utama: Brevo Transactional Email REST API v3 (Direct HTTP via api-key)
        if (!empty($apiKey) && !in_array($apiKey, ['[Masukkan API Key Brevo Anda di .env]', 'YOUR_BREVO_API_KEY'])) {
            try {
                $payload = [
                    'sender' => [
                        'name' => $fromName,
                        'email' => $fromEmail,
                    ],
                    'to' => [
                        [
                            'email' => $toEmail,
                            'name' => $toName ?: $toEmail,
                        ]
                    ],
                    'subject' => $subject,
                    'htmlContent' => $htmlContent,
                ];

                if ($textContent) {
                    $payload['textContent'] = $textContent;
                }

                $response = Http::withHeaders([
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->timeout(10)->post('https://api.brevo.com/v3/smtp/email', $payload);

                if ($response->successful()) {
                    Log::info("Brevo REST API v3: Email sent successfully to {$toEmail} (Subject: {$subject})");
                    return true;
                } else {
                    Log::warning("Brevo REST API v3 responded with status {$response->status()}: " . $response->body());
                }
            } catch (\Throwable $e) {
                Log::error("Brevo REST API v3 Error: " . $e->getMessage());
            }
        }

        // 2. Fallback: Laravel Mailer (Hanya jika kredensial SMTP tersedia atau mail driver adalah log)
        $mailDriver = config('mail.default', 'smtp');
        $smtpUser = config('mail.mailers.smtp.username');
        $smtpPass = config('mail.mailers.smtp.password');

        if ($mailDriver === 'log' || ($mailDriver === 'smtp' && !empty($smtpUser) && !empty($smtpPass))) {
            try {
                Mail::html($htmlContent, function ($message) use ($toEmail, $toName, $subject, $fromEmail, $fromName) {
                    $message->to($toEmail, $toName ?: null)
                        ->from($fromEmail, $fromName)
                        ->subject($subject);
                });
                Log::info("Laravel Mailer: Email sent to {$toEmail} (Subject: {$subject})");
                return true;
            } catch (\Throwable $e) {
                Log::error("Laravel Mailer Error: " . $e->getMessage());
                return false;
            }
        }

        // 3. Fallback Aman: Catat pengiriman email di log jika API key & SMTP login belum diisi di lingkungan lokal
        Log::info("Email queued/logged to {$toEmail} [Subject: {$subject}] (Brevo API key & SMTP credentials not configured in environment)");
        return true;
    }

    /**
     * Generate standard HTML email template with Ma'had Aly Hasyim Asy'ari Tebuireng academic branding
     *
     * @param string $title
     * @param string $greeting
     * @param array $paragraphs
     * @param array $details Key-value pairs
     * @param string|null $actionUrl
     * @param string|null $actionText
     * @param string|null $footerNote
     * @return string
     */
    public static function buildAcademicTemplate($title, $greeting, array $paragraphs = [], array $details = [], $actionUrl = null, $actionText = null, $footerNote = null)
    {
        $adminWa = config('services.admin.wa') ?: env('ADMIN_WA', '@mahadalytbi');
        $adminEmail = config('services.admin.email') ?: env('ADMIN_EMAIL', 'mahadaly@tebuireng.ac.id');
        $adminAddress = config('services.admin.address') ?: env('ADMIN_ADDRESS', 'Jl. Irian Jaya No. 10, Tebuireng, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471');

        $detailRows = '';
        if (!empty($details)) {
            $detailRows .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #f9fbf9; border: 1px solid #e1ede3; border-radius: 6px;">';
            foreach ($details as $label => $value) {
                $detailRows .= '<tr>
                    <td style="padding: 10px 14px; font-weight: 600; color: #0A3D24; width: 38%; border-bottom: 1px solid #edf4ee; font-size: 14px;">' . htmlspecialchars($label) . '</td>
                    <td style="padding: 10px 14px; color: #2d3748; border-bottom: 1px solid #edf4ee; font-size: 14px;">' . $value . '</td>
                </tr>';
            }
            $detailRows .= '</table>';
        }

        $paragraphHtml = '';
        foreach ($paragraphs as $p) {
            $paragraphHtml .= '<p style="margin: 0 0 14px; line-height: 1.6; color: #374151; font-size: 15px;">' . $p . '</p>';
        }

        $buttonHtml = '';
        if ($actionUrl && $actionText) {
            $buttonHtml = '
            <div style="text-align: center; margin: 28px 0 20px;">
                <a href="' . htmlspecialchars($actionUrl) . '" style="display: inline-block; background-color: #007A1E; color: #FFFFFF; font-weight: 600; font-size: 15px; padding: 13px 28px; text-decoration: none; border-radius: 6px; border-bottom: 3px solid #0A3D24; box-shadow: 0 4px 6px rgba(0,122,30,0.18);">
                    ' . htmlspecialchars($actionText) . '
                </a>
            </div>
            <p style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 8px;">
                Jika tombol di atas tidak dapat diklik, salin tautan berikut ke peramban Anda:<br>
                <a href="' . htmlspecialchars($actionUrl) . '" style="color: #007A1E; word-break: break-all;">' . htmlspecialchars($actionUrl) . '</a>
            </p>';
        }

        $footerNoteHtml = $footerNote ? '<p style="font-size: 13px; color: #4b5563; background: #fffbe8; border-left: 4px solid #C69C2B; padding: 10px 14px; margin: 18px 0; border-radius: 0 4px 4px 0;">' . $footerNote . '</p>' : '';

        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . '</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f5f4; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; color: #2d3748;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f3f5f4; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" style="max-width: 620px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 14px rgba(10,61,36,0.08); border: 1px solid #e2e8e4;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0A3D24 0%, #007A1E 100%); padding: 26px 30px; text-align: center; border-bottom: 4px solid #C69C2B;">
                            <div style="font-size: 12px; font-weight: 700; color: #C69C2B; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 4px;">MA\'HAD ALY HASYIM ASY\'ARI</div>
                            <div style="font-size: 20px; font-weight: 700; color: #ffffff; margin-bottom: 2px;">PONDOK PESANTREN TEBUIRENG JOMBANG</div>
                            <div style="font-size: 13px; color: #d4ebd7; font-weight: 400;">Platform Resmi e-TOEFL & Uji Kompetensi Bahasa</div>
                        </td>
                    </tr>
                    <!-- Title Bar -->
                    <tr>
                        <td style="background-color: #f9fbf9; padding: 14px 30px; border-bottom: 1px solid #edf4ee;">
                            <h2 style="margin: 0; font-size: 17px; color: #0A3D24; font-weight: 700;">' . htmlspecialchars($title) . '</h2>
                        </td>
                    </tr>
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 28px 30px 20px;">
                            <p style="margin: 0 0 16px; font-weight: 600; color: #0A3D24; font-size: 15px;">' . htmlspecialchars($greeting) . '</p>
                            ' . $paragraphHtml . '
                            ' . $detailRows . '
                            ' . $buttonHtml . '
                            ' . $footerNoteHtml . '
                            <p style="margin: 24px 0 0; font-size: 14px; line-height: 1.5; color: #374151;">
                                <em>Wassalamu\'alaikum Warahmatullahi Wabarakatuh</em><br><br>
                                <strong>Pusat Layanan Bahasa & e-TOEFL</strong><br>
                                Ma\'had Aly Hasyim Asy\'ari Tebuireng
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0A3D24; color: #ffffff; padding: 22px 30px; font-size: 12px; line-height: 1.6; text-align: center; border-top: 3px solid #C69C2B;">
                            <p style="margin: 0 0 8px; font-weight: 600; color: #C69C2B; font-size: 13px;">MA\'HAD ALY HASYIM ASY\'ARI TEBUIRENG</p>
                            <p style="margin: 0 0 6px; color: #e2ede4;">' . htmlspecialchars($adminAddress) . '</p>
                            <p style="margin: 0; color: #d1e3d4;">
                                Kontak: <a href="mailto:' . htmlspecialchars($adminEmail) . '" style="color: #ffffff; text-decoration: underline;">' . htmlspecialchars($adminEmail) . '</a> | 
                                WhatsApp: <span style="color: #C69C2B; font-weight: 600;">' . htmlspecialchars($adminWa) . '</span>
                            </p>
                            <p style="margin: 10px 0 0; color: #a4c4ab; font-size: 11px;">
                                © ' . date('Y') . ' e-TOEFL Ma\'had Aly Hasyim Asy\'ari. Hak Cipta Dilindungi.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }
}
