<?php

namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;

class Chorck extends AbstractProvider
{
    //public $scopes = ['r_basicprofile r_emailaddress r_contactinfo'];
    public $responseType = 'json';
    public $authorizationHeader = 'Bearer';
    public $fields = [
        'id', 'email-address', 'first-name', 'last-name', 'headline',
        'location', 'industry', 'picture-url', 'public-profile-url',
    ];

    public function urlAuthorize()
    {
        return 'http://local.chorck.com/OAuth2/Authorize';
    }

    public function urlAccessToken()
    {
        return 'http://local.chorck.com/OAuth2/Token';
    }

    public function urlUserDetails(AccessToken $token)
    {
        //$fields = implode(',', $this->fields);
        return 'http://local.chorck.com/OAuth2/Resource';
    }

    public function userDetails($response, AccessToken $token)
    {
        $user = new User();

        $email = (isset($response->Email)) ? $response->Email : null;
        $firstName = (isset($response->FirstName)) ? $response->FirstName : null;
        $surname = (isset($response->Surname)) ? $response->Surname : null;

        $user->exchangeArray([
            'uid' => $response->id,
            'firstname' => $firstName,
            'surname' => $surname,
            'email' => $email
        ]);

        return $user;
    }

    public function userUid($response, AccessToken $token)
    {
        return $response->id;
    }

    public function userEmail($response, AccessToken $token)
    {
        return isset($response->emailAddress) && $response->emailAddress
            ? $response->emailAddress
            : null;
    }

    public function userScreenName($response, AccessToken $token)
    {
        return [$response->firstName, $response->lastName];
    }
}
