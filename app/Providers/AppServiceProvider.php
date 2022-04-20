<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\HospitalRepository;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use App\Repositories\Eloquent\PeopleRepository;
use App\Repositories\Interfaces\PeopleRepositoryInterface;
<<<<<<< HEAD
use App\Repositories\Eloquent\FolderRepository;
use App\Repositories\Interfaces\FolderRepositoryInterface;
=======
use App\Repositories\Eloquent\KeyWordRepository;
use App\Repositories\Interfaces\KeyWordRepositoryInterface;
use App\Repositories\Eloquent\MediaKeyWordRepository;
use App\Repositories\Interfaces\MediaKeyWordRepositoryInterface;
>>>>>>> 31d8ba868f3c0d80e05240cfa3eecfb7a682c5e6

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
<<<<<<< HEAD
            FolderRepositoryInterface::class,
            FolderRepository::class
=======
            KeyWordRepositoryInterface::class,
            KeyWordRepository::class
        );

        $this->app->bind(
            MediaKeyWordRepositoryInterface::class,
            MediaKeyWordRepository::class
>>>>>>> 31d8ba868f3c0d80e05240cfa3eecfb7a682c5e6
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
