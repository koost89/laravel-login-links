<?php

return [
    /**
     * In this file you can configure parts of login-links.
     */
    'route' => [
        /**
         * Here you can specify the path which is used to generate and authenticate the user on.
         */
        'path' => '/uli',

        /**
         * The amount (in seconds) it takes for the generated URL to expire.
         * Afterwards it simply returns a 404 for the user.
         */
        'expiration' => 60 * 2,

        /**
         * The path the user gets redirected to after the login has finished.
         */
        'redirect_after_login' => '/',

        /**
         * If the token should expire after the first visit.
         * If set to true you must run the migration for the one_time_logins table.
         * If set to false the link can be used until it is expired.
         */
        'expire_after_visit' => true,

        /**
         * Extra middleware you would like to add to the route
         */
        'additional_middleware' => [
            // Middleware\AdminUser::class,
        ]
    ],

    'auth' => [
        /**
         * If you are using a different guard, you can specify it below.
         */
        'guard' => 'web',

        /**
         * Dictates if the application should remember the user.
         */
        'remember' => false,
    ]
];
