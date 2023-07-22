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
 * 
 * A controller class that handles the translation of options value.
 * 
 */
class OptionsValueTranslateController extends AbstractTranslater
{

    /**
     * The constructor for the features value translate controller class.
     *
     * @param string $lang The language code for the translations.
     */
    public function __construct(string $lang)
    {
        $this->translator = new TranslationService("Google");

        // Call the parent constructor with the vendor name and the file name
        parent::__construct($lang, 'OptionsValue', $this->translator);
    }

    /**
     * Get the translation for the given feature and value.
     *
     * @param string $feature The feature key.
     * @param string $value The value key.
     * @return string The translated string or null if the key is not found.
     */
    public function get(string $feature): string
    {
        // Load the translations if not loaded yet
        if (count($this->Translations) <= 0) {
            $this->loadTranslations();
        }

        if (strstr($feature, ".") !== false) {
            list($feature, $value) = explode('.', $feature);
        } else {
            $value = $feature;
        }

        // Разделяем строку Color1.Black1 на две части по точке

        // Check if the feature exists
        if (!isset($this->Translations[$feature])) {
            // Create an empty array for the feature and save it to the file
            $this->Translations[$feature] = [];
            $this->saveTranslations();
        }

        // Check if the value exists
        if (!isset($this->Translations[$feature][$value])) {
            // Add the value to the feature array and save it to the file
            $this->Translations[$feature][$value] = $this->translator->translate($value, $this->language, "en");
            $this->saveTranslations();
        }

        // // Check if the value exists
        // if (!isset($this->Translations[$feature][$value])) {
        //     // Add the value to the feature array and save it to the file
        //     $this->Translations[$feature][$value] = $value;
        //     $this->saveTranslations();
        // }

        // Return the translation if found or null otherwise
        return $this->Translations[$feature][$value];
    }
}