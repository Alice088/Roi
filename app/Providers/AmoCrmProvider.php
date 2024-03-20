<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;

class AmoCrmProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singletonIf(AmoCRMApiClient::class, function () {
            return (new AmoCRMApiClient())
                ->setAccessToken(new LongLivedAccessToken(env("AMOCRM_LONG_ACCESS_TOKEN")))
                ->setAccountBaseDomain(env("AMOCRM_USER_DOMAIN"));
        });
    }
}
