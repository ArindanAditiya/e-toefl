<?php

namespace App\Services\Transaction;

use App\Repositories\Contracts\Transaction\TransactionInterface as TransactionRepo;
use App\Repositories\Contracts\Exam\ExamInterface as ExamRepo;
use App\Services\Contracts\Transaction\TransactionInterface;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use App\Notifications\SendTransactionNotification;
use App\Models\User;

class TransactionService implements TransactionInterface
{
    protected $transactionRepo, $examRepo;

    public function __construct(TransactionRepo $transactionRepo, ExamRepo $examRepo)
    {
        $this->transactionRepo = $transactionRepo;
        $this->examRepo = $examRepo;
    }

    public function all()
    {
        return $this->transactionRepo->all();
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->transactionRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->transactionRepo->getAllPaginatedWithParams($params, $limit);
    }

     /**
     * Find data by id
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->transactionRepo->find($id);
    }

    /**
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function create($request)
    {
        $permissions = DB::transaction(function () use ($request) {
            $input = $request->all();
            return $this->transactionRepo->create($input);
        });

        return $permissions;
    }

     /**
     * @param $id
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
    */
    public function update($request, $id)
    {
        $permissions = DB::transaction(function () use ($request, $id) {
            $input = $request->except('_token','_method');
            $token = $request->transaction_status == "done" ? strtoupper(Str::random(15)) : null;
            $input['voucher_token'] = $token;
            $this->transactionRepo->update($input, $id);

            $transaction = $this->find($id);

            $user = User::find($transaction->user_id);
          
            if($request->transaction_status == "paid") {
                sendBrevoNotification(
                    $user->email,
                    $user->name,
                    "[e-TOEFL Ma'had Aly] Pembayaran Diterima - Invoice #" . $transaction->code,
                    [
                        "Pembayaran untuk pesanan paket ujian Anda telah kami terima.",
                        "Admin sedang memproses aktivasi. Token voucher ujian akan segera diterbitkan dan dikirimkan ke akun Anda."
                    ],
                    [
                        'No. Transaksi' => htmlspecialchars($transaction->code),
                        'Paket Ujian' => htmlspecialchars($transaction->exam->title),
                        'Total Pembayaran' => $this->formatPrice($transaction->total_purchases),
                        'Status' => '<strong style="color: #007A1E;">PEMBAYARAN DITERIMA</strong>'
                    ],
                    url('/user/transactions'),
                    "Lihat Riwayat Transaksi"
                );
            }

            if($request->transaction_status == "failed") {
                sendBrevoNotification(
                    $user->email,
                    $user->name,
                    "[e-TOEFL Ma'had Aly] Transaksi Dibatalkan - Invoice #" . $transaction->code,
                    [
                        "Transaksi untuk pemesanan paket ujian berikut telah dibatalkan karena melewati batas waktu pembayaran atau alasan lainnya.",
                        "Jika Anda masih ingin mengikuti ujian ini, silakan lakukan pemesanan ulang melalui dashboard pengguna."
                    ],
                    [
                        'No. Transaksi' => htmlspecialchars($transaction->code),
                        'Paket Ujian' => htmlspecialchars($transaction->exam->title),
                        'Total' => $this->formatPrice($transaction->total_purchases),
                        'Status' => '<strong style="color: #dc2626;">DIBATALKAN</strong>'
                    ],
                    url('/user/dashboard'),
                    "Pesan Ulang Paket Ujian"
                );
            }

            if($request->transaction_status == "done") {
                sendBrevoNotification(
                    $user->email,
                    $user->name,
                    "[e-TOEFL Ma'had Aly] Token Voucher Ujian Anda - Invoice #" . $transaction->code,
                    [
                        "Transaksi Anda telah <strong>SELESAI</strong>. Berikut adalah kode voucher ujian Anda:",
                    ],
                    [
                        'No. Transaksi' => htmlspecialchars($transaction->code),
                        'Paket Ujian' => htmlspecialchars($transaction->exam->title),
                        'KODE VOUCHER' => '<code style="background: #e2ede4; padding: 6px 12px; font-size: 16px; font-weight: bold; color: #0A3D24; letter-spacing: 2px;">' . htmlspecialchars($transaction->voucher_token) . '</code>',
                        'Status' => '<strong style="color: #007A1E;">SELESAI / AKTIF</strong>'
                    ],
                    url('/user/voucher-activation'),
                    "Aktivasi Voucher Sekarang",
                    "Silakan salin kode voucher di atas dan masukkan pada menu <strong>Aktivasi Voucher</strong> di dashboard e-TOEFL Anda."
                );
            }
        });

        return $permissions;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->transactionRepo->delete($id);
    }

