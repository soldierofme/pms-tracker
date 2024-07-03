<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Google_Client::class, function ($app) {
            $client = new Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setRedirectUri(config('services.google.redirect_uri'));
            $client->addScope(Google_Service_Calendar::CALENDAR);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            return $client;
        });

        $this->app->singleton(Google_Service_Calendar::class, function ($app) {
            $client = $app->make(Google_Client::class);
            return new Google_Service_Calendar($client);
        });
    }

    public function boot()
    {
        //
    }
}