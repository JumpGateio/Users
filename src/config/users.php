<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Load Views
    |--------------------------------------------------------------------------
    |
    | NukaCode Users comes with some default bootstrap 3 view files to make
    | getting started quicker.  If you don't want these to load, set
    | this value to false.
    |
    */
    'load_views'    => true,

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
    | Social Authentication Details
    |--------------------------------------------------------------------------
    |
    | If using social authentication, specify the driver being used here.  You
    | can also specify any additional scopes or extras you need here.
    | Setting the enable_Social flag to true will change the existing routes
    | to their social counterparts.
    |
    */
    'enable_social' => false,
    'providers'     => [
        [
            'driver' => null,
            'scopes' => [],
            'extras' => [],
        ],
    ],
];
