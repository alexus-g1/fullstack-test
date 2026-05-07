<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get("/", "Comments::index");
$routes->get("comments", "Comments::index");
$routes->get("comments/list", "Comments::list");
$routes->post("comments", "Comments::create");
$routes->post("comments/delete/(:num)", 'Comments::delete/$1');
