<?php
/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @package    Devolegkosarev\Parsing\Controllers
 * @version    0.0.1
 * @since      0.0.1
 */

namespace Devolegkosarev\Parsing\Controllers;

use Devolegkosarev\Parsing\AbstractTranslater;
use Devolegkosarev\Parsing\Services\TranslationService;

/**
 * A controller class that handles the translation of categories.
 */
class CategoryTranslateController extends AbstractTranslater
{
    /**
     * The constructor for the category translate controller class.
     *
     * @param string $lang The language code for the translations.
     */
    public function __construct(string $lang)
    {
        // Assign a value to the translator property here
        $this->translator = new TranslationService("Google");

        // Call the parent constructor with the vendor name, language code and file name for the translations
        parent::__construct($lang, 'Category', $this->translator);
    }

    /**
     * Get the translation for the given key.
     *
     * @param string $key The translation key.
     *
     * @return string The translated string or the key itself if not found.
     */
    public function get(string $key): string
    {
        // Call the parent method to get the translation
        $translation = parent::get($key);
        return $translation;
    }

    /**
     * Add a new translation to the translations array and save it to the file.
     *
     * @param string $key   The translation key.
     * @param string $value The translation value.
     *
     * @return string The added translation value.
     */
    public function add(string $key, ?string $value = null): string
    {
        // Call the parent method to add the translation and return it as a string
        return parent::add($key, $value);
    }

    /**
     * Remove a translation from the translations array and save the changes to the file.
     *
     * @param string $key The translation key.
     *
     * @return bool True if the translation was successfully removed, false otherwise.
     */
    public function remove(string $key): bool
    {
        // Call the parent method to remove the translation and return the result as a bool
        return parent::remove($key);
    }

    /**
     * Update a translation in the translations array and save the changes to the file.
     *
     * @param string      $key   The translation key.
     * @param string|null $value The translation value. If null, use the key as the value.
     *
     * @return string The updated or added translation value.
     */
    public function update(string $key, ?string $value = null): string
    {
        // Call the parent method to update or add the translation and return it as a string
        return parent::update($key, $value);
    }
}