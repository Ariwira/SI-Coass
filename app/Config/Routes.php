<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth Routes
$routes->get('/', 'Auth::index');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/processLogin', 'Auth::processLogin');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/access-denied', 'Auth::accessDenied');


// Admin Routes (protected by filter)
$routes->group('admin', ['filter' => 'auth:Admin'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Routes (Mahasiswa Coass)
    $routes->get('mahasiswa-coass', 'Admin\Mahasiswa::index');
    $routes->get('mahasiswa-coass/tambah-mahasiswa', 'Admin\Mahasiswa::create');
    $routes->post('mahasiswa-coass/store', 'Admin\Mahasiswa::store');
    $routes->get('mahasiswa-coass/edit-mahasiswa/(:segment)', 'Admin\Mahasiswa::edit/$1');
    $routes->post('mahasiswa-coass/update/(:segment)', 'Admin\Mahasiswa::update/$1');
    $routes->post('mahasiswa-coass/delete-mahasiswa/(:segment)', 'Admin\Mahasiswa::delete/$1');

    // Routes untuk Dokter
    $routes->get('dokter', 'Admin\Doctor::index');
    $routes->get('dokter/tambah-dokter', 'Admin\Doctor::create');
    $routes->post('dokter/store', 'Admin\Doctor::store');
    $routes->get('dokter/edit-dokter/(:segment)', 'Admin\Doctor::edit/$1');
    $routes->post('dokter/update/(:segment)', 'Admin\Doctor::update/$1');
    $routes->post('dokter/delete-dokter/(:segment)', 'Admin\Doctor::delete/$1');

    // Routes untuk Stase
    $routes->get('stase', 'Admin\Stase::index');
    $routes->get('stase/tambah-stase', 'Admin\Stase::create');
    $routes->post('stase/store', 'Admin\Stase::store');
    $routes->get('stase/edit-stase/(:segment)', 'Admin\Stase::edit/$1');
    $routes->post('stase/update/(:segment)', 'Admin\Stase::update/$1');
    $routes->post('stase/delete-stase/(:segment)', 'Admin\Stase::delete/$1');

    // Routes untuk Logbook
    $routes->get('logbook', 'Admin\Logbook::index');
    $routes->get('logbook/tambah-logbook', 'Admin\Logbook::create');
    $routes->post('logbook/store', 'Admin\Logbook::store');
    $routes->get('logbook/edit-logbook/(:segment)', 'Admin\Logbook::edit/$1');
    $routes->post('logbook/update/(:segment)', 'Admin\Logbook::update/$1');
    $routes->post('logbook/delete-logbook/(:segment)', 'Admin\Logbook::delete/$1');
    $routes->get('logbook/detail-logbook/(:segment)', 'Admin\Logbook::detail/$1');
});


// Dokter Routes (protected by filter)
$routes->group('dokter', ['filter' => 'auth:Dokter'], function ($routes) {
    $routes->get('dashboard', 'Dokter\Dashboard::index');
    // Other dokter routes...
});

// Mahasiswa Routes (protected by filter)
$routes->group('mahasiswa', ['filter' => 'auth:Mahasiswa Coass'], function ($routes) {
    $routes->get('dashboard', 'Mahasiswa::dashboard');
    // Other mahasiswa routes...
});
