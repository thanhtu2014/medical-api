<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\HospitalRepository;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use App\Repositories\Eloquent\PeopleRepository;
use App\Repositories\Interfaces\PeopleRepositoryInterface;
use App\Repositories\Eloquent\KeyWordRepository;
use App\Repositories\Interfaces\KeyWordRepositoryInterface;
use App\Repositories\Eloquent\MediaKeyWordRepository;
use App\Repositories\Interfaces\MediaKeyWordRepositoryInterface;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Interfaces\ScheduleRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            HospitalRepositoryInterface::class,
            HospitalRepository::class
        );

        $this->app->bind(
            PeopleRepositoryInterface::class,
            PeopleRepository::class
        );

        $this->app->bind(
            KeyWordRepositoryInterface::class,
            KeyWordRepository::class
        );

        $this->app->bind(
            MediaKeyWordRepositoryInterface::class,
            MediaKeyWordRepository::class
        );

        $this->app->bind(
            ScheduleRepositoryInterface::class,
            ScheduleRepository::class
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
