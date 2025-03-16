<?php

namespace App\Services\Admin\Activities;

use Jubaer\Zoom\Zoom as BaseZoom;

class CustomZoomService extends BaseZoom
{
    public function setCustomAuth($clientId, $clientSecret, $accountId)
    {
        $this->client_id = $clientId;
        $this->client_secret = $clientSecret;
        $this->account_id = $accountId;

        // Refresh access token
        $this->accessToken = $this->getAccessToken();
    }
}
