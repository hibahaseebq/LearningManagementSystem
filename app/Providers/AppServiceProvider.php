<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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

		$this->app->bind(
			\App\Interfaces\AuthServiceInterface::class,
			\App\Services\AuthService::class
		);

		$this->app->bind(
			\App\Interfaces\AdminServiceInterface::class,
			\App\Services\AdminService::class
		);

		$this->app->bind(
			\App\Interfaces\StudentServiceInterface::class,
			\App\Services\StudentService::class
		);

		$this->app->bind(
			\App\Interfaces\NotificationServiceInterface::class,
			\App\Services\NotificationService::class
		);

		$this->app->bind(
			\App\Interfaces\PasswordSetupServiceInterface::class,
			\App\Services\PasswordSetupService::class
		);

		$this->app->bind(
			\App\Interfaces\QuizAssignmentServiceInterface::class,
			\App\Services\QuizAssignmentService::class
		);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
