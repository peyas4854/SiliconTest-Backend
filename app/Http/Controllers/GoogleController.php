<?php

namespace App\Http\Controllers;

use Exception;
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
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
            $authUrl = $provider->getAuthorizationUrl(
                [
                    'scope' => [
                        'https://www.googleapis.com/auth/gmail.send'
                    ],
                ]
            );
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

    public function sendMail()
    {
        $sender = 'demo@gmail.com';
        $to = 'peyaschandra@gmail.com';
        $subject= 'This email is sending via test application';
        $messageText ='This is the another body.';
        $client = new Google_Client();
        $client->setClientId('324022333218-20bjndidmt1fv8ro9t6kl6f873akkg1g.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://127.0.0.1:8000/oauth/gmail/callback');
        $client->setAccessToken('ya29.a0AWY7CknDWvTP_rXyijLmCVBfoeDfiLjciLCqWNnZ_9X_mmFqoW2vyKImCn6iHhhAyHhK1FDbBNKnWVqiNdc8kboCC7jMHXf8oaN77j3POLwaQ0uVLWQxigSCb8u3uHivgqMXZH3pLSE8WCYc9Bx5QTgeV6zoaCgYKAcoSARASFQG1tDrp--lBytWCFeD6AMk0yZdB-A0163');
        $client->addScope('https://www.googleapis.com/auth/gmail.send');
        $service = new Google_Service_Gmail($client);
        $email = new Google_Service_Gmail_Message();
//        $email->setRaw(base64_encode("From: demo@gmail.com\r\nTo: peyaschandra@gmail.com\r\nSubject: Test Subject\r\n\r\nThis is the another body."));

        $rawMessageString = "From: <{$sender}>\r\n";
        $rawMessageString .= "To: <{$to}>\r\n";
        $rawMessageString .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
        $rawMessageString .= "MIME-Version: 1.0\r\n";
        $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n";
        $rawMessageString .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
        $rawMessageString .= "{$messageText}\r\n";
        $rawMessage = strtr(base64_encode($rawMessageString), array('+' => '-', '/' => '_'));
        $email->setRaw($rawMessage);

      // Send the email
        $message = $service->users_messages->send('me', $email);

     // Output the message ID
        echo 'Message ID: ' . $message->getId();

        dd('send message',$message->getId());


    }
}
