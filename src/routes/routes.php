<?php

Route::group(['middleware' => 'web'], function () {
    require('activation.php');
    require('authentication.php');
    require('forgot-password.php');
    require('logout.php');
    require('registration.php');
});
