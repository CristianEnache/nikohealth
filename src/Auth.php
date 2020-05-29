<?php


namespace Nikohealth;
use kamermans\OAuth2\GrantType\ClientCredentials;
use kamermans\OAuth2\OAuth2Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

class Auth {

    /**
     * @return GuzzleHttp Client
     */
    public static function getHttpClient(){


        // Authorization client - this is used to request OAuth access tokens
        $reauth_client = new Client([
            // URL for access_token request
            'base_uri' => config('app.niko_health_api_url') . '/identity/connect/token',
        ]);

        $reauth_config = [
            "client_id" => config('app.niko_health_client_id'),
            "client_secret" => config('app.niko_health_client_secret')
        ];

        $grant_type = new ClientCredentials($reauth_client, $reauth_config);
        $oauth = new OAuth2Middleware($grant_type);

        $stack = HandlerStack::create();
        $stack->push($oauth);

        // This is the normal Guzzle client that you use in your application
        $guzzle_http_client = new Client([
            'handler' => $stack,
            'auth'    => 'oauth',
        ]);

        return $guzzle_http_client;
    }

}
