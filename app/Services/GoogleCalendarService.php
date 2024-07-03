<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendarService
{
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    
    public function createEvent($summary, $start, $end)
    {
        $service = new Google_Service_Calendar($this->client);

        $event = new Google_Service_Calendar_Event([
            'summary' => $summary,
            'start' => ['dateTime' => $start->format('c')],
            'end' => ['dateTime' => $end->format('c')],
        ]);

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);

        return $event->htmlLink;
    }
}