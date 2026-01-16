<?php

$router->get('/', 'HomeController@index');

// Auth routes
$router->get('/register', 'UserController@showRegisterForm');
$router->post('/register', 'UserController@register');
$router->get('/login', 'UserController@showLoginForm');
$router->post('/login', 'UserController@login');
$router->post('/logout', 'UserController@logout');

// Users
$router->get('/users', 'UserController@listUsers');

// User Dashboard
$router->get('/dashboard', 'UserController@showDashboard')->only('auth');
$router->post('/dashboard/update', 'UserController@updateProfile')->only('auth');
$router->post('/dashboard/delete', 'UserController@deleteAccount')->only('auth');

