<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
<<<<<<<<< Temporary merge branch 1
$routes->get('/db-test', 'Home::dbTest');
=========
$routes->get('/dashboard', 'front\DashboardController::index');
>>>>>>>>> Temporary merge branch 2
