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

use Exception;
use Devolegkosarev\Parsing\AbstractTranslater;
use Devolegkosarev\Parsing\Services\TranslationService;

/**
 * 
 * A controller class for translating features and values.
 * 
 */
class FeaturesValueTranslateController extends AbstractTranslater
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
        parent::__construct($lang, 'FeaturesValue', $this->translator);
    }

    /**
     * Get the translation for the given feature and value.
     *
     * @param string $feature The feature key.
     * @return string The translated string or null if the key is not found.
     * @throws Exception If the feature format is invalid.
     */
    public function get(string $feature): string
    {
        // Load the translations if not loaded yet
        if (count($this->Translations) <= 0) {
            $this->loadTranslations();
        }

        // Check if the feature contains a dot
        if (strstr($feature, ".") !== false) {
            // Split the feature string by dot and assign values to variables
            list($feature, $value) = explode('.', $feature);
        } else {
            // Throw an exception or return null
            throw new Exception("Invalid feature format. Expected 'feature.value'");
            // return null;
        }

        // Check if the feature exists
        if (!isset($this->Translations[$feature])) {
            $this->add($feature, $value);
        }

        // Check if the value exists
        if (!isset($this->Translations[$feature][$value])) {
            $this->add($feature, $value);
        }

        // Return the translation if found or null otherwise
        return $this->Translations[$feature][$value];
    }


    /**
     * Add a new translation to the translations array and save it to the file.
     *
     * @param string $feature The feature key.
     * @param string|null $value The value key or null if not given.
     * @return string The translated value.
     */
    public function add(string $feature, ?string $value = null): string
    {
        if ($this->Translations === null) {
            $this->loadTranslations();
        }

        // Create an empty array for the feature and save it to the file
        if (!isset($this->Translations[$feature])) {
            $this->Translations[$feature] = [];
        }

        // Translate the value from English to the target language and save it to the file
        $this->Translations[$feature][$value] = $this->translator->translate($value, $this->language, "en");
        $this->saveTranslations();
        return $this->Translations[$feature][$value];
    }

    /**
     * Remove a translation from the translations array and save it to the file.
     *
     * @param string $key The translation key.
     * @return bool True if successful, false otherwise.
     */
    public function remove(string $key): bool
    {
        throw new Exception("WIP (work-in-progress) " . __METHOD__);
    }

    /**
     * Update a translation in the translations array and save it to the file.
     *
     * @param string $key The translation key.
     * @param string|null $value The new translation value or null if not given.
     * @return void
     */
    public function update(string $key, ?string $value = null): string
    {
        throw new Exception("WIP (work-in-progress) " . __METHOD__);
    }
}