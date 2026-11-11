<?php
function sendWhatsappNotification($number, $message)
		{
			$token = "_0Zi4K@S!3YUr9Cc4UiV";
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
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)),
				CURLOPT_HTTPHEADER => array(
					'Authorization: '.$token.''
			),
			]);

			$response = curl_exec($curl);

			curl_close($curl);
			return json_decode($response);
		}

sendWhatsappNotification("+6283814305092", "Tes Pesan");
?>