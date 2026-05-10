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
$routes->get('/profil/objectifs/create', 'front\ProfilController::createObjectif');
$routes->post('/profil/objectifs/store', 'front\ProfilController::storeObjectif');
$routes->post('/profil/objectifs/achieve/(:num)', 'front\ProfilController::achieveObjectif/$1');
$routes->post('/profil/objectifs/abandon/(:num)', 'front\ProfilController::abandonObjectif/$1');
$routes->get('/porte-monnaie', 'front\PorteMonnaieController::index');

// Offres
$routes->get('/offres', 'front\OffreController::index');
$routes->get('/offres/suggestions', 'front\OffreController::suggestions');
$routes->post('/offres/demand/(:num)', 'front\OffreController::demand/$1');
$routes->get('/offres/detail/(:num)', 'front\OffreController::detailDemande/$1');
$routes->post('/offres/accept/(:num)', 'front\OffreController::accept/$1');
$routes->post('/offres/reject/(:num)', 'front\OffreController::reject/$1');
$routes->post('/offres/subscribe', 'front\OffreController::subscribe');

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

    // Offres
    $routes->get('offres', 'back\AdminOffreController::index');
    $routes->get('offres/create', 'back\AdminOffreController::create');
    $routes->post('offres/store', 'back\AdminOffreController::store');
    $routes->get('offres/edit/(:num)', 'back\AdminOffreController::edit/$1');
    $routes->post('offres/update/(:num)', 'back\AdminOffreController::update/$1');
    $routes->post('offres/delete/(:num)', 'back\AdminOffreController::delete/$1');

    // Demandes d'offres
    $routes->get('offres/demandes/all', 'back\AdminOffreController::demandes');
    $routes->get('offres/demandes/(:num)', 'back\AdminOffreController::detailDemande/$1');
    $routes->post('offres/demandes/accept/(:num)', 'back\AdminOffreController::acceptDemande/$1');
    $routes->post('offres/demandes/reject/(:num)', 'back\AdminOffreController::rejectDemande/$1');

    // Abonnements - Options
    $routes->get('abonnements/options', 'back\AdminAbonnementController::options');
    $routes->get('abonnements/options/create', 'back\AdminAbonnementController::createOption');
    $routes->post('abonnements/options/store', 'back\AdminAbonnementController::storeOption');
    $routes->get('abonnements/options/edit/(:num)', 'back\AdminAbonnementController::editOption/$1');
    $routes->post('abonnements/options/update/(:num)', 'back\AdminAbonnementController::updateOption/$1');
    $routes->post('abonnements/options/delete/(:num)', 'back\AdminAbonnementController::deleteOption/$1');

    // Abonnements - Utilisateurs
    $routes->get('abonnements/utilisateurs', 'back\AdminAbonnementController::utilisateurs');
    $routes->get('abonnements/utilisateurs/assign/(:num)', 'back\AdminAbonnementController::assignAbo/$1');
    $routes->post('abonnements/utilisateurs/store/(:num)', 'back\AdminAbonnementController::storeAbo/$1');
    $routes->post('abonnements/utilisateurs/cancel/(:num)', 'back\AdminAbonnementController::cancelAbo/$1');

    // Abonnements - Statistiques
    $routes->get('abonnements/stats', 'back\AdminAbonnementController::stats');
});