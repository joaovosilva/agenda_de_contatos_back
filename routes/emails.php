<?php

use Illuminate\Support\Facades\Route;

Route::get('sendWelcome', 'EmailController@welcomeEmail');