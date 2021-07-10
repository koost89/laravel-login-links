<?php

return [
    /**
     * In this file you can configure parts of the login.
     * OTL uses Laravels built in signedRoute functionality to generate signedURLs for users.
     */
    'route' => [
        /*
         * Here you can specify the path which is used to generate and authenticate the user on.
         * The finished URL should look like domain.tld/<path>
         */
        'path' => '/uli',

        /*
         * The amount (in seconds) it takes for the generated URL to expire.
         * Afterwards it simply returns a 404 for the user.
         */
        'expiration' => 60 * 2,

        /*
         * The path the user gets redirected to after the login has finished.
         */
        'redirect_after_login' => '/',

        /*
         * If the token should expire after the first visit.
         * If set to true you must run the migration for the one_time_logins table.
         * If set to false the link can be used until it is expired.
         */
        'expire_after_visit' => true,
    ],

    'auth' => [
        /*
         * If you are using a different guard, you can specify it below.
         */
        'guard' => 'web',

        /*
         * Dictates if the application should remember the user.
         */
        'remember' => true,

        /*
         * The response code that should be returned to the user when the signature is invalid.
         * This can be useful if you want it to look like the page has not been found for example.
         */
        'invalid_signature_response' => 403,
    ]
];
