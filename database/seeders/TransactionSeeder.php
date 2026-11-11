<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::insert([
            [
                "id" => "c15ea12a-0ac8-424b-9465-77f777f6dde4",
                "created_at" => "2023-05-28 12:28:21",
                "updated_at" => "2023-05-28 12:28:21",
                "deleted_at" => null,
                "user_id" => "62bcbaec-82a9-44f5-985b-e5abcc83ccca",
                "exam_id" => "49e1686d-0433-4d68-8040-9019ce3ef0e2",
                "code" => "INV.230528.0001",
                "voucher_activated" => 1,
                "voucher_used" => 1,
                "total_purchases" => 99000,
                "maximum_payment_time" => "2023-05-30 12:28:21",
                "transaction_status" => "done",
                "voucher_token" => "Q9P9XDSNFML3SAU"
            ],
            [
                "id" => "c15ea12a-0ac8-424b-9465-77f777f6dde5",
                "created_at" => "2023-05-28 12:28:21",
                "updated_at" => "2023-05-28 12:28:21",
                "deleted_at" => null,
                "user_id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccb",
                "exam_id" => "49e1686d-0433-4d68-8040-9019ce3ef0e2",
                "code" => "INV.230528.0001",
                "voucher_activated" => 1,
                "voucher_used" => 1,
                "total_purchases" => 99000,
                "maximum_payment_time" => "2023-05-30 12:28:21",
                "transaction_status" => "done",
                "voucher_token" => "Q9P9XDSNFML3SAL"
            ],
            [
                "id" => "c15ea12a-0ac8-424b-9465-77f777f6dde6",
                "created_at" => "2023-05-28 12:28:21",
                "updated_at" => "2023-05-28 12:28:21",
                "deleted_at" => null,
                "user_id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccc",
                "exam_id" => "49e1686d-0433-4d68-8040-9019ce3ef0e2",
                "code" => "INV.230528.0001",
                "voucher_activated" => 1,
                "voucher_used" => 1,
                "total_purchases" => 99000,
                "maximum_payment_time" => "2023-05-30 12:28:21",
                "transaction_status" => "done",
                "voucher_token" => "Q9P9XDSNFML3SAZ"
            ],
            [
                "id" => "c15ea12a-0ac8-424b-9465-77f777f6dde7",
                "created_at" => "2023-05-28 12:28:21",
                "updated_at" => "2023-05-28 12:28:21",
                "deleted_at" => null,
                "user_id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccd",
                "exam_id" => "49e1686d-0433-4d68-8040-9019ce3ef0e2",
                "code" => "INV.230528.0001",
                "voucher_activated" => 1,
                "voucher_used" => 1,
                "total_purchases" => 99000,
                "maximum_payment_time" => "2023-05-30 12:28:21",
                "transaction_status" => "done",
                "voucher_token" => "Q9P9XDSNFML3SAZ"
            ],
            [
                "id" => "c15ea12a-0ac8-424b-9465-77f777f6dde8",
                "created_at" => "2023-05-28 12:28:21",
                "updated_at" => "2023-05-28 12:28:21",
                "deleted_at" => null,
                "user_id" => "62bcbaec-82a9-44f5-985b-e5abcc83ccce",
                "exam_id" => "49e1686d-0433-4d68-8040-9019ce3ef0e2",
                "code" => "INV.230528.0001",
                "voucher_activated" => 1,
                "voucher_used" => 1,
                "total_purchases" => 99000,
                "maximum_payment_time" => "2023-05-30 12:28:21",
                "transaction_status" => "done",
                "voucher_token" => "Q9P9XDSNFML3SAZ"
            ],
            [
                "id" => "c15ea12a-0ac8-424b-9465-77f777f6dde9",
                "created_at" => "2023-05-28 12:28:21",
                "updated_at" => "2023-05-28 12:28:21",
                "deleted_at" => null,
                "user_id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccf",
                "exam_id" => "49e1686d-0433-4d68-8040-9019ce3ef0e2",
                "code" => "INV.230528.0001",
                "voucher_activated" => 1,
                "voucher_used" => 1,
                "total_purchases" => 99000,
                "maximum_payment_time" => "2023-05-30 12:28:21",
                "transaction_status" => "done",
                "voucher_token" => "Q9P9XDSNFML3SAZ"
            ],
        ]);
    }
}
