<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar', 500)->nullable()->after('google_id');
            }
        });

        // Make password nullable for OAuth users via safe MySQL alter statement
        try {
            DB::statement("ALTER TABLE `users` MODIFY `password` VARCHAR(191) NULL");
        } catch (\Throwable $e) {}

        // Make student region and profile fields nullable for seamless 1-click Google OAuth registration
        try {
            DB::statement("ALTER TABLE `students` MODIFY `province_id` BIGINT UNSIGNED NULL");
            DB::statement("ALTER TABLE `students` MODIFY `city_id` BIGINT UNSIGNED NULL");
            DB::statement("ALTER TABLE `students` MODIFY `district_id` BIGINT UNSIGNED NULL");
            DB::statement("ALTER TABLE `students` MODIFY `village_id` BIGINT UNSIGNED NULL");
            DB::statement("ALTER TABLE `students` MODIFY `address` TEXT NULL");
            DB::statement("ALTER TABLE `students` MODIFY `gender` ENUM('F','M') NULL");
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
