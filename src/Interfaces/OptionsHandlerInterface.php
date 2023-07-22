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
interface OptionsHandlerInterface
{
    /**
     * A public function that takes an array of option names and returns a string of options
     *
     * @param array $optionNames The array of option names to get
     * @return array The options string or an empty string if no options are found
     */
    public function getMultipleOptions(array $optionNames = [], string $lang = 'en'): array;
}