    public function createTransacationWithExamId($id)
    {
        $exam = $this->examRepo->find($id);

        $transaction = [
            'user_id' => Auth::user()->id,
            'exam_id' => $exam->id,
            'code' => Transaction::generateCode(),
            'date' => Carbon::now(),
            'voucher_token' => $exam->price == 0 ? strtoupper(Str::random(15)) : null,
            'total_purchases' => $exam->price,
            'maximum_payment_time' => Carbon::now()->addDays(2),
            'transaction_status' => $exam->price == 0 ? 'done' : 'pending'
        ];

        $transaction = $this->transactionRepo->create($transaction);

        $user = User::find($transaction->user_id);

        if($exam->price == 0) {
            sendBrevoNotification(
                $user->email,
                $user->name,
                "[e-TOEFL Ma'had Aly] Pemesanan Paket Ujian Gratis #" . $transaction->code,
                [
                    "Pemesanan paket try out gratis <strong>" . htmlspecialchars($exam->title) . "</strong> telah berhasil diproses.",
                    "Berikut adalah kode voucher ujian Anda untuk memulai simulasi:",
                ],
                [
                    'No. Transaksi' => htmlspecialchars($transaction->code),
                    'Nama Try Out' => htmlspecialchars($exam->title),
                    'Total Biaya' => 'GRATIS / Rp 0',
                    'KODE VOUCHER' => '<code style="background: #e2ede4; padding: 6px 12px; font-size: 16px; font-weight: bold; color: #0A3D24; letter-spacing: 2px;">' . htmlspecialchars($transaction->voucher_token) . '</code>',
                ],
                url('/user/voucher-activation'),
                "Aktivasi Voucher Sekarang",
                "Silakan salin token voucher di atas dan masukkan pada menu <strong>Aktivasi Voucher</strong> di akun e-TOEFL Anda."
            );
        } else {
            sendBrevoNotification(
                $user->email,
                $user->name,
                "[e-TOEFL Ma'had Aly] Tagihan Pemesanan Ujian #" . $transaction->code,
                [
                    "Terima kasih telah memesan paket ujian <strong>" . htmlspecialchars($exam->title) . "</strong> di platform e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng.",
                    "Silakan lakukan pembayaran sesuai dengan rincian invoice berikut sebelum batas waktu yang ditentukan:"
                ],
                [
                    'No. Transaksi' => htmlspecialchars($transaction->code),
                    'Paket Ujian' => htmlspecialchars($exam->title),
                    'Total Pembayaran' => '<strong>' . $this->formatPrice($transaction->total_purchases) . '</strong>',
                    'Batas Waktu Pembayaran' => htmlspecialchars($transaction->maximum_payment_time),
                    'Status' => '<strong style="color: #b45309;">MENUNGGU PEMBAYARAN</strong>'
                ],
                url('/user/transactions/' . $transaction->id),
                "Lihat Detail Invoice & Pembayaran",
                "Setelah melakukan transfer pembayaran ke nomor rekening resmi, token voucher ujian akan otomatis aktif atau dikonfirmasi oleh Admin."
            );
        }

        $admin = User::find('20b2a4122c614bb68e41b1a6f3f37780');
        if ($admin) {
            $admin->notify(new SendTransactionNotification($transaction));
        }

        return $transaction;
    }

    public function getAllPaginatedWithParamsByUser($params, $limit = 10)
    {
        return $this->transactionRepo->getAllPaginatedWithParamsByUser($params, $limit);
    }

    public function getAllVoucherActivatedPaginatedWithParamsByUser($params, $limit = 10)
    {
        return $this->transactionRepo->getAllVoucherActivatedPaginatedWithParamsByUser($params, $limit);
    }

    public function getSummaryTransactionByUser($limit = 5)
    {
        return $this->transactionRepo->getSummaryTransactionByUser($limit);
    }

    public function getTotalTransactionToday()
    {
        return $this->transactionRepo->getTotalTransactionToday();
    }

    public function getTotalTransactionMonthly()
    {
        return $this->transactionRepo->getTotalTransactionMonthly();
    }

    public function getTotalTransactionYearly()
    {
        return $this->transactionRepo->getTotalTransactionYearly();
    }

    public function getTotalTransactionPending()
    {
        return $this->transactionRepo->getTotalTransactionPending();
    }

    public function getTotalTransactionPaid()
    {
        return $this->transactionRepo->getTotalTransactionPaid();
    }

    public function getTotalTransactionDone()
    {
        return $this->transactionRepo->getTotalTransactionDone();
    }


    public function getTotalTransactionFailed()
    {
        return $this->transactionRepo->getTotalTransactionFailed();
    }

    public function getTransactionMonthly($limit = 10)
    {
        return $this->transactionRepo->getTransactionMonthly($limit);
    }

    public function checkTransactionPendingByUser()
    {
        return $this->transactionRepo->checkTransactionPendingByUser();
    }

    public function formatPrice($price)
    {
        return "Rp. ".number_format($price, 0, ",", ".");
    }
}
