<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProviderController extends Controller
{
    protected  $scopes = [
        'https://www.googleapis.com/auth/blogger',
        'https://www.googleapis.com/auth/calendar'
    ];

    public function index()
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => '324022333218-20bjndidmt1fv8ro9t6kl6f873akkg1g.apps.googleusercontent.com',    // The client ID assigned to you by the provider
            'clientSecret'            => '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',    // The client password assigned to you by the provider
            'redirectUri'             => 'http://127.0.0.1:8000/oauth/gmail/callback/',
            'urlAuthorize' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'urlAccessToken' => 'https://oauth2.googleapis.com/token',
            'urlResourceOwnerDetails' => 'https://www.googleapis.com/oauth2/v3/userinfo',
            'scope'=>"read:user",
        ]);

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Optional, only required when PKCE is enabled.
            // Get the PKCE code generated for you and store it to the session.
            $_SESSION['oauth2pkceCode'] = $provider->getPkceCode();

            // Redirect the user to the authorization URL.
            header('Location: ' . $authorizationUrl);
            exit;

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || empty($_SESSION['oauth2state']) || $_GET['state'] !== $_SESSION['oauth2state']) {

            if (isset($_SESSION['oauth2state'])) {
                unset($_SESSION['oauth2state']);
            }

            exit('Invalid state');

        } else {

            try {

                // Optional, only required when PKCE is enabled.
                // Restore the PKCE code stored in the session.
                $provider->setPkceCode($_SESSION['oauth2pkceCode']);

                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                // We have an access token, which we may use in authenticated
                // requests against the service provider's API.
                echo 'Access Token: ' . $accessToken->getToken() . "<br>";
                echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
                echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
                echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

                // Using the access token, we may look up details about the
                // resource owner.
                $resourceOwner = $provider->getResourceOwner($accessToken);

                var_export($resourceOwner->toArray());

                // The provider provides a way to get an authenticated API request for
                // the service, using the access token; it returns an object conforming
                // to Psr\Http\Message\RequestInterface.
                $request = $provider->getAuthenticatedRequest(
                    'GET',
                    'https://www.googleapis.com/oauth2/v3/userinfo',

                    $accessToken
                );

            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

                // Failed to get the access token or user details.
                exit($e->getMessage());

            }

        }
    }
}