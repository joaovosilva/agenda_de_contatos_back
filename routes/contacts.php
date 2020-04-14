<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'ContactsController@registerContact');
Route::get('getContact/{contact_id}', 'ContactsController@getContactById');
Route::get('getUserContacts/{user_fk}', 'ContactsController@getUserContacts');
Route::delete('deleteContact/{contact_id}', 'ContactsController@deleteContact');
Route::get('sendMail', 'EmailController@sendMail');
