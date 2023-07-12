<?php

namespace Devolegkosarev\Parsing\Config\Config;

$routes->match(['get', 'cli'], 'vendors/(:segment)', '\Devolegkosarev\Parsing\Controllers\VendorsController::parsing_products/$1');