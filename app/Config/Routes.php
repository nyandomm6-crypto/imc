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

$routes->get('/profil', 'front\ProfilController::index');
$routes->get('/profil/objectifs', 'front\ProfilController::objectifs');
