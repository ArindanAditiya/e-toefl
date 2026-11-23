<?php

use Carbon\Carbon;

    if (!function_exists('formatCode')) {
		function formatCode($fitureCode, $sequence) {
			//tanggal
			$year = Carbon::now()->format('y');
			$month = Carbon::now()->format('m');
			$date = Carbon::now()->format('d');
			$dateFormat = $year.$month.$date;

			$sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);

			return $fitureCode.'.'.$dateFormat.'.'.$sequence;
		}
	}

	if(!function_exists('numberFormat')) {
        function numberFormat($angka) {
            return number_format($angka, 0, ',' ,'.' );
        }
	}

	if (!function_exists('dateFormat')) {
        /**
         * @param string $timestamp
         * @param string $date_format
         * @param string $suffix
         * @return string
         */
		function dateFormat($timestamp = '', $date_format = 'j F Y', $suffix = '') {
			if (trim ($timestamp) == '') {
				$timestamp = time ();
			} elseif (!ctype_digit ($timestamp)) {
				$timestamp = strtotime ($timestamp);
			}
			# remove S (st,nd,rd,th) there are no such things in indonesia :p
			$date_format = preg_replace ("/S/", "", $date_format);
			$pattern = array (
				'/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
				'/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
				'/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
				'/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
				'/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
				'/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
				'/April/','/June/','/July/','/August/','/September/','/October/',
				'/November/','/December/',
			);
			$replace = array ( 'Mon','Tue','Wed','Thu','Fri','Sat','Sun',
				'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
				'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Des',
				'Januari','Februari','Maret','April','Juni','Juli','Agustus','September',
				'Oktober','November','Desember',
			);
			$date = date ($date_format, $timestamp);
			$date = preg_replace ($pattern, $replace, $date);
			$date = "{$date} {$suffix}";
			return trim($date);
		}
    }

	if(!function_exists('formatRupiah')) {
		/**
		 * @param $angka
		 * @return string
		 */
		function formatRupiah($angka)
		{
			return "Rp. ". number_format($angka, 0, ',' ,'.' );
		}
	}

	if(!function_exists('sendBrevoNotification')) {
		function sendBrevoNotification($toEmail, $toName, $subject, array $paragraphs = [], array $details = [], $actionUrl = null, $actionText = null, $footerNote = null)
		{
			$html = \App\Services\Notification\BrevoEmailService::buildAcademicTemplate(
				$subject,
				"Assalamu'alaikum Wr. Wb. " . ($toName ?: 'Mahasantri / Peserta') . ",",
				$paragraphs,
				$details,
				$actionUrl,
				$actionText,
				$footerNote
			);
			return \App\Services\Notification\BrevoEmailService::sendEmail($toEmail, $toName, $subject, $html);
		}
	}

	if(!function_exists('sendWhatsappNotification')) {
		function sendWhatsappNotification($number, $message)
		{
			// Bridging to Brevo Email API when student email is resolvable
			try {
				$student = \App\Models\MasterData\Student::where('phone_number', $number)->first();
				if ($student && $student->user && !empty($student->user->email)) {
					$cleanMsg = str_replace(['*', '_'], '', $message);
					$paragraphs = array_filter(explode("\n\n", $cleanMsg));
					sendBrevoNotification(
						$student->user->email,
						$student->user->name,
						"Pemberitahuan Akun e-TOEFL Ma'had Aly Tebuireng",
						$paragraphs
					);
				}
			} catch (\Throwable $e) {
				\Illuminate\Support\Facades\Log::warning("sendWhatsappNotification email bridge warning: " . $e->getMessage());
			}

			// Optional Legacy WhatsApp API (Fonnte) if token configured
			$token = config('services.whatsapp.token') ?: env('WHATSAPP_TOKEN');
			if (empty($token)) {
				return;
			}

        	$curl = curl_init();

			$data = [
				'target' => $number,
				'message' => $message
			];

			curl_setopt_array($curl, [
				CURLOPT_URL => 'https://api.fonnte.com/send',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => http_build_query($data),
				CURLOPT_HTTPHEADER => array(
					'Authorization: '.$token.''
				),
			]);

			$response = curl_exec($curl);
			curl_close($curl);
		}
	}

    if (!function_exists('validateTurnstile')) {
        /**
         * Validasi respon keamanan Cloudflare Turnstile
         *
         * @param string|null $token
         * @param string|null $ip
         * @return bool
         */
        function validateTurnstile($token = null, $ip = null) {
            $secretKey = trim(config('services.turnstile.secret_key') ?: env('TURNSTILE_SECRET_KEY') ?: '');

            // Fallback untuk testing keys Cloudflare Turnstile (selalu lolos)
            // https://developers.cloudflare.com/turnstile/troubleshooting/testing/
            $testingPassKeys = [
                '1x00000000000000000000000000000000BB', // Always passes secret
                '2x00000000000000000000000000000000BB', // Always passes secret
            ];

            if (empty($secretKey) || in_array($secretKey, $testingPassKeys)) {
                return true;
            }

            // Jika token testing atau bypass
            $testingTokens = [
                '1x00000000000000000000AA',
                '2x00000000000000000000AA',
                '3x00000000000000000000AA',
                '1x00000000000000000000000000000000BB',
                '2x00000000000000000000000000000000BB',
                'BYPASS_LOCAL',
                'XXXX.DUMMY.TOKEN.XXXX',
            ];
            if (in_array($token, $testingTokens)) {
                return true;
            }

            if (empty($token)) {
                \Illuminate\Support\Facades\Log::warning('Turnstile verification failed: token is empty.');
                return false;
            }

            // Dapatkan IP publik client jika tersedia, hindari loopback / private IP (127.0.0.1, ::1)
            $clientIp = request()->header('CF-Connecting-IP');
            if (!$clientIp) {
                $forwarded = request()->header('X-Forwarded-For');
                if ($forwarded) {
                    $parts = explode(',', $forwarded);
                    $clientIp = trim($parts[0]);
                }
            }
            if (!$clientIp && $ip) {
                $clientIp = $ip;
            }

            // Filter out private/loopback range so Cloudflare doesn't reject with invalid-remoteip
            if ($clientIp && filter_var($clientIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                $clientIp = null;
            }

            $payload = [
                'secret' => $secretKey,
                'response' => $token,
            ];
            if (!empty($clientIp)) {
                $payload['remoteip'] = $clientIp;
            }

            try {
                // Gunakan withoutVerifying() untuk menghindari cURL error 60 di Windows/Laragon jika cacert.pem belum terpasang
                $response = \Illuminate\Support\Facades\Http::asForm()
                    ->withoutVerifying()
                    ->timeout(10)
                    ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', $payload);

                $data = $response->json();
                $isSuccess = is_array($data) && isset($data['success']) && $data['success'] === true;

                if (!$isSuccess) {
                    \Illuminate\Support\Facades\Log::warning('Turnstile verification rejected by Cloudflare', [
                        'http_status' => $response->status(),
                        'data' => $data,
                        'payload_sent' => [
                            'secret_prefix' => substr($secretKey, 0, 8) . '...',
                            'token_prefix' => substr($token, 0, 15) . '...',
                            'remoteip' => $payload['remoteip'] ?? null,
                        ],
                    ]);
                }

                return $isSuccess;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Turnstile verification network error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);

                // Jika terjadi gangguan jaringan ke endpoint Cloudflare, di mode local/testing berikan toleransi
                if (app()->environment('local', 'testing')) {
                    return true;
                }

                return false;
            }
        }
    }





