<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/db-test', 'Home::dbTest');
$routes->get('/dashboard', 'front\DashboardController::index');
$routes->get('/login', 'front\AuthController::login');
$routes->post('/login', 'front\AuthController::authenticate');
$routes->get('/logout', 'front\AuthController::logout');
$routes->get('/inscription', 'front\AuthController::inscriptionEtape1');
$routes->post('/inscription', 'front\AuthController::inscriptionEtape1Store');
$routes->get('/inscription/etape-2', 'front\AuthController::inscriptionEtape2');
$routes->post('/inscription/etape-2', 'front\AuthController::inscriptionEtape2Store');

$routes->post('/api/code-promo', 'back\AdminCodeController::apiCode');

$routes->get('/profil', 'front\ProfilController::index');
$routes->post('/profil/update', 'front\ProfilController::updateProfil');
$routes->post('/profil/mesure', 'front\ProfilController::addMesure');
$routes->get('/profil/objectifs', 'front\ProfilController::objectifs');
$routes->get('/porte-monnaie', 'front\PorteMonnaieController::index');

// $routes->group('', ['filter' => 'auth'], function($routes) {
//     $routes->get('dashboard', 'front\DashboardController::index');
// });


$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'back\AdminDashboardController::index');

    // Utilisateurs
    $routes->get('utilisateurs', 'back\AdminUtilisateurController::index');
    $routes->get('utilisateurs/(:num)', 'back\AdminUtilisateurController::show/$1');
    $routes->post('utilisateurs/delete/(:num)', 'back\AdminUtilisateurController::delete/$1');

    // Aliments
    $routes->get('aliments', 'back\AdminAlimentController::index');
    $routes->get('aliments/create', 'back\AdminAlimentController::create');
    $routes->post('aliments/store', 'back\AdminAlimentController::store');
    $routes->get('aliments/edit/(:num)', 'back\AdminAlimentController::edit/$1');
    $routes->post('aliments/update/(:num)', 'back\AdminAlimentController::update/$1');
    $routes->post('aliments/delete/(:num)', 'back\AdminAlimentController::delete/$1');

    // Régimes
    $routes->get('regimes', 'back\AdminRegimeController::index');
    $routes->get('regimes/create', 'back\AdminRegimeController::create');
    $routes->post('regimes/store', 'back\AdminRegimeController::store');
    $routes->get('regimes/edit/(:num)', 'back\AdminRegimeController::edit/$1');
    $routes->post('regimes/update/(:num)', 'back\AdminRegimeController::update/$1');
    $routes->post('regimes/delete/(:num)', 'back\AdminRegimeController::delete/$1');

    // Recettes
    $routes->get('recettes/(:num)', 'back\AdminRecetteController::index/$1');
    $routes->get('recettes/create/(:num)', 'back\AdminRecetteController::create/$1');
    $routes->post('recettes/store/(:num)', 'back\AdminRecetteController::store/$1');
    $routes->get('recettes/edit/(:num)/(:num)', 'back\AdminRecetteController::edit/$1/$2');
    $routes->post('recettes/update/(:num)/(:num)', 'back\AdminRecetteController::update/$1/$2');
    $routes->post('recettes/delete/(:num)/(:num)', 'back\AdminRecetteController::delete/$1/$2');

    // Sports
    $routes->get('sports', 'back\AdminSportController::index');
    $routes->get('sports/create', 'back\AdminSportController::create');
    $routes->post('sports/store', 'back\AdminSportController::store');
    $routes->get('sports/edit/(:num)', 'back\AdminSportController::edit/$1');
    $routes->post('sports/update/(:num)', 'back\AdminSportController::update/$1');
    $routes->post('sports/delete/(:num)', 'back\AdminSportController::delete/$1');

    // Codes Promo
    $routes->get('codes', 'back\AdminCodeController::index');
    $routes->get('codes/create', 'back\AdminCodeController::create');
    $routes->post('codes/store', 'back\AdminCodeController::store');
    $routes->post('codes/delete/(:num)', 'back\AdminCodeController::delete/$1');
});