<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Load Views
    |--------------------------------------------------------------------------
    |
    | JumpGate Users comes with some default view files to make getting started
    | quicker.  If you don't want these to load, set this value to false.
    |
    */

    'load_views' => true,

    /*
    |--------------------------------------------------------------------------
    | Require email activation
    |--------------------------------------------------------------------------
    |
    | When a user signs up you can require them to verify their email to activate
    | their account.  This is on by default, but you can turn it off by setting
    | the below value to false.
    |
    */

    'require_email_activation' => true,

    /*
    |--------------------------------------------------------------------------
    | Allow user invitations
    |--------------------------------------------------------------------------
    |
    | If you want to lock down your site and allow access through invitation
    | only, set this value to true.  It will enable routes and database
    | columns.  Make sure you set this before running the user migrations.
    |
    */

    'allow_invitations' => false,

    /*
    |--------------------------------------------------------------------------
    | Automatic blocking
    |--------------------------------------------------------------------------
    |
    | While you can manually block a user by calling the block() method, you
    | may often want to automate some blocking rules.  You can do this below.
    | Each entry must contain what column is being checked and the value.
    | Set any column this to zero to disable that specific rule.
    |
    | Available checks: failed_login_attempts
    |
    */

    'blocking' => [
        'failed_login_attempts' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Group
    |--------------------------------------------------------------------------
    |
    | When a user signs up, this is the group they will be automatically
    | assigned to.  This should match the name column of the group.
    |
    */

    'default_group' => 'guest',

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    |
    | when redirecting a user to login, we don't know which route to send
    | them to.  This lets us know what route to aim for.
    |
    */

    'default_route' => [
        'name'    => 'auth.login',
        'options' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Authentication Details
    |--------------------------------------------------------------------------
    |
    | If using social authentication, specify the driver being used here.  You
    | can also specify any additional scopes or extras you need here.
    | Setting the enable_social flag to true will add social routes.
    |
    | Note: You must have at least one provider or you will get an exception.
    |
    */

    'enable_social' => false,

    'providers' => [
        [
            'driver' => null,
            'scopes' => [],
            'extras' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Only
    |--------------------------------------------------------------------------
    |
    | If using social authentication by setting enable_social to true, you can
    | allow social to be the only authentication, or exist alongside standard
    | auth.  Set the following to true to force only social logins.
    |
    | Once you set this to true, make sure to remove any routes pointing to
    | the non-social versions (ie auth.login and auth.register)
    |
    */

    'social_auth_only' => false,
];
