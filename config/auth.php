<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'apiAdmin',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'members',
        ],

        'apiAdmin' => [
            'driver' => 'jwt',
            'provider' => 'members',
        ],

        'apiFront' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'members' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Member::class
        ],

        'users' => [
             'driver' => 'eloquent',
             'model' => \App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],


    'jwt' => [
        'inputKey' => 'token', //识别标识
        'iss' => env('APP_NAME','coca-admin'), //issuer 请求实体，可以是发起请求的用户的信息，也可是jwt的签发者。
        'exp' => env('JWT_EXP',0), //expire 指定token的生命周期。单位秒 0 标识永久有效
        'nbf' => env('JWT_NBF',0), //not before。多少秒之后token才有效。单位秒
        'type' => env('JWT_TYPE','HS256'),
        'privateKey' => env('JWT_PRIVATE_KEY',''),
        'publicKey' => env('JWT_PUBLIC_KEY','')
    ]

];
