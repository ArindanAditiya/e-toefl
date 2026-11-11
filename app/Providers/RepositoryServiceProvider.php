<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $models = [
            #Master Data
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
            'Region\Province',
            'Region\City',
            'Region\District',
            'Region\Village',
            #Exam
            'Exam\Exam',
            'Exam\Grade',
        ];

        foreach ($models as $model) {
            $this->app->bind("App\Repositories\Contracts\\{$model}Interface", "App\Repositories\\{$model}Repository");
        }
    }
}
