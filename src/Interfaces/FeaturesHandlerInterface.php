<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package Devolegkosarev\Parsing\Interfaces
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Interfaces;

/**
 * An interface that specifies a common interface for handling features.
 */
interface FeaturesHandlerInterface
{
    /**
     * A method that takes an array of features and returns an array of processed features.
     *
     * @param array $features The array of features to handle.
     * @return array The array of processed features.
     */
    public function handleFeatures(array $features): array;
}