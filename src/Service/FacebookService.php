<?php

namespace App\Service;

use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Request;

class FacebookService
{
    private $facebook;

    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => $_ENV['FACEBOOK_APP_ID'],
            'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
            'default_graph_version' => 'v12.0',
        ]);
    }

    public function getFacebookLoginUrl(string $redirectUri): string
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        return $helper->getLoginUrl($redirectUri, $permissions);
    }

    public function getUserFromAccessToken(string $accessToken)
    {
        try {
            $response = $this->facebook->get('/me?fields=id,name,email', $accessToken);
            return $response->getGraphUser();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Graph returned an error: ' . $e->getMessage());
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK returned an error: ' . $e->getMessage());
        }
    }

    public function getFacebookUser(Request $request): ?array
    {
        $code = $request->query->get('code');
        if (!$code) return null;

        $accessToken = $this->exchangeCodeForAccessToken($code);
        if (!$accessToken) return null;

        return $this->getUserFromAccessToken($accessToken);
    }

    private function exchangeCodeForAccessToken(string $code): ?string
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
            return $accessToken ? $accessToken->getValue() : null;
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Error getting access token: ' . $e->getMessage());
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Error getting access token: ' . $e->getMessage());
        }
    }
}
