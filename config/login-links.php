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
         * After how many visits the token should expire
         * After the specified amount visits, the token is immediately deleted from the database.
         */
        'allowed_visits_before_expiration' => 1,

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
