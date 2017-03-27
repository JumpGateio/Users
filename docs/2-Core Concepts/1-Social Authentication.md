# Social Authentication

## Prerequisites
`composer require laravel/socialite`

Regardless of whether you use a built in driver or one from [Socialite Providers](https://socialiteproviders.github.io/), 
you will need this package.

# Socialite Set up
Add the socialite provider to your providers.

```
'providers' => [
    // Other service providers...

    Laravel\Socialite\SocialiteServiceProvider::class,
],
```

Add the facade to your aliases.

```
'Socialite' => Laravel\Socialite\Facades\Socialite::class,
```

Lastly, add your OAUTH details to `config/services.php`.  You will need the key, secret and redirect url.  Some providers 
provided by Socialite Providers will require extra steps.  Make sure to read that specific providers documentation.

> You will need to do this last step for each provider you intend to use.

## Database
By default, this package wont install any social tables in the database until you set `enable_social` to `true` in your 
`config/jumpgate/users.php`.  If you enable this after running the initial migrations, its not a problem, just 
set it to true and then run `php artisan migrate` and it will now add them.

> Running the social migration also sets the password field on the `users` table to nullable.

When a user logs in through a social provider it will create a new row in that table for that provider.  It stores the 
`social_id` (the user's ID with that provider) and the `avatar` (any avatar the user may have set for them).  So if you 
allow multiple providers, a user will have multiple records in this table that you can use as needed.

It will also log the last provider the user logged in through in the `users` table on the `authenticated_as` column.

## Models
Now that you have a new database, you should create a link for the `User` model to access the `Social` model.  We have 
done this for you in that you just need to have your User model use the `JumpGate\Users\Traits\HasSocials` trait and you're 
done.

> Make sure you add this since the login process expects the `addSocial()` and `hasProvider()` methods to exist on the 
user model.

# How To Use It

1. Add the driver(s) to `config/jumpgate/users.php` in the `providers` array.
    * You can add any scopes you need or any extra details here as well.
    * Do this for all providers you want to allow.
2. Add a link for the user to log into this provider.
    * Link should point to `{{ route('auth.social.login', [$provider]) }}`.
    * The callback link should point to `route('auth.social.callback', [$provider])`.
3. The controllers will handle the rest.

## Example
**HTML**
```
    <a href="{{ route('auth.social.login', ['google']) }}">Log in with Google</a>
    <a href="{{ route('auth.social.login', ['github']) }}">Log in with Github</a>
```

**Config**
```
    'providers' => [
        [
            'driver' => 'google',
            'scopes' => [
                'https://www.googleapis.com/auth/userinfo.email',
                'https://www.googleapis.com/auth/calendar',
            ],
            'extras' => [
                'approval_prompt' => 'auto',
                'access_type'     => 'offline',
            ],
        ],
        [
            'driver' => 'github',
            'scopes' => [],
            'extras' => [],
        ],
    ]
```
