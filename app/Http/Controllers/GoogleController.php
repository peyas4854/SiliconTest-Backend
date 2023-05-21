<?php

namespace App\Http\Controllers;
use Exception;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;



class GoogleController extends Controller
{
    public function index()
    {
        session_start();
        $provider = new Google([
            'clientId' => '324022333218-20bjndidmt1fv8ro9t6kl6f873akkg1g.apps.googleusercontent.com',
            'clientSecret' => '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',
            'redirectUri' => 'http://127.0.0.1:8000/oauth/gmail/callback',
            // optional; used to restrict access to users on your G Suite/Google Apps for Business accounts
        ]);
        if (!empty($_GET['error'])) {

            // Got an error, probably user denied access
            exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

        } elseif (empty($_GET['code'])) {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;

        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

            // State is invalid, possible CSRF attack in progress
            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the owner details
                $ownerDetails = $provider->getResourceOwner($token);

                // Use these details to create a new profile
//                printf('Hello %s!', $ownerDetails->getFirstName());
//                echo $ownerDetails->getFirstName() . "<br>";

            } catch (Exception $e) {

                // Failed to get user details
                exit('Something went wrong: ' . $e->getMessage());

            }

            // Use this to interact with an API on the users behalf
            echo $token->getToken() . "<br>";

            // Use this to get a new access token if the old one expires
//            echo $token->getRefreshToken() . "<br>";

            // Unix timestamp at which the access token expires
//            echo $token->getExpires() . "<br>";
        }
    }

    public function refreshToken()
    {
        $provider = new Google([
            'clientId' => '324022333218-20bjndidmt1fv8ro9t6kl6f873akkg1g.apps.googleusercontent.com',
            'clientSecret' => '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',
            'redirectUri' => 'http://127.0.0.1:8000/oauth/gmail/callback',
        ]);

        $grant = new RefreshToken();
        $token = $provider->getAccessToken($grant, ['refresh_token' => 'ya29.a0AWY7Ckmx3MGLsJScCgL7rkAlhHvZT_mOtZIV0OV8LAZog6Ecs9GAfM6tAlyarKRNmIRFoKIpGnMdWeAGQnodwfsc3rct8zMkbScHFSTiVAl6qYiImKaOW9AmWABrQH3I46zDV5PNdes9vUDTzbDhkh7s2t1baCgYKAV0SARASFQG1tDrp4kd380c4NhF8w42nu465lg0163
']);

        dd($token);
    }
}
