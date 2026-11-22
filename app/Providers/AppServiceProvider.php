<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (
            $this->app->environment('production') ||
            str_starts_with((string) config('app.url'), 'https://') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTP_CF_VISITOR']) && str_contains($_SERVER['HTTP_CF_VISITOR'], 'https'))
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $models = [
            #Master data
            'MasterData\Category',
            'MasterData\Student',
            'MasterData\Announcement',
            'MasterData\Faq',
            #Lesson
            'Lesson\ValueCategory',
            'Lesson\DetailValueCategory',
            'Lesson\QuestionTitle',
            'Lesson\Question',
            #User
            'User\User',
            #Transaction
            'Transaction\Bank',
            'Transaction\Transaction',
            #Region
            'Region\Region',
            #Exam
            'Exam\Exam',
            'Exam\Grade',
        ];

        foreach ($models as $model) {
            $this->app->bind("App\Services\Contracts\\{$model}Interface", "App\Services\\{$model}Service");
        }
    }
}
