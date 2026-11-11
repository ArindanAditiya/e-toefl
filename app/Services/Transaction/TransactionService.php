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
                $message = "*[TRANSAKSI e-TOEFL]*\n\n_Hallo, saya admin dari e-TOEFL, berikut adalah detail pesanan anda._\n\nNama: ".$user->name."\nEmail: ".$user->email."\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \nNomor Transaksi: ".$transaction->code."\nNama Try Out: ".$transaction->exam->title."\nTotal Pembayaran: *".$this->formatPrice($transaction->total_purchases)."*\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \n\n*STATUS : PEMBAYARAN TELAH DITERIMA*\n\n*_SILAKAN TUNGGU HINGGA TOKEN DIKIRIMKAN KEPADA ANDA_*\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu dalam proses belajar anda. aamiin._";
                sendWhatsappNotification($user->student->phone_number, $message);    
            }

            if($request->transaction_status == "failed") {
                $message = "*[TRANSAKSI e-TOEFL]*\n\n_Hallo, saya admin dari e-TOEFL, berikut adalah detail pesanan anda._\n\nNama: ".$user->name."\nEmail: ".$user->email."\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \nNomor Transaksi: ".$transaction->code."\nNama Try Out: ".$transaction->exam->title."\nTotal Pembayaran: *".$this->formatPrice($transaction->total_purchases)."*\nMaksimal Pembayaran: ".$transaction->maximum_payment_time."\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \n\n*STATUS: TRANSAKSI DIBATALKAN*\n\n*_SILAKAN LAKUKAN TRANSAKSI ULANG_*\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu dalam proses belajar anda. aamiin._";
                sendWhatsappNotification($user->student->phone_number, $message);
            }

            if($request->transaction_status == "done") {
                $message = "*[TRANSAKSI e-TOEFL]*\n\n_Hallo, saya admin dari e-TOEFL, berikut adalah detail pesanan anda._\n\nNama: ".$user->name."\nEmail: ".$user->email."\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \nNomor Transaksi: ".$transaction->code."\nNama Try Out: ".$transaction->exam->title."\nTotal Pembayaran: *".$this->formatPrice($transaction->total_purchases)."*\n\n- - - - - - - - - - - - - - - - - - - - - - - - - - - -\n\n*TOKEN ANDA*\n*".$transaction->voucher_token."*\n\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \n\n_Silakan masukan token untukk aktivasi status member anda di menu *AKTIVASI VOUCHER*. pastikan token di copy paste, jangan diketik, untuk menghindari kesalahan._\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu dalam proses belajar anda. aamiin._";
                sendWhatsappNotification($user->student->phone_number, $message);    
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
            $message = "*[TRANSAKSI e-TOEFL]*\n\n_Hallo, saya admin dari e-TOEFL, berikut adalah detail pesanan anda._\n\nNama: ".$user->name."\nEmail: ".$user->email."\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \nNomor Transaksi: ".$transaction->code."\nNama Try Out: ".$exam->title."\nTotal Pembayaran: *".$this->formatPrice($transaction->total_purchases)."*\n\n- - - - - - - - - - - - - - - - - - - - - - - - - - - -\n\n*TOKEN ANDA*\n*".$transaction->voucher_token."*\n\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \n\n_Silakan masukan token untukk aktivasi status member anda di menu *AKTIVASI VOUCHER*. pastikan token di copy paste, jangan diketik, untuk menghindari kesalahan._\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu dalam proses belajar anda. aamiin._";
        } else {
            $message = "*Mohon dibaca dan dipahami!*\n\n_Hallo, saya admin dari e-TOEFL, berikut adalah detail pesanan anda._\n\nNama: ".$user->name."\nEmail: ".$user->email."\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \nNomor Transaksi: ".$transaction->code."\nNama Try Out: ".$exam->title."\nTotal Pembayaran: *".$this->formatPrice($transaction->total_purchases)."*\nMaksimal Pembayaran: ".$transaction->maximum_payment_time."\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - \n\n*_token voucher akan dikirimkan setelah anda membayar ke nomor rekening yang terdaftar, silakan konfirmasi dan kirim bukti pembayaran ke Whatsapp ini._*\n\n*_untuk FREE MEMBER tidak melakukan pembayaran dan token akan otomatis dikirim_*\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu dalam proses belajar anda. aamiin._";
        }

        sendWhatsappNotification($user->student->phone_number, $message);

        $admin = User::find('20b2a4122c614bb68e41b1a6f3f37780');
        $admin->notify(new SendTransactionNotification($transaction));

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
