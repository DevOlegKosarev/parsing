<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    Devolegkosarev\Parsing\Interfaces
 * @author     Oleg Kosarev <dev.oleg.kosarev@outlook.com>
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Interfaces;

/**
 *
 * This interface defines the contract for interacting with a translation service
 */
interface TranslatorInterface
{
    /**
     * Get the translation for the given key.
     *
     * @param string $key      The translation key.
     * @param string $toLang   The target language.
     * @param string $fromLang The source language. Default: 'en'
     * @return string The translated string.
     */
    public function translate(string $key, string $toLang, string $fromLang = 'en'): string;
}