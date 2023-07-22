<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package Devolegkosarev\Parsing\Config
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Config\Config;

$routes->match(['get', 'cli'], 'vendors/(:segment)', '\Devolegkosarev\Parsing\Controllers\VendorsController::parsing_products/$1');
$routes->match(['get', 'cli'], 'vendors/sync/(:segment)', '\Devolegkosarev\Parsing\Controllers\VendorsController::syncProducts/$1');