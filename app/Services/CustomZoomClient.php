<?php

namespace App\Services;

use Jubaer\Zoom\Zoom;
use GuzzleHttp\Client;
use Exception;

class CustomZoomClient extends Zoom
{
    protected $clientId;
    protected $clientSecret;
    protected $accountId;
    private $credentialsSet = false;

    public function __construct()
    {
        // Don't call parent constructor to avoid automatic initialization
    }

    public function setCredentials($clientId, $clientSecret, $accountId)
    {
        if (empty($clientId) || empty($clientSecret) || empty($accountId)) {
            throw new Exception('Invalid Zoom credentials');
        }

        $this->clientId = trim($clientId);
        $this->clientSecret = trim($clientSecret);
        $this->accountId = trim($accountId);

        // Set parent class properties
        $this->client_id = $this->clientId;
        $this->client_secret = $this->clientSecret;
        $this->account_id = $this->accountId;

        $this->credentialsSet = true;
        $this->initializeClient();
    }

    protected function initializeClient()
    {
        if (!$this->credentialsSet) {
            throw new Exception('Credentials must be set before initializing client');
        }

        // Don't use cache for debugging - fetch fresh token each time
        $this->accessToken = $this->fetchAccessToken();

        $this->client = new Client([
            'base_uri' => 'https://api.zoom.us/v2/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function fetchAccessToken()
    {
        if (!$this->credentialsSet) {
            throw new Exception('Credentials must be set before fetching token');
        }

        $authString = $this->clientId . ':' . $this->clientSecret;
        $authHeader = 'Basic ' . base64_encode($authString);

        $client = new Client([
            'headers' => [
                'Authorization' => $authHeader,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        try {
            // Test the exact same request that works in Postman
            $response = $client->request('POST', 'https://zoom.us/oauth/token', [
                'form_params' => [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ],
                'http_errors' => false, // Don't throw exception to get more details
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            if ($statusCode !== 200) {
                throw new Exception("Token request failed with status {$statusCode}: {$responseBody}");
            }

            $responseData = json_decode($responseBody, true);

            if (!isset($responseData['access_token'])) {
                throw new Exception("No access token in response: {$responseBody}");
            }

            return $responseData['access_token'];
        } catch (\Exception $e) {
            throw new Exception('Token fetch failed: ' . $e->getMessage());
        }
    }

    public function getAccessTokenPublic()
    {
        try {
            if (empty($this->accessToken) || !$this->credentialsSet) {
                $this->initializeClient();
            }

            return $this->accessToken;
        } catch (\Exception $e) {
            throw new Exception('Failed to get token: ' . $e->getMessage());
        }
    }

    public function createMeeting(array $data)
    {
        if (!$this->credentialsSet) {
            throw new Exception('Credentials must be set before creating meeting');
        }
        return parent::createMeeting($data);
    }

    public function updateMeeting(string $meetingId, array $data)
    {
        if (!$this->credentialsSet) {
            throw new Exception('Credentials must be set before updating meeting');
        }
        return parent::updateMeeting($meetingId, $data);
    }

    public function deleteMeeting(string $meetingId)
    {
        if (!$this->credentialsSet) {
            throw new Exception('Credentials must be set before deleting meeting');
        }
        return parent::deleteMeeting($meetingId);
    }
}
