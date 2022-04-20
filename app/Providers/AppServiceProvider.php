<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\HospitalRepository;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use App\Repositories\Eloquent\DoctorRepository;
use App\Repositories\Interfaces\DoctorRepositoryInterface;
use App\Repositories\Eloquent\KeyWordRepository;
use App\Repositories\Interfaces\KeyWordRepositoryInterface;
use App\Repositories\Eloquent\MediaKeywordRepository;
use App\Repositories\Interfaces\MediaKeywordRepositoryInterface;
use App\Repositories\Eloquent\FamilyRepository;
use App\Repositories\Interfaces\FamilyRepositoryInterface;

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
            DoctorRepositoryInterface::class,
            DoctorRepository::class
        );

        $this->app->bind(
            FamilyRepositoryInterface::class,
            FamilyRepository::class
        );

        $this->app->bind(
            KeyWordRepositoryInterface::class,
            KeyWordRepository::class
        );

        $this->app->bind(
            MediaKeywordRepositoryInterface::class,
            MediaKeywordRepository::class
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
