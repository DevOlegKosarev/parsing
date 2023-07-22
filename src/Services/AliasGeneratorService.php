<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package Devolegkosarev\Parsing\Services
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Services;

class AliasGeneratorService
{
    /**
     * Generate an alias from a given string
     *
     * @param string $string The input string
     * @return string The generated alias
     */
    public function generate(string $string): string
    {
        // Encode the string to UTF-8
        $string = utf8_encode($string);
        // Transliterate the string to ASCII
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        // Remove any non-alphanumeric or non-hyphen characters
        $string = preg_replace('/[^a-z0-9- ]/i', '', $string);
        // Replace spaces with hyphens
        $string = str_replace(' ', '-', $string);
        // Trim any leading or trailing hyphens
        $string = trim($string, '-');
        // Convert the string to lowercase
        $string = strtolower($string);
        // Return the alias or 'n-a' if empty
        return $string ?: 'n-a';
    }
